<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configure.php,v 1.13 2003/02/10 22:30:51 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

define('OOS_HTTPS_SERVER', ''); // No trailing slash
define('OOS_SHOP', '');
define('OOS_ADMIN', 'admin/');

define('OOS_IMAGES', 'images/');

define('OOS_SHOP_IMAGES', OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES);
define('OOS_ICONS', OOS_IMAGES . 'icons/');

define('OOS_MEDIA', 'media/');
define('OOS_COOKIE_CONSENT', OOS_HTTPS_SERVER . OOS_SHOP . 'cookie_consent');
define('OOS_DOWNLOAD', OOS_SHOP . 'pub/');

define('OOS_ABSOLUTE_PATH', '');
define('OOS_DOWNLOAD_PATH', OOS_ABSOLUTE_PATH . 'download/');
define('OOS_DOWNLOAD_PATH_PUBLIC', OOS_ABSOLUTE_PATH . 'pub/');
define('OOS_FEEDS_EXPORT_PATH', OOS_ABSOLUTE_PATH . 'feed/');
define('OOS_EXPORT_PATH', OOS_ABSOLUTE_PATH . OOS_ADMIN . 'export/');

define('OOS_UPLOADS', OOS_ABSOLUTE_PATH . OOS_IMAGES . 'uploads/');

define('OOS_TEMP_PATH', OOS_ABSOLUTE_PATH . 'temp/');
define('ADODB_ERROR_LOG_DEST', OOS_TEMP_PATH . 'logs/adodb_error.log');

define('ADODB_ERROR_LOG_TYPE', 3);
define('ADODB_ASSOC_CASE', 0); // assoc lowercase for ADODB_FETCH_ASSOC

define('OOS_DB_TYPE', '');
define('OOS_DB_SERVER', '');
define('OOS_DB_USERNAME', '');
define('OOS_DB_PASSWORD', '');
define('OOS_DB_DATABASE', '');
define('OOS_DB_PREFIX', '');
define('OOS_ENCODED', '');
