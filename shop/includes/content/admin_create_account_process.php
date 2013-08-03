<?php
/* ----------------------------------------------------------------------
   $Id: admin_create_account_process.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_admin_process.php
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
   P&G Shipping Module Version 0.1 12/03/2002
   osCommerce Shipping Management Module
   Copyright (c) 2002  - Oliver Baelde
   http://www.francecontacts.com
   dev@francecontacts.com
   - eCommerce Solutions development and integration -

   osCommerce, Open Source E-Commerce Solutions
   Copyright (c) 2002 osCommerce
   http://www.oscommerce.com

   IMPORTANT NOTE:
   This script is not part of the official osCommerce distribution
   but an add-on contributed to the osCommerce community. Please
   read the README and  INSTALL documents that are provided
   with this file for further information and installation notes.

   LICENSE:
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

   All contributions are gladly accepted though Paypal.
   ---------------------------------------------------------------------- */

   /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/admin_create_account_process.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';

  if (!isset($_POST['action'])) {
    oos_redirect(oos_href_link($aContents['main']));
  }

  $manual_infotable = $oostable['manual_info'];
  $sql = "SELECT man_name, defined
          FROM $manual_infotable
          WHERE man_key = '" . oos_db_input($keya) . "'
            AND man_key2 = '" . oos_db_input($keyb) . "'
            AND status = '1'";
  $login_result = $dbconn->Execute($sql);
  if (!$login_result->RecordCount()) {
    $manual_infotable = $oostable['manual_info'];
    $dbconn->Execute("UPDATE " . $manual_infotable . "
                  SET man_key = '',
                      man_key2 = ''
                  WHERE man_info_id = '1'");
    oos_redirect(oos_href_link($aContents['main']));
  }


  $error = false; // reset error flag

  if (ACCOUNT_GENDER == 'true') {
    if (($gender == 'm') || ($gender == 'f')) {
      $gender_error = false;
    } else {
      $error = true;
      $gender_error = true;
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;
    $firstname_error = true;
  } else {
    $firstname_error = false;
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;
    $lastname_error = true;
  } else {
    $lastname_error = false;
  }

  if (ACCOUNT_DOB == 'true') {
    if (checkdate(substr(oos_date_raw($dob), 4, 2), substr(oos_date_raw($dob), 6, 2), substr(oos_date_raw($dob), 0, 4))) {
      $date_of_birth_error = false;
    } else {
      $error = true;
      $date_of_birth_error = true;
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;
    $email_address_error = true;
  } else {
    $email_address_error = false;
  }

  if (!oos_validate_is_email($email_address)) {
    $error = true;
    $email_address_check_error = true;
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
    $street_address_error = true;
  } else {
    $street_address_error = false;
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;
    $post_code_error = true;
  } else {
    $post_code_error = false;
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;
    $city_error = true;
  } else {
    $city_error = false;
  }

  if (!$country) {
    $error = true;
    $country_error = true;
  } else {
    $country_error = false;
  }

  if (ACCOUNT_STATE == 'true') {
    if ($country_error == true) {
      $state_error = true;
    } else {
      $zone_id = 0;
      $state_error = false;
      $zonestable = $oostable['zones'];
      $sql = "SELECT COUNT(*) as total
              FROM $zonestable
              WHERE zone_country_id = '" . oos_db_input($country) . "'";
      $check_result = $dbconn->Execute($sql);
      $check_value = $check_result->fields;
      $state_has_zones = ($check_value['total'] > 0);
      if ($state_has_zones == true) {
        $zonestable = $oostable['zones'];
        $sql = "SELECT zone_id
                FROM $zonestable
                WHERE zone_country_id = '" . oos_db_input($country) . "'
                  AND zone_name = '" . oos_db_input($state) . "'";
        $zone_result = $dbconn->Execute($sql);
        if ($zone_result->RecordCount() == 1) {
          $zone_values = $zone_result->fields;
          $zone_id = $zone_values['zone_id'];
        } else {
          $zonestable = $oostable['zones'];
          $sql = "SELECT zone_id
                  FROM $zonestable
                  WHERE zone_country_id = '" . oos_db_input($country) . "'
                    AND zone_code = '" . oos_db_input($state) . "'";
          $zone_result = $dbconn->Execute($sql);
          if ($zone_result->RecordCount() == 1) {
            $zone_values = $zone_result->fields;
            $zone_id = $zone_values['zone_id'];
          } else {
            $error = true;
            $state_error = true;
          }
        }
      } else {
        if ($state == false) {
          $error = true;
          $state_error = true;
        }
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;
    $telephone_error = true;
  } else {
    $telephone_error = false;
  }

  $customerstable = $oostable['customers'];
  $sql = "SELECT customers_email_address
          FROM $customerstable
          WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
  $check_email = $dbconn->Execute($sql);
  if ($check_email->RecordCount()) {
    $error = true;
    $email_address_exists = true;
  } else {
    $email_address_exists = false;
  }

  if ($error == true) {
    $processed = true;

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['admin_create_account']));
    $oBreadcrumb->add($aLang['navbar_title_2']);

    ob_start();
    require 'js/form_check.js.php';
    $javascript = ob_get_contents();
    ob_end_clean();

    $aTemplate['page'] = $sTheme . '/modules/create_account_admin_process.tpl';

    $nPageType = OOS_PAGE_TYPE_SERVICE;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

// assign Smarty variables;
  $smarty->assign(
      array('oos_breadcrumb'      => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title'   => $aLang['heading_title'],
            'oos_heading_image'   => 'account.gif',

            'oos_js'              => $javascript,

            'error'               => $error,
            'gender_error'        => $gender_error,
            'firstname_error'     => $firstname_error,
            'lastname_error'      => $lastname_error,
            'date_of_birth_error' => $date_of_birth_error,
            'email_address_error' => $email_address_error,
            'email_address_check_error' => $email_address_check_error,
            'email_address_exists' => $email_address_exists,
            'vatid_check_error'    => $vatid_check_error,
            'street_address_error' => $street_address_error,
            'post_code_error'      => $post_code_error,
            'city_error'           => $city_error,
            'country_error'        => $country_error,
            'state_error'          => $state_error,
            'state_has_zones'      => $state_has_zones,
            'telephone_error'      => $telephone_error,
            'password_error'       => $password_error,

            'gender'               => $gender,
            'firstname'            => $firstname,
            'lastname'             => $lastname,
            'dob'                  => $dob,
            'number'               => $number,
            'email_address'        => $email_address,
            'company'              => $company,
            'owner'                => $owner,
            'vat_id'               => $vat_id,
            'street_address'       => $street_address,
            'suburb'               => $suburb,
            'postcode'             => $postcode,
            'city'                 => $city,
            'country'              => $country,
            'telephone'            => $telephone,
            'fax'                  => $fax,
            'newsletter'           => $newsletter,
            'password'             => $password,
            'confirmation'         => $confirmation,

            'email_address'        => $email_address,
            'show_password'        => $show_password,

            'verif_key'            => $keya,
            'newkey2'              => $keyb
        )
    );

    if ($state_has_zones == 'true') {
      $zones_names = array();
      $zones_values = array();
      $zonestable = $oostable['zones'];
      $zones_result = $dbconn->Execute("SELECT zone_name FROM $zonestable WHERE zone_country_id = '" . oos_db_input($country) . "' ORDER BY zone_name");
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
      $news = ENTRY_NEWSLETTER_YES;
    } else {
      $news = ENTRY_NEWSLETTER_NO;
    }
    $smarty->assign('news', $news);

    $smarty->assign('newsletter_ids', array(0,1));
    $smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

	// display the template
	$smarty->display($aTemplate['page']);
  } else {
    $customer_max_order = DEFAULT_MAX_ORDER;
    $customers_status = DEFAULT_CUSTOMERS_STATUS_ID;

    $time = mktime();
    $wishlist_link_id = '';
    for ($x=3;$x<10;$x++) {
      $wishlist_link_id .= substr($time,$x,1) . oos_create_random_value(1, $type = 'chars');
    }
    $sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_fax' => $fax,
                            'customers_newsletter' => $newsletter,
                            'customers_status' => $customers_status,
                            'customers_login' => 1,
                            'customers_max_order' => $customer_max_order,
                            'customers_password' => oos_encrypt_password($password),
                            'customers_wishlist_link_id' => $wishlist_link_id,
                            'customers_default_address_id' => 1);

    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    if (ACCOUNT_NUMBER == 'true') $sql_data_array['customers_number'] = $number;
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = oos_date_raw($dob);
    if (ACCOUNT_VAT_ID == 'true') {
      $sql_data_array['customers_vat_id'] = $vat_id;
      if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === false)) {
        $sql_data_array['customers_vat_id_status'] = 1;
      } else {
        $sql_data_array['customers_vat_id_status'] = 0;
      }
    }

    oos_db_perform($oostable['customers'], $sql_data_array);

    $customer_id = $dbconn->Insert_ID();

    $sql_data_array = array('customers_id' => $customer_id,
                            'address_book_id' => 1,
                            'entry_firstname' => $firstname,
                            'entry_lastname' => $lastname,
                            'entry_street_address' => $street_address,
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

    oos_db_perform($oostable['address_book'], $sql_data_array);

    $customers_infotable = $oostable['customers_info'];
    $dbconn->Execute("INSERT INTO " . $customers_infotable . "
                (customers_info_id,
                 customers_info_number_of_logons,
                 customers_info_date_account_created) VALUES ('" . intval($customer_id) . "',
                                                              '0',
                                                              now())");

    $_SESSION['customer_id'] = $customer_id;
    $_SESSION['customer_wishlist_link_id'] = $wishlist_link_id;
    $_SESSION['customer_first_name'] = $firstname;
    $_SESSION['customer_default_address_id'] = 1;
    $_SESSION['customer_country_id'] = $country;
    $_SESSION['customer_zone_id'] = $zone_id;
    $_SESSION['customer_max_order'] = $customer_max_order;
    $_SESSION['man_key'] = $keya;

    if (ACCOUNT_VAT_ID == 'true') {
      if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === false)) {
        $_SESSION['customers_vat_id_status'] = 1;
      } else {
        $_SESSION['customers_vat_id_status'] = 0;
      }
    }

// restore cart contents
    $_SESSION['cart']->restore_contents();

    oos_redirect(oos_href_link($aContents['create_account_success'], '', 'SSL'));
  }

