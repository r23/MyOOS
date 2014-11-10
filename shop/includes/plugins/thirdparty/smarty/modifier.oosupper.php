<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier
 * Name:     oosupper
 * Version:  0.1
 * Date:     September 08, 2003
 * Install:  Drop into the plugin directory
 *
 * Examples: {$box.heading|oosupper:"oos-bigInfo"}
 *           {$box.heading|oosupper}
 * Author:   r23 <info at r23 dot de>
 * -------------------------------------------------------------
 */
 
function smarty_modifier_oosupper($string, $big = "oos-bigTitle")
{

	      $string = str_replace("&szlig;", 'ß', $string );     
	      $string = str_replace("&ouml;", 'ö', $string);
	      $string = str_replace("&auml;", 'ä', $string);
	      $string = str_replace("&uuml;", 'ü', $string);
	      $string = str_replace("&Ouml;", 'Ö', $string);
	      $string = str_replace("&Auml;", 'Ä', $string);
	      $string = str_replace("&Uuml;", 'Ü', $string);
	
	      $string = strtoupper($string);
	
	      for ($i=0; $i<strlen($string); $i++) {
	         $string2 .= $string[$i] . " ";
	      }
	      $string = trim($string2);
	           
	      $string = preg_replace("#(  |^)([^ ])#", "\\1<span class=\"$big\">\\2</span>", $string);
	      $string = str_replace ("  ", "&nbsp;&nbsp;", $string);
	
	      return  $string;

}

?>