<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_notifications.php,v 1.7 2003/02/14 05:51:27 hpdl
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

if (!$oEvent->installed_plugin('notify')) {
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
    oos_redirect(oos_href_link($aContents['login']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_product_notifications.php';

if (isset($_POST['action']) && ($_POST['action'] == 'update_notifications')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    (array)$products = $_POST['products'];
    $aRemove = [];
    for ($i=0, $n=is_countable($products) ? count($products) : 0; $i<$n; $i++) {
        if (is_numeric($products[$i])) {
            $aRemove[] = $products[$i];
        }
    }

    if (oos_is_not_null($aRemove)) {
        $products_notificationstable = $oostable['products_notifications'];
        $dbconn->Execute(
            "DELETE FROM $products_notificationstable
                        WHERE customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                              products_id IN (" . implode(',', $aRemove) . ")"
        );
    }

    oos_redirect(oos_href_link($aContents['product_notifications']));
} elseif (isset($_POST['action']) && ($_POST['action'] == 'global_notify')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    if (isset($_POST['global']) && ($_POST['global'] == 'enable')) {
        $customers_infotable = $oostable['customers_info'];
        $dbconn->Execute(
            "UPDATE $customers_infotable
                        SET global_product_notifications = '1'
                        WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'"
        );
    } else {
        $customers_infotable   = $oostable['customers_info'];
        $sql = "SELECT COUNT(*) AS total
              FROM $customers_infotable
              WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'
                AND global_product_notifications = '1'";
        $check_result = $dbconn->Execute($sql);
        if ($check_result->fields['total'] > 0) {
            $customers_infotable = $oostable['customers_info'];
            $dbconn->Execute(
                "UPDATE $customers_infotable
                          SET global_product_notifications = '0'
                          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'"
            );
        }
    }
    oos_redirect(oos_href_link($aContents['product_notifications']));
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['product_notifications']));

$aTemplate['page'] = $sTheme . '/page/user_product_notifications.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,nofollow,noodp,noydir']
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

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");


// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
