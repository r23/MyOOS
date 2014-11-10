<?php 
/* ----------------------------------------------------------------------
   $Id: global.php 216 2013-04-02 08:24:45Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

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

if (strstr($_ENV["OS"],"Win")) {
  @setlocale(LC_TIME, 'ge'); 
} else {
  @setlocale(LC_TIME, 'de_DE'); 
}

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS','dir="LTR" lang="de"');
define('CHARSET', 'UTF-8');
define('INSTALLATION', 'myOOS [OSIS Online Shop] Installation');

define('BTN_CONTINUE', 'Weiter');
define('BTN_NEXT' ,'Weiter');
define('BTN_RECHECK', 'wiederholen');
define('BTN_SET_LANGUAGE', 'Sprache festlegen');
define('BTN_START','Start');
define('BTN_SUBMIT','best&auml;tigen');
define('BTN_NEW_INSTALL', 'Neue Installation');
define('BTN_UPGARDE', 'Upgrade');
define('BTN_CHANGE_INFO', 'Info &auml;ndern');
define('BTN_LOGIN_SUBMIT','Admin installieren');
define('BTN_SET_LOGIN', 'Weiter');
define('BTN_FINISH', 'Beenden');

define('GREAT', 'Willkommen bei OOS [OSIS Online Shop]!');
define('GREAT_1', 'Der OOS [OSIS Online Shop] ist eine umfassende Internet-Shopping-L&ouml;sung. Diese besticht durch ein besonders hohes Ma&szlig; an Anpassungsf&auml;higkeit, Schnelligkeit und hohe Performance. Die OOS [OSIS Online-Shop] Standard Software ist mit allen Grundfunktionen f&uuml;r Online- Verkauf, Bestellung, Bezahlung, Statistik und Administration ausgestattet. Die Wartung der Produktdatenbank kann jederzeit online vorgenommen werden. So ist gew&auml;hrleistet, dass den Kunden stets das aktuellste Online-Angebot pr&auml;sentiert wird.');
define('SELECT_LANGUAGE_1', 'Auswahl Ihrer Sprache.');
define('SELECT_LANGUAGE_2', 'Sprachen: ');

define('DEFAULT_1', 'GNU/GPL License:');
define('DEFAULT_2', 'OOS [OSIS Online Shop] ist freie Software.');
define('DEFAULT_3', 'Ich akzeptiere die GPL License');

define('METHOD_1', 'Bitte w&auml;hlen Sie <b>Neue Installation</b> oder <b>Upgrade</b>');

define('PHP_CHECK_1', 'PHP Diagnose');
define('PHP_CHECK_2', 'Hier pr&uuml;fen wir die Konfigurationseinstellungen Ihrer PHP Installation. <a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', 'Ihre PHP Version ist ');
define('PHP_CHECK_4', 'Bitte installieren Sie eine aktuelle PHP Version - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', 'Es sind uns keine Probleme mit Ihrer PHP Version in Verbindung mit OOS [OSIS Online Shop] bekannt.');
define('PHP_CHECK_6', 'magic_quotes_gpc is Off.');
define('PHP_CHECK_7', 'Tragen Sie in Ihre .htaccess Datei folgende Zeile ein:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc is ON.');
define('PHP_CHECK_9', 'magic_quotes_runtime is On.');
define('PHP_CHECK_10', 'Tragen Sie in Ihre .htaccess Datei folgende Zeile ein:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime is Off.');
define('PHP_CHECK_12', 'keine Grafik-Funktionen'); 
define('PHP_CHECK_13', 'F&uuml;r die Grafik-Funktionen ben&ouml;tigen Sie die GD-Bibliothek gd-lib (empfohlen Version 2.0 oder h&ouml;her) <br />verf&uuml;gbar unter - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', 'keine truecolor Grafik-Funktionen'); 
define('PHP_CHECK_15', 'F&uuml;r die Grafik-Funktionen im OOS [OSIS Online Shop] empfehlen wir Ihnen die <br />GD-Bibliothek gd-lib Version 2.0 oder h&ouml;her - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', 'Der Dateiname des gerade ausgef&uuml;hrten Skripts, relativ zum Wurzel-Verzeichnis des Dokuments ist nicht verf&uuml;gbar.');

define('CHMOD_CHECK_1', 'Schreibrechte (CHMOD Check)');
define('CHMOD_CHECK_2', 'Es wird &uuml;berpr&uuml;ft, ob die Schreibrechte (CHMOD) von configure.php korrekt gesetzt sind, ansonsten wird dieses Skript nicht in der Lage sein, die Datenbank-Informationen zu verschl&uuml;sseln. Die Verschl&uuml;sselung der Datenbank-Informationen ist eine zus&auml;tzliche Sicherheit.');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php ist 666 -- RICHTIG');
define('CHMOD_CHECK_4', 'Bitte &auml;ndern Sie die Zugriffsrechte (CHMOD 666) der Datei ~/includes/configure.php ');
define('CHMOD_CHECK_7', 'CHMOD ~/includes/configure-old.php ist 666 -- RICHTIG');


define('CHM_CHECK_1', 'Bitte die Datenbank-Informationen eingeben. <br />Falls kein Root-Zugriff auf die Datenbank besteht, k&ouml;nnen keine neuen Datenbanken angelegt werden - in diesem Fall die Datenbank angeben, in die das Skript die erforderlichen Tabellen anlegen soll');
define('DBINFO', 'Datenbank Information');
define('DBHOST', 'Datenbank - Servername');
define('DBHOST_DESC', 'Datenbank - Servername');
define('DBNAME', 'Datenbankname');
define('DBNAME_DESC', 'Name der Datenbank');
define('DBPASS', 'Datenbank Passwort');
define('DBPASS_DESC', 'Passwort des Datenbank-Benutzers');
define('DBPREFIX', 'Datenbank-Pr&auml;fix');
define('DBPREFIX_DESC', 'Tabellenpr&auml;fix (f&uuml;r Table-Sharing)');
define('DBTYPE', 'Datenbanktyp');
define('DBTYPE_DESC', 'Datenbanktyp');
define('DBUNAME', 'Name des Datenbank-Benutzers');
define('DBUNAME_DESC', 'Name des Datenbank-Benutzers');

define('SUBMIT_1', 'Bitte die folgenden Informationen auf Korrektheit &uuml;berpr&uuml;fen.');
define('SUBMIT_2', 'Folgende Informationen wurden eingegeben:');
define('SUBMIT_3', '<b>Neue Installation</b> oder <b>Upgrade</b> w&auml;hlen bzw. mit <b>Info &auml;ndern</b> die Angaben korrigieren.');

define('CHANGE_INFO_1', 'DB Zugangsdaten &auml;ndern');
define('CHANGE_INFO_2', 'Bitte korrigieren Sie Ihre Datenbank Zugangsdaten');
define('NEW_INSTALL_1', 'Neuinstallation.');
define('NEW_INSTALL_2', 'Es wurde <b>Neuinstallation</b> gew&auml;hlt.<br />Bitte die folgenden Informationen &uuml;berpr&uuml;fen.');
define('NEW_INSTALL_3', 'Hinweis: <b>neue Datenbank anlegen</b> nur anw&auml;hlen, falls Root-Zugriff auf die Datenbank besteht -<br />andernfalls wird das Skript die Tabellen in der angebenen Datenbank anlegen.');
define('NEW_INSTALL_4', 'neue Datenbank anlegen');

define('UPGRADE_1', 'Uprade');
define('UPGRADE_2', 'Die OOS [OSIS Online Shop] Datenbank wird mit folgenden Zugangsdaten erstellt:');
define('UPGRADE_3', 'Bitte w&auml;hlen Sie die Shop Version, die Sie verwenden:');
define('UPGRADE_INFO', 'HINWEIS: Vor dem Upgrade sollte auf <b>jeden Fall eine Datensicherung</b> vorgenommen werden. Es besteht keine Gew&auml;hrleistung f&uuml;r die Funktion des Upgrades.');

define('OOSUPGRADE_1', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_2', 'Wenn Sie den OOS [OSIS Online Shop] 1.0.1 verwenden, klicken Sie bitte auf <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_4', 'Wenn Sie den OOS [OSIS Online Shop] 1.0.2 verwenden, klicken Sie bitte auf <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', 'Ihre OOS [OSIS Online Shop] Version k&ouml;nnen Sie in der Datei <samp>~/shop/includes/oos_version.php</samp> ersehen.');

define('MADE', ' erstellt.');
define('MAKE_DB_1', 'Datenbank konnte nicht erstellt werden');
define('MAKE_DB_2', 'wurde angelegt.');
define('MAKE_DB_3', 'Keine Datenbank erstellt.');
define('MODIFY_FILE_1', 'Error: unable to open for read:');

define('MODIFY_FILE_2', 'Error: unable to open for write:');
define('MODIFY_FILE_3', 'Error: lines changed, did nothing');
define('SHOW_ERROR_INFO', 'Fehler:</b> OOS [OSIS Online Shop] Installation konnte nicht in die \'configure.php\' Datei  schreiben. <br /> Sie k&ouml;nnen mit einem EditOR diese Datei selbst &auml;ndern. <br />Hier die Informationen  die Sie eintragen sollten:');

define('VIRTUAL_1', 'Web Server');
define('VIRTUAL_2', 'Legen Sie nun die WebServer Umgebung f&uuml;r OOS [OSIS Online Shop] fest.');
define('VIRTUAL_3', 'SSL-Verschl&uuml;sselung aktivieren');
define('VIRTUAL_4', 'Webserver Root Directory');
define('VIRTUAL_5', 'Webserver Shop Directory');

define('VIRTUAL_7', 'WWW Shop Directory');

define('VIRTUAL_9', 'Template Directory');

define('CONFIG_VIRTUAL_1', 'SSL-Verschl&uuml;sselung');
define('CONFIG_VIRTUAL_2', 'Bitte kontrollieren Sie Ihre Angaben:');
define('CONFIG_VIRTUAL_3', 'Sind die Angaben korrekt, klicken Sie bitte auf <code>weiter</code>');

define('INSTALL_WRITE_FILE', 'Probiere die Datei %s zu erstellen...');
define('ERROR_TEMPLATE_FILE', 'Die Templatedatei konnte nicht ge&ouml;ffnet werden! Bitte Prfen Sie die Pfade und/oder Leserechte');
define('FILE_WRITE_ERROR', 'Datei %s kann nicht geschrieben werden.');
define('COPY_CODE_BELOW', '<br />* Kopieren Sie den folgenden Code in die Datei %s des %s Verzeichnisses:<b><pre>%s</pre></b>' . "\n");
define('DONE', 'Fertig');

define('ERROR_NO_HTTPS_SERVER', 'Fehler: Server %s existiert nicht.');
define('ERROR_NO_DIRECTORY', 'Fehler: Verzeichnis %s existiert nicht.');
define('ERROR_NO_INFO', 'Notwendige Angaben fehlen!<br/>Bitte richtig ausf&uuml;llen.<br/><br/>');
define('INSTALL_REWRITE', 'URL-Formung');
define('INSTALL_REWRITE_DESC', 'W&auml;hlen Sie die Methode zur Erzeugung der URLs. Wenn diese aktiviert werden, k&ouml;nnen sprechende Namen f&uuml;r jeden Artikel verwendet werden und somit besser von Suchmaschinen indiziert. Der Webserver muss entweder mod_rewrite oder die "AllowOverride All"-Direktive unterst&uuml;tzen. Der Standardwert wird automagisch bestimmt');

define('HTACCESS_ERROR', 'Um die Webserverkonfiguration zu testen ben&ouml;tigt OOS [OSIS Online Shop] die M&ouml;glichkeit, die Datei ".htaccess" zu erstellen. Dies war aufgrund von Rechteproblemen nicht m&ouml;glich. Bitte passen Sie die Rechte wie folgt an: <br />&nbsp;&nbsp;%s<br />und laden Sie diese Seite neu.');

define('TMP_VIRTUAL_1', 'Session Einstellungen');
define('TMP_VIRTUAL_2', 'Die Unterst&uuml;tzung von Sessions im OOS [OSIS Online Shop] bietet die M&ouml;glichkeit, bestimmte Daten w&auml;hrend einer Folge von Aufrufen Ihres Shop\'s festzuhalten. Sie k&ouml;nnen zwischen der standardm&auml;&szlig;igen files Prozedur und der Speicherung der Session-Daten in Ihre Datenbank w&auml;hlen. Bei der Speicherung in Ihre Datenbank k&ouml;nnen Sie zus&auml;tzlich noch festlegen, ob die Daten verschl&uuml;sselt in diese geschrieben werden sollen.');
define('TMP_VIRTUAL_3', 'Die Session in Files speichern   - aktivieren:');
define('TMP_VIRTUAL_4', 'Die Session in Ihre Datenbank speichern   - aktivieren:');
define('TMP_VIRTUAL_5', 'Session soll verschl&uuml;sselt in die Datenbank geschrieben werden - aktivieren:');

define('TMP_CONFIG_VIRTUAL_2', 'Bitte kontrollieren Sie Ihre Angaben:');
define('TMP_CONFIG_VIRTUAL_3', 'Die Session werden in Dateien gespeichert.');
define('TMP_CONFIG_VIRTUAL_4', 'Die Session wird in die Datenbank gespeichert.');
define('TMP_CONFIG_VIRTUAL_5', 'Verschl&uuml;sselung der Session-Daten:');
define('TMP_SESSION_NON_EXISTENT', 'Warnung: Das Verzeichnis f&uuml;r die Sessions existiert nicht: ' . session_save_path() . '. Die Sessions werden nicht funktionieren bis das Verzeichnis erstellt wurde!');

define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warnung: OOS [OSIS Online Shop] kann nicht in das Sessions Verzeichnis schreiben: ' . session_save_path() . '. Die Sessions werden nicht funktionieren bis die richtigen Benutzerberechtigungen gesetzt wurden!');
define('TMP_ADODB_DIRECTORY', 'Fehler: Das Datenbankabstraktions Layer Verzeichnis ist nicht vorhanden.');
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', 'Fehler: Das Datenbankabstraktions Layer Verzeichnis ist schreibgesch&uuml;tzt.');

define('TMP_ADODB_FILE', 'Fehler: Die Log Datei existiert nicht: Die Datenbank Fehlerbehandlung wird im OOS [OSIS Online Shop] nicht funktionieren bis die Datei erstellt wurde!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', 'Fehler: Die Log Datei ist schreibgesch&uuml;tzt.');

define('YES', 'aktiviert');
define('NO', 'deaktiviert');

define('NOTMADE', ' nicht erstellt');
define('NOTUPDATED', '<img src="images/no.gif" alt="FEHLER" border="0" align="absmiddle">  FEHLER ');
define('UPDATED', 'aktualisiert');
define('NOW_104', 'Ihre OOS [OSIS Online Shop] Datenbank wurde erfolgreich aktualisiert!');

define('CONTINUE_1', 'Shop Administrator');
define('CONTINUE_2', 'Legen Sie nun den Administrator-Account f&uuml;r OOS [OSIS Online Shop] fest. Sie k&ouml;nnen sp&auml;ter mit der Email - Adresse und dem Passwort Ihren OOS [OSIS Online Shop] konfigurieren.');
define('CONTINUE_3', 'Bitte kontrollieren Sie Ihre Angaben. Eine &Auml;nderung ist sp&auml;ter nicht mehr m&ouml;glich!');

define('ADMIN_GENDER', 'Admin Anrede');
define('MALE', 'Herr');
define('FEMALE', 'Frau');

define('ADMIN_FIRSTNAME', 'Admin Vorname');
define('ADMIN_NAME', 'Admin Name');
define('ADMIN_EMAIL','Admin E-Mail');
define('ADMIN_PHONE', 'Admin Telefon');
define('ADMIN_FAX', 'Admin Fax');
define('ADMIN_PASS','Admin Passwort');
define('ADMIN_REPEATPASS','Passwort best&auml;tigen');
define('PASSWORD_HIDDEN', '--VERSTECKT--');
define('OWP_URL', 'Virtual Path (URL)');
define('ROOT_DIR', 'Webserver Root Directory');
define('ADMIN_INSTALL', 'Sind die Angaben korrekt, klicken Sie bitte auf <code>Admin installieren</code>');
define('PASSWORD_ERROR', 'Das \'Passwort\' und die \'Best&auml;tigung\' m&uuml;ssen &uuml;bereinstimmen!');
define('ADMIN_ERROR', 'Fehler:');
define('ADMIN_PASSWORD_ERROR', 'Bitte geben Sie ein \'Passwort\' ein!');
define('ADMIN_EMAIL_ERROR', 'Bitte geben Sie Ihre \'E-Mail Adresse\' ein!');

define('INPUT_DATA', 'Daten f&uuml;r OOS [OSIS Online Shop] ');

define('FINISH_1', 'Danksagung');
define('FINISH_2', 'Bei dieser Gelegenheit m&ouml;chten wir allen danken, die zur Entwicklung von OOS [OSIS Online Shop] beigetragen haben. Unser spezieller Dank geb&uuml;hrt den Entwicklern  von PHP. ');
define('FINISH_3', 'Sie haben OOS [OSIS Online Shop] erfolgreich installiert. Bitte l&ouml;schen Sie nun das Installations Verzeichnis');
define('FINISH_4', 'OOS [OSIS Online Shop] Admin');

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

define('FOOTER', 'Diese WebSite wurde mit <a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> erstellt. <br /><a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> ist als freie Software unter der <a target="_blank" href="http://www.gnu.org/">GNU/GPL Lizenz</a> erh&auml;tlich.');

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
