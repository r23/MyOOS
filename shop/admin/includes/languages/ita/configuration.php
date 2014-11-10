<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php,v 1.5 2008/06/04 14:41:37 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.7 2002/01/04 03:51:40 hpdl
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

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Titolo');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Valore');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_INFO_EDIT_INTRO', 'Fare tutte le modifiche neccessarie');
define('TEXT_INFO_DATE_ADDED', 'Data di aggiunta:');
define('TEXT_INFO_LAST_MODIFIED', 'Ultima modifica:');


define('STORE_NAME_TITLE', 'Nome negozio');
define('STORE_NAME_DESC', 'Il nome del mio negozio');

define('STORE_OWNER_TITLE', 'Proprietario Negozio');
define('STORE_OWNER_DESC', 'Il nome del proprietario del Negozio');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'Indirizzo E-Mail');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'L\' indirizzo E-Mail del proprietario del negozio');

define('STORE_OWNER_VAT_ID_TITLE' , 'VAT ID of Shop Owner');
define('STORE_OWNER_VAT_ID_DESC' , 'The VAT ID of the Shop Owner');

define('SKYPE_ME_TITLE', 'Nome-Skype');
define('SKYPE_ME_DESC', 'Se non hai un account Skype <a href=\"http://www.skype.com/go/download\" target=\"_blank\">vai qui</a> per crearne uno, poi inserisci i dati.');


define('STORE_COUNTRY_TITLE', 'Nazione');
define('STORE_COUNTRY_DESC', 'La nazione in cui è il mio negozio <br><br><b>Nota: ricordati aggiornare anche la regione.</b>');

define('STORE_ZONE_TITLE', 'Regione');
define('STORE_ZONE_DESC', 'La regione in cui è il mio negozio');

define('EXPECTED_PRODUCTS_SORT_TITLE', 'Ordine di "In arrivo"');
define('EXPECTED_PRODUCTS_SORT_DESC', 'Questo è l\'ordinamento utilizzato nel box In Arrivo.');

define('EXPECTED_PRODUCTS_FIELD_TITLE', 'Campo di ordinamento per "In Arrivo"');
define('EXPECTED_PRODUCTS_FIELD_DESC', 'La colonna di ordinamento per i prodotti in arrivo.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'Cambia alla valuta associata alla lingua');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Automaticamente cambia alla valuta associata alla lingua in caso quando modificata.');

define('SEND_EXTRA_ORDER_EMAILS_TO_TITLE', 'Indirizzi email extra per gli ordini');
define('SEND_EXTRA_ORDER_EMAILS_TO_DESC', 'Spedisce copie degli ordini a piu indirizzi e-mail, inserirli nel seguente formato: Nome 1 &lt;email@address1&gt;');

define('SEND_BANKINFO_TO_ADMIN_TITLE', 'Bankinfo per Mail');
define('SEND_BANKINFO_TO_ADMIN_DESC', 'Vuoi Ricevere in Mail i dati della banca per l\'addebito?');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Operatori di ricerca predefinito');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'Operatori di ricerca predefinito');

define('STORE_NAME_ADDRESS_TITLE', 'Recapiti Negozio');
define('STORE_NAME_ADDRESS_DESC', 'Questi sono il nome del negozio, l\'indirizzo, il telefono ed altri dati utilizzati nei documenti stampati ed online.');

define('TAX_DECIMAL_PLACES_TITLE', 'Cifre Decimali nelle Tasse');
define('TAX_DECIMAL_PLACES_DESC', 'Fa il pad delle cifre decimali nelle tasse');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Mostra prezzi con tassa inclusa');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Mostra i prezzi IVA compresa(vero) oppure IVA esclusa (falso)');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE', 'Visualizza le condizioni all\'acquisto.');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC', 'Visualizza le condizioni durante la fase finale dell\'acquisto.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Ordine opzioni prodotti');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'Vuoi ordinare le opzioni dei prodotti per Prezzo?');

define('WEB_SEARCH_GOOGLE_KEY_TITLE', 'Chiave di licenza API di Google');
define('WEB_SEARCH_GOOGLE_KEY_DESC', 'Licenza API Google (gratis!) <A HREF=\"http://www.google.com/apis\" TARGET=\"_blank\">http://www.google.com/apis</A>.');

define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE', 'Nome');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC', 'Lunghezza Minima per il Nome');

define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE', 'Cognome');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC', 'Lunghezza Minima per il Cognome');

define('ENTRY_DOB_MIN_LENGTH_TITLE', 'Data di nascita');
define('ENTRY_DOB_MIN_LENGTH_DESC', 'Lunghezza Minima per la data di nascita');

define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE', 'Indirizzo e-mail');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC', 'Lunghezza Minima per l\'indirizzo e-mail');

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Indirizzo');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Lunghezza Minima per l\'indirizzo');

define('ENTRY_COMPANY_LENGTH_TITLE', 'Società');
define('ENTRY_COMPANY_LENGTH_DESC', 'Lunghezza Minima per la Società');

define('ENTRY_POSTCODE_MIN_LENGTH_TITLE', 'CAP');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC', 'Lunghezza Minima per il CAP');

define('ENTRY_CITY_MIN_LENGTH_TITLE', 'Città');
define('ENTRY_CITY_MIN_LENGTH_DESC', 'Lunghezza Minima per la Città');

define('ENTRY_STATE_MIN_LENGTH_TITLE', 'Stato');
define('ENTRY_STATE_MIN_LENGTH_DESC', 'Lunghezza Minima per la Stato');

define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE', 'Numero telefonico');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC', 'Lunghezza Minima per il Numero telefonico');

define('ENTRY_PASSWORD_MIN_LENGTH_TITLE', 'Password');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC', 'Lunghezza Minima per la password');

define('CC_OWNER_MIN_LENGTH_TITLE', 'Nome proprietario carta di credito');
define('CC_OWNER_MIN_LENGTH_DESC', 'Lunghezza minima per proprietario della carta di credito');

