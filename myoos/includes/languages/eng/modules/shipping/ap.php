<?php
/**
   ----------------------------------------------------------------------
   $Id: ap.php,v 1.3 2007/06/13 16:42:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ap.php,v 1.02 2003/02/18 03:33:00 harley_vb
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


define('MODULE_SHIPPING_AP_STATUS_TITLE', '�terreichische Post AG');
define('MODULE_SHIPPING_AP_STATUS_DESC', 'Wollen Sie den Versand ber die �terreichische Post AG anbieten?');

define('MODULE_SHIPPING_AP_HANDLING_TITLE', 'Handling Fee');
define('MODULE_SHIPPING_AP_HANDLING_DESC', 'Bearbeitungsgebhr fr diese Versandart in Euro');

define('MODULE_SHIPPING_AP_TAX_CLASS_TITLE', 'Steuersatz');
define('MODULE_SHIPPING_AP_TAX_CLASS_DESC', 'W�len Sie den MwSt.-Satz fr diese Versandart aus.');

define('MODULE_SHIPPING_AP_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_AP_ZONE_DESC', 'If you select a zone, this shipping method will be offered only in this zone.');

define('MODULE_SHIPPING_AP_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_AP_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_SHIPPING_AP_COUNTRIES_1_TITLE', 'Zone 1a L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_1_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 1a sind.');

define('MODULE_SHIPPING_AP_COST_1_TITLE', 'Zone 1a Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_1_DESC', 'Tarif Tabelle fr die Zone 1a, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_2_TITLE', 'Zone 1b L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_2_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 1b sind.');

define('MODULE_SHIPPING_AP_COST_2_TITLE', 'Zone 1b Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_2_DESC', 'Tarif Tabelle fr die Zone 1b, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_3_TITLE', 'Zone 2 L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_3_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_AP_COST_3_TITLE', 'Zone 2 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_3_DESC', 'Tarif Tabelle fr die Zone 2, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_4_TITLE', 'Zone 3 L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_4_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 3 sind.');

define('MODULE_SHIPPING_AP_COST_4_TITLE', 'Zone 3 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_4_DESC', 'Tarif Tabelle fr die Zone 3, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_5_TITLE', 'Zone 4 L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_5_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_AP_COST_5_TITLE', 'Zone 4 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_5_DESC', 'Tarif Tabelle fr die Zone 4, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_6_TITLE', 'Zone 4 L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_6_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_AP_COST_6_TITLE', 'Zone 4 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_6_DESC', 'Tarif Tabelle fr die Zone 4, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_7_TITLE', 'Zone 5 L�der');
define('MODULE_SHIPPING_AP_COUNTRIES_7_DESC', 'Durch Komma getrennt Liste der L�der als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 5 sind.');

define('MODULE_SHIPPING_AP_COST_7_TITLE', 'Zone 5 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_COST_7_DESC', 'Tarif Tabelle fr die Zone 5, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_COUNTRIES_8_TITLE', 'Zone Inland');
define('MODULE_SHIPPING_AP_COUNTRIES_8_DESC', 'Inlandszone');

define('MODULE_SHIPPING_AP_COST_8_TITLE', 'Zone Tarif Tabelle bis 31.5 kg');
define('MODULE_SHIPPING_AP_COST_8_DESC', 'Tarif Tabelle fr die Inlandszone, bis 31.5 kg Versandgewicht.');


$aLang['module_shipping_ap_text_title'] = 'Austrian Post AG';
$aLang['module_shipping_ap_text_description'] = 'Austrian Post AG - Worldwide Dispatch';
$aLang['module_shipping_ap_text_way'] = 'Dispatch to';
$aLang['module_shipping_ap_text_units'] = 'kg';
$aLang['module_shipping_ap_invalid_zone'] = 'Unfortunately it is not possible to dispatch into this country';
$aLang['module_shipping_ap_undefined_rate'] = 'Forwarding expenses cannot be calculated for the moment';
