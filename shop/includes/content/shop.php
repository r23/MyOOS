<?php
/* ----------------------------------------------------------------------
   $Id: shop.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main_shop.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_default.php';

// the following category references come from oos_main.php
  $category_depth = 'top';
  $aLang['heading_title'] = $aLang['heading_title_top'];
  if (isset($category) && oos_is_not_null($category)) {
    $products_to_categoriestable = $oostable['products_to_categories'];
    $sql = "SELECT COUNT(*) AS total
            FROM $products_to_categoriestable
            WHERE categories_id = '" . intval($nCurrentCategoryId) . "'";
    $categories_products = $dbconn->Execute($sql);
    if ($categories_products->fields['total'] > 0) {
      $category_depth = 'products'; // display products
      $aLang['heading_title'] = $aLang['heading_title_products'];
    } else {
      $categoriestable = $oostable['categories'];
      $sql = "SELECT COUNT(*) AS total
              FROM $categoriestable
              WHERE parent_id = '" . intval($nCurrentCategoryId) . "'";
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

    $aTemplate['page'] = $sTheme . '/system/nested.tpl';

    $nPageType = OOS_PAGE_TYPE_CATALOG;

    $sGroup = trim($_SESSION['member']->group['text']);
    $contents_cache_id = $sTheme . '|shop|nested|' . intval($nCurrentCategoryId) . '|' . $category . '|' . $sGroup . '|' . $sLanguage;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    if ( (USE_CACHE == 'true') && (!SID) ) {
      $smarty->setCaching(true);
      $smarty->setCacheLifetime(24 * 3600);
    }

    $smarty->assign('oos_breadcrumb', $oBreadcrumb->trail(BREADCRUMB_SEPARATOR));

    if (!$smarty->isCached($aTemplate['page'], $contents_cache_id)) {
      $categoriestable = $oostable['categories'];
      $categories_descriptiontable = $oostable['categories_description'];
      $sql = "SELECT cd.categories_name, cd.categories_heading_title, cd.categories_description,
                     cd.categories_description_meta, cd.categories_keywords_meta, c.categories_image
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_id = '" . intval($nCurrentCategoryId) . "'
                AND cd.categories_id = '" . intval($nCurrentCategoryId) . "'
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'";
      $category = $dbconn->GetRow($sql);

// todo multilanguage support
      $smarty->assign('oos_meta_description', $category['categories_description_meta']);

      if (isset($category) && preg_match('/_/', $category)) {
        // check to see if there are deeper categories within the current category
        $category_links = array_reverse($aCategoryPath);
        for ($i=0, $n=count($category_links); $i<$n; $i++) {
          $categoriestable = $oostable['categories'];
          $categories_descriptiontable = $oostable['categories_description'];
          $sql = "SELECT c.categories_id, c.categories_image, c.parent_id, c.categories_status, cd.categories_name, p.parent_id as gparent_id
                  FROM $categoriestable c,
                       $categoriestable p,
                       $categories_descriptiontable cd
                  WHERE c.categories_status = '1'
                    AND ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                    AND c.parent_id = '" . intval($category_links[$i]) . "'
                    AND c.categories_id = cd.categories_id
                    AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
                    AND p.categories_id = '" . intval($category_links[$i]) . "'
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
                  AND c.parent_id = '" . intval($nCurrentCategoryId) . "'
                  AND c.categories_id = cd.categories_id
                  AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
                  AND p.categories_id = '" . intval($nCurrentCategoryId) . "'
                ORDER BY c.sort_order, cd.categories_name";
        $categories_result = $dbconn->Execute($sql);
      }

      $rows = 0;
      $categories_box = '';
      while ($categories = $categories_result->fields) {
        $rows++;
        $category_new = oos_get_path($categories['categories_id'], $categories['parent_id'], $categories['gparent_id']);
        $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

        $categories_box .= '                <td align="center" class="smallText" style="width: ' . $width . '" valign="top"><a href="' . oos_href_link($aContents['shop'], $category_new) . '">';

        if (oos_is_not_null($categories['categories_image'])) {
          $categories_box .= oos_image(OOS_IMAGES . $categories['categories_image'], $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br />';
        } else {
          $categories_box .= oos_image(OOS_IMAGES . 'trans.gif', $categories['categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, 'style="border: 3px double black"') . '<br />';
        }
        $categories_box .= $categories['categories_name'] . '</a></td>' . "\n";

        if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $categories_result->RecordCount())) {
          $categories_box .= '              </tr>' . "\n";
          $categories_box .= '              <tr>' . "\n";
        }
        // Move that ADOdb pointer!
        $categories_result->MoveNext();
      }
      // Close result set
      $categories_result->Close();

      $new_products_category_id = $nCurrentCategoryId;
      require_once MYOOS_INCLUDE_PATH . '/includes/modules/new_products.php';

      // assign Smarty variables;
      if ( (ALLOW_CATEGORY_DESCRIPTIONS == 'true') && (oos_is_not_null($category['categories_heading_title'])) ) {
        $smarty->assign('oos_heading_title', $category['categories_heading_title']);
      } else {
        $smarty->assign('oos_heading_title', $aLang['heading_title']);
      }
      $smarty->assign(
          array(
              'category'       => $category,
              'categories_box' => $categories_box
          )
      );
    }
    $smarty->setCaching(false);
	$smarty->display($aTemplate['page']);
	
  } elseif ($category_depth == 'products' || isset($_GET['manufacturers_id'])) {

    $aTemplate['page'] = $sTheme . '/system/products.tpl';
    $aTemplate['page_navigation'] = $sTheme . '/heading/page_navigation.tpl';

    $nPageType = OOS_PAGE_TYPE_CATALOG;

    $nManufacturersID = isset($_GET['manufacturers_id']) ? $_GET['manufacturers_id']+0 : 0;
    $nPage = isset($_GET['page']) ? $_GET['page']+0 : 1;
    $nFilterID = intval($_GET['filter_id']) ? $_GET['filter_id']+0 : 0;
    $sSort = oos_var_prep_for_os($_GET['sort']);
    $sGroup = trim($_SESSION['member']->group['text']);
    $contents_cache_id = $sTheme . '|shop|products|' . intval($nCurrentCategoryId) . '|' . $category . '|' . $nManufacturersID . '|' . $nPage . '|' . $nFilterID . '|' . $sGroup . '|' . $sLanguage;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // index_products_heading.html
    if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
      $categoriestable = $oostable['categories'];
      $categories_descriptiontable = $oostable['categories_description'];
      $sql = "SELECT cd.categories_name, cd.categories_heading_title, cd.categories_description,
                     cd.categories_description_meta, c.categories_image
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_id = '" . intval($nCurrentCategoryId) . "'
                AND cd.categories_id = '" . intval($nCurrentCategoryId) . "'
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'";
      $category = $dbconn->GetRow($sql);

      if (oos_is_not_null($category['categories_heading_title'])) {
        $smarty->assign('oos_heading_title', $category['categories_heading_title']);
      } else {
        $smarty->assign('oos_heading_title', $aLang['heading_title']);
      }
      $smarty->assign('oos_meta_description', $category['categories_description_meta']);
    }

    if ( (USE_CACHE == 'true') && (!SID) ) {
      $smarty->setCaching(true);
      $smarty->setCacheLifetime(24 * 3600);
    }

    $smarty->assign('oos_breadcrumb', $oBreadcrumb->trail(BREADCRUMB_SEPARATOR));

    if (!$smarty->isCached($aTemplate['page'], $contents_cache_id)) {

// create column list
      $define_list = array('PRODUCT_LIST_MODEL' => PRODUCT_LIST_MODEL,
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

      $column_list = array();
      reset($define_list);
      while (list($column, $value) = each($define_list)) {
        if ($value) $column_list[] = $column;
      }

      $select_column_list = '';

      for ($col=0, $n=count($column_list); $col<$n; $col++) {
        if ( ($column_list[$col] == 'PRODUCT_LIST_BUY_NOW')
            || ($column_list[$col] == 'PRODUCT_LIST_PRICE')
            || ($column_list[$col] == 'PRODUCT_LIST_UVP') ) {
          continue;
        }

        if (oos_is_not_null($select_column_list)) {
          $select_column_list .= ', ';
        }

        switch ($column_list[$col]) {
          case 'PRODUCT_LIST_MODEL':
            $select_column_list .= 'p.products_model';
            break;

          case 'PRODUCT_LIST_NAME':
            $select_column_list .= 'pd.products_name';
            break;

          case 'PRODUCT_LIST_MANUFACTURER':
            $select_column_list .= 'm.manufacturers_name';
            break;

          case 'PRODUCT_LIST_QUANTITY':
            $select_column_list .= 'p.products_quantity';
            break;

          case 'PRODUCT_LIST_IMAGE':
            $select_column_list .= 'p.products_image';
            break;

          case 'PRODUCT_LIST_WEIGHT':
            $select_column_list .= 'p.products_weight';
            break;

          case 'PRODUCT_LIST_SORT_ORDER':
            $select_column_list .= 'p.products_sort_order';
            break;

          default:
            $select_column_list .= "pd.products_name";
            break;

        }
      }

      if (oos_is_not_null($select_column_list)) {
        $select_column_list .= ', ';
      }

// show the products of a specified manufacturer
      if (isset($_GET['manufacturers_id'])) {
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
        if (isset($_GET['filter_id'])) {
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
                            AND p2c.categories_id = '" . intval($nCurrentCategoryId) . "'";
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
                            AND p2c.categories_id = '" . intval($nCurrentCategoryId) . "'";
        }
        // We build the manufacturers Dropdown
        $productstable = $oostable['products'];
        $manufacturerstable = $oostable['manufacturers'];
        $products_to_categoriestable = $oostable['products_to_categories'];
        $filterlist_sql= "SELECT DISTINCT m.manufacturers_id AS id, m.manufacturers_name AS name
                          FROM $productstable p,
                               $products_to_categoriestable p2c,
                               $manufacturerstable m
                          WHERE p.products_status >= '1'
                            AND p.manufacturers_id = m.manufacturers_id
                            AND p.products_id = p2c.products_id
                            AND p2c.categories_id = '" . intval($nCurrentCategoryId) . "'
                          ORDER BY m.manufacturers_name";
      }


      if ( (!isset($_GET['sort'])) || (!preg_match('/[1-8][ad]/', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > count($column_list)) ) {
        for ($col=0, $n=count($column_list); $col<$n; $col++) {
          if ($column_list[$col] == 'PRODUCT_LIST_NAME') {
            $_GET['sort'] = 'products_sort_order';
            $listing_sql .= " ORDER BY p.products_sort_order asc";
            break;
          }
        }
      } else {
        $sort_col = substr($_GET['sort'], 0 , 1);
        $sort_order = substr($_GET['sort'], 1);
        $listing_sql .= ' ORDER BY ';

        switch ($column_list[$sort_col-1]) {
          case 'PRODUCT_LIST_MODEL':
            $listing_sql .= "p.products_model " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
            break;

          case 'PRODUCT_LIST_NAME':
            $listing_sql .= "pd.products_name " . ($sort_order == 'd' ? 'desc' : '');
            break;

          case 'PRODUCT_LIST_MANUFACTURER':
            $listing_sql .= "m.manufacturers_name " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
            break;

          case 'PRODUCT_LIST_QUANTITY':
            $listing_sql .= "p.products_quantity " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
            break;

          case 'PRODUCT_LIST_IMAGE':
            $listing_sql .= "pd.products_name";
            break;

          case 'PRODUCT_LIST_WEIGHT':
            $listing_sql .= "p.products_weight " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
            break;

          case 'PRODUCT_LIST_PRICE':
            $listing_sql .= "final_price " . ($sort_order == 'd' ? 'desc' : '') . ", pd.products_name";
            break;

          case 'PRODUCT_LIST_SORT_ORDER':
            $listing_sql .= "p.products_sort_order " . ($sort_order == 'd' ? "desc" : '') . ", pd.products_name";
            break;

          default:
            $listing_sql .= "pd.products_name";
            break;

        }
      }

// optional Product List Filter
      $product_filter_select = '';
      if (PRODUCT_LIST_FILTER > 0) {
        $filterlist_result = $dbconn->Execute($filterlist_sql);
        if ($filterlist_result->RecordCount() > 1) {
          $product_filter_select .= '            <td align="center" class="main">' . $aLang['text_show'] . '<select size="1" onChange="if(options[selectedIndex].value) window.location.href=(options[selectedIndex].value)">';
          if (isset($_GET['manufacturers_id'])) {
            $manufacturers_id = intval($_GET['manufacturers_id']);
            $arguments = 'manufacturers_id=' . intval($manufacturers_id);
          } else {
            $arguments = 'category=' . $category;
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
      }
// Get the right image for the top-right
      $image = 'list.gif';
      if (isset($_GET['manufacturers_id'])) {
        $nManufacturersID = intval($_GET['manufacturers_id']);
        $manufacturerstable = $oostable['manufacturers'];
        $sql = "SELECT manufacturers_image
                FROM $manufacturerstable
                WHERE manufacturers_id = '" . intval($nManufacturersID) . "'";
        $image_value = $dbconn->GetOne($sql);
      } elseif ($nCurrentCategoryId) {
        $categoriestable = $oostable['categories'];
        $sql = "SELECT categories_image
                FROM $categoriestable
                WHERE categories_id = '" . intval($nCurrentCategoryId) . "'";
        $image_value = $dbconn->GetOne($sql);
      }

      if (oos_is_not_null($image_value)) {
        $image = $image_value;
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

      if ( (isset($_GET['manufacturers_id'])) ||  (oos_total_products_in_category($nCurrentCategoryId) >= 1) ) {
        require_once MYOOS_INCLUDE_PATH . '/includes/modules/product_listing.php';
      }
    }
    $smarty->assign('oosPageNavigation', $smarty->fetch($aTemplate['page_navigation'], $contents_cache_id));
    $smarty->setCaching(false);
  } else {
    // $category_depth = 'top';
    oos_redirect(oos_href_link($aContents['main']));
  }

// display the template
$smarty->display($aTemplate['page']);

