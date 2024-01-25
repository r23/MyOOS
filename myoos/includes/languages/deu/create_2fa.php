<?php
/**
   ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 16:36:39 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.12 2002/06/17 23:10:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title_1'] = 'Anmelden';
$aLang['navbar_title_2'] = 'Zweistufige Verifizierung';

$aLang['navbar_title'] = 'Zweistufige Verifizierung';
$aLang['heading_title'] = 'Zweistufige Verifizierung';

$aLang['text_2fa_title'] = 'Code mit Authentifizierungs-App generieren.';
$aLang['text_2fa_info'] = 'Jedes Mal, wenn Sie sich einloggen, generieren Sie mit einer Authentifizierungs-App einen einmaligen Code.';
$aLang['text_2fa_step1'] = 'Schritt 1: Scannen Sie den folgenden QR-Code oder geben Sie den Schlüssel manuell in Ihre Authentifizierungs-App ein.';
$aLang['text_2fa_key'] = 'Schlüssel';
$aLang['text_2fa_step2'] = 'Schritt 2: Geben Sie den 6-stelligen Sicherheitscode aus Ihrer Authentifizierungs-App ein.';
$aLang['text_2fa_placeholder'] = 'Authentifizierungscode';

$aLang['text_2fa_app'] =  'Sie brauchen eine Authentifizierungs-App?';
$aLang['text_2fa_app_info'] = 'Eine Authentifizierungs-App können Sie ganz einfach herunterladen. Damit wird ein einmaliger Sicherheitscode generiert, den Sie zusätzlich zu Ihrem Passwort zum Einloggen verwenden können. Diese App-Anbieter haben jedoch keinen Zugriff auf Ihre Kontoinformationen.';
$aLang['text_2fa_app_download'] = 'Um eine App herunterzuladen, öffnen Sie den App Store auf Ihrem Handy. Suchen Sie nach &quot;Google Authenticator&quot; und laden Sie diese App herunter.';

$aLang['text_code_error'] = '<strong>FEHLER:</strong> Keine Übereinstimmung mit dem eingebenen \'Authentifizierungscode\' .';
$aLang['entry_code_error'] = '<strong>FEHLER:</strong> Der Sicherheitscode besteht aus 6 Ziffern';
$aLang['entry_2fa_success'] = 'Sie haben die zweistufige Verifizierung für Ihr Konto eingerichtet.';
