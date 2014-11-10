<?php
/* ----------------------------------------------------------------------
   $Id: max_order.php,v 1.4 2007/06/21 15:34:11 r23 Exp $

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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('NAVBAR_TITLE', 'Ordine Massimo');
define('HEADING_TITLE', 'Ordine Massimo');
define('TEXT_INFORMATION', 'Sei '. $currencies->format ($cart->show_total() - (+$max_order)) .' oltre il tuoi ' . $currencies->format($max_order) . ' di limite di credito. <br />Contatta il servizio commerciale per confermare l\'ordine.');

?>
