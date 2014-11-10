<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty clean_text modifier plugin
 *
 * Type:     modifier<br>
 * Name:     clean_text<br>
 * Purpose:  Cleans text of all formating and scripting code
 *
 * @author r23 <info@r23.de>
 * @version    1.0
 * @param string
 * @return string
 */
function smarty_modifier_clean_text($string, $with_links = true)
{

/*
   Based on:
   File: mambo.php,v 1.186 2004/09/29 15:54:32 saka
   Mambo_4.5.1
*/
    $string = preg_replace( "'<script[^>]*>.*?</script>'si", '', $string );

    if ($with_links) {
      $string = preg_replace( '/<a\s+.*?href="([^"]+)"[^>]*>([^<]+)<\/a>/is', '\2 (\1)', $string );
    }

    $string = preg_replace( '/<!--.+?-->/', '', $string );
    $string = preg_replace( '/{.+?}/', '', $string );
    $string = preg_replace( '/&nbsp;/', ' ', $string );
    $string = preg_replace( '/&amp;/', ' ', $string );
    $string = preg_replace( '/&quot;/', ' ', $string );

    $string = strip_tags( $string );

    #$string = htmlspecialchars( $string );

    return $string;

}

?>