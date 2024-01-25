<?php
/**
   ----------------------------------------------------------------------
   $Id: max_order.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

define('NAVBAR_TITLE', 'Maximaler Bestellwert');
define('HEADING_TITLE', 'Maximaler Bestellwert');
define('TEXT_INFORMATION', 'Sie sind '. $currencies->format($cart->show_total() - (+$customer_max_order)) .' über Ihrem ' . $currencies->format($customer_max_order) . ' maximalen Bestellwert. <br>Bitte kontaktieren Sie unser Verkaufsteam, um Ihre Bestellung bestätigen zu lassen.');
