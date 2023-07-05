<?php
/**
   ----------------------------------------------------------------------
   $Id: invoice.php,v 1.3 2007/06/14 16:15:58 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

define('MODULE_PAYMENT_INVOICE_ZONE_TITLE', 'Zone fr diese Zahlungsweise');
define('MODULE_PAYMENT_INVOICE_ZONE_DESC', 'Wenn Sie eine Zone ausw�len, wird diese Zahlungsweise nur in dieser Zone angeboten.');

define('MODULE_PAYMENT_INVOICE_SORT_ORDER_TITLE', 'Reihenfolge der Anzeige');
define('MODULE_PAYMENT_INVOICE_SORT_ORDER_DESC', 'Niedrigste wird zuerst angezeigt.');

define('MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID_TITLE', 'Order Status');
define('MODULE_PAYMENT_INVOICE_ORDER_STATUS_ID_DESC', 'Festlegung des Status fr Bestellungen, welche mit dieser Zahlungsweise durchgefhrt werden.');

$aLang['module_payment_invoice_text_description'] = 'Invoice';
$aLang['module_payment_invoice_text_title'] = 'Invoice';
