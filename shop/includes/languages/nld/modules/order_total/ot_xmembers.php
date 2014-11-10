<?php
/* ----------------------------------------------------------------------
   $Id: ot_xmembers.php,v 1.3 2007/08/04 04:52:50 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_gv.php,v 1.0 2002/04/03 23:09:49 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_XMEMBERS_STATUS_TITLE', 'Display Total');
define('MODULE_XMEMBERS_STATUS_DESC', 'Do you want to enable the Order Discount?');

define('MODULE_XMEMBERS_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_XMEMBERS_SORT_ORDER_DESC', 'Sort order of display.');

define('MODULE_XMEMBERS_INC_SHIPPING_TITLE', 'Include Shipping');
define('MODULE_XMEMBERS_INC_SHIPPING_DESC', 'Include Shipping in calculation');

define('MODULE_XMEMBERS_INC_TAX_TITLE', 'Include Tax');
define('MODULE_XMEMBERS_INC_TAX_DESC', 'Include Tax in calculation.');

define('MODULE_XMEMBERS_CALC_TAX_TITLE', 'Calculate Tax');
define('MODULE_XMEMBERS_CALC_TAX_DESC', 'Re-calculate Tax on discounted amount.');

$aLang['module_xmembers_title'] = 'Klantenkorting';
$aLang['module_xmembers_description'] = 'Klantenkorting';
$aLang['shipping_not_included'] = ' [Verzendkosten niet inbegrepen]';
$aLang['tax_not_included'] = ' [B.T.W. niet inbegrepen]';
?>
