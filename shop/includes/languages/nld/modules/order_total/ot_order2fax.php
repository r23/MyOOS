<?php
/* ----------------------------------------------------------------------
   $Id: ot_order2fax.php,v 1.3 2007/08/04 04:52:50 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order2fax.php,v 1.0 2006/06/12 18:05:04 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS_TITLE', 'Enable ot_order2fax Module');
define('MODULE_ORDER_TOTAL_ORDER2FAX_STATUS_DESC', 'Do you want to send orders by fax?');

define('MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME_TITLE', 'Username');
define('MODULE_ORDER_TOTAL_ORDER2FAX_USERNAME_DESC', 'Username');

define('MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD_TITLE', 'Password');
define('MODULE_ORDER_TOTAL_ORDER2FAX_PASSWORD_DESC', 'Password');

define('MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER_TITLE', 'Fax number');
define('MODULE_ORDER_TOTAL_ORDER2FAX_FAXNUMBER_DESC', 'Fax number');

define('MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL_TITLE', 'Sender email address');
define('MODULE_ORDER_TOTAL_ORDER2SENDEREMAIL_DESC', 'Sender email address');

define('MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER_TITLE', 'Sort Order');
define('MODULE_ORDER_TOTAL_ORDER2FAX_SORT_ORDER_DESC', 'Sort order of display.');

$aLang['module_order_total_order2fax_text_title'] = 'Order by Fax';
$aLang['module_order_total_order2fax_text_description'] = 'send incoming orders by fax (a mail2fax gateway account required!)';

?>
