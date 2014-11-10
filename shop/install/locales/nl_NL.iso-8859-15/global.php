<?php 
/* ----------------------------------------------------------------------
   $Id: global.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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
  @setlocale(LC_TIME, 'nl'); 
} else {
  @setlocale(LC_TIME, 'nl_NL'); 
}

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS','dir="LTR" lang="nl"');
define('CHARSET', 'iso-8859-15');
define('INSTALLATION', 'OOS [OSIS Online Shop] Installatie');

define('BTN_CONTINUE', 'Volgende');
define('BTN_NEXT' ,'Volgende');
define('BTN_RECHECK', 'Herhaal');
define('BTN_SET_LANGUAGE', 'Taal bepalen');
define('BTN_START','Accoord');
define('BTN_SUBMIT','Bevestigen');
define('BTN_NEW_INSTALL', 'Nieuwe installatie');
define('BTN_UPGARDE', 'Opwaarderen');
define('BTN_CHANGE_INFO', 'Wijzig gegevens');
define('BTN_LOGIN_SUBMIT','Beheerder installeren');
define('BTN_SET_LOGIN', 'Volgende');
define('BTN_FINISH', 'Beeindigen');

define('GREAT', 'Welkom bij OOS [OSIS Online Shop]!');
define('GREAT_1', 'De OOS [OSIS Online Shop] is een alles omvattende internetwinkel-oplossing. Deze is opgebouwd uit een combinatie van een hoge mate aan aanpassingsmogelijkheden, snelheid en uitvoering. De OSIS Online-Shop standaard software is met alle basisfuncties  voor online-verkoop, bestelling, betaling, statistiek en administratie  uitgevoerd. Het onderhoud van de produktendatabank kan ieder moment online worden uitgevoerd. Zo is men er van verzekerd, dat de klant steeds het actueelste online-aanbod  gepresenteerd krijgt.');
define('SELECT_LANGUAGE_1', 'Uw taalkeuze.');
define('SELECT_LANGUAGE_2', 'Talen: ');

define('DEFAULT_1', 'GNU/GPL Licentie:');
define('DEFAULT_2', 'OOS [OSIS Online Shop] is vrije software.');
define('DEFAULT_3', 'Ik accepteer de GPL Licentie');

define('METHOD_1', 'Kies a.u.b. <b>Nieuwe installatie</b> of <b>Opwaarderen</b>');

define('PHP_CHECK_1', 'PHP Diagnose');
define('PHP_CHECK_2', 'Hier controleren we de configuratieinstellingen van de PHP installatie. <a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', 'Uw PHP versie is ');
define('PHP_CHECK_4', 'Installeer a.u.b. een actuele PHP versie - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', 'Er zijn ons geen problemen met uw PHP versie in verband met OOS [OSIS Online Shop] bekend.');
define('PHP_CHECK_6', 'magic_quotes_gpc is Off.');
define('PHP_CHECK_7', 'Voeg aan uw .htaccess bestand de volgende regel toe:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc is ON.');
define('PHP_CHECK_9', 'magic_quotes_runtime is On.');
define('PHP_CHECK_10', 'Voeg aan uw .htaccess bestand de volgende regel toe:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime is Off.');
define('PHP_CHECK_12', 'Geen grafische funkties'); 
define('PHP_CHECK_13', 'Voor de grafische funkties heeft u de GD-Bibliotheek gd-lib (aanbevolen wordt versie 2.0 of hoger) <br />beschikbaar op - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', 'geen truecolor grafische functies'); 
define('PHP_CHECK_15', 'Voor de grafische funkties in OOS [OSIS Online Shop] bevelen we de <br />GD-Bibliotheek gd-lib version 2.0 of hoger aan - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', 'De bestandsnaam van het net uitgevoerde script, relatief naar de root aanduiging van het dokument is niet beschikbaar.');

define('CHMOD_CHECK_1', 'Schrijftoegang (CHMOD Check)');
define('CHMOD_CHECK_2', 'Er wordt gecontroleerd of de toegangsrechten (CHMOD) van configure.php und configure-old.php correct ingesteld zijn, anders zal dit script niet in staat zijn, de databankinformatie te versleutelen. De versleuteling van de databankinformatie is een extra zekerheid.');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php is 666 -- JUIST');
define('CHMOD_CHECK_4', 'Verander a.u.b. de toegangsrechten (CHMOD 666) van  ~/includes/configure.php ');
define('CHMOD_CHECK_7', 'CHMOD ~/includes/configure-old.php ist 666 -- JUIST');
define('CHMOD_CHECK_8', 'Verander a.u.b. de toegangsrechten (CHMOD 666) van ~/includes/configure-old.php ');

define('CHM_CHECK_1', 'A.u.b de databankinformatie invoeren. <br />Als er geen roottoegang tot de databank is, kan er geen nieuwe databank aangemaakt worden - in dat geval de databank aangeven, waarvan het script de nodige tabellen aanmaken moet');
define('DBINFO', 'Databank informatie');
define('DBHOST', 'Databank - host');
define('DBHOST_DESC', 'The hostname for your database');
define('DBINFO', 'Databank informatie');
define('DBNAME', 'Naam van de databank');
define('DBNAME_DESC', 'The name of your database');
define('DBPASS', 'Wachtwoord van de databank-gebruiker');
define('DBPASS_DESC', 'The password matching the above username');
define('DBPREFIX', 'Tabellenvoorzetsel (voor tabelindeling)');
define('DBPREFIX_DESC', 'Table prefix (for Table sharing)');
define('DBTYPE', 'Database type');
define('DBTYPE_DESC', 'Database type');
define('DBUNAME', 'Naam van de databank-gebruiker');
define('DBUNAME_DESC', 'The username used to connect to your database');

define('SUBMIT_1', 'Graag de volgende gegevens op juistheid controleren.');
define('SUBMIT_2', 'Volgende gegevens werden ingevoerd:');
define('SUBMIT_3', '<b>Nieuwe installatie</b> of <b>Opwaardering</b> kiezen resp. met <b>Info veranderen</b> de gegevens corrigeren.');

define('CHANGE_INFO_1', 'DB toegangsgegevens veranderen');
define('CHANGE_INFO_2', 'A.u.b. corrigeert u uw databank toegangsgegevens');
define('NEW_INSTALL_1', 'Nieuwe installatie.');
define('NEW_INSTALL_2', 'Er werd <b>Nieuwe installatie</b> gekozen.<br />A.u.b. volgende gegevens controleren.');
define('NEW_INSTALL_3', 'OPMEREKING: <b>Nieuwe databank aanmaken</b> alleen kiezen, als Root-toegang naar de databank bestaat -<br />anders zal het script de tabellen in de aangegeven databank aanmaken.');
define('NEW_INSTALL_4', 'Nieuwe databank aanmaken');

define('UPGRADE_1', 'Opwaardering');
define('UPGRADE_2', 'De  databank werd met de volgende toegangsgegevens aangemaakt:');
define('UPGRADE_3', 'Kies a.u.b. de winkelversie u gebruikt.');
define('UPGRADE_INFO', 'OPMERKING: Voor de opwaardering moet u in <b>iedere geval een data backup</b> uitvoering. Er bestaat geen zekerheid voor de opwaardering.');

define('OOSUPGRADE_1', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_2', 'Als u OOS [OSIS Online Shop] 1.0.1 gebruikt, klik op <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_4', 'Als u OOS [OSIS Online Shop] 1.0.2 gebruikt, klik op <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', 'Uw OOS [OSIS Online Shop] versie kan u in het bestand <samp>~/shop/includes/oos_version.php</samp> terugvinden.');

define('MADE', ' aangemaakt.');
define('MAKE_DB_1', 'Databank kon niet aangemaakt worden');
define('MAKE_DB_2', 'Werd aangemaakt.');
define('MAKE_DB_3', 'Geen databank aangemaakt.');
define('MODIFY_FILE_1', 'Fout: niet mogelijk om voor lezen te openen:');

define('MODIFY_FILE_2', 'Fout: niet mogelijk om voor schrijven te openen:');
define('MODIFY_FILE_3', 'Fout: regels veranderd, niets gebeurd');
define('SHOW_ERROR_INFO', 'Fout:</b> OOS [OSIS Online Shop] installatie kon niet in het \'configure.php\' bestand schrijven. <br /> U kan met een editor dit bestand zelf veranderen. <br />Hier is de informatie  die u invoeren moet:');

define('VIRTUAL_1', 'Web Server');
define('VIRTUAL_2', 'Bepaal nu de WebServer omgeving voor OOS [OSIS Online Shop].');
define('VIRTUAL_3', 'SSL-Versleuteling activeren');
define('VIRTUAL_4', 'Webserver rootmap');
define('VIRTUAL_5', 'Webserver winkelmap');

define('VIRTUAL_7', 'Winkelmap');

define('VIRTUAL_9', 'Sjabloonmap');

define('CONFIG_VIRTUAL_1', 'SSL-Versleuteling');
define('CONFIG_VIRTUAL_2', 'Controleer a.u.b. uw gegevens:');
define('CONFIG_VIRTUAL_3', 'Zijn de gegevens correct, klik dan op <code>Volgende</code>');

define('INSTALL_WRITE_FILE', 'Attemping to write %s file...');
define('ERROR_TEMPLATE_FILE', 'Unable to open template file!');
define('FILE_WRITE_ERROR', 'Can\'t write to file %s.');
define('COPY_CODE_BELOW', '<br />* Just copy the code below and place it in %s in your %s folder:<b><pre>%s</pre></b>' . "\n");
define('DONE', 'Done');

define('ERROR_NO_HTTPS_SERVER', 'Error: Server %s does not exist');
define('ERROR_NO_DIRECTORY', 'Error: Directory %s does not exist');
define('ERROR_NO_INFO', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');
define('INSTALL_REWRITE', 'URL Rewriting');
define('INSTALL_REWRITE_DESC', 'Select which rules you wish to use when generating URLs. Enabling rewrite rules will make pretty URLs for your blog and make it better indexable for spiders like google. The webserver needs to support either mod_rewrite or "AllowOverride All" for your OOS dir. The default setting is auto-detected');

define('HTACCESS_ERROR', 'To check your local webserver installation, serendipity needs to be able to write the file ".htaccess". This was not possible because of permission errors. Please adjust the permissions like this: <br />&nbsp;&nbsp;%s<br />and reload this page.');


define('TMP_VIRTUAL_1', 'Sessie instellingen');
define('TMP_VIRTUAL_2', 'De ondersteuning van sessies in OOS [OSIS Online Shop] biedt de mogelijkheid, bepaalde gegevens tijdens een serie van aanmeldingen in uw winkel vast te houden. U kan tussen de standard bestandsopslag en de sessieopslag in de databank kiezen. Bij de opslag in de databank kan u nog extra kiezen, of de gegevens versleutelt moeten worden weggeschreven.');
define('TMP_VIRTUAL_3', 'De session in bestanden opslaan   - activeren:');
define('TMP_VIRTUAL_4', 'De session in de databank opslaan   - activeren:');
define('TMP_VIRTUAL_5', 'Sessie moet versleutelt in de databank geschreven worden - activeren:');

define('TMP_CONFIG_VIRTUAL_2', 'Controleer uw gegevens:');
define('TMP_CONFIG_VIRTUAL_3', 'De sessie wordt in bestanden opgeslagen.');
define('TMP_CONFIG_VIRTUAL_4', 'De sessie wordt in de databank opgeslagen.');
define('TMP_CONFIG_VIRTUAL_5', 'Versleuteling van de sessie-gegevens:');
define('TMP_SESSION_NON_EXISTENT', 'Waarschuwing: De loactie voor sesies bestaat niet: ' . session_save_path() . '. De Sessies zullen niet functioneren tot de locatie aangemaakt is!');

define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', 'Waarschuwing: OOS [OSIS Online Shop] kan geen sessie schrijven: ' . session_save_path() . '. De sessies zullen niet functioneren totdat de juiste gebruikersrechten aangemaakt zijn!');
define('TMP_ADODB_DIRECTORY', 'Fout: De databankabstractielaag aanduiding bestaat niet.');
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', 'Fout: Het databank abstactielaag is schrijfbeschermd.');

define('TMP_ADODB_FILE', 'Fout: Het logbestand bestaat niet: De databank foutafhandeling zal in OOS [OSIS Online Shop] niet functioneren totdat het bestand is aangemaakt!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', 'Fout: Het logbestand is schrijfbeschermd.');

define('YES', 'geactiveerd');
define('NO', 'gedeactiveerd');

define('NOTMADE', ' niet aangemaakt');
define('NOTUPDATED', '<img src="images/no.gif" alt="FOUT" border="0" align="absmiddle">  FOUT ');
define('UPDATED', 'geactualiseerd');
define('NOW_104', 'Uw OOS [OSIS Online Shop] databank werd succesvol geactualiseerd');

define('CONTINUE_1', 'Winkelbeheerder');
define('CONTINUE_2', 'Leg nu het beheerdersaccount vast. U kan later met het Emailadres en het wachtwoord uw OOS [OSIS Online Shop] configureren.');
define('CONTINUE_3', 'Controleer a.u.b. uw gegevens. Een verandering is later niet meer mogelijk!');

define('ADMIN_GENDER', 'Beheerder aanspeektitel');
define('MALE', 'De heer');
define('FEMALE', 'Mevrouw');

define('ADMIN_FIRSTNAME', 'Beheerder voornaam');
define('ADMIN_NAME', 'Beheerder achternaam');
define('ADMIN_EMAIL','Beheerder email');
define('ADMIN_PHONE', 'Beheerder telefoon');
define('ADMIN_FAX', 'Beheerder fax');
define('ADMIN_PASS','Beheerder wachtwoord');
define('ADMIN_REPEATPASS','Wachtwoord bevestigen');
define('PASSWORD_HIDDEN', '--VERBORGEN--');
define('OWP_URL', 'Virtueel Pad (URL)');
define('ROOT_DIR', 'Webserver rootmap');
define('ADMIN_INSTALL', 'Zijn de gegevens juist, klik dan op <code>Beheerder installeren</code>');
define('PASSWORD_ERROR', 'Het \'wachtwoord\' en de \'bevestiging\' moeten overeenkomen!');
define('ADMIN_ERROR', 'Fout:');
define('ADMIN_PASSWORD_ERROR', 'Voer a.u.b. een \'wachtwoord\' in!');
define('ADMIN_EMAIL_ERROR', 'Voer a.u.b. uw \'Emailadres\' in!');

define('INPUT_DATA', 'Gegevens voor OOS [OSIS Online Shop] ');

define('FINISH_1', 'Dankwoord');
define('FINISH_2', 'Bij deze gelegenheid willen wij een ieder bedanken die aan de ontwikkeling van OOS [OSIS Online Shop] heeft bijgedragen. Onze speciale dank gaat uit naar de ontwikkelaars van PHP.');
define('FINISH_3', 'U heeft OOS [OSIS Online Shop] succesvol geinstalleerd. Wis nu a.u.b. de installatiemap');
define('FINISH_4', 'OOS [OSIS Online Shop] Beheerder');

// All entries use ISO 639-2/T
// http://www.loc.gov/standards/iso639-2/langcodes.html

define('LANGUAGE_DAN', 'Dansk');
define('LANGUAGE_NLD', 'Nederlands');
define('LANGUAGE_ENG', 'English');
define('LANGUAGE_FIN', 'Fins');
define('LANGUAGE_FRA', 'Francais');
define('LANGUAGE_DEU', 'Deutsch');
define('LANGUAGE_ITA', 'Italiano');
define('LANGUAGE_NOR', 'Norge');
define('LANGUAGE_POR', 'Portugaise');
define('LANGUAGE_SLV', 'Sloveens');
define('LANGUAGE_SPA', 'Espanol');
define('LANGUAGE_SWE', 'Sverge');

define('FOOTER', 'Deze website werd met <a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> gemaakt. <br /><a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> is als vrije software onder de <a target="_blank" href="http://www.gnu.org/">GNU/GPL Licentie</a> beschikbaar.');

define('STEP_1', 'Welkom');
define('STEP_2', 'Licentie');
define('STEP_3', 'Diagnose');
define('STEP_4', 'Databank');
define('STEP_5', 'Configuratie');
define('STEP_6', 'Sessie');
define('STEP_7', 'Beheerder');
define('STEP_8', 'Klaar');

define('LINK_BACK', 'Terug');
define('LINK_TOP', 'Naar boven');
?>
