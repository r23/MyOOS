<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_word_cleaner.php,v 2.3 01/05/2017 Sloppy Words Cleaner
   ----------------------------------------------------------------------
   this version by @raiwa info@oscaddons.com www.oscaddons.com

   http://www.gokartsrus.com

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2017 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


function replace_word($sStr)
{
    // replace target words
    $word = [
      // duplicate the line below if you want to add new entry
      "Saint" => "St", // caution, first letter must be uppercase
      "Sainte" => "Ste", // plural of the word must be interpreted too in present case
      "Saints" => "Sts", // femenin of the word must be interpreted too in present case
      "Saintes" => "Stes", // femenin plural of the word must be interpreted too in present case
    ];
    return strtr($sStr, $word);
}


function oos_remove_shouting($sStr, $is_name = false)
{
    $specials_first = 'ä|ö|ü|á|é|ó|ú|í|ñ|à|è|ò|ù|ì|â|ê|ô|û|î|ë|ï|å|ã|æ|ø|ç';

    // exceptions to standard case conversion
    if ($is_name) {
        $all_uppercase = '';
        $all_lowercase = 'Or|And';
    } else {
        // address abreviations and anything else
        $all_uppercase = 'Aly|Anx|Apt|Ave|Bch|Blvd|Bldg|Bp|bp|Bsmt|Byu|Ch|Cors|Cswy|Cr|Crk|Crt|Cts|Cv|Cvs|Est|Ests|Expy|Frnt|Fl|Frks|Fwy|Gdn|Gtwy|Hbr|Hbrs|Hts|Hwy|Ii|Iii|Iv|Jct|Jcts|Lk|Lks|Ln|Ldg|Mnt|Mnr|Mnrs|Msn|Mtwy|Mtn|Mtns|Ne|Nw|Pkwy|Pl|Pln|Plns|Ph|Po|Pob|P.o.b.|P.O.b.|p.O.b.|p.o.B.|p.O.B.|Rm|Rr|Se|Skwy|Smt|Sw|Sta|Ste|Sq|Ter|Tpke|Trpk|Trlr|Trl|Trwy|Vl|Vlg|Vlgs|Vly|Vlys|Vi|Vii|Viii|Xi|Xing|Xrd';
        $all_lowercase = 'À|A|And|As|Am|An|Au|Aux|By|D|Da|De|Des|Del|Du|Der|Die|Das|En|Et|In|L|Le|La|Les|Of|Or|Ou|Sous|Sur|To|Von|Y';
    }

    $prefixes = "'|Mc|Mac";
    $suffixes = "'S";

    // captialize all first letters
    $sStr = mb_convert_case((string) $sStr, MB_CASE_TITLE, "UTF-8");

    if ($specials_first) {
        $sStr = preg_replace_callback(
            "/\\b($prefixes)($specials_first)($specials_first)?\\b/",
            fn ($m) => $m[1] . mb_convert_case((string) $m[2], MB_CASE_UPPER, "UTF-8") . $m[3],
            $sStr
        );
    }

    if ($all_uppercase) {
        // capitalize acronymns and initialisms i.e. PO
        $sStr = preg_replace_callback(
            "/\\b($all_uppercase)\\b/",
            fn ($m) => mb_convert_case((string) $m[1], MB_CASE_UPPER, "UTF-8"),
            $sStr
        );
    }

    if ($all_lowercase) {
        // decapitalize short words i.e. and
        if ($is_name) {
            // all occurences will be changed to lowercase
            $sStr = preg_replace_callback(
                "/\\b($all_lowercase)\\b/",
                fn ($m) => mb_convert_case((string) $m[1], MB_CASE_LOWER, "UTF-8"),
                $sStr
            );
        } else {
            // first and last word will not be changed to lower case (i.e. titles)
            $sStr = preg_replace_callback(
                "/(?<=\\W)($all_lowercase)(?=\\W)/",
                fn ($m) => mb_convert_case((string) $m[1], MB_CASE_LOWER, "UTF-8"),
                $sStr
            );
        }
    }

    if ($prefixes) {
        // capitalize letter after certain name prefixes i.e. 'Mc'
        $sStr = preg_replace_callback(
            "/\\b($prefixes)(\\w)/",
            fn ($m) => $m[1] . mb_convert_case((string) $m[2], MB_CASE_UPPER, "UTF-8"),
            $sStr
        );
    }

    if ($suffixes) {
        // decapitalize certain word suffixes i.e. 's
        $sStr = preg_replace_callback(
            "/(\\w)($suffixes)\\b/",
            fn ($m) => $m[1] . mb_convert_case(stripslashes((string) $m[2]), MB_CASE_LOWER, "UTF-8"),
            $sStr
        );
    }

    // replace target words after RemoveShouting
    $sStr = replace_word($sStr);

    return $sStr;
}

// Last Name, edit to suite your needs
function oos_remove_shouting_name($sStr, $is_name = true)
{
    $specials_first = 'ä|ö|ü|á|é|ó|ú|í|ñ|à|è|ò|ù|ì|â|ê|ô|û|î|ë|ï|å|ã|æ|ø|ç';

    if ($is_name) {
        $all_uppercase = '';
        $all_lowercase = 'D|De La|Da|De Las|Del|De Los|Der|Van De|Van Der|Vit De|Von|Or|And|Y|En|De|La|Del|Do|Du|Am|An|Der|Die|Das';
    } else {
        $all_uppercase = '';
        $all_lowercase = 'A|And|As|By|In|Of|Or|To';
    }

    $prefixes = "'|Mc|Mac";
    $suffixes = "'S";

    $sStr = mb_convert_case((string) $sStr, MB_CASE_TITLE, "UTF-8");

    if ($specials_first) {
        $sStr = preg_replace_callback(
            "/\\b($prefixes)($specials_first)($specials_first)?\\b/",
            fn ($m) => $m[1] . mb_convert_case((string) $m[2], MB_CASE_UPPER, "UTF-8") . $m[3],
            $sStr
        );
    }

    if ($all_uppercase) {
        $sStr = preg_replace_callback(
            "/\\b($all_uppercase)\\b/",
            fn ($m) => mb_convert_case((string) $m[1], MB_CASE_UPPER, "UTF-8"),
            $sStr
        );
    }

    if ($all_lowercase) {
        if ($is_name) {
            $sStr = preg_replace_callback(
                "/\\b($all_lowercase)\\b/",
                fn ($m) => mb_convert_case((string) $m[1], MB_CASE_LOWER, "UTF-8"),
                $sStr
            );
        } else {
            $sStr = preg_replace_callback(
                "/(?<=\\W)($all_lowercase)(?=\\W)/",
                fn ($m) => mb_convert_case((string) $m[1], MB_CASE_LOWER, "UTF-8"),
                $sStr
            );
        }
    }

    if ($prefixes) {
        $sStr = preg_replace_callback(
            "/\\b($prefixes)(\\w)/",
            fn ($m) => $m[1] . mb_convert_case((string) $m[2], MB_CASE_UPPER, "UTF-8"),
            $sStr
        );
    }

    if ($suffixes) {
        $sStr = preg_replace_callback(
            "/(\\w)($suffixes)\\b/",
            fn ($m) => $m[1] . mb_convert_case(stripslashes((string) $m[2]), MB_CASE_LOWER, "UTF-8"),
            $sStr
        );
    }

    // replace target words after RemoveShouting
    $sStr = replace_word($sStr);

    return $sStr;
}
