<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: add_ccgvdc_application_top.php
         gv_sent.php,v 1.1 2003/02/18 00:18:50 wilt
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
function oos_create_coupon_code($salt="secret", $length=SECURITY_CODE_LENGTH)
{
    $ccid = md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));

    mt_srand((float)microtime()*1_000_000); // seed the random number generator
    $random_start = @random_int(0, (128-$length));
    $good_result = 0;

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    while ($good_result == 0) {
        $coupon_code = substr($ccid, $random_start, $length);
        $query = "SELECT coupon_code
                FROM " . $oostable['coupons'] . " 
                WHERE coupon_code = '" . $coupon_code . "'";
        $result = $dbconn->Execute($query);
        if ($result->RecordCount() == 0) {
            $good_result = 1;
        }
    }


    return $coupon_code;
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

    $coupon_gv_query = "SELECT coupon_amount
                        FROM " . $oostable['coupons'] . "
                        WHERE coupon_id = '" . $gv_id . "'";
    $coupon_gv_result = $dbconn->Execute($coupon_gv_query);
    $coupon_gv = $coupon_gv_result->fields;

    if ($customer_gv_result->RecordCount() > 0) {
        $customer_gv_query = "SELECT amount 
                            FROM " . $oostable['coupon_gv_customer'] . "
                            WHERE customer_id = '" . $customer_id . "'";
        $customer_gv_result = $dbconn->Execute($customer_gv_query);

        $customer_gv = $customer_gv_result->fields;
        $new_gv_amount = $customer_gv['amount'] + $coupon_gv['coupon_amount'];

        $gv_query = "UPDATE " . $oostable['coupon_gv_customer'] . "
                   SET amount = '" . $new_gv_amount . "'";
        $result = $dbconn->Execute($gv_query);
    } else {
        $gv_query = "INSERT INTO " . $oostable['coupon_gv_customer'] . " 
                  (customer_id, amount) VALUES ('" . $customer_id . "', '" . $coupon_gv['coupon_amount'] . "'";
        $result = $dbconn->Execute($gv_query);
    }
}


 /**
  * Output a day/month/year dropdown selector
  *
  * @param  $prefix
  * @param  $date
  * @return string
  */
function oos_draw_date_selector($prefix, $date='')
{
    $month_array = [];
    $month_array[1] =_JANUARY;
    $month_array[2] =_FEBRUARY;
    $month_array[3] =_MARCH;
    $month_array[4] =_APRIL;
    $month_array[5] =_MAY;
    $month_array[6] =_JUNE;
    $month_array[7] =_JULY;
    $month_array[8] =_AUGUST;
    $month_array[9] =_SEPTEMBER;
    $month_array[10] =_OCTOBER;
    $month_array[11] =_NOVEMBER;
    $month_array[12] =_DECEMBER;
    $usedate = getdate($date);
    $day = $usedate['mday'];
    $month = $usedate['mon'];
    $year = $usedate['year'];
    $date_selector = '<select name="'. $prefix .'_day">';
    for ($i=1;$i<32;$i++) {
        $date_selector .= '<option value="' . $i . '"';
        if ($i==$day) {
            $date_selector .= ' selected="selected"';
        }
        $date_selector .= '>' . $i . '</option>';
    }
    $date_selector .= '</select>';
    $date_selector .= '<select name="'. $prefix .'_month">';
    for ($i=1;$i<13;$i++) {
        $date_selector .= '<option value="' . $i . '"';
        if ($i==$month) {
            $date_selector .= ' selected="selected"';
        }
        $date_selector .= '>' . $month_array[$i] . '</option>';
    }
    $date_selector .= '</select>';
    $date_selector .= '<select name="'. $prefix .'_year">';
    for ($i=2021;$i<2029;$i++) {
        $date_selector .= '<option value="' . $i . '"';
        if ($i==$year) {
            $date_selector .= ' selected="selected"';
        }
        $date_selector .= '>' . $i . '</option>';
    }
    $date_selector .= '</select>';

    return $date_selector;
}
