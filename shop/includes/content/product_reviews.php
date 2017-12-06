<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_reviews.php,v 1.47 2003/02/13 03:53:19 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (!$oEvent->installed_plugin('reviews')) {
	oos_redirect(oos_href_link($aContents['home']));
}

if (isset($_GET['products_id'])) {
	if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_GET['products_id']);
} else {
	oos_redirect(oos_href_link($aContents['reviews']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';  
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews_product.php';

// lets retrieve all $_GET keys and values..
$get_params = oos_get_all_get_parameters(array('reviews_id'));
$get_params = oos_remove_trailing($get_params);

$nPage = isset($_GET[page]) ? $_GET[page]+0 : 1;

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$sql = "SELECT p.products_id, p.products_model, p.products_image, pd.products_name
          FROM $productstable p,
		       $products_descriptiontable pd 
          WHERE pd.products_languages_id = '" .  intval($nLanguageID) . "'
            AND p.products_status >= '1'
			AND p.products_id = pd.products_id
            AND pd.products_id = '" . intval($nProductsID) . "'";
$product_info_result = $dbconn->Execute($sql);
if (!$product_info_result->RecordCount()) {
	oos_redirect(oos_href_link($aContents['reviews']));
}
$product_info = $product_info_result->fields;

$reviewstable = $oostable['reviews'];
$reviews_descriptiontable = $oostable['reviews_description'];
$sql = "SELECT r.reviews_id, left(rd.reviews_text, 100) as reviews_text, r.reviews_rating, r.date_added, r.customers_name, r.reviews_read
          FROM $reviewstable r,
		       $reviews_descriptiontable rd
          WHERE products_id = '" . intval($nProductsID) . "'
		  AND r.reviews_id = rd.reviews_id 
		  AND rd.reviews_languages_id = '" .  intval($nLanguageID) . "'
		  AND r.reviews_status = 1 
          ORDER BY r.reviews_id DESC";
$reviews_result = $dbconn->Execute($sql);
$aReviews = array();
while ($reviews = $reviews_result->fields) {
    $aReviews[] = array('rating' => $reviews['reviews_rating'],
                        'id' => $reviews['reviews_id'],
						'reviews_text' => $reviews['reviews_text'], 
                        'customers_name' => $reviews['customers_name'],
                        'date_added' => oos_date_short($reviews['date_added']),
                        'read' => $reviews['reviews_read']);
    $reviews_result->MoveNext();
}

  
$reviews_split = new splitPageResults($nPage, MAX_DISPLAY_NEW_REVIEWS, $reviews_result_raw, $reviews_numrows);
$reviews_result = $dbconn->Execute($reviews_result_raw);  

while ($reviews = $reviews_result->fields) {
    $aReviews[] = array('rating' => $reviews['reviews_rating'],
                        'id' => $reviews['reviews_id'],
                        'customers_name' => $reviews['customers_name'],
                        'date_added' => oos_date_short($reviews['date_added']),
                        'read' => $reviews['reviews_read']);
    $reviews_result->MoveNext();
}
  
// add the products model or products_name to the breadcrumb trail
// links breadcrumb
if (SHOW_PRODUCTS_MODEL == 'true') {
    $oBreadcrumb->add($product_info['products_model'], oos_href_link($aContents['product_info'], 'category=' . $sCategory . '&amp;products_id=' . $nProductsID));
} else {
    $oBreadcrumb->add($product_info['products_name'], oos_href_link($aContents['product_info'], 'category=' . $sCategory . '&amp;products_id=' . $nProductsID));
}
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['product_reviews'], $get_params));
$sCanonical = oos_href_link($aContents['product_reviews'], $get_params, FALSE, TRUE);
  
  
$aTemplate['page'] = $sTheme . '/page/product_reviews.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_REVIEWS;
$sPagetitle = sprintf($aLang['heading_title'], $product_info['products_name']) . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$smarty->assign(
	array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => sprintf($aLang['heading_title'], $product_info['products_name']),
		'canonical'		=> $sCanonical,

		'page_split'	=> $reviews_split->display_count($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, $nPage, $aLang['text_display_number_of_reviews']),
		'display_links'	=> $reviews_split->display_links($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, MAX_DISPLAY_PAGE_LINKS, $nPage, oos_get_all_get_parameters(array('page', 'info'))),
		'numrows'		=> $reviews_numrows,
		
		'oos_reviews_array' => $aReviews
		  
      )
);

$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination']));

// display the template
$smarty->display($aTemplate['page']);
