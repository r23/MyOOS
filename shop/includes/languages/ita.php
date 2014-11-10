<?php
/* ----------------------------------------------------------------------
   $Id: ita.php,v 1.3 2007/06/12 16:57:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.107 2003/02/17 11:49:25 hpdl
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
  * on RedHat try 'en_US'
  * on FreeBSD try 'en_US.ISO_8859-1'
  * on Windows try 'en', or 'English'
  */
@setlocale(LC_TIME, 'it_IT.utf-8');
define('DATE_FORMAT_SHORT', '%d/%m/%Y');  // this is used for strftime()
define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
define('DATE_FORMAT', 'd/m/Y'); // this is used for date()
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
define('HTML_PARAMS','dir="LTR" lang="it"');
define('XML_PARAMS','xml:lang="it" lang="it"');

// charset for web pages and emails
define('CHARSET', 'utf-8');

//text in oos_temp/templates/your theme/system/user_navigation.html
$aLang['header_title_create_account'] = 'Crea account';
$aLang['header_title_my_account'] = 'Il mio Account';
$aLang['header_title_cart_contents'] = 'Nel carrello';
$aLang['header_title_checkout'] = 'Acquista';
$aLang['header_title_top'] = 'Home Page';
$aLang['header_title_catalog'] = 'Catalogo';
$aLang['header_title_logoff'] = 'Log Off';
$aLang['header_title_login'] = 'Log In';
$aLang['header_title_whats_new'] = 'Cosa c\'è di nuovo?';

$aLang['block_heading_specials'] = 'Offerte';

// footer text in includes/oos_footer.php
$aLang['footer_text_requests_since'] = 'visite da';

// text for gender
$aLang['male'] = 'Uomo';
$aLang['female'] = 'Donna';
$aLang['male_address'] = 'Sig.';
$aLang['female_address'] = 'Sig.ra';

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');
$aLang['dob_format_string'] = 'mm/dd/yyyy';

// search block text in tempalate/your theme/block/search.html
$aLang['box_search_text'] = 'Usa parole chiave per trovare il prodotto';
$aLang['box_search_advanced_search'] = 'Ricerca avanzata';
$aLang['text_search'] = 'cerca...';

// reviews block text in tempalate/your theme/block/reviews.html
$aLang['box_reviews_write_review'] = 'Scrivi una recensione su questo prodotto!';
$aLang['box_reviews_no_reviews'] = 'In questo momento non ci sono recensioni disponibili';
$aLang['box_reviews_text_of_5_stars'] = '%s su 5 Stelle!';

// shopping_cart block text in tempalate/your theme/block/shopping_cart.html
$aLang['box_shopping_cart_empty'] = '0 prodotti ...è vuoto';

// notifications block text in tempalate/your theme/block/products_notifications.html
$aLang['box_notifications_notify'] = 'Comunica gli aggiornamenti di<b>%s</b>';
$aLang['box_notifications_notify_remove'] = 'Non comunicare gli aggiornamenti di<b>%s</b>';

// wishlist block text in tempalate/your theme/block/wishlist.html
$aLang['block_wishlist_empty'] = 'No hai oggetti nella lista dei desideri';

// manufacturer block text in tempalate/your theme/block/
$aLang['box_manufacturer_info_homepage'] = '%s Homepage';
$aLang['box_manufacturer_info_other_products'] = 'Altri prodotti';

$aLang['block_add_product_id_text'] = 'Inserisci il modello del prodotto che vorresti aggiungere al carrello.';

// information block text in tempalate/your theme/block/information.html
$aLang['block_information_imprint'] = 'Imprint';
$aLang['box_information_privacy'] = 'Privacy';
$aLang['box_information_conditions'] = 'Condizioni';
$aLang['box_information_shipping'] = 'Spedizioni  e Consegna';
$aLang['box_information_contact'] = 'Contattaci';
$aLang['block_information_v_card'] = 'vCard';
$aLang['block_information_mapquest'] = 'Dove Siamo';
$aLang['block_skype_me'] = 'Skype Me';
$aLang['block_information_gv'] = 'Buoni Sconto FAQ';
$aLang['block_information_gallery'] = 'Gallery';


//service
$aLang['block_service_links'] = 'Web Links';
$aLang['block_service_newsfeed'] = 'RDS/RSS Newsfeed';
$aLang['block_service_gv'] = 'Buoni Sconto FAQ';
$aLang['block_service_sitemap'] = 'Mappa Sito';

