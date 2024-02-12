<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.5.2.2 2003/04/27 12:36:00 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Reactivation of shopping cart abandonment');

define('TABLE_HEADING_TITLE', 'Title');
define('TABLE_HEADING_FILE_DATE', 'Date');
define('TABLE_HEADING_FILE_SIZE', 'Size');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_HEADING_NEW_EXPORT', 'New product data export');
define('TEXT_INFO_HEADING_RESTORE_LOCAL', 'Restore Local');
define('TEXT_INFO_NEW_EXPORT', 'Please do NOT interrupt the export process. This may take a few minutes.');
define('TEXT_INFO_UNPACK', '<br><br>(after unpacking the file from the archive)');
define('TEXT_INFO_DATE', 'Date:');
define('TEXT_INFO_SIZE', 'Size:');
define('TEXT_INFO_COMPRESSION', 'Compression:');
define('TEXT_INFO_USE_GZIP', 'Use GZIP');
define('TEXT_INFO_USE_ZIP', 'Use ZIP');
define('TEXT_INFO_USE_NO_COMPRESSION', 'No Compression (Pure SQL)');
define('TEXT_INFO_DOWNLOAD_ONLY', 'Download only (do not store server side)');
define('TEXT_INFO_BEST_THROUGH_HTTPS', 'Best through a HTTPS connection');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this backup?');
define('TEXT_NO_EXTENSION', 'None');
define('TEXT_EXPORT_DIRECTORY', 'Export directory:');
define('TEXT_FORGET', '(<u>forget</u>)');

define('ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST', 'Error!</strong> Export directory does not exist. Please set this in configure.php.');
define('ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE', 'Error!</strong> Export directory is not writeable.');
define('ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE', 'Error!</strong> Download link not acceptable.');

define('SUCCESS_DATABASE_SAVED', 'Success!</strong> The Product data has been saved.');
define('SUCCESS_DATABASE_RESTORED', 'Success!</strong> The Product data has been restored.');
define('SUCCESS_EXPORT_DELETED', 'Success!</strong> The backup has been removed.');

