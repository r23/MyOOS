<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php 442 2013-06-27 00:04:01Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Title');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Value');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');


define('STORE_NAME_TITLE', 'Store Name');
define('STORE_NAME_DESC', 'The name of my store');

define('STORE_OWNER_TITLE', 'Store Owner');
define('STORE_OWNER_DESC', 'The name of my store owner');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'E-Mail Address');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'The e-mail address of my store owner');

define('STORE_OWNER_VAT_ID_TITLE' , 'VAT ID of Shop Owner');
define('STORE_OWNER_VAT_ID_DESC' , 'The VAT ID of the Shop Owner');

define('STORE_ADDRESS_STREET_TITLE', 'Store Address: Street');
define('STORE_ADDRESS_STREET_DESC', 'This is the Street used on printable documents and displayed online');

define('STORE_ADDRESS_POSTCODE_TITLE', 'Store Address: Postcode');
define('STORE_ADDRESS_POSTCODE_DESC', 'This is the Postcode used on printable documents and displayed online');

define('STORE_ADDRESS_CITY_TITLE', 'Store Address: City');
define('STORE_ADDRESS_CITY_DESC', 'This is the City used on printable documents and displayed online');

define('STORE_ADDRESS_TELEPHONE_NUMBER_TITLE', 'Store Address: Phone');
define('STORE_ADDRESS_TELEPHONE_NUMBER_DESC', 'This is the Phone used on printable documents and displayed online');

define('STORE_ADDRESS_EMAIL_TITLE', 'Store Address: E-Mail Address');
define('STORE_ADDRESS_EMAIL_DESC', 'This is the e-mail address of my store ');

define('SKYPE_ME_TITLE', 'Skype-Name');
define('SKYPE_ME_DESC', 'If you don\'t have a Skype Name, please <a href=\"http://www.skype.com/go/download\" target=\"_blank\">download Skype</a> to create one, and visit this page again.');

define('STORE_COUNTRY_TITLE', 'Country');
define('STORE_COUNTRY_DESC', 'The country my store is located in <br><br><b>Note: Please remember to update the store zone.</b>');

define('STORE_ZONE_TITLE', 'Zone');
define('STORE_ZONE_DESC', 'The zone my store is located in');

define('EXPECTED_PRODUCTS_SORT_TITLE', 'Expected Sort Order');
define('EXPECTED_PRODUCTS_SORT_DESC', 'This is the sort order used in the expected products box.');

define('EXPECTED_PRODUCTS_FIELD_TITLE', 'Expected Sort Field');
define('EXPECTED_PRODUCTS_FIELD_DESC', 'The column to sort by in the expected products box.');

define('USE_DEFAULT_LANGUAGE_CURRENCY_TITLE', 'Switch To Default Language Currency');
define('USE_DEFAULT_LANGUAGE_CURRENCY_DESC', 'Automatically switch to the language\'s currency when it is changed');

define('ADVANCED_SEARCH_DEFAULT_OPERATOR_TITLE', 'Default Search Operator');
define('ADVANCED_SEARCH_DEFAULT_OPERATOR_DESC', 'Default search operators');

define('TAX_DECIMAL_PLACES_TITLE', 'Tax Decimal Places');
define('TAX_DECIMAL_PLACES_DESC', 'Pad the tax value this amount of decimal places');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Display Prices with Tax');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Display prices with tax included (true) or add the tax at the end (false)');

define('DISPLAY_CONDITIONS_ON_CHECKOUT_TITLE', 'Conditions on checkout');
define('DISPLAY_CONDITIONS_ON_CHECKOUT_DESC', 'Display the conditions on the checkout confirmation page before process.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sortierung Produktoptionen');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'M&ouml;chten Sie die Produktopionen nach Preisen sortieren?');

define('WEB_SEARCH_GOOGLE_KEY_TITLE', 'Google API license key');
define('WEB_SEARCH_GOOGLE_KEY_DESC', 'Google API license key (for free!) <A HREF=\"http://www.google.com/apis\" TARGET=\"_blank\">http://www.google.com/apis</A>.');

define('ENTRY_FIRST_NAME_MIN_LENGTH_TITLE', 'First Name');
define('ENTRY_FIRST_NAME_MIN_LENGTH_DESC', 'Minimum length of first name');

define('ENTRY_LAST_NAME_MIN_LENGTH_TITLE', 'Last Name');
define('ENTRY_LAST_NAME_MIN_LENGTH_DESC', 'Minimum length of last name');

