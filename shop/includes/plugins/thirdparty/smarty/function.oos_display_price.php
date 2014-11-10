<?php
 /* ----------------------------------------------------------------------
   $Id: function.oos_display_price.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

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
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     oos_href_link
 * Version:  1.0
 * Date:    
 * Purpose:	
 *			
 *       
 * Install:  Drop into the plugin directory
 * Author:   
 * -------------------------------------------------------------
 */

function smarty_function_oos_display_price($params, &$smarty)
{
   
   global $oCurrencies;
    
   require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');
  
   $price = '';
   $tax = '';
   $qty = '';
   $calculate_currency_value = true;
   $currency = '';
   $currency_value = '';
   
   foreach($params as $_key => $_val) {
     $$_key = smarty_function_escape_special_chars($_val);
   } 

   print $oCurrencies->format(oos_add_tax($price, $tax) * $qty, $calculate_currency_value, $currency, $currency_value);
}
?>
