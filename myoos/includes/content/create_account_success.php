<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_success.php,v 1.29 2003/02/13 02:27:56 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/create_account_success.php';

$oBreadcrumb->add($aLang['navbar_title_1']);
$oBreadcrumb->add($aLang['navbar_title_2']);

if (count($_SESSION['navigation']->snapshot) > 0) {
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
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,follow,noodp,noydir',

		'origin_href' => $origin_href
      )
);


// display the template
$smarty->display($aTemplate['page']);
