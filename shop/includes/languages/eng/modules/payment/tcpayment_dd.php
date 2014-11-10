<?php
/* ----------------------------------------------------------------------
   $Id: tcpayment_dd.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

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

define('MODULE_PAYMENT_TCPAYMENT_DD_STATUS_TITLE', 'Enable Tcpayment DD Module');
define('MODULE_PAYMENT_TCPAYMENT_DD_STATUS_DESC', 'Do you want to accept TeleCash Click&Pay easy payments?');

define('MODULE_PAYMENT_TCPAYMENT_DD_ID_TITLE', 'Merchant-ID');
define('MODULE_PAYMENT_TCPAYMENT_DD_ID_DESC', 'The Merchant-ID You got from TeleCash.');

define('MODULE_PAYMENT_TCPAYMENT_DD_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_TCPAYMENT_DD_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_TCPAYMENT_DD_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_TCPAYMENT_DD_ZONE_DESC', 'Please select a zone containing only Germany. Create it first, if it does not exist.');

define('MODULE_PAYMENT_TCPAYMENT_DD_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_TCPAYMENT_DD_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');


$aLang['module_payment_tcpayment_dd_text_title'] = 'TeleCash C&Peasy Lastschrift';
$aLang['module_payment_tcpayment_dd_text_description'] = 'WalletPage';

?>
