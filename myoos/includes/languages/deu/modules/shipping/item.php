<?php
/**
   ----------------------------------------------------------------------
   $Id: item.php,v 1.3 2007/08/04 04:53:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: item.php,v 1.6 2003/02/16 00:52:41 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_SHIPPING_ITEM_STATUS_TITLE', 'Versandkosten pro Stück aktivieren');
define('MODULE_SHIPPING_ITEM_STATUS_DESC', 'Möchten Sie Versandkosten pro Stück anbieten?');

define('MODULE_SHIPPING_ITEM_COST_TITLE', 'Versandkosten');
define('MODULE_SHIPPING_ITEM_COST_DESC', 'Die Versandkosten werden mit der Anzahl an Artikel einer Bestellung multipliziert, wenn diese Versandart angegeben ist.');

define('MODULE_SHIPPING_ITEM_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_ITEM_HANDLING_DESC', 'Handling Gebühr für diese Versandart.');

define('MODULE_SHIPPING_ITEM_ZONE_TITLE', 'Erlaubte Versandzonen');
define('MODULE_SHIPPING_ITEM_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_ITEM_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_SHIPPING_ITEM_SORT_ORDER_DESC', 'Reihenfolge der Anzeige');

$aLang['module_shipping_item_text_title'] = 'Versandkosten pro Stück';
$aLang['module_shipping_item_text_description'] = 'Versandkosten pro Stück';
$aLang['module_shipping_item_text_way'] = 'Versandkosten';
