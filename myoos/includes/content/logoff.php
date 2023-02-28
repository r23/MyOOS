<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: logoff.php,v 1.1.2.2 2003/05/13 23:20:53 wilt Exp $
   orig: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl
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

// if the customer is not logged on, redirect them to the login page
if (!isset($_SESSION['customer_id'])) {
    oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_logoff.php';

unset($_SESSION['customer_id']);
unset($_SESSION['customer_wishlist_link_id']);
unset($_SESSION['customer_default_address_id']);
unset($_SESSION['customer_gender']);
unset($_SESSION['customer_first_name']);
unset($_SESSION['customer_lastname']);
unset($_SESSION['customer_country_id']);
unset($_SESSION['delivery_country_id']);
unset($_SESSION['customer_zone_id']);
unset($_SESSION['comments']);
unset($_SESSION['customer_max_order']);
unset($_SESSION['coupon_amount']);
unset($_SESSION['cc_id']);
unset($_SESSION['man_key']);

if (ACCOUNT_VAT_ID == 'true') {
    $_SESSION['customers_vat_id_status'] = 0;
}

$_SESSION['cart']->reset();
$_SESSION['user']->anonymous();
$aUser = $oUser->group;

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title']);
$sCanonical = oos_href_link($aContents['logoff'], '', false, true);

$aTemplate['page'] = $sTheme . '/page/user_logoff.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    array(
        'breadcrumb'    => $oBreadcrumb->trail(),
        'heading_title' => $aLang['heading_title'],
        'robots'        => 'noindex,follow,noodp,noydir',
        'login_active'    => 1,
        'canonical'        => $sCanonical
    )
);

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
