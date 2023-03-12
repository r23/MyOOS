<?php
/**
   ----------------------------------------------------------------------
   $Id: eng.php,v 1.3 2007/06/13 17:20:31 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: english.php,v 1.101 2002/11/11 13:30:16 project3000
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

 /**
  * look in your $PATH_LOCALE/locale directory for available locales..
  * on RedHat try 'en_US'
  * on FreeBSD try 'en_US.ISO_8859-1'
  * on Windows try 'en', or 'English'
  */
  define('THE_LOCALE', 'en_US');
  define('LANG', 'en');
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
  * @param  $date
  * @param  $reverse
  * @return string
  */
function oos_date_raw($date, $reverse = false)
{
    if ($reverse) {
        return substr($date, 3, 2) . substr($date, 0, 2) . substr($date, 6, 4);
    } else {
        return substr($date, 6, 4) . substr($date, 0, 2) . substr($date, 3, 2);
    }
}

// GLOBAL entries for the <html> tag
define('HTML_PARAMS', 'lang="en"');

// charset for emails
define('CHARSET', 'utf-8');

// page title
define('TITLE', 'MyOOS [Shopsystem]');

// header text in includes/header.php
define('HEADER_TITLE_TOP', 'Home');
define('HEADER_TITLE_SUPPORT_SITE', 'Support Site');
define('HEADER_TITLE_ONLINE_CATALOG', 'Online Catalog');
define('HEADER_TITLE_ADMINISTRATION', 'Administration');
define('HEADER_TITLE_LOGOFF', 'Logoff');

$aLang['header_title_top'] = 'Welcome to MyOOS [Shopsystem]';
$aLang['header_title_support_site'] = 'Support Site';
$aLang['header_title_online_catalog'] = 'Online Catalog';
$aLang['header_title_administration'] = 'Administration';
$aLang['header_title_account'] = 'My Account';
$aLang['header_title_logoff'] = 'Logoff';

// text for gender
define('MALE', 'Male');
define('FEMALE', 'Female');
define('DIVERSE', 'Diverse');

// text for date of birth example
define('DOB_FORMAT_STRING', 'mm/dd/yyyy');

// configuration box text in includes/boxes/configuration.php
define('BOX_HEADING_CONFIGURATION', 'Configuration');
define('BOX_CONFIGURATION_MYSTORE', 'My Store');
define('BOX_CONFIGURATION_LOGGING', 'Logging');
define('BOX_CONFIGURATION_CACHE', 'Cache');

// modules box text in includes/boxes/modules.php
define('BOX_HEADING_MODULES', 'Modules');
define('BOX_MODULES_PAYMENT', 'Payment');
define('BOX_MODULES_SHIPPING', 'Shipping');
define('BOX_MODULES_ORDER_TOTAL', 'Order Total');

// plugins box text in includes/boxes/plugins.php
define('BOX_HEADING_PLUGINS', 'Plugins');
define('BOX_PLUGINS_EVENT', 'Event Plugins');

// categories box text in includes/boxes/catalog.php
define('BOX_HEADING_CATALOG', 'Catalog');
define('BOX_CATALOG_CATEGORIES_PRODUCTS', 'Categories/Products');
define('BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES', 'Products Attributes');
define('BOX_CATALOG_PRODUCTS_STATUS', 'Products Status');
define('BOX_CATALOG_PRODUCTS_UNITS', 'Packing unit');
define('BOX_CATALOG_MANUFACTURERS', 'Manufacturers');
define('BOX_CATALOG_REVIEWS', 'Reviews');
define('BOX_CATALOG_SPECIALS', 'Specials');
define('BOX_CATALOG_PRODUCTS_EXPECTED', 'Products Expected');
define('BOX_CATALOG_PRODUCTS_FEATURED', 'Featured');
define('BOX_CATALOG_SLIDER', 'Slider');
define('BOX_CATALOG_EXPORT_EXCEL', 'Export Excel Product');
define('BOX_CATALOG_IMPORT_EXCEL', 'Update Excel Price');
define('BOX_CATALOG_WASTEBASKET', 'Wastebasket');


// categories box text in includes/boxes/content.php
define('BOX_HEADING_CONTENT', 'Content Manager');
define('BOX_CONTENT_BLOCK', 'Block Manager');
define('BOX_CONTENT_NEWS', 'News');
define('BOX_CONTENT_INFORMATION', 'Information');
define('BOX_CONTENT_PAGE_TYPE', 'Conten Page Type');

