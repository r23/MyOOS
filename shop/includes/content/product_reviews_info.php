<?php
/* ----------------------------------------------------------------------
   $Id: product_reviews_info.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) {
    oos_redirect(oos_href_link($aContents['main']));
  }

  if (!isset($_GET['reviews_id'])) {
    oos_redirect(oos_href_link($aContents['reviews_reviews']));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews_product_info.php';

// lets retrieve all $_GET keys and values..
  $get_parameters = oos_get_all_get_parameters(array('reviews_id'));
  $get_parameters = oos_remove_trailing($get_parameters);

  $reviewstable  = $oostable['reviews'];
  $productstable = $oostable['products'];
  $reviews_descriptiontable  = $oostable['reviews_description'];
  $products_descriptiontable = $oostable['products_description'];
  $sql = "SELECT rd.reviews_text, r.reviews_rating, r.reviews_id, r.products_id,
                 r.customers_name, r.date_added, r.last_modified, r.reviews_read,
                 p.products_id, pd.products_name, p.products_model, p.products_image
          FROM $reviewstable r,
               $reviews_descriptiontable rd,
               $productstable p,
               $products_descriptiontable pd
          WHERE r.reviews_id = '" . intval($_GET['reviews_id']) . "'
            AND r.reviews_id = rd.reviews_id
            AND rd.reviews_languages_id = '" . intval($nLanguageID) . "'
            AND r.products_id = p.products_id
            AND p.products_status >= '1'
            AND p.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
  $reviews_result = $dbconn->Execute($sql);
  if (!$reviews_result->RecordCount()) oos_redirect(oos_href_link($aContents['reviews_reviews']));
  $reviews = $reviews_result->fields;

  $dbconn->Execute("UPDATE " . $oostable['reviews'] . "
                SET reviews_read = reviews_read+1
                WHERE reviews_id = '" . $reviews['reviews_id'] . "'");

  // add the products model or products_name to the breadcrumb trail
  // links breadcrumb
  if (SHOW_PRODUCTS_MODEL == 'true') {
    $oBreadcrumb->add($reviews['products_model'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $reviews['products_id']));
  } else {
    $oBreadcrumb->add($reviews['products_name'], oos_href_link($aContents['product_info'], 'category=' . $category . '&amp;products_id=' . $reviews['products_id']));
  }
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['product_reviews'], $get_parameters));

  $aTemplate['page'] = $sTheme . '/modules/product_reviews_info.tpl';

  $nPageType = OOS_PAGE_TYPE_REVIEWS;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  $smarty->assign(
       array(
           'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
           'oos_heading_title' => sprintf($aLang['heading_title'], $reviews['products_name']),
           'oos_heading_image' => 'reviews.gif',

           'reviews' => $reviews
       )
  );

// display the template
$smarty->display($aTemplate['page']);
