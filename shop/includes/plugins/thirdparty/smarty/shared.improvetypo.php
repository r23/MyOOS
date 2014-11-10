<?php
/*
 * Smarty plugin "ImproveTypo"
 * Purpose: improve typo for better site appearance (Comments will not be edited, so use them to prevent css-sections)
 * Home: http://www.cerdmann.com/improvetypo/
 * Copyright (C) 2005 Christoph Erdmann
 * 
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation; either version 2.1 of the License, or (at your option) any later version.
 * 
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License along with this library; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA 
 * -------------------------------------------------------------
 * Author:   Christoph Erdmann <smarty@cerdmann.com>
 * Internet: http://www.cerdmann.com
 *
 * Changelog:
 * 2006-04-12 Erkennung des Gedankenstriches sowie des ß in Versalien verbessert
 * 2005-12-01 Anführungszeichen am Anfang werden jetzt zuverlässig erkannt
 * 2005-07-02 Funktioniert jetzt auch in Templates mit Smarty-PHP-Tags und PHP4
 * 2005-05-23 Währungskorrektur und Erkennung der Anführungszeichen überarbeitet
 * 2005-05-12 Währungsangabe wird mit geschütztem Leerzeichen korrigiert
 * 2005-05-12 Fehler beim Ersetzen eines falsch gesetzten Apostrophs wurde korrigiert
 * 2004-11-18 Script-Bereiche werden jetzt auch komplett nicht korrigiert
 * 2004-10-09 Verschiede falsche Anführungszeichen werden jetzt korrigiert. Ein Euro-Symbol wird durch die Unicode-Nummer ersetzt
 * 2004-10-09 Leerzeichen nach Preis-Regex hinzugefügt, weil Durchwahlen auch geändert wurden
 * 2004-10-06 Benannte Entities wurden durch Unicode-Nummern ersetzt, um kompatibel mit PHP-Funktionen zu sein, die mit Unicode umgehen können (z.B. imagettftext)
 * 2004-10-01 "." or "," in Preisangaben ist nicht mehr optional
 * 2004-09-30 "." in Preisangaben ist akzeptiert
 * 2004-09-30 Auch Nicht-Standard-Smarty-Delimiter werden erkannt
 * 2004-09-27 Apostrophe werden besser erkannt
 * 2004-09-25 HTML-Kommentare werden jetzt wirklich nicht mehr geparsed
 * -------------------------------------------------------------
 */