define('ENTRY_DOB_MIN_LENGTH_TITLE', 'Date of Birth');
define('ENTRY_DOB_MIN_LENGTH_DESC', 'Minimum length of date of birth');

define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_TITLE', 'E-Mail Address');
define('ENTRY_EMAIL_ADDRESS_MIN_LENGTH_DESC', 'Minimum length of e-mail address');

define('ENTRY_STREET_ADDRESS_MIN_LENGTH_TITLE', 'Street Address');
define('ENTRY_STREET_ADDRESS_MIN_LENGTH_DESC', 'Minimum length of street address');

define('ENTRY_COMPANY_LENGTH_TITLE', 'Company');
define('ENTRY_COMPANY_LENGTH_DESC', 'Minimum length of company name');

define('ENTRY_POSTCODE_MIN_LENGTH_TITLE', 'Post Code');
define('ENTRY_POSTCODE_MIN_LENGTH_DESC', 'Minimum length of post code');

define('ENTRY_CITY_MIN_LENGTH_TITLE', 'City');
define('ENTRY_CITY_MIN_LENGTH_DESC', 'Minimum length of city');

define('ENTRY_STATE_MIN_LENGTH_TITLE', 'State');
define('ENTRY_STATE_MIN_LENGTH_DESC', 'Minimum length of state');

define('ENTRY_TELEPHONE_MIN_LENGTH_TITLE', 'Telephone Number');
define('ENTRY_TELEPHONE_MIN_LENGTH_DESC', 'Minimum length of telephone number');

define('ENTRY_PASSWORD_MIN_LENGTH_TITLE', 'Password');
define('ENTRY_PASSWORD_MIN_LENGTH_DESC', 'Minimum length of password');

define('CC_OWNER_MIN_LENGTH_TITLE', 'Credit Card Owner Name');
define('CC_OWNER_MIN_LENGTH_DESC', 'Minimum length of credit card owner name');

define('CC_NUMBER_MIN_LENGTH_TITLE', 'Credit Card Number');
define('CC_NUMBER_MIN_LENGTH_DESC', 'Minimum length of credit card number');

define('MIN_DISPLAY_BESTSELLERS_TITLE', 'Best Sellers');
define('MIN_DISPLAY_BESTSELLERS_DESC', 'Minimum number of best sellers to display');

define('MIN_DISPLAY_ALSO_PURCHASED_TITLE', 'Also Purchased');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC', 'Minimum number of products to display in the \'This Customer Also Purchased\' block');

define('MIN_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MIN_DISPLAY_XSELL_PRODUCTS_DESC', 'Minimum Anzahl von Produkten, die in der \'Produkt-Empfehlungen\' Anzeige angezeigt werden');

define('MIN_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Neue Produkte im Newsfeed');
define('MIN_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Minimum Anzahl von Produkten, die im \'Newsfeed\' angezeigt werden');

define('MIN_DISPLAY_NEW_NEWS_TITLE', 'News Meldungen');
define('MIN_DISPLAY_NEW_NEWS_DESC', 'Minimum Anzahl von Meldungen, die auf der \'Startseite\' angezeigt werden');

define('MAX_ADDRESS_BOOK_ENTRIES_TITLE', 'Address Book Entries');
define('MAX_ADDRESS_BOOK_ENTRIES_DESC', 'Maximum address book entries a customer is allowed to have');

define('MAX_DISPLAY_SEARCH_RESULTS_TITLE', 'Search Results');
define('MAX_DISPLAY_SEARCH_RESULTS_DESC', 'Amount of products to list');

define('MAX_DISPLAY_PAGE_LINKS_TITLE', 'Page Links');
define('MAX_DISPLAY_PAGE_LINKS_DESC', 'Number of \'number\' links use for page-sets');

define('MAX_DISPLAY_NEW_PRODUCTS_TITLE', 'New Products Module');
define('MAX_DISPLAY_NEW_PRODUCTS_DESC', 'Maximum number of new products to display in a category');

define('MAX_DISPLAY_UPCOMING_PRODUCTS_TITLE', 'Products Expected');
define('MAX_DISPLAY_UPCOMING_PRODUCTS_DESC', 'Maximum number of products expected to display');

define('MAX_RANDOM_SELECT_NEW_TITLE', 'Selection of Random New Products');
define('MAX_RANDOM_SELECT_NEW_DESC', 'How many records to select from to choose one random new product to display');

define('MAX_DISPLAY_CATEGORIES_PER_ROW_TITLE', 'Categories To List Per Row');
define('MAX_DISPLAY_CATEGORIES_PER_ROW_DESC', 'How many categories to list per row');

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'New Products Listing');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Maximum number of new products to display in new products page');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Best Sellers');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Maximum number of best sellers to display');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Also Purchased');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximum number of products to display in the \'This Customer Also Purchased\' block');

