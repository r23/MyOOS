<?php
/* ----------------------------------------------------------------------
   $Id: nld.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  * on RedHat try 'nl_NL'
  * on FreeBSD try 'nl_NL.ISO_8859-1'
  * on Windows try 'nl' or 'Dutch'
  */
  @setlocale(LC_TIME, 'nl_NL');
  define('DATE_FORMAT_SHORT', '%d-%m-%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A, %d %B %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'd-m-Y');  // this is used for strftime()
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
define('HTML_PARAMS','dir="LTR" lang="nl"');
define('XML_PARAMS','xml:lang="nl" lang="nl"');

// charset for web pages and emails
define('CHARSET', 'iso-8859-15');

//text in oos_temp/templates/oos/system/user_navigation.html
$aLang['header_title_create_account'] = 'Nieuwe rekening';
$aLang['header_title_my_account'] = 'Mijn rekening';
$aLang['header_title_cart_contents'] = 'Winkelwagen';
$aLang['header_title_checkout'] = 'Kassa';
$aLang['header_title_top'] = 'Start';
$aLang['header_title_catalog'] = 'Catalogus';
$aLang['header_title_logoff'] = 'Afmelden';
$aLang['header_title_login'] = 'Aanmelden';
$aLang['header_title_whats_new'] = 'Nieuwe produkten';

$aLang['block_heading_specials'] = 'Aanbiedingen';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'bezoekers sinds';

// text for gender
$aLang['male'] = 'mijnheer';
$aLang['female'] = 'mevrouw';
$aLang['male_address'] = 'Mijnheer';
$aLang['female_address'] = 'Mevrouw';

// text for date of birth example
define('DOB_FORMAT_STRING', 'dd.mm.jjjj');
$aLang['dob_format_string'] = 'dd.mm.jjjj';

// search block text in tempalate/your theme/block/search.html
$aLang['block_search_text'] = 'Gebruik a.u.b. verkorte begrippen om een produkt te vinden.';
$aLang['block_search_advanced_search'] = 'Uitgebreid zoeken';
$aLang['text_search'] = 'search...';

// reviews block text in tempalate/your theme/block/reviews.php
$aLang['block_reviews_write_review'] = 'Beoordeel dit product!';
$aLang['block_reviews_no_reviews'] = 'Er zijn nog geen beoordelingen';
$aLang['block_reviews_text_of_5_stars'] = '%s van 5 sterren!';

// shopping_cart block text in tempalate/your theme/block/shopping_cart.html
$aLang['block_shopping_cart_empty'] = '0 Produkten';

// notifications block text in tempalate/your theme/block/products_notifications.php
$aLang['block_notifications_notify'] = 'Informeer mij over actuele zaken van dit artikel <b>%s</b>';
$aLang['block_notifications_notify_remove'] = 'Informeer mij niet meer over dit artikel <b>%s</b>';

// wishlist block text in tempalate/your theme/block/wishlist.html
$aLang['block_wishlist_empty'] = 'U hebt geen producten op uw verlanglijst';

// manufacturer box text
$aLang['block_manufacturer_info_homepage'] = '%s Homepage';
$aLang['block_manufacturer_info_other_products'] = 'Andere produkten';

$aLang['block_add_product_id_text'] = 'Invoeren van het bestelnummer.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Bedrijsgegevens';
$aLang['block_information_privacy'] = 'Privacy';
$aLang['block_information_conditions'] = 'Leveringsvoorwaarden';
$aLang['block_information_shipping'] = 'Aflevering en<br/>&nbsp; terugname';
$aLang['block_information_contact'] = 'Contact';
$aLang['block_information_v_card'] = 'vCard';
$aLang['block_information_mapquest'] = 'Map This Location';
$aLang['block_skype_me'] = 'Skype Me';
$aLang['block_information_gv'] = 'Tegoedbon feiten';
$aLang['block_information_gallery'] = 'Gallery';

//service
$aLang['block_service_links'] = 'Web Links';
$aLang['block_service_newsfeed'] = 'RSS';
$aLang['block_service_gv'] = 'Tegoedbon feiten';
$aLang['block_service_sitemap'] = 'Sitemap';

//login 
$aLang['entry_email_address'] = 'Emailadres:';
$aLang['entry_password'] = 'Wachtwoord:';
$aLang['text_password_info'] = 'Wachtwoord vergeten?';
$aLang['image_button_login'] = 'Inloggen';
$aLang['login_block_new_customer'] = 'Nieuwe klant';
$aLang['login_block_account_edit'] = 'Gegevens veranderen';
$aLang['login_block_account_history'] = 'Besteloverzicht';
$aLang['login_block_order_history'] = 'Order History';
$aLang['login_block_address_book'] = 'Mijn adresboek';
$aLang['login_block_product_notifications'] = 'Produkt berichten';
$aLang['login_block_my_account'] = 'Algemene gegevens';
$aLang['login_block_logoff'] = 'Afmelden';
$aLang['login_entry_remember_me'] = 'Auto inloggen';

// tell a friend block text in tempalate/your theme/block/tell_a_friend.html
$aLang['block_tell_a_friend_text'] = 'Raad dit artikel aan per email aan anderen.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Verzendinformatie';
$aLang['checkout_bar_payment'] = 'Betalingswijze';
$aLang['checkout_bar_confirmation'] = 'Bevestiging';
$aLang['checkout_bar_finished'] = 'Klaar!';

// pull down default text
$aLang['pull_down_default'] = 'Selecteren a.u.b.';
$aLang['type_below'] = 'Hieronder invoeren';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Subscribe';
$aLang['block_newsletters_unsubscribe'] = 'Unsubscribe';

//myworld
$aLang['text_date_account_created'] = 'Zugang erstellt am:';
$aLang['text_yourstore'] = 'Your Participation';
$aLang['edit_yourimage'] = 'Your Image';

// javascript messages
$aLang['js_error'] = 'er waren fouten tijdens de verwerking van de gegevens!\nGraag het onderstaande corrigeren.\n\n';

$aLang['js_review_text'] = '* De tekst moet minstens uit ' . REVIEW_TEXT_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_review_rating'] = '* Voer uw waardering van dir produkt in.\n';

$aLang['js_gender'] = '* Geslacht kiezen.\n';
$aLang['js_first_name'] = '* De \'Voornaam\' moet minstens uit ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_last_name'] = '* De \'Achternaam\' moet minstens uit ' . ENTRY_LAST_NAME_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_dob'] = '* De \'Geboortedatum\' in Format xx.xx.xxxx (dag.maand.jaar) invoeren.\n';
$aLang['js_email_address'] = '* Het \'emailadres\' moet minstens uit ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_address'] = '* De \'Straat/nr.\' moet minstens uit ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_post_code'] = '* De \'Postcode\' moet minstens uit ' . ENTRY_POSTCODE_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_city'] = '* De \'Stad\' moet minstens uit ' . ENTRY_CITY_MIN_LENGTH . ' karakters bestaan.\n';
$aLang['js_state'] = '* De \'Provincie\' moet gekozen worden.\n';
$aLang['js_country'] = '* Het \'Land\' moet gekozen worden.\n';
$aLang['js_telephone'] = '* Het \'Telefoonnummer\' moet minstens uit ' . ENTRY_TELEPHONE_MIN_LENGTH . ' Zahlen bestehen.\n';
$aLang['js_password'] = '* Het \'Wachtwoord\' en de \'Bevestiging\' moet overeenkomen en minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters hebben.\n';

