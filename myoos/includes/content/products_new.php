<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// split-page-results
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_new.php';


$aTemplate['page'] = $sTheme . '/page/products_new.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$sGroup = trim((string) $aUser['text']);
$nContentCacheID = $sTheme . '|products_new|' . $nPage. '|' . $sGroup . '|' . $sLanguage;

$sCanonical = oos_href_link($aContents['products_new'], 'page='. $nPage, false, true);

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}



if (!$smarty->isCached($aTemplate['page'], $nContentCacheID)) {
    $config = HTMLPurifier_Config::createDefault();
    $purifier = new HTMLPurifier($config);


    $productstable  = $oostable['products'];
    $specialsstable = $oostable['specials'];
    $manufacturersstable = $oostable['manufacturers'];
    $products_descriptiontable = $oostable['products_description'];
    $products_new_result_raw = "SELECT p.products_id, pd.products_name, p.products_image, p.products_price, p.products_price_list,
                                       p.products_base_price, p.products_base_unit, p.products_units_id,
										p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, 
										p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty,									   
									   p.products_product_quantity,  p.products_quantity_order_min, 
										p.products_quantity_order_max, p.products_quantity_order_units, p.products_tax_class_id, 
										left(pd.products_short_description, 230) AS products_short_description,
                                       IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
										IF(s.status, s.specials_cross_out_price, null) AS specials_cross_out_price,			   
										IF(s.status, s.expires_date, null) AS expires_date,										   
                                       p.products_date_added, p.manufacturers_id, m.manufacturers_name
                               FROM $productstable p LEFT JOIN
                                    $manufacturersstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                                    $products_descriptiontable pd ON p.products_id = pd.products_id AND 
									pd.products_languages_id = '" . intval($nLanguageID) . "' LEFT JOIN
                                    $specialsstable s ON p.products_id = s.products_id
                               WHERE p.products_setting = '2'
                               ORDER BY p.products_date_added DESC, pd.products_name";
    $products_new_split = new splitPageResults($products_new_result_raw, MAX_DISPLAY_PRODUCTS_NEW);
    $products_new_result = $dbconn->Execute($products_new_split->sql_query);

    $products_new_array = [];
    while ($products_new = $products_new_result->fields) {
        $discount = null;

        $new_product_price = null;
        $new_product_price_list = null;
        $new_product_special_price = null;
        $new_product_discount_price = null;
        $new_base_product_price = null;
        $products_new_until = null;
        $products_new_cross_out_price = null;
        $products_new_base_product_price = null;
        $base_product_price = $products_new['products_price'];

        if ($aUser['show_price'] == 1) {
            $new_product_price = $oCurrencies->display_price($products_new['products_price'], oos_get_tax_rate($products_new['products_tax_class_id']));
            if ($products_new['products_price_list'] > 0) {
                $new_product_price_list = $oCurrencies->display_price($products_new['products_price_list'], oos_get_tax_rate($products_new['products_tax_class_id']));
            }

            if ($products_new['products_discount4'] > 0) {
                $discount = $products_new['products_discount4'];
            } elseif ($products_new['products_discount3'] > 0) {
                $discount = $products_new['products_discount3'];
            } elseif ($products_new['products_discount2'] > 0) {
                $discount = $products_new['products_discount2'];
            } elseif ($products_new['products_discount1'] > 0) {
                $discount = $products_new['products_discount1'];
            }

            if ($discount > 0) {
                $base_product_price = $discount;
                $products_new_discount_price = $oCurrencies->display_price($discount, oos_get_tax_rate($products_new['products_tax_class_id']));
            }

            if ($products_new['specials_new_products_price'] > 0) {
                $base_product_price = $products_new['specials_new_products_price'];
                $new_product_special_price = $oCurrencies->display_price($products_new['specials_new_products_price'], oos_get_tax_rate($products_new['products_tax_class_id']));

                if ($products_new['specials_cross_out_price'] > 0) {
                    $products_new_cross_out_price = $oCurrencies->display_price($products_new['specials_cross_out_price'], oos_get_tax_rate($products_new['products_tax_class_id']));
                }

                $products_new_until = sprintf($aLang['only_until'], oos_date_short($products_new['expires_date']));
            }

            if ($products_new['products_base_price'] != 1) {
                $products_new_base_product_price = $oCurrencies->display_price($base_product_price * $products_new['products_base_price'], oos_get_tax_rate($products_new['products_tax_class_id']));
            }
        }

        $order_min = number_format($products_new['products_quantity_order_min']);
        $order_max = number_format($products_new['products_quantity_order_max']);

        $products_short_description = $purifier->purify($products_new['products_short_description']);

        $products_new_array[] = array(
                                    'id' => $products_new['products_id'],
                                    'name' => $products_new['products_name'],
                                    'image' => $products_new['products_image'],
                                    'products_short_description' => $products_short_description,
                                    'new_product_price' => $new_product_price,
                                    'new_product_price_list' => $new_product_price_list,
                                    'new_product_units' => $products_new['products_units_id'],
                                    'new_product_quantity' => $products_new['products_product_quantity'],
                                    'order_min' => $order_min,
                                    'order_max' => $order_max,
                                    'new_product_special_price' => $new_product_special_price,
                                    'new_product_discount_price' => $new_product_discount_price,
                                    'new_base_product_price' => $new_base_product_price,
                                    'listing_until' => $products_new_until,
                                    'products_new_base_product_price' => $products_new_base_product_price,
                                    'products_new_cross_out_price'    => $products_new_cross_out_price,
                                    'products_base_price' => $products_new['products_base_price'],
                                    'new_products_base_unit' => $products_new['products_base_unit'],
                                    'date_added' => $products_new['products_date_added'],
                                    'manufacturers_id' => $products_new['manufacturers_id'],
                                    'manufacturer' => $products_new['manufacturers_name']);
        $products_new_result->MoveNext();
    }

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));


    // assign Smarty variables;
    $smarty->assign(
        array(
           'breadcrumb'         => $oBreadcrumb->trail(),
           'heading_title'      => $aLang['heading_title'],
           'robots'             => 'noindex,follow,noodp,noydir',
           'canonical'          => $sCanonical,

           'page_split'         => $products_new_split->display_count($aLang['text_display_number_of_products_new']),
           'display_links'      => $products_new_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
            'numrows'           => $products_new_split->number_of_rows,
            'numpages'          => $products_new_split->number_of_pages,

            'page'              => $nPage,
           'products_new'       => $products_new_array
        )
    );
}

$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination'], $nContentCacheID));

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