define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_TITLE', 'Customer Order History-Block');
define('MAX_DISPLAY_PRODUCTS_IN_ORDER_HISTORY_BOX_DESC', 'Maximum number of products to display in the customer order history block');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Order History');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximum number of orders to display in the order history page');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Maximale Anzahl von Produkten, die im \'Produkt-Empfehlungen\'-Block angezeigt werden');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Wunschzettel');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Maximale Produkte auf der Wunschzettel-Seite');

define('MAX_DISPLAY_WISHLIST_BOX_TITLE', 'Wunschzettel-Infobox');
define('MAX_DISPLAY_WISHLIST_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Wunschzettel\'-Block angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_NEWSFEED_TITLE', 'Neue Produkte im Newsfeed');
define('MAX_DISPLAY_PRODUCTS_NEWSFEED_DESC', 'Maximale Anzahl von Produkten, die im \'Newsfeed\' angezeigt werden');

define('MAX_RANDOM_SELECT_NEWSFEED_TITLE', 'Newsfeed');
define('MAX_RANDOM_SELECT_NEWSFEED_DESC', 'Die Menge der Newsfeeds, aus denen per Zufall ein Newsfeed angezeigt wird');

define('MAX_DISPLAY_NEW_NEWS_TITLE', 'Anzahl der News Meldungen');
define('MAX_DISPLAY_NEW_NEWS_DESC', 'Maximale Anzahl von Meldungen, die auf der Startseite angezeigt werden');

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Anzahl der Products History');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Products History\'-Block angezeigt werden');

define('SMALL_IMAGE_WIDTH_TITLE', 'Small Image Width');
define('SMALL_IMAGE_WIDTH_DESC', 'The pixel width of small images');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Small Image Height');
define('SMALL_IMAGE_HEIGHT_DESC', 'The pixel height of small images');

define('HEADING_IMAGE_WIDTH_TITLE', 'Heading Image Width');
define('HEADING_IMAGE_WIDTH_DESC', 'The pixel width of heading images');

define('HEADING_IMAGE_HEIGHT_TITLE', 'Heading Image Height');
define('HEADING_IMAGE_HEIGHT_DESC', 'The pixel height of heading images');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Subcategory Image Width');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'The pixel width of subcategory images');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Subcategory Image Height');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'The pixel height of subcategory images');

define('CONFIG_CALCULATE_IMAGE_SIZE_TITLE', 'Calculate Image Size');
define('CONFIG_CALCULATE_IMAGE_SIZE_DESC', 'Calculate the size of images?');

define('IMAGE_REQUIRED_TITLE', 'Image Required');
define('IMAGE_REQUIRED_DESC', 'Enable to display broken images. Good for development.');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Zugangsberechtigung');
define('CUSTOMER_NOT_LOGIN_DESC', 'Die Zugangsberechtigung wird durch den Administrator nach Prüfung der Kundendaten erteilt');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Kundendaten per Mail');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'Die Kundendaten werden an den Shopbetreiber per eMail gesandt');

define('DEFAULT_MAX_ORDER_TITLE', 'Kundenkredit');
define('DEFAULT_MAX_ORDER_DESC', 'maximaler Wert einer Bestellung');

define('ACCOUNT_GENDER_TITLE', 'Anrede');
define('ACCOUNT_GENDER_DESC', 'Die Anrede wird angezeigt');

define('ACCOUNT_DOB_TITLE', 'Geburtsdatum');
define('ACCOUNT_DOB_DESC', 'Das Gebutsdatum wird zwingend gefordert');

define('ACCOUNT_NUMBER_TITLE', 'Kundennummer');
define('ACCOUNT_NUMBER_DESC', 'Verwaltung von eigenen Kundenummern');

define('ACCOUNT_COMPANY_TITLE', 'Firmenname');
define('ACCOUNT_COMPANY_DESC', 'Firmenname wird angezeigt');

define('ACCOUNT_OWNER_TITLE', 'Inhaber');
define('ACCOUNT_OWNER_DESC', 'Inhaber der Firmen wird angezeigt');

define('ACCOUNT_VAT_ID_TITLE', 'Umsatzsteuer ID');
define('ACCOUNT_VAT_ID_DESC', 'Die Umsatzsteuer ID bei gewerblichen Kunden kann eingegeben werden.');


define('ACCOUNT_SUBURB_TITLE', 'Stadtteil');
define('ACCOUNT_SUBURB_DESC', 'Stadtteil wird angezeigt');