$aLang['js_error_no_payment_module_selected'] = '* Kies a.u.b. de betalingswijze voor uw bestelling.\n';
$aLang['js_error_submitted'] = 'Deze pagina wordt al verwerkt. Druk a.u.b. op OK en wacht totdat de verwerking klaar is.';

$aLang['error_no_payment_module_selected'] = 'Kies a.u.b. de betalingswijze voor uw bestelling.';
$aLang['error_conditions_not_accepted'] = 'Als u onze leveringsvoorwaarden niet accepteerd, kunnen wij uw bestelling tot onze spijt niet in ontvangst nemen!';

$aLang['category_company'] = 'Bedrijsgegevens';
$aLang['category_personal'] = 'Uw persoonlijke gegevens';
$aLang['category_address'] = 'Uw adres';
$aLang['category_contact'] = 'Uw contactgegevens';
$aLang['category_options'] = 'Opties';
$aLang['category_password'] = 'Uw wachtwoord';
$aLang['entry_company'] = 'Bedrijfsnaam:';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Eigenaar';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'VAT ID';
$aLang['entry_vat_id_error'] = 'The chosen VatID is not valid or not proofable at this moment! Please fill in a valid ID or leave the field empty.';
$aLang['entry_vat_id_text'] = '* for Germany and EU-Countries only';
$aLang['entry_number'] = 'Klantnummer:';
$aLang['entry_number_error'] = '';
$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Geslacht:';
$aLang['entry_gender_error'] = 'eenmalig verplicht';
$aLang['entry_first_name'] = 'Voornaam:';
$aLang['entry_first_name_error'] = 'minstens ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' karakters';
$aLang['entry_first_name_text'] = 'eenmalig verplicht';
$aLang['entry_last_name'] = 'Achternaam:';
$aLang['entry_last_name_error'] = 'minstens ' . ENTRY_LAST_NAME_MIN_LENGTH . ' karakters';
$aLang['entry_date_of_birth'] = 'Geboortedatum:';
$aLang['entry_date_of_birth_error'] = 'FOUT: voorbeeld 21-05-1970';
$aLang['entry_date_of_birth_text'] = '(b.v. 21-05-1970)';
$aLang['entry_email_address'] = 'Emailadres:';
$aLang['entry_email_address_error'] = 'minstens ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' karakters';
$aLang['entry_email_address_check_error'] = 'ongeldig emailadres!';
$aLang['entry_email_address_error_exists'] = 'Dit emailadres bestaat al!';
$aLang['entry_street_address'] = 'Straat/nr.:';
$aLang['entry_street_address_error'] = 'minstens ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' karakters';

