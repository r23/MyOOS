<?php
/* ----------------------------------------------------------------------

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
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/newsletters_subscription_center.php';

  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_firstname, customers_lastname, customers_id
            FROM $customerstable
            WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($sql);
    if ($check_customer_result->RecordCount()) {
      $check_customer = $check_customer_result->fields;

      $customerstable = $oostable['customers'];
      $dbconn->Execute("UPDATE $customerstable
                    SET customers_newsletter = '0'
                    WHERE customers_id = '" . $check_customer['customers_id'] . "'");
      oos_redirect(oos_href_link($aContents['newsletters_unsubscribe_success']));
    } else {
      $maillisttable = $oostable['maillist'];
      $sql = "SELECT customers_firstname
              FROM $maillisttable
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
      $check_mail_customer_result = $dbconn->Execute($sql);
      if ($check_mail_customer_result->RecordCount()) {
        $maillisttable = $oostable['maillist'];
        $dbconn->Execute("UPDATE $maillisttable
                          SET customers_newsletter = '0'
                          WHERE customers_email_address = '" . oos_db_input($email_address) . "'");
        oos_redirect(oos_href_link($aContents['newsletters_unsubscribe_success']));
      }
    }
    oos_redirect(oos_href_link($aContents['subscription_center'], 'email=nonexistent', 'SSL'));
  } else {

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['newsletters']));

    $aTemplate['page'] = $sTheme . '/page/subscription_center.html';
    $aTemplate['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType = OOS_PAGE_TYPE_SERVICE;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title']
        )
    );

    $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading']));
  

    // display the template
  $smarty->display($aTemplate['page']);
  }
