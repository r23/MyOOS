<?php
/* ----------------------------------------------------------------------
   $Id: create.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_create.php,v 1.5 2003/04/25 21:37:12 hook
   ----------------------------------------------------------------------
   OSC-SupportTicketSystem
   Copyright (c) 2003 Henri Schmidhuber IN-Solution

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id']) && (isset($_GET['login']) && ($_GET['login'] == 'yes')) ) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/ticket_create.php';

  $ticket_departments = array();
  $ticket_department_values = array();
  $ticket_departmenttable = $oostable['ticket_department'];
  $sql = "SELECT ticket_department_id, ticket_department_name
          FROM $ticket_departmenttable
          WHERE ticket_languages_id = '" .  intval($nLanguageID) . "'";
  $ticket_department_result = $dbconn->Execute($sql);
  while ($ticket_department = $ticket_department_result->fields) {
    $ticket_departments[] = $ticket_department['ticket_department_id'];
    $ticket_department_values[] = $ticket_department['ticket_department_name'];
    $ticket_department_result->MoveNext();
  }

  $ticket_prioritys = array();
  $ticket_priority_values = array();
  $ticket_prioritytable = $oostable['ticket_priority'];
  $sql = "SELECT ticket_priority_id, ticket_priority_name
          FROM $ticket_prioritytable
          WHERE ticket_languages_id = '" .  intval($nLanguageID) . "'";
  $ticket_priority_result = $dbconn->Execute($sql);
  while ($ticket_priority = $ticket_priority_result->fields) {
    $ticket_prioritys[] = $ticket_priority['ticket_priority_id'];
    $ticket_priority_values[] = $ticket_priority['ticket_priority_name'];
    $ticket_priority_result->MoveNext();
  }


// Customer is logged in:
  $customerstable = $oostable['customers'];
  if (isset($_SESSION['customer_id'])) {
    $sql = "SELECT customers_firstname, customers_lastname,customers_email_address
            FROM $customerstable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $customer = $dbconn->GetRow($sql);
  }


// Form was submitted
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send')) {
    // Check Name length
    if (!isset($_SESSION['customer_id']) && isset($name) && strlen($name) < TICKET_ENTRIES_MIN_LENGTH ) {
      $error = true;
      $error_name = true;
    }

    // Check Subject length
    if (isset($subject) && strlen($subject) < TICKET_ENTRIES_MIN_LENGTH ) {
      $error = true;
      $error_subject = true;
    }

    // Check Message length
    if (isset($enquiry) && strlen($enquiry) < TICKET_ENTRIES_MIN_LENGTH ) {
      $error = true;
      $error_enquiry = true;
    }

    // Check Email for non logged in Customers
    if (!isset($_SESSION['customer_id']) && !oos_validate_is_email($email)) {
      $error = true;
      $error_email = true;
    }

    if ($error == false) {
      $ticket_customers_id = '';
      // Get the customers_id
      if (isset($_SESSION['customer_id'])) {
        $ticket_customers_id = $_SESSION['customer_id'];
      } else {
        $customerstable = $oostable['customers'];
        $sql = "SELECT customers_id
                FROM $customerstable
                WHERE customers_email_address='" . oos_db_input($email) . "'";
        $customerid_result = $dbconn->Execute($sql);
        if ($customerid == $customerid_result->fields) {
          $ticket_customers_id = $customerid['customers_id'];
        }
      }
      // generate LinkID
      $time = mktime();
      $ticket_link_id = '';
      for ($x=3;$x<10;$x++) {
        $ticket_link_id .= substr($time,$x,1) . oos_create_random_value(1, $type = 'chars');
      }

      $sql_data_array = array('ticket_link_id' => $ticket_link_id,
                              'ticket_customers_id' => $ticket_customers_id,
                              'ticket_customers_orders_id' => $ticket_customers_orders_id,
                              'ticket_customers_email' => $email,
                              'ticket_customers_name' => $name,
                              'ticket_subject' => $subject,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_department_id' => $department,
                              'ticket_priority_id' => $priority,
                              'ticket_login_required' => TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT,
                              'ticket_date_last_modified' => 'now()',
                              'ticket_date_last_customer_modified' => 'now()',
                              'ticket_date_created' => 'now()');
      oos_db_perform($oostable['ticket_ticket'], $sql_data_array);
      $insert_id = $dbconn->Insert_ID();

      $sql_data_array = array('ticket_id' => $insert_id,
                              'ticket_status_id' => TICKET_DEFAULT_STATUS_ID,
                              'ticket_priority_id' => $priority,
                              'ticket_department_id' => $department,
                              'ticket_date_modified' => 'now()',
                              'ticket_customer_notified' => '1',
                              'ticket_edited_by' => $name,
                              'ticket_comments' => $enquiry);
      oos_db_perform($oostable['ticket_status_history'], $sql_data_array); 

       // Email  Customer doesn't get the Message cause he should use the web
      $ticket_email_subject = $aLang['ticket_email_subject'] . $subject;
      $ticket_email_message = $aLang['ticket_email_message_header'] . "\n\n" . oos_href_link($aModules['ticket'], $aFilename['ticket_view'], 'tlid=' . $ticket_link_id, 'NONSSL',false,false) . "\n\n" . $aLang['ticket_email_ticket_nr'] . " " . $ticket_link_id . "\n" . $aLang['ticket_email_message_footer'];
      oos_mail($name, $email, $ticket_email_subject, nl2br($ticket_email_message), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '3');

      // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
       $ticket_email_message = $aLang['ticket_email_message_header'] . "\n\n" . oos_href_link($aModules['ticket'], $aFilename['ticket_view'], 'tlid=' . $ticket_link_id, 'NONSSL', false, false) . "\n\n" . $aLang['ticket_email_message_footer'] . "\n\n" . $enquiry;
       oos_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $ticket_email_subject,nl2br($ticket_email_message), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '1');
      }
      oos_redirect(oos_href_link($aModules['ticket'], $aFilename['ticket_create'], 'action=success&amp;tlid=' . $ticket_link_id ));
    }
  }

  if (TICKET_USE_ORDER_IDS == 'true' && isset($_SESSION['customer_id'])) {
    $oOrders_id = array();
    $oOrders_values = array();
    $oOrderstable = $oostable['orders'];
    $sql = "SELECT orders_id, date_purchased
            FROM $oOrderstable
            WHERE customers_id= '" . intval($_SESSION['customer_id']) . "'";
    $customers_orders_result = $dbconn->Execute($sql);
    if (isset($_GET['ticket_order_id'])) $ticket_preselected_order_id = oos_var_prep_for_os($_GET['ticket_order_id']);
    $oOrders_id[] = '';
    $oOrders_values[] = ' --- ';
    while ($customers_orders = $customers_orders_result->fields) {
      $oOrders_id[] = $customers_orders['orders_id'];
      $oOrders_values[] = $customers_orders['orders_id'] . "  (" . oos_date_short($customers_orders['date_purchased']) . ")";
      $customers_orders_result->MoveNext();
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['ticket'], $aFilename['ticket_create']));

  $aOption['template_main'] = $sTheme . '/modules/ticket_create.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_SERVICE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'              => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'           => $aLang['heading_title'],
          'oos_heading_image'           => 'contact_us.gif',
          'success_image'               => 'man_on_board.gif',

          'error_name'                  => $error_name,
          'error_email'                 => $error_email,
          'error_subject'               => $error_subject,
          'error_enquiry'               => $error_enquiry,

          'customer'                    => $customer,
          'orders_id'                   => $oOrders_id,
          'orders_values'               => $oOrders_values,

          'ticket_preselected_order_id' => $ticket_preselected_order_id,
          'ticket_departments'          => $ticket_departments,
          'ticket_department_values'    => $ticket_department_values,
          'ticket_prioritys'            => $ticket_prioritys,
          'ticket_priority_values'      => $ticket_priority_values
      )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>