$aLang['entry_suburb'] = 'Stadsdeel:';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'Postcode:';
$aLang['entry_post_code_error'] = 'minstens ' . ENTRY_POSTCODE_MIN_LENGTH . ' karakters';
$aLang['entry_city'] = 'Woonplaats:';
$aLang['entry_city_error'] = 'minstens ' . ENTRY_CITY_MIN_LENGTH . ' karakters';
$aLang['entry_state'] = 'Provincie:';
//$aLang['entry_state_error'] = 'eenmalig verplicht';
$aLang['entry_country'] = 'Land:';
$aLang['entry_country_error'] = '';
//$aLang['entry_country_text'] = 'eenmalig verplicht';
$aLang['entry_telephone_number'] = 'Telefoonnummer:';
$aLang['entry_telephone_number_error'] = 'minstens ' . ENTRY_TELEPHONE_MIN_LENGTH . ' cijfers';
$aLang['entry_fax_number'] = 'Faxnummer:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Nieuwsmelding:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'Geabonneerd';
$aLang['entry_newsletter_no'] = 'Niet geabonneerd';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Wachtwoord:';
$aLang['entry_password_confirmation'] = 'Bevestigen:';
//$aLang['entry_password_confirmation_text'] = '&nbsp;<small><font color="#AABBDD">eenmalig verplicht</font></small>';
$aLang['entry_password_error'] = 'minstens ' . ENTRY_PASSWORD_MIN_LENGTH . ' karakters';
$aLang['password_hidden'] = '--VERBORGEN--';
$aLang['entry_info_text'] = 'eenmalig verplicht';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Overzicht pagina:';
$aLang['text_display_number_of_products'] = 'getoonde produkten: <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';
$aLang['text_display_number_of_orders'] = 'getoonde bestellingen: <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';
$aLang['text_display_number_of_reviews'] = 'getoonde meningen: <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';
$aLang['text_display_number_of_products_new'] = 'getoonde nieuwe produkten: <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';
$aLang['text_display_number_of_specials'] = 'getoonde aanbiedingen <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';
$aLang['text_display_number_of_wishlist'] = 'getoonde speciale aanbiedingen <b>%d</b> tot <b>%d</b> (van <b>%d</b> in totaal)';