define('CC_NUMBER_MIN_LENGTH_TITLE', 'Numero carta di credito');
define('CC_NUMBER_MIN_LENGTH_DESC', 'Lunghezza minima per il numero carta di credito');

define('MIN_DISPLAY_BESTSELLERS_TITLE', 'I più venduti');
define('MIN_DISPLAY_BESTSELLERS_DESC', 'Numero minimo di prodtti più venduti da visualizzare');

define('MIN_DISPLAY_ALSO_PURCHASED_TITLE', 'Acquistato anche');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC', 'Numero minimo di prodotti da mostrare nell\'area \'Chi ha comprato questo articolo ha comprato anche\'');

define('MIN_DISPLAY_XSELL_PRODUCTS_TITLE', 'Raccomandazione Prodotti(Vendita incrociata)');
define('MIN_DISPLAY_XSELL_PRODUCTS_DESC', 'Numero minimo di prodotti da mostrare nell\'area \'Vendita Incorciata (X-Sell)\'');

define('MIN_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Nuovi Prdotti nella NewsFeed');
define('MIN_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Numero minimo di prodotti da mostrare nell newsFeed');

define('MIN_DISPLAY_NEW_NEWS_TITLE', 'Messaggi News');
define('MIN_DISPLAY_NEW_NEWS_DESC', 'Numero minimo di messaggi da mostrare nelle news');

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE', 'Record Rubrica');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC', 'Numero massimo di record che possono essere inseriti nella rubrica utente');

define('MAX_DISPLAY_SEARCH_RESULTS_TITLE', 'Risultato della ricerca');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC', 'Numero di prodotti elencati per pagina');

define('MAX_DISPLAY_PAGE_LINKS_TITLE', 'Links per pagina');
define('MAX_DISPLAY_PAGE_LINKS_DESC', 'Numero di links usati per ogni pagina di rimando ad altre pagine nella ricerca');

define('MAX_DISPLAY_NEW_PRODUCTS_TITLE', 'Modulo Nuovi Prodotti');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC', 'Numero massimo di prodotti da mostrare come novità');

define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE', 'Prodotti in arrivo');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC', 'Numero massimo di prodotti in arrivo da mostrare');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Selezione di prodotti nuovi');
define('MAX_RANDOM_SELECT_NEW_DESC', 'Quanti record selezionare per mostrare un nuovo prodotto a caso');

define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE', 'Categorie da elencare per riga');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC', 'Quante categorie mostrare per riga');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'Elenco nuovi prodotti');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Numero massimo di prodotti da mostrare nella pagina novità');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'I più venduti');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Numero massimo di prodotti più venduti da mostrare');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Ha acquistato anche');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Numero massimo di prodotti da mostrare nel box \'Chi ha comprato questo articolo ha comprato anche\'');

define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE', 'Ordini del cliente(Box)');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC', 'Numero massimo di prodotti da mostrare nel box storico ordini del cliente');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Ordini del cliente(Pagina)');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Numero massimo di prodotti da mostrare nella pagina storico ordini del cliente');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Raccomandazione Prodotti(Vendita incrociata)');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Numero massimo di prodotti da mostrare nell\'area \'Vendita Incorciata (X-Sell)\'');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Lista dei desideri');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Massimo numero prodotti della lista dei desideri.');

define('MAX_DISPLAY_WISHLIST_BOX_TITLE', 'Lista dei desideri(Box)');
define('MAX_DISPLAY_WISHLIST_BOX_DESC', 'Massimo numero prodotti nel box della lista dei desideri');

define('MAX_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Nuovi prodotti in newsFeed');
define('MAX_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Massimo numero prodotti nel newsFeed');

define('MAX_RANDOM_SELECT_NEWSFEED_TITLE', 'Newsfeed');
define('MAX_RANDOM_SELECT_NEWSFEED_DESC', 'Numero massimo di newsfeed casuali');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Numero di messagi News');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Massimo numero di messaggi News');

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Numero Stroico Prodotti');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Massimo numero da visualizzare nello Storico Prodotti');

define('SMALL_IMAGE_WIDTH_TITLE', 'Larghezza Immagini Piccole');
define('SMALL_IMAGE_WIDTH_DESC', 'Largezza in pixel delle Immagini Piccole');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Altezza Immagini Piccole');
define('SMALL_IMAGE_HEIGHT_DESC', 'Altezza in pixel delle Immagini Piccole');

define('HEADING_IMAGE_WIDTH_TITLE', 'Larghezza Immagini Intestazione');
define('HEADING_IMAGE_WIDTH_DESC', 'Largezza in pixel delle Immagini Intestazione');

define('HEADING_IMAGE_HEIGHT_TITLE', 'Altezza Immagini Intestazione');
define('HEADING_IMAGE_HEIGHT_DESC', 'Altezza in pixel delle Immagini Intestazione');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Larghezza Immagini Sottocategorie');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'Larghezza in pixel Immagini Sottocategorie');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Altezza Immagini Sottocategorie');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'Altezza in pixel Immagini Sottocategorie');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE', 'Calcola la dimensione delle Immagini');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC', 'Calcola la dimensione delle Immagini?');

define('IMAGE_REQUIRED_TITLE', 'Immagine Richiesta');
define('IMAGE_REQUIRED_DESC', 'Mostra le immagini non funzionanti. Utile in fase di sviluppo.');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Autorizzazione di accesso');
define('CUSTOMER_NOT_LOGIN_DESC', 'L\'autorizzazione di accesso verrà rilasciata dall\'amministratore, dopo l\'esaminazione dei dati rilasciati dall\'utente');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Dati Utente per Mail');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'I Dati Utente sono stati inviati via E-Mail all\'operatore del negozio');

