<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty number_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_format<br>
 * @param string
 * @param string
 * @return string
 */
function smarty_modifier_number_format($number)
{
    if (($number < 10) && (substr($number, 0, 1) != '0')) $number = '0' . $number;

    return $number;
}

?>