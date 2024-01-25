<?php
/**
   ----------------------------------------------------------------------
   $Id: configuration.php,v 1.4 2008/06/04 14:41:37 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.7 2002/01/04 03:51:40 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */

define('TABLE_HEADING_CONFIGURATION_TITLE', 'Title');
define('TABLE_HEADING_CONFIGURATION_VALUE', 'Value');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_EDIT_INTRO', 'Please make any necessary changes');
define('TEXT_INFO_DATE_ADDED', 'Date Added:');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');

define('STORE_NAME_TITLE', 'Store Name');
define('STORE_NAME_DESC', 'The name of my store');

define('STORE_LOGO_TITLE', 'Logo');
define('STORE_LOGO_DESC', 'The logo of my shop');

define('STORE_OWNER_TITLE', 'Store Owner');
define('STORE_OWNER_DESC', 'The name of my store owner');

define('STORE_OWNER_EMAIL_ADDRESS_TITLE', 'E-Mail Address');
define('STORE_OWNER_EMAIL_ADDRESS_DESC', 'The e-mail address of my store owner');

define('STORE_OWNER_VAT_ID_TITLE', 'VAT ID of Shop Owner');
define('STORE_OWNER_VAT_ID_DESC', 'The VAT ID of the Shop Owner');

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

define('STORE_NAME_ADDRESS_TITLE', 'Store Address and Phone');
define('STORE_NAME_ADDRESS_DESC', 'This is the Store Name, Address and Phone used on printable documents and displayed online');

define('TAX_DECIMAL_PLACES_TITLE', 'Tax Decimal Places');
define('TAX_DECIMAL_PLACES_DESC', 'Pad the tax value this amount of decimal places');

define('DISPLAY_PRICE_WITH_TAX_TITLE', 'Display Prices with Tax');
define('DISPLAY_PRICE_WITH_TAX_DESC', 'Display prices with tax included (true) or add the tax at the end (false)');

define('BASE_PRICE_TITLE', 'Use base price');
define('BASE_PRICE_DESC', 'Would you like to use products with a basic price?');

define('TAKE_BACK_OBLIGATION_TITLE', 'Rücknahmepflicht für Elektroaltgeräte verwenden');
define('TAKE_BACK_OBLIGATION_DESC', 'Sind Sie verpflichtet, bei bestimmten Geräten, Altgeräte bei Ablieferung des Neugerätes gleich mitzunehmen?');

define('OFFER_B_WARE_TITLE', 'B-goods / used goods');
define('OFFER_B_WARE_DESC', 'Do you offer B-goods / used goods in your online store?');

define('MINIMUM_ORDER_VALUE_TITLE', 'Use minimum order value');
define('MINIMUM_ORDER_VALUE_DESC', 'Set a minimum order value. A number without currency.');

define('PRODUCTS_OPTIONS_SORT_BY_PRICE_TITLE', 'Sorting product options');
define('PRODUCTS_OPTIONS_SORT_BY_PRICE_DESC', 'Would you like to sort the product opions according to prices?');

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

define('MIN_DISPLAY_BESTSELLERS_TITLE', 'Best Sellers');
define('MIN_DISPLAY_BESTSELLERS_DESC', 'Minimum number of best sellers to display');

define('MIN_DISPLAY_ALSO_PURCHASED_TITLE', 'Also Purchased');
define('MIN_DISPLAY_ALSO_PURCHASED_DESC', 'Minimum number of products to display in the \'This Customer Also Purchased\' block');

define('MIN_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MIN_DISPLAY_XSELL_PRODUCTS_DESC', 'Minimum Anzahl von Produkten, die in der \'Produkt-Empfehlungen\' Anzeige angezeigt werden');

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

define('MAX_DISPLAY_PRODUCTS_NEW_TITLE', 'New Products Listing');
define('MAX_DISPLAY_PRODUCTS_NEW_DESC', 'Maximum number of new products to display in new products page');

define('MAX_DISPLAY_BESTSELLERS_TITLE', 'Best Sellers');
define('MAX_DISPLAY_BESTSELLERS_DESC', 'Maximum number of best sellers to display');

