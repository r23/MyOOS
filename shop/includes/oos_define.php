<?php
/* ----------------------------------------------------------------------
   $Id: oos_define.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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


  if (strlen(ini_get("safe_mode"))< 1) {
    define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'true');
    define('WARN_SESSION_AUTO_START', 'true');
    define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true');
  } else {
    define('WARN_SESSION_DIRECTORY_NOT_WRITEABLE', 'false');
    define('WARN_SESSION_AUTO_START', 'false');
    define('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'false');
  }

  define('TIME_BASED_GREETING', 'true');
  define('PDF_WITH_HTMLDOC', 'false');

  define('DECIMAL_CART_QUANTITY', 'false');
  define('PRODUCT_LISTING_WITH_QTY', 'true');

  define('LIGHTBOX', 'true'); // Lightbox on the products info page 
  define('SOCIAL_BOOKMARKS', 'true'); // SOCIAL_BOOKMARKS Links on the products info page 

  define('UNITS_DELIMITER', '&nbsp;/&nbsp;');
  define('ACCOUNT_COMPANY_VAT_ID_CHECK', 'true');

  define('STORE_STREET_ADDRESS', 'Thueringenstrasse 20');
  define('STORE_CITY', 'Hagen');
  define('STORE_POSTCODE', '58135');
  define('STORE_ISO_639_2', 'de');


  define('OOS_PAGE_TYPE_MAINPAGE',  1);
  define('OOS_PAGE_TYPE_CATALOG',   2);
  define('OOS_PAGE_TYPE_PRODUCTS',  3);
  define('OOS_PAGE_TYPE_NEWS',      4);
  define('OOS_PAGE_TYPE_SERVICE',   5);
  define('OOS_PAGE_TYPE_CHECKOUT',  6);
  define('OOS_PAGE_TYPE_AFFILIATE', 7);
  define('OOS_PAGE_TYPE_ACCOUNT',   8);
  define('OOS_PAGE_TYPE_REVIEWS',   9);

  define('MAKE_PASSWORD', 'true'); // OOS create the Password for login; 


  define('AFFILIATE_KIND_OF_BANNERS','2');          // 1 Direct Link to Banner ; no counting of how much banners are shown
                                                    // 2 Banners are shown with affiliate_show_banner.php; bannerviews are counted (recommended)
  define('AFFILIATE_SHOW_BANNERS_DEBUG', 'false');  // Debug for affiliate_show_banner.php; If you have difficulties geting banners set to true,
                                                    // and try to load the banner in a new Browserwindow
                                                    // i.e.: http://yourdomain.com/affiliate_show_banner.php?ref=3569&affiliate_banner_id=3
  define('AFFILIATE_SHOW_BANNERS_DEFAULT_PIC', ''); // absolute path to default pic for affiliate_show_banner.php, which is showed if no banner is found
                                                    // Only works with AFFILIATE_KIND_OF_BANNERS=2 


/**
 * php version
 * Based on:
 *
 * File:  defines_php.lib.php,v 1.51.2.3 2003/10/18 21:28:36 lem9 Exp 
 *
 * phpMyAdmin
 *  
 * A set of PHP-scripts to administrate MySQL over the WWW.
 * http://phpmyadmin.net
 *  
 * Copyright (c) 1998 - 2004 phpMyAdmin
 */
  if (!defined('OOS_PHP_INT_VERSION')) {
    if (!ereg('([0-9]{1,2}).([0-9]{1,2}).([0-9]{1,2})', phpversion(), $match)) {
      $result = ereg('([0-9]{1,2}).([0-9]{1,2})', phpversion(), $match);
    }
    if (isset($match) && !empty($match[1])) {
      if (!isset($match[2])) {
        $match[2] = 0;
      }
      if (!isset($match[3])) {
        $match[3] = 0;
      }
      define('OOS_PHP_INT_VERSION', (int)sprintf('%d%02d%02d', $match[1], $match[2], $match[3]));
      unset($match);
    } else {
      define('OOS_PHP_INT_VERSION', 0);
    }
    define('OOS_PHP_STR_VERSION', phpversion());
  }

?>
