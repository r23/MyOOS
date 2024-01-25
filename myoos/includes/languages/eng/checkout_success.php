<?php
/**
   ----------------------------------------------------------------------
   $Id: checkout_success.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_success.php,v 1.11 2002/11/01 04:27:01 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title_1'] = 'Checkout';
$aLang['navbar_title_2'] = 'Success';

$aLang['heading_title'] = 'Your Order Has Been Processed!';

$aLang['text_success'] = 'Your order has been successfully processed! Your products will arrive at their destination within 2-5 working days.';
$aLang['text_notify_products'] = 'Please notify me of updates to the products I have selected below:';
$aLang['text_see_orders'] = 'You can view your order history by going to the <a href="' . oos_href_link($aContents['account']) . '">\'My Account\'</a> page and by clicking on <a href="' . oos_href_link($aContents['account_history']) . '">\'History\'</a>.';
$aLang['text_contact_store_owner'] = 'Please direct any questions you have to the <a href="' . oos_href_link($aContents['contact_us']) . '">store owner</a>.';
$aLang['text_thanks_for_shopping'] = 'Thanks for shopping with us online!';

$aLang['table_heading_download_date'] = 'Expiry date: ';
$aLang['table_heading_download_count'] = ' downloads remain.';
$aLang['heading_download'] = 'Download your products here:';
$aLang['footer_download'] = 'You can also download your products at a later time at \'%s\'';

$aLang['text_sincere_regards'] = 'Thank you very much for choosing us,';
