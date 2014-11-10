<?php
/* ----------------------------------------------------------------------
   $Id: admin_files.php,v 1.3 2007/06/13 16:39:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_categories.php,v 1.13 2002/08/19 01:45:58 hpdl
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
define('HEADING_TITLE', 'Menu Amministrazione "Boxes"');

define('TABLE_HEADING_ACTION', 'Azione');
define('TABLE_HEADING_BOXES', 'Boxes');
define('TABLE_HEADING_FILENAME', 'Nomi File');
define('TABLE_HEADING_GROUPS', 'Gruppi');
define('TABLE_HEADING_STATUS', 'Stato');

define('TEXT_COUNT_BOXES', 'Boxes: ');
define('TEXT_COUNT_FILES', 'File(s): ');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Boxes: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Semplicemente clicca il bottone verde per attivare il box, il rosso per disattivarlo.<br /><br /><b>Attenzione:</b> Se disattivi un box, verrannno perse le associazioni dei files!');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' Installato');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' non installato');

define('STATUS_BOX_INSTALLED', 'Installato');
define('STATUS_BOX_NOT_INSTALLED', 'Non Installato');
define('STATUS_BOX_REMOVE', 'Rimuovi');
define('STATUS_BOX_INSTALL', 'Installa');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'File: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Conferma rimozione');
define('TEXT_INFO_HEADING_NEW_FILE', 'Inserisci File');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Clicca <b>Inserisci File</b> per iserire un nuovo file nel box corrente: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Rimuovi <font color="red"><b>%s</b></font> dal box <b>%s</b>? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Controlla il <font color="red"><b>menu sinistro</b></font> per assicurarti di aver inserito i file corretti.');

define('TEXT_INFO_NEW_FILE_BOX', 'Box Selezionato: ');

?>
