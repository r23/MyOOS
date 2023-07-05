<?php
/**
   ----------------------------------------------------------------------
   $Id: orders_status.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders_status.php,v 1.7 2002/01/30 11:10:08 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('HEADING_TITLE', 'Bestellstatus');

define('TABLE_HEADING_ORDERS_STATUS', 'Bestellstatus');
define('TABLE_HEADING_ACTION', 'Aktion');

define('TEXT_INFO_EDIT_INTRO', 'Bitte führen Sie alle notwendigen Änderungen durch');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Bestellstatus:');
define('TEXT_INFO_INSERT_INTRO', 'Bitte geben Sie den neuen Bestellstatus mit allen relevanten Daten ein');
define('TEXT_INFO_DELETE_INTRO', 'Sind Sie sicher, dass Sie diesen Bestellstatus löschen möchten?');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', 'Neuer Bestellstatus');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', 'Bestellstatus bearbeiten');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', 'Bestellstatus löschen');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Fehler: Der Standard-Bestellstatus kann nicht gelöscht werden. Bitte definieren Sie einen neuen Standard-Bestellstatus und wiederholen Sie den Vorgang.');
define('ERROR_STATUS_USED_IN_ORDERS', 'Fehler: Dieser Bestellstatus wird zur Zeit noch bei den Bestellungen verwendet.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Fehler: Dieser Bestellstatus wird zur Zeit noch in der Bestellhistorie verwendet.');