$aLang['prevnext_title_first_page'] = 'Eerste pagina';
$aLang['prevnext_title_previous_page'] = 'Vorige pagina';
$aLang['prevnext_title_next_page'] = 'Volgende pagina';
$aLang['prevnext_title_last_page'] = 'Laatste pagina';
$aLang['prevnext_title_page_no'] = 'Pagina %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Vorige %d Seiten';
$aLang['prevnext_title_next_set_of_no_page'] = 'Volgende %d Seiten';
$aLang['prevnext_button_first'] = '&lt;&lt;EERSTE';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;vorige';
$aLang['prevnext_button_next'] = 'volgende&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'LAATSTE&gt;&gt;';

$aLang['image_button_add_address'] = 'Adres toevoegen';
$aLang['image_button_address_book'] = 'Adresboek';
$aLang['image_button_back'] = 'Terug';
$aLang['image_button_change_address'] = 'Adres veranderen';
$aLang['image_button_checkout'] = 'Kassa';
$aLang['image_button_confirm_order'] = 'Bestelling bevestigen';
$aLang['image_button_continue'] = 'Verder';
$aLang['image_button_continue_shopping'] = 'Doorgaan met inkopen';
$aLang['image_button_delete'] = 'Wissen';
$aLang['image_button_edit_account'] = 'Gegevens veranderen';
$aLang['image_button_history'] = 'Besteloverzicht';
$aLang['image_button_login'] = 'Aanmelden';
$aLang['image_button_in_cart'] = 'In de winkelwagen';
$aLang['image_button_notifications'] = 'Bericht sturen';
$aLang['image_button_quick_find'] = 'Snelzoeken';
$aLang['image_button_remove_notifications'] = 'Berichten wissen';
$aLang['image_button_reviews'] = 'Beoordelingen';
$aLang['image_button_search'] = 'Zoeken';
$aLang['image_button_tell_a_friend'] = 'Aanbevelen';
$aLang['image_button_update'] = 'Actualiseren';
$aLang['image_button_update_cart'] = 'Winkelwagen actualiseren';
$aLang['image_button_write_review'] = 'Beoordeling schrijven';
$aLang['image_button_add_quick'] = 'Snelkoop!';
$aLang['image_wishlist_delete'] = 'wissen';
$aLang['image_button_in_wishlist'] = 'Verlanglijst';
$aLang['image_button_add_wishlist'] = 'Verlanglijst';
$aLang['image_button_redeem_voucher'] = 'Tegoedbon inwisselen';

$aLang['image_button_hp_buy'] = 'In de winkelwagen';
$aLang['image_button_hp_more'] = 'Toon meer';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Movie';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Print';

$aLang['icon_arrow_right'] = 'Toon meer';
$aLang['icon_cart'] = 'In de winkelwagen';
$aLang['icon_warning'] = 'Waarschuwing';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['text_greeting_personal'] = 'Leuk, dat u weer terug komt <span class="greetUser">%s!</span> Wilt u de <a href="%s"><u>nieuwe produkten</u></a> bekijken?';
$aLang['text_greeting_guest'] = 'Van harte welkom <span class="greetUser">Bezoeker!</span> Wilt u zich <a href="%s"><u>aanmelden</u></a>? of wilt u een <a href="%s"><u>Klantenrekening</u></a> openen?';

