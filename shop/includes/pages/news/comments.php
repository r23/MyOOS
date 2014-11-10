<?php
/* ----------------------------------------------------------------------
   $Id: comments.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

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

  // Get the number of times a word/character is present in a string
  function oosWordCount($string, $needle) {
    $temp_array = split($needle, $string);

    return count($temp_array);
  }

  require 'includes/languages/' . $sLanguage . '/news_comments.php';

  $reviews_result_raw = "SELECT nr.news_reviews_id, nrd.news_reviews_text, nr.news_reviews_rating, nr.date_added,
                                n.news_id, nd.news_name, n.news_image, nr.customers_name
                         FROM " . $oostable['news_reviews'] . " nr,
                              " . $oostable['news_reviews_description'] . " nrd,
                              " . $oostable['news'] . " n,
                              " . $oostable['news_description'] . " nd
                         WHERE n.news_status = '1'
                           AND n.news_id = nr.news_id
                           AND nr.news_reviews_id  = nrd.news_reviews_id
                           AND n.news_id = nd.news_id
                           AND nd.news_languages_id  = '" . intval($nLanguageID) . "'
                           AND nrd.news_reviews_languages_id  = '" . intval($nLanguageID) . "'
                         ORDER BY nr.news_reviews_id  DESC";
  $reviews_split = new splitPageResults($_GET['page'], MAX_DISPLAY_NEW_REVIEWS, $reviews_result_raw, $reviews_numrows);
  $reviews_result = $dbconn->Execute($reviews_result_raw);
  $aReviews = array();
  while ($reviews = $reviews_result->fields) {
    $aReviews[] = array('id' => $reviews['news_reviews_id'],
                        'news_id' => $reviews['news_id'],
                        'news_reviews_id' => $reviews['news_reviews_id'],
                        'news_name' => $reviews['news_name'],
                        'news_image' => $reviews['news_image'],
                        'authors_name' => $reviews['customers_name'],
                        'review' => htmlspecialchars(substr($reviews['news_reviews_text'], 0, 250)) . '..',
                        'rating' => $reviews['news_reviews_rating'],
                        'word_count' => oosWordCount($reviews['news_reviews_text'], ' '),
                        'date_added' => oos_date_long($reviews['date_added']));
    $reviews_result->MoveNext();
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['news'], $aFilename['news_comments']));

  $aOption['template_main'] = $sTheme . '/modules/reviews/news_comments.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

  $nPageType = OOS_PAGE_TYPE_NEWS;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => sprintf($aLang['heading_title'], $product_info['news_name']),
          'oos_heading_image' => 'specials.gif',

          'oos_page_split' => $reviews_split->display_count($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, $_GET['page'], $aLang['text_display_number_of_reviews']),
          'oos_display_links' => $reviews_split->display_links($reviews_numrows, MAX_DISPLAY_NEW_REVIEWS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
          'oos_page_numrows' => $reviews_numrows,

          'oos_reviews_array', $aReviews
      )
  );

  $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
