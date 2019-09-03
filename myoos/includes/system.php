<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
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
	$smarty->debugging       = TRUE;
	$smarty->clearAllCache();
	$smarty->clearCompiledTemplate();
}

// object register
$smarty->assignByRef("oEvent", $oEvent);
$smarty->assignByRef("oNavMenu", $oNavMenu);

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
if (empty($facebook_title)) $facebook_title = $sPagetitle;
if (empty($twitter_title)) $twitter_title = $facebook_title;

if (empty($sDescription)) $sDescription = OOS_META_DESCRIPTION;
if (empty($facebook_description)) $facebook_description = $sDescription;
if (empty($twitter_description)) $twitter_description = $facebook_description;

if (empty($twitter_card)) $twitter_card = TWITTER_CARD;


$smarty->assign(
	array(
		'filename'		=> $aContents,
		'page_file'		=> $sContent,

		'theme_set'		=> $sTheme,
		'theme_image'	=> 'themes/' . $sTheme . '/images',
		'theme'			=> 'themes/' . $sTheme,

		'lang'				=> $aLang,
		'language'			=> $sLanguage,
		'content_language'	=> $sLanguageCode,
		'currency'			=> $sCurrency,

		'pagetitle'			=> $sPagetitle,
		'facebook_title'	=> $facebook_title,
		'twitter_title'		=> $twitter_title,
		
		'meta_description'	=> $sDescription,
		'facebook_description'	=> $facebook_description,
		'twitter_description'	=> $twitter_description,
		
		'welcome'         => sprintf($aLang['welcome_msg'], STORE_NAME)
	)

);

$smarty->assign('oos_base', OOS_HTTPS_SERVER . OOS_SHOP);

$sNavMenue = $oNavMenu->build();
$smarty->assign('nav_menu', $sNavMenue);

$cart_products = array();
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
		$cart_products = $_SESSION['cart']->get_products();
		$cart_show_total = $oCurrencies->format($_SESSION['cart']->show_total()); 
	}
}


$smarty->assign(
	array(
		'mySystem'              => $aSystem,
		'myUser'				=> $aUser,
		'cart_products' 		=> $cart_products,
		'cart_show_total'		=> $cart_show_total,
		'cart_count_contents'	=> $cart_count_contents
	)
);

/* -----------shopping_cart.php--------------------------------------- */

if (isset($_SESSION)) { 
	$gv_coupon_show = 0;
	$gv_amount_show = 0;
	
	if (isset($_SESSION['customer_id'])) {
		$coupon_gv_customertable = $oostable['coupon_gv_customer'];
		$query = "SELECT amount
				  FROM $coupon_gv_customertable
				  WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
		$gv_result = $dbconn->GetRow($query);
		if ($gv_result['amount'] > 0 ) {
			$gv_amount_show = $oCurrencies->format($gv_result['amount']);
		}
	}

  
	if (isset($_SESSION['gv_id'])) {
		$couponstable = $oostable['coupons'];
		$query = "SELECT coupon_amount
                  FROM $couponstable
                  WHERE coupon_id = '" . oos_db_input($_SESSION['gv_id']) . "'";
		$coupon = $dbconn->GetRow($query);
		$gv_coupon_show = $oCurrencies->format($coupon['coupon_amount']);
	}
	$smarty->assign(
		array(
			'gv_amount_show' => $gv_amount_show,
			'gv_coupon_show' => $gv_coupon_show
		)
	);	
	
}


$products_unitstable = $oostable['products_units'];
$query = "SELECT products_units_id, products_unit_name
		FROM $products_unitstable
		WHERE languages_id = '" . intval($nLanguageID) . "'";
$products_units = $dbconn->GetAssoc($query);


// PAngV
$sPAngV = $aLang['text_taxt_incl'];
if ($aUser['show_price'] == 1) {
	if ($aUser['price_with_tax'] == 1) {
		$tax_plus_shipping = sprintf($aLang['text_incl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));
		$sPAngV = $aLang['text_taxt_incl'];	
	} else {
		$tax_plus_shipping = sprintf($aLang['text_excl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));		
		$sPAngV = $aLang['text_taxt_add'];
	}

	if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
		$tax_plus_shipping = sprintf($aLang['text_excl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));
		$sPAngV = $aLang['tax_info_excl'];
	}
}

$sPAngV .= sprintf($aLang['text_shipping'], oos_href_link($aContents['information'], 'information_id=5'));

$smarty->assign(
	array(
		'pangv' => $sPAngV,
		'tax_plus_shipping' => $tax_plus_shipping,
		'products_units'=> $products_units,
	)
);
