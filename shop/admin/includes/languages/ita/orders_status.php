<?php
/* ----------------------------------------------------------------------
   $Id: orders_status.php,v 1.3 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders_status.php,v 1.5 2002/01/29 14:43:00 hpdl 
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

define('HEADING_TITLE', 'Stato Ordini');

define('TABLE_HEADING_ORDERS_STATUS', 'Stato Ordini');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_INFO_EDIT_INTRO', 'Effettua i cambiamenti necessari');
define('TEXT_INFO_ORDERS_STATUS_NAME', 'Stato Ordini:');
define('TEXT_INFO_INSERT_INTRO', 'Inserisci il nuovo stato dell\'Ordine con i relativi dati');
define('TEXT_INFO_DELETE_INTRO', 'Sicuro di voler cancellare lo stato dell\' Ordine?');
define('TEXT_INFO_HEADING_NEW_ORDERS_STATUS', 'Nuovo Stato');
define('TEXT_INFO_HEADING_EDIT_ORDERS_STATUS', 'Modifica Stato');
define('TEXT_INFO_HEADING_DELETE_ORDERS_STATUS', 'Cancella Stato');

define('ERROR_REMOVE_DEFAULT_ORDER_STATUS', 'Errore: Lo stato dell\' ordine impostato di default non può essere cancellato. Abilita un altro Stato di default e riprova.');
define('ERROR_STATUS_USED_IN_ORDERS', 'Errore: Questo stato dell\'ordine è correntemente usato negli Ordini.');
define('ERROR_STATUS_USED_IN_HISTORY', 'Errore: Questo stato dell\'ordine è correntemente usato nella Cronologia degli Stati.');
?>
