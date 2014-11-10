<?php
/* ----------------------------------------------------------------------
   $Id: contact.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_contact.php,v 1.3 2003/02/15 00:52:24 harley_vb
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

  if (!isset($_SESSION['affiliate_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_affiliate'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/affiliate_contact.php';

  $error = 'false';
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    if (oos_validate_is_email(trim($email))) {
      oos_mail(STORE_OWNER, AFFILIATE_EMAIL_ADDRESS, $aLang['email_subject'], $enquiry, $name, $email);
      oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_contact'], 'action=success'));
    } else {
      $error = 'true';
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_contact']));

  $affiliate_affiliatetable = $oostable['affiliate_affiliate'];
  $sql = "SELECT affiliate_firstname, affiliate_lastname, affiliate_email_address
          FROM $affiliate_affiliatetable
          WHERE affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'";
  $affiliate = $dbconn->GetRow($sql);


  $aOption['template_main'] = $sTheme . '/modules/affiliate_contact.html';
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
          'oos_heading_image' => 'contact_us.gif',

          'affiliate'         => $affiliate,
          'name'              => $name,
          'email'             => $email,
          'enquiry'           => $enquiry,
          'error'             => $error
      )
  );
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