define('DEFAULT_MAX_ORDER_TITLE', 'Credito Cliente');
define('DEFAULT_MAX_ORDER_DESC', 'Valore Massimo per Ordine');

define('ACCOUNT_GENDER_TITLE', 'Indirizzo');
define('ACCOUNT_GENDER_DESC', 'Indirizzo indicato');

define('ACCOUNT_DOB_TITLE', 'Data di nscita');
define('ACCOUNT_DOB_DESC', 'Data di nascita necessaria');

define('ACCOUNT_NUMBER_TITLE', 'Numero Cliente');
define('ACCOUNT_NUMBER_DESC', 'Gestione dei numeri del cliente');

define('ACCOUNT_COMPANY_TITLE', 'Nome Azienda');
define('ACCOUNT_COMPANY_DESC', 'Nome Azienda indicato');

define('ACCOUNT_OWNER_TITLE', 'Proprietario');
define('ACCOUNT_OWNER_DESC', 'Proprietario indicato');

define('ACCOUNT_VAT_ID_TITLE', 'Identificazione della tassa sul valore aggiunto');
define('ACCOUNT_VAT_ID_DESC', 'L\'identificazione della tassa sul valore aggiunto ai clienti commerciali ');


define('ACCOUNT_SUBURB_TITLE', 'Zona');
define('ACCOUNT_SUBURB_DESC', 'Zona indicata');

define('ACCOUNT_STATE_TITLE', 'Stato');
define('ACCOUNT_STATE_DESC', 'Stato indicato');

define('STORE_ORIGIN_COUNTRY_TITLE', 'Codice Statale');
define('STORE_ORIGIN_COUNTRY_DESC', 'Inserire il &quot;ISO 3166&quot; codice di stato del negozio per le spese di spedizione. Per trovare questo codice puoi visitare il sito: <A HREF=\"http://www.address-service-center.it/contents.php?page=sigle_stati_iso_3166>www.address-service-center.it</A>.');

define('STORE_ORIGIN_ZIP_TITLE', 'Codice postale');
define('STORE_ORIGIN_ZIP_DESC', 'Inserire il Codice postale (ZIP) del negozio usato per le spese di spedizione');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Inserire il peso massimo per la spedizione');
define('SHIPPING_MAX_WEIGHT_DESC', 'I corrieri hanno un limite massimo di peso per singola spedizione. questo limite è uguale per tutti.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Peso della tara del pacco');
define('SHIPPING_BOX_WEIGHT_DESC', 'Qual\'è il peso tipoco dei pacchi medio-piccoli');

define('SHIPPING_BOX_PADDING_TITLE', 'Pacchi più grandi - aumento percentuale');
define('SHIPPING_BOX_PADDING_DESC', 'per 10% inserire 10');

define('PRODUCT_LIST_IMAGE_TITLE', 'Visulaizza Immagine Prodotto');
define('PRODUCT_LIST_IMAGE_DESC', 'Vuoi visualizzare l\'immagine del prodotto?');

define('PRODUCT_LIST_MANUFACTURER_TITLE', 'Visualizza il Produttore del prodotto');
define('PRODUCT_LIST_MANUFACTURER_DESC', 'Vuoi visualizzare il produttore del prodotto?');

define('PRODUCT_LIST_MODEL_TITLE', 'Visualizza il Modello del prodotto');
define('PRODUCT_LIST_MODEL_DESC', 'Vuoi visualizzare il Modello del prodotto?');

define('PRODUCT_LIST_NAME_TITLE', 'Visualizza il Nome del prodotto');
define('PRODUCT_LIST_NAME_DESC', 'Vuoi visulaizzare il Nome del prodotto?');

define('PRODUCT_LIST_UVP_TITLE', 'Visualizza il Prezzo del listino prodotto');
define('PRODUCT_LIST_UVP_DESC', 'Vuoi visualizzare il Prezzo del listino prodotto');

define('PRODUCT_LIST_PRICE_TITLE', 'Visualizza il Prezzo del prodotto');
define('PRODUCT_LIST_PRICE_DESC', 'Vuoi visulaizzare il Prezzo del prodotto?');

define('PRODUCT_LIST_QUANTITY_TITLE', 'Visualizza la Quantità del prodotto');
define('PRODUCT_LIST_QUANTITY_DESC', 'Vuoi visulizzare la Quantità del prodotto?');

define('PRODUCT_LIST_WEIGHT_TITLE', 'Visualizza il Peso del prodotto');
define('PRODUCT_LIST_WEIGHT_DESC', 'Vuoi Visualizzare il Peso del prodotto');

define('PRODUCT_LIST_BUY_NOW_TITLE', 'Visualizza la colonna Compra Ora');
define('PRODUCT_LIST_BUY_NOW_DESC', 'Vuoi visualizzare la colonna Compra Ora?');

define('PRODUCT_LIST_FILTER_TITLE', 'Visualizza il filtro Categorie/Produtori (0=disbilita; 1=abilita)');
define('PRODUCT_LIST_FILTER_DESC', 'Vuoi visualizzare il filtro Categorie/Produtori?');

define('PRODUCT_LIST_SORT_ORDER_TITLE', 'Visualizza l\'ordinamento della pubblicazione dei prodotti');
define('PRODUCT_LIST_SORT_ORDER_DESC', 'Vuoi visulaizzare l\'ordinamento della pubblicazione dei prodotti');

define('PREV_NEXT_BAR_LOCATION_TITLE', 'Posizionamento della barra di navigazione Prev/Succ (1-In Alto, 2-In Basso, 3-Entrambe)');
define('PREV_NEXT_BAR_LOCATION_DESC', 'Posiziona la barra di navigazione Prev/Succ (1-In Alto, 2-In Basso, 3-Entrambe)');

define('STOCK_CHECK_TITLE', 'Controllare la quantità nel magazzino');
define('STOCK_CHECK_DESC', 'Controlla se c\'è sufficiente disponibilità dal magazzino.');

