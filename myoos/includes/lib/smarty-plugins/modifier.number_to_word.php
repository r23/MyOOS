<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty number_to_word modifier plugin
 *
 * Type:     modifier<br>
 * Name:     number_to_word<br>
 * @param string
 * @return string
 */
function smarty_modifier_number_to_word($number)
{
    $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    return $f->format($number);
}