define('ACCOUNT_STATE_TITLE', 'Bundesland');
define('ACCOUNT_STATE_DESC', 'Bundesland wird angezeigt');

define('STORE_ORIGIN_COUNTRY_TITLE', 'Country Code');
define('STORE_ORIGIN_COUNTRY_DESC', 'Enter the &quot;ISO 3166&quot; Country Code of the Store to be used in shipping quotes.  To find your country code, visit the <A HREF=\"http://www.din.de/gremien/nas/nabd/iso3166ma/codlstp1/index.html\" TARGET=\"_blank\">ISO 3166 Maintenance Agency</A>.');

define('STORE_ORIGIN_ZIP_TITLE', 'Postal Code');
define('STORE_ORIGIN_ZIP_DESC', 'Enter the Postal Code (ZIP) of the Store to be used in shipping quotes.');

define('SHIPPING_MAX_WEIGHT_TITLE', 'Enter the Maximum Package Weight you will ship');
define('SHIPPING_MAX_WEIGHT_DESC', 'Carriers have a max weight limit for a single package. This is a common one for all.');

define('SHIPPING_BOX_WEIGHT_TITLE', 'Package Tare weight.');
define('SHIPPING_BOX_WEIGHT_DESC', 'What is the weight of typical packaging of small to medium packages?');

define('SHIPPING_BOX_PADDING_TITLE', 'Larger packages - percentage increase.');
define('SHIPPING_BOX_PADDING_DESC', 'For 10% enter 10');

define('PRODUCT_LIST_IMAGE_TITLE', 'Display Product Image');
define('PRODUCT_LIST_IMAGE_DESC', 'Do you want to display the Product Image?');

define('PRODUCT_LIST_MANUFACTURER_TITLE', 'Display Product Manufaturer Name');
define('PRODUCT_LIST_MANUFACTURER_DESC', 'Do you want to display the Product Manufacturer Name?');

define('PRODUCT_LIST_MODEL_TITLE', 'Display Product Model');
define('PRODUCT_LIST_MODEL_DESC', 'Do you want to display the Product Model?');

define('PRODUCT_LIST_NAME_TITLE', 'Display Product Name');
define('PRODUCT_LIST_NAME_DESC', 'Do you want to display the Product Name?');

define('PRODUCT_LIST_UVP_TITLE', 'Display Product List Price');
define('PRODUCT_LIST_UVP_DESC', 'Do you want to display the Product List Price?');

define('PRODUCT_LIST_PRICE_TITLE', 'Display Product Price');
define('PRODUCT_LIST_PRICE_DESC', 'Do you want to display the Product Price?');

define('PRODUCT_LIST_QUANTITY_TITLE', 'Display Product Quantity');
define('PRODUCT_LIST_QUANTITY_DESC', 'Do you want to display the Product Quantity?');

define('PRODUCT_LIST_WEIGHT_TITLE', 'Display Product Weight');
define('PRODUCT_LIST_WEIGHT_DESC', 'Do you want to display the Product Weight?');

define('PRODUCT_LIST_BUY_NOW_TITLE', 'Display Buy Now column');
define('PRODUCT_LIST_BUY_NOW_DESC', 'Do you want to display the Buy Now column?');

define('PRODUCT_LIST_FILTER_TITLE', 'Display Category/Manufacturer Filter (0=disable; 1=enable)');
define('PRODUCT_LIST_FILTER_DESC', 'Do you want to display the Category/Manufacturer Filter?');

define('PRODUCT_LIST_SORT_ORDER_TITLE', 'Display Product Sort Order');
define('PRODUCT_LIST_SORT_ORDER_DESC', 'Do you want to display the Product Sort Order column?');

define('PREV_NEXT_BAR_LOCATION_TITLE', 'Location of Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)');
define('PREV_NEXT_BAR_LOCATION_DESC', 'Sets the location of the Prev/Next Navigation Bar (1-top, 2-bottom, 3-both)');

define('STOCK_CHECK_TITLE', 'Check stock level');
define('STOCK_CHECK_DESC', 'Check to see if sufficent stock is available');

define('STOCK_LIMITED_TITLE', 'Subtract stock');
define('STOCK_LIMITED_DESC', 'Subtract product in stock by product orders');

define('STOCK_ALLOW_CHECKOUT_TITLE', 'Allow Checkout');
define('STOCK_ALLOW_CHECKOUT_DESC', 'Allow customer to checkout even if there is insufficient stock');

