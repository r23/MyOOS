<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_affiliate.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  class oos_event_affiliate {

    var $name;
    var $description;
    var $uninstallable;
    var $depends;
    var $preceeds;
    var $author;
    var $version;
    var $requirements;


   /**
    *  class constructor
    */
    function oos_event_affiliate() {

      $this->name          = PLUGIN_EVENT_AFFILIATE_NAME;
      $this->description   = PLUGIN_EVENT_AFFILIATE_DESC;
      $this->uninstallable = true;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.9',
                               'adodb'       => '4.62',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $affiliate_clientdate = (date ("Y-m-d H:i:s"));
      $affiliate_clientbrowser = oos_server_get_var('HTTP_USER_AGENT');
      $affiliate_clientreferer = oos_server_get_var('HTTP_REFERER');
      $affiliate_clientip = oos_server_get_remote();

      if (!isset($_SESSION['affiliate_ref'])) {
        if (isset($_GET['ref']) || isset($_POST['ref'])) {
          if (isset($_GET['ref'])) $_SESSION['affiliate_ref'] = oos_var_prep_for_os($_GET['ref']);
          if (isset($_POST['ref'])) $_SESSION['affiliate_ref'] = oos_var_prep_for_os($_POST['ref']);
          if (isset($_GET['products_id'])) $affiliate_products_id = oos_var_prep_for_os($_GET['products_id']);
          if (isset($_POST['products_id'])) $affiliate_products_id = oos_var_prep_for_os($_POST['products_id']);
          if (isset($_GET['affiliate_banner_id'])) $affiliate_banner_id = oos_var_prep_for_os($_GET['affiliate_banner_id']);
          if (isset($_POST['affiliate_banner_id'])) $affiliate_banner_id = oos_var_prep_for_os($_POST['affiliate_banner_id']);

          if (!$link_to) $link_to = "0";

          $sql_data_array = array('affiliate_id' => $_SESSION['affiliate_ref'],
                                  'affiliate_clientdate' => $affiliate_clientdate,
                                  'affiliate_clientbrowser' => $affiliate_clientbrowser,
                                  'affiliate_clientip' => $affiliate_clientip,
                                  'affiliate_clientreferer' => $affiliate_clientreferer,
                                  'affiliate_products_id' => $affiliate_products_id,
                                  'affiliate_banner_id' => $affiliate_banner_id);
          oos_db_perform($oostable['affiliate_clickthroughs'], $sql_data_array);
          $_SESSION['affiliate_clickthroughs_id'] = $dbconn->Insert_ID();

          // Banner has been clicked, update stats:
          if ($affiliate_banner_id && isset($_SESSION['affiliate_ref'])) {
            $day = date('Y-m-d');
            $banner_stats_sql = "SELECT affiliate_banners_history_id, affiliate_banners_products_id,
                                        affiliate_banners_shown, affiliate_banners_clicks
                                 FROM " . $oostable['affiliate_banners_history'] . "
                                 WHERE  affiliate_banners_id = '" . oos_db_input($affiliate_banner_id)  . "'
                                 AND    affiliate_banners_affiliate_id = '" . oos_db_input($_SESSION['affiliate_ref']) . "'
                                 AND    affiliate_banners_history_date = '" . oos_db_input($day) . "'";
            $banner_stats_result = $dbconn->Execute($banner_stats_sql);

            // Banner has been shown today
            if ($banner_stats_result->fields) {
              $dbconn->Execute("UPDATE " . $oostable['affiliate_banners_history'] . "
                            SET affiliate_banners_clicks = affiliate_banners_clicks + 1
                            WHERE affiliate_banners_id = '" . oos_db_input($affiliate_banner_id) . "'
                            AND   affiliate_banners_affiliate_id = '" . oos_db_input($_SESSION['affiliate_ref']). "'
                            AND   affiliate_banners_history_date = '" . oos_db_input($day) . "'");
            // Initial entry if banner has not been shown
            } else {
              $sql_data_array = array('affiliate_banners_id' => $affiliate_banner_id,
                                      'affiliate_banners_products_id' => $affiliate_products_id,
                                      'affiliate_banners_affiliate_id' => $_SESSION['affiliate_ref'],
                                      'affiliate_banners_clicks' => '1',
                                      'affiliate_banners_history_date' => $affiliate_clientdate);
              oos_db_perform($oostable['affiliate_banners_history'], $sql_data_array);
            }
          }
          // Set Cookie if the customer comes back and orders it counts
          setcookie('affiliate_ref', $_SESSION['affiliate_ref'], time() + AFFILIATE_COOKIE_LIFETIME);
        }
        if (isset($_COOKIE['affiliate_ref'])) {
          $_SESSION['affiliate_ref'] = $_COOKIE['affiliate_ref'];
        }
      }

      return true;
    }


    function install() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_status = 1 WHERE block_file = 'affiliate'");

      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate.php'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_banners'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_banners_manager'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_clicks'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_contact'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help1'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help2'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help3'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help4'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help5'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help6'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help7'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_help8'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_invoice'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_payment'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_popup_image'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_sales'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_statistics'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_summary'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 1 WHERE admin_files_name = 'affiliate_reset'");


      $today = date("Y-m-d H:i:s");

      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_OWNER', '" . STORE_OWNER . "', 6, 1, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_EMAIL_ADDRESS', '" . STORE_OWNER_EMAIL_ADDRESS . "', 6, 2, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_PERCENT', '10.0000', 6, 3, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_THRESHOLD', '50.00', 6, 4, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_COOKIE_LIFETIME', '7200', 6, 5, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_BILLING_TIME', '30', 6, 6, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_PAYMENT_ORDER_MIN_STATUS', '3', 6, 7, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_USE_CHECK', 'true', 6, 8, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_USE_PAYPAL', 'true', 6, 9, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILIATE_USE_BANK', 'true', 6, 10, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
      $dbconn->Execute("INSERT INTO " . $oostable['configuration'] . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('AFFILATE_INDIVIDUAL_PERCENTAGE', 'true', 6, 11, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");

      return true;
    }


    function remove() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate.php'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_banners'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_banners_manager'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_clicks'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_contact'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help1'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help2'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help3'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help4'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help5'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help6'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help7'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_help8'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_invoice'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_payment'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_popup_image'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_sales'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_statistics'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_summary'");
      $dbconn->Execute("UPDATE " . $oostable['admin_files'] . " SET admin_groups_id = 0 WHERE admin_files_name = 'affiliate_reset'");


      $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_status = 0 WHERE block_file = 'affiliate'");
      $dbconn->Execute("DELETE FROM " . $oostable['configuration'] . " WHERE configuration_key in ('" . implode("', '", $this->config_item()) . "')");

      return true;
    }

    function config_item() {
      return array('AFFILIATE_OWNER', 'AFFILIATE_EMAIL_ADDRESS', 'AFFILIATE_PERCENT', 'AFFILIATE_THRESHOLD', 'AFFILIATE_COOKIE_LIFETIME', 'AFFILIATE_BILLING_TIME', 'AFFILIATE_PAYMENT_ORDER_MIN_STATUS', 'AFFILIATE_USE_CHECK', 'AFFILIATE_USE_PAYPAL', 'AFFILIATE_USE_BANK', 'AFFILATE_INDIVIDUAL_PERCENTAGE');
    }
  }

?>
