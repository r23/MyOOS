<?php
/* ----------------------------------------------------------------------
   $Id: gv_queue.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
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
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Tegoedbon vrijschakeling');

define('TABLE_HEADING_CUSTOMERS', 'Klant');
define('TABLE_HEADING_ORDERS_ID', 'Bestelnr.');
define('TABLE_HEADING_VOUCHER_VALUE', 'Tegoedbonwaarde');
define('TABLE_HEADING_DATE_PURCHASED', 'Besteldatum');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_REDEEM_COUPON_MESSAGE_HEADER', 'U hebt succesvol een tegoedbon in onze winkel aangevraagd.' . "\n"
                                          . 'Uit veiligheidsredenen werd de tegoedbon niet meteen op uw rekening bijgschreven.' . "\n"
                                          . 'De tegoedbon werd op uw rekening bijgeschreven. U kan nu in onze webwinkel winkelen' . "\n"
                                          . 'en de tegoedbon aan ieder willekeurige ontvanger versturen.' . "\n\n");

define('TEXT_REDEEM_COUPON_MESSAGE_AMOUNT', 'De tegoedbon(nen) die uw hebt verkregen zijn %s waard' . "\n\n");

define('TEXT_REDEEM_COUPON_MESSAGE_BODY', '');
define('TEXT_REDEEM_COUPON_MESSAGE_FOOTER', '');
define('TEXT_REDEEM_COUPON_SUBJECT', 'Tegoedboninkoop');
?>
