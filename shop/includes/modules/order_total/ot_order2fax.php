<?php
/* ----------------------------------------------------------------------
   $Id: ot_order2fax.php,v 1.1 2007/06/07 17:30:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_order2fax.php,v 1.0 2006/06/12 18:05:04 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  class ot_order2fax {
    var $title, $output, $enabled = false;

    function ot_order2fax() {
      global $aLang;

      $this->code = 'ot_order2fax';
      $this->title = $aLang['module_order_total_order2fax_text_title'];
      $this->description = $aLang['module_order_total_order2fax_text_description'];
      $this->enabled = (defined('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS') && (MODULE_ORDER_TOTAL_ORDER2FAX_STATUS == 'true') ? true : false);
      $this->sort_order = (defined('MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER') ? MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER : null);

      $this->output = array();
    }

    function process() {
      return;
    }

    function sendFax() {
      global $email_order;


      $aFilename = oos_get_filename();

      if ((isset($_GET['file'])) && ($_GET['file'] == $aFilename['checkout_process'])) {
        $message = "\n";
        $message .= "sender: ".MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL."\n";
        $message .= "subject: order\n";
        $message .= "user: ".MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME."\n";
        $message .= "password: ".MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD."\n";
        $message .= "job: send\n";
        $message .= "faxnumber: ".MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER."\n";
        $message .= "message: ".$email_order;

        $header = 'From: '.MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL."\r\n".'Reply-To: '.MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL;
        mail('mail2fax@tecspace.net', 'order', $message, $header);
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $this->_check = defined('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS');
      }

      return $this->_check;
    }


    function keys() {
      return array('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS', 'MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME', 'MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD', 'MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER', 'MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL', 'MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER');
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS', 'true', '6', '1','oos_cfg_select_option(array(\'true\', \'false\'), ', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME', 'Username', '6', '2', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD', 'Password', '6', '3', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER', 'Fax number', '6', '4', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL', 'you@yourbusiness.com', '6', '5', now())");
      $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER', '10', '6', '6', now())");


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
