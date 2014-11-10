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
 * Name:     number<br>
 * Version:  0.1
 * Date:     November 03, 2004
 * Install:  Drop into the plugin directory
 *
 * Examples: {$discount|number:2}
 * Author:   r23 <info at r23 dot de>
 *
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_number($number, $decimal_places = 2)
{
    return number_format($number, $decimal_places);
}

?>