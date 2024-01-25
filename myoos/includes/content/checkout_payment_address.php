<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_payment_address.php,v 1.7 2003/02/13 04:23:22 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_word_cleaner.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/checkout_payment_address.php';

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login']));
}

// if there is nothing in the customers cart, redirect them to the shopping cart page
if ($_SESSION['cart']->count_contents() < 1) {
    oos_redirect(oos_href_link($aContents['shopping_cart']));
}

$bError = false; // reset error flag
$bProcess = false;
if (isset($_POST['action']) && ($_POST['action'] == 'submit')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {


    // Process a new billing address
    if (oos_is_not_null($_POST['firstname']) && oos_is_not_null($_POST['lastname']) && oos_is_not_null($_POST['street_address'])) {
        $bProcess = true;

        if (ACCOUNT_GENDER == 'true') {
            $gender = filter_string_polyfill(filter_input(INPUT_POST, 'gender'));
        }
        $firstname = filter_string_polyfill(filter_input(INPUT_POST, 'firstname'));
        $lastname = filter_string_polyfill(filter_input(INPUT_POST, 'lastname'));
        if (ACCOUNT_COMPANY == 'true') {
            $company = filter_string_polyfill(filter_input(INPUT_POST, 'company'));
        }
        if (ACCOUNT_OWNER == 'true') {
            $owner = filter_string_polyfill(filter_input(INPUT_POST, 'owner'));
        }
        if (ACCOUNT_VAT_ID == 'true') {
            $vat_id = filter_string_polyfill(filter_input(INPUT_POST, 'vat_id'));
        }
        $street_address = filter_string_polyfill(filter_input(INPUT_POST, 'street_address'));
        $postcode = filter_string_polyfill(filter_input(INPUT_POST, 'postcode'));
        $city = filter_string_polyfill(filter_input(INPUT_POST, 'city'));

        if (ACCOUNT_STATE == 'true') {
            $state = filter_string_polyfill(filter_input(INPUT_POST, 'state'));
            $zone_id = filter_input(INPUT_POST, 'zone_id', FILTER_VALIDATE_INT);
        }
        $country = filter_string_polyfill(filter_input(INPUT_POST, 'country'));

        $firstname = oos_remove_shouting($firstname, true);
        $lastname = oos_remove_shouting_name($lastname, true);
        $street_address = oos_remove_shouting($street_address);
        $postcode = strtoupper((string) $postcode);
        $city = oos_remove_shouting($city);

        if (ACCOUNT_GENDER == 'true') {
            if (($gender != 'm') && ($gender != 'f') && ($gender != 'd')) {
                $bError = true;
                $oMessage->add($aLang['entry_gender_error']);
            }
        }

        if (strlen($firstname ?? '') < ENTRY_FIRST_NAME_MIN_LENGTH) {
            $bError = true;
            $oMessage->add($aLang['entry_first_name_error']);
        }

        if (strlen($lastname ?? '') < ENTRY_LAST_NAME_MIN_LENGTH) {
            $bError = true;
            $oMessage->add($aLang['entry_last_name_error']);
        }


        if (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') {
            if (!empty($vat_id) && (!oos_validate_is_vatid($vat_id))) {
                $bError = true;
                $oMessage->add($aLang['entry_vat_id_error']);
            } else {
                $vatid_check_error = false;
            }
        }

        if (strlen($street_address ?? '') < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
            $bError = true;
            $oMessage->add($aLang['entry_street_address_error']);
        }

        if (strlen($postcode ?? '') < ENTRY_POSTCODE_MIN_LENGTH) {
            $bError = true;
            $oMessage->add($aLang['entry_post_code_error']);
        }

        if (strlen($city ?? '') < ENTRY_CITY_MIN_LENGTH) {
            $bError = true;
            $oMessage->add($aLang['entry_city_error']);
        }

        if (is_numeric($country) == false) {
            $bError = true;
            $oMessage->add($aLang['entry_country_error']);
        }


        if (ACCOUNT_STATE == 'true') {
            $zone_id = 0;
            $zonestable = $oostable['zones'];
            $country_check_sql = "SELECT COUNT(*) AS total
								FROM $zonestable
								WHERE zone_country_id = '" . intval($country) . "'";
            $country_check = $dbconn->Execute($country_check_sql);
            $entry_state_has_zones = ($country_check->fields['total'] > 0);
            if ($entry_state_has_zones == true) {
                $zonestable = $oostable['zones'];
                $zone_query = "SELECT DISTINCT zone_id
								FROM $zonestable
								WHERE zone_country_id = '" . intval($country) . "'
									AND (zone_name = '" . oos_db_input($state) . "'
									OR zone_code = '" . oos_db_input($state) . "')";
                $zone_result = $dbconn->Execute($zone_query);
                if ($zone_result->RecordCount() == 1) {
                    $zone = $zone_result->fields;
                    $zone_id = $zone['zone_id'];
                } else {
                    $bError = true;
                    $oMessage->add($aLang['entry_state_error_select']);
                }
            } else {
                if (strlen($state ?? '') < ENTRY_STATE_MIN_LENGTH) {
                    $bError = true;
                    $oMessage->add($aLang['entry_state_error']);
                }
            }
        }


        if ($bError == false) {
            $address_booktable = $oostable['address_book'];
            $sql = "SELECT max(address_book_id) AS address_book_id 
					FROM $address_booktable
					WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
            $next_id_result = $dbconn->Execute($sql);
            if ($next_id_result->RecordCount()) {
                $next_id = $next_id_result->fields;
                $entry_id = $next_id['address_book_id'] + 1;
            } else {
                $entry_id = 1;
            }

            $sql_data_array = ['customers_id' => intval($_SESSION['customer_id']), 'address_book_id' => $entry_id, 'entry_firstname' => $firstname, 'entry_lastname' => $lastname, 'entry_street_address' => $street_address, 'entry_postcode' => $postcode, 'entry_city' => $city, 'entry_country_id' => $country];
            if (ACCOUNT_GENDER == 'true') {
                $sql_data_array['entry_gender'] = $gender;
            }
            if (ACCOUNT_COMPANY == 'true') {
                $sql_data_array['entry_company'] = $company;
            }
            if (ACCOUNT_OWNER == 'true') {
                $sql_data_array['entry_owner'] = $owner;
            }
            if (ACCOUNT_VAT_ID == 'true') {
                $sql_data_array['entry_vat_id'] = $vat_id;
                if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false) && ($country != STORE_COUNTRY)) {
                    $sql_data_array['entry_vat_id_status'] = 1;
                } else {
                    $sql_data_array['entry_vat_id_status'] = 0;
                }
            }

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

            $_SESSION['billto'] = $entry_id;

            if (isset($_SESSION['payment'])) {
                unset($_SESSION['payment']);
            }

            oos_redirect(oos_href_link($aContents['checkout_payment']));
        }

        // Process the selected billing destination
    } elseif (isset($_POST['address'])) {
        $reset_payment = false;
        if (isset($_SESSION['billto'])) {
            if ($_SESSION['billto'] != $_POST['address']) {
                if (isset($_SESSION['payment'])) {
                    $reset_payment = true;
                }
            }
        }

        $_SESSION['billto'] = intval($_POST['address']);

        $address_booktable = $oostable['address_book'];
        $sql = "SELECT COUNT(*) AS total
				FROM $address_booktable
				WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
					AND address_book_id = '" . intval($_SESSION['billto']) . "'";
        $check_address_result = $dbconn->Execute($sql);
        $check_address = $check_address_result->fields;

        if ($check_address['total'] == '1') {
            if ($reset_payment == true) {
                unset($_SESSION['payment']);
            }
            oos_redirect(oos_href_link($aContents['checkout_payment']));
        } else {
            unset($_SESSION['billto']);
        }
        // no addresses to select from - customer decided to keep the current assigned address
    } else {
        $_SESSION['billto'] = intval($_SESSION['customer_default_address_id']);

        oos_redirect(oos_href_link($aContents['checkout_payment']));
    }
}

