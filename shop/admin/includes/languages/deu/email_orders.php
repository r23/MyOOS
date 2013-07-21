<?php
/* ----------------------------------------------------------------------
   $Id: email_orders.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.27 2003/02/16 02:09:20 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('EMAIL_SEPARATOR', '------------------------------------------------------');
define('EMAIL_TEXT_SUBJECT', 'Statusänderung Ihrer Bestellung');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestell-Nr.:');
define('EMAIL_TEXT_INVOICE_URL', 'Ihre Bestellung können Sie unter folgender Adresse einsehen:');
define('EMAIL_TEXT_DATE_ORDERED', 'Bestelldatum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'Der Status Ihrer Bestellung wurde geändert.' . "\n\n" . 'Neuer Status: %s' . "\n\n" . 'Bei Fragen zu Ihrer Bestellung antworten Sie bitte auf diese eMail.' . "\n\n" . 'Mit freundlichen Grüssen' . "\n\n" . STORE_OWNER. "\n" . STORE_NAME . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Anmerkungen und Kommentare zu Ihrer Bestellung:' . "\n\n%s\n\n");

?>