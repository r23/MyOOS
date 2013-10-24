<?php
/**
*
* acp_permissions_phpbb (phpBB Permission Set) [Deutsch — Du]
*
* @package language
* @version $Id: permissions_phpbb.php 617 2013-09-29 10:21:18Z pyramide $
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

/**
*	MODDERS PLEASE NOTE
*
*	You are able to put your permission sets into a separate file too by
*	prefixing the new file with permissions_ and putting it into the acp
*	language folder.
*
*	An example of how the file could look like:
*
*	<code>
*
*	if (empty($lang) || !is_array($lang))
*	{
*		$lang = array();
*	}
*
*	// Adding new category
*	$lang['permission_cat']['bugs'] = 'Bugs';
*
*	// Adding new permission set
*	$lang['permission_type']['bug_'] = 'Bug Permissions';
*
*	// Adding the permissions
*	$lang = array_merge($lang, array(
*		'acl_bug_view'		=> array('lang' => 'Can view bug reports', 'cat' => 'bugs'),
*		'acl_bug_post'		=> array('lang' => 'Can post bugs', 'cat' => 'post'), // Using a phpBB category here
*	));
*
*	</code>
*/

// Define categories and permission types
$lang = array_merge($lang, array(
	'permission_cat'	=> array(
		'actions'		=> 'Aktivitäten',
		'content'		=> 'Inhalt',
		'forums'		=> 'Foren',
		'misc'			=> 'Diverses',
		'permissions'	=> 'Rechte',
		'pm'			=> 'Private Nachrichten',
		'polls'			=> 'Umfragen',
		'post'			=> 'Beiträge',
		'post_actions'	=> 'Beitrags-Vorgänge',
		'posting'		=> 'Beiträge',
		'profile'		=> 'Profil',
		'settings'		=> 'Einstellungen',
		'topic_actions'	=> 'Themen-Vorgänge',
		'user_group'	=> 'Benutzer &amp; Gruppen',
	),

	// With defining 'global' here we are able to specify what is printed out if the permission is within the global scope.
	'permission_type'	=> array(
		'u_'			=> 'Benutzer-Berechtigungen',
		'a_'			=> 'Administrator-Berechtigungen',
		'm_'			=> 'Moderator-Berechtigungen',
		'f_'			=> 'Forums-Berechtigungen',
		'global'		=> array(
			'm_'			=> 'Globale Moderator-Berechtigungen',
		),
	),
));

