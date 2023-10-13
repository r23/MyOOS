<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search_result.php,v 1.67 2003/02/13 04:23:22 hpdl
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

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_search.php';
require 'includes/languages/' . $sLanguage . '/search_advanced_result.php';

$get_parameters = '';
$keywords = filter_string_polyfill(filter_input(INPUT_GET, 'keywords'));
$get_parameters .= '&keywords=' . $keywords;


$search_in_description = filter_input(INPUT_GET, 'search_in_description', FILTER_VALIDATE_INT) ?: 0; 
$get_parameters .= '&search_in_description=' . $search_in_description;


$categories_id = filter_input(INPUT_GET, 'categories_id', FILTER_VALIDATE_INT);
$get_parameters .= '&categories_id=' . $categories_id;


$inc_subcat = filter_input(INPUT_GET, 'inc_subcat', FILTER_VALIDATE_INT) ?: 0; 
$get_parameters .= '&inc_subcat=' . $inc_subcat;


$manufacturers_id = filter_input(INPUT_GET, 'manufacturers_id', FILTER_VALIDATE_INT);
$get_parameters .= '&manufacturers_id=' . $manufacturers_id;


$pfrom = filter_string_polyfill(filter_input(INPUT_GET, 'pfrom'));
$pfrom = str_replace(',', '.', (string) $pfrom);
$pfrom = floatval($pfrom);
$get_parameters .= '&pfrom=' . $pfrom;


$pto = filter_string_polyfill(filter_input(INPUT_GET, 'pto'));
$pto = str_replace(',', '.', (string) $pto);
$pto = floatval($pto);
$get_parameters .= '&pto=' . $pto;


$dfrom = filter_string_polyfill(filter_input(INPUT_GET, 'dfrom'));
$get_parameters .= '&dfrom=' . $dfrom;


$dto = filter_string_polyfill(filter_input(INPUT_GET, 'dto'));
$get_parameters .= '&dto=' . $dto;

$sort = filter_string_polyfill(filter_input(INPUT_GET, 'sort'));

$errorno = 0;


$dfrom_to_check = (($dfrom == DOB_FORMAT_STRING) ? '' : $dfrom);
$dto_to_check = (($dto == DOB_FORMAT_STRING) ? '' : $dto);

if (strlen($dfrom_to_check ?? '') > 0) {
    if (!oos_checkdate($dfrom_to_check, DOB_FORMAT_STRING, $dfrom_array)) {
        $errorno += 10;
    }
}

if (strlen($dto_to_check ?? '') > 0) {
    if (!oos_checkdate($dto_to_check, DOB_FORMAT_STRING, $dto_array)) {
        $errorno += 100;
    }
}

if (strlen($dfrom_to_check ?? '') > 0 && !(($errorno & 10) == 10) && strlen((string) $dto_to_check) > 0 && !(($errorno & 100) == 100)) {
    if (mktime(0, 0, 0, $dfrom_array[1], $dfrom_array[2], $dfrom_array[0]) > mktime(0, 0, 0, $dto_array[1], $dto_array[2], $dto_array[0])) {
        $errorno += 1000;
    }
}

if (strlen($pfrom ?? '') > 0) {
    $pfrom_to_check = oos_var_prep_for_os($pfrom);
    if (!settype($pfrom_to_check, "double")) {
        $errorno += 10000;
    }
}

if (strlen($pto ?? '') > 0) {
    $pto_to_check = oos_var_prep_for_os($pto);
    if (!settype($pto_to_check, "double")) {
        $errorno += 100000;
    }
}

if (strlen($pfrom ?? '') > 0 && !(($errorno & 10000) == 10000) && strlen($pto) > 0 && !(($errorno & 100000) == 100000)) {
    if ($pfrom_to_check > $pto_to_check) {
        $errorno += 1_000_000;
    }
}

$search_keywords = &oos_parse_search_string($keywords);

if ($keywords && !$search_keywords) {
    $errorno += 10_000_000;
}


