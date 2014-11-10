<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php,v 1.2 2008/06/04 14:41:37 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.8 2002/01/04 03:51:40 hpdl
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

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Naam');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Waarde');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in');
define('TEXT_INFO_DATE_ADDED', 'toegevoegd op:');
define('TEXT_INFO_LAST_MODIFIED', 'laatste verandering op:');


define('STORE_NAME_TITLE', 'Winkelnaam');
define('STORE_NAME_DESC', 'De naam van mijn winkel');

define('STORE_OWNER_TITLE', 'Winkeleigenaar');
define('STORE_OWNER_DESC', 'De naam van de webwinkeleigenaar');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'Email adres');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'Het E-mail adres van de winkeleigenaar');

define('STORE_OWNER_VAT_ID_TITLE' , 'VAT ID of Shop Owner');
define('STORE_OWNER_VAT_ID_DESC' , 'The VAT ID of the Shop Owner');

define('SKYPE_ME_TITLE', 'Skypenaam');
define('SKYPE_ME_DESC', 'Nieuwe gebruikers kunnen <a href=\"http://www.skype.com/go/download\" target=\"_blank\">een nieuwe account aanmaken</a> en een Skypenaam ontvangen.');

define('STORE_COUNTRY_TITLE', 'Land');
define('STORE_COUNTRY_DESC', 'In welk land wordt de webwinkel bedreven <br><br><b>Aanwijzing: Vergeet niet de provincie aan te passen</b>');

define('STORE_ZONE_TITLE', 'Provincie');
define('STORE_ZONE_DESC', 'In welke provincie wordt de webwinkel bedreven?');

define('EXPECTED_PRODUCTS_SORT_TITLE', 'Sorteervolgorde verwachte produkten');
define('EXPECTED_PRODUCTS_SORT_DESC', 'Sorteervolgorde, die in \'verwachte produkten\'-Blok gebruikt wordt.');

define('EXPECTED_PRODUCTS_FIELD_TITLE', 'Sorteerruimte verwachte produkten');
define('EXPECTED_PRODUCTS_FIELD_DESC', 'De ruimte, waarna de in \'verwchte produkten\'-Blok gesorterd wordt.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'Valuta automatisch omwisselen');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Wisselt automatisch de valuta om aan de hand van de ingestelde taal');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Standaard koppelwoord voor zoekmachine');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'De standaard koppeling waarmee meerdere zoekbegrippen gekoppeld worden');

define('STORE_NAME_ADDRESS_TITLE', 'Adresinformatie van de winkel');
define('STORE_NAME_ADDRESS_DESC', 'De contactinformatie van de winkel, die zowel in documenten als in ook Online afgegeven worden');

define('TAX_DECIMAL_PLACES_TITLE', 'Decimalen van de B.T.W.');
define('TAX_DECIMAL_PLACES_DESC', 'Aantal decimalen van de B.T.W.');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Prijs incl. B.T.W.');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Prijs incl. B.T.W. aangeven (true) of de B.T.W. bij het totaalbedrag optellen (false)');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE', 'Algemene voorwaarden tonen');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC', 'In het kassavenster de algemne voorwaarden tonen, voordat men verder kan.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sortering van produktopties');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'Wilt u de produktopties aan de hand van de prijzen sorteren?');

define('WEB_SEARCH_GOOGLE_KEY_TITLE', 'Google API licentie sleutel');
define('WEB_SEARCH_GOOGLE_KEY_DESC', 'Google API licentie sleutel (gratis!) <A HREF=\"http://www.google.com/apis\" TARGET=\"_blank\">http://www.google.com/apis</A>.');

define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE', 'Voornaam');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC', 'Minimale lengte voornaam');

define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE', 'Achternaam');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC', 'Minimale lengte achternaam');

define('ENTRY_DOB_MIN_LENGTH_TITLE', 'Geboortedatum');
define('ENTRY_DOB_MIN_LENGTH_DESC', 'Minimale lengte geboortedatum');

define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE', 'Emailadres');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC', 'Minimale lengte emailadres');

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Straat');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Minimale lengte straat');

define('ENTRY_COMPANY_LENGTH_TITLE', 'Bedrijf');
define('ENTRY_COMPANY_LENGTH_DESC', 'Minimale lengte bedrijfsnaam');

define('ENTRY_POSTCODE_MIN_LENGTH_TITLE', 'Postcode');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC', 'Minimale lengte postcode');

define('ENTRY_CITY_MIN_LENGTH_TITLE', 'Woonplaats');
define('ENTRY_CITY_MIN_LENGTH_DESC', 'Minimale lengte woonplaats');

define('ENTRY_STATE_MIN_LENGTH_TITLE', 'Provincie');
define('ENTRY_STATE_MIN_LENGTH_DESC', 'Minimale lengte provincie');

define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE', 'Telefoonnummer');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC', 'Minimale lengte telefoonnummer');

define('ENTRY_PASSWORD_MIN_LENGTH_TITLE', 'Wachtwoord');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC', 'Minimale lengte wachtwoord');

define('CC_OWNER_MIN_LENGTH_TITLE', 'Naam eigenaar credietkaart');
define('CC_OWNER_MIN_LENGTH_DESC', 'Minimale lengte  naam creditkaarteigenaar');

define('CC_NUMBER_MIN_LENGTH_TITLE', 'Credietkaartnummer');
define('CC_NUMBER_MIN_LENGTH_DESC', 'Minimale lengte creditkaartnummer');

define('MIN_DISPLAY_BESTSELLERS_TITLE', 'Verkoopsucces');
define('MIN_DISPLAY_BESTSELLERS_DESC', 'Minimum aantalverkoopsuccessen');

define('MIN_DISPLAY_ALSO_PURCHASED_TITLE', 'Klanten kochten ook');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC', 'Minimum aantal van produkten, die in het \'Klanten kochten ook\' venster getoond worden');

define('MIN_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt aanbevelingen');
define('MIN_DISPLAY_XSELL_PRODUCTS_DESC', 'Minimum aantal van produkten, die in het \'Produkt aanbevelingen\' venster getoond worden');

define('MIN_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Nieuwe produkten in de nieuwsbrief');
define('MIN_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Minimum aantal van produkten, die in \'Nieuwsbrief\' getoond worden');

