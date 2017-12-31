<?php
/* ----------------------------------------------------------------------
   $Id: deu.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: german.php,v 1.116 2003/02/17 11:49:26 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'de_DE'
  * on FreeBSD try 'de_DE.ISO_8859-1'
  * on Windows try 'de' or 'German'
  */
  @setlocale(LC_TIME, 'de_DE');
  define('DATE_FORMAT_SHORT', '%d.%m.%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A, %d. %B %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'd.m.Y');  // this is used for strftime()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
  define('DATE_TIME_FORMAT_SHORT', '%H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param $date
  * @param $reverse
  * @return string
  */
function oos_date_raw($date, $reverse = FALSE) {
	if ($reverse) {
		return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
	} else {
		return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
	}
}

// Global entries for the <html> tag
define('LANG','de');

$aLang['welcome_msg'] = 'VIELE PRODUKTE 40% REDUZIERT';
$aLang['welcome_msg_title'] = '';
$aLang['danger'] = 'Oh nein! Es ist ein Fehler aufgetreten!';
$aLang['warning'] = 'Warnung!';


// theme/system/_header.html
$aLang['header_title_create_account'] = 'Neues Konto';
$aLang['header_title_my_account'] = 'Mein Konto';
$aLang['header_title_cart_contents'] = 'Warenkorb';
$aLang['header_title_checkout'] = 'Kasse';
$aLang['header_title_top'] = 'Home';
$aLang['header_title_logoff'] = 'Abmelden';
$aLang['header_title_login'] = 'Anmelden';
$aLang['header_title_contact'] = 'Kontakt';
$aLang['header_title_whats_new'] = 'Neue Produkte';
$aLang['header_select_language'] = 'Ihre Sprache';
$aLang['header_select_currencies'] = 'Währung';

$aLang['sub_title_total'] = 'Summe:';


$aLang['block_heading_specials'] = 'Angebote';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'Zugriffe seit';

// text for gender
$aLang['male'] = 'Herr';
$aLang['female'] = 'Frau';
$aLang['male_address'] = 'Herr';
$aLang['female_address'] = 'Frau';
$aLang['email_greet_mr'] = 'Sehr geehrter Herr %s,';
$aLang['email_greet_ms'] = 'Sehr geehrte Frau %s,';
$aLang['email_greet_none'] = 'Guten Tag!';


// text for date of birth example
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');
$aLang['dob_format_string'] = 'tt.mm.jjjj';

// search block text in tempalate/your theme/block/search.html
$aLang['block_search_text'] = 'Verwenden Sie Stichworte, um ein Produkt zu finden.';
$aLang['block_search_advanced_search'] = 'erweiterte Suche';
$aLang['text_search'] = 'suchen...';

// reviews block text in tempalate/your theme/block/reviews.php
$aLang['block_reviews_write_review'] = 'Bewerten Sie dieses Produkt!';
$aLang['block_reviews_no_reviews'] = 'Es liegen noch keine Bewertungen vor';
$aLang['block_reviews_text_of_5_stars'] = '%s von 5 Sternen!';

// shopping_cart block text in tempalate/your theme/system/_header.html
$aLang['block_shopping_cart_empty'] = '0 Produkte';
$aLang['sub_title_sub_total'] = 'Summe';

// notifications block text in tempalate/your theme/block/products_notifications.php
$aLang['block_notifications_notify'] = 'Benachrichtigen Sie mich über Aktuelles zum Artikel <strong>%s</strong>';
$aLang['block_notifications_notify_remove'] = 'Benachrichtigen Sie mich nicht mehr zum Artikel <strong>%s</strong>';

// wishlist 
$aLang['button_wishlist'] = 'Merkliste';
$aLang['block_wishlist'] = 'Merkliste';
$aLang['block_wishlist_empty'] = 'Sie haben keine Produkte auf Ihrem Merkzettel';

// manufacturer box text
$aLang['block_manufacturer_info_homepage'] = '%s Homepage';
$aLang['block_manufacturer_info_other_products'] = 'Mehr Produkte';

$aLang['block_add_product_id_text'] = 'Eingabe der Bestellnummer.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Impressum';
$aLang['block_information_privacy'] = 'Privatsphäre&nbsp;und Datenschutz';
$aLang['block_information_conditions'] = 'Unsere AGB';
$aLang['block_information_shipping'] = 'Liefer- und&nbsp;Versandkosten';
$aLang['block_information_gv'] = 'Gutschein einlösen';

//login
$aLang['entry_email_address'] = 'eMail Adresse';
$aLang['entry_password'] = 'Passwort';
$aLang['text_password_info'] = 'Passwort vergessen?';
$aLang['button_login'] = 'Login';
$aLang['login_block_new_customer'] = 'Neukunde';
$aLang['login_block_account_edit'] = 'Daten ändern';
$aLang['login_block_account_history'] = 'Bestellübersicht';
$aLang['login_block_order_history'] = 'Einkaufsliste';
$aLang['login_block_address_book'] = 'Adressbuch';
$aLang['login_block_product_notifications'] = 'Benachrichtigungen';
$aLang['login_block_my_account'] = 'persönliche Daten';
$aLang['login_block_logoff'] = 'Abmelden';
$aLang['login_entry_remember_me'] = 'Einlogautomatik';


// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Versandinformationen';
$aLang['checkout_bar_payment'] = 'Zahlungsweise';
$aLang['checkout_bar_confirmation'] = 'Bestätigung';
$aLang['checkout_bar_finished'] = 'Fertig!';

// pull down default text
$aLang['pull_down_default'] = 'Bitte wählen';
$aLang['type_below'] = 'bitte unten eingeben';

//newsletter
$aLang['block_newsletter_subscribe'] = 'Abonnieren Sie unseren wöchentlichen <strong>Newsletter</strong>';
$aLang['block_newsletter_placeholder'] = 'Ihre E-Mail-Adresse eingeben...';
$aLang['block_newsletter_unsubscribe'] = 'Austragen';
$aLang['error_email_address'] =  '<strong>FEHLER Ihre E-Mail-Adresse:</strong> Keine oder ungültige Eingabe!';
$aLang['newsletter_email_info'] =  'Ihre E-Mail-Adresse wurde in unser System eingetragen.<br />Gleichzeitig wurde Ihnen vom System eine E-Mail mit einem Aktivierungslink geschickt. Bitte klicken Sie nach dem Erhalt der E-Mail auf den Link, um Ihre Eintragung zu bestätigen.';
$aLang['newsletter_email_subject'] = 'Bitte bestätigen Sie Ihre Anmeldung';
$aLang['newsletter_notice'] = 'Sie können Ihr Einverständnis jederzeit widerrufen. Unsere Kontaktinformationen finden Sie u. a. in der Datenschutzerklärung.';
$aLang['entry_newsletter_no'] = 'Nein';
$aLang['entry_newsletter_yes'] = 'Ja';
$aLang['text_email_active'] = 'Ihre E-Mail-Adresse wurde erfolgreich für den Newsletterempfang freigeschaltet!';
$aLang['text_email_active_error'] = 'Es ist ein Fehler aufgetreten, Ihre E-Mail-Adresse wurde nicht freigeschaltet!';
$aLang['text_email_del'] = 'Ihre E-Mail-Adresse wurde aus unserer Newsletterdatenbank gelöscht.';
$aLang['text_email_del_error'] = 'Es ist ein Fehler aufgetreten, Ihre E-Mail-Adresse wurde nicht gelöscht!';

//footer
$aLang['get_in_touch_with_us'] = $sServer . ' entdecken';
$aLang['header_title_service'] = 'Shop Service';
$aLang['block_service_new'] = 'Neue Produkte';
$aLang['block_service_specials'] = 'Angebote';
$aLang['block_service_sitemap'] = 'Sitemap';
$aLang['block_service_advanced_search'] = 'Erweiterte Suche';
$aLang['block_service_reviews'] = 'Meinungen';
$aLang['block_service_shopping_cart'] = 'Warenkorb';
$aLang['block_service_contact'] = 'Kontakt';


$aLang['review_text'] = 'Die Rezension muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.';
$aLang['review_rating'] = 'Geben Sie Ihre Bewertung ein.';
$aLang['review_headline'] = 'Die Überschrift muss mindestens aus 10 Buchstaben bestehen.';
$aLang['form_error'] = '<strong>Notwendige Angaben fehlen!</strong> Bitte richtig ausfüllen.';

// javascript messages
$aLang['js_error'] = 'Notwendige Angaben fehlen!\nBitte richtig ausfüllen.\n\n';
$aLang['js_gender'] = '* Anredeform festlegen.\n';
$aLang['js_first_name'] = '* Der \'Vornname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_last_name'] = '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_dob'] = '* Die \'Geburtsdaten\' im Format xx.xx.xxxx (Tag.Monat.Jahr) eingeben.\n';
$aLang['js_email_address'] = '* Die \'eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_address'] = '* Die \'Strasse/Nr.\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_post_code'] = '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_city'] = '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_state'] = '* Das \'Bundesland\' muss ausgewählt werden.\n';
$aLang['js_country'] = '* Das \'Land\' muss ausgewählt werden.\n';
$aLang['js_password'] = '* Das \'Passwort\' und die \'Bestätigung\' müssen übereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben enthalten.\n';

