<?php
/* ----------------------------------------------------------------------
   $Id: function_products_attributes.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

 /**
  * Products Attributes
  *
  * @link http://www.oos-shop.de/
  * @package Products Attributes
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */

 /**
  * Return options name
  *
  * @param $options_id
  * @return string
  */
  function oos_options_name($options_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_optionstable = $oostable['products_options'];
    $query = "SELECT products_options_name
              FROM $products_optionstable
              WHERE products_options_id = '" . $options_id . "'
                AND products_options_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);

    $products_options_name = $result->fields['products_options_name'];

    // Close result set
    $result->Close();

    return $products_options_name;
  }


 /**
  * Return values name
  *
  * @param $values_id
  * @return string
  */
  function oos_values_name($values_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_options_valuestable = $oostable['products_options_values'];
    $query = "SELECT products_options_values_name
              FROM $products_options_valuestable
              WHERE products_options_values_id = '" . $values_id . "'
                AND products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $result = $dbconn->Execute($query);

    $products_options_values_name = $result->fields['products_options_values_name'];

    // Close result set
    $result->Close();

    return $products_options_values_name;
  }


 /**
  * Draw a pulldown for Option Types
  *
  * @param $name
  * @param $default
  */
  function oos_draw_option_type_pull_down_menu($name, $default = '') {
    global $products_options_types_list;

    $values = array();
    foreach ($products_options_types_list as $id => $text) {
      $values[] = array('id' => $id, 'text' => $text);
    }
    return oos_draw_pull_down_menu($name, $values, $default);
  }


 /**
  * Return options type name
  *
  * @param $opt_type
  */
  function oos_options_type_name($opt_type) {
    global $products_options_types_list;

    return isset($products_options_types_list[$opt_type]) ? $products_options_types_list[$opt_type] : 'Error ' . $opt_type;
  }

