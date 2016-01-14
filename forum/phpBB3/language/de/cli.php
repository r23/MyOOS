<?php
/**
*
* This file is part of the phpBB Forum Software package.
*
* @copyright (c) phpBB Limited <https://www.phpbb.com>
* @license GNU General Public License, version 2 (GPL-2.0)
*
* For full copyright and license information, please see
* the docs/CREDITS.txt file.
*
* Deutsche Übersetzung durch die Übersetzer-Gruppe von phpBB.de:
* siehe language/de/AUTHORS.md und https://www.phpbb.de/go/ubersetzerteam
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}

/**
* DO NOT CHANGE
*/
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'CLI_CONFIG_CANNOT_CACHED'			=> 'Verwende diese Option, wenn sich die Konfigurations-Einstellung zu oft ändert, um effizient gecached zu werden.',
	'CLI_CONFIG_CURRENT'				=> 'Aktuell eingestellter Wert. Nutze 0 und 1, um einen boolschen Wert festzulegen',
	'CLI_CONFIG_DELETE_SUCCESS'			=> 'Konfigurations-Einstellung %s erfolgreich gelöscht.',
	'CLI_CONFIG_NEW'					=> 'Neuer Konfigurations-Wert. Nutze 0 und 1, um einen boolschen Wert festzulegen',
	'CLI_CONFIG_NOT_EXISTS'				=> 'Konfigurations-Einstellung %s existiert nicht',
	'CLI_CONFIG_OPTION_NAME'			=> 'Der Name der Konfigurations-Einstellung',
	'CLI_CONFIG_PRINT_WITHOUT_NEWLINE'	=> 'Verwende diese Option, wenn der Wert ohne Zeilenwechsel am Ende ausgegeben werden soll.',
	'CLI_CONFIG_INCREMENT_BY'			=> 'Wert, um den erhöht werden soll',
	'CLI_CONFIG_INCREMENT_SUCCESS'		=> 'Konfigurations-Einstellung %s erfolgreich erhöht',
	'CLI_CONFIG_SET_FAILURE'			=> 'Konnte Konfiguration %s nicht einstellen',
	'CLI_CONFIG_SET_SUCCESS'			=> 'Konfiguration %s erfolgreich eingestellt',

	'CLI_DESCRIPTION_CRON_LIST'					=> 'Gibt eine Liste aller bereiten und aller nicht bereiten Cron-Jobs aus.',
	'CLI_DESCRIPTION_CRON_RUN'					=> 'Führt alle bereiten Cron-Jobs aus.',
	'CLI_DESCRIPTION_CRON_RUN_ARGUMENT_1'		=> 'Name des Jobs, der ausgeführt werden soll',
	'CLI_DESCRIPTION_DB_MIGRATE'				=> 'Aktualisiert die Datenbank über eine Migration.',
	'CLI_DESCRIPTION_DELETE_CONFIG'				=> 'Löscht eine Konfigurations-Einstellung',
	'CLI_DESCRIPTION_DISABLE_EXTENSION'			=> 'Deaktiviert die angegebene Erweiterung.',
	'CLI_DESCRIPTION_ENABLE_EXTENSION'			=> 'Aktiviert die angegebene Erweiterung.',
	'CLI_DESCRIPTION_FIND_MIGRATIONS'			=> 'Findet Migrationen, die von keinen anderen Migrationen vorausgesetzt werden.',
	'CLI_DESCRIPTION_GET_CONFIG'				=> 'Gibt den Wert einer Konfigurations-Einstellung aus',
	'CLI_DESCRIPTION_INCREMENT_CONFIG'			=> 'Erhöht den ganzzahligen Wert einer Konfigurations-Einstellung',
	'CLI_DESCRIPTION_LIST_EXTENSIONS'			=> 'Gibt alle in der Datenbank und im Dateisystem vorhandenen Erweiterungen aus.',
	'CLI_DESCRIPTION_OPTION_SAFE_MODE'			=> 'Im abgesicherten Modus ausführen (ohne Erweiterungen).',
	'CLI_DESCRIPTION_OPTION_SHELL'				=> 'Komandozeile starten.',
	'CLI_DESCRIPTION_PURGE_EXTENSION'			=> 'Löscht die angegebene Erweiterung.',
	'CLI_DESCRIPTION_RECALCULATE_EMAIL_HASH'	=> 'Berechnet die Einträge im Feld user_email_hash der Benutzer-Tabelle neu.',
	'CLI_DESCRIPTION_SET_ATOMIC_CONFIG'			=> 'Legt den Wert für eine Konfigurations-Einstellung fest, wenn die derzeitige Einstellung dem angegebenen Vergleichswert entspricht',
	'CLI_DESCRIPTION_SET_CONFIG'				=> 'Legt den Wert für eine Konfigurations-Einstellung fest',

	'CLI_EXTENSION_DISABLE_FAILURE'		=> 'Konnte Erweiterung %s nicht deaktivieren',
	'CLI_EXTENSION_DISABLE_SUCCESS'		=> 'Erweiterung %s erfolgreich deaktiviert',
	'CLI_EXTENSION_ENABLE_FAILURE'		=> 'Konnte Erweiterung %s nicht aktivieren',
	'CLI_EXTENSION_ENABLE_SUCCESS'		=> 'Erweiterung %s erfolgreich aktiviert',
	'CLI_EXTENSION_NAME'				=> 'Name der Erweiterung',
	'CLI_EXTENSION_PURGE_FAILURE'		=> 'Konnte Arbeitsdaten der Erweiterung %s nicht löschen',
	'CLI_EXTENSION_PURGE_SUCCESS'		=> 'Arbeitsdaten der Erweiterung %s erfolgreich gelöscht',
	'CLI_EXTENSION_NOT_FOUND'			=> 'Es wurden keine Erweiterungen gefunden.',
	'CLI_EXTENSIONS_AVAILABLE'			=> 'Verfügbar',
	'CLI_EXTENSIONS_DISABLED'			=> 'Deaktiviert',
	'CLI_EXTENSIONS_ENABLED'			=> 'Aktiviert',

	'CLI_FIXUP_RECALCULATE_EMAIL_HASH_SUCCESS'	=> 'Alle E-Mail-Hashes wurden erfolgreich neu ermittelt.',
));

// Additional help for commands.
$lang = array_merge($lang, array(
	'CLI_HELP_CRON_RUN'			=> $lang['CLI_DESCRIPTION_CRON_RUN'] . ' Optional kannst du den Namen eines Cron-Jobs angeben, um nur diesen auszuführen.',
));
