<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type: modifier
 * Name: escape_wquotes
 * Version: 1.0
 * Date: 2004-05-11
 * Author: Carlo Sacripante sacripante[NOSPAM]@libero.it
 * Purpose: Escape chr(146) to chr(151) from MS Word text
 * Notes:  This modifier uses a simple PHP str_replace function to
 *  replace single and double quotes characters from a Microsoft Word
 * 	"cut and paste like" string, with an HTML encoded string.
 *
 * Example smarty code:
 *
 * {$dirtyString|escape_wquotes}
 *
 *
 * -------------------------------------------------------------
 */

function smarty_modifier_escape_wquotes ($text)

{
   $badwordchars=array(
                           chr(145),
                           chr(146),
                           chr(147),
                           chr(148),
                           chr(151)
                           );
   $fixedwordchars=array(
                           "&acute;",
                           "&acute;",
                           '&quot;',
                           '&quot;',
                           '&mdash;'
                           );
   return str_replace($badwordchars,$fixedwordchars,$text);
}

?>