<?php
/* ----------------------------------------------------------------------
   $Id: oos_define.php,v 1.2 2007/10/29 18:21:06 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   BruttoAdmin V1.1 for OSC http://www.oscommerce.com

   Images_resize Vs 1.3 for OSC http://www.oscommerce.com
   Copyright 2003 Henri Schmidhuber
   mailto: info@in-solution.de http://www.in-solution.de 

   osCommerce 2.2 Milestone 1
   Copyright (c) osCommerce 2003

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  define('OOS_PRICE_IS_BRUTTO', 'true');  // Price with Tax
  define('OOS_BASE_PRICE', 'true');
  define('DECIMAL_CART_QUANTITY', 'false');

  define('NEW_PRODUCT_PREVIEW', 'false');

  define('IMGSWAP', 'true');
  define('IMGLENS', 'true');

  //affiliate
  define ('AFFILIATE_NOTIFY_AFTER_BILLING','true'); // Nofify affiliate if he got a new invoice
  define ('AFFILIATE_DELETE_ORDERS','false');       // Delete affiliate_sales if an order is deleted (Warning: Only not yet billed sales are deleted)

  define ('AFFILIATE_TAX_ID','1');  // Tax Rates used for billing the affiliates 
                                   // you get this from the URl (tID) when you select you Tax Rate at the admin: tax_rates.php?tID=1
  // If set, the following actions take place each time you call the admin/affiliate_summary                  
  define ('AFFILIATE_DELETE_CLICKTHROUGHS','false');  // (days / false) To keep the clickthrough report small you can set the days after which they are deleted (when calling affiliate_summary in the admin) 
  define ('AFFILIATE_DELETE_AFFILIATE_BANNER_HISTORY','false');  // (days / false) To keep thethe table AFFILIATE_BANNER_HISTORY small you can set the days after which they are deleted (when calling affiliate_summary in the admin) 
?>