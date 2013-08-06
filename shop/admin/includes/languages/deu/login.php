<?php
/* ----------------------------------------------------------------------
   $Id: login.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['heading_title'] = 'Anmeldebereich';
$aLang['text_returning_admin'] = 'Nur für Mitarbeiter!';
$aLang['entry_email_address'] = 'E-Mail-Adresse:';
$aLang['placeholder_email_address'] = 'Ihre E-Mail-Adresse';
$aLang['entry_password'] = 'Passwort:';

$aLang['button_login'] = 'Anmelden';
$aLang['text_password_forgotten'] = 'Passwort vergessen?';
$aLang['text_welcome'] = '<h4>Willkommen bei MyOOS!</h4>Verwenden Sie eine gültige E-Mail und Passwort, um Zugriff auf die Administrationskonsole zu erhalten.';
$aLang['text_login_error'] = '<strong>FEHLER:</strong> Falscher Benutzername oder Passwort!';

$aLang['text_forgotten_fail'] = 'Sie haben es mehr als 3x versucht. Aus Sicherheitsgründen kontaktieren Sie bitte Ihren Administrator um ein neues Passwort zu erhalten.';
$aLang['text_forgotten_success'] = 'Das neue Passwort wurde an Ihre E-Mail-Adresse gesendet. Überprüfen Sie Ihren E-Mail-Eingang um sich anzumelden.';

$aLang['admin_email_subject'] = 'Neues Passwort'; 
$aLang['admin_email_text'] = 'Hallo %s,' . "\n\n" . 'Sie können den redaktionellen Bereich mit folgenden Passwort betreten. Nach erfolgtem Login, ändern Sie bitte aus Sicherheitsgründen Ihr Passwort!' . "\n\n" . 'Website : %s' . "\n" . 'Benutzername: %s' . "\n" . 'Passwort: %s' . "\n\n" . 'Danke!' . "\n" . '%s' . "\n\n" . 'Dies ist eine automatisierte Antwortmail. Bitte beantworten Sie diese nicht!';
 
