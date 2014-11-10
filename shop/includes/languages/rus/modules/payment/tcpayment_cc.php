<?php
/* ----------------------------------------------------------------------
   $Id: tcpayment_cc.php,v 1.3 2007/08/04 04:51:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:


   osCommerce Payment-Modul TeleCash Click&Pay easy     
   Version 0.8 vom 23.03.2004   
   
   (c) 2004: Dieter H�auf
   mailto:kontakt@dieter-hoerauf.de   
   http://jana.dieter-hoerauf.de/  

   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


define('MODULE_PAYMENT_TCPAYMENT_CC_STATUS_TITLE', 'Enable Tcpayment CC Module');
define('MODULE_PAYMENT_TCPAYMENT_CC_STATUS_DESC', 'Do you want to accept TeleCash Click&Pay easy payments?');

define('MODULE_PAYMENT_TCPAYMENT_CC_ID_TITLE', 'Merchant-ID');
define('MODULE_PAYMENT_TCPAYMENT_CC_ID_DESC', 'The Merchant-ID You got from TeleCash.');

define('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_TCPAYMENT_CC_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_TCPAYMENT_CC_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

$aLang['module_payment_tcpayment_cc_text_title'] = 'TeleCash C&Peasy Kreditkarten';
$aLang['module_payment_tcpayment_cc_text_description'] = 'WalletPage';

?>
