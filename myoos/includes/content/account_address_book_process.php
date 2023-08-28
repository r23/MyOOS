<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book_process.php,v 1.73 2003/02/13 01:58:23 hpdl
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

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_word_cleaner.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_address_book_process.php';

if (isset($_POST['action']) && ($_POST['action'] == 'deleteconfirm') && isset($_POST['entry_id']) && is_numeric($_POST['entry_id'])
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $entry_id = oos_db_prepare_input($_POST['entry_id']);

    if ($entry_id == $_SESSION['customer_default_address_id']) {
        $oMessage->add_session('warning', $aLang['warning_primary_address_deletion']);
    } else {
        $address_booktable = $oostable['address_book'];
        $query = "DELETE FROM $address_booktable
					WHERE address_book_id = '" . intval($entry_id) . "' 
					AND	customers_id = '" . intval($_SESSION['customer_id']) . "'";
        $dbconn->Execute($query);

        $oMessage->add_session('success', $aLang['success_address_book_entry_deleted']);
    }

    oos_redirect(oos_href_link($aContents['account_address_book']));
}

// Post-entry error checking when updating or adding an entry
$bProcess = false;
if (isset($_POST['action']) && ($_POST['action'] == 'process') || ($_POST['action'] == 'update')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $bProcess = true;

    if (isset($_POST['entry_id']) && is_numeric($_POST['entry_id'])) {
        $entry_id = oos_db_prepare_input($_POST['entry_id']);
    }

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
        $zone_id = filter_string_polyfill(filter_input(INPUT_POST, 'zone_id'));
    }
    $country = filter_string_polyfill(filter_input(INPUT_POST, 'country'));

    $firstname = oos_remove_shouting($firstname, true);
    $lastname = oos_remove_shouting_name($lastname, true);
    $street_address = oos_remove_shouting($street_address);
    $postcode = strtoupper((string) $postcode);
    $city = oos_remove_shouting($city);


    $bError = false; // reset error flag
    if (ACCOUNT_GENDER == 'true') {
        if (($gender != 'm') && ($gender != 'f') && ($gender != 'd')) {
            $bError = true;
            $oMessage->add_session('danger', $aLang['entry_gender_error']);
        }
    }

    if (strlen($firstname ?? '') < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_first_name_error']);
    }

    if (strlen($lastname ?? '') < ENTRY_LAST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_last_name_error']);
    }


    if (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') {
        if (!empty($vat_id) && (!oos_validate_is_vatid($vat_id))) {
            $bError = true;
            $oMessage->add_session('danger', $aLang['entry_vat_id_error']);
        } else {
            $vatid_check_error = false;
        }
    }

    if (strlen($street_address ?? '') < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_street_address_error']);
    }

    if (strlen($postcode ?? '') < ENTRY_POSTCODE_MIN_LENGTH) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_post_code_error']);
    }

    if (strlen($city ?? '') < ENTRY_CITY_MIN_LENGTH) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_city_error']);
    }

    if (is_numeric($country) == false) {
        $bError = true;
        $oMessage->add_session('danger', $aLang['entry_country_error']);
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
                $oMessage->add_session('danger', $aLang['entry_state_error_select']);
            }
        } else {
            if (strlen($state ?? '') < ENTRY_STATE_MIN_LENGTH) {
                $bError = true;
                $oMessage->add_session('danger', $aLang['entry_state_error']);
            }
        }
    }


    if ($bError == false) {
        $sql_data_array = ['entry_firstname' => $firstname, 'entry_lastname' => $lastname, 'entry_street_address' => $street_address, 'entry_postcode' => $postcode, 'entry_city' => $city, 'entry_country_id' => $country];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['entry_gender'] = $gender;
        }
        if (ACCOUNT_COMPANY == 'true') {
            $sql_data_array['entry_company'] = $company;
        }
        if (ACCOUNT_OWNER == 'true') {
            $sql_data_array['entry_owner'] = $owner;
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
        if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false)) {
            $sql_data_array['entry_vat_id_status'] = '1';
        } else {
            $sql_data_array['entry_vat_id_status'] = '0';
        }

        if ($_POST['action'] == 'update') {
            $address_booktable = $oostable['address_book'];
            $check_query = "SELECT address_book_id FROM $address_booktable WHERE address_book_id = '" . intval($entry_id) . "' AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
            $check_result = $dbconn->Execute($check_query);

            if ($check_result->RecordCount()) {
                oos_db_perform($oostable['address_book'], $sql_data_array, 'UPDATE', "address_book_id = '" . intval($entry_id) . "' AND customers_id ='" . intval($_SESSION['customer_id']) . "'");

                if ((isset($_POST['primary']) && ($_POST['primary'] == 'on')) || ($entry_id == $_SESSION['customer_default_address_id'])) {
                    if (ACCOUNT_GENDER == 'true') {
                        $_SESSION['customer_gender'] = $gender;
                    }
                    $_SESSION['customer_first_name'] = $firstname;
                    $_SESSION['customer_lastname'] = $lastname;
                    $_SESSION['customer_country_id'] = $country;
                    $_SESSION['customer_zone_id'] = (($zone_id > 0) ? (int)$zone_id : '0');
                    $_SESSION['customer_default_address_id'] = intval($entry_id);

                    if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false)) {
                        $_SESSION['customers_vat_id_status'] = '1';
                    } else {
                        $_SESSION['customers_vat_id_status'] = '0';
                    }

                    $sql_data_array = ['customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_default_address_id' => intval($entry_id)];

                    if (ACCOUNT_GENDER == 'true') {
                        $sql_data_array['customers_gender'] = $gender;
                    }

                    oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "'");

                    $update_info_sql = "UPDATE " . $oostable['customers_info'] . " 
										SET customers_info_date_account_last_modified = now() 
										WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
                    $dbconn->Execute($update_info_sql);
                }
            }
        } else {
            $sql_data_array['customers_id'] = intval($_SESSION['customer_id']);
            oos_db_perform($oostable['address_book'], $sql_data_array);

            $new_address_book_id = $dbconn->Insert_ID();


            if (isset($_POST['primary']) && ($_POST['primary'] == 'on')) {
                if (ACCOUNT_GENDER == 'true') {
                    $_SESSION['customer_gender'] = $gender;
                }
                $_SESSION['customer_first_name'] = $firstname;
                $_SESSION['customer_lastname'] = $lastname;
                $_SESSION['customer_country_id'] = $country;
                $_SESSION['customer_zone_id'] = (($zone_id > 0) ? (int)$zone_id : '0');
                $_SESSION['customer_default_address_id'] = $new_address_book_id;

                if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false)) {
                    $_SESSION['customers_vat_id_status'] = '1';
                } else {
                    $_SESSION['customers_vat_id_status'] = '0';
                }

                $sql_data_array = ['customers_firstname' => $firstname, 'customers_lastname' => $lastname];

                if (ACCOUNT_GENDER == 'true') {
                    $sql_data_array['customers_gender'] = $gender;
                }
                $sql_data_array['customers_default_address_id'] = $new_address_book_id;

                oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "'");

                $update_info_sql = "UPDATE " . $oostable['customers_info'] . " 
									SET customers_info_date_account_last_modified = now() 
									WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
                $dbconn->Execute($update_info_sql);
            }
        }
        $oMessage->add_session('success', $aLang['success_address_book_entry_updated']);
        oos_redirect(oos_href_link($aContents['account_address_book']));
    }
}

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	$entry_id = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
    $address_booktable = $oostable['address_book'];
    $address_sql = "SELECT entry_gender, entry_company, entry_owner, entry_vat_id, entry_vat_id_status,
						entry_firstname, entry_lastname, entry_street_address, entry_postcode, entry_city,
						entry_state, entry_zone_id, entry_country_id				   
					FROM $address_booktable
					WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
					AND address_book_id = '" . intval($entry_id) . "'";
    $entry_result = $dbconn->Execute($address_sql);

    if (!$entry_result->RecordCount()) {
        $oMessage->add_session('danger', $aLang['error_nonexisting_address_book_entry']);

        oos_redirect(oos_href_link($aContents['account_address_book']));
    }

    $entry = $entry_result->fields;
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
	$entry_id = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    if ($entry_id == $_SESSION['customer_default_address_id']) {
        $oMessage->add_session('warning', $aLang['warning_primary_address_deletion']);

        oos_redirect(oos_href_link($aContents['account_address_book']));
    } else {
        $address_booktable = $oostable['address_book'];
        $check_query = "SELECT count(*) as total FROM $address_booktable WHERE address_book_id = '" . intval($entry_id) . "' AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
        $check_result = $dbconn->Execute($check_query);

        if ($check_result->fields['total'] < 1) {
            $oMessage->add_session('danger', $aLang['error_nonexisting_address_book_entry']);

            oos_redirect(oos_href_link($aContents['account_address_book']));
        }
    }
} else {
    $entry = ['entry_country_id' => STORE_COUNTRY];
}