$aLang['js_error_no_payment_module_selected'] = '* Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.\n';
$aLang['js_error_submitted'] = 'Diese Seite wurde bereits bestätigt. Betätigen Sie bitte OK und warten bis der Prozess durchgeführt wurde.';

$aLang['error_no_payment_module_selected'] = 'Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.';

$aLang['category_company'] = 'Firmendaten';
$aLang['category_personal'] = 'Ihre persönlichen Daten';
$aLang['category_address'] = 'Ihre Adresse';
$aLang['category_contact'] = 'Ihre Kontaktinformationen';
$aLang['category_options'] = 'Optionen';
$aLang['category_password'] = 'Ihr Passwort';
$aLang['entry_company'] = 'Firmenname';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Inhaber';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'Umsatzsteuer ID';
$aLang['entry_vat_id_error'] = 'Die Eingegebene USt-IdNr. ist ungültig oder kann derzeit nicht überprüft werden! Bitte geben Sie eine gültige Umsatzsteuer ID ein oder lassen Sie das Feld leer.';
$aLang['entry_vat_id_text'] = 'Nur für Deutschland und EU!';

$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Anrede';
$aLang['entry_gender_error'] = 'Bitte wählen Sie Ihre Anrede.';
$aLang['entry_first_name'] = 'Vorname';
$aLang['entry_first_name_error'] = 'Ihr Vorname muss mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben enthalten';
$aLang['entry_last_name'] = 'Nachname';
$aLang['entry_last_name_error'] = 'Ihr Nachname muss mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben enthalten';
$aLang['entry_date_of_birth'] = 'Geburtsdatum';
$aLang['entry_date_of_birth_text'] = '(z.B. 21.05.1970)';
$aLang['entry_date_of_birth_error'] = 'Ihr Geburtsdatum muss im Format TT.MM.JJJJ (zB. 21.05.1970) eingeben werden';

