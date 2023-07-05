<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
* #@+
 * Constants for expressing human-readable data sizes in their respective number of bytes.
 */
define('KB_IN_BYTES', 1024);
define('MB_IN_BYTES', 1024 * KB_IN_BYTES);
define('GB_IN_BYTES', 1024 * MB_IN_BYTES);
/**
* #@-
*/

define('COST', '14');
define('PEPPER', '.m3h-RL=^XM/72;tSdU\Bz');

define('ACCOUNT_COMPANY_VAT_ID_CHECK', 'true');

define('OOS_PAGE_TYPE_MAINPAGE', 1);
define('OOS_PAGE_TYPE_CATALOG', 2);
define('OOS_PAGE_TYPE_PRODUCTS', 3);
define('OOS_PAGE_TYPE_SERVICE', 4);
define('OOS_PAGE_TYPE_CHECKOUT', 5);
define('OOS_PAGE_TYPE_ACCOUNT', 6);
define('OOS_PAGE_TYPE_REVIEWS', 7);

define('LOGIN_FOR_PRICE', 'false');
