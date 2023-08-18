<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_success.php,v 1.29 2003/02/13 02:27:56 hpdl
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
    oos_redirect(oos_href_link($aContents['login']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/create_account_success.php';

$oBreadcrumb->add($aLang['navbar_title_1']);
$oBreadcrumb->add($aLang['navbar_title_2']);

if ((is_countable($_SESSION['navigation']->snapshot) ? count($_SESSION['navigation']->snapshot) : 0) > 0) {
    $origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get']);
    $_SESSION['navigation']->clear_snapshot();
} else {
    $origin_href = oos_href_link($aContents['home']);
}

$aTemplate['page'] = $sTheme . '/page/create_account_success.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$smarty->assign('thank_you', sprintf($aLang['text_main'], oos_href_link($aContents['contact_us']), oos_href_link($aContents['contact_us'])));

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,follow,noodp,noydir', 'origin_href' => $origin_href]
);


// display the template
$smarty->display($aTemplate['page']);
