<?php
 /*
  * Smarty plugin
  *
  * Type:     modifier
  * Name:     str_pad_trim
  * Date:     May 04, 2005
  * Version:  0.2
  * Author:   Terence Johnson <terry at scribendi dot com>
  *           Pablo Dias <pablo at grafia dot com dot br> (modifier.str_pad.php)
  * Purpose:  Pad a string to a certain length with another string, 
  *           like php/str_pad, or shorten it if it's too long.
  *
  * Example:  {$text|str_pad_trim:20:'.':'right'}
  *    If $text has less tha 20 characters, this modifier
  *    will pad $string with dots, on the right hand side,
  *    until $text is 20 characters.  If $text has more 
  *    than 20 characters, it will shorten the string from
  *    the right hand side.
  *
  * Input:
  *    string - the string to be padded or truncated
  *    length - desired string length
  *    pad_string - string used to pad
  *    pad_type - both, left or right.
  */

function smarty_modifier_str_pad_trim($string, $length, $pad_string=' ', $pad_type='right') {
  $strlen = strlen($string);
  if ($strlen == $length) return $string; // that was easy.
  $pads = array('left'=>0, 'right'=>1, 'both'=>2);
  if(!array_key_exists($pad_type, $pads)) $pad_type = 'right';
  if ($strlen < $string) {
    return str_pad($string, $length ,$pad_string,$pads[$pad_type]);
  } elseif ($pad_type == 'left') {
    return substr($string, -$length);
  } elseif ($pad_type == 'right') {
    return substr($string,0,$length);
  } else {
    return substr($string,intval(($strlen-$length)/2),$length);
  }
}

?>