// User Permissions
$lang = array_merge($lang, array(
	'acl_u_viewprofile'	=> array('lang' => 'Kann Profile, die Mitglieder- und die Wer-ist-online-Liste ansehen', 'cat' => 'profile'),
	'acl_u_chgname'		=> array('lang' => 'Kann Benutzernamen ändern', 'cat' => 'profile'),
	'acl_u_chgpasswd'	=> array('lang' => 'Kann Passwort ändern', 'cat' => 'profile'),
	'acl_u_chgemail'	=> array('lang' => 'Kann E-Mail-Adresse ändern', 'cat' => 'profile'),
	'acl_u_chgavatar'	=> array('lang' => 'Kann Avatar ändern', 'cat' => 'profile'),
	'acl_u_chggrp'		=> array('lang' => 'Kann Hauptgruppe ändern', 'cat' => 'profile'),

	'acl_u_attach'		=> array('lang' => 'Kann Dateianhänge erstellen', 'cat' => 'post'),
	'acl_u_download'	=> array('lang' => 'Kann Dateianhänge herunterladen', 'cat' => 'post'),
	'acl_u_savedrafts'	=> array('lang' => 'Kann Entwürfe speichern', 'cat' => 'post'),
	'acl_u_chgcensors'	=> array('lang' => 'Kann Wortzensur umgehen', 'cat' => 'post'),
	'acl_u_sig'			=> array('lang' => 'Kann Signatur verwenden', 'cat' => 'post'),

	'acl_u_sendpm'		=> array('lang' => 'Kann Private Nachrichten verschicken', 'cat' => 'pm'),
	'acl_u_masspm'		=> array('lang' => 'Kann PNs an mehrere Benutzer verschicken', 'cat' => 'pm'),
	'acl_u_masspm_group'=> array('lang' => 'Kann PNs an Gruppen verschicken', 'cat' => 'pm'),
	'acl_u_readpm'		=> array('lang' => 'Kann Private Nachrichten lesen', 'cat' => 'pm'),
	'acl_u_pm_edit'		=> array('lang' => 'Kann eigene Private Nachrichten ändern', 'cat' => 'pm'),
	'acl_u_pm_delete'	=> array('lang' => 'Kann Private Nachrichten aus eigenem Ordner entfernen', 'cat' => 'pm'),
	'acl_u_pm_forward'	=> array('lang' => 'Kann Private Nachrichten weiterleiten', 'cat' => 'pm'),
	'acl_u_pm_emailpm'	=> array('lang' => 'Kann Private Nachrichten per E-Mail versenden', 'cat' => 'pm'),
	'acl_u_pm_printpm'	=> array('lang' => 'Kann Private Nachrichten drucken', 'cat' => 'pm'),
	'acl_u_pm_attach'	=> array('lang' => 'Kann Dateianhänge in Privaten Nachrichten versenden', 'cat' => 'pm'),
	'acl_u_pm_download'	=> array('lang' => 'Kann Dateianhänge in Privaten Nachrichten herunterladen', 'cat' => 'pm'),
	'acl_u_pm_bbcode'	=> array('lang' => 'Kann BBCode in Privaten Nachrichten verwenden', 'cat' => 'pm'),
	'acl_u_pm_smilies'	=> array('lang' => 'Kann Smilies in Privaten Nachrichten verwenden', 'cat' => 'pm'),
	'acl_u_pm_img'		=> array('lang' => 'Kann den [img]-BBCode-Tag in Privaten Nachrichten verwenden', 'cat' => 'pm'),
	'acl_u_pm_flash'	=> array('lang' => 'Kann den [flash]-BBCode-Tag in Privaten Nachrichten verwenden', 'cat' => 'pm'),

	'acl_u_sendemail'	=> array('lang' => 'Kann E-Mails versenden', 'cat' => 'misc'),
	'acl_u_sendim'		=> array('lang' => 'Kann Instant Messages versenden', 'cat' => 'misc'),
	'acl_u_ignoreflood'	=> array('lang' => 'Kann Wartezeiten übergehen', 'cat' => 'misc'),
	'acl_u_hideonline'	=> array('lang' => 'Kann Online-Status ausblenden', 'cat' => 'misc'),
	'acl_u_viewonline'	=> array('lang' => 'Kann auch unsichtbare Benutzer online sehen', 'cat' => 'misc'),
	'acl_u_search'		=> array('lang' => 'Kann die Suchfunktion benutzen', 'cat' => 'misc'),
));

// Forum Permissions
$lang = array_merge($lang, array(
	'acl_f_list'		=> array('lang' => 'Kann Forum sehen', 'cat' => 'post'),
	'acl_f_read'		=> array('lang' => 'Kann Forum lesen', 'cat' => 'post'),
	'acl_f_post'		=> array('lang' => 'Kann neue Themen im Forum starten', 'cat' => 'post'),
	'acl_f_reply'		=> array('lang' => 'Kann auf Themen antworten', 'cat' => 'post'),
	'acl_f_icons'		=> array('lang' => 'Kann Themen-/Beitrags-Symbole verwenden', 'cat' => 'post'),
	'acl_f_announce'	=> array('lang' => 'Kann Bekanntmachungen erstellen', 'cat' => 'post'),
	'acl_f_sticky'		=> array('lang' => 'Kann wichtige Themen erstellen', 'cat' => 'post'),

	'acl_f_poll'		=> array('lang' => 'Kann Umfragen erstellen', 'cat' => 'polls'),
	'acl_f_vote'		=> array('lang' => 'Kann an Umfragen teilnehmen', 'cat' => 'polls'),
	'acl_f_votechg'		=> array('lang' => 'Kann Abstimmung ändern', 'cat' => 'polls'),

	'acl_f_attach'		=> array('lang' => 'Kann Dateianhänge anfügen', 'cat' => 'content'),
	'acl_f_download'	=> array('lang' => 'Kann Dateianhänge herunterladen', 'cat' => 'content'),
	'acl_f_sigs'		=> array('lang' => 'Kann Signatur verwenden', 'cat' => 'content'),
	'acl_f_bbcode'		=> array('lang' => 'Kann BBCode verwenden', 'cat' => 'content'),
	'acl_f_smilies'		=> array('lang' => 'Kann Smilies verwenden', 'cat' => 'content'),
	'acl_f_img'			=> array('lang' => 'Kann den [img]-BBCode-Tag verwenden', 'cat' => 'content'),
	'acl_f_flash'		=> array('lang' => 'Kann den [flash]-BBCode-Tag verwenden', 'cat' => 'content'),

	'acl_f_edit'		=> array('lang' => 'Kann eigene Beiträge ändern', 'cat' => 'actions'),
	'acl_f_delete'		=> array('lang' => 'Kann eigene Beiträge löschen', 'cat' => 'actions'),
	'acl_f_user_lock'	=> array('lang' => 'Kann eigene Themen sperren', 'cat' => 'actions'),
	'acl_f_bump'		=> array('lang' => 'Kann Themen als neu markieren', 'cat' => 'actions'),
	'acl_f_report'		=> array('lang' => 'Kann Beiträge melden', 'cat' => 'actions'),
	'acl_f_subscribe'	=> array('lang' => 'Kann Forum beobachten', 'cat' => 'actions'),
	'acl_f_print'		=> array('lang' => 'Kann Themen drucken', 'cat' => 'actions'),
	'acl_f_email'		=> array('lang' => 'Kann Themen per E-Mail empfehlen', 'cat' => 'actions'),

	'acl_f_search'		=> array('lang' => 'Kann das Forum durchsuchen', 'cat' => 'misc'),
	'acl_f_ignoreflood' => array('lang' => 'Kann die Wartezeit umgehen', 'cat' => 'misc'),
	'acl_f_postcount'	=> array('lang' => 'Beitrags-Zähler wird erhöht<br /><em>Bitte beachte, dass diese Einstellung nur für neue Beiträge greift.</em>', 'cat' => 'misc'),
	'acl_f_noapprove'	=> array('lang' => 'Kann Beiträge ohne Freigabe erstellen', 'cat' => 'misc'),
));

