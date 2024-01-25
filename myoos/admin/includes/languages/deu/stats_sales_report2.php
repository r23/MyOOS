<?php
/**
   ----------------------------------------------------------------------
   $Id: stats_sales_report2.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_customers.php,v 1.9 2002/03/30 15:03:59 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Verkaufs Report');

define('REPORT_TYPE_YEARLY', 'Jährlich');
define('REPORT_TYPE_MONTHLY', 'Monatlich');
define('REPORT_TYPE_WEEKLY', 'Wöchentlich');
define('REPORT_TYPE_DAILY', 'Täglich');
define('REPORT_START_DATE', 'von Datum');
define('REPORT_END_DATE', 'bis Datum (inklusive)');
define('REPORT_DETAIL', 'Detail');
define('REPORT_MAX', 'Top x zeigen');
define('REPORT_ALL', 'Alle');
define('REPORT_SORT', 'Sortierung');
define('REPORT_EXP', 'Export');
define('REPORT_SEND', 'Senden');
define('EXP_NORMAL', 'Normal');
define('EXP_HTML', 'HTML only');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', 'Datum');
define('TABLE_HEADING_ORDERS', '#Bestellungen');
define('TABLE_HEADING_ITEMS', '#Artikel');
define('TABLE_HEADING_REVENUE', 'Umsatz');
define('TABLE_HEADING_SHIPPING', 'Versand');

define('DET_HEAD_ONLY', 'keine Details');
define('DET_DETAIL', 'Details anzeigen');
define('DET_DETAIL_ONLY', 'Details mit Betrag');

define('SORT_VAL0', 'Standard');
define('SORT_VAL1', 'Beschreibung');
define('SORT_VAL2', 'Beschreibung ab');
define('SORT_VAL3', '#Artikel auf');
define('SORT_VAL4', '#Artikel');
define('SORT_VAL5', 'Umsatz auf');
define('SORT_VAL6', 'Umsatz');

define('REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '<br>');