define('STOCK_LIMITED_TITLE', 'Sottrai al Magazzino');
define('STOCK_LIMITED_DESC', 'Prodotti sottratti dal magazzino in ordine di prodotto');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Permettere l\'acquisto');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Permetti ai clienti di acquistare anche se la quantità di magazzino è insufficiente');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Contrassegna i prodotti fuori magazzino');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Fai visualizzare agli utenti che il prodotto ha una quantità insufficiente nel magazzino');

define('STOCK_REORDER_LEVEL_TITLE', 'Livello di ri-ordine  magazzino');
define('STOCK_REORDER_LEVEL_DESC', 'Definisci quando il magazzino deve essere ricaricato');

define('STORE_PAGE_PARSE_TIME_TITLE', 'tempo impiegato per generare');
define('STORE_PAGE_PARSE_TIME_DESC', 'Salva tempo impiegato per generare pagina');

define('STORE_PAGE_PARSE_TIME_LOG_TITLE', 'Destinazione dei Log');
define('STORE_PAGE_PARSE_TIME_LOG_DESC', 'Cartella e Nome del file della pagina generata da loggare');

define('STORE_PARSE_DATE_TIME_FORMAT_TITLE', 'Formato Data del Log');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC', 'Il formato della Data');

define('DISPLAY_PAGE_PARSE_TIME_TITLE', 'Visualizza il tempo di generazione di pagina');
define('DISPLAY_PAGE_PARSE_TIME_DESC', 'Visualizza il tempo di generazione di pagina (il tempo di generazione di pagina deve essere attivato');

define('USE_CACHE_TITLE', 'Usa la Cache');
define('USE_CACHE_DESC', 'Usa strumenti della cache');

define('DOWNLOAD_ENABLED_TITLE', 'Attiva il Download/Scarico');
define('DOWNLOAD_ENABLED_DESC', 'Attiva le funzioni per scaricare i prodotti');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download/Scarico da reindirizzamento');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Usa il reinderizzamento del browser per il Download. Disabilitato su sistemi non-Unix');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Data di Scadenza (Delay in giorni)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Configura il numero di giorni rimanenti per il Download, prima che il link non sia più visualizzato. 0 significa illimitato');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Massimo numero di Download');
define('DOWNLOAD_MAX_COUNT_DESC', 'Defnisci numero di Download massimi. 0 significa nessun download abilitato.');

define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_TITLE', 'Controllo dei Download ');
define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_DESC', 'Stato ordine che azzera i giorni di download ed il massimo numero di download - Predefinito è 4');

define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_TITLE', 'Controllo downloads messaggio di download in attesa');
define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_DESC', 'Controllo downloads messaggio di download in attesa:');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Controllo downloads messaggio di stato dell\'ordine');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Controllo downloads messaggio di download in attesa - Default=2');

define('TICKET_ENTRIES_MIN_LENGTH_TITLE', 'Ticket di Soppurto');
define('TICKET_ENTRIES_MIN_LENGTH_DESC', 'Le informazioni più importanti dei Ticket di Supporto');

define('TICKET_ADMIN_NAME_TITLE', 'Amminisistratore Tickets');
define('TICKET_ADMIN_NAME_DESC', 'Il Nome Amministratore');

define('TICKET_USE_STATUS_TITLE', 'Indicatore di stato nel Negozio');
define('TICKET_USE_STATUS_DESC', 'Si vuole indicare lo stato per il Tiket di sopporto?');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_TITLE', 'Permetti all\'utente');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_DESC', 'Permetti all\'utente di cambiare lo stato dopo aver risposto');

define('TICKET_USE_DEPARTMENT_TITLE', 'Usare il reparto');
define('TICKET_USE_DEPARTMENT_DESC', 'Usare il reparto ne catalogo');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_TITLE', 'Reparto');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_DESC', 'Permetti all\'utente di modificare lo stato dopo aver risposto');

define('TICKET_USE_PRIORITY_TITLE', 'Usa Priorità');
define('TICKET_USE_PRIORITY_DESC', 'Usa Priorità nel Catalogo');

define('TICKET_USE_ORDER_IDS_TITLE', 'ID dell\'ordine');
define('TICKET_USE_ORDER_IDS_DESC', 'Se il cliente è loggato , i suoi ordini sono mostrati');

define('TICKET_USE_SUBJECT_TITLE', 'Visualizza Soggetto');
define('TICKET_USE_SUBJECT_DESC', 'Visualizza Soggetto');

define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_TITLE', 'Login');
define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_DESC', 'Se si imposta questo valore True so può permettere - non permettere agli utenti registrati di visualizzare i Tickets quando sono loggati ');

define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_TITLE', 'Shop - Login');
define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_DESC', '0 i clienti rgistratit devono essere loggati per vedere i Tickets <br />1 gli utenti registrati non devono essere loggati per vedere i Tickets');

define('SECURITY_CODE_LENGTH_TITLE', 'Codice Acquisto');
define('SECURITY_CODE_LENGTH_DESC', 'Imposta la lunghezza del codice di acquisto più lunga e più sicura');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Coupon Nuovo Cliente');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Determinare l\'identità del bonus per il nuovo cliente. Lasciare il campo vuoto equivale a nessuno bonus.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Coupon ID');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Indicare il coupon ID che sarà spedito via E-mail ad un nuovo cliente, se non è indicato non si invieranno E-mail');

define('STORE_TEMPLATES_TITLE', 'Layout collecting main');
define('STORE_TEMPLATES_DESC', 'Shop Templates');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Data Prodotto');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'Visualizzare quando un prodotto è stato aggiunto/inserito?');

define('SHOW_COUNTS_TITLE', 'Numero di articoli per rispettive categorie');
define('SHOW_COUNTS_DESC', 'Annuncio: quanti articoli sono presenti nelle categorie');

