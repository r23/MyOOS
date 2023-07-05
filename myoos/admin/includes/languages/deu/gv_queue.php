<?php
/**
   ----------------------------------------------------------------------
   $Id: gv_queue.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_queue.php,v 1.1.2.1 2003/05/15 23:10:55 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Gutschein Freigabe');

define('TABLE_HEADING_CUSTOMERS', 'Kunde');
define('TABLE_HEADING_ORDERS_ID', 'Bestell-Nr.');
define('TABLE_HEADING_VOUCHER_VALUE', 'Gutscheinwert');
define('TABLE_HEADING_DATE_PURCHASED', 'Bestelldatum');
define('TABLE_HEADING_ACTION', 'Aktion');

define(
    'TEXT_REDEEM_COUPON_MESSAGE_HEADER',
    'Sie haben erfolgreich einen Gutschein in unserem Shop erworben.' . "\n"
                                          . 'Aus Sicherheitsgrnden wurde der Gutschein nicht sofort Ihrem Konto gutgeschrieben.' . "\n"
                                          . 'Der Gutschein wurde Ihrem Konto jetzt gutgeschrieben. Sie können nun unseren Shop besuchen' . "\n"
    . 'und den Gutschein an jeden beliebigen Empfänger versenden.' . "\n\n"
);

define('TEXT_REDEEM_COUPON_MESSAGE_AMOUNT', 'The Gift Voucher(s) you purchased are worth %s' . "\n\n");

define('TEXT_REDEEM_COUPON_MESSAGE_BODY', '');
define('TEXT_REDEEM_COUPON_MESSAGE_FOOTER', '');
define('TEXT_REDEEM_COUPON_SUBJECT', 'Gutscheinkauf');
