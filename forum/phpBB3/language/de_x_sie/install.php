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
	'ADMIN_CONFIG'				=> 'Administrator-Konfiguration',
	'ADMIN_PASSWORD'			=> 'Administrator-Passwort',
	'ADMIN_PASSWORD_CONFIRM'	=> 'Bestätigung des Administrator-Passworts',
	'ADMIN_PASSWORD_EXPLAIN'	=> 'Bitte geben Sie ein Passwort mit einer Länge von 6 bis 30 Zeichen ein.',
	'ADMIN_TEST'				=> 'Administrator-Einstellungen prüfen',
	'ADMIN_USERNAME'			=> 'Benutzername des Administrators',
	'ADMIN_USERNAME_EXPLAIN'	=> 'Bitte geben Sie einen Benutzernamen mit einer Länge von 3 bis 20 Zeichen ein.',
	'APP_MAGICK'				=> 'Unterstützung von Imagemagick [ Dateianhänge ]',
	'AUTHOR_NOTES'				=> 'Autoren-Anmerkungen<br />» %s',
	'AVAILABLE'					=> 'Verfügbar',
	'AVAILABLE_CONVERTORS'		=> 'Verfügbare Konverter',

	'BEGIN_CONVERT'					=> 'Konvertierung starten',
	'BLANK_PREFIX_FOUND'			=> 'Die Prüfung Ihrer Tabellen ergab eine gültige Installation ohne Tabellen-Präfix.',
	'BOARD_NOT_INSTALLED'			=> 'Es wurde keine Installation gefunden',
	'BOARD_NOT_INSTALLED_EXPLAIN'	=> 'Um eine Konvertierung vorzunehmen, benötigt das phpBB-Konvertierungs-System eine Standardinstallation von phpBB 3. Bitte fahren Sie fort, in dem Sie <a href="%s">phpBB 3 zuerst installieren</a>.',
	'BACKUP_NOTICE'					=> 'Bitte erstellen Sie vor dem Update Ihres Boards ein Backup, falls während des Update-Vorgangs Probleme auftreten.',

	'CATEGORY'					=> 'Kategorie',
	'CACHE_STORE'				=> 'Cache-Art',
	'CACHE_STORE_EXPLAIN'		=> 'Der physikalische Ort, an dem die Daten zwischengespeichert werden. Bevorzugt wird das Dateisystem.',
	'CAT_CONVERT'				=> 'Konvertieren',
	'CAT_INSTALL'				=> 'Installieren',
	'CAT_OVERVIEW'				=> 'Übersicht',
	'CAT_UPDATE'				=> 'Update',
	'CHANGE'					=> 'Ändern',
	'CHECK_TABLE_PREFIX'		=> 'Bitte prüfen Sie Ihr Tabellen-Präfix und versuchen Sie es erneut.',
	'CLEAN_VERIFY'				=> 'Aufräumen und Prüfen der endgültigen Struktur',
	'CLEANING_USERNAMES'		=> 'Bereinigen der Benutzernamen',
	'COLLIDING_CLEAN_USERNAME'	=> '<strong>%s</strong> ist der bereinigte Benutzername für:',
	'COLLIDING_USERNAMES_FOUND'	=> 'Auf Ihrem alten Board wurden kollidierende Benutzernamen gefunden. Um mit der Konvertierung fortfahren zu können, löschen oder nennen Sie diese Benutzer um, damit auf Ihrem alten Board jeder Benutzer einen eindeutigen bereinigten Benutzernamen hat.',
	'COLLIDING_USER'			=> '» Benutzer-ID: <strong>%d</strong> Benutzername: <strong>%s</strong> (%d Beiträge)',
	'CONFIG_CONVERT'			=> 'Konvertiere die Konfiguration',
	'CONFIG_FILE_UNABLE_WRITE'	=> 'Die Konfigurationsdatei konnte nicht geschrieben werden. Alternative Methoden zum Erstellen dieser Datei werden unten angezeigt.',
	'CONFIG_FILE_WRITTEN'		=> 'Die Konfigurationsdatei wurde geschrieben. Sie können nun zum nächsten Schritt der Installation gehen.',
	'CONFIG_PHPBB_EMPTY'		=> 'Die phpBB3-Konfigurationsvariable für „%s“ ist leer.',
	'CONFIG_RETRY'				=> 'Erneuter Versuch',
	'CONTINUE_CONVERT'			=> 'Konvertierung fortsetzen',
	'CONTINUE_CONVERT_BODY'		=> 'Ein bereits gestarteter Konvertierungs-Versuch wurde gefunden. Sie können auswählen, ob Sie ihn fortsetzen oder einen neuen starten möchten.',
	'CONTINUE_LAST'				=> 'Mit den abschließenden Anweisungen fortfahren',
	'CONTINUE_OLD_CONVERSION'	=> 'Bereits gestartete Konvertierung fortsetzen',
	'CONVERT'					=> 'Konvertieren',
	'CONVERT_COMPLETE'			=> 'Konvertierung abgeschlossen',
	'CONVERT_COMPLETE_EXPLAIN'	=> 'Sie haben nun Ihr Board erfolgreich auf phpBB 3.1 konvertiert. Sie können sich jetzt anmelden und <a href="../">Ihr Board betreten</a>. Bitte prüfen Sie, ob alle Einstellungen richtig übernommen wurden, bevor Sie Ihr Board durch Löschen des „install“-Verzeichnisses freigeben. Hilfe zum Gebrauch von phpBB erhalten Sie online über die <a href="https://www.phpbb.com/support/docs/en/3.1/ug/">Dokumentation (englisch)</a> (<a href="https://www.phpbb.de/go/3.1/dokumentation">deutsche Übersetzung</a>) und das <a href="https://www.phpbb.com/community/viewforum.php?f=466">Support-Forum (englisch)</a> (<a href="https://www.phpbb.de/go/3.1/supportforum">deutschsprachiges Forum auf phpBB.de</a>).',
	'CONVERT_INTRO'				=> 'Willkommen beim phpBB-Konvertierungs-System',
	'CONVERT_INTRO_BODY'		=> 'Von hier aus können Sie Daten aus anderen (installierten) Boards importieren. Die unten stehende Liste zeigt alle verfügbaren Konverter-Module. Falls in dieser Liste kein Konverter für die Board-Software, von der Sie konvertieren möchten, enthalten ist, schauen Sie bitte auf unserer Website nach, wo möglicherweise weitere Konvertierungs-Module verfügbar sind.',
	'CONVERT_NEW_CONVERSION'	=> 'Neue Konvertierung',
	'CONVERT_NOT_EXIST'			=> 'Der angegebene Konverter existiert nicht.',
	'CONVERT_OPTIONS'			=> 'Optionen',
	'CONVERT_SETTINGS_VERIFIED'	=> 'Die eingegebenen Informationen wurden überprüft. Um den Konvertierungsprozess zu starten, klicken Sie unten auf die Schaltfläche.',
	'CONV_ERR_FATAL'			=> 'Fataler Konvertierungsfehler',

	'CONV_ERROR_ATTACH_FTP_DIR'			=> 'Der FTP-Upload für Dateianhänge ist im alten Forum eingeschaltet. Bitte deaktivieren Sie den FTP-Upload und stellen Sie sicher, dass ein gültiges Upload-Verzeichnis angegeben ist. Kopieren Sie dann alle Dateianhänge in dieses neue, aus dem Web zugängliche Verzeichnis. Sobald Sie dies getan haben, können Sie das Konvertierungsprogramm erneut aufrufen.',
	'CONV_ERROR_CONFIG_EMPTY'			=> 'Es sind keine Konfigurationsinformationen für die Konvertierung verfügbar.',
	'CONV_ERROR_FORUM_ACCESS'			=> 'Foren-Berechtigungen konnten nicht ausgelesen werden',
	'CONV_ERROR_GET_CATEGORIES'			=> 'Kategorien konnten nicht ausgelesen werden',
	'CONV_ERROR_GET_CONFIG'				=> 'Ihre Boardkonfiguration konnte nicht ausgelesen werden.',
	'CONV_ERROR_COULD_NOT_READ'			=> 'Konnte nicht auf „%s“ zugreifen / nicht lesen.',
	'CONV_ERROR_GROUP_ACCESS'			=> 'Konnte Gruppen-Berechtigungen nicht auslesen',
	'CONV_ERROR_INCONSISTENT_GROUPS'	=> 'add_bots() hat eine Unstimmigkeit in der Gruppen-Tabelle festgestellt — Sie müssen alle Systemgruppen manuell hinzufügen.',
	'CONV_ERROR_INSERT_BOT'				=> 'Konnte Bot nicht in users-Tabelle eintragen.',
	'CONV_ERROR_INSERT_BOTGROUP'		=> 'Konnte Bot nicht in bots-Tabelle eintragen.',
	'CONV_ERROR_INSERT_USER_GROUP'		=> 'Konnte Benutzer nicht in user_group-Tabelle eintragen.',
	'CONV_ERROR_MESSAGE_PARSER'			=> 'Fehler beim Message Parser',
	'CONV_ERROR_NO_AVATAR_PATH'			=> 'Hinweis an Entwickler: $convertor[\'avatar_path\'] muss angegeben werden, um %s zu benutzen.',
	'CONV_ERROR_NO_FORUM_PATH'			=> 'Der relative Pfad zum Quell-Board wurde nicht angegeben.',
	'CONV_ERROR_NO_GALLERY_PATH'		=> 'Hinweis an Entwickler: $convertor[\'avatar_gallery_path\'] muss angegeben werden, um %s zu benutzen.',
	'CONV_ERROR_NO_GROUP'				=> 'Gruppe „%1$s“ konnte nicht in %2$s gefunden werden.',
	'CONV_ERROR_NO_RANKS_PATH'			=> 'Hinweis an Entwickler: $convertor[\'ranks_path\'] muss angegeben werden, um %s zu benutzen.',
	'CONV_ERROR_NO_SMILIES_PATH'		=> 'Hinweis an Entwickler: $convertor[\'smilies_path\'] muss angegeben werden, um %s zu benutzen.',
	'CONV_ERROR_NO_UPLOAD_DIR'			=> 'Hinweis an Entwickler: $convertor[\'upload_path\'] muss angegeben werden, um %s zu benutzen.',
	'CONV_ERROR_PERM_SETTING'			=> 'Konnte Berechtigungen nicht einfügen / ändern.',
	'CONV_ERROR_PM_COUNT'				=> 'Konnte Nachrichtenzahl des PN-Verzeichnisses nicht abrufen.',
	'CONV_ERROR_REPLACE_CATEGORY'		=> 'Konnte neues Forum als Ersatz der alten Kategorie nicht einfügen.',
	'CONV_ERROR_REPLACE_FORUM'			=> 'Konnte neues Forum als Ersatz des alten Forums nicht einfügen.',
	'CONV_ERROR_USER_ACCESS'			=> 'Konnte Benutzer-Authentifizierungsinformationen nicht abrufen.',
	'CONV_ERROR_WRONG_GROUP'			=> 'Falsche Gruppe „%1$s“ in %2$s definiert.',
	'CONV_OPTIONS_BODY'					=> 'Diese Seite fragt die Daten ab, die zum Zugriff auf das Quell-Board erforderlich sind. Geben Sie die Datenbank-Daten Ihres alten Boards ein; der Konverter wird an der unten angegebenen Datenbank keine Änderungen vornehmen. Das Quell-Board sollte deaktiviert sein, um eine konsistente Konvertierung zu ermöglichen.',
	'CONV_SAVED_MESSAGES'				=> 'Gesicherte Nachrichten',

	'COULD_NOT_COPY'			=> 'Konnte die Datei <strong>%1$s</strong> nicht nach <strong>%2$s</strong> kopieren.<br /><br />Bitte prüfen Sie, ob das Zielverzeichnis existiert und durch den Webserver beschrieben werden kann.',
	'COULD_NOT_FIND_PATH'		=> 'Der Pfad zu Ihrem alten Board konnte nicht gefunden werden. Bitte prüfen Sie Ihre Einstellungen und versuchen Sie es erneut.<br />» Der angegebene Quell-Pfad war: %s',

	'DBMS'						=> 'Datenbank-Typ',
	'DB_CONFIG'					=> 'Datenbank-Konfiguration',
	'DB_CONNECTION'				=> 'Datenbank-Verbindung',
	'DB_ERR_INSERT'				=> 'Fehler bei der Verarbeitung der <code>INSERT</code>-Anfrage.',
	'DB_ERR_LAST'				=> 'Fehler bei der Verarbeitung von <var>query_last</var>.',
	'DB_ERR_QUERY_FIRST'		=> 'Fehler bei der Ausführung von <var>query_first</var>.',
	'DB_ERR_QUERY_FIRST_TABLE'	=> 'Fehler bei der Ausführung von <var>query_first</var>, %s („%s“).',
	'DB_ERR_SELECT'				=> 'Fehler beim Durchführen der <code>SELECT</code>-Anfrage.',
	'DB_HOST'					=> 'Datenbankserver-Hostname oder DSN',
	'DB_HOST_EXPLAIN'			=> 'DSN steht für Data Source Name und ist nur für ODBC-Installationen relevant. Bei PostgreSQL wird mit <em>localhost</em> eine Verbindung zum lokalen Server über UNIX-Domain-Socket hergestellt und mit <em>127.0.0.1</em> über TCP. Für SQLite ist der vollständige Pfad der Datenbank-Datei anzugeben.',
	'DB_NAME'					=> 'Name der Datenbank',
	'DB_PASSWORD'				=> 'Datenbank-Passwort',
	'DB_PORT'					=> 'Datenbankserver-Port',
	'DB_PORT_EXPLAIN'			=> 'Lassen Sie dieses Feld frei, es sei denn, Sie wissen, dass der Server nicht den Standard-Port verwendet.',
	'DB_UPDATE_NOT_SUPPORTED'	=> 'Dieses Skript unterstützt kein Update von phpBB-Versionen vor „%1$s“. Sie haben derzeit Version „%2$s“ installiert, so dass leider kein direktes Update möglich ist. Sie müssen daher zunächst auf eine frühere Version aktualisieren, die ein Update von Version „%2$s“ unterstützt, bevor Sie dieses Script ausführen können. Unterstützung dazu erhalten Sie in den Support-Foren von phpBB.com und phpBB.de.',
	'DB_USERNAME'				=> 'Datenbank-Benutzername',
	'DB_TEST'					=> 'Verbindung testen',
	'DEFAULT_LANG'				=> 'Standardsprache',
	'DEFAULT_PREFIX_IS'			=> 'Der Konverter konnte keine Tabellen mit dem angegebenen Präfix finden. Bitte stellen Sie sicher, dass Sie die richtigen Daten des Boards angegeben haben, von dem Sie konvertieren möchten. Der standardmäßige Tabellenpräfix für %1$s ist <strong>%2$s</strong>.',
	'DEV_NO_TEST_FILE'			=> 'Für die test_file-Variable im Konverter wurde kein Wert angegeben. Falls Sie ein Nutzer dieses Konverters sind, sollten Sie diesen Fehler nicht sehen. Bitte melden Sie diese Nachricht an die Autoren des Konverters. Falls Sie ein Konverter-Autor sind, müssen Sie den Namen einer im Quell-Board existierenden Datei angeben, damit der Pfad dorthin verifiziert werden kann.',
	'DIRECTORIES_AND_FILES'		=> 'Verzeichnis- und Datei-Setup',
	'DISABLE_KEYS'				=> 'Deaktiviere Datenbank-Schlüssel',
	'DLL_FTP'					=> 'Remote FTP Unterstützung [ Installation ]',
	'DLL_GD'					=> 'GD Grafik-Unterstützung [ Visuelle Bestätigung ]',
	'DLL_MBSTRING'				=> 'Multibyte-Zeichenketten-Unterstützung',
	'DLL_MSSQL'					=> 'MSSQL Server 2000+',
	'DLL_MSSQL_ODBC'			=> 'MSSQL Server 2000+ über ODBC',
	'DLL_MSSQLNATIVE'			=> 'MSSQL Server 2005+ [ Nativ ]',
	'DLL_MYSQL'					=> 'MySQL',
	'DLL_MYSQLI'				=> 'MySQL mit MySQLi-Erweiterung',
	'DLL_ORACLE'				=> 'Oracle',
	'DLL_POSTGRES'				=> 'PostgreSQL',
	'DLL_SQLITE'				=> 'SQLite 2',
	'DLL_SQLITE3'				=> 'SQLite 3',
	'DLL_XML'					=> 'XML Unterstützung [ Jabber ]',
	'DLL_ZLIB'					=> 'zlib Kompressions-Unterstützung [ gz, .tar.gz, .zip ]',
	'DL_CONFIG'					=> 'Konfigurationsdatei herunterladen',
	'DL_CONFIG_EXPLAIN'			=> 'Sie können die config.php auf Ihren PC herunterladen. Diese muss dann manuell hochgeladen und eine evtl. existierende config.php im phpBB 3.1-Hauptverzeichnis ersetzt werden. Bitte denken Sie daran, die Datei im ASCII-Format hochzuladen (lesen Sie hierzu die Dokumentation Ihres FTP-Programms, wenn Sie nicht wissen, wie Sie dazu vorgehen müssen). Wenn die config.php hochgeladen wurde, klicken Sie auf „Erledigt“, um fortzufahren.',
	'DL_DOWNLOAD'				=> 'Download',
	'DONE'						=> 'Erledigt',

	'ENABLE_KEYS'				=> 'Datenbank-Schlüssel werden wieder aktiviert. Dies kann etwas Zeit in Anspruch nehmen.',

	'FILES_OPTIONAL'			=> 'Optionale Dateien und Verzeichnisse',
	'FILES_OPTIONAL_EXPLAIN'	=> '<strong>Optional</strong> — Diese Dateien, Verzeichnisse oder Rechte-Einstellungen sind keine Voraussetzung für die Installation. Das Installationssystem wird versuchen, diese auf verschiedene Weisen zu erstellen, falls sie nicht existieren oder nicht beschreibbar sind. Wenn sie vorhanden und beschreibbar sind, wird allerdings die Installation vereinfacht.',
	'FILES_REQUIRED'			=> 'Dateien und Verzeichnisse',
	'FILES_REQUIRED_EXPLAIN'	=> '<strong>Voraussetzung</strong> — phpBB muss auf diverse Dateien und Verzeichnisse zugreifen oder diese beschreiben können, um reibungslos zu funktionieren. Wenn „Nicht gefunden“ angezeigt wird, müssen Sie die entsprechende Datei oder das Verzeichnis erstellen. Wenn „Nicht beschreibbar“ angezeigt wird, müssen Sie die Berechtigungen für die Datei oder das Verzeichnis so ändern, dass phpBB darauf schreiben kann.',
	'FILLING_TABLE'				=> 'Fülle Tabelle <strong>%s</strong>',
	'FILLING_TABLES'			=> 'Fülle Tabellen',

	'FINAL_STEP'				=> 'Abschließenden Schritt ausführen',
	'FORUM_ADDRESS'				=> 'Board-Adresse',
	'FORUM_ADDRESS_EXPLAIN'		=> 'Dies ist die URL Ihres alten Boards, zum Beispiel <samp>http://www.domain.tld/phpBB2/</samp>. Wenn hier eine Adresse angegeben wird und das Feld nicht leer gelassen wird, wird jedes Vorkommen dieser Adresse in Beiträgen, Privaten Nachrichten und Signaturen mit der neuen Adresse Ihres Forums aktualisiert.',
	'FORUM_PATH'				=> 'Board-Pfad',
	'FORUM_PATH_EXPLAIN'		=> 'Dies ist der <strong>relative</strong> Pfad im Dateisystem zu Ihrem alten Board vom <strong>Hauptverzeichnis dieser neuen phpBB-Installation</strong> aus.',
	'FOUND'						=> 'Gefunden',
	'FTP_CONFIG'				=> 'Konfiguration über FTP hochladen',
	'FTP_CONFIG_EXPLAIN'		=> 'phpBB hat das FTP-Modul auf diesem Server gefunden. Wenn Sie möchten, können Sie auf diesem Wege die config.php hochladen. Dazu müssen Sie die unten aufgelisteten Informationen angeben. Denken Sie daran, dass Sie den Benutzernamen und das Passwort für den FTP-Server angeben müssen! (Fragen Sie Ihren Webhosting-Provider, wenn Sie sich nicht sicher sind, wie diese lauten.)',
	'FTP_PATH'					=> 'FTP-Pfad',
	'FTP_PATH_EXPLAIN'			=> 'Dies ist der Pfad zu Ihrer phpBB-Installation vom Root-Verzeichnis aus, z.&nbsp;B. <samp>htdocs/phpBB3/</samp>.',
	'FTP_UPLOAD'				=> 'Hochladen',

	'GPL'						=> 'General Public License',

	'INITIAL_CONFIG'			=> 'Grund-Konfiguration',
	'INITIAL_CONFIG_EXPLAIN'	=> 'Nachdem nun festgestellt wurde, dass phpBB auf Ihrem Server betrieben werden kann, müssen Sie noch einige Informationen angeben. Wenn Sie nicht wissen, wie die Verbindungsdaten für Ihre Datenbank lauten, kontaktieren Sie bitte als erstes Ihren Webhosting-Provider oder wenden Sie sich an die phpBB Support-Foren. Wenn Sie Daten eingeben, prüfen Sie diese bitte sorgfältig, bevor Sie fortfahren.',
	'INSTALL_CONGRATS'			=> 'Herzlichen Glückwunsch!',
	'INSTALL_CONGRATS_EXPLAIN'	=> '
		Sie haben phpBB %1$s erfolgreich installiert. Bitte fahren Sie mit einer der folgenden Optionen fort:</p>
		<h2>Ein bestehendes Board auf phpBB3 konvertieren</h2>
		<p>Das phpBB-Konvertierungs-System unterstützt die Konvertierung von phpBB 2.0.x und anderen Board-Systemen auf phpBB3. Wenn Sie ein bestehendes Board konvertieren möchten, fahren Sie bitte <a href="%2$s">mit dem Konverter fort</a>.</p>
		<h2>Starten Sie mit phpBB3 durch!</h2>
		<p>Wenn Sie unten auf die Schaltfläche klicken, werden Sie zu einem Formular im Administrations-Bereich weitergeleitet, mit dem Sie statistische Daten an phpBB.com übermitteln können. Wir würden uns freuen, wenn Sie unsere Arbeit mit Ihren Angaben unterstützen würden. Anschließend sollten Sie sich etwas Zeit nehmen, um die verfügbaren Optionen kennen zu lernen. Hilfe zum Gebrauch von phpBB erhalten Sie online über die <a href="https://www.phpbb.com/support/docs/en/3.1/ug/">Dokumentation (englisch)</a> (<a href="https://www.phpbb.de/go/3.1/dokumentation">deutsche Übersetzung</a>), die <a href="%3$s">README</a> und das <a href="https://www.phpbb.com/community/viewforum.php?f=466">Support-Forum (englisch)</a> (<a href="https://www.phpbb.de/go/3.1/supportforum">deutschsprachiges Forum auf phpBB.de</a>).<br /><br /><strong>Bitte löschen oder verschieben Sie das Installations-Verzeichnis „install“ oder nennen Sie es um, bevor Sie Ihr Board benutzen. Solange dieses Verzeichnis existiert, ist nur der Administrations-Bereich zugänglich.</strong>',
	'INSTALL_INTRO'				=> 'Willkommen bei der Installation',
	'INSTALL_INTRO_BODY'		=> 'Dieser Assistent ermöglicht Ihnen die Installation von phpBB3 auf Ihrem Server.</p><p>Bevor Sie fortsetzen können, benötigen Sie die Daten Ihrer Datenbank. Wenn Sie die Daten Ihrer Datenbank nicht kennen, kontaktieren Sie bitte Ihren Server-Betreiber und fragen Sie nach den Daten. Ohne die Datenbankdaten können Sie nicht fortfahren. Sie benötigen:</p>
	<ul>
		<li>Den Datenbank-Typ — die Art der Datenbank, auf die Sie zugreifen werden.</li>
		<li>Den Hostname oder DSN des Datenbankservers — die Adresse, unter der der Datenbankserver erreichbar ist.</li>
		<li>Den Port des Datenbank-Servers — den Port, über den der Datenbankserver erreicht wird (in den meisten Fällen ist diese Information nicht notwendig).</li>
		<li>Den Namen der Datenbank — den Namen der Datenbank auf dem Server.</li>
		<li>Den Benutzernamen und das Passwort für die Datenbank — die Zugangsdaten, um auf die Datenbank zugreifen zu können.</li>
	</ul>

	<p><strong>Hinweis:</strong> wenn Sie SQLite verwenden, sollten Sie den vollständigen Pfad zu Ihrer Datenbank-Datei im DSN-Feld eingeben und die Felder für Benutzername und Passwort frei lassen. Aus Sicherheitsgründen sollte die Datenbank in keinem Verzeichnis gespeichert werden, das aus dem Internet zugänglich ist.</p>

	<p>phpBB3 unterstützt folgende Datenbank-Typen:</p>
	<ul>
		<li>MySQL 3.23 und höher (MySQLi wird unterstützt)</li>
		<li>PostgreSQL 8.3+</li>
		<li>SQLite 2.8.2+</li>
		<li>SQLite 3.6.15+</li>
		<li>MS SQL Server 2000 und höher (direkt oder über ODBC)</li>
		<li>MS SQL Server 2005 und höher (nativ)</li>
		<li>Oracle</li>
	</ul>

	<p>Es werden nur die Datenbank-Typen zur Auswahl angeboten, die Ihr Server unterstützt.',
	'INSTALL_INTRO_NEXT'		=> 'Klicken Sie unten auf die Schaltfläche, um mit der Installation zu beginnen.',
	'INSTALL_LOGIN'				=> 'Anmelden',
	'INSTALL_NEXT'				=> 'Nächster Schritt',
	'INSTALL_NEXT_FAIL'			=> 'Einige Tests sind fehlgeschlagen. Sie sollten diese Probleme zuerst korrigieren, bevor Sie mit dem nächsten Schritt weiter machen. Wenn Sie dies unterlassen, könnte dies zu einer unvollständigen Installation führen.',
	'INSTALL_NEXT_PASS'			=> 'Alle Voraussetzungen wurden erfolgreich geprüft und Sie können nun mit dem nächsten Schritt der Installation weitermachen. Sollten Sie Berechtigungen, Module o.&nbsp;ä. geändert haben und einen erneuten Testdurchlauf wünschen, so können Sie den Test wiederholen.',
	'INSTALL_PANEL'				=> 'Installations-Routine',
	'INSTALL_SEND_CONFIG'		=> 'phpBB konnte leider die Konfiguration nicht direkt in die config.php schreiben. Dies kann daran liegen, dass die Datei entweder nicht existiert oder nicht beschreibbar ist. Ihnen werden unten einige Möglichkeiten angeboten, wie Sie die Installation bezüglich der config.php abschließen können.',
	'INSTALL_START'				=> 'Installation starten',
	'INSTALL_TEST'				=> 'Erneut prüfen',
	'INST_ERR'					=> 'Installationsfehler',
	'INST_ERR_DB_CONNECT'		=> 'Es kann keine Verbindung zur Datenbank aufgebaut werden. Details stehen in unten angezeigter Fehlermeldung.',
	'INST_ERR_DB_FORUM_PATH'	=> 'Die angegebene Datenbank-Datei liegt innerhalb Ihres Board-Verzeichnisses. Sie sollten sie an einem nicht über das Web zugänglichen Ort ablegen.',
	'INST_ERR_DB_INVALID_PREFIX'=> 'Der angegebene Tabellen-Präfix ist ungültig. Er muss mit einem alphanumerischen Zeichen beginnen und darf nur Buchstaben, Ziffern und Unterstriche enthalten.',
	'INST_ERR_DB_NO_ERROR'		=> 'Es wurde keine Fehlermeldung übergeben.',
	'INST_ERR_DB_NO_MYSQLI'		=> 'Die auf diesem System installierte MySQL-Version ist nicht kompatibel mit der „MySQL mit MySQLi-Erweiterung“-Option, die von Ihnen gewählt wurde. Bitte versuchen Sie es stattdessen mit der „MySQL“-Option.',
	'INST_ERR_DB_NO_SQLITE'		=> 'Die installierte Version der SQLite-Erweiterung ist zu alt. Sie muss auf 2.8.2 oder höher aktualisiert werden.',
	'INST_ERR_DB_NO_SQLITE3'		=> 'Die installierte Version der SQLite-Erweiterung ist zu alt. Sie muss auf 3.6.15 oder höher aktualisiert werden.',
	'INST_ERR_DB_NO_ORACLE'		=> 'Die installierte Oracle-Version erfordert, dass der Parameter <var>NLS_CHARACTERSET</var> auf <var>UTF8</var> gesetzt ist. Bitte aktualisieren Sie die Oracle-Version oder änderen Sie den genannten Parameter entsprechend.',
	'INST_ERR_DB_NO_POSTGRES'	=> 'Die ausgewählte Datenbank wurde nicht mit der Codierung <var>UNICODE</var> oder <var>UTF8</var> erstellt. Bitte versuchen Sie die Installation erneut mit einer Datenbank, die mit dieser Codierung erstellt wurde.',
	'INST_ERR_DB_NO_NAME'		=> 'Kein Datenbank-Name angegeben.',
	'INST_ERR_EMAIL_INVALID'	=> 'Die angegebene E-Mail-Adresse ist ungültig.',
	'INST_ERR_EMAIL_MISMATCH'	=> 'Die angegebenen E-Mail-Adressen stimmen nicht überein.',
	'INST_ERR_FATAL'			=> 'Schwerer Installations-Fehler',
	'INST_ERR_FATAL_DB'			=> 'Es trat ein Datenbankfehler auf, der nicht von phpBB selbst behoben werden kann. Dies kann daran liegen, dass der angegebene Benutzer keine ausreichenden Berechtigungen hat, um die Befehle <code>CREATE TABLE</code> oder <code>INSERT</code> etc. auszuführen. Weitere Informationen werden möglicherweise unten angezeigt. Bitte wenden Sie sich zuerst an Ihren Webhosting-Provider oder an die Support-Foren von phpBB für weitere Unterstützung.',
	'INST_ERR_FTP_PATH'			=> 'Kann nicht in das angegebene Verzeichnis wechseln, bitte prüfen Sie den Pfad.',
	'INST_ERR_FTP_LOGIN'		=> 'Kann nicht am FTP-Server anmelden, bitte prüfen Sie Benutzernamen und Passwort.',
	'INST_ERR_MISSING_DATA'		=> 'Sie müssen alle Felder dieses Blocks ausfüllen.',
	'INST_ERR_NO_DB'			=> 'Kann das PHP-Modul für den gewählten Datenbank-Typ nicht laden.',
	'INST_ERR_PASSWORD_MISMATCH'	=> 'Die eingegebenen Passwörter stimmen nicht überein.',
	'INST_ERR_PASSWORD_TOO_LONG'	=> 'Das eingegebene Passwort ist zu lang. Die maximale Länge beträgt 30 Zeichen.',
	'INST_ERR_PASSWORD_TOO_SHORT'	=> 'Das eingegebene Passwort ist zu kurz. Die minimale Länge beträgt 6 Zeichen.',
	'INST_ERR_PREFIX'			=> 'Es existieren bereits Tabellen mit dem angegebenen Präfix, bitte wählen Sie ein alternatives.',
	'INST_ERR_PREFIX_INVALID'	=> 'Das angegebene Tabellen-Präfix kann für diese Datenbank nicht verwendet werden. Bitte geben Sie eine Alternative an und vermeiden Sie Zeichen wie z.&nbsp;B. den Bindestrich.',
	'INST_ERR_PREFIX_TOO_LONG'	=> 'Das angegebene Tabellen-Präfix ist zu lang. Die maximale Länge beträgt %d Zeichen.',
	'INST_ERR_USER_TOO_LONG'	=> 'Der von Ihnen angegebene Benutzername ist zu lang. Die maximale Länge beträgt 20 Zeichen.',
	'INST_ERR_USER_TOO_SHORT'	=> 'Der von Ihnen angegebene Benutzername ist zu kurz. Die minimale Länge beträgt 3 Zeichen.',
	'INVALID_PRIMARY_KEY'		=> 'Ungültiger Primärschlüssel: %s',

	'LONG_SCRIPT_EXECUTION'		=> 'Bitte beachten Sie, dass dieser Vorgang etwas dauern kann. Bitte unterbrechen Sie das Skript nicht.',

	// mbstring
	'MBSTRING_CHECK'						=> 'Prüfung der <samp>mbstring</samp>-Erweiterung',
	'MBSTRING_CHECK_EXPLAIN'				=> '<strong>Erforderlich</strong> – <samp>mbstring</samp> ist eine PHP-Erweiterung, die Unterstützung für Multibyte-Zeichenketten zur Verfügung stellt. Bestimmte Funktionen von mbstring sind nicht mit phpBB kompatibel und müssen deaktiviert werden.',
	'MBSTRING_FUNC_OVERLOAD'				=> 'Überladen von Funktionen',
	'MBSTRING_FUNC_OVERLOAD_EXPLAIN'		=> '<var>mbstring.func_overload</var> muss entweder 0 oder 4 sein.',
	'MBSTRING_ENCODING_TRANSLATION'			=> 'Transparente Zeichenkodierung',
	'MBSTRING_ENCODING_TRANSLATION_EXPLAIN'	=> '<var>mbstring.encoding_translation</var> muss 0 sein.',
	'MBSTRING_HTTP_INPUT' 					=> 'HTTP-Eingabe-Kodierung',
	'MBSTRING_HTTP_INPUT_EXPLAIN' 			=> '<var>mbstring.http_input</var> muss auf <samp>pass</samp> eingestellt sein.',
	'MBSTRING_HTTP_OUTPUT'					=> 'HTTP-Ausgabe-Kodierung',
	'MBSTRING_HTTP_OUTPUT_EXPLAIN'			=> '<var>mbstring.http_output</var> muss auf <samp>pass</samp> eingestellt sein.',

	'MAKE_FOLDER_WRITABLE'		=> 'Bitte stellen Sie sicher, dass dieser Ordner existiert und durch den Webserver beschreibbar ist. Versuchen Sie es dann erneut:<br />»<strong>%s</strong>.',
	'MAKE_FOLDERS_WRITABLE'		=> 'Bitte stellen Sie sicher, dass diese Ordner existieren und durch den Webserver beschreibbar sind. Versuchen Sie es dann erneut:<br />»<strong>%s</strong>.',

	'MYSQL_SCHEMA_UPDATE_REQUIRED'	=> 'Ihr MySQL-Datenbankschema ist veraltet. phpBB hat ein Schema für MySQL 3.x/4.x erkannt, der Server läuft aber mit MySQL %2$s.<br /><strong>Sie müssen das Schema aktualisieren, um mit dem Update fortfahren zu können.</strong><br /><br />Bitte verfahren Sie dabei wie im <a href="https://www.phpbb.com/kb/article/doesnt-have-a-default-value-errors/">Knowledge-Base-Artikel zur Aktualisierung des MySQL-Schemas</a> beschrieben wird (<a href="https://www.phpbb.de/go/3.1/no_default">deutschsprachige Fassung</a>. Wenn Sie dabei Probleme auf Probleme stoßen, nutzen Sie bitte <a href="https://www.phpbb.com/community/viewforum.php?f=466">unsere Support-Foren</a> (<a href="https://www.phpbb.de/go/3.1/supportforum">deutschsprachige Foren</a>).',

	'NAMING_CONFLICT'			=> 'Namens-Konflikt: %s und %s sind beides Aliasse<br /><br />%s',
	'NEXT_STEP'					=> 'Mit dem nächsten Schritt fortfahren',
	'NOT_FOUND'					=> 'Nicht gefunden',
	'NOT_UNDERSTAND'			=> 'Kann %s #%d nicht verstehen, Tabelle %s („%s“)',
	'NO_CONVERTORS'				=> 'Es stehen keine Konverter zur Verfügung',
	'NO_CONVERT_SPECIFIED'		=> 'Kein Konverter angegeben.',
	'NO_LOCATION'				=> 'Kann den Pfad nicht ermitteln. Wenn Sie wissen, dass Imagemagick installiert ist, können Sie den Pfad auch später noch im Administrations-Bereich angeben.',
	'NO_TABLES_FOUND'			=> 'Keine Tabellen gefunden.',

	'OVERVIEW_BODY'				=> 'Willkommen bei phpBB3!<br /><br />phpBB ist die am weitesten verbreitete Open-Source-Forensoftware. phpBB3 ist die jüngste Fortsetzung einer im Jahr 2000 begonnenen Entwicklungsgeschichte. Wie seine Vorversionen ist phpBB3 funktionsreich, benutzerfreundlich und vollständig vom phpBB-Team unterstützt. phpBB3 verbessert deutlich, was phpBB2 beliebt gemacht hat und bringt neue Funktionen, die häufig gefragt und nicht in den Vorversionen enthalten waren. Wir hoffen, dass es Ihre Erwartungen übertrifft.<br /><br />Dieses Installations-System wird Sie durch die Installation von phpBB3, das Update von einer älteren auf die aktuelle Version von phpBB3 als auch die Konvertierung von einem anderen Software-Paket (inklusive phpBB2) führen. Für weitere Informationen empfehlen wir Ihnen, <a href="../docs/INSTALL.html">die Installationsanweisungen</a> zu lesen.<br /><br />Um die Lizenz von phpBB3 zu lesen oder Informationen über den Erhalt von Support und unsere Einstellung dazu zu erhalten, wählen Sie bitte die entsprechende Option aus dem seitlichen Menü aus. Um fortzufahren, wählen Sie bitte oben das entsprechende Register aus.',

	'PCRE_UTF_SUPPORT'				=> 'PCRE UTF-8-Unterstützung',
	'PCRE_UTF_SUPPORT_EXPLAIN'		=> 'phpBB wird <strong>nicht</strong> funktionieren, wenn Ihre PHP-Installation ohne UTF-8-Unterstützung in der PCRE-Erweiterung kompiliert wurde.',
	'PHP_GETIMAGESIZE_SUPPORT'			=> 'PHP-Funktion getimagesize() ist verfügbar',
	'PHP_GETIMAGESIZE_SUPPORT_EXPLAIN'	=> '<strong>Voraussetzung</strong> — Damit phpBB richtig funktioniert, muss die Funktion getimagesize() verfügbar sein.',
	'PHP_JSON_SUPPORT'				=> 'PHP JSON-Unterstützung',
	'PHP_JSON_SUPPORT_EXPLAIN'		=> '<strong>Voraussetzung</strong> — Damit phpBB richtig funktioniert, muss die PHP JSON-Erweiterung verfügbar sein.',
	'PHP_OPTIONAL_MODULE'			=> 'Optionale Module',
	'PHP_OPTIONAL_MODULE_EXPLAIN'	=> '<strong>Optional</strong> — Diese Module oder Applikationen sind optional. Sollten sie verfügbar sein, so ermöglichen sie zusätzliche Funktionen.',
	'PHP_SUPPORTED_DB'				=> 'Unterstützte Datenbanken',
	'PHP_SUPPORTED_DB_EXPLAIN'		=> '<strong>Voraussetzung</strong> — Sie müssen Unterstützung für mindestens eine kompatible Datenbank in PHP bereitstellen. Falls keine Datenbank-Module als verfügbar angezeigt werden, sollten Sie Ihren Webhosting-Provider kontaktieren oder die entsprechende PHP-Dokumentation zu Rate ziehen.',
	'PHP_REGISTER_GLOBALS'			=> 'PHP Einstellung <var>register_globals</var> ist deaktiviert',
	'PHP_REGISTER_GLOBALS_EXPLAIN'	=> 'phpBB wird auch funktionieren, wenn diese Einstellung aktiviert ist. Allerdings wird aus Sicherheitsgründen empfohlen, register_globals in der PHP-Installation zu deaktivieren, falls dies möglich ist.',
	'PHP_SAFE_MODE'					=> 'Safe Mode',
	'PHP_SETTINGS'					=> 'PHP-Version und -Einstellungen',
	'PHP_SETTINGS_EXPLAIN'			=> '<strong>Voraussetzung</strong> — Sie müssen mindestens PHP-Version 5.3.3 verwenden, um phpBB installieren zu können. Falls unten <var>Safe Mode</var> angezeigt wird, läuft Ihre PHP-Installation in diesem Modus. Dies wird manche Funktionen des Administrations-Bereichs einschränken.',
	'PHP_URL_FOPEN_SUPPORT'			=> 'PHP-Einstellung <var>allow_url_fopen</var> ist erlaubt',
	'PHP_URL_FOPEN_SUPPORT_EXPLAIN'	=> '<strong>Optional</strong> — Diese Einstellung ist optional, jedoch werden bestimmt phpBB-Funktionen wie extern verlinkte Avatare ohne sie nicht richtig funktionieren.',
	'PHP_VERSION_REQD'				=> 'PHP-Version >= 5.3.3',
	'POST_ID'						=> 'Beitrags-ID',
	'PREFIX_FOUND'					=> 'Die Prüfung Ihrer Tabellen ergab eine gültige Installation mit <strong>%s</strong> als Tabellen-Präfix.',
	'PREPROCESS_STEP'				=> 'Vorbereitende Funktionen/Abfragen werden ausgeführt.',
	'PRE_CONVERT_COMPLETE'			=> 'Die vorbereitenden Schritte der Konvertierung wurden erfolgreich abgeschlossen. Sie können nun mit der eigentlichen Konvertierung beginnen. Bitte beachten Sie, dass Sie einige Dinge manuell einstellen und anpassen müssen. Nach der Konvertierung sollten Sie insbesondere die zugewiesenen Berechtigungen prüfen, sofern nötig Ihren Suchindex neu aufbauen und sicherstellen, dass alle Dateien wie z.&nbsp;B. Benutzerbilder und Smilies richtig kopiert wurden.',
	'PROCESS_LAST'					=> 'Verarbeite abschließende Anweisungen',

	'REFRESH_PAGE'				=> 'Seite aktualisieren, um Konvertierung fortzusetzen',
	'REFRESH_PAGE_EXPLAIN'		=> 'Wenn auf Ja gesetzt, wird der Konverter die Seite aktualisieren, wenn er einen Schritt abgeschlossen hat. Wenn dies Ihre erste Konvertierung zu Testzwecken und um Fehler im Vorfeld festzustellen ist, empfehlen wir, dies auf Nein zu stellen.',
	'REQUIREMENTS_TITLE'		=> 'Installations-Kompatibilität',
	'REQUIREMENTS_EXPLAIN'		=> 'Bevor die Installation fortgesetzt werden kann, wird phpBB einige Tests zu Ihrer Server-Konfiguration und Ihren Dateien durchführen, um sicherzustellen, dass Sie phpBB installieren und benutzen können. Bitte lesen Sie die Ergebnisse aufmerksam durch und fahren Sie nicht weiter fort, bevor alle erforderlichen Tests bestanden sind. Falls Sie irgendeine der Funktionen, die unter den optionalen Modulen aufgeführt sind, nutzen möchten, sollten Sie sicherstellen, dass die entsprechenden Tests auch bestanden werden.',
	'RETRY_WRITE'				=> 'Erneut versuchen, die Konfigurationsdatei zu schreiben',
	'RETRY_WRITE_EXPLAIN'		=> 'Wenn Sie möchten, können Sie die Berechtigungen der config.php ändern, so dass sie phpBB schreiben kann. Mit „Erneut versuchen, die Konfigurationsdatei zu schreiben“ können Sie einen weiteren Versuch starten. Denken Sie daran, die Berechtigungen der config.php nach der Installation wieder zurückzustellen.',

	'SCRIPT_PATH'				=> 'Scriptpfad',
	'SCRIPT_PATH_EXPLAIN'		=> 'Der Pfad, in dem sich phpBB befindet, relativ zum Domainnamen. Z.&nbsp;B. <samp>/phpBB3</samp>.',
	'SELECT_LANG'				=> 'Sprache wählen',
	'SERVER_CONFIG'				=> 'Server-Konfiguration',
	'SEARCH_INDEX_UNCONVERTED'	=> 'Der Suchindex wurde nicht konvertiert',
	'SEARCH_INDEX_UNCONVERTED_EXPLAIN'	=> 'Ihr alter Suchindex wurde nicht konvertiert. Eine Suche wird immer zu einem leeren Ergebnis führen. Um einen neuen Suchindex zu erstellen, gehen Sie in den Administrations-Bereich, wählen Sie dort das Register Wartung aus und rufen Sie dann den Punkt Such-Indizes auf.',
	'SELECT_FORUM_GA'			=> 'In phpBB 3.1 sind Globale Bekanntmachungen mit Foren verknüpft. Wählen Sie ein Forum für Ihre vorhandenen globalen Bekanntmachungen aus (Sie können sie später verschieben):',
	'SOFTWARE'					=> 'Board-Software',
	'SPECIFY_OPTIONS'			=> 'Konvertierungs-Optionen festlegen',
	'STAGE_ADMINISTRATOR'		=> 'Administrator-Details',
	'STAGE_ADVANCED'			=> 'Erweiterte Einstellungen',
	'STAGE_ADVANCED_EXPLAIN'	=> 'Die Einstellungen auf dieser Seite sind nur nötig, wenn Sie wissen, dass sie bei Ihnen vom Standard abweichen. Wenn Sie sich nicht sicher sind, gehen Sie einfach zur nächsten Seite, da die Einstellungen auch noch später im Administrations-Bereich geändert werden können.',
	'STAGE_CONFIG_FILE'			=> 'Konfigurationsdatei',
	'STAGE_CREATE_TABLE'		=> 'Datenbank-Tabellen erstellen',
	'STAGE_CREATE_TABLE_EXPLAIN'	=> 'Die von phpBB 3.1 genutzten Datenbank-Tabellen wurden nun erstellt und mit einigen Ausgangswerten gefüllt. Fahren Sie mit dem nächsten Schritt fort, um die Installation von phpBB abzuschließen.',
	'STAGE_DATABASE'			=> 'Datenbank-Einstellungen',
	'STAGE_FINAL'				=> 'Abschließender Schritt',
	'STAGE_INTRO'				=> 'Einführung',
	'STAGE_IN_PROGRESS'			=> 'Konvertierung wird durchgeführt',
	'STAGE_REQUIREMENTS'		=> 'Voraussetzungen',
	'STAGE_SETTINGS'			=> 'Einstellungen',
	'STARTING_CONVERT'			=> 'Starte Konvertierungsprozess',
	'STEP_PERCENT_COMPLETED'	=> 'Schritt <strong>%d</strong> von <strong>%d</strong>',
	'SUB_INTRO'					=> 'Einführung',
	'SUB_LICENSE'				=> 'Lizenz',
	'SUB_SUPPORT'				=> 'Support',
	'SUCCESSFUL_CONNECT'		=> 'Verbindung erfolgreich',
	'SUPPORT_BODY'				=> 'Für die aktuelle, stabile Version von phpBB3 wird kostenloser Support gewährt. Dies umfasst:</p><ul><li>Installation</li><li>Konfiguration</li><li>Technische Fragen</li><li>Probleme durch eventuelle Fehler in der Software</li><li>Aktualisierung von Release Candidates (RC) oder stabilen Versionen zur aktuellen stabilen Version</li><li>Konvertierungen von phpBB 2.0.x zu phpBB3</li><li>Konvertierung von anderen Forensoftwares zu phpBB3 (Bitte beachten Sie das <a href="https://www.phpbb.com/community/viewforum.php?f=486">Konvertierungs-Forum (englisch)</a> (<a href="https://www.phpbb.de/go/3.1/konvertierung">Konvertierungs-Support auf phpBB.de</a>))</li></ul><p>Wir ermutigen Benutzer, die noch eine Beta-Version von phpBB3 verwenden, ihre Installation mit einer aktuellen Ausgabe von phpBB3 zu ersetzen.</p><h2>Erweiterungen / Styles</h2><p>Fragen zu Erweiterungen stellen Sie bitte im <a href="https://www.phpbb.com/community/viewforum.php?f=451">englischsprachigen Erweiterungen-Forum</a> (<a href="https://www.phpbb.de/go/3.1/extensions">Erweiterungen auf phpBB.de</a>).<br />Fragen bezüglich Styles, Templates und Themes stellen Sie bitte im <a href="https://www.phpbb.com/community/viewforum.php?f=471">englischsprachigen Styles-Forum</a> (<a href="https://www.phpbb.de/go/3.1/styles">Styles auf phpBB.de</a>).<br /><br />Wenn sich Ihre Frage direkt auf ein bestimmtes Paket bezieht, stellen Sie Ihre Frage bitte direkt in dem Thema, das für das Paket vorgesehen ist.</p><h2>Support erhalten</h2><p><a href="https://www.phpbb.com/community/viewtopic.php?f=14&amp;t=571070">Das phpBB Willkommenspaket (englisch)</a><br /><a href="https://www.phpbb.com/support/">Supportbereich (englisch)</a><br /><a href="https://www.phpbb.com/support/docs/en/3.1/ug/quickstart/">Schnellstartanleitung (englisch)</a><br /><a href="https://www.phpbb.de/go/3.1/ersteschritte">Erste Schritte mit phpBB</a><br /><a href="https://www.phpbb.de/go/3.1/support">deutschsprachiger Support auf phpBB.de</a><br /><a href="https://www.phpbb.de/go/3.1/schnellstart">Schnellstartanleitung</a><br /><br />Um immer die neuesten Informationen zu Updates und Veröffentlichungen zu erhalten, sollten Sie sich für den <a href="https://www.phpbb.com/support/">phpBB Newsletter (englisch)</a> anmelden (<a href="https://www.phpbb.de/go/newsletter">deutschsprachiger phpBB.de-Newsletter</a>).<br /><br />',
	'SYNC_FORUMS'				=> 'Beginne, die Foren zu synchronisieren',
	'SYNC_POST_COUNT'			=> 'Synchronisiere post_counts',
	'SYNC_POST_COUNT_ID'		=> 'Synchronisiere post_counts von <var>Eintrag</var> %1$s bis %2$s.',
	'SYNC_TOPICS'				=> 'Beginne, die Themen zu synchronisieren',
	'SYNC_TOPIC_ID'				=> 'Synchronisiere Themen von <var>topic_id</var> %1$s bis %2$s.',

	'TABLES_MISSING'			=> 'Kann diese Tabellen nicht finden<br />» <strong>%s</strong>.',
	'TABLE_PREFIX'				=> 'Präfix der Tabellen in der Datenbank',
	'TABLE_PREFIX_EXPLAIN'		=> 'Der Tabellen-Präfix muss mit einem alphanumerischen Zeichen beginnen und darf nur Buchstaben, Ziffern und Unterstriche enthalten.',
	'TABLE_PREFIX_SAME'			=> 'Das Tabellen-Präfix muss mit dem übereinstimmen, das von der Software, von der Sie konvertieren möchten, genutzt wird.<br />» Das angegebene Tabellen-Präfix lautete %s.',
	'TESTS_PASSED'				=> 'Tests bestanden',
	'TESTS_FAILED'				=> 'Tests nicht bestanden',

	'UNABLE_WRITE_LOCK'			=> 'Die Sperrdatei (lock file) konnte nicht erstellt werden.',
	'UNAVAILABLE'				=> 'Nicht verfügbar',
	'UNWRITABLE'				=> 'Nicht beschreibbar',
	'UPDATE_TOPICS_POSTED'		=> 'Ermittle Informationen über Themen mit eigenen Beiträgen',
	'UPDATE_TOPICS_POSTED_ERR'	=> 'Während der Ermittlung der Informationen über Themen mit eigenen Beiträgen ist ein Fehler aufgetreten. Sie können diesen Vorgang nach der Konvertierung im Administrations-Bereich erneut aufrufen.',
	'VERIFY_OPTIONS'			=> 'Konvertierungs-Einstellungen überprüfen',
	'VERSION'					=> 'Version',

	'WELCOME_INSTALL'			=> 'Willkommen zur phpBB3-Installation',
	'WRITABLE'					=> 'Beschreibbar',
));

