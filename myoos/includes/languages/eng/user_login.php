<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de
   
   
   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: login.php,v 1.11 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (isset($_GET['origin']) && ($_GET['origin'] == $aContents['checkout_payment'])) {
  $aLang['navbar_title'] = 'Order';
  $aLang['heading_title'] = 'Ordering online is easy.';
} else {
  $aLang['navbar_title'] = 'Login';
  $aLang['heading_title'] = 'Welcome, Please Sign In';
}

$aLang['heading_new_customer'] = 'New Customer';
$aLang['text_new_customer'] = 'Not registered?';
$aLang['link_new_customer'] = 'Create an Account.';

$aLang['heading_returning_customer'] = 'Returning Customer';
$aLang['text_returning_customer'] = 'I am a returning customer.';

$aLang['text_password_forgotten'] = 'Lost your password?';
$aLang['link_password_forgotten'] = 'Click here to recover.';


$aLang['text_login_error'] = '<strong>ERROR:</strong> No match for \'E-Mail-Address\' and/or \'Password\'.';
$aLang['text_visitors_cart'] = '<strong>NOTE:</strong> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. [More Info]</a>';

$aLang['sub_heading_title'] = 'Visitors Cart / Members Cart';
$aLang['sub_heading_title_1'] = 'Visitors Cart';
$aLang['sub_heading_title_2'] = 'Members Cart';
$aLang['sub_heading_title_3'] = 'Info';
$aLang['sub_heading_text_1'] = 'Every visitor to our online shop will be given a \'Visitors Cart\'. This allows the visitor to store their products in a temporary shopping cart. Once the visitor leaves the online shop, so will the contents of their shopping cart.';
$aLang['sub_heading_text_2'] = 'Every member to our online shop that logs in is given a \'Members Cart\'. This allows the member to add products to their shopping cart, and come back at a later date to finalize their checkout. All products remain in their shopping cart until the member has  checked them out, or removed the products themselves.';
$aLang['sub_heading_text_3'] = 'If a member adds products to their \'Visitors Cart\' and decides to log in to the online shop to use their \'Members Cart\', the contents of their \'Visitors Cart\' will merge with their \'Members Cart\' contents automatically.';
$aLang['text_close_window'] = '[ close window ]';
