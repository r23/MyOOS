<?php
/* ----------------------------------------------------------------------
   $Id: ticket_view.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_view.php,v 1.5 2003/04/25 21:37:11 hook 
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

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $ticket_admins = array();
  $ticket_admin_array = array();

  $ticket_adminstable = $oostable['ticket_admins'];
  $ticket_admin_result = $dbconn->Execute("SELECT ticket_admin_id, ticket_admin_name FROM $ticket_adminstable WHERE ticket_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($ticket_admin = $ticket_admin_result->fields) {
    $ticket_admins[] = array('id' => $ticket_admin['ticket_admin_id'],
                             'text' => $ticket_admin['ticket_admin_name']);
    $ticket_admin_array[$ticket_admin['ticket_admin_id']] = $ticket_admin['ticket_admin_name'];

    // Move that ADOdb pointer!
    $ticket_admin_result->MoveNext();
  }


  $ticket_departments = array();
  $ticket_department_array = array();

  $ticket_departmenttable = $oostable['ticket_department'];
  $ticket_department_result = $dbconn->Execute("SELECT ticket_department_id, ticket_department_name FROM $ticket_departmenttable WHERE ticket_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($ticket_department = $ticket_department_result->fields) {
    $ticket_departments[] = array('id' => $ticket_department['ticket_department_id'],
                                  'text' => $ticket_department['ticket_department_name']);
    $ticket_department_array[$ticket_department['ticket_department_id']] = $ticket_department['ticket_department_name'];

    // Move that ADOdb pointer!
    $ticket_department_result->MoveNext();
  }

  $ticket_prioritys = array();
  $ticket_priority_array = array();

  $ticket_prioritytable = $oostable['ticket_priority'];
  $ticket_priority_result = $dbconn->Execute("SELECT ticket_priority_id, ticket_priority_name FROM $ticket_prioritytable WHERE ticket_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($ticket_priority = $ticket_priority_result->fields) {
    $ticket_prioritys[] = array('id' => $ticket_priority['ticket_priority_id'],
                                'text' => $ticket_priority['ticket_priority_name']);
    $ticket_priority_array[$ticket_priority['ticket_priority_id']] = $ticket_priority['ticket_priority_name'];

    // Move that ADOdb pointer!
    $ticket_priority_result->MoveNext();
  }

  $ticket_statuses = array();
  $ticket_status_array = array();

  $ticket_statustable = $oostable['ticket_status'];
  $ticket_status_result = $dbconn->Execute("SELECT ticket_status_id, ticket_status_name FROM $ticket_statustable WHERE ticket_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($ticket_status = $ticket_status_result->fields) {
    $ticket_statuses[] = array('id' => $ticket_status['ticket_status_id'],
                               'text' => $ticket_status['ticket_status_name']);
    $ticket_status_array[$ticket_status['ticket_status_id']] = $ticket_status['ticket_status_name'];

    // Move that ADOdb pointer!
    $ticket_status_result->MoveNext();
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'update_ticket':
        $error = false;
        $tID = oos_db_prepare_input($_GET['tID']);
         // Check Message length
        if (strlen($enquiry) < TICKET_ENTRIES_MIN_LENGTH ) {
          $error = true;
          $messageStack->add_session(WARNING_ENTRY_TO_SHORT,  'warning');
        }
         // Check if Ticket exists
        $ticket_tickettable = $oostable['ticket_ticket'];
        $ticket_update_result = $dbconn->Execute("SELECT ticket_customers_email, ticket_customers_name, ticket_link_id FROM $ticket_tickettable WHERE ticket_id = '" . $tID . "'");
        $ticket_update = $ticket_update_result->fields;
        if (!$ticket_update['ticket_customers_email']) {
          $error = true;
          $messageStack->add_session(WARNING_TICKET_NOT_UPDATED ."AA", 'warning');
        }
        if ($error == false) {
          $sql_data_array = array('ticket_id' => $tID,
                                  'ticket_status_id' => $status,
                                  'ticket_priority_id' => $priority,
                                  'ticket_department_id' => $department,
                                  'ticket_date_modified' => 'now()',
                                  'ticket_customer_notified' => '0',
                                  'ticket_edited_by' => $ticket_admin_array[$admin],
                                  'ticket_comments' => $enquiry);
          oos_db_perform($oostable['ticket_status_history'], $sql_data_array);
          $sql_data_array = array('ticket_date_last_modified' => 'now()',
                                  'ticket_status_id' => $status,
                                  'ticket_priority_id' => $priority,
                                  'ticket_department_id' => $department,
                                  'ticket_login_required' => $ticket_login_required);
          oos_db_perform($oostable['ticket_ticket'], $sql_data_array,'update','ticket_id=\'' . $tID . '\'');
           // Email  Customer doesn't get the Message cause he should use the web
          $ticket_email_subject = TICKET_EMAIL_SUBJECT . $subject;
          $ticket_email_message = TICKET_EMAIL_message_HEADER . "\n\n" . oos_catalog_link($oosModules['ticket'], $oosCatalogFilename['ticket_view'], 'tlid=' . $ticket_update['ticket_link_id'],'NONSSL',false,false) . "\n\n" . TICKET_EMAIL_message_FOOTER;
          oos_mail($ticket_update['ticket_customers_name'], $ticket_update['ticket_customers_email'], $ticket_email_subject, nl2br($ticket_email_message), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          $ticket_updated = true;
        }
        if ($ticket_updated) {
          $messageStack->add_session(SUCCESS_TICKET_UPDATED, 'success');
        } else {
          $messageStack->add_session(WARNING_TICKET_NOT_UPDATED, 'warning');
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('action')) . 'action=edit'));
        break;

      case 'deleteconfirm':
        $tID = oos_db_prepare_input($_GET['tID']);
        $ticket_tickettable = $oostable['ticket_ticket'];
        $dbconn->Execute ("DELETE FROM $ticket_tickettable WHERE ticket_id='" . $tID . "'");
        oos_redirect_admin(oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action'))));
        break;
    }
  }

  if (($action == 'edit') && isset($_GET['tID'])) {
    $tID = oos_db_prepare_input($_GET['tID']);

    $ticket_tickettable = $oostable['ticket_ticket'];
    $ticket_result = $dbconn->Execute("SELECT * FROM $ticket_tickettable WHERE ticket_id = '" . oos_db_input($tID) . "'");
    $ticket_exists = true;
    if (!$ticket_result->RecordCount()) {
      $ticket_exists = false;
      $messageStack->add(sprintf(ERROR_TICKET_DOES_NOT_EXIST, $tID), 'error');
    }
  }

  $no_js_general = true;
  require 'includes/oos_header.php'; 
?>
<link rel="stylesheet" type="text/css" href="includes/ticketstyle.css">
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($action == 'edit') && isset($ticket_exists) ) {
    $ticket = $ticket_result->fields;

    $ticket_status_historytable = $oostable['ticket_status_history'];
    $ticket_status_result = $dbconn->Execute("SELECT * FROM $ticket_status_historytable WHERE ticket_id = '". oos_db_input($ticket['ticket_id']) . "'");
?>
      <tr>
        <td><table class="ticket" width="100%" border="1" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan=2 class="ticketInfoBoxHeading" align="left"><b><?php echo $ticket['ticket_subject']; ?></b></td>
          </tr> 
          <tr>
            <td class="ticketSmallText" colspan=2 align="left">
<?php 
      echo TEXT_OPENED . ' ' .oos_datetime_short($ticket['ticket_date_created']) . ' ' . TEXT_BY . ' ' . $ticket['ticket_customers_name'] . "<br />";
      echo TEXT_CUSTOMERS_EMAIL . ' ' . $ticket['ticket_customers_email'] . "<br />";
      echo TEXT_TICKET_NR . '&nbsp;' . $ticket['ticket_link_id'];
      if ($ticket['ticket_customers_orders_id'] > 0 && TICKET_USE_ORDER_IDS == 'true') echo '<br />' . TEXT_CUSTOMERS_ORDERS_ID . '&nbsp;' . $ticket['ticket_customers_orders_id'];
?>
            </td>
          </tr>
<?php
      while ($ticket_status = $ticket_status_result->fields) {
?>
          <tr >
            <td class="ticketSmallText" width="15%">
<?php
                  echo '<b>' . $ticket_status['ticket_edited_by'] . '</b><br /></br>';
                  echo TEXT_DATE . '&nbsp;' .  oos_datetime_short($ticket_status['ticket_date_modified']) . '<br />';
                  if (TICKET_USE_STATUS == 'true') echo TEXT_STATUS . '&nbsp;' .  $ticket_status_array[$ticket_status['ticket_status_id']] . '<br />';
                  if (TICKET_USE_DEPARTMENT == 'true') echo TEXT_DEPARTMENT . '&nbsp;' .  $ticket_department_array[$ticket_status['ticket_department_id']] . '<br />';
                  if (TICKET_USE_PRIORITY == 'true') echo TEXT_PRIORITY . '&nbsp;' .  $ticket_priority_array[$ticket_status['ticket_priority_id']] . '<br />';
                  $ticket_last_used_status = $ticket_status['ticket_status_id'];
                  $ticket_last_used_department = $ticket_status['ticket_department_id'];
                  $ticket_last_used_priority = $ticket_status['ticket_priority_id'];
?>
            </td>
            <td align=left class="ticketSmallText"><?php echo nl2br($ticket_status['ticket_comments']); ?></td>
          </tr>

<?php
        // Move that ADOdb pointer!
        $ticket_status_result->MoveNext();
      }

      // Close result set
      $ticket_status_result->Close();

      echo oos_draw_form('status', $aFilename['ticket_view'], oos_get_all_get_params(array('action')) . 'action=update_ticket');
?>
          <tr>
            <td class="ticketSmallText" valign="top">
<?php 
      echo '            ' . TEXT_COMMENT . '<br /><br /><br />';
      echo '            ' . TEXT_ADMIN . '&nbsp;' . oos_draw_pull_down_menu('admin', $ticket_admins, ($ticket_last_used_admin ? $ticket_last_used_admins : TICKET_DEFAULT_ADMIN_ID) ) . "<br /><br />";
      if (TICKET_USE_STATUS == 'true') echo '            ' . TEXT_STATUS . '&nbsp;' . oos_draw_pull_down_menu('status', $ticket_statuses, ($ticket_last_used_status ? $ticket_last_used_status : TICKET_DEFAULT_STATUS_ID) ) . "<br /><br />";
      if (TICKET_USE_DEPARTMENT == 'true') echo '            ' . TEXT_DEPARTMENT . '&nbsp;' . oos_draw_pull_down_menu('department', $ticket_departments, ($ticket_last_used_department ? $ticket_last_used_department : TICKET_DEFAULT_DEPARTMENT_ID) ) . "<br /><br />";
      if (TICKET_USE_PRIORITY == 'true') echo '            ' . TEXT_PRIORITY . '&nbsp;' . oos_draw_pull_down_menu('priority', $ticket_prioritys, ($ticket_last_used_priority ? $ticket_last_used_priority : TICKET_DEFAULT_PRIORITY_ID) ) . "<br /><br />";
      echo '            ' . TEXT_REPLY . '&nbsp;' ;

      $ticket_replytable = $oostable['ticket_reply'];
      $reply_result = $dbconn->Execute("SELECT ticket_reply_id, ticket_reply_name, ticket_reply_text FROM $ticket_replytable WHERE ticket_languages_id = '" . intval($_SESSION['language_id']) . "'");
      echo ' <select name="dummy" size="1">';
      while ($reply = $reply_result->fields) {
        echo '            <option value="' . $reply['ticket_reply_text'] . '"';
        if (TICKET_DEFAULT_REPLY_ID == $reply['ticket_reply_id']) echo ' selected';
        echo '>';
        echo $reply['ticket_reply_name'] . '</option>' . "\n"; 

        // Move that ADOdb pointer!
        $reply_result->MoveNext();
      }
      echo '             </select>';
      echo '             <input type="button" name="insert" value="' . TEXT_INSERT . '" onclick="document.status.enquiry.value = document.status.enquiry.value + document.status.dummy.value">';
?>
            </td>
            <td  class="ticketSmallText" ><?php echo oos_draw_textarea_field('enquiry', 'soft', 50, 15,'','class="ticket"'); ?></td>
          </tr>
<?php
      if ($ticket['ticket_customers_id'] > 0) {
?>
          <tr>
            <td colspan=2 class="main" align="left">
<?php 

        $ticket_change_login = array();
        $ticket_change_login_array = array();
        $ticket_change_login[] = array('id' => '0', 'text' => TEXT_CUSTOMER_LOGIN_NO);
        $ticket_change_login_array['0'] = TEXT_CUSTOMER_LOGIN_NO;
        $ticket_change_login[] = array('id' => '1', 'text' => TEXT_CUSTOMER_LOGIN_YES);
        $ticket_change_login_array['1'] = TEXT_CUSTOMER_LOGIN_YES;

        if (TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT == 'true') {
          echo oos_draw_pull_down_menu('ticket_login_required', $ticket_change_login, $ticket['ticket_login_required']);
        }
        else {
          echo $ticket_change_login_array[$ticket['ticket_login_required']];
        }
?>
            </td>
          </tr>
<?php
      }
?>
          <tr>
            <td colspan=2 class="main" align="center"><?php echo oos_image_swap_submits('update', 'update_off.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('action'))) . '">' . oos_image_swap_button('back', 'back_off.gif', IMAGE_BACK) . '</a>' ?></td>
          </tr>
          </form>
        </table></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo oos_draw_form('status', $aFilename['ticket_view'], '', 'get'); ?>
                <td class="smallText" align="right">
<?php
    if (TICKET_USE_STATUS == 'true') echo HEADING_TITLE_STATUS . ' ' . oos_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_TICKETS)), $ticket_statuses), '', 'onChange="this.form.submit();"') . "<br />\n";
    if (TICKET_USE_DEPARTMENT == 'true') echo HEADING_TITLE_DEPARTMENT . ' ' . oos_draw_pull_down_menu('department', array_merge(array(array('id' => '', 'text' => TEXT_ALL_DEPARTMENTS)), $ticket_departments), '', 'onChange="this.form.submit();"') . "<br />\n"; 
    if (TICKET_USE_PRIORITY == 'true') echo HEADING_TITLE_PRIORITY . ' ' . oos_draw_pull_down_menu('priority', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PRIORITYS)), $ticket_prioritys), '', 'onChange="this.form.submit();"') . "<br />\n"; 
?>
                </td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE; ?></td>
<?php
    if (TICKET_USE_SUBJECT == 'true') echo '                <td class="dataTableHeadingContent" align="left">' .  TABLE_HEADING_TICKET_SUBJECT . '</td>';
    if (TICKET_USE_ORDER_IDS == 'true') echo '                <td class="dataTableHeadingContent" align="center">' . TABLE_HEADING_ORDER_ID . '</td>';
    if (TICKET_USE_STATUS == 'true') echo '                <td class="dataTableHeadingContent" align="center">' . TABLE_HEADING_STATUS . '</td>';
    if (TICKET_USE_PRIORITY == 'true') echo '                <td class="dataTableHeadingContent" align="center">' . TABLE_HEADING_PRIORITY . '</td>';
    if (TICKET_USE_DEPARTMENT == 'true') echo '                <td class="dataTableHeadingContent" align="center">' . TABLE_HEADING_DEPARTMENT . '&nbsp;</td>';
?>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $ticket_tickettable = $oostable['ticket_ticket'];
    $ticket_result_raw  = "SELECT * FROM $ticket_tickettable ";
    if (isset($_GET['status']) || isset($_GET['department']) || isset($_GET['priority'])) {
      $sql_and = false;
      $ticket_result_raw .= "WHERE ";
      if (isset($_GET['status'])) {
        $ticket_result_raw .= " ticket_status_id = '" . $_GET['status'] . "' ";
        $sql_and = true;
      }
      if (isset($_GET['department'])) {
        if ($sql_and === true) $ticket_result_raw .= " AND ";
        $ticket_result_raw .= " ticket_department_id = '" . $_GET['department'] . "' ";
        $sql_and = true;
      }
      if (isset($_GET['priority'])) {
        if ($sql_and === true) $ticket_result_raw .= " AND ";
        $ticket_result_raw .= " ticket_priority_id = '" . $_GET['priority'] . "' ";
        $sql_and = true;
      }
    }
    $ticket_result_raw .= "ORDER BY ticket_date_last_customer_modified DESC";

    $ticket_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $ticket_result_raw, $ticket_result_numrows);
    $ticket_result = $dbconn->Execute($ticket_result_raw);
    while ($ticket = $ticket_result->fields) {
      if ((!isset($_GET['tID']) || (isset($_GET['tID']) && ($_GET['tID'] == $ticket['ticket_id']))) && !isset($tInfo)) {
        $tInfo = new objectInfo($ticket);
      }
      if (isset($tInfo) && is_object($tInfo) && ($ticket['ticket_id'] == $tInfo->ticket_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID')) . 'tID=' . $ticket['ticket_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $ticket['ticket_id'] . '&action=edit') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $ticket['ticket_customers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php echo ($ticket['ticket_customers_id']>0) ? ('<a href="' . oos_href_link_admin($aFilename['customers'],"cID=" . $ticket['ticket_customers_id'] ."&action=edit") . '" target="' . TICKET_LINK_TARGET . '">' . $ticket['ticket_customers_id'] . "</a>")  : '--'; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php echo oos_datetime_short($ticket['ticket_date_last_modified']); ?></td>
<?php
      if (TICKET_USE_SUBJECT == 'true') echo '                <td class="dataTableContent" align="left">' . strip_tags($ticket['ticket_subject']) . '&nbsp;</td>';
      if (TICKET_USE_ORDER_IDS == 'true') echo '                <td class="dataTableContent" align="center">' . (($ticket['ticket_customers_orders_id'] > 0) ? ('<a href="' . oos_href_link_admin($aFilename['orders'],"oID=" . $ticket['ticket_customers_orders_id'] ."&action=edit") . '" target="' . TICKET_LINK_TARGET . '">' . $ticket['ticket_customers_orders_id'] . "</a>") : '--') . '&nbsp;</td>';
      if (TICKET_USE_STATUS == 'true') echo '                <td class="dataTableContent" align="center">' . $ticket_status_array[$ticket['ticket_status_id']] . '&nbsp;</td>';
      if (TICKET_USE_PRIORITY == 'true') echo '                <td class="dataTableContent" align="center">' . $ticket_priority_array[$ticket['ticket_priority_id']] . '</td>';
      if (TICKET_USE_DEPARTMENT == 'true') echo '                <td class="dataTableContent" align="center">' . $ticket_department_array[$ticket['ticket_department_id']] . '</td>';
?>
                <td class="dataTableContent" align="right"><?php if (isset($tInfo) && is_object($tInfo) && ($ticket['ticket_id'] == $tInfo->ticket_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID')) . 'tID=' . $ticket['ticket_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $ticket_result->MoveNext();
    }

    // Close result set
    $ticket_result->Close();
?>
              <tr>
                <td colspan="9"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $ticket_split->display_count($ticket_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TICKET); ?></td>
                    <td class="smallText" align="right"><?php echo $ticket_split->display_links($ticket_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'tID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TICKET . '</b>');

      $contents = array('form' => oos_draw_form('orders', $aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=deleteconfirm'));
      $contents[] = array('align' => 'left', 'text' => $tInfo->ticket_subject);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete', 'delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      $contents[] = array('form' => '</form>');
      break;

    default:
      if (isset($tInfo) && is_object($tInfo)) {

        $heading[] = array('text' => '<b>[' . $tInfo->ticket_id . ']&nbsp;&nbsp;' . $tInfo->ticket_subject . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['ticket_view'], oos_get_all_get_params(array('tID', 'action')) . 'tID=' . $tInfo->ticket_id . '&action=delete') . '">' . oos_image_swap_button('delete', 'delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_DATE_TICKET_CREATED . ' ' . oos_datetime_short($tInfo->ticket_date_created));
        if (oos_is_not_null($tInfo->ticket_date_last_modified)) $contents[] = array('text' => TEXT_DATE_TICKET_LAST_MODIFIED . ' ' . oos_datetime_short($tInfo->ticket_date_last_modified));
        if (oos_is_not_null($tInfo->ticket_date_last_customer_modified)) $contents[] = array('text' => TEXT_DATE_TICKET_LAST_CUSTOMER_MODIFIED . ' ' . oos_datetime_short($tInfo->ticket_date_last_customer_modified));
        if (TICKET_USE_STATUS == 'true') $contents[] = array('text' => '<br />' . TEXT_STATUS . ' ' . $ticket_status_array[$tInfo->ticket_status_id]);
        if (TICKET_USE_PRIORITY == 'true') $contents[] = array('text' => '<br />' . TEXT_PRIORITY . ' ' . $ticket_priority_array[$tInfo->ticket_priority_id]);
        if (TICKET_USE_DEPARTMENT == 'true') $contents[] = array('text' => '<br />' . TEXT_DEPARTMENT . ' ' . $ticket_department_array[$tInfo->ticket_department_id]);  
        $contents[] = array('text' => '<br />' . TEXT_TICKET_NR . ' ' . $tInfo->ticket_link_id);  
        $contents[] = array('text' => '<br />' . TEXT_CUSTOMERS_NAME . ' ' . $tInfo->ticket_customers_name); 
        $contents[] = array('text' => '<br />' . TEXT_CUSTOMERS_EMAIL . ' ' . $tInfo->ticket_customers_email);
        if ($tInfo->ticket_customers_id > 0) $contents[] = array('text' => TEXT_CUSTOMERS_ID . ' ' . '<a href="' . oos_href_link_admin($aFilename['customers'],"cID=" . $tInfo->ticket_customers_id ."&action=edit") . '" target="' . TICKET_LINK_TARGET . '">' . $tInfo->ticket_customers_id . '</a>');  
        if (TICKET_USE_ORDER_IDS == 'true') $contents[] = array('text' => '<br />' . TEXT_CUSTOMERS_ORDERS_ID . ' ' . '<a href="' . oos_href_link_admin($aFilename['orders'],"oID=" . $tInfo->ticket_customers_orders_id ."&action=edit") . '" target="' . TICKET_LINK_TARGET . '">' . $tInfo->ticket_customers_orders_id . '</a>');  
      }
      break;
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>

      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>