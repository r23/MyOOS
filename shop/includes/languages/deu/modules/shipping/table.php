<?php
/* ----------------------------------------------------------------------
   $Id: table.php,v 1.3 2007/08/04 04:53:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table.php,v 1.6 2003/02/16 00:52:41 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_SHIPPING_TABLE_STATUS_TITLE', 'Enable Table Method');
define('MODULE_SHIPPING_TABLE_STATUS_DESC', 'Do you want to offer table rate shipping?');

define('MODULE_SHIPPING_TABLE_COST_TITLE', 'Shipping Table');
define('MODULE_SHIPPING_TABLE_COST_DESC', 'The shipping cost is based on the total cost or weight of items. Example: 25:8.50,50:5.50,etc.. Up to 25 charge 8.50, from there to 50 charge 5.50, etc');

define('MODULE_SHIPPING_TABLE_MODE_TITLE', 'Table Method');
define('MODULE_SHIPPING_TABLE_MODE_DESC', 'The shipping cost is based on the order total or the total weight of the items ordered.');

define('MODULE_SHIPPING_TABLE_HANDLING_TITLE', 'Handling Geb&uuml;hr');
define('MODULE_SHIPPING_TABLE_HANDLING_DESC', 'Handling Geb&uuml;hr f&uuml;r diese Versandart.');

define('MODULE_SHIPPING_TABLE_TAX_CLASS_TITLE', 'Steuerklasse');
define('MODULE_SHIPPING_TABLE_TAX_CLASS_DESC', 'Folgende Steuerklasse an Versandkosten anwenden.');

define('MODULE_SHIPPING_TABLE_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_TABLE_ZONE_DESC', 'Geben Sie <b>einzeln</b> die Zonen an, in welche ein Versand m&ouml;glich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_TABLE_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_SHIPPING_TABLE_SORT_ORDER_DESC', 'Reihenfolge der Anzeige');

$aLang['module_shipping_table_text_title'] = 'Tabellarische Versandkosten';
$aLang['module_shipping_table_text_description'] = 'Tabellarische Versandkosten';
$aLang['module_shipping_table_text_way'] = '';
$aLang['module_shipping_table_text_weight'] = 'Gewicht';
$aLang['module_shipping_table_text_amount'] = 'Menge';
?>
