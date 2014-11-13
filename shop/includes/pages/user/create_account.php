<?php
/* ----------------------------------------------------------------------
   $Id: create_account.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/user_create_account.php';

// start the session
if ( is_session_started() === FALSE ) oos_session_start();    
  
  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['user'], $aFilename['create_account']));

  $snapshot = count($_SESSION['navigation']->snapshot); 
  if (isset($_GET['email_address'])) {
    $email_address = oos_db_prepare_input($_GET['email_address']);
  }
  $account['entry_country_id'] = STORE_COUNTRY;

  ob_start();
  require 'js/form_check.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aOption['template_main'] = $sTheme . '/modules/user_create_account.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
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
  $smarty->assign('login_orgin_text', sprintf($aLang['text_origin_login'], oos_href_link($aModules['user'], $aFilename['login'], oos_get_all_get_parameters(), 'SSL')));

  $smarty->assign('newsletter_ids', array(0,1));
  $smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>