define('MIN_DISPLAY_NEW_NEWS_TITLE', 'Nieuws vermeldingen');
define('MIN_DISPLAY_NEW_NEWS_DESC', 'Minimum aantal van vermeldingen, die op de \'Startpagina\' getoond worden');

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE', 'Aantal adresboek invoeringen');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC', 'Maximale aantal adresboekinvoeren per klant');

define('MAX_DISPLAY_SEARCH_RESULTS_TITLE', 'Aantal zoekresultaten');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC', 'Aantal artikelen die getoond worden');

define('MAX_DISPLAY_PAGE_LINKS_TITLE', 'Pagina links');
define('MAX_DISPLAY_PAGE_LINKS_DESC', 'Aantal \'number\' links gebruikt voor paginasets');

define('MAX_DISPLAY_NEW_PRODUCTS_TITLE', 'Nieuwe produkten');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC', 'Maximale aantal nieuwe produkten die in iedere categorie getoond worden');

define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE', 'Verwachte produkten');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC', 'Maximale aantal van de verwachte produkten die gettond worden');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Toevallige produktaanduiding');
define('MAX_RANDOM_SELECT_NEW_DESC', 'De hoeveelheid nieuwe produkten waaruit per toeval een produkt getoond wordt');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Toevallige produktaanduiding');
define('MAX_RANDOM_SELECT_NEW_DESC', 'De hoeveelheid nieuwe produkten waaruit per toeval een produkt getoond wordt');


define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE', 'Aantal categorie&euml;n per regel');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC', 'Hoeveel categorie&euml;n mogen per regel maximaal getoond worden?');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'Aantal nieuwe produkten');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Hoeveel nieuwe produkten mogen in het overzicht nieuwe produkten maximaal getoond worden?');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Verkoopsucces');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Hoeveel verkoopsuccessen mogen maximaal getoond worden?');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Klanten kochten ook');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximale aantal produkten die in \'Klanten kochten ook\' veld getoond worden');

define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE', 'Produktaantal besteloverzicht veld');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC', 'Maximale aantal produkten die in het besteloverzicht veld getoond worden');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Aantal bestellingen besteloverzicht veld');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximale aantal bestellingen in het besteloverzicht veld');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt aanbiedingen');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Maximale aantal produkten, die in het \'Produkt-aanbiedingen\'-veld getoond worden');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Verlanglijst');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Maximale aantal produkten op de verlanglijst pagina');

define('MAX_DISPLAY_WISHLIST_BOX_TITLE', 'Verlanglijst infovenster');
define('MAX_DISPLAY_WISHLIST_BOX_DESC', 'Maximale aantal produkten, die in \'Verlanglijst\'-veld getoond worden');

define('MAX_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Nieuwe produkten in nieuwsmeldingen');
define('MAX_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Maximale aantal produkten, die in \'Niewsmeldingen\' getoond worden');

define('MAX_RANDOM_SELECT_NEWSFEED_TITLE', 'Nieuwsmeldingen');
define('MAX_RANDOM_SELECT_NEWSFEED_DESC', 'De hoeveelheid nieuwsmeldingen waaruit per toeval een nieuwsmelding getoond wordt');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Selection of Random New Products');
define('MAX_RANDOM_SELECT_NEW_DESC', 'How many records to select from to choose one random new product to display');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Aantal nieuwsmeldingen');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Maximale aantal meldingen, die op de startpagina getoond worden');

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Aantal in produktgeschiedenis');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale aantal produkten die in \'Produkt geschiedenis\'-Blok getoond worden');

define('SMALL_IMAGE_WIDTH_TITLE', 'Breedte kleine afbeeldingen');
define('SMALL_IMAGE_WIDTH_DESC', 'De pixelbreedte van kleine afbeeldingen');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Hoogte klein afbeeldingen');
define('SMALL_IMAGE_HEIGHT_DESC', 'De pixelhoogte van kleine afbeeldingen');

define('HEADING_IMAGE_WIDTH_TITLE', 'Breedte kopafbeeldingen');
define('HEADING_IMAGE_WIDTH_DESC', 'De pixelbreedte van de kopafbeeldingen');

define('HEADING_IMAGE_HEIGHT_TITLE', 'Hoogte kopafbeeldingen');
define('HEADING_IMAGE_HEIGHT_DESC', 'De pixelhoogte van de kopafbeeldingen');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Breedte sub-categorie afbeeldingen');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'De pixelbreedte van sub-categorieafbeeldingen');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Hoogte sub-categorie afbeeldingen');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'De pixelhoogte van sub-categorieafbeeldingen');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE', 'Berekenen van de afbeeldingsgrootte');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC', 'Beeldgrootte berekenen??');

define('IMAGE_REQUIRED_TITLE', 'Beeld gewenst');
define('IMAGE_REQUIRED_DESC', 'Inschakelen om dode afbeeldingslinks weer te geven. Behulpzaam bij het ontwerpen.');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Toegangsrecht');
define('CUSTOMER_NOT_LOGIN_DESC', 'Het toegangsrecht word door de beheerder na controle van de klantgegevens afgegeven');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Klantgegevens per email');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'De klantgegevens worden per email naar de winkelbeheerder gestuurd');

define('DEFAULT_MAX_ORDER_TITLE', 'Klantencrediet');
define('DEFAULT_MAX_ORDER_DESC', 'Maximale bedrag van een bestelling');

define('ACCOUNT_GENDER_TITLE', 'Aanspreektitel');
define('ACCOUNT_GENDER_DESC', 'De aanspreektitel wordt getoond');

define('ACCOUNT_DOB_TITLE', 'Geboortedatum');
define('ACCOUNT_DOB_DESC', 'De geboortedatum wordt nadrukkelijk gevraagd');

define('ACCOUNT_NUMBER_TITLE', 'Klantennummer');
define('ACCOUNT_NUMBER_DESC', 'Verwerking van eigen klantnummers');

define('ACCOUNT_COMPANY_TITLE', 'Bedrijfsnaam');
define('ACCOUNT_COMPANY_DESC', 'Bedrijfsnaam wordt getoond');

define('ACCOUNT_OWNER_TITLE', 'Eigenaar');
define('ACCOUNT_OWNER_DESC', 'Eigenaar van het bedrijf wordt getoond');

