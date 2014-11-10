<?php
/* ----------------------------------------------------------------------
   $Id: max_order.php,v 1.1 2007/06/13 17:03:54 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
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
define('TEXT_INFORMATION', 'You are '.$currencies->format ($cart->show_total() - (+$max_order)) .' above your ' . $oCurrencies->format($max_order) . ' Credit Limit. <br />Please contact our Sales Team to confirm your order.');

?>
