<?php
/**
   ----------------------------------------------------------------------
   $Id: stats_sales_report2.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

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

define('REPORT_DATE_FORMAT', 'm. d. Y');

define('HEADING_TITLE', 'Sales Report');

define('REPORT_TYPE_YEARLY', 'Yearly');
define('REPORT_TYPE_MONTHLY', 'Monthly');
define('REPORT_TYPE_WEEKLY', 'Weekly');
define('REPORT_TYPE_DAILY', 'Daily');
define('REPORT_START_DATE', 'from date');
define('REPORT_END_DATE', 'to date (inclusive)');
define('REPORT_DETAIL', 'detail');
define('REPORT_MAX', 'show top');
define('REPORT_ALL', 'all');
define('REPORT_SORT', 'sort');
define('REPORT_EXP', 'export');
define('REPORT_SEND', 'send');
define('EXP_NORMAL', 'normal');
define('EXP_HTML', 'HTML only');
define('EXP_CSV', 'CSV');

define('TABLE_HEADING_DATE', 'Date');
define('TABLE_HEADING_ORDERS', '#Orders');
define('TABLE_HEADING_ITEMS', '#Items');
define('TABLE_HEADING_REVENUE', 'Revenue');
define('TABLE_HEADING_SHIPPING', 'Shipping');

define('DET_HEAD_ONLY', 'no details');
define('DET_DETAIL', 'show details');
define('DET_DETAIL_ONLY', 'details with amount');

define('SORT_VAL0', 'standard');
define('SORT_VAL1', 'description');
define('SORT_VAL2', 'description desc');
define('SORT_VAL3', '#Items');
define('SORT_VAL4', '#Items desc');
define('SORT_VAL5', 'Revenue');
define('SORT_VAL6', 'Revenue desc');

define('REPORT_STATUS_FILTER', 'Status');

define('SR_SEPARATOR1', ';');
define('SR_SEPARATOR2', ';');
define('SR_NEWLINE', '\n\r');
