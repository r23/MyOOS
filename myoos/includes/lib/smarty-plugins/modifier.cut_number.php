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
    $aNumber = explode(".", (string) $number, 2);

    if ($aNumber[1] == '0000') {
        return $aNumber[0];
    }

    return $number;
}