define('CATEGORIES_BOX_SCROLL_LIST_ON_TITLE', 'Lista Selezione Categorie');
define('CATEGORIES_BOX_SCROLL_LIST_ON_DESC', 'Indicare le catogorie come liste selezionabili?');

define('CATEGORIES_SCROLL_BOX_LEN_TITLE', 'Quantità delle Categorie');
define('CATEGORIES_SCROLL_BOX_LEN_DESC', 'Se si vuole indicare le categorie come lista selezionabile, inserire qui la lunghezza.');

define('SHOPPING_CART_IMAGE_ON_TITLE', 'Picture in goods basket contents');
define('SHOPPING_CART_IMAGE_ON_DESC', 'Would you like to indicate the Porduktbild in the detail opinion of the Warenkorb?');

define('SHOPPING_CART_MINI_IMAGE_TITLE', 'Riduzione Immagine');
define('SHOPPING_CART_MINI_IMAGE_DESC', 'Valore per la riduzione del particolare del Warenkorb');

define('DISPLAY_CART_TITLE', 'Visulizza il carrello dopo avervi inserito prodotti');
define('DISPLAY_CART_DESC', 'Visulizza la pagine del carello dopo aver inserito un prodotto (o torna alla pagina di provenienza)');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE', 'Permetti di inviare messaggi agli amici');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC', 'Permetti di inviare messaggi agli amici su un particolare prodotto');

define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE', 'Permetti la descrizione delle categorie');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC', 'Permetti una descrizione dettagliata di una categoria');

define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_TITLE', 'Permetti la descrizione alle nuove categorie');
define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_DESC', 'Permettere una descrizione dettagliate delle nuove categorie');

define('SHOW_PRODUCTS_MODEL_TITLE', 'Navigazione con il numero del pezzo');
define('SHOW_PRODUCTS_MODEL_DESC', 'Si uole indicare nelle informazioni del prodotto il numero del pezzo?');

define('BREADCRUMB_SEPARATOR_TITLE', 'separatore barra di navigazione');
define('BREADCRUMB_SEPARATOR_DESC', 'separatore barra di navigazione');

define('BLOCK_BEST_SELLERS_IMAGE_TITLE', 'Immagini nel blocco più venduti');
define('BLOCK_BEST_SELLERS_IMAGE_DESC', 'Inserire l\'immagine nel blocco dei più venduti?');

define('BLOCK_PRODUCTS_HISTORY_IMAGE_TITLE', 'Immagine nel blocco dei prodotti comprati');
define('BLOCK_PRODUCTS_HISTORY_IMAGE_DESC', 'Inserire l\'immagine nel blocco dei prodotti comprati?');

define('BLOCK_WISHLIST_IMAGE_TITLE', 'Immagine nella lista dei desideri');
define('BLOCK_WISHLIST_IMAGE_DESC', 'Inserire l\'immagine nella lista dei desideri?');

define('BLOCK_XSELL_PRODUCTS_IMAGE_TITLE', 'immagine nel blocco link prodotto ');
define('BLOCK_XSELL_PRODUCTS_IMAGE_DESC', 'Inserire l\'immagine nel blocco link prodotto ?');


define('OOS_GD_LIB_VERSION_TITLE', 'GD - libreria');
define('OOS_GD_LIB_VERSION_DESC', '1 per vecchia libreria GD (versione 1.x)<br />2 per attuale libreria GD (versione 2.x)');

define('OOS_SMALLIMAGE_WAY_OF_RESIZE_TITLE', 'Trattamento delle Immagini piccole immagini');
define('OOS_SMALLIMAGE_WAY_OF_RESIZE_DESC', '0: riduzione proporzionale; larghezza o altezza sono al massimo valore <br />1: l\'immagine viene copiata proporzionatamente nelle nuova immagine; il colore di sfondo viene considerato <br />2: il ritaglio è copiato in una nuova immagine. ');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Larghezza Immagini Piccole');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'Largezza in pixel delle Immagini Piccole');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Altezza Immagini Piccole');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'Altezza in pixel delle Immagini Piccole');

define('OOS_IMAGE_BGCOLOUR_R_TITLE', 'Sfondo piccola immmagine R');
define('OOS_IMAGE_BGCOLOUR_R_DESC', 'Valore rosso per piccolla immagine prodotti');

define('OOS_IMAGE_BGCOLOUR_G_TITLE', 'Sfondo piccola immmagine G');
define('OOS_IMAGE_BGCOLOUR_G_DESC', 'Valore verde per piccolla immagine prodotti');

define('OOS_IMAGE_BGCOLOUR_B_TITLE', 'Sfondo piccola immmagine B');
define('OOS_IMAGE_BGCOLOUR_B_DESC', 'Valore blu per piccolla immagine prodotti');

define('OOS_BIGIMAGE_WAY_OF_RESIZE_TITLE', 'Trattamento immagini grande immagine');
define('OOS_BIGIMAGE_WAY_OF_RESIZE_DESC', '0: riduzione proporzionale; larghezza o altezza sono al massimo valore <br />1: l\'immagine viene copiata proporzionatamente nelle nuova immagine; il colore di sfondo viene considerato <br />2: il ritaglio è copiato in una nuova immagine. ');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Aprire l\'immagine grande');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Larghezza dell\'immagine grande in pixel');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'Altezza immgine grande');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'Altezza immgine grande in pixel');

define('OOS_WATERMARK_TITLE', 'Filigrana ');
define('OOS_WATERMARK_DESC', 'Introdurre una descrizione come filigrana');

define('OOS_WATERMARK_QUALITY_TITLE', 'Qualità della filigrana');
define('OOS_WATERMARK_QUALITY_DESC', 'Specifica qui la qualità della filigrana');

define('OOS_IMAGE_SWF_TITLE', 'Ming');
define('OOS_IMAGE_SWF_DESC', 'Ming è installato?');