define('ACCOUNT_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('ACCOUNT_VAT_ID_DESC', 'Die Umsatzsteuer ID bei gewerblichen Kunden kann eingegeben werden.');


define('ACCOUNT_SUBURB_TITLE', 'Stadsdistrict');
define('ACCOUNT_SUBURB_DESC', 'District van een stad kan worden getoond');

define('ACCOUNT_STATE_TITLE', 'Provincie');
define('ACCOUNT_STATE_DESC', 'Provincie wordt getoond');

define('STORE_ORIGIN_COUNTRY_TITLE', 'Landcode');
define('STORE_ORIGIN_COUNTRY_DESC', 'Voer de &quot;ISO 3166&quot; landcode van de winkel in die wordt gebruikt op de verzendendbiljetten.  Om de landcode te vinden ga naar <A HREF=\"http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html\" TARGET=\"_blank\">ISO 3166 Maintenance Agency</');

define('STORE_ORIGIN_ZIP_TITLE', 'Postcode');
define('STORE_ORIGIN_ZIP_DESC', 'Voer de postcode van de winkel in voor op de verzendbiljetten.');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Voer het max. gewicht in dat u wilt verzenden');
define('SHIPPING_MAX_WEIGHT_DESC', 'Max. gewicht voor de pakketten. Dit is algemeen voor elk pakket.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Pakket brutogewicht.');
define('SHIPPING_BOX_WEIGHT_DESC', 'Wat is het gemiddelde gewicht van een klein tot midelgroot pakket?');

define('SHIPPING_BOX_PADDING_TITLE', 'Grotere pakketten - procentueel verhogen.');
define('SHIPPING_BOX_PADDING_DESC', 'Voor 10% voer 10 in');

define('PRODUCT_LIST_IMAGE_TITLE', 'Artikelafbeelding tonen');
define('PRODUCT_LIST_IMAGE_DESC', 'Wilt u een afbeelding van het artikel tonen?');

define('PRODUCT_LIST_MANUFACTURER_TITLE', 'Artikelfabrikant tonen');
define('PRODUCT_LIST_MANUFACTURER_DESC', 'Wilt u de fabrikant van het artikel aangeven?');

define('PRODUCT_LIST_MODEL_TITLE', 'Artikelmodel tonen');
define('PRODUCT_LIST_MODEL_DESC', 'Wilt u het artikelmodel aangeven?');

define('PRODUCT_LIST_NAME_TITLE', 'Artikelnaam tonen');
define('PRODUCT_LIST_NAME_DESC', 'Wilt u de artikelnaam aangeven?');

define('PRODUCT_LIST_UVP_TITLE', 'Display Product List Price');
define('PRODUCT_LIST_UVP_DESC', 'Do you want to display the Product List Price?');

define('PRODUCT_LIST_PRICE_TITLE', 'Artikelprijs tonen');
define('PRODUCT_LIST_PRICE_DESC', 'Wilt u de artikelprijs aangeven?');

define('PRODUCT_LIST_QUANTITY_TITLE', 'Artikelaantal tonen');
define('PRODUCT_LIST_QUANTITY_DESC', 'Wilt u het voorraadaantal van het artikel aangeven?');

define('PRODUCT_LIST_WEIGHT_TITLE', 'Artikelgewicht tonen');
define('PRODUCT_LIST_WEIGHT_DESC', 'Wilt u het artikelgewicht aangeven?');

define('PRODUCT_LIST_BUY_NOW_TITLE', 'Koop nu');
define('PRODUCT_LIST_BUY_NOW_DESC', 'Wilt u de Koop nu\' knop tonen?');

define('PRODUCT_LIST_FILTER_TITLE', 'Categorie/Fabrikant filter tonen');
define('PRODUCT_LIST_FILTER_DESC', 'Wilt u het Categorie/Fabrikant filter tonen (0:uit,1:aan)?');

define('PRODUCT_LIST_SORT_ORDER_TITLE', 'Display Product Sort Order');
define('PRODUCT_LIST_SORT_ORDER_DESC', 'Do you want to display the Product Sort Order column?');

define('PREV_NEXT_BAR_LOCATION_TITLE', 'Positie van der Vorige/Volgende navigatie');
define('PREV_NEXT_BAR_LOCATION_DESC', 'Legt de positie van de Vorige/Volgende navigatieknop vast (1:boven, 2:onder, 3:beiden)');

define('STOCK_CHECK_TITLE', 'Voorraadbestand controleren');
define('STOCK_CHECK_DESC', 'Controleren of voldoende voorraad aanwezig is');

define('STOCK_LIMITED_TITLE', 'Voorraadbestand actualiseren');
define('STOCK_LIMITED_DESC', 'Gekochte produkten worden van de voorraad afgetrokken');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Controleren toegestaan');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Sta gebruikers toe te controleren, of er voldoende voorraad is');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Produktmarkering, wanneer niet op voorraad');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Tekst weergave waaraan een klant kan zien of een produkt niet op voorraad');

define('STOCK_REORDER_LEVEL_TITLE', 'Minimum voorraad');
define('STOCK_REORDER_LEVEL_DESC', 'Als de aangegeven artikelenhoeveelheid bereikt of overschreden wordt, verschijnt er een melding in het voorrraadoverzicht');

define('STORE_PAGE_PARSE_TIME_TITLE', 'Opslaan pagina zoektijd');
define('STORE_PAGE_PARSE_TIME_DESC', 'Sla de tijd op die nodig is om een pagina te verwerken');

define('STORE_PAGE_PARSE_TIME_LOG_TITLE', 'Logboek bestemming');
define('STORE_PAGE_PARSE_TIME_LOG_DESC', 'Map en bestandsnaam van de paginaverwerkingstijd log');

define('STORE_PARSE_DATE_TIME_FORMAT_TITLE', 'Logboek  datumformaat');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC', 'Het datumformaat');

define('DISPLAY_PAGE_PARSE_TIME_TITLE', 'Toon pagina verwerkingstijd');
define('DISPLAY_PAGE_PARSE_TIME_DESC', 'Toon de pagina verwerkingstijd (Opslaan pagina zoektijd moet aan staan)');

define('USE_CACHE_TITLE', 'Gebruik Cache');
define('USE_CACHE_DESC', 'Gebruik cache mogelijkheden');

