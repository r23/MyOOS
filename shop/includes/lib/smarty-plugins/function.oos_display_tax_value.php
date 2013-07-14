<?php
/* ----------------------------------------------------------------------
   $Id: function.oos_display_tax_value.php 216 2013-04-02 08:24:45Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
 * Name:     oos_display_price
 * Version:  1.0
 * Date:    
 * Purpose:	
 *			
 *       
 * Install:  Drop into the plugin directory
 * Author:   
 * -------------------------------------------------------------
 */

function smarty_function_oos_display_tax_value($params, &$smarty)
{
     
   require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');
  
   $value = '';
   $padding = TAX_DECIMAL_PLACES;
   
   foreach($params as $_key => $_val) {
     $$_key = smarty_function_escape_special_chars($_val);
   } 

   if (strpos($value, '.')) {
     $loop = true;
     while ($loop) {
       if (substr($value, -1) == '0') {
         $value = substr($value, 0, -1);
       } else {
         $loop = false;
         if (substr($value, -1) == '.') {
           $value = substr($value, 0, -1);
         }
       }
     }
   }
   if ($padding > 0) {
     if ($decimal_pos = strpos($value, '.')) {
       $decimals = strlen(substr($value, ($decimal_pos+1)));
       for ($i=$decimals; $i<$padding; $i++) {
         $value .= '0';
       }
     } else {
       $value .= '.';
       for ($i=0; $i<$padding; $i++) {
         $value .= '0';
       }
     }
   }
   return $value;
}
?>
