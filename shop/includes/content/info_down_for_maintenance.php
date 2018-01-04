<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   WebMakers.com Added: Down for Maintenance No Store
   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (!$oEvent->installed_plugin('down_for_maintenance')) {
	oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/info_down_for_maintenance.php';

$aTemplate['page'] = $sTheme . '/page/coming-soon.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;


require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['info_down_for_maintenance']));
$sCanonical = oos_href_link($aContents['info_down_for_maintenance'], '', FALSE, TRUE);
	
// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,nofollow,noodp,noydir',
		'canonical'		=> $sCanonical
    )
);

  
// display the template
$smarty->display($aTemplate['page']);
