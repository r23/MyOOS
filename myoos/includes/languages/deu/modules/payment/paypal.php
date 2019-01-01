<?php
/* ----------------------------------------------------------------------
   $Id: paypal.php,v 1.5 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: paypal.php,v 1.7 2002/04/17 20:31:18 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_PAYPAL_STATUS_TITLE', 'PayPal Modul aktivieren');
define('MODULE_PAYMENT_PAYPAL_STATUS_DESC', 'Möchten Sie Zahlungen per PayPal akzeptieren?');

define('MODULE_PAYMENT_PAYPAL_ID_TITLE', 'eMail Adresse');
define('MODULE_PAYMENT_PAYPAL_ID_DESC', 'eMail Adresse, welche für PayPal verwendet wird.');

define('MODULE_PAYMENT_PAYPAL_CURRENCY_TITLE', 'Transaktionswährung');
define('MODULE_PAYMENT_PAYPAL_CURRENCY_DESC', 'Währung, welche für Kreditkartentransaktionen verwendet wird.');

define('MODULE_PAYMENT_PAYPAL_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MODULE_PAYMENT_PAYPAL_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MODULE_PAYMENT_PAYPAL_ZONE_TITLE', 'Zahlungszone');
define('MODULE_PAYMENT_PAYPAL_ZONE_DESC', 'Wenn eine Zone ausgewählt ist, gilt die Zahlungsmethode nur für diese Zone.');

define('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID_TITLE', 'Bestellstatus festlegen');
define('MODULE_PAYMENT_PAYPAL_ORDER_STATUS_ID_DESC', 'Bestellungen, welche mit diesem Modul gemacht werden, auf diesen Status setzen.');

$aLang['module_payment_paypal_text_title'] = 'PayPal';
$aLang['module_payment_paypal_text_description'] = 'PayPal';

