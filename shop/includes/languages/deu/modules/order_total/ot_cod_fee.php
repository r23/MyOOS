<?php
/* ----------------------------------------------------------------------
   $Id: ot_cod_fee.php,v 1.6 2008/08/29 10:25:21 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_cod_fee.php,v 1.01 2003/02/24 06:05:00 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
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
   ---------------------------------------------------------------------- */


define('MODULE_ORDER_TOTAL_COD_STATUS_TITLE',  'Nachnahmegeb&uuml;hr');
define('MODULE_ORDER_TOTAL_COD_STATUS_DESC', 'Berechnung der Nachnahmegeb&uuml;hr');

define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_ORDER_TOTAL_COD_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_TITLE', 'Pauschale Versandkosten');
define('MODULE_ORDER_TOTAL_COD_FEE_FLAT_DESC', 'Pauschale Versandkosten: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');

define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_TITLE', 'Versandkosten pro St&uuml;ck');
define('MODULE_ORDER_TOTAL_COD_FEE_ITEM_DESC', 'Versandkosten pro St&uuml;ck: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');


define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_TITLE', 'Tabellarische Versandkosten');
define('MODULE_ORDER_TOTAL_COD_FEE_TABLE_DESC', 'Tabellarische Versandkosten: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');

define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_TITLE', 'Versandkosten nach Zonen');
define('MODULE_ORDER_TOTAL_COD_FEE_ZONES_DESC', 'Versandkosten nach Zonen: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');

define('MODULE_ORDER_TOTAL_COD_FEE_AP_TITLE', '&Ouml;sterreichische Post AG');
define('MODULE_ORDER_TOTAL_COD_FEE_AP_DESC', '&Ouml;sterreichische Post AG: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');

define('MODULE_ORDER_TOTAL_COD_FEE_CHP_TITLE', 'The Swiss Post');
define('MODULE_ORDER_TOTAL_COD_FEE_CHP_DESC', 'The Swiss Post: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');


define('MODULE_ORDER_TOTAL_COD_FEE_DP_TITLE', 'Deutsche Post AG');
define('MODULE_ORDER_TOTAL_COD_FEE_DP_DESC', 'Deutsche Post AG: &lt;ISO2-Code&gt;:&lt;Preis&gt;, ....<br />00 als ISO2-Code erm&ouml;glicht den Nachnahmeversand in alle L&auml;nder. Wenn 00 verwendet wird, muss dieses als letztes Argument eingetragen werden. Wenn kein 00:9.99 eingetragen ist, wird der Nachnahmeversand ins Ausland nicht berechnet (nicht m&ouml;glich).');

define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_TITLE', 'Steuerklasse');
define('MODULE_ORDER_TOTAL_COD_TAX_CLASS_DESC', 'W&auml;hlen Sie eine Steuerklasse.');

$aLang['module_order_total_cod_title'] = 'Nachnahmegeb&uuml;hr';
$aLang['module_order_total_cod_description'] = 'Nachnahmegeb&uuml;hr';


