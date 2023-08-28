<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book.php,v 1.55 2003/02/13 01:58:23 hpdl
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
    $_SESSION['guest_login'] = 'off';
    oos_redirect(oos_href_link($aContents['login']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_address_book.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

$address_booktable = $oostable['address_book'];
$sql = "SELECT address_book_id, entry_company, entry_firstname, entry_lastname,
				entry_street_address, entry_postcode, entry_city, entry_state,
				entry_country_id, entry_zone_id
          FROM $address_booktable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
          ORDER BY entry_firstname, entry_lastname";
$address_book_result = $dbconn->Execute($sql);

$aAddressBook = [];
while ($address_book = $address_book_result->fields) {
    $state = $address_book['entry_state'];
    $country_id = $address_book['entry_country_id'];
    $zone_id = $address_book['entry_zone_id'];
    $country = oos_get_country_name($country_id);

    if (ACCOUNT_STATE == 'true') {
        $state = oos_get_zone_code($country_id, $zone_id, $state);
    }

    $aAddressBook[] = ['address_book_id'    => $address_book['address_book_id'], 'company'             => $address_book['entry_company'], 'firstname'         => $address_book['entry_firstname'], 'lastname'             => $address_book['entry_lastname'], 'street_address'    => $address_book['entry_street_address'], 'postcode'            => $address_book['entry_postcode'], 'city'                => $address_book['entry_city'], 'country'            => $country, 'state'                => $state];
    $address_book_result->MoveNext();
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_address_book']));

$aTemplate['page'] = $sTheme . '/page/address_book.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'        => $aLang['heading_title'], 'robots'            => 'noindex,nofollow,noodp,noydir', 'account_active'    => 1, 'address_book'         => $aAddressBook]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
