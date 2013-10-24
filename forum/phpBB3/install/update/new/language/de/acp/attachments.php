<?php
/**
*
* acp_attachments [Deutsch — Du]
*
* @package language
* @version $Id: attachments.php 617 2013-09-29 10:21:18Z pyramide $
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
	'ACP_ATTACHMENT_SETTINGS_EXPLAIN'	=> 'Hier kannst du die Einstellungen für Dateianhänge und verknüpfte Spezialkategorien vornehmen.',
	'ACP_EXTENSION_GROUPS_EXPLAIN'		=> 'Hier kannst du Dateityp-Gruppen hinzufügen, ändern, entfernen, deaktivieren. Du kannst ihnen auch eine Spezialkategorie zuweisen, den Download-Modus ändern oder auch ein Symbol festlegen, welches dann vor jedem zur Gruppe gehörenden Dateianhang angezeigt wird.',
	'ACP_MANAGE_EXTENSIONS_EXPLAIN'		=> 'Hier kannst du die erlaubten Dateitypen einstellen. Die Erweiterungen können bei den Dateityp-Gruppen-Einstellungen aktiviert werden. Es wird dringend empfohlen, Script-Dateierweiterungen (wie <code>php</code>, <code>php3</code>, <code>php4</code>, <code>phtml</code>, <code>pl</code>, <code>cgi</code>, <code>py</code>, <code>rb</code>, <code>asp</code>, <code>aspx</code> usw.) nicht zuzulassen!',
	'ACP_ORPHAN_ATTACHMENTS_EXPLAIN'	=> 'Hier siehst du verwaiste Dateien. Dies passiert meist dann, wenn Benutzer Dateianhänge erstellen, aber den Beitrag nicht absenden. Du kannst die Dateien löschen oder sie einem bestehenden Beitrag anhängen. Zum Anhängen brauchst du eine gültige ID eines Beitrags; diese musst du selbst ermitteln. Dadurch wird der Dateianhang dem von dir angegebenen Beitrag zugeordnet.',
	'ADD_EXTENSION'						=> 'Dateierweiterung hinzufügen',
	'ADD_EXTENSION_GROUP'				=> 'Dateityp-Gruppe hinzufügen',
	'ADMIN_UPLOAD_ERROR'				=> 'Fehler beim Anhängen der Datei: „%s“.',
	'ALLOWED_FORUMS'					=> 'Erlaubte Foren',
	'ALLOWED_FORUMS_EXPLAIN'			=> 'Die zugewiesenen Dateierweiterungen können in den ausgewählten Foren (oder in allen Foren, falls eingestellt) benutzt werden.',
	'ALLOWED_IN_PM_POST'				=> 'Erlaubt',
	'ALLOW_ATTACHMENTS'					=> 'Dateianhänge erlauben',
	'ALLOW_ALL_FORUMS'					=> 'Alle Foren erlauben',
	'ALLOW_IN_PM'						=> 'Erlaubt in Privaten Nachrichten',
	'ALLOW_PM_ATTACHMENTS'				=> 'Dateianhänge in Privaten Nachrichten erlauben',
	'ALLOW_SELECTED_FORUMS'				=> 'Nur in folgenden ausgewählten Foren',
	'ASSIGNED_EXTENSIONS'				=> 'Zugeordnete Dateierweiterungen',
	'ASSIGNED_GROUP'					=> 'Zugeordnete Gruppe',
	'ATTACH_EXTENSIONS_URL'				=> 'Dateierweiterung',
	'ATTACH_EXT_GROUPS_URL'				=> 'Dateityp-Gruppen',
	'ATTACH_ID'							=> 'ID',
	'ATTACH_MAX_FILESIZE'				=> 'Maximale Dateigröße',
	'ATTACH_MAX_FILESIZE_EXPLAIN'		=> 'Maximale Größe pro Datei; bei 0 wird die Dateigröße nur durch die PHP-Konfiguration limitiert.',
	'ATTACH_MAX_PM_FILESIZE'			=> 'Maximale Dateigröße in Privaten Nachrichten',
	'ATTACH_MAX_PM_FILESIZE_EXPLAIN'	=> 'Maximale Größe pro Datei in Privaten Nachrichten; 0 bedeutet unbegrenzt.',
	'ATTACH_ORPHAN_URL'					=> 'Verwaiste Dateianhänge',
	'ATTACH_POST_ID'					=> 'Beitrags-ID',
	'ATTACH_QUOTA'						=> 'Maximales Kontingent für Dateianhänge',
	'ATTACH_QUOTA_EXPLAIN'				=> 'Maximaler für Dateianhänge verfügbarer Speicherplatz für das gesamte Forum; 0 bedeutet unbegrenzt.',
	'ATTACH_TO_POST'					=> 'Datei an den Beitrag anhängen',

	'CAT_FLASH_FILES'			=> 'Flash-Dateien',
	'CAT_IMAGES'				=> 'Bilder',
	'CAT_QUICKTIME_FILES'		=> 'QuickTime-Dateien',
	'CAT_RM_FILES'				=> 'RealMedia-Dateien',
	'CAT_WM_FILES'				=> 'Windows Media-Dateien',
	'CHECK_CONTENT'				=> 'Dateianhänge prüfen',
	'CHECK_CONTENT_EXPLAIN'		=> 'Manchen Browsern kann ein fehlerhafter MIME-Typ für hochgeladene Dateien vorgetäuscht werden. Diese Option stellt sicher, dass Dateien, die dieses Verhalten provozieren könnten, abgewiesen werden.',
	'CREATE_GROUP'				=> 'Neue Gruppe erstellen',
	'CREATE_THUMBNAIL'			=> 'Vorschaubild erstellen',
	'CREATE_THUMBNAIL_EXPLAIN'	=> 'Vorschaubild in allen möglichen Fällen erstellen.',

	'DEFINE_ALLOWED_IPS'			=> 'Erlaubte IPs/Hostnamen einstellen',
	'DEFINE_DISALLOWED_IPS'			=> 'Verbotene IPs/Hostnamen einstellen',
	'DOWNLOAD_ADD_IPS_EXPLAIN'		=> 'Gib jede IP-Adresse/jeden Hostnamen in einer separaten Zeile ein. Wenn du einen IP-Bereich angeben möchtest, musst du Anfang und Ende dieses Bereiches mit einem Bindestrich (-) trennen, verwende „*“ als Platzhalter.',
	'DOWNLOAD_REMOVE_IPS_EXPLAIN'	=> 'Du kannst mehrere IP-Adressen gleichzeitig entfernen (oder aus der Ausnahmeliste entfernen), indem du mit der entsprechenden Tasten- und Mauskombination mehrere Einträge markierst. IP-Adressen auf der Ausnahmeliste sind hervorgehoben.',
	'DISPLAY_INLINED'				=> 'Bilder im Beitrag anzeigen',
	'DISPLAY_INLINED_EXPLAIN'		=> 'Wenn diese Option auf „Nein“ gesetzt wird, werden Bildanhänge als Link dargestellt.',
	'DISPLAY_ORDER'					=> 'Sortierung der Dateianhänge',
	'DISPLAY_ORDER_EXPLAIN'			=> 'Dateianhänge sortiert nach Erstellungszeit anzeigen.',

	'EDIT_EXTENSION_GROUP'			=> 'Dateityp-Gruppen bearbeiten',
	'EXCLUDE_ENTERED_IP'			=> 'Diese Option aktivieren, um die eingegebene IP/den eingegebenen Hostnamen auszuschließen.',
	'EXCLUDE_FROM_ALLOWED_IP'		=> 'Adressen von erlaubten IPs/Hostnamen ausschließen',
	'EXCLUDE_FROM_DISALLOWED_IP'	=> 'Adressen von verbotenen IPs/Hostnamen ausschließen',
	'EXTENSIONS_UPDATED'			=> 'Dateierweiterungen erfolgreich aktualisiert.',
	'EXTENSION_EXIST'				=> 'Die Dateierweiterung %s existiert bereits.',
	'EXTENSION_GROUP'				=> 'Dateityp-Gruppe',
	'EXTENSION_GROUPS'				=> 'Dateityp-Gruppen',
	'EXTENSION_GROUP_DELETED'		=> 'Dateityp-Gruppe erfolgreich gelöscht.',
	'EXTENSION_GROUP_EXIST'			=> 'Die Dateityp-Gruppe %s existiert bereits.',

	'EXT_GROUP_ARCHIVES'			=> 'Archiv-Dateien',
	'EXT_GROUP_DOCUMENTS'			=> 'Dokumente',
	'EXT_GROUP_DOWNLOADABLE_FILES'	=> 'Herunterladbare Dateien',
	'EXT_GROUP_FLASH_FILES'			=> 'Flash-Dateien',
	'EXT_GROUP_IMAGES'				=> 'Bilder',
	'EXT_GROUP_PLAIN_TEXT'			=> 'Text-Dateien',
	'EXT_GROUP_QUICKTIME_MEDIA'		=> 'QuickTime-Dateien',
	'EXT_GROUP_REAL_MEDIA'			=> 'RealMedia-Dateien',
	'EXT_GROUP_WINDOWS_MEDIA'		=> 'Windows Media-Dateien',

	'GO_TO_EXTENSIONS'		=> 'Dateierweiterungen bearbeiten',
	'GROUP_NAME'			=> 'Gruppenname',

	'IMAGE_LINK_SIZE'			=> 'Dateigröße für verlinkte Bilder',
	'IMAGE_LINK_SIZE_EXPLAIN'	=> 'Bild-Dateianhänge werden als Link dargestellt, wenn deren Größe diese Werte überschreitet. Um dieses Verhalten abzuschalten, stelle als Werte 0px &times; 0px ein.',
	'IMAGICK_PATH'				=> 'Imagemagick-Pfad',
	'IMAGICK_PATH_EXPLAIN'		=> 'Voller Pfad zum Imagemagick-Programm, z.&nbsp;B. <samp>/usr/bin/</samp>.',

	'MAX_ATTACHMENTS'				=> 'Maximale Anzahl an Dateianhängen pro Beitrag',
	'MAX_ATTACHMENTS_PM'			=> 'Maximale Anzahl an Dateianhängen pro Privater Nachricht',
	'MAX_EXTGROUP_FILESIZE'			=> 'Maximale Dateigröße',
	'MAX_IMAGE_SIZE'				=> 'Maximale Bildgröße',
	'MAX_IMAGE_SIZE_EXPLAIN'		=> 'Maximale Größe von Bild-Dateianhängen. Um die Überprüfung der Bildgröße abzuschalten, stelle als Werte 0px &times; 0px ein.',
	'MAX_THUMB_WIDTH'				=> 'Maximale Größe der Vorschaubildern in Pixeln',
	'MAX_THUMB_WIDTH_EXPLAIN'		=> 'Ein Vorschaubild wird nicht größer sein als der hier eingestellte Wert.',
	'MIN_THUMB_FILESIZE'			=> 'Minimale Vorschaubild-Dateigröße',
	'MIN_THUMB_FILESIZE_EXPLAIN'	=> 'Erstellt keine Vorschaubilder bei Bildern, die kleiner sind als dieser Wert.',
	'MODE_INLINE'					=> 'indirekt',
	'MODE_PHYSICAL'					=> 'direkt',

	'NOT_ALLOWED_IN_PM'			=> 'Nur in Beiträgen erlaubt',
	'NOT_ALLOWED_IN_PM_POST'	=> 'Nicht erlaubt',
	'NOT_ASSIGNED'				=> 'Nicht zugewiesen',
	'NO_EXT_GROUP'				=> 'Keine',
	'NO_EXT_GROUP_NAME'			=> 'Kein Gruppenname eingegeben.',
	'NO_EXT_GROUP_SPECIFIED'	=> 'Keine Dateityp-Gruppe angegeben.',
	'NO_FILE_CAT'				=> 'Keine',
	'NO_IMAGE'					=> 'Kein Bild',
	'NO_THUMBNAIL_SUPPORT'		=> 'Die Unterstützung von Vorschaubildern wurde deaktiviert. Für diese Funktionalität muss entweder die GD-Bibliothek verfügbar oder Imagemagick installiert sein. Beide konnten nicht gefunden werden.',
	'NO_UPLOAD_DIR'				=> 'Das angegebene Upload-Verzeichnis existiert nicht.',
	'NO_WRITE_UPLOAD'			=> 'Das angegebene Upload-Verzeichnis ist nicht beschreibbar. Bitte ändere die Berechtigungen, damit der Webserver in das Verzeichnis schreiben kann.',

	'ONLY_ALLOWED_IN_PM'	=> 'Nur erlaubt in Privaten Nachrichten',
	'ORDER_ALLOW_DENY'		=> 'Erlauben',
	'ORDER_DENY_ALLOW'		=> 'Verbieten',

	'REMOVE_ALLOWED_IPS'		=> '<em>Erlaubte</em> IPs/Hostnamen oder Ausnahmen entfernen',
	'REMOVE_DISALLOWED_IPS'		=> '<em>Verbotene</em> IPs/Hostnamen oder Ausnahmen entfernen',

	'SEARCH_IMAGICK'				=> 'Nach Imagemagick suchen',
	'SECURE_ALLOW_DENY'				=> 'Erlauben-/Verbieten-Liste',
	'SECURE_ALLOW_DENY_EXPLAIN'		=> 'Ändert, wenn sichere Downloads aktiviert sind, das Verhalten der Erlauben-/Verbieten-Liste zu einer Liste von erlaubten bzw. verbotenen Adressen.',
	'SECURE_DOWNLOADS'				=> 'Sichere Downloads aktivieren',
	'SECURE_DOWNLOADS_EXPLAIN'		=> 'Durch diese Einstellung werden Downloads auf eine Liste von IP-Adressen/Hostnames eingeschränkt.',
	'SECURE_DOWNLOAD_NOTICE'		=> 'Sichere Downloads sind nicht aktiviert. Die folgenden Einstellungen werden erst wirksam, wenn sichere Downloads aktiviert werden.',
	'SECURE_DOWNLOAD_UPDATE_SUCCESS'=> 'Die IP-Liste wurde erfolgreich aktualisiert.',
	'SECURE_EMPTY_REFERRER'			=> 'Unterdrückten Referrer erlauben',
	'SECURE_EMPTY_REFERRER_EXPLAIN'	=> 'Sichere Downloads basieren auf Referrern. Sollen Downloads für unterdrückte Referrer erlaubt werden?',
	'SETTINGS_CAT_IMAGES'			=> 'Bildkategorie-Einstellungen',
	'SPECIAL_CATEGORY'				=> 'Spezialkategorie',
	'SPECIAL_CATEGORY_EXPLAIN'		=> 'Spezialkategorien unterscheiden sich in der Art, wie sie in Beiträgen dargestellt werden.',
	'SUCCESSFULLY_UPLOADED'			=> 'Erfolgreich hochgeladen.',
	'SUCCESS_EXTENSION_GROUP_ADD'	=> 'Dateityp-Gruppe erfolgreich hinzugefügt.',
	'SUCCESS_EXTENSION_GROUP_EDIT'	=> 'Dateityp-Gruppe erfolgreich geändert.',

	'UPLOADING_FILES'				=> 'Dateien werden hochgeladen',
	'UPLOADING_FILE_TO'				=> 'Datei „%1$s“ in Beitrag Nummer %2$d wird hochgeladen …',
	'UPLOAD_DENIED_FORUM'			=> 'Du hast keine Berechtigung, Dateien in das Forum „%s“ zu laden.',
	'UPLOAD_DIR'					=> 'Verzeichnis für hochgeladene Dateien',
	'UPLOAD_DIR_EXPLAIN'			=> 'Hier werden die Dateianhänge gespeichert. Wenn du dieses Verzeichnis änderst und schon hochgeladene Dateien existieren, musst du diese manuell in das neue Verzeichnis kopieren.',
	'UPLOAD_ICON'					=> 'Upload-Symbol',
	'UPLOAD_NOT_DIR'				=> 'Der angegebene Pfad für hochgeladene Dateien ist kein existierendes Verzeichnis.',
));

?>