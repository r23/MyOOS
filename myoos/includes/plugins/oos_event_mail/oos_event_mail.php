<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_event_mail.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class oos_event_mail
{
    public $name = PLUGIN_EVENT_MAIL_NAME;
    public $description = PLUGIN_EVENT_MAIL_DESC;
    public $uninstallable = true;
    public $depends;
    public $preceeds = 'session';
    public $author = 'MyOOS Development Team';
    public $version = '1.0';
    public $requirements = ['oos'         => '1.7.0', 'smarty'      => '2.6.9', 'adodb'       => '4.62', 'php'         => '5.9.0'];


    /**
     *  class constructor
     */
    public function __construct()
    {
    }


    public static function create_plugin_instance()
    {
        return true;
    }

    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $today = date("Y-m-d H:i:s");

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('SEND_EXTRA_ORDER_EMAILS_TO', '', 6, 1, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('EMAIL_TRANSPORT', 'mail', 6, 3, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'mail\', \'sendmail\', \'smtp\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('EMAIL_LINEFEED', 'LF', 6, 4, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'LF\', \'CRLF\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('EMAIL_USE_HTML', 'false', 6, 5, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('ENTRY_EMAIL_ADDRESS_CHECK', 'false', 6, 6, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPAUTH', 'true', 6, 7, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPUSER', '', 6, 8, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPPASS', '', 6, 9, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPHOST', '', 6, 10, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPENCRYPTION', '', 6, 11, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'NONE\', \'SSL\', \'TLS\'),')");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SMTPPORT', '', 6, 12, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OOS_SENDMAIL', '', 6, 13, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");

        return true;
    }

    public function remove()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

        return true;
    }

    public function config_item()
    {
        return ['SEND_EXTRA_ORDER_EMAILS_TO', 'EMAIL_TRANSPORT', 'EMAIL_LINEFEED', 'EMAIL_USE_HTML', 'ENTRY_EMAIL_ADDRESS_CHECK', 'OOS_SMTPAUTH', 'OOS_SMTPUSER', 'OOS_SMTPPASS', 'OOS_SMTPHOST', 'OOS_SMTPENCRYPTION', 'OOS_SMTPPORT', 'OOS_SENDMAIL'];
    }
}
