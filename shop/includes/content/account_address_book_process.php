<?php
/* ----------------------------------------------------------------------
   $Id: account_address_book_process.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book_process.php,v 1.73 2003/02/13 01:58:23 hpdl
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
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if ($_SESSION['navigation']->snapshot['file'] != $aFilename['account_address_book']) {
    $_SESSION['navigation']->set_path_as_snapshot(1);
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_address_book_process.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'remove') && oos_is_not_null($_GET['entry_id']) ) {
    $entry_id = oos_db_prepare_input($_GET['entry_id']);

    $address_booktable = $oostable['address_book'];
    $query = "DELETE FROM $address_booktable
              WHERE address_book_id = '" . oos_db_input($entry_id) . "' AND
                    customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $dbconn->Execute($query);

    $address_booktable = $oostable['address_book'];
    $query = "UPDATE $address_booktable
                 SET address_book_id = address_book_id - 1
               WHERE address_book_id > " . oos_db_input($entry_id)  . " AND
                     customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $dbconn->Execute($query);

    oos_redirect(oos_href_link($aContents['account_address_book'], '', 'SSL'));
  }

// Post-entry error checking when updating or adding an entry
  $process = 'false';
  if (isset($_POST['action']) && (($_POST['action'] == 'process') || ($_POST['action'] == 'update'))) {
    $process = 'true';
    $error = 'false';

    if (ACCOUNT_GENDER == 'true') {
      if (($gender == 'm') || ($gender == 'f')) {
        $gender_error = 'false';
      } else {
        $gender_error = 'true';
        $error = 'true';
      }
    }

    if (ACCOUNT_COMPANY == 'true') {
      if (strlen($company) < ENTRY_COMPANY_MIN_LENGTH) {
        $company_error = 'true';
        $error = 'true';
      } else {
        $company_error = 'false';
      }
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $firstname_error = 'true';
      $error = 'true';
    } else {
      $firstname_error = 'false';
    }

    if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $lastname_error = 'true';
      $error = 'true';
    } else {
      $lasttname_error = 'false';
    }

    if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $street_address_error = 'true';
      $error = 'true';
    } else {
      $street_address_error = 'false';
    }

    if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $postcode_error = 'true';
      $error = 'true';
    } else {
      $postcode_error = 'false';
    }

    if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
      $city_error = 'true';
      $error = 'true';
    } else {
      $city_error = 'false';
    }

    if (!$country) {
      $country_error = 'true';
      $error = 'true';
    } else {
      $country_error = 'false';
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
                              WHERE zone_country_id = '" . oos_db_input($country) . "'";
        $country_check = $dbconn->Execute($country_check_sql);
        if ($entry_state_has_zones = ($country_check->fields['total'] > 0)) {
          $state_has_zones = 'true';

          $zonestable = $oostable['zones'];
          $match_zone_sql = "SELECT zone_id
                             FROM $zonestable
                             WHERE zone_country_id = '" . oos_db_input($country) . "'
                               AND zone_name = '" . oos_db_input($state) . "'";
          $match_zone_result = $dbconn->Execute($match_zone_sql);
          if ($match_zone_result->RecordCount() == 1) {
            $match_zone = $match_zone_result->fields;
            $zone_id = $match_zone['zone_id'];
          } else {
            $zonestable = $oostable['zones'];
            $match_zone_sql2 = "SELECT zone_id
                                FROM $zonestable
                                WHERE zone_country_id = '" . oos_db_input($country) . "'
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

    if ($error == 'false') {
      $sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

      if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
      if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
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

      if ($_POST['action'] == 'update') {
        oos_db_perform($oostable['address_book'], $sql_data_array, 'update', "address_book_id = '" . oos_db_input($entry_id) . "' AND customers_id ='" . intval($_SESSION['customer_id']) . "'");
      } else {
        $sql_data_array['customers_id'] = $_SESSION['customer_id'];
        $sql_data_array['address_book_id'] = $entry_id;
        oos_db_perform($oostable['address_book'], $sql_data_array);

        if (count($_SESSION['navigation']->snapshot) > 0) {
          $origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
          $_SESSION['navigation']->clear_snapshot();
          oos_redirect($origin_href);
        }
      }

      oos_redirect(oos_href_link($aContents['account_address_book'], '', 'SSL'));
    }
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'modify') && oos_is_not_null($_GET['entry_id'])) {
    $address_booktable = $oostable['address_book'];
    $sql = "SELECT entry_gender, entry_company, entry_firstname, entry_lastname,
                   entry_street_address, entry_suburb, entry_postcode, entry_city,
                   entry_state, entry_zone_id, entry_country_id
            FROM $address_booktable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND address_book_id = '" . intval($_GET['entry_id']) . "'";
    $entry = $dbconn->GetRow($sql);
  } else {
    $entry = array('entry_country_id' => STORE_COUNTRY);
  }
  if (!isset($process)) {
    $process = 'false';
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_address_book'], '', 'SSL'));

  if ( (isset($_GET['action']) && ($_GET['action'] == 'modify')) || (isset($_POST['action']) && ($_POST['action'] == 'update') && oos_is_not_null($_POST['entry_id'])) ) {
    $oBreadcrumb->add($aLang['navbar_title_modify_entry'], oos_href_link($aContents['account_address_book_process'], 'action=modify&amp;entry_id=' . ((isset($_GET['entry_id'])) ? $_GET['entry_id'] : $_POST['entry_id']), 'SSL'));
  } else {
    $oBreadcrumb->add($aLang['navbar_title_add_entry'], oos_href_link($aContents['account_address_book_process'], '', 'SSL'));
  }

  if (count($_SESSION['navigation']->snapshot) > 0) {
    $back_link = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
  } else {
    $back_link = oos_href_link($aContents['account_address_book'], '', 'SSL');
  }

  if (isset($_GET['entry_id'])) {
    $entry_id = oos_var_prep_for_os($_GET['entry_id']);
  }

  ob_start();
  require 'js/address_book_process.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/modules/address_book_process.tpl';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

// assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),

          'back_link'      => $back_link,
          'entry_id'       => $entry_id,
          'process'        => $process,

          'oos_js'         => $javascript
      )
  );

  if (isset($_GET['action']) && $_GET['action'] == 'modify') {
    $smarty->assign(
        array(
            'oos_heading_title' => $aLang['heading_title_modify_entry'],
            'oos_heading_image' => 'address_book.gif'
        )
    );
  } else {
    $smarty->assign(
        array(
            'oos_heading_title' => $aLang['heading_title_add_entry'],
            'oos_heading_image' => 'address_book.gif'
        )
    );
  }

  $smarty->assign(
      array(
          'gender'         => $gender,
          'firstname'      => $firstname,
          'lastname'       => $lastname,
          'company'        => $company,
          'street_address' => $street_address,
          'suburb'         => $suburb,
          'postcode'       => $postcode,
          'city'           => $city,
          'country'        => $country
      )
  );

  $smarty->assign(
      array(
          'error'                => $error,
          'gender_error'         => $gender_error,
          'firstname_error'      => $firstname_error,
          'lastname_error'       => $lastname_error,
          'street_address_error' => $street_address_error,
          'post_code_error'      => $post_code_error,
          'city_error'           => $city_error,
          'country_error'        => $country_error,
          'state_error'          => $state_error,
          'state_has_zones'      => $state_has_zones
      )
  );

  if ($state_has_zones == 'true') {
     $aZonesNames = array();
     $aZonesValues = array();
     $zonestable = $oostable['zones'];
     $zones_query = "SELECT zone_name FROM $zonestable
                     WHERE zone_country_id = '" . oos_db_input($country) . "'
                     ORDER BY zone_name";
     $zones_result = $dbconn->Execute($zones_query);
     while ($zones = $zones_result->fields) {
       $aZonesNames[] =  $zones['zone_name'];
       $aZonesValues[] = $zones['zone_name'];
       $zones_result->MoveNext();
     }
     $smarty->assign('zones_names', $aZonesNames);
     $smarty->assign('zones_values', $aZonesValues);
   } else {
     $state = oos_get_zone_name($country, $zone_id, $state);
     $smarty->assign('state', $state);
     $smarty->assign('zone_id', $zone_id);
   }
   $country_name = oos_get_country_name($country);
   $smarty->assign('country_name', $country_name);

   $smarty->assign('entry', $entry);


// display the template
$smarty->display($aTemplate['page']);