// login
$aLang['entry_email_address'] = 'Indirizzo E-Mail:';
$aLang['entry_password'] = 'Password:';
$aLang['text_password_info'] = 'Password dimeticata?';
$aLang['image_button_login'] = 'Login';
$aLang['login_block_new_customer'] = 'Nuovo Cliente';
$aLang['login_block_account_edit'] = 'Modifica Info. Account';
$aLang['login_block_account_history'] = 'Storico Account';
$aLang['login_block_order_history'] = 'Storico Ordini';
$aLang['login_block_address_book'] = 'I miei indirizzi';
$aLang['login_block_product_notifications'] = 'Notifiche Prodotti';
$aLang['login_block_my_account'] = 'Informazioni Generali';
$aLang['login_block_logoff'] = 'Log Off';
$aLang['login_entry_remember_me'] = 'Auto Log On';

// tell a friend block text in tempalate/your theme/block/tell_a_friend.html
$aLang['block_tell_a_friend_text'] = 'Segnala ad un amico questo prodotto.';

// checkout procedure text
$aLang['checkout_bar_delivery'] = 'Invio Informazioni';
$aLang['checkout_bar_payment'] = 'Metodo di pagamento';
$aLang['checkout_bar_confirmation'] = 'Conferma';
$aLang['checkout_bar_finished'] = 'Fine!';

// pull down default text
$aLang['pull_down_default'] = 'Selezionare';
$aLang['type_below'] = 'Inserire qui';

//newsletter
$aLang['block_newsletters_subscribe'] = 'Iscriviti';
$aLang['block_newsletters_unsubscribe'] = 'Cancellati';

//myworld
$aLang['text_date_account_created'] = 'Zugang erstellt am:';
$aLang['text_yourstore'] = 'Your Participation';
$aLang['edit_yourimage'] = 'Your Image';


// javascript messages
$aLang['js_error'] = 'Ci sono stati degli errori nella compilazione del modulo!\nEseguire le seguenti modifiche:\n\n';

$aLang['js_review_text'] = '* Il testo delle \'Recensioni\' deve essere di almeno ' . REVIEW_TEXT_MIN_LENGTH . ' caratteri.\n';
$aLang['js_review_rating'] = '* Devi votare il prodotto per recensirlo.\n';

$aLang['js_gender'] = '* Campo \'Sesso\' Richiesto.\n';
$aLang['js_first_name'] = '* Il campo \'Nome\' deve contentere minimo ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' caratteri.\n';
$aLang['js_last_name'] = '* Il campo \'Cognome\' deve contentere minimo ' . ENTRY_LAST_NAME_MIN_LENGTH . ' caratteri.\n';
$aLang['js_dob'] = '* La \'Data di nascita\' deve essere inserita seguendo il formato dd/mm/yyyy (mese/giorno/anno).';
$aLang['js_email_address'] = '* Il campo \'Indirizzo E-Mail\' deve contentere minimo ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' caratteri.\n';
$aLang['js_address'] = '* Il campo \'Indirizzo\' deve contentere minimo ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' caratteri.\n';
$aLang['js_post_code'] = '* Il \'codice di avviamento postale(CAP)\' deve contentere minimo ' . ENTRY_POSTCODE_MIN_LENGTH . ' caratteri.\n';
$aLang['js_city'] = '* Il campo \'Città\' deve contentere minimo ' . ENTRY_CITY_MIN_LENGTH . ' caratteri.\n';
$aLang['js_state'] = '* Lo \'Stato\' deve essere selezionato.\n';
$aLang['js_country'] = '* Il campo \'Stato/Provincia\' deve essere selezionato.\n';
$aLang['js_telephone'] = '* Il campo \'Numero di telefono\' deve contentere minimo ' . ENTRY_TELEPHONE_MIN_LENGTH . ' caratteri.\n';
$aLang['js_password'] = '* Le Password \"Password\" e \"Conferma password\" inserite non corrispondono e/o deve contentere minimo ' . ENTRY_PASSWORD_MIN_LENGTH . ' caratteri.\n';

$aLang['js_error_no_payment_module_selected'] = '* Seleziona un metodo di pagamento per il tuo ordine.\n';
$aLang['js_error_submitted'] = 'I dati del form sono gia stati inviati. Premi Ok e aspetta che il processo sia completato.';

