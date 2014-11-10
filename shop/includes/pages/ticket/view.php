<?php
/* ----------------------------------------------------------------------
   $Id: view.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_view.php,v 1.5 2003/04/25 21:37:12 hook 
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

  require 'includes/languages/' . $sLanguage . '/ticket_view.php';

  $ticket_departments = array();
  $ticket_department_array = array();
  $ticket_departmenttable = $oostable['ticket_department'];
  $sql = "SELECT ticket_department_id, ticket_department_name
          FROM $ticket_departmenttable
          WHERE ticket_languages_id = '" .  intval($nLanguageID) . "'";
  $ticket_department_result = $dbconn->Execute($sql);
  while ($ticket_department = $ticket_department_result->fields) {
    $ticket_departments[] = array('id' => $ticket_department['ticket_department_id'],
                                  'text' => $ticket_department['ticket_department_name']);
    $ticket_department_array[$ticket_department['ticket_department_id']] = $ticket_department['ticket_department_name'];
    $ticket_department_result->MoveNext();
  }
  // Close result set
  $ticket_department_result->Close();

  $ticket_prioritys = array();
  $ticket_priority_array = array();
  $ticket_prioritytable = $oostable['ticket_priority'];
  $sql = "SELECT ticket_priority_id, ticket_priority_name
          FROM $ticket_prioritytable
          WHERE ticket_languages_id = '" .  intval($nLanguageID) . "'";
  $ticket_priority_result = $dbconn->Execute($sql);
  while ($ticket_priority = $ticket_priority_result->fields) {
    $ticket_prioritys[] = array('id' => $ticket_priority['ticket_priority_id'],
                               'text' => $ticket_priority['ticket_priority_name']);
    $ticket_priority_array[$ticket_priority['ticket_priority_id']] = $ticket_priority['ticket_priority_name'];
    $ticket_priority_result->MoveNext();
  }
  // Close result set
  $ticket_priority_result->Close();

  $ticket_statuses = array();
  $ticket_status_array = array();
  $ticket_statustable = $oostable['ticket_status'];
  $sql = "SELECT ticket_status_id, ticket_status_name
          FROM $ticket_statustable
          WHERE ticket_languages_id = '" .  intval($nLanguageID) . "'";
  $ticket_status_result = $dbconn->Execute($sql);
  while ($ticket_status = $ticket_status_result->fields) {
    $ticket_statuses[] = array('id' => $ticket_status['ticket_status_id'],
                               'text' => $ticket_status['ticket_status_name']);
    $ticket_status_array[$ticket_status['ticket_status_id']] = $ticket_status['ticket_status_name'];
    $ticket_status_result->MoveNext();
  }
  // Close result set
  $ticket_status_result->Close();

  if (isset($_GET['tlid'])) $tlid =  oos_db_prepare_input($_GET['tlid']);
  if (strlen($tlid) < 10) unset($tlid);
// Form was submitted
  $error = false;
  if (isset($_GET['action']) && ($_GET['action'] == 'send') && isset($tlid) ) {
    // Check Message length
    if (isset($enquiry) && strlen($enquiry) < TICKET_ENTRIES_MIN_LENGTH ) {
      $error = true;
      $_GET['error_message'] = $aLang['ticket_warning_enquiry_too_short'];
    }
    if ($error == false) {
      $ticket_tickettable = $oostable['ticket_ticket'];
      $sql = "SELECT ticket_id, ticket_customers_name
              FROM $ticket_tickettable
              WHERE ticket_link_id = '" . oos_db_input($tlid) . "'";

      $ticket_id_result = $dbconn->Execute($sql);
      $ticket_id = $ticket_id_result->fields;
      if ($ticket_id['ticket_id']) {
        if (TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS == 'false' && TICKET_CUSTOMER_REPLY_STATUS_ID > 0 ) $status = TICKET_CUSTOMER_REPLY_STATUS_ID;
        $sql_data_array = array('ticket_id' => $ticket_id['ticket_id'],
                          'ticket_status_id' => $status,
                          'ticket_priority_id' => $priority,
                          'ticket_department_id' => $department,
                          'ticket_date_modified' => 'now()',
                          'ticket_customer_notified' => '0',
                          'ticket_edited_by' => $ticket_id['ticket_customers_name'],
                          'ticket_comments' => $enquiry);
        oos_db_perform($oostable['ticket_status_history'], $sql_data_array);
        $sql_data_array = array('ticket_status_id' => $status,
                          'ticket_priority_id' => $priority,
                          'ticket_department_id' => $department,
                          'ticket_date_last_modified' => 'now()',
                          'ticket_date_last_customer_modified' => 'now()');

        oos_db_perform($oostable['ticket_ticket'], $sql_data_array, 'update', 'ticket_id = \'' . $ticket_id['ticket_id'] . '\'');
        $_GET['info_message'] = $aLang['ticket_message_updated'];

      }
    }
  }
  if (isset($_SESSION['customer_id'])) {
    $ticket_tickettable = $oostable['ticket_ticket'];
    $customers_tickets_raw = "SELECT ticket_link_id, ticket_subject, ticket_status_id, ticket_department_id,
                                     ticket_priority_id, ticket_date_created, ticket_date_last_modified
                              FROM $ticket_tickettable
                              WHERE ticket_customers_id = '" . intval($_SESSION['customer_id']) . "'
                              ORDER BY ticket_date_last_modified DESC";
    $customers_tickets_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $customers_tickets_raw, $customers_tickets_numrows);
    if ($customers_tickets_numrows > 0 ) {
      $customers_tickets_result = $dbconn->Execute($customers_tickets_raw);
      $customers_tickets_array = array();
      while ($customers_tickets = $customers_tickets_result->fields) {
        $customers_tickets_array[] = array('ticket_link_id' => $customers_tickets['ticket_link_id'],
                                           'ticket_subject' =>  $customers_tickets['ticket_subject'],
                                           'ticket_status_id' => $customers_tickets['ticket_status_id'],
                                           'ticket_department_id' => $customers_tickets['ticket_department_id'],
                                           'ticket_priority_id' => $customers_tickets['ticket_priority_id'],
                                           'ticket_date_created' => $customers_tickets['ticket_date_created'],
                                           'ticket_date_last_modified' => $customers_tickets['ticket_date_last_modified']);
        $customers_tickets_result->MoveNext();
      }
      // Close result set
      $customers_tickets_result->Close();
    }
  }
  if (isset($tlid)) {
    $ticket_tickettable = $oostable['ticket_ticket'];
    $sql = "SELECT ticket_id, ticket_link_id, ticket_customers_id, ticket_customers_orders_id,
                   ticket_customers_email, ticket_customers_name, ticket_subject, ticket_status_id,
                   ticket_department_id, ticket_priority_id, ticket_date_created, ticket_date_last_modified,
                   ticket_date_last_customer_modified, ticket_login_required
            FROM $ticket_tickettable
            WHERE ticket_link_id= '" . oos_db_input($tlid) . "'";
    $ticket_result = $dbconn->Execute($sql);
    $ticket = $ticket_result->fields;

    $ticket_status_historytable = $oostable['ticket_status_history'];
    $sql = "SELECT ticket_status_history_id, ticket_id, ticket_status_id, ticket_priority_id, ticket_department_id,
                   ticket_date_modified, ticket_customer_notified, ticket_comments, ticket_edited_by
            FROM $ticket_status_historytable
            WHERE ticket_id = '". oos_db_input($ticket['ticket_id']) . "'";
    $ticket_status_result = $dbconn->Execute($sql);
    $statuses_array = array();
    while ($ticket_status = $ticket_status_result->fields) {
      $statuses_array[] = array('ticket_edited_by' => $ticket_status['ticket_edited_by'],
                                'ticket_date_modified' => $ticket_status['ticket_date_modified'],
                                'ticket_status_id' => $ticket_status['ticket_status_id'],
                                'ticket_department_id' => $ticket_status['ticket_department_id'],
                                'ticket_priority_id' => $ticket_status['ticket_priority_id'],
                                'ticket_status_id' => $ticket_status['ticket_status_id'],
                                'ticket_department_id' => $ticket_status['ticket_department_id'],
                                'ticket_priority_id' => $ticket_status['ticket_priority_id'],
                                'ticket_comments' => $ticket_status['ticket_comments']);
      $ticket_status_result->MoveNext();
    }
    // Close result set
    $ticket_status_result->Close();
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['ticket'], $aFilename['ticket_create']));

  $aOption['template_main'] = $sTheme . '/modules/ticket_view.html';
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
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'contact_us.gif'
      )
  );

  $oSmarty->assign(
      array(
          'tlid'                      => $tlid,
          'customers_tickets_numrows' => $customers_tickets_numrows,
          'ticket'                    => $ticket,
          'text_view_ticket_login'    => sprintf($aLang['text_view_ticket_login'], oos_href_link($aModules['ticket'], $aFilename['ticket_view'], 'login=yes&amp;tlid=' . $tlid, 'SSL'), oos_href_link($aModules['user'], $aFilename['create_account'], '', 'SSL'))
     )
  );

  $oSmarty->assign('customers_tickets_array', $customers_tickets_array);
  $oSmarty->assign('statuses_array', $statuses_array);
  $oSmarty->assign('ticket_status_array', $ticket_status_array);
  $oSmarty->assign('ticket_department_array', $ticket_department_array);
  $oSmarty->assign('ticket_priority_array', $ticket_priority_array);

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>