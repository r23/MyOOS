<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty linkurl modifier plugin
 *
 * Type:     modifier<br>
 * Name:     linkurl<br>
 * Purpose:  links URLs und shortens it to $length
 *
 * Author:   Christoph Erdmann 
 * Internet: http://www.cerdmann.com
 *
 * Changelog:
 * 2004-11-24 New parameter allows truncation without linking the URL
 * 2004-11-20 In braces enclosed URLs are now better recognized
 *
 * Modified by r23 <info@r23.de> for OSIS Online Shop
 * Install:  Drop into the plugin directory
 *
 * Examples: {$html_link|linkurl:45:false}
 *           {$html_link|linkurl:30}
 *
 * @param string
 * @param integer
 * @param boolean
 * @return string
 */
function smarty_modifier_linkurl($string, $length = 50, $link = true) {
  
  if (!function_exists(oos_truncate)) {
    function oos_truncate($string, $length) {
      $returner = $string;
      if (strlen($returner) > $length) {
        $url = preg_match("=[^/]/[^/]=",$returner,$treffer,PREG_OFFSET_CAPTURE);
        $cutpos = $treffer[0][1]+2;
        $part[0] = substr($returner,0,$cutpos);
        $part[1] = substr($returner,$cutpos);

        $strlen1 = $cutpos;
        if ($strlen1 > $length) return substr($returner,0,$length-3).'...';
        $strlen2 = strlen($part[1]);
        $cutpos = $strlen2-($length-3-$strlen1);
        $returner = $part[0].'...'.substr($part[1],$cutpos);
      }
      return $returner;
    }
  }

  if ($link == true){
    $pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>\)]+)([\s\n<>\)]|$)#sme';
    $string = preg_replace($pattern,"'$1<a href=\"$2$3\" title=\"$2$3\" target=\"_blank\">'.oos_truncate('$2$3',$length).'</a>$4'",$string);
  } else {
    $pattern = '#(^|[^\"=]{1})(http://|ftp://|mailto:|news:)([^\s<>\)]+)([\s\n<>\)]|$)#sme';
    $string = preg_replace($pattern,"oos_truncate('$2$3',$length)",$string);
  }

  return $string;
}

?>