<?php
/* ----------------------------------------------------------------------
   $Id: checkout_shipping_address.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_shipping_address.php,v 1.8 2003/02/13 04:23:22 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_shipping_address.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

// if there is nothing in the customers cart, redirect them to the shopping cart page
  if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['main_shopping_cart']));
  }

// if the order contains only virtual products, forward the customer to the billing page as
// a shipping address is not needed
  if ($oOrder->content_type == 'virtual') {
    $_SESSION['shipping'] = false;
    $_SESSION['sendto'] = false;
    oos_redirect(oos_href_link($aContents['checkout_payment'], '', 'SSL'));
  }

  $error = false;
  $process = 'false';
  if (isset($_POST['action']) && ($_POST['action'] == 'submit')) {
// process a new shipping address
    if (oos_is_not_null($_POST['firstname']) && oos_is_not_null($_POST['lastname']) && oos_is_not_null($_POST['street_address'])) {
      $process = 'true';

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
        $lastname_error = 'false';
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

      if (strlen($country) < 1) {
        $country_error = 'true';
        $error = 'true';
      } else {
        $country_error = 'false';
      }

      if (ACCOUNT_STATE == 'true') {
        if ($country_error == 'true') {
          $state_error = 'true';
        } else {
          $zone_id = 0;
          $state_error = 'false';
          $zonestable = $oostable['zones'];
          $sql = "SELECT COUNT(*) as total
                  FROM $zonestable
                  WHERE zone_country_id = '" . oos_db_input($country) . "'";
          $check_result = $dbconn->Execute($sql);
          $check_value = $check_result->fields;
          $state_has_zones = 'false';
          if ($check_value['total'] > 0) {
            $state_has_zones = 'true';
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
              $error = 'true';
              $state_error = 'true';
            }
          } else {
            if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
              $error = 'true';
              $state_error = 'true';
            }
          }
        }
      }

      if ($error == false) {
        $address_booktable = $oostable['address_book'];
        $sql = "SELECT max(address_book_id) AS address_book_id
                FROM $address_booktable
                WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
        $next_id_result = $dbconn->Execute($sql);
        if ($next_id_result->RecordCount()) {
          $next_id = $next_id_result->fields;
          $entry_id = $next_id['address_book_id']+1;
        } else {
          $entry_id = 1;
        }

        $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                                'address_book_id' => $entry_id,
                                'entry_firstname' => $firstname,
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
        oos_db_perform($oostable['address_book'], $sql_data_array);

        $_SESSION['sendto'] = $entry_id;

        if (isset($_SESSION['shipping'])) unset($_SESSION['shipping']);

        oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
      }
// process the selected shipping destination
    } elseif (isset($_POST['address'])) {
      $reset_shipping = false;
      if (isset($_SESSION['sendto'])) {
        if ($_SESSION['sendto'] != $_POST['address']) {
          if (isset($_SESSION['shipping'])) {
            $reset_shipping = true;
          }
        }
      }
      $_SESSION['sendto'] = $_POST['address'];

      $address_booktable = $oostable['address_book'];
      $sql = "SELECT COUNT(*) AS total
              FROM $address_booktable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
                AND address_book_id = '" . intval($_SESSION['sendto']) . "'";
      $check_address_result = $dbconn->Execute($sql);
      $check_address = $check_address_result->fields;

      if ($check_address['total'] == '1') {
        if ($reset_shipping == true) unset($_SESSION['shipping']);
        oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
      } else {
        unset($_SESSION['sendto']);
      }
    } else {
      $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];

      oos_redirect(oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
    }
  }

  if ($process == 'false') {
    $address_booktable = $oostable['address_book'];
    $sql = "SELECT COUNT(*) AS total
            FROM $address_booktable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND address_book_id != '" . intval($_SESSION['sendto']) . "'";
    $addresses_count_result = $dbconn->Execute($sql);
    $addresses_count = $addresses_count_result->fields['total'];

    if ($addresses_count > 0) {
      $radio_buttons = 0;
      $address_booktable = $oostable['address_book'];
      $sql = "SELECT address_book_id, entry_firstname AS firstname, entry_lastname AS lastname,
                     entry_company AS company, entry_street_address AS street_address,
                     entry_suburb AS suburb, entry_city AS city, entry_postcode AS postcode,
                     entry_state AS state, entry_zone_id AS zone_id, entry_country_id AS country_id
              FROM $address_booktable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $addresses_result = $dbconn->Execute($sql);
      $addresses_array = array();
      while ($addresses = $addresses_result->fields) {
        $format_id = oos_get_address_format_id($address['country_id']);
        $addresses_array[] = array('format_id' => $format_id,
                                   'radio_buttons' => $radio_buttons,
                                   'firstname' => $addresses['firstname'],
                                   'lastname' => $addresses['lastname'],
                                   'address_book_id' => $addresses['address_book_id'],
                                   'address' => oos_address_format($format_id, $addresses, true, ' ', ', '));
        $radio_buttons++;
        // Move that ADOdb pointer!
        $addresses_result->MoveNext();
      }
      // Close result set
      $addresses_result->Close();
    }
  }
  // if no shipping destination address was selected, use their own address as default
  if (!isset($_SESSION['sendto'])) {
    $_SESSION['sendto'] = $_SESSION['customer_default_address_id'];
  }
  if (!isset($process)) $process = 'false';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_shipping'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_shipping_address'], '', 'SSL'));

  ob_start();
  require 'js/checkout_shipping_address.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/modules/shipping_address.tpl';

  $nPageType = OOS_PAGE_TYPE_CHECKOUT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'delivery.gif',

          'process' => $process,
          'addresses_count' => $addresses_count,

          'gender' => $gender,
          'firstname' => $firstname,
          'lastname' => $lastname,
          'company' => $company,
          'street_address' => $street_address,
          'suburb' => $suburb,
          'postcode' => $postcode,
          'city' => $city,
          'country' => $country,

          'gender_error' => $gender_error,
          'firstname_error' => $firstname_error,
          'lastname_error' => $lastname_error,
          'street_address_error' => $street_address_error,
          'post_code_error' => $post_code_error,
          'city_error' => $city_error,
          'state_error' => $state_error,
          'state_has_zones' => $state_has_zones,
          'country_error' => $country_error
      )
  );

  // JavaScript
  $smarty->assign('oos_js', $javascript);

  if ($process == 'false') {
    $smarty->assign('addresses_array', $addresses_array);
  }


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
  $state = oos_get_zone_name($country, $zone_id, $state);
  $smarty->assign('state', $state);


// display the template
$smarty->display($aTemplate['page']);
