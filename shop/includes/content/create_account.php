<?php
/* ----------------------------------------------------------------------
   $Id: create_account.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account.php,v 1.59 2003/02/14 05:51:17 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['create_account']));

  $snapshot = count($_SESSION['navigation']->snapshot);
  if (isset($_GET['email_address'])) {
    $email_address = oos_db_prepare_input($_GET['email_address']);
  }
  $account['entry_country_id'] = STORE_COUNTRY;

  ob_start();
  require 'js/form_check.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/modules/user_create_account.tpl';
  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }
  $read = 'false';
  $smarty->assign('read', $read);
  $smarty->assign('oos_js', $javascript);

  // assign Smarty variables;
  $smarty->assign(
      array(
         'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
         'oos_heading_title' => $aLang['heading_title'],
         'oos_heading_image' => 'account.gif',

         ));

  $smarty->assign('account', $account);
  $smarty->assign('email_address', $email_address);

  if ((CUSTOMER_NOT_LOGIN == 'true') or (MAKE_PASSWORD == 'true')) {
    $show_password = false;
  } else {
    $show_password = 'true';
  }
  $smarty->assign('show_password', $show_password);

  $smarty->assign('snapshot', $snapshot);
  $smarty->assign('login_orgin_text', sprintf($aLang['text_origin_login'], oos_href_link($aContents['login'], oos_get_all_get_parameters(), 'SSL')));

  $smarty->assign('newsletter_ids', array(0,1));
  $smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));


// display the template
$smarty->display($aTemplate['page']);
