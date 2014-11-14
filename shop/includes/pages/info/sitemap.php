<?php
/* ----------------------------------------------------------------------
   $Id: sitemap.php,v 1.1 2007/06/07 16:45:18 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: sitemap.php,v 1.1 2004/02/16 07:13:17 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/info_sitemap.php';

  $aOption['template_main'] = $sTheme . '/system/sitemap.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  $sGroup = trim($_SESSION['member']->group['text']);
  $contents_cache_id = $sTheme . '|info|' . $sGroup . '|sitemap|' . $sLanguage;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

  if (!$smarty->isCached($aOption['template_main'], $contents_cache_id)) {

    $oSitemap = new oosCategoryTree;
    $oSitemap->setShowCategoryProductCount(false);

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['info'], $aFilename['info_sitemap']));

    // assign Smarty variables;
    $smarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif'
        )
    );

    $smarty->assign('sitemap', $oSitemap->buildTree());
  }
  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading'], $contents_cache_id));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main'], $contents_cache_id));
  $smarty->setCaching(false);

  // display the template
  require_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';