define('MAX_DISPLAY_ALSO_PURCHASED_TITLE', 'Also Purchased');
define('MAX_DISPLAY_ALSO_PURCHASED_DESC', 'Maximum number of products to display in the \'This Customer Also Purchased\' block');

define('MAX_DISPLAY_ORDER_HISTORY_TITLE', 'Order History');
define('MAX_DISPLAY_ORDER_HISTORY_DESC', 'Maximum number of orders to display in the order history page');

define('MAX_DISPLAY_XSELL_PRODUCTS_TITLE', 'Produkt-Empfehlungen');
define('MAX_DISPLAY_XSELL_PRODUCTS_DESC', 'Maximale Anzahl von Produkten, die im \'Produkt-Empfehlungen\'-Block angezeigt werden');

define('MAX_DISPLAY_WISHLIST_PRODUCTS_TITLE', 'Wunschzettel');
define('MAX_DISPLAY_WISHLIST_PRODUCTS_DESC', 'Maximale Produkte auf der Wunschzettel-Seite');

define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_TITLE', 'Anzahl der Products History');
define('MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX_DESC', 'Maximale Anzahl von Produkten, die im \'Products History\'-Block angezeigt werden');

define('SMALL_IMAGE_WIDTH_TITLE', 'Small Image Width');
define('SMALL_IMAGE_WIDTH_DESC', 'The pixel width of small images');

define('SMALL_IMAGE_HEIGHT_TITLE', 'Small Image Height');
define('SMALL_IMAGE_HEIGHT_DESC', 'The pixel height of small images');

define('SUBCATEGORY_IMAGE_WIDTH_TITLE', 'Subcategory Image Width');
define('SUBCATEGORY_IMAGE_WIDTH_DESC', 'The pixel width of subcategory images');

define('SUBCATEGORY_IMAGE_HEIGHT_TITLE', 'Subcategory Image Height');
define('SUBCATEGORY_IMAGE_HEIGHT_DESC', 'The pixel height of subcategory images');

define('IMAGE_ZOOM_TITLE', 'Enlarge image on product info pagee');
define('IMAGE_ZOOM_DESC', 'Enlarge image on:');

define('ZOOM_BUTTON_TITLE', 'Position of the \'Zoom In\' button');
define('ZOOM_BUTTON_DESC', 'The button position on the image of the product info page');

define('CUSTOMER_NOT_LOGIN_TITLE', 'Access authorisation');
define('CUSTOMER_NOT_LOGIN_DESC', 'The access authorization is granted by the administrator after checking the customer data.');

define('SEND_CUSTOMER_EDIT_EMAILS_TITLE', 'Customer data by mail');
define('SEND_CUSTOMER_EDIT_EMAILS_DESC', 'The customer data are sent to the Shopbetreiber by email');

define('DEFAULT_MAX_ORDER_TITLE', 'Customer credit');
define('DEFAULT_MAX_ORDER_DESC', 'Maximum value of an order');

define('ACCOUNT_GENDER_TITLE', 'Form of address');
define('ACCOUNT_GENDER_DESC', 'The salutation is displayed');

define('ACCOUNT_DOB_TITLE', 'Date of birth');
define('ACCOUNT_DOB_DESC', 'The dough date is required as an input if \'true\' is set. Otherwise it is not displayed as an input option.');

define('ACCOUNT_COMPANY_TITLE', 'Company Name');
define('ACCOUNT_COMPANY_DESC', 'Company name is displayed');

define('ACCOUNT_OWNER_TITLE', 'Owner');
define('ACCOUNT_OWNER_DESC', 'The owner of the company is displayed');

define('ACCOUNT_VAT_ID_TITLE', 'Value added tax ID');
define('ACCOUNT_VAT_ID_DESC', 'The sales tax ID for commercial customers can be entered.');

define('ACCOUNT_STATE_TITLE', 'Federal State');
define('ACCOUNT_STATE_DESC', 'The display and input of the federal state is possible. The input is mandatory for display.');

