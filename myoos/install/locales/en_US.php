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

@setlocale(LC_TIME, 'en_US');
define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS', 'dir="LTR" lang="en"');
define('CHARSET', 'iso-8859-15');
define('INSTALLATION', 'MyOOS [Shopsystem] Installation');

define('BTN_CONTINUE', 'Further');
define('BTN_NEXT', 'Further');
define('BTN_RECHECK', 'repeat');
define('BTN_SET_LANGUAGE', 'Language specify');
define('BTN_START', 'Start');
define('BTN_SUBMIT', 'confirm');
define('BTN_NEW_INSTALL', 'New installation');
define('BTN_UPGARDE', 'Upgrade');
define('BTN_CHANGE_INFO', 'Change info');
define('BTN_LOGIN_SUBMIT', 'Admin install');
define('BTN_SET_LOGIN', 'Further');
define('BTN_FINISH', 'Finish');

define('GREAT', 'Welcome to MyOOS [Shopsystem]!');
define('GREAT_1', 'The MyOOS [Shopsystem] is a comprehensive Internet-Shopping-Solution. This captivates by a particularly high Measure to Adaptability, Speed and high Performance. The MyOOS [Shopsystem] Standard Software is equipped with all basic functions for Online Sales, Order, Payment, Statistics and Administration. Maintenance of the Product data base can be made at any time online. Like that it is ensured that the Customers the most current Online-Offer is always presented.');
define('SELECT_LANGUAGE_1', 'Select your language.');
define('SELECT_LANGUAGE_2', 'Languages: ');

define('DEFAULT_1', 'GNU/GPL License:');
define('DEFAULT_2', 'MyOOS [Shopsystem] is free Software.');
define('DEFAULT_3', 'I Accept the GPL License');

define('METHOD_1', 'To begin the installation, please choose your installation method:');

define('PHP_CHECK_1', 'PHP Diagnosis');
define('PHP_CHECK_2', 'Here we examine the configuration attitudes of its PHP installation. <a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', 'Its PHP version is ');
define('PHP_CHECK_4', 'Please install a current PHP version - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', 'There is not us Problems with its PHP Version Shop Online in connection with OSIS well-known.');
define('PHP_CHECK_6', 'magic_quotes_gpc is Off.');
define('PHP_CHECK_7', 'Insert into its .htaccess file the Following line:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc is On.');
define('PHP_CHECK_9', 'magic_quotes_runtime is On.');
define('PHP_CHECK_10', 'Insert into its .htaccess file the Following line:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime is Off.');
define('PHP_CHECK_12', 'no Graphic-Functions');
define('PHP_CHECK_13', 'For Graphic-Functions you need the GD-Library gd-lib (recommended version 2.0 or more highly) <br />available under - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', 'no truecolor Graphic-Functions');
define('PHP_CHECK_15', 'For Graphic-Functions in MyOOS [Shopsystem] we recommended <br />GD-Library gd-lib Version 2.0 or higher - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', 'The File name straight implemented Scripts, relative to the root listing of the document is not available.');

define('CHMOD_CHECK_1', 'Write rights (CHMOD Check)');
define('CHMOD_CHECK_2', 'It examined, whether the Write rights (CHMOD) are correctly set of configure.php, otherwise this Script able will not be to code the Database information. The Coding of the Database information is an additional Security.');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php is 666 -- RIGHT');
define('CHMOD_CHECK_4', 'Please change Rights of Access (CHMOD 666) for file ~/includes/configure.php ');

define('CHM_CHECK_1', 'Enter please the Database-Information. <br />If no Root Access insists on the Database, no new Databases cannot to be put on - in this case the Database to indicate, into which the Script is to put on the necessary tables');
define('DBINFO', 'Database Information');
define('DBHOST', 'Database - host');
define('DBHOST_DESC', 'The hostname for your database');
define('DBNAME', 'Name of Database');
define('DBNAME_DESC', 'The name of your database');
define('DBPASS', 'Password of Database-User');
define('DBPASS_DESC', 'The password matching the above username');
define('DBPREFIX', 'Database table prefix');
define('DBPREFIX_DESC', 'Table prefix (for Table sharing)');
define('DBTYPE', 'Type of Database');
define('DBTYPE_DESC', 'Database type');
define('DBUNAME', 'Name of Database-User');
define('DBUNAME_DESC', 'The username used to connect to your database');

