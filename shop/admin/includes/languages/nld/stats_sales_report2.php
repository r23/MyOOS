<?php
/* ----------------------------------------------------------------------
   $Id: stats_sales_report2.php,v 1.1 2007/06/13 16:39:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_customers.php,v 1.9 2002/03/30 15:03:59 harley_vb  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Verkoopreport');

define('REPORT_TYPE_YEARLY', 'Jaarlijks');
define('REPORT_TYPE_MONTHLY', 'Maandelijks');
define('REPORT_TYPE_WEEKLY', 'Wekelijks');
define('REPORT_TYPE_DAILY', 'Dagelijks');
define('REPORT_START_DATE', 'vanaf datum');
define('REPORT_END_DATE', 'tot datum (inklusief)');
define('REPORT_DETAIL', 'Detail');
define('REPORT_MAX', 'Top x tonen');
define('REPORT_ALL', 'Alle');
define('REPORT_SORT', 'Sortering');
define('REPORT_EXP', 'Export');
define('REPORT_SEND', 'Versturen');
define('EXP_NORMAL', 'Normaal');
define('EXP_HTML', 'Alleen HTML');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', 'Datum');
define('TABLE_HEADING_ORDERS', 'Bestelingen');
define('TABLE_HEADING_ITEMS', 'Artikel');
define('TABLE_HEADING_REVENUE', 'Omzet');
define('TABLE_HEADING_SHIPPING', 'Verzending');

define('DET_HEAD_ONLY', 'Geen details');
define('DET_DETAIL', 'Details tonen');
define('DET_DETAIL_ONLY', 'Details met bedrag');

define('SORT_VAL0', 'Standaard');
define('SORT_VAL1', 'Beschrijving');
define('SORT_VAL2', 'Beschrijving vanaf');
define('SORT_VAL3', 'Artikel op');
define('SORT_VAL4', 'Artikel');
define('SORT_VAL5', 'Omzet op');
define('SORT_VAL6', 'Omzet');

define('REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '<br />');
?>
