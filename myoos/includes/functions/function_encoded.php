<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

  /**
   * ensure this file is being included by a parent file
   */
  defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


 /**
  * Decode string encoded with htmlspecialchars()
  *
  * @param  $sStr
  * @return string
  */
function oos_decode_special_chars($sStr)
{
    $sStr = str_replace('&gt;', '>', (string) $sStr);
    $sStr = str_replace('&lt;', '<', $sStr);
    $sStr = str_replace('&#039;', "'", $sStr);
    $sStr = str_replace('&quot;', "\"", $sStr);
    $sStr = str_replace('&amp;', '&', $sStr);

    return $sStr;
}


 /**
  * string encoded
  *
  * @param  $sStr
  * @return string
  */
function oos_make_filename($sStr)
{
    static $aFrom = [
                   ' ',

                   'Ä',
                   'ä',

                   'Ö',
                   'ö',

                   'Ü',
                   'ü',

                   'ß',

                   'é',
                   'è',
                   'ê',

                   'í',
                   'ì',
                   'î',

                   'á',
                   'à',
                   'â',
                   'å',

                   'ó',
                   'ò',
                   'ô',
                   'õ',

                   'ú',
                   'ù',
                   'û',

                   'ç',
                   'Ç',

                   'ñ',

                   'ý'];

    static $aTo   = [
                   '-',

                   'AE',
                   'ae',

                   'OE',
                   'oe',

                   'UE',
                   'ue',

                   'ss',

                   'e',
                   'e',
                   'e',

                   'i',
                   'i',
                   'i',

                   'a',
                   'a',
                   'a',
                   'a',

                   'o',
                   'o',
                   'o',
                   'o',

                   'u',
                   'u',
                   'u',

                   'c',
                   'C',

                   'n',

                   'y'];
    // Replace international chars not detected by every locale
    $sStr = str_replace($aFrom, $aTo, (string) $sStr);

    $special_chars = ["?",
                          "[",
                          "]",
                          "/",
                          "\\",
                          "=",
                          "<",
                          ">",
                          ":",
                          ";",
                          ",",
                          "'",
                          "\"",
                          "&",
                          "$",
                          "#",
                          "*",
                          "(",
                          ")",
                          "|",
                          "~",
                          "`",
                          "!",
                          "{",
                          "}",
                          "%",
                          "+",
                          chr(0)];
    //strip html tags from text
    $sStr = strip_tags($sStr);

    // Nuke chars not allowed in our URI
    $sStr = preg_replace('#[^0-9a-z\.\_!;,\+\-]#i', '', $sStr);

    // Recover delimiters as spaces
    $sStr = str_replace("\x01", " ", $sStr);

    $sStr = preg_replace("#\x{00a0}#siu", '', $sStr);
    $sStr = str_replace($special_chars, '', $sStr);
    $sStr = str_replace(['%20', '+'], '-', $sStr);
    $sStr = preg_replace('/[\r\n\t -]+/', '-', $sStr);
    $sStr = trim((string) $sStr, '.-_');
    $sStr = strtolower($sStr);

    return $sStr;
}

  /**
   * string encoded
   *
   * @param  $sStr
   * @return string
   */
function oos_html_to_xml($sStr)
{

    //Taken from Reverend's Jim feedparser
    //http://revjim.net/code/feedParser/feedParser-0.5.phps

    static $aEntities = [
            '&nbsp'   => "&#160;",  '&iexcl'  => "&#161;",  '&cent'   => "&#162;",
            '&pound'  => "&#163;",  '&curren' => "&#164;",  '&yen'    => "&#165;",
            '&brvbar' => "&#166;",  '&sect'   => "&#167;",  '&uml'    => "&#168;",
            '&copy'   => "&#169;",  '&ordf'   => "&#170;",  '&laquo'  => "&#171;",
            '&not'    => "&#172;",  '&shy'    => "&#173;",  '&reg'    => "&#174;",
            '&macr'   => "&#175;",  '&deg'    => "&#176;",  '&plusmn' => "&#177;",
            '&sup2'   => "&#178;",  '&sup3'   => "&#179;",  '&acute'  => "&#180;",
            '&micro'  => "&#181;",  '&para'   => "&#182;",  '&middot' => "&#183;",
            '&cedil'  => "&#184;",  '&sup1'   => "&#185;",  '&ordm'   => "&#186;",
            '&raquo'  => "&#187;",  '&frac14' => "&#188;",  '&frac12' => "&#189;",
            '&frac34' => "&#190;",  '&iquest' => "&#191;",  '&Agrave' => "&#192;",
            '&Aacute' => "&#193;",  '&Acirc'  => "&#194;",  '&Atilde' => "&#195;",
            '&Auml'   => "&#196;",  '&Aring'  => "&#197;",  '&AElig'  => "&#198;",
            '&Ccedil' => "&#199;",  '&Egrave' => "&#200;",  '&Eacute' => "&#201;",
            '&Ecirc'  => "&#202;",  '&Euml'   => "&#203;",  '&Igrave' => "&#204;",
            '&Iacute' => "&#205;",  '&Icirc'  => "&#206;",  '&Iuml'   => "&#207;",
            '&ETH'    => "&#208;",  '&Ntilde' => "&#209;",  '&Ograve' => "&#210;",
            '&Oacute' => "&#211;",  '&Ocirc'  => "&#212;",  '&Otilde' => "&#213;",
            '&Ouml'   => "&#214;",  '&times'  => "&#215;",  '&Oslash' => "&#216;",
            '&Ugrave' => "&#217;",  '&Uacute' => "&#218;",  '&Ucirc'  => "&#219;",
            '&Uuml'   => "&#220;",  '&Yacute' => "&#221;",  '&THORN'  => "&#222;",
            '&szlig'  => "&#223;",  '&agrave' => "&#224;",  '&aacute' => "&#225;",
            '&acirc'  => "&#226;",  '&atilde' => "&#227;",  '&auml'   => "&#228;",
            '&aring'  => "&#229;",  '&aelig'  => "&#230;",  '&ccedil' => "&#231;",
            '&egrave' => "&#232;",  '&eacute' => "&#233;",  '&ecirc'  => "&#234;",
            '&euml'   => "&#235;",  '&igrave' => "&#236;",  '&iacute' => "&#237;",
            '&icirc'  => "&#238;",  '&iuml'   => "&#239;",  '&eth'    => "&#240;",
            '&ntilde' => "&#241;",  '&ograve' => "&#242;",  '&oacute' => "&#243;",
            '&ocirc'  => "&#244;",  '&otilde' => "&#245;",  '&ouml'   => "&#246;",
            '&divide' => "&#247;",  '&oslash' => "&#248;",  '&ugrave' => "&#249;",
            '&uacute' => "&#250;",  '&ucirc'  => "&#251;",  '&uuml'   => "&#252;",
            '&yacute' => "&#253;",  '&thorn'  => "&#254;",  '&yuml' =>   "&#255;"
    ];
    $sStr = strtr($sStr, $aEntities);

    return $sStr;
}
