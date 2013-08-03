<?php
/* ----------------------------------------------------------------------
   $Id: class_member.php 439 2013-06-24 22:47:03Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Customers_status v3.x / Catalog part
   Copyright elari@free.fr

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class oosMember {
    var $group;
    var $groupID;

    function oosMember() {
      $this->reset();
    }

    function default_member() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;

      $customers_statustable = $oostable['customers_status'];
      $sql = "SELECT customers_status_id, customers_status_name, customers_status_public,
                     customers_status_show_price, customers_status_show_price_tax, customers_status_image,
                     customers_status_ot_discount_flag, customers_status_ot_discount,
                     customers_status_ot_minimum, customers_status_qty_discounts, customers_status_payment
               FROM $customers_statustable
              WHERE customers_status_id = '" . DEFAULT_CUSTOMERS_STATUS_ID . "' AND
                    customers_status_languages_id = '" .  intval($nLanguageID) . "'";
      $customer_status = $dbconn->GetRow($sql);

      $this->group = array('id' => $customer_status['customers_status_id'],
                           'text' => $customer_status['customers_status_name'],
                           'public' => $customer_status['customers_status_public'],
                           'show_price' => $customer_status['customers_status_show_price'],
                           'show_price_tax' => $customer_status['customers_status_show_price_tax'],
                           'image' => $customer_status['customers_status_image'],
                           'ot_discount_flag' => $customer_status['customers_status_ot_discount_flag'],
                           'ot_discount' => $customer_status['customers_status_ot_discount'],
                           'ot_minimum' => $customer_status['customers_status_ot_minimum'],
                           'qty_discounts' => $customer_status['customers_status_qty_discounts'],
                           'payment' => $customer_status['customers_status_payment']);

    }

    function restore_group() {

      if (!isset($_SESSION['customer_id'])) return false;

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $nLanguageID = isset( $_SESSION['language_id']) ?  $_SESSION['language_id']+0 : DEFAULT_CUSTOMERS_STATUS_ID;

      $customerstable = $oostable['customers'];
      $customers_statustable = $oostable['customers_status'];
      $sql = "SELECT c.customers_status, cs.customers_status_id, cs.customers_status_name, cs.customers_status_public, 
                     cs.customers_status_show_price, cs.customers_status_show_price_tax, cs.customers_status_image, 
                     cs.customers_status_ot_discount_flag, cs.customers_status_ot_minimum, 
                     cs.customers_status_ot_discount, cs.customers_status_qty_discounts, cs.customers_status_payment
                FROM $customerstable AS c LEFT JOIN
                     $customers_statustable AS cs
                  ON customers_status = customers_status_id
               WHERE c.customers_id='" . intval($_SESSION['customer_id']) . "' AND
                     cs.customers_status_languages_id = '" .  intval($nLanguageID) . "'";
      $customer_status = $dbconn->GetRow($sql);

      $this->group = array('id' => $customer_status['customers_status_id'],
                           'text' => $customer_status['customers_status_name'],
                           'public' => $customer_status['customers_status_public'],
                           'show_price' => $customer_status['customers_status_show_price'],
                           'show_price_tax' => $customer_status['customers_status_show_price_tax'],
                           'image' => $customer_status['customers_status_image'],
                           'ot_discount_flag' => $customer_status['customers_status_ot_discount_flag'],
                           'ot_discount' => $customer_status['customers_status_ot_discount'],
                           'ot_minimum' => $customer_status['customers_status_ot_minimum'],
                           'qty_discounts' => $customer_status['customers_status_qty_discounts'],
                           'payment' => $customer_status['customers_status_payment']);
      $this->groupID = $this->generate_group_id();
    }


    function reset() {

      $this->group = array();

      unset($this->groupID);
      if (isset($_SESSION['groupID'])) unset($_SESSION['groupID']);
    }

    function generate_group_id($length = 5) {
      return oos_create_random_value($length, 'digits');
    }
  }
