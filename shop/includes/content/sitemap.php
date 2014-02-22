<?php
/* ----------------------------------------------------------------------
   $Id: sitemap.php 409 2013-06-11 15:53:40Z r23 $

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
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/info_sitemap.php';

$aTemplate['page'] = $sTheme . '/system/sitemap.tpl';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;

$contents_cache_id = $sTheme . '|sitemap|' . $sLanguage;

require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
}

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	$smarty->setCacheLifetime(24 * 3600);
}

if (!$smarty->isCached($aTemplate['page'], $contents_cache_id))
{

    $oSitemap = new oosCategoryTree();
    $oSitemap->setShowCategoryProductCount(false);

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title']);
    $sCanonical = oos_href_link($aContents['info_sitemap'], '', 'NONSSL', FALSE, TRUE);
    $sPagetitle = $aLang['heading_title'];
    
    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'heading_title' => $aLang['heading_title'],
            'pagetitle'         => htmlspecialchars($sPagetitle),
            'canonical'         => $sCanonical
        )
    );

    $smarty->assign('sitemap', $oSitemap->buildTree());
}

// display the template
$smarty->display($aTemplate['page'], $contents_cache_id);

