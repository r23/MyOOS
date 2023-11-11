<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.75 2003/02/13 03:01:49 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce

   Max Order - 2003/04/27 JOHNSON - Copyright (c) 2003 Matti Ressler - mattifinn@optusnet.com.au
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';

$bError = false;

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new oosUser();
    $_SESSION['user']->anonymous();
}

$password = oos_db_prepare_input($_SESSION['password']);

if (empty($password) || !is_string($password)) {
    oos_redirect(oos_href_link($aContents['login']));
}


/* Check if it is ok to login */
if (!isset($_SESSION['password_forgotten_count'])) {
    $_SESSION['login_count'] = 1;
} else {
    $_SESSION['login_count'] ++;
}

if ($_SESSION['login_count'] > 20) {
    oos_redirect(oos_href_link($aContents['403']));
}


if (isset($_GET['action']) && ($_GET['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_GET['formid']))
) {
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname,
                   customers_password, customers_wishlist_link_id, customers_language,
                   customers_email_address, customers_2fa_active, customers_default_address_id, customers_max_order 
            FROM $customerstable
            WHERE customers_login = '1'
              AND customers_id = '" . intval($_SESSION['customer_2fa_id']) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if (!$check_customer_result->RecordCount()) {
        oos_redirect(oos_href_link($aContents['403']));
    } else {
        $check_customer = $check_customer_result->fields;

        // Check that password is good
        if (!oos_validate_password($password, $check_customer['customers_password'])) {
            $bError = true;
        } else {
            $address_booktable = $oostable['address_book'];
            $sql = "SELECT entry_vat_id, entry_vat_id_status, entry_country_id, entry_zone_id
					FROM $address_booktable
					WHERE customers_id = '" . intval($check_customer['customers_id']) . "'
						AND address_book_id = '" . intval($check_customer['customers_default_address_id']) . "'";
            $check_country = $dbconn->GetRow($sql);

            if ($check_customer['customers_language'] == '') {
                $customerstable = $oostable['customers'];
                $dbconn->Execute(
                    "UPDATE $customerstable
					SET customers_language = '" . oos_db_input($sLanguage) . "'
					WHERE customers_id = '" . intval($check_customer['customers_id']) . "'"
                );
            }


            $_SESSION['login_count'] = 1;
            $_SESSION['customer_wishlist_link_id'] = $check_customer['customers_wishlist_link_id'];
            $_SESSION['customer_id'] = $check_customer['customers_id'];
            $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
            if (ACCOUNT_GENDER == 'true') {
                $_SESSION['customer_gender'] = $check_customer['customers_gender'];
            }
            $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
            $_SESSION['customer_lastname'] = $check_customer['customers_lastname'];
            $_SESSION['customer_max_order'] = $check_customer['customers_max_order'];
            $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
            $_SESSION['delivery_country_id'] = $check_country['entry_country_id'];
            $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
            if (ACCOUNT_VAT_ID == 'true') {
                $_SESSION['customers_vat_id_status'] = $check_country['entry_vat_id_status'];
            }

            $_SESSION['user']->restore_group();
            $aUser = $_SESSION['user']->group;

            $customers_infotable = $oostable['customers_info'];
            $dbconn->Execute(
                "UPDATE $customers_infotable
								SET customers_info_date_of_last_logon = now(),
									customers_info_number_of_logons = customers_info_number_of_logons+1
								WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'"
            );

            // coupon
            $coupon_gv_customertable = $oostable['coupon_gv_customer'];
            $query = "SELECT amount
					FROM $coupon_gv_customertable
					WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
            $gv_result = $dbconn->GetRow($query);
            if ($gv_result['amount'] > 0) {
                $_SESSION['coupon_amount'] = $gv_result['amount'];
            }

            // restore cart contents
            $_SESSION['cart']->restore_contents();

            if ((is_countable($_SESSION['navigation']->snapshot) ? count($_SESSION['navigation']->snapshot) : 0) > 0) {
                $origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get']);
                $_SESSION['navigation']->clear_snapshot();
                oos_redirect($origin_href);
            } else {
                oos_redirect(oos_href_link($aContents['account']));
            }
        }
    }
}

oos_redirect(oos_href_link($aContents['403']));
