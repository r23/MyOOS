<?php
/* ----------------------------------------------------------------------
   $Id: new_news.php,v 1.2 2008/08/29 16:53:12 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!is_numeric(MAX_DISPLAY_NEW_NEWS)) return false;

  $new_news_block = 'false';
  $aNewNews = array();

  $newstable = $oostable['news'];
  $news_descriptiontable = $oostable['news_description'];
  $sql = "SELECT n.news_id, n.news_image, n.news_date_added, n.news_added_by, n.news_status,
                 nd.news_name, nd.news_viewed,
                 substring(nd.news_description, 1, 562) AS news_description
          FROM $newstable n,
               $news_descriptiontable nd
          WHERE n.news_status = '1'
            AND n.news_id = nd.news_id
            AND nd.news_languages_id = '" . intval($nLanguageID) . "'
          ORDER BY n.news_date_added DESC";
  $new_news_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_NEW_NEWS);
  if ($new_news_result->RecordCount() >= MIN_DISPLAY_NEW_NEWS) {
    $new_news_block = 'true';
    while ($new_news = $new_news_result->fields) {
      $new_news['author_name'] = oos_get_news_author_name($new_news['news_added_by']);
      $new_news['news_reviews'] = oos_get_news_reviews($new_news['news_id']);

      $aNewNews[] = array('news_id' => $new_news['news_id'],
                          'news_image' => $new_news['news_image'],
                          'news_date_added' => $new_news['news_date_added'],
                          'author_name' => $new_news['author_name'],
                          'news_name' => $new_news['news_name'],
                          'news_viewed' => $new_news['news_viewed'],
                          'news_reviews' => $new_news['news_reviews'],
                          'news_description' => $new_news['news_description']);

      // Move that ADOdb pointer!
      $new_news_result->MoveNext();
    }
    // Close result set
    $new_news_result->Close();

    $oSmarty->assign('new_news_array', $aNewNews);
  }

  $oSmarty->assign('new_news_block', $new_news_block);

?>
