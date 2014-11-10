<?php
/* ----------------------------------------------------------------------
   $Id: NotifyShop.php,v 1.1 2007/06/08 17:14:43 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/


   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce Payment-Modul TeleCash Click&Pay easy
   Version 0.8 vom 23.03.2004

   (c) 2004: Dieter H�auf
   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');

  /**
   * Log connection
   * 1 = database
   * 2 = file
   * 3 = both
   */
  define('OOS_TELECASH_LOG_TYPE', '1');


  include '../includes/configure.php';
  include '../includes/oos_define.php';
  include '../includes/oos_tables.php';

  require '../includes/functions/function_global.php';
  require '../includes/functions/function_kernel.php';
  require '../includes/functions/function_server.php';


  if (isset($_GET)) {
    foreach ($_GET as $key=>$value) {
      $$key = oos_prepare_input($value);
    }
  }

  if($tcphMerchantID != 'demo') die('ERROR'); // Tragen Sie hier Ihre TeleCash-MerchantID ein.


// include the database functions
  if (!defined('ADODB_LOGSQL_TABLE')) {
    define('ADODB_LOGSQL_TABLE', $oostable['adodb_logsql']);
  }
  require_once('../includes/classes/adodb/adodb-errorhandler.inc.php');
  require_once('../includes/classes/adodb/adodb.inc.php');
  require_once('../includes/functions/function_db.php');

// make a connection to the database... now 
  if (!oosDBInit()) {
    die('Unable to connect to database server!');
  }

  $db =& oosDBGetConn();

  $sql = "SELECT tcph_session_id
          FROM " . $oostable['telecash_info'] . " 
          WHERE  tcph_transaction_id = '" .  oos_db_input($tcphTransactionID) . "'";

  $notify_shop = $db->Execute($sql);
  $tcphSessionID = $notify_shop->fields['tcph_session_id'];

  if ( (OOS_TELECASH_LOG_TYPE == '1') || (OOS_TELECASH_LOG_TYPE == '3') ) {
    $db->Execute("INSERT INTO " . $oostable['telecash_log'] . "
                 (timestamp,
                  tcph_cc_flag,
                  tcph_Merchant_id,
                  tcph_payment_type,
                  tcph_remote_address,
                  tcph_result_additional_data,
                  tcph_result_authorization_id,
                  tcph_result_capture_token,
                  tcph_result_code,
                  tcph_result_date,
                  tcph_result_message,
                  tcph_result_response_code,
                  tcph_result_sequence_no,
                  tcph_result_trace_audit_number,
                  tcph_result_terminal_id,
                  tcph_transaction_id,
                  tcph_user_agent)
                  VALUES ('" . oos_db_input($tcphTimestamp) . "', 
                          '" . oos_db_input($tcphCCflag) . "', 
                          '" . oos_db_input($tcphMerchantID) . "', 
                          '" . oos_db_input($tcphPaymentType) . "', 
                          '" . oos_db_input($tcphRemoteAddress) . "', 
                          '" . oos_db_input($tcphResultAdditionalData) . "', 
                          '" . oos_db_input($tcphResultAuthorizationID) . "', 
                          '" . oos_db_input($tcphResultCaptureToken) . "', 
                          '" . oos_db_input($tcphResultCode) . "', 
                          '" . oos_db_input($tcphResultDate) . "', 
                          '" . oos_db_input($tcphResultmessage) . "', 
                          '" . oos_db_input($tcphResultResponseCode) . "', 
                          '" . oos_db_input($tcphResultSequenceNo) . "', 
                          '" . oos_db_input($tcphResultTraceAuditNumber) . "', 
                          '" . oos_db_input($tcphResultTerminalID) . "', 
                          '" . oos_db_input($tcphTransactionID) . "', 
                          '" . oos_db_input($tcphUserAgent) . "')");

  } elseif ( (OOS_TELECASH_LOG_TYPE == '2') || (OOS_TELECASH_LOG_TYPE == '3') ) {
    $log = fopen(OOS_TEMP_PATH . 'logs/telecash.log', "a");
    $log_write = fwrite($log,
        $tcphTimestamp . ";"
        $tcphCCflag . ";"
        $tcphMerchantID . ";"
        $tcphPaymentType . ";"
        $tcphRemoteAddress . ";"
        $tcphResultAdditionalData . ";"
        $tcphResultAuthorizationID . ";"
        $tcphResultCaptureToken . ";"
        $tcphResultCode . ";"
        $tcphResultDate . ";"
        $tcphResultmessage . ";"
        $tcphResultResponseCode . ";"
        $tcphResultSequenceNo . ";"
        $tcphResultTraceAuditNumber . ";"
        $tcphResultTerminalID . ";"
        $tcphTransactionID . ";"
        $tcphUserAgent);
    $log_close = fclose($log);
  }

  $db->Execute("DELETE FROM " . $oostable['telecash_info'] . " WHERE tcph_transaction_id = '" . oos_db_input($tcphTransactionID) . "'");

  echo "tcphNotifyResultCode=\"OK\" ";

  if (ENABLE_SSL == 'true') {
    $link = OOS_HTTPS_SERVER . OOS_SHOP;
  } else {
    $link = OOS_HTTP_SERVER . OOS_SHOP;
  }

  echo "tcphAnswerPageTemplateURL=\"" . $link . "tcpayment/AnswerPageTemplate.php?tcphResultCode=$tcphResultCode&;\$sessionid\$$tcphSessionID\"";

?>