define('ACCOUNT_ACCOUNT_TELEPHONE_TITLE', 'Telephone number');
define('ACCOUNT_ACCOUNT_TELEPHONE_DESC', 'Do you need the phone number of your customer?');

define('NEWSLETTER_TITLE', 'Newsletter');
define('NEWSLETTER_DESC', 'Would you like to send newsletter?');

define('PRODUCTS_CHARTS_TITLE', 'Graph about price trend');
define('PRODUCTS_CHARTS_DESC', 'Do you want to display the price development as a graph on the product info page?');


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

define('SHIPPING_PRICE_WITH_TAX_TITLE', 'Recorded shipping costs include sales tax?');
define('SHIPPINGPRICE_WITH_TAX_DESC', 'Enter shipping costs incl. tax (true) or add the tax from the store (false)');

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

define('STORE_PAGE_PARSE_TIME_TITLE', 'Store Page Parse Time');
define('STORE_PAGE_PARSE_TIME_DESC', 'Store the time it takes to parse a page');

define('STORE_PAGE_PARSE_TIME_LOG_TITLE', 'Log Destination');
define('STORE_PAGE_PARSE_TIME_LOG_DESC', 'Directory and filename of the page parse time log');

define('STORE_PARSE_DATE_TIME_FORMAT_TITLE', 'Log Date Format');
define('STORE_PARSE_DATE_TIME_FORMAT_DESC', 'The date format');

define('DISPLAY_PAGE_PARSE_TIME_TITLE', 'Display The Page Parse Time');
define('DISPLAY_PAGE_PARSE_TIME_DESC', 'Display the page parse time (store page parse time must be enabled)');

define('USE_CACHE_TITLE', 'Use Cache');
define('USE_CACHE_DESC', 'Use caching features');

define('BLOG_URL_TITLE', 'Blog');
define('BLOG_URL_DESC', 'URL of the Blog 3 page:');

define('PHPBB_URL_TITLE', 'phpBB 3');
define('PHPBB_URL_DESC', 'URL of the phpBB 3 page:');

define('WARN_INSTALL_EXISTENCE_TITLE', 'Warning: The installation directory still exists');
define('WARN_INSTALL_EXISTENCE_DESC', 'The store warns about the installation directory if set to \'true\' and the directory exists.');

define('WARN_CONFIG_WRITEABLE_TITLE', 'Warning: MyOOS [store system] is able to write to the configuration file');
define('WARN_CONFIG_WRITEABLE_DESC', 'The store warns about the write permissions if set to \'true\' and the configuration file is writable.');

define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE_TITLE', 'Warning: The directory for the article download does not exist');
define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE_DESC', 'The store warns about directory if set to \'true\' and the directory is missing.');

define('DOWNLOAD_ENABLED_TITLE', 'Enable download');
define('DOWNLOAD_ENABLED_DESC', 'Enable the products download functions.');

define('DOWNLOAD_BY_REDIRECT_TITLE', 'Download by redirect');
define('DOWNLOAD_BY_REDIRECT_DESC', 'Use browser redirection for download. Disable on non-Unix systems.');

define('DOWNLOAD_MAX_DAYS_TITLE', 'Expiry delay (days)');
define('DOWNLOAD_MAX_DAYS_DESC', 'Set number of days before the download link expires. 0 means no limit.');

define('DOWNLOAD_MAX_COUNT_TITLE', 'Maximum number of downloads');
define('DOWNLOAD_MAX_COUNT_DESC', 'Set the maximum number of downloads. 0 means no download authorized.');

define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_TITLE', 'Downloads Controller Order Status Value');
define('DOWNLOADS_CONTROLLER_ORDERS_STATUS_DESC', 'Downloads Controller Order Status Value - Default=2');

define('SHOW_PRICE_TITLE', 'Product price');
define('SHOW_PRICE_DESC', 'Do you want to view the product price?');

define('SHOW_SPECIALS_PRICE_TITLE', 'Special offers');
define('SHOW_SPECIALS_PRICE_DESC', 'Do you want to see the offer price?');