// Updater
$lang = array_merge($lang, array(
	'ALL_FILES_UP_TO_DATE'		=> 'Alle Dateien sind auf dem Stand der neuesten phpBB-Version.',
	'ARCHIVE_FILE'				=> 'Quelldatei im Archiv',

	'BACK'				=> 'Zurück',
	'BINARY_FILE'		=> 'Binärdatei',
	'BOT'				=> 'Spider/Robot',

	'CHANGE_CLEAN_NAMES'			=> 'Die Methode, die sicherstellt, dass ein Benutzername nicht von mehreren Benutzern genutzt wird, wurde geändert. Manche Benutzer haben mit der neuen Methode einen identischen Benutzernamen. Sie müssen diese Benutzer löschen oder umbenennen, bevor Sie fortfahren können.',

	'CHECK_FILES'					=> 'Prüfe Dateien',
	'CHECK_FILES_AGAIN'				=> 'Dateien erneut prüfen',
	'CHECK_FILES_EXPLAIN'			=> 'Im nächsten Schritt werden alle Dateien gegen die Update-Dateien geprüft – das kann eine Weile dauern, falls dies die erste Dateiüberprüfung ist.',
	'CHECK_FILES_UP_TO_DATE'		=> 'Laut Ihrer Datenbank ist Ihre Version auf dem neuesten Stand. Sie sollten mit der Dateiüberprüfung fortfahren, um sicher zu gehen, dass alle Dateien wirklich auf dem Stand der aktuellen phpBB-Version sind.',
	'CHECK_UPDATE_DATABASE'			=> 'Update-Prozess fortsetzen',
	'COLLECTED_INFORMATION'			=> 'Datei-Informationen',
	'COLLECTED_INFORMATION_EXPLAIN'	=> 'Die folgende Liste zeigt Ihnen die Dateien, die eine Aktualisierung benötigen. Bitte lesen Sie die Informationen vor jedem Abschnitt durch, um zu verstehen was passiert und was Sie möglicherweise tun müssen, um ein erfolgreiches Update durchzuführen.',
	'COLLECTING_FILE_DIFFS'			=> 'Ermittle Datei-Unterschiede',
	'COMPLETE_LOGIN_TO_BOARD'		=> 'Sie sollten sich jetzt <a href="../ucp.php?mode=login">in Ihrem Forum anmelden</a> und prüfen, ob alles funktioniert. Vergessen Sie nicht, das Installations-Verzeichnis „install“ zu löschen, umzubenennen oder zu verschieben!',
	'CONTINUE_UPDATE_NOW'			=> 'Den Update-Prozess jetzt fortsetzen',		// Shown within the database update script at the end if called from the updater
	'CONTINUE_UPDATE'				=> 'Update jetzt fortsetzen',					// Shown after file upload to indicate the update process is not yet finished
	'CURRENT_FILE'					=> 'Anfang des Konflikts — Inhalt der originalen Datei vor Aktualisierung',
	'CURRENT_VERSION'				=> 'Momentan installierte Version',

	'DATABASE_TYPE'						=> 'Datenbank-Typ',
	'DATABASE_UPDATE_COMPLETE'			=> 'Das Datenbank-Update wurde fertiggestellt!',
	'DATABASE_UPDATE_CONTINUE'			=> 'Mit dem Datenbank-Update fortfahren',
	'DATABASE_UPDATE_INFO_OLD'			=> 'Die Datenbank-Aktualisierungsdatei im install-Verzeichnis ist veraltet. Bitte stellen Sie sicher, dass Sie die korrekte Version der Datei hochgeladen haben.',
	'DATABASE_UPDATE_NOT_COMPLETED'		=> 'Das Datenbank-Update ist noch nicht abgeschlossen.',
	'DELETE_USER_REMOVE'				=> 'Benutzer löschen und Beiträge entfernen',
	'DELETE_USER_RETAIN'				=> 'Benutzer löschen, aber Beiträge beibehalten',
	'DESTINATION'						=> 'Zieldatei',
	'DIFF_INLINE'						=> 'Inline',
	'DIFF_RAW'							=> 'Raw unified diff',
	'DIFF_SEP_EXPLAIN'					=> 'Inhalt der neuen / aktualisierten Datei',
	'DIFF_SIDE_BY_SIDE'					=> 'Side by Side',
	'DIFF_UNIFIED'						=> 'Unified diff',
	'DO_NOT_UPDATE'						=> 'Diese Datei nicht aktualisieren',
	'DONE'								=> 'Erledigt',
	'DOWNLOAD'							=> 'Herunterladen',
	'DOWNLOAD_AS'						=> 'Herunterladen als',
	'DOWNLOAD_UPDATE_METHOD_BUTTON'		=> 'Archiv mit veränderten Dateien herunterladen (empfohlen)',
	'DOWNLOAD_CONFLICTS'				=> 'Konflikte für diese Datei herunterladen',
	'DOWNLOAD_CONFLICTS_EXPLAIN'		=> 'Suchen Sie nach &lt;&lt;&lt;, um die Konflikte zu finden',
	'DOWNLOAD_UPDATE_METHOD'			=> 'Archiv mit veränderten Dateien herunterladen',
	'DOWNLOAD_UPDATE_METHOD_EXPLAIN'	=> 'Nach dem Download sollten Sie das Archiv entpacken. Darin sind die geänderten Dateien enthalten, die Sie in Ihr phpBB-Verzeichnis laden müssen. Bitte laden Sie die Dateien in die entsprechenden Verzeichnisse hoch. Anschließend überprüfen Sie die Dateien bitte noch mal, indem Sie den Anweisungen weiter unten folgen.',

	'EDIT_USERNAME'	=> 'Benutzernamen ändern',
	'ERROR'			=> 'Fehler',
	'EVERYTHING_UP_TO_DATE'		=> 'Es wurde alles auf die aktuelle phpBB-Version aktualisiert. Sie sollten sich jetzt <a href="%1$s">in Ihrem Forum anmelden</a> und prüfen, ob alles funktioniert. Vergessen Sie nicht, das Installations-Verzeichnis „install“ zu löschen, umzubenennen oder zu verschieben! Bitte senden Sie uns aktualisierte Informationen über Ihren Server und Ihre Board-Konfiguration über das <a href="%2$s">Statistik-Übermittlungs</a>-Modul in Ihrem Administrations-Bereich.',

	'FILE_ALREADY_UP_TO_DATE'		=> 'Die Datei ist bereits auf dem neuesten Stand.',
	'FILE_DIFF_NOT_ALLOWED'			=> 'Unterschiedsanzeige für diese Datei nicht möglich.',
	'FILE_USED'						=> 'Informationen benutzt von',			// Single file
	'FILES_CONFLICT'				=> 'Dateien mit Konflikten',
	'FILES_CONFLICT_EXPLAIN'		=> 'Die folgenden Dateien wurden geändert und entsprechen nicht den Originaldateien der alten Version. phpBB hat festgestellt, dass die Dateien nicht zusammengeführt werden können, da beide Versionen verändert wurden. Bitte sehen Sie sich diese Konflikte an und versuchen Sie, sie von Hand zu lösen oder fahren Sie mit der Aktualisierung fort, indem Sie Ihre bevorzugte Methode des Zusammenführens auswählen. Wenn Sie die Konflikte von Hand lösen, prüfen Sie die Dateien nach der Bearbeitung erneut. Sie können außerdem die Zusammenführungsmethode für jede Datei getrennt angeben. Die erste Methode erzeugt eine Datei, in der die problematischen Zeilen Ihrer alten Datei verworfen werden, die andere Methode verwirft die Änderungen in der neuen Datei.',
	'FILES_DELETED'					=> 'Zu löschende Dateien',
	'FILES_DELETED_EXPLAIN'			=> 'Die folgenden Dateien existieren nicht mehr in der neuen Version. Diese Dateien müssen aus Ihrer Installation gelöscht werden.',
	'FILES_MODIFIED'				=> 'Geänderte Dateien',
	'FILES_MODIFIED_EXPLAIN'		=> 'Die folgenden Dateien wurden geändert und entsprechen nicht den Originaldateien der alten Version. In der aktualisierten Version werden Ihre Änderungen und die Neuerungen der phpBB-Datei zusammengeführt.',
	'FILES_NEW'						=> 'Neu hinzuzufügende Dateien',
	'FILES_NEW_EXPLAIN'				=> 'Die folgenden Dateien fehlen in Ihrer Installation. Sie werden dieser hinzugefügt.',
	'FILES_NEW_CONFLICT'			=> 'Neue Dateien, die bereits existieren',
	'FILES_NEW_CONFLICT_EXPLAIN'	=> 'Die folgenden Dateien sind neu innerhalb der aktuellen Version, aber es wurde festgestellt, dass bereits eine Datei mit dem gleichen Namen im gleichen Verzeichnis existiert. Diese wird durch die neue Datei überschrieben.',
	'FILES_NOT_MODIFIED'			=> 'Nicht geänderte Dateien',
	'FILES_NOT_MODIFIED_EXPLAIN'	=> 'Die folgenden Dateien sind nicht geändert und entsprechen den originalen phpBB-Dateien der Version, von der aus Sie updaten möchten.',
	'FILES_UP_TO_DATE'				=> 'Bereits aktualisierte Dateien',
	'FILES_UP_TO_DATE_EXPLAIN'		=> 'Die folgenden Dateien sind bereits auf dem neuesten Stand und müssen nicht aktualisiert werden.',
	'FTP_SETTINGS'					=> 'FTP-Einstellungen',
	'FTP_UPDATE_METHOD'				=> 'FTP-Upload',

	'INCOMPATIBLE_UPDATE_FILES'		=> 'Die gefundenen Update-Dateien sind inkompatibel zu Ihrer installierten Version. Ihre phpBB-Version ist %1$s. Das Update-Paket aktualisiert Version %2$s auf %3$s.',
	'INCOMPLETE_UPDATE_FILES'		=> 'Das Update-Paket ist unvollständig.',
	'INLINE_UPDATE_SUCCESSFUL'		=> 'Die Aktualisierung der Datenbank war erfolgreich. Sie müssen nun den Update-Prozess fortsetzen.',

	'KEEP_OLD_NAME'		=> 'Benutzernamen beibehalten',

	'LATEST_VERSION'		=> 'Neueste Version',
	'LINE'					=> 'Zeile',
	'LINE_ADDED'			=> 'Hinzugefügt',
	'LINE_MODIFIED'			=> 'Verändert',
	'LINE_REMOVED'			=> 'Entfernt',
	'LINE_UNMODIFIED'		=> 'Unverändert',
	'LOGIN_UPDATE_EXPLAIN'	=> 'Sie müssen sich anmelden, um ein Update Ihrer phpBB-Installation durchzuführen.',

	'MAPPING_FILE_STRUCTURE'	=> 'Um den Upload zu erleichtern, finden Sie hier die Dateipfade, die Ihrer phpBB-Installation entsprechen.',

	'MERGE_MODIFICATIONS_OPTION'	=> 'Änderungen zusammenführen',

	'MERGE_NO_MERGE_NEW_OPTION'	=> 'Nicht zusammenführen — neue Datei verwenden',
	'MERGE_NO_MERGE_MOD_OPTION'	=> 'Nicht zusammenführen — vorhandene Datei verwenden',
	'MERGE_MOD_FILE_OPTION'		=> 'Unterschiede zusammenführen (Ignoriert bei Konflikt neuen phpBB-Code)',
	'MERGE_NEW_FILE_OPTION'		=> 'Unterschiede zusammenführen (Ignoriert bei Konflikt geänderten Code)',
	'MERGE_SELECT_ERROR'		=> 'Es wurde keine gültige Methode zum Lösen von Dateikonflikten gewählt.',
	'MERGING_FILES'				=> 'Unterschiede zusammenführen',
	'MERGING_FILES_EXPLAIN'		=> 'Die Dateiänderungen werden abschließend ermittelt.<br /><br />Bitte warten Sie, bis phpBB alle Anpassungen an den geänderten Dateien vorgenommen hat.',

	'NEW_FILE'						=> 'Ende des Konflikts',
	'NEW_USERNAME'					=> 'Neuer Benutzername',
	'NO_AUTH_UPDATE'				=> 'Sie haben keine Berechtigung, das Update durchzuführen',
	'NO_ERRORS'						=> 'Keine Fehler',
	'NO_UPDATE_FILES'				=> 'Die folgenden Dateien werden nicht aktualisiert',
	'NO_UPDATE_FILES_EXPLAIN'		=> 'Die folgenden Dateien sind neu oder wurden verändert. Das Verzeichnis, in dem sie sich normalerweise befinden, konnte jedoch in Ihrer Installation nicht gefunden werden. Wenn diese Liste Dateien in anderen Verzeichnissen als language/ oder styles/ enthält, so haben Sie möglicherweise Ihre Verzeichnissturktur geändert und das Update könnte unvollständig sein.',
	'NO_UPDATE_FILES_OUTDATED'		=> 'Es wurde kein gültiges Aktualisierungsverzeichnis gefunden. Bitte stellen Sie sicher, dass Sie die entsprechenden Dateien hochgeladen haben.<br /><br />Ihre Installation scheint <strong>nicht</strong> auf dem neuesten Stand zu sein. Für Ihre phpBB-Version %1$s sind Updates verfügbar. Bitte besuchen Sie <a href="https://www.phpbb.com/downloads/" rel="external">https://www.phpbb.com/downloads/</a>, um das richtige Packet für das Update von Version %2$s auf Version %3$s herunterzuladen (<a href="https://www.phpbb.de/go/3.1/downloads" rel="external">deutschsprachige Downloadseite</a>).',
	'NO_UPDATE_FILES_UP_TO_DATE'	=> 'Ihre Version ist auf dem neuesten Stand. Es ist nicht nötig, das Update-Tool auszuführen. Wenn Sie eine Integritätsprüfung der Dateien ausführen möchten, stellen Sie sicher, dass Sie das richtige Update-Paket hochgeladen haben.',
	'NO_UPDATE_INFO'				=> 'Information zu den Update-Paketen konnte nicht gefunden werden.',
	'NO_UPDATES_REQUIRED'			=> 'Kein Update notwendig',
	'NO_VISIBLE_CHANGES'			=> 'Keine sichtbaren Änderungen',
	'NOTICE'						=> 'Hinweis',
	'NUM_CONFLICTS'					=> 'Anzahl der Konflikte',
	'NUMBER_OF_FILES_COLLECTED'		=> 'Bisher wurden die Unterschiede aus %1$d von insgesamt %2$d Dateien ermittelt.<br />Bitte warten Sie, bis alle Dateien untersucht wurden.',

	'OLD_UPDATE_FILES'		=> 'Die Update-Dateien sind nicht auf dem neuesten Stand. Die gefundenen Update-Dateien sind für eine Update von phpBB %1$s auf phpBB %2$s, aber die neueste Version von phpBB ist %3$s.',

	'PACKAGE_UPDATES_TO'				=> 'Dieses Paket aktualisiert auf Version',
	'PERFORM_DATABASE_UPDATE'			=> 'Datenbankaktualisierung durchführen',
	'PERFORM_DATABASE_UPDATE_EXPLAIN'	=> 'Weiter unten finden Sie eine Schaltfläche zum Skript für die Datenbank-Aktualisierung. Die Aktualisierung der Datenbank kann eine Weile dauern, also unterbrechen Sie bitte die Ausführung nicht, falls sie zu hängen scheint. Nachdem die Datenbank-Aktualisierung durchgeführt wurde, folgen Sie bitten den Hinweisen, um den Update-Prozess fortzusetzen.',
	'PREVIOUS_VERSION'					=> 'Vorherige Version',
	'PROGRESS'							=> 'Fortschritt',

	'RELEASE_ANNOUNCEMENT'		=> 'Bekanntmachung',
	'RESULT'					=> 'Ergebnis',
	'RUN_DATABASE_SCRIPT'		=> 'Datenbank jetzt aktualisieren',

	'SELECT_DIFF_MODE'			=> 'Unterschiedmodus auswählen',
	'SELECT_DOWNLOAD_FORMAT'	=> 'Format des Download-Archivs wählen',
	'SELECT_FTP_SETTINGS'		=> 'FTP-Einstellungen auswählen',
	'SHOW_DIFF_CONFLICT'		=> 'Unterschiede/Konflikte zeigen',
	'SHOW_DIFF_DELETED'			=> 'Dateiinhalt zeigen',
	'SHOW_DIFF_FINAL'			=> 'Die sich ergebende Datei zeigen',
	'SHOW_DIFF_MODIFIED'		=> 'Zusammengefügte Unterschiede anzeigen',
	'SHOW_DIFF_NEW'				=> 'Dateiinhalte zeigen',
	'SHOW_DIFF_NEW_CONFLICT'	=> 'Unterschiede zeigen',
	'SHOW_DIFF_NOT_MODIFIED'	=> 'Unterschiede zeigen',
	'SOME_QUERIES_FAILED'		=> 'Einige Abfragen sind gescheitert. Die Abfragen und die zugehörigen Fehler sind weiter unten aufgeführt.',
	'SQL'						=> 'SQL',
	'SQL_FAILURE_EXPLAIN'		=> 'Dies ist in der Regel nicht kritisch, die Aktualisierung wird fortgeführt. Sollte deren Fertigstellung scheitern, müssen Sie möglicherweise Hilfe in unserem Supportforum in Anspruch nehmen. Details, wie und wo Sie Hilfe bekommen, können Sie der <a href="../docs/README.html">README-Datei</a> entnehmen.',
	'STAGE_FILE_CHECK'			=> 'Dateien überprüfen',
	'STAGE_UPDATE_DB'			=> 'Datenbank aktualisieren',
	'STAGE_UPDATE_FILES'		=> 'Dateien aktualisieren',
	'STAGE_VERSION_CHECK'		=> 'Versionsprüfung	',
	'STATUS_CONFLICT'			=> 'Geänderte Datei, die Konflikte verursacht',
	'STATUS_DELETED'			=> 'Gelöschte Datei',
	'STATUS_MODIFIED'			=> 'Veränderte Datei',
	'STATUS_NEW'				=> 'Neue Datei',
	'STATUS_NEW_CONFLICT'		=> 'Problematische neue Datei',
	'STATUS_NOT_MODIFIED'		=> 'Unveränderte Datei',
	'STATUS_UP_TO_DATE'			=> 'Bereits aktualisierte Datei',

	'TOGGLE_DISPLAY'			=> 'Datei-Liste ein-/ausblenden',
	'TRY_DOWNLOAD_METHOD'		=> 'Vielleicht möchten Sie versuchen, die veränderten Dateien herunterzuladen.<br />Diese Methode funktioniert immer und ist der empfohlene Weg für ein Update.',
	'TRY_DOWNLOAD_METHOD_BUTTON'=> 'Diese Methode jetzt versuchen',

	'UPDATE_COMPLETED'				=> 'Update abgeschlossen',
	'UPDATE_DATABASE'				=> 'Datenbank jetzt aktualisieren',
	'UPDATE_DATABASE_EXPLAIN'		=> 'Im nächsten Schritt wird die Datenbank aktualisiert.',
	'UPDATE_DATABASE_SCHEMA'		=> 'Datenbankstruktur wird aktualisiert',
	'UPDATE_FILES'					=> 'Dateien jetzt aktualisieren',
	'UPDATE_FILES_NOTICE'			=> 'Bitte stellen Sie sicher, dass Sie auch die Dateien des Boards aktualisiert haben. Diese Datei aktualisiert nur die Datenbank.',
	'UPDATE_INSTALLATION'			=> 'Update der phpBB-Installation',
	'UPDATE_INSTALLATION_EXPLAIN'	=> 'Mit dieser Option können Sie Ihre phpBB-Version auf den neuesten Stand bringen.<br />Während dieses Prozesses wird die Integrität aller Ihrer Dateien überprüft. Sie haben die Möglichkeit, alle Dateiunterschiede vor dem Update zu überprüfen.<br /><br />Die Dateiaktualisierung an sich kann auf zwei Wegen erfolgen:</p><h2>Manuelle Aktualisierung</h2><p>Bei dieser Methode laden Sie nur die von Ihnen geänderten Dateien herunter, damit Sie sichergehen können, dass die erfolgten Dateiänderungen nicht verloren gehen. Nach dem Herunterladen dieses Archivs müssen Sie die Dateien in die entsprechenden Verzeichnisse Ihrer phpBB-Installation hochladen. Nachdem Sie das getan haben, können Sie die Dateiüberprüfung erneut ausführen, um zu sehen, ob Sie alle Dateien korrekt hochgeladen haben.</p><h2>Automatische Aktualisierung über FTP</h2><p>Diese Methode ist der ersten sehr ähnlich, mit dem Unterschied, dass Sie die veränderten Dateien nicht herunter- und anschließend von Hand wieder hochladen müssen. Dies wird automatisch erledigt. Um diese Methode nutzen zu können, müssen Sie Ihre FTP-Anmeldedaten kennen und eingeben. Nach der Fertigstellung wird auch hier eine Integritätsprüfung der Dateien ausgeführt.',
	'UPDATE_INSTRUCTIONS'			=> '

		<h1>Bekanntmachungen zur Veröffentlichung</h1>

		<p>Bitte lesen Sie die Bekanntmachung zur Veröffentlichung (Release announcement) der neuesten Version, bevor Sie den Update-Prozess beginnen, sie enthält wichtige Informationen. Außerdem enthält sie die Download-Links sowie ein Änderungsprotokoll (Changelog) der Versionen.</p>

		<br />

		<h1>Wie Sie ein Update Ihrer Installation mit dem „Automatisches-Update-Paket“ durchführen</h1>

		<p>Diese empfohlene Anleitung zum Update Ihrer Installation gilt nur für das „Automatisches-Update-Paket“ („automatic update package“). Sie können Ihre Installation auch mit den in der INSTALL.html beschriebenen Methoden aktualisieren. Zum automatischen Update von phpBB müssen Sie folgende Schritte ausführen:</p>

		<ul style="margin-left: 20px; font-size: 1.1em;">
			<li>Gehen Sie zur <a href="https://www.phpbb.com/downloads/" title="https://www.phpbb.com/downloads/">phpBB.com-Downloadseite</a> und laden Sie das entsprechende „Automatisches-Update-Paket“ herunter (<a href="https://www.phpbb.de/go/3.1/downloads">deutschsprachige Downloadseite</a>).<br /><br /></li>
			<li>Entpacken Sie das Archiv.<br /><br /></li>
			<li>Laden Sie die entpackten Verzeichnisse „install“ und „vendor“ komplett in Ihr phpBB-Hauptverzeichnis (dort, wo die config.php ist).<br /><br /></li>
		</ul>

		<p>Nach dem Upload wird das Forum vorübergehend für normale Benutzer nicht zugänglich sein, da das von Ihnen hochgeladene Installations-Verzeichnis vorhanden ist.<br /><br />
		<strong><a href="%1$s" title="%1$s">Starten Sie nun den Update-Prozess, indem Sie in Ihrem Webbrowser die Adresse zum Installationsverzeichnis angeben</a>.</strong><br />
		<br />
		Anschließend werden Sie durch den Update-Prozess geführt. Sie werden benachrichtigt, sobald das Update abgeschlossen ist.
		</p>
	',
	'UPDATE_METHOD'					=> 'Aktualisierungs-Methode',
	'UPDATE_METHOD_EXPLAIN'			=> 'Sie können nun Ihre bevorzugte Aktualisierungs-Methode auswählen. Wenn Sie den FTP-Upload wählen, müssen Sie Ihre FTP-Zugangsdaten eingeben. Mit dieser Methode werden die Dateien automatisch an die richtigen Stellen kopiert. Außerdem werden hierbei Sicherheitskopien der alten Dateien erstellt. Bei diesen Sicherheitskopien wird der Dateiname um .bak ergänzt. Wenn Sie auswählen, dass Sie die geänderten Dateien herunterladen möchten, dann müssen Sie sie aus einem Archiv entpacken und später von Hand in das korrekte Verzeichnis hochladen.',
	'UPDATE_REQUIRES_FILE'			=> 'Das Aktualisierungs-Programm benötigt folgende Datei: %s',
	'UPDATE_SUCCESS'				=> 'Die Aktualisierung war erfolgreich',
	'UPDATE_SUCCESS_EXPLAIN'		=> 'Alle Dateien wurden erfolgreich aktualisiert. Der nächste Schritt beinhaltet eine Datei-Prüfung, damit sichergestellt ist, dass die Dateien fehlerfrei aktualisiert wurden.',
	'UPDATE_VERSION_OPTIMIZE'		=> 'Version wird aktualisiert und Tabellen optimiert',
	'UPDATING_DATA'					=> 'Daten werden aktualisiert',
	'UPDATING_TO_LATEST_STABLE'		=> 'Die Datenbank wird auf die neueste Version aktualisiert',
	'UPDATED_VERSION'				=> 'Aktualisierte Version',
	'UPLOAD_METHOD'					=> 'Upload-Methode',

	'UPDATE_DB_SUCCESS'				=> 'Das Update der Datenbank war erfolgreich.',
	'UPDATE_FILE_SUCCESS'			=> 'Das Update der Dateien war erfolgreich.',
	'USER_ACTIVE'					=> 'Aktiver Benutzer',
	'USER_INACTIVE'					=> 'Inaktiver Benutzer',

	'VERSION_CHECK'					=> 'Versionsprüfung',
	'VERSION_CHECK_EXPLAIN'			=> 'Prüft, ob Ihre phpBB-Installation auf dem neuesten Stand ist.',
	'VERSION_NOT_UP_TO_DATE'		=> 'Ihre phpBB-Version ist nicht auf dem neuesten Stand. Bitte fahren Sie mit der Aktualisierung fort.',
	'VERSION_NOT_UP_TO_DATE_ACP'	=> 'Ihre phpBB-Version ist nicht auf dem neuesten Stand<br />Im Folgenden finden Sie einen Link zur Release-Ankündigung der neuesten Version, die weitere Informationen sowie Hinweise, wie Sie Ihre Version aktualisieren können, enthält.',
	'VERSION_NOT_UP_TO_DATE_TITLE'	=> 'Ihre phpBB-Version ist nicht auf dem neuesten Stand.',
	'VERSION_UP_TO_DATE'			=> 'Ihre phpBB-Installation ist auf dem neuesten Stand. Obwohl keine Updates zur Verfügung stehen, können Sie fortfahren, um die Dateien auf Gültigkeit zu überprüfen.',
	'VERSION_UP_TO_DATE_ACP'		=> 'Ihre phpBB-Installation ist auf dem neuesten Stand. Es stehen keine Updates zur Verfügung.',
	'VIEWING_FILE_CONTENTS'			=> 'Dateiinhalte anzeigen',
	'VIEWING_FILE_DIFF'				=> 'Dateiunterschiede anzeigen',

	'WRONG_INFO_FILE_FORMAT'	=> 'Ungültiges Dateiformat',
));