define('DOWNLOAD_ENABLED_TITLE', 'Schakel download in');
define('DOWNLOAD_ENABLED_DESC', 'Schakel de produktdownload functies in.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download via redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Gebruik browser omleiding voor de download. Schakel uit op Niet-Unix systemen.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Looptijd vertraging (dagen)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Stel aantal dagen in voordat de download link verloopt. 0 betekent geen limiet.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximum aantal downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Stel het maximum aantal downloads in. 0 betekent geen download toegestaan.');

define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_TITLE', 'Downloads beheerder update statuswaarde');
define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_DESC', 'Welke ordersstatus zet de download dagen terug en de max. downloads - Standaard is');

define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_TITLE', 'Downloads beheerder download wachtbericht');
define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_DESC', 'Downloadsbeheerder download wachtbericht');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads beheerder order statuswaarde');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloadsbeheerder order statuswaarde - standaard=2');

define('PDF_DATA_SHEET_TITLE', 'PDF bestanden activeren');
define('PDF_DATA_SHEET_DESC', 'Wilt u de produktinformatie als PDF-bestand te download aanbieden?');

define('HEADER_COLOR_TABLE_TITLE', 'Kleur: Cataloguskop-tabel');
define('HEADER_COLOR_TABLE_DESC', 'Kleur in RGB waarde(voorb: 230,230,230)');

define('PRODUCT_NAME_COLOR_TABLE_TITLE', 'Kleur: Produktnaam-tabel');
define('PRODUCT_NAME_COLOR_TABLE_DESC', 'Kleur in RGB waarde(voorb: 230,230,230)');

define('FOOTER_CELL_BG_COLOR_TITLE', 'Achtergrond kleur: Catalogusonderkant');
define('FOOTER_CELL_BG_COLOR_DESC', 'Kleur in RGB waarde(voorb: 230,230,230)');

define('SHOW_BACKGROUND_TITLE', 'Achtergrond');
define('SHOW_BACKGROUND_DESC', 'Wilt u een achtergrondkleur tonen?');

define('PAGE_BG_COLOR_TITLE', 'Achtergrond kleur: Catalogus');
define('PAGE_BG_COLOR_DESC', 'Kleur in RGB waarde(voorb: 230,230,230)');

define('SHOW_WATERMARK_TITLE', 'Watermerk');
define('SHOW_WATERMARK_DESC', 'Wilt u uw bedrijfsnaam als watermerk tonen?');

define('PAGE_WATERMARK_COLOR_TITLE', 'Watermerk kleur');
define('PAGE_WATERMARK_COLOR_DESC', 'Kleur in RGB waarde(voorb: 230,230,230)');

define('PDF_IMAGE_KEEP_PROPORTIONS_TITLE', 'Produkt afbeeldingen');
define('PDF_IMAGE_KEEP_PROPORTIONS_DESC', 'Wilt u de maximale  minimale produktgrootte gebruiken?');

define('MAX_IMAGE_WIDTH_TITLE', 'Breedte van de produktafbeeldingen');
define('MAX_IMAGE_WIDTH_DESC', 'Max. breedte in mm van de produktafbeeldingen');

define('MAX_IMAGE_HEIGHT_TITLE', 'Hoogte van de produktafbeeldingen');
define('MAX_IMAGE_HEIGHT_DESC', 'Max. hoogte in mm van de produktafbeeldingen');

define('PDF_TO_MM_FACTOR_TITLE', 'Factor');
define('PDF_TO_MM_FACTOR_DESC', 'Produktafbeeldingen');

define('SHOW_PATH_TITLE', 'Categorienaam');
define('SHOW_PATH_DESC', 'Wilt u de categorienaam tonen?');

define('SHOW_IMAGES_TITLE', 'Produktafbeelding');
define('SHOW_IMAGES_DESC', 'Wilt u de produktafbeelding tonen?');

define('SHOW_NAME_TITLE', 'Produktnaam');
define('SHOW_NAME_DESC', 'Wilt u de produktnaam in de beschrijving tonen?');

define('SHOW_MODEL_TITLE', 'Bestelnummer');
define('SHOW_MODEL_DESC', 'Wilt u het bestelnummer tonen?');

define('SHOW_DESCRIPTION_TITLE', 'Produktbeschrijving');
define('SHOW_DESCRIPTION_DESC', 'Wilt u de produktbeschrijving tonen?');

define('SHOW_MANUFACTURER_TITLE', 'Fabrikant');
define('SHOW_MANUFACTURER_DESC', 'Wilt u de fabrikant tonen?');

define('SHOW_PRICE_TITLE', 'Produktprijs');
define('SHOW_PRICE_DESC', 'Wilt u de produktprijs tonen?');

define('SHOW_SPECIALS_PRICE_TITLE', 'Speciale aanbieding');
define('SHOW_SPECIALS_PRICE_DESC', 'Wilt u de aanbiedingsprijs tonen?');

define('SHOW_SPECIALS_PRICE_EXPIRES_TITLE', 'Datum speciale aanbieding');
define('SHOW_SPECIALS_PRICE_EXPIRES_DESC', 'Wilt u de geldigheids termijn van de aanbiedingsprijs tonen?');

define('SHOW_TAX_CLASS_ID_TITLE', 'B.T.W. tonen');
define('SHOW_TAX_CLASS_ID_DESC', 'Wilt u het B.T.W. tarief tonen?');

define('SHOW_OPTIONS_TITLE', 'Produktopties');
define('SHOW_OPTIONS_DESC', 'Wilt u de produktopties tonen?');

define('SHOW_OPTIONS_PRICE_TITLE', 'Prijs van de produktopties');
define('SHOW_OPTIONS_PRICE_DESC', 'Wilt  u de prijzen van de produktopties tonen?');

define('TICKET_ENTRIES_MIN_LENGTH_TITLE', 'Hulpaanvraag');
define('TICKET_ENTRIES_MIN_LENGTH_DESC', 'Het minimale aantal karakters van de hulpaanvraag');

define('TICKET_ADMIN_NAME_TITLE', 'Naam hulpaanvraagbeheerder');
define('TICKET_ADMIN_NAME_DESC', 'De naam van de beheerder die de hulpaanvragen behartigd');

define('TICKET_USE_STATUS_TITLE', 'Statusaanduiding in de winkel');
define('TICKET_USE_STATUS_DESC', 'Wilt u de status van de hulpaanvragen tonen?');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_TITLE', 'Sta klant toe');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_DESC', 'Sta de klant toe, de status te veranderen bij beantwoorden');

