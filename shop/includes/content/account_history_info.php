<?php
/* ----------------------------------------------------------------------
   $Id: account_history_info.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_history_info.php,v 1.94 2003/02/14 20:28:46 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  if (!isset($_GET['order_id'])) {
    oos_redirect(oos_href_link($aContents['account_history'], '', 'SSL'));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_history_info.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

  $orderstable = $oostable['orders'];
  $sql = "SELECT customers_id
          FROM $orderstable
          WHERE orders_id = '" . intval($_GET['order_id']) . "'";
  $customer_number = $dbconn->GetOne($sql);

  if ($customer_number != $_SESSION['customer_id']) {
    oos_redirect(oos_href_link($aContents['account_history'], '', 'SSL'));
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_history'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_3'], oos_href_link($aContents['account_history_info'], 'order_id=' . $_GET['order_id'], 'SSL'));

  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
  $oOrder = new order($_GET['order_id']);

  $aTemplate['page'] = $sTheme . '/modules/account_history_info.tpl';

  if (DOWNLOAD_ENABLED == 'true') {
    $aTemplate['download'] = $sTheme . '/modules/download.tpl';
  }

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

// assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'history.gif'
      )
  );

  $smarty->assign('order', $oOrder);
  $smarty->assign('currencies', $oCurrencies);

  $orders_statustable = $oostable['orders_status'];
  $orders_status_historytable = $oostable['orders_status_history'];
  $sql = "SELECT os.orders_status_name, osh.date_added, osh.comments
          FROM $orders_statustable os,
               $orders_status_historytable osh
          WHERE osh.orders_id = '" . intval($_GET['order_id']) . "'
            AND osh.orders_status_id = os.orders_status_id
            AND os.orders_languages_id = '" . intval($nLanguageID) . "'
          ORDER BY osh.date_added";
  $smarty->assign('statuses_array', $dbconn->GetAll($sql));

  if (DOWNLOAD_ENABLED == 'true') {
    require_once MYOOS_INCLUDE_PATH . '/includes/modules/downloads.php';
    $smarty->assign('download', $smarty->fetch($aTemplate['download']));
  }

// display the template
$smarty->display($aTemplate['page']);