// Moderator Permissions
$lang = array_merge($lang, array(
	'acl_m_edit'		=> array('lang' => 'Kann Beiträge ändern', 'cat' => 'post_actions'),
	'acl_m_delete'		=> array('lang' => 'Kann Beiträge löschen', 'cat' => 'post_actions'),
	'acl_m_approve'		=> array('lang' => 'Kann Beiträge freigeben', 'cat' => 'post_actions'),
	'acl_m_report'		=> array('lang' => 'Kann Meldungen schließen und löschen', 'cat' => 'post_actions'),
	'acl_m_chgposter'	=> array('lang' => 'Kann Autor eines Beitrags ändern', 'cat' => 'post_actions'),

	'acl_m_move'	=> array('lang' => 'Kann Themen verschieben', 'cat' => 'topic_actions'),
	'acl_m_lock'	=> array('lang' => 'Kann Themen sperren', 'cat' => 'topic_actions'),
	'acl_m_split'	=> array('lang' => 'Kann Themen teilen', 'cat' => 'topic_actions'),
	'acl_m_merge'	=> array('lang' => 'Kann Themen zusammenführen', 'cat' => 'topic_actions'),

	'acl_m_info'	=> array('lang' => 'Kann Beitrags-Details ansehen', 'cat' => 'misc'),
	'acl_m_warn'	=> array('lang' => 'Kann Verwarnungen aussprechen<br /><em>Diese Berechtigung wird global und nicht forenbezogen erteilt.</em>', 'cat' => 'misc'), // This moderator setting is only global (and not local)
	'acl_m_ban'		=> array('lang' => 'Kann Sperren verwalten<br /><em>Diese Berechtigung wird global und nicht forenbezogen erteilt.</em>', 'cat' => 'misc'), // This moderator setting is only global (and not local)
));

