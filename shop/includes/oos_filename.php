<?php
/* ----------------------------------------------------------------------
   $Id: oos_filename.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.264 2003/02/17 16:37:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$aContents = array();
$prefix_filename = '';
if (!$prefix_filename == '') $prefix_filename = $prefix_filename . '_';

$aContents['conditions_download'] = $prefix_filename . 'conditions.pdf';

//includes/content/account
$aContents['account_history'] = $prefix_filename . 'account_history';
$aContents['account_history_info'] = $prefix_filename . 'account_history_info';
$aContents['account_address_book'] = $prefix_filename . 'account_address_book';
$aContents['account_address_book_process'] = $prefix_filename . 'account_address_book_process';
$aContents['account_my_wishlist'] = $prefix_filename . 'account_my_wishlist';
$aContents['account_order_history'] = $prefix_filename . 'account_order_history';

//includes/content/admin
$aContents['admin_create_account'] = $prefix_filename . 'admin_create_account';
$aContents['admin_create_account_process'] = $prefix_filename . 'admin_create_account_process';
$aContents['admin_login'] = $prefix_filename . 'admin_login';

//includes/content/gv
$aContents['gv_faq'] = $prefix_filename . 'gv_faq';
$aContents['gv_redeem'] = $prefix_filename . 'gv_redeem';
$aContents['gv_send'] = $prefix_filename . 'gv_send';
$aContents['popup_coupon_help'] = $prefix_filename . 'popup_coupon_help';

//includes/content/info
$aContents['info_down_for_maintenance'] = $prefix_filename . 'down_for_maintenance';
$aContents['info_max_order'] = $prefix_filename . 'max_order';
$aContents['info_sitemap'] = $prefix_filename . 'sitemap';
$aContents['information'] = $prefix_filename . 'information';


//includes/content/main
$aContents['main'] = $prefix_filename . 'main';
$aContents['shop'] = $prefix_filename . 'shop';
$aContents['redirect'] = $prefix_filename . 'redirect';
$aContents['main_shopping_cart'] = $prefix_filename . 'main_shopping_cart';
$aContents['info_autologon'] = $prefix_filename . 'info_autologon'; 
$aContents['info_shopping_cart'] = $prefix_filename . 'info_shopping_cart';
$aContents['main_wishlist'] = $prefix_filename . 'main_wishlist.php';
$aContents['contact_us'] = $prefix_filename . 'contact_us';

//includes/content/newsletters
$aContents['newsletters'] = $prefix_filename . 'newsletters';
$aContents['newsletters_subscribe_success'] = $prefix_filename . 'newsletters_subscribe_success';
$aContents['newsletters_unsubscribe_success'] = $prefix_filename . 'newsletters_unsubscribe_success';
$aContents['subscription_center'] = $prefix_filename . 'subscription_center';

//includes/content/products
$aContents['cross_sell'] = $prefix_filename . 'cross_sell';
$aContents['product_info'] = $prefix_filename . 'product_info';
$aContents['products_new'] = $prefix_filename . 'products_new';
$aContents['specials'] = $prefix_filename . 'specials';
$aContents['popup_image'] = $prefix_filename . 'popup_image';


//includes/content/pub
$aContents['download'] = $prefix_filename . 'download';

//includes/content/reviews
$aContents['reviews_reviews'] = $prefix_filename . 'reviews';
$aContents['product_reviews'] = $prefix_filename . 'product_reviews';
$aContents['product_reviews_info'] = $prefix_filename . 'product_reviews_info';
$aContents['product_reviews_write'] = $prefix_filename . 'product_reviews_write';

//includes/content/search
$aContents['advanced_search'] = $prefix_filename . 'advanced_search';
$aContents['advanced_search_result'] = $prefix_filename . 'advanced_search_result';
$aContents['popup_search_help'] = $prefix_filename . 'popup_search_help';
$aContents['quickfind'] = $prefix_filename . 'quickfind';

//includes/content/tell_a_friend
$aContents['tell_a_friend'] = $prefix_filename . 'tell_a_friend';

//includes/content/user
$aContents['account'] = $prefix_filename . 'account';
$aContents['account_edit'] = $prefix_filename . 'account_edit';
$aContents['account_edit_process'] = $prefix_filename . 'account_edit_process';
$aContents['create_account'] = $prefix_filename . 'create_account'; 
$aContents['create_account_process'] = $prefix_filename . 'create_account_process';
$aContents['create_account_success'] = $prefix_filename . 'create_account_success';
$aContents['login'] = $prefix_filename . 'login';
$aContents['logoff'] = $prefix_filename . 'logoff';
$aContents['password_forgotten'] = $prefix_filename . 'password_forgotten';
$aContents['product_notifications'] = $prefix_filename . 'product_notifications';
$aContents['yourstore'] = $prefix_filename . 'yourstore';
$aContents['customers_image'] = $prefix_filename . 'customers_image';

//includes/content/checkout
$aContents['checkout_confirmation'] = $prefix_filename . 'checkout_confirmation';
$aContents['checkout_payment'] = $prefix_filename . 'checkout_payment';
$aContents['checkout_payment_address'] = $prefix_filename . 'checkout_payment_address';
$aContents['checkout_process'] = $prefix_filename . 'checkout_process';
$aContents['checkout_shipping'] = $prefix_filename . 'checkout_shipping';
$aContents['checkout_shipping_address'] = $prefix_filename . 'checkout_shipping_address';
$aContents['checkout_success'] = $prefix_filename . 'checkout_success';