$aLang['entry_email_address_error'] = 'Ihre E-Mail muss mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben enthalten';
$aLang['entry_email_address_check_error'] = 'Bitte überprüfen Sie Ihre E-Mail Adresse';
$aLang['entry_email_address_error_exists'] = 'Ihre E-Mail Adresse existiert bereits in unserem Online Shop - bitte melden Sie sich mit der E-Mail Adresse an oder erstellen Sie ein Konto mit einer anderen E-Mail Adresse.';
$aLang['entry_street_address'] = 'Straße/Nr.';
$aLang['entry_street_address_error'] = 'Ihre Straße muss mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben enthalten';

$aLang['entry_post_code'] = 'Postleitzahl';
$aLang['entry_post_code_error'] = 'Ihre Postleitzahl muss mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zahlen enthalten';
$aLang['entry_city'] = 'Ort';
$aLang['entry_city_error'] = 'Ihr Ort muss mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben enthalten';
$aLang['entry_state'] = 'Bundesland';
$aLang['entry_state_error'] = 'Ihr Bundesland muss mindestens ' . ENTRY_STATE_MIN_LENGTH . ' Buchstaben enthalten';
$aLang['entry_state_error_select'] = 'Bitte wählen Sie Ihr Bundesland aus der Liste aus.';

