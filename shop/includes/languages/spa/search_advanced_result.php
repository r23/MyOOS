<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced_result.php,v 1.1 2007/06/13 15:54:26 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search_result.php,v 1.12 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title1'] = 'Bsqueda';
$aLang['navbar_title2'] = 'Resultados de la Bsqueda';
$aLang['heading_title'] = 'Productos que satisfacen los criterios de bsqueda';
$aLang['table_heading_image'] = '';
$aLang['table_heading_model'] = 'Modelo';
$aLang['table_heading_products'] = 'Descripcion';
$aLang['table_heading_manufacturer'] = 'Fabricante';
$aLang['table_heading_quantity'] = 'Cantidad';
$aLang['table_heading_price'] = 'Precio';
$aLang['table_heading_weight'] = 'Peso';
$aLang['table_heading_buy_now'] = 'Compre Ahora';
$aLang['table_heading_product_sort'] = 'Sort';
$aLang['text_no_products'] = '<br /><span style="font-size:11px;">Your search - <b>' . stripslashes($_GET['keywords']) . '</b> - did not match any products.</span><br /><br />Some Suggestions:<ol><li>Check that your spelling was accurate.</li><li>Try using different keywords</li><li>Try using fewer keywords</li><li>Try using more general keywords</li></ol>';
$aLang['text_no_products2'] = '<br /><span style="font-size:11px;">Your search - <b>' . stripslashes($_GET['keywords']) . '</b> - did not match any products.</span><br /><br />Some Suggestions:<ol><li>Check that your spelling was accurate.</li><li>Try using different keywords</li><li>Try using fewer keywords</li></ol>';
$aLang['text_buy'] = 'Compre 1 \'';
$aLang['text_now'] = '\' ahora';
$aLang['text_replacement_suggestion'] = 'You could also try: ';
?>
