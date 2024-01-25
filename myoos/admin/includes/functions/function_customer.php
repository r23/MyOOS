<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general_elari_cs.php,v 1.1 2003/01/08 10:53:01 elarifr
   ----------------------------------------------------------------------
   For customers_status_v3.x / Admin

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Customer Status Name
 *
 * @param  $customers_status_id
 * @param  $language
 * @return string
 */
function oos_get_customer_status_name($customers_status_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $query = "SELECT customers_status_name
              FROM " . $oostable['customers_status'] . "
              WHERE customers_status_id = '" . intval($customers_status_id) . "'
                AND customers_status_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $customers_status_name = $result->fields['customers_status_name'];

    return $customers_status_name;
}

/**
 * Return all customers statuses for a specified language_id and return an array(array())
 * Use it to make pull_down_menu, checkbox
 *
 * @author  elari - Added in CS V1.1
 * @changed by $Author: r23 $
 * @param   $customers_status_id
 * @param   $language
 * @return  array(array())
 */
function oos_get_customers_statuses()
{
    $customers_statuses_array = [];

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $query = "SELECT customers_status_id, customers_status_name, customers_status_ot_discount_flag,
                     customers_status_ot_discount, customers_status_payment
              FROM " . $oostable['customers_status'] . "
              WHERE customers_status_languages_id = '" . intval($_SESSION['language_id']) . "'
              ORDER BY customers_status_id";
    $result = $dbconn->Execute($query);

    while ($customers_statuses = $result->fields) {
        $i = $customers_statuses['customers_status_id'];
        $customers_statuses_array[$i] = ['id' => $customers_statuses['customers_status_id'], 'text' => $customers_statuses['customers_status_name'], 'cs_ot_discount_flag' => $customers_statuses['customers_status_ot_discount_flag'], 'cs_ot_discount' => $customers_statuses['customers_status_ot_discount'], 'cs_payment_unallowed' => $customers_statuses['customers_status_payment']];
        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return $customers_statuses_array;
}

/**
 * Return all status info values for a customer_id in admin . no need to check session !
 * Use it to make pull_down_menu, checkbox
 *
 * @author  elari - Added in CS V1.1
 * @changed by $Author: r23 $
 * @param   $customers_id
 * @return  array(array())
 */
function oos_get_customers_status($customer_id)
{
    $customer_status_array = [];

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $query = "SELECT customers_status, customers_status_name, customers_status_public, 
                     customers_status_ot_discount_flag, customers_status_ot_discount,
                     customers_status_qty_discounts, customers_status_payment
              FROM " . $oostable['customers'] . " LEFT JOIN
                   " . $oostable['customers_status'] . " ON
                    customers_status = customers_status_id
              WHERE customers_id= '" . intval($customer_id) . "'
                AND customers_status_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);
    $customer_status_array = $result->fields;

    return $customer_status_array;
}

/**
 * Set Login Status
 *
 * @param $customer_id
 * @param $status
 */
function oos_set_customer_login($customer_id, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if ($status == '1') {
        $query = "UPDATE " . $oostable['customers'] . "
                SET customers_login = '1'
                WHERE customers_id = '" . intval($customer_id) . "'";
        $result = $dbconn->Execute($query);

        return;
    } elseif ($status == '0') {
        $query = "UPDATE " . $oostable['customers'] . " 
                SET customers_login = '0'
                WHERE customers_id = '" . intval($customer_id) . "'";
        $result = $dbconn->Execute($query);

        return;
    } else {
        return false;
    }
}

/**
 * Installed Payment
 *
 * @author    r23 <info@r23.de>
 * @copyright 2003 r23
 * @param     $customers_payment
 * @return    string
 */
function oos_installed_payment($customers_payment = '')
{
    global $aLang;

    $install_payment = '';
    $installed_payment = explode(';', (string) MODULE_PAYMENT_INSTALLED);
    $select_payment = explode(';', (string) $customers_payment);
    for ($i = 0, $n = count($installed_payment); $i < $n; $i++) {
        $file = $installed_payment[$i];

        include OOS_ABSOLUTE_PATH . 'includes/languages/' . $_SESSION['language'] . '/modules/payment/' . $file;
        include OOS_ABSOLUTE_PATH . 'includes/modules/payment/' . $file;

        $class = substr($file, 0, strrpos($file, '.'));
        if (oos_class_exits($class)) {
            $module = new $class();

            if (in_array($file, $select_payment)) {
                $install_payment .= oos_draw_checkbox_field('payment[]', $file, true) . $module->title . '<br>';
            } else {
                $install_payment .= oos_draw_checkbox_field('payment[]', $file) . $module->title . '<br>';
            }
        }
    }
    return $install_payment;
}

/**
 * Customers Payment
 *
 * @author    r23 <info@r23.de>
 * @copyright 2003 r23
 * @param     $customers_payment
 * @return    string
 */
function oos_customers_payment($customers_payment = '')
{
    global $aLang;

    $payment_title = '';
    if (oos_is_not_null($customers_payment)) {
        $select_payment = explode(';', (string) $customers_payment);
        for ($i = 0, $n = count($select_payment); $i < $n; $i++) {
            $file = $select_payment[$i];

            include OOS_ABSOLUTE_PATH . 'includes/languages/' . $_SESSION['language'] . '/modules/payment/' . $file;
            include OOS_ABSOLUTE_PATH . 'includes/modules/payment/' . $file;

            $class = substr($file, 0, strrpos($file, '.'));
            if (oos_class_exits($class)) {
                $module = new $class();
                $payment_title .= $module->title . '<br>';
            }
        }
    }
    return $payment_title;
}
