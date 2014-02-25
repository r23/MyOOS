<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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
$aLang['text_new_customer'] = 'I am a new customer.';
$aLang['text_new_customer_introduction'] = 'By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.';

$aLang['heading_returning_customer'] = 'Returning Customer';
$aLang['text_returning_customer'] = 'I am a returning customer.';

$aLang['title_guest'] = 'Order with a guest account';
$aLang['text_guest'] = 'No private data is stored and no private account will be created.<br /><br />If you return for another order, all details need to be filled in again.';


$aLang['text_login_error'] = '<font color="#ff0000"><b>ERROR:</b></font> No match for \'E-Mail Address\' and/or \'Password\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>NOTE:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win(\'' . oos_href_link($aContents['info_shopping_cart']) . '\');">[More Info]</a>';

