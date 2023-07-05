<?php
/**
   ----------------------------------------------------------------------
   $Id: fedexeu.php,v 1.3 2007/06/13 16:42:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: fedexeu.php,v 1.01 2003/02/18 03:33:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/********************************************************************
 *    Copyright (C) 2002 - 2003 TheMedia, Dipl.-Ing Thomas Pl�kers
 *       http://www.themedia.at & http://www.oscommerce.at
 *
 *                    All rights reserved.
 *
 * This program is free software licensed under the GNU General Public License (GPL).
 *
 *    This program is free software; you can redistribute it and/or modify
 *    it under the terms of the GNU General Public License as published by
 *    the Free Software Foundation; either version 2 of the License, or
 *    (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
 *    USA
 *********************************************************************/


define('MODULE_SHIPPING_FEDEXEU_STATUS_TITLE', 'FedEx Express Europe');
define('MODULE_SHIPPING_FEDEXEU_STATUS_DESC', 'Do you want to offer shipping through FedEx Express Europe?');

define('MODULE_SHIPPING_FEDEXEU_HANDLING_TITLE', 'Handling Fee');
define('MODULE_SHIPPING_FEDEXEU_HANDLING_DESC', 'Handling fee for this shipping method.');

define('MODULE_SHIPPING_FEDEXEU_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_FEDEXEU_ZONE_DESC', 'If you select a zone, this shipping method will be offered only in this zone.');

define('MODULE_SHIPPING_FEDEXEU_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_SHIPPING_FEDEXEU_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_1_TITLE', 'Europazone 1 Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_1_DESC', 'Eurozone');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_1_TITLE', 'Tariff table for zone 1 up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_1_DESC', 'Tariff table for zone 1, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_1_TITLE', 'Tariff table for zone 1 up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_1_DESC', 'Tariff table for zone 1, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_1_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_1_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_1_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_1_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_1_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_1_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_2_TITLE', 'Europazone 2 Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_2_DESC', 'Durch Komma getrennt Liste der Countries als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_2_TITLE', 'Tariff table for zone 2 up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_2_DESC', 'Tariff table for zone 2, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_2_TITLE', 'Tariff table for zone 2 up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_2_DESC', 'Tariff table for zone 2, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_2_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_2_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_2_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_2_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_2_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_2_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_3_TITLE', 'Europe zone 3 countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_3_DESC', 'Separated by comma List of countries as two characters ISO code Country codes that are part of zone 3.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_3_TITLE', 'Tariff table for zone 3 up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_3_DESC', 'Tariff table for zone 3, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_3_TITLE', 'Tariff table for zone 3 up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_3_DESC', 'Tariff table for zone 3, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_3_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_3_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_3_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_3_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_3_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_3_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_4_TITLE', 'World Zone A Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_4_DESC', 'Comma-separated list of countries as two-character ISO code country codes that are part of zone A.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_4_TITLE', 'Tariff table for zone A up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_4_DESC', 'Tariff table for zone A, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_4_TITLE', 'Tariff table for zone A up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_4_DESC', 'Tariff table for zone A, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_4_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_4_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_4_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_4_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_4_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_4_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_5_TITLE', 'World Zone B Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_5_DESC', 'Comma-separated list of countries as two-character ISO code country codes that are part of zone B.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_5_TITLE', 'Tariff table for zone B up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_5_DESC', 'Tariff table for zone B, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_5_TITLE', 'Tariff table for zone B up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_5_DESC', 'Tariff table for zone B, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_5_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_5_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_5_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_5_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_5_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_5_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_6_TITLE', 'World Zone C Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_6_DESC', 'Comma-separated list of countries as two-character ISO code country codes that are part of zone C.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_6_TITLE', 'Tariff table for zone C up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_6_DESC', 'Tariff table for zone C, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_6_TITLE', 'Tariff table for zone C up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_6_DESC', 'Tariff table for zone C, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_6_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_6_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_6_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_6_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_6_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_6_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_7_TITLE', 'World Zone D Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_7_DESC', 'Comma-separated list of countries as two-character ISO code country codes that are part of zone D');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_7_TITLE', 'Tariff table for zone D up to 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_7_DESC', 'Tariff table for zone D, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_7_TITLE', 'Tariff table for zone D up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_7_DESC', 'Tariff table for zone D, based on <strong>\'BOX\'</strong> up to 10 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_7_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_7_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_7_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_7_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_7_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_7_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_8_TITLE', 'World Zone E Countries');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_8_DESC', 'Comma-separated list of countries as two-character ISO code country codes that are part of zone E.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_8_TITLE', 'Tariff table for zone E up to 2.50 kg PAH');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_8_DESC', 'Tariff table for zone E, based on <strong>\'PAK\'</strong> up to 2.50 kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_8_TITLE', 'Tariff table for zone E up to 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_8_DESC', 'Tariff table for zone E, based on <strong>\'BOX\'</strong> up to 10  kg shipping weight.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_8_TITLE', 'Increase surcharge up to 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_8_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_8_TITLE', 'Increase surcharge up to 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_8_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_8_TITLE', 'Increase surcharge up to 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_8_DESC', 'Increase surcharge per exceeding 0,50 kg in EUR');


$aLang['module_shipping_fedexeu_text_title'] = 'FedEx Express Europe';
$aLang['module_shipping_fedexeu_text_description'] = 'FedEx Express Europe';
$aLang['module_shipping_fedexeu_text_way'] = 'Dispatch to';
$aLang['module_shipping_fedexeu_text_units'] = 'kg';
$aLang['module_shipping_fedexeu_invalid_zone'] = 'Unfortunately it is not possible to dispatch into this country';
$aLang['module_shipping_fedexeu_undefined_rate'] = 'Forwarding expenses cannot be calculated for the moment';
