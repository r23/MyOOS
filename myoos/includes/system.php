<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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

//smarty
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
$smarty = new myOOS_Smarty();

//debug
if ($debug == 1) {
    $smarty->force_compile   = true;
    $smarty->debugging       = true;
    $smarty->clearAllCache();
    $smarty->clearCompiledTemplate();
}

// object register
$smarty->assignByRef("oEvent", $oEvent);
$smarty->assignByRef("oNavMenu", $oNavMenu);


// Create a nonce RANDOM_VALUE
$nonce = bin2hex(random_bytes(16));
define('NONCE', $nonce);


// cache_id
$sCacheID            = $sTheme . '|block|' . $sLanguage;
$sSystemCacheID        = $sTheme . '|block|' . $sLanguage;
$sCategoriesCacheID    = $sTheme . '|block|categories|' . $sLanguage . '|' . $sCategory;
$sModulesCacheID    = $sTheme . '|modules|' . $sLanguage . '|' . $sCurrency;


$nManufacturersID = filter_input(INPUT_GET, 'manufacturers_id', FILTER_VALIDATE_INT) ?: 0;

$sManufacturersCacheID = $sTheme . '|block|manufacturers|' . $sLanguage . '|' . $nManufacturersID;
$sManufacturersInfoCacheID = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . $nManufacturersID;

if (isset($_GET['products_id'])) {
    $sProductsId = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
    $nProductsID = oos_get_product_id($sProductsId);
    $sManufacturersInfoCacheID = $sTheme . '|block|manufacturer_info|' . $sLanguage . '|' . intval($nProductsID);
    $sProductsInfoCacheID = $sTheme . '|products_info|' . $sLanguage . '|' . intval($nProductsID);
    $sXsellProductsCacheID = $sTheme . '|block|products|' . $sLanguage . '|' . intval($nProductsID);
}

// Meta-Tags
$locale = locale($sLanguageCode);
if (empty($sPagetitle)) {
    $sPagetitle = OOS_META_TITLE;
}
if (empty($facebook_title)) {
    $facebook_title = $sPagetitle;
}
if (empty($twitter_title)) {
    $twitter_title = $facebook_title;
}

if (empty($sDescription)) {
    $sDescription = OOS_META_DESCRIPTION;
}
if (empty($facebook_description)) {
    $facebook_description = $sDescription;
}
if (empty($twitter_description)) {
    $twitter_description = $facebook_description;
}

if (empty($twitter_card)) {
    $twitter_card = TWITTER_CARD;
}

$site_name = (!empty(SITE_NAME) ? SITE_NAME : STORE_NAME);

if (!empty(OPEN_GRAPH_THUMBNAIL)) {
    if (empty($og_image)) {
        $og_image = OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'og/1200x630/' . OPEN_GRAPH_THUMBNAIL;
    }
}

if (!empty($og_image)) {
    $size = @getimagesize($og_image);
    $smarty->assign('og_image', $og_image);
    $smarty->assign('size', $size);
}

$part = [
            '@context'         => 'http://schema.org',
            'headline'         => $sPagetitle,
            'name'             => $sPagetitle,
            'description'      => $sDescription,
            'url'              => $sCanonical,
            'mainEntityOfPage' => $sCanonical,
            'image'            => $og_image,
        ];


$smarty->assign(
    ['filename'        => $aContents, 'page_file'        => $sContent, 'theme_set'        => $sTheme, 'theme_image'    => 'themes/' . $sTheme . '/images', 'theme'            => 'themes/' . $sTheme, 'lang'                => $aLang, 'language'            => $sLanguage, 'language_id'        => $nLanguageID, 'content_language'    => $sLanguageCode, 'language_name'        => $sLanguageName, 'currency'            => $sCurrency, 'locale'            => $locale, 'pagetitle'            => $sPagetitle, 'facebook_title'    => $facebook_title, 'site_name'            => $site_name, 'twitter_title'        => $twitter_title, 'twitter_card'        => $twitter_card, 'meta_description'    => $sDescription, 'facebook_description'    => $facebook_description, 'twitter_description'    => $twitter_description, 'oos_css'            => $oos_css, 'oos_js'            => $oos_js, 'part'                => $part]
);


$smarty->assign('oos_base', OOS_HTTPS_SERVER . OOS_SHOP);

$sNavMenue = $oNavMenu->build();
$smarty->assign('nav_menu', $sNavMenue);

$cart_products = [];
$cart_count_contents = 0;
$cart_show_subtotal = 0;
$cart_show_total = 0;
$wishlist_count_contents = 0;


$aSystem = [];

