<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.46 2003/02/13 04:23:23 hpdl
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

if (!$oEvent->installed_plugin('specials')) {
    oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_specials.php';

$aTemplate['page'] = $sTheme . '/page/specials.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

$sGroup = trim((string) $aUser['text']);
$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);
$nContentCacheID = $sTheme . '|info|' . $sGroup . '|specials|' . $nPage . '|' . $sLanguage;

$sCanonical = oos_href_link($aContents['specials'], 'page='. $nPage, false, true);


require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

if (!$smarty->isCached($aTemplate['page'], $nContentCacheID)) {
    $productstable = $oostable['products'];
    $specialstable = $oostable['specials'];
    $products_descriptiontable = $oostable['products_description'];
    $specials_result_raw = "SELECT p.products_id, pd.products_name,  pd.products_short_description, p.products_image, p.products_price,
                                   p.products_base_price, p.products_base_unit, p.products_tax_class_id, p.products_units_id,
									p.products_quantity_order_min, p.products_quantity_order_max, p.products_product_quantity,
								   p.products_image, s.specials_new_products_price, s.specials_cross_out_price, s.expires_date 
                            FROM $productstable p,
                                 $products_descriptiontable pd,
                                 $specialstable s
                           WHERE p.products_setting = '2'
                             AND s.products_id = p.products_id
                             AND p.products_id = pd.products_id
                             AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                             AND s.status = '1'
                           ORDER BY s.specials_date_added DESC";
    $specials_split = new splitPageResults($specials_result_raw, MAX_DISPLAY_SPECIAL_PRODUCTS);
    $specials_result = $dbconn->Execute($specials_split->sql_query);

    $aSpecials = [];
    while ($specials = $specials_result->fields) {
        $specials_special_price = null;
        $specials_base_product_price = null;
        $specials_base_product_special_price = null;
        $specials_cross_out_price = null;
        $specials_price_list = null;
        $specials_until = null;

        if ($aUser['show_price'] == 1) {
            $base_product_price = $specials['specials_new_products_price'];
            $specials_special_price = $oCurrencies->display_price($specials['specials_new_products_price'], oos_get_tax_rate($specials['products_tax_class_id']));

            if ($specials['products_price_list'] > 0) {
                $specials_price_list = $oCurrencies->display_price($specials['products_price_list'], oos_get_tax_rate($specials['products_tax_class_id']));
            }

            if ($specials['specials_cross_out_price'] > 0) {
                $specials_cross_out_price = $oCurrencies->display_price($specials['specials_cross_out_price'], oos_get_tax_rate($specials['products_tax_class_id']));
            }

            $specials_until = sprintf($aLang['only_until'], oos_date_short($specials['expires_date']));

            if ($specials['products_base_price'] != 1) {
                $specials_base_product_price = $oCurrencies->display_price($base_product_price * $specials['products_base_price'], oos_get_tax_rate($specials['products_tax_class_id']));
            }
        }



        $aSpecials[] = array(
                         'products_id'                => $specials['products_id'],
                         'products_image'             => $specials['products_image'],
                         'products_name'              => $specials['products_name'],
                         'products_description'       => $specials['products_description'],
                         'products_base_unit'         => $specials['products_base_unit'],
                         'products_base_price'        => $specials['products_base_price'],
                         'products_units'                => $specials['products_units_id'],
                         'product_quantity'                => $specials['products_product_quantity'],
                         'specials_product_special_price'    => $specials_special_price,
                         'specials_base_product_price'        => $specials_base_product_price,
                         'specials_cross_out_price'            => $specials_cross_out_price,
                         'specials_product_price_list'        => $specials_price_list,
                         'specials_until'                    => $specials_until,
                         'base_product_special_price'        => $specials_base_product_special_price
                     );
        $specials_result->MoveNext();
    }

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['specials']));

    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'        => $oBreadcrumb->trail(),
            'heading_title'        => $aLang['heading_title'],
            'canonical'            => $sCanonical,

            'page_split'        => $specials_split->display_count($aLang['text_display_number_of_specials']),
            'display_links'        => $specials_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
            'numrows'             => $specials_split->number_of_rows,
            'numpages'             => $specials_split->number_of_pages,

            'page'                => $nPage,
            'specials'            => $aSpecials
        )
    );
}

$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination'], $nContentCacheID));

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
