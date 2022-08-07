<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_history_info.php,v 1.94 2003/02/14 20:28:46 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}


// start the session
if ($session->hasStarted() === false) {
    $session->start();
}


if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }
    $_SESSION['navigation']->set_snapshot();
    $_SESSION['guest_login'] = 'off';
    oos_redirect(oos_href_link($aContents['login']));
}

if (!isset($_GET['order_id'])) {
    oos_redirect(oos_href_link($aContents['account_history']));
}

$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_history_info.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

$orderstable = $oostable['orders'];
$sql = "SELECT customers_id
          FROM $orderstable
          WHERE orders_id = '" . intval($_GET['order_id']) . "'";
$customer_number = $dbconn->GetOne($sql);

if ($customer_number != $_SESSION['customer_id']) {
    oos_redirect(oos_href_link($aContents['account_history']));
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_history'], 'page=' . $nPage));
$oBreadcrumb->add($aLang['navbar_title_3'], oos_href_link($aContents['account_history_info'], 'order_id=' . intval($_GET['order_id'])));

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';
$oOrder = new order($_GET['order_id']);

$aTemplate['page'] = $sTheme . '/page/account_history_info.html';

if (DOWNLOAD_ENABLED == 'true') {
    $aTemplate['download'] = $sTheme . '/page/download.html';
}

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    array(
        'breadcrumb'        => $oBreadcrumb->trail(),
        'heading_title'     => $aLang['heading_title'],
        'robots'            => 'noindex,nofollow,noodp,noydir',
        'account_active'    => 1,
        'page'              => $nPage
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
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/downloads.php';
    $smarty->assign('download', $smarty->fetch($aTemplate['download']));
}

  // display the template
$smarty->display($aTemplate['page']);
