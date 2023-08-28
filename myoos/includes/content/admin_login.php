<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login_admin.php
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
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// check
$manual_infotable = $oostable['manual_info'];
$sql = "SELECT status FROM $manual_infotable WHERE man_info_id = '1'";
$login = $dbconn->GetRow($sql);

if ($login['status'] == '0') {
    oos_redirect(oos_href_link($aContents['403']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}


require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_key_generate.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/admin_login.php';


if (isset($_SESSION['customer_id'])) {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_wishlist_link_id']);
    unset($_SESSION['customer_default_address_id']);
    unset($_SESSION['customer_gender']);
    unset($_SESSION['customer_first_name']);
    unset($_SESSION['customer_lastname']);
    unset($_SESSION['customer_country_id']);
    unset($_SESSION['customer_zone_id']);
    unset($_SESSION['comments']);
    unset($_SESSION['customer_max_order']);
    unset($_SESSION['coupon_amount']);
    unset($_SESSION['cc_id']);
    unset($_SESSION['man_key']);

    $_SESSION['cart']->reset();

    $_SESSION['user']->anonymous();
}


if (isset($_POST['action']) && ($_POST['action'] == 'login_process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $email_address = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);
	$keya = filter_string_polyfill(filter_input(INPUT_POST, 'keya'));
	$keyb = filter_string_polyfill(filter_input(INPUT_POST, 'keyb'));

    if (empty($email_address) || !is_string($email_address)) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    if (empty($keyb) || !is_string($keyb)) {
        oos_redirect(oos_href_link($aContents['403']));
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
        $dbconn->Execute(
            "UPDATE $manual_infotable
							SET man_key = '',
								man_key2 = ''
						WHERE man_info_id = '1'"
        );
        oos_redirect(oos_href_link($aContents['403']));
    }

    // Check if email exists
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname,
                   customers_password, customers_wishlist_link_id, 
                   customers_email_address, customers_default_address_id, customers_max_order
            FROM $customerstable
            WHERE customers_login = '1'
              AND customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if (!$check_customer_result->RecordCount()) {
        $manual_infotable = $oostable['manual_info'];
        $dbconn->Execute(
            "UPDATE " . $oostable['manual_info'] . "
							SET man_key2  = ''
						WHERE where man_info_id = '1'"
        );
        oos_redirect(oos_href_link($aContents['403']));
    } else {
        $check_customer = $check_customer_result->fields;
        $login_result_values = $login_result->fields;

        // Check that status is 1 and
        $address_booktable = $oostable['address_book'];
        $sql = "SELECT entry_vat_id, entry_vat_id_status, entry_country_id, entry_zone_id
		        FROM $address_booktable
		        WHERE customers_id = '" . intval($check_customer['customers_id']) . "'
		          AND address_book_id = '" . intval($check_customer['customers_default_address_id']) . "'";
        $check_country = $dbconn->GetRow($sql);

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
        $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
        if (ACCOUNT_VAT_ID == 'true') {
            $_SESSION['customers_vat_id_status'] = $check_customer['entry_vat_id_status'];
        }

        $_SESSION['man_key'] = $keya;

        $_SESSION['user']->restore_group();
        $aUser = $_SESSION['user']->group;


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

        oos_redirect(oos_href_link($aContents['account']));
    }
}



// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['login']));
$sCanonical = oos_href_link($aContents['login'], '', false, true);

$aTemplate['page'] = $sTheme . '/page/admin_login.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,nofollow,noodp,noydir', 'canonical'        => $sCanonical]
);



if (isset($_GET['action']) && ($_GET['action'] == 'login_admin')) {
    $email_address = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);
    $verif_key = filter_string_polyfill(filter_input(INPUT_POST, 'verif_key'));

    if (empty($email_address) || !is_string($email_address)) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    if (empty($verif_key) || !is_string($verif_key)) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $passwordLength = 24;
    $newkey2 = RandomPassword($passwordLength);

    $manual_infotable = $oostable['manual_info'];
    $dbconn->Execute(
        "UPDATE $manual_infotable
                    SET man_key2  = '" . oos_db_input($newkey2) . "'
                    WHERE man_key = '" . oos_db_input($verif_key) . "' 
					  AND man_info_id = '1'"
    );

    $manual_infotable = $oostable['manual_info'];
    $login_query = "SELECT man_key2, man_key3, status FROM $manual_infotable WHERE man_key = '" . oos_db_input($verif_key) . "' AND status = '1'";
    $login_result_values = $dbconn->Execute($login_query);
    if (!$login_result_values->RecordCount()) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $smarty->assign(
        ['newkey2'             => $newkey2, 'email_address'       => $email_address, 'verif_key'           => $verif_key, 'login_result_values' => $login_result_values]
    );
}

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
