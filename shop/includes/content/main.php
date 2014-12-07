<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_default.php';

// default
$sCanonical = (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP;

$aTemplate['page'] = $sTheme . '/page/main.html';
if ($oEvent->installed_plugin('spezials')) $aTemplate['new_spezials'] = $sTheme . '/page/products/new_spezials.html';
if ($oEvent->installed_plugin('featured')) $aTemplate['featured'] = $sTheme . '/page/products/featured.html';
if ($oEvent->installed_plugin('manufacturers')) $aTemplate['mod_manufacturers'] = $sTheme . '/page/products/manufacturers.html';
$aTemplate['new_products'] = $sTheme . '/page/products/new_products.html';
$aTemplate['upcoming_products'] = $sTheme . '/page/products/upcoming_products.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'	=> $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'canonical'		=> $sCanonical
	)
);

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}


if ($oEvent->installed_plugin('spezials')) {
	if (!$smarty->isCached($aTemplate['new_spezials'], $nModulesCacheID)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/modules/new_spezials.php';
	}
	$smarty->assign('new_spezials', $smarty->fetch($aTemplate['new_spezials'], $nModulesCacheID));
}
 
if ($oEvent->installed_plugin('featured')) {
	if (!$smarty->isCached($aTemplate['featured'], $nModulesCacheID)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
	}
	$smarty->assign('featured', $smarty->fetch($aTemplate['featured'], $nModulesCacheID));
}

if (!$smarty->isCached($aTemplate['new_products'], $nModulesCacheID)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/modules/new_products.php';
}
$smarty->assign('new_products', $smarty->fetch($aTemplate['new_products'], $nModulesCacheID));

if ($oEvent->installed_plugin('manufacturers')) {
	if (!$smarty->isCached($aTemplate['mod_manufacturers'], $nModulesCacheID)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/modules/mod_manufacturers.php';
	}
    $smarty->assign('mod_manufacturers', $smarty->fetch($aTemplate['mod_manufacturers'], $nModulesCacheID));
}

if (!$smarty->isCached($aTemplate['upcoming_products'], $nModulesCacheID)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/modules/upcoming_products.php';
}
$smarty->assign('upcoming_products', $smarty->fetch($aTemplate['upcoming_products'], $nModulesCacheID));

// display the template
$smarty->display($aTemplate['page']);
