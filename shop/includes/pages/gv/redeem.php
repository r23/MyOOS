<?php
/* ----------------------------------------------------------------------
   $Id: redeem.php,v 1.2 2007/12/20 21:26:33 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_redeem.php,v 1.3.2.1 2003/04/18 15:52:40 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001, 2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/gv_redeem.php';

  $bError = true;
// check for a voucher number in the url
  if ( (isset($_GET['gv_no']) && !empty($_GET['gv_no'])) ) {
    $couponstable = $oostable['coupons'];
    $coupon_email_tracktable = $oostable['coupon_email_track'];
    $sql = "SELECT c.coupon_id, c.coupon_amount
            FROM $couponstable c,
                 $coupon_email_tracktable et
            WHERE coupon_code = '" . oos_db_input($_GET['gv_no']) . "'
              AND c.coupon_id = et.coupon_id";
    $gv_result = $dbconn->Execute($sql);
    if ($gv_result->RecordCount() >0) {
      $coupon = $gv_result->fields;
      $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
      $sql = "SELECT coupon_id
              FROM $coupon_redeem_tracktable
              WHERE coupon_id = '" . oos_db_input($coupon['coupon_id']) . "'";
      $redeem_result = $dbconn->Execute($sql);
      if ($redeem_result->RecordCount() == 0 ) {
        // check for require_onced session variables
        $_SESSION['gv_id'] = $coupon['coupon_id'];
        $bError = false;
      }
    }
  } else {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }
  if ( (!$bError) && (isset($_SESSION['customer_id'])) ) {
// Update redeem status
    $remote_addr = oos_server_get_remote();
    $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
    $gv_result = $dbconn->Execute("INSERT INTO $coupon_redeem_tracktable
                            (coupon_id,
                             customer_id,
                             redeem_date,
                             redeem_ip) VALUES ('" . $coupon['coupon_id'] . "',
                                                '" . intval($_SESSION['customer_id']) . "',
                                                now(),
                                                '" . oos_db_input($remote_addr) . "')");
    $couponstable = $oostable['coupons'];
    $gv_update = $dbconn->Execute("UPDATE $couponstable
                               SET coupon_active = 'N' 
                               WHERE coupon_id = '" . $coupon['coupon_id'] . "'");
    oos_gv_account_update($_SESSION['customer_id'], $_SESSION['gv_id']);
    unset($_SESSION['gv_id']);
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title']);

  // if we get here then either the url gv_no was not set or it was invalid
  // so output a message.
  $sMessage = sprintf($aLang['text_valid_gv'], $oCurrencies->format($coupon['coupon_amount']));
  if ($bError) {
    $sMessage = $aLang['text_invalid_gv'];
  }

  $aOption['template_main'] = $sTheme . '/modules/redeem.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'specials.gif',

          'message'           => $sMessage
      )
  );

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  require 'includes/oos_display.php';
?>