if ($errorno > 0) {
    oos_redirect(oos_href_link($aContents['advanced_search'], 'errorno=' . $errorno . $get_parameters));
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title1'], oos_href_link($aContents['advanced_search']));
$oBreadcrumb->add($aLang['navbar_title2']);

// create column list
$define_list = ['PRODUCT_LIST_MODEL' => '1', 'PRODUCT_LIST_NAME' => '2', 'PRODUCT_LIST_MANUFACTURER' => '3', 'PRODUCT_LIST_UVP' => '4', 'PRODUCT_LIST_PRICE' => '5', 'PRODUCT_LIST_QUANTITY' => '6', 'PRODUCT_LIST_WEIGHT' => '7', 'PRODUCT_LIST_IMAGE' => '8', 'PRODUCT_LIST_BUY_NOW' => '9'];
asort($define_list);

$column_list = [];
reset($define_list);
foreach ($define_list as $column => $value) {
    if ($value) {
        $column_list[] = $column;
    }
}

$select_column_list = '';

for ($col=0, $n=count($column_list); $col<$n; $col++) {
    if (($column_list[$col] == 'PRODUCT_LIST_BUY_NOW')
        || ($column_list[$col] == 'PRODUCT_LIST_NAME')
        || ($column_list[$col] == 'PRODUCT_LIST_PRICE')
    ) {
        continue;
    }

    if (oos_is_not_null($select_column_list)) {
        $select_column_list .= ', ';
    }

    match ($column_list[$col]) {
        'PRODUCT_LIST_MODEL' => $select_column_list .= 'p.products_model',
        'PRODUCT_LIST_MANUFACTURER' => $select_column_list .= 'm.manufacturers_name',
        'PRODUCT_LIST_QUANTITY' => $select_column_list .= 'p.products_quantity',
        'PRODUCT_LIST_IMAGE' => $select_column_list .= 'p.products_image',
        'PRODUCT_LIST_WEIGHT' => $select_column_list .= 'p.products_weight',
        default => $select_column_list .= "pd.products_name",
    };
}

if (oos_is_not_null($select_column_list)) {
    $select_column_list .= ', ';
}

$select_str = "SELECT DISTINCT " . $select_column_list . " m.manufacturers_id, p.products_id, p.products_replacement_product_id, pd.products_name,
                          p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                          p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                          p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_quantity_order_min, p.products_quantity_order_max,
                          p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_product_quantity,
                          IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
						IF(s.status, s.specials_cross_out_price, null) AS specials_cross_out_price,			   
						IF(s.status, s.expires_date, null) AS expires_date,							  
                          IF(s.status, s.specials_new_products_price, s.specials_cross_out_price) AS final_price ";

if (($aUser['price_with_tax'] == 1) && ((isset($_GET['pfrom']) && oos_is_not_null($_GET['pfrom'])) || (isset($pto) && oos_is_not_null($pto)))) {
    $select_str .= ", SUM(tr.tax_rate) AS tax_rate ";
}

$from_str = "FROM " . $oostable['products'] . " p LEFT JOIN
                      " . $oostable['manufacturers'] . " m using(manufacturers_id) LEFT JOIN
                      " . $oostable['specials'] . " s ON p.products_id = s.products_id";

if (($aUser['price_with_tax'] == 1) && ((isset($_GET['pfrom']) && oos_is_not_null($_GET['pfrom'])) || (isset($pto) && oos_is_not_null($pto)))) {
    $nCountry_id = STORE_COUNTRY;
    $nZone_id = STORE_ZONE;
    if (isset($_SESSION)) {
        if (isset($_SESSION['customer_country_id'])) {
            $nCountry_id = intval($_SESSION['customer_country_id']);
            $nZone_id = intval($_SESSION['customer_zone_id']);
        }
    }

    $from_str .= " LEFT JOIN
                        " . $oostable['tax_rates'] . " tr
                     ON p.products_tax_class_id = tr.tax_class_id LEFT JOIN
                        " . $oostable['zones_to_geo_zones'] . " gz
                     ON tr.tax_zone_id = gz.geo_zone_id AND
                        (gz.zone_country_id is null OR
                         gz.zone_country_id = '0' OR
                         gz.zone_country_id = '" . intval($nCountry_id) . "') AND
                        (gz.zone_id is null OR
                         gz.zone_id = '0' OR
                         gz.zone_id = '" . intval($nZone_id) . "')";
}

$from_str .= ", " . $oostable['products_description'] . " pd, " . $oostable['categories'] . " c, " . $oostable['products_to_categories'] . " p2c";

$where_str = " WHERE
                      p.products_setting = '2' AND
                      p.products_id = pd.products_id AND
                      pd.products_languages_id = '" .  intval($nLanguageID) . "' AND
                      p.products_id = p2c.products_id AND
                      p2c.categories_id = c.categories_id ";

if (isset($categories_id) && is_numeric($categories_id)) {
    if ($_GET['inc_subcat'] == '1') {
        $subcategories_array = [];
        oos_get_subcategories($subcategories_array, $categories_id);
        $where_str .= " AND
                           p2c.products_id = p.products_id AND
                           p2c.products_id = pd.products_id AND
                           (p2c.categories_id = '" . intval($categories_id) . "'";
		$n = is_countable($subcategories_array) ? count($subcategories_array) : 0;				   
        for ($i=0, $n; $i<$n; $i++) {
            $where_str .= " OR p2c.categories_id = '" . intval($subcategories_array[$i]) . "'";
        }
        $where_str .= ")";
    } else {
        $where_str .= " AND
                           p2c.products_id = p.products_id AND
                           p2c.products_id = pd.products_id AND
                           pd.products_languages_id = '" .  intval($nLanguageID) . "' AND
                           p2c.categories_id = '" . intval($categories_id) . "'";
    }
}

if (isset($manufacturers_id) && is_numeric($manufacturers_id)) {
    $where_str .= " AND m.manufacturers_id = '" . intval($manufacturers_id) . "'";
}



if (isset($search_keywords) && ((is_countable($search_keywords) ? count($search_keywords) : 0) > 0)) {
    $where_str .= " AND (";
	$n = is_countable($search_keywords) ? count($search_keywords) : 0;
    for ($i=0, $n; $i<$n; $i++) {
        switch ($search_keywords[$i]) {
        case '(':
        case ')':
        case 'and':
        case 'or':
            $where_str .= " " . $search_keywords[$i] . " ";
            break;

        default:
            $keyword = oos_db_prepare_input($search_keywords[$i]);
            $where_str .= "   (pd.products_name LIKE '%" . oos_db_input($keyword) . "%'
								OR p.products_model LIKE '%" . oos_db_input($keyword) . "%'
								OR p.products_ean LIKE '%" . oos_db_input($keyword) . "%'
								OR m.manufacturers_name LIKE '%" . oos_db_input($keyword) . "%'";
            if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) {
                $where_str .= " OR pd.products_short_description LIKE '%" . oos_db_input($keyword) . "%'";
            }
            if (isset($_GET['search_in_description']) && ($_GET['search_in_description'] == '1')) {
                $where_str .= " OR pd.products_description LIKE '%" . oos_db_input($keyword) . "%'";
            }
            $where_str .= ')';
            break;
        }
    }
    $where_str .= " )";
}

if (isset($dfrom) && oos_is_not_null($dfrom) && ($dfrom != DOB_FORMAT_STRING)) {
    $where_str .= " AND p.products_date_added >= '" . oos_date_raw($dfrom_to_check) . "'";
}

if (isset($dto) && oos_is_not_null($dto) && ($dto != DOB_FORMAT_STRING)) {
    $where_str .= " AND p.products_date_added <= '" . oos_date_raw($dto_to_check) . "'";
}

$rate = $oCurrencies->get_value($sCurrency);
if ($rate) {
    $pfrom = $pfrom / $rate;
    $pto = $pto / $rate;
}

if ($aUser['price_with_tax'] == 1) {
    if ($pfrom) {
        $where_str .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) >= " . oos_db_input($pfrom) . ")";
    }
    if ($pto) {
        $where_str .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) * if(gz.geo_zone_id is null, 1, 1 + (tr.tax_rate / 100) ) <= " . oos_db_input($pto) . ")";
    }
} else {
    if ($pfrom) {
        $where_str .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) >= " . oos_db_input($pfrom) . ")";
    }
    if ($pto) {
        $where_str .= " AND (IF(s.status, s.specials_new_products_price, p.products_price) <= " . oos_db_input($pto) . ")";
    }
}

