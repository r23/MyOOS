<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account_success.php';

  $oBreadcrumb->add($aLang['navbar_title_1']);
  $oBreadcrumb->add($aLang['navbar_title_2']);

  if (count($_SESSION['navigation']->snapshot) > 0) {
    $origin_href = oos_href_link($_SESSION['navigation']->snapshot['file'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
    $_SESSION['navigation']->clear_snapshot();
  } else {
    $origin_href = oos_href_link($aContents['main']);
  }

  $aTemplate['page'] = $sTheme . '/page/user_create_account_success.html';
  $aTemplate['page_heading'] = $sTheme . '/heading/success_page_heading.html';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],

          'origin_href' => $origin_href
      )
  );

  $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading']));
  

  // display the template
$smarty->display($aTemplate['page']);
