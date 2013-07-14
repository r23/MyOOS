<?php
/**
*
* acp_prune [Deutsch — Du]
*
* @package language
* @version $Id: prune.php 433 2010-02-24 13:15:18Z philippk $
* @copyright (c) 2005 phpBB Group; 2006 phpBB.de
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Deutsche Übersetzung durch die Übersetzer-Gruppe von phpBB.de:
* siehe docs/AUTHORS und http://www.phpbb.de/go/ubersetzerteam
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

// User pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_USERS_EXPLAIN'	=> 'Dieser Bereich erlaubt es dir, Benutzer deines Boards zu löschen oder zu deaktivieren. Benutzerkonten können anhand verschiedener Kriterien selektriert werden: der Beitragszahl, der letzten Aktivität usw. Die Kriterien können kombiniert werden, so dass du die betroffenen Benutzer weiter einschränken kannst. So kannst du z.&nbsp;B. Benutzer löschen, die weniger als 10 Beiträge haben und deren letzte Aktivität vor dem 2002-01-01 war. Alternativ kannst du die Kriterien auch überspringen, indem du eine Liste von Benutzernamen direkt in das Textfeld eingibst (jeden Benutzer in eine Zeile). Gehe mit diesem Tool vorsichtig um! Wenn ein Benutzer gelöscht ist, gibt es keine Funktion, um diesen Vorgang rückgängig zu machen.',

	'DEACTIVATE_DELETE'			=> 'Deaktivieren oder löschen',
	'DEACTIVATE_DELETE_EXPLAIN'	=> 'Wähle aus, ob der Benutzer deaktiviert oder gelöscht werden soll. Ein deaktivierter Benutzer kann in der Benutzerverwaltung wieder aktiviert werden, eine Löschung hingegen ist endgültig.',
	'DELETE_USERS'				=> 'Löschen',
	'DELETE_USER_POSTS'			=> 'Lösche Beiträge der gelöschten Benutzer',
	'DELETE_USER_POSTS_EXPLAIN' => 'Entfernt zusätzlich die Beiträge der gelöschten Benutzer. Hat keine Auswirkung, wenn die Benutzer deaktiviert werden.',

	'JOINED_EXPLAIN'			=> 'Gib ein Datum im Format <kbd>JJJJ-MM-TT</kbd> an.',

	'LAST_ACTIVE_EXPLAIN'		=> 'Gib ein Datum im Format <kbd>JJJJ-MM-TT</kbd> an. Verwende <kbd>0000-00-00</kbd>, um die Benutzer zu löschen, die sich nie angemeldet haben; die <em>Vor dem</em>- und <em>Nach dem</em>-Bedingungen werden dann ignoriert.',

	'PRUNE_USERS_LIST'				=> 'Zu löschende Benutzer',
	'PRUNE_USERS_LIST_DELETE'		=> 'Mit den angegebenen Kriterien werden folgende Benutzer gelöscht:',
	'PRUNE_USERS_LIST_DEACTIVATE'	=> 'Mit den angegebenen Kriterien werden folgende Benutzer deaktiviert:',

	'SELECT_USERS_EXPLAIN'		=> 'Gib hier spezifische Benutzernamen ein. Die oben angegebenen Kriterien werden dann ignoriert. Gründer können nicht gelöscht werden.',

	'USER_DEACTIVATE_SUCCESS'	=> 'Die ausgewählten Benutzer wurden erfolgreich deaktiviert.',
	'USER_DELETE_SUCCESS'		=> 'Die ausgewählten Benutzer wurden erfolgreich gelöscht.',
	'USER_PRUNE_FAILURE'		=> 'Keine Benutzer entsprachen den ausgewählten Kriterien.',

	'WRONG_ACTIVE_JOINED_DATE'	=> 'Das eingegebene Datum ist falsch, es wird in der Forum <kbd>JJJJ-MM-TT</kbd> erwartet.',
));

// Forum Pruning
$lang = array_merge($lang, array(
	'ACP_PRUNE_FORUMS_EXPLAIN'	=> 'Diese Funktion löscht alle Themen, die in der von dir festgelegten Zeit weder beantwortet noch gelesen wurden. Gibst du keinen Zeitraum an, so werden alle Beiträge gelöscht. Themen mit Umfragen und Bekanntmachungen werden nur gelöscht, sofern du die entsprechenden Optionen aktiviert hast.',

	'FORUM_PRUNE'		=> 'Löschen inaktiver Themen',

	'NO_PRUNE'			=> 'Es wurden in keinem Forum Themen gelöscht.',

	'SELECTED_FORUM'	=> 'Forum auswählen',
	'SELECTED_FORUMS'	=> 'Foren auswählen',

	'POSTS_PRUNED'					=> 'Beiträge gelöscht',
	'PRUNE_ANNOUNCEMENTS'			=> 'Bekanntmachungen löschen',
	'PRUNE_FINISHED_POLLS'			=> 'Abgeschlossene Umfragen löschen',
	'PRUNE_FINISHED_POLLS_EXPLAIN'	=> 'Löscht Themen mit Umfragen, die beendet sind.',
	'PRUNE_FORUM_CONFIRM'			=> 'Bist du dir sicher, dass du die ausgewählten Foren mit den angegebenen Einstellungen löschen willst? Es gibt keine Möglichkeit, einmal gelöschte Beiträge und Themen wiederherzustellen.',
	'PRUNE_NOT_POSTED'				=> 'Seit dem letzten Beitrag vergangene Tage',
	'PRUNE_NOT_VIEWED'				=> 'Seit dem letzten Zugriff vergangene Tage',
	'PRUNE_OLD_POLLS'				=> 'Lösche alte Umfragen',
	'PRUNE_OLD_POLLS_EXPLAIN'		=> 'Löscht Themen mit Umfragen, in denen in dem für neue Beiträge geltenden Zeitraum keine Abstimmung erfolgte.',
	'PRUNE_STICKY'					=> 'Wichtige Themen löschen',
	'PRUNE_SUCCESS'					=> 'Das Löschen inaktiver Themen war erfolgreich.',

	'TOPICS_PRUNED'		=> 'Themen gelöscht',
));

?>