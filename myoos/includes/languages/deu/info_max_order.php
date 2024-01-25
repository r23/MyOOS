<?php
/**
   ----------------------------------------------------------------------
   $Id: info_max_order.php,v 1.3 2007/06/12 16:36:39 r23 Exp $

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

$aLang['navbar_title'] = 'Kreditlimit';
$aLang['heading_title'] = 'Kreditlimit';

$aLang['text_information'] = 'Sie sind '. $oCurrencies->format($_SESSION['cart']->show_total() - (+$_SESSION['customer_max_order'])) .' über Ihrem ' . $oCurrencies->format($_SESSION['customer_max_order']) . ' Kreditlimit. <br />Bitte kontaktieren Sie unser Verkaufsteam, um Ihre Bestellung zu bestätigen!';