$aLang['entry_country'] = 'Land';
$aLang['entry_country_error'] = 'Bitte wählen Sie Ihr Land aus der Liste aus.';

$aLang['entry_telephone_number'] = 'Telefonnummer';
$aLang['entry_newsletter'] = 'Newsletter';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'abonniert';
$aLang['entry_newsletter_no'] = 'nicht abonniert';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password_confirmation'] = 'Bestätigung';
$aLang['entry_password_error'] = 'Ihr Passwort muss mindestens '  . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten';
$aLang['entry_password_error_not_matching'] = 'Ihre Passwörter stimmen leider nicht überein.';

$aLang['password_hidden'] = '--VERSTECKT--';
$aLang['entry_info_text'] = 'notwendige Eingabe';
$aLang['entry_subject'] = 'Subject';

$aLang['entry_agree_error'] = 'Bitte akzeptieren Sie unsere AGB und Datenschutzbestimmungen!';
$aLang['agree'] = 'Ja, ich stimme den <a href="%s" target="_blank"><strong>AGB</strong></a> und den <a href="%s" target="_blank"><strong>Datenschutzbestimmungen</strong></a> zu.';
$aLang['newsletter_agree'] = 'Ja, ich möchte per E-Mail Newsletter über Trends, Aktionen &amp; Gutscheine informiert werden. Abmeldung jederzeit möglich. (optional)';

$aLang['success_address_book_entry_deleted'] = 'Die ausgewählte Adresse wurde erfolgreich gelöscht.';
$aLang['warning_primary_address_deletion'] = 'Die Standardadresse kann nicht gelöscht werden.';
$aLang['success_address_book_entry_updated'] = 'Ihr Adressbuch wurde erfolgreich aktualisiert!';
$aLang['error_nonexisting_address_book_entry'] = 'Dieser Adressbucheintrag ist leider nicht vorhanden.';
$aLang['error_address_book_full'] = 'Ihr Adressbuch kann leider keine weiteren Adressen aufnehmen. Bitte löschen Sie eine nicht mehr benötigte Adresse. Danach können Sie eine neue Adresse speichern.';

// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Seiten:';
$aLang['text_display_number_of_products'] = 'angezeigte Produkte: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';
$aLang['text_display_number_of_orders'] = 'angezeigte Bestellungen: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';
$aLang['text_display_number_of_reviews'] = 'angezeigte Meinungen: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';
$aLang['text_display_number_of_products_new'] = 'angezeigte neue Produkte: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';
$aLang['text_display_number_of_specials'] = 'angezeigte Angebote <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';
$aLang['text_display_number_of_wishlist'] = 'angezeigt Wunschprodukte <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)';

$aLang['prevnext_title_first_page'] = 'erste Seite';
$aLang['prevnext_title_previous_page'] = 'vorherige Seite';
$aLang['prevnext_title_next_page'] = 'nächste Seite';
$aLang['prevnext_title_last_page'] = 'letzte Seite';
$aLang['prevnext_title_page_no'] = 'Seite %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Vorhergehende %d Seiten';
$aLang['prevnext_title_next_set_of_no_page'] = 'Nächste %d Seiten';
$aLang['prevnext_button_first'] = '&lt;&lt;ERSTE';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;vorherige';
$aLang['prevnext_button_next'] = 'nächste&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'LETZTE&gt;&gt;';

