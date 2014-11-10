<?php
/* ----------------------------------------------------------------------
   $Id: function.oos_check_stock.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.212 2003/02/17 07:55:54 hpdl  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {oos_check_stock} function plugin
 *
 * Type:     function
 * Name:     oos_check_stock
 * Version:  1.0
 * -------------------------------------------------------------
 */

function smarty_function_oos_check_stock($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
    
    $products_id = ''; 
    $products_quantity = '';

    
    foreach($params as $_key => $_val) {
      $$_key = smarty_function_escape_special_chars($_val);
    } 
      
    $stock_left = oos_get_products_stock($products_id) - $products_quantity;
    $out_of_stock = '';
    
    if ($stock_left < 0) {
      $out_of_stock = '<span class="oos-MarkProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }
    
    return $out_of_stock;
  }

?>