$aLang['error_no_payment_module_selected'] = 'Seleziona un metodo di pagamento per il tuo ordine.';
$aLang['error_conditions_not_accepted'] = 'Se non accetto le condizioni non possiamo procedere con l\'ordine!!';

$aLang['category_company'] = 'Azienda';
$aLang['category_personal'] = 'Dettagli personali';
$aLang['category_address'] = 'Indirizzo';
$aLang['category_contact'] = 'Contatti';
$aLang['category_options'] = 'Opzioni';
$aLang['category_password'] = 'Password';
$aLang['entry_company'] = 'Nome dell\' azienda:';
$aLang['entry_company_error'] = '';
$aLang['entry_company_text'] = '';
$aLang['entry_owner'] = 'Propietario';
$aLang['entry_owner_error'] = '';
$aLang['entry_owner_text'] = '';
$aLang['entry_vat_id'] = 'IVA';
$aLang['entry_vat_id_error'] = 'La partita IVA scelta non è valida o non verificabile al momento! Inserisci una valida partita IVA o lascia il campo vuoto.';
$aLang['entry_vat_id_text'] = '* solo per italia e paesi della comunità europea';
$aLang['entry_number'] = 'Numero cliente';
$aLang['entry_number_error'] = '';
$aLang['entry_number_text'] = '';
$aLang['entry_gender'] = 'Sesso:';
$aLang['entry_gender_error'] = 'Campo \"Sesso\" Richiesto.';
$aLang['entry_first_name'] = 'Nome:';
$aLang['entry_first_name_error'] = 'Il campo \"Nome\" deve contentere minimo ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' caratteri.';
$aLang['entry_first_name_text'] = '*';
$aLang['entry_last_name'] = 'Cognome:';
$aLang['entry_last_name_error'] = 'Il campo \"Cognome\" deve contenere minimo ' . ENTRY_LAST_NAME_MIN_LENGTH . ' caratteri.';
$aLang['entry_date_of_birth'] = 'Data di nascita:';
$aLang['entry_date_of_birth_error'] = 'La \"Data di nascita\" deve essere inserita seguendo il formato DD/MM/YYYY (eg. 21/05/1970).';
$aLang['entry_date_of_birth_text'] = '* (eg. 21/05/1970)';
$aLang['entry_email_address'] = 'Indirizzo E-Mail:';
$aLang['entry_email_address_error'] = 'Il campo \"Indirizzo E-Mail\" deve contentere minimo ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' caratteri.';
$aLang['entry_email_address_check_error'] = 'Indirizzo email non valido - accertarsi e correggere.';
$aLang['entry_email_address_error_exists'] = 'Indirizzo email già contenuto nel nostro database - accedere con questo indirizzo oppure creare un account con un indirizzo differente.';
$aLang['entry_street_address'] = 'Indirizzo:';
$aLang['entry_street_address_error'] = 'Il campo \"Indirizzo\" deve contentere minimo ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' caratteri.';
$aLang['entry_suburb'] = 'Frazione:';
$aLang['entry_suburb_error'] = '';
$aLang['entry_suburb_text'] = '';
$aLang['entry_post_code'] = 'CAP:';
$aLang['entry_post_code_error'] = 'Il campo \"CAP\" deve contentere minimo ' . ENTRY_POSTCODE_MIN_LENGTH . ' caratteri.';
$aLang['entry_city'] = 'Città:';
$aLang['entry_city_error'] = 'Il campo \"Città\" deve contentere minimo ' . ENTRY_CITY_MIN_LENGTH . ' caratteri.';
$aLang['entry_state'] = 'Stato/Provincia:';
$aLang['entry_state_error'] = 'Il campo \"Stato/Provincia\" deve contentere minimo ' . ENTRY_STATE_MIN_LENGTH . ' caratteri.';
$aLang['entry_country'] = 'Nazione:';
$aLang['entry_country_error'] = 'Seleziona una Nazione del menù a scorrimento.';
$aLang['entry_country_text'] = '*';
$aLang['entry_telephone_number'] = 'Numero di telefono:';
$aLang['entry_telephone_number_error'] = 'Il campo \"Numero di telefono\" deve contentere minimo ' . ENTRY_TELEPHONE_MIN_LENGTH . ' caratteri.';
$aLang['entry_fax_number'] = 'Numero Fax:';
$aLang['entry_fax_number_error'] = '';
$aLang['entry_fax_number_text'] = '';
$aLang['entry_newsletter'] = 'Newsletter:';
$aLang['entry_newsletter_text'] = '';
$aLang['entry_newsletter_yes'] = 'Mi iscrivo';
$aLang['entry_newsletter_no'] = 'Non mi iscrivo';
$aLang['entry_newsletter_error'] = '';
$aLang['entry_password'] = 'Password:';
$aLang['entry_password_confirmation'] = 'Conferma Password:';
$aLang['entry_password_confirmation_text'] = '*';
$aLang['entry_password_current'] = 'Password Attuale:';
$aLang['password_hidden'] = '--NASCOSTA--';
$aLang['entry_info_text'] = 'richiesto';


