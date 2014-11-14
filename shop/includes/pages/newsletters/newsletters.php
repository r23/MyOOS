<?php
/* ----------------------------------------------------------------------
   $Id: newsletters.php,v 1.1 2007/06/07 16:50:51 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Newsletter Module
   P&G developmment

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   Copyright (c) 2000,2001 The Exchange Project
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  if(!defined('OOS_VALID_MOD'))die('Direct Access to this location is not allowed.');

  include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/newsletters_newsletters.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    if (!oos_validate_is_email($email_address)) {
      oos_redirect(oos_href_link($aModules['newsletters'], $aFilename['newsletters'], 'email=nonexistent', 'SSL'));
    } else {

      $customerstable = $oostable['customers'];
      $sql = "SELECT customers_firstname, customers_lastname, customers_id
              FROM " .$customerstable . "
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
      $check_customer_result = $dbconn->Execute($sql);

      if ($check_customer_result->RecordCount()) {
        $check_customer = $check_customer_result->fields;

        $customerstable = $oostable['customers'];
        $dbconn->Execute("UPDATE $customerstable
                      SET customers_newsletter = '1'
                      WHERE customers_id = '" . $check_customer['customers_id'] . "'");
        oos_redirect(oos_href_link($aModules['newsletters'], $aFilename['newsletters_subscribe_success']));
      } else {
        $maillisttable = $oostable['maillist'];
        $sql = "SELECT customers_firstname
                FROM " . $maillisttable . "
                WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
        $check_mail_customer_result = $dbconn->Execute($sql);
        if ($check_mail_customer_result->RecordCount()) {
          $maillisttable = $oostable['maillist'];
          $dbconn->Execute("UPDATE " . $maillisttable . "
                            SET customers_newsletter = '1'
                            WHERE customers_email_address = '" . oos_db_input($email_address) . "'");
          oos_redirect(oos_href_link($aModules['newsletters'], $aFilename['newsletters_subscribe_success']));
        } else {
          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_email_address' => $email_address,
                                  'customers_newsletter' => 1);
          oos_db_perform($oostable['maillist'], $sql_data_array);
          oos_redirect(oos_href_link($aModules['newsletters'], $aFilename['newsletters_subscribe_success']));
        }
      }
    }
  } else {

    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aModules['newsletters'], $aFilename['newsletters'], '', 'SSL'));

    $aOption['template_main'] = $sTheme . '/modules/newsletters.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType = OOS_PAGE_TYPE_SERVICE;

    include_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      include_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'password_forgotten.gif'
        )
    );

    $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
    $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

    // display the template
    include_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';
  }
