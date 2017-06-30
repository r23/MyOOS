<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
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

/** ensure this file is being required by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shop.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_default.php';

// the following cPath references come from main.php
$category_depth = 'top';
$aLang['heading_title'] = $aLang['heading_title_top'];

if (isset($sCategory) && oos_is_not_null($sCategory)) {
	$products_to_categoriestable = $oostable['products_to_categories'];
	$sql = "SELECT COUNT(*) AS total
            FROM $products_to_categoriestable
            WHERE categories_id = '" . intval($nCurrentCategoryID) . "'";
    $categories_products = $dbconn->Execute($sql);
	if ($categories_products->fields['total'] > 0) {
		$category_depth = 'products'; // display products
		$aLang['heading_title'] = $aLang['heading_title_products'];
	} else {
		$categoriestable = $oostable['categories'];
		$sql = "SELECT COUNT(*) AS total
              FROM $categoriestable
              WHERE parent_id = '" . intval($nCurrentCategoryID) . "'";
		$category_parent = $dbconn->Execute($sql);
		if ($category_parent->fields['total'] > 0) {
			$category_depth = 'nested'; // navigate through the categories
			$aLang['heading_title'] = $aLang['heading_title_nested'];
		} else {
			$category_depth = 'products'; // category has no products, but display the 'no products' message
			$aLang['heading_title'] = $aLang['heading_title_products'];
		}
	}
}

if ($category_depth == 'nested') {

	$aTemplate['page'] = $sTheme . '/page/shop_nested.html';
	$aTemplate['new_products'] = $sTheme . '/products/_new_products.html';
	
	$nPageType = OOS_PAGE_TYPE_CATALOG;
	$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

	$sGroup = trim($_SESSION['user']->group['text']);
	$sContentCacheID = $sTheme . '|shop|nested|' . intval($nCurrentCategoryID) . '|' . $sCategory . '|' . $sGroup . '|' . $sLanguage;

	require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
	if (!isset($option)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
	}

	if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
		$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	}

	$smarty->assign('breadcrumb', $oBreadcrumb->trail());
	$smarty->assign('canonical', $sCanonical);

	if (!$smarty->isCached($aTemplate['page'], $sContentCacheID)) {
		$categoriestable = $oostable['categories'];
		$categories_descriptiontable = $oostable['categories_description'];
		$sql = "SELECT cd.categories_name, cd.categories_heading_title, cd.categories_description,
                     cd.categories_description_meta, cd.categories_keywords_meta, c.categories_image
              FROM $categoriestable c,
                   $categories_descriptiontable cd 
              WHERE c.categories_id = '" . intval($nCurrentCategoryID) . "'
                AND cd.categories_id = '" . intval($nCurrentCategoryID) . "'
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'";
		$category = $dbconn->GetRow($sql);

		if (empty($category['categories_description_meta'])) {
			$smarty->assign('meta_description', substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '',$category['categories_description'])),0 , 160));
		} else {
			$smarty->assign('meta_description', $category['categories_description_meta']);
		}

		if (isset($sCategory) && strpos('_', $sCategory)) {
			// check to see if there are deeper categories within the current category
			$aCategoryLinks = array_reverse($aCategoryPath);
			$n = count($aCategoryLinks);
			for ($i = 0, $n; $i < $n; $i++) {
				$categoriestable = $oostable['categories'];
				$categories_descriptiontable = $oostable['categories_description'];
				$sql = "SELECT c.categories_id, c.categories_image, c.parent_id, c.categories_status, cd.categories_name, p.parent_id as gparent_id
                  FROM $categoriestable c,
                       $categoriestable p,
                       $categories_descriptiontable cd
                  WHERE c.categories_status = '1'
                    AND ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                    AND c.parent_id = '" . intval($aCategoryLinks[$i]) . "'
                    AND c.categories_id = cd.categories_id
                    AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
                    AND p.categories_id = '" . intval($aCategoryLinks[$i]) . "'
                  ORDER BY c.sort_order, cd.categories_name";
				$categories_result = $dbconn->Execute($sql);
				if ($categories_result->RecordCount() < 1) {
					// do nothing, go through the loop
				} else {
					break; // we've found the deepest category the customer is in
				}
			}
		} else {
			$categoriestable = $oostable['categories'];
			$categories_descriptiontable = $oostable['categories_description'];
			$sql = "SELECT c.categories_id, cd.categories_name, cd.categories_description,
                       c.categories_image, c.parent_id, c.categories_status, p.parent_id as gparent_id
                FROM $categoriestable c,
                     $categoriestable p,
                     $categories_descriptiontable cd
                WHERE c.categories_status = '1'
                  AND ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                  AND c.parent_id = '" . intval($nCurrentCategoryID) . "'
                  AND c.categories_id = cd.categories_id
                  AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
                  AND p.categories_id = '" . intval($nCurrentCategoryID) . "'
                ORDER BY c.sort_order, cd.categories_name";
			$categories_result = $dbconn->Execute($sql);
		}
		
		$aCategoriesBoxs = array();
		while ($categories = $categories_result->fields) {
			$sCategoryNew = oos_get_path($categories['categories_id'], $categories['parent_id'], $categories['gparent_id']);
			$aCategoriesBoxs[] = array(
									'image'	=> $categories['categories_image'],
									'name'	=> $categories['categories_name'],
									'path'	=> $sCategoryNew
									);
			// Move that ADOdb pointer!
			$categories_result->MoveNext();
		}

		if (!$smarty->isCached($aTemplate['new_products'], $sContentCacheID)) {
			$smarty->assign('cpath', $sCategory);
			require_once MYOOS_INCLUDE_PATH . '/includes/modules/new_products.php';
		}
		$smarty->assign('new_products', $smarty->fetch($aTemplate['new_products'], $sContentCacheID));
		
		// assign Smarty variables;
		if (!empty($category['categories_heading_title'])) {
			$sPagetitle = $category['categories_name'] . ' ' . OOS_META_TITLE;
			$smarty->assign('pagetitle',  $sPagetitle);
			$smarty->assign('heading_title', $category['categories_heading_title']);
		} else {
			$smarty->assign('heading_title', $aLang['heading_title']);
		}

		$smarty->assign(
			array(
				'category'      => $category,
				'categories' 	=> $aCategoriesBoxs
			)
		);
    }   
    $smarty->setCaching(false);

} elseif ($category_depth == 'products' || (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id']))) {

    $aTemplate['page'] = $sTheme . '/page/shop_products.html';
    $aTemplate['pagination'] = $sTheme . '/system/_pagination.htm';

    $nPageType = OOS_PAGE_TYPE_CATALOG;
	$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

    $nManufacturersID = isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id']+0 : 0;
    $nPage = isset($_GET['page']) ? $_GET['page']+0 : 1;
    $nFilterID = intval($_GET['filter_id']) ? $_GET['filter_id']+0 : 0;
    $sSort = oos_var_prep_for_os($_GET['sort']);
    $sGroup = trim($_SESSION['user']->group['text']);
    $sContentCacheID = $sTheme . '|shop|products|' . intval($nCurrentCategoryID) . '|' . $sCategory . '|' . $nManufacturersID . '|' . $nPage . '|' . $nFilterID . '|' . $sGroup . '|' . $sLanguage;
	
    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

	// index_products_heading.html
	$categoriestable = $oostable['categories'];
	$categories_descriptiontable = $oostable['categories_description'];
	$sql = "SELECT cd.categories_name, cd.categories_heading_title, cd.categories_description,
                     cd.categories_description_meta, cd.categories_keywords_meta,
                     c.categories_image
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_id = '" . intval($nCurrentCategoryID) . "'
                AND cd.categories_id = '" . intval($nCurrentCategoryID) . "'
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'";
	$category = $dbconn->GetRow($sql);

	if (empty($category['categories_description_meta'])) {
		$smarty->assign('meta_description', substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '',$category['categories_description'])),0 , 250));
	} else {
		$smarty->assign('meta_description', $category['categories_description_meta']);
	}

	if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
		$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	}

	$smarty->assign('breadcrumb', $oBreadcrumb->trail());
	$smarty->assign('canonical', $sCanonical);
	
    if (!$smarty->isCached($aTemplate['page'], $sContentCacheID)) {

// create column list
		$aDefineList = array();
		$aDefineList = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
                           'PRODUCT_LIST_NAME' => PRODUCT_LIST_NAME,
                           'PRODUCT_LIST_MANUFACTURER' => PRODUCT_LIST_MANUFACTURER,
                           'PRODUCT_LIST_UVP' => PRODUCT_LIST_UVP,
                           'PRODUCT_LIST_PRICE' => PRODUCT_LIST_PRICE,
                           'PRODUCT_LIST_QUANTITY' => PRODUCT_LIST_QUANTITY,
                           'PRODUCT_LIST_WEIGHT' => PRODUCT_LIST_WEIGHT,
                           'PRODUCT_LIST_IMAGE' => PRODUCT_LIST_IMAGE,
                           'PRODUCT_LIST_BUY_NOW' => PRODUCT_LIST_BUY_NOW,
                           'PRODUCT_LIST_SORT_ORDER' => PRODUCT_LIST_SORT_ORDER);
		asort($define_list);
		$aColumnList = array();

		foreach($aDefineList as $key => $value) {
			if ($value > 0) $aColumnList[] = $key;
		}

	  
	  
		$select_column_list = '';
		$n = count($aColumnList);  
		for ($col = 0, $n; $col < $n; $col++) {
			if ( ($aColumnList[$col] == 'PRODUCT_LIST_BUY_NOW')
				|| ($aColumnList[$col] == 'PRODUCT_LIST_PRICE')
				|| ($aColumnList[$col] == 'PRODUCT_LIST_UVP') ) {
				continue;
			}

			switch ($aColumnList[$col]) {
				case 'PRODUCT_LIST_MODEL':
					$select_column_list .= 'p.products_model, ';
					break;

				case 'PRODUCT_LIST_NAME':
					$select_column_list .= 'pd.products_name, ';
					break;

				case 'PRODUCT_LIST_MANUFACTURER':
					$select_column_list .= 'm.manufacturers_name, ';
					break;

				case 'PRODUCT_LIST_QUANTITY':
					$select_column_list .= 'p.products_quantity, ';
					break;

				case 'PRODUCT_LIST_IMAGE':
					$select_column_list .= 'p.products_image, ';
					break;

				case 'PRODUCT_LIST_WEIGHT':
					$select_column_list .= 'p.products_weight, ';
					break;

				case 'PRODUCT_LIST_SORT_ORDER':
					$select_column_list .= 'p.products_sort_order, ';
					break;
			}
		}


// show the products of a specified manufacturer
		if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
			$nManufacturersID = intval($_GET['manufacturers_id']);
			if (isset($_GET['filter_id'])) {
				// We are asked to show only a specific category
				$productstable = $oostable['products'];
				$products_descriptiontable = $oostable['products_description'];
				$manufacturerstable = $oostable['manufacturers'];
				$products_to_categoriestable = $oostable['products_to_categories'];
				$specialstable = $oostable['specials'];
				$listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id,
                                 p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_quantity_order_min,
                                 p.products_discount_allowed, p.products_discount1, p.products_discount2, p.products_discount3,
                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                                 p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_sort_order,
                                 IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                                 IF(s.status, s.specials_new_products_price, p.products_price) AS final_price
                          FROM $productstable p LEFT JOIN
                               $specialstable s ON p.products_id = s.products_id,
                               $products_descriptiontable pd,
                               $manufacturerstable m,
                               $products_to_categoriestable p2c
                          WHERE p.products_status >= '1'
                            AND p.manufacturers_id = m.manufacturers_id
                            AND m.manufacturers_id = '" . intval($nManufacturersID) . "'
                            AND p.products_id = p2c.products_id
                            AND pd.products_id = p2c.products_id
                            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                            AND p2c.categories_id = '" . intval($_GET['filter_id']) . "'";
			} else {
				// We show them all
				$productstable = $oostable['products'];
				$products_descriptiontable = $oostable['products_description'];
				$manufacturerstable = $oostable['manufacturers'];
				$specialstable = $oostable['specials'];
				$listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id,
                                 p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_quantity_order_min,
                                 p.products_discount_allowed, p.products_discount1, p.products_discount2, p.products_discount3,
                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                                 p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_sort_order,
                                 IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                                 IF(s.status, s.specials_new_products_price, p.products_price) AS final_price
                          FROM $productstable p LEFT JOIN
                               $specialstable s ON p.products_id = s.products_id,
                               $products_descriptiontable  pd,
                               $manufacturerstable m
                          WHERE p.products_status >= '1'
                            AND pd.products_id = p.products_id
                            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                            AND p.manufacturers_id = m.manufacturers_id
                            AND m.manufacturers_id = '" . intval($nManufacturersID) . "'";

			}
				// We build the categories-dropdown
				$productstable = $oostable['products'];
				$products_to_categoriestable = $oostable['products_to_categories'];
				$categoriestable = $oostable['categories'];
				$categories_descriptiontable = $oostable['categories_description'];
				$filterlist_sql = "SELECT DISTINCT c.categories_id AS id, cd.categories_name AS name
                           FROM $productstable p,
                                $products_to_categoriestable p2c,
                                $categoriestable c,
                                $categories_descriptiontable cd
                           WHERE p.products_status >= '1'
                             AND p.products_id = p2c.products_id
                             AND p2c.categories_id = c.categories_id
                             AND p2c.categories_id = cd.categories_id
                             AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
                             AND p.manufacturers_id = '" . intval($nManufacturersID) . "'
                           ORDER BY cd.categories_name";
		} else {
// show the products in a given categorie
			if ((isset($_GET['filter_id'])) && oos_is_not_null($_GET['filter_id'])) {
				// We are asked to show only specific catgeory
				$productstable = $oostable['products'];
				$products_descriptiontable = $oostable['products_description'];
				$manufacturerstable = $oostable['manufacturers'];
				$products_to_categoriestable = $oostable['products_to_categories'];
				$specialstable = $oostable['specials'];
				$listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id,
                                 p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_quantity_order_min,
                                 p.products_discount_allowed, p.products_discount1, p.products_discount2, p.products_discount3,
                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                                 p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_sort_order,
                                 IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                                 IF(s.status, s.specials_new_products_price, p.products_price) AS final_price
                          FROM $productstable p LEFT JOIN
                               $specialstable s on p.products_id = s.products_id,
                               $products_descriptiontable pd,
                               $manufacturerstable m,
                               $products_to_categoriestable p2c
                          WHERE p.products_status >= '1'
                            AND p.manufacturers_id = m.manufacturers_id
                            AND m.manufacturers_id = '" . intval($_GET['filter_id']) . "'
                            AND p.products_id = p2c.products_id
                            AND pd.products_id = p2c.products_id
                            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                            AND p2c.categories_id = '" . intval($nCurrentCategoryID) . "'";
			} else {
				// We show them all
				$productstable = $oostable['products'];
				$products_descriptiontable = $oostable['products_description'];
				$manufacturerstable = $oostable['manufacturers'];
				$products_to_categoriestable = $oostable['products_to_categories'];
				$specialstable = $oostable['specials'];
				$listing_sql = "SELECT " . $select_column_list . " p.products_id, p.manufacturers_id,
                                 p.products_price, p.products_price_list, p.products_base_price, p.products_base_unit, p.products_quantity_order_min,
                                 p.products_discount_allowed, p.products_discount1, p.products_discount2, p.products_discount3,
                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                                 p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_sort_order,
                                 IF(s.status, s.specials_new_products_price, NULL) AS specials_new_products_price,
                                 IF(s.status, s.specials_new_products_price, p.products_price) AS final_price
                          FROM $products_descriptiontable pd,
                               $productstable p LEFT JOIN
                               $manufacturerstable m ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                               $specialstable s ON p.products_id = s.products_id,
                               $products_to_categoriestable p2c
                          WHERE p.products_status >= '1'
                            AND p.products_id = p2c.products_id
                            AND pd.products_id = p2c.products_id
                            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                            AND p2c.categories_id = '" . intval($nCurrentCategoryID) . "'";
			}

			// We build the manufacturers Dropdown
			$productstable = $oostable['products'];
			$manufacturerstable = $oostable['manufacturers'];
			$products_to_categoriestable = $oostable['products_to_categories'];
			$filterlist_sql = "SELECT DISTINCT m.manufacturers_id AS id, m.manufacturers_name AS name
                         FROM $productstable p,
                               $products_to_categoriestable p2c,
                               $manufacturerstable m
                          WHERE p.products_status >= '1'
                            AND p.manufacturers_id = m.manufacturers_id
                            AND p.products_id = p2c.products_id
                            AND p2c.categories_id = '" . intval($nCurrentCategoryID) . "'
                          ORDER BY m.manufacturers_name";
		}


		if ( (!isset($_GET['sort'])) || (!preg_match('/^[1-8][ad]$/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > count($aColumnList)) ) {
			$n = count($aColumnList);
			for ($col = 0, $n; $col < $n; $col++) {
				if ($aColumnList[$col] == 'PRODUCT_LIST_NAME') {
					$_GET['sort'] = $i+1 . 'a';
					//  $_GET['sort'] = 'products_sort_order';
					$listing_sql .= " ORDER BY p.products_sort_order asc";
					break;
				}
			}
		} else {
			$sort_col = substr($_GET['sort'], 0 , 1);
			$sort_order = substr($_GET['sort'], 1);

			switch ($aColumnList[$sort_col-1]) {
				case 'PRODUCT_LIST_MODEL':
					$listing_sql .= " ORDER BY p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;

				case 'PRODUCT_LIST_NAME':
					$listing_sql .= " ORDER BY pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
					break;

				case 'PRODUCT_LIST_MANUFACTURER':
					$listing_sql .= " ORDER BY m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;

				case 'PRODUCT_LIST_QUANTITY':
					$listing_sql .= " ORDER BY p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;

				case 'PRODUCT_LIST_IMAGE':
					$listing_sql .= " ORDER BY pd.products_name";
					break;

				case 'PRODUCT_LIST_WEIGHT':
					$listing_sql .= " ORDER BY p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;

				case 'PRODUCT_LIST_PRICE':
					$listing_sql .= " ORDER BY final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
					break;

				case 'PRODUCT_LIST_SORT_ORDER':
					$listing_sql .= " ORDER BY p.products_sort_order " . ($sort_order == 'd' ? "desc" : '') . ", pd.products_name";
					break;

			}
		}

// optional Product List Filter
		$product_filter_select = '';
		$filterlist_result = $dbconn->Execute($filterlist_sql);
		if ($filterlist_result->RecordCount() > 1) {
			$product_filter_select .= '            <td align="center" class="main">' . $aLang['text_show'] . '<select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">';
			if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
				$manufacturers_id = intval($_GET['manufacturers_id']);
				$arguments = 'manufacturers_id=' . intval($manufacturers_id);
			} else {
				$arguments = 'category=' . $sCategory;
			}
			$arguments .= '&amp;sort=' . $_GET['sort'];

			$option_url = oos_href_link($aContents['shop'], $arguments);

			if (!isset($_GET['filter_id'])) {
				$product_filter_select .= '<option value="' . $option_url . '" selected="selected">' . $aLang['text_all'] . '</option>';
			} else {
				$product_filter_select .= '<option value="' . $option_url . '">' . $aLang['text_all'] . '</option>';
			}

			$product_filter_select .= '<option value="">---------------</option>';
			while ($filterlist = $filterlist_result->fields) {
				$option_url = oos_href_link($aContents['shop'], $arguments . '&amp;filter_id=' . $filterlist['id']);
				if (isset($_GET['filter_id']) && ($_GET['filter_id'] == $filterlist['id'])) {
					$product_filter_select .= '<option value="' . $option_url . '" selected="selected">' . $filterlist['name'] . '</option>';
				} else {
					$product_filter_select .= '<option value="' . $option_url . '">' . $filterlist['name'] . '</option>';
				}
				$filterlist_result->MoveNext();
			}
			$product_filter_select .= '</select></td>' . "\n";
		}

// Get the image for the top
// $image =
		if (isset($_GET['manufacturers_id']) && !empty($_GET['manufacturers_id'])) {
			$nManufacturersID = intval($_GET['manufacturers_id']);
			$manufacturerstable = $oostable['manufacturers'];
				$sql = "SELECT teaser_brand_image
                        FROM $manufacturerstable
                        WHERE manufacturers_id = '" . intval($nManufacturersID) . "'";			
			$image = $dbconn->GetOne($sql);
		} elseif ($nCurrentCategoryID) {
			$image = $category['categories_image'];
		}

		// assign Smarty variables;
		$smarty->assign(
			array(
				'product_filter_select' => $product_filter_select,
				'image' => $image,
				'heading_title' => $aLang['heading_title'],
				'category' => $category
			)
		);

		if ( (isset($_GET['manufacturers_id'])) ||  (oos_total_products_in_category($nCurrentCategoryID) >= 1) ) {
			require_once MYOOS_INCLUDE_PATH . '/includes/modules/product_listing.php';
		}
    }
    $smarty->assign('pagination', $smarty->fetch($aTemplate['pagination'], $sContentCacheID));   
    $smarty->setCaching(false);
} else {
	// $category_depth = 'top';
	oos_redirect(oos_href_link($aContents['main']));
}

// display the template
$smarty->display($aTemplate['page']);