// Admin Permissions
$lang = array_merge($lang, array(
	'acl_a_board'		=> array('lang' => 'Kann Board-Einstellungen ändern/auf Updates prüfen', 'cat' => 'settings'),
	'acl_a_server'		=> array('lang' => 'Kann Server-/Kommunikations-Einstellungen ändern', 'cat' => 'settings'),
	'acl_a_jabber'		=> array('lang' => 'Kann Jabber-Einstellungen ändern', 'cat' => 'settings'),
	'acl_a_phpinfo'		=> array('lang' => 'Kann PHP-Einstellungen anzeigen', 'cat' => 'settings'),

	'acl_a_forum'		=> array('lang' => 'Kann Foren verwalten', 'cat' => 'forums'),
	'acl_a_forumadd'	=> array('lang' => 'Kann neue Foren erstellen', 'cat' => 'forums'),
	'acl_a_forumdel'	=> array('lang' => 'Kann Foren löschen', 'cat' => 'forums'),
	'acl_a_prune'		=> array('lang' => 'Kann inaktive Themen löschen', 'cat' => 'forums'),

	'acl_a_icons'		=> array('lang' => 'Kann Themen-/Beitrags-Symbole und Smilies ändern', 'cat' => 'posting'),
	'acl_a_words'		=> array('lang' => 'Kann Wortzensur einstellen', 'cat' => 'posting'),
	'acl_a_bbcode'		=> array('lang' => 'Kann BBCode-Tags festlegen', 'cat' => 'posting'),
	'acl_a_attach'		=> array('lang' => 'Kann Einstellungen zu Dateianhängen ändern', 'cat' => 'posting'),

	'acl_a_user'		=> array('lang' => 'Kann Benutzer verwalten<br /><em>Dies beinhaltet das Recht, den verwendeten Browser in der Wer-ist-online-Liste einzusehen.</em>', 'cat' => 'user_group'),
	'acl_a_userdel'		=> array('lang' => 'Kann Benutzer löschen', 'cat' => 'user_group'),
	'acl_a_group'		=> array('lang' => 'Kann Gruppen verwalten', 'cat' => 'user_group'),
	'acl_a_groupadd'	=> array('lang' => 'Kann neue Gruppen erstellen', 'cat' => 'user_group'),
	'acl_a_groupdel'	=> array('lang' => 'Kann Gruppen löschen', 'cat' => 'user_group'),
	'acl_a_ranks'		=> array('lang' => 'Kann Ränge verwalten', 'cat' => 'user_group'),
	'acl_a_profile'		=> array('lang' => 'Kann benutzerdefinierte Profilfelder verwalten', 'cat' => 'user_group'),
	'acl_a_names'		=> array('lang' => 'Kann verbotene Benutzernamen verwalten', 'cat' => 'user_group'),
	'acl_a_ban'			=> array('lang' => 'Kann Sperrungen verwalten', 'cat' => 'user_group'),

	'acl_a_viewauth'	=> array('lang' => 'Kann effektive Berechtigungen anzeigen', 'cat' => 'permissions'),
	'acl_a_authgroups'	=> array('lang' => 'Kann Berechtigungen für einzelne Gruppen ändern', 'cat' => 'permissions'),
	'acl_a_authusers'	=> array('lang' => 'Kann Berechtigungen für einzelne Benutzer ändern', 'cat' => 'permissions'),
	'acl_a_fauth'		=> array('lang' => 'Kann allgemeine Foren-Berechtigungen ändern', 'cat' => 'permissions'),
	'acl_a_mauth'		=> array('lang' => 'Kann allgemeine Moderatoren-Berechtigungen ändern', 'cat' => 'permissions'),
	'acl_a_aauth'		=> array('lang' => 'Kann allgemeine Administrator-Berechtigungen ändern', 'cat' => 'permissions'),
	'acl_a_uauth'		=> array('lang' => 'Kann allgemeine Benutzer-Berechtigungen ändern', 'cat' => 'permissions'),
	'acl_a_roles'		=> array('lang' => 'Kann Rollen verwalten', 'cat' => 'permissions'),
	'acl_a_switchperm'	=> array('lang' => 'Kann andere Berechtigungen testen', 'cat' => 'permissions'),

	'acl_a_styles'		=> array('lang' => 'Kann Styles verwalten', 'cat' => 'misc'),
	'acl_a_viewlogs'	=> array('lang' => 'Kann Protokolle anzeigen', 'cat' => 'misc'),
	'acl_a_clearlogs'	=> array('lang' => 'Kann Protokolle löschen', 'cat' => 'misc'),
	'acl_a_modules'		=> array('lang' => 'Kann Module verwalten', 'cat' => 'misc'),
	'acl_a_language'	=> array('lang' => 'Kann Sprachpakete verwalten', 'cat' => 'misc'),
	'acl_a_email'		=> array('lang' => 'Kann Massen-E-Mails versenden', 'cat' => 'misc'),
	'acl_a_bots'		=> array('lang' => 'Kann Bots verwalten', 'cat' => 'misc'),
	'acl_a_reasons'		=> array('lang' => 'Kann Meldungs-/Ablehnungs-Gründe verwalten', 'cat' => 'misc'),
	'acl_a_backup'		=> array('lang' => 'Kann ein Backup der Datenbank vornehmen und sie wiederherstellen', 'cat' => 'misc'),
	'acl_a_search'		=> array('lang' => 'Kann Such-Backends verwalten und einstellen', 'cat' => 'misc'),
));

?>