// customers box text in includes/boxes/customers.php
define('BOX_HEADING_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_CUSTOMERS', 'Customers');
define('BOX_CUSTOMERS_ORDERS', 'Orders');
define('BOX_ORDERS_STATUS', 'Orders Status');
define('BOX_ADMIN_LOGIN', 'Admin login');

// taxes box text in includes/boxes/taxes.php
define('BOX_HEADING_LOCATION_AND_TAXES', 'Locations / Taxes');
define('BOX_TAXES_COUNTRIES', 'Country');
define('BOX_TAXES_ZONES', 'Zones');
define('BOX_TAXES_GEO_ZONES', 'Tax Zones');
define('BOX_TAXES_TAX_CLASSES', 'Tax Classes');
define('BOX_TAXES_TAX_RATES', 'Tax Rates');

// reports box text in includes/boxes/reports.php
define('BOX_HEADING_REPORTS', 'Reports');
define('BOX_REPORTS_PRODUCTS_VIEWED', 'Products Viewed');
define('BOX_REPORTS_PRODUCTS_PURCHASED', 'Products Purchased');
define('BOX_REPORTS_ORDERS_TOTAL', 'Customer Orders-Total');
define('BOX_REPORTS_STOCK_LEVEL', 'Low Stock Report');
define('BOX_REPORTS_SALES_REPORT2', 'SalesReport2');

// tools text in includes/boxes/tools.php
define('BOX_HEADING_TOOLS', 'Tools');
define('BOX_TOOLS_DEFINE_LANGUAGE', 'Define Languages');
define('BOX_TOOLS_FILE_MANAGER', 'File Manager');
define('BOX_TOOLS_MAIL', 'Send Email');
define('BOX_TOOLS_NEWSLETTER_MANAGER', 'Newsletter Manager');
define('BOX_HEADING_ADMINISTRATORS', 'Administrators');
define('BOX_ADMINISTRATORS_SETUP', 'Set Up');

// localizaion box text in includes/boxes/localization.php
define('BOX_HEADING_LOCALIZATION', 'Localization');
define('BOX_LOCALIZATION_CURRENCIES', 'Currencies');
define('BOX_LOCALIZATION_LANGUAGES', 'Languages');
define('BOX_LOCALIZATION_CUSTOMERS_STATUS', 'Customers Status');

// export
define('BOX_HEADING_EXPORT', 'Export');

//information
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
define('ENTRY_POST_CODE', 'Post Code:');
define('ENTRY_CITY', 'City:');
define('ENTRY_STATE', 'State:');
define('ENTRY_COUNTRY', 'Country:');
define('ENTRY_TELEPHONE_NUMBER', 'Telephone Number:');
define('ENTRY_NEWSLETTER', 'Newsletter:');
define('ENTRY_NEWSLETTER_YES', 'subscribes');
define('ENTRY_NEWSLETTER_NO', 'unsubscribes');
define('ENTRY_PASSWORD', 'Password:');
define('ENTRY_PASSWORD_CONFIRMATION', 'Password Confirmation:');
define('PASSWORD_HIDDEN', '--HIDDEN--');

define('PLACEHOLDER_FIRST_NAME', 'First Name');
define('PLACEHOLDER_EMAIL_ADDRESS', 'E-Mail Address');
define('PLACEHOLDER_PASSWORD', 'Password');

// images
define('IMAGE_ANI_SEND_EMAIL', 'Sending E-Mail');
define('BUTTON_AR', 'Augmented Reality');
define('BUTTON_BACK', 'Back');
define('BUTTON_EXPORT', 'Product data export');
define('BUTTON_CANCEL', 'Cancel');
define('BUTTON_UPLOAD_IMAGES', 'Upload Images');
define('BUTTON_CANCEL_UPLOAD', 'Cancel upload');
define('BUTTON_START_UPLOAD', 'Start upload');
define('BUTTON_ADD_FILES', 'Add files...');

define('BUTTON_CHANGE', 'Change');
define('BUTTON_CONFIRM', 'Confirm');
define('BUTTON_COPY', 'Copy');
define('IMAGE_COPY_TO', 'Copy To');
define('BUTTON_CUBE', '3-D Objekt');
define('IMAGE_DEFINE', 'Define');
define('BUTTON_DELETE', 'Delete');
define('BUTTON_DELETE_PERMANENTLY', 'Delete Permanently');

