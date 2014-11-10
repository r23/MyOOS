<?php
/* ----------------------------------------------------------------------
   $Id: reviews.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) {
    $_SESSION['navigation']->remove_current_page();
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  if (isset($_GET['news_id'])) {
    $oos_news_id = intval($_GET['news_id']);
  } else {
    oos_redirect(oos_href_link($aModules['news'], $aFilename['news_comments']));
  }

  require 'includes/languages/' . $sLanguage . '/news_reviews.php';

  // lets retrieve all $_GET keys and values..
  $get_parameters = oos_get_all_get_parameters(array('news_reviews_id'));
  $get_parameters = oos_remove_trailing($get_parameters);

  $news_descriptiontable  = $oostable['news_description'];
  $newstable  = $oostable['news'];
  $sql = "SELECT nd.news_name
          FROM $news_descriptiontable nd LEFT JOIN
               $newstable n ON nd.news_id = n.news_id
          WHERE nd.news_languages_id  = '" .  intval($nLanguageID) . "'
            AND n.news_status = '1'
            AND nd.news_id = '" . intval($oos_news_id) . "'";
  $news_info_result = $dbconn->Execute($sql);
  if (!$news_info_result->RecordCount()) oos_redirect(oos_href_link($aModules['news'], $aFilename['news_comments']));
  $news_info = $news_info_result->fields;

  $news_reviewstable  = $oostable['news_reviews'];
  $sql = "SELECT news_reviews_rating, news_reviews_id, customers_name, date_added, news_reviews_read
          FROM $news_reviewstable
          WHERE news_id = '" . intval($oos_news_id) . "'
          ORDER BY news_reviews_id  DESC";
  $reviews_result = $dbconn->Execute($sql);
  $aRreviews = array();
  while ($reviews = $reviews_result->fields) {
    $aRreviews[] = array('rating' => $reviews['news_reviews_rating'],
                         'id' => $reviews['news_reviews_id'],
                         'customers_name' => $reviews['customers_name'],
                         'date_added' => oos_date_short($reviews['date_added']),
                         'read' => $reviews['news_reviews_read']);
    $reviews_result->MoveNext();
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['news'], $aFilename['news_reviews'], $get_parameters));

  $aOption['template_main'] = $sTheme . '/modules/news_reviews.html';
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
          'oos_heading_title' => sprintf($aLang['heading_title'], $news_info['news_name']),
          'oos_heading_image' => 'reviews.gif',

          'oos_reviews_array' => $aRreviews
      )
  );

  $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
