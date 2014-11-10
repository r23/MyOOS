<?php
/* ----------------------------------------------------------------------
   $Id: email_orders.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
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
define('EMAIL_TEXT_SUBJECT', 'Statusverandering van uw bestelling');
define('EMAIL_TEXT_ORDER_NUMBER', 'Bestelnr.:');
define('EMAIL_TEXT_INVOICE_URL', 'U kan uw bestelling via het volgende adres bekijken:');
define('EMAIL_TEXT_DATE_ORDERED', 'Besteldatum:');
define('EMAIL_TEXT_STATUS_UPDATE', 'De status vaan uw bestelling werd veranderd.' . "\n\n" . 'Nieuwe status: %s' . "\n\n" . 'Bij vragen over uw bestelling reageer a.u.b. op deze email.' . "\n\n" . 'Met vriendelijke groeten' . "\n");
define('EMAIL_TEXT_COMMENTS_UPDATE', 'Opmerkingen en commentaar over uw bestelling:' . "\n\n%s\n\n");

?>
