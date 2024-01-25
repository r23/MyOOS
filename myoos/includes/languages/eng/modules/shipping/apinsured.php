<?php
/**
   ----------------------------------------------------------------------
   $Id: apinsured.php,v 1.1 2008/08/12 22:18:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ap.php,v 1.02 2003/02/18 03:25:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers
       http://www.themedia.at & http://www.oscommerce.at

                    All rights reserved.

   This program is free software licensed under the GNU General Public License (GPL).

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
   USA
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
   ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ----------------------------------------------------------------------
 */


define('MODULE_SHIPPING_AP_INSURED_STATUS_TITLE', 'Österreichische Post AG (versichert)');
define('MODULE_SHIPPING_AP_INSURED_STATUS_DESC', 'Wollen Sie den Versand über die Österreichische Post AG anbieten?');

define('MODULE_SHIPPING_AP_INSURED_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_AP_INSURED_HANDLING_DESC', 'Bearbeitungsgebühr für diese Versandart in Euro');

define('MODULE_SHIPPING_AP_INSURED_TAX_CLASS_TITLE', 'Steuersatz');
define('MODULE_SHIPPING_AP_INSURED_TAX_CLASS_DESC', 'Wählen Sie den MwSt.-Satz für diese Versandart aus.');

define('MODULE_SHIPPING_AP_INSURED_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_AP_INSURED_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_AP_INSURED_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_AP_INSURED_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_1_TITLE', 'Zone 1a Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_1_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 1a sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_1_TITLE', 'Zone 1a Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_1_DESC', 'Tarif Tabelle für die Zone 1a, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_2_TITLE', 'Zone 1b Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_2_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 1b sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_2_TITLE', 'Zone 1b Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_2_DESC', 'Tarif Tabelle für die Zone 1b, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_3_TITLE', 'Zone 2 Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_3_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_3_TITLE', 'Zone 2 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_3_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_4_TITLE', 'Zone 3 Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_4_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 3 sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_4_TITLE', 'Zone 3 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_4_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_5_TITLE', 'Zone 4 Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_5_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_5_TITLE', 'Zone 4 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_5_DESC', 'Tarif Tabelle für die Zone 4, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_6_TITLE', 'Zone 4 Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_6_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_6_TITLE', 'Zone 4 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_6_DESC', 'Tarif Tabelle für die Zone 4, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_7_TITLE', 'Zone 5 Länder');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_7_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 5 sind.');

define('MODULE_SHIPPING_AP_INSURED_COST_7_TITLE', 'Zone 5 Tarif Tabelle bis 20 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_7_DESC', 'Tarif Tabelle für die Zone 5, basiered auf <strong>\'Schnelles Paket\'</strong> bis 20 kg Versandgewicht.');

define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_8_TITLE', 'Zone Inland');
define('MODULE_SHIPPING_AP_INSURED_COUNTRIES_8_DESC', 'Inlandszone');

define('MODULE_SHIPPING_AP_INSURED_COST_8_TITLE', 'Zone Tarif Tabelle bis 31.5 kg');
define('MODULE_SHIPPING_AP_INSURED_COST_8_DESC', 'Tarif Tabelle für die Inlandszone, bis 31.5 kg Versandgewicht.');

$aLang['module_shipping_ap_insured_text_title'] = 'Österreichische Post AG (versichert)';
$aLang['module_shipping_ap_insured_text_description'] = 'Österreichische Post AG - Weltweites Versandmodul';
$aLang['module_shipping_ap_insured_text_way'] = 'Versand nach';
$aLang['module_shipping_ap_insured_text_units'] = 'kg';
$aLang['module_shipping_ap_insured_invalid_zone'] = 'Es ist leider kein Versand in dieses Land möglich';
$aLang['module_shipping_ap_insured_undefined_rate'] = 'Die Versandkosten können im Moment nicht errechnet werden';