$aLang['button_account'] = 'Profil';
$aLang['button_add_address'] = 'Neue Adresse';
$aLang['button_address_book'] = 'Adressbuch';
$aLang['button_apply_coupon'] = 'Gutschein einlösen';
$aLang['button_back'] = 'Zurück';
$aLang['button_change_address'] = 'Adresse ändern';
$aLang['button_checkout'] = 'Kasse';
$aLang['button_confirm_order'] = 'Bestellung bestätigen';
$aLang['button_continue'] = 'Weiter';
$aLang['button_continue_shopping'] = 'Einkauf fortsetzen';
$aLang['button_delete'] = 'Löschen';
$aLang['button_edit_account'] = 'Daten ändern';
$aLang['button_history'] = 'Bestellübersicht';
$aLang['button_login'] = 'Anmelden';
$aLang['button_in_cart'] = 'In den Warenkorb';
$aLang['button_notifications'] = 'Benachrichtigungen';
$aLang['button_quick_find'] = 'Schnellsuche';
$aLang['button_remove_notifications'] = 'Benachrichtigungen löschen';
$aLang['button_reviews'] = 'Bewertungen';
$aLang['button_submit_a_review'] = 'Bewertung abgeben';
$aLang['button_send_message'] = 'Nachricht senden';
$aLang['button_search'] = 'Suchen';
$aLang['button_start_shopping'] = 'Beginnen Sie Ihren Einkauf';
$aLang['button_update'] = 'Aktualisieren';
$aLang['button_update_cart'] = 'Warenkorb aktualisieren';
$aLang['button_write_review'] = 'Bewertung schreiben';
$aLang['button_add_quick'] = 'Schnellkauf!';
$aLang['image_wishlist_delete'] = 'löschen';
$aLang['button_in_wishlist'] = 'Wunschliste';
$aLang['button_add_wishlist'] = 'Wunschliste';
$aLang['button_redeem_voucher'] = 'Gutschein einlösen';
$aLang['button_callaction'] = 'Fordern Sie ein Angebot';
$aLang['button_register'] = 'Jetzt registrieren';
$aLang['button_save_info'] = 'Daten speichern';

$aLang['button_hp_buy'] = 'In den Warenkorb';
$aLang['button_hp_more'] = 'Zeige mehr';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Movie';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['icon_arrow_right'] = 'Zeige mehr';
$aLang['icon_cart'] = 'In den Warenkorb';
$aLang['icon_warning'] = 'Warnung';

$aLang['text_sort_products'] = 'Sortierung der Artikel ist ';
$aLang['text_descendingly'] = 'absteigend';
$aLang['text_ascendingly'] = 'aufsteigend';
$aLang['text_by'] = ' nach ';

$aLang['text_review_by'] = 'von %s';
$aLang['text_review_word_count'] = '%s Worte';
$aLang['text_review_rating'] = 'Bewertung:';
$aLang['text_review_date_added'] = 'Datum hinzugefügt: ';
$aLang['text_no_reviews'] = 'Es liegen noch keine Bewertungen vor.';
$aLang['text_no_new_products'] = 'Zur Zeit gibt es keine neuen Produkte.';
$aLang['text_unknown_tax_rate'] = 'Unbekannter Steuersatz';
$aLang['text_required'] = 'erforderlich';


$aLang['warning_install_directory_exists'] = 'Warnung: Das Installationverzeichnis ist noch vorhanden auf: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Bitte löschen Sie das Verzeichnis aus Gründen der Sicherheit!';
$aLang['warning_config_file_writeable'] = 'Warnung: MyOOS [Shopsystem] kann in die Konfigurationsdatei schreiben: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. Das stellt ein mögliches Sicherheitsrisiko dar - bitte korrigieren Sie die Benutzerberechtigungen zu dieser Datei!';
$aLang['warning_download_directory_non_existent'] = 'Warnung: Das Verzeichnis für den Artikel Download existiert nicht: ' . OOS_DOWNLOAD_PATH . '. Diese Funktion wird nicht funktionieren bis das Verzeichnis erstellt wurde!';


