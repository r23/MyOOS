<?php
/* ----------------------------------------------------------------------
   $Id: oos_event_autologon.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

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

  class oos_event_autologon {

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
    function oos_event_autologon() {

      $this->name          = PLUGIN_EVENT_AUTOLOGON_NAME;
      $this->description   = PLUGIN_EVENT_AUTOLOGON_DESC;
      $this->uninstallable = true;
      $this->author        = 'OOS Development Team';
      $this->version       = '2.0';
      $this->requirements  = array(
                               'oos'         => '1.7.0',
                               'smarty'      => '2.6.12',
                               'adodb'       => '4.81',
                               'php'         => '4.2.0'
      );
    }

    function create_plugin_instance() {

      $aFilename = oos_get_filename();
      $aModules = oos_get_modules();

      if ( ($_GET['file'] != $aFilename['login'])
        && !isset($_SESSION['customer_id']) ) {

        $cookie_url_array = parse_url((ENABLE_SSL == true ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . substr(OOS_SHOP, 0, -1));
        $cookie_path = $cookie_url_array['path'];

        if ( isset($_COOKIE['email_address']) && isset($_COOKIE['password']) ) {

          // Get database information
          $dbconn =& oosDBGetConn();
          $oostable =& oosDBGetTables();

          $customerstable = $oostable['customers'];
          $sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname,
                         customers_password, customers_wishlist_link_id, customers_language,
                         customers_vat_id_status, customers_email_address, customers_default_address_id, customers_max_order
                  FROM $customerstable
                  WHERE customers_login = '1' 
                  AND customers_email_address = '" . oos_db_input($_COOKIE['email_address']) . "'";
          $check_customer_result = $dbconn->Execute($sql);

          if ($check_customer_result->RecordCount()) {
            $check_customer = $check_customer_result->fields;

            if (oos_validate_password($_COOKIE['password'], $check_customer['customers_password'])) {

              $address_booktable = $oostable['address_book'];
              $sql = "SELECT entry_country_id, entry_zone_id
                      FROM $address_booktable
                      WHERE customers_id = '" . $check_customer['customers_id'] . "'
                        AND address_book_id = '1'";
              $check_country = $dbconn->GetRow($sql);

              if ($check_customer['customers_language'] == '') {
                $sLanguage = oos_var_prep_for_os($_SESSION['language']);
                $customerstable = $oostable['customers'];
                $dbconn->Execute("UPDATE $customerstable
                                  SET customers_language = '" . oos_db_input($sLanguage) . "'
                                  WHERE customers_id = '" . intval($check_customer['customers_id']) . "'");
              }

              $_SESSION['customer_wishlist_link_id'] = $check_customer['customers_wishlist_link_id'];
              $_SESSION['customer_id'] = $check_customer['customers_id'];
              $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
              if (ACCOUNT_GENDER == 'true') $_SESSION['customer_gender'] = $check_customer['customers_gender'];
              $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
              $_SESSION['customer_lastname'] = $check_customer['customers_lastname'];
              $_SESSION['customer_max_order'] = $check_customer['customers_max_order'];
              $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
              $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
              if (ACCOUNT_VAT_ID == 'true') $_SESSION['customers_vat_id_status'] = $check_customer['customers_vat_id_status'];

              $_SESSION['member']->restore_group();


              setcookie('email_address', $email_address, time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));
              setcookie('password', $check_customer['customers_password'], time()+ (365 * 24 * 3600), $cookie_path, '', ((getenv('HTTPS') == 'on') ? 1 : 0));

              $date_now = date('Ymd');
              $customers_infotable = $oostable['customers_info'];
              $dbconn->Execute("UPDATE $customers_infotable
                                SET customers_info_date_of_last_logon = now(),
                                    customers_info_number_of_logons = customers_info_number_of_logons+1
                                WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'");

              $_SESSION['cart']->restore_contents();    // restore cart contents
            }
          }
        }
      }

      return true;
    }

    function install() {
      return true;
    }

    function remove() {
      return false;
    }

    function config_item() {
      return false;
    }
  }

?>