define('BUTTON_EDIT', 'Edit');
define('IMAGE_EMAIL', 'Email');
define('IMAGE_FEATURED', 'Featured');
define('IMAGE_ICON_STATUS_GREEN', 'Active');
define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Set Active');
define('IMAGE_ICON_STATUS_RED', 'Inactive');
define('IMAGE_ICON_STATUS_RED_LIGHT', 'Set Inactive');
define('IMAGE_ICON_INFO', 'Info');
define('BUTTON_INSERT', 'Insert');
define('IMAGE_LOCK', 'Lock');
define('IMAGE_LOGIN', 'Shop Login');
define('BUTTON_MOVE', 'Move');
define('BUTTON_MOVE_TRASH', 'Move to the Trash');
define('IMAGE_NEW_CATEGORY', 'New Category');
define('IMAGE_NEW_COUNTRY', 'New Country');
define('IMAGE_NEW_CURRENCY', 'New Currency');
define('IMAGE_NEW_FILE', 'New File');
define('IMAGE_NEW_FOLDER', 'New Folder');
define('IMAGE_NEW_LANGUAGE', 'New Language');
define('IMAGE_NEW_NEWSLETTER', 'New Newsletter');
define('IMAGE_NEW_PRODUCT', 'New Product');
define('IMAGE_NEW_PRODUCT_STATUS', 'Create a new product status');
define('IMAGE_NEW_TAB', 'New Tab');
define('IMAGE_NEW_TAX_CLASS', 'New Tax Class');
define('IMAGE_NEW_TAX_RATE', 'New Tax Rate');
define('IMAGE_NEW_TAX_ZONE', 'New Tax Zone');
define('IMAGE_NEW_ZONE', 'New Zone');
define('IMAGE_ORDERS', 'Orders');
define('IMAGE_ORDERS_INVOICE', 'Invoice');
define('IMAGE_ORDERS_PACKINGSLIP', 'Packing Slip');
define('BUTTON_PANORAMA', 'Panorama');
define('IMAGE_PLUGINS_INSTALL', 'Install Plugins');
define('IMAGE_PLUGINS_REMOVE', 'Remove Plugins');
define('BUTTON_PREVIEW', 'Preview');
define('IMAGE_RESTORE', 'Restore');
define('BUTTON_RESET', 'Reset');
define('BUTTON_SAVE', 'Save');
define('IMAGE_SEARCH', 'Search');
define('IMAGE_SELECT', 'Select');
define('BUTTON_SELECT_IMAGE', 'Select image');
define('IMAGE_SEND', 'Send');
define('IMAGE_SEND_EMAIL', 'Send Email');
define('BUTTON_SEND_PASSWORD', 'Send password');
define('IMAGE_SLIDER', 'Slider');
define('IMAGE_SPECIALS', 'Specials');
define('IMAGE_STATUS', 'Customers Status');
define('IMAGE_UNLOCK', 'Unlock');
define('BUTTON_UNTRASH', 'Untrash');
define('BUTTON_UPDATE', 'Update');
define('IMAGE_UPDATE_CURRENCIES', 'Update Exchange Rate');
define('IMAGE_UPLOAD', 'Upload');
define('BUTTON_VIDEO', 'Video');
define('IMAGE_WISHLIST', 'Wishlist');

// coupon_admin
define('BUTTON_CONFIRM_DELETE_VOUCHER', 'Confirm Delete Voucher');
define('BUTTON_EMAIL_VOUCHER', 'Email Voucher');
define('BUTTON_EDIT_VOUCHER', 'Edit Voucher');
define('BUTTON_DELETE_VOUCHER', 'Delete Voucher');
define('BUTTON_REPORT_VOUCHER', 'Report Voucher');

$aLang['image_new_tax_rate'] = 'New Tax Rate';
$aLang['image_new_zone'] = 'New Zone';

define('TEXT_ERROR', 'Error');

define('ICON_CROSS', 'false');
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
define('ICON_UNLOCKED', 'Unlocked');
define('ICON_WARNING', 'Warning');