$aLang['text_ccval_error_invalid_date'] = 'Das \'Gültig bis\' Datum ist ungültig.<br>Bitte korrigieren Sie Ihre Angaben.';
$aLang['text_ccval_error_invalid_number'] = 'Die \'KreditkarteNummer\', die Sie angegeben haben, ist ungültig.<br>Bitte korrigieren Sie Ihre Angaben.';
$aLang['text_ccval_error_unknown_card'] = 'Die ersten 4 Ziffern Ihrer Kreditkarte sind: %s<br>Wenn diese Angaben stimmen, wird dieser Kartentyp leider nicht akzeptiert.<br>Bitte korrigieren Sie Ihre Angaben gegebenfalls.';

$aLang['voucher_balance'] = 'Gutschein - Guthaben';
$aLang['gv_faq'] = 'Gutschein FAQ';
$aLang['error_redeemed_amount'] = 'Prima: Der Einlösewert wurde Ihrem Kundenkonto gutgeschrieben! ';
$aLang['error_no_redeem_code'] = 'Sie haben keinen Gutschein-Code eingegeben!';  
$aLang['error_no_invalid_redeem_gv'] = 'Fehler: Sie haben keinen gültigen Gutschein-Code eingegeben!'; 
$aLang['table_heading_credit'] = 'Guthaben';
$aLang['gv_has_vouchera'] = 'Sie haben ein Gutschein - Guthaben auf Ihrem Kundenkonto. Möchten Sie einen Teil <br>
                         Ihres Guthabens per';
$aLang['gv_has_voucherb'] = 'versenden?';
$aLang['entry_amount_check_error'] = '&nbsp;<small><font color="#FF0000">Leider keine ausreichende Deckung auf Ihrem Kundenkonto!</font></smal>'; 


$aLang['voucher_redeemed'] = 'Voucher Redeemed';
$aLang['cart_coupon'] = 'Coupon :';
$aLang['cart_coupon_info'] = 'more info';

$aLang['category_payment_details'] = 'Auszahlung kann erfolgen über';

$aLang['block_ticket_generate'] = 'Support Anfrage';
$aLang['block_ticket_view'] = 'Ticket einsehen';

$aLang['down_for_maintenance_text'] = 'Down for Maintenance ... Please try back later';
$aLang['down_for_maintenance_no_prices_display'] = 'Down for Maintenance';
$aLang['no_login_no_prices_display'] = 'Preise nur für Händler';
$aLang['text_products_base_price'] = 'Grundpreis';

$aLang['products_see_qty_discounts'] = 'Staffelpreise';
$aLang['products_order_qty_text'] = 'Menge: ';
$aLang['products_order_qty_min_text'] = '<br />' . ' Mindestbestellmenge: ';
$aLang['products_order_qty_min_text_info'] = ' Die Mindestbestellmenge ist: ';
$aLang['products_order_qty_min_text_cart'] = ' Die Mindestbestellmenge ist: ';
$aLang['products_order_qty_min_text_cart_short'] = 'min. M.: ';

$aLang['products_order_qty_unit_text'] = ' Verpackungseinheit: ';
$aLang['products_order_qty_unit_text_info'] = 'bei der Verpackungseinheit von: ';
$aLang['products_order_qty_unit_text_cart'] = 'bei der Verpackungseinheit von: ';
$aLang['products_order_qty_unit_text_cart_short'] = ' Verpackungseinheit: ';

$aLang['error_products_quantity_order_min_text'] = '';
$aLang['error_products_quantity_invalid'] = 'Fehler: Menge: ';
$aLang['error_products_quantity_order_units_text'] = '';
$aLang['error_products_units_invalid'] = 'Fehler: Menge ';

$aLang['error_destination_does_not_exist'] = 'Fehler: Speicherort existiert nicht.';
$aLang['error_destination_not_writeable'] = 'Fehler: Speicherort ist nicht beschreibbar.';
$aLang['error_file_not_saved'] = 'Fehler: Datei wurde nicht gespeichert.';
$aLang['error_filetype_not_allowed'] =  'Fehler: Dateityp ist nicht erlaubt.';
$aLang['success_file_saved_successfully'] = 'Erfolg: Datei erfolgreich hochgeladen.';
$aLang['warning_no_file_uploaded'] = 'Warnung: Es wurde keine Datei hochgeladen.';
$aLang['warning_file_uploads_disabled'] = 'Warning: File uploads are disabled in the php.ini configuration file.';


