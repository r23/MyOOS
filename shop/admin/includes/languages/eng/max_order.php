<?php
/* ----------------------------------------------------------------------
   $Id: max_order.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: max_order.php v1.00 2003/04/27 JOHNSON   
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
  
   Max Order - 2003/04/27 JOHNSON - Copyright (c) 2003 Matti Ressler - mattifinn@optusnet.com.au
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('NAVBAR_TITLE', 'Maximum order');
define('HEADING_TITLE', 'Maximum order');
define('TEXT_INFORMATION', 'You are '. $currencies->format ($cart->show_total() - (+$max_order)) .' above your ' . $currencies->format($max_order) . ' Credit Limit. <br />Please contact our Sales Team to confirm your order.');

?>