$aLang['text_sort_products'] = 'Sortering van het artikel is ';
$aLang['text_descendingly'] = 'neerwaarts';
$aLang['text_ascendingly'] = 'opwaarts';
$aLang['text_by'] = ' naar ';

$aLang['text_review_by'] = 'van %s';
$aLang['text_review_word_count'] = '%s Woorden';
$aLang['text_review_rating'] = 'Beoordeling:';
$aLang['text_review_date_added'] = 'Datum toegevoegd: ';
$aLang['text_no_reviews'] = 'Er zijn nog geen beoordelingen.';
$aLang['text_no_new_products'] = 'Op dit moment zijn er geen nieuwe produkten.';
$aLang['text_unknown_tax_rate'] = 'Onbekend belastingtarief';
$aLang['text_required'] = 'verplicht';
$aLang['error_oos_mail'] = '<small>Fout:</small> De email kan niet via de aangegeven SMTP-Server verstuurd worden. Controleer a.u.b. de instellingen in het php.ini bestand en voer de noodzakelijke correctie uit!';

$aLang['warning_install_directory_exists'] = 'Waarschuwing: De installatiemap is nog aanwezig op: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Wis a.u.b. de map uit veiligheidsoverweging voor het systeem!';
$aLang['warning_config_file_writeable'] = 'Waarschuwing: OOS [OSIS Online Shop] kan in het configuratiebestand schrijven: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. Dit geeft een mogelijk veiligheidrisico - corrigeer a.u.b. de toegangsrechten voor dit bestand!';
$aLang['warning_session_auto_start'] = 'Waarschuwing: session.auto_start is enabled - Zet deze PHP instelling op disable in de php.ini en start de WEB-Server opnieuw!';
$aLang['warning_download_directory_non_existent'] = 'Waarschuwing: De map voor de artikelen download bestaat niet: ' . OOS_DOWNLOAD_PATH . '. Deze functie zal niet functioneren totdat de map is aangemaakt!';
$aLang['warning_session_directory_non_existent'] = 'Waarschuwing: De map voor de de sessies bestaat niet: ' . oos_session_save_path() . '. De sessies zullen niet functioneren totdat de map is aangemaakt!';
$aLang['warning_session_directory_not_writeable'] = 'Waarschuwing: OOS [OSIS Online Shop] kan niet in de sessiesmap schrijven: ' . oos_session_save_path() . '. De ssessies zullen niet functioneren totdat de juiste toegangsrechten gezet zijn!';

$aLang['text_ccval_error_invalid_date'] = 'De "Geldig tot" datum is ongeldig.<br>Corrigeer a.u.b. uw gegevens.';
$aLang['text_ccval_error_invalid_number'] = 'Het "Credietkaartnummer", die u ingevoerd hebben, is ongeldig.<br/>Corrigeer a.u.b. uw gegevens.';
$aLang['text_ccval_error_unknown_card'] = 'De eerste 4 cijfers van uw credietkaart zijn: %s<br>Wanneer deze gegevens kloppen, wordt dit kaarttype helaas niet geaccepteerd.<br>Corrigeer a.u.b. uw gegevens indien nodig.';

$aLang['voucher_balance'] = 'Tegoedbon - tegoed';
$aLang['gv_faq'] = 'Tegoedbon FAQ';
$aLang['error_redeemed_amount'] = 'Uitstekend: De inwisselwaarde werd op uw rekening bijgeschreven! ';
$aLang['error_no_redeem_code'] = 'U hebt geen tegoedboncode ingevoerd!';  
$aLang['error_no_invalid_redeem_gv'] = 'Fout: U hebt geen geldige tegoedboncode ingevoerd!'; 
$aLang['table_heading_credit'] = 'Tegoed';
$aLang['gv_has_vouchera'] = 'U hebt een tegoedbon-tegoed op uw rekening. Wilt u een deel van uw <br>
                         toegoed per';      
