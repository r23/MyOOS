<?php
/**
   ----------------------------------------------------------------------
   $jd: zones.php,v '.$j.'.8 2007/04/23 '.$j.'5:37:35 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: zones.php,v 1.3 2002/04/17 16:52:31 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('NUMBER_OF_ZONES', 8);

define('MODULE_SHIPPING_ZONES_STATUS_TITLE', 'Versandkosten nach Zonen');
define('MODULE_SHIPPING_ZONES_STATUS_DESC', 'Möchten Sie Versandkosten Zonenbasierend  anbieten?');

define('MODULE_SHIPPING_ZONES_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_SHIPPING_ZONES_SORT_ORDER_DESC', 'Reihenfolge der Anzeige');

for ($j=0;$j<NUMBER_OF_ZONES;$j++) {
    define('MODULE_SHIPPING_ZONES_COUNTRIES_'.$j.'_TITLE', 'Zone '.$j.' Länder');
    define('MODULE_SHIPPING_ZONES_COUNTRIES_'.$j.'_DESC', 'Durch Komma getrennte Liste von ISO Ländercodes (2 Zeichen), welche Teil von Zone '.$j.' sind.');

    define('MODULE_SHIPPING_ZONES_COST_'.$j.'_TITLE', 'Zone '.$j.' Versandkosten');
    define('MODULE_SHIPPING_ZONES_COST_'.$j.'_DESC', 'Versandkosten nach Zone '.$j.' Bestimmungsorte, basierend auf einer Gruppe von max. Bestellgewichten. Beispiel: 3:8.50,7:10.50,... Gewicht von kleiner oder gleich 3 würde 8.50 für die Zone '.$j.' Bestimmungsländer kosten.');

    define('MODULE_SHIPPING_ZONES_HANDLING_'.$j.'_TITLE', 'Zone '.$j.' Handling Gebühr');
    define('MODULE_SHIPPING_ZONES_HANDLING_'.$j.'_DESC', 'Handling Gebühr für diese Versandzone');
}


$aLang['module_shipping_zones_text_title'] = 'Versandkosten nach Zonen';
$aLang['module_shipping_zones_text_description'] = 'Versandkosten Zonenbasierend';
$aLang['module_shipping_zones_text_way'] = 'Versand nach:';
$aLang['module_shipping_zones_text_units'] = 'kg';
$aLang['module_shipping_zones_invalid_zone'] = 'Es ist kein Versand in dieses Land möglich!';
$aLang['module_shipping_zones_undefined_rate'] = 'Die Versandkosten können im Moment nicht berechnet werden.';
