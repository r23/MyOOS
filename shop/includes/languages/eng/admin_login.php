<?php
/* ----------------------------------------------------------------------
   $Id: admin_login.php,v 1.3 2007/06/12 16:51:19 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Login';
$aLang['heading_title'] = 'Admin, Please Sign In';
$aLang['entry_key'] = 'Secure Key'; // should be empty
$aLang['heading_admin_login'] = 'Login under Customer account';
$aLang['entry_email_address'] = 'E-Mail Address:';
$aLang['text_login_error'] = '<font color="#ff0000"><b>ERROR:</b></font> No match for \'E-Mail Address\' and/or \'Password\'.';
$aLang['text_login_error2'] = '<font color="#ff0000"><b>NO ACCESS GRANTED: Due to prior chargebacks or other fraud, your account is blacklisted on our site.<br /><br /> We no longer accept online orders from this account.<br /><br />If you wish to order, you will need to get in touch with us by phone and pay by money order or bank transfer before we ship.<br /><br />Order will be shipped with Signature Confirmation surcahrge.<br /><br /> We will need to receive by mail or fax a SIGNED purchase order before shipping.<br /><br />The order will be shipped to a verified address only. </b></font><br /><br />';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>NOTE:</b></font> Your &quot;Visitors Cart&quot; contents will be merged with your &quot;Members Cart&quot; contents once you have logged on. <a href="javascript:session_win(\'' . oos_href_link($aContents['info_shopping_cart']) . '\');">[More Info]</a>';

