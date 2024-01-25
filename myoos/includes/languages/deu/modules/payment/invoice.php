<?php
/**
   ----------------------------------------------------------------------
   $Id: invoice.php,v 1.4 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: invoice.php,v 1.21 2003/02/19 02:10:00 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('MODULE_PAYMENT_INVOICE_STATUS_TITLE', 'Rechnung');
define('MODULE_PAYMENT_INVOICE_STATUS_DESC', 'Wollen Sie Zahlungen per Rechnung anbieten?');

define('MODULE_PAYMENT_INVOICE_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_INVOICE_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_INVOICE_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_INVOICE_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

$aLang['module_payment_invoice_text_description'] = 'Rechnung';
$aLang['module_payment_invoice_text_title'] = 'Rechnung';
