<?php
/* ----------------------------------------------------------------------
   $Id: eng.php 475 2013-07-13 08:22:26Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.101 2002/11/11 13:30:16 project3000 
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
  @setlocale(LC_TIME, 'en_US');
  define('DATE_FORMAT_SHORT', '%m/%d/%Y');  // this is used for strftime()
  define('DATE_FORMAT_LONG', '%A %d %B, %Y'); // this is used for strftime()
  define('DATE_FORMAT', 'm/d/Y'); // this is used for date()
  define('PHP_DATE_TIME_FORMAT', 'm/d/Y H:i:s'); // this is used for date()
  define('DATE_TIME_FORMAT', DATE_FORMAT_SHORT . ' %H:%M:%S');


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
      return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
      return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
  }

// Global entries for the <html> tag
define('HTML_PARAMS','dir="ltr" lang="en"');
define('LANG', 'en');

// charset for web pages and emails
define('CHARSET', 'UTF-8');

// page title
define('TITLE', ' MyOOS [Shopsystem]');

$aLang['page_title'] = 'MyOOS';
$aLang['page_headline'] = 'free, intuitive, simple';

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Administration');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');

$aLang['header_title_top'] = 'Administration';
$aLang['header_title_support_site'] = 'Support Site';
$aLang['header_title_online_catalog'] = 'Online Catalog';
$aLang['header_title_administration'] = 'Administration';
$aLang['header_title_account'] = 'My Account';
$aLang['header_title_logoff'] = 'Logoff';

$aLang['collapse_menu'] = 'On/Off Menu';
$aLang['login'] = 'Login';

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
$aLang['box_heading_configuration'] = 'Configuration';
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
$aLang['box_heading_modules'] = 'Modules';
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

// plugins box text in includes/boxes/plugins.php
$aLang['box_heading_plugins'] = 'Event Plugins';
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
$aLang['box_heading_catalog'] = 'Catalog';
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Products Status');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Packing unit');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_QADD_PRODUCT', 'Add Product');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Featured');
define('BOX_CATALOG_EASYPOPULATE', 'EasyPopulate');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Price');
define('BOX_CATALOG_XSELL_PRODUCTS', 'Cross Sell Products');
define('BOX_CATALOG_UP_SELL_PRODUCTS', 'UP Sell Products');
define('BOX_CATALOG_QUICK_STOCKUPDATE', 'Quick Stock Update');

// categories box text in includes/boxes/content.php
$aLang['box_heading_content'] = 'Content Manager';
define('BOX_HEADING_CONTENT', 'Content Manager');
define('BOX_CONTENT_BLOCK', 'Block Manager');
define('BOX_CONTENT_INFORMATION', 'Information');
define('BOX_CONTENT_PAGE_TYPE', 'Conten Page Type');

// customers box text in includes/boxes/customers.php
$aLang['box_heading_customers'] = 'Kunden';
define('BOX_HEADING_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_CAMPAIGNS', 'Campaigns');
define('BOX_ADMIN_LOGIN', 'Admin login');

// gv_admin
$aLang['box_heading_gv_admin'] = 'Vouchers/Coupons';
define('BOX_HEADING_GV_ADMIN', 'Vouchers/Coupons');
define('BOX_GV_ADMIN_QUEUE', 'Gift Voucher Queue');
define('BOX_GV_ADMIN_MAIL', 'Mail Gift Voucher');
define('BOX_GV_ADMIN_SENT', 'Gift Vouchers sent');
define('BOX_COUPON_ADMIN','Coupon Admin');

// taxes box text in includes/boxes/taxes.php
$aLang['box_heading_location_and_taxes'] = 'Locations / Taxes';
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Countries');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
$aLang['box_heading_reports'] = 'Reports';
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');
define('BOX_REPORTS_KEYWORDS', 'Keyword Manager');
define('BOX_REPORTS_REFERER' , 'HTTP Referers');

// tools text in includes/boxes/tools.php
$aLang['box_heading_tools'] = 'Tools';
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_BACKUP', 'Database Backup');
define('BOX_TOOLS_BANNER_MANAGER', 'Banner Manager');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_TOOLS_WHOS_ONLINE', 'Who\'s Online');
define('BOX_TOOLS_KEYWORD_SHOW', 'Keyword Show');
define('BOX_HEADING_ADMINISTRATORS', 'Administrators');
define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
$aLang['box_heading_localization'] = 'Sprachen/Währungen';
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Customers Status');
define('BOX_LOCALIZATION_ORDERS_STATUS', 'Orders Status');

//information
$aLang['box_heading_information'] = 'Information';
define('BOX_HEADING_INFORMATION', 'Information');
define('BOX_INFORMATION', 'Information');

// javascript messages
define('JS_ERROR', 'Errors have occured during the process of your form!\nPlease make the following corrections:\n\n');

define('JS_OPTIONS_VALUE_PRICE', '* The new product atribute needs a price value\n');
define('JS_OPTIONS_VALUE_PRICE_PREFIX', '* The new product atribute needs a price prefix\n');

define('JS_PRODUCTS_NAME', '* The new product needs a name\n');
define('JS_PRODUCTS_DESCRIPTION', '* The new product needs a description\n');
define('JS_PRODUCTS_PRICE', '* The new product needs a price value\n');
define('JS_PRODUCTS_WEIGHT', '* The new product needs a weight value\n');
define('JS_PRODUCTS_QUANTITY', '* The new product needs a quantity value\n');
define('JS_PRODUCTS_MODEL', '* The new product needs a model value\n');
define('JS_PRODUCTS_IMAGE', '* The new product needs an image value\n');

define('JS_SPECIALS_PRODUCTS_PRICE', '* A new price for this product needs to be set\n');

define('JS_GENDER', '* The \'Gender\' value must be chosen.\n');
define('JS_FIRST_NAME', '* The \'First Name\' entry must have at least ' . ENTRY_FIRST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_LAST_NAME', '* The \'Last Name\' entry must have at least ' . ENTRY_LAST_NAME_MIN_LENGTH . ' characters.\n');
define('JS_DOB', '* The \'Date of Birth\' entry must be in the format: xx/xx/xxxx (month/date/year).\n');
define('JS_EMAIL_ADDRESS', '* The \'E-Mail Address\' entry must have at least ' . ENTRY_EMAIL_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_ADDRESS', '* The \'Street Address\' entry must have at least ' . ENTRY_STREET_ADDRESS_MIN_LENGTH . ' characters.\n');
define('JS_POST_CODE', '* The \'Post Code\' entry must have at least ' . ENTRY_POSTCODE_MIN_LENGTH . ' characters.\n');
define('JS_CITY', '* The \'City\' entry must have at least ' . ENTRY_CITY_MIN_LENGTH . ' characters.\n');
define('JS_STATE', '* The \'State\' entry is must be selected.\n');
define('JS_STATE_SELECT', '-- Select Above --');
define('JS_ZONE', '* The \'State\' entry must be selected from the list for this country.');
define('JS_COUNTRY', '* The \'Country\' value must be chosen.\n');
define('JS_TELEPHONE', '* The \'Telephone Number\' entry must have at least ' . ENTRY_TELEPHONE_MIN_LENGTH . ' characters.\n');
define('JS_PASSWORD', '* The \'Password\' amd \'Confirmation\' entries must match amd have at least ' . ENTRY_PASSWORD_MIN_LENGTH . ' characters.\n');

define('JS_ORDER_DOES_NOT_EXIST', 'Order Number %s does not exist!');

define('CATEGORY_PERSONAL', 'Personal');
define('CATEGORY_ADDRESS', 'Address');
define('CATEGORY_CONTACT', 'Contact');
define('CATEGORY_COMPANY', 'Company');
define('CATEGORY_PASSWORD', 'Password');
define('CATEGORY_OPTIONS', 'Options');
define('ENTRY_GENDER', 'Gender:');
define('ENTRY_FIRST_NAME', 'First Name:');
define('ENTRY_LAST_NAME', 'Last Name:');
define('ENTRY_NUMBER', 'Customer Number:');
define('ENTRY_DATE_OF_BIRTH', 'Date of Birth:');
define('ENTRY_EMAIL_ADDRESS', 'E-Mail Address:');
define('ENTRY_COMPANY', 'Company name:');
define('ENTRY_OWNER', 'Owner name:');
define('ENTRY_VAT_ID', 'VAT ID:');
define('ENTRY_STREET_ADDRESS', 'Street Address:');
define('ENTRY_SUBURB', 'Suburb:');
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_CITY', 'City:');
define('ENTRY_STATE', 'State:');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_FAX_NUMBER', 'Fax Number:');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'subscribes');
define('ENTRY_NEWSLETTER_NO', 'unsubscribes');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('PASSWORD_HIDDEN', '--HIDDEN--');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('IMAGE_BACK', 'Back');
define('IMAGE_BACKUP', 'Backup');
define('IMAGE_CANCEL', 'Cancel');
define('IMAGE_CONFIRM', 'Confirm');
define('IMAGE_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('IMAGE_DEFINE', 'Define');
define('IMAGE_DELETE', 'Delete');
define('IMAGE_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FEATURED', 'Featured');
define('IMAGE_FILE_MANAGER', 'File Manager');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('IMAGE_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_MOVE', 'Move');
define('IMAGE_NEW_BANNER', 'New Banner');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWS', 'New News');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('IMAGE_ORDERS_WEBPRINTER', 'WebPrinter');
define('IMAGE_PLUGINS_INSTALL', 'Install Plugins');
define('IMAGE_PLUGINS_REMOVE', 'Remove Plugins');
define('IMAGE_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('IMAGE_RESET', 'Reset');
define('IMAGE_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('IMAGE_SPECIALS', 'Specials');
define('IMAGE_STATUS', 'Customers Status');
define('IMAGE_UNLOCK', 'Unlock');
define('IMAGE_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('IMAGE_WISHLIST', 'Wishlist');

$aLang['button_ani_send_email'] = 'Sending E-Mail';
$aLang['button_back'] = 'Back';
$aLang['button_backup'] = 'Backup';
$aLang['button_cancel'] = 'Cancel';
$aLang['button_confirm'] = 'Confirm';
$aLang['button_copy'] = 'Copy';
$aLang['button_copy_to'] = 'Copy To';
$aLang['button_define'] = 'Define';
$aLang['button_delete'] = 'Delete';
$aLang['button_edit'] = 'Edit';
$aLang['button_email'] = 'Email';
$aLang['button_featured'] = 'Featured';
$aLang['button_file_manager'] = 'File Manager';
$aLang['button_icon_status_green'] = 'Active';
$aLang['button_icon_status_green_light'] = 'Set Active';
$aLang['button_icon_status_red'] = 'Inactive';
$aLang['button_icon_status_red_light'] = 'Set Inactive';
$aLang['button_icon_info'] = 'Info';
$aLang['button_insert'] = 'Insert';
$aLang['button_lock'] = 'Lock';
$aLang['button_move'] = 'Move';
$aLang['button_new_banner'] = 'New Banner';
$aLang['button_new_category'] = 'New Category';
$aLang['button_new_country'] = 'New Country';
$aLang['button_new_currency'] = 'New Currency';
$aLang['button_new_file'] = 'New File';
$aLang['button_new_folder'] = 'New Folder';
$aLang['button_new_language'] = 'New Language';
$aLang['button_new_newsletter'] = 'New Newsletter';
$aLang['button_new_product'] = 'New Product';
$aLang['button_new_tax_class'] = 'New Tax Class';
$aLang['button_new_tax_rate'] = 'New Tax Rate';
$aLang['button_new_tax_zone'] = 'New Tax Zone';
$aLang['button_orders'] = 'Orders';
$aLang['button_orders_invoice'] = 'Invoice';
$aLang['button_orders_packingslip'] = 'Packing Slip';
$aLang['button_orders_webprinter'] = 'WebPrinter';
$aLang['button_plugins_install'] = 'Install Plugins';
$aLang['button_plugins_remove'] = 'Remove Plugins';
$aLang['button_preview'] = 'Preview';
$aLang['button_restore'] = 'Restore';
$aLang['button_reset'] = 'Reset';
$aLang['button_save'] = 'Save';
$aLang['button_search'] = 'Search';
$aLang['button_select'] = 'Select';
$aLang['button_send'] = 'Send';
$aLang['button_send_email'] = 'Send Email';
$aLang['button_send_password]' = 'Send Password';
$aLang['button_specials'] = 'Specials';
$aLang['button_status'] = 'Customers Status';
$aLang['button_unlock'] = 'Unlock';
$aLang['button_update'] = 'Update';
$aLang['button_update_currencies'] = 'Update Exchange Rate';
$aLang['button_upload'] = 'Upload';
$aLang['button_wishlist'] = 'Wishlist';
$aLang['button_new_tax_rate'] = 'New Tax Rate';
$aLang['button_new_zone'] = 'New Zone';

define('ICON_CROSS', 'False');
define('ICON_CURRENT_FOLDER', 'Current Folder');
define('ICON_DELETE', 'Delete');
define('ICON_ERROR', 'Error');
define('ICON_FILE', 'File');
define('ICON_FILE_DOWNLOAD', 'Download');
define('ICON_FOLDER', 'Folder');
define('ICON_LOCKED', 'Locked');
define('ICON_PREVIOUS_LEVEL', 'Previous Level');
define('ICON_PREVIEW', 'Preview');
define('ICON_STATISTICS', 'Statistics');
define('ICON_SUCCESS', 'Success');
define('ICON_TICK', 'True');
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>  HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> packing unit)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers status)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> box)');
define('TEXT_DISPLAY_NUMBER_OF_RSS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> categories)');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Information)');

$aLang['prevnext_title_first_page'] = 'First Page';
$aLang['prevnext_title_previous_page'] = 'Previous Page';
$aLang['prevnext_title_next_page'] = 'Next Page';
$aLang['prevnext_title_last_page'] = 'Last Page';
$aLang['prevnext_title_page_no'] = 'Page %d';
$aLang['prevnext_title_prev_set_of_no_page'] = 'Previous Set of %d Pages';
$aLang['prevnext_title_next_set_of_no_page'] = 'Next Set of %d Pages';
$aLang['prevnext_button_first'] = '&lt;&lt;FIRST';
$aLang['prevnext_button_prev'] = '&lt;&lt;&nbsp;Prev';
$aLang['prevnext_button_next'] = 'Next&nbsp;&gt;&gt;';
$aLang['prevnext_button_last'] = 'LAST&gt;&gt;';

define('PREVNEXT_BUTTON_PREV', '&lt;&lt;');
define('PREVNEXT_BUTTON_NEXT', '&gt;&gt;');

define('TEXT_DEFAULT', 'default');
define('TEXT_SET_DEFAULT', 'Set as default');
define('TEXT_FIELD_REQUIRED', '&nbsp;<span class="fieldRequired">* Required</span>');

define('ERROR_NO_DEFAULT_CURRENCY_DEFINED', 'Error: There is currently no default currency set. Please set one at: Administration Tool->Localization->Currencies');
define('ERROR_USER_FOR_THIS_PAGE', 'Fehler: Sie haben für diesen Bereich keine Zugangsrechte.');

define('TEXT_INFO_USER_NAME', 'UserName:');
define('TEXT_INFO_PASSWORD', 'Password:');

define('TEXT_NONE', '--none--');
define('TEXT_TOP', 'Top');

define('ENTRY_TAX_YES','Yes');
define('ENTRY_TAX_NO','No');



define('IMAGE_RELEASE', 'Redeem Gift Voucher');

define('_JANUARY', 'January');
define('_FEBRUARY', 'February');
define('_MARCH', 'March');
define('_APRIL', 'April');
define('_MAY', 'May');
define('_JUNE', 'June');
define('_JULY', 'July');
define('_AUGUST', 'August');
define('_SEPTEMBER', 'September');
define('_OCTOBER', 'October');
define('_NOVEMBER', 'November');
define('_DECEMBER', 'December');

define('TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> gift vouchers)');
define('TEXT_DISPLAY_NUMBER_OF_COUPONS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> coupons)');

define('TEXT_VALID_PRODUCTS_LIST', 'Products List');
define('TEXT_VALID_PRODUCTS_ID', 'Products ID');
define('TEXT_VALID_PRODUCTS_NAME', 'Products Name');
define('TEXT_VALID_PRODUCTS_MODEL', 'Products Model');

define('TEXT_VALID_CATEGORIES_LIST', 'Categories List');
define('TEXT_VALID_CATEGORIES_ID', 'Category ID');
define('TEXT_VALID_CATEGORIES_NAME', 'Category Name');

define('HEADER_TITLE_ACCOUNT', 'My Account');
define('HEADER_TITLE_LOGOFF', 'Logoff');

// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT_LOGOFF', 'Logoff');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'File Access');

$aLang['box_heading_administrator'] = 'Administrator';

// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('IMAGE_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');

define('PULL_DOWN_DEFAULT', 'Please Select');
$aLang['bulk_actions'] = 'Bulk Actions';

define('BOX_REPORTS_RECOVER_CART_SALES', 'Recover Carts');
define('BOX_TOOLS_RECOVER_CART', 'Recover Carts');

// BOF: WebMakers.com Added: All Add-Ons
// Download Controller
// Add a new Order Status to the orders_status table - Updated
define('ORDERS_STATUS_UPDATED_VALUE','4'); // set to the Updated status to update max days and max count

// Quantity Definitions
require('includes/languages/eng/quantity_control.php');