define('TICKET_USE_DEPARTMENT_TITLE', 'Gebruik afdeling');
define('TICKET_USE_DEPARTMENT_DESC', 'Gebruik afdeling in de catalogus');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_TITLE', 'Sta klant toe');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_DESC', 'Sta klant toe, de status te veranderen bij beantwoorden');

define('TICKET_USE_PRIORITY_TITLE', 'Gebruik prioriteit');
define('TICKET_USE_PRIORITY_DESC', 'Gebruik prioriteit in de catalogus');

define('TICKET_USE_ORDER_IDS_TITLE', 'Bestel-ID');
define('TICKET_USE_ORDER_IDS_DESC', 'Als de klant is ingelogd, worden zijn bestel-id \'s getoond');

define('TICKET_USE_SUBJECT_TITLE', 'Toon onderwerp');
define('TICKET_USE_SUBJECT_DESC', 'Toon onderwerp');

define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_TITLE', 'Inloggen');
define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_DESC', 'Als u dit op true zet kan u toestaan/niet toestaan of geregistreerde klanten kaarten mogen bekijken zonder te zijn ingelogd');

define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_TITLE', 'Winkel - inlog');
define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_DESC', '0 = geregistreerde klant hoeft niet ingelogd te zijn om kaart te bekijkent<br>1 = geregistrerde klant moet ingelogd zijn om kaart te bekijken');

define('SECURITY_CODE_LENGTH_TITLE', 'Inleveringscode');
define('SECURITY_CODE_LENGTH_DESC', 'Stel de lengte van de inleveringscode in, hoe langer hoe veiliger');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Tegoedbon nieuwe klant');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Determines the amount of the rebate which the new customer will receive. Leave the field empty when the new customer will not be receiving a \'Welcome Gift\'.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Tegoedboncode');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Stel de tegoedboncode in, die per email naar een nieuwe aanvraag wordt verstuurd, als geen code is ingevoerd dan geen email');

define('STORE_TEMPLATES_TITLE', 'Opmaak winkel');
define('STORE_TEMPLATES_DESC', 'Winkel sjabloon');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Produktdatum');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'Wilt u in de winkel de datum van de opname van het produkt tonen?');

define('SHOW_COUNTS_TITLE', 'Artikelenaantal in de betreffende categorie&euml;n');
define('SHOW_COUNTS_DESC', 'Tonen, hoeveel produkten in iedere categorie aanwezig zijn');

define('CATEGORIES_BOX_SCROLL_LIST_ON_TITLE', 'Categorie&euml;n-keuzelijst');
define('CATEGORIES_BOX_SCROLL_LIST_ON_DESC', 'Wilt u de categorie&euml;n als keuzelist tonen?');

define('CATEGORIES_SCROLL_BOX_LEN_TITLE', 'Categorie&euml;n overzichtaantal');
define('CATEGORIES_SCROLL_BOX_LEN_DESC', 'Wanneer u de categorie&euml;n als keuzelijst tonen wilt, leg dan hier de lengte vast');

define('SHOPPING_CART_IMAGE_ON_TITLE', 'Afbeelding in winkelwageninhoud');
define('SHOPPING_CART_IMAGE_ON_DESC', 'Wilt u in de gedetailleerde inhoud van de winkelwagen de produktafbeelding tonen?');

define('SHOPPING_CART_MINI_IMAGE_TITLE', 'Afbeelding verkleinen');
define('SHOPPING_CART_MINI_IMAGE_DESC', 'Waarde voor de verkleining van de gedetailleerde blik in de winkelwagen');

define('DISPLAY_CART_TITLE', 'Winkelwagen tonen');
define('DISPLAY_CART_DESC', 'Toont de winkelwagen nadat er een produkt aan toegevoeg werd');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE', 'Aanraders ook voor bezoekers tonen');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC', 'Gasten toestaan een produkt aan te raden');

define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE', 'Toestaan categorie&euml;n beschrijving');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC', 'Staat een uitgebreide beschrijving toe van de afzonderlijke categorie&euml;n');

define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_TITLE', 'Toestaan nieuws-categorie&euml;n beschrijving');
define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_DESC', 'Staat een uitgebreide beschrijving toe van de afzonderlijke nieuwscategorie&euml;n');

define('SHOW_PRODUCTS_MODEL_TITLE', 'Navigatie via bestelnummer');
define('SHOW_PRODUCTS_MODEL_DESC', 'Wilt u op de produktinformatie-pagina het bestelnummer in de Navigatie tonen?');

define('BREADCRUMB_SEPARATOR_TITLE', 'Restbestand afscheider');
define('BREADCRUMB_SEPARATOR_DESC', 'Restbestand afscheider');

define('BLOCK_BEST_SELLERS_IMAGE_TITLE', 'Afbeelding in veld verkoopsucces');
define('BLOCK_BEST_SELLERS_IMAGE_DESC', 'Afbeelding in Inhoudsveld verkoopsucces tonen?');

define('BLOCK_PRODUCTS_HISTORY_IMAGE_TITLE', 'Afbeelding in veld gekochte produkten');
define('BLOCK_PRODUCTS_HISTORY_IMAGE_DESC', 'Afbeelding in Inhoudsveld gekochte produkten tonen?');

define('BLOCK_WISHLIST_IMAGE_TITLE', 'Afbeelding in veld verlanglijst');
define('BLOCK_WISHLIST_IMAGE_DESC', 'Afbeelding in Inhoudsveld verlanglijst tonen?');

define('BLOCK_XSELL_PRODUCTS_IMAGE_TITLE', 'Bild im Block �nliche Produkte');
define('BLOCK_XSELL_PRODUCTS_IMAGE_DESC', 'Bild im Content-Block �nliche Produkte anzeigen?');

define('OOS_GD_LIB_VERSION_TITLE', 'GD-Bibliotheek');
define('OOS_GD_LIB_VERSION_DESC', '1 voor de oude GD-Lib Versie (1.x)<br> 2 voor de actuele GD-Lib Version (2.x)');

define('OOS_SMALLIMAGE_WAY_OF_RESIZE_TITLE', 'Beeldbewerking van klein beeld');
define('OOS_SMALLIMAGE_WAY_OF_RESIZE_DESC', '0:Proportionele verkleining van breedte of hoogte. Maximale grootte<br> 1: Afbeelding wordt proportioneel in het nieuwe venster gekopieerd. Er wordt met de achtergrondkleur rekening gehouden.<br> 2: Een samenvoeging wordt in de nieuwe afbeelding gekopieer');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Breedte kleine afbeeldingen');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'De pixelbreedte van kleine afbeeldingen');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Hoogte klein afbeeldingen');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'De pixelhoogte van kleine afbeeldingen');