function smarty_improvetypo($source, $diff = false)
	{
	########## Options
	// what did the function replace? Show it and overwrite $diff-param
	//$diff	= 0;
	// Shows plugins runtime in HTML source
	//$measuretime = 1;
	
	########## Start time measurement
	if ($measuretime == 1)
		{
		if (!function_exists('getmicrotime'))
			{
			function getmicrotime()
				{
				list($usec, $sec) = explode(" ",microtime());
				return ((float)$usec + (float)$sec);
				}
			}
		$time['start'] = getmicrotime();
		}

	
	########## Prepare source
	// Which smarty delimiters are in use?
	global $smarty;
	$ldelim = preg_quote($smarty->left_delimiter);
	$rdelim = preg_quote($smarty->right_delimiter);
	
	// replace and save HTML and smarty tags, at the end they will rereplaced. Makes the function faster.
	$what = "=(".$ldelim."php".$rdelim.".*?".$ldelim."/php".$rdelim.")=is";
	preg_match_all($what,$source,$hits['smarty_php']);
	$source = str_replace($hits['smarty_php'][0],'[#SMARTY_PHP]',$source);

	preg_match_all("=(<script.*?</script>)=is",$source,$hits['script']);
	$source = str_replace($hits['script'][0],'[#SCRIPT]',$source);
	preg_match_all("=(<[a-zA-Z!][^>]+[^ ]>)=",$source,$hits['html']);
	$source = str_replace($hits['html'][0],'[#HTML]',$source);
	preg_match_all("=(".$ldelim."[^\}]+".$rdelim.")=",$source,$hits['smarty']);
	$source = str_replace($hits['smarty'][0],'[#SMARTY]',$source);


	########## Show replacements in HTML
	// If not exists, a function called "show_diff" is created to show the replacements in source
	if (!function_exists(show_diff)) {
		function show_diff ($string,$use) {
			if ($use) {
				return '<span style="background-color: yellow">'.$string.'</span>';
			} else {
				return $string;
			}
		}
	}
		
	########## Improve Typo
	// Replace "string" with &raquo;string&laquo;
	$what = '=([^\d]|\A)"|“|”|«|»=ieS';
	$then = 'show_diff("$1{QUOTE}",$diff)';
	$source = preg_replace($what, $then, $source);

	// Now replace ...
	$source = preg_replace('/\{QUOTE\}(.*?)\{QUOTE\}/sS', "&#187;$1&#171;", $source);

	// Re-replace lonely {QUOTE}s
	$source = str_replace('{QUOTE}', "\"", $source);


	// Replace - with &ndash;
	$what = '= -(\W)=eS';
	$then = 'show_diff(" &#8211;$1",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace ... with &hellip;
	$what = '=\.{2,}=eS';
	$then = 'show_diff(" &#8230; ",$diff)';
	$source = preg_replace($what, $then, $source);

	// Correct incorrect use of apostroph
	$what = "=([a-z])('|´)s([^a-z])=ieS";
	$then = 'show_diff("$1s$3",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace the other correct apostrophes with the correct symbol
	$what = '=\'|´=eS';
	$then = 'show_diff("&#8217;",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace EUR/O with &euro; for EURO in front of number
	$what = '=(EURO?|€)\s?([0-9]+)=ieS';
	$then = 'show_diff("&#8364;&nbsp;$2",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace EUR/O with &euro; for EURO behind a number
	$what = '=([0-9-]+)\s?(EURO?|€)([^a-z]+)=ieS';
	$then = 'show_diff("$1&nbsp;&#8364;$3",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace false extension of prices
	$what = '=([0-9])(<[^>]+>)?(,|\.)-([^a-z])=ieS';
	$then = 'show_diff("$1$2,&#8212;$4",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace Telefon: or Tel: with Tel.:
	$what = '=[^a-z](Telefon:)|(Tel:)=ieS';
	$then = 'show_diff(" Tel.:",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace false E-Mail-Writing
	$what = '=[eE]-?[mM]ail([^a-zA-Z])=eS';
	$then = 'show_diff("E-Mail$1",$diff)';
	$source = preg_replace($what, $then, $source);

	// Replace false use of ß in majuscules
	$what = '=([A-Z])ß([A-Z]|\W)=eS';
	$then = 'show_diff("$1SS$2",$diff)';
	$source = preg_replace($what, $then, $source);


	########## Rereplace HTML and smarty tags
	$i = 0;
	while (strpos($source, '[#SCRIPT]') !== false) $source = preg_replace('=\[#SCRIPT\]=', $hits['script'][0][$i++], $source, 1);
	$i = 0;
	while (strpos($source, '[#SMARTY]') !== false) $source = preg_replace('=\[#SMARTY\]=', $hits['smarty'][0][$i++], $source, 1);
	$i = 0;
	while (strpos($source, '[#HTML]') !== false) $source = preg_replace('=\[#HTML\]=', $hits['html'][0][$i++], $source, 1);
	$i = 0;
	while (strpos($source, '[#SMARTY_PHP]') !== false) $source = preg_replace('=\[#SMARTY_PHP\]=', $hits['smarty_php'][0][$i++], $source, 1);


	########## Stop time measurement
	if ($measuretime == 1)
		{
		$time['end'] = getmicrotime();
		$time = round($time['end'] - $time['start'],4);
		$source = "\n<!-- start typo-improving -->\n".$source."\n<!-- improved typo in $time sec -->\n";
		}

	########## Return
	return $source;
	}

?>
