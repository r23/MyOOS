<?php
/* ----------------------------------------------------------------------
   $Id: stats_sales_report2.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

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

/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('REPORT_DATE_FORMAT', 'd. m. Y');

define('HEADING_TITLE', 'Report Vendite');

define('REPORT_TYPE_YEARLY', 'Annuale');
define('REPORT_TYPE_MONTHLY', 'Mensile');
define('REPORT_TYPE_WEEKLY', 'Settimanale');
define('REPORT_TYPE_DAILY', 'Giornaliero');
define('REPORT_START_DATE', 'dalla data');
define('REPORT_END_DATE', 'alla data (inclusa)');
define('REPORT_DETAIL', 'dettagli');
define('REPORT_MAX', 'mostra il massimo');
define('REPORT_ALL', 'tutto');
define('REPORT_SORT', 'ordina');
define('REPORT_EXP', 'esporta');
define('REPORT_SEND', 'spedisci');
define('EXP_NORMAL', 'normale');
define('EXP_HTML', 'solo HTML');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', 'Data');
define('TABLE_HEADING_ORDERS', '#Ordini');
define('TABLE_HEADING_ITEMS', '#Prodotti');
define('TABLE_HEADING_REVENUE', 'Ricavo');
define('TABLE_HEADING_SHIPPING', 'Trasporto');

define('DET_HEAD_ONLY', 'no dettalgi');
define('DET_DETAIL', 'vedi dettagli');
define('DET_DETAIL_ONLY', 'dettagli con importo');

define('SORT_VAL0', 'standard');
define('SORT_VAL1', 'descrizione');
define('SORT_VAL2', 'decrizione desc');
define('SORT_VAL3', '#Prodotti');
define('SORT_VAL4', '#Prodotti desc');
define('SORT_VAL5', 'Ricavo');
define('SORT_VAL6', 'Ricavo desc');

define('REPORT_STATUS_FILTER', 'Stato');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '\n\r');

?>
