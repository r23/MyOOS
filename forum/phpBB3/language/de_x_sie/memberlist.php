<?php
/**
*
* memberlist [Deutsch — Sie]
*
* @package language
* @version $Id: memberlist.php 617 2013-09-29 10:21:18Z pyramide $
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
	'ABOUT_USER'			=> 'Profil',
	'ACTIVE_IN_FORUM'		=> 'Am meisten aktiv in Forum',
	'ACTIVE_IN_TOPIC'		=> 'Am meisten aktiv in Thema',
	'ADD_FOE'				=> 'Zu den ignorierten Mitgliedern hinzufügen',
	'ADD_FRIEND'			=> 'Zu den Freunden hinzufügen',
	'AFTER'					=> 'Nach dem',

	'ALL'					=> 'Alle',

	'BEFORE'				=> 'Vor dem',

	'CC_EMAIL'				=> 'Eine Kopie dieser E-Mail an mich senden.',
	'CONTACT_USER'			=> 'Kontaktdaten',

	'DEST_LANG'				=> 'Sprache',
	'DEST_LANG_EXPLAIN'		=> 'Wählen Sie — sofern verfügbar — eine passende Sprache aus, in der der Empfänger die Nachricht erhalten soll.',

	'EMAIL_BODY_EXPLAIN'	=> 'Diese Nachricht wird als reiner Text verschickt, verwenden Sie daher kein HTML oder BBCode. Als Antwort-Adresse für die E-Mail wird Ihre E-Mail-Adresse angegeben.',
	'EMAIL_DISABLED'		=> 'Leider wurden alle E-Mail-Funktionen deaktiviert.',
	'EMAIL_SENT'			=> 'Die E-Mail wurde gesendet.',
	'EMAIL_TOPIC_EXPLAIN'	=> 'Diese Nachricht wird als reiner Text verschickt, verwenden Sie daher kein HTML oder BBCode. Hinweise zu dem Thema werden der Nachricht automatisch hinzugefügt. Als Antwort-Adresse für die E-Mail wird Ihre E-Mail-Adresse angegeben.',
	'EMPTY_ADDRESS_EMAIL'	=> 'Sie müssen eine gültige E-Mail-Adresse für den Empfänger angeben.',
	'EMPTY_MESSAGE_EMAIL'	=> 'Sie müssen eine Nachricht angeben, die versendet werden soll.',
	'EMPTY_MESSAGE_IM'		=> 'Sie müssen eine Nachricht angeben, die versendet werden soll.',
	'EMPTY_NAME_EMAIL'		=> 'Sie müssen den Namen des Empfängers angeben.',
	'EMPTY_SUBJECT_EMAIL'	=> 'Sie müssen einen Betreff für die E-Mail angeben.',
	'EQUAL_TO'				=> 'Entspricht',

	'FIND_USERNAME_EXPLAIN'	=> 'Benutzen Sie dieses Formular, um nach Mitgliedern zu suchen. Es müssen nicht alle Felder ausgefüllt werden. Verwenden Sie ein * als Platzhalter für teilweise Übereinstimmungen. Verwenden Sie das Format <kbd>JJJJ-MM-TT</kbd> (z.&nbsp;B. <samp>2002-01-01</samp>), um Datumswerte anzugeben. Benutzen Sie die Kontrollkästchen, um mehrere Benutzer auszuwählen (mehrere Benutzer werden abhängig vom Formular akzeptiert) und wählen Sie dann „Markierte auswählen“.',
	'FLOOD_EMAIL_LIMIT'		=> 'Sie können derzeit keine weitere E-Mail versenden. Bitte versuchen Sie es später erneut.',

	'GROUP_LEADER'			=> 'Gruppenleiter',

	'HIDE_MEMBER_SEARCH'	=> 'Das Suchformular ausblenden',

	'IM_ADD_CONTACT'		=> 'Kontakt hinzufügen',
	'IM_AIM'				=> 'Bitte beachten Sie, dass Sie AOL Instant Messenger installiert haben müssen, um diese Funktion zu nutzen.',
	'IM_AIM_EXPRESS'		=> 'AIM Express',
	'IM_DOWNLOAD_APP'		=> 'Anwendung herunterladen',
	'IM_ICQ'				=> 'Bitte beachten Sie, dass die Benutzer den Empfang unverlangter Nachrichten deaktiviert haben können.',
	'IM_JABBER'				=> 'Bitte beachten Sie, dass die Benutzer den Empfang unverlangter Nachrichten deaktiviert haben können.',
	'IM_JABBER_SUBJECT'		=> 'Dies ist eine automatische Nachricht, bitte beantworten Sie sie nicht. Nachricht von Benutzer %1$s auf %2$s.',
	'IM_MESSAGE'			=> 'Ihre Nachricht',
	'IM_MSNM'				=> 'Bitte beachten Sie, dass Sie Windows Live Messenger installiert haben müssen, um diese Funktion zu nutzen.',
	'IM_MSNM_BROWSER'		=> 'Ihr Browser unterstützt diese Funktion nicht.',
	'IM_MSNM_CONNECT'		=> 'Es besteht keine Verbindung zu Windows Live Messenger.\nUm fortzufahren, muss eine Verbindung zu Windows Live Messenger bestehen.',
	'IM_NAME'				=> 'Ihr Name',
	'IM_NO_DATA'			=> 'Es gibt keine passenden Kontaktdaten für diesen Benutzer.',
	'IM_NO_JABBER'			=> 'Direkter Kontakt zu Jabber-Benutzern wird auf diesem Board nicht unterstützt. Sie benötigen einen installierten Jabber-Client auf Ihrem Rechner, um den Benutzer kontaktieren zu können.',
	'IM_RECIPIENT'			=> 'Empfänger',
	'IM_SEND'				=> 'Nachricht senden',
	'IM_SEND_MESSAGE'		=> 'Nachricht senden',
	'IM_SENT_JABBER'		=> 'Ihre Nachricht an %1$s wurde erfolgreich gesendet.',
	'IM_USER'				=> 'Eine Instant Message senden',

	'LAST_ACTIVE'				=> 'Letzte Aktivität',
	'LESS_THAN'					=> 'Weniger als',
	'LIST_USER'					=> '1 Mitglied',
	'LIST_USERS'				=> '%d Mitglieder',
	'LOGIN_EXPLAIN_LEADERS'		=> 'Sie müssen registriert und angemeldet sein, um die Liste der Team-Mitglieder anzuschauen.',
	'LOGIN_EXPLAIN_MEMBERLIST'	=> 'Sie müssen registriert und angemeldet sein, um auf die Mitgliederliste zuzugreifen.',
	'LOGIN_EXPLAIN_SEARCHUSER'	=> 'Sie müssen registriert und angemeldet sein, um nach Mitgliedern zu suchen.',
	'LOGIN_EXPLAIN_VIEWPROFILE'	=> 'Sie müssen registriert und angemeldet sein, um Profile anzuschauen.',

	'MORE_THAN'				=> 'Mehr als',

	'NO_EMAIL'				=> 'Sie sind nicht berechtigt, eine E-Mail an diesen Benutzer zu senden.',
	'NO_VIEW_USERS'			=> 'Sie sind nicht berechtigt, die Mitgliederliste oder Profile anzusehen.',

	'ORDER'					=> 'Sortierung',
	'OTHER'					=> 'Anderes Zeichen',

	'POST_IP'				=> 'Erstellt von IP/Domain',

	'REAL_NAME'				=> 'Name des Empfängers',
	'RECIPIENT'				=> 'Empfänger',
	'REMOVE_FOE'			=> 'Aus der Liste der ignorierten Mitglieder entfernen',
	'REMOVE_FRIEND'			=> 'Aus der Liste der Freunde entfernen',

	'SELECT_MARKED'			=> 'Markierte auswählen',
	'SELECT_SORT_METHOD'	=> 'Sortierung auswählen',
	'SEND_AIM_MESSAGE'		=> 'AIM-Nachricht senden',
	'SEND_ICQ_MESSAGE'		=> 'ICQ-Nachricht senden',
	'SEND_IM'				=> 'Instant Message senden',
	'SEND_JABBER_MESSAGE'	=> 'Jabber-Nachricht senden',
	'SEND_MESSAGE'			=> 'Nachricht',
	'SEND_MSNM_MESSAGE'		=> 'WLM-Nachricht senden',
	'SEND_YIM_MESSAGE'		=> 'YIM-Nachricht senden',
	'SORT_EMAIL'			=> 'E-Mail',
	'SORT_LAST_ACTIVE'		=> 'Letzte Aktivität',
	'SORT_POST_COUNT'		=> 'Beitragszahl',

	'USERNAME_BEGINS_WITH'	=> 'Benutzername fängt an mit',
	'USER_ADMIN'			=> 'Benutzer administrieren',
	'USER_BAN'				=> 'Sperren',
	'USER_FORUM'			=> 'Benutzer-Statistik',
	'USER_LAST_REMINDED'	=> array(
		0		=> 'Bislang wurde keine Erinnerung versendet',
		1		=> '%1$d Erinnerung versendet<br />» %2$s',
		2		=> '%1$d Erinnerungen versendet<br />» %2$s',
	),
	'USER_ONLINE'			=> 'Online',
	'USER_PRESENCE'			=> 'Anwesenheit im Board',

	'VIEWING_PROFILE'		=> 'Profil von %s',
	'VISITED'				=> 'Letzte Anmeldung',

	'WWW'					=> 'Website',
));

?>