// constants for use in oos_prev_next_display function
$aLang['text_result_page'] = 'Pagina dei risultati:';
$aLang['text_display_number_of_products'] = 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> prodotti)';
$aLang['text_display_number_of_orders'] = 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> acquisti)';
$aLang['text_display_number_of_reviews'] = 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> recensioni)';
$aLang['text_display_number_of_products_new'] = 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> nuovi prodotti)';
$aLang['text_display_number_of_specials'] = 'Visualizzati <b>%d</b> su <b>%d</b> (di <b>%d</b> offerte)';
$aLang['text_display_number_of_wishlist'] = 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)';

$aLang['prevnext_title_first_page'] = 'Prima pagina';
$aLang['prevnext_title_previous_page'] = 'Pagina precedente';
$aLang['prevnext_title_next_page'] = 'Pagina successiva';
$aLang['prevnext_title_last_page'] = 'Ultima pagina';
$aLang['prevnext_title_page_no'] = 'Pagina %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Precedenti  %d pagine';
$aLang['prevnext_title_next_set_of_no_page'] = 'Successive %d pagine';
$aLang['prevnext_button_first'] = '&lt;&lt;PRIMO';
$aLang['prevnext_button_prev'] = '[&lt;&lt;&nbsp;Precedente]';
$aLang['prevnext_button_next'] = '[Successivo&nbsp;&gt;&gt;]';
$aLang['prevnext_button_last'] = 'ULTIMO&gt;&gt;';

$aLang['image_button_add_address'] = 'Aggiungi indirizzo';
$aLang['image_button_address_book'] = 'Indirizzo';
$aLang['image_button_back'] = 'Indietro';
$aLang['image_button_change_address'] = 'Cambia indirizzo';
$aLang['image_button_checkout'] = 'Acquista';
$aLang['image_button_confirm_order'] = 'Conferma acquisto';
$aLang['image_button_continue'] = 'Continua';
$aLang['image_button_continue_shopping'] = 'Continua gli acquisti';
$aLang['image_button_delete'] = 'Cancella';
$aLang['image_button_edit_account'] = 'Modifica account';
$aLang['image_button_history'] = 'I miei acquisti';
$aLang['image_button_login'] = 'Entra-login-';
$aLang['image_button_in_cart'] = 'Aggiungi al carrello';
$aLang['image_button_notifications'] = 'Comunicazioni';
$aLang['image_button_quick_find'] = 'Ricerca veloce';
$aLang['image_button_remove_notifications'] = 'Cancella comunicazioni';
$aLang['image_button_reviews'] = 'Recensioni';
$aLang['image_button_search'] = 'Cerca';
$aLang['image_button_tell_a_friend'] = 'Dillo ad un amico';
$aLang['image_button_update'] = 'Aggiorna';
$aLang['image_button_update_cart'] = 'Aggiorna il carrello';
$aLang['image_button_write_review'] = 'Scrivi una recensione';
$aLang['image_button_add_quick'] = 'Aggiungi veloce!';
$aLang['image_wishlist_delete'] = 'elimina';
$aLang['image_button_in_wishlist'] = 'Lista dei desideri';
$aLang['image_button_add_wishlist'] = 'Lista dei desideri';
$aLang['image_button_redeem_voucher'] = 'Richiedi';

$aLang['icon_button_mail'] = 'E-mail';
$aLang['icon_button_movie'] = 'Filmato';
$aLang['icon_button_pdf'] = 'PDF';
$aLang['icon_button_print'] = 'Stampa';
$aLang['icon_button_zoom'] = 'Zoom';


$aLang['icon_arrow_right'] = 'Altro';
$aLang['icon_cart'] = 'Nel carrello';
$aLang['icon_warning'] = 'Attenzione';

