<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

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
$aLang['text_new_customer_introduction'] = 'By creating an account at ' . STORE_NAME . ' you will be able to shop faster, be up to date on an orders status, and keep track of the orders you have previously made.';

$aLang['heading_returning_customer'] = 'Returning Customer';
$aLang['text_returning_customer'] = 'I am a returning customer.';

$aLang['entry_remember_me'] = 'Remember me<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon(\'' . oos_href_link($aContents['info_autologon']) . '\');"><b><u>Read this first!</u></b></a>';
$aLang['text_password_forgotten'] = 'Password forgotten? Click here.';

$aLang['text_login_error'] = '<font color="#ff0000"><b>ERROR:</b></font> No match for \'E-Mail Address\' and/or \'Password\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>NOTE:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win(\'' . oos_href_link($aContents['info_shopping_cart']) . '\');">[More Info]</a>';

