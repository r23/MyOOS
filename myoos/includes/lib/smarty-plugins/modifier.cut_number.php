<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty cut_number modifier plugin
 *
 * Type:     modifier<br>
 * Name:     cut_number<br>
 *
 * @param  string
 * @param  string
 * @return string
 */
function smarty_modifier_cut_number($number)
{
    $number = explode(".", $number, 2);

    if ($number[1] == '0000') {
        return $number[0];
    }

    return $number;
}
