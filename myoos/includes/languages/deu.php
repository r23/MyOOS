<?php
/**
   ----------------------------------------------------------------------
   $Id: deu.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: german.php,v 1.116 2003/02/17 11:49:26 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'de_DE'
  * on FreeBSD try 'de_DE.ISO_8859-1'
  * on Windows try 'de' or 'German'
  */
  define('THE_LOCALE', 'de_DE');
  define('DATE_FORMAT_SHORT', '%d.%m.%Y');
  define('DATE_FORMAT_LONG', '%A, %d. %B %Y');
  define('DATE_FORMAT', 'd.m.Y');
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');
  define('DATE_TIME_FORMAT_SHORT', '%H:%M:%S');


 /**
  * Return date in raw format
  * $date should be in format mm/dd/yyyy
  * raw date is in format YYYYMMDD, or DDMMYYYY
  *
  * @param  $date
  * @param  $reverse
  * @return string
  */
function oos_date_raw($date, $reverse = false)
{
    if ($reverse) {
        return substr((string) $date, 0, 2) . substr((string) $date, 3, 2) . substr((string) $date, 6, 4);
    } else {
        return substr((string) $date, 6, 4) . substr((string) $date, 3, 2) . substr((string) $date, 0, 2);
    }
}

// Global entries for the <html> tag
define('LANG', 'de');
define('DOB_FORMAT_STRING', 'tt.mm.jjjj');

