<?php
/**
   ----------------------------------------------------------------------
   $Id: user_create_account.php,v 1.3 2007/06/12 16:36:39 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account.php,v 1.12 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title'] = 'Konto erstellen';
$aLang['heading_title'] = 'Informationen zu Ihrem Kundenkonto';
$aLang['text_origin_login'] = '<strong>ACHTUNG:</strong> Wenn Sie bereits ein Konto besitzen, so melden Sie sich bitte <a href="%s" class="alert-link"><strong>hier</strong></a> an.';

$aLang['email_subject'] = 'Willkommen zu ' . STORE_NAME;
$aLang['email_greet_mr'] = 'Sehr geehrter Herr ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_ms'] = 'Sehr geehrte Frau ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_none'] = 'Sehr geehrte ' . stripslashes($firstname) . ',' . "\n\n";
$aLang['email_welcome'] = 'willkommen zu <strong>' . STORE_NAME . '</strong>.' . "\n\n";
$aLang['email_text'] = 'Sie können jetzt unseren <strong>Online-Service</strong> nutzen. Der Service bietet unter anderem:' . "\n\n" . '<li><strong>Kundenwarenkorb</strong> - Jeder Artikel bleibt registriert bis Sie zur Kasse gehen, oder die Produkte aus dem Warenkorb entfernen.' . "\n" . '<li><strong>Adressbuch</strong> - Wir können jetzt die Produkte zu der von Ihnen ausgesuchten Adresse senden. Der perfekte Weg ein Geburtstagsgeschenk zu versenden.' . "\n" . '<li><strong>Vorherige Bestellungen</strong> - Sie können jederzeit Ihre vorherigen Bestellungen überprüfen.' . "\n" . '<li><strong>Meinungen über Produkte</strong> - Teilen Sie Ihre Meinung zu unseren Produkten mit anderen Kunden.' . "\n\n";
$aLang['email_contact'] = 'Falls Sie Fragen zu unserem Kunden-Service haben, wenden Sie sich bitte an den Vertrieb: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<strong>Achtung:</strong> Diese eMail-Adresse wurde uns von einem Kunden bekannt gegeben. Falls Sie sich nicht angemeldet haben, senden Sie bitte eine eMail an ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";

$aLang['email_gv_incentive_header'] = 'Um Sie als Neukunden zu begrüßen, haben wir Ihnen einen Gutschein über %s gesendet.';
$aLang['email_gv_redeem'] = 'Der Gutscheincode lautet: %s. Sie können diesen, beim Abschluß Ihrer Bestellung eingeben';
$aLang['email_gv_link'] = 'Oder Sie benutzen den folgenden Link: ';
$aLang['email_coupon_incentive_header'] = 'Herzlichen Glückwunsch! Um den ersten Besuch in unserm Shop attraktiver zu machen erhalten Sie diesen Gutschein!' . "\n" .
                                         'Es folgen weitere Details über Ihren persönlichen Einkaufsgutschein.' . "\n\n";
$aLang['email_coupon_redeem'] = 'Um den Einkaufsgutschein zu nutzen geben Sie bitte den Gutscheincode %s ' . "\n" .
                               'beim Beenden Ihrer Bestellung ein!';

$aLang['email_password'] = 'Ihr Passwort für \'' . STORE_NAME . '\' lautet:' . "\n\n" . '   %s' . "\n\n";



$aLang['owner_email_subject'] = 'Neuer Kunde';
$aLang['owner_email_date'] = 'Datum:';
$aLang['owner_email_company_info'] = 'Firmendaten';
$aLang['owner_email_contact'] = 'Kontaktinformationen';
$aLang['owner_email_options'] = 'Optionen';
$aLang['owner_email_company'] = 'Firmenname:';
$aLang['owner_email_owner'] = 'Inhaber:';
$aLang['owner_email_gender'] = 'Anrede:';
$aLang['owner_email_first_name'] = 'Vorname:';
$aLang['owner_email_last_name'] = 'Nachname:';
$aLang['owner_email_date_of_birth'] = 'Geburtsdatum:';
$aLang['owner_email_address'] = 'eMail-Adresse:';
$aLang['owner_email_street'] = 'Straße/Nr.:';
$aLang['owner_email_post_code'] = 'Postleitzahl:';
$aLang['owner_email_city'] = 'Ort:';
$aLang['owner_email_state'] = 'Bundesland:';
$aLang['owner_email_country'] = 'Land:';
$aLang['owner_email_telephone_number'] = 'Telefonnummer:';
$aLang['owner_email_newsletter'] = 'Newsletter:';
$aLang['owner_email_newsletter_yes'] = 'abonniert';
$aLang['owner_email_newsletter_no'] = 'nicht abonniert';
$aLang['email_separator'] = '------------------------------------------------------';
