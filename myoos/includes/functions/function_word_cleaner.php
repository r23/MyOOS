<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  function replace_word($str) {

	// replace target words
    $word = array (
	  // duplicate the line below if you want to add new entry
	  "Saint" => "St", // caution, first letter must be uppercase
	  "Sainte" => "Ste", // plural of the word must be interpreted too in present case
	  "Saints" => "Sts", // femenin of the word must be interpreted too in present case
	  "Saintes" => "Stes", // femenin plural of the word must be interpreted too in present case
	);
	return strtr($str,$word);
  }

  function RemoveShouting($str, $is_name=false) {
	
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
   $str = mb_convert_case($str, MB_CASE_TITLE, CHARSET);

   if ($specials_first) {
     $str = preg_replace_callback(
          "/\\b($prefixes)($specials_first)($specials_first)?\\b/", 
          function ($m) {
            return $m[1] . mb_convert_case($m[2], MB_CASE_UPPER, CHARSET) . $m[3];
          },
          $str
          );
   }
   
   if ($all_uppercase) {
   	 // capitalize acronymns and initialisms i.e. PO
       $str = preg_replace_callback(
   	 		   "/\\b($all_uppercase)\\b/",
           function ($m) {
             return mb_convert_case($m[1], MB_CASE_UPPER, CHARSET);
           },
           $str
           );
   }
   
   if ($all_lowercase) {
   	 // decapitalize short words i.e. and
       if ($is_name) {
       	 // all occurences will be changed to lowercase
           $str = preg_replace_callback(
           	 	"/\\b($all_lowercase)\\b/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	);   
       } else {
       	 // first and last word will not be changed to lower case (i.e. titles)
           $str = preg_replace_callback(
           	 	"/(?<=\\W)($all_lowercase)(?=\\W)/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	);   
       }
   }
   
   if ($prefixes) {
   	 // capitalize letter after certain name prefixes i.e. 'Mc'
       $str = preg_replace_callback(
          "/\\b($prefixes)(\\w)/",
          function ($m) {
          	return $m[1] . mb_convert_case($m[2], MB_CASE_UPPER, CHARSET);
          },
          $str
          );   
   }

   if ($suffixes) {
   	 // decapitalize certain word suffixes i.e. 's
       $str = preg_replace_callback(
          "/(\\w)($suffixes)\\b/",
          function ($m) {
          	return $m[1] . mb_convert_case(stripslashes($m[2]), MB_CASE_LOWER, CHARSET);
          },
          $str
          );   
   }
   
   // replace target words after RemoveShouting
   $str = replace_word($str);

   return $str;
}

// Last Name, edit to suite your needs
function RemoveShoutingLN($str, $is_name=true) {
	
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
   
   $str = mb_convert_case($str, MB_CASE_TITLE, CHARSET);

   if ($specials_first) {
     $str = preg_replace_callback(
          "/\\b($prefixes)($specials_first)($specials_first)?\\b/", 
          function ($m) {
            return $m[1] . mb_convert_case($m[2], MB_CASE_UPPER, CHARSET) . $m[3];
          },
          $str
          );
   }
   
   if ($all_uppercase) {
       $str = preg_replace_callback(
   	 		   "/\\b($all_uppercase)\\b/",
           function ($m) {
             return mb_convert_case($m[1], MB_CASE_UPPER, CHARSET);
           },
           $str
           );
   }
   
   if ($all_lowercase) {
       if ($is_name) {
           $str = preg_replace_callback(
           	 	"/\\b($all_lowercase)\\b/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	); 
       } else {
           $str = preg_replace_callback(
           	 	"/(?<=\\W)($all_lowercase)(?=\\W)/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	);   
       }
   }
   
   if ($prefixes) {
       $str = preg_replace_callback(
          "/\\b($prefixes)(\\w)/",
          function ($m) {
          	return $m[1] . mb_convert_case($m[2], MB_CASE_UPPER, CHARSET);
          },
          $str
          );   
   }
   
   if ($suffixes) {
       $str = preg_replace_callback(
          "/(\\w)($suffixes)\\b/",
          function ($m) {
          	return $m[1] . mb_convert_case(stripslashes($m[2]), MB_CASE_LOWER, CHARSET);
          },
          $str
          );   
   }
   
   // replace target words after RemoveShouting
   $str = replace_word($str);

   return $str;
}

// Company Name, edit to suite your needs
function RemoveShoutingCN($str, $is_name=false) {
	
   if ($is_name) {
       $all_uppercase = '';
       $all_lowercase = 'De La|De Las|Del|De Los|Der|Van De|Van Der|Vit De|Von|Or|And';
   } else {
       $all_uppercase = 'S.a.|S.l.|3m|Aa|Aaa|Ab|Abc|Abn|Aflac|Ag|Akso|Amd|Aol|Basf|Bb|Bbb|Bmw|Ca|Cbs|Cc|Ccc|Csx|Cvs|Dd|Ddd|Dec|Dhl|Ee|Eee|Ff|Fff|Ftd|Gg|Ggg|Ge|Gm|Gnc|Hh|Hhh|Hsbc|Ii|Iii|Ibm|Jj|Jjj|Jal|Jbl|Jvc|Kk|Kkk|Kfc|Klm|Lcl|Ll|Lll|Ltd|Lg|Mbna|Mips|Mm|Mmm|Mci|Mgm|Mvc|Ncr|Nn|Nnn|Nec|Oo|Ooo|Pmc|Pp|Ppp|Pg&e|Qq|Qqq|Qantas|Qvc|Rca|Reo|Rr|Rrr|Rsa|Sa|Saab|Sap|Sas|Scb|Sco|Sega|Skf|Snk|Ss|Sss|Stx|Tcl|Tcs|Tnt|Tt|Ttt|Twa|Uu|Uuu|Ua|Ubl|Ubs|Ul|Ups|Usx|Vv|Vvv|Vw|Ww|Www|Xx|Xxx|Yy|Yyy|Zz|Zzz';
       $all_lowercase = 'A|And|As|By|In|Of|Or|To|The';
   }
   
   $prefixes = '';
   $suffixes = "'S";
   
   $str = mb_convert_case($str, MB_CASE_TITLE, CHARSET);

   if ($all_uppercase) {
       $str = preg_replace_callback(
   	 		   "/\\b($all_uppercase)\\b/",
           function ($m) {
             return mb_convert_case($m[1], MB_CASE_UPPER, CHARSET);
           },
           $str
           );
   }
   
   if ($all_lowercase) {
       if ($is_name) {
           $str = preg_replace_callback(
           	 	"/\\b($all_lowercase)\\b/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	);   
       } else {
           $str = preg_replace_callback(
           	 	"/(?<=\\W)($all_lowercase)(?=\\W)/",
           	 	function ($m) {
           	 		return mb_convert_case($m[1], MB_CASE_LOWER, CHARSET);
           	 	},
           	 	$str
           	 	);   
       }
   }
   
   if ($prefixes) {
       $str = preg_replace_callback(
          "/\\b($prefixes)(\\w)/",
          function ($m) {
          	return $m[1] . mb_convert_case($m[2], MB_CASE_UPPER, CHARSET);
          },
          $str
          );   
   }
   
   if ($suffixes) {
       $str = preg_replace_callback(
          "/(\\w)($suffixes)\\b/",
          function ($m) {
          	return $m[1] . mb_convert_case(stripslashes($m[2]), MB_CASE_LOWER, CHARSET);
          },
          $str
          );   
   }
   return $str;
}  
?>