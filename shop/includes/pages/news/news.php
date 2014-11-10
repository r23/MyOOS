<?php
/* ----------------------------------------------------------------------
   $Id: news.php,v 1.2 2008/01/22 07:35:32 r23 Exp $

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

  require 'includes/languages/' . $sLanguage . '/news_news.php';

  $newstable  = $oostable['news'];
  $news_descriptiontable  = $oostable['news_description'];
  $sql = "SELECT n.news_id, n.news_image, n.news_date_added, n.news_added_by, n.news_status,
                 nd.news_name, nd.news_description, nd.news_url, nd.news_viewed
          FROM $newstable n,
               $news_descriptiontable nd
          WHERE n.news_status = '1'
            AND n.news_id = '" . intval($_GET['news_id']) . "'
            AND nd.news_id = n.news_id
            AND nd.news_languages_id  = '" . intval($nLanguageID) . "'";
  $news_info_result = $dbconn->Execute($sql);

  if (!$news_info_result->RecordCount()) {
    $aOption['template_main'] = $sTheme . '/system/info.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  } else {
    $aOption['template_main'] = $sTheme . '/modules/news_info.html';
    $aOption['page_heading'] = $sTheme . '/modules/news_heading.html';
  }

  $nPageType = OOS_PAGE_TYPE_NEWS;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  if (!$news_info_result->RecordCount()) {
    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'list.gif'
        )
    );
  } else {
    $news_descriptiontable  = $oostable['news_description'];
    $dbconn->Execute("UPDATE $news_descriptiontable
                  SET news_viewed  = news_viewed+1 
                  WHERE news_id = '" . intval($_GET['news_id']) . "' AND 
                        news_languages_id = '" . intval($nLanguageID) . "'");
    $news_info = $news_info_result->fields;
    $news_info['author_name'] = oos_get_news_author_name($news_info['news_added_by']);

    $reviews_total = 0;
    if ($oEvent->installed_plugin('reviews')) {
      $news_reviewstable  = $oostable['news_reviews'];
      $news_reviews_descriptiontable  = $oostable['news_reviews_description'];
      $sql = "SELECT COUNT(*) AS total
              FROM $news_reviewstable nr,
                   $news_reviews_descriptiontable nrd
              WHERE nr.news_id = '" . intval($_GET['news_id']) . "'
                AND nr.news_reviews_id = nrd.news_reviews_id
                AND nrd.news_reviews_languages_id = '" .intval($nLanguageID) . "'";
      $reviews = $dbconn->Execute($sql);
      $reviews_total = $reviews->fields['total'];
    }

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'news_info'      => $news_info,
            'reviews_total'  => $reviews_total
        )
    );
    $oSmarty->assign('redirect', oos_href_link($aModules['main'], $aFilename['redirect'], 'action=url&amp;goto=' . urlencode($news_info['news_url']), 'NONSSL', true, false));
  }

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