$aLang['gv_has_voucherb'] = 'versturen?'; 
$aLang['entry_amount_check_error'] = '&nbsp;<small><font color="#FF0000">Helaas geen voldoende dekking op uw klantenrekening!</font></smal>'; 
$aLang['gv_send_to_friend'] = 'Tegoedbon versturen';

$aLang['voucher_redeemed'] = 'Tegoedbon ingewisseld';
$aLang['cart_coupon'] = 'Tegoedbon :';
$aLang['cart_coupon_info'] = 'meer info';

$aLang['block_affiliate_info'] = 'Partner informatie';
$aLang['block_affiliate_summary'] = 'Partnerrekening overzicht';
$aLang['block_affiliate_account'] = 'Partnerrekening bewerken';
$aLang['block_affiliate_clickrate'] = 'Overzicht kliks';
$aLang['block_affiliate_payment'] = 'Provisiebetalingen';
$aLang['block_affiliate_sales'] = 'Overzicht verkopen';
$aLang['block_affiliate_banners'] = 'Banner';
$aLang['block_affiliate_contact'] = 'Contact';
$aLang['block_affiliate_faq'] = 'FAQ';
$aLang['block_affiliate_login'] = 'Partner aanmelding';
$aLang['block_affiliate_logout'] = 'Afmelden';

$aLang['entry_affiliate_payment_details'] = 'Te betalen aan:';
$aLang['entry_affiliate_accept_agb'] = 'Bevestig a.u.b. dat u met onze <a target="_new" href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_terms'], '', 'SSL') . '">algemene leveringsvoorwaarden</a> accoord gaat.';
$aLang['entry_affiliate_agb_error'] = '&nbsp;<small><font color="#FF0000">U moet met onze algemene leveringsvoorwaarden instemmen.</font></small>';
$aLang['entry_affiliate_payment_check'] = 'Ontvanger van de cheque:';
$aLang['entry_affiliate_payment_check_text'] = '';
$aLang['entry_affiliate_payment_check_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_paypal'] = 'PayPal rekening email:';
$aLang['entry_affiliate_payment_paypal_text'] = '';
$aLang['entry_affiliate_payment_paypal_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_bank_name'] = 'Credietorganisatie:';
$aLang['entry_affiliate_payment_bank_name_text'] = '';
$aLang['entry_affiliate_payment_bank_name_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_bank_account_name'] = 'Rekeningeigenaar:';
$aLang['entry_affiliate_payment_bank_account_name_text'] = '';
$aLang['entry_affiliate_payment_bank_account_name_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_bank_account_number'] = 'Rekeningnr.:';
$aLang['entry_affiliate_payment_bank_account_number_text'] = '';
$aLang['entry_affiliate_payment_bank_account_number_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_bank_branch_number'] = 'IBAN nummer:';
$aLang['entry_affiliate_payment_bank_branch_number_text'] = '';
$aLang['entry_affiliate_payment_bank_branch_number_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_payment_bank_swift_code'] = 'SWIFT Code:';
$aLang['entry_affiliate_payment_bank_swift_code_text'] = '';
$aLang['entry_affiliate_payment_bank_swift_code_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_company'] = 'Firma';
$aLang['entry_affiliate_company_text'] = '';
$aLang['entry_affiliate_company_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_company_taxid'] = 'B.T.W. Nr.:';
$aLang['entry_affiliate_company_taxid_text'] = '';
$aLang['entry_affiliate_company_taxid_error'] = '&nbsp;<small><font color="#FF0000">verplicht</font></small>';
$aLang['entry_affiliate_homepage'] = 'Homepage';

$aLang['entry_affiliate_homepage_text'] = '&nbsp;<small><font color="#000000"> (http://)</font></small>';
$aLang['entry_affiliate_homepage_error'] = '&nbsp;<small><font color="#FF0000">verplicht (http://)</font></small>';

$aLang['category_payment_details'] = 'Uitbetaling kan volgen via';

$aLang['block_ticket_generate'] = 'Hulpaanvraag';
$aLang['block_ticket_view'] = 'Aanvraag bekijken';

