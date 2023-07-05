<?php
/**
   ----------------------------------------------------------------------
   $Id: deu.php,v 1.1 2007/06/12 17:11:55 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('PLUGIN_EVENT_MAIL_NAME', 'Sende E-Mails');
define('PLUGIN_EVENT_MAIL_DESC', 'Verschicke E-Mails');

define('SEND_EXTRA_ORDER_EMAILS_TO_TITLE', 'Zus&auml;tzliche Bestellbest&auml;tigung per Mail senden');
define('SEND_EXTRA_ORDER_EMAILS_TO_DESC', 'Sendet zus&auml;tzliche Bestellbenachrichtigungen an folgende E-Mail Adresse, in diesem Format: Name 1 &lt;email@adresse1&gt;');

define('EMAIL_TRANSPORT_TITLE', 'E-Mail Versandmethode');
define('EMAIL_TRANSPORT_DESC', 'Definiert, ob dieser Server eine lokale Verbindung zu sendmail oder eine SMTP-Verbindung &uuml;ber TCP/IP benutzt. Bei Servern, die unter Windows oder MacOS laufen sollte SMTP eingetragen werden.');

define('EMAIL_LINEFEED_TITLE', 'E-Mail Linefeeds');
define('EMAIL_LINEFEED_DESC', 'Defines the character sequence used to separate mail headers.');

define('EMAIL_USE_HTML_TITLE', 'Benutze MIME HTML beim Versand von E-Mails');
define('EMAIL_USE_HTML_DESC', 'Sende E-Mails im HTML-Format');

define('ENTRY_EMAIL_ADDRESS_CHECK_TITLE', 'Pr&uuml;fe E-Mail Adressen &uuml;ber DNS');
define('ENTRY_EMAIL_ADDRESS_CHECK_DESC', 'E-Mail Adressen werden durch einen DNS-Server &uuml;berpr&uuml;ft.');

define('OOS_SMTPAUTH_TITLE', 'SMTP Anmeldung');
define('OOS_SMTPAUTH_DESC', 'Ist eine Anmeldung notwendig?');

define('OOS_SMTPUSER_TITLE', 'SMTP Benutzer');
define('OOS_SMTPUSER_DESC', 'SMTP Benutzer');

define('OOS_SMTPPASS_TITLE', 'SMTP Passwort');
define('OOS_SMTPPASS_DESC', 'SMTP Passwort');

define('OOS_SMTPHOST_TITLE', 'Server');
define('OOS_SMTPHOST_DESC', '[hostname:port]  (e.g. "smtp1.example.com:25;smtp2.example.com")');

define('OOS_SENDMAIL_TITLE', 'Pfade zu sendmail');
define('OOS_SENDMAIL_DESC', '/var/qmail/bin/sendmail');
