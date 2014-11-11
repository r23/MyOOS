<?php
 /*
  * Smarty plugin
  *
  * Type:     modifier
  * Name:     str_pad
  * Date:     Jun 02, 2004
  * Version:  1.0
  * Author:   Pablo Dias <pablo at grafia dot com dot br>
  * Purpose:  pad a string to a certain length with another string. like php/str_pad
  *
  * Example:  {$text|str_pad:20:'.':'both'}
  *    will pad $string with dots, in both sides 
  *    until $text length equal to 20 characteres
  *    (assuming that $text has less than 20 characteres)
  *
  * Input:
  *    string - the string to be padded
  *    length - desired string length
  *    pad_string - string used to pad
  *    pad_type - both, left or right
  */

function smarty_modifier_str_pad($string, $length, $pad_string=' ', $pad_type='left') {
  $pads = array('left'=>0, 'right'=>1, 'both'=>2);
  if(array_key_exists($pad_type, $pads))
    return str_pad($string, $length ,$pad_string,$pads[$pad_type]);
}