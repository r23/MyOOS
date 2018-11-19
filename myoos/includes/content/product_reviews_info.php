<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_reviews_info.php,v 1.47 2003/02/13 04:23:23 hpdl
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

if (!isset($_GET['reviews_id'])) {
	oos_redirect(oos_href_link($aContents['reviews']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews_product_info.php';

$reviewstable = $oostable['reviews'];
$productstable = $oostable['products'];
$reviews_descriptiontable = $oostable['reviews_description'];
$products_descriptiontable = $oostable['products_description'];
$sql = "SELECT rd.reviews_headline, rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id,
                 r.customers_name, r.verified, r.date_added, r.last_modified, r.reviews_read,
                 p.products_id, pd.products_name, p.products_model, p.products_replacement_product_id, p.products_image
          FROM $reviewstable r,
               $reviews_descriptiontable rd,
               $productstable p,
               $products_descriptiontable pd
          WHERE r.reviews_id = '" . intval($_GET['reviews_id']) . "'
            AND r.reviews_id = rd.reviews_id
            AND rd.reviews_languages_id = '" . intval($nLanguageID) . "'
            AND r.products_id = p.products_id
            AND p.products_status >= '1'
            AND p.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
$reviews_result = $dbconn->Execute($sql);
if (!$reviews_result->RecordCount()){
	// product reviews not found
	oos_redirect(oos_href_link($aContents['reviews']));
}
$reviews = $reviews_result->fields;

$dbconn->Execute("UPDATE " . $oostable['reviews'] . "
                SET reviews_read = reviews_read+1
                WHERE reviews_id = '" . $reviews['reviews_id'] . "'");

// add the products model or products_name to the breadcrumb trail
// links breadcrumb
$oBreadcrumb->add($reviews['products_name'], oos_href_link($aContents['product_info'], 'category=' . $sCategory . '&amp;products_id=' . $reviews['products_id']));
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['product_reviews']));
$sCanonical = oos_href_link($aContents['product_reviews'], $get_parameters, FALSE, TRUE);

$aTemplate['page'] = $sTheme . '/page/product_reviews_info.html';

$nPageType = OOS_PAGE_TYPE_REVIEWS;
$sPagetitle = sprintf($aLang['heading_title'], $reviews['products_name']) . ' ' . OOS_META_TITLE;

if ($oMessage->size('reviews') > 0) {
	$aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('reviews') );
}

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$smarty->assign(
       array(
			'breadcrumb'	=> $oBreadcrumb->trail(),
			'heading_title'	=> sprintf($aLang['heading_title'], $reviews['products_name']),
			'canonical'		=> $sCanonical,
			
			'reviews'		=> $reviews
       )
);

// display the template
$smarty->display($aTemplate['page']);
