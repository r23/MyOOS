<?php
/* ----------------------------------------------------------------------
   $Id: weight.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: weight.php,v 1.02 2003/02/18 03:25:00 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_SHIPPING_WEIGHT_STATUS_TITLE', 'Aktivieren der gew. Versandkosten');
define('MODULE_SHIPPING_WEIGHT_STATUS_DESC', 'Möchten Sie gewichtsabhängige Versandkosten anbieten?');

define('MODULE_SHIPPING_WEIGHT_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_WEIGHT_HANDLING_DESC', 'Bearbeitungsgebühr für diese Versandart.');

define('MODULE_SHIPPING_WEIGHT_TAX_CLASS_TITLE', 'Steuersatz');
define('MODULE_SHIPPING_WEIGHT_TAX_CLASS_DESC', 'Wählen Sie den MwSt.-Satz für diese Versandart aus.');

define('MODULE_SHIPPING_WEIGHT_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_WEIGHT_ZONE_DESC', 'Geben Sie <b>einzeln</b> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_WEIGHT_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_WEIGHT_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_SHIPPING_WEIGHT_COST_TITLE', 'Versandkostentabelle');
define('MODULE_SHIPPING_WEIGHT_COST_DESC', 'Versandkosten nach beliebigem Gewicht gestaffelt. z.B.: 31:15,40:28,50:30.5,100:33 bis 31kg->15 EUR, von 31-40kg->28 EUR, von 40-50kg->30.5 EUR und von 50-100kg->33 EUR. Von da an wird der "Erhöhungsschritt" benutzt!');

define('MODULE_SHIPPING_WEIGHT_STEP_TITLE', 'Erhöhungsschritt');
define('MODULE_SHIPPING_WEIGHT_STEP_DESC', 'Erhöhungsschritt pro übersteigendes kg in EUR');

define('MODULE_SHIPPING_WEIGHT_MODE_TITLE', 'Table Method');
define('MODULE_SHIPPING_WEIGHT_MODE_DESC', 'Is the shipping table based on total Weight or Total amount of order.');

$aLang['module_shipping_weight_text_title'] = 'Gewichtsabhägige Versandkosten';
$aLang['module_shipping_weight_text_description'] = 'Gewichtsabhängige Versandkosten';
$aLang['module_shipping_weight_text_way'] = 'Versandkosten';
$aLang['module_shipping_weight_text_weight'] = 'Gewicht';
$aLang['module_shipping_weight_text_amount'] = 'Menge';