define('OOS_SWF_MOVIECLIP_TITLE', 'Filmato Flash');
define('OOS_SWF_MOVIECLIP_DESC', 'Convertire le immagini piccole in un filmato flash?');

define('OOS_SWF_BGCOLOUR_R_TITLE', 'Sondo del filmato flash R');
define('OOS_SWF_BGCOLOUR_R_DESC', 'Valore rosso per piccolla immagine prodotti del filmato flash');

define('OOS_SWF_BGCOLOUR_G_TITLE', 'Sondo del filmato flash G');
define('OOS_SWF_BGCOLOUR_G_DESC', 'Valore verde per piccolla immagine prodotti del filmato flash');

define('OOS_SWF_BGCOLOUR_B_TITLE', 'Sondo del filmato flash  B');
define('OOS_SWF_BGCOLOUR_B_DESC', 'Valore blu per piccolla immagine prodotti del filmato flash');

define('OOS_RANDOM_PICTURE_NAME_TITLE', 'Nome del file');
define('OOS_RANDOM_PICTURE_NAME_DESC', 'Nome del file Casuale');

define('OOS_MO_PIC_TITLE', 'Più immagini prodotto');
define('OOS_MO_PIC_DESC', 'Inserire più immagini nelle info prodotto?');

define('PSM_TITLE', 'Motore di ricerca prezzi');
define('PSM_DESC', 'Per l\'interfaccia del motoere di ricerca per prezzi è necessario registrarsi qui: <A HREF=\"http://www.preissuchmaschine.de/psm_frontend/main.asp?content=mitmachenreissuchmaschine\" TARGET=\"_blank\">http://www.preissuchmaschine.de</A>');

define('OOS_PSM_DIR_TITLE', 'Motore di ricerca liste prezzi');
define('OOS_PSM_DIR_DESC', 'Il file del motore di ricerca prezzi deve essere caricato all\'interno del negozio.');

define('OOS_PSM_FILE_TITLE', 'Nome del File');
define('OOS_PSM_FILE_DESC', 'Il nome del file del motore di ricerca prezzi');

define('OOS_META_TITLE_TITLE', 'Titolo del negozio');
define('OOS_META_TITLE_DESC', 'Titolo');

define('OOS_META_DESCRIPTION_TITLE', 'Descrizione');
define('OOS_META_DESCRIPTION_DESC', 'La descrizione del loro negozio (max 250 car.)');

define('OOS_META_KEYWORDS_TITLE', 'Parole chiave');
define('OOS_META_KEYWORDS_DESC', 'Inserire le parole chiave separate da una virgola "," (max 259 car.)');

define('OOS_META_PAGE_TOPIC_TITLE', 'Soggetto');
define('OOS_META_PAGE_TOPIC_DESC', 'Soggetto del proprio negozio');

define('OOS_META_AUDIENCE_TITLE', 'Gruppo di target');
define('OOS_META_AUDIENCE_DESC', 'Il loro guppo target');

define('OOS_META_AUTHOR_TITLE', 'Autore');
define('OOS_META_AUTHOR_DESC', 'L\'autore del negozio');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Lo sviluppatore del negozio');

define('OOS_META_PAGE_TYPE_TITLE', 'Tipo di pagina');
define('OOS_META_PAGE_TYPE_DESC', 'Tipo di connessione Internet');

define('OOS_META_PUBLISHER_TITLE', 'Editore');
define('OOS_META_PUBLISHER_DESC', 'Nome dell\'editore');

define('OOS_META_ROBOTS_TITLE', 'Indicizzazione');
define('OOS_META_ROBOTS_DESC', 'Tipo di indicizzazione');

define('OOS_META_EXPIRES_TITLE', 'Periodo di validità');
define('OOS_META_EXPIRES_DESC', 'Eliminzione pagine offerte (0 per siti aggiornati frequentemente)');

define('OOS_META_PAGE_PRAGMA_TITLE', 'Proxy Caching');
define('OOS_META_PAGE_PRAGMA_DESC', 'il loro negozio utilizza un buffer proxy?');

define('OOS_META_REVISIT_AFTER_TITLE', 'Riattendi dopo');
define('OOS_META_REVISIT_AFTER_DESC', 'Quando il motore di ricerca deve rivisitare il tuo sito?');

define('OOS_META_PRODUKT_TITLE', 'Mantieni l\'articolo');
define('OOS_META_PRODUKT_DESC', 'Mantenere le parole chiavi e le descrizioni per ogni articolo?');

define('OOS_META_KATEGORIEN_TITLE', 'mantieni le categorie');
define('OOS_META_KATEGORIEN_DESC', 'Mantenere le parole chiavi e le descrizioni per ogni articolo?');

define('OOS_META_INDEX_PAGE_TITLE', 'Indice prodotti a lato');
define('OOS_META_INDEX_PAGE_DESC', 'Produrre un indice di tutti i prodotti per i motori di ricerca?');

define('OOS_META_INDEX_PATH_TITLE', 'Percorso a IndexSite');
define('OOS_META_INDEX_PATH_DESC', 'Percorso per caricare il  File per lo  Spider');

define('ADMIN_CONFIG_KEYWORD_SHOW_TITLE', 'Visualizza parole chiave (ADMIN)');
define('ADMIN_CONFIG_KEYWORD_SHOW_DESC', 'Controlla le ricerche fatte dal proprio indirizzo  IP (se è possibile)');

define('OOS_CONFIG_KEYWORD_SHOW_TITLE', 'Visualizza le chiavi di ricerca dei visitatore');
define('OOS_CONFIG_KEYWORD_SHOW_DESC', 'Controlla le ricerche dei Clienti e degli iscritti? (Se è possibile)');

define('CONFIG_KEYWORD_SHOW_EXCLUDED_TITLE', 'Parole chiave (esculo il proprio IP)');
define('CONFIG_KEYWORD_SHOW_EXCLUDED_DESC', 'Il Vostro IP, può essere escluso da ADMIN <br />(come webmaster/proprietario/beta tester)');