// if no billing destination address was selected, use their own address as default
if (!isset($_SESSION['billto'])) {
    $_SESSION['billto'] = intval($_SESSION['customer_default_address_id']);
}

if ($bProcess == false) {
    $address_booktable = $oostable['address_book'];
    $sql = "SELECT COUNT(*) AS total
           FROM $address_booktable
           WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
             AND address_book_id != '" . intval($_SESSION['billto']) . "'";
    $addresses_count_result = $dbconn->Execute($sql);
    $addresses_count = $addresses_count_result->fields['total'];

    if ($addresses_count > 0) {
        $radio_buttons = 0;
        $address_booktable = $oostable['address_book'];
        $sql = "SELECT address_book_id, entry_firstname AS firstname, entry_lastname AS lastname,
                     entry_company AS company, entry_street_address AS street_address,
                     entry_city AS city, entry_postcode AS postcode,
                     entry_state AS state, entry_zone_id AS zone_id, entry_country_id AS country_id
				FROM $address_booktable
				WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
        $addresses_result = $dbconn->Execute($sql);
        $addresses_array = [];
        while ($addresses = $addresses_result->fields) {
            $format_id = oos_get_address_format_id($address['country_id']);
            $addresses_array[] = ['format_id' => $format_id, 'radio_buttons' => $radio_buttons, 'firstname' => $addresses['firstname'], 'lastname' => $addresses['lastname'], 'address_book_id' => $addresses['address_book_id'], 'address' => oos_address_format($format_id, $addresses, true, ' ', ', ')];
            $radio_buttons++;
            $addresses_result->MoveNext();
        }
    }
}

if (!isset($bProcess)) {
    $bProcess = false;
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['checkout_payment']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['checkout_payment_address']));

$aTemplate['page'] = $sTheme . '/page/checkout_payment_address.html';

$nPageType = OOS_PAGE_TYPE_CHECKOUT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb' => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,nofollow,noodp,noydir', 'checkout_active' => 1, 'process' => $bProcess, 'addresses_count' => $addresses_count, 'gender' => $gender, 'firstname' => $firstname, 'lastname' => $lastname, 'company' => $company, 'owner' => $owner, 'vat_id' => $vat_id, 'street_address' => $street_address, 'postcode' => $postcode, 'city' => $city, 'country' => $country, 'store_country' => STORE_COUNTRY, 'gender_error' => $gender_error, 'firstname_error' => $firstname_error, 'lastname_error' => $lastname_error, 'street_address_error' => $street_address_error, 'post_code_error' => $post_code_error, 'city_error' => $city_error, 'state_error' => $state_error, 'state_has_zones' => $entry_state_has_zones, 'country_error' => $country_error]
);


if ($bProcess == false) {
    $smarty->assign('addresses_array', $addresses_array);
}


if ($entry_state_has_zones == true) {
    $zones_names = [];
    $zones_values = [];
    $zonestable = $oostable['zones'];
    $zones_result = $dbconn->Execute("SELECT zone_name FROM $zonestable WHERE zone_country_id = '" . intval($country) . "' ORDER BY zone_name");
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

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