define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_TITLE', 'Mark product out of stock');
define('STOCK_MARK_PRODUCT_OUT_OF_STOCK_DESC', 'Display something on screen so customer can see which product has insufficient stock');

define('STOCK_REORDER_LEVEL_TITLE', 'Stock Re-order level');
define('STOCK_REORDER_LEVEL_DESC', 'Define when stock needs to be re-ordered');

define('USE_CACHE_TITLE', 'Use Cache');
define('USE_CACHE_DESC', 'Use caching features');

define('DOWNLOAD_ENABLED_TITLE', 'Enable download');
define('DOWNLOAD_ENABLED_DESC', 'Enable the products download functions.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download by redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Use browser redirection for download. Disable on non-Unix systems.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Expiry delay (days)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Set number of days before the download link expires. 0 means no limit.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximum number of downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Set the maximum number of downloads. 0 means no download authorized.');

define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_TITLE', 'Downloads Controller Update Status Value');
define('DOWNLOADS_ORDERS_STATUS_UPDATED_VALUE_DESC', 'What orders_status resets the Download days and Max Downloads - Default is 4');

define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_TITLE', 'Downloads Controller Download on hold message');
define('DOWNLOADS_CONTROLLER_ON_HOLD_MSG_DESC', 'Downloads Controller Download on hold message');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads Controller Order Status Value');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloads Controller Order Status Value - Default=2');

define('PDF_DATA_SHEET_TITLE', 'Enable PDF Data Sheet');
define('PDF_DATA_SHEET_DESC', 'M&ouml;chten Sie die Produktinformationen als PDF-Datei zum download anbieten?');

define('HEADER_COLOR_TABLE_TITLE', 'Farbe: Prospektkopf-Tabelle');
define('HEADER_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('PRODUCT_NAME_COLOR_TABLE_TITLE', 'Farbe: Produkname-Tabelle');
define('PRODUCT_NAME_COLOR_TABLE_DESC', 'Farbe in R, G, B, Werten (Beispiel: 230,230,230)');

define('FOOTER_CELL_BG_COLOR_TITLE', 'Hintergundfarbe: Prospektfuss');
define('FOOTER_CELL_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 210,210,210)');

define('SHOW_BACKGROUND_TITLE', 'Hintergrund');
define('SHOW_BACKGROUND_DESC', 'M&ouml;chten Sie die Hintergrundfarbe angezeigen?');

define('PAGE_BG_COLOR_TITLE', 'Hintergundfarbe: Prospekt');
define('PAGE_BG_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 250,250,250)');

define('SHOW_WATERMARK_TITLE', 'Wasserzeichen');
define('SHOW_WATERMARK_DESC', 'M&ouml;chten Sie Ihren Firmenname als Wasserzeichen angezeigen?');

define('PAGE_WATERMARK_COLOR_TITLE', 'Wasserzeichenfarbe');
define('PAGE_WATERMARK_COLOR_DESC', 'Farbe in R, G, B, Werten (Beispiel: 236,245,255)');

define('PDF_IMAGE_KEEP_PROPORTIONS_TITLE', 'Produktbilder');
define('PDF_IMAGE_KEEP_PROPORTIONS_DESC', 'M&ouml;chten Sie die maximale bzw. minimale Produktgr&ouml;sse verwenden?');

define('MAX_IMAGE_WIDTH_TITLE', 'Breite der Produktbilder');
define('MAX_IMAGE_WIDTH_DESC', 'max. Breite in mm der Produktbilder');

define('MAX_IMAGE_HEIGHT_TITLE', 'H&ouml;he der Produktbilder');
define('MAX_IMAGE_HEIGHT_DESC', 'max. H&ouml;he in mm der Produktbilder');

define('PDF_TO_MM_FACTOR_TITLE', 'Faktor');
define('PDF_TO_MM_FACTOR_DESC', 'Produktbilder');

define('SHOW_PATH_TITLE', 'Kategoriename');
define('SHOW_PATH_DESC', 'M&ouml;chten Sie den Kategorienamen anzeigen?');

define('SHOW_IMAGES_TITLE', 'Produktbild');
define('SHOW_IMAGES_DESC', 'M&ouml;chten Sie das Produktbild anzeigen?');

define('SHOW_NAME_TITLE', 'Produktname');
define('SHOW_NAME_DESC', 'M&ouml;chten Sie den Produktnamen in der Beschreibung anzeigen?');

define('SHOW_MODEL_TITLE', 'Bestellnummer');
define('SHOW_MODEL_DESC', 'M&ouml;chten Sie die Bestellnummer anzeigen?');

define('SHOW_DESCRIPTION_TITLE', 'Produktbeschreibung');
define('SHOW_DESCRIPTION_DESC', 'M&ouml;chten Sie die Produktbeschreibung anzeigen?');

define('SHOW_MANUFACTURER_TITLE', 'Hersteller');
define('SHOW_MANUFACTURER_DESC', 'M&ouml;chten Sie den Hersteller anzeigen?');

define('SHOW_PRICE_TITLE', 'Produktpreis');
define('SHOW_PRICE_DESC', 'M&ouml;chten Sie den Produktpreis anzeigen?');

define('SHOW_SPECIALS_PRICE_TITLE', 'Sonderangebote');
define('SHOW_SPECIALS_PRICE_DESC', 'M&ouml;chten Sie den Angebotspreis anzeigen?');

define('SHOW_SPECIALS_PRICE_EXPIRES_TITLE', 'Datum Sonderangebote');
define('SHOW_SPECIALS_PRICE_EXPIRES_DESC', 'M&ouml;chten Sie das Gültigkeitsdatum der Angebotspreise anzeigen?');

define('SHOW_TAX_CLASS_ID_TITLE', 'Steuersatz');
define('SHOW_TAX_CLASS_ID_DESC', 'M&ouml;chten Sie den Steuersatz anzeigen?');

define('SHOW_OPTIONS_TITLE', 'Produktoptionen');
define('SHOW_OPTIONS_DESC', 'M&ouml;chten Sie die Produktoptionen anzeigen?');

define('SHOW_OPTIONS_PRICE_TITLE', 'Preis der Produktoptionen');
define('SHOW_OPTIONS_PRICE_DESC', 'M&ouml;chten Sie die Preise der Produktoptionen anzeigen?');

define('TICKET_ENTRIES_MIN_LENGTH_TITLE', 'Supporttickets');
define('TICKET_ENTRIES_MIN_LENGTH_DESC', 'Die mindest Zeichen der Supporttickets');

define('TICKET_ADMIN_NAME_TITLE', 'Ticket Admin Name');
define('TICKET_ADMIN_NAME_DESC', 'The name of Admin');

define('TICKET_USE_STATUS_TITLE', 'Statusanzeige im Shop');
define('TICKET_USE_STATUS_DESC', 'M&ouml;chten Sie den Supportticketstatus anzeigen?');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_TITLE', 'Allow customer');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_STATUS_DESC', 'Allow customer to change status when replying');