define('KEYWORD_SHOW_LOG_PATH_TITLE', 'Parole chiave (path assoluta per il log file)');
define('KEYWORD_SHOW_LOG_PATH_DESC', 'Inserire qui la pat assoluta per il log file, incudendo il nome del log file <br/>(file compresso o non compresso, .gz log file)');

define('ENABLE_LINKS_COUNT_TITLE', 'Conteggio dei click');
define('ENABLE_LINKS_COUNT_DESC', 'Abilita conteggio dei click sul link');

define('ENABLE_SPIDER_FRIENDLY_LINKS_TITLE', 'Friendy links per gli Spider');
define('ENABLE_SPIDER_FRIENDLY_LINKS_DESC', 'Abilita Friendy links per gli Spider (Raccomandato)');

define('LINKS_IMAGE_WIDTH_TITLE', 'Links larghezza immagini');
define('LINKS_IMAGE_WIDTH_DESC', 'Larghezza massima per i links alle immagini');

define('LINKS_IMAGE_HEIGHT_TITLE', 'Links altezza immagini');
define('LINKS_IMAGE_HEIGHT_DESC', 'Altezza massima per i links alle immagini');

define('LINK_LIST_IMAGE_TITLE', 'Visualizza i links alle immagini');
define('LINK_LIST_IMAGE_DESC', 'Visualizzare i links alle immagini?');

define('LINK_LIST_URL_TITLE', 'Visualizza gli URL dei links');
define('LINK_LIST_URL_DESC', 'Visualizzare gli URL deli links?');

define('LINK_LIST_TITLE_TITLE', 'Visualizza il Title dei links');
define('LINK_LIST_TITLE_DESC', 'Visualizzare il Title dei links?');

define('LINK_LIST_DESCRIPTION_TITLE', 'Visualizza la descrizione dei links');
define('LINK_LIST_DESCRIPTION_DESC', 'Visualizzare la descrizione dei links?');

define('LINK_LIST_COUNT_TITLE', 'Visulaizza il conteggio dei click sui links');
define('LINK_LIST_COUNT_DESC', 'Visualizzareil conteggio dei click sui links?');

define('ENTRY_LINKS_TITLE_MIN_LENGTH_TITLE', 'Lunghezza minima del Title dei Links');
define('ENTRY_LINKS_TITLE_MIN_LENGTH_DESC', 'Lunghezza minima del Title dei Links');

define('ENTRY_LINKS_URL_MIN_LENGTH_TITLE', 'Lunghezza minima del URL dei Links');
define('ENTRY_LINKS_URL_MIN_LENGTH_DESC', 'Lunghezza minima del URL dei Links');

define('ENTRY_LINKS_DESCRIPTION_MIN_LENGTH_TITLE', 'Lunghezza minima della  descrizione dei Links');
define('ENTRY_LINKS_DESCRIPTION_MIN_LENGTH_DESC', 'Lunghezza minima della descrizione dei Links');

define('ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH_TITLE', 'Lunghezza minima del nome del contatto dei Links');
define('ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH_DESC', 'Lunghezza minima del nome del contatto dei Links');

define('LINKS_CHECK_PHRASE_TITLE', 'Controllo frasi links');
define('LINKS_CHECK_PHRASE_DESC', 'Ricerca frase, quando ottimizzi i link');

define('DISPLAY_NEWSFEED_TITLE', 'Newsfeed offerte');
define('DISPLAY_NEWSFEED_DESC', 'Abilitare RDF/RSS newsfeed per i clienti?');

define('MULTIPLE_CATEGORIES_USE_TITLE', 'Usa categorie multiple');
define('MULTIPLE_CATEGORIES_USE_DESC', 'Impostare true o false per aggiungere un prodotto a più categorie con un click');

define('OOS_SPAW_TITLE', 'SPAW PHP WYSIWYG Editor ');
define('OOS_SPAW_DESC', 'Usare SPAW PHP WYSIWYG durante l\'acquisizione?');

define('SLAVE_LIST_IMAGE_TITLE', 'Mostra l\'immagine derivante');
define('SLAVE_LIST_IMAGE_DESC', 'Visualizzare le immagini prodotto?');

define('SLAVE_LIST_MANUFACTURER_TITLE', 'Visualizzare il nome del fornitore derivante');
define('SLAVE_LIST_MANUFACTURER_DESC', 'Visualizzare il nome del fornitore del prodotto?');

define('SLAVE_LIST_MODEL_TITLE', 'Visualizza il modello derivante');
define('SLAVE_LIST_MODEL_DESC', 'Visualizzare visualizzare il modello del prodotto?');

define('SLAVE_LIST_NAME_TITLE', 'Visualizza il nome derivante');
define('SLAVE_LIST_NAME_DESC', 'Visualizzare il nome del prodotto?');

define('SLAVE_LIST_PRICE_TITLE', 'Visualiiza il prezzo derivante');
define('SLAVE_LIST_PRICE_DESC', 'Visualizzare il prezzo del prodotto?');

define('SLAVE_LIST_QUANTITY_TITLE', 'Visualizza la quantità derivante');
define('SLAVE_LIST_QUANTITY_DESC', 'Visualizzare la quantità del prodotto?');

define('SLAVE_LIST_WEIGHT_TITLE', 'Visualizza il pese derivante');
define('SLAVE_LIST_WEIGHT_DESC', 'Visualizzare il peso del prodotto?');

define('SLAVE_LIST_BUY_NOW_TITLE', 'Visualizza la colonna "Compra Subito"');
define('SLAVE_LIST_BUY_NOW_DESC', 'Visualizzare la colonna "CompraSubito"?');

define('RCS_BASE_DAYS_TITLE', 'Guarda i giorni prima');
define('RCS_BASE_DAYS_DESC', 'Numero di giorni per guardare indietro da oggi, per abbonati');

