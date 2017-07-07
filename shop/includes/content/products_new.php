<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: products_new.php,v 1.2 2003/01/09 09:40:07 elarifr
   orig: products_new.php,v 1.24 2003/02/13 04:23:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// split-page-results
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_new.php';


$aTemplate['page'] = $sTheme . '/page/products_new.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

$nPage = isset($_GET['page']) ? $_GET['page']+0 : 1;
$sGroup = trim($_SESSION['user']->group['text']);
$nContentCacheID = $sTheme . '|products_new|' . $nPage. '|' . $sGroup . '|' . $sLanguage;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

if (!$smarty->isCached($aTemplate['page'], $nContentCacheID)) {
	$productstable  = $oostable['products'];
    $specialsstable = $oostable['specials'];
    $manufacturersstable = $oostable['manufacturers'];
    $products_descriptiontable = $oostable['products_description'];
    $products_new_result_raw = "SELECT p.products_id, pd.products_name, p.products_image, p.products_price,
                                       p.products_base_price, p.products_base_unit, p.products_units_id,
                                       p.products_discount_allowed, p.products_tax_class_id, p.products_units_id,
                                       IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                                       p.products_date_added, m.manufacturers_name
                               FROM $productstable p LEFT JOIN
                                    $manufacturersstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                                    $products_descriptiontable pd ON p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($nLanguageID) . "' LEFT JOIN
                                    $specialsstable s ON p.products_id = s.products_id
                               WHERE p.products_status >= '1'
                               ORDER BY p.products_date_added DESC, pd.products_name";
    $products_new_split = new splitPageResults($nPage, MAX_DISPLAY_PRODUCTS_NEW, $products_new_result_raw, $products_new_numrows);
    $products_new_result = $dbconn->Execute($products_new_result_raw);

    $products_new_array = array();
    while ($products_new = $products_new_result->fields) {

		$new_product_price = '';
		$new_product_special_price = '';
		$new_special_price = '';
		$new_product_discount_price = '';
		$new_base_product_price = '';
		$new_base_product_special_price = '';

		$new_product_units = UNITS_DELIMITER . $products_units[$products_new['products_units_id']];

		$new_product_price = $oCurrencies->display_price($products_new['products_price'], oos_get_tax_rate($products_new['products_tax_class_id']));
		if (isset($products_new['specials_new_products_price'])) {
			$new_special_price = $products_new['specials_new_products_price'];
			$new_product_special_price = $oCurrencies->display_price($new_special_price, oos_get_tax_rate($products_new['products_tax_class_id']));
		} 

		if ($products_new['products_base_price'] != 1) {
			$new_base_product_price = $oCurrencies->display_price($products_new['products_price'] * $products_new['products_base_price'], oos_get_tax_rate($products_new['products_tax_class_id']));

			if ($new_special_price != '') {
				$new_base_product_special_price = $oCurrencies->display_price($new_special_price * $products_new['products_base_price'], oos_get_tax_rate($products_new['products_tax_class_id']));
			}
		}
		$products_new_array[] = array(
									'id' => $products_new['products_id'],
									'name' => $products_new['products_name'],
                                    'image' => $products_new['products_image'],
                                    'new_product_price' => $new_product_price,
                                    'new_product_units' => $new_product_units,
                                    'new_product_special_price' => $new_product_special_price,
                                    'new_special_price' => $new_special_price,
                                    'new_product_discount_price' => $new_product_discount_price,
                                    'new_base_product_price' => $new_base_product_price,
                                    'new_base_product_special_price' => $new_base_product_special_price,
                                    'products_base_price' => $products_new['products_base_price'],
                                    'new_products_base_unit' => $products_new['products_base_unit'],
                                    'date_added' => $products_new['products_date_added'],
                                    'manufacturer' => $products_new['manufacturers_name']);
		$products_new_result->MoveNext();
	}

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));
	$sCanonical = oos_href_link($aContents['products_new'], 'page='. $nPage, 'NONSSL', FALSE, TRUE);
	
    // assign Smarty variables;
    $smarty->assign(
        array(
           'breadcrumb'         => $oBreadcrumb->trail(),
           'heading_title'      => $aLang['heading_title'],
		   'robots'				=> 'noindex,follow,noodp,noydir',
		   'canonical'			=> $sCanonical,

           'page_split'			=> $products_new_split->display_count($products_new_numrows, MAX_DISPLAY_PRODUCTS_NEW, $nPage, $aLang['text_display_number_of_products_new']),
           'display_links'		=> $products_new_split->display_links($products_new_numrows, MAX_DISPLAY_PRODUCTS_NEW, MAX_DISPLAY_PAGE_LINKS, $nPage, oos_get_all_get_parameters(array('page', 'info'))),
           'numrows'			=> $products_new_numrows,

           'products_image_box'     => SMALL_IMAGE_WIDTH + 10,
           'oos_products_new_array' => $products_new_array
		)
    );
}
$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination'], $nContentCacheID));

// display the template
$smarty->display($aTemplate['page']);
