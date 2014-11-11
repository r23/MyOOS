<?php
/* ----------------------------------------------------------------------
   $Id: subscribe_success.php,v 1.2 2008/10/03 15:38:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/newsletters_subscribe_success.php';

  $origin_href = oos_href_link($aModules['main'], $aFilename['main']);
  $_SESSION['newsletter'] = 1;

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aModules['newsletters'], $aFilename['newsletters']));
  $oBreadcrumb->add($aLang['navbar_title_2']);

  $aOption['template_main'] = $sTheme . '/modules/newsletters_subscribe_success.html';
  $aOption['page_heading'] = $sTheme . '/heading/success_page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'man_on_board.gif'
      )
  );
  $smarty->assign('origin_href', $origin_href);

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>