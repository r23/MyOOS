<?php
/* ----------------------------------------------------------------------
   $Id: reviews_write.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_reviews_write.php,v 1.51 2003/02/13 04:23:23 hpdl 
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

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/news_reviews_write.php';

// lets retrieve all $_GET keys and values..
  $get_parameters = oos_get_all_get_parameters();
  $get_parameters_back = oos_get_all_get_parameters(array('news_reviews_id ')); // for back button
  $get_parameters = oos_remove_trailing($get_parameters);
  if (oos_is_not_null($get_parameters_back)) {
    $get_parameters_back = oos_remove_trailing($get_parameters_back);
  } else {
    $get_parameters_back = $get_parameters;
  }

  $newstable  = $oostable['news'];
  $news_descriptiontable  = $oostable['news_description'];
  $sql = "SELECT nd.news_name, n.news_image
          FROM $newstable n,
               $news_descriptiontable nd
          WHERE n.news_id = '" . intval($_GET['news_id']) . "'
            AND nd.news_id = n.news_id
            AND nd.news_languages_id  = '" . intval($nLanguageID) . "'
            AND n.news_status = '1'";
  $news_result = $dbconn->Execute($sql);
  $valid_news = ($news_result->RecordCount() > 0);
  $news_info = $news_result->fields;

  if (isset($_GET['action']) && $_GET['action'] == 'process') {
    if ($valid_news == true) { // We got to the process but it is an illegal product, don't write
      $customerstable  = $oostable['customers'];
      $sql = "SELECT customers_firstname, customers_lastname
              FROM $customerstable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $customer = $dbconn->Execute($sql);
      $customer_values = $customer->fields;
      $date_now = date('Ymd');

      $firstname = ltrim($customer_values['customers_firstname']);
      $firstname = substr($firstname, 0, 1);
      $customers_name = $firstname . '. ' . $customer_values['customers_lastname'];

      $news_reviewstable  = $oostable['news_reviews'];
      $dbconn->Execute("INSERT INTO $news_reviewstable
                        (news_id,
                         customers_id,
                         customers_name,
                         news_reviews_rating , 
                         date_added) VALUES ('" . intval($_GET['news_id']) . "',
                                             '" . intval($_SESSION['customer_id']) . "',
                                             '" . oos_db_input($customers_name) . "',
                                             '" . oos_db_input($rating) . "',
                                             now())");
      $insert_id = $dbconn->Insert_ID();
      $news_reviews_descriptiontable  = $oostable['news_reviews_description'];
      $dbconn->Execute("INSERT INTO $news_reviews_descriptiontable
                       (news_reviews_id ,
                        news_reviews_languages_id ,
                        news_reviews_text ) VALUES ('" . intval($insert_id) . "',
                                                    '" . intval($nLanguageID) . "',
                                                    '" . oos_db_input($review) . "')");
    }
    oos_redirect(oos_href_link($aModules['news'], $aFilename['news_reviews'], $get_parameters));
  }


  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['news'], $aFilename['news_reviews'], $get_parameters));

  $customerstable  = $oostable['customers'];
  $sql = "SELECT customers_firstname, customers_lastname
          FROM $customerstable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
  $customer_info_result = $dbconn->Execute($sql);
  $customer_info = $customer_info_result->fields;

  ob_start();
  require 'js/news_reviews_write.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aOption['template_main'] = $sTheme . '/modules/news_reviews_write.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_PRODUCTS;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }


  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_js'            => $javascript,

          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'reviews.gif',

          'valid_news'        => $valid_news,
          'news_info'         => $news_info,
          'customer_info'     => $customer_info
      )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