$aLang['text_greeting_personal'] = 'Bentornato <span class="greetUser">%s!</span> Vuoi vedere i <a href="%s"><u>nuovi prodotti</u></a> che sono disponibili?';
$aLang['text_greeting_guest'] = 'Benvenuto <span class="greetUser">!</span> Puoi effettuare qui <a href="%s"><u>il log-in</u></a>? Oppure puoi creare qui <a href="%s"><u>un account</u></a>?';

$aLang['text_sort_products'] = 'Tipi di prodotti';
$aLang['text_descendingly'] = 'in modo discendente';
$aLang['text_ascendingly'] = 'in modo ascendente';
$aLang['text_by'] = ' by ';

$aLang['text_review_by'] = 'da %s';
$aLang['text_review_word_count'] = '%s vocaboli';
$aLang['text_review_rating'] = 'Valutazione: %s [%s]';
$aLang['text_review_date_added'] = 'Data di inserimento: %s';
$aLang['text_no_reviews'] = 'Non ci sono recensioni per questo prodotto.';

$aLang['text_no_new_products'] = 'Non ci sono prodotti.';
$aLang['text_unknown_tax_rate'] = 'Tassa sconosciuta';
$aLang['text_required'] = 'Richiesto';

$aLang['error_oos_mail'] = '<small>ERRORE:</small> Non posso spedire le email tramite il server SMTP. Controlla la configurazione del tuo php.ini e correggi le impostazioni dell server SMTP se neccessario.';

$aLang['warning_install_directory_exists'] = 'Attenzione: La directory di installazione esiste locata in: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install. Rimuovila per ragioni di sicurezza!.';
$aLang['warning_config_file_writeable'] = 'Attenzione: è possibile scrivere sul file di configurazione: ' . dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php. Questo è un rischio - configura tale file in sola lettura.';
$aLang['warning_session_auto_start'] = 'Attenzione: session.auto_start è abilitata - disabilitala nel file  php.ini e riavvia il web server.';
$aLang['warning_download_directory_non_existent'] = 'Attenzione: La directory che contiene i download non esiste: ' . OOS_DOWNLOAD_PATH . '. I download non funzioneranno finche non verrà corretto questo errore.';
$aLang['warning_session_directory_non_existent'] = 'Attenzione: La directory che contiene la sessione non esiste: ' . oos_session_save_path() . '. La sessione non funzionerà finche non si corregge questo errore.';
$aLang['warning_session_directory_not_writeable'] = 'Attenzione: Non è possibile scrivere-lavorare sulla directory che contiene la sessione: ' . oos_session_save_path() . '. La sessione non funzionerà finche non verrà corretto questo errore.';

$aLang['text_ccval_error_invalid_date'] = 'La data di scadenza della carta di credito non è corretta.<br>Controlla la data e riprova.';
$aLang['text_ccval_error_invalid_number'] = 'Il numero della carta di credito immesso è invalido.<br>Controlla il numero e riprova.';
$aLang['text_ccval_error_unknown_card'] = 'I primi quattro numeri digitati sono: %s<br>Se questi numeri sono corretti, noi accettiamo la carta di credito.<br>Se non sono giusti, riprova.';

$aLang['voucher_balance'] = 'Resoconto Buoni';
$aLang['gv_faq'] = 'Buoni Sconto FAQ';
$aLang['error_redeemed_amount'] = 'Congratulazione, hai riscattato i buoni ';
$aLang['error_no_redeem_code'] = 'Non hai inserito un codice di riscatto.';
$aLang['error_no_invalid_redeem_gv'] = 'Codice Buono Sconto Invalido';
$aLang['table_heading_credit'] = 'Credito Disponibile';
$aLang['gv_has_vouchera'] = 'Hai dei fondi nel tuo Account Buono Sconto. Se vuoi <br>
                         li puoi spedire tramite';
$aLang['gv_has_voucherb'] = 'a qualcuno';
$aLang['entry_amount_check_error'] = 'Non hai abbastanza credito per spedire questa quantità.';
$aLang['gv_send_to_friend'] = 'Spedisci Buono Sconto';

$aLang['voucher_redeemed'] = 'Sconto Riscattato';
$aLang['cart_coupon'] = 'Coupon :';
$aLang['cart_coupon_info'] = 'più informazioni';

