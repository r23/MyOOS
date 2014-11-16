<?php
/* ----------------------------------------------------------------------
   $Id: oos_define.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

define('OOS_LOG_SQL', 'false');    // OOS Performance Monitor
define('USE_DB_CACHE', 'false');   // OOS SQL-Layer Cache 
define('USE_DB_CACHE_LEVEL_HIGH', 'false');  // OOS SQL-Layer Cache HIGH

define('WARN_INSTALL_EXISTENCE', 'true');
define('WARN_CONFIG_WRITEABLE', 'true');
define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
define('WARN_SESSION_AUTO_START', 'true');
define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');

define('DECIMAL_CART_QUANTITY', 'false');
define('PRODUCT_LISTING_WITH_QTY', 'true');



define('UNITS_DELIMITER', '&nbsp;/&nbsp;');
define('ACCOUNT_COMPANY_VAT_ID_CHECK', 'true');

define('OOS_PAGE_TYPE_MAINPAGE',  1);
define('OOS_PAGE_TYPE_CATALOG',   2);
define('OOS_PAGE_TYPE_PRODUCTS',  3);
define('OOS_PAGE_TYPE_SERVICE',   5);
define('OOS_PAGE_TYPE_CHECKOUT',  6);
define('OOS_PAGE_TYPE_ACCOUNT',   8);
define('OOS_PAGE_TYPE_REVIEWS',   9);

define('MAKE_PASSWORD', 'true'); // OOS create the Password for login; 


