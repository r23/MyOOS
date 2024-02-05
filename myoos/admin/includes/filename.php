<?php
/**
   ----------------------------------------------------------------------
   $Id: oos_filename.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.155 2003/02/17 16:54:11 hpdl
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

$aContents = [];

$prefix_filename = '';
if (!$prefix_filename == '') {
    $prefix_filename = $prefix_filename . '_';
}

$aContents['admin_account'] = $prefix_filename . 'admin_account.php';
$aContents['admin_files'] = $prefix_filename . 'admin_files.php';
$aContents['admin_members'] = $prefix_filename . 'admin_members.php';
$aContents['cart_cancelling'] = $prefix_filename . 'cart_cancelling.php';
$aContents['catalog_product_info'] = $prefix_filename . 'product_info.php';
$aContents['categories'] = $prefix_filename . 'categories.php';
$aContents['categories_panorama'] = $prefix_filename . 'categories_panorama.php';
$aContents['categories_slider'] = $prefix_filename . 'categories_slider.php';
$aContents['configuration'] = $prefix_filename . 'configuration.php';
$aContents['content_block'] = $prefix_filename . 'content_block.php';
$aContents['content_page_type'] = $prefix_filename . 'content_page_type.php';
$aContents['countries'] = $prefix_filename . 'countries.php';
$aContents['currencies'] = $prefix_filename . 'currencies.php';
$aContents['customers'] = $prefix_filename . 'customers.php';
$aContents['customers_status'] = $prefix_filename . 'customers_status.php';
$aContents['coupon_admin'] = $prefix_filename . 'coupon_admin.php';
$aContents['default'] = $prefix_filename . 'index.php';
$aContents['edit_orders'] = $prefix_filename . 'edit_orders.php';
$aContents['export_excel'] = $prefix_filename . 'export_excel.php';
$aContents['import_excel'] = $prefix_filename . 'import_excel.php';
$aContents['featured'] = $prefix_filename . 'featured.php';
$aContents['forbiden'] = $prefix_filename . 'forbiden.php';
$aContents['geo_zones'] = $prefix_filename . 'geo_zones.php';
$aContents['gv_queue'] = $prefix_filename . 'gv_queue.php';
$aContents['gv_mail'] = $prefix_filename . 'gv_mail.php';
$aContents['gv_sent'] = $prefix_filename . 'gv_sent.php';
$aContents['invoice'] = $prefix_filename . 'invoice.php';
$aContents['information'] = $prefix_filename . 'information.php';
$aContents['login'] = $prefix_filename . 'login.php';
$aContents['logoff'] = $prefix_filename . 'logoff.php';
$aContents['languages'] = $prefix_filename . 'languages.php';
$aContents['listcategories'] = $prefix_filename . 'listcategories.php';
$aContents['listproducts'] = $prefix_filename . 'listproducts.php';
$aContents['mail'] = $prefix_filename . 'mail.php';
$aContents['manual_loging'] = $prefix_filename . 'manual_loging.php';
$aContents['manufacturers'] = $prefix_filename . 'manufacturers.php';
$aContents['modules'] = $prefix_filename . 'modules.php';
$aContents['newsletters'] = $prefix_filename . 'newsletters.php';
$aContents['orders'] = $prefix_filename . 'orders.php';
$aContents['orders_status'] = $prefix_filename . 'orders_status.php';
$aContents['packingslip'] = $prefix_filename . 'packingslip.php';
$aContents['password_forgotten'] = $prefix_filename . 'password_forgotten.php';
$aContents['plugins'] = $prefix_filename . 'plugins.php';
$aContents['products'] = $prefix_filename . 'products.php';
$aContents['products_attributes'] = $prefix_filename . 'products_attributes.php';
$aContents['products_properties'] = $prefix_filename . 'products_properties.php';
$aContents['products_expected'] = $prefix_filename . 'products_expected.php';
$aContents['products_status'] = $prefix_filename . 'products_status.php';
$aContents['products_units'] = $prefix_filename . 'products_units.php';
$aContents['product_model_viewer'] = $prefix_filename . 'product_model_viewer.php';
$aContents['product_options'] = $prefix_filename . 'product_options.php';
$aContents['product_video'] = $prefix_filename . 'product_video.php';
$aContents['product_webgl_gltf'] = $prefix_filename . 'product_webgl_gltf.php';
$aContents['reviews'] = $prefix_filename . 'reviews.php';
$aContents['shipping_modules'] = $prefix_filename . 'shipping_modules.php';
$aContents['specials'] = $prefix_filename . 'specials.php';
$aContents['stats_customers'] = $prefix_filename . 'stats_customers.php';
$aContents['stats_low_stock'] = $prefix_filename . 'stats_low_stock.php';
$aContents['stats_products_purchased'] = $prefix_filename . 'stats_products_purchased.php';
$aContents['stats_products_viewed'] = $prefix_filename . 'stats_products_viewed.php';
$aContents['stats_sales_report2'] = $prefix_filename . 'stats_sales_report2.php';
$aContents['tax_classes'] = $prefix_filename . 'tax_classes.php';
$aContents['tax_rates'] = $prefix_filename . 'tax_rates.php';
$aContents['validcategories'] = $prefix_filename . 'validcategories.php';
$aContents['validproducts'] = $prefix_filename . 'validproducts.php';
$aContents['wastebasket'] = $prefix_filename . 'wastebasket.php';
$aContents['zones'] = $prefix_filename . 'zones.php';

//catalogLink

$prefix_catalog_filename = '';
if (!$prefix_catalog_filename == '') {
    $prefix_catalog_filename = $prefix_catalog_filename . '_';
}

$aCatalog = [];
$aCatalog['account_history_info'] = $prefix_catalog_filename . 'account_history_info';
$aCatalog['default'] = $prefix_catalog_filename . 'home';
$aCatalog['gv_redeem'] = $prefix_catalog_filename . 'gv_redeem';
$aCatalog['product_info'] = $prefix_catalog_filename . 'product_info';
$aCatalog['login_admin'] = $prefix_catalog_filename . 'admin_login';
$aCatalog['login'] = $prefix_catalog_filename . 'login';