define('RCS_REPORT_DAYS_TITLE', 'Giorni di Rapporto delle vendite');
define('RCS_REPORT_DAYS_DESC', 'Numero di giorni per il rapporto delle vendite negli iscritti. Più giorni si richiedono più potra essere lungo il tempo di attesa per la query SQL.');

define('RCS_EMAIL_TTL_TITLE', 'Tempo di vita delle E.mail');
define('RCS_EMAIL_TTL_DESC', 'Numero di giorni da attribuire alle E-mail prima che esse non siano più ne spedite ne visualizzate');

define('RCS_EMAIL_FRIENDLY_TITLE', 'Friendly E-Mails');
define('RCS_EMAIL_FRIENDLY_DESC', 'Se <b>True</b> il nome del cliente sarà usato nel saluto. Se <b>False</b>verrà usato un saluto generico');

define('RCS_SHOW_ATTRIBUTES_TITLE', 'Visualizza attributi');
define('RCS_SHOW_ATTRIBUTES_DESC', 'Visualizza il controllo degli attributi <br/><br/>Alcuni siti hanno propri attributi<br/><br/>Impostare a <b>true</b>se si vuole visualizzare altrimetti impostare <b>false</b>');

define('RCS_CHECK_SESSIONS_TITLE', 'Ignorare clienti con sessioni');
define('RCS_CHECK_SESSIONS_DESC', 'Se si vuole ignorare utenti con sessioni aperte (internet explorer, probabilmente continuerà a funzionare) impostare <b>true</b><br/><br/>Impostare </b>false</b> si opererà nel modo standard ignorando i dati provenienti dalle sessioni utilizzando gli ultimi disponibili');

define('RCS_CURCUST_COLOR_TITLE', 'Cliente corrente Hilight');
define('RCS_CURCUST_COLOR_DESC', 'Il colore delle parole/frasi usate per contrassegnare un cliente.<br/><br/>Un cliente corrente è qualcuno che può aver comprato nel vostro negozio in precedenza.');

define('RCS_UNCONTACTED_COLOR_TITLE', 'Hilight non contattati.');
define('RCS_UNCONTACTED_COLOR_DESC', 'Righe colorate per highlight di clienti non contattati <br/><br/>Per utenti non contattati si intendono quelli per il quale non è stato usato prima questo strumento per mandare e-mail');

define('RCS_CONTACTED_COLOR_TITLE', 'Hilight contattati');
define('RCS_CONTACTED_COLOR_DESC', 'Righe colorate per highlight di clienti contattati <br/><br/>Per utenti contattati si intendono quelli per il quale è stato usato prima questo strumento per mandare e-mail');

define('RCS_MATCHED_ORDER_COLOR_TITLE', 'Ordini per corrispondenza Hilight');
define('RCS_MATCHED_ORDER_COLOR_DESC', 'Riga evidenziata per le entrate che possono avere corrispondenze con ordini. <br><br>Un\'entrata può essere marcata con questo colore se un ordine contiene altri prodotti nel carrello abbondonato <b>e</b> e collegare il carrello del cliente all\'indirizzo email, o al database ID');

define('RCS_SKIP_MATCHED_CARTS_TITLE', 'Scarta i carrelli con ordini  w/contassegnati ');
define('RCS_SKIP_MATCHED_CARTS_DESC', 'Per ignorare carrelli con un set di ordini contrassegnati impostare <b>true</b><br><br> Impostando <b>false</b> verranno inserite entrate con ordini da visualizzare con l\'ordine di corrispondenza. <br><br> Vedi documentazione per dettagli');

define('RCS_PENDING_SALE_STATUS_TITLE', 'Le più basse condizioni di vendita in corso.');
define('RCS_PENDING_SALE_STATUS_DESC', 'Il più alto valore che un ordine possa avere ed ancora essere considerato in corso. Tutto il valore più superiore a questo sarà considerato da RCS come vendita completa<br><br>Vedi documentazione per i dettagli.');

define('RCS_REPORT_EVEN_STYLE_TITLE', 'Riga pari nello stile del report');
define('RCS_REPORT_EVEN_STYLE_DESC', 'Lo stile delle righe pari che risulta nel report. Le impostazioni tipiche sono <i>dataTableRow</i> e <i>attributes-even</i>. ');

define('RCS_REPORT_ODD_STYLE_TITLE', 'Riga dispari nello stile del report');
define('RCS_REPORT_ODD_STYLE_DESC', 'Lo stile delle righe dispari che risulta nel report. Le impostazioni tipiche sono NULL (ie, no entrate) e <i>attributes-odd</i>.  no entry) and <i>attributes-odd</i>.');

define('RCS_EMAIL_COPIES_TO_TITLE', 'Copie a E-Mail');
define('RCS_EMAIL_COPIES_TO_DESC', 'Se si vuole copie di e-mail che sono state inviate ai clienti da questa contribition, inserisci l\'indirizzo email qui. Se non è inserito non vengono inviate copie');

define('RCS_AUTO_CHECK_TITLE', 'Controllo automatico  "safe" carrello  a email');
define('RCS_AUTO_CHECK_DESC', 'Per controllare le entate che devono preferibilmente essere salavate via email (ie, non ci sono clienti, non ci sono email precedenti, etc) impostare <b>true</b><br><br><b>false</b> lascerà tutte le entrate non selezionate.');

define('RCS_CARTS_MATCH_ALL_DATES_TITLE', 'Seleziona ordini da tutte le date');
define('RCS_CARTS_MATCH_ALL_DATES_DESC', 'Se <b>true</b> tutti gli ordini trovati con contrassegni saranno considerati come ordini contrassegnati.<br><br><b>false</b> solo gli ordini solo gli ordini dopo l\'abbandono del carrello saranno considerati');

?>