if (!isset($_GET['delete']) && !isset($_GET['edit'])) {
    if (oos_count_customer_address_book_entries() >= MAX_ADDRESS_BOOK_ENTRIES) {
        $oMessage->add_session('danger', $aLang['error_address_book_full']);

        oos_redirect(oos_href_link($aContents['account_address_book']));
    }
}

$entry_id = filter_input(INPUT_GET, 'entry_id', FILTER_VALIDATE_INT);
$back_link = oos_href_link($aContents['account_address_book']);

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_address_book']));

if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
	$entry_id = filter_input(INPUT_GET, 'entry_id', FILTER_VALIDATE_INT);
    $oBreadcrumb->add($aLang['navbar_title_modify_entry'], oos_href_link($aContents['account_address_book_process'], 'edit=' . intval($entry_id)));
} elseif (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
	$delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
    $oBreadcrumb->add($aLang['navbar_title_delete_entry'], oos_href_link($aContents['account_address_book_process'], 'delete=' . intval($delete)));
} else {
    $oBreadcrumb->add($aLang['navbar_title_add_entry'], oos_href_link($aContents['account_address_book_process']));
}


$aTemplate['page'] = $sTheme . '/page/address_book_process.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['navbar_title_1'] . ' ' . $aLang['navbar_title_2'] . ' ' . OOS_META_TITLE;


require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    ['breadcrumb' => $oBreadcrumb->trail(), 'back_link'      => $back_link, 'entry_id'       => $entry_id, 'process'        => $process]
);

if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $smarty->assign(
        ['heading_title' => $aLang['heading_title_modify_entry']]
    );
} else {
    $smarty->assign(
        ['heading_title' => $aLang['heading_title_add_entry']]
    );
}

$smarty->assign(
    ['robots'            => 'noindex,nofollow,noodp,noydir', 'account_active'    => 1, 'gender'         => $gender, 'firstname'      => $firstname, 'lastname'       => $lastname, 'company'        => $company, 'street_address' => $street_address, 'postcode'       => $postcode, 'city'           => $city, 'country'        => $country]
);


if ($state_has_zones == 'true') {
    $aZonesNames = [];
    $aZonesValues = [];
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

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