define('TICKET_USE_DEPARTMENT_TITLE', 'Use Department');
define('TICKET_USE_DEPARTMENT_DESC', 'Use Department in Catalog');

define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_TITLE', 'Department');
define('TICKET_ALLOW_CUSTOMER_TO_CHANGE_DEPARTMENT_DESC', 'Allow customer to change status when replying');

define('TICKET_USE_PRIORITY_TITLE', 'Use Priority');
define('TICKET_USE_PRIORITY_DESC', 'Use Priority in Catalog');

define('TICKET_USE_ORDER_IDS_TITLE', 'Order Id');
define('TICKET_USE_ORDER_IDS_DESC', 'If customer is logged in, his orderid \'s are shown');

define('TICKET_USE_SUBJECT_TITLE', 'Show Subject');
define('TICKET_USE_SUBJECT_DESC', 'Show Subject');

define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_TITLE', 'Login');
define('TICKET_CHANGE_CUSTOMER_LOGIN_REQUIREMENT_DESC', 'if you set this to true you can allow - notallow registered customers to view tickets without beeing logged in');

define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_TITLE', 'Shop - Login');
define('TICKET_CUSTOMER_LOGIN_REQUIREMENT_DEFAULT_DESC', '0 registered Customer must not be logged in to view ticket<br>1 registered Customer must  be logged in to view ticket');

define('SECURITY_CODE_LENGTH_TITLE', 'Redeem Code');
define('SECURITY_CODE_LENGTH_DESC', 'Set the length of the redeem code, the longer the more secure');

define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_TITLE', 'Neukunden Gutschein');
define('NEW_SIGNUP_GIFT_VOUCHER_AMOUNT_DESC', 'Determines the amount of the rebate which the new customer will receive. Leave the field empty when the new customer will not be receiving a \'Welcome Gift\'.');

define('NEW_SIGNUP_DISCOUNT_COUPON_TITLE', 'Coupon ID');
define('NEW_SIGNUP_DISCOUNT_COUPON_DESC', 'Set the coupon ID that will be sent by email to a new signup, if no id is set then no email');

