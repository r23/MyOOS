<?php
/**
*
* acp_language [Deutsch — Du]
*
* @package language
* @version $Id: language.php 617 2013-09-29 10:21:18Z pyramide $
* @copyright (c) 2005 phpBB Group; 2006 phpBB.de
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Deutsche Übersetzung durch die Übersetzer-Gruppe von phpBB.de:
* siehe docs/AUTHORS und https://www.phpbb.de/go/ubersetzerteam
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_FILES'						=> 'Sprachdateien Administrations-Bereich',
	'ACP_LANGUAGE_PACKS_EXPLAIN'	=> 'Hier kannst du Sprachpakete installieren und entfernen. Das Standard-Sprachpaket ist mit einem Stern (*) gekennzeichnet.',

	'EMAIL_FILES'			=> 'E-Mail-Templates',

	'FILE_CONTENTS'				=> 'Datei-Inhalte',
	'FILE_FROM_STORAGE'			=> 'Datei aus Speicher-Ordner',

	'HELP_FILES'				=> 'Hilfe-Dateien',

	'INSTALLED_LANGUAGE_PACKS'	=> 'Installierte Sprachpakete',
	'INVALID_LANGUAGE_PACK'		=> 'Das gewählte Sprachpaket scheint ungültig zu sein. Bitte prüfe das Paket und lade es, wenn nötig, erneut hoch.',
	'INVALID_UPLOAD_METHOD'		=> 'Die gewählte Methode zum Hochladen ist ungültig, bitte wähle eine andere.',

	'LANGUAGE_DETAILS_UPDATED'			=> 'Sprachdetails erfolgreich aktualisiert.',
	'LANGUAGE_ENTRIES'					=> 'Sprachpaket-Einträge',
	'LANGUAGE_ENTRIES_EXPLAIN'			=> 'Hier kannst du bestehende oder noch nicht übersetzte Einträge der Sprachpakete ändern.<br /><strong>Beachte:</strong> Sobald du eine Sprachdatei geändert hast, werden die Änderungen in einem separaten Ordner abgelegt, von dem aus du sie herunterladen kannst. Deine Benutzer werden die Änderungen solange nicht sehen können, bis du die Originaldateien ersetzt hast (indem du sie mit den neuen Dateien überschreibst).',
	'LANGUAGE_FILES'					=> 'Sprachdateien',
	'LANGUAGE_KEY'						=> 'Sprachvariable',
	'LANGUAGE_PACK_ALREADY_INSTALLED'	=> 'Dieses Sprachpaket ist bereits installiert.',
	'LANGUAGE_PACK_DELETED'				=> 'Das Sprachpaket <strong>%s</strong> wurde erfolgreich entfernt. Alle Benutzer, die dieses Paket genutzt haben, wurden auf die Standard-Sprache des Boards umgestellt.',
	'LANGUAGE_PACK_DETAILS'				=> 'Sprachpaket-Details',
	'LANGUAGE_PACK_INSTALLED'			=> 'Das Sprachpaket <strong>%s</strong> wurde erfolgreich installiert.',
	'LANGUAGE_PACK_CPF_UPDATE'			=> 'Die Sprachvariablen der benutzerdefinierten Profilfelder wurden von der Standard-Sprache übernommen. Bitte passe sie gegebenenfalls an.',
	'LANGUAGE_PACK_ISO'					=> 'ISO',
	'LANGUAGE_PACK_LOCALNAME'			=> 'Lokaler Name',
	'LANGUAGE_PACK_NAME'				=> 'Name',
	'LANGUAGE_PACK_NOT_EXIST'			=> 'Das gewählte Sprachpaket existiert nicht.',
	'LANGUAGE_PACK_USED_BY'				=> 'Verwendet von (inklusive Spiders/Robots)',
	'LANGUAGE_VARIABLE'					=> 'Wert der Variablen',
	'LANG_AUTHOR'						=> 'Autor des Sprachpakets',
	'LANG_ENGLISH_NAME'					=> 'Englischer Name',
	'LANG_ISO_CODE'						=> 'ISO-Code',
	'LANG_LOCAL_NAME'					=> 'Lokaler Name',

	'MISSING_LANGUAGE_FILE'		=> 'Fehlende Sprachdatei: <strong style="color:red">%s</strong>',
	'MISSING_LANG_VARIABLES'	=> 'Fehlende Sprachvariablen',
	'MODS_FILES'				=> 'MOD-Sprachdateien',

	'NO_FILE_SELECTED'				=> 'Du hast keine Sprachdatei angegeben.',
	'NO_LANG_ID'					=> 'Du hast kein Sprachpaket angegeben.',
	'NO_REMOVE_DEFAULT_LANG'		=> 'Du kannst das Standard-Sprachpaket nicht entfernen.<br />Wenn du dieses Sprachpaket entfernen möchtest, musst du zuerst die Standard-Sprache das Boards ändern.',
	'NO_UNINSTALLED_LANGUAGE_PACKS'	=> 'Keine deinstallierten Sprachpakete',

	'REMOVE_FROM_STORAGE_FOLDER'		=> 'Aus dem Speicher-Ordner entfernen',

	'SELECT_DOWNLOAD_FORMAT'	=> 'Download-Format auswählen',
	'SUBMIT_AND_DOWNLOAD'		=> 'Absenden und Datei herunterladen',
	'SUBMIT_AND_UPLOAD'			=> 'Absenden und Datei hochladen',

	'THOSE_MISSING_LANG_FILES'			=> 'Die folgenden Sprachdateien fehlen im %s-Sprachpaket',
	'THOSE_MISSING_LANG_VARIABLES'		=> 'Die folgenden Sprachvariablen fehlen im <strong>%s</strong>-Sprachpaket',

	'UNINSTALLED_LANGUAGE_PACKS'	=> 'Deinstallierte Sprachpakete',

	'UNABLE_TO_WRITE_FILE'		=> 'Die Datei konnte nicht nach %s geschrieben werden.',
	'UPLOAD_COMPLETED'			=> 'Das Hochladen wurde abgeschlossen.',
	'UPLOAD_FAILED'				=> 'Das Hochladen ist aus einem nicht bekanntem Grund gescheitert. Du musst eventuell die betroffene Datei manuell ersetzen.',
	'UPLOAD_METHOD'				=> 'Methode zum Hochladen',
	'UPLOAD_SETTINGS'			=> 'Einstellungen zum Hochladen',

	'WRONG_LANGUAGE_FILE'		=> 'Die gewählte Sprachdatei ist ungültig.',
));

?>