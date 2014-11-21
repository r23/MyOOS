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
* siehe language/de_x_sie/AUTHORS.md und https://www.phpbb.de/go/ubersetzerteam
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
	'ACP_MODULE_MANAGEMENT_EXPLAIN'	=> 'Hier können Sie alle Module verwalten. Bitte beachten Sie, dass der Administrations-Bereich eine Menüstruktur mit drei Ebenen (Kategorie -> Kategorie -> Modul) hat, während die anderen eine mit zwei Ebenen (Kategorie -> Modul) haben, die beibehalten werden muss. Bitte beachten Sie, dass Sie sich selbst aussperren können, wenn Sie die für die Modulverwaltung selbst zuständigen Module deaktivieren oder ausblenden.',
	'ADD_MODULE'					=> 'Modul hinzufügen',
	'ADD_MODULE_CONFIRM'			=> 'Sind Sie sich sicher, dass Sie das gewählte Modul in diesem Modus hinzufügen möchten?',
	'ADD_MODULE_TITLE'				=> 'Modul hinzufügen',

	'CANNOT_REMOVE_MODULE'	=> 'Das Modul konnte nicht entfernt werden, weil es ihm zugehörige Untermodule hat. Bitte entfernen oder verschieben Sie alle Untermodule, bevor Sie diese Aktion durchführen.',
	'CATEGORY'				=> 'Kategorie',
	'CHOOSE_MODE'			=> 'Methode des Moduls',
	'CHOOSE_MODE_EXPLAIN'	=> 'Wählen Sie die Methode, die das Modul nutzen soll.',
	'CHOOSE_MODULE'			=> 'Modul-Datei',
	'CHOOSE_MODULE_EXPLAIN'	=> 'Wählen Sie die Datei aus, die durch das Modul aufgerufen wird.',
	'CREATE_MODULE'			=> 'Neues Modul erstellen',

	'DEACTIVATED_MODULE'	=> 'Deaktiviertes Modul',
	'DELETE_MODULE'			=> 'Modul löschen',
	'DELETE_MODULE_CONFIRM'	=> 'Sind Sie sich sicher, dass dieses Modul entfernt werden soll?',

	'EDIT_MODULE'			=> 'Modul bearbeiten',
	'EDIT_MODULE_EXPLAIN'	=> 'Hier können Sie modulspezifische Einstellungen vornehmen.',

	'HIDDEN_MODULE'			=> 'Verstecktes Modul',

	'MODULE'					=> 'Modul',
	'MODULE_ADDED'				=> 'Das Modul wurde erfolgreich hinzugefügt.',
	'MODULE_DELETED'			=> 'Das Modul wurde erfolgreich entfernt.',
	'MODULE_DISPLAYED'			=> 'Modul anzeigen',
	'MODULE_DISPLAYED_EXPLAIN'	=> 'Wenn Sie dieses Modul nicht anzeigen lassen, es aber nutzen möchten, stellen Sie dies auf „Nein“.',
	'MODULE_EDITED'				=> 'Das Modul wurde erfolgreich bearbeitet.',
	'MODULE_ENABLED'			=> 'Modul aktiviert',
	'MODULE_LANGNAME'			=> 'Name des Moduls',
	'MODULE_LANGNAME_EXPLAIN'	=> 'Name des anzuzeigenden Moduls. Sie können eine Sprachvariable nutzen, wenn der Name in einer Sprachdatei definiert ist.',
	'MODULE_TYPE'				=> 'Modul-Typ',

	'NO_CATEGORY_TO_MODULE'	=> 'Die Kategorie konnte nicht in ein Modul umgewandelt werden. Bitte entfernen/verschieben Sie alle Untermodule, bevor Sie diese Aktion durchführen.',
	'NO_MODULE'				=> 'Kein Modul gefunden.',
	'NO_MODULE_ID'			=> 'Keine Modul-ID angegeben.',
	'NO_MODULE_LANGNAME'	=> 'Kein Modul-Name angegeben.',
	'NO_PARENT'				=> 'Kein übergeordnetes Modul.',

	'PARENT'				=> 'Übergeordnet',
	'PARENT_NO_EXIST'		=> 'Übergeordnetes Modul existiert nicht.',

	'SELECT_MODULE'			=> 'Wählen Sie ein Modul',
));