define('OOS_IMAGE_BGCOLOUR_R_TITLE', 'Achtergrond van klein beeld R');
define('OOS_IMAGE_BGCOLOUR_R_DESC', 'Rood waarde voor de kleine produktafbeelding');

define('OOS_IMAGE_BGCOLOUR_G_TITLE', 'Achtergrond klein beeld G');
define('OOS_IMAGE_BGCOLOUR_G_DESC', 'Groen waarde voor de kleine produktafbeelding');

define('OOS_IMAGE_BGCOLOUR_B_TITLE', 'Achtergrond klein beeld B');
define('OOS_IMAGE_BGCOLOUR_B_DESC', 'Blauw waarde voor de kleine produktafbeelding');

define('OOS_BIGIMAGE_WAY_OF_RESIZE_TITLE', 'Beeldbewerking groot beeld');
define('OOS_BIGIMAGE_WAY_OF_RESIZE_DESC', '0: Proportionele verkleining breedte of hoogte. Maximale grootte<br> 1: Afbeelding wordt proportioneel in het nieuwe venster gekopieerd. Er wordt met de achtergrondkleur rekening gehouden.<br> 2: Een samenvoeging wordt in de nieuwe afbeelding gekopieerd');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Breedte groot beeld');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Breedte in pixels van de grote afbeelding');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'Hoogte groot beeld');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'Hoogte in pixels van de grote afbeelding');

define('OOS_WATERMARK_TITLE', 'Watermerk');
define('OOS_WATERMARK_DESC', 'Wilt u in de grote afbeelding een watermerk invoegen?');

define('OOS_WATERMARK_QUALITY_TITLE', 'Kwaliteit van het watermerk');
define('OOS_WATERMARK_QUALITY_DESC', 'Hier legt u de kwaliteit van het watermerk vast');

define('OOS_IMAGE_SWF_TITLE', 'Ming');
define('OOS_IMAGE_SWF_DESC', 'Is Ming geinstalleerd?');

define('OOS_SWF_MOVIECLIP_TITLE', 'Flash-Film');
define('OOS_SWF_MOVIECLIP_DESC', 'Wilt u de kleine produktafbeelding in een flashfilm omzetten?');

define('OOS_SWF_BGCOLOUR_R_TITLE', 'Achtergrond van Flashfilm R');
define('OOS_SWF_BGCOLOUR_R_DESC', 'Rood waarde voor de kleine produktafbeelding in de flashfilm');

define('OOS_SWF_BGCOLOUR_G_TITLE', 'Achtergrond van Flashfilm G');
define('OOS_SWF_BGCOLOUR_G_DESC', 'Groen waarde voor de kleine produktafbeelding in de flashfilm');

define('OOS_SWF_BGCOLOUR_B_TITLE', 'Achtergrond van Flashfilm B');
define('OOS_SWF_BGCOLOUR_B_DESC', 'Blauw waarde voor de kleine produktafbeelding in de flashfilm');

define('OOS_RANDOM_PICTURE_NAME_TITLE', 'Bestandsnaam');
define('OOS_RANDOM_PICTURE_NAME_DESC', 'Willekeurig samengestelde bestandsnaam voor het plaatje');

define('OOS_MO_PIC_TITLE', 'Meer produktafbeeldingen');
define('OOS_MO_PIC_DESC', 'Verdere produktafbeeldingen op de produktinfo pagina tonen?');

define('PSM_TITLE', 'Prijszoekmachine');
define('PSM_DESC', 'Wilt u het invoegpunt naar de prijszoekmachine gebruiken? Hiervoor is een aanmelding bij <A HREF=\"http://www.preissuchmaschine.de/psm_frontend/main.asp?content=mitmachenreissuchmaschine\" TARGET=\"_blank\">http://www.preissuchmaschine.de</A> nodig.');

define('OOS_PSM_DIR_TITLE', 'Map prijszoekmachine');
define('OOS_PSM_DIR_DESC', 'Het bestand voor prijszoekmachine moet in deze map opgeslagen worden.');

define('OOS_PSM_FILE_TITLE', 'Bestandsnaam');
define('OOS_PSM_FILE_DESC', 'Het bestand voor de prijszoekmachine');

define('OOS_META_TITLE_TITLE', 'Naam van de internetwinkel');
define('OOS_META_TITLE_DESC', 'De Kop');

define('OOS_META_DESCRIPTION_TITLE', 'Beschrijving');
define('OOS_META_DESCRIPTION_DESC', 'De beschrijving van uw winkel(max. 250 tekens)');

define('OOS_META_KEYWORDS_TITLE', 'Zoekwoorden');
define('OOS_META_KEYWORDS_DESC', 'voer hier uw zoekwoorden(door komma gescheiden) in(max. 250 tekens)');

define('OOS_META_PAGE_TOPIC_TITLE', 'Onderwerp');
define('OOS_META_PAGE_TOPIC_DESC', 'Het onderwerp va de winkel');

define('OOS_META_AUDIENCE_TITLE', 'Doelgroep');
define('OOS_META_AUDIENCE_DESC', 'Uw doelgroep');

define('OOS_META_AUTHOR_TITLE', 'Auteur');
define('OOS_META_AUTHOR_DESC', 'De auteur van de winkel');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'De bouwer van de winkel');

define('OOS_META_PAGE_TYPE_TITLE', 'Pagina soort');
define('OOS_META_PAGE_TYPE_DESC', 'Type van de paginainhoud');

define('OOS_META_PUBLISHER_TITLE', 'Uitgever');
define('OOS_META_PUBLISHER_DESC', 'De uitgever');

define('OOS_META_ROBOTS_TITLE', 'Indexering');
define('OOS_META_ROBOTS_DESC', 'Type indexering');

define('OOS_META_EXPIRES_TITLE', 'Geldigheidsduur');
define('OOS_META_EXPIRES_DESC', 'Aanbod vervalt op:( 0 voor vaak veranderde paginas)');

define('OOS_META_PAGE_PRAGMA_TITLE', 'Proxy Caching');
define('OOS_META_PAGE_PRAGMA_DESC', 'Uw winkel moet door proxis gebufferd worden?');

