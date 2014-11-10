<?php
/* ----------------------------------------------------------------------
   $Id: oos_filename.php,v 1.2 2008/01/27 10:19:10 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  $aFilename = array();
  $prefix_filename = '';
  if (!$prefix_filename == '') $prefix_filename =  $prefix_filename . '_';

  $aFilename['conditions_download'] = $prefix_filename . 'conditions.pdf';

  //includes/pages/account
  $aFilename['account_history'] = $prefix_filename . 'history';
  $aFilename['account_history_info'] = $prefix_filename . 'history_info';
  $aFilename['account_address_book'] = $prefix_filename . 'address_book';
  $aFilename['account_address_book_process'] = $prefix_filename . 'address_book_process';
  $aFilename['account_my_wishlist'] = $prefix_filename . 'my_wishlist';
  $aFilename['account_order_history'] = $prefix_filename . 'order_history';

  //includes/pages/admin
  $aFilename['admin_create_account'] = $prefix_filename . 'create_account';
  $aFilename['admin_create_account_process'] = $prefix_filename . 'create_account_process';
  $aFilename['admin_login'] = $prefix_filename . 'login';

  //includes/pages/gv
  $aFilename['gv_faq'] = $prefix_filename . 'faq';
  $aFilename['gv_redeem'] = $prefix_filename . 'redeem';
  $aFilename['gv_send'] = $prefix_filename . 'send';
  $aFilename['popup_coupon_help'] = $prefix_filename . 'popup_coupon_help';

  //includes/pages/info
  $aFilename['info_down_for_maintenance'] = $prefix_filename . 'down_for_maintenance';
  $aFilename['info_max_order'] = $prefix_filename . 'max_order';
  $aFilename['info_newsfeed'] = $prefix_filename . 'newsfeed';
  $aFilename['info_sitemap'] = $prefix_filename . 'sitemap';
  $aFilename['info_vcard'] = $prefix_filename . 'vcard';
  $aFilename['information'] = $prefix_filename . 'information';
  $aFilename['info_directions'] = $prefix_filename . 'directions';
  $aFilename['info_skype'] = $prefix_filename . 'skype';
  $aFilename['info_gallery'] = $prefix_filename . 'gallery';

  //includes/pages/main
  $aFilename['main'] = $prefix_filename . 'main';
  $aFilename['shop'] = $prefix_filename . 'shop';
  $aFilename['redirect'] = $prefix_filename . 'redirect';
  $aFilename['main_shopping_cart'] = $prefix_filename . 'shopping_cart';
  $aFilename['info_autologon'] = $prefix_filename . 'info_autologon'; 
  $aFilename['info_shopping_cart'] = $prefix_filename . 'info_shopping_cart';
  $aFilename['main_wishlist'] = $prefix_filename . 'wishlist.php';
  $aFilename['contact_us'] = $prefix_filename . 'old_contact_us';



  //includes/pages/newsletters
  $aFilename['newsletters'] = $prefix_filename . 'newsletters';
  $aFilename['newsletters_subscribe_success'] = $prefix_filename . 'subscribe_success';
  $aFilename['newsletters_unsubscribe_success'] = $prefix_filename . 'unsubscribe_success';
  $aFilename['subscription_center'] = $prefix_filename . 'subscription_center';

  //includes/pages/products
  $aFilename['cross_sell'] = $prefix_filename . 'cross_sell';
  $aFilename['product_info'] = $prefix_filename . 'info';
  $aFilename['products_new'] = $prefix_filename . 'new';
  $aFilename['products_rss'] = $prefix_filename . 'rss';
  $aFilename['product_zoom'] = $prefix_filename . 'zoom';
  $aFilename['product_movie'] = $prefix_filename . 'movie';
  $aFilename['specials'] = $prefix_filename . 'specials';
  $aFilename['product_info_pdf'] = $prefix_filename . 'pdf_info';
  $aFilename['popup_image'] = $prefix_filename . 'popup_image';
  $aFilename['popup_print'] = $prefix_filename . 'popup_print';

  //includes/pages/pub
  $aFilename['download'] = $prefix_filename . 'download';

  //includes/pages/reviews
  $aFilename['reviews_reviews'] = $prefix_filename . 'reviews';
  $aFilename['product_reviews'] = $prefix_filename . 'product';
  $aFilename['product_reviews_info'] = $prefix_filename . 'product_info';
  $aFilename['product_reviews_write'] = $prefix_filename . 'product_write';

  //includes/pages/search
  $aFilename['advanced_search'] = $prefix_filename . 'advanced';
  $aFilename['advanced_search_result'] = $prefix_filename . 'advanced_result';
  $aFilename['popup_search_help'] = $prefix_filename . 'popup_help';
  $aFilename['quickfind'] = $prefix_filename . 'quickfind';


  //includes/pages/web
  $aFilename['search_result'] = $prefix_filename . 'search_result';


  //includes/pages/user
  $aFilename['account'] = $prefix_filename . 'account';
  $aFilename['account_edit'] = $prefix_filename . 'account_edit';
  $aFilename['account_edit_process'] = $prefix_filename . 'account_edit_process';
  $aFilename['create_account'] = $prefix_filename . 'create_account'; 
  $aFilename['create_account_process'] = $prefix_filename . 'create_account_process';
  $aFilename['create_account_success'] = $prefix_filename . 'create_account_success';
  $aFilename['login'] = $prefix_filename . 'login';
  $aFilename['logoff'] = $prefix_filename . 'logoff';
  $aFilename['password_forgotten'] = $prefix_filename . 'password_forgotten';
  $aFilename['product_notifications'] = $prefix_filename . 'product_notifications';
  $aFilename['yourstore'] = $prefix_filename . 'yourstore';
  $aFilename['customers_image'] = $prefix_filename . 'customers_image';

  //includes/pages/checkout
  $aFilename['checkout_confirmation'] = $prefix_filename . 'confirmation';
  $aFilename['checkout_payment'] = $prefix_filename . 'payment';
  $aFilename['checkout_payment_address'] = $prefix_filename . 'payment_address';
  $aFilename['checkout_process'] = $prefix_filename . 'process';
  $aFilename['checkout_shipping'] = $prefix_filename . 'shipping';
  $aFilename['checkout_shipping_address'] = $prefix_filename . 'shipping_address';
  $aFilename['checkout_success'] = $prefix_filename . 'success';

