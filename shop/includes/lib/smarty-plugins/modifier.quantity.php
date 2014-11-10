<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/*
 * Smarty plugin
 * 
 * Type:     modifier
 * Name:     quantity<br />
 * Version:  0.1
 * Date:     August 01, 2006
 * Install:  Drop into the plugin directory
 *
 * Examples: {$discount|quantity:2}
 * Author:   r23 <info at r23 dot de>
 *
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_quantity($number, $decimal_places = 2)
{

    if (DECIMAL_CART_QUANTITY == 'true') {
      $number = number_format($number, $decimal_places);
    } else {
      $number = number_format($number);
    }

    return $number;
}

?>