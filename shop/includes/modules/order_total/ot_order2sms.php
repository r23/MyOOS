<?php
/* ----------------------------------------------------------------------
   $Id: ot_order2sms.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  class ot_order2sms {
    var $title, $output, $enabled = false;

    function ot_order2sms() {
      global $aLang;

      $this->code = 'ot_order2sms';
      $this->title = $aLang['module_order_total_order2sms_text_title'];
      $this->description = $aLang['module_order_total_order2sms_text_description'];
      $this->enabled = (defined('MODULE_ORDER_TOTAL_ORDER2SMS_STATUS') && (MODULE_ORDER_TOTAL_ORDER2SMS_STATUS == 'true') ? true : false);
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_ORDER2SMS_SORT_ORDER') ? MODULE_ORDER_TOTAL_ORDER2SMS_SORT_ORDER : null);

      $this->output = array();
    }

    function process() {
      return;
    }

    function sendSMS() {

      $aContents = oos_get_content();

      if ((isset($_GET['file'])) && ($_GET['file'] == $aFilename['checkout_process'])) {
        $referer = OOS_HTTP_SERVER;
        $user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";

        $message = "\n";
        $message .= "New order\n";

        $geturl = 'http://www.fittcom.de/cgi/smssend.pl?appid=2&id=' . MODULE_ORDER_TOTAL_ORDER2SMS_ID . '&pw=' . MODULE_ORDER_TOTAL_ORDER2SMS_PASSWORD . '&dnr=' . urlencode(MODULE_ORDER_TOTAL_ORDER2SMS_DNR) . '&snr=' . urlencode(MODULE_ORDER_TOTAL_ORDER2SMS_SNR) . '&msg=' . urlencode($message) . '&msgtype=text&deliverynotify=1&confirmemail=' . MODULE_ORDER_TOTAL_ORDER2SMS_SENDEREMAIL;

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_URL,"$geturl");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
        $pre_result = curl_exec ($ch);
        curl_close ($ch);

      }
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_ORDER_TOTAL_ORDER2SMS_STATUS');
      }

      return $this->_check;
    }


    function keys() {
      return array('MODULE_ORDER_TOTAL_ORDER2SMS_STATUS', 'MODULE_ORDER_TOTAL_ORDER2SMS_ID', 'MODULE_ORDER_TOTAL_ORDER2SMS_PASSWORD', 'MODULE_ORDER_TOTAL_ORDER2SMS_DNR', 'MODULE_ORDER_TOTAL_ORDER2SMS_SNR', 'MODULE_ORDER_TOTAL_ORDER2SMS_SENDEREMAIL', 'MODULE_ORDER_TOTAL_ORDER2SMS_SORT_ORDER');
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_ID', 'Username', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_PASSWORD', 'Password', '6', '3', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_DNR', '', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_SNR', '', '6', '5', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_SENDEREMAIL', 'you@yourbusiness.com', '6', '6', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SMS_SORT_ORDER', '11', '6', '7', now())");


    }


    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>
