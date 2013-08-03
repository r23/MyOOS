<?php
/* ----------------------------------------------------------------------
   $Id: oos_system.php 477 2013-07-14 21:57:50Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
$smarty = new myOOS_Smarty;


//debug
if ($debug == 'true')
{
	$smarty->force_compile   = true;
	$smarty->debugging       = true;
	$smarty->clearAllCache();
	$smarty->clearCompiledTemplate();
}

// object register
$smarty->registerObject("cart", $_SESSION['cart'],array('count_contents', 'get_products'));
$smarty->assignByRef("oEvent", $oEvent);


// cache_id
$oos_cache_id                   = $sTheme . '|block|' . $sLanguage;
$oos_system_cache_id            = $sTheme . '|block|' . $sLanguage;
$oos_categories_cache_id        = $sTheme . '|block|categories|' . $sLanguage . '|' . $category;
$oos_modules_cache_id           = $sTheme . '|modules|' . $sLanguage . '|' . $_SESSION['currency'];
$oos_news_cache_id              = $sTheme . '|modules|news|' . $sLanguage;

if (isset($_GET['manufacturers_id']) && is_numeric($_GET['manufacturers_id'])) 
{
	$nManufacturersId = intval($_GET['manufacturers_id']);
}
else
{
	$nManufacturersId  = 0;
}
$oos_manufacturers_cache_id     = $sTheme . '|block|manufacturers|' . $sLanguage . '|' . $nManufacturersId;
$oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . $nManufacturersId;

if (isset($_GET['products_id']))
{
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
    $oos_manufacturer_info_cache_id = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . intval($nProductsId);
    $oos_products_info_cache_id     = $sTheme . '|products_info|' . $sLanguage . '|' . intval($nProductsId);
    $oos_xsell_products_cache_id    = $sTheme . '|block|products|' . $sLanguage . '|' . intval($nProductsId);
}


// Meta-Tags
if (empty($oos_pagetitle)) $oos_pagetitle = OOS_META_TITLE;
if (empty($oos_meta_description)) $oos_meta_description = OOS_META_DESCRIPTION;


$sFormid = md5(uniqid(rand(), true));
$_SESSION['formid'] = $sFormid;

$cart_count_contents = $_SESSION['cart']->count_contents();
$cart_show_total = $oCurrencies->format($_SESSION['cart']->show_total());

$smarty->assign(
      array(
          'contents'               => $aContents,
          'content_file'           => $sContent,

          'formid'              => $sFormid,

          'request_type'        => $request_type,

          'cart_show_total'     => $cart_show_total,
          'cart_count_contents' => $cart_count_contents,

          'theme_set'         => $sTheme,
          'theme_image'       => 'themes/' . $sTheme . '/images',
          'theme'         		=> 'themes/' . $sTheme,

          'lang'              => $aLang,
          'language'          => $sLanguage,

          'pangv'             => $sPAngV,
          'products_units'    => $products_units,

          'oos_session_name'  => oos_session_name(),
          'oos_session_id'    => oos_session_id(),

          'pagetitle'           => htmlspecialchars($oos_pagetitle),
          'meta_description'    => htmlspecialchars($oos_meta_description)
      )
);

$smarty->assign('oos_base', (($request_type == 'SSL') ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP);

