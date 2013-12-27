<?php
/**
*
* acp_board [Deutsch — Sie]
*
* @package language
* @version $Id: board.php 617 2013-09-29 10:21:18Z pyramide $
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

// Board Settings
$lang = array_merge($lang, array(
	'ACP_BOARD_SETTINGS_EXPLAIN'	=> 'Hier können Sie einige grundlegende Einstellungen Ihres Boards vornehmen, ihm einen passenden Namen und eine Beschreibung geben und, neben anderen Werten, die Standard-Einstellungen für Zeitzone und Sprache anpassen.',
	'CUSTOM_DATEFORMAT'				=> 'Eigenes …',
	'DEFAULT_DATE_FORMAT'			=> 'Datumsformat',
	'DEFAULT_DATE_FORMAT_EXPLAIN'	=> 'Die Syntax entspricht der der <a href="http://www.php.net/date"><code>date()</code></a>-Funktion von PHP.',
	'DEFAULT_LANGUAGE'				=> 'Standard-Sprache',
	'DEFAULT_STYLE'					=> 'Standard-Style',
	'DISABLE_BOARD'					=> 'Board deaktivieren',
	'DISABLE_BOARD_EXPLAIN'			=> 'Hiermit sperren Sie das Board für alle Benutzer. Wenn Sie möchten, können Sie eine kurze Nachricht (bis zu 255 Zeichen) angeben.',
	'OVERRIDE_STYLE'				=> 'Benutzer-Style überschreiben',
	'OVERRIDE_STYLE_EXPLAIN'		=> 'Verwendet den Standard-Style statt der individuell von den Benutzern gewählten Styles.',
	'SITE_DESC'						=> 'Beschreibung des Boards',
	'SITE_NAME'						=> 'Name des Boards',
	'SYSTEM_DST'					=> 'Derzeit ist Sommerzeit',
	'SYSTEM_TIMEZONE'				=> 'Zeitzone für Gäste',
	'SYSTEM_TIMEZONE_EXPLAIN'		=> 'Zeitzone, die für Benutzer verwendet wird, die nicht angemeldet sind (Gäste, Bots). Angemeldete Benutzer legen ihre Zeitzone während der Registrierung fest und können sie im persönlichen Bereich ändern.',
	'WARNINGS_EXPIRE'				=> 'Gültigkeit von Verwarnungen',
	'WARNINGS_EXPIRE_EXPLAIN'		=> 'Die Anzahl der Tage, nach denen eine Verwarnung automatisch aus dem Benutzer-Profil gelöscht wird. Um Verwarnungen dauerhaft zu machen, stellen Sie als Wert 0 ein.',
));

// Board Features
$lang = array_merge($lang, array(
	'ACP_BOARD_FEATURES_EXPLAIN'	=> 'Hier können Sie einige Funktionen des Boards aktivieren bzw. deaktivieren.',

	'ALLOW_ATTACHMENTS'			=> 'Dateianhänge erlauben',
	'ALLOW_BIRTHDAYS'			=> 'Geburtstage aktivieren',
	'ALLOW_BIRTHDAYS_EXPLAIN'	=> 'Aktiviert die Eingabe von Geburtstagen und die Anzeige des Alters im Profil. Beachten Sie, dass für die Geburtstagsanzeige in der Foren-Übersicht eine getrennte Option in den Einstellungen zur Serverlast existiert.',
	'ALLOW_BOOKMARKS'			=> 'Setzen von Lesezeichen für Themen erlauben',
	'ALLOW_BOOKMARKS_EXPLAIN'	=> 'Der Benutzer darf persönliche Lesezeichen speichern.',
	'ALLOW_BBCODE'				=> 'BBCode erlauben',
	'ALLOW_FORUM_NOTIFY'		=> 'Beobachten von Foren erlauben',
	'ALLOW_NAME_CHANGE'			=> 'Namenswechsel erlauben',
	'ALLOW_NO_CENSORS'			=> 'Deaktivieren der Wortzensur erlauben',
	'ALLOW_NO_CENSORS_EXPLAIN'	=> 'Benutzer können die automatische Wortzensur in Beiträgen und Privaten Nachrichten deaktivieren.',
	'ALLOW_PM_ATTACHMENTS'		=> 'Dateianhänge in Privaten Nachrichten erlauben',
	'ALLOW_PM_REPORT'			=> 'Benutzern die Meldung Privater Nachrichten erlauben',
	'ALLOW_PM_REPORT_EXPLAIN'	=> 'Wenn diese Option aktiviert ist, können Benutzer eine Private Nachricht, die sie empfangen oder gesendet haben, an die Moderatoren des Boards melden Diese Privaten Nachrichten können dann im Moderations-Bereich eingesehen werden.',
	'ALLOW_QUICK_REPLY'			=> 'Schnellantwort erlauben',
	'ALLOW_QUICK_REPLY_EXPLAIN'	=> 'Diese Einstellung ermöglicht es, die Schnellantwort im gesamten Board zu deaktivieren. Wenn die Einstellung aktiviert ist, regeln die spezifischen Einstellungen der Foren, ob die Schnellantwort verfügbar ist.',
	'ALLOW_QUICK_REPLY_BUTTON'	=> 'Absenden und Schnellantwort in allen Foren aktivieren',
	'ALLOW_SIG'					=> 'Signaturen erlauben',
	'ALLOW_SIG_BBCODE'			=> 'BBCode in Signaturen erlauben',
	'ALLOW_SIG_FLASH'			=> 'BBCode-Tag <code>flash</code> in Signaturen erlauben',
	'ALLOW_SIG_IMG'				=> 'BBCode-Tag <code>img</code> in Signaturen erlauben',
	'ALLOW_SIG_LINKS'			=> 'Links in Signaturen erlauben',
	'ALLOW_SIG_LINKS_EXPLAIN'	=> '„Nein“ deaktiviert den <code>[URL]</code> BBCode-Tag und die automatische Verlinkung von URLs.',
	'ALLOW_SIG_SMILIES'			=> 'Smilies in Signaturen erlauben',
	'ALLOW_SMILIES'				=> 'Smilies erlauben',
	'ALLOW_TOPIC_NOTIFY'		=> 'Beobachten von Themen erlauben',
	'BOARD_PM'					=> 'Private Nachrichten',
	'BOARD_PM_EXPLAIN'			=> 'Aktiviert Private Nachrichten für alle Benutzer.',
));

// Avatar Settings
$lang = array_merge($lang, array(
	'ACP_AVATAR_SETTINGS_EXPLAIN'	=> 'Avatare sind im Allgemeinen kleine, einzigartige Bilder, mit denen sich die Mitglieder identifizieren können. Abhängig vom Style werden diese Bilder normalerweise unter dem Benutzernamen angezeigt, wenn Themen betrachtet werden. Hier können Sie die Art der Avatar-Nutzung festlegen. Bitte denken Sie daran, dass Sie das von Ihnen angegebene Verzeichnis erstellen und sicherstellen müssen, dass es vom Webserver beschreibbar ist, damit Avatare hochgeladen werden können. Bedenken Sie außerdem, dass Dateigrößen-Beschränkungen nur bei hochgeladenen Avataren greifen, nicht jedoch bei von anderen Seiten verlinkten Bildern.',

	'ALLOW_AVATARS'					=> 'Avatare erlauben',
	'ALLOW_AVATARS_EXPLAIN'			=> 'Erlaubt die generelle Nutzung von Avataren.<br />Wenn Sie Avatare generell oder die eines bestimmten Typs deaktivieren, werden die deaktivierten Avatare nicht mehr im Board angezeigt, Benutzer können ihren Avatar aber weiterhin im persönlichen Bereich herunterladen.',
	'ALLOW_LOCAL'					=> 'Galerie-Avatare erlauben',
	'ALLOW_REMOTE'					=> 'Remote-Avatare erlauben',
	'ALLOW_REMOTE_EXPLAIN'			=> 'Avatare, die von einer anderen Website verlinkt werden.',
	'ALLOW_REMOTE_UPLOAD'			=> 'Avatar-Upload von URL aktivieren',
	'ALLOW_REMOTE_UPLOAD_EXPLAIN'	=> 'Erlaubt das Hochladen eines Avatars von einer anderen Website.',
	'ALLOW_UPLOAD'					=> 'Hochladen von Avataren erlauben',
	'AVATAR_GALLERY_PATH'			=> 'Avatar-Galeriepfad',
	'AVATAR_GALLERY_PATH_EXPLAIN'	=> 'Der Pfad von Ihrem phpBB-Hauptverzeichnis aus, in dem die Galerie-Avatare liegen (z.&nbsp;B. <samp>images/avatars/gallery</samp>).',
	'AVATAR_STORAGE_PATH'			=> 'Avatar Speicherpfad',
	'AVATAR_STORAGE_PATH_EXPLAIN'	=> 'Der Pfad von Ihrem phpBB-Hauptverzeichnis aus, in dem die Avatare gespeichert werden (z.&nbsp;B. <samp>images/avatars/upload</samp>).',
	'MAX_AVATAR_SIZE'				=> 'Maximale Abmessungen für Avatare',
	'MAX_AVATAR_SIZE_EXPLAIN'		=> 'Breite &times; Höhe in Pixel',
	'MAX_FILESIZE'					=> 'Maximale Dateigröße',
	'MAX_FILESIZE_EXPLAIN'			=> 'Für hochgeladene Avatare. Bei 0 wird die Dateigröße nur durch die PHP-Konfiguration limitiert.',
	'MIN_AVATAR_SIZE'				=> 'Minimale Abmessungen für Avatare',
	'MIN_AVATAR_SIZE_EXPLAIN'		=> 'Breite &times; Höhe in Pixel',
));

// Message Settings
$lang = array_merge($lang, array(
	'ACP_MESSAGE_SETTINGS_EXPLAIN'		=> 'Hier können Sie alle Standard-Einstellungen für Private Nachrichten vornehmen.',

	'ALLOW_BBCODE_PM'			=> 'BBCode in Privaten Nachrichten erlauben',
	'ALLOW_FLASH_PM'			=> 'BBCode-Tag <code>[FLASH]</code> in Privaten Nachrichten erlauben',
	'ALLOW_FLASH_PM_EXPLAIN'	=> 'Die Möglichkeit, Flash in Privaten Nachrichten zu verwenden, hängt auch von den gesetzten Berechtigungen ab.',
	'ALLOW_FORWARD_PM'			=> 'Weiterleiten von Privaten Nachrichten erlauben',
	'ALLOW_IMG_PM'				=> 'BBCode-Tag <code>[IMG]</code> in Privaten Nachrichten erlauben',
	'ALLOW_MASS_PM'				=> 'Versand von Privaten Nachrichten an mehrere Mitglieder oder Gruppen erlauben',
	'ALLOW_MASS_PM_EXPLAIN'		=> 'Der Versand an Gruppen kann für jede Gruppe in den Gruppeneinstellungen angepasst werden.',
	'ALLOW_PRINT_PM'			=> 'Druckansicht in Privaten Nachrichten erlauben',
	'ALLOW_QUOTE_PM'			=> 'Zitate in Privaten Nachrichten erlauben',
	'ALLOW_SIG_PM'				=> 'Signatur in Privaten Nachrichten erlauben',
	'ALLOW_SMILIES_PM'			=> 'Smilies in Privaten Nachrichten erlauben',
	'BOXES_LIMIT'				=> 'Maximale Anzahl von Nachrichten pro Ordner',
	'BOXES_LIMIT_EXPLAIN'		=> 'Benutzer können in einem Ordner nicht mehr als die hier festgelegte Anzahl an Privaten Nachrichten ablegen. Um eine unbegrenzte Anzahl zuzulassen, stellen Sie als Wert 0 ein.',
	'BOXES_MAX'					=> 'Maximale Anzahl an Ordnern',
	'BOXES_MAX_EXPLAIN'			=> 'Standardmäßig können Benutzer diese Anzahl an persönlichen Ordnern für Private Nachrichten erstellen.',
	'ENABLE_PM_ICONS'			=> 'Die Nutzung von Themen-Symbolen in Privaten Nachrichten aktivieren',
	'FULL_FOLDER_ACTION'		=> 'Standard-Verhalten bei vollem Ordner',
	'FULL_FOLDER_ACTION_EXPLAIN'=> 'Das standardmäßige Verhalten, wenn der Ordner eines Benutzers voll ist und die von ihm eingestellte Aktion nicht durchführbar ist bzw. diese nicht festgelegt wurde. Eine Ausnahme gilt für den Ordner „Gesendete Nachrichten“, wo das Standard-Verhalten immer so eingestellt ist, dass alte Nachrichten gelöscht werden.',
	'HOLD_NEW_MESSAGES'			=> 'Neue Nachrichten zurückhalten',
	'PM_EDIT_TIME'				=> 'Nachträgliche Bearbeitung einschränken',
	'PM_EDIT_TIME_EXPLAIN'		=> 'Limitiert die Zeit zur Bearbeitung einer gesendeten, aber noch ungelesenen Privaten Nachricht. Um dieses Verhalten abzuschalten, stellen Sie als Wert 0 ein.',
	'PM_MAX_RECIPIENTS'			=> 'Maximale Anzahl zulässiger Empfänger',
	'PM_MAX_RECIPIENTS_EXPLAIN'	=> 'Die maximale Anzahl zulässiger Empfänger für eine Private Nachricht. Bei einem Wert von 0 sind unbegrenzt viele Empfänger zulässig. Diese Einstellung kann gruppenbezogen in den Gruppeneinstellungen angepasst werden.',
));

// Post Settings
$lang = array_merge($lang, array(
	'ACP_POST_SETTINGS_EXPLAIN'			=> 'Hier können Sie alle Standard-Einstellungen für Beiträge vornehmen.',
	'ALLOW_POST_LINKS'					=> 'Links in Beiträgen/Privaten Nachrichten erlauben',
	'ALLOW_POST_LINKS_EXPLAIN'			=> '„Nein“ deaktiviert den <code>[URL]</code> BBCode-Tag und die automatische Verlinkung von URLs.',
	'ALLOW_POST_FLASH'					=> 'BBCode-Tag <code>[FLASH]</code> in Beiträgen erlauben',
	'ALLOW_POST_FLASH_EXPLAIN'			=> 'Wenn deaktiviert, ist der <code>[FLASH]</code> BBCode-Tag in Beiträgen deaktiviert. Andernfalls wird durch das Berechtigungssystem festgelegt, welche Benutzer den <code>[FLASH]</code> BBCode-Tag benutzen können.',

	'BUMP_INTERVAL'					=> 'Neu-Markierung möglich nach',
	'BUMP_INTERVAL_EXPLAIN'			=> 'Die Zahl der Minuten, Stunden oder Tage, die seit dem letzten Beitrag zu einem Thema vergangen sein müssen, damit das Thema als „Neu“ markiert werden kann. Um die Markierung als „Neu“ vollständig zu deaktivieren, stellen Sie als Wert 0 ein.',
	'CHAR_LIMIT'					=> 'Maximale Anzahl der Zeichen pro Beitrag/Nachricht',
	'CHAR_LIMIT_EXPLAIN'			=> 'Die maximale Zahl von Zeichen, die in einem Beitrag/einer Privaten Nachricht zulässig sind; 0 bedeutet unbegrenzt.',
	'DELETE_TIME'					=> 'Begrenze Löschzeit',
	'DELETE_TIME_EXPLAIN'			=> 'Begrenzt die Zeit, die zur Löschung eines neuen Beitrags zur Verfügung steht. Um keine Begrenzung festzulegen, stelle als Wert 0 ein.',
	'DISPLAY_LAST_EDITED'			=> 'Bearbeitungen anzeigen',
	'DISPLAY_LAST_EDITED_EXPLAIN'	=> 'Wählen Sie aus, ob die Information „Zuletzt bearbeitet von“ in Beiträgen angezeigt werden soll.',
	'EDIT_TIME'						=> 'Nachträgliche Bearbeitung einschränken',
	'EDIT_TIME_EXPLAIN'				=> 'Limitiert die Zeit, in der ein neuer Beitrag bearbeitet werden kann; 0 bedeutet unbegrenzt.',
	'FLOOD_INTERVAL'				=> 'Wartezeit zwischen zwei Beiträgen',
	'FLOOD_INTERVAL_EXPLAIN'		=> 'Die Zeit in Sekunden, die ein Benutzer warten muss, bevor er einen neuen Beitrag schreiben kann. Wenn Sie Benutzern erlauben möchten, die Wartezeit zu umgehen, müssen Sie deren Befugnisse anpassen.',
	'HOT_THRESHOLD'					=> 'Grenzwert für beliebte Themen',
	'HOT_THRESHOLD_EXPLAIN'			=> 'Anzahl der Beiträge in einem Thema, bis es als „beliebtes Thema“ angezeigt wird. Um beliebte Themen zu deaktivieren, stellen Sie als Wert 0 ein.',
	'MAX_POLL_OPTIONS'				=> 'Maximale Anzahl an Umfrage-Optionen',
	'MAX_POST_FONT_SIZE'			=> 'Maximale Schriftgröße in Beiträgen',
	'MAX_POST_FONT_SIZE_EXPLAIN'	=> 'Maximal in Beiträgen zulässige Schriftgröße. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_POST_IMG_HEIGHT'			=> 'Maximale Bild-Höhe in Beiträgen',
	'MAX_POST_IMG_HEIGHT_EXPLAIN'	=> 'Die maximale Höhe eines Bildes/einer Flash-Datei in Beiträgen. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_POST_IMG_WIDTH'			=> 'Maximale Bild-Breite in Beiträgen',
	'MAX_POST_IMG_WIDTH_EXPLAIN'	=> 'Die maximale Breite eines Bildes/einer Flash-Datei in Beiträgen. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_POST_URLS'					=> 'Maximale Anzahl an Links pro Beitrag',
	'MAX_POST_URLS_EXPLAIN'			=> 'Maximale Anzahl von Links in einem Beitrag. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MIN_CHAR_LIMIT'				=> 'Minimale Anzahl von Zeichen pro Beitrag/Nachricht',
	'MIN_CHAR_LIMIT_EXPLAIN'		=> 'Die minimale Anzahl von Zeichen, die ein Benutzer in einem Beitrag/einer Nachricht mindestens eingeben muss. Der Wert muss 1 oder größer sein.',
	'POSTING'						=> 'Beiträge schreiben',
	'POSTS_PER_PAGE'				=> 'Beiträge pro Seite',
	'QUOTE_DEPTH_LIMIT'				=> 'Maximale Tiefe verschachtelter Zitate',
	'QUOTE_DEPTH_LIMIT_EXPLAIN'		=> 'Die maximale Tiefe für verschachtelte Zitate. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'SMILIES_LIMIT'					=> 'Maximale Smilies pro Beitrag',
	'SMILIES_LIMIT_EXPLAIN'			=> 'Die maximale Anzahl an Smilies in einem Beitrag. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'SMILIES_PER_PAGE'				=> 'Smilies pro Seite',
	'TOPICS_PER_PAGE'				=> 'Themen pro Seite',
));

// Signature Settings
$lang = array_merge($lang, array(
	'ACP_SIGNATURE_SETTINGS_EXPLAIN'	=> 'Hier können Sie alle Standard-Einstellungen für Signaturen vornehmen.',

	'MAX_SIG_FONT_SIZE'				=> 'Maximale Schriftgröße',
	'MAX_SIG_FONT_SIZE_EXPLAIN'		=> 'Die maximal erlaubte Schriftgröße, die ein Benutzer für seine Signatur verwenden kann. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_SIG_IMG_HEIGHT'			=> 'Maximale Bild-Höhe',
	'MAX_SIG_IMG_HEIGHT_EXPLAIN'	=> 'Die maximal erlaubte Höhe einer Bild- oder Flash-Datei in der Signatur. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_SIG_IMG_WIDTH'				=> 'Maximale Bild-Breite',
	'MAX_SIG_IMG_WIDTH_EXPLAIN'		=> 'Die maximal erlaubte Breite einer Bild- oder Flash-Datei in der Benutzer-Signatur. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_SIG_LENGTH'				=> 'Maximale Länge',
	'MAX_SIG_LENGTH_EXPLAIN'		=> 'Die maximal erlaubte Anzahl an Zeichen in der Signatur.',
	'MAX_SIG_SMILIES'				=> 'Maximale Smilies',
	'MAX_SIG_SMILIES_EXPLAIN'		=> 'Die maximal erlaubte Anzahl an Smilies in der Signatur. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'MAX_SIG_URLS'					=> 'Maximale Links',
	'MAX_SIG_URLS_EXPLAIN'			=> 'Die maximal erlaubte Anzahl der Links in der Signatur. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
));

// Registration Settings
$lang = array_merge($lang, array(
	'ACP_REGISTER_SETTINGS_EXPLAIN'		=> 'Hier können Sie Einstellungen bezüglich der Registrierung und der Mitgliederprofile vornehmen.',

	'ACC_ACTIVATION'				=> 'Benutzerkonto-Aktivierung',
	'ACC_ACTIVATION_EXPLAIN'		=> 'Diese Einstellung legt fest, ob Benutzer sofortigen Zugang zum Board haben, oder ob eine Bestätigung erforderlich ist. Sie können neue Registrierungen auch komplett deaktivieren. Die E-Mail-Funktionalität des Boards muss aktiviert sein, damit eine Aktivierung durch den Benutzer oder einen Administrator möglich ist.',
	'NEW_MEMBER_POST_LIMIT'			=> 'Grenze für kürzlich registrierte Benutzer',
	'NEW_MEMBER_POST_LIMIT_EXPLAIN'	=> 'Jeder neu registrierte Benutzer ist Mitglied der Gruppe „Kürzlich registrierte Benutzer“, bis er diese Zahl von Beiträgen erreicht hat. Sie können diese Gruppe nutzen, um für sie die Nutzung von Privaten Nachrichten zu unterbinden oder um eine Freigabe ihrer Beiträge erforderlich zu machen. <strong>Ein Wert von 0 deaktiviert diese Funktion.</strong>',
	'NEW_MEMBER_GROUP_DEFAULT'		=> 'Kürzlich registrierte Benutzer-Gruppe als Standard setzen',
	'NEW_MEMBER_GROUP_DEFAULT_EXPLAIN'	=> 'Wenn diese Funktion aktiviert und eine Grenze für kürzlich registrierte Benutzer gesetzt ist, werden neue Benutzer nicht nur in die <em>Kürzlich registrierte Benutzer</em>-Gruppe aufgenommen, sondern diese ist zugleich ihre Standardgruppe. Diese Funktion ist hilfreich, wenn Sie einen Rang oder einen Avatar für die Gruppe festlegen wollen, die dann für den Benutzer übernommen werden.',

	'ACC_ADMIN'					=> 'Durch einen Administrator',
	'ACC_DISABLE'				=> 'Registrierung deaktivieren',
	'ACC_NONE'					=> 'Keine Aktivierung (direkter Zugang ohne Prüfung)',
	'ACC_USER'					=> 'Durch den Benutzer (Verifizierung der E-Mail-Adresse)',
//	'ACC_USER_ADMIN'			=> 'User + Admin',
	'ALLOW_EMAIL_REUSE'			=> 'Mehrfachnutzung der E-Mail-Adresse erlauben',
	'ALLOW_EMAIL_REUSE_EXPLAIN'	=> 'Mehrere Benutzer können sich mit derselben E-Mail-Adresse registrieren.',
	'COPPA'						=> 'COPPA',
	'COPPA_FAX'					=> 'COPPA-Fax-Nummer',
	'COPPA_MAIL'				=> 'COPPA-Post-Adresse',
	'COPPA_MAIL_EXPLAIN'		=> 'Dies ist die Adresse, zu der Eltern die COPPA-Registrierungsformulare senden können.',
	'ENABLE_COPPA'				=> 'COPPA aktivieren',
	'ENABLE_COPPA_EXPLAIN'		=> 'Dadurch müssen Benutzer erklären, ob sie 13 Jahre oder älter sind, um dem amerikanischen COPPA nachzukommen. Wenn diese Einstellung deaktiviert ist, werden die COPPA-spezifischen Gruppen nicht angezeigt.',
	'MAX_CHARS'					=> 'Max.',
	'MIN_CHARS'					=> 'Min.',
	'NO_AUTH_PLUGIN'			=> 'Keine passende Authentifizierungs-Methode gefunden.',
	'PASSWORD_LENGTH'			=> 'Passwortlänge',
	'PASSWORD_LENGTH_EXPLAIN'	=> 'Die minimale und maximale Anzahl an Zeichen in Passwörtern.',
	'REG_LIMIT'					=> 'Registrierungs-Versuche',
	'REG_LIMIT_EXPLAIN'			=> 'Die Zahl der Versuche, die ein Benutzer für die Lösung der Anti-Spam-Bot-Aufgabe hat, bevor er für die Sitzung gesperrt wird.',
	'USERNAME_ALPHA_ONLY'		=> 'Nur alphanumerische Zeichen',
	'USERNAME_ALPHA_SPACERS'	=> 'Alphanumerische Zeichen und Füllzeichen',
	'USERNAME_ASCII'			=> 'ASCII (keine internationalen Unicode-Zeichen)',
	'USERNAME_LETTER_NUM'		=> 'Alle Buchstaben und Ziffern',
	'USERNAME_LETTER_NUM_SPACERS'	=> 'Alle Buchstaben, Ziffern und Füllzeichen',
	'USERNAME_CHARS'			=> 'Erlaubte Zeichen in Benutzernamen',
	'USERNAME_CHARS_ANY'		=> 'Alle Zeichen',
	'USERNAME_CHARS_EXPLAIN'	=> 'Legt fest, welche Zeichen in Benutzernamen genutzt werden können. Füllzeichen sind: Leerzeichen, -, +, _, [ und ].',
	'USERNAME_LENGTH'			=> 'Länge des Benutzernamens',
	'USERNAME_LENGTH_EXPLAIN'	=> 'Die minimale und maximale Anzahl an Zeichen in Benutzernamen.',
));

// Feeds
$lang = array_merge($lang, array(
	'ACP_FEED_MANAGEMENT'				=> 'Allgemeine Feed-Einstellungen',
	'ACP_FEED_MANAGEMENT_EXPLAIN'		=> 'Dieses Modul stellt verschiedene ATOM-Feeds zur Verfügung. Es wandelt BBCode um, so dass er in externen Feeds dargestellt werden kann.',

	'ACP_FEED_GENERAL'					=> 'Allgemeine Feed-Einstellungen',
	'ACP_FEED_POST_BASED'				=> 'Beitragsbezogene Feed-Einstellungen',
	'ACP_FEED_TOPIC_BASED'				=> 'Themenbezogene Feed-Einstellungen',
	'ACP_FEED_SETTINGS_OTHER'			=> 'Weitere Feed-Einstellungen',

	'ACP_FEED_ENABLE'					=> 'Feeds aktivieren',
	'ACP_FEED_ENABLE_EXPLAIN'			=> 'Aktiviert oder deaktiviert ATOM-Feeds für das ganze Board.<br />Eine Deaktivierung schaltet alle Feeds unabhängig der folgenden Einstellungen ab.',
	'ACP_FEED_LIMIT'					=> 'Anzahl von Elementen',
	'ACP_FEED_LIMIT_EXPLAIN'			=> 'Die maximale Anzahl von Elementen eines Feeds, die angezeigt werden.',

	'ACP_FEED_OVERALL'					=> 'Board-Feed',
	'ACP_FEED_OVERALL_EXPLAIN'			=> 'Neue Beiträge des gesamten Boards.',
	'ACP_FEED_FORUM'					=> 'Forenspezifische Feeds aktivieren',
	'ACP_FEED_FORUM_EXPLAIN'			=> 'Neue Beiträge eines einzelnen Forums und Unterforen.',
	'ACP_FEED_TOPIC'					=> 'Themenspezifische Feeds aktivieren',
	'ACP_FEED_TOPIC_EXPLAIN'			=> 'Neue Beiträge eines Themas.',

	'ACP_FEED_TOPICS_NEW'				=> 'Neue Themen-Feed',
	'ACP_FEED_TOPICS_NEW_EXPLAIN'		=> 'Aktiviert den „Neue Themen“-Feed, der die zuletzt erstellten Themen und deren ersten Beitrag anzeigt.',
	'ACP_FEED_TOPICS_ACTIVE'			=> 'Aktive Themen-Feed',
	'ACP_FEED_TOPICS_ACTIVE_EXPLAIN'	=> 'Aktiviert den „Aktive Themen“-Feed, der die zuletzt aktiven Themen und deren letzten Beitrag anzeigt.',
	'ACP_FEED_NEWS'						=> 'News-Feed',
	'ACP_FEED_NEWS_EXPLAIN'				=> 'Gibt den ersten Beitrag aus diesen Foren aus. Wählen Sie keine Foren aus, um den News-Feed zu deaktivieren.<br />Wählen Sie mehrere Foren aus/ab, indem Sie beim Klicken die <samp>Strg</samp>-Taste drücken.',

	'ACP_FEED_OVERALL_FORUMS'			=> 'Foren-Feed aktivieren',
	'ACP_FEED_OVERALL_FORUMS_EXPLAIN'	=> 'Dieser Feed zeigt eine Liste aller Foren des Boards an.',

	'ACP_FEED_HTTP_AUTH'				=> 'HTTP-Authentifizierung erlauben',
	'ACP_FEED_HTTP_AUTH_EXPLAIN'		=> 'Aktiviert die HTTP-Authentifizierung. Dadurch können Benutzer Inhalte empfangen, die für Gäste nicht sichtbar sind. Um die Funktion zu nutzen, muss der Parameter <samp>auth=http</samp> der URL des Feeds hinzugefügt werden. Beachten Sie bitte, dass bei manchen PHP-Konfigurationen eine Anpassung der .htaccess-Datei notwendig ist. Entsprechende Hinweise sind in der Datei enthalten.',
	'ACP_FEED_ITEM_STATISTICS'			=> 'Element-Statistiken',
	'ACP_FEED_ITEM_STATISTICS_EXPLAIN'	=> 'Zeigt individuelle Statistiken unterhalb der Feed-Elemente an<br />(Ersteller, Datum und Uhrzeit, Antworten, Zugriffe)',
	'ACP_FEED_EXCLUDE_ID'				=> 'Foren ausschließen',
	'ACP_FEED_EXCLUDE_ID_EXPLAIN'		=> 'Inhalte dieser Foren werden <strong>nicht in den Feeds berücksichtigt</strong>. Wählen Sie keine Foren aus, um die Daten aller Foren auszugeben.<br />Wählen Sie mehrere Foren aus/ab, indem Sie beim Klicken die <samp>Strg</samp>-Taste drücken.',
));

// Visual Confirmation Settings
$lang = array_merge($lang, array(
	'ACP_VC_SETTINGS_EXPLAIN'				=> 'Hier können Sie Plugins auswählen und konfigurieren, die das automatisierte Versenden von Formularen durch Spam-Bots unterbinden sollen. Diese Plugins zeigen dem Benutzer normalerweise einen <em>CAPTCHA</em>-Test an, der für einen Computer nur schwer zu lösen ist.',
	'AVAILABLE_CAPTCHAS'					=> 'Verfügbare Plugins',
	'CAPTCHA_UNAVAILABLE'					=> 'Das Plugin kann nicht ausgewählt werden, da seine Voraussetzungen nicht erfüllt werden.',
	'CAPTCHA_GD'							=> 'GD-Grafik',
	'CAPTCHA_GD_3D'							=> 'GD 3D-Grafik',
	'CAPTCHA_GD_FOREGROUND_NOISE'			=> 'Vordergrund-Rauschen',
	'CAPTCHA_GD_EXPLAIN'					=> 'Verwendet die GD-Library, um komplexere Grafiken erstellen zu können.',
	'CAPTCHA_GD_FOREGROUND_NOISE_EXPLAIN'	=> 'Fügt den Grafiken ein Vordergrund-Rauschen hinzu, um eine automatisierte Erkennung zu erschweren.',
	'CAPTCHA_GD_X_GRID'						=> 'Hintergrund-Rauschen x-Achse',
	'CAPTCHA_GD_X_GRID_EXPLAIN'				=> 'Verwenden Sie einen niedrigeren Wert, um die Lösung der Grafik schwieriger zu machen. 0 deaktiviert das Hintergrund-Rauschen auf der x-Achse.',
	'CAPTCHA_GD_Y_GRID'						=> 'Hintergrund-Rauschen Y-Achse',
	'CAPTCHA_GD_Y_GRID_EXPLAIN'				=> 'Verwenden Sie einen niedrigeren Wert, um die Lösung der Grafik schwieriger zu machen. 0 deaktiviert das Hintergrund-Rauschen auf der y-Achse.',
	'CAPTCHA_GD_WAVE'						=> 'Wellen-Verzerrung',
	'CAPTCHA_GD_WAVE_EXPLAIN'				=> 'Fügt der Grafik eine Wellen-Verzerrung hinzu.',
	'CAPTCHA_GD_3D_NOISE'					=> '3D-Rauschen hinzufügen',
	'CAPTCHA_GD_3D_NOISE_EXPLAIN'			=> 'Fügt den Grafiken zusätzliche Objekte hinzu.',
	'CAPTCHA_GD_FONTS'						=> 'Unterschiedliche Schriften nutzen',
	'CAPTCHA_GD_FONTS_EXPLAIN'				=> 'Diese Einstellung legt fest, wie viele verschiedene Schriftformen genutzt werden. Sie können nur die Standard-Formen nutzen oder neue Formen aktivieren. Es können auch Kleinbuchstaben hinzugefügt werden.',
	'CAPTCHA_FONT_DEFAULT'					=> 'Standard',
	'CAPTCHA_FONT_NEW'						=> 'Neue Formen',
	'CAPTCHA_FONT_LOWER'					=> 'Auch Kleinbuchstaben',
	'CAPTCHA_NO_GD'							=> 'Einfache Grafik',
	'CAPTCHA_PREVIEW_MSG'					=> 'Ihre Änderungen wurden nicht gespeichert. Dies ist nur eine Vorschau.',
	'CAPTCHA_PREVIEW_EXPLAIN'				=> 'So würde die Anzeige des Plugins mit den aktuellen Einstellungen aussehen.',

	'CAPTCHA_SELECT'						=> 'Installierte Plugins',
	'CAPTCHA_SELECT_EXPLAIN'				=> 'Die Liste enthält die Plugins, die vom Board gefunden wurden. Ausgegraute Elemente stehen derzeit nicht zur Verfügung und müssen ggf. erst konfiguriert werden, bevor sie genutzt werden können.',
	'CAPTCHA_CONFIGURE'						=> 'Plugins konfigurieren',
	'CAPTCHA_CONFIGURE_EXPLAIN'				=> 'Ändert die Einstellungen für das ausgewählte Plugin.',
	'CONFIGURE'								=> 'Konfigurieren',
	'CAPTCHA_NO_OPTIONS'					=> 'Dieses Plugin hat keine Konfigurations-Optionen.',

	'VISUAL_CONFIRM_POST'					=> 'Spam-Bot-Schutz für Beiträge von Gästen aktivieren',
	'VISUAL_CONFIRM_POST_EXPLAIN'			=> 'Gäste müssen eine Anti-Spam-Bot-Aufgabe beim Schreiben von Beiträgen lösen. Dadurch sollen Massenbeiträge (Spam) unterbunden werden.',
	'VISUAL_CONFIRM_REG'					=> 'Spam-Bot-Schutz für Registrierungen aktivieren',
	'VISUAL_CONFIRM_REG_EXPLAIN'			=> 'Neue Benutzer müssen eine Anti-Spam-Bot-Aufgabe bei der Registrierung lösen. Dadurch sollen Massenregistrierungen unterbunden werden.',
	'VISUAL_CONFIRM_REFRESH'				=> 'Benutzer Austausch der Anti-Spam-Bot-Aufgabe erlauben',
	'VISUAL_CONFIRM_REFRESH_EXPLAIN'		=> 'Erlaubt den Benutzern, eine neue Anti-Spam-Bot-Aufgabe anzufordern, wenn sie sie bei der Registrierung nicht lösen können. Nicht alle Plugins unterstützen diese Option.',
));

// Cookie Settings
$lang = array_merge($lang, array(
	'ACP_COOKIE_SETTINGS_EXPLAIN'		=> 'Hier legen Sie die Einstellungen fest, die verwendet werden, um Cookies an die Browser Ihrer Benutzer zu senden. In den meisten Fällen sollten die Standardwerte ausreichend sein. Führe Änderungen mit Bedacht durch, falsche Einstellungen könnten Ihre Benutzer daran hindern, sich anzumelden.',

	'COOKIE_DOMAIN'				=> 'Cookie-Domain',
	'COOKIE_NAME'				=> 'Cookie-Name',
	'COOKIE_PATH'				=> 'Cookie-Pfad',
	'COOKIE_SECURE'				=> 'Sicherer Server',
	'COOKIE_SECURE_EXPLAIN'		=> 'Falls Ihr Server über SSL läuft, aktivieren Sie diese Option, ansonsten lassen Sie sie deaktiviert. Wenn diese Option aktiviert ist, obwohl der Server nicht über SSL aufgerufen wird, können Fehler bei der Weiterleitung auftreten.',
	'ONLINE_LENGTH'				=> 'Zeitspanne für die Online-Anzeige',
	'ONLINE_LENGTH_EXPLAIN'		=> 'Die Zeit in Minuten, nach der inaktive Benutzer nicht mehr in der „Wer ist online“-Anzeige erscheinen. Je größer dieser Wert ist, desto größer ist die Rechenleistung, die zur Erstellung dieser Liste benötigt wird.',
	'SESSION_LENGTH'			=> 'Sitzungslänge',
	'SESSION_LENGTH_EXPLAIN'	=> 'Die Zeit in Sekunden, nach der Sitzungen ungültig werden.',
));

// Load Settings
$lang = array_merge($lang, array(
	'ACP_LOAD_SETTINGS_EXPLAIN'	=> 'Hier können Sie einige Board-Funktionen aktivieren und deaktivieren, um die beanspruchte Rechenleistung zu verringern. Auf den meisten Servern ist es allerdings nicht nötig, irgendeine Funktion zu deaktivieren. Andererseits kann es auf einigen Systemen oder auf Servern, die man sich mit anderen teilt, durchaus Vorteile bringen, wenn Funktionen abgeschaltet werden, die nicht wirklich benötigt werden. Sie können hier auch Limits für die Systemauslastung und für die aktiven Sitzungen festlegen, bei deren Überschreitung das Board offline geht.',

	'CUSTOM_PROFILE_FIELDS'			=> 'Zusätzliche Profil-Felder',
	'LIMIT_LOAD'					=> 'Schränke Systemauslastung ein',
	'LIMIT_LOAD_EXPLAIN'			=> 'Wenn die durchschnittliche Systemauslastung der letzten Minute (load average) diesen Wert überschreitet, geht das Board automatisch offline. 1.0 steht für eine ca. 100-prozentige Auslastung eines Prozessors. Diese Einstellung steht nur auf System zur Verfügung, die auf UNIX basieren und bei denen dieser Wert zugänglich ist. Der Wert stellt sich auf 0 zurück, wenn phpBB diesen Wert nicht auslesen konnte.',
	'LIMIT_SESSIONS'				=> 'Schränke Sitzungen ein',
	'LIMIT_SESSIONS_EXPLAIN'		=> 'Wenn die Zahl der Sitzungen innerhalb einer Minute diesen Wert überschreitet, geht das Board offline. Um keine Begrenzung festzulegen, stellen Sie als Wert 0 ein.',
	'LOAD_CPF_MEMBERLIST'			=> 'Erlaubt Styles, zusätzliche Profil-Felder in der Mitgliederliste anzuzeigen',
	'LOAD_CPF_VIEWPROFILE'			=> 'Zusätzliche Profil-Felder in Mitgliederprofilen anzeigen',
	'LOAD_CPF_VIEWTOPIC'			=> 'Zusätzliche Profil-Felder in der Themen-Ansicht anzeigen',
	'LOAD_USER_ACTIVITY'			=> 'Aktivität der Mitglieder anzeigen',
	'LOAD_USER_ACTIVITY_EXPLAIN'	=> 'Zeigt im Profil und im persönlichen Bereich an, in welchen Foren und Themen ein Mitglied am aktivsten ist. Es wird empfohlen, diese Funktion in Foren zu deaktivieren, die mehr als eine Million Beiträge haben.',
	'RECOMPILE_STYLES'				=> 'Rekompilieren veralteter Style-Komponenten',
	'RECOMPILE_STYLES_EXPLAIN'		=> 'Prüft auf neue Style-Komponenten und rekompiliert diese.',
	'YES_ANON_READ_MARKING'			=> 'Gelesen-Markierung für Gäste',
	'YES_ANON_READ_MARKING_EXPLAIN'	=> 'Speichert auch für Gäste, ob ein Thema gelesen oder ungelesen ist. Wenn diese Option deaktiviert ist, erscheinen Beiträge für Gäste immer als gelesen.',
	'YES_BIRTHDAYS'					=> 'Anzeige der Geburtstage aktivieren',
	'YES_BIRTHDAYS_EXPLAIN'			=> 'Wenn deaktiviert, wird die Liste der Geburtstage nicht länger angezeigt. Um diese Funktion zu aktivieren, muss die Geburtstagsfunktion ebenfalls aktiviert werden.',
	'YES_JUMPBOX'					=> 'Anzeige der Jumpbox aktivieren',
	'YES_MODERATORS'				=> 'Anzeige der Moderatoren aktivieren',
	'YES_ONLINE'					=> 'Online-Anzeige der Mitglieder aktivieren',
	'YES_ONLINE_EXPLAIN'			=> 'Zeigt in der Foren-Übersicht, in den Foren und den Themen an, welches Mitglied online ist.',
	'YES_ONLINE_GUESTS'				=> 'Online-Anzeige der Gäste aktivieren',
	'YES_ONLINE_GUESTS_EXPLAIN'		=> 'Zeigt Informationen zu Gästen in „Wer ist online“ an.',
	'YES_ONLINE_TRACK'				=> 'Anzeige des Online-/Offline-Symbols aktivieren',
	'YES_ONLINE_TRACK_EXPLAIN'		=> 'Zeigt im Profil und der Themen-Ansicht den Online-Status des Mitglieds an.',
	'YES_POST_MARKING'				=> 'Themen-Markierung aktivieren',
	'YES_POST_MARKING_EXPLAIN'		=> 'Zeigt an, ob ein Benutzer in einem Thema schon einen Beitrag erstellt hat.',
	'YES_READ_MARKING'				=> 'Serverseitige Gelesen-Markierung aktivieren',
	'YES_READ_MARKING_EXPLAIN'		=> 'Speichert Informationen zu gelesenen/ungelesenen Beiträgen in der Datenbank statt im Cookie.',
	'YES_UNREAD_SEARCH'				=> 'Aktiviert die Suche nach ungelesenen Beiträgen',
));

// Auth settings
$lang = array_merge($lang, array(
	'ACP_AUTH_SETTINGS_EXPLAIN'	=> 'phpBB unterstützt Authentifizierungs-Plugins oder -Module. Mit diesen können Sie festlegen, wie Benutzer authentifiziert werden, wenn sie sich im Forum anmelden. Standardmäßig gibt es drei Plugins: DB, LDAP und Apache. Nicht alle Methoden benötigen zusätzliche Angaben, füllen Sie daher nur Felder aus, wenn sie für die gewählte Methode von Belang sind.',

	'AUTH_METHOD'				=> 'Authentifizierungs-Methode wählen',

	'APACHE_SETUP_BEFORE_USE'	=> 'Sie müssen die Apache-Authentifizierung konfigurieren, bevor diese Methode in phpBB eingestellt wird. Beachten Sie, dass der Benutzername der Apache-Authentifizierung Ihrem phpBB-Benutzernamen entsprechen muss. Die Apache-Authentifizierung kann nur mit mod_php (nicht mit der CGI-Version) und deaktiviertem safe_mode verwendet werden.',

	'LDAP_DN'						=> 'LDAP-Basis <var>DN</var>',
	'LDAP_DN_EXPLAIN'				=> 'Distinguished Name des Verzeichnisses, in dem sich die Benutzer-Daten befinden, z.&nbsp;B. <samp>o=Meine&nbsp;Firma,c=DE</samp>.',
	'LDAP_EMAIL'					=> 'LDAP-E-Mail-Attribut',
	'LDAP_EMAIL_EXPLAIN'			=> 'Geben Sie hier den Namen des E-Mail-Attributes (falls existent) ein, um die E-Mail-Adresse für neue Benutzer automatisch zu setzen. Wenn dieses Feld freigelassen wird, ist bei Benutzern, die sich zum ersten Mal anmelden, keine E-Mail-Adresse gesetzt.',
	'LDAP_INCORRECT_USER_PASSWORD'	=> 'Die Verbindung zum LDAP-Server mit der angegebenen Benutzernamen und Passwort ist gescheitert.',
	'LDAP_NO_EMAIL'					=> 'Das angegebene E-Mail-Attribut existiert nicht.',
	'LDAP_NO_IDENTITY'				=> 'Kann keine Anmeldekennung für %s finden.',
	'LDAP_PASSWORD'					=> 'LDAP-Passwort',
	'LDAP_PASSWORD_EXPLAIN'			=> 'Lassen Sie das Feld für eine anonyme Verbindung frei; ansonsten geben Sie das Passwort für obigen Benutzer an. Erforderlich bei Active Directory-Servern.<br /><em><strong>WARNUNG:</strong> Dieses Passwort wird im Klartext in der Datenbank gespeichert und ist daher für jeden einsehbar, der Zugriff auf die Datenbank oder diese Konfigurationsseite hat.</em>',
	'LDAP_PORT'						=> 'Port des LDAP-Servers',
	'LDAP_PORT_EXPLAIN'				=> 'Sie können optional einen Port angeben, der statt dem Standardport 389 für die Verbindung zum LDAP-Server verwendet werden soll.',
	'LDAP_SERVER'					=> 'LDAP-Server-Name',
	'LDAP_SERVER_EXPLAIN'			=> 'Wenn LDAP genutzt wird, ist dies der Servername oder die IP-Adresse des LDAP-Servers. Alternativ können Sie eine URL der Form <samp>ldap://hostname:port/</samp> angeben.',
	'LDAP_UID'						=> 'LDAP <var>uid</var>',
	'LDAP_UID_EXPLAIN'				=> 'Attribut, unter dem nach einem angegebenen Benutzernamen gesucht werden soll, z.&nbsp;B. <var>uid</var>, <var>sn</var> usw.',
	'LDAP_USER'						=> 'LDAP-Benutzer <var>dn</var>',
	'LDAP_USER_EXPLAIN'				=> 'Lassen Sie das Feld für eine anonyme Verbindung frei. Wenn ausgefüllt, wird phpBB den angegebenen Benutzer dazu verwenden, um sich für die Suche nach dem passenden Benutzer wie <samp>uid=Benutzername,ou=Organisationseinheit,o=Firma,c=DE</samp> anzumelden. Erforderlich bei Active Directory-Servern.',
	'LDAP_USER_FILTER'				=> 'LDAP Benutzer-Filter',
	'LDAP_USER_FILTER_EXPLAIN'		=> 'Sie können optional die durchsuchten Objekte durch weitere Filter einschränken. Zum Beispiel führt <samp>objectClass=posixGruppe</samp> zur Benutzung von <samp>(&amp;(uid=$username)(objectClass=posixGruppe))</samp>.',
));

// Server Settings
$lang = array_merge($lang, array(
	'ACP_SERVER_SETTINGS_EXPLAIN'	=> 'Hier können Sie einige Einstellungen bezüglich Server und Domain vornehmen. Bitte stellen Sie sicher, dass die Daten, die Sie eingeben, auch wirklich stimmen, denn fehlerhafte Angaben könnten zu E-Mails führen, die falsche Informationen enthalten. Wenn Sie den Domain-Namen eingeben, denken Sie daran, dass http:// oder eine andere Protokoll-Bezeichnung darin enthalten ist. Ändern Sie den Port nur, wenn Sie wissen, dass Ihr Server einen anderen Port nutzt; Port 80 ist in den allermeisten Fällen richtig.',

	'ENABLE_GZIP'				=> 'gzip-Komprimierung aktivieren',
	'ENABLE_GZIP_EXPLAIN'		=> 'Der Seiteninhalt wird vor dem Senden an den Benutzer komprimiert. Dies kann den Netzverkehr reduzieren, wird aber auch zu einer Erhöhung der CPU-Last sowohl auf Server- als auch auf Benutzerseite führen. Erfordert, dass die zlib-Erweiterung von PHP geladen ist.',
	'FORCE_SERVER_VARS'			=> 'Erzwinge Server-URL-Einstellungen',
	'FORCE_SERVER_VARS_EXPLAIN'	=> 'Wenn dies auf „Ja“ gestellt wird, werden die hier vorgenommenen Server-Einstellungen anstelle der automatisch ermittelten Werte genommen.',
	'ICONS_PATH'				=> 'Speicherpfad für Themen-Symbole',
	'ICONS_PATH_EXPLAIN'		=> 'Pfad von Ihrem phpBB-Hauptverzeichnis aus, z.&nbsp;B. <samp>images/icons</samp>.',
	'PATH_SETTINGS'				=> 'Pfad-Einstellungen',
	'RANKS_PATH'				=> 'Speicherpfad für Rang-Bilder',
	'RANKS_PATH_EXPLAIN'		=> 'Pfad von Ihrem phpBB-Hauptverzeichnis aus, z.&nbsp;B. <samp>images/ranks</samp>.',
	'SCRIPT_PATH'				=> 'Scriptpfad',
	'SCRIPT_PATH_EXPLAIN'		=> 'Der Pfad in dem sich phpBB befindet, relativ zum Domainnamen; z.&nbsp;B. <samp>/phpBB3</samp>.',
	'SERVER_NAME'				=> 'Domain-Name',
	'SERVER_NAME_EXPLAIN'		=> 'Die Domain, auf der das Board läuft (bspw. <samp>www.phpbb.de</samp>).',
	'SERVER_PORT'				=> 'Server-Port',
	'SERVER_PORT_EXPLAIN'		=> 'Der Port, auf dem der Server läuft, für gewöhnlich 80. Ändern Sie den Wert nur, wenn er sich davon unterscheidet.',
	'SERVER_PROTOCOL'			=> 'Server-Protokoll',
	'SERVER_PROTOCOL_EXPLAIN'	=> 'Dies wird als Server-Protokoll verwendet, wenn diese Einstellungen erzwungen werden. Ansonsten, oder wenn dieses Feld leer ist, werden die Einstellungen für „Sicherer Server“ aus den Cookie-Einstellungen genommen (<samp>http://</samp> oder <samp>https://</samp>).',
	'SERVER_URL_SETTINGS'		=> 'Server URL-Einstellungen',
	'SMILIES_PATH'				=> 'Speicherpfad für Smilies',
	'SMILIES_PATH_EXPLAIN'		=> 'Pfad von Ihrem phpBB-Hauptverzeichnis aus, z.&nbsp;B. <samp>images/smilies</samp>.',
	'UPLOAD_ICONS_PATH'			=> 'Speicherpfad der Dateityp-Gruppen-Symbole',
	'UPLOAD_ICONS_PATH_EXPLAIN'	=> 'Pfad von Ihrem phpBB-Hauptverzeichnis aus, z.&nbsp;B. <samp>images/upload_icons</samp>.',
));

// Security Settings
$lang = array_merge($lang, array(
	'ACP_SECURITY_SETTINGS_EXPLAIN'		=> 'Hier können die Einstellungen zu Sitzungen und zur Anmeldung festgelegt werden.',

	'ALL'							=> 'Alle',
	'ALLOW_AUTOLOGIN'				=> 'Dauerhafte Anmeldung erlauben',
	'ALLOW_AUTOLOGIN_EXPLAIN'		=> 'Legt fest, ob Benutzer sich automatisch bei jedem Besuch des Boards anmelden können.',
	'AUTOLOGIN_LENGTH'				=> 'Verfallszeit für Anmelde-Schlüssel',
	'AUTOLOGIN_LENGTH_EXPLAIN'		=> 'Die Anzahl der Tage, nach denen ein Anmelde-Schlüssel für die automatische Anmeldung verfällt. Um den Schlüssel nicht verfallen zu lassen, stellen Sie als Wert 0 ein.',
	'BROWSER_VALID'					=> 'Browser prüfen',
	'BROWSER_VALID_EXPLAIN'			=> 'Aktiviert die Prüfung des Browsers für die jeweilige Sitzung, um die Sicherheit zu erhöhen.',
	'CHECK_DNSBL'					=> 'IP gegen Schwarze DNS-Liste prüfen',
	'CHECK_DNSBL_EXPLAIN'			=> 'Wenn aktiviert, wird die IP-Adresse des Benutzers bei der Registrierung und bei der Beitragserstellung gegen folgende DNSBL-Dienste geprüft: <a href="http://spamcop.net">spamcop.net</a> und <a href="http://www.spamhaus.org">www.spamhaus.org</a>. Diese Prüfung kann, abhängig von der Serverkonfiguration, etwas Zeit in Anspruch nehmen. Wenn Verzögerungen oder zu viele falsche Ablehnungen beobachtet werden, sollte diese Prüfung deaktiviert werden.',
	'CLASS_B'						=> 'A.B',
	'CLASS_C'						=> 'A.B.C',
	'EMAIL_CHECK_MX'				=> 'E-Mail-Domain auf gültigen MX-Eintrag prüfen',
	'EMAIL_CHECK_MX_EXPLAIN'		=> 'Wenn aktiviert, wird die Domain der E-Mail-Adresse bei der Registrierung und der Änderung des Profils auf einen gültigen MX-Eintrag geprüft.',
	'FORCE_PASS_CHANGE'				=> 'Passwortänderung erzwingen',
	'FORCE_PASS_CHANGE_EXPLAIN'		=> 'Verlangt von den Benutzern, ihr Passwort nach einer festgelegten Anzahl an Tagen zu erneuern. Um dieses Verhalten abzuschalten, stellen Sie als Wert 0 ein.',
	'FORM_TIME_MAX'					=> 'Maximale Zeit zur Übermittlung eines Formulars',
	'FORM_TIME_MAX_EXPLAIN'			=> 'Die Zeit, die ein Benutzer hat, um ein Formular abzusenden. Stellen Sie als Wert -1 ein, um das Verhalten abzuschalten. Beachten Sie, dass ein Formular unabhängig dieser Einstellung ungültig werden kann, wenn die Sitzung abläuft.',
	'FORM_SID_GUESTS'				=> 'Formulare an Gast-Sitzungen binden',
	'FORM_SID_GUESTS_EXPLAIN'		=> 'Wenn aktiviert, ist ein Formular bei Gästen nur für die aktuelle Sitzung gültig. Dies kann bei manchen Internet-Providern zu Problemen führen.',
	'FORWARDED_FOR_VALID'			=> '<var>X_FORWARDED_FOR</var>-Kopfzeilen prüfen',
	'FORWARDED_FOR_VALID_EXPLAIN'	=> 'Sitzungen werden nur fortgesetzt, wenn die übermittelte <var>X_FORWARDED_FOR</var>-Kopfzeile mit der der letzten Anfrage identisch ist. Die in <var>X_FORWARDED_FOR</var> angegebene Adresse wird ebenfalls auf Sperrung geprüft.',
	'IP_VALID'						=> 'Überprüfung der Sitzungs-IP',
	'IP_VALID_EXPLAIN'				=> 'Legt fest, welche Teile der IP eines Benutzers zur Validierung einer Sitzung herangezogen werden. <samp>Alle</samp> bedeutet, dass die komplette IP Adresse verglichen wird; <samp>A.B.C</samp> vergleicht die ersten drei Oktetts; <samp>A.B</samp> vergleicht die ersten zwei Oktetts; <samp>Keine</samp> deaktiviert die Prüfung. Bei IPv6-Adressen prüft <samp>A.B.C</samp> die ersten 4 Blöcke und <samp>A.B</samp> die ersten 3.',
	'IP_LOGIN_LIMIT_MAX'			=> 'Maximale Anzahl an Anmeldeversuchen pro IP-Adresse',
	'IP_LOGIN_LIMIT_MAX_EXPLAIN'	=> 'Anzahl erfolgloser Anmeldeversuche von einer IP-Adresse, nach der zusätzlich eine Anti-Spam-Bot-Aufgabe gelöst werden muss. Mit 0 wird die Prüfung erfolgloser Anmeldungen für IP-Adressen deaktiviert.',
	'IP_LOGIN_LIMIT_TIME'			=> 'Ablaufzeit für erfolglose Anmeldeversuche von einer IP-Adresse',
	'IP_LOGIN_LIMIT_TIME_EXPLAIN'	=> 'Erfolglose Anmeldeversuche verfallen nach dieser Zeit.',
	'IP_LOGIN_LIMIT_USE_FORWARDED'	=> 'Anmeldeversuche anhand <var>X_FORWARDED_FOR</var>-Header prüfen',
	'IP_LOGIN_LIMIT_USE_FORWARDED_EXPLAIN'	=> 'Anstatt einer Prüfung der IP-Adressen erfolgt eine Prüfung der <var>X_FORWARDED_FOR</var>-Werte im Header.<br /><em><strong>Warnung:</strong> Diese Funktion darf nur aktiviert werden, wenn sich Ihr Board hinter einem Proxy-Server befindet, der den <var>X_FORWARDED_FOR</var>-Header vertrauenswürdig festlegt.</em>',
	'MAX_LOGIN_ATTEMPTS'			=> 'Maximale Anzahl an Anmeldeversuchen pro Benutzername',
	'MAX_LOGIN_ATTEMPTS_EXPLAIN'	=> 'Anzahl erfolgloser Anmeldeversuche für ein Benutzerkonto, nach der zusätzlich eine Anti-Spam-Bot-Aufgabe gelöst werden muss. Mit 0 wird die Prüfung erfolgloser Anmeldungen für Benutzerkonten deaktiviert.',
	'NO_IP_VALIDATION'				=> 'Keine',
	'NO_REF_VALIDATION'				=> 'Keine',
	'PASSWORD_TYPE'					=> 'Passwort-Komplexität',
	'PASSWORD_TYPE_EXPLAIN'			=> 'Legt die bei der Wahl oder Änderung eines Passworts erforderliche Komplexität fest. Nachfolgende Optionen beinhalten jeweils die darüberstehenden.',
	'PASS_TYPE_ALPHA'				=> 'Muss Buchstaben und Ziffern enthalten',
	'PASS_TYPE_ANY'					=> 'Keine Erfordernisse',
	'PASS_TYPE_CASE'				=> 'Muss Groß- und Kleinbuchstaben enthalten',
	'PASS_TYPE_SYMBOL'				=> 'Muss Sonderzeichen enthalten',
	'REF_HOST'						=> 'Prüfe nur den Hostnamen',
	'REF_PATH'						=> 'Prüfe auch den Skript-Pfad',
	'REFERER_VALID'					=> 'Referrer prüfen',
	'REFERER_VALID_EXPLAIN'			=> 'Wenn aktiviert, wird der Referrer von POST-Anfragen gegen die Einstellungen des Hostnamen/Skript-Pfads geprüft. Dies kann bei Boards zu Problemen führen, die mehrere Domains oder eine externe Anmeldung nutzen.',
	'TPL_ALLOW_PHP'					=> 'Erlaube PHP in Templates',
	'TPL_ALLOW_PHP_EXPLAIN'			=> 'Wenn diese Option eingeschaltet ist, werden <code>PHP</code>- und <code>INCLUDEPHP</code>-Anweisungen in Templates erkannt und ausgeführt.',
));

// Email Settings
$lang = array_merge($lang, array(
	'ACP_EMAIL_SETTINGS_EXPLAIN'	=> 'Diese Informationen werden benötigt, wenn das Board E-Mails an Ihre Benutzer sendet. Stellen Sie bitte sicher, dass die von Ihnen angegebene Adresse gültig ist; geblockte oder nicht zustellbare Nachrichten werden an diese Adresse geschickt. Falls Ihr Webhosting-Provider keinen PHP-basierten E-Mail Service anbietet, können Sie Ihre Nachrichten auch direkt über SMTP versenden. Dies erfordert die Angabe der Adresse eines geeigneten Servers (fragen Sie falls nötig Ihren Provider). Falls der Server eine Authentifizierung erfordert (und nur, wenn dies der Fall ist), geben Sie den Benutzernamen und das Passwort ein und wählen Sie eine Authentifizierungsmethode aus.',

	'ADMIN_EMAIL'					=> 'Antwort-E-Mail-Adresse',
	'ADMIN_EMAIL_EXPLAIN'			=> 'Diese technische Kontakt-Adresse wird als Antwort-Adresse für alle E-Mails genommen. Sie wird in allen E-Mails als <samp>Rückleitungs</samp>- und <samp>Absender</samp>-Adresse verwendet.',
	'BOARD_EMAIL_FORM'				=> 'E-Mails über das Board versenden',
	'BOARD_EMAIL_FORM_EXPLAIN'		=> 'Anstatt die E-Mail-Adresse der Benutzer anzuzeigen, können diese ihre E-Mails über das Board versenden.',
	'BOARD_HIDE_EMAILS'				=> 'E-Mail-Adressen verstecken',
	'BOARD_HIDE_EMAILS_EXPLAIN'		=> 'Diese Funktion hält E-Mail-Adressen komplett privat.',
	'CONTACT_EMAIL'					=> 'Kontakt-E-Mail-Adresse',
	'CONTACT_EMAIL_EXPLAIN'			=> 'Diese Adresse wird angegeben, wann immer eine spezifische Kontaktmöglichkeit benötigt wird, z.&nbsp;B. bei Spam, Fehlermeldungen etc. Sie wird in allen E-Mails als <samp>Von</samp>- und <samp>Antwort</samp>-Adresse verwendet.',
	'EMAIL_FUNCTION_NAME'			=> 'Name der E-Mail-Funktion',
	'EMAIL_FUNCTION_NAME_EXPLAIN'	=> 'Die PHP-Funktion, die genutzt wird, um E-Mails zu versenden.',
	'EMAIL_PACKAGE_SIZE'			=> 'Größe von E-Mail-Paketen',
	'EMAIL_PACKAGE_SIZE_EXPLAIN'	=> 'Dies ist die Anzahl der E-Mails, die maximal in einem Paket gesendet werden können. Diese Einstellung greift für die interne Nachrichten-Warteschlange; verwenden Sie 0, wenn Sie Probleme mit nicht versandten Benachrichtigungs-E-Mails haben.',
	'EMAIL_SIG'						=> 'E-Mail-Signatur',
	'EMAIL_SIG_EXPLAIN'				=> 'Dieser Text wird an alle E-Mails angehängt, die das Board versendet.',
	'ENABLE_EMAIL'					=> 'Aktiviere E-Mail-Funktionalität',
	'ENABLE_EMAIL_EXPLAIN'			=> 'Wenn dies deaktiviert ist, werden keinerlei E-Mails vom Board versendet. <em>Die Aktivierung von Benutzerkonten durch den Benutzer oder einen Administrator erfordert, dass diese Option aktiviert ist. Wenn derzeit die Aktivierung durch den Benutzer oder einen Administrator aktiviert ist, ist bei einer Deaktivierung dieser Option keine Aktivierung mehr erforderlich.</em>',
	'SMTP_AUTH_METHOD'				=> 'Authentifizierungsmethode für SMTP',
	'SMTP_AUTH_METHOD_EXPLAIN'		=> 'Nur benötigt, wenn ein Benutzername/Passwort eingegeben ist. Fragen Sie Ihren Webhosting-Provider, falls Sie nicht sicher sind, welche Methode Sie wählen sollen.',
	'SMTP_CRAM_MD5'					=> 'CRAM-MD5',
	'SMTP_DIGEST_MD5'				=> 'DIGEST-MD5',
	'SMTP_LOGIN'					=> 'LOGIN',
	'SMTP_PASSWORD'					=> 'SMTP-Passwort',
	'SMTP_PASSWORD_EXPLAIN'			=> 'Geben Sie nur ein Passwort ein, wenn Ihr SMTP-Server dies erfordert. <em><strong>WARNUNG:</strong> Dieses Passwort wird im Klartext in der Datenbank gespeichert und ist daher für jeden einsehbar, der Zugriff auf die Datenbank oder diese Konfigurationsseite hat.</em>',
	'SMTP_PLAIN'					=> 'PLAIN',
	'SMTP_POP_BEFORE_SMTP'			=> 'POP-BEFORE-SMTP',
	'SMTP_PORT'						=> 'SMTP-Server-Port',
	'SMTP_PORT_EXPLAIN'				=> 'Ändern Sie diese Einstellung nur, wenn Sie wissen, dass Ihr SMTP-Server einen anderen Port nutzt.',
	'SMTP_SERVER'					=> 'SMTP-Server-Adresse',
	'SMTP_SETTINGS'					=> 'SMTP-Einstellungen',
	'SMTP_USERNAME'					=> 'SMTP-Benutzername',
	'SMTP_USERNAME_EXPLAIN'			=> 'Geben Sie nur einen Benutzernamen ein, wenn Ihr SMTP-Server dies erfordert.',
	'USE_SMTP'						=> 'SMTP-Server für E-Mail nutzen',
	'USE_SMTP_EXPLAIN'				=> 'Wählen Sie „Ja“ aus, wenn Sie E-Mails über einen SMTP-Server senden möchten (oder müssen), anstatt die PHP-eigene Mail-Funktion zu nutzen.',
));

// Jabber settings
$lang = array_merge($lang, array(
	'ACP_JABBER_SETTINGS_EXPLAIN'	=> 'Hier können Sie die Nutzung von <a href="http://de.wikipedia.org/wiki/Jabber">Jabber</a> für Instant Messages und Benachrichtigungen des Boards aktivieren und kontrollieren. Jabber ist ein OpenSource-Protokoll und daher für jeden verfügbar. Einige Jabber-Server nutzen Gateways oder Transport-Dienste, die es Ihnen erlauben, Benutzer anderer Netzwerke zu kontaktieren. Nicht alle Server bieten alle Transport-Dienste an, und Änderungen an den Protokollen können Transport-Dienste am Funktionieren hindern. Stellen Sie sicher, dass Sie die korrekten Daten eines bereits registrierten Jabber-Kontos eingeben — phpBB verwendet die Daten so, wie sie hier eingegeben sind.',

	'JAB_ENABLE'				=> 'Jabber aktivieren',
	'JAB_ENABLE_EXPLAIN'		=> 'Aktiviert die Nutzung von Jabber-Nachrichten und -Benachrichtigungen.',
	'JAB_GTALK_NOTE'			=> 'Beachten Sie, dass GTalk nicht funktionieren wird, da die <samp>dns_get_record</samp>-Funktion nicht gefunden werden konnte. Diese Funktion ist in PHP 4 nicht verfügbar und nicht in Windows-Plattformen implementiert. Sie funktioniert derzeit nicht auf BSD-basierten Systemen inklusive Mac OS.',
	'JAB_PACKAGE_SIZE'			=> 'Jabber-Paketgröße',
	'JAB_PACKAGE_SIZE_EXPLAIN'	=> 'Dies ist die Anzahl der Nachrichten, die in einem Paket gesendet werden. Um die Nachrichten sofort zu senden, stellen Sie als Wert 0 ein.',
	'JAB_PASSWORD'				=> 'Jabber-Passwort',
	'JAB_PASSWORD_EXPLAIN'		=> '<em><strong>WARNUNG:</strong> Dieses Passwort wird im Klartext in der Datenbank gespeichert und ist daher für jeden einsehbar, der Zugriff auf die Datenbank oder diese Konfigurationsseite hat.</em>',
	'JAB_PORT'					=> 'Jabber-Port',
	'JAB_PORT_EXPLAIN'			=> 'Lassen Sie dieses Feld frei, es sei denn, Sie wissen, dass es nicht Port 5222 ist.',
	'JAB_SERVER'				=> 'Jabber-Server',
	'JAB_SERVER_EXPLAIN'		=> 'Siehe %sjabber.org%s für eine Liste an Servern.',
	'JAB_SETTINGS_CHANGED'		=> 'Jabber-Einstellungen erfolgreich geändert.',
	'JAB_USE_SSL'				=> 'Mit SSL verbinden',
	'JAB_USE_SSL_EXPLAIN'		=> 'Wenn aktiviert, wird versucht, eine sichere Verbindung zu verwenden. Der Jabber-Port wird auf 5223 geändert, sofern Port 5222 angegeben ist.',
	'JAB_USERNAME'				=> 'Jabber-Benutzername oder JID',
	'JAB_USERNAME_EXPLAIN'		=> 'Geben Sie einen bereits registrierten Benutzernamen oder eine gültige JID an. Der Benutzername wird nicht auf Gültigkeit geprüft. Wenn Sie nur einen Benutzernamen angeben, wird die JID aus dem Benutzernamen und dem oben festgelegten Server ermittelt. Geben Sie ansonsten eine gültige JID wie <samp>user@jabber.org</samp> ein.',
));

?>
