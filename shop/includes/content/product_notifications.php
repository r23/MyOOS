<?php
/* ----------------------------------------------------------------------
   $Id: product_notifications.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_notifications.php,v 1.7 2003/02/14 05:51:27 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


  if (!$oEvent->installed_plugin('notify')) {
    oos_redirect(oos_href_link($aContents['main']));
  }

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_product_notifications.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'update_notifications')) {
    (array)$products = $_POST['products'];
    $aRemove = array();
    for ($i=0, $n=count($products); $i<$n; $i++) {
      if (is_numeric($products[$i])) {
        $aRemove[] = $products[$i];
      }
    }

    if (oos_is_not_null($aRemove)) {
      $products_notificationstable = $oostable['products_notifications'];
      $dbconn->Execute("DELETE FROM $products_notificationstable
                        WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                              products_id IN (" . implode(',', $aRemove) . ")");
    }

    oos_redirect(oos_href_link($aContents['product_notifications'], '', 'SSL'));

  } elseif (isset($_GET['action']) && ($_GET['action'] == 'global_notify')) {
    if (isset($_POST['global']) && ($_POST['global'] == 'enable')) {
      $customers_infotable = $oostable['customers_info'];
      $dbconn->Execute("UPDATE $customers_infotable
                        SET global_product_notifications = '1'
                        WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'");
    } else {
      $customers_infotable   = $oostable['customers_info'];
      $sql = "SELECT COUNT(*) AS total
              FROM $customers_infotable
              WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'
                AND global_product_notifications = '1'";
      $check_result = $dbconn->Execute($sql);
      if ($check_result->fields['total'] > 0) {
        $customers_infotable = $oostable['customers_info'];
        $dbconn->Execute("UPDATE $customers_infotable
                          SET global_product_notifications = '0'
                          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'");
      }
    }

    oos_redirect(oos_href_link($aContents['product_notifications'], '', 'SSL'));
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['product_notifications'], '', 'SSL'));

  $aTemplate['page'] = $sTheme . '/modules/user_product_notifications.tpl';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'account.gif'
      )
  );

  $customers_infotable = $oostable['customers_info'];
  $sql = "SELECT global_product_notifications
          FROM $customers_infotable
          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
  $global_status_result = $dbconn->Execute($sql);
  $global_status = $global_status_result->fields;
  $smarty->assign('global_status', $global_status);

  $products_descriptionstable  = $oostable['products_description'];
  $products_notificationstable = $oostable['products_notifications'];
  $sql = "SELECT pd.products_id, pd.products_name
          FROM $products_descriptionstable pd,
               $products_notificationstable pn
          WHERE pn.customers_id = '" . intval($_SESSION['customer_id']) . "'
            AND pn.products_id = pd.products_id
            AND pd.products_languages_id = '" . intval($nLanguageID) . "'
          ORDER BY pd.products_name";
  $smarty->assign('products_array', $dbconn->GetAll($sql));


// display the template
$smarty->display($aTemplate['page']);
