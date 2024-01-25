<?php
/**
   ----------------------------------------------------------------------
   $Id: chp.php,v 1.3 2007/08/04 04:53:51 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File:: chp.php,v 1.01 2003/02/18 03:25:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers
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

define('MODULE_SHIPPING_CHP_STATUS_TITLE', 'Schweizerische Post');
define('MODULE_SHIPPING_CHP_STATUS_DESC', 'Wollen Sie den Versand ber die schweizerische Post anbieten?');

define('MODULE_SHIPPING_CHP_HANDLING_TITLE', 'Handling Gebühr');
define('MODULE_SHIPPING_CHP_HANDLING_DESC', 'Bearbeitungsgebhr für diese Versandart in CHF');

define('MODULE_SHIPPING_CHP_ZONE_TITLE', 'Versand Zone');
define('MODULE_SHIPPING_CHP_ZONE_DESC', 'Geben Sie <strong>einzeln</strong> die Zonen an, in welche ein Versand möglich sein soll. (z.B. AT,DE (lassen Sie dieses Feld leer, wenn Sie alle Zonen erlauben wollen))');

define('MODULE_SHIPPING_CHP_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_SHIPPING_CHP_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_SHIPPING_CHP_COUNTRIES_1_TITLE', 'Tarifzone 0 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_1_DESC', 'Inlandszone');

define('MODULE_SHIPPING_CHP_COST_ECO_1_TITLE', 'Tariftabelle für Zone 0 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_1_DESC', 'Tarif Tabelle für die Inlandszone, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_1_TITLE', 'Tariftabelle für Zone 0 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_1_DESC', 'Tarif Tabelle für die Inlandszone, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_1_TITLE', 'Tariftabelle für Zone 0 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_1_DESC', 'Tarif Tabelle für die Zone 0, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_2_TITLE', 'Tarifzone 1 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_2_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 1 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_2_TITLE', 'Tariftabelle für Zone 1 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_2_DESC', 'Tarif Tabelle für die Zone 1, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_2_TITLE', 'Tariftabelle für Zone 1 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_2_DESC', 'Tarif Tabelle für die Zone 1, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_2_TITLE', 'Tariftabelle für Zone 1 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_2_DESC', 'Tarif Tabelle für die Zone 1, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_3_TITLE', 'Tarifzone 2 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_3_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 2 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_3_TITLE', 'Tariftabelle für Zone 2 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_3_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_3_TITLE', 'Tariftabelle für Zone 2 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_3_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_3_TITLE', 'Tariftabelle für Zone 2 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_3_DESC', 'Tarif Tabelle für die Zone 2, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_4_TITLE', 'Tarifzone 3 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_4_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 3 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_4_TITLE', 'Tariftabelle für Zone 3 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_4_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_4_TITLE', 'Tariftabelle für Zone 3 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_4_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_4_TITLE', 'Tariftabelle für Zone 3 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_4_DESC', 'Tarif Tabelle für die Zone 3, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_5_TITLE', 'Tarifzone 4 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_5_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 4 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_5_TITLE', 'Tariftabelle für Zone 4 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_5_DESC', 'Tarif Tabelle für die Zone 4, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_5_TITLE', 'Tariftabelle für Zone 4 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_5_DESC', 'Tarif Tabelle für die Zone 4, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_5_TITLE', 'Tariftabelle für Zone 4 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_5_DESC', 'Tarif Tabelle für die Zone 4, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_6_TITLE', 'Tarifzone 5 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_6_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 5 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_6_TITLE', 'Tariftabelle für Zone 5 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_6_DESC', 'Tarif Tabelle für die Zone 5, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_6_TITLE', 'Tariftabelle für Zone 5 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_6_DESC', 'Tarif Tabelle für die Zone 5, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_6_TITLE', 'Tariftabelle für Zone 5 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_6_DESC', 'Tarif Tabelle für die Zone 5, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COUNTRIES_7_TITLE', 'Tarifzone 6 Länder');
define('MODULE_SHIPPING_CHP_COUNTRIES_7_DESC', 'Durch Komma getrennt Liste der Länder als zwei Zeichen ISO-Code Landeskennzahlen, die Teil der Zone 6 sind.');

define('MODULE_SHIPPING_CHP_COST_ECO_7_TITLE', 'Tariftabelle für Zone 6 bis 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_7_DESC', 'Tarif Tabelle für die Zone 6, basiered auf <strong>\'ECO\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_PRI_7_TITLE', 'Tariftabelle für Zone 6 bis 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_7_DESC', 'Tarif Tabelle für die Zone 6, basiered auf <strong>\'PRI\'</strong> bis 30 kg Versandgewicht.');

define('MODULE_SHIPPING_CHP_COST_URG_7_TITLE', 'Tariftabelle für Zone 6 bis 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_7_DESC', 'Tarif Tabelle für die Zone 6, basiered auf <strong>\'URG\'</strong> bis 30 kg Versandgewicht.');


$aLang['module_shipping_chp_text_title'] = 'Schweizerische Post';
$aLang['module_shipping_chp_text_description'] = 'Die Schweizerische Post';
$aLang['module_shipping_chp_text_way'] = 'Versand nach';
$aLang['module_shipping_chp_text_units'] = 'kg';
$aLang['module_shipping_chp_invalid_zone'] = 'Es ist leider kein Versand in dieses Land möglich';
$aLang['module_shipping_chp_undefined_rate'] = 'Die Versandkosten können im Moment nicht errechnet werden';
