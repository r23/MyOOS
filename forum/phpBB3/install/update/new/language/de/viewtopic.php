<?php
/**
*
* viewtopic [Deutsch — Du]
*
* @package language
* @version $Id: viewtopic.php 617 2013-09-29 10:21:18Z pyramide $
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
	'ATTACHMENT'						=> 'Dateianhang',
	'ATTACHMENT_FUNCTIONALITY_DISABLED'	=> 'Die Funktion für Dateianhänge wurde deaktiviert.',

	'BOOKMARK_ADDED'		=> 'Lesezeichen für das Thema erfolgreich gesetzt.',
	'BOOKMARK_ERR'			=> 'Das Setzen eines Lesezeichens für das Thema ist gescheitert. Bitte versuche es erneut.',
	'BOOKMARK_REMOVED'		=> 'Lesezeichen für das Thema erfolgreich entfernt.',
	'BOOKMARK_TOPIC'		=> 'Lesezeichen setzen',
	'BOOKMARK_TOPIC_REMOVE'	=> 'Lesezeichen entfernen',
	'BUMPED_BY'				=> 'Zuletzt als neu markiert von %1$s am %2$s.',
	'BUMP_TOPIC'			=> 'Thema als neu markieren',

	'CODE'					=> 'Code',
	'COLLAPSE_QR'			=> 'Schnellantwort ausblenden',

	'DELETE_TOPIC'			=> 'Thema löschen',
	'DOWNLOAD_NOTICE'		=> 'Du hast keine ausreichende Berechtigung, um die Dateianhänge dieses Beitrags anzusehen.',

	'EDITED_TIMES_TOTAL'	=> 'Zuletzt geändert von %1$s am %2$s, insgesamt %3$d-mal geändert.',
	'EDITED_TIME_TOTAL'		=> 'Zuletzt geändert von %1$s am %2$s, insgesamt %3$d-mal geändert.',
	'EMAIL_TOPIC'			=> 'Thema weiterempfehlen',
	'ERROR_NO_ATTACHMENT'	=> 'Der ausgewählte Dateianhang existiert nicht mehr.',

	'FILE_NOT_FOUND_404'	=> 'Die Datei <strong>%s</strong> existiert nicht.',
	'FORK_TOPIC'			=> 'Thema duplizieren',
	'FULL_EDITOR'			=> 'Vollständiger Editor',

	'LINKAGE_FORBIDDEN'		=> 'Du bist nicht berechtigt, diese Seite anzusehen, von ihr herunterzuladen oder auf sie zu linken.',
	'LOGIN_NOTIFY_TOPIC'	=> 'Du wurdest über einen neuen Beitrag in diesem Thema informiert. Bitte melde dich an, um es anzusehen.',
	'LOGIN_VIEWTOPIC'		=> 'Du musst registriert und angemeldet sein, um dieses Thema anzusehen.',

	'MAKE_ANNOUNCE'				=> 'In Bekanntmachung ändern',
	'MAKE_GLOBAL'				=> 'In globale Bekanntmachung ändern',
	'MAKE_NORMAL'				=> 'In Standard-Thema ändern',
	'MAKE_STICKY'				=> 'In wichtiges Thema ändern',
	'MAX_OPTIONS_SELECT'		=> 'Du kannst bis zu <strong>%d</strong> Optionen auswählen',
	'MAX_OPTION_SELECT'			=> 'Du kannst <strong>eine</strong> Option auswählen',
	'MISSING_INLINE_ATTACHMENT'	=> 'Der Dateianhang <strong>%s</strong> existiert nicht mehr.',
	'MOVE_TOPIC'				=> 'Thema verschieben',

	'NO_ATTACHMENT_SELECTED'=> 'Du hast keinen Dateianhang zur Anzeige oder zum Herunterladen ausgewählt.',
	'NO_NEWER_TOPICS'		=> 'Es gibt keine neueren Themen in diesem Forum.',
	'NO_OLDER_TOPICS'		=> 'Es gibt keine älteren Themen in diesem Forum.',
	'NO_UNREAD_POSTS'		=> 'Es gibt keine neuen ungelesenen Beiträge in diesem Thema.',
	'NO_VOTE_OPTION'		=> 'Du musst eine Option auswählen, um abzustimmen.',
	'NO_VOTES'				=> 'Keine Stimmen',

	'POLL_ENDED_AT'			=> 'Umfrage endete am %s',
	'POLL_RUN_TILL'			=> 'Die Umfrage läuft bis %s.',
	'POLL_VOTED_OPTION'		=> 'Du hast für diese Option gestimmt',
	'PRINT_TOPIC'			=> 'Druckansicht',

	'QUICK_MOD'				=> 'Schnellmoderation',
	'QUICKREPLY'			=> 'Schnellantwort',
	'QUOTE'					=> 'Zitat',

	'REPLY_TO_TOPIC'		=> 'Auf das Thema antworten',
	'RETURN_POST'			=> '%sZurück zum Beitrag%s',

	'SHOW_QR'				=> 'Schnellantwort',
	'SUBMIT_VOTE'			=> 'Abstimmen',

	'TOTAL_VOTES'			=> 'Abstimmungen insgesamt',

	'UNLOCK_TOPIC'			=> 'Thema entsperren',

	'VIEW_INFO'				=> 'Beitrags-Details',
	'VIEW_NEXT_TOPIC'		=> 'Nächstes Thema',
	'VIEW_PREVIOUS_TOPIC'	=> 'Vorheriges Thema',
	'VIEW_RESULTS'			=> 'Ergebnis anzeigen',
	'VIEW_TOPIC_POST'		=> '1 Beitrag',
	'VIEW_TOPIC_POSTS'		=> '%d Beiträge',
	'VIEW_UNREAD_POST'		=> 'Erster ungelesener Beitrag',
	'VISIT_WEBSITE'			=> 'Website besuchen',
	'VOTE_SUBMITTED'		=> 'Deine Abstimmung wurde erfasst.',
	'VOTE_CONVERTED'		=> 'Das Ändern der Abstimmung ist bei Umfragen nicht möglich, die konvertiert wurden.',
));

?>