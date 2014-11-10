<?php
/* ----------------------------------------------------------------------
   $Id: old_contact_us.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: contact_us.php,v 1.39 2003/02/14 05:51:15 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/main_contact_us.php';

  $error = 'false';
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    if (oos_validate_is_email(trim($email))) {
      oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['email_subject'], $enquiry, $name, $email);
      oos_redirect(oos_href_link($aModules['main'], $aFilename['contact_us'], 'action=success'));
    } else {
      $error = 'true';
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['main'], $aFilename['contact_us']));

  $aOption['template_main'] = $sTheme . '/system/old_contact_us.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'contact_us.gif',

          'error'             => $error
      )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
