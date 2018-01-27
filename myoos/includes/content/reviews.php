<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reviews.php,v 1.47 2003/02/13 04:23:23 hpdl 
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

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_split_page_results.php';  
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews.php';
  

/**
 * Get the number of times a word/character is present in a string
 *
 * @param $sStr
 * @param $sNeedle
 * @return number
 */
function oosWordCount($sStr, $sNeedle = ' ') {
	$aTemp = explode($sNeedle, $sStr);

	return count($aTemp);
}


$aTemplate['page'] = $sTheme . '/page/reviews.html';
$aTemplate['pagination'] = $sTheme . '/system/_pagination.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

$nPage = isset($_GET['page']) ? intval( $_GET['page'] ) : 1;
$sGroup = trim($aUser['text']);
$nContentCacheID = $sTheme . '|products|reviews|' . $nPage. '|' . $sGroup . '|' . $sLanguage;

if ($oMessage->size('reviews') > 0) {
	$aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('reviews') );
}

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

if (!$smarty->isCached($aTemplate['page'], $nContentCacheID)) {

    $reviewstable  = $oostable['reviews'];
    $productstable = $oostable['products'];
    $reviews_descriptiontable  = $oostable['reviews_description'];
    $products_descriptiontable = $oostable['products_description'];
    $reviews_result_raw = "SELECT r.reviews_id, rd.reviews_text,
								  r.reviews_rating, r.date_added, p.products_id,
                                  pd.products_name, p.products_image, r.customers_name
                           FROM $reviewstable r,  $reviews_descriptiontable rd,
                                $productstable p, $products_descriptiontable pd
                           WHERE p.products_status >= '1'
                             AND p.products_id = r.products_id
                             AND r.reviews_id = rd.reviews_id
                             AND p.products_id = pd.products_id
							 AND r.reviews_status = 1 
                             AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                             AND rd.reviews_languages_id = '" . intval($nLanguageID) . "'
                           ORDER BY r.reviews_id DESC";
    $reviews_split = new splitPageResults($reviews_result_raw, MAX_DISPLAY_NEW_REVIEWS);
    $reviews_result = $dbconn->Execute($reviews_split->sql_query);

    $aReviews = array();
    while ($reviews = $reviews_result->fields) {
		$aReviews[] = array('id' => $reviews['reviews_id'],
                          'products_id' => $reviews['products_id'],
                          'reviews_id' => $reviews['reviews_id'],
                          'products_name' => $reviews['products_name'],
                          'products_image' => $reviews['products_image'],
                          'authors_name' => $reviews['customers_name'],
                          'review' => htmlspecialchars(substr($reviews['reviews_text'], 0, 250)) . '..',
                          'rating' => $reviews['reviews_rating'],
                          'word_count' => oosWordCount($reviews['reviews_text'], ' '),
                          'date_added' => oos_date_long($reviews['date_added']));
		$reviews_result->MoveNext();
    }

	// links breadcrumb
	$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['reviews']));
	$sCanonical = oos_href_link($aContents['reviews'], 'page=' . $nPage, FALSE, TRUE);
	
	$smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['heading_title'],
			'canonical'		=> $sCanonical,

            'page_split'    => $reviews_split->display_count($aLang['text_display_number_of_reviews']),
            'display_links' => $reviews_split->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
			'numrows' 		=> $reviews_split->number_of_rows,
			'numpages' 		=> $reviews_split->number_of_pages,
			'page'			=> $nPage,
            'reviews' 		=> $aReviews
        )
    );
  }

$smarty->assign('pagination', $smarty->fetch($aTemplate['pagination'], $nContentCacheID));

// display the template
$smarty->display($aTemplate['page']);
