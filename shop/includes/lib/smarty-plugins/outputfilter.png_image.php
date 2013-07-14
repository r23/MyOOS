<?php
/*
 * Smarty plugin
 *-------------------------------------------------------------
 * File:     outputfilter.png_image.php
 * Type:     outputfilter
 * Name:     png_image
 * Date:     March 20, 2006
 * Version:  0.1
 * Author:   Bradley Holt <bradley@foundline.com>
 * License:  LGPL <http://www.gnu.org/copyleft/lesser.html>
 * Purpose:  Output a PNG image with Alpha Transparency without
 *           requiring a Smarty function.
 *           If browser is IE then we use a special trick with
 *           the AlphaImageLoader FILTER style.
 *           For all other browser we don't do anything special
 *           because they display PNG's correctly.
 * Usage:    Drop this PHP file into your Smarty plugins
 *           directory and load the output filter by calling:
 *           $smarty->load_filter('output', 'png_image');
 * Notes:    Based on Bart Bons' png_image function.
 *           This will only work if you use double quotes
 *           in your HTML img tags. It will not work if you
 *           use single quotes in your HTML img tags.
 *           The regex should be flexible enough to output
 *           either HTML or XHTML compliant code automatically
 *           based on the input.
 *           I have not used this with caching but imagine that
 *           you would have to add $_SERVER['HTTP_USER_AGENT']
 *           to your cache_id.
 *-------------------------------------------------------------
 */

function smarty_outputfilter_png_image($tpl_source, &$smarty)
{
 $PNGcompliantAgent = !(stristr( $_SERVER['HTTP_USER_AGENT'], 'MSIE') && stristr( $_SERVER['HTTP_USER_AGENT'], 'Windows'));
 if ($PNGcompliantAgent) {
   return $tpl_source;
 } else {
   return preg_replace("/<img([^>]*)src=\"([^\">]*)\.png\"([^>]*)>/",
      "<img\$1src=\"/images/spacer.gif\"style=\"px;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='\$2.png',sizingMethod='scale');\"\$3>", $tpl_source);
 }
}
?>