$aLang['block_affiliate_info'] = 'Affiliate Information';
$aLang['block_affiliate_summary'] = 'Affiliate Summary';
$aLang['block_affiliate_account'] = 'Edit Affiliate Account';
$aLang['block_affiliate_clickrate'] = 'Clickthrough Report';
$aLang['block_affiliate_payment'] = 'Payment Report';
$aLang['block_affiliate_sales'] = 'Sales Report';
$aLang['block_affiliate_banners'] = 'Affiliate Banners';
$aLang['block_affiliate_contact'] = 'Contact Us';
$aLang['block_affiliate_faq'] = 'Affiliate Program FAQ';
$aLang['block_affiliate_login'] = 'Affiliate Log In';
$aLang['block_affiliate_logout'] = 'Affiliate Log Out';

$aLang['entry_affiliate_payment_details'] = 'Payable to:';
$aLang['entry_affiliate_accept_agb'] = 'Check here to indicate that you have read and agree to the <a target="_new" href="' . oos_href_link($aModules['affiliate'], $aFilename['affiliate_terms'], '', 'SSL') . '">Associates Terms & Conditions</a>.';
$aLang['entry_affiliate_agb_error'] = '&nbsp;<small><font color="#FF0000">You must accept our Associates Terms & Conditions</font></small>';
$aLang['entry_affiliate_payment_check'] = 'Check Payee Name:';
$aLang['entry_affiliate_payment_check_text'] = '';
$aLang['entry_affiliate_payment_check_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_paypal'] = 'PayPal Account Email:';
$aLang['entry_affiliate_payment_paypal_text'] = '';
$aLang['entry_affiliate_payment_paypal_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_name'] = 'Bank Name:';
$aLang['entry_affiliate_payment_bank_name_text'] = '';
$aLang['entry_affiliate_payment_bank_name_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_account_name'] = 'Account Name:';
$aLang['entry_affiliate_payment_bank_account_name_text'] = '';
$aLang['entry_affiliate_payment_bank_account_name_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_account_number'] = 'Account Number:';
$aLang['entry_affiliate_payment_bank_account_number_text'] = '';
$aLang['entry_affiliate_payment_bank_account_number_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_branch_number'] = 'ABA/BSB number (branch number):';
$aLang['entry_affiliate_payment_bank_branch_number_text'] = '';
$aLang['entry_affiliate_payment_bank_branch_number_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_payment_bank_swift_code'] = 'SWIFT Code:';
$aLang['entry_affiliate_payment_bank_swift_code_text'] = '';
$aLang['entry_affiliate_payment_bank_swift_code_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_company'] = 'Company';
$aLang['entry_affiliate_company_text'] = '';
$aLang['entry_affiliate_company_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_company_taxid'] = 'VAT-Id.:';
$aLang['entry_affiliate_company_taxid_text'] = '';
$aLang['entry_affiliate_company_taxid_error'] = '&nbsp;<small><font color="#FF0000">require_onced</font></small>';
$aLang['entry_affiliate_homepage'] = 'Homepage';

$aLang['entry_affiliate_homepage_text'] = '&nbsp;<small><font color="#AABBDD">require_onced (http://)</font></small>';
$aLang['entry_affiliate_homepage_error'] = '&nbsp;<small><font color="#FF0000">require_onced (http://)</font></small>';

$aLang['category_payment_details'] = 'You get your money by:';

$aLang['block_ticket_generate'] = 'Apri un Ticket di Supporto';
$aLang['block_ticket_view'] = 'Visualizza Ticket';

$aLang['down_for_maintenance_text'] = 'Servizi sospesi per manutenzione ... Tornremo online a breve';
$aLang['down_for_maintenance_no_prices_display'] = 'Sospeso per manutenzione';
$aLang['no_login_no_prices_display'] = 'Prices for dealer only';
$aLang['text_products_base_price'] = 'Base Price';

// Product Qty, List, Rebate Pricing and Savings
$aLang['products_see_qty_discounts'] = 'SEE: QTY DISCOUNTS';
$aLang['products_order_qty_text'] = 'Add Qty: ';
$aLang['products_order_qty_min_text'] = '<br>' . ' Min Qty: ';
$aLang['products_order_qty_min_text_info'] = 'Order Minumum is: ';
$aLang['products_order_qty_min_text_cart'] = 'Order Minimum is: ';
$aLang['products_order_qty_min_text_cart_short'] = ' Min Qty: ';
$aLang['products_order_qty_unit_text'] = ' in Units of: ';
$aLang['products_order_qty_unit_text_info'] = 'Order in Units of: ';
$aLang['products_order_qty_unit_text_cart'] = 'Order in Units of: ';
$aLang['products_order_qty_unit_text_cart_short'] = ' Units: ';
$aLang['error_products_quantity_order_min_text'] = '';
$aLang['error_products_quantity_invalid'] = 'Invalid Qty: ';
$aLang['error_products_quantity_order_units_text'] = '';
$aLang['error_products_units_invalid'] = 'Invalid Units: ';

