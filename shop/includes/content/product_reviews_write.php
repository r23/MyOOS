<?php
/* ----------------------------------------------------------------------
   $Id: product_reviews_write.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('reviews')) {
    oos_redirect(oos_href_link($aContents['main']));
  }

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);
  } else {
    oos_redirect(oos_href_link($aContents['main']));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews_product_write.php';

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $sql = "SELECT pd.products_name, p.products_image
          FROM $productstable p,
               $products_descriptiontable pd
          WHERE p.products_id = '" . intval($nProductsId) . "'
            AND pd.products_id = p.products_id
            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
            AND p.products_status >= '1'";
  $product_result = $dbconn->Execute($sql);
  $valid_product = ($product_result->RecordCount() > 0);
  $product_info = $product_result->fields;

  if (isset($_GET['action']) && $_GET['action'] == 'process') {
    if ($valid_product == true) { // We got to the process but it is an illegal product, don't write
      $customersstable = $oostable['customers'];
      $sql = "SELECT customers_firstname, customers_lastname
              FROM $customersstable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $customer = $dbconn->Execute($sql);
      $customer_values = $customer->fields;
      $date_now = date('Ymd');

      $firstname = ltrim($customer_values['customers_firstname']);
      $firstname = substr($firstname, 0, 1);
      $customers_name = $firstname . '. ' . $customer_values['customers_lastname'];

      $reviewstable  = $oostable['reviews'];
      $dbconn->Execute("INSERT INTO $reviewstable
                  (products_id,
                   customers_id,
                   customers_name,
                   reviews_rating,
                   date_added) VALUES ('" . intval($nProductsId) . "',
                                       '" . intval($_SESSION['customer_id']) . "',
                                       '" . oos_db_input($customers_name) . "',
                                       '" . oos_db_input($rating) . "',
                                       now())");
      $insert_id = $dbconn->Insert_ID();
      $reviews_descriptiontable  = $oostable['reviews_description'];
      $dbconn->Execute("INSERT INTO $reviews_descriptiontable
                  (reviews_id,
                   reviews_languages_id,
                   reviews_text) VALUES ('" . intval($insert_id) . "',
                                         '" . intval($nLanguageID) . "',
                                         '" . oos_db_input($review) . "')");

      $email_subject = 'Review: ' . $product_info['products_name'];

      $email_text = "\n";
      $email_text .= "Firstname: ". $customer_values['customers_firstname'] . "\n";
      $email_text .= "Lastname:  ". $customer_values['customers_lastname'] . "\n";
      $email_text .= "\n";
      $email_text .= "Text:         ". $review . "\n";

      oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $email_subject, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

// clear cache
      require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
      $smarty =& new Template;

      $sLocaleDir = $smarty->template_dir;
      $aSkins = array();

      if (is_dir($sLocaleDir)) {
        if ($dh = opendir($sLocaleDir)) {
          while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..' || $file == 'CVS' || $file == 'default' || filetype($sLocaleDir . $file) == 'file' ) continue;
            if (filetype(realpath($sLocaleDir . $file)) == 'dir') {
              $aSkins[] = $file;
            }
          }
          closedir($dh);
        }
      }

      sort($aSkins);

      foreach ($aSkins as $sName) {
        $smarty->clear_cache(null, $sName.'|products|reviews');
      }

    }
    oos_redirect(oos_href_link($aContents['product_reviews'], $get_parameters));
  }

// lets retrieve all $_GET keys and values
  $get_parameters = oos_get_all_get_parameters();
  $get_parameters_back = oos_get_all_get_parameters(array('reviews_id')); // for back button
  $get_parameters = oos_remove_trailing($get_parameters);
  if (oos_is_not_null($get_parameters_back)) {
    $get_parameters_back = oos_remove_trailing($get_parameters_back); //remove trailing &
  } else {
    $get_parameters_back = $get_parameters;
  }

  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['product_reviews'], $get_parameters));

  $customerstable = $oostable['customers'];
  $sql = "SELECT customers_firstname, customers_lastname
          FROM $customerstable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
  $customer_info_result = $dbconn->Execute($sql);
  $customer_info = $customer_info_result->fields;

  ob_start();
  require 'js/product_reviews_write.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/modules/product_reviews_write.tpl';

  $nPageType = OOS_PAGE_TYPE_REVIEWS;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  $smarty->assign(
      array(
          'oos_breadcrumb'   => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'reviews.gif',

          'oos_js'            => $javascript,

          'valid_product'     => $valid_product,
          'product_info'      => $product_info,
          'customer_info'     => $customer_info
      )
  );


// display the template
$smarty->display($aTemplate['page']);
