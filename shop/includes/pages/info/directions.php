<?php
/* ----------------------------------------------------------------------
   $Id: directions.php,v 1.1 2007/06/07 16:45:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $aOption['template_main'] = $sTheme . '/modules/directions.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  $contents_cache_id = $sTheme . '|info|directions|' . $sLanguage;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  if ( (USE_CACHE == 'true') && (!SID) ) {
    $oSmarty->caching = 2;
    $oSmarty->cache_lifetime = 24 * 3600;
  }

  if (!$oSmarty->is_cached($aOption['template_main'], $contents_cache_id)) {

    $sMapquest = 'http://www.mapquest.de/mq/maps/linkToMap.do?' .
                 'address=' . urlencode(strtoupper(STORE_STREET_ADDRESS)) .
                 '&amp;city=' . urlencode(strtoupper(STORE_CITY)) .
                 '&amp;Postcode=' . urlencode(strtoupper(STORE_POSTCODE)) .
                 '&amp;country=' . urlencode(strtoupper(STORE_ISO_639_2)) .
                 '&amp;cid=lfmaplink';

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['info'], $aFilename['info_directions']));

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif',

            'mapquest'          => $sMapquest
        )
    );

  }
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading'], $contents_cache_id));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main'], $contents_cache_id));
  $oSmarty->caching = false;

  // display the template
  require 'includes/oos_display.php';
?>