$aLang = [
'danger'                => 'Oh nein! Es ist ein Fehler aufgetreten!',
'warning'               => 'Warnung!',
'info'                   => 'Anmeldung!',

// theme/system/_header.html
'header_title_create_account'  => 'Neues Konto',
'header_title_my_account'      => 'Mein Konto',
'header_title_cart_contents'   => 'Warenkorb',
'header_title_checkout'        => 'Kasse',
'header_title_top'             => 'Home',
'header_title_logoff'          => 'Abmelden',
'header_title_login'           => 'Anmelden',
'header_title_contact'         => 'Kontakt',
'header_title_search'          => 'Suche',
'header_title_whats_new'       => 'Neue Produkte',
'header_select_language'       => 'Meine Sprache',
'header_select_currencies'     => 'Meine Währung',
'sub_title_total'              => 'Summe:',
'block_heading_specials'       => 'Angebote',
'nav_toggle'                   => 'Navigation öffnen oder schließen',
// footer text in includes/oos_footer.php
'footer_text_requests_since'   => 'Zugriffe seit',
// text for gender
'male'                         => 'Herr',
'female'                       => 'Frau',
'diverse'                      => 'Divers',
'male_address'                 => 'Herr',
'female_address'               => 'Frau',
'diverse_address'              => '',
'email_greet_mr'               => 'Sehr geehrter Herr %s,',
'email_greet_ms'               => 'Sehr geehrte Frau %s,',
'email_greet_diverse'          => 'Guten Tag %s,',
'email_greet_none'             => 'Guten Tag!',

// text for date of birth example
'dob_format_string'            => 'tt.mm.jjjj',
// search block text in tempalate/your theme/block/search.html
'block_search_text'            => 'Verwenden Sie Stichworte, um ein Produkt zu finden.',
'block_search_advanced_search' => 'erweiterte Suche',
'text_search'                  => 'suchen...',
// reviews block text in tempalate/your theme/block/reviews.php
'block_reviews_write_review'   => 'Bewerten Sie dieses Produkt!',
'block_reviews_no_reviews'     => 'Es liegen noch keine Bewertungen vor',
'block_reviews_text_of_5_stars' => '%s von 5 Sternen!',
// shopping_cart block text in tempalate/your theme/system/_header.html
'block_shopping_cart_empty'    => '0 Produkte',
'sub_title_sub_total'          => 'Summe',

// notifications block text in tempalate/your theme/block/products_notifications.php
'block_notifications_notify'        => 'Benachrichtigen Sie mich über Aktuelles zum Artikel <strong>%s</strong>',
'block_notifications_notify_remove' => 'Benachrichtigen Sie mich nicht mehr zum Artikel <strong>%s</strong>',

// wishlist
'button_wishlist'             => 'Merkliste',
'block_wishlist'              => 'Merkliste',
'block_wishlist_empty'        => 'Sie haben keine Produkte auf Ihrem Merkzettel',

// manufacturer box text
'block_manufacturer_info_homepage'        => '%s Homepage',
'block_manufacturer_info_other_products'  => 'Mehr Produkte',

// information block text in tempalate/your theme/block/information.html
'block_information_imprint'         => 'Impressum',
'block_information_privacy'         => 'Datenschutz',
'block_information_conditions'      => 'AGB',
'block_information_shipping'        => 'Liefer- und&nbsp;Versandkosten',
'block_information_gv'              => 'Gutschein einlösen',
'block_cookie_settings'             => 'Cookie-Einstellungen',

//login
'entry_email_address'               => 'eMail Adresse',
'entry_password'                    => 'Passwort',
'text_password_info'                => 'Passwort vergessen?',
'button_login'                      => 'Login',
'login_block_new_customer'          => 'Neukunde',
'login_block_account_history'       => 'Mein Kontoverlauf',
'login_block_order_history'         => 'Meine Bestellungen',
'login_block_address_book'          => 'Mein Adressbuch',
'login_block_product_notifications' => 'Benachrichtigungen',
'login_block_my_account'            => 'Mein Konto',
'login_block_logoff'                => 'Abmelden',
'login_block_no_account_yet'         => 'Noch keinen Account?',
'login_block_book_now'              => 'Buchen Sie jetzt %s',
'text_password_forgotten'           => 'Sie haben Ihr Passwort vergessen?',
'link_password_forgotten'           => 'Dann klicken Sie <u>hier</u>',
'text_please_enter_a_password'        => 'Bitte geben Sie Ihr Passwort ein',
'text_please_provide_email_address' => 'Bitte geben Sie Ihre E-Mail Adresse ein',


//offcanvas-cart
'text_clear_cart'                 => 'Warenkorb löschen',
'text_item_successfull'            => 'Der Artikel wurde erfolgreich in den Warenkorb gelegt.',


// checkout procedure text
'checkout_bar_delivery'           => 'Versandinformationen',
'checkout_bar_payment'            => 'Zahlungsweise',
'checkout_bar_confirmation'       => 'Bestätigung',
'checkout_bar_finished'           => 'Fertig!',

// pull down default text
'pull_down_default'               => 'Bitte wählen',
'type_below'                      => 'bitte unten eingeben',

//newsletter
'block_newsletter_subscribe'      => 'Abonnieren Sie unseren wöchentlichen <strong>Newsletter</strong>',
'block_newsletter_placeholder'    => 'Ihre E-Mail-Adresse eingeben...',
'block_newsletter_unsubscribe'    => 'Austragen',
'error_email_address'             => '<strong>FEHLER Ihre E-Mail-Adresse:</strong> Keine oder ungültige Eingabe!',
'newsletter_email_info'           => 'Ihre E-Mail-Adresse wurde in unser System eingetragen.<br />Gleichzeitig wurde Ihnen vom System eine E-Mail mit einem Aktivierungslink geschickt. Bitte klicken Sie nach dem Erhalt der E-Mail auf den Link, um Ihre Eintragung zu bestätigen.',
'newsletter_email_subject'        => 'Bitte bestätigen Sie Ihre Anmeldung',
'newsletter_notice'               => 'Sie können Ihr Einverständnis jederzeit widerrufen. Unsere Kontaktinformationen finden Sie u. a. in der Datenschutzerklärung.',
'entry_newsletter_no'             => 'Nein',
'entry_newsletter_yes'            => 'Ja',
'text_email_active'               => 'Ihre E-Mail-Adresse wurde erfolgreich für den Newsletterempfang freigeschaltet!',
'text_email_active_error'         => 'Es ist ein Fehler aufgetreten, Ihre E-Mail-Adresse wurde nicht freigeschaltet!',
'text_email_del'                  => 'Ihre E-Mail-Adresse wurde aus unserer Newsletterdatenbank gelöscht.',
'text_email_del_error'            => 'Es ist ein Fehler aufgetreten, Ihre E-Mail-Adresse wurde nicht gelöscht!',

//footer
'get_in_touch_with_us' => '' . $sServer . ' entdecken',
'header_title_service'            => 'Shop Service',
'block_service_specials'          => 'Angebote',
'block_service_sitemap'           => 'Sitemap',
'block_service_advanced_search'   => 'Erweiterte Suche',
'block_service_reviews'           => 'Meinungen',
'block_service_shopping_cart'     => 'Warenkorb',
'block_service_contact'           => 'Kontakt',

'page_order_history'              => 'Meine Bestellungen',
'page_products_new'               => 'Neue Produkte',
'page_specials'                   => 'Angebote',
'page_blog'                       => 'Blog',
'page_phpb3'                      => 'Support Forum',


'form_error'                      => '<strong>Notwendige Angaben fehlen!</strong> Bitte richtig ausfüllen.',

// javascript messages
'js_error'                        => 'Notwendige Angaben fehlen!\nBitte richtig ausfüllen.\n\n',
'js_gender'                       => '* Anredeform festlegen.\n',
'js_first_name'                   => '* Der \'Vornname\' muss mindestens aus ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_last_name'                    => '* Der \'Nachname\' muss mindestens aus ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_dob'                          => '* Die \'Geburtsdaten\' im Format xx.xx.xxxx (Tag.Monat.Jahr) eingeben.\n',
'js_email_address'                => '* Die \'eMail-Adresse\' muss mindestens aus ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_address'                      => '* Die \'Straße/Nr.\' muss mindestens aus ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_post_code'                    => '* Die \'Postleitzahl\' muss mindestens aus ' . ENTRY_POSTCODE_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_city'                         => '* Die \'Stadt\' muss mindestens aus ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben bestehen.\n',
'js_state'                        => '* Das \'Bundesland\' muss ausgewählt werden.\n',
'js_country'                      => '* Das \'Land\' muss ausgewählt werden.\n',
'js_password'                     => '* Das \'Passwort\' und die \'Bestätigung\' müssen übereinstimmen und mindestens ' . ENTRY_PASSWORD_MIN_LENGTH . ' Buchstaben enthalten.\n',

'js_error_no_payment_module_selected'  => '* Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.\n',
'js_error_submitted'                   => 'Diese Seite wurde bereits bestätigt. Betätigen Sie bitte OK und warten bis der Prozess durchgeführt wurde.',

'error_no_payment_module_selected'     => 'Bitte wählen Sie eine Zahlungsweise für Ihre Bestellung.',

'category_company'                     => 'Firmendaten',
'category_personal'                    => 'Ihre persönlichen Daten',
'category_address'                     => 'Ihre Adresse',
'category_contact'                     => 'Ihre Kontaktinformationen',
'category_options'                     => 'Optionen',
'category_password'                    => 'Ihr Passwort',
'entry_company'                        => 'Firmenname',
'entry_company_error'                  => '',
'entry_company_text'                   => '',
'entry_owner'                          => 'Inhaber',
'entry_owner_error'                    => '',
'entry_owner_text'                     => '',
'entry_vat_id'                         => 'Umsatzsteuer ID',
'entry_vat_id_error'                   => 'Die Eingegebene USt-IdNr. ist ungültig oder kann derzeit nicht überprüft werden! Bitte geben Sie eine gültige Umsatzsteuer ID ein oder lassen Sie das Feld leer.',
'entry_vat_id_text'                    => 'Nur für Deutschland und EU!',

'entry_number_text'                    => '',
'entry_gender'                         => 'Anrede',
'entry_gender_error'                   => 'Bitte wählen Sie Ihre Anrede.',
'entry_first_name'                     => 'Vorname',
'entry_first_name_error'               => 'Ihr Vorname muss mindestens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' Buchstaben enthalten',
'entry_last_name'                      => 'Nachname',
'entry_last_name_error'                => 'Ihr Nachname muss mindestens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' Buchstaben enthalten',
'entry_date_of_birth'                  => 'Geburtsdatum',
'entry_date_of_birth_text'             => '(z.B. 21.05.1970)',
'entry_date_of_birth_error'            => 'Ihr Geburtsdatum muss im Format TT.MM.JJJJ (zB. 21.05.1970) eingeben werden',

'entry_email_address_error'            => 'Ihre E-Mail muss mindestens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' Buchstaben enthalten',
'entry_email_address_check_error'      => 'Bitte überprüfen Sie Ihre E-Mail-Adresse',
'entry_email_address_error_exists'     => 'Ihre E-Mail-Adresse existiert bereits in unserem Online Shop - bitte melden Sie sich mit der E-Mail-Adresse an oder erstellen Sie ein Konto mit einer anderen E-Mail-Adresse.',
'entry_street_address'                 => 'Straße/Nr.',
'entry_street_address_error'           => 'Ihre Straße muss mindestens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' Buchstaben enthalten',

'entry_post_code'                      => 'Postleitzahl',
'entry_post_code_error'                => 'Ihre Postleitzahl muss mindestens ' . ENTRY_POSTCODE_MIN_LENGTH . ' Zahlen enthalten',
'entry_city'                           => 'Ort',
'entry_city_error'                     => 'Ihr Ort muss mindestens ' . ENTRY_CITY_MIN_LENGTH . ' Buchstaben enthalten',
'entry_state'                          => 'Bundesland',
'entry_state_error'                    => 'Ihr Bundesland muss mindestens ' . ENTRY_STATE_MIN_LENGTH . ' Buchstaben enthalten',
'entry_state_error_select'             => 'Bitte wählen Sie Ihr Bundesland aus der Liste aus.',

'entry_country'                        => 'Land',
'entry_country_error'                  => 'Bitte wählen Sie Ihr Land aus der Liste aus.',
'entry_telephone_number'               => 'Telefonnummer',
'entry_newsletter'                     => 'Newsletter',
'entry_newsletter_text'                => '',
'entry_newsletter_yes'                 => 'abonniert',
'entry_newsletter_no'                  => 'nicht abonniert',
'entry_newsletter_error'               => '',
'entry_password_confirmation'          => 'Bestätigung',
'entry_password_error'                 => 'Ihr Passwort muss mindestens '  . ENTRY_PASSWORD_MIN_LENGTH . ' Zeichen enthalten',
'entry_password_error_not_matching'    => 'Ihre Passwörter stimmen leider nicht überein.',

'password_hidden'                      => '--VERSTECKT--',
'entry_info_text'                      => 'notwendige Eingabe',
'entry_subject'                        => 'Subject',

'entry_agree_error'                    => 'Bitte akzeptieren Sie unsere AGB und Datenschutzbestimmungen!',
'agree'                                => 'Ja, ich stimme den <a href="%s" target="_blank" rel="noopener"><strong>AGB</strong></a> und den <a href="%s" target="_blank" rel="noopener"><strong>Datenschutzbestimmungen</strong></a> zu.',
'newsletter_agree'                     => 'Ja, ich möchte per E-Mail-Newsletter über Trends, Aktionen &amp; Gutscheine informiert werden. Abmeldung jederzeit möglich.',

'success_address_book_entry_deleted'   => 'Die ausgewählte Adresse wurde erfolgreich gelöscht.',
'warning_primary_address_deletion'     => 'Die Standardadresse kann nicht gelöscht werden.',
'success_address_book_entry_updated'   => 'Ihr Adressbuch wurde erfolgreich aktualisiert!',
'error_nonexisting_address_book_entry' => 'Dieser Adressbucheintrag ist leider nicht vorhanden.',
'error_address_book_full'              => 'Ihr Adressbuch kann leider keine weiteren Adressen aufnehmen. Bitte löschen Sie eine nicht mehr benötigte Adresse. Danach können Sie eine neue Adresse speichern.',

// constants for use in oos_prev_next_display function
'text_result_page'                     => 'Seiten:',
'text_display_number_of_products'      => 'angezeigte Produkte: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',
'text_display_number_of_orders'        => 'angezeigte Bestellungen: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',
'text_display_number_of_reviews'       => 'angezeigte Meinungen: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',
'text_display_number_of_products_new'  => 'angezeigte neue Produkte: <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',
'text_display_number_of_specials'      => 'angezeigte Angebote <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',
'text_display_number_of_wishlist'      => 'angezeigt Wunschprodukte <strong>%d</strong> bis <strong>%d</strong> (von <strong>%d</strong> insgesamt)',

'prevnext_title_first_page'            => 'erste Seite',
'prevnext_title_previous_page'         => 'vorherige Seite',
'prevnext_title_next_page'             => 'nächste Seite',
'prevnext_title_last_page'             => 'letzte Seite',
'prevnext_title_page_no'               => 'Seite %d',
'prevnext_title_prev_set_of_no_page'   => 'Vorhergehende %d Seiten',
'prevnext_title_next_set_of_no_page'   => 'Nächste %d Seiten',
'prevnext_button_first'                => '&lt;&lt;ERSTE',
'prevnext_button_prev'                 => '&lt;&lt;&nbsp;vorherige',
'prevnext_button_next'                 => 'nächste&nbsp;&gt;&gt;',
'prevnext_button_last'                 => 'LETZTE&gt;&gt;',
'prevnext_slider_previous'             => 'zurück',
'prevnext_slider_next'                 => 'weiter',

'button_account'                     => 'Profil',
'button_add_address'                 => 'Neue Adresse',
'button_address_book'                => 'Adressbuch',
'button_apply_coupon'                => 'Gutschein einlösen',
'button_back'                        => 'Zurück',
'button_buy_it_again'                => 'Nochmals kaufen',
'button_calculate_shipping'          => 'Versand berechnen',
'button_change_address'              => 'Adresse ändern',
'button_checkout'                    => 'Kasse',
'button_clear_cart'                  => 'Warenkorb löschen',
'button_confirm_order'               => 'Jetzt zum genannten Preis bestellen',
'button_continue'                    => 'Weiter',
'button_continue_shopping'           => 'Einkauf fortsetzen',
'button_delete'                      => 'Löschen',
'button_edit_account'                => 'Daten ändern',
'button_history'                     => 'Bestellübersicht',
'button_login'                       => 'Anmelden',
'button_in_cart'                     => 'In den Warenkorb',
'button_notifications'               => 'Benachrichtigungen',
'button_set_price_alert'             => 'Preisalarm einstellen',
'button_place_price_alert'           => 'Preisalarm stellen',
'button_save_price_alert'            => 'Weiter mit E-Mail',
'button_quick_find'                  => 'Schnellsuche',
'button_remove_notifications'        => 'Benachrichtigungen löschen',
'button_reviews'                     => 'Bewertungen',
'button_submit_a_review'             => 'Bewertung abgeben',
'button_send'                        => 'Absenden',
'button_send_message'                => 'Nachricht senden',
'button_search'                      => 'Suche starten',
'button_start_shopping'              => 'Beginnen Sie Ihren Einkauf',
'button_update'                      => 'Aktualisieren',
'button_update_cart'                 => 'Warenkorb aktualisieren',
'button_write_review'                => 'Bewertung schreiben',
'button_write_a_product_review'      => 'Schreiben Sie eine Produktrezension',
'button_write_first_review'          => 'Schreiben Sie die erste Produktrezension',
'button_add_quick'                   => 'Schnellkauf!',
'image_wishlist_delete'              => 'löschen',
'button_add_wishlist'                => 'Artikel merken',
'button_redeem_voucher'              => 'Gutschein einlösen',
'button_callaction'                  => 'Fordern Sie ein Angebot',
'button_register'                    => 'Jetzt registrieren',
'button_further_than_guest'          => 'Weiter als Gast',
'button_save_info'                   => 'Daten speichern',
'button_view'                        => 'Details',

'button_new_2fa'                     => 'Einrichten',
'button_not_now'                     => 'Nicht jetzt',
'button_activate'                    => 'Aktivieren',
'button_deactivate'                  => 'Deaktivieren',

'button_hp_buy'            => 'In den Warenkorb',
'button_hp_more'           => 'Zeige mehr',

'icon_button_mail'          => 'E-mail',
'icon_button_movie'         => 'Movie',
'icon_button_pdf'           => 'PDF',
'icon_button_print'         => 'Print',
'icon_button_zoom'          => 'Zoom',


'icon_arrow_right'          => 'Zeige mehr',
'icon_cart'                 => 'In den Warenkorb',
'icon_warning'              => 'Warnung',

'text_sort_products'          => 'Sortierung der Artikel ist ',
'text_descendingly'           => 'absteigend',
'text_ascendingly'            => 'aufsteigend',
'text_by'                     => ' nach ',

'text_review_by'             => 'von %s',
'text_review_word_count'     => '%s Worte',
'text_review_rating'         => 'Bewertung:',
'text_review_date_added'     => 'Datum hinzugefügt: ',
'text_no_reviews'            => 'Es liegen noch keine Bewertungen vor.',
'text_no_new_products'       => 'Zur Zeit gibt es keine neuen Produkte.',
'text_unknown_tax_rate'      => 'Unbekannter Steuersatz',
'text_required'              => 'erforderlich',
'text_more'                  => 'mehr...',
'text_new'                   => 'NEU',
'text_sale'                   => 'SALE',
'text_categories'              => 'Kategorien',

'text_no_longer_available'    => 'Nicht mehr verfügbar',
'text_replacement_product'    => 'Es gibt ein Ersatzprodukt',

'warning_install_directory_exists'           => 'Warnung: Das Installationsverzeichnis ist noch vorhanden auf: ' . dirname((string) oos_server_get_var('SCRIPT_FILENAME')) . '/install. Bitte löschen Sie das Verzeichnis aus Gründen der Sicherheit!',
'warning_config_file_writeable'              => 'Warnung: MyOOS [Shopsystem] kann in die Konfigurationsdatei schreiben: ' . dirname((string) oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. Das stellt ein mögliches Sicherheitsrisiko dar - bitte korrigieren Sie die Benutzerberechtigungen zu dieser Datei!',
'warning_download_directory_non_existent'    => 'Warnung: Das Verzeichnis für den Artikel Download existiert nicht: ' . OOS_DOWNLOAD_PATH . '. Diese Funktion wird nicht funktionieren bis das Verzeichnis erstellt wurde!',

'info_login_for_wichlist'             => 'Sie möchten Ihre Artikel dauerhaft speichern und alle Funktionen des Merkzettels nutzen? Dann melden Sie sich bitte an und wir speichern Ihre Artikel auf Ihrem Merkzettel im Kundenkonto.',

'text_ccval_error_invalid_date'         => 'Das \'Gültig bis\' Datum ist ungültig.<br>Bitte korrigieren Sie Ihre Angaben.',
'text_ccval_error_invalid_number'       => 'Die \'KreditkarteNummer\', die Sie angegeben haben, ist ungültig.<br>Bitte korrigieren Sie Ihre Angaben.',
'text_ccval_error_unknown_card'         => 'Die ersten 4 Ziffern Ihrer Kreditkarte sind: %s<br>Wenn diese Angaben stimmen, wird dieser Kartentyp leider nicht akzeptiert.<br>Bitte korrigieren Sie Ihre Angaben gegebenfalls.',

'voucher_balance'                 => 'Gutschein - Guthaben',
'gv_faq'                          => 'Gutscheine, Fragen und Antworten',
'error_redeemed_amount'           => 'Prima: Der Einlösewert wurde Ihrem Kundenkonto gutgeschrieben! ',
'error_no_redeem_code'            => 'Sie haben keinen Gutschein-Code eingegeben!',
'error_no_invalid_redeem_gv'      => 'Fehler: Sie haben keinen gültigen Gutschein-Code eingegeben!',
'table_heading_credit'            => 'Guthaben',
'gv_has_vouchera'                => 'Sie haben ein Gutschein - Guthaben auf Ihrem Kundenkonto. Möchten Sie einen Teil <br>
                         Ihres Guthabens per',
'gv_has_voucherb'                => 'versenden?',
'entry_amount_check_error'       => '&nbsp;Leider keine ausreichende Deckung auf Ihrem Kundenkonto!',


'voucher_redeemed'           => 'Voucher Redeemed',
'cart_coupon'                => 'Coupon :',
'cart_coupon_info'           => 'Weitere Informationen',

'category_payment_details'   => 'Auszahlung kann erfolgen über',

'block_ticket_generate'     => 'Support Anfrage',
'block_ticket_view'         => 'Ticket einsehen',

'down_for_maintenance_text'               => 'Down for Maintenance ... Please try back later',
'down_for_maintenance_no_prices_display'  => 'Down for Maintenance',
'no_login_no_prices_display'              => 'Preise nur für Händler',
'text_products_base_price'                => 'Grundpreis',

'products_see_qty_discounts'           => 'Staffelpreise',
'products_order_qty_text'              => 'Menge: ',
'products_order_qty_min_text'          => ' Mindestbestellmenge: ',
'products_order_qty_min_text_info'     => ' Die Mindestbestellmenge ist: ',
'products_order_qty_min_text_cart'     => ' Die Mindestbestellmenge ist: ',
'products_order_qty_min_text_cart_short' => 'min. M.: ',

'products_order_qty_unit_text'           => ' Verpackungseinheit: ',
'products_order_qty_unit_text_info'      => 'bei der Verpackungseinheit von: ',
'products_order_qty_unit_text_cart'      => 'bei der Verpackungseinheit von: ',
'products_order_qty_unit_text_cart_short' => ' Verpackungseinheit: ',

'error_products_quantity_order_min_text'    => '',
'error_products_quantity_invalid'           => 'Fehler: Menge: ',
'error_products_quantity_order_units_text'  => '',
'error_products_units_invalid'              => 'Fehler: Menge ',
'error_product_has_attributes'                => 'Dieses Produkt hat Variationen. Wählen Sie bitte die gewünschte Variation aus.',
'error_product_information_obligation'         => 'Bei diesem Produkt haben Sie die Möglichkeit der unentgeltlichen Rücknahme Ihres Altgerätes. Weitere Informationen erhalten Sie hier in den Produktdetials.',
'error_product_information_used_goods'         => 'Bitte bestätigen Sie, dass Sie die Gebrauchtware (B-Ware) Hinweise erhalten haben.',


'error_destination_does_not_exist'          => 'Fehler: Speicherort existiert nicht.',
'error_destination_not_writeable'           => 'Fehler: Speicherort ist nicht beschreibbar.',
'error_file_not_saved'                      => 'Fehler: Datei wurde nicht gespeichert.',
'error_filetype_not_allowed'                => 'Fehler: Dateityp ist nicht erlaubt.',
'success_file_saved_successfully'           => 'Erfolg: Datei erfolgreich hochgeladen.',
'warning_no_file_uploaded'                  => 'Warnung: Es wurde keine Datei hochgeladen.',
'warning_file_uploads_disabled'             => 'Warnung: Datei-Uploads sind in der Konfigurationsdatei php.ini deaktiviert.',


'err404'                 => '404 Fehlermeldung',
'err404_page_not_found'  => 'Die angeforderte Seite wurde nicht gefunden bei',
'err404_sorry'           => 'Die angeforderte Seite',
'err404_doesntexist'     => 'existiert nicht bei',
'err404_mailed'          => '<strong>Details zum Fehler wurden automatisch an den Webmaster gesendet.</strong>',
'err404_commonm'         => '<strong>Typische Fehler</strong>',
'err404_commonh'         => 'Namenskonventionen nicht beachtet',
'err404_urlend'          => 'die angegebene URL endet mit',
'err404_allpages'        => 'alle Seiten bei',
'err404_endwith'         => 'enden mit',
'err404_uppercase'       => 'die Benutzung von GROSSBUCHSTABEN',
'err404_alllower'        => 'alle Namen werden kleingeschrieben',

'text_info_csname'       => 'Sie sind Mitglied der Kundengruppe : ',
'text_info_csdiscount'   => 'Sie bekommen je nach Produkt einen maximalen Rabatt bis zu : ',
'text_info_csotdiscount' => 'Sie bekommen auf Ihre Gesamtbestellung einen Rabatt von : ',
'text_info_csstaff'      => 'Sie sind berechtigt zu Staffelpreisen einzukaufen.',
'text_info_cspay'        => 'Folgende Zahlungsarten können Sie benutzen : ',
'text_info_show_price_no'             => 'Sie erhalten noch keine Preisinformationen. Bitte melden Sie sich an',
'text_info_show_price_with_tax_yes'   => 'Die Preise beinhalten die MwSt.',
'text_info_show_price_with_tax_no'    => 'Die Preise werden ohne MwSt. angezeigt.',
'text_info_receive_mail_mode'         => 'Infos hätte ich gerne im Format : ',
'entry_receive_mail_text'             => 'Text only',
'entry_receive_mail_html'             => 'HTML',
'entry_receive_mail_pdf'              => 'PDF',

'table_heading_price_unit'       => 'pro Stk.Netto',
'table_heading_discount'         => 'Rabatt',
'table_heading_ot_discount'      => 'Pauschalrabatt',
'text_info_minimum_amount'       => 'Ab dem folgenden Bestellwert erhalten Sie den Pausschalrabatt.',
'sub_title_ot_discount'          => 'Pauschalrabatt:',
'text_new_customer_introduction_newsletter'     => 'Durch das Abonnieren des Newsletters von ' .  STORE_NAME . ' Sie werden über alle Neuigkeiten auf dem Laufenden gehalten.',
'text_new_customer_ip'                     => 'Dieses Konto wurde von dieser Computer-IP erstellt: ',
'text_customer_account_password_security'  => 'Zu Ihrer eigenen Sicherheit können wir das Passwort nicht kennen. Wenn Sie es vergessen haben, können Sie neues anfordern.',

'text_tax_incl'        => 'Preisangaben inkl. gesetzl. MwSt.',
'tax_incl_available_from'   => 'inkl. %s MwSt.',
'text_tax_add'          => 'zzgl. gesetzl. MwSt.',
'tax_info_excl'         => 'exkl. MwSt.',
'text_shipping'         => ' und zzgl. <a href="%s">Service- und Versandkosten</a>.',

'text_excl_tax_plus_shipping'   => 'exkl. MwSt., zzgl. <a href="%s">Versandkosten</a>',
'text_incl_tax_plus_shipping'   => 'inkl. MwSt., zzgl. <a href="%s">Versandkosten</a>',
'total_info'                    => 'alle Angaben in %s, inkl. MwSt.',


'price'                => 'Preis',
'price_from'           => 'Ab',

'price_reduced_from'   => 'Statt',
'price_rrp'            => 'UVP',

'only_until'        => 'Nur bis zum %s!',
'text_content'         => 'Inhalt: ',
'text_base_price'    => 'Grundpreis: ',

'in_stock'     => 'sofort lieferbar',
'out_of_stock' => 'später wieder lieferbar',
'available_from'    => 'lieferbar ab ca. %s',

'text_info_minimum_order_value' => 'Bitte beachten Sie den Mindestbestellwert von %s',
'warning_minimum_order_value'   => 'Der Mindestbestellwert von %s wurde noch nicht erreicht. Daher ist aktuell mit diesem Warenkorb keine Bestellung möglich.',


];

if (defined('TEST')) {
    $aLang = [
    'review_text'                     => 'Die Rezension muss mindestens aus ' . REVIEW_TEXT_MIN_LENGTH . ' Buchstaben bestehen.',
    'review_rating'                   => 'Geben Sie Ihre Bewertung ein.',
    'review_headline'                 => 'Die Überschrift muss mindestens aus 10 Buchstaben bestehen.',
    ];
}