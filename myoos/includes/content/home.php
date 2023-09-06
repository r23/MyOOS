<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: default.php,v 1.2 2003/01/09 09:40:07 elarifr
   orig: default.php,v 1.81 2003/02/13 04:23:23 hpdl
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/home.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_default.php';

// default
$sCanonical = OOS_HTTPS_SERVER . OOS_SHOP;

$aTemplate['page'] = $sTheme . '/page/home.html';
$aTemplate['slider'] = $sTheme . '/page/slider/_slider.html';
if ($oEvent->installed_plugin('featured')) {
    $aTemplate['featured'] = $sTheme . '/products/_featured.html';
}
if ($oEvent->installed_plugin('specials')) {
    $aTemplate['specials'] = $sTheme . '/products/_specials.html';
}
if ($oEvent->installed_plugin('manufacturers')) {
    $aTemplate['mod_manufacturers'] = $sTheme . '/modules/manufacturers.html';
}
$aTemplate['new_products'] = $sTheme . '/products/_new_products.html';
$aTemplate['upcoming_products'] = $sTheme . '/products/upcoming_products.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'home_active'   => 1, 'canonical'     => $sCanonical]
);

if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

if (!$smarty->isCached($aTemplate['slider'], $sModulesCacheID)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/slider.php';
}
$smarty->assign('slider', $smarty->fetch($aTemplate['slider'], $sModulesCacheID));

if ($oEvent->installed_plugin('featured')) {
    if (!$smarty->isCached($aTemplate['featured'], $sModulesCacheID)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
    }
    $smarty->assign('featured', $smarty->fetch($aTemplate['featured'], $sModulesCacheID));
}

if ($oEvent->installed_plugin('specials')) {
    if (!$smarty->isCached($aTemplate['specials'], $sModulesCacheID)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/specials.php';
    }
    $smarty->assign('specials', $smarty->fetch($aTemplate['specials'], $sModulesCacheID));
}


if (!$smarty->isCached($aTemplate['new_products'], $sModulesCacheID)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/new_products.php';
}
$smarty->assign('new_products', $smarty->fetch($aTemplate['new_products'], $sModulesCacheID));

if ($oEvent->installed_plugin('manufacturers')) {
    if (!$smarty->isCached($aTemplate['mod_manufacturers'], $sModulesCacheID)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/mod_manufacturers.php';
    }
    $smarty->assign('mod_manufacturers', $smarty->fetch($aTemplate['mod_manufacturers'], $sModulesCacheID));
}

if (!$smarty->isCached($aTemplate['upcoming_products'], $sModulesCacheID)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/upcoming_products.php';
}
$smarty->assign('upcoming_products', $smarty->fetch($aTemplate['upcoming_products'], $sModulesCacheID));


// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