define('STORE_TEMPLATES_TITLE', 'Layout Vorlage');
define('STORE_TEMPLATES_DESC', 'Shop Templates');

define('SHOW_DATE_ADDED_AVAILABLE_TITLE', 'Produkt - Datum');
define('SHOW_DATE_ADDED_AVAILABLE_DESC', 'M&ouml;chten Sie im Shop das Datum von der Aufnahme des Produktes zeigen?');

define('SHOW_COUNTS_TITLE', 'Artikelanzahl in den jeweiligen Kategorien');
define('SHOW_COUNTS_DESC', 'Anzeigen, wieviele Produkte in jeder Kategorie vorhanden sind');

define('CATEGORIES_SCROLL_BOX_LEN_TITLE', 'Kategorie-Menge');
define('CATEGORIES_SCROLL_BOX_LEN_DESC', 'Wenn Sie die Kategorien als Auswahlliste anzeigen wollen, legen Sie hier die Länge fest');

define('SHOPPING_CART_IMAGE_ON_TITLE', 'Bild im Warenkorbinhalt');
define('SHOPPING_CART_IMAGE_ON_DESC', 'M&ouml;chten Sie in der Detailansicht vom Warenkorb das Porduktbild anzeigen?');

define('SHOPPING_CART_MINI_IMAGE_TITLE', 'Bildverkleinerung');
define('SHOPPING_CART_MINI_IMAGE_DESC', 'Wert für die Verkleinerung in der Detailansicht vom Warenkorb');

define('DISPLAY_CART_TITLE', 'Display Cart After Adding Product');
define('DISPLAY_CART_DESC', 'Display the shopping cart after adding a product (or return back to their origin)');

define('ALLOW_GUEST_TO_TELL_A_FRIEND_TITLE', 'Allow Guest To Tell A Friend');
define('ALLOW_GUEST_TO_TELL_A_FRIEND_DESC', 'Allow guests to tell a friend about a product');

define('ALLOW_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube Kategorienbeschreibung');
define('ALLOW_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausführliche Beschreibung der einzelnen Kategorien');

define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_TITLE', 'Erlaube News-Kategorienbeschreibung');
define('ALLOW_NEWS_CATEGORY_DESCRIPTIONS_DESC', 'Erlaubt eine ausführliche Beschreibung der einzelnen News-Kategorien');

define('SHOW_PRODUCTS_MODEL_TITLE', 'Navigation mit Bestellummer');
define('SHOW_PRODUCTS_MODEL_DESC', 'M&ouml;chten Sie die auf der Produkt-Informations-Seite die Bestellnummer in der Navation anzeigen?');

define('BREADCRUMB_SEPARATOR_TITLE', 'breadcrumb separator');
define('BREADCRUMB_SEPARATOR_DESC', 'Breadcrumb separator');

define('BLOCK_BEST_SELLERS_IMAGE_TITLE', 'Bild im Block Verkaufschlager');
define('BLOCK_BEST_SELLERS_IMAGE_DESC', 'Bild im Content-Block Verkaufschlager anzeigen?');

define('BLOCK_PRODUCTS_HISTORY_IMAGE_TITLE', 'Bild im Block gekaufte Produkte');
define('BLOCK_PRODUCTS_HISTORY_IMAGE_DESC', 'Bild im Content-Block gekaufte Produkte anzeigen?');

define('BLOCK_WISHLIST_IMAGE_TITLE', 'Bild im Block Wunschliste');
define('BLOCK_WISHLIST_IMAGE_DESC', 'Bild im Content-Block Wunschliste anzeigen?');

define('BLOCK_XSELL_PRODUCTS_IMAGE_TITLE', 'Bild im Block �nliche Produkte');
define('BLOCK_XSELL_PRODUCTS_IMAGE_DESC', 'Bild im Content-Block �nliche Produkte anzeigen?');

define('OOS_SMALLIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung kleines Bild');
define('OOS_SMALLIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder H&ouml;he ist die maximale Gr&ouml;ße<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  berücksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Small Image Width');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'The pixel width of small images');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Small Image Height');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'The pixel height of small images');

define('OOS_IMAGE_BGCOLOUR_R_TITLE', 'Hintergrund kleines Bild R');
define('OOS_IMAGE_BGCOLOUR_R_DESC', 'Rot Wert für kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_G_TITLE', 'Hintergrund kleines Bild G');
define('OOS_IMAGE_BGCOLOUR_G_DESC', 'Grün Wert für kleines Produktbild');

define('OOS_IMAGE_BGCOLOUR_B_TITLE', 'Hintergrund kleines Bild B');
define('OOS_IMAGE_BGCOLOUR_B_DESC', 'Blau Wert für kleines Produktbild');

