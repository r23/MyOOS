<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Produktstatus');

define('TABLE_HEADING_PRODUCTS_STATUS', 'Produktstatus');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_EDIT_INTRO', 'Bitte führen Sie alle notwendigen Änderungen durch');
define('TEXT_INFO_PRODUCTS_STATUS_NAME', 'Produktstatus:');
define('TEXT_INFO_INSERT_INTRO', 'Bitte geben Sie den neuen Produktstatus mit allen relevanten Daten ein');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diesen Produktstatus löschen möchten?');
define('TEXT_INFO_HEADING_NEW_PRODUCTS_STATUS', 'Neuer Produktstatus');
define('TEXT_INFO_HEADING_EDIT_PRODUCTS_STATUS', 'Produktstatus bearbeiten');
define('TEXT_INFO_HEADING_DELETE_PRODUCTS_STATUS', 'Produktstatus löschen');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Fehler: Der Standard-Produktstatus kann nicht gelöscht werden. Bitte definieren Sie einen neuen Standard-Produktstatus und wiederholen Sie den Vorgang.');
define('ERROR_STATUS_USED_IN_PRODUCTS', 'Fehler: Dieser Produktstatus wird zur Zeit noch bei den Bestellungen verwendet.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fehler: Dieser Produktstatus wird zur Zeit noch in der Bestellhistorie verwendet.');
