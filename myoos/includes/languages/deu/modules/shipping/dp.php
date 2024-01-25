<?php
/**
   ----------------------------------------------------------------------
   $Id: dp.php,v 1.5 2007/10/10 15:43:56 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: dp.php,v 1.4 2003/02/18 04:28:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_SHIPPING_DP_STATUS_TITLE', 'DHL');
define('MODULE_SHIPPING_DP_STATUS_DESC', 'Wollen Sie den Versand über die Deutsche Post anbieten?');

define('MODULE_SHIPPING_DP_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_DP_HANDLING_DESC', 'Bearbeitungsgebühr für diese Versandart in Euro');

define('MODULE_SHIPPING_DP_ZONE_TITLE', 'Versand Zone');
define('MODULE_SHIPPING_DP_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_DP_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_DP_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_SHIPPING_DP_COUNTRIES_1_TITLE', 'DHL Zone 1 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_1_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 1 sind.');

define('MODULE_SHIPPING_DP_COST_1_TITLE', 'DHL Zone 1 Versandkosten');
define('MODULE_SHIPPING_DP_COST_1_DESC', 'Die Versandkosten für Zone 1 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 1 kosten');

define('MODULE_SHIPPING_DP_COUNTRIES_2_TITLE', 'DHL Zone 2 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_2_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_DP_COST_2_TITLE', 'DHL Zone 2 Versandkosten');
define('MODULE_SHIPPING_DP_COST_2_DESC', 'Die Versandkosten für Zone 2 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 2 kosten');

define('MODULE_SHIPPING_DP_COUNTRIES_3_TITLE', 'DHL Zone 3 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_3_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 3 sind.');

define('MODULE_SHIPPING_DP_COST_3_TITLE', 'DHL Zone 3 Länder');
define('MODULE_SHIPPING_DP_COST_3_DESC', 'Die Versandkosten für Zone 3 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 3 kosten');

define('MODULE_SHIPPING_DP_COUNTRIES_4_TITLE', 'DHL Zone 4 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_4_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_DP_COST_4_TITLE', 'DHL Zone 4 Versandkosten');
define('MODULE_SHIPPING_DP_COST_4_DESC', 'Die Versandkosten für Zone 4 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 4 kosten.');

define('MODULE_SHIPPING_DP_COUNTRIES_5_TITLE', 'DHL Zone 5 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_5_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 5 sind.');

define('MODULE_SHIPPING_DP_COST_5_TITLE', 'DHL Zone 5 Versandkosten');
define('MODULE_SHIPPING_DP_COST_5_DESC', 'Die Versandkosten für Zone 5 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 5 kosten');

define('MODULE_SHIPPING_DP_COUNTRIES_6_TITLE', 'DHL Zone 6 Länder');
define('MODULE_SHIPPING_DP_COUNTRIES_6_DESC', 'Komma getrennte Liste von zweistelligen ISO-Ländercodes, die Teil der Zone 6 sind.');

define('MODULE_SHIPPING_DP_COST_6_TITLE', 'DHL Zone 6 Versandkosten');
define('MODULE_SHIPPING_DP_COST_6_DESC', 'Die Versandkosten für Zone 6 basieren auf einer Reihe von Bestellgewichten. Beispiel: 0-3:8.50,3-7:10.50,.... Gewichte, die größer als 0 und kleiner oder gleich 3 sind, würden 8.50 für Zone 6 kosten');


$aLang['module_shipping_dp_text_title'] = 'DHL';
$aLang['module_shipping_dp_text_description'] = 'DHL - Weltweites Versandmodul';
$aLang['module_shipping_dp_text_way'] = 'Versand nach';
$aLang['module_shipping_dp_text_units'] = 'kg';
$aLang['module_shipping_dp_invalid_zone'] = 'Es ist leider kein Versand in dieses Land möglich';
$aLang['module_shipping_dp_undefined_rate'] = 'Die Versandkosten können im Moment nicht errechnet werden';
