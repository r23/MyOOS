<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: language.php,v 1.4 2002/03/06 09:17:10 voll
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Based on:
   PHP-NUKE Web Portal System - http://phpnuke.org/
   Thatware - http://thatware.org/
   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file: Gregor J. Rothfuss
   Purpose of file: Provide ML functionality for the installer.
   ---------------------------------------------------------------------- 
 */

/**
 * Loads the required language file for the installer
 **/
function installer_get_language()
{
    global $currentlang;

    if (!isset($currentlang)) {
        $currentlang = 'de_DE';
    }
    if (file_exists($file="locales/$currentlang.php")) {
        @include $file;
    }
}

// Make common language selection dropdown (from Tim Litwiller)
function lang_dropdown()
{
    global $currentlang;

    $locale_dir = './locales/';
    $lang = languagelist();
    $langlist = [];

    if (is_dir($locale_dir)) {
        if ($dh = opendir($locale_dir)) {
            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..' || $file == 'CVS' || filetype($locale_dir . $file) == 'dir') {
                    continue;
                }
                $sContent = substr($file, 0, -4);
                if (is_file($locale_dir . $file) && @$lang[$sContent]) {
                    $langlist[$sContent] = $lang[$sContent];
                }
            }
            closedir($dh);
        }
    }
    asort($langlist);

    $selection = '<select name="alanguage" class="ow-text">';
    foreach ($langlist as $k=>$v) {
        $selection .= '<option value="' . $k . '"';
        if ($currentlang == $k) {
            $selection .= ' selected';
        }
        $selection .= '>'. $v . '</option> ';
    }
    $selection .= '</select>';

    return $selection;
}


// list of all availabe languages (from Patrick Kellum <webmaster@ctarl-ctarl.com>)
function languagelist()
{
    $lang['en_US'] = LANGUAGE_ENG . ' (en_US)'; // English
    $lang['de_DE'] = LANGUAGE_DEU . ' (de_DE)'; // German
    /*
    $lang['nl_NL'] = LANGUAGE_NLD . ' (nl_NL)'; // Dutch
    $lang['en_US'] = LANGUAGE_ENG . ' (en_US)'; // English
    $lang['de_DE'] = LANGUAGE_DEU . ' (de_DE)'; // German

    $lang['dan'] = LANGUAGE_DAN; // Danish
    $lang['fin'] = LANGUAGE_FIN; // Finnish
    $lang['fra'] = LANGUAGE_FRA; // French
    $lang['ita'] = LANGUAGE_ITA; // Italian
    $lang['nor'] = LANGUAGE_NOR; // Norwegian
    $lang['por'] = LANGUAGE_POR; // Portuguese
    $lang['pol'] = LANGUAGE_POL; // Polish
    $lang['slv'] = LANGUAGE_SLV; // Slovenian
    $lang['spa'] = LANGUAGE_SPA; // Spanish
    $lang['swe'] = LANGUAGE_SWE; // Swedish
    */
    //    end of list
    return $lang;
}