define('OOS_META_REVISIT_AFTER_TITLE', 'Opnieuw bezoeken na');
define('OOS_META_REVISIT_AFTER_DESC', 'Wanneer  moet de zoekmachine uw pagina weer bezoeken?');

define('OOS_META_PRODUKT_TITLE', 'Onderhouden in artikel');
define('OOS_META_PRODUKT_DESC', 'Wilt u zoekwoorden en beschrijving voor ieder artikel onderhouden?');

define('OOS_META_KATEGORIEN_TITLE', 'Zoekwoordbeheer in categorie&euml;n');
define('OOS_META_KATEGORIEN_DESC', 'Wilt u zoekwoorden en beschrijving voor iedere categorie beheren');

define('OOS_META_INDEX_PAGE_TITLE', 'Indexpagina genereren');
define('OOS_META_INDEX_PAGE_DESC', 'Wilt u een indexpagina met alle artikelen voor zoekmachines genereren?');

define('OOS_META_INDEX_PATH_TITLE', 'Map voor indexpagina');
define('OOS_META_INDEX_PATH_DESC', 'Het bestand voor de zoekmachines moet in deze winkelmap opgeslagen worden.');

define('ADMIN_CONFIG_KEYWORD_SHOW_TITLE', 'Zoekwoord tonen (Beheerder)');
define('ADMIN_CONFIG_KEYWORD_SHOW_DESC', 'Controleer zoekacties vanaf uw eigen IP adres? (elke zoekactie wordt getoond)');

define('OOS_CONFIG_KEYWORD_SHOW_TITLE', 'Zoekwoord tonen bezoekers');
define('OOS_CONFIG_KEYWORD_SHOW_DESC', 'Controleer de Klanten/Gasten zoekacties? (elke zoekactie wordt getoond)');

define('CONFIG_KEYWORD_SHOW_EXCLUDED_TITLE', 'Zoekwoord tonen (sluit dit IP-Adres uit)');
define('CONFIG_KEYWORD_SHOW_EXCLUDED_DESC', 'Uw IP adres, kan worden uitgesloten door de beheerder<br>(zoals webmaster/owners/Beta-testers)');

define('KEYWORD_SHOW_LOG_PATH_TITLE', 'Zoekwoord tonen (absolute pad naar uw logbestand)');
define('KEYWORD_SHOW_LOG_PATH_DESC', 'Plaats hier het absolutepad naar uw logfile, voeg de naam van de logfile er aan toe<br>(ongecomprimeerd of comprimeerd,.gz logfile)');

define('ENABLE_LINKS_COUNT_TITLE', 'Klikteller');
define('ENABLE_LINKS_COUNT_DESC', 'Schakel klikteller in.');

define('ENABLE_SPIDER_FRIENDLY_LINKS_TITLE', 'Spider vriendelijke links');
define('ENABLE_SPIDER_FRIENDLY_LINKS_DESC', 'Schakel spider vriendelijke links in (aangeraden).');

define('LINKS_IMAGE_WIDTH_TITLE', 'Linkafbeeldings breedte');
define('LINKS_IMAGE_WIDTH_DESC', 'Maximale breedte van de linkafbeelding.');

define('LINKS_IMAGE_HEIGHT_TITLE', 'Linkafbeeldings hoogte');
define('LINKS_IMAGE_HEIGHT_DESC', 'Maximale hoogte van de linkafbeelding.');

define('LINK_LIST_IMAGE_TITLE', 'Toon linkafbeelding');
define('LINK_LIST_IMAGE_DESC', 'Wilt u de linkafbeelding tonen?');

define('LINK_LIST_URL_TITLE', 'Toon link-URL');
define('LINK_LIST_URL_DESC', 'Wilt u de link URL tonen?');

define('LINK_LIST_TITLE_TITLE', 'Toon linktitel');
define('LINK_LIST_TITLE_DESC', 'Wilt u de link titel tonen?');

define('LINK_LIST_DESCRIPTION_TITLE', 'Toon linkbeschrijving');
define('LINK_LIST_DESCRIPTION_DESC', 'Wilt u de link beschrijving tonen?');

define('LINK_LIST_COUNT_TITLE', 'Toon linkklikteller');
define('LINK_LIST_COUNT_DESC', 'Wilt u de linkteller tonen?');

define('ENTRY_LINKS_TITLE_MIN_LENGTH_TITLE', 'Linktitel minimum lengte');
define('ENTRY_LINKS_TITLE_MIN_LENGTH_DESC', 'Minimale lengte van de linktitel.');

define('ENTRY_LINKS_URL_MIN_LENGTH_TITLE', 'Link-URL minimum lengte');
define('ENTRY_LINKS_URL_MIN_LENGTH_DESC', 'Minimale lengte van de link URL.');

define('ENTRY_LINKS_DESCRIPTION_MIN_LENGTH_TITLE', 'Linkbeschrijving minimum lengte');
define('ENTRY_LINKS_DESCRIPTION_MIN_LENGTH_DESC', 'Minimale lengte van de link beschrijving.');

define('ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH_TITLE', 'Linkcontactnaam minimum lengte');
define('ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH_DESC', 'Minimale lengte van de link contactnaam.');

define('LINKS_CHECK_PHRASE_TITLE', 'Zinsnede linktest');
define('LINKS_CHECK_PHRASE_DESC', 'Zinsnede om op te letten, wanneer je een linktest uitvoerd.');

define('DISPLAY_NEWSFEED_TITLE', 'Nieuwsmeldingen aanbieden');
define('DISPLAY_NEWSFEED_DESC', 'Wilt u uw klanten RDF/RSS nieuwsbrieven aanbieden?');

define('MULTIPLE_CATEGORIES_USE_TITLE', 'Gebruik meerdere categorie&euml;n');
define('MULTIPLE_CATEGORIES_USE_DESC', 'Staat op true of false om een produkt met een klik aan meerdere categorie&euml;n toe te voegen.');

define('OOS_SPAW_TITLE', 'SPAW PHP WYSIWYG Editor');
define('OOS_SPAW_DESC', 'SPAW PHP WYSIWYG bij de gegevensverwerking gebruiken?');

define('SLAVE_LIST_IMAGE_TITLE', 'Toon secundaire afbeelding');
define('SLAVE_LIST_IMAGE_DESC', 'Wilt u een afbeelding van het produkt tonen?');

