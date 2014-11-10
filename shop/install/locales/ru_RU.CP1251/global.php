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
  @setlocale(LC_TIME, 'ru'); 
} else {
  @setlocale(LC_TIME, 'ru_RU.ru_RU.CP1251'); 
}

define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
define('DATE_TIME_FORMAT', DATE_FORMAT_LONG . ' %H:%M:%S');

define('HTML_PARAMS','dir="LTR" lang="ru"');
define('CHARSET', 'windows-1251');
define('INSTALLATION', '�����OOS [OSIS Online Shop]');

define('BTN_CONTINUE', '���);
define('BTN_NEXT' ,'���);
define('BTN_RECHECK', '����');
define('BTN_SET_LANGUAGE', '�����');
define('BTN_START','��� �����);
define('BTN_SUBMIT','�����');
define('BTN_NEW_INSTALL', '�� �����);
define('BTN_UPGARDE', '�����');
define('BTN_CHANGE_INFO', '���� ���);
define('BTN_LOGIN_SUBMIT','����� ���);
define('BTN_SET_LOGIN', '���);
define('BTN_FINISH', '����');

define('GREAT', '�������� �OOS [OSIS Online Shop]!');
define('GREAT_1', 'OOS [OSIS Online Shop] � ����������� ����� ��� ����� ������� ����������-���� OOS [OSIS Online Shop] - � ����� ������ �������������� ����, ��� �� ��������� ������� ���� ������, ������� ���, �����, ��������, ���... ��� ������������������� ��������� �������� ���������� �� ���������� ��������');
define('SELECT_LANGUAGE_1', '��������');
define('SELECT_LANGUAGE_2', '��: ');

define('DEFAULT_1', 'GNU/GPL ����:');
define('DEFAULT_2', 'OOS [OSIS Online Shop] � ���� �������� ����� ������');
define('DEFAULT_3', '����� ������);

define('METHOD_1', '����� ���� ��� �����<b>�� �����/b> ��<b>�����</b>');

define('PHP_CHECK_1', '���� PHP');
define('PHP_CHECK_2', '�����PHP � �� ����<a href=\'phpinfo.php\' target=\'_blank\'>PHP Info</a>');
define('PHP_CHECK_3', '��� PHP ');
define('PHP_CHECK_4', '����� ���� ��� PHP. - <a href=\'http://www.php.net\' target=\'_blank\'>http://www.php.net</a>');
define('PHP_CHECK_OK', '������ ��� PHP ����� c [OSIS Online Shop] ');
define('PHP_CHECK_6', 'magic_quotes_gpc ����.');
define('PHP_CHECK_7', '���� �� .htaccess �� ���� ���:<br />php_flag magic_quotes_gpc On');
define('PHP_CHECK_8', 'magic_quotes_gpc ����.');
define('PHP_CHECK_9', 'magic_quotes_runtime ����.');
define('PHP_CHECK_10', '���� �� .htaccess �� ���� ���:<br />php_flag magic_quotes_runtime Off');
define('PHP_CHECK_11', 'magic_quotes_runtime ����.');
define('PHP_CHECK_12', '����� ��� ����); 
define('PHP_CHECK_13', '� ���������GD-����� gd-lib (������ ��� 2.0 ���� <br />���� GD ����� � - <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_14', '����� ��� ����truecolor'); 
define('PHP_CHECK_15', '� �����������OOS [OSIS Online Shop] � ������GD ����� <br />GD-Bibliothek gd-lib ��� 2.0 ����- <a href=\'http://www.boutell.com/gd/\' target=\'_blank\'>http://www.boutell.com/gd/</a>');
define('PHP_CHECK_16', 'PHP_SELF');
define('PHP_CHECK_17', '� ������� ���������� ������ ��� ���� �����������');

define('CHMOD_CHECK_1', '����������� (CHMOD ����)');
define('CHMOD_CHECK_2', '����� ���� �� ����(CHMOD) ���� configure.php �configure-old.php, �� ������������ ������ ����� ����������, ����� ��� ���� ��������');
define('CHMOD_CHECK_3', 'CHMOD ~/includes/configure.php - 666 -- �����);
define('CHMOD_CHECK_4', '����� ����� �������(CHMOD 666) ����~/includes/configure.php ');
define('CHMOD_CHECK_7', 'CHMOD ~/includes/configure-old.php - 666 -- �����);
define('CHMOD_CHECK_8', '����� ����� ��������(CHMOD 666) ���~/includes/configure-old.php ');

define('CHM_CHECK_1', '������� ��� <br /> �� ������� ������� � ���������� � ��� ���� ����� ��� ������ ����������� �� ��� ');
define('DBINFO', '���������� ��');
define('DBHOST', '� �����');
define('DBHOST_DESC', '� ���������);
define('DBNAME', '� �');
define('DBNAME_DESC', '� �� ���);
define('DBPASS', '���');
define('DBPASS_DESC', '��� ������ �� ���);
define('DBPREFIX', '�����');
define('DBPREFIX_DESC', '�������, � ������ ������');
define('DBTYPE', '��� �');
define('DBTYPE_DESC', '��� ���������);
define('DBUNAME', '� ������ �');
define('DBUNAME_DESC', '� ������ �� ���);

define('SUBMIT_1', '����� ���� �������');
define('SUBMIT_2', '� ������� ���);
define('SUBMIT_3', '<b>�� �����/b> ��<b>�����</b> ����� ������� <b>���� �����</b> �������� ���');

define('CHANGE_INFO_1', '���������� ��');
define('CHANGE_INFO_2', ' ���� ������������ ��');
define('NEW_INSTALL_1', '�� �����);
define('NEW_INSTALL_2', '�� ����<b>�� �����</b> ;hlt.<br />����� ���� �������');
define('NEW_INSTALL_3', '����: �� <b>��� �� �� ���/b> ���� ��� �������� �������.');
define('NEW_INSTALL_4', '��� �� �� ���);

define('UPGRADE_1', '�����');
define('UPGRADE_2', '��� ��� ���OOS [OSIS Online Shop] �������� � ���������������� ��:');
define('UPGRADE_3', '���� ������ ��� ����:');
define('UPGRADE_INFO', '����: ���������<b>����� �����/b> ���� ���! � � ������� ���� ����� �����������������!');

define('OOSUPGRADE_1', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_2', '�� � ������OOS [OSIS Online Shop] 1.0.1 ���� ����� <samp>OOS 1.0.1</samp>');
define('OOSUPGRADE_3', 'OOS [OSIS Online Shop]');
define('OOSUPGRADE_4', '�� � ������OOS [OSIS Online Shop] 1.0.2 ���� ����� <samp>OOS 1.0.2</samp>');
define('OOSUPGRADE_5', '��OOS [OSIS Online Shop] ��� � ��� ����� ���� <samp>~/shop/includes/oos_version.php</samp>');

define('MADE', ' ����');
define('MAKE_DB_1', '��� ��� �');
define('MAKE_DB_2', '�� ����');
define('MAKE_DB_3', '�� ���� ����');
define('MODIFY_FILE_1', '���: �� ����� � ���:');

define('MODIFY_FILE_2', '���: �� ����� � ���:');
define('MODIFY_FILE_3', '���: ���� �� ��� ���);
define('SHOW_ERROR_INFO', '���:</b> OOS [OSIS Online Shop] ����� � ��� ���� ������ \'configure.php\' <br /> � ��� ���� �������� �� ���� ���. <br />������� ����� ��� ���:');

define('VIRTUAL_1', '�����');
define('VIRTUAL_2', '�����������C��� � OOS [OSIS Online Shop].');
define('VIRTUAL_3', 'SSL �����');
define('VIRTUAL_4', '���� ����� � ������);
define('VIRTUAL_5', '����� ���� � ������);

define('VIRTUAL_7', 'WWW ����� ����');

define('VIRTUAL_9', '����� ���� � ������);

define('CONFIG_VIRTUAL_1', 'SSL-�����);
define('CONFIG_VIRTUAL_2', '���� �������);
define('CONFIG_VIRTUAL_3', '�� ������ ��� ���� <code>���/code>');


define('INSTALL_WRITE_FILE', 'Attemping to write %s file...');
define('ERROR_TEMPLATE_FILE', '����� ��� �� template!');
define('FILE_WRITE_ERROR', 'Can\'t write to file %s.');
define('COPY_CODE_BELOW', '<br />* Just copy the code below and place it in %s in your %s folder:<b><pre>%s</pre></b>' . "\n");
define('DONE', 'Done');

define('ERROR_NO_HTTPS_SERVER', '���: ��� %s ������);
define('ERROR_NO_DIRECTORY', '���: ����� %s ������);
define('ERROR_NO_INFO', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');
define('INSTALL_REWRITE', 'URL Rewriting');
define('INSTALL_REWRITE_DESC', 'Select which rules you wish to use when generating URLs. Enabling rewrite rules will make pretty URLs for your blog and make it better indexable for spiders like google. The webserver needs to support either mod_rewrite or "AllowOverride All" for your OOS dir. The default setting is auto-detected');

define('HTACCESS_ERROR', 'To check your local webserver installation, serendipity needs to be able to write the file ".htaccess". This was not possible because of permission errors. Please adjust the permissions like this: <br />&nbsp;&nbsp;%s<br />and reload this page.');


define('TMP_VIRTUAL_1', '��������');
define('TMP_VIRTUAL_2', '�����Sessions �OOS [OSIS ��� ���� ����� ����� ��� ���������� �� ������������������� \'s. � ��� ���� �������������� ����� ���� ����� ������ ����� ��� ������� ����� ���� ��� � ������ ����, ��� � ���� ����� �����');
define('TMP_VIRTUAL_3', '���� ��� ����   - ������:');
define('TMP_VIRTUAL_4', '���� ��� ��   - ������:');
define('TMP_VIRTUAL_5', '��� ������ ����� �� - ������:');

define('TMP_CONFIG_VIRTUAL_2', '����� ���� �������');
define('TMP_CONFIG_VIRTUAL_3', '��� ���� ��.');
define('TMP_CONFIG_VIRTUAL_4', '��� ����� ��.');
define('TMP_CONFIG_VIRTUAL_5', '����������.');
define('TMP_SESSION_NON_EXISTENT', '�������: ������ ���! ' . session_save_path() . '. ��� � ���������� �� � ����������');

define('TMP_SESSION_DIRECTORY_NOT_WRITEABLE', '�������: OOS [OSIS Online Shop] ������� ������ ��� ' . session_save_path() . '. ��� � ���������� �� �������������� � ��������!');
define('TMP_ADODB_DIRECTORY', '���: Databaseabstraktions Layer ����� � �����);
define('TMP_ADODB_DIRECTORY_NOT_WRITEABLE', '���: Databaseabstraktions Layer ����� ����� ���.');

define('TMP_ADODB_FILE', '���: Log �� � ����� �������� �� ����OOS [OSIS Online Shop] � ���������� �� � ������ ��!');
define('TMP_ADODB_FILE_NOT_WRITEABLE', '���: Log �� ��� � ���.');

define('YES', '������');
define('NO', '�������');

define('NOTMADE', ' � ����);
define('NOTUPDATED', '<img src="images/no.gif" alt="FEHLER" border="0" align="absmiddle">  ��� ');
define('UPDATED', '�����);
define('NOW_104', '����� �� � OOS [OSIS Online Shop] ��� �����');

define('CONTINUE_1', '�����������');
define('CONTINUE_2', '��� ���� ����������� OOS [OSIS Online Shop]. ���� ��� ������� � OOS [OSIS Online Shop] �E-mail - ���������� ');
define('CONTINUE_3', '����� ���� ���� �����. ������������������!');

define('ADMIN_GENDER', '���� ����');
define('MALE', '����');
define('FEMALE', '����);

define('ADMIN_FIRSTNAME', '� ���');
define('ADMIN_NAME', '��� ���');
define('ADMIN_EMAIL','E-Mail ���');
define('ADMIN_PHONE', '�� ���');
define('ADMIN_FAX', '�� ���');
define('ADMIN_PASS','���� ���');
define('ADMIN_REPEATPASS','������������);
define('PASSWORD_HIDDEN', '--���--');
define('OWP_URL', '�������� (URL)');
define('ROOT_DIR', '�����Root �����');
define('ADMIN_INSTALL', '���� ����� ��������<code>������� ���</code>');
define('PASSWORD_ERROR', '\'Passwort\' �\'�������' ��� ��� ����!');
define('ADMIN_ERROR', '���:');
define('ADMIN_PASSWORD_ERROR', '����� ����� \'Passwort\'');
define('ADMIN_EMAIL_ERROR', '����� ����� \'E-Mail\'');

define('INPUT_DATA', '�� ���OOS [OSIS Online Shop] ');

define('FINISH_1', '������');
define('FINISH_2', '����������� ��������� �� ������� ������ ����OOS [OSIS Online Shop]. ������� ������ �������PHP. ');
define('FINISH_3', '� ��� ����� OOS [OSIS Online Shop]. ��� ��������� ����� ����� install');
define('FINISH_4', 'OOS [OSIS Online Shop] ���);

// All entries use ISO 639-2/T
// http://www.loc.gov/standards/iso639-2/langcodes.html

define('LANGUAGE_DAN', '����);
define('LANGUAGE_NLD', '�������);
define('LANGUAGE_ENG', '�����');
define('LANGUAGE_FIN', '����);
define('LANGUAGE_FRA', '������);
define('LANGUAGE_DEU', '����');
define('LANGUAGE_ITA', '�����);
define('LANGUAGE_NOR', '�����');
define('LANGUAGE_POR', '������');
define('LANGUAGE_RUS', '����);
define('LANGUAGE_SLV', '�������');
define('LANGUAGE_SPA', '�����);
define('LANGUAGE_SWE', '����');

define('FOOTER', '������ �����<a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a> <br /><a target="_blank" href="http://www.oos-shop.de/">OOS [OSIS Online Shop]</a>� ������������������ ���� GPL. <a target="_blank" href="http://www.gnu.org/">GNU/GPL Lizenz</a>');

define('STEP_1', '��������');
define('STEP_2', '����');
define('STEP_3', '����');
define('STEP_4', '�� ���);
define('STEP_5', '������');
define('STEP_6', '���');
define('STEP_7', '�������);
define('STEP_8', '���');

define('LINK_BACK', '���);
define('LINK_TOP', '� ��');

?>
