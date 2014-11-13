<?php
/* ----------------------------------------------------------------------
   $Id: oos_filename.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.155 2003/02/17 16:54:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $aFilename = array(); 

  $prefix_filename = '';
  if (!$prefix_filename == '') $prefix_filename =  $prefix_filename . '_';

  $aFilename['admin_account'] = $prefix_filename . 'admin_account.php';
  $aFilename['admin_files'] = $prefix_filename . 'admin_files.php';
  $aFilename['admin_members'] = $prefix_filename . 'admin_members.php';

  $aFilename['advanced_search_result'] = $prefix_filename . 'advanced_search_result.php';
  $aFilename['cache'] = $prefix_filename . 'cache.php';
  $aFilename['campaigns'] = $prefix_filename . 'campaigns.php';
  $aFilename['catalog_product_info'] = $prefix_filename . 'product_info.php';
  $aFilename['categories'] = $prefix_filename . 'categories.php';
  $aFilename['configuration'] = $prefix_filename . 'configuration.php';
  $aFilename['content_block'] = $prefix_filename . 'content_block.php';
  $aFilename['content_information'] = $prefix_filename . 'content_information.php';
  $aFilename['content_news'] = $prefix_filename . 'content_news.php';
  $aFilename['content_page_type'] = $prefix_filename . 'content_page_type.php';
  $aFilename['countries'] = $prefix_filename . 'countries.php';
  $aFilename['currencies'] = $prefix_filename . 'currencies.php';
  $aFilename['customers'] = $prefix_filename . 'customers.php';
  $aFilename['customers_status'] = $prefix_filename . 'customers_status.php';
  $aFilename['coupon_admin'] = $prefix_filename . 'coupon_admin.php';
  $aFilename['default'] = $prefix_filename . 'index.php';
  $aFilename['define_language'] = $prefix_filename . 'define_language.php';
  $aFilename['easypopulate'] = $prefix_filename . 'easypopulate.php';
  $aFilename['edit_orders'] = $prefix_filename . 'edit_orders.php';
  $aFilename['export_excel'] = $prefix_filename . 'export_excel.php';
  $aFilename['import_excel'] = $prefix_filename . 'import_excel.php';
  $aFilename['featured'] = $prefix_filename . 'featured.php';
  $aFilename['file_manager'] = $prefix_filename . 'file_manager.php';
  $aFilename['forbiden'] = $prefix_filename . 'forbiden.php';
  $aFilename['geo_zones'] = $prefix_filename . 'geo_zones.php';
  $aFilename['gv_queue'] = $prefix_filename . 'gv_queue.php';
  $aFilename['gv_mail'] = $prefix_filename . 'gv_mail.php';
  $aFilename['gv_sent'] = $prefix_filename . 'gv_sent.php';
  $aFilename['invoice'] = $prefix_filename . 'invoice.php';
  $aFilename['information'] = $prefix_filename . 'information.php';
  $aFilename['login'] = $prefix_filename . 'login.php';
  $aFilename['login_create'] = $prefix_filename . 'login_create.php';
  $aFilename['logoff'] = $prefix_filename . 'logoff.php';
  $aFilename['languages'] = $prefix_filename . 'languages.php';
  $aFilename['listcategories'] = $prefix_filename . 'listcategories.php';
  $aFilename['listproducts'] = $prefix_filename . 'listproducts.php';
  $aFilename['mail'] = $prefix_filename . 'mail.php';
  $aFilename['manual_loging'] = $prefix_filename . 'manual_loging.php';
  $aFilename['manufacturers'] = $prefix_filename . 'manufacturers.php';
  $aFilename['modules'] = $prefix_filename . 'modules.php';
  $aFilename['newsletters'] = $prefix_filename . 'newsletters.php';
  $aFilename['orders'] = $prefix_filename . 'orders.php';
  $aFilename['orders_status'] = $prefix_filename . 'orders_status.php';
  $aFilename['packingslip'] = $prefix_filename . 'packingslip.php';
  $aFilename['password_forgotten'] = $prefix_filename . 'password_forgotten.php';

  $aFilename['plugins'] = $prefix_filename . 'plugins.php';
  $aFilename['popup_google_map'] = $prefix_filename . 'popup_google_map.php';
  $aFilename['popup_image'] = $prefix_filename . 'popup_image.php';
  $aFilename['popup_image_news'] = $prefix_filename . 'popup_image_news.php';
  $aFilename['popup_image_product'] = $prefix_filename . 'popup_image_product.php';
  $aFilename['popup_subimage_product'] = $prefix_filename . 'popup_subimage_product.php';
  $aFilename['products'] = $prefix_filename . 'products.php';
  $aFilename['products_attributes'] = $prefix_filename . 'products_attributes.php';
  $aFilename['products_attributes_add'] = $prefix_filename . 'products_attributes_add.php';
  $aFilename['products_expected'] = $prefix_filename . 'products_expected.php';
  $aFilename['products_status'] = $prefix_filename . 'products_status.php';
  $aFilename['products_units'] = $prefix_filename . 'products_units.php';
  $aFilename['quick_stockupdate'] = $prefix_filename . 'quick_stockupdate.php';
  $aFilename['recover_cart_sales'] = $prefix_filename . 'recover_cart_sales.php';
  $aFilename['reviews'] = $prefix_filename . 'reviews.php';

  $aFilename['server_info'] = $prefix_filename . 'server_info.php';
  $aFilename['shipping_modules'] = $prefix_filename . 'shipping_modules.php';
  $aFilename['specials'] = $prefix_filename . 'specials.php';
  $aFilename['stats_customers'] = $prefix_filename . 'stats_customers.php';
  $aFilename['stats_keywords'] = $prefix_filename . 'stats_keywords.php';
  $aFilename['stats_low_stock'] = $prefix_filename . 'stats_low_stock.php';
  $aFilename['stats_products_purchased'] = $prefix_filename . 'stats_products_purchased.php';
  $aFilename['stats_products_viewed'] = $prefix_filename . 'stats_products_viewed.php';
  $aFilename['stats_recover_cart_sales'] = $prefix_filename . 'stats_recover_cart_sales.php';
  $aFilename['stats_referer'] = $prefix_filename . 'stats_referer.php';
  $aFilename['stats_sales_report2'] = $prefix_filename . 'stats_sales_report2.php';
  $aFilename['tax_classes'] = $prefix_filename . 'tax_classes.php';
  $aFilename['tax_rates'] = $prefix_filename . 'tax_rates.php';
  $aFilename['validcategories'] = $prefix_filename . 'validcategories.php';
  $aFilename['validproducts'] = $prefix_filename . 'validproducts.php';
  $aFilename['whos_online'] = $prefix_filename . 'whos_online.php';
  $aFilename['xsell_products'] = 'xsell_products.php';
  $aFilename['up_sell_products'] = 'up_sell_products.php';
  $aFilename['zones'] = $prefix_filename . 'zones.php';

  //catalogLink


  $prefix_modules = '';
  if (!$prefix_modules == '') $prefix_modules =  $prefix_modules . '_';

  $oosModules = array();
  $oosModules['account'] = $prefix_modules . 'account';
  $oosModules['admin'] = $prefix_modules . 'admin';
  $oosModules['gv'] = $prefix_modules . 'gv';
  $oosModules['main'] = $prefix_modules . 'main';
  $oosModules['products'] = $prefix_modules . 'products';
  $oosModules['search'] = $prefix_modules . 'search';
  $oosModules['user'] = $prefix_modules . 'user';

  $prefix_catalog_filename = '';
  if (!$prefix_catalog_filename == '') $prefix_catalog_filename =  $prefix_catalog_filename . '_';

  $oosCatalogFilename = array();
  $oosCatalogFilename['account_history_info'] = $prefix_catalog_filename . 'history_info';
  $oosCatalogFilename['advanced_search_result'] = $prefix_catalog_filename . 'advanced_result';
  $oosCatalogFilename['default'] = $prefix_catalog_filename . 'main';
  $oosCatalogFilename['gv_redeem'] = $prefix_catalog_filename . 'redeem';
  $oosCatalogFilename['product_info'] = $prefix_catalog_filename . 'info'; 
  $oosCatalogFilename['login_admin'] = $prefix_catalog_filename . 'login';
  $oosCatalogFilename['create_account_admin'] = $prefix_catalog_filename . 'create_account';
  $oosCatalogFilename['wishlist'] = $prefix_catalog_filename . 'wishlist'; 
  $oosCatalogFilename['user_login'] = $prefix_catalog_filename . 'login';