// constants for use in oos_prev_next_display function
define('TEXT_RESULT_PAGE', 'Page %s of %d');
define('TEXT_DISPLAY_NUMBER_OF_COUNTRIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> countries)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers)');
define('TEXT_DISPLAY_NUMBER_OF_CURRENCIES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> currencies)');
define('TEXT_DISPLAY_NUMBER_OF_FEATURED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> featured products)');
define('TEXT_DISPLAY_NUMBER_OF_HTTP_REFERERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b>  HTTP Referers)');
define('TEXT_DISPLAY_NUMBER_OF_LANGUAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> languages)');
define('TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> manufacturers)');
define('TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> newsletters)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders)');
define('TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> orders status)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_EXPECTED', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products expected)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> packing unit)');
define('TEXT_DISPLAY_NUMBER_OF_PRODUCTS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products status)');
define('TEXT_DISPLAY_NUMBER_OF_REVIEWS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> product reviews)');
define('TEXT_DISPLAY_NUMBER_OF_SPECIALS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> products on special)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax classes)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax zones)');
define('TEXT_DISPLAY_NUMBER_OF_TAX_RATES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> tax rates)');
define('TEXT_DISPLAY_NUMBER_OF_ZONES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> zones)');
define('TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> customers status)');
define('TEXT_DISPLAY_NUMBER_OF_BLOCKES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> box)');
define('TEXT_DISPLAY_NUMBER_OF_PAGE_TYPES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> page types)');
define('TEXT_DISPLAY_NUMBER_OF_INFORMATION', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> Information)');


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

define('ENTRY_TAX_YES', 'Yes');
define('ENTRY_TAX_NO', 'No');

define('ENTRY_ON', 'On');
define('ENTRY_OFF', 'Off');

define('ENTRY_YES', 'Yes');
define('ENTRY_NO', 'No');

$aLang['error_destination_does_not_exist'] = 'Error: Destination does not exist.';
$aLang['error_destination_not_writeable'] = 'Error: Destination not writeable.';
$aLang['error_file_not_saved'] = 'Error: File upload not saved.';
$aLang['error_filetype_not_allowed'] = 'Error: File upload type not allowed.';
$aLang['success_file_saved_successfully'] = 'Erfolg: Datei erfolgreich hochgeladen.';
$aLang['warning_no_file_uploaded'] = 'Warning: No file uploaded.';
$aLang['warning_file_uploads_disabled'] = 'Warning: File uploads are disabled in the php.ini configuration file.';


define('BOX_HEADING_GV_ADMIN', 'Vouchers/Coupons');
define('BOX_GV_ADMIN_QUEUE', 'Gift Voucher Queue');
define('BOX_GV_ADMIN_MAIL', 'Mail Gift Voucher');
define('BOX_GV_ADMIN_SENT', 'Gift Vouchers sent');
define('BOX_COUPON_ADMIN', 'Coupon Admin');

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


// Admin Account
define('BOX_HEADING_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT', 'My Account');
define('BOX_MY_ACCOUNT_LOGOFF', 'Logoff');

// configuration box text in includes/boxes/administrator.php
define('BOX_HEADING_ADMINISTRATOR', 'Administrator');
define('BOX_ADMINISTRATOR_MEMBERS', 'Member Groups');
define('BOX_ADMINISTRATOR_MEMBER', 'Members');
define('BOX_ADMINISTRATOR_BOXES', 'File Access');

// images
define('IMAGE_FILE_PERMISSION', 'File Permission');
define('IMAGE_GROUPS', 'Groups List');
define('BUTTON_INSERT_FILE', 'Insert File');
define('IMAGE_MEMBERS', 'Members List');
define('IMAGE_NEW_GROUP', 'New Group');
define('IMAGE_NEW_MEMBER', 'New Member');
define('IMAGE_NEXT', 'Next');

// constants for use in oosPrevNextDisplay function
define('TEXT_DISPLAY_NUMBER_OF_FILENAMES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> filenames)');
define('TEXT_DISPLAY_NUMBER_OF_MEMBERS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> members)');

define('PULL_DOWN_DEFAULT', 'Please Select');

//.htaccess
define('ERROR_HTACC_CHECK_ERROR', 'It could not be checked whether the program is protected!<br>The simulated external access could not be carried out.');
define('ERROR_HTACC_INCOMPLETE', 'The program is not protected, the directory protection is incomplete!');
define('ERROR_HTACC_PROPOSED', 'The program is not protected, directory protection is strongly recommended!');
