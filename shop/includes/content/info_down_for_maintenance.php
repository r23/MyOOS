<?php
/* ----------------------------------------------------------------------
   $Id: down_for_maintenance.php,v 1.1 2007/06/07 16:45:18 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   WebMakers.com Added: Down for Maintenance No Store
   Written by Linda McGrath osCOMMERCE@WebMakers.com
   http://www.thewebmakerscorner.com
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('down_for_maintenance')) {
    oos_redirect(oos_href_link($aContents['main']));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/info_down_for_maintenance.php';

  $aTemplate['page'] = $sTheme . '/page/info.html';
  $aTemplate['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;
  $contents_cache_id = $sTheme . '|down_for_maintenance|' . $sLanguage;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

  if (!$smarty->isCached($aTemplate['page'], $contents_cache_id)) {

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['info_down_for_maintenance']));

    // assign Smarty variables;
    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title']
        )
    );
  }
  $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading'], $contents_cache_id));
  
  $smarty->setCaching(false);

  // display the template
$smarty->display($aTemplate['page']);

