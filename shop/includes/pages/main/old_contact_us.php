<?php
/* ----------------------------------------------------------------------
   $Id: old_contact_us.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

  include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main_contact_us.php';

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

  include_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'contact_us.gif',

          'error'             => $error
      )
  );

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  include_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';

