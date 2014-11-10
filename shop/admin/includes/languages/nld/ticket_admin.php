<?php
/* ----------------------------------------------------------------------
   $Id: ticket_admin.php,v 1.1 2007/06/13 16:39:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_admin.php,v 1.3 2003/04/25 21:37:11 hook 
   ----------------------------------------------------------------------
   OSC-SupportTicketSystem
   Copyright (c) 2003 Henri Schmidhuber IN-Solution
  
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Hulpaanvraag beheer');

define('TABLE_HEADING_TEXT_ADMIN', 'Hulpaanvraag beheerder');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_ADMIN','Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> beheerders)');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_INFO_TEXT_ADMIN_NAME', 'Hulpaanvraag beheerders:');
define('TEXT_INFO_INSERT_INTRO', 'Voer a.u.b. de nieuwe hulpaanvraag beheerder met alle relevante gegevens in');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze hulpaanvraag beheerder wilt wissen');
define('TEXT_INFO_HEADING_NEW_TEXT_ADMIN', 'Nieuwe hulpaanvraag beheerder');
define('TEXT_INFO_HEADING_EDIT_TEXT_ADMIN', 'Hulpaanvraag beheerder veranderen');
define('TEXT_INFO_HEADING_DELETE_TEXT_ADMIN', 'Hulpaanvraag beheerder wissen');

define('ERROR_REMOVE_DEFAULT_TEXT_ADMIN', 'Fout: De standaard hulpaanvraag beheerder kan niet verwijderd worden. Stel a.u.b. een andere hulpaanvraag beheerder als standaard in en probeer het nog en keer.');
define('ERROR_ADMIN_USED_IN_TICKET', 'Fout: Deze hulpaanvraag beheerder is op dit moment bezet bij de hulpaanvragen.');
define('ERROR_ADMIN_USED_IN_HISTORY', 'Fout: Deze hulpaanvraag beheerder is op dit moment gebruikt in de aanvraag geschiedenis.');
?>
