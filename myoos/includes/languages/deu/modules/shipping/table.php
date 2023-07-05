<?php
/**
   ----------------------------------------------------------------------
   $Id: table.php,v 1.3 2007/08/04 04:53:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: table.php,v 1.6 2003/02/16 00:52:41 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_SHIPPING_TABLE_STATUS_TITLE', 'Tabellarische Versandkosten aktivieren');
define('MODULE_SHIPPING_TABLE_STATUS_DESC', 'Möchten Sie Tabellarische Versandkosten anbieten?');

define('MODULE_SHIPPING_TABLE_COST_TITLE', 'Versandkosten');
define('MODULE_SHIPPING_TABLE_COST_DESC', 'Die Versandkosten basieren auf Gesamtkosten oder Gesamtgewicht der bestellten Waren. Beispiel: 25:5.50,50:8.50,etc.. Bis 25 werden 5.50 verrechnet, dar&uuml;ber bis 50 werden 8.50 verrechnet, etc');

define('MODULE_SHIPPING_TABLE_MODE_TITLE', 'Versandkosten');
define('MODULE_SHIPPING_TABLE_MODE_DESC', 'Die Versandkosten basieren auf Gesamtkosten oder Gesamtgewicht der bestellten Waren.');

define('MODULE_SHIPPING_TABLE_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_TABLE_HANDLING_DESC', 'Handling Gebühr für diese Versandart.');

define('MODULE_SHIPPING_TABLE_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_TABLE_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_TABLE_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_SHIPPING_TABLE_SORT_ORDER_DESC', 'Reihenfolge der Anzeige');

$aLang['module_shipping_table_text_title'] = 'Tabellarische Versandkosten';
$aLang['module_shipping_table_text_description'] = 'Tabellarische Versandkosten';
$aLang['module_shipping_table_text_way'] = '';
$aLang['module_shipping_table_text_weight'] = 'Gewicht';
$aLang['module_shipping_table_text_amount'] = 'Menge';
