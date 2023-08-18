<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$bError = false;

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new oosUser();
    $_SESSION['user']->anonymous();
}

if (!isset($_SESSION['customer_2fa_id'])) {
    oos_redirect(oos_href_link($aContents['login']));
}

if ($_SESSION['login_count'] > 3) {
    oos_redirect(oos_href_link($aContents['403']));
}


// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/login_2fa_info.php';


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['login']));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['login_2fa_info']));
$sCanonical = oos_href_link($aContents['login'], '', false, true);


$aTemplate['page'] = $sTheme . '/page/login_2fa_info.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'    => $aLang['navbar_title'], 'robots'            => 'noindex,nofollow,noodp,noydir', 'login_active'    => 1, 'canonical'        => $sCanonical]
);

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
