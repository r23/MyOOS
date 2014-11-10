<?php
/* ----------------------------------------------------------------------
   $Id: reviews_info.php,v 1.2 2008/01/22 07:35:32 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) {
    $_SESSION['navigation']->remove_current_page();
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  require 'includes/languages/' . $sLanguage . '/news_reviews_info.php';

// lets retrieve all $_GET keys and values..
  $get_parameters = oos_get_all_get_parameters(array('news_reviews_id'));
  $get_parameters = oos_remove_trailing($get_parameters);

  $news_reviewstable  = $oostable['news_reviews'];
  $news_reviews_descriptiontable  = $oostable['news_reviews_description'];
  $newstable  = $oostable['news'];
  $news_descriptiontable  = $oostable['news_description'];
  $sql = "SELECT nrd.news_reviews_text, nr.news_reviews_rating, nr.news_reviews_id, nr.news_id,
                 nr.customers_name, nr.date_added, nr.last_modified, nr.news_reviews_read,
                 n.news_id, nd.news_name, n.news_image
          FROM $news_reviewstable nr,
               $news_reviews_descriptiontable nrd,
               $newstable n,
               $news_descriptiontable nd
          WHERE nr.news_reviews_id  = '" . intval($_GET['news_reviews_id']) . "'
            AND nr.news_reviews_id  = nrd.news_reviews_id
            AND n.news_status = '1'
            AND nr.news_id = n.news_id
            AND n.news_id = nd.news_id
            AND nd.news_languages_id = '" . intval($nLanguageID) . "'";
  $reviews_result = $dbconn->Execute($sql);
  if (!$reviews_result->RecordCount()) oos_redirect(oos_href_link($aModules['news'], $aFilename['news_comments']));
  $reviews = $reviews_result->fields;

  $news_reviewstable  = $oostable['news_reviews'];
  $dbconn->Execute("UPDATE $news_reviewstable
                SET news_reviews_read  = news_reviews_read +1
                WHERE news_reviews_id  = '" . $reviews['news_reviews_id'] . "'");

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['news'], $aFilename['news_reviews'], $get_parameters));

  $aOption['template_main'] = $sTheme . '/modules/news_reviews_info.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

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
          'oos_heading_title' => sprintf($aLang['heading_title'], $reviews['news_name']),
          'oos_heading_image' => 'reviews.gif',

          'reviews'           => $reviews
     )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