// Default database schema entries...
$lang = array_merge($lang, array(
	'CONFIG_BOARD_EMAIL_SIG'		=> 'Danke, die Board-Administration',
	'CONFIG_SITE_DESC'				=> 'Ein kurzer Text, der Ihr Forum beschreibt',
	'CONFIG_SITENAME'				=> 'ihredomain.tld',

	'DEFAULT_INSTALL_POST'			=> 'Dies ist ein Beispielbeitrag Ihrer phpBB3-Installation. Alles scheint zu funktionieren. Wenn Sie möchten, können Sie diesen Beitrag löschen und mit der Einrichtung Ihres Boards fortfahren. Während des Installationsvorgangs wurden Ihrer ersten Kategorie und Ihrem ersten Forum passende Berechtigungen für die Benutzergruppen Administratoren, Bots, globale Moderatoren, Gäste, Registrierte Benutzer und Registrierte COPPA-Benutzer zugewiesen. Wenn Sie sich entscheiden, auch Ihre erste Kategorie und Ihr erstes Forum zu löschen, dürfen Sie nicht vergessen, den genannten Gruppen entsprechende Rechte für alle neuen Kategorien und Foren, die Sie erstellen, zuzuweisen. Es wird jedoch empfohlen, Ihre erste Kategorie und Ihr erstes Forum umzubenennen und deren Rechte zu übernehmen, wenn neue Kategorien und Foren erstellt werden. Viel Spaß mit phpBB!',

	'FORUMS_FIRST_CATEGORY'			=> 'Ihre erste Kategorie',
	'FORUMS_TEST_FORUM_DESC'		=> 'Beschreibung Ihres ersten Forums.',
	'FORUMS_TEST_FORUM_TITLE'		=> 'Ihr erstes Forum',

	'RANKS_SITE_ADMIN_TITLE'		=> 'Administrator',
	'REPORT_WAREZ'					=> 'Der gemeldete Beitrag enthält Links zu illegaler Software oder Raubkopien.',
	'REPORT_SPAM'					=> 'Der gemeldete Beitrag hat nur zum Ziel, für eine Website oder ein anderes Produkt zu werben.',
	'REPORT_OFF_TOPIC'				=> 'Der gemeldete Beitrag betrifft ein anderes Thema.',
	'REPORT_OTHER'					=> 'Keine der genannten Kategorien. Bitte benutzen Sie „Weitere Informationen“ für Ihre Meldung.',

	'SMILIES_ARROW'					=> 'Pfeil',
	'SMILIES_CONFUSED'				=> 'Verwirrt',
	'SMILIES_COOL'					=> 'Fetzig',
	'SMILIES_CRYING'				=> 'Weinend oder sehr traurig',
	'SMILIES_EMARRASSED'			=> 'Verlegen',
	'SMILIES_EVIL'					=> 'Böse oder sehr verärgert',
	'SMILIES_EXCLAMATION'			=> 'Ausruf',
	'SMILIES_GEEK'					=> 'Computerfreak',
	'SMILIES_IDEA'					=> 'Idee',
	'SMILIES_LAUGHING'				=> 'Lachend',
	'SMILIES_MAD'					=> 'Verärgert',
	'SMILIES_MR_GREEN'				=> 'Mr. Green',
	'SMILIES_NEUTRAL'				=> 'Neutral',
	'SMILIES_QUESTION'				=> 'Frage',
	'SMILIES_RAZZ'					=> 'Hänseln',
	'SMILIES_ROLLING_EYES'			=> 'Augen verdrehen',
	'SMILIES_SAD'					=> 'Traurig',
	'SMILIES_SHOCKED'				=> 'Erschüttert',
	'SMILIES_SMILE'					=> 'Lächeln',
	'SMILIES_SURPRISED'				=> 'Überrascht',
	'SMILIES_TWISTED_EVIL'			=> 'Verrückter Teufel',
	'SMILIES_UBER_GEEK'				=> 'Extremer Computerfreak',
	'SMILIES_VERY_HAPPY'			=> 'Überglücklich',
	'SMILIES_WINK'					=> 'Zwinkern',

	'TOPICS_TOPIC_TITLE'			=> 'Willkommen bei phpBB3!',
));
