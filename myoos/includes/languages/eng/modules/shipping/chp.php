<?php
/**
   ----------------------------------------------------------------------
   $Id: chp.php,v 1.3 2007/06/13 16:42:27 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: chp.php,v 1.01 2003/02/18 03:33:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/********************************************************************
 *    Copyright (C) 2002 - 2003 TheMedia, Dipl.-Ing Thomas Plänkers
 *       http://www.themedia.at & http://www.oscommerce.at
 *
 *                    All rights reserved
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

define('MODULE_SHIPPING_CHP_STATUS_TITLE', 'The Swiss Post');
define('MODULE_SHIPPING_CHP_STATUS_DESC', 'Do you want to offer Swiss Post shipping?');

define('MODULE_SHIPPING_CHP_HANDLING_TITLE', 'Handling Fee');
define('MODULE_SHIPPING_CHP_HANDLING_DESC', 'Handlingfee for this shipping method in CHF');

define('MODULE_SHIPPING_CHP_ZONE_TITLE', 'Shipping Zone');
define('MODULE_SHIPPING_CHP_ZONE_DESC', 'If a zone is selected, only enable this shipping method for that zone');

define('MODULE_SHIPPING_CHP_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_SHIPPING_CHP_SORT_ORDER_DESC', 'Sort order of display');

define('MODULE_SHIPPING_CHP_COUNTRIES_1_TITLE', 'Swiss Post Zone 0 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_1_DESC', 'Inland zone');

define('MODULE_SHIPPING_CHP_COST_ECO_1_TITLE', 'Shipping Table Zone 0 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_1_DESC', 'Shipping Table Zone 0, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_1_TITLE', 'Shipping Table Zone 0 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_1_DESC', 'Shipping Table Zone 0, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_1_TITLE', 'Shipping Table Zone 0 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_1_DESC', 'Shipping Table Zone 0, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_2_TITLE', 'Swiss Post Zone 1 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_2_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 1.');

define('MODULE_SHIPPING_CHP_COST_ECO_2_TITLE', 'Shipping Table Zone 1 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_2_DESC', 'Shipping Table Zone 1, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_2_TITLE', 'Shipping Table Zone 1 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_2_DESC', 'Shipping Table Zone 1, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_2_TITLE', 'Shipping Table Zone 1 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_2_DESC', 'Shipping Table Zone 1, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_3_TITLE', 'Swiss Post Zone 2 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_3_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 2.');

define('MODULE_SHIPPING_CHP_COST_ECO_3_TITLE', 'Shipping Table Zone 2 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_3_DESC', 'Shipping Table Zone 2, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_3_TITLE', 'Shipping Table Zone 2 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_3_DESC', 'Shipping Table Zone 2, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_3_TITLE', 'Shipping Table Zone 2 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_3_DESC', 'Shipping Table Zone 2, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_4_TITLE', 'Swiss Post Zone 3 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_4_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 3.');

define('MODULE_SHIPPING_CHP_COST_ECO_4_TITLE', 'Shipping Table Zone 3 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_4_DESC', 'Shipping Table Zone 3, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_4_TITLE', 'Shipping Table Zone 3 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_4_DESC', 'Shipping Table Zone 3, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_4_TITLE', 'Shipping Table Zone 3 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_4_DESC', 'Shipping Table Zone 3, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_5_TITLE', 'Swiss Post Zone 4 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_5_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 4.');

define('MODULE_SHIPPING_CHP_COST_ECO_5_TITLE', 'Shipping Table Zone 4 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_5_DESC', 'Shipping Table Zone 4, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_5_TITLE', 'Shipping Table Zone 4 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_5_DESC', 'Shipping Table Zone 4, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_5_TITLE', 'Shipping Table Zone 4 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_5_DESC', 'Shipping Table Zone 4, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_6_TITLE', 'Swiss Post Zone 5 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_6_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 5.');

define('MODULE_SHIPPING_CHP_COST_ECO_6_TITLE', 'Shipping Table Zone 5 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_6_DESC', 'Shipping Table Zone 5, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_6_TITLE', 'Shipping Table Zone 5 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_6_DESC', 'Shipping Table Zone 5, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_6_TITLE', 'Shipping Table Zone 5 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_6_DESC', 'Shipping Table Zone 5, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COUNTRIES_7_TITLE', 'Swiss Post Zone 6 Countries');
define('MODULE_SHIPPING_CHP_COUNTRIES_7_DESC', 'Comma separated list of two character ISO country codes that are part of Zone 6.');

define('MODULE_SHIPPING_CHP_COST_ECO_7_TITLE', 'Shipping Table Zone 6 up to 30 kg ECO');
define('MODULE_SHIPPING_CHP_COST_ECO_7_DESC', 'Shipping Table Zone 6, based on <strong>\'ECO\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_PRI_7_TITLE', 'Shipping Table Zone 6 up to 30 kg PRI');
define('MODULE_SHIPPING_CHP_COST_PRI_7_DESC', 'Shipping Table Zone 6, based on <strong>\'PRI\'</strong> up to 30 kg shipping weight.');

define('MODULE_SHIPPING_CHP_COST_URG_7_TITLE', 'Shipping Table Zone 6 up to 30 kg URG');
define('MODULE_SHIPPING_CHP_COST_URG_7_DESC', 'Shipping Table Zone 6, based on <strong>\'URG\'</strong> up to 30 kg shipping weight.');

$aLang['module_shipping_chp_text_title'] = 'The Swiss Post';
$aLang['module_shipping_chp_text_description'] = 'The Swiss Post';
$aLang['module_shipping_chp_text_way'] = 'Dispatch to';
$aLang['module_shipping_chp_text_units'] = 'kg';
$aLang['module_shipping_chp_invalid_zone'] = 'Unfortunately it is not possible to dispatch into this country';
$aLang['module_shipping_chp_undefined_rate'] = 'Forwarding expenses cannot be calculated for the moment';
