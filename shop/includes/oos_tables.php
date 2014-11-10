<?php
/* ----------------------------------------------------------------------
   $Id: oos_tables.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

  $prefix_table = OOS_DB_PREFIX;

  if (!$prefix_table == '') $prefix_table = $prefix_table . '_';

  // Initialise table array
  $oostable = array();

  $oostable['address_book'] = $prefix_table . 'address_book';
  $oostable['address_format'] = $prefix_table . 'address_format';
  $oostable['admin'] = $prefix_table . 'admin';
  $oostable['admin_files'] = $prefix_table . 'admin_files';
  $oostable['admin_groups'] = $prefix_table . 'admin_groups';
  $oostable['adodb_logsql'] = $prefix_table . 'logsql';
  $oostable['affiliate_affiliate'] = $prefix_table . 'affiliate_affiliate';
  $oostable['affiliate_banners'] = $prefix_table . 'affiliate_banners';
  $oostable['affiliate_banners_history'] = $prefix_table . 'affiliate_banners_history';
  $oostable['affiliate_clickthroughs'] = $prefix_table . 'affiliate_clickthroughs';
  $oostable['affiliate_sales'] = $prefix_table . 'affiliate_sales';
  $oostable['affiliate_payment'] = $prefix_table . 'affiliate_payment';
  $oostable['affiliate_payment_status'] = $prefix_table . 'affiliate_payment_status';
  $oostable['affiliate_payment_status_history'] = $prefix_table . 'affiliate_payment_status_history';
  $oostable['banktransfer'] = $prefix_table . 'banktransfer';
  $oostable['banktransfer_blz'] = $prefix_table . 'banktransfer_blz';
  $oostable['banners'] = $prefix_table . 'banners';
  $oostable['banners_history'] = $prefix_table . 'banners_history';
  $oostable['block'] = $prefix_table . 'block';
  $oostable['block_info'] = $prefix_table . 'block_info';
  $oostable['block_to_page_type'] = $prefix_table . 'block_to_page_type';
  $oostable['campaigns'] = $prefix_table . 'campaigns';
  $oostable['categories'] = $prefix_table . 'categories';
  $oostable['categories_description'] = $prefix_table . 'categories_description';
  $oostable['configuration'] = $prefix_table . 'configuration';
  $oostable['configuration_group'] = $prefix_table . 'configuration_group';
  $oostable['counter'] = $prefix_table . 'counter';
  $oostable['counter_history'] = $prefix_table . 'counter_history';
  $oostable['countries'] = $prefix_table . 'countries';
  $oostable['coupons'] = $prefix_table . 'coupons';
  $oostable['coupons_description'] = $prefix_table . 'coupons_description';
  $oostable['coupon_email_track'] = $prefix_table . 'coupon_email_track';
  $oostable['coupon_gv_customer'] = $prefix_table . 'coupon_gv_customer';
  $oostable['coupon_gv_queue'] = $prefix_table . 'coupon_gv_queue';
  $oostable['coupon_redeem_track'] = $prefix_table . 'coupon_redeem_track';
  $oostable['currencies'] = $prefix_table . 'currencies';
  $oostable['customers'] = $prefix_table . 'customers';
  $oostable['customers_basket'] = $prefix_table . 'customers_basket';
  $oostable['customers_basket_attributes'] = $prefix_table . 'customers_basket_attributes';
  $oostable['customers_info'] = $prefix_table . 'customers_info';
  $oostable['customers_status'] = $prefix_table . 'customers_status';
  $oostable['customers_status_history'] = $prefix_table . 'customers_status_history';
  $oostable['customers_wishlist'] = $prefix_table . 'customers_wishlist'; 
  $oostable['customers_wishlist_attributes'] = $prefix_table . 'customers_wishlist_attributes';
  $oostable['featured'] = $prefix_table . 'featured';
  $oostable['files_uploaded'] = $prefix_table . 'files_uploaded';
  $oostable['geo_zones'] = $prefix_table . 'geo_zones';
  $oostable['information'] = $prefix_table . 'information';
  $oostable['information_description'] = $prefix_table . 'information_description';
  $oostable['languages'] = $prefix_table . 'languages';
  $oostable['link_categories'] = $prefix_table . 'link_categories';
  $oostable['link_categories_description'] = $prefix_table . 'link_categories_description';
  $oostable['links'] = $prefix_table . 'links';
  $oostable['links_description'] = $prefix_table . 'links_description';
  $oostable['links_status'] = $prefix_table . 'links_status';
  $oostable['links_to_link_categories'] = $prefix_table . 'links_to_link_categories';
  $oostable['maillist'] = $prefix_table . 'maillist';
  $oostable['manual_info'] = $prefix_table . 'manual_info';
  $oostable['manufacturers'] = $prefix_table . 'manufacturers';
  $oostable['manufacturers_info'] = $prefix_table . 'manufacturers_info';
  $oostable['news'] = $prefix_table . 'news';
  $oostable['news_categories'] = $prefix_table . 'news_categories';
  $oostable['news_categories_description'] = $prefix_table . 'news_categories_description';
  $oostable['news_description'] = $prefix_table . 'news_description';
  $oostable['news_reviews'] = $prefix_table . 'news_reviews';
  $oostable['news_reviews_description'] = $prefix_table . 'news_reviews_description';
  $oostable['news_to_categories'] = $prefix_table . 'news_to_categories';
  $oostable['newsletters'] = $prefix_table . 'newsletters';
  $oostable['newsfeed'] = $prefix_table . 'newsfeed';
  $oostable['newsfeed_categories'] = $prefix_table . 'newsfeed_categories';
  $oostable['newsfeed_info'] = $prefix_table . 'newsfeed_info';
  $oostable['newsfeed_manager'] = $prefix_table . 'newsfeed_manager';
  $oostable['orders'] = $prefix_table . 'orders';
  $oostable['orders_products'] = $prefix_table . 'orders_products';
  $oostable['orders_products_attributes'] = $prefix_table . 'orders_products_attributes';
  $oostable['orders_products_download'] = $prefix_table . 'orders_products_download';
  $oostable['orders_status'] = $prefix_table . 'orders_status';
  $oostable['orders_status_history'] = $prefix_table . 'orders_status_history';
  $oostable['orders_total'] = $prefix_table . 'orders_total';
  $oostable['page_type'] = $prefix_table . 'page_type';
  $oostable['products'] = $prefix_table . 'products';
  $oostable['products_attributes'] = $prefix_table . 'products_attributes';
  $oostable['products_attributes_download'] = $prefix_table . 'products_attributes_download';
  $oostable['products_description'] = $prefix_table . 'products_description';
  $oostable['products_notifications'] = $prefix_table . 'products_notifications';
  $oostable['products_options'] = $prefix_table . 'products_options';
  $oostable['products_options_types'] = $prefix_table . 'products_options_types';
  $oostable['products_options_values'] = $prefix_table . 'products_options_values';
  $oostable['products_options_values_to_products_options'] = $prefix_table . 'products_options_values_to_products_options';
  $oostable['products_status'] = $prefix_table . 'products_status';
  $oostable['products_to_categories'] = $prefix_table . 'products_to_categories';
  $oostable['products_to_master'] = $prefix_table . 'products_to_master';
  $oostable['products_units'] = $prefix_table . 'products_units';
  $oostable['products_up_sell'] = $prefix_table . 'products_up_sell';
  $oostable['products_xsell'] = $prefix_table . 'products_xsell';
  $oostable['recovercartsales'] = $prefix_table . 'recovercartsales';
  $oostable['referer'] = $prefix_table . 'referer';
  $oostable['reviews'] = $prefix_table . 'reviews';
  $oostable['reviews_description'] = $prefix_table . 'reviews_description';
  $oostable['search_queries'] = $prefix_table . 'search_queries';
  $oostable['search_queries_sorted'] = $prefix_table . 'search_queries_sorted';
  $oostable['searchword_swap'] = $prefix_table . 'searchword_swap';
  $oostable['sessions'] = $prefix_table . 'sessions';
  $oostable['specials'] = $prefix_table . 'specials';
  $oostable['spelling_words'] = $prefix_table . 'spelling_words';
  $oostable['tax_class'] = $prefix_table . 'tax_class';
  $oostable['tax_rates'] = $prefix_table . 'tax_rates';
  $oostable['ticket_admins'] = $prefix_table . 'ticket_admins';
  $oostable['ticket_department'] = $prefix_table . 'ticket_department';
  $oostable['ticket_priority'] = $prefix_table . 'ticket_priority';
  $oostable['ticket_reply'] = $prefix_table . 'ticket_reply';
  $oostable['ticket_status'] = $prefix_table . 'ticket_status';
  $oostable['ticket_status_history'] = $prefix_table . 'ticket_status_history';
  $oostable['ticket_ticket'] = $prefix_table . 'ticket_ticket';
  $oostable['geo_zones'] = $prefix_table . 'geo_zones';
  $oostable['zones_to_geo_zones'] = $prefix_table . 'zones_to_geo_zones';
  $oostable['whos_online'] = $prefix_table . 'whos_online';
  $oostable['zones'] = $prefix_table . 'zones';

?>