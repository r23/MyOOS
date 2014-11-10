<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {highlight_char} modifier plugin
 *
 * Type:     modifier<br>
 * Name:     highlight_char<br>
 * Date:     March 8, 2004<br>
 * Purpose:  highlight first character of the input string with css class<br>
 * Input:
 *         - string = string whose first char should be wrapped in the given class
 * 
 * Examples:<br>
 * <pre>
 * {$xxx|highlight_char:big_font}
 * Output:
 *   <span class="bigfont">x</span>xx
 *
 * {$xxx|highlight_char:none:b}	 {* you cannot leave a param empty, so none is no class *}
 * Output:
 *   <b>x</b>xx
 *
 * </pre>
 * @author Mark Hewitt <mark at formfunction dot co dot za>
 * @version  1.0
 * @param string $string Text to modify
 * @param string $css_class CSS classname to put in span tags, use 'none' to ignore
 * @param string $tag_name (Optional) Tag to wrap first char in
 * @param Smarty
 * @return string|null
 */
function smarty_modifier_highlight_char($string,$css_class,$tag_name="span")
{ 	  									  
	// strip whitespace off the front of the string, and make sure
	// there are characters in the string, we only return valid characters...
	if ( ($s = trim($string)) != '' )
	{				
		$html = "<$tag_name";
		if ( $css_class != 'none' ) $html .= ' class="'.$css_class.'"';
		$html .= '>'.substr($s,0,1)."</$tag_name>".substr($s,1);
		return $html;
	}
	else
	{	
		return '';
	}
}
?>