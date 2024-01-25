<?php
/**
   ----------------------------------------------------------------------
   $Id: ot_coupon.php,v 1.6 2008/08/29 10:25:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ot_coupon.php,v 1.1.2.2 2003/05/15 23:05:02 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('MODULE_ORDER_TOTAL_COUPON_STATUS_TITLE', 'Rabattgutscheine');
define('MODULE_ORDER_TOTAL_COUPON_STATUS_DESC', 'Möchten Sie Rabattgutscheinee anbieten?');

define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_TITLE', 'Sortierreihenfolge');
define('MODULE_ORDER_TOTAL_COUPON_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_TITLE', 'Inklusive Versandkosten');
define('MODULE_ORDER_TOTAL_COUPON_INC_SHIPPING_DESC', 'Versandkosten sind enthalten.');

$aLang['module_order_total_coupon_title'] = 'Rabattgutschein';
$aLang['module_order_total_coupon_header'] = 'Rabattgutschein';
$aLang['module_order_total_coupon_description'] = 'Rabattgutschein';
$aLang['shipping_not_included'] = ' [Versand nicht enthalten]';

$aLang['module_order_total_coupon_user_prompt'] = '';
$aLang['error_no_invalid_redeem_coupon'] = 'Ungültiger Gutscheincode.';
$aLang['error_invalid_startdate_coupon'] = 'Dieser Gutschein ist noch nicht verfügbar.';
$aLang['error_invalid_finisdate_coupon'] = 'Dieser Gutschein ist nicht mehr gültig.';
$aLang['error_invalid_uses_coupon'] = 'Dieser Gutschein kann nur %s mal benutzt werden.';
$aLang['error_invalid_uses_user_coupon'] = 'Der maximale Wert dieses Gutscheines wurde erreicht.';
$aLang['error_coupon_minimum_order'] = 'Bitte beachten Sie den Mindestbestellwert von %s. Noch %s und Ihr Gutschein wird eingelöst!';

$aLang['redeemed_coupon'] = 'ein Gutschein über ';
$aLang['redeemed_min_order'] = 'für Waren über ';
$aLang['redeemed_restrictions'] = ' [Artikel / Kategorie Einschränkungen]';
$aLang['text_enter_coupon_code'] = 'Gutscheincode&nbsp;&nbsp;';
