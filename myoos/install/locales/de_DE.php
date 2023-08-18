<?php
/* ----------------------------------------------------------------------
   $Id: global.php 216 2013-04-02 08:24:45Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: global.php,v 1.17.2.1 2002/04/03 21:03:19 jgm
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Original Author of file: Gregor J. Rothfuss
   Purpose of file: Installer language defines.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (strstr((string) $_ENV["OS"], "Win")) {
    @setlocale(LC_TIME, 'ge');
} else {
    @setlocale(LC_TIME, 'de_DE');
}

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS', 'dir="LTR" lang="de"');
define('CHARSET', 'UTF-8');
define('INSTALLATION', 'MyOOS [Shopsystem] Installation');

define('BTN_CONTINUE', 'Weiter');
define('BTN_NEXT', 'Weiter');
define('BTN_RECHECK', 'wiederholen');
define('BTN_SET_LANGUAGE', 'Sprache festlegen');
define('BTN_START', 'Start');
define('BTN_SUBMIT', 'bestätigen');
define('BTN_NEW_INSTALL', 'Neue Installation');
define('BTN_UPGARDE', 'Upgrade');
define('BTN_CHANGE_INFO', 'Info ändern');
define('BTN_LOGIN_SUBMIT', 'Admin installieren');
define('BTN_SET_LOGIN', 'Weiter');
define('BTN_FINISH', 'Beenden');

define('GREAT', 'Willkommen bei MyOOS [Shopsystem]!');
define('GREAT_1', 'Der MyOOS [Shopsystem] ist eine umfassende Internet-Shopping-Lösung. Diese besticht durch ein besonders hohes Ma&szlig; an Anpassungsfähigkeit, Schnelligkeit und hohe Performance. Die OOS [OSIS Online-Shop] Standard Software ist mit allen Grundfunktionen für Online- Verkauf, Bestellung, Bezahlung, Statistik und Administration ausgestattet. Die Wartung der Produktdatenbank kann jederzeit online vorgenommen werden. So ist gewährleistet, dass den Kunden stets das aktuellste Online-Angebot präsentiert wird.');
define('SELECT_LANGUAGE_1', 'Auswahl Ihrer Sprache.');
define('SELECT_LANGUAGE_2', 'Sprachen: ');

define('DEFAULT_1', 'GNU/GPL License:');
define('DEFAULT_2', 'MyOOS [Shopsystem] ist freie Software.');
define('DEFAULT_3', 'Ich akzeptiere die GPL License');

define('METHOD_1', 'Bitte wählen Sie <b>Neue Installation</b> oder <b>Upgrade</b>');

define('PHP_CHECK_1', 'PHP Diagnose');
define('PHP_CHECK_2', 'Hier prüfen wir die Konfigurationseinstellungen Ihrer PHP Installation. <a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', 'Ihre PHP Version ist ');
define('PHP_CHECK_4', 'Bitte installieren Sie eine aktuelle PHP Version - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', 'Es sind uns keine Probleme mit Ihrer PHP Version in Verbindung mit MyOOS [Shopsystem] bekannt.');
define('PHP_CHECK_6', 'magic_quotes_gpc is Off.');
define('PHP_CHECK_7', 'Tragen Sie in Ihre .htaccess Datei folgende Zeile ein:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc is ON.');
define('PHP_CHECK_9', 'magic_quotes_runtime is On.');
define('PHP_CHECK_10', 'Tragen Sie in Ihre .htaccess Datei folgende Zeile ein:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime is Off.');
define('PHP_CHECK_12', 'keine Grafik-Funktionen');
define('PHP_CHECK_13', 'Für die Grafik-Funktionen benötigen Sie die GD-Bibliothek gd-lib (empfohlen Version 2.0 oder höher) <br />verfügbar unter - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', 'keine truecolor Grafik-Funktionen');
define('PHP_CHECK_15', 'Für die Grafik-Funktionen im MyOOS [Shopsystem] empfehlen wir Ihnen die <br />GD-Bibliothek gd-lib Version 2.0 oder höher - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', 'Der Dateiname des gerade ausgeführten Skripts, relativ zum Wurzel-Verzeichnis des Dokuments ist nicht verfügbar.');

define('CHMOD_CHECK_1', 'Schreibrechte (CHMOD Check)');
define('CHMOD_CHECK_2', 'Es wird überprüft, ob die Schreibrechte (CHMOD) von configure.php korrekt gesetzt sind, ansonsten wird dieses Skript nicht in der Lage sein, die Datenbank-Informationen zu verschlüsseln. Die Verschlüsselung der Datenbank-Informationen ist eine zusätzliche Sicherheit.');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php ist 666 -- RICHTIG');
define('CHMOD_CHECK_4', 'Bitte ändern Sie die Zugriffsrechte (CHMOD 666) der Datei ~/includes/configure.php ');
define('CHMOD_CHECK_7', 'CHMOD ~/includes/configure-old.php ist 666 -- RICHTIG');


define('CHM_CHECK_1', 'Bitte die Datenbank-Informationen eingeben. <br />Falls kein Root-Zugriff auf die Datenbank besteht, können keine neuen Datenbanken angelegt werden - in diesem Fall die Datenbank angeben, in die das Skript die erforderlichen Tabellen anlegen soll');
define('DBINFO', 'Datenbank Information');
define('DBHOST', 'Datenbank - Servername');
define('DBHOST_DESC', 'Datenbank - Servername');
define('DBNAME', 'Datenbankname');
define('DBNAME_DESC', 'Name der Datenbank');
define('DBPASS', 'Datenbank Passwort');
define('DBPASS_DESC', 'Passwort des Datenbank-Benutzers');
define('DBPREFIX', 'Datenbank-Präfix');
define('DBPREFIX_DESC', 'Tabellenpräfix (für Table-Sharing)');
define('DBTYPE', 'Datenbanktyp');
define('DBTYPE_DESC', 'Datenbanktyp');
define('DBUNAME', 'Name des Datenbank-Benutzers');
define('DBUNAME_DESC', 'Name des Datenbank-Benutzers');

define('SUBMIT_1', 'Bitte die folgenden Informationen auf Korrektheit überprüfen.');
define('SUBMIT_2', 'Folgende Informationen wurden eingegeben:');
define('SUBMIT_3', '<b>Neue Installation</b> oder <b>Upgrade</b> wählen bzw. mit <b>Info ändern</b> die Angaben korrigieren.');

define('CHANGE_INFO_1', 'DB Zugangsdaten ändern');
define('CHANGE_INFO_2', 'Bitte korrigieren Sie Ihre Datenbank Zugangsdaten');
define('NEW_INSTALL_1', 'Neuinstallation.');
define('NEW_INSTALL_2', 'Es wurde <b>Neuinstallation</b> gewählt.<br />Bitte die folgenden Informationen überprüfen.');
define('NEW_INSTALL_3', 'Hinweis: <b>neue Datenbank anlegen</b> nur anwählen, falls Root-Zugriff auf die Datenbank besteht -<br />andernfalls wird das Skript die Tabellen in der angebenen Datenbank anlegen.');
define('NEW_INSTALL_4', 'neue Datenbank anlegen');

define('UPGRADE_1', 'Uprade');
define('UPGRADE_2', 'Die MyOOS [Shopsystem] Datenbank wird mit folgenden Zugangsdaten erstellt:');
define('UPGRADE_3', 'Bitte wählen Sie die Shop Version, die Sie verwenden:');
define('UPGRADE_INFO', 'HINWEIS: Vor dem Upgrade sollte auf <b>jeden Fall eine Datensicherung</b> vorgenommen werden. Es besteht keine Gewährleistung für die Funktion des Upgrades.');

define('OOSUPGRADE_1', 'MyOOS [Shopsystem]');
define('OOSUPGRADE_2', 'Wenn Sie den MyOOS [Shopsystem] 1.0.1 verwenden, klicken Sie bitte auf <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'MyOOS [Shopsystem]');
define('OOSUPGRADE_4', 'Wenn Sie den MyOOS [Shopsystem] 1.0.2 verwenden, klicken Sie bitte auf <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', 'Ihre MyOOS [Shopsystem] Version können Sie in der Datei <samp>~/shop/includes/version.php</samp> ersehen.');

define('MADE', ' erstellt.');
define('MAKE_DB_1', 'Datenbank konnte nicht erstellt werden');
define('MAKE_DB_2', 'wurde angelegt.');
define('MAKE_DB_3', 'Keine Datenbank erstellt.');
define('MODIFY_FILE_1', 'Error: unable to open for read:');

define('MODIFY_FILE_2', 'Error: unable to open for write:');
define('MODIFY_FILE_3', 'Error: lines changed, did nothing');
define('SHOW_ERROR_INFO', 'Fehler:</b> MyOOS [Shopsystem] Installation konnte nicht in die \'configure.php\' Datei  schreiben. <br /> Sie können mit einem EditOR diese Datei selbst ändern. <br />Hier die Informationen  die Sie eintragen sollten:');

define('VIRTUAL_1', 'Web Server');
define('VIRTUAL_2', 'Legen Sie nun die WebServer Umgebung für MyOOS [Shopsystem] fest.');
define('VIRTUAL_3', 'SSL-Verschlüsselung aktivieren');
define('VIRTUAL_4', 'Webserver Root Directory');
define('VIRTUAL_5', 'Webserver Shop Directory');

define('VIRTUAL_7', 'WWW Shop Directory');

define('VIRTUAL_9', 'Template Directory');

define('CONFIG_VIRTUAL_1', 'SSL-Verschlüsselung');
define('CONFIG_VIRTUAL_2', 'Bitte kontrollieren Sie Ihre Angaben:');
define('CONFIG_VIRTUAL_3', 'Sind die Angaben korrekt, klicken Sie bitte auf <code>weiter</code>');

define('INSTALL_WRITE_FILE', 'Probiere die Datei %s zu erstellen...');
define('ERROR_TEMPLATE_FILE', 'Die Templatedatei konnte nicht geöffnet werden! Bitte Prfen Sie die Pfade und/oder Leserechte');
define('FILE_WRITE_ERROR', 'Datei %s kann nicht geschrieben werden.');
define('COPY_CODE_BELOW', '<br />* Kopieren Sie den folgenden Code in die Datei %s des %s Verzeichnisses:<b><pre>%s</pre></b>' . "\n");
define('DONE', 'Fertig');

define('ERROR_NO_HTTPS_SERVER', 'Fehler: Server %s existiert nicht.');
define('ERROR_NO_DIRECTORY', 'Fehler: Verzeichnis %s existiert nicht.');
define('ERROR_NO_INFO', 'Notwendige Angaben fehlen!<br/>Bitte richtig ausfüllen.<br/><br/>');
define('INSTALL_REWRITE', 'URL-Formung');
define('INSTALL_REWRITE_DESC', 'Wählen Sie die Methode zur Erzeugung der URLs. Wenn diese aktiviert werden, können sprechende Namen für jeden Artikel verwendet werden und somit besser von Suchmaschinen indiziert. Der Webserver muss entweder mod_rewrite oder die "AllowOverride All"-Direktive unterstützen. Der Standardwert wird automagisch bestimmt');

define('HTACCESS_ERROR', 'Um die Webserverkonfiguration zu testen benötigt MyOOS [Shopsystem] die Möglichkeit, die Datei ".htaccess" zu erstellen. Dies war aufgrund von Rechteproblemen nicht möglich. Bitte passen Sie die Rechte wie folgt an: <br />&nbsp;&nbsp;%s<br />und laden Sie diese Seite neu.');

define('TMP_VIRTUAL_1', 'Session Einstellungen');
define('TMP_VIRTUAL_2', 'Die Unterstützung von Sessions im MyOOS [Shopsystem] bietet die Möglichkeit, bestimmte Daten während einer Folge von Aufrufen Ihres Shop\'s festzuhalten. Sie können zwischen der standardmä&szlig;igen files Prozedur und der Speicherung der Session-Daten in Ihre Datenbank wählen. Bei der Speicherung in Ihre Datenbank können Sie zusätzlich noch festlegen, ob die Daten verschlüsselt in diese geschrieben werden sollen.');
define('TMP_VIRTUAL_3', 'Die Session in Files speichern   - aktivieren:');
define('TMP_VIRTUAL_4', 'Die Session in Ihre Datenbank speichern   - aktivieren:');
define('TMP_VIRTUAL_5', 'Session soll verschlüsselt in die Datenbank geschrieben werden - aktivieren:');

define('TMP_CONFIG_VIRTUAL_2', 'Bitte kontrollieren Sie Ihre Angaben:');
define('TMP_CONFIG_VIRTUAL_3', 'Die Session werden in Dateien gespeichert.');
define('TMP_CONFIG_VIRTUAL_4', 'Die Session wird in die Datenbank gespeichert.');
define('TMP_CONFIG_VIRTUAL_5', 'Verschlüsselung der Session-Daten:');
define('TMP_SESSION_NON_EXISTENT', 'Warnung: Das Verzeichnis für die Sessions existiert nicht: ' . session_save_path() . '. Die Sessions werden nicht funktionieren bis das Verzeichnis erstellt wurde!');

define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warnung: MyOOS [Shopsystem] kann nicht in das Sessions Verzeichnis schreiben: ' . session_save_path() . '. Die Sessions werden nicht funktionieren bis die richtigen Benutzerberechtigungen gesetzt wurden!');
define('TMP_ADODB_DIRECTORY', 'Fehler: Das Datenbankabstraktions Layer Verzeichnis ist nicht vorhanden.');
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Datenbankabstraktions Layer Verzeichnis ist schreibgeschützt.');

define('TMP_ADODB_FILE', 'Fehler: Die Log Datei existiert nicht: Die Datenbank Fehlerbehandlung wird im MyOOS [Shopsystem] nicht funktionieren bis die Datei erstellt wurde!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', 'Fehler: Die Log Datei ist schreibgeschützt.');

define('YES', 'aktiviert');
define('NO', 'deaktiviert');

define('NOTMADE', ' nicht erstellt');
define('NOTUPDATED', '<img src="images/no.gif" alt="FEHLER" border="0" align="absmiddle">  FEHLER ');
define('UPDATED', 'aktualisiert');
define('NOW_104', 'Ihre MyOOS [Shopsystem] Datenbank wurde erfolgreich aktualisiert!');

define('CONTINUE_1', 'Shop Administrator');
define('CONTINUE_2', 'Legen Sie nun den Administrator-Account für MyOOS [Shopsystem] fest. Sie können später mit der Email - Adresse und dem Passwort Ihren MyOOS [Shopsystem] konfigurieren.');
define('CONTINUE_3', 'Bitte kontrollieren Sie Ihre Angaben. Eine Änderung ist später nicht mehr möglich!');

define('ADMIN_GENDER', 'Admin Anrede');
define('MALE', 'Herr');
define('FEMALE', 'Frau');

define('ADMIN_FIRSTNAME', 'Admin Vorname');
define('ADMIN_NAME', 'Admin Name');
define('ADMIN_EMAIL', 'Admin E-Mail');
define('ADMIN_PHONE', 'Admin Telefon');
define('ADMIN_PASS', 'Admin Passwort');
define('ADMIN_REPEATPASS', 'Passwort bestätigen');
define('PASSWORD_HIDDEN', '--VERSTECKT--');
define('OWP_URL', 'Virtual Path (URL)');
define('ROOT_DIR', 'Webserver Root Directory');
define('ADMIN_INSTALL', 'Sind die Angaben korrekt, klicken Sie bitte auf <code>Admin installieren</code>');
define('PASSWORD_ERROR', 'Das \'Passwort\' und die \'Bestätigung\' müssen übereinstimmen!');
define('ADMIN_ERROR', 'Fehler:');
define('ADMIN_PASSWORD_ERROR', 'Bitte geben Sie ein \'Passwort\' ein!');
define('ADMIN_EMAIL_ERROR', 'Bitte geben Sie Ihre \'E-Mail Adresse\' ein!');

define('GST_CHECK', '<b>Länderbezogene Umsatzsteuer anlegen</b><br><b>Hinweis:</b> Nur anwählen, wenn Sie <b>nicht</b> am <br>One-Stop-Shop-Verfahren (OSS) teilnehmen.');
define('GST', '<b>Länderbezogene Umsatzsteuer anlegen</b>');


define('INPUT_DATA', 'Daten für MyOOS [Shopsystem] ');

define('FINISH_1', 'Danksagung');
define('FINISH_2', 'Bei dieser Gelegenheit möchten wir allen danken, die zur Entwicklung von MyOOS [Shopsystem] beigetragen haben. Unser spezieller Dank gebührt den Entwicklern  von PHP. ');
define('FINISH_3', 'Sie haben MyOOS [Shopsystem] erfolgreich installiert. Bitte löschen Sie nun das Installations Verzeichnis');
define('FINISH_4', 'MyOOS [Shopsystem] Admin');

// All entries use ISO 639-2/T
// http://www.loc.gov/standards/iso639-2/langcodes.html

define('LANGUAGE_DAN', 'Danish');
define('LANGUAGE_NLD', 'Dutch');
define('LANGUAGE_ENG', 'English');
define('LANGUAGE_FIN', 'Finnish');
define('LANGUAGE_FRA', 'French');
define('LANGUAGE_DEU', 'Deutsch');
define('LANGUAGE_ITA', 'Italian');
define('LANGUAGE_NOR', 'Norwegian');
define('LANGUAGE_POR', 'Portuguese');
define('LANGUAGE_RUS', 'Russian');
define('LANGUAGE_SLV', 'Slovenian');
define('LANGUAGE_SPA', 'Spanish');
define('LANGUAGE_SWE', 'Swedish');

define('FOOTER', 'Diese WebSite wurde mit <a target="_blank" href="https://www.oos-shop.de">MyOOS [Shopsystem]</a> erstellt. <br /><a target="_blank" href="https://www.oos-shop.de">MyOOS [Shopsystem]</a> ist als freie Software unter der <a target="_blank" href="http://www.gnu.org/">GNU/GPL Lizenz</a> erhätlich.');

define('STEP_1', 'Willkommen');
define('STEP_2', 'License');
define('STEP_3', 'Diagnose');
define('STEP_4', 'Datenbank');
define('STEP_5', 'Configuration');
define('STEP_6', 'Session');
define('STEP_7', 'Administrator');
define('STEP_8', 'Fertig');

define('LINK_BACK', 'Zurück');
define('LINK_TOP', 'Nach Oben');