define('SLAVE_LIST_MANUFACTURER_TITLE', 'Toon secundaire fabrikantnaam');
define('SLAVE_LIST_MANUFACTURER_DESC', 'Wilt u de fabrikantnaam tonen?');

define('SLAVE_LIST_MODEL_TITLE', 'Toon secundaire model');
define('SLAVE_LIST_MODEL_DESC', 'Wilt u het produktmodel tonen?');

define('SLAVE_LIST_NAME_TITLE', 'Toon secundaire naam');
define('SLAVE_LIST_NAME_DESC', 'Wilt u de produktnaam tonen?');

define('SLAVE_LIST_PRICE_TITLE', 'Toon secundaire prijs');
define('SLAVE_LIST_PRICE_DESC', 'Wilt u de produktprijs tonen');

define('SLAVE_LIST_QUANTITY_TITLE', 'Toon secundaire hoeveelheid');
define('SLAVE_LIST_QUANTITY_DESC', 'Wilt u de produkthoeveelheid tonen');

define('SLAVE_LIST_WEIGHT_TITLE', 'Toon secundaire gewicht');
define('SLAVE_LIST_WEIGHT_DESC', 'Wilt u het produktgewicht tonen?');

define('SLAVE_LIST_BUY_NOW_TITLE', 'Toon Koopnu');
define('SLAVE_LIST_BUY_NOW_DESC', 'Wilt de Koopnu regel tonen?');

define('RCS_BASE_DAYS_TITLE', 'Look back days');
define('RCS_BASE_DAYS_DESC', 'Number of days to look back from today for abandoned cards.');

define('RCS_REPORT_DAYS_TITLE', 'Sales Results Report days');
define('RCS_REPORT_DAYS_DESC', 'Number of days the sales results report takes into account. The more days the longer the SQL queries!.');

define('RCS_EMAIL_TTL_TITLE', 'E-Mail time to live');
define('RCS_EMAIL_TTL_DESC', 'Number of days to give for emails before they no longer show as being sent');

define('RCS_EMAIL_FRIENDLY_TITLE', 'Friendly E-Mails');
define('RCS_EMAIL_FRIENDLY_DESC', 'If <b>true</b> then the customer\'s name will be used in the greeting. If <b>false</b> then a generic greeting will be used.');

define('RCS_SHOW_ATTRIBUTES_TITLE', 'Show Attributes');
define('RCS_SHOW_ATTRIBUTES_DESC', 'Controls display of item attributes.<br><br>Some sites have attributes for their items.<br><br>Set this to <b>true</b> if yours does and you want to show them, otherwise set to <b>false</b>.');

define('RCS_CHECK_SESSIONS_TITLE', 'Ignore Customers with Sessions');
define('RCS_CHECK_SESSIONS_DESC', 'If you want the tool to ignore customers with an active session (ie, probably still shopping) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will operate in the default manner of ignoring session data &amp; using less resources');

define('RCS_CURCUST_COLOR_TITLE', 'Current Customer Hilight');
define('RCS_CURCUST_COLOR_DESC', 'Color for the word/phrase used to notate a current customer<br><br>A current customer is someone who has purchased items from your store in the past.');

define('RCS_UNCONTACTED_COLOR_TITLE', 'Uncontacted hilight Hilight');
define('RCS_UNCONTACTED_COLOR_DESC', 'Row highlight color for uncontacted customers.<br><br>An uncontacted customer is one that you have <i>not</i> used this tool to send an email to before.');

define('RCS_CONTACTED_COLOR_TITLE', 'Contacted hilight Hilight');
define('RCS_CONTACTED_COLOR_DESC', 'Row highlight color for contacted customers.<br><br>An contacted customer is one that you <i>have</i> used this tool to send an email to before.');

define('RCS_MATCHED_ORDER_COLOR_TITLE', 'Matching Order Hilight');
define('RCS_MATCHED_ORDER_COLOR_DESC', 'Row highlight color for entrees that may have a matching order.<br><br>An entry will be marked with this color if an order contains one or more of an item in the abandoned cart <b>and</b> matches either the cart\'s customer email address or database ID.');

define('RCS_SKIP_MATCHED_CARTS_TITLE', 'Skip Carts w/Matched Orders');
define('RCS_SKIP_MATCHED_CARTS_DESC', 'To ignore carts with an a matching order set this to <b>true</b>.<br><br>Setting this to <b>false</b> will cause entries with a matching order to show, along with the matching order\'s status.<br><br>See documentation for details.');

define('RCS_PENDING_SALE_STATUS_TITLE', 'Lowest Pending sales status');
define('RCS_PENDING_SALE_STATUS_DESC', 'The highest value that an order can have and still be considered pending. Any value higher than this will be considered by RCS as sale which completed.<br><br>See documentation for details.');

define('RCS_REPORT_EVEN_STYLE_TITLE', 'Report Even Row Style');
define('RCS_REPORT_EVEN_STYLE_DESC', 'Style for even rows in results report. Typical options are <i>dataTableRow</i> and <i>attributes-even</i>.');

define('RCS_REPORT_ODD_STYLE_TITLE', 'Report Odd Row Style');
define('RCS_REPORT_ODD_STYLE_DESC', 'Style for odd rows in results report. Typical options are NULL (ie, no entry) and <i>attributes-odd</i>.');

define('RCS_EMAIL_COPIES_TO_TITLE', 'E-Mail Copies to');
define('RCS_EMAIL_COPIES_TO_DESC', 'If you want copies of emails that are sent to customers by this contribution, enter the email address here. If empty no copies are sent');

define('RCS_AUTO_CHECK_TITLE', 'Autocheck "safe" carts to email');
define('RCS_AUTO_CHECK_DESC', 'To check entries which are most likely safe to email (ie, not existing customers, not previously emailed, etc.) set this to <b>true</b>.<br><br>Setting this to <b>false</b> will leave all entries unchecked (you will have to check each entry you want to send an email for).');

define('RCS_CARTS_MATCH_ALL_DATES_TITLE', 'Match orders from any date');
define('RCS_CARTS_MATCH_ALL_DATES_DESC', 'If <b>true</b> then any order found with a matching item will be considered a matched order.<br><br>If <b>false</b> only orders placed after the abandoned cart are considered.');
?>