// File upload ~/includes/classes/oos_upload.php
$aLang['error_destination_does_not_exist'] = 'Error: Destination does not exist.';
$aLang['error_destination_not_writeable'] = 'Error: Destination not writeable.';
$aLang['error_file_not_saved'] = 'Error: File upload not saved.';
$aLang['error_filetype_not_allowed'] = 'Error: File upload type not allowed.';
$aLang['success_file_saved_successfully'] = 'Success: File upload saved successfully.';
$aLang['warning_no_file_uploaded'] = 'Warning: No file uploaded.';
$aLang['warning_file_uploads_disabled'] = 'Warning: File uploads are disabled in the php.ini configuration file.';


// 404 Email Error Report
$aLang['error404_email_subject'] = '404 Error Report';
$aLang['error404_email_header'] = '404 Error Message';
$aLang['error404_email_text'] = 'A 404 error was encountered by';
$aLang['error404_email_date'] = 'Date:';
$aLang['error404_email_uri'] = 'The URI which generated the error is:';
$aLang['error404_email_ref'] = 'The referring page was:';

$aLang['err404'] = '404 Error Message';
$aLang['err404_page_not_found'] = 'Page Not Found on';
$aLang['err404_sorry'] = 'We\'re sorry. The page you requested';
$aLang['err404_doesntexist'] = 'doesn\'t exist on';
$aLang['err404_mailed'] = '<b>The details of this error have automatically been mailed to the webmaster.</b>';
$aLang['err404_commonm'] = '<b>Common Mistakes</b>';
$aLang['err404_commonh'] = 'Here are the most common mistakes in accessing';
$aLang['err404_urlend'] = 'URL ends with';
$aLang['err404_allpages'] = 'all pages on';
$aLang['err404_endwith'] = 'end with';
$aLang['err404_uppercase'] = 'Using UPPER CASE CHARACTERS';
$aLang['err404_alllower'] = 'all names are in lower case only';

$aLang['text_info_csname'] = 'Your customer status is : ';
$aLang['text_info_csdiscount'] = 'You have on products a maximum discount of : ';
$aLang['text_info_csotdiscount'] = 'You have a total discount of  : ';
$aLang['text_info_csstaff'] = 'You have acces to ours quantity price discount.';
$aLang['text_info_cspay'] = 'You can not pay using following method : ';
$aLang['text_info_receive_mail_mode'] = 'I want to receive info in : ';
$aLang['text_info_show_price_no'] = 'You can not see price.';
$aLang['text_info_show_price_with_tax_yes'] = 'Price include Tax.';
$aLang['text_info_show_price_with_tax_no'] = 'Price without Tax.';
$aLang['entry_receive_mail_text'] = 'Text only';
$aLang['entry_receive_mail_html'] = 'HTML';
$aLang['entry_receive_mail_pdf'] = 'PDF';

$aLang['table_heading_price_unit'] = 'U.P.Net';
$aLang['table_heading_discount'] = 'Discount';
$aLang['table_heading_ot_discount'] = 'Global Discount';
$aLang['text_info_minimum_amount'] = 'Minimum order before discount';
$aLang['sub_title_ot_discount'] = 'Global Discount:';
$aLang['text_new_customer_introduction_newsletter'] = 'By subscribing to newsletter from ' .  STORE_NAME . ' you will stay informed of all news info.';
$aLang['text_new_customer_ip'] = 'This account has been created by this computer IP : ';
$aLang['text_customer_account_password_security'] = 'For you\'r own security we are not able to know or retrieve this password. If you forgot it, you can request a new one.';
$aLang['text_login_need_upgrade_csnewsletter'] = '<font color="#ff0000"><b>NOTE:</b></font>You have already subscribed to an account for &quot;Newsletter &quot;. You need to upgade this account to be able to buy.';

// use TimeBasedGreeting
$aLang['good_morning'] = 'Buon Giorno!';
$aLang['good_afternoon'] = 'Buon Pomeriggio!';
$aLang['good_evening'] = 'Buona Sera!';

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
