<?php
/* ----------------------------------------------------------------------
   $Id: max_order.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: max_order.php v1.00 2003/04/27 JOHNSON   
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
  
   Max Order - 2003/04/27 JOHNSON - Copyright (c) 2003 Matti Ressler - mattifinn@optusnet.com.au
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('NAVBAR_TITLE', 'Maximale bestelwaarde');
define('HEADING_TITLE', 'Maximale bestelwaarde');
define('TEXT_INFORMATION', 'U bent '. $currencies->format ($cart->show_total() - (+$customer_max_order)) .' over uw ' . $currencies->format($customer_max_order) . ' maximale Bestelwaarde. <br />Neem a.u.b. contact op met ons verkoopteam, om uw bestelling te laten bevestigen.');
?>
