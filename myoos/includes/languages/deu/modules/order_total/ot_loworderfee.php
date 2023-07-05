<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_loworderfee.php,v 1.6 2008/10/03 15:38:16 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_loworderfee.php,v 1.2 2002/04/17 12:01:46 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS_TITLE', 'Mindermengenzuschlag');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS_DESC', 'Möchten Sie sich den Mindermengenzuschlag ansehen?');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE_TITLE', 'Mindermengenzuschlag erlauben');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE_DESC', 'Möchten Sie Mindermengenzuschläge erlauben?');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER_TITLE', 'Mindermengenzuschlag für Bestellungen unter.');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER_DESC', 'Mindermengenzuschlag wird für Bestellungen unter diesem Wert hinzugefügt.');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_FEE_TITLE', 'Zuschlag');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_FEE_DESC', 'Mindermengenzuschlag.');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION_TITLE', 'Mindestmengenzuschlag nach Zonen berechnen');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION_DESC', 'Mindestmengenzuschlag für Bestellungen, die an diesen Ort versandt werden.');

define('MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS_TITLE', 'Steuerklasse');
define('MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS_DESC', 'Folgende Steuerklasse für den Mindermengenzuschlag verwenden.');

$aLang['module_order_total_loworderfee_title'] = 'Mindermengenzuschlag';
$aLang['module_order_total_loworderfee_description'] = 'Zuschlag bei Unterschreitung des Mindestbestellwertes';
