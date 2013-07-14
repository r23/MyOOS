<?php
/* ----------------------------------------------------------------------
   $Id: deu.php 453 2013-06-28 16:03:28Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
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
  function oos_date_raw($date, $reverse = false) {
    if ($reverse) {
      return substr($date, 0, 2) . substr($date, 3, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 3, 2) . substr($date, 0, 2);
    }
  }

// if USE_DEFAULT_LANGUAGE_CURRENCY is true, use the following currency, instead of the applications default currency (used when changing language)
define('LANGUAGE_CURRENCY', 'EUR');

// Global entries for the <html> tag
define('LANG','de');

$aLang['welcome_msg'] = 'VIELE PRODUKTE 40% REDUZIERT';
$aLang['welcome_msg_title'] = '';

//text in oos_temp/templates/oos/system/user_navigation.html
$aLang['header_title_create_account'] = 'Neues Konto';
$aLang['header_title_my_account'] = 'Mein Konto';
$aLang['header_title_cart_contents'] = 'Warenkorb';
$aLang['header_title_checkout'] = 'Kasse';
$aLang['header_title_top'] = 'Startseite';
$aLang['header_title_catalog'] = 'Katalog';
$aLang['header_title_logoff'] = 'Abmelden';
$aLang['header_title_login'] = 'Anmelden';
$aLang['header_title_whats_new'] = 'Neue Produkte';


$aLang['block_heading_specials'] = 'Angebote';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'Zugriffe seit';

// text for gender
$aLang['male'] = 'Herr';
$aLang['female'] = 'Frau';
$aLang['male_address'] = 'Herr';
$aLang['female_address'] = 'Frau';

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

// shopping_cart block text in tempalate/your theme/block/shopping_cart.html
$aLang['block_shopping_cart_empty'] = '0 Produkte';

// notifications block text in tempalate/your theme/block/products_notifications.php
$aLang['block_notifications_notify'] = 'Benachrichtigen Sie mich über Aktuelles zum Artikel <b>%s</b>';
$aLang['block_notifications_notify_remove'] = 'Benachrichtigen Sie mich nicht mehr zum Artikel <b>%s</b>';

// wishlist block text in tempalate/your theme/block/wishlist.html
$aLang['block_wishlist_empty'] = 'Sie haben keine Produkte in Ihrem Wunschzettel';

// manufacturer box text
$aLang['block_manufacturer_info_homepage'] = '%s Homepage';
$aLang['block_manufacturer_info_other_products'] = 'Mehr Produkte';

$aLang['block_add_product_id_text'] = 'Eingabe der Bestellnummer.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Impressum';
$aLang['block_information_privacy'] = 'Privatsphäre&nbsp;und Datenschutz';
$aLang['block_information_conditions'] = 'Unsere AGB';
$aLang['block_information_shipping'] = 'Liefer- und&nbsp;Versandkosten';
$aLang['block_information_v_card'] = 'vCard';
$aLang['block_information_mapquest'] = 'Wegbeschreibung';
$aLang['block_skype_me'] = 'Skype Me';
$aLang['block_information_gv'] = 'Gutschein einlösen';
$aLang['block_information_gallery'] = 'Gallery';

//login
$aLang['entry_email_address'] = 'eMail Adresse:';
$aLang['entry_password'] = 'Passwort:';
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

// tell a friend block text in tempalate/your theme/block/tell_a_friend.html
$aLang['block_tell_a_friend_text'] = 'Empfehlen Sie diesen Artikel einfach per eMail weiter.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Versandinformationen';
$aLang['checkout_bar_payment'] = 'Zahlungsweise';
$aLang['checkout_bar_confirmation'] = 'Bestätigung';
$aLang['checkout_bar_finished'] = 'Fertig!';

// pull down default text
$aLang['pull_down_default'] = 'Bitte wählen';
$aLang['type_below'] = 'bitte unten eingeben';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Eintragen';
$aLang['block_newsletters_unsubscribe'] = 'Austragen';

//myworld
$aLang['text_date_account_created'] = 'Zugang erstellt am:';
$aLang['text_yourstore'] = 'Ihre persönliche Seite';
$aLang['edit_yourimage'] = 'Bild bearbeiten';

// footer
$sServer = oos_server_get_var('HTTP_HOST');
$aLang['get_in_touch_with_us'] = $sServer . ' entdecken';
$aLang['header_title_service'] = 'Shop Service';
$aLang['block_service_new'] = 'Neue Produkte';
$aLang['block_service_specials'] = 'Angebote';
$aLang['block_service_sitemap'] = 'Sitemap';
$aLang['block_service_advanced_search'] = 'Erweiterte Suche';
$aLang['block_service_reviews'] = 'Meinungen';
$aLang['block_service_shopping_cart'] = 'Warenkorb';
$aLang['block_service_contact'] = 'Kontakt';


// javascript messages
$aLang['js_error'] = 'Notwendige Angaben fehlen!\nBitte richtig ausfüllen.\n\n';

$aLang['js_review_text'] = '* Der Text muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.\n';
$aLang['js_review_rating'] = '* Geben Sie Ihre Bewertung ein.\n';

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
$aLang['js_telephone'] = '* Die \'Telefonnummer\' muss mindestens aus ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['js_password'] = '* Das \'Passwort\' und die \'Bestätigung\' müssen übereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben enthalten.\n';

$aLang['js_error_no_payment_module_selected'] = '* Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.\n';
$aLang['js_error_submitted'] = 'Diese Seite wurde bereits bestätigt. Betätigen Sie bitte OK und warten bis der Prozess durchgeführt wurde.';

$aLang['error_no_payment_module_selected'] = 'Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.';
$aLang['error_conditions_not_accepted'] = 'Sofern Sie unsere AGB\'s nicht akzeptieren, können wir Ihre Bestellung bedauerlicherweise nicht entgegen nehmen!';


$aLang['category_company'] = 'Firmendaten';
$aLang['category_personal'] = 'Ihre persönlichen Daten';
$aLang['category_address'] = 'Ihre Adresse';
$aLang['category_contact'] = 'Ihre Kontaktinformationen';
$aLang['category_options'] = 'Optionen';
$aLang['category_password'] = 'Ihr Passwort';
$aLang['entry_company'] = 'Firmenname:';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Inhaber';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'Umsatzsteuer ID';
$aLang['entry_vat_id_error'] = 'Die Eingegebene USt-IdNr. ist ungültig oder kann derzeit nicht überprüft werden! Bitte geben Sie eine gültige Umsatzsteuer ID ein oder lassen Sie das Feld leer.';
$aLang['entry_vat_id_text'] = 'Nur für Deutschland und EU!';
$aLang['entry_number'] = 'Kundennummer';
$aLang['entry_number_error'] = '';
$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Anrede:';

$aLang['entry_first_name'] = 'Vorname:';
$aLang['entry_first_name_error'] = 'mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben';
$aLang['entry_last_name'] = 'Nachname:';
$aLang['entry_last_name_error'] = 'mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben';
$aLang['entry_date_of_birth'] = 'Geburtsdatum:';
$aLang['entry_date_of_birth_text'] = '(z.B. 21.05.1970)';
$aLang['entry_email_address'] = 'eMail-Adresse:';
$aLang['entry_email_address_error'] = 'mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben';
$aLang['entry_email_address_check_error'] = 'ungültige eMail-Adresse!';
$aLang['entry_email_address_error_exists'] = 'Diese eMail-Adresse existiert schon!';
$aLang['entry_street_address'] = 'Strasse/Nr.:';
$aLang['entry_street_address_error'] = 'mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben';

$aLang['entry_suburb'] = 'Stadtteil:';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'Postleitzahl:';
$aLang['entry_post_code_error'] = 'mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zahlen';
$aLang['entry_city'] = 'Ort:';
$aLang['entry_city_error'] = 'mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben';
$aLang['entry_state'] = 'Bundesland:';
$aLang['entry_country'] = 'Land:';
$aLang['entry_country_error'] = '';
$aLang['entry_telephone_number'] = 'Telefonnummer:';
$aLang['entry_telephone_number_error'] = 'mindestens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen';
$aLang['entry_fax_number'] = 'Telefaxnummer:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Newsletter:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'abonniert';
$aLang['entry_newsletter_no'] = 'nicht abonniert';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Passwort:';
$aLang['entry_password_confirmation'] = 'Bestätigung:';
$aLang['entry_password_error'] = 'mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen';
$aLang['password_hidden'] = '--VERSTECKT--';
$aLang['entry_info_text'] = 'notwendige Eingabe';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Seiten:';
$aLang['text_display_number_of_products'] = 'angezeigte Produkte: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';
$aLang['text_display_number_of_orders'] = 'angezeigte Bestellungen: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';
$aLang['text_display_number_of_reviews'] = 'angezeigte Meinungen: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';
$aLang['text_display_number_of_products_new'] = 'angezeigte neue Produkte: <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';
$aLang['text_display_number_of_specials'] = 'angezeigte Angebote <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';
$aLang['text_display_number_of_wishlist'] = 'angezeigt Wunschprodukte <b>%d</b> bis <b>%d</b> (von <b>%d</b> insgesamt)';

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

$aLang['button_add_address'] = 'Neue Adresse';
$aLang['button_address_book'] = 'Adressbuch';
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
$aLang['button_search'] = 'Suchen';
$aLang['button_tell_a_friend'] = 'Weiterempfehlen';
$aLang['button_update'] = 'Aktualisieren';
$aLang['button_update_cart'] = 'Warenkorb aktualisieren';
$aLang['button_write_review'] = 'Bewertung schreiben';
$aLang['button_add_quick'] = 'Schnellkauf!';
$aLang['image_wishlist_delete'] = 'löschen';
$aLang['button_in_wishlist'] = 'Wunschliste';
$aLang['button_add_wishlist'] = 'Wunschliste';
$aLang['button_redeem_voucher'] = 'Gutschein einlösen';
$aLang['button_callaction'] = 'Fordern Sie ein Angebot';

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

$aLang['text_greeting_personal'] = 'Schön, dass Sie wieder da sind <span class="greetUser">%s!</span> Möchten Sie die <a href="%s"><u>neuen Produkte</u></a> ansehen?';
$aLang['text_greeting_guest'] = 'Herzlich Willkommen <span class="greetUser">Gast!</span> Möchten Sie sich <a href="%s"><u>anmelden</u></a>? Oder wollen Sie ein <a href="%s"><u>Kundenkonto</u></a> eröffnen?';

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
$aLang['error_oos_mail'] = '<small>Fehler:</small> Die eMail kann nicht über den angegebenen SMTP-Server verschickt werden. Bitte kontrollieren Sie die Einstellungen in der php.ini Datei und führen Sie notwendige Korrekturen durch!';

$aLang['warning_install_directory_exists'] = 'Warnung: Das Installationverzeichnis ist noch vorhanden auf: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Bitte löschen Sie das Verzeichnis aus Gründen der Sicherheit!';
$aLang['warning_config_file_writeable'] = 'Warnung: MyOOS [Shopsystem] kann in die Konfigurationsdatei schreiben: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. Das stellt ein mögliches Sicherheitsrisiko dar - bitte korrigieren Sie die Benutzerberechtigungen zu dieser Datei!';
$aLang['warning_session_auto_start'] = 'Warnung: session.auto_start ist enabled - Bitte disablen Sie dieses PHP Feature in der php.ini und starten Sie den WEB-Server neu!';
$aLang['warning_download_directory_non_existent'] = 'Warnung: Das Verzeichnis für den Artikel Download existiert nicht: ' . OOS_DOWNLOAD_PATH . '. Diese Funktion wird nicht funktionieren bis das Verzeichnis erstellt wurde!';
$aLang['warning_session_directory_non_existent'] = 'Warnung: Das Verzeichnis für die Sessions existiert nicht: ' . oos_session_save_path() . '. Die Sessions werden nicht funktionieren bis das Verzeichnis erstellt wurde!';
$aLang['warning_session_directory_not_writeable'] = 'Warnung: MyOOS [Shopsystem] kann nicht in das Sessions Verzeichnis schreiben: ' . oos_session_save_path() . '. Die Sessions werden nicht funktionieren bis die richtigen Benutzerberechtigungen gesetzt wurden!';

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
$aLang['gv_send_to_friend'] = 'Gutschein versenden';

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

$aLang['error_destination_does_not_exist'] = 'Error: Destination does not exist.';
$aLang['error_destination_not_writeable'] = 'Error: Destination not writeable.';
$aLang['error_file_not_saved'] = 'Error: File upload not saved.';
$aLang['error_filetype_not_allowed'] = 'Error: File upload type not allowed.';
$aLang['success_file_saved_successfully'] = 'Erfolg: Datei erfolgreich hochgeladen.';
$aLang['warning_no_file_uploaded'] = 'Warning: No file uploaded.';
$aLang['warning_file_uploads_disabled'] = 'Warning: File uploads are disabled in the php.ini configuration file.';


// 404 Email Error Report
$aLang['error404_email_subject'] = '404 Fehlerreport';
$aLang['error404_email_header'] = '404 Fehlermeldung';
$aLang['error404_email_text'] = 'Ein 404-Fehler trat auf';
$aLang['error404_email_date'] = 'Datum:';
$aLang['error404_email_uri'] = 'Die fehlerhafte URI:';
$aLang['error404_email_ref'] = 'Die Ausgangsseite:';

$aLang['err404'] = '404 Fehlermeldung';
$aLang['err404_page_not_found'] = 'Die angeforderte Seite wurde nicht gefunden bei';
$aLang['err404_sorry'] = 'Die angeforderte Seite';
$aLang['err404_doesntexist'] = 'existiert nicht bei';
$aLang['err404_mailed'] = '<b>Details zum Fehler wurden automatisch an den Webmaster gesendet.</b>';
$aLang['err404_commonm'] = '<b>Typische Fehler</b>';
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
$aLang['text_login_need_upgrade_csnewsletter'] = '<font color="#ff0000"><b>NOTE:</b></font>You have already subscribed to an account for &quot;Newsletter &quot;. You need to upgade this account to be able to buy.';

// use TimeBasedGreeting
$aLang['good_morning'] = 'Guten Morgen!';
$aLang['good_afternoon'] = 'Guten Tag!';
$aLang['good_evening'] = 'Guten Abend!';

$aLang['text_taxt_incl'] = 'inkl. gesetzl. MwSt.';
$aLang['text_taxt_add'] = 'zzgl. gesetzl. MwSt.';
$aLang['tax_info_excl'] = 'exkl. MwSt.';
$aLang['text_shipping'] = 'zzgl. <a href="%s"><u>Versandkosten</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'Ab';

$aLang['price_info'] = 'Alle Preise pro Stück in &euro; inkl. der gesetzlichen Mehrwertsteuer, zzgl. <a href="' . oos_href_link($aContents['information'], 'information_id=1') . '">Versandkostenpauschale</a>.';
$aLang['support_info'] = 'Haben Sie noch Fragen? Sie erreichen uns über unser Kontaktformular.';

