<?php
/**
   ----------------------------------------------------------------------
   $Id: fedexeu.php,v 1.4 2008/08/11 09:07:01 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: fedexeu.php,v 1.01 2003/02/18 03:25:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl?kers
       http://www.themedia.at & http://www.oscommerce.at

                    All rights reserved.

   This program is füree software licensed under the GNU General Public License (GPL).

   This program is füree software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the füree Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the füree Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307
   USA
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('MODULE_SHIPPING_FEDEXEU_STATUS_TITLE', 'FedEx Express Europe');
define('MODULE_SHIPPING_FEDEXEU_STATUS_DESC', 'Wollen Sie den Versand durch FedEx Express Europe anbieten?');

define('MODULE_SHIPPING_FEDEXEU_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_FEDEXEU_HANDLING_DESC', 'Bearbeitungsgebhr für diese Versandart in Euro');

define('MODULE_SHIPPING_FEDEXEU_ZONE_TITLE', 'Versand Zone');
define('MODULE_SHIPPING_FEDEXEU_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_FEDEXEU_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_FEDEXEU_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_1_TITLE', 'Europazone 1 Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_1_DESC', 'Eurozone');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_1_TITLE', 'Tariftabelle für Zone 1 bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_1_DESC', 'Tarif Tabelle für die Zone 1, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_1_TITLE', 'Tariftabelle für Zone 1 bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_1_DESC', 'Tarif Tabelle für die Zone 1, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_1_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_1_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_1_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_1_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_1_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_1_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_2_TITLE', 'Europazone 2 Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_2_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_2_TITLE', 'Tariftabelle für Zone 2 bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_2_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_2_TITLE', 'Tariftabelle für Zone 2 bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_2_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_2_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_2_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_2_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_2_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_2_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_2_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_3_TITLE', 'Europazone 3 Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_3_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 3 sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_3_TITLE', 'Tariftabelle für Zone 3 bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_3_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_3_TITLE', 'Tariftabelle für Zone 3 bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_3_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_3_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_3_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_3_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_3_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_3_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_3_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_4_TITLE', 'Weltzone A Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_4_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone A sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_4_TITLE', 'Tariftabelle für Zone A bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_4_DESC', 'Tarif Tabelle für die Zone A, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_4_TITLE', 'Tariftabelle für Zone A bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_4_DESC', 'Tarif Tabelle für die Zone A, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_4_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_4_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_4_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_4_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_4_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_4_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_5_TITLE', 'Weltzone B Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_5_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone B sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_5_TITLE', 'Tariftabelle für Zone B bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_5_DESC', 'Tarif Tabelle für die Zone B, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_5_TITLE', 'Tariftabelle für Zone B bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_5_DESC', 'Tarif Tabelle für die Zone B, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_5_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_5_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_5_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_5_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_5_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_5_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_6_TITLE', 'Weltzone C Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_6_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone C sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_6_TITLE', 'Tariftabelle für Zone C bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_6_DESC', 'Tarif Tabelle für die Zone C, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_6_TITLE', 'Tariftabelle für Zone C bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_6_DESC', 'Tarif Tabelle für die Zone C, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_6_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_6_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_6_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_6_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_6_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_6_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_7_TITLE', 'Weltzone D Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_7_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone D sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_7_TITLE', 'Tariftabelle für Zone D bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_7_DESC', 'Tarif Tabelle für die Zone D, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_7_TITLE', 'Tariftabelle für Zone D bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_7_DESC', 'Tarif Tabelle für die Zone D, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_7_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_7_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_7_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_7_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_7_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_7_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_8_TITLE', 'Weltzone E Länder');
define('MODULE_SHIPPING_FEDEXEU_COUNTRIES_8_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone E sind.');

define('MODULE_SHIPPING_FEDEXEU_COST_PAK_8_TITLE', 'Tariftabelle für Zone E bis 2.50 kg PAK');
define('MODULE_SHIPPING_FEDEXEU_COST_PAK_8_DESC', 'Tarif Tabelle für die Zone E, basiered auf <strong>\'PAK\'</strong> bis 2.50 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_COST_BOX_8_TITLE', 'Tariftabelle für Zone E bis 10 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_COST_BOX_8_DESC', 'Tarif Tabelle für die Zone E, basiered auf <strong>\'BOX\'</strong> bis 10 kg Versandgewicht.');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_8_TITLE', 'Erhöhungszuschlag bis 20 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_20_8_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_8_TITLE', 'Erhöhungszuschlag bis 40 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_40_8_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');

define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_8_TITLE', 'Erhöhungszuschlag bis 70 kg BOX');
define('MODULE_SHIPPING_FEDEXEU_SOOS_BOX_70_8_DESC', 'Erhöhungszuschlag pro bersteigende 0,50 kg in EUR');


$aLang['module_shipping_fedexeu_text_title'] = 'FedEx Express Europa';
$aLang['module_shipping_fedexeu_text_description'] = 'FedEx Express Europa';
$aLang['module_shipping_fedexeu_text_way'] = 'Versand nach';
$aLang['module_shipping_fedexeu_text_units'] = 'kg';
$aLang['module_shipping_fedexeu_invalid_zone'] = 'Es ist leider kein Versand in dieses Land möglich';
$aLang['module_shipping_fedexeu_undefined_rate'] = 'Die Versandkosten können im Moment nicht errechnet werden';
