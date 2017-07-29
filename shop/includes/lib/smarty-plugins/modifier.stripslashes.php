<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty strip_tags modifier plugin
 *
 * Type:     modifier<br>
 * Name:     stripslashes<br>
 * Version:  0.1
 * Date:     September 08, 2003
 * Install:  Drop into the plugin directory
 *
 * Examples: {$product.description|stripslashes}
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */
function smarty_modifier_stripslashes($string)
{
     return  stripslashes($string);
}
