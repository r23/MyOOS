<?php
/**
   ----------------------------------------------------------------------
   $Id: eng.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('PLUGIN_EVENT_MAIL_NAME', 'Send E-Mails');
define('PLUGIN_EVENT_MAIL_DESC', 'Send out e-mails');

define('SEND_EXTRA_ORDER_EMAILS_TO_TITLE', 'Send Extra Order Emails To');
define('SEND_EXTRA_ORDER_EMAILS_TO_DESC', 'Send extra order emails to the following email addresses, in this format: Name 1 &lt;email@address1&gt;');

define('EMAIL_TRANSPORT_TITLE', 'E-Mail Transport Method.');
define('EMAIL_TRANSPORT_DESC', 'Defines if this server uses a local connection to sendmail or uses an SMTP connection via TCP/IP. Servers running on Windows and MacOS should change this setting to SMTP.');

define('EMAIL_LINEFEED_TITLE', 'E-Mail Linefeeds');
define('EMAIL_LINEFEED_DESC', 'Defines the character sequence used to separate mail headers.');

define('EMAIL_USE_HTML_TITLE', 'Use MIME HTML When Sending Emails');
define('EMAIL_USE_HTML_DESC', 'Send e-mails in HTML format');

define('ENTRY_EMAIL_ADDRESS_CHECK_TITLE', 'Verify E-Mail Addresses Through DNS');
define('ENTRY_EMAIL_ADDRESS_CHECK_DESC', 'Verify e-mail address through a DNS server');

define('OOS_SMTPAUTH_TITLE', 'Sets SMTP authentication.');
define('OOS_SMTPAUTH_DESC', ' Utilizes the Username and Password variables.');

define('OOS_SMTPUSER_TITLE', 'SMTP username');
define('OOS_SMTPUSER_DESC', 'SMTP username');

define('OOS_SMTPPASS_TITLE', 'SMTP password');
define('OOS_SMTPPASS_DESC', 'SMTP password');

define('OOS_SMTPHOST_TITLE', 'Sets the SMTP hosts.');
define('OOS_SMTPHOST_DESC', 'smtp.example.com');

define('OOS_SENDMAIL_TITLE', 'Sets the path of the sendmail program');
define('OOS_SENDMAIL_DESC', '/var/qmail/bin/sendmail');

define('OOS_SMTPENCRYPTION_TITLE', 'Type of encryption');
define('OOS_SMTPENCRYPTION_DESC', 'none, SSL, TTS');

define('OOS_SMTPPORT_TITLE', 'SMTP-Port');
define('OOS_SMTPPORT_DESC', '');