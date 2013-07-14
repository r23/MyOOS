<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced_result.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search_result.php,v 1.10 2002/11/19 01:48:08 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title1'] = 'Search';
$aLang['navbar_title2'] = 'Search Results';
$aLang['heading_title'] = 'Products meeting the search criteria';
$aLang['table_heading_image'] = '';
$aLang['table_heading_model'] = 'Model';
$aLang['table_heading_products'] = 'Product Name';
$aLang['table_heading_manufacturer'] = 'Manufacturer';
$aLang['table_heading_quantity'] = 'Quantity';
$aLang['table_heading_list_price'] = 'List';
$aLang['table_heading_price'] = 'Price';
$aLang['table_heading_weight'] = 'Weight';
$aLang['table_heading_buy_now'] = 'Buy Now';
$aLang['table_heading_product_sort'] = 'Sort';
$aLang['text_no_products'] = '<br /><span style="font-size:11px;">Your search - <b>' . stripslashes($_GET['keywords']) . '</b> - did not match any products.</span><br /><br />Some Suggestions:<ol><li>Check that your spelling was accurate.</li><li>Try using different keywords</li><li>Try using fewer keywords</li><li>Try using more general keywords</li></ol>';
$aLang['text_no_products2'] = '<br /><span style="font-size:11px;">Your search - <b>' . stripslashes($_GET['keywords']) . '</b> - did not match any products.</span><br /><br />Some Suggestions:<ol><li>Check that your spelling was accurate.</li><li>Try using different keywords</li><li>Try using fewer keywords</li></ol>';
$aLang['text_buy'] = 'Buy 1 \'';
$aLang['text_now'] = '\' now';
$aLang['text_replacement_suggestion'] = 'You could also try: ';
?>
