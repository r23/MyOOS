<?php
/* ----------------------------------------------------------------------
   $Id: affiliate.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_affiliate.php,v 1.8 2003/02/19 00:28:16 harley_vb
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('affiliate')) {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  require 'includes/languages/' . $sLanguage . '/affiliate_affiliate.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    oos_session_regenerate_id();

    // Check if username exists
    $affiliate_affiliatetable = $oostable['affiliate_affiliate'];
    $sql = "SELECT affiliate_id, affiliate_firstname, affiliate_password, affiliate_email_address 
            FROM $affiliate_affiliatetable
            WHERE affiliate_email_address = '" . oos_db_input($affiliate_username) . "'";
    $check_affiliate_result = $dbconn->Execute($sql);
    if (!$check_affiliate_result->RecordCount()) {
      $_GET['login'] = 'fail';
    } else {
      $check_affiliate = $check_affiliate_result->fields;
      // Check that password is good
      if (!oos_validate_password($affiliate_password, $check_affiliate['affiliate_password'])) {
        $_GET['login'] = 'fail';
      } else {
        $_SESSION['affiliate_id'] = intval($check_affiliate['affiliate_id']);

        $date_now = date('Ymd');

        $affiliate_affiliatetable = $oostable['affiliate_affiliate'];
        $dbconn->Execute("UPDATE $affiliate_affiliatetable
                      SET affiliate_date_of_last_logon = now(), 
                          affiliate_number_of_logons = affiliate_number_of_logons + 1 
                      WHERE affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'");

        oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_summary'],'','SSL'));
      }
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_affiliate'], '', 'SSL'));

  $info_message = '';
  if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {
    $info_message = $aLang['text_login_error'];
  }

  $aOption['template_main'] = $sTheme . '/modules/affiliate_affiliate.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_AFFILIATE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'login.gif',

          'info_message'      => $info_message
      )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
