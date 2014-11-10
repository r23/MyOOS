<?php
/* ----------------------------------------------------------------------
   $Id: nld.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('PLUGIN_EVENT_MAIL_NAME', 'Verstuur emails');
define('PLUGIN_EVENT_MAIL_DESC', 'Verstuur emails');

define('SEND_EXTRA_ORDER_EMAILS_TO_TITLE', 'Extra orderbevestiging  per e-mail versturen');
define('SEND_EXTRA_ORDER_EMAILS_TO_DESC', 'Verstuurt extra orderbevestiging naar volgend e-mailadres, in deze opstelling: Naam 1 &lt;email@adres1&gt;');

define('SEND_BANKINFO_TO_ADMIN_TITLE', 'Bankinfo per Mail');
define('SEND_BANKINFO_TO_ADMIN_DESC', 'Wilt u de bankgegevens van de vervoerder met de E-mail ontvangen?');

define('EMAIL_TRANSPORT_TITLE', 'Email transportmethode');
define('EMAIL_TRANSPORT_DESC', 'Defini&euml;erd of deze server de lokale verbinding naar sendmail gebruikt of een SMTP verbinding via TCP/IP. Servers op Windows en MacOS moeten dit veranderen in SMTP.');

define('EMAIL_LINEFEED_TITLE', 'Email linefeeds');
define('EMAIL_LINEFEED_DESC', 'Defini&euml;erd het karakterpatroon dat gebruikt wordt om mailheaders te scheiden.');

define('EMAIL_USE_HTML_TITLE', 'Gebruik MIME HTML bij versturen emails');
define('EMAIL_USE_HTML_DESC', 'Verstuur emails in HTML formaat');

define('ENTRY_EMAIL_ADDRESS_CHECK_TITLE', 'Verifi&euml;r email adres via DNS');
define('ENTRY_EMAIL_ADDRESS_CHECK_DESC', 'Verifi&euml;r eailadres via een DNS server');

define('OOS_SMTPAUTH_TITLE', 'Sets SMTP authentication.');
define('OOS_SMTPAUTH_DESC', ' Utilizes the Username and Password variables.');

define('OOS_SMTPUSER_TITLE', 'SMTP username');
define('OOS_SMTPUSER_DESC', 'SMTP username');

define('OOS_SMTPPASS_TITLE', 'SMTP password');
define('OOS_SMTPPASS_DESC', 'SMTP password');

define('OOS_SMTPHOST_TITLE', 'Sets the SMTP hosts.');
define('OOS_SMTPHOST_DESC', 'All hosts must be separated by a semicolon.  You can also specify a different port for each host by using this format: [hostname:port]  (e.g. "smtp1.example.com:25;smtp2.example.com"). Hosts will be tried in order.');

define('OOS_SENDMAIL_TITLE', 'Sets the path of the sendmail program');
define('OOS_SENDMAIL_DESC', '/var/qmail/bin/sendmail');
?>
