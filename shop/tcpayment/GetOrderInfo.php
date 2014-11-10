<?php
/* ----------------------------------------------------------------------
   $Id: GetOrderInfo.php,v 1.1 2007/06/08 17:14:43 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/


   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:


   osCommerce Payment-Modul TeleCash Click&Pay easy
   Version 0.8 vom 23.03.2004

   (c) 2005: Dieter H�auf
   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');

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

 // if($tcphMerchantID != 'demo') die('ERROR'); // Tragen Sie hier Ihre TeleCash-MerchantID ein.


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

  $sql = "SELECT tcph_amount, tcph_currency, tcph_order_description 
          FROM " . $oostable['telecash_info'] . " 
          WHERE  tcph_transaction_id = '" .  oos_db_input($tcphTransactionID) . "'";    
  $telecash_get_info_result = $db->Execute($sql);

  $telecash_get_info = $telecash_get_info_result->fields;
  $tcphAmount = $telecash_get_info['tcph_amount'];
  $tcphCurrency = $telecash_get_info['tcph_currency'];
  $tcphOrderDescription = $telecash_get_info['tcph_order_description'];

  echo "tcphAmount=\"$tcphAmount\" ";
  echo "tcphCurrency=\"$tcphCurrency\" ";
  echo "tcphOrderDescription=\"$tcphOrderDescription\"";

?>