$aLang['down_for_maintenance_text'] = 'Gesloten wegens onder onderhoud ... Probeer het a.u.b. straks weer';
$aLang['down_for_maintenance_no_prices_display'] = 'Gesloten wegens onderhoud';
$aLang['no_login_no_prices_display'] = 'Alleen voor handelaren';
$aLang['text_products_base_price'] = 'Basisprijs';

$aLang['products_see_qty_discounts'] = 'Staffelprijzen';
$aLang['products_order_qty_text'] = 'Hoeveelheid: ';
$aLang['products_order_qty_min_text'] = '<br />' . ' Minimale bestelhoeveelheid: ';
$aLang['products_order_qty_min_text_info'] = ' De minimale bestelhoeveelheid is: ';
$aLang['products_order_qty_min_text_cart'] = ' De minimale bestelhoeveelheid is: ';
$aLang['products_order_qty_min_text_cart_short'] = 'min. hoev.: ';

$aLang['products_order_qty_unit_text'] = ' Verpakinghoeveelheid: ';
$aLang['products_order_qty_unit_text_info'] = 'bij de verpakkingseenheid van: ';
$aLang['products_order_qty_unit_text_cart'] = 'bij de verpakkingseenheid van: ';
$aLang['products_order_qty_unit_text_cart_short'] = ' verpakkingseenheid: ';

$aLang['error_products_quantity_order_min_text'] = '';
$aLang['error_products_quantity_invalid'] = 'Fout: Hoeveelheid: ';
$aLang['error_products_quantity_order_units_text'] = '';
$aLang['error_products_units_invalid'] = 'Fout: Hoeveelheid ';

$aLang['error_destination_does_not_exist'] = 'Fout: Bestemming bestaat niet.';
$aLang['error_destination_not_writeable'] = 'Fout: Bestemming niet beschrijfbaar.';
$aLang['error_file_not_saved'] = 'Fout: Bestand upload niet opgeslagen.';
$aLang['error_filetype_not_allowed'] = 'Fout: Bestand upload type niet toegestaan.';
$aLang['success_file_saved_successfully'] = 'Succes: Bestand upload succesvol opgeslagen.';
$aLang['warning_no_file_uploaded'] = 'Waarschuwing: Geen bestand geupload.';
$aLang['warning_file_uploads_disabled'] = 'Waarschuwing: Bestanden uploaden is uitgeschakeld in de php.ini configuratiebestand.';


// 404 Email Error Report
$aLang['error404_email_subject'] = '404 Foutreport';
$aLang['error404_email_header'] = '404 Foutmelding';
$aLang['error404_email_text'] = 'Een 404-Fout trad op';
$aLang['error404_email_date'] = 'Datum:';
$aLang['error404_email_uri'] = 'De foute URI:';
$aLang['error404_email_ref'] = 'De uitgaande pagina:';

$aLang['err404'] = '404 Foutmelding';
$aLang['err404_page_not_found'] = 'De aangevraagde pagina werd niet gevonden bij';
$aLang['err404_sorry'] = 'De aangevraagde pagina';
$aLang['err404_doesntexist'] = 'bestaat niet bij';
$aLang['err404_mailed'] = '<b>Details over de fout werden automatisch aan de webmaster gestuurd.</b>';
$aLang['err404_commonm'] = '<b>Typische Fout</b>';
$aLang['err404_commonh'] = 'Namenskonventionen nicht beachtet';
$aLang['err404_urlend'] = 'de aangevraagde URL eindigd met';
$aLang['err404_allpages'] = 'alle pagina bij';
$aLang['err404_endwith'] = 'eindigd met';
$aLang['err404_uppercase'] = 'het gebruik van HOOFDLETTERS';
$aLang['err404_alllower'] = 'alle namen worden klein geschreven';