define('OOS_BIGIMAGE_WAY_OF_RESIZE_TITLE', 'Bildbearbeitung grosses Bild');
define('OOS_BIGIMAGE_WAY_OF_RESIZE_DESC', '0: proportionale Verkleinerung; Breite oder H&ouml;he ist die maximale Gr&ouml;ße<br> 1: Bild wird proportional in das neue Bild kopiert. Die Hintergrundfarbe wird  berücksichtigt.<br> 2: ein Ausschnitt wird in das neue Bild kopiert');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Breite grosses Bild');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Breite vom grossen Bild in Pixel');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'H&ouml;he grosses Bild');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'H&ouml;he vom grossen Bild in Pixel');

define('OOS_WATERMARK_TITLE', 'Wasserzeichen');
define('OOS_WATERMARK_DESC', 'M&ouml;chten Sie im grossen Bild ein Wasserzeichen einfügen?');

define('OOS_WATERMARK_QUALITY_TITLE', 'Qualität vom Wasserzeichen');
define('OOS_WATERMARK_QUALITY_DESC', 'Hier legen Sie die Qualität vom Wasserzeichen fest');

define('PSM_TITLE', 'Preissuchmaschine');
define('PSM_DESC', 'M&ouml;chten Sie Die Schnittstelle zur Preissuchmaschine verwenden? Hierfür ist eine Anmeldung bei <A HREF=\"http://www.preissuchmaschine.de/psm_frontend/main.asp?content=mitmachenreissuchmaschine\" TARGET=\"_blank\">http://www.preissuchmaschine.de</A> n');

define('OOS_PSM_DIR_TITLE', 'Verzeichnis Preissuchmaschine');
define('OOS_PSM_DIR_DESC', 'Die Datei für die Preissuchmaschine soll in diesem Shop-Verzeichnis gespeichert werden.');

define('OOS_PSM_FILE_TITLE', 'Dateiname');
define('OOS_PSM_FILE_DESC', 'Die Datei für die Preissuchmaschine');

define('OOS_META_TITLE_TITLE', 'Shop Titel');
define('OOS_META_TITLE_DESC', 'Der Titel');

define('OOS_META_DESCRIPTION_TITLE', 'Beschreibung');
define('OOS_META_DESCRIPTION_DESC', 'Die Beschreibung Ihres Shop(max. 250 Zeichen)');

define('OOS_META_AUTHOR_TITLE', 'Autor');
define('OOS_META_AUTHOR_DESC', 'Der Autor des Shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');


define('MULTIPLE_CATEGORIES_USE_TITLE', 'Use Multiple Categories');
define('MULTIPLE_CATEGORIES_USE_DESC', 'Set to true or false in order to add product to multiple categories with one click.');

define('OOS_SPAW_TITLE', 'SPAW PHP WYSIWYG Editor');
define('OOS_SPAW_DESC', 'SPAW PHP WYSIWYG bei der Datenerfassung verwenden?');

define('SLAVE_LIST_IMAGE_TITLE', 'Display Slave Image');
define('SLAVE_LIST_IMAGE_DESC', 'Do you want to display the Product Image?');

define('SLAVE_LIST_MANUFACTURER_TITLE', 'Display Slave Manufacturer Name');
define('SLAVE_LIST_MANUFACTURER_DESC', 'Do you want to display the Product Manufacturer Name?');

define('SLAVE_LIST_MODEL_TITLE', 'Display Slave Model');
define('SLAVE_LIST_MODEL_DESC', 'Do you want to display the Product Model?');

define('SLAVE_LIST_NAME_TITLE', 'Display Slave Name');
define('SLAVE_LIST_NAME_DESC', 'Do you want to display the Product Name?');

define('SLAVE_LIST_PRICE_TITLE', 'Display Slave Price');
define('SLAVE_LIST_PRICE_DESC', 'Do you want to display the Product Price');

define('SLAVE_LIST_QUANTITY_TITLE', 'Display Slave Quantity');
define('SLAVE_LIST_QUANTITY_DESC', 'Do you want to display the Product Quantity?');

define('SLAVE_LIST_WEIGHT_TITLE', 'Display Slave Weight');
define('SLAVE_LIST_WEIGHT_DESC', 'Do you want to display the Product Weight?');

define('SLAVE_LIST_BUY_NOW_TITLE', 'Display Buy Now column');
define('SLAVE_LIST_BUY_NOW_DESC', 'Do you want to display the Buy Now column?');

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
