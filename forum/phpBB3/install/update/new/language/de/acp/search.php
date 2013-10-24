<?php
/**
*
* acp_search [Deutsch — Du]
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
	'ACP_SEARCH_INDEX_EXPLAIN'				=> 'Hier kannst du die Indizes der Such-Backends verwalten. Da normalerweise nur ein Backend genutzt wird, solltest du alle Indizes löschen, die du nicht verwendest. Nach der Änderung bestimmter Such-Einstellungen (z.&nbsp;B. die minimal/maximal indizierten Zeichen) kann es sinnvoll sein, den Suchindex neu aufzubauen, damit er die Änderungen berücksichtigt.',
	'ACP_SEARCH_SETTINGS_EXPLAIN'			=> 'Hier kannst du festlegen, welches Backend zur Indizierung von und zur Suche nach Beiträgen verwendet wird. Du kannst verschiedene Optionen festlegen, die die Verarbeitungszeit dieser Vorgänge beeinflussen können. Manche Einstellungen sind für alle Backends identisch.',

	'COMMON_WORD_THRESHOLD'					=> 'Schwelle für häufig vorkommende Wörter',
	'COMMON_WORD_THRESHOLD_EXPLAIN'			=> 'Wörter, die in einem größeren prozentualem Anteil von Beiträgen enthalten sind, werden als häufig vorkommende Wörter angesehen. Häufig vorkommende Wörter werden in Suchanfragen ignoriert. Um dieses Verhalten abzuschalten, stell als Wert 0 ein. Die Funktion greift nur, wenn mehr als 100 Beiträge existieren. Wenn Wörter, die derzeit als häufig gekennzeichnet sind, wieder in den Suchindex aufgenommen werden sollen, muss der Index neu aufgebaut werden.',
	'CONFIRM_SEARCH_BACKEND'				=> 'Bist du dir sicher, dass du das Backend für die Suchen ändern möchtest? Nach dem Wechsel des Backends musst du einen neuen Index für das neue Backend aufbauen. Wenn du nicht planst, zum alten Backend zurückzukehren, kannst du auch den Index des alten Backends löschen, um Systemressourcen zu sparen.',
	'CONTINUE_DELETING_INDEX'				=> 'Letzte Index-Löschung fortsetzen',
	'CONTINUE_DELETING_INDEX_EXPLAIN'		=> 'Die Löschung eines Indexes wurde gestartet. Um auf die Such-Indizes-Seite zugreifen zu können, muss dieser Vorgang erst abgeschlossen oder abgebrochen werden.',
	'CONTINUE_INDEXING'						=> 'Letzte Indizierung fortsetzen',
	'CONTINUE_INDEXING_EXPLAIN'				=> 'Eine Indizierung wurde gestartet. Um auf die Such-Indizes-Seite zugreifen zu können, muss dieser Vorgang erst abgeschlossen oder abgebrochen werden.',
	'CREATE_INDEX'							=> 'Index erstellen',

	'DELETE_INDEX'							=> 'Index löschen',
	'DELETING_INDEX_IN_PROGRESS'			=> 'Index wird derzeit gelöscht',
	'DELETING_INDEX_IN_PROGRESS_EXPLAIN'	=> 'Das Backend löscht derzeit seinen Index. Dieser Vorgang kann einige Minuten beanspruchen.',

	'FULLTEXT_MYSQL_INCOMPATIBLE_VERSION'	=> 'Die MySQL-Volltextsuche kann nur mit MySQL 4 oder höher verwendet werden.',
	'FULLTEXT_MYSQL_NOT_SUPPORTED'			=> 'Die MySQL-Volltextsuche kann nur mit MyISAM- oder InnoDB-Tabellen genutzt werden. Für eine Volltextsuche mit InnoDB-Tabellen ist MySQL 5.6.4 oder höher erforderlich.',
	'FULLTEXT_MYSQL_TOTAL_POSTS'			=> 'Insgesamt indizierte Beiträge',
	'FULLTEXT_MYSQL_MBSTRING'				=> 'Unterstützung für nicht-lateinische UTF-8-Zeichen mit mbstring:',
	'FULLTEXT_MYSQL_PCRE'					=> 'Unterstützung für nicht-lateinische UTF-8-Zeichen mit PCRE:',
	'FULLTEXT_MYSQL_MBSTRING_EXPLAIN'		=> 'Wenn die PCRE-Unicode-Zeichenunterstützung nicht vorhanden ist, versucht das Backend, das reguläre Ausdrucks-System von mbstring zu verwenden.',
	'FULLTEXT_MYSQL_PCRE_EXPLAIN'			=> 'Dieses Such-Backend benötigt PCRE-Unicode-Zeichenunterstützung, um nach nicht-lateinischen Zeichen zu suchen. Diese Funktion ist nur in PHP 4.4, 5.1 oder höher verfügbar.',
	'FULLTEXT_MYSQL_MIN_SEARCH_CHARS_EXPLAIN'	=> 'Nur Wörter mit mindestens dieser Anzahl an Zeichen werden indiziert. Dieser Wert kann nur durch den Server-Betreiber in der MySQL-Konfiguration geändert werden.',
	'FULLTEXT_MYSQL_MAX_SEARCH_CHARS_EXPLAIN'	=> 'Nur Wörter mit maximal dieser Anzahl an Zeichen werden indiziert. Dieser Wert kann nur durch den Server-Betreiber in der MySQL-Konfiguration geändert werden.',

	'GENERAL_SEARCH_SETTINGS'				=> 'Allgemeine Such-Einstellungen',
	'GO_TO_SEARCH_INDEX'					=> 'Zur Übersicht der Such-Indizes gehen',

	'INDEX_STATS'							=> 'Index-Statistik',
	'INDEXING_IN_PROGRESS'					=> 'Indizierung erfolgt',
	'INDEXING_IN_PROGRESS_EXPLAIN'			=> 'Das Backend indiziert derzeit alle Beiträge des Boards. Dies kann abhängig von der Größe deines Boards zwischen wenigen Minuten und einigen Stunden dauern.',

	'LIMIT_SEARCH_LOAD'						=> 'Systemauslastungs-Limit für Suche',
	'LIMIT_SEARCH_LOAD_EXPLAIN'				=> 'Wenn die Systemauslastung der letzten Minute (load average) diesen Wert überschreitet, wird die Suchfunktion deaktiviert. 1.0 steht für eine ca. 100-prozentige Auslastung eines Prozessors. Diese Einstellung steht nur auf Systemen zur Verfügung, die auf UNIX basieren und bei denen dieser Wert zugänglich ist.',

	'MAX_SEARCH_CHARS'						=> 'Maximal indizierte Zeichen',
	'MAX_SEARCH_CHARS_EXPLAIN'				=> 'Wörter mit nicht mehr als so vielen Zeichen werden für die Suche indiziert.',
	'MAX_NUM_SEARCH_KEYWORDS'				=> 'Maximal zulässige Wörter',
	'MAX_NUM_SEARCH_KEYWORDS_EXPLAIN'		=> 'Maximale Zahl von Wörtern, nach denen ein Benutzer suchen kann. Ein Wert von 0 erlaubt eine Suche nach einer unbegrenzten Zahl an Wörtern.',
	'MIN_SEARCH_CHARS'						=> 'Minimal indizierte Zeichen',
	'MIN_SEARCH_CHARS_EXPLAIN'				=> 'Wörter mit mindestens so vielen Zeichen werden für die Suche indiziert.',
	'MIN_SEARCH_AUTHOR_CHARS'				=> 'Mindestlänge für Suche nach Benutzernamen',
	'MIN_SEARCH_AUTHOR_CHARS_EXPLAIN'		=> 'Benutzer müssen mindestens diese Zahl von Zeichen eingeben, wenn sie eine Mitgliedersuche mit Platzhaltern durchführen. Wenn der Benutzername des Autors kürzer als diese Zahl ist, kannst du immer noch nach den Beiträgen des Benutzers suchen, indem du nach dem vollständigen Namen suchst.',

	'PROGRESS_BAR'							=> 'Fortschritt',

	'SEARCH_GUEST_INTERVAL'					=> 'Wartezeit zwischen zwei Suchvorgängen von Gästen',
	'SEARCH_GUEST_INTERVAL_EXPLAIN'			=> 'Zeit in Sekunden, die Gäste zwischen Suchvorgängen warten müssen. Wenn ein Gast eine Suche durchgeführt hat, müssen alle Gäste so lange warten, bis sie die Suchfunktion wieder nutzen können.',
	'SEARCH_INDEX_CREATE_REDIRECT'			=> 'Alle Beiträge bis Beitrags-ID %1$d wurden in den Suchindex aufgenommen, davon %2$d in diesem Schritt.<br />Die aktuelle Indizierungsgeschwindigkeit beträgt ungefähr %3$.1f Beiträge pro Sekunde.<br />Indizierung in Arbeit…',
	'SEARCH_INDEX_DELETE_REDIRECT'			=> 'Alle Beiträge bis Beitrags-ID %1$d wurden aus dem Suchindex entfernt.<br />Löschen in Arbeit…',
	'SEARCH_INDEX_CREATED'					=> 'Alle Beiträge in der Datenbank wurden erfolgreich indiziert.',
	'SEARCH_INDEX_REMOVED'					=> 'Der Suchindex für das Backend wurde erfolgreich gelöscht.',
	'SEARCH_INTERVAL'						=> 'Wartezeit zwischen zwei Suchvorgängen von Benutzern',
	'SEARCH_INTERVAL_EXPLAIN'				=> 'Zeit in Sekunden, die ein Benutzer zwischen Suchvorgängen warten muss. Dieser Zeitabstand wird für jeden Benutzer individuell gemessen.',
	'SEARCH_STORE_RESULTS'					=> 'Cache-Zeit von Suchergebnissen',
	'SEARCH_STORE_RESULTS_EXPLAIN'			=> 'Zwischengespeicherte Suchergebnisse verfallen nach dieser Zeit (in Sekunden). Um die Zwischenspeicherung zu deaktivieren, stelle als Wert 0 ein.',
	'SEARCH_TYPE'							=> 'Verwendetes Backend',
	'SEARCH_TYPE_EXPLAIN'					=> 'phpBB erlaubt dir, das für die Beitragssuche zu verwendende Backend zu wählen. Standardmäßig wird die phpBB-eigene Volltextsuche verwendet.',
	'SWITCHED_SEARCH_BACKEND'				=> 'Du hast das Such-Backend gewechselt. Um das neue Backend zu benutzen, solltest du sicherstellen, dass für das neue Backend ein Index angelegt wurde.',

	'TOTAL_WORDS'							=> 'Insgesamt indizierte Wörter',
	'TOTAL_MATCHES'							=> 'Insgesamt indizierte Wort-Beitrags-Beziehungen',

	'YES_SEARCH'							=> 'Suchfunktion aktivieren',
	'YES_SEARCH_EXPLAIN'					=> 'Aktiviert die Suchfunktionen inklusive der Mitgliedersuche für die Benutzer.',
	'YES_SEARCH_UPDATE'						=> 'Aktualisierung des Suchindexes erlauben',
	'YES_SEARCH_UPDATE_EXPLAIN'				=> 'Erlaubt eine Aktualisierung des Indexes beim Erstellen/Ändern von Beiträgen. Wird überschrieben, wenn die Suchfunktion deaktiviert ist.',
));

?>