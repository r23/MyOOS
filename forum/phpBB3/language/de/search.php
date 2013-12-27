<?php
/**
*
* search [Deutsch — Du]
*
* @package language
* @version $Id: search.php 617 2013-09-29 10:21:18Z pyramide $
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
	'ALL_AVAILABLE'			=> 'Alle verfügbaren',
	'ALL_RESULTS'			=> 'Alle Ergebnisse',

	'DISPLAY_RESULTS'		=> 'Ergebnisse anzeigen als',

	'FOUND_SEARCH_MATCH'		=> 'Die Suche ergab %d Treffer',
	'FOUND_SEARCH_MATCHES'		=> 'Die Suche ergab %d Treffer',
	'FOUND_MORE_SEARCH_MATCHES'	=> 'Die Suche ergab mehr als %d Treffer',

	'GLOBAL'				=> 'Globale Bekanntmachung',

	'IGNORED_TERMS'			=> 'ignoriert',
	'IGNORED_TERMS_EXPLAIN'	=> 'Die folgenden Wörter deiner Suchanfrage wurden ignoriert, da sie zu häufig vorkommen: <strong>%s</strong>.',

	'JUMP_TO_POST'			=> 'Rufe den Beitrag auf',

	'LOGIN_EXPLAIN_EGOSEARCH'	=> 'Um deine eigenen Beiträge anzusehen, musst du auf diesem Board registriert und angemeldet sein.',
	'LOGIN_EXPLAIN_UNREADSEARCH'=> 'Um deine ungelesenen Beiträge anzusehen, musst du auf diesem Board registriert und angemeldet sein.',
	'LOGIN_EXPLAIN_NEWPOSTS'	=> 'Um die Beiträge seit deinem letzten Besuch anzusehen, musst du auf diesem Board registriert und angemeldet sein.',

	'MAX_NUM_SEARCH_KEYWORDS_REFINE'	=> 'Deine Suchanfrage enthält zu viele Wörter. Bitte gib nicht mehr als %1$d Wörter an.',

	'NO_KEYWORDS'			=> 'Du musst mindestens ein Wort angeben, nach dem gesucht werden soll. Jedes Wort muss aus mindestens %d Buchstaben bestehen und darf ohne Platzhalter nicht mehr als %d Buchstaben haben.',
	'NO_RECENT_SEARCHES'	=> 'In der letzten Zeit wurden keine Suchanfragen durchgeführt.',
	'NO_SEARCH'				=> 'Du bist leider nicht berechtigt, die Suche zu verwenden.',
	'NO_SEARCH_RESULTS'		=> 'Es wurden keine passenden Ergebnisse gefunden.',
	'NO_SEARCH_TIME'		=> 'Die Suche steht dir derzeit leider nicht zur Verfügung. Bitte versuche es in ein paar Minuten erneut.',
	'NO_SEARCH_UNREADS'		=> 'Die Suche nach ungelesenen Beiträgen wurde auf diesem Board deaktiviert.',
	'WORD_IN_NO_POST'		=> 'Es wurden keine Beiträge gefunden, weil das Wort <strong>%s</strong> in keinem Beitrag enthalten ist.',
	'WORDS_IN_NO_POST'		=> 'Es wurden keine Beiträge gefunden, weil die Wörter <strong>%s</strong> in keinem Beitrag enthalten sind.',

	'POST_CHARACTERS'		=> 'Zeichen der Beiträge anzeigen',

	'RECENT_SEARCHES'		=> 'Die letzten Suchanfragen',
	'RESULT_DAYS'			=> 'Suchzeitraum begrenzen',
	'RESULT_SORT'			=> 'Ergebnisse sortieren nach',
	'RETURN_FIRST'			=> 'Die ersten',
	'RETURN_TO_SEARCH_ADV'	=> 'Zurück zur erweiterten Suche',

	'SEARCHED_FOR'				=> 'Benutzte Suchanfrage',
	'SEARCHED_TOPIC'			=> 'Durchsuchtes Thema',
	'SEARCHED_QUERY'			=> 'Suchanfrage',
	'SEARCH_ALL_TERMS'			=> 'Nach allen Begriffen suchen oder Suche wie angegeben verwenden',
	'SEARCH_ANY_TERMS'			=> 'Nach einem Begriff suchen',
	'SEARCH_AUTHOR'				=> 'Zu suchender Autor',
	'SEARCH_AUTHOR_EXPLAIN'		=> 'Benutze ein * als Platzhalter für teilweise Übereinstimmungen.',
	'SEARCH_FIRST_POST'			=> 'Nur im ersten Beitrag der Themen',
	'SEARCH_FORUMS'				=> 'Zu durchsuchende Foren',
	'SEARCH_FORUMS_EXPLAIN'		=> 'Wähle das Forum oder die Foren aus, in denen gesucht werden soll. Unterforen werden automatisch mit durchsucht, sofern du die Option „Unterforen durchsuchen“ unten nicht deaktivierst.',
	'SEARCH_IN_RESULTS'			=> 'Diese Ergebnisse durchsuchen',
	'SEARCH_KEYWORDS_EXPLAIN'	=> 'Setze ein <strong>+</strong> vor ein Wort, das gefunden werden muss und ein <strong>-</strong> vor ein Wort, das nicht gefunden werden darf. Verwende mehrere Wörter getrennt durch <strong>|</strong> innerhalb einer Klammer, wenn nur eines der Wörter gefunden werden muss. Benutze ein * als Platzhalter für teilweise Übereinstimmungen.',
	'SEARCH_MSG_ONLY'			=> 'Nur im Text der Beiträge',
	'SEARCH_OPTIONS'			=> 'Suchoptionen',
	'SEARCH_QUERY'				=> 'Suchanfrage',
	'SEARCH_SUBFORUMS'			=> 'Unterforen durchsuchen',
	'SEARCH_TITLE_MSG'			=> 'Betreff und Text der Beiträge',
	'SEARCH_TITLE_ONLY'			=> 'Nur im Betreff der Themen',
	'SEARCH_WITHIN'				=> 'Innerhalb suchen',
	'SORT_ASCENDING'			=> 'Aufsteigend',
	'SORT_AUTHOR'				=> 'Autor',
	'SORT_DESCENDING'			=> 'Absteigend',
	'SORT_FORUM'				=> 'Forum',
	'SORT_POST_SUBJECT'			=> 'Betreff des Beitrags',
	'SORT_TIME'					=> 'Erstellungsdatum des Beitrags',

	'TOO_FEW_AUTHOR_CHARS'	=> 'Du musst mindestens %d Zeichen des Benutzernamens angeben.',
));

?>