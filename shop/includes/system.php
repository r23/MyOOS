<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

//smarty
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
$smarty = new myOOS_Smarty();

//debug
if ($debug == 1) {
	$smarty->force_compile   = TRUE;
	$smarty->debugging       = FALSE;
	$smarty->clearAllCache();
	$smarty->clearCompiledTemplate();
}

// object register
$smarty->assignByRef("oEvent", $oEvent);

// object register
#  $smarty->register_object("cart", $_SESSION['cart'],array('count_contents', 'get_products'));

// cache_id
$sCacheID			= $sTheme . '|block|' . $sLanguage;
$sSystemCacheID		= $sTheme . '|block|' . $sLanguage;
$sCategoriesCacheID	= $sTheme . '|block|categories|' . $sLanguage . '|' . $sCategory;
$sModulesCacheID	= $sTheme . '|modules|' . $sLanguage . '|' . $sCurrency;


if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
    $nManufacturersID = intval($_GET['manufacturers_id']);
} else {
    $nManufacturersID = 0;
}
$sManufacturersCacheID = $sTheme . '|block|manufacturers|' . $sLanguage . '|' . $nManufacturersID;
$sManufacturersInfoCacheID = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . $nManufacturersID;

if (isset($_GET['products_id'])) {
	if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_GET['products_id']);
	$sManufacturersInfoCacheID = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . intval($nProductsID);
	$sProductsInfoCacheID = $sTheme . '|products_info|' . $sLanguage . '|' . intval($nProductsID);
	$sXsellProductsCacheID = $sTheme . '|block|products|' . $sLanguage . '|' . intval($nProductsID);
}

// Meta-Tags
if (empty($sPagetitle)) $sPagetitle = OOS_META_TITLE;
if (empty($sDescription)) $sDescription = OOS_META_DESCRIPTION;

$smarty->assign(
	array(
		'filename'		=> $aContents,
		'page_file'		=> $sContent,

		'request_type'	=> $request_type,

		'theme_set'		=> $sTheme,
		'theme_image'	=> 'themes/' . $sTheme . '/images',
		'theme'			=> 'themes/' . $sTheme,

		'lang'			=> $aLang,
		'language'		=> $sLanguage,
		'currency'		=> $sCurrency,
		
		'pagetitle'		=> $sPagetitle,
		'meta_description'	=> $sDescription
)
);
 
$smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);


$cart_count_contents = 0;
$cart_show_total = 0;

$aSystem = array();

if (isset($_SESSION)) {
 
	$sFormid = md5(uniqid(rand(), true));
	$_SESSION['formid'] = $sFormid;

	$aSystem = array(
		'sed'	=> true,
		'formid' => $sFormid,
		'session_name' => $session->getName(),
		'session_id' => $session->getId()
	);

	if (is_object($_SESSION['cart'])) {
		$smarty->registerObject("cart", $_SESSION['cart'],array('count_contents', 'get_products')); 

		$cart_count_contents = $_SESSION['cart']->count_contents();
		$cart_show_total = $oCurrencies->format($_SESSION['cart']->show_total()); 
	}

}


$smarty->assign(
	array(
		'mySystem'              => $aSystem,
		'cart_show_total'		=> $cart_show_total,
		'cart_count_contents'	=> $cart_count_contents
	)
);


$products_unitstable = $oostable['products_units'];
$query = "SELECT products_units_id, products_unit_name
FROM $products_unitstable
WHERE languages_id = '" . intval($nLanguageID) . "'";
$products_units = $dbconn->GetAssoc($query);


// PAngV
$sPAngV = $aLang['text_taxt_incl'];
if ($_SESSION['user']->group['show_price'] == 1) {
	if ($_SESSION['user']->group['show_price_tax'] == 1) {
		$sPAngV = $aLang['text_taxt_incl'];
	} else {
		$sPAngV = $aLang['text_taxt_add'];
	}

	if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
		$sPAngV = $aLang['tax_info_excl'];
	}
}


$smarty->assign(
	array(
		'pangv' => $sPAngV,
		'products_units'=> $products_units,
	)
);
