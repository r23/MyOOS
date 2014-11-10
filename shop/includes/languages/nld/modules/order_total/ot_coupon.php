<?php
/* ----------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.3 2007/08/04 04:52:50 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_coupon.php,v 1.1.2.2 2003/05/15 23:05:02 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_ORDER_TOTAL_COUPON_STATUS_TITLE', 'Toon totaal');
define('MODULE_ORDER_TOTAL_COUPON_STATUS_DESC', 'Wilt u het bedrag van de kortingsbon tonen?');

define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_TITLE', 'Sorteervolgorde');
define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_DESC', 'Sorteervolgorde van tonen.');

define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_TITLE', 'Inclusief vezendkosten');
define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_DESC', 'Bereken inclusief verzendkosten');

define('MODULE_ORDER_TOTAL_COUPON_INC_TAX_TITLE', 'Inclusief B.T.W.');
define('MODULE_ORDER_TOTAL_COUPON_INC_TAX_DESC', 'Bereken inclusief B.T.W.');

define('MODULE_ORDER_TOTAL_COUPON_CALC_TAX_TITLE', 'Herbereken B.T.W.');
define('MODULE_ORDER_TOTAL_COUPON_CALC_TAX_DESC', 'Herbereken B.T.W.');

define('MODULE_ORDER_TOTAL_COUPON_TAX_CLASS_TITLE', 'B.T.W. bedrag');
define('MODULE_ORDER_TOTAL_COUPON_TAX_CLASS_DESC', 'Gebruik volgende B.T.W. bedrag als de kortingsbon als creditnota wordt behandeld.');


$aLang['module_order_total_coupon_title'] = 'Tegoedbonnen';
$aLang['module_order_total_coupon_header'] = 'Tegoedbonnen';
$aLang['module_order_total_coupon_description'] = 'Tegoedbon';
$aLang['shipping_not_included'] = ' [Transport niet inbegrepen]';
$aLang['tax_not_included'] = ' [B.T.W. niet inbegrepen]';
$aLang['module_order_total_coupon_user_prompt'] = '';
$aLang['error_no_invalid_redeem_coupon'] = 'Ongeldige tegoedboncode';
$aLang['error_invalid_startdate_coupon'] = 'Deze tegoedbon is nog niet beschikbaar';
$aLang['error_invalid_finisdate_coupon'] = 'Deze tegoedbon is verlopen';
$aLang['error_invalid_uses_coupon'] = 'Deze tegoedbon kan maar gebruikt worden ';  
$aLang['times'] = ' keer.';
$aLang['error_invalid_uses_user_coupon'] = 'U hebt de tegoedbon voor het maximale aantal keren gebruikt dat per klant is toegestaan.'; 
$aLang['redeemed_coupon'] = 'een tegoedbon ter waarde van ';  
$aLang['redeemed_min_order'] = 'op bestellingen van meer dan ';  
$aLang['redeemed_restrictions'] = ' [Produktcategorie beperkingen zijn van toepassing]';  
$aLang['text_enter_coupon_code'] = 'Tegoedboncode&nbsp;&nbsp;';
?>
