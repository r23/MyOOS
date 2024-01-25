<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_shipping.php,v 1.6 2008/08/29 10:25:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_shipping.php,v 1.4 2003/02/16 01:18:31 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_ORDER_TOTAL_SHIPPING_STATUS_TITLE', 'Versandkosten');
define('MODULE_ORDER_TOTAL_SHIPPING_STATUS_DESC', 'Anzeige der Versandkosten?');

define('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_ORDER_TOTAL_SHIPPING_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER_TITLE', 'Versandkostenfrei für Bestellungen ab');
define('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER_DESC', 'Versandkostenfrei ab einem Bestellwert von:');

define('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION_TITLE', 'Versandkostenfrei nach Zonen');
define('MODULE_ORDER_TOTAL_SHIPPING_DESTINATION_DESC', 'Versandkostenfrei nach Zonen berechnen.');

$aLang['module_order_total_shipping_title'] = 'Versandkosten';
$aLang['module_order_total_shipping_description'] = 'Versandkosten einer Bestellung';

$aLang['free_shipping_title'] = 'Versandkostenfrei';
$aLang['free_shipping_description'] = 'Versandkostenfrei bei einem Bestellwert über %s';

$aLang['shopping_cart_shipping_info'] = 'Versandoptionen werden während des Bezahlvorgangs aktualisiert.';
