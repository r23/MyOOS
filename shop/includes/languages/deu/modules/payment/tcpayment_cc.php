<?php
/* ----------------------------------------------------------------------
   $Id: tcpayment_cc.php,v 1.4 2008/08/25 14:28:07 r23 Exp $

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

define('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_TCPAYMENT_CC_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_TCPAYMENT_CC_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_TCPAYMENT_CC_ZONE_DESC', 'Wenn eine Zone ausgew&auml;hlt ist, gilt die Zahlungsmethode nur f&uuml;r diese Zone.');

define('MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_TCPAYMENT_CC_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');


$aLang['module_payment_tcpayment_cc_text_title'] = 'TeleCash C&Peasy Kreditkarten';
$aLang['module_payment_tcpayment_cc_text_description'] = 'WalletPage';

?>