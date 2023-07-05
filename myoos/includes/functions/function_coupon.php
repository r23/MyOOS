<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_sent.php,v 1.1 2003/02/18 00:18:50 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Credit Class GV/Discount Coupon v5.03
   Copyright (c) 2001 - 2003 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

 /**
  * Credit Class GV/Discount Coupon
  *
  * @link    https://www.oos-shop.de
  * @package Credit Class GV/Discount Coupon
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/12 16:49:27 $
  */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

 /**
  * Create a Coupon Code. length may be between 1 and 16 Characters
  *
  * @param  $salt
  * @param  $length
  * @return string
  */
function oos_create_coupon_code($salt="secret", $length = SECURITY_CODE_LENGTH)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ccid = md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    srand((float)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
        $id1 = substr($ccid, $random_start, $length);
        $couponstable = $oostable['coupons'];
        $sql = "SELECT coupon_code
              FROM $couponstable
              WHERE coupon_code = '" . oos_db_input($id1) . "'";
        $query = $dbconn->Execute($sql);
        if ($query->RecordCount() == 0) {
            $good_result = 1;
        }
    }
    return $id1;
}


 /**
  * Update the Customers GV account
  *
  * @param $customer_id
  * @param $gv_id
  */
function oos_gv_account_update($customer_id, $gv_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
    $sql = "SELECT amount
            FROM $coupon_gv_customertable
            WHERE customer_id = '" . intval($customer_id) . "'";
    $customer_gv_result = $dbconn->Execute($sql);

    $couponstable = $oostable['coupons'];
    $sql = "SELECT coupon_amount
            FROM $couponstable
            WHERE coupon_id = '" . oos_db_input($gv_id) . "'";
    $coupon_amount = $dbconn->GetOne($sql);

    if ($customer_gv_result->RecordCount() > 0) {
        $customer_gv = $customer_gv_result->fields;
        $new_gv_amount = $customer_gv['amount'] + $coupon_amount;

        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
        $dbconn->Execute(
            "UPDATE $coupon_gv_customertable
                        SET amount = '" . oos_db_input($new_gv_amount) . "'"
        );
    } else {
        $coupon_gv_customertable = $oostable['coupon_gv_customer'];
        $dbconn->Execute(
            "INSERT INTO $coupon_gv_customertable
                                    (customer_id,
                                     amount) VALUES ('" . intval($customer_id) . "',
                                                     '" . oos_db_input($coupon_amount) . "')"
        );
    }
}


 /**
  * Get tax rate from tax description
  *
  * @param  $tax_desc
  * @return string
  */
function oos_get_tax_rate_from_desc($tax_desc)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $tax_ratestable = $oostable['tax_rates'];
    $sql = "SELECT tax_rate
            FROM $tax_ratestable
            WHERE tax_description = '" . oos_db_input($tax_desc) . "'";
    $tax = $dbconn->Execute($sql);
    return $tax->fields['tax_rate'];
}