if (($aUser['price_with_tax'] == 1) && ((isset($_GET['pfrom']) && oos_is_not_null($_GET['pfrom'])) || (isset($_GET['pto']) && oos_is_not_null($_GET['pto'])))) {
    $where_str .= " GROUP BY p.products_id";
}

if ((!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', (string) $_GET['sort'])) || (substr((string) $_GET['sort'], 0, 1) > count($column_list))) {
    for ($col=0, $n=count($column_list); $col<$n; $col++) {
        if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
            $sort = $col+1 . 'a';
            $order_str = ' ORDER BY pd.products_name';
            break;
        }
    }
} else {
    $sort_col = substr((string) $sort, 0, 1);
    $sort_order = substr((string) $sort, 1);
    $order_str = ' ORDER BY ';

    match ($column_list[$sort_col-1]) {
        'PRODUCT_LIST_MODEL' => $order_str .= "p.products_model " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name",
        'PRODUCT_LIST_NAME' => $order_str .= "pd.products_name " . ($sort_order == 'd' ? "desc" : ""),
        'PRODUCT_LIST_MANUFACTURER' => $order_str .= "m.manufacturers_name " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name",
        'PRODUCT_LIST_QUANTITY' => $order_str .= "p.products_quantity " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name",
        'PRODUCT_LIST_IMAGE' => $order_str .= "pd.products_name",
        'PRODUCT_LIST_WEIGHT' => $order_str .= "p.products_weight " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name",
        'PRODUCT_LIST_PRICE' => $order_str .= "final_price " . ($sort_order == 'd' ? "desc" : "") . ", pd.products_name",
        default => $order_str .= "pd.products_name",
    };
}

$listing_sql = $select_str . $from_str . $where_str . $order_str;

$aTemplate['page'] = $sTheme . '/page/advanced_search_result.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,follow,noodp,noydir', 'text_no_products' => sprintf($aLang['text_no_products'], $keywords)]
);

require_once MYOOS_INCLUDE_PATH . '/includes/modules/product_listing.php';

$smarty->assign('oos_get_all_get_params', oos_get_all_get_parameters(['sort', 'page']));
$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination']));

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
