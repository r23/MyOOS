<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_top.php,v 1.264 2003/02/17 16:37:52 hpdl
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

$prefix_filename = '';
if (!$prefix_filename == '') {
    $prefix_filename = $prefix_filename . '_';
}

$aContents = [];
$aContents = [
'conditions_download'             => $prefix_filename . 'conditions.pdf',
//account
'account_history'                 => $prefix_filename . 'account_history',
'account_history_info'            => $prefix_filename . 'account_history_info',
'account_address_book'            => $prefix_filename . 'account_address_book',
'account_address_book_process'    => $prefix_filename . 'account_address_book_process',
'account_wishlist'                => $prefix_filename . 'account_wishlist',
'create_google2fa'                => $prefix_filename . 'create_2fa',
'disabled_google2fa'              => $prefix_filename . 'disabled_2fa',
//admin
'admin_login'                     => $prefix_filename . 'admin_login',
//gv
'gv_faq'                          => $prefix_filename . 'gv_faq',
'gv_redeem'                       => $prefix_filename . 'gv_redeem',
'popup_coupon_help'               => $prefix_filename . 'popup_coupon_help',
//info
'info_down_for_maintenance'       => $prefix_filename . 'info_down_for_maintenance',
'info_max_order'                  => $prefix_filename . 'info_max_order',
'sitemap'                         => $prefix_filename . 'sitemap',
'information'                     => $prefix_filename . 'information',
'403'                             => $prefix_filename . 'error403',
'404'                             => $prefix_filename . 'error404',
//main
'home'                            => $prefix_filename . 'home',
'shop'                            => $prefix_filename . 'shop',
'panorama'                        => $prefix_filename . 'panorama',
'redirect'                        => $prefix_filename . 'redirect',
'shopping_cart'                   => $prefix_filename . 'shopping_cart',
'contact_us'                      => $prefix_filename . 'contact_us',
//newsletter
'newsletter'                      => $prefix_filename . 'newsletter',
//products
'product_info'                    => $prefix_filename . 'product_info',
'product_info_webgl_gltf'          => $prefix_filename . 'product_info_webgl_gltf',
'products_new'                    => $prefix_filename . 'products_new',
'specials'                        => $prefix_filename . 'specials',
//pub
'download'                        => $prefix_filename . 'download',
//reviews
'reviews'                         => $prefix_filename . 'reviews',
'product_reviews'                 => $prefix_filename . 'product_reviews',
'product_reviews_info'            => $prefix_filename . 'product_reviews_info',
'product_reviews_write'           => $prefix_filename . 'product_reviews_write',
//search
'advanced_search'                 => $prefix_filename . 'advanced_search',
'advanced_search_result'          => $prefix_filename . 'advanced_search_result',
//user
'account'                         => $prefix_filename . 'account',
'account_edit'                    => $prefix_filename . 'account_edit',
'create_account'                  => $prefix_filename . 'create_account',
'create_account_success'          => $prefix_filename . 'create_account_success',
'create_2fa'                      => $prefix_filename . 'create_2fa',
'login'                           => $prefix_filename . 'login',
'login_2fa_info'                  => $prefix_filename . 'login_2fa_info',
'login_2fa'                       => $prefix_filename . 'login_2fa',
'login_process'                   => $prefix_filename . 'login_process',
'logoff'                          => $prefix_filename . 'logoff',
'password_forgotten'              => $prefix_filename . 'password_forgotten',
'product_notifications'           => $prefix_filename . 'product_notifications',
//checkout
'checkout_confirmation'           => $prefix_filename . 'checkout_confirmation',
'checkout_payment'                => $prefix_filename . 'checkout_payment',
'checkout_payment_address'        => $prefix_filename . 'checkout_payment_address',
'checkout_process'                => $prefix_filename . 'checkout_process',
'checkout_shipping'               => $prefix_filename . 'checkout_shipping',
'checkout_shipping_address'       => $prefix_filename . 'checkout_shipping_address',
'checkout_success'                => $prefix_filename . 'checkout_success',
//ajax
'clear_cart_ajax'                 => $prefix_filename . 'clear_cart',
'shopping_cart_ajax'              => $prefix_filename . 'shopping_cart'
];
