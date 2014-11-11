<?php
/* ----------------------------------------------------------------------
   $Id: reviews.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) {
    $_SESSION['navigation']->remove_current_page();
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }


 /**
  * Get the number of times a word/character is present in a string
  *
  * @param $sStr
  * @param $sNeedle
  * @return number
  */
  function oosWordCount($sStr, $sNeedle = ' ') {
    $aTemp = split($sNeedle, $sStr);

    return count($aTemp);
  }

  $aOption['template_main'] = $sTheme . '/modules/reviews.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

  $nPageType = OOS_PAGE_TYPE_CATALOG;

  $nPage = isset($_GET['page']) ? $_GET['page']+0 : 1;
  $sGroup = trim($_SESSION['member']->group['text']);
  $contents_cache_id = $sTheme . '|products|reviews|' . $nPage. '|' . $sGroup . '|' . $sLanguage;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

  if (!$smarty->isCached($aOption['template_main'], $contents_cache_id)) {
    require 'includes/languages/' . $sLanguage . '/reviews_reviews.php';

    $reviewstable  = $oostable['reviews'];
    $productstable = $oostable['products'];
    $reviews_descriptiontable  = $oostable['reviews_description'];
    $products_descriptiontable = $oostable['products_description'];
    $reviews_result_raw = "SELECT r.reviews_id, rd.reviews_text, r.reviews_rating, r.date_added, p.products_id,
                                  pd.products_name, p.products_image, r.customers_name
                           FROM $reviewstable r,  $reviews_descriptiontable rd,
                                $productstable p, $products_descriptiontable pd
                           WHERE p.products_status >= '1'
                             AND p.products_id = r.products_id
                             AND r.reviews_id = rd.reviews_id
                             AND p.products_id = pd.products_id
                             AND pd.products_languages_id = '" . intval($nLanguageID) . "'
                             AND rd.reviews_languages_id = '" . intval($nLanguageID) . "'
                           ORDER BY r.reviews_id DESC";
    $reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_NEW_REVIEWS, $reviews_result_raw, $reviews_numrows);
    $reviews_result = $dbconn->Execute($reviews_result_raw);
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
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['reviews'], $aFilename['reviews_reviews']));

    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif',

            'oos_page_split'    => $reviews_split->display_count($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, $_GET['page'], $aLang['text_display_number_of_reviews']),
            'oos_display_links' => $reviews_split->display_links($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
            'oos_page_numrows'  => $reviews_numrows,

            'oos_reviews_array' => $aReviews
        )
    );
  }
  $smarty->assign('oosPageNavigation', $smarty->fetch($aOption['page_navigation'], $contents_cache_id));
  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading'], $contents_cache_id));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main'], $contents_cache_id));
  $smarty->setCaching(false);

  // display the template
  require 'includes/oos_display.php';
?>