define('SUBMIT_1', 'Examine please the following information for correctness.');
define('SUBMIT_2', 'The following information was entered:');
define('SUBMIT_3', '<b>New installation</b> or <b>Upgrade</b> selects and/or with <b>changes Info</b> the Data corrects.');

define('CHANGE_INFO_1', 'DB Entrance data change');
define('CHANGE_INFO_2', 'Please correct their ?tabase Entrance data');

define('NEW_INSTALL_1', 'New installation.');
define('NEW_INSTALL_2', 'It became <b>New installation</b> select. <br />Please the following information to examine.');
define('NEW_INSTALL_3', 'Note: <b>new Database</b> put on only select, if root access insists on the Database <br />otherwise the Script the Tables in the existing Database will put on.');
define('NEW_INSTALL_4', 'new Database put on');

define('UPGRADE_1', 'Upgrade');
define('UPGRADE_2', 'The MyOOS [Shopsystem] Database is provided with the following Entrance data:');
define('UPGRADE_3', 'Please select the Shop Version, which you use:');
define('UPGRADE_INFO', 'NOTE: Before the Upgrade an in any case Data protection should be made. There is not a Guarantee for the Function of the Upgrades.');

define('OOSUPGRADE_1', 'MyOOS [Shopsystem]');
define('OOSUPGRADE_2', 'If you use the MyOOS [Shopsystem] 1.0.1, click please up <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'MyOOS [Shopsystem]');
define('OOSUPGRADE_4', 'If you use the MyOOS [Shopsystem] 1.0.2, click please up <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', 'Its MyOOS [Shopsystem] Version can see you in the File <samp>~/shop/includes/version.php</samp>.');

define('MADE', ' provided.');
define('MAKE_DB_1', 'Database could not be provided');
define('MAKE_DB_2', 'one put on.');
define('MAKE_DB_3', 'No Database provides.');
define('MODIFY_FILE_1', 'Error: unable to open for read:');
define('MODIFY_FILE_2', 'Error: unable to open for write:');
define('MODIFY_FILE_3', 'Error: lines changed, did nothing');
define('SHOW_ERROR_INFO', 'Error:</b> MyOOS [Shopsystem] Installation could not into the \'configure.php\' File write. <br /> They can change this File with an Editor. <br />Here the information you to register should:');

define('VIRTUAL_1', 'Web Server');
define('VIRTUAL_2', 'Specify now the Web servers environment for MyOOS [Shopsystem].');
define('VIRTUAL_3', 'SSL-Coding activate');
define('VIRTUAL_4', 'Webserver Root Directory');
define('VIRTUAL_5', 'Webserver Shop Directory');

define('VIRTUAL_7', 'WWW Shop Directory');

define('VIRTUAL_9', 'Template Directory');

define('CONFIG_VIRTUAL_1', 'SSL-Coding');
define('CONFIG_VIRTUAL_2', 'Please control your data:');
define('CONFIG_VIRTUAL_3', 'If the data are correct, click please on <code>further</code>');

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

define('TMP_VIRTUAL_1', 'Session Attitudes');
define('TMP_VIRTUAL_2', 'The Support of Sessions in the MyOOS [Shopsystem] offers the Marketing to hold certain Data during a consequence of calls of your Shop\'s. They cannot between the standard physical files Procedure and the Storage of the Session-Data into your Database select. During Storage into Your Database you cannot additionally still specify whether the Data are to be written coded into these.');
define('TMP_VIRTUAL_3', 'The Session in Files store - activate:');
define('TMP_VIRTUAL_4', 'The Session into Your Database store - activate:');
define('TMP_VIRTUAL_5', 'Session is to be written coded into the Database - to activate:');

define('TMP_CONFIG_VIRTUAL_2', 'Please control Your Data:');
define('TMP_CONFIG_VIRTUAL_3', 'The Session in Files are stored.');
define('TMP_CONFIG_VIRTUAL_4', 'The Session is stored into the Database.');
define('TMP_CONFIG_VIRTUAL_5', 'Coding of the Session-Data:');
define('TMP_SESSION_NON_EXISTENT', 'Warning: The Directory for the Sessions does not exist: ' . session_save_path() . '. The Sessions will not Function to the Directory were provided!');
define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', 'Warning: MyOOS [Shopsystem] cannot write into Sessions Directory: ' . session_save_path() . '. The Sessions will not function to the correct user Authorizations were set!');
define('TMP_ADODB_DIRECTORY', 'Error: Database abstraction the Layer Directory is missing.');
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', 'Error: The logs file is not writeable.');

define('TMP_ADODB_FILE', 'Error: The Logs File does not exist: The Database Error handling will not function in the MyOOS [Shopsystem] to the File was provided!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', 'Error: The logs file is not writeable.');

define('YES', 'activated');
define('NO', 'deactivated');

define('NOTMADE', ' does not provide');
define('NOTUPDATED', '<img src="images/no.gif" alt="ERROR" border="0" align="absmiddle">  ERROR ');
define('UPDATED', 'updated');
define('NOW_104', 'Its MyOOS [Shopsystem] Database was successfully updated!');

define('CONTINUE_1', 'Shop Administrator');
define('CONTINUE_2', 'Specify now the administrator account for MyOOS [Shopsystem]. They can configure later with the email-address and the password its MyOOS [Shopsystem].');
define('CONTINUE_3', 'Please you control your data. A change is later no longer possible!');

define('ADMIN_GENDER', 'Admin Gender');
define('MALE', 'Male');
define('FEMALE', 'Female');
define('ADMIN_FIRSTNAME', 'Admin Firstname');
define('ADMIN_NAME', 'Admin Name');
define('ADMIN_EMAIL', 'Admin E-Mail');
define('ADMIN_PHONE', 'Admin Phone');
define('ADMIN_PASS', 'Admin Password');
define('ADMIN_REPEATPASS', 'Password Repeat');
define('PASSWORD_HIDDEN', '--HIDDEN--');
define('OWP_URL', 'Virtual Path (URL)');
define('ROOT_DIR', 'Webserver Root Directory');
define('ADMIN_INSTALL', 'If the Data are correct, click please on <code>Admin install</code>');
define('PASSWORD_ERROR', 'The \'Admin Password\' and the \'Password Repeat\' must Agree!');
define('ADMIN_ERROR', 'Error:');
define('ADMIN_PASSWORD_ERROR', 'Please give You a \'Admin Password\' !');
define('ADMIN_EMAIL_ERROR', 'Please give You a \'Admin E-Mail\' !');

define('GST_CHECK', '<b>Create country-specific VAT.</b><br><b>Note:</b> Only select if you do not participate in the One-Stop-Shop procedure (OSS).');
define('GST', '<b>Create country-specific VAT</b>');


define('INPUT_DATA', 'Data for MyOOS [Shopsystem] ');

define('FINISH_1', 'Thank saying');
define('FINISH_2', 'On this occasion we would like to thank all, which contributed to the development of MyOOS [Shopsystem]. Our special thanks are entitled to the developers of PHP. ');
define('FINISH_3', 'They successfully installed MyOOS [Shopsystem]. Please delete now installations Directories');
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
define('LANGUAGE_SLV', 'Slovenian');
define('LANGUAGE_SPA', 'Spanish');
define('LANGUAGE_SWE', 'Swedish');

define('FOOTER', 'Diese WebSite wurde mit <a target="_blank" href="https://www.oos-shop.de">MyOOS [Shopsystem]</a> erstellt. <br /><a target="_blank" href="https://www.oos-shop.de">MyOOS [Shopsystem]</a> ist als freie Software unter der <a target="_blank" href="http://www.gnu.org/">GNU/GPL Lizenz</a> erh?tlich.');

define('STEP_1', 'Welcome');
define('STEP_2', 'License');
define('STEP_3', 'Diagnose');
define('STEP_4', 'Datenbank');
define('STEP_5', 'Configuration');
define('STEP_6', 'Session');
define('STEP_7', 'Administrator');
define('STEP_8', 'Finishied');

define('LINK_BACK', 'Back');
define('LINK_TOP', 'Top');
