<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_cod_fee.php,v 1.5 2007/10/29 18:21:06 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_cod_fee.php,v 1.01 2003/02/24 06:05:00 harley_vb
   ----------------------------------------------------------------------
   Copyright (C) 2001 - 2003 TheMedia, Dipl.-Ing Thomas Pl�kers
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

define('MODULE_ORDER_TOTAL_COD_STATUS_TITLE', 'Display COD');
define('MODULE_ORDER_TOTAL_COD_STATUS_DESC', 'Do you want this module to display?');

define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_TITLE', 'COD Fee for FLAT');
define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_DESC', 'FLAT: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_TITLE', 'COD Fee for ITEM');
define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_DESC', 'ITEM: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_TITLE', 'COD Fee for TABLE');
define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_DESC', 'TABLE: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_TITLE', 'COD Fee for ZONES');
define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_DESC', 'ZONES: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_AP_TITLE', 'COD Fee for Austrian Post');
define('MODULE_ORDER_TOTAL_COD_FEE_AP_DESC', 'Austrian Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_CHP_TITLE', 'The Swiss Post');
define('MODULE_ORDER_TOTAL_COD_FEE_CHP_DESC', 'Swiss Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_FEE_DP_TITLE', 'COD Fee for German Post');
define('MODULE_ORDER_TOTAL_COD_FEE_DP_DESC', 'German Post: &lt;Country code&gt;:&lt;COD price&gt;, .... 00 as country code applies for all countries. If country code is 00, it must be the last statement. If no 00:9.99 appears, COD shipping in foreign countries is not calculated (not possible)');

define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_TITLE', 'Tax Class');
define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_DESC', 'Use the following tax class on the COD fee.');

$aLang['module_order_total_cod_title'] = 'Cash on Delivery Fee';
$aLang['module_order_total_cod_description'] = 'Cash on Delivery Fee';
