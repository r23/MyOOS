<?php
/* ----------------------------------------------------------------------
   $Id: function_ticket.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_functions.php,v 1.5 2003/04/25 21:37:11 hook
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

 /**
  * Support Ticket System
  *
  * @link http://www.oos-shop.de/
  * @package Support Ticket System
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */

 /**
  * Returns Ticket Status Name
  *
  * @param $ticket_status_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_status_name($ticket_status_id, $lang_id = '') {

    if ($ticket_status_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_statustable = $oostable['ticket_status'];
    $query = "SELECT ticket_status_name
              FROM $ticket_statustable
              WHERE ticket_status_id = '" . $ticket_status_id . "'
                AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_status_name = $result->fields['ticket_status_name'];

    // Close result set
    $result->Close();

    return $ticket_status_name;
  }

 /**
  * Returns Ticket Admin Name
  *
  * @param $ticket_admin_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_admin_name($ticket_admin_id, $lang_id = '') {

    if ($ticket_admin_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_adminstable = $oostable['ticket_admins'];
    $query = "SELECT ticket_admin_name
              FROM $ticket_adminstable
              WHERE ticket_admin_id = '" . $ticket_admin_id . "'
              AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_admin_name = $result->fields['ticket_admin_name'];

    // Close result set
    $result->Close();

    return $ticket_admin_name;
  }


 /**
  * Returns Ticket Department Name
  *
  * @param $ticket_department_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_department_name($ticket_department_id, $lang_id = '') {

    if ($ticket_department_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_departmenttable = $oostable['ticket_department'];
    $query = "SELECT ticket_department_name
              FROM $ticket_departmenttable
              WHERE ticket_department_id = '" . $ticket_department_id . "'
                AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_department_name = $result->fields['ticket_department_name'];

    // Close result set
    $result->Close();

    return $ticket_department_name;
  }


 /**
  * Returns Ticket Priority Name
  *
  * @param $ticket_priority_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_priority_name($ticket_priority_id, $lang_id = '') {

    if ($ticket_priority_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_prioritytable = $oostable['ticket_priority'];
    $query = "SELECT ticket_priority_name
              FROM $ticket_prioritytable
              WHERE ticket_priority_id = '" . $ticket_priority_id . "'
                AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_priority_name = $result->fields['ticket_priority_name'];

    // Close result set
    $result->Close();

    return $ticket_priority_name;
  }


 /**
  * Returns Ticket Reply Name
  *
  * @param $ticket_reply_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_reply_name($ticket_reply_id, $lang_id = '') {

    if ($ticket_reply_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_replytable = $oostable['ticket_reply'];
    $query = "SELECT ticket_reply_name
              FROM $ticket_replytable
              WHERE ticket_reply_id = '" . $ticket_reply_id . "'
                AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_reply_name = $result->fields['ticket_reply_name'];

    // Close result set
    $result->Close();

    return $ticket_reply_name;
  }


 /**
  * Returns Ticket Reply Text
  *
  * @param $ticket_reply_id
  * @param $language
  * @return string
  */
  function oos_get_ticket_reply_text($ticket_reply_id, $lang_id = '') {

    if ($ticket_reply_id < 1) return TEXT_DEFAULT;
    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ticket_replytable = $oostable['ticket_reply'];
    $query = "SELECT ticket_reply_text
              FROM $ticket_replytable
              WHERE ticket_reply_id = '" . $ticket_reply_id . "'
                AND ticket_languages_id = '" . intval($lang_id) . "'";
    $result =& $dbconn->Execute($query);

    $ticket_reply_text = $result->fields['ticket_reply_text'];

    // Close result set
    $result->Close();

    return $ticket_reply_text;
  }

?>