if (isset($_SESSION)) {
    $sFormid = md5(uniqid(random_int(0, mt_getrandmax()), true));
    $_SESSION['formid'] = $sFormid;

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }


    $aSystem = ['sed'    => true, 'formid' => $sFormid, 'session_name' => $session->getName(), 'session_id' => $session->getId()];


    if (is_object($_SESSION['cart'])) {
        $smarty->registerObject("cart", $_SESSION['cart'], ['count_contents', 'get_products']);

        $cart_count_contents = $_SESSION['cart']->count_contents();
        $cart_products = $_SESSION['cart']->get_products();
        $cart_show_subtotal = $oCurrencies->format($_SESSION['cart']->info['subtotal']);
        $cart_show_total = $oCurrencies->format($_SESSION['cart']->info['total']);
    }

    // counter for wishlist
    $customers_wishlisttable = $oostable['customers_wishlist'];
    $wishlist_count_result = $dbconn->Execute(
        "SELECT COUNT(*) AS total 
		FROM $customers_wishlisttable
		WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
		AND customers_wishlist_link_id = '" . oos_db_input($_SESSION['customer_wishlist_link_id']) . "'"
    );
    $wishlist_count = $wishlist_count_result->fields;
    $wishlist_count_contents = $wishlist_count['total'];
}

$smarty->assign(
    ['mySystem'                  => $aSystem, 'myUser'                    => $aUser, 'cart_products'             => $cart_products, 'cart_show_subtotal'        => $cart_show_subtotal, 'cart_show_total'            => $cart_show_total, 'cart_count_contents'        => $cart_count_contents, 'wishlist_count_contents'    => $wishlist_count_contents]
);

/* -----------shopping_cart.php--------------------------------------- */

if (isset($_SESSION)) {
    $gv_amount_show = 0;

    if (isset($_SESSION['customer_id'])) {
        if (isset($_SESSION['coupon_amount']) && is_numeric($_SESSION['coupon_amount'])) {
            $gv_amount_show = $oCurrencies->format($_SESSION['coupon_amount']);
        }
    }
    $smarty->assign('gv_amount_show', $gv_amount_show);

    /*
       if (isset($_SESSION['gv_id'])) {
           $couponstable = $oostable['coupons'];
           $query = "SELECT coupon_amount
                     FROM $couponstable
                     WHERE coupon_id = '" . oos_db_input($_SESSION['gv_id']) . "'";
           $coupon = $dbconn->GetRow($query);
           $gv_coupon_show = $oCurrencies->format($coupon['coupon_amount']);
       }
    */
}


// Minimum Order Value
if (defined('MINIMUM_ORDER_VALUE') && oos_is_not_null(MINIMUM_ORDER_VALUE)) {
    $minimum_order_value = str_replace(',', '.', (string) MINIMUM_ORDER_VALUE);
    $sMinimumOrder = sprintf($aLang['text_info_minimum_order_value'], $oCurrencies->format($minimum_order_value));
    $smarty->assign('info_minimum_order_value', $sMinimumOrder);
}


$products_unitstable = $oostable['products_units'];
$query = "SELECT products_units_id, products_unit_name, unit_of_measure
		FROM $products_unitstable
		WHERE languages_id = '" . intval($nLanguageID) . "'";
$products_unit_result = $dbconn->Execute($query);
$products_units = []; // initialize a new array
while ($products_unit = $products_unit_result->fields) {
    // get the products_units_id as the index for the new array
    $index = $products_unit['products_units_id'];
    // insert the products_unit_name and unit_of_measure as a numeric array under the index
    $products_units[$index] = [$products_unit['products_unit_name'], $products_unit['unit_of_measure']];
    // Move that ADOdb pointer!
    $products_unit_result->MoveNext();
}


// PAngV
$sPAngV = $aLang['text_tax_incl'];
if ($aUser['show_price'] == 1) {
    if ($aUser['price_with_tax'] == 1) {
        $tax_plus_shipping = sprintf($aLang['text_incl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));
        $sPAngV = $aLang['text_tax_incl'];
    } else {
        $tax_plus_shipping = sprintf($aLang['text_excl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));
        $sPAngV = $aLang['text_tax_add'];
    }

    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
        $tax_plus_shipping = sprintf($aLang['text_excl_tax_plus_shipping'], oos_href_link($aContents['information'], 'information_id=5'));
        $sPAngV = $aLang['tax_info_excl'];
    }
}

$sPAngV .= sprintf($aLang['text_shipping'], oos_href_link($aContents['information'], 'information_id=5'));

$smarty->assign(
    ['pangv' => $sPAngV, 'tax_plus_shipping' => $tax_plus_shipping, 'products_units' => $products_units]
);


// cookie-notice
$hideEffect = isset($hideEffect) ? oos_prepare_input($hideEffect) : 'none';
$aCookie = [];
$aCookie = ['hideEffect'            => $hideEffect, 'onScroll'                => 'no', 'onScrollOffset'        => '100', 'cookieName'            => 'cookie_notice_accepted', 'cookieValue'            => 'true', 'cookieTime'            => '31536000', 'cookiePath'            => '', 'cookieDomain'            => '', 'redirection'            => '', 'cache'                    => '', 'refuse'                => 'no', 'revoke_cookies'        => '0', 'revoke_cookies_opt'    => 'automatic', 'secure'                => '0'];
$smarty->assign('cookiearray', $aCookie);

/* remove $_COOKIE
if (isset($_COOKIE)) {

    reset($_COOKIE);

    $params = session_get_cookie_params();
    unset($params['lifetime']);

    foreach ($_COOKIE as $name => $value) {
        setcookie($name, '', $params);
    }
}
*/
