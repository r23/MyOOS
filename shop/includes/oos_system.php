<?php
/* ----------------------------------------------------------------------
   $Id: oos_system.php 477 2013-07-14 21:57:50Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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
if ($debug == 'true') {
	$smarty->force_compile   = TRUE;
	$smarty->debugging       = TRUE;
	$smarty->clearAllCache();
	$smarty->clearCompiledTemplate();
}

// object register
$smarty->assignByRef("oEvent", $oEvent);


// cache_id
$oos_cache_id                   = $sTheme . '|block|' . $sLanguage;
$oos_system_cache_id            = $sTheme . '|block|' . $sLanguage;
$oos_categories_cache_id        = $sTheme . '|block|categories|' . $sLanguage . '|' . $category;
$oos_modules_cache_id           = $sTheme . '|modules|' . $sLanguage . '|' . $sCurrency;
$oos_news_cache_id              = $sTheme . '|modules|news|' . $sLanguage;

if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) {
	$nManufacturersId = intval($_GET['manufacturers_id']);
} else {
	$nManufacturersId  = 0;
}
$oos_manufacturers_cache_id     = $sTheme . '|block|manufacturers|' . $sLanguage . '|' . $nManufacturersId;
$oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . $nManufacturersId;

if (isset($_GET['products_id'])) {
	if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
	$oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . intval($nProductsId);
	$oos_products_info_cache_id     = $sTheme . '|products_info|' . $sLanguage . '|' . intval($nProductsId);
	$oos_xsell_products_cache_id    = $sTheme . '|block|products|' . $sLanguage . '|' . intval($nProductsId);
}


if (empty($oos_meta_description)) $oos_meta_description = OOS_META_DESCRIPTION;


$smarty->assign(
      array(
          'contents'            => $aContents,
          'content_file'        => $sContent,

		  'currency'			=> $sCurrency,
		  
          'request_type'        => $request_type,

          'theme_set'           => $sTheme,
          'theme_image'         => 'themes/' . $sTheme . '/images',
          'theme'               => 'themes/' . $sTheme,

          'lang'                => $aLang,
          'language'            => $sLanguage,

          'meta_description'    => htmlspecialchars($oos_meta_description)
      )
);

$smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);

$cart_count_contents = 0;
$cart_show_total = 0;

if (isset($_SESSION)) {
   
    $sFormid = md5(uniqid(rand(), true));
    $_SESSION['formid'] = $sFormid;

	if (is_object($_SESSION['cart'])) {
		$smarty->registerObject("cart", $_SESSION['cart'],array('count_contents', 'get_products')); 

		$cart_count_contents = $_SESSION['cart']->count_contents();
		$cart_show_total = $oCurrencies->format($_SESSION['cart']->show_total()); 
	}
	$smarty->assign(
		array(
			'formid'            => $sFormid,

			'oos_session_name'  => oos_session_name(),
			'oos_session_id'    => oos_session_id()

        )
    );
}

$smarty->assign(
    array(
		'cart_show_total'     => $cart_show_total,
		'cart_count_contents' => $cart_count_contents
    )
);


$products_unitstable = $oostable['products_units'];
$query = "SELECT products_units_id, products_unit_name
          FROM $products_unitstable
          WHERE languages_id = '" . intval($nLanguageID) . "'";
$products_units = $dbconn->GetAssoc($query);

// PAngV
$sPAngV = $aLang['text_taxt_incl'];
if ($_SESSION['member']->group['show_price'] == 1) {
    if ($_SESSION['member']->group['show_price_tax'] == 1) {
		$sPAngV = $aLang['text_taxt_incl'];
    } else {
		$sPAngV = $aLang['text_taxt_add'];
    }

    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
		$sPAngV = $aLang['tax_info_excl'];
    }
}

$sPAngV .= ', <br />';
$sPAngV .= sprintf($aLang['text_shipping'], oos_href_link($aContents['information'], 'information_id=2'));

  
$smarty->assign(
      array(
          'pangv'               => $sPAngV,
          'products_units'      => $products_units,
      )
);