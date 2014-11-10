<?php
/* ----------------------------------------------------------------------
   $Id: nochex.php,v 1.3 2007/06/12 17:30:00 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: nochex.php,v 1.2 2002/11/01 05:38:20 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_NOCHEX_STATUS_TITLE', 'Enable NOCHEX Module');
define('MODULE_PAYMENT_NOCHEX_STATUS_DESC', 'Do you want to accept NOCHEX payments?');

define('MODULE_PAYMENT_NOCHEX_ID_TITLE', 'E-Mail Address');
define('MODULE_PAYMENT_NOCHEX_ID_DESC', 'The e-mail address to use for the NOCHEX service');

define('MODULE_PAYMENT_NOCHEX_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_NOCHEX_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_NOCHEX_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_NOCHEX_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_NOCHEX_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

$aLang['module_payment_nochex_text_title'] = 'NOCHEX';
$aLang['module_payment_nochex_text_description'] = 'NOCHEX<br />Requires the GBP currency.';

?>