define('SHOW_SPECIALS_PRICE_EXPIRES_TITLE', 'Date special offers');
define('SHOW_SPECIALS_PRICE_EXPIRES_DESC', 'Do you want to see the validity date of the offer prices?');

define('SHOW_TAX_CLASS_ID_TITLE', 'Tax rate');
define('SHOW_TAX_CLASS_ID_DESC', 'Do you want to display the tax rate?');

define('SHOW_OPTIONS_TITLE', 'Product options');
define('SHOW_OPTIONS_DESC', 'Do you want to view the product options?');

define('SHOW_OPTIONS_PRICE_TITLE', 'Product options price');
define('SHOW_OPTIONS_PRICE_DESC', 'Do you want to see the prices of the product options?');

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

define('DISPLAY_CART_TITLE', 'Display Cart After Adding Product');
define('DISPLAY_CART_DESC', 'Display the shopping cart after adding a product (or return back to their origin)');

define('OOS_SMALL_IMAGE_WIDTH_TITLE', 'Small Image Width');
define('OOS_SMALL_IMAGE_WIDTH_DESC', 'The pixel width of small images');

define('OOS_SMALL_IMAGE_HEIGHT_TITLE', 'Small Image Height');
define('OOS_SMALL_IMAGE_HEIGHT_DESC', 'The pixel height of small images');

define('OOS_BIGIMAGE_WIDTH_TITLE', 'Width large image');
define('OOS_BIGIMAGE_WIDTH_DESC', 'Width of the large image in pixels');

define('OOS_BIGIMAGE_HEIGHT_TITLE', 'Height large image');
define('OOS_BIGIMAGE_HEIGHT_DESC', 'Height of the large image in pixels');

define('OOS_META_TITLE_TITLE', 'Shop Title');
define('OOS_META_TITLE_DESC', 'The Title');

define('OOS_META_DESCRIPTION_TITLE', 'Description');
define('OOS_META_DESCRIPTION_DESC', 'The description of your shop(max. 250 characters)');

define('OOS_META_AUTHOR_TITLE', 'Author');
define('OOS_META_AUTHOR_DESC', 'The author of the shop');

define('OOS_META_COPYRIGHT_TITLE', 'Copyright');
define('OOS_META_COPYRIGHT_DESC', 'Der Entwickler des Shop');

define('SITE_ICONS_TITLE', 'Site Icons');
define('SITE_ICONS_DESC', 'Site Icons are what you see in browser tabs and bookmark bars. Upload one here!');

define('OPEN_GRAPH_THUMBNAIL_TITLE', 'OpenGraph Thumbnail');
define('OPEN_GRAPH_THUMBNAIL_DESC', 'When a featured image is not set, this image will be used as a thumbnail when your post is shared on Facebook. Recommended image size 1200 x 630 pixels.');

define('SITE_NAME_TITLE', 'Store Name');
define('SITE_NAME_DESC', 'The name of my store');

define('TWITTER_CARD_TITLE', 'Twitter Card Type');
define('TWITTER_CARD_DESC', 'Card type selected when creating a new post. This will also be applied for posts without a card type selected.');

define('TWITTER_CREATOR_TITLE', 'Twitter Username');
define('TWITTER_CREATOR_DESC', 'Enter the Twitter username of the author to add twitter:creator tag to posts.');



define('FACEBOOK_URL_TITLE', 'Facebook');
define('FACEBOOK_URL_DESC', 'URL of the Facebook page:');

define('SKYPE_URL_TITLE', 'Skype');
define('SKYPE_URL_DESC', 'URL of the Skype page');

define('LINKEDIN_URL_TITLE', 'Linkedin');
define('LINKEDIN_URL_DESC', 'URL of the Linkedin page');

define('PINTEREST_URL_TITLE', 'Pinterest');
define('PINTEREST_URL_DESC', 'URL of the Pinterest page');

define('TWITTER_URL_TITLE', 'Twitter');
define('TWITTER_URL_DESC', 'URL of the Twitter page');

define('DRIBBBLE_URL_TITLE', 'Dribbble');
define('DRIBBBLE_URL_DESC', 'URL of the Dribbble page');
