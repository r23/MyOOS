<?php
/* ----------------------------------------------------------------------
   $Id: orders_status.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders_status.php,v 1.7 2002/01/30 11:10:08 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Bestelstatus');

define('TABLE_HEADING_ORDERS_STATUS', 'Bestelstatus');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Bestelstatus:');
define('TEXT_INFO_INSERT_INTRO', 'Voer a.u.b.  de nieuwe bestelstatus met alle relevante gegevens in');
define('TEXT_INFO_DELETE_INTRO', 'weet u zeker dat deze bestelstatus wilt wissen?');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', 'Nieuwe Bestelstatus');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', 'Bestelstatus bewerken');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', 'Bestelstatus wissen');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Fout: De standaard bestellstatus kan niet gewist worden. Definieer a.u.b. een nieuwe standaard bestelstatus en herhaal het proces.');
define('ERROR_STATUS_USED_IN_ORDERS', 'Fout: De bestelstatus wordt op dit moment nog bij de bestellingen gebruikt.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fout: De bestelstatus wordt op dit moment nog in de bestelhistorie gebruikt.');
?>
