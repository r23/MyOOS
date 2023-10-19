<?php
/**
   ----------------------------------------------------------------------
   $Id: class_payment_module_info.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

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

class paymentModuleInfo
{
    public $payment_code;
    public $keys;

    // class constructor
    public function __construct($pmInfo_array)
    {
        $this->payment_code = $pmInfo_array['payment_code'];

        // Get database information
        $dbconn = & oosDBGetConn();
        $oostable = & oosDBGetTables();

        for ($i = 0, $n = (is_countable($pmInfo_array) ? count($pmInfo_array) : 0) - 1; $i < $n; $i++) {
            $query = "SELECT configuration_value 
                  FROM " . $oostable['configuration'] . " 
                  WHERE configuration_key = '" . oos_db_input($pmInfo_array[$i]) . "'";
            $result = $dbconn->Execute($query);
            $key_value = $result->fields;

            $this->keys[$pmInfo_array[$i]]['title'] = constant(strtoupper($pmInfo_array[$i] . '_TITLE'));
            $this->keys[$pmInfo_array[$i]]['value'] = $key_value['configuration_value'];
            $this->keys[$pmInfo_array[$i]]['description'] = constant(strtoupper($pmInfo_array[$i] . '_DESC'));
        }
    }
}
