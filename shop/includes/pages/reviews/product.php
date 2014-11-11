<?php
/* ----------------------------------------------------------------------
   $Id: product.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

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

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aModules['reviews'], $aFilename['reviews_reviews']));
  }

  require 'includes/languages/' . $sLanguage . '/reviews_product.php';

  // lets retrieve all $_GET keys and values..
  $get_params = oos_get_all_get_parameters(array('reviews_id'));
  $get_params = oos_remove_trailing($get_params);

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $sql = "SELECT pd.products_name, p.products_model
          FROM $products_descriptiontable pd LEFT JOIN
               $productstable p ON pd.products_id = p.products_id
          WHERE pd.products_languages_id = '" .  intval($nLanguageID) . "'
            AND p.products_status >= '1'
            AND pd.products_id = '" . intval($nProductsId) . "'";
  $product_info_result = $dbconn->Execute($sql);
  if (!$product_info_result->RecordCount()) oos_redirect(oos_href_link($aModules['reviews'], $aFilename['reviews_reviews']));
  $product_info = $product_info_result->fields;

  $reviewstable  = $oostable['reviews'];
  $sql = "SELECT reviews_rating, reviews_id, customers_name, date_added, reviews_read
          FROM $reviewstable
          WHERE products_id = '" . intval($nProductsId) . "'
          ORDER BY reviews_id DESC";
  $reviews_result = $dbconn->Execute($sql);
  $aReviews = array();
  while ($reviews = $reviews_result->fields) {
    $aReviews[] = array('rating' => $reviews['reviews_rating'],
                        'id' => $reviews['reviews_id'],
                        'customers_name' => $reviews['customers_name'],
                        'date_added' => oos_date_short($reviews['date_added']),
                        'read' => $reviews['reviews_read']);
    $reviews_result->MoveNext();
  }

  // add the products model or products_name to the breadcrumb trail
  // links breadcrumb
  if (SHOW_PRODUCTS_MODEL == 'true') {
    $oBreadcrumb->add($product_info['products_model'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
  } else {
    $oBreadcrumb->add($product_info['products_name'], oos_href_link($aModules['products'], $aFilename['product_info'], 'cPath=' . $cPath . '&amp;products_id=' . $nProductsId));
  }
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['reviews'], $aFilename['product_reviews'], $get_params));

  $aOption['template_main'] = $sTheme . '/modules/product_reviews.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

  $nPageType = OOS_PAGE_TYPE_REVIEWS;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => sprintf($aLang['heading_title'], $product_info['products_name']),
          'oos_heading_image' => 'reviews.gif',

          'oos_reviews_array' => $aReviews
      )
  );

  $smarty->assign('oosPageNavigation', $smarty->fetch($aOption['page_navigation']));
  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>