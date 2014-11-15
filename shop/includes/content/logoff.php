<?php
/* ----------------------------------------------------------------------
   $Id: logoff.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: logoff.php,v 1.1.2.2 2003/05/13 23:20:53 wilt Exp $
   orig: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_logoff.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title']);

  unset($_SESSION['customer_id']);
  unset($_SESSION['customer_wishlist_link_id']);
  unset($_SESSION['customer_default_address_id']);
  unset($_SESSION['customer_gender']);
  unset($_SESSION['customer_first_name']);
  unset($_SESSION['customer_lastname']);
  unset($_SESSION['customer_country_id']);
  unset($_SESSION['customer_zone_id']);
  unset($_SESSION['comments']);
  unset($_SESSION['customer_max_order']);
  unset($_SESSION['gv_id']);
  unset($_SESSION['cc_id']);
  unset($_SESSION['man_key']);

  if (ACCOUNT_VAT_ID == 'true') {
    $_SESSION['customers_vat_id_status'] = 0;
  }

  $_SESSION['cart']->reset(); 
  $_SESSION['member']->default_member();

  $aOption['template_main'] = $sTheme . '/system/success.html';
  $aOption['page_heading'] = $sTheme . '/heading/success_page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

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
          'oos_heading_image' => 'man_on_board.gif',

          'file_default'      => $aContents['main']
      )
  );

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';
