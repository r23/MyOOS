<?php
/* ----------------------------------------------------------------------
   $Id: block_news_reviews.php,v 1.1 2007/06/07 11:55:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reviews.php,v 1.36 2003/02/12 20:27:32 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) return false;

  $news_reviews_block = 'false';

  if ($sFile != $aFilename['news_reviews_write']) {

    $news_reviewstable = $oostable['news_reviews'];
    $news_reviews_descriptiontable = $oostable['news_reviews_description'];
    $newstable = $oostable['news'];
    $news_descriptiontable = $oostable['news_description'];
    $random_select = "SELECT nr.news_reviews_id, nr.news_id, nr.news_reviews_rating,
                             substring(nrd.news_reviews_text, 1, 60) AS news_reviews_text,
                             n.news_id, nd.news_name, n.news_image
                      FROM $news_reviewstable nr,
                           $news_reviews_descriptiontable nrd,
                           $newstable n,
                           $news_descriptiontable nd
                      WHERE n.news_status = '1'
                        AND nr.news_reviews_id = nrd.news_reviews_id
                        AND nrd.news_reviews_languages_id = '" . intval($nLanguageID) . "'
                        AND n.news_id = nd.news_id
                        AND nd.news_languages_id = '" . intval($nLanguageID) . "'";
    if (isset($_GET['news_id'])) {
      $random_select .= " AND n.news_id = '" . intval($_GET['news_id']) . "'";
    }
    $random_select .= " ORDER BY nr.news_id DESC";

    $random_news = oos_random_select($random_select, MAX_RANDOM_SELECT_REVIEWS);

    if ($random_news) {
      $news_reviews_block = 'true'; // display random news review block

      $oSmarty->assign(
          array(
              'block_heading_reviews' => $block_heading,
              'random_news' => $random_news
          )
      );
    }
  }
  $oSmarty->assign('news_reviews_block', $news_reviews_block);

?>
