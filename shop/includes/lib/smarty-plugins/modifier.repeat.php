<?php
/** 
 * Smarty plugin 
 * @package Smarty 
 * @subpackage plugins 
 */ 


/** 
 * Smarty repeat modifier plugin 
 * 
 * Type:     modifier 
 * Name:     repeat 
 * Date:     Feb 19, 2005 
 * Example:  {$level|repeat:"str"} 
 */ 

function smarty_modifier_repeat($level, $replace = ' ') 
{ 
    return str_repeat($replace, $level); 
}

?>