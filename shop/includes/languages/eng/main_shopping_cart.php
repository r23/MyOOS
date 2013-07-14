<?php
/* ----------------------------------------------------------------------
   $Id: main_shopping_cart.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shopping_cart.php,v 1.13 2002/04/05 20:24:02 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Cart Contents';
$aLang['heading_title'] = 'What\'s In My Cart?';
$aLang['table_heading_remove'] = 'Remove';
$aLang['table_heading_quantity'] = 'Qty.';
$aLang['table_heading_model'] = 'Model';
$aLang['table_heading_products'] = 'Product(s)';
$aLang['table_heading_total'] = 'Total';
$aLang['text_cart_empty'] = 'Your Shopping Cart is empty!';
$aLang['sub_title_sub_total'] = 'Sub-Total:';
$aLang['sub_title_total'] = 'Total:';

$aLang['out_of_stock_cant_checkout'] = 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br />Please alter the quantity of products marked with (' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '), Thank you';
$aLang['out_of_stock_can_checkout'] = 'Products marked with ' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . ' dont exist in desired quantity in our stock.<br />You can buy them anyway and check the quantity we have in stock for immediate deliver in the checkout process.';
?>
