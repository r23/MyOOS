<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_password_forgotten.php,v 1.6 2003/02/19 12:06:02 simarilius 
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

  require 'includes/languages/' . $sLanguage . '/affiliate_password_forgotten.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $affiliate_affiliatetable = $oostable['affiliate_affiliate'];
    $sql = "SELECT affiliate_firstname, affiliate_lastname, affiliate_password, affiliate_id
            FROM $affiliate_affiliatetable
            WHERE affiliate_email_address = '" . oos_db_input($email_address) . "'";
    $check_affiliate_result = $dbconn->Execute($sql);
    if ($check_affiliate_result->RecordCount()) {
      $check_affiliate = $check_affiliate_result->fields;
      // Crypted password mods - create a new password, update the database and mail it to them
      $newpass = oos_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
      $crpted_password = oos_encrypt_password($newpass);

      $affiliate_affiliatetable = $oostable['affiliate_affiliate'];
      $dbconn->Execute("UPDATE $affiliate_affiliatetable
                    SET affiliate_password = '" . oos_db_input($crypted_password) . "'
                    WHERE affiliate_id = '" . $check_affiliate['affiliate_id'] . "'");
      oos_mail($check_affiliate['affiliate_firstname'] . " " . $check_affiliate['affiliate_lastname'], $email_address, $aLang['email_password_reminder_subject'], nl2br(sprintf($aLang['email_password_reminder_body'], $newpass)), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
      oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_affiliate'], 'info_message=' . urlencode($aLang['text_password_sent']), 'SSL', true, false));
    } else {
      oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_password_forgotten'], 'email=nonexistent', 'SSL'));
    }
  } else {

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_affiliate'], '', 'SSL'));
    $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_password_forgotten'], '', 'SSL'));

    $aOption['template_main'] = $sTheme . '/modules/affiliate_password_forgotten.html';
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
            'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'password_forgotten.gif'
        )
    );

    $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
    $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

    // display the template
    require 'includes/oos_display.php';
  }
?>