$aLang['err404'] = '404 Fehlermeldung';
$aLang['err404_page_not_found'] = 'Die angeforderte Seite wurde nicht gefunden bei';
$aLang['err404_sorry'] = 'Die angeforderte Seite';
$aLang['err404_doesntexist'] = 'existiert nicht bei';
$aLang['err404_mailed'] = '<strong>Details zum Fehler wurden automatisch an den Webmaster gesendet.</strong>';
$aLang['err404_commonm'] = '<strong>Typische Fehler</strong>';
$aLang['err404_commonh'] = 'Namenskonventionen nicht beachtet';
$aLang['err404_urlend'] = 'die angegebene URL endet mit';
$aLang['err404_allpages'] = 'alle Seiten bei';
$aLang['err404_endwith'] = 'enden mit';
$aLang['err404_uppercase'] = 'die Benutzung von GROSSBUCHSTABEN';
$aLang['err404_alllower'] = 'alle Namen werden kleingeschrieben';

$aLang['text_info_csname'] = 'Sie sind Mitglied der Kundengruppe : ';
$aLang['text_info_csdiscount'] = 'Sie bekommen je nach Produkt einen maximalen Rabatt bis zu : ';
$aLang['text_info_csotdiscount'] = 'Sie bekommen auf Ihre Gesamtbestellung einen Rabatt von : ';
$aLang['text_info_csstaff'] = 'Sie sind berechtigt zu Staffelpreisen einzukaufen.';
$aLang['text_info_cspay'] = 'Folgende Zahlungsarten können Sie benutzen : ';
$aLang['text_info_show_price_no'] = 'Sie erhalten noch keine Preisinformationen. Bitte melden Sie sich an';
$aLang['text_info_show_price_with_tax_yes'] = 'Die Preise beinhalten die MwSt.';
$aLang['text_info_show_price_with_tax_no'] = 'Die Preise werden ohne MwSt. angezeigt.';
$aLang['text_info_receive_mail_mode'] = 'Infos hätte ich gerne im Format : ';
$aLang['entry_receive_mail_text'] = 'Text only';
$aLang['entry_receive_mail_html'] = 'HTML';
$aLang['entry_receive_mail_pdf'] = 'PDF';
 
$aLang['table_heading_price_unit'] = 'pro Stk.Netto';
$aLang['table_heading_discount'] = 'Rabatt';
$aLang['table_heading_ot_discount'] = 'Pauschalrabatt';
$aLang['text_info_minimum_amount'] = 'Ab dem folgenden Bestellwert erhalten Sie den Pausschalrabatt.';
$aLang['sub_title_ot_discount'] = 'Pauschalrabatt:';
$aLang['text_new_customer_introduction_newsletter'] = 'By subscribing to newsletter from ' .  STORE_NAME . ' you will stay informed of all news info.';
$aLang['text_new_customer_ip'] = 'This account has been created by this computer IP : ';
$aLang['text_customer_account_password_security'] = 'For you\'r own security we are not able to know this password. If you forgot it, you can request a new one.';
$aLang['text_login_need_upgrade_csnewsletter'] = '<font color="#ff0000"><strong>NOTE:</strong></font>You have already subscribed to an account for &quot;Newsletter &quot;. You need to upgade this account to be able to buy.';

// use TimeBasedGreeting

$aLang['text_taxt_incl'] = 'inkl. gesetzl. MwSt.';
$aLang['text_taxt_add'] = 'zzgl. gesetzl. MwSt.';
$aLang['tax_info_excl'] = 'exkl. MwSt.';
$aLang['text_shipping'] = 'zzgl. <a href="%s"><u>Versandkosten</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'Ab';