$aLang['text_info_csname'] = 'Bent u lid van een ruitersportvereniging : ';
$aLang['text_info_csdiscount'] = 'U krijgt afhankelijk van het produkt een maximale korting van : ';
$aLang['text_info_csotdiscount'] = 'U krijgt op uw totale bestelling een korting van : '; 
$aLang['text_info_csstaff'] = 'U kan tegen staffelprijzen inkopen.';
$aLang['text_info_cspay'] = 'Volgende betalingswijze kan u gebruiken : ';
$aLang['text_info_show_price_no'] = 'U krijgt nog geen prijsinformatie. Meldt u aan';
$aLang['text_info_show_price_with_tax_yes'] = 'De prijzen zijn incl. B.T.W.';
$aLang['text_info_show_price_with_tax_no'] = 'De prijzen zijn zonder B.T.W.';
$aLang['text_info_receive_mail_mode'] = 'Infos had ik graag in het formaat : ';
$aLang['entry_receive_mail_text'] = 'Tekst only';
$aLang['entry_receive_mail_html'] = 'HTML';
$aLang['entry_receive_mail_pdf'] = 'PDF';
 
$aLang['table_heading_price_unit'] = 'per st. netto';
$aLang['table_heading_discount'] = 'Korting';
$aLang['table_heading_ot_discount'] = 'Staffelkorting';
$aLang['text_info_minimum_amount'] = 'Vanaf het volgende bestelbedrag krijgt u staffelkorting.';
$aLang['sub_title_ot_discount'] = 'Staffelkorting:';
$aLang['text_new_customer_introduction_newsletter'] = 'Door het abonneren op de nieuwbrief van ' .  STORE_NAME . ' wordt u  geinformeerd op alle nieuwsinfo.';
$aLang['text_new_customer_ip'] = 'Deze rekening is gemaakt door deze computer IP : ';
$aLang['text_customer_account_password_security'] = 'Voor uw eigen veiligheid zijn we niet in staat om dit wachtwoord te achterhalen. Als u het vergeten bent, kan u een nieuwe aanvragen.';
$aLang['text_login_need_upgrade_csnewsletter'] = '<font color="#ff0000"><b>NOTE:</b></font>U bent al geabonneerd op &quot;Nieuwsbrieven &quot;. U moet uw rekening opwaarderen om te kunnen kopen.';

// use TimeBasedGreeting
$aLang['good_morning'] = 'Goedemorgen!';
$aLang['good_afternoon'] = 'Goedemiddag!';
$aLang['good_evening'] = 'Goedeavond!';

$aLang['text_taxt_incl'] = 'incl. Tax';
$aLang['text_taxt_add'] = 'plus. Tax';
$aLang['tax_info_excl'] = 'exkl. Tax';
$aLang['text_shipping'] = 'excl. <a href="%s"><u>Shipping cost</u></a>.';

$aLang['price'] = 'Preis';
$aLang['price_from'] = 'from';
$aLang['price_info'] = 'Alle Preise pro St&uuml;ck in &euro; inkl. der gesetzlichen Mehrwertsteuer, zzgl. <a href="' . oos_href_link($aModules['info'], $aFilename['information'], 'information_id=1') . '">Versandkostenpauschale</a>.';
$aLang['support_info'] = 'Haben Sie noch Fragen? Sie erreichen uns &uuml;ber unser <a href="' . oos_href_link($aModules['ticket'], $aFilename['ticket_create']) . '">Kontaktformular</a>.';


/*
  The following copyright announcement can only be
  appropriately modified or removed if the layout of
  the site theme has been modified to distinguish
  itself from the default osCommerce-copyrighted
  theme.

  For more information please read the following
  Frequently Asked Questions entry on the osCommerce
  support site:

  http://www.oscommerce.com/community.php/faq,26/q,50

  Please leave this comment intact together with the
  following copyright announcement.
*/
define('FOOTER_TEXT_BODY', 'Copyright &copy; 2003 <a href="http://www.oscommerce.com" target="_blank">osCommerce</a><br>Powered by <a href="http://www.oscommerce.com" target="_blank">osCommerce</a>');

?>
