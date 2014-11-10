<?php
/* ----------------------------------------------------------------------
   $Id: ot_cod_fee.php,v 1.4 2007/10/26 22:47:37 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_cod_fee.php,v 1.01 2003/02/24 06:05:00 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl�nkers
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
   ---------------------------------------------------------------------- */


define('MODULE_ORDER_TOTAL_COD_STATUS_TITLE', 'Toon verzendkosten');
define('MODULE_ORDER_TOTAL_COD_STATUS_DESC', 'Wilt u de verzendkosten tonen?');

define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_TITLE', 'Sorteervolgorde');
define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_DESC', 'Sorteervolgorde van tonen.');

define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_TITLE', 'Verzendkosten voor standaard');
define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_DESC', 'FLAT: &lt;Landcode&gt;:&lt;Verzendkosten&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_TITLE', 'Verzendkosten per stuk');
define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_DESC',  'ITEM: &lt;Landcode&gt;:&lt;Verzendkosten&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_TITLE', 'Verzendkosten via tabellarisch');
define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_DESC', 'TABLE: &lt;Landcode&gt;:&lt;Verzendkosten&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_TITLE', 'Verzendkosten voor zones');
define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_DESC', 'ZONES: &lt;Landcode&gt;:&lt;Verzendkosten&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_AP_TITLE', 'Verzendkosten voor &Ouml;stereichische Post');
define('MODULE_ORDER_TOTAL_COD_FEE_AP_DESC', '&Ouml;stereichische Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_CHP_TITLE', 'The Swiss Post');
define('MODULE_ORDER_TOTAL_COD_FEE_CHP_DESC', 'The Swiss Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_FEE_DP_TITLE', 'Verzendkosten voor Deutsche Post');
define('MODULE_ORDER_TOTAL_COD_FEE_DP_DESC',  'Deutsche Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 als landcode geldt voor alle landen. Als de landcode 00 is, dan moet dit de laatste invoer zijn. Als geen 00:9.99 verschijnt, dan worden de verzendkosten naar het buitenland niet berekend (niet mogelijk)');

define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_TITLE', 'B.T.W.');
define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_DESC', 'Pas het volgende B.T.W. tarief toe op de verzendkosten.');

$aLang['module_order_total_cod_title'] = 'Remboursement';
$aLang['module_order_total_cod_description'] = 'Remboursement';
?>
