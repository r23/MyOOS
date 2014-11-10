<?php
/* ----------------------------------------------------------------------
   $Id: language.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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
   ---------------------------------------------------------------------- */

/** Loads the required language file for the installer **/
function installer_get_language() {
   global $currentlang;

   if (!isset($currentlang)) {
     $currentlang = 'de_DE.iso-8859-15'; 
   }
   if (file_exists($file="locales/$currentlang/global.php")) {
     @include $file;
   }
}

// Make common language selection dropdown (from Tim Litwiller)
   function lang_dropdown() {
      global $currentlang;

      $locale_dir = './locales/';
      $lang = languagelist();
      $langlist = array();

      if (is_dir($locale_dir)) {
        if ($dh = opendir($locale_dir)) {
          while (($file = readdir($dh)) !== false) {
            if ($file == '.' || $file == '..' || $file == 'CVS' || filetype($locale_dir . $file) == 'file' ) continue;
            if (is_dir($locale_dir . $file) && @$lang[$file]) {
              $langlist[$file] = $lang[$file];
            }
          }
          closedir($dh);
        }
      }
      asort($langlist);

      $selection = '<select name="alanguage" class="ow-text">';
      foreach ($langlist as $k=>$v) {
        $selection .= '<option value="' . $k . '"';
        if ( $currentlang == $k) {
          $selection .= ' selected';
         }
        $selection .= '>'. $v . '</option> ';
      }
      $selection .= '</select>';

      return $selection;

   }


// list of all availabe languages (from Patrick Kellum <webmaster@ctarl-ctarl.com>)
   function languagelist() {
    /*
      $lang['nl_NL.utf-8'] = LANGUAGE_NLD . ' (nl_NL.utf-8)'; // Dutch
      $lang['en_US.utf-8'] = LANGUAGE_ENG . ' (en_US.utf-8)'; // English
      $lang['de_DE.utf-8'] = LANGUAGE_DEU . ' (de_DE.utf-8)'; // German
    */
      $lang['ru_RU.utf-8'] = LANGUAGE_RUS . ' (ru_RU.utf-8)'; // Russian

      $lang['nl_NL.iso-8859-15'] = LANGUAGE_NLD . ' (nl_NL.iso-8859-15)'; // Dutch
      $lang['en_US.iso-8859-15'] = LANGUAGE_ENG . ' (en_US.iso-8859-15)'; // English
      $lang['de_DE.iso-8859-15'] = LANGUAGE_DEU . ' (de_DE.iso-8859-15)'; // German

      $lang['ru_RU.CP1251'] = LANGUAGE_RUS . ' (ru_RU.CP1251)'; // Russian
      
      
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
//    end of list
      return $lang;
}

?>
