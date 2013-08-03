<?php
/* ----------------------------------------------------------------------
   $Id: account_edit_process.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_edit_process.php,v 1.75 2003/02/13 01:58:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot(array('mode' => 'SSL', 'content' => $aContents['account_edit']));
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if (!isset($_POST['action']) || ($_POST['action'] != 'process')) {
    oos_redirect(oos_href_link($aContents['account_edit'], '', 'SSL'));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_account_edit_process.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';

  $firstname = oos_db_prepare_input($_POST['firstname']);
  $lastname = oos_db_prepare_input($_POST['lastname']);

  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if ( ($gender == 'm') || ($gender == 'f') ) {
      $gender_error = false;
    } else {
      $error = true;
      $gender_error = 'true';
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $firstname_error = 'true';
  } else {
    $firstname_error = false;
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $lastname_error = 'true';
  } else {
    $lastname_error = false;
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(oos_date_raw($dob), 4, 2), substr(oos_date_raw($dob), 6, 2), substr(oos_date_raw($dob), 0, 4))) {
      $date_of_birth_error = false;
    } else {
      $error = true;
      $date_of_birth_error = 'true';
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $email_address_error = 'true';
  } else {
    $email_address_error = false;
  }

  if (!oos_validate_is_email($email_address)) {
    $error = true;
    $email_address_check_error = 'true';
  } else {
    $email_address_check_error = false;
  }

  if ((ACCOUNT_VAT_ID == 'true') && (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && oos_is_not_null($vat_id)) {
    if (!oos_validate_is_vatid($vat_id)) {
      $error = true;
      $vatid_check_error = 'true';
    } else {
      $vatid_check_error = false;
    }
  }


  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;
    $street_address_error = 'true';
  } else {
    $street_address_error = false;
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $post_code_error = 'true';
  } else {
    $post_code_error = false;
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $city_error = 'true';
  } else {
    $city_error = false;
  }

  if (!is_numeric($country)) {
    $error = true;
    $country_error = 'true';
  } else {
    $country_error = false;
  }

  if (ACCOUNT_STATE == 'true') {
    if ($entry_country_error) {
      $state_error = 'true';
    } else {
      $zone_id = 0;
      $state_error = 'false';

      $zonestable = $oostable['zones'];
      $country_check_sql = "SELECT COUNT(*) AS total
                            FROM $zonestable
                            WHERE zone_country_id = '" . intval($country) . "'";
      $country_check = $dbconn->Execute($country_check_sql);

      $entry_state_has_zones = ($country_check->fields['total'] > 0);

      if ($entry_state_has_zones === true) {
        $state_has_zones = 'true';

        $zonestable = $oostable['zones'];
        $match_zone_sql = "SELECT zone_id
                           FROM $zonestable
                           WHERE zone_country_id = '" . intval($country) . "'
                             AND zone_name = '" . oos_db_input($state) . "'";
        $match_zone_result = $dbconn->Execute($match_zone_sql);

        if ($match_zone_result->RecordCount() == 1) {
          $match_zone = $match_zone_result->fields;
          $zone_id = $match_zone['zone_id'];
        } else {
          $zonestable = $oostable['zones'];
          $match_zone_sql2 = "SELECT zone_id
                              FROM $zonestable
                              WHERE zone_country_id = '" . intval($country) . "'
                                AND zone_code = '" . oos_db_input($state) . "'";
          $match_zone_result = $dbconn->Execute($match_zone_sql2);
          if ($match_zone_result->RecordCount() == 1) {
            $match_zone = $match_zone_result->fields;
            $zone_id = $match_zone['zone_id'];
          } else {
            $error = 'true';
            $state_error = 'true';
          }
        }
      } elseif (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = 'true';
        $state_error = 'true';
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $telephone_error = 'true';
  } else {
    $telephone_error = false;
  }

  if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;
    $password_error = 'true';
  } else {
    $password_error = false;
  }

  if ($password != $confirmation) {
    $error = true;
    $password_error = 'true';
  }

  $customerstable = $oostable['customers'];
  $check_email_sql = "SELECT COUNT(*) AS total
                      FROM $customerstable
                      WHERE customers_email_address = '" . oos_db_input($email_address) . "'
                        AND customers_id != '" . intval($_SESSION['customer_id']) . "'";
  $check_email = $dbconn->Execute($check_email_sql);
  if ($check_email->fields['total'] > 0) {
    $error = true;
    $email_address_exists = 'true';
  } else {
    $email_address_exists = false;
  }

  if ($error == true) {

    $processed = true;
    $no_edit = true;
    $show_password = 'true';

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
    $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_edit'], '', 'SSL'));

    ob_start();
    require 'js/form_check.js.php';
    $javascript = ob_get_contents();
    ob_end_clean();

    $aTemplate['page'] = $sTheme . '/modules/user_account_edit_process.tpl';

    $nPageType = OOS_PAGE_TYPE_ACCOUNT;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    $smarty->assign('oos_js', $javascript);
    $smarty->assign(array('error' => $error,
                          'gender_error' => $gender_error,
                          'firstname_error' => $firstname_error,
                          'lastname_error' => $lastname_error,
                          'date_of_birth_error' => $date_of_birth_error,
                          'email_address_error' => $email_address_error,
                          'email_address_check_error' => $email_address_check_error,
                          'email_address_exists' => $email_address_exists,
                          'vatid_check_error' => $vatid_check_error,
                          'street_address_error' => $street_address_error,
                          'post_code_error' => $post_code_error,
                          'city_error' => $city_error,
                          'country_error' => $country_error,
                          'state_error' => $state_error,
                          'state_has_zones' => $state_has_zones,
                          'telephone_error' => $telephone_error,
                          'password_error' => $password_error));
    $smarty->assign(array('gender' => $gender,
                          'firstname' => $firstname,
                          'lastname' => $lastname,
                          'dob' => $dob,
                          'number' => $number,
                          'email_address' => $email_address,
                          'company' => $company,
                          'owner' => $owner,
                          'vat_id' => $vat_id,
                          'street_address' => $street_address,
                          'suburb' => $suburb,
                          'postcode' => $postcode,
                          'city' => $city,
                          'country' => $country,
                          'telephone' => $telephone,
                          'fax' => $fax,
                          'newsletter' => $newsletter,
                          'password' => $password,
                          'confirmation' => $confirmation));

    if ($state_has_zones == 'true') {
      $zones_names = array();
      $zones_values = array();

      $zonestable = $oostable['zones'];
      $zones_query = "SELECT zone_name FROM $zonestable WHERE zone_country_id = '" . oos_db_input($country) . "' ORDER BY zone_name";
      $zones_result = $dbconn->Execute($zones_query);
      while ($zones = $zones_result->fields) {
        $zones_names[] =  $zones['zone_name'];
        $zones_values[] = $zones['zone_name'];
        $zones_result->MoveNext();
      }
      $smarty->assign('zones_names', $zones_names);
      $smarty->assign('zones_values', $zones_values);
    } else {
      $state = oos_get_zone_name($country, $zone_id, $state);
      $smarty->assign('state', $state);
      $smarty->assign('zone_id', $zone_id);
    }
    $country_name = oos_get_country_name($country);
    $smarty->assign('country_name', $country_name);
    if ($newsletter == '1') {
      $news = $aLang['entry_newsletter_yes'];
    } else {
      $news = $aLang['entry_newsletter_no'];
    }
    $smarty->assign('news', $news);

    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'account.gif',

            'email_address'     => $email_address,
            'show_password'     => $show_password

        )
    );

    $smarty->assign('newsletter_ids', array(0,1));
    $smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

	// display the template
	$smarty->display($aTemplate['page']);

	} else {
    $new_encrypted_password = oos_encrypt_password($password);
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax,
                            'customers_newsletter' => $newsletter,
                            'customers_password' => $new_encrypted_password);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = oos_date_raw($dob);
    if (ACCOUNT_VAT_ID == 'true') {
      $sql_data_array['customers_vat_id'] = $vat_id;
      if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === false) && ($country != STORE_COUNTRY)) {
        $sql_data_array['customers_vat_id_status'] = 1;
      } else {
        $sql_data_array['customers_vat_id_status'] = 0;
      }
    }

    oos_db_perform($oostable['customers'], $sql_data_array, 'update', "customers_id = '" . intval($_SESSION['customer_id']) . "'");

    if (oos_is_not_null($_COOKIE['password'])) {
      $cookie_url_array = parse_url((ENABLE_SSL == true ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . substr(OOS_SHOP, 0, -1));
      $cookie_path = $cookie_url_array['path'];
      setcookie('email_address', $email_address, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
      setcookie('password', $new_encrypted_password, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
    }

    $sql_data_array = array('entry_street_address' => $street_address,
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_postcode' => $postcode,
                            'entry_city' => $city,
                            'entry_country_id' => $country);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_OWNER == 'true') $sql_data_array['entry_owner'] = $owner;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;

    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }

    oos_db_perform($oostable['address_book'], $sql_data_array, 'update', "customers_id = '" . intval($_SESSION['customer_id']) . "' AND address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'");

    $update_info_sql = "UPDATE " . $oostable['customers_info'] . "
                        SET customers_info_date_account_last_modified = now()
                        WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
    $dbconn->Execute($update_info_sql);

    //session
    $_SESSION['customer_country_id'] = $country;
    $_SESSION['customer_zone_id'] = $zone_id;

    if (ACCOUNT_VAT_ID == 'true') {
      if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === false)) {
        $_SESSION['customers_vat_id_status'] = 1;
      } else {
        $_SESSION['customers_vat_id_status'] = 0;
      }
    }


    if (SEND_CUSTOMER_EDIT_EMAILS == 'true') {
      $email_owner = $aLang['owner_email_subject'] . "\n" .
                     $aLang['email_separator'] . "\n" .
                     $aLang['owner_email_date'] . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n" .
                     $aLang['email_separator'] . "\n";
      if (ACCOUNT_NUMBER == 'true') {
        $email_owner .= $aLang['owner_email_number'] . ' ' . $number . "\n" .
                        $aLang['email_separator'] . "\n\n";
      }
      if (ACCOUNT_COMPANY == 'true') {
        $email_owner .= $aLang['owner_email_company_info'] . "\n" .
                        $aLang['owner_email_company'] . ' ' . $company . "\n";
        if (ACCOUNT_OWNER == 'true') {
          $email_owner .= $aLang['owner_email_owner'] . ' ' . $owner . "\n";
        }
        if (ACCOUNT_VAT_ID == 'true') {
          $email_owner .= $aLang['entry_vat_id'] . ' ' . $vat_id . "\n";
        }
      }
      if (ACCOUNT_GENDER == 'true') {
        if ($gender == 'm') {
          $email_owner .= $aLang['entry_gender'] . ' ' . $aLang['male'] . "\n";
        } else {
          $email_owner .= $aLang['entry_gender'] . ' ' . $aLang['female'] . "\n";
        }
      }

      $email_owner .= $aLang['owner_email_first_name'] . ' ' . $firstname . "\n" .
                      $aLang['owner_email_last_name'] . ' ' . $lastname . "\n\n" .
                      $aLang['owner_email_street'] . ' ' . $street_address . "\n" .
                      $aLang['owner_email_post_code'] . ' ' . $postcode . "\n" .
                      $aLang['owner_email_city'] . ' ' . $city . "\n" .
                      $aLang['email_separator'] . "\n\n" .
                      $aLang['owner_email_contact'] . "\n" .
                      $aLang['owner_email_telephone_number'] . ' ' . $telephone . "\n" .
                      $aLang['owner_email_fax_number'] . ' ' . $fax . "\n" .
                      $aLang['owner_email_address'] . ' ' . $email_address . "\n" .
                      $aLang['email_separator'] . "\n\n" .
                      $aLang['owner_email_options'] . "\n";
      if ($newsletter == '1') {
        $email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_yes'] . "\n";
      } else {
        $email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_no'] . "\n";
      }
      oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['owner_email_subject'], nl2br($email_owner), $name, $email_address);
    }

    oos_redirect(oos_href_link($aContents['account'], '', 'SSL'));
  }
