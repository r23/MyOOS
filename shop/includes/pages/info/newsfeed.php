<?php
/* ----------------------------------------------------------------------
   $Id: newsfeed.php,v 1.1 2007/06/07 16:45:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (DISPLAY_NEWSFEED != 'true') {
    $_SESSION['navigation']->remove_current_page();
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  require 'includes/languages/' . $sLanguage . '/info_newsfeed.php';

  $aOption['template_main'] = $sTheme . '/system/info.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;
  $contents_cache_id = $sTheme . '|info|newsfeed|' . $sLanguage;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  if ( (USE_CACHE == 'true') && (!SID) ) {
    $oSmarty->caching = 2;
    $oSmarty->cache_lifetime = 30 * 24 * 3600;
  }

  if (!$oSmarty->is_cached($aOption['template_main'], $contents_cache_id)) {
    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['info'], $aFilename['info_newsfeed']));

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif'
        )
    );
  }
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading'], $contents_cache_id));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main'], $contents_cache_id));
  $oSmarty->caching = false;

  // display the template
  require 'includes/oos_display.php';
?>
