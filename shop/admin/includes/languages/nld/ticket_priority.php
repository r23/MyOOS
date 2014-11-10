<?php
/* ----------------------------------------------------------------------
   $Id: ticket_priority.php,v 1.1 2007/06/14 17:11:36 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_priority.php,v 1.3 2003/04/25 21:37:11 hook
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


define('HEADING_TITLE', 'Prioriteiten');

define('TABLE_HEADING_TEXT_PRIORITY', 'Aanvraagprioriteit');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_PRIORITY','Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> prioriteiten.)');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_INFO_TEXT_PRIORITY_NAME', 'Aanvraagprioriteit:');
define('TEXT_INFO_INSERT_INTRO', 'Voer a.u.b. de nieuwe prioriteit met alle relevante gegevens in.');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze prioriteit wilt wissen?');
define('TEXT_INFO_HEADING_NEW_TEXT_PRIORITY', 'Nieuwe aanvraagprioriteit');
define('TEXT_INFO_HEADING_EDIT_TEXT_PRIORITY', 'Aanvraagprioriteit bewerken');
define('TEXT_INFO_HEADING_DELETE_TEXT_PRIORITY', 'Aanvraagprioriteit wissen');

define('ERROR_REMOVE_DEFAULT_TEXT_PRIORITY', 'Fout: De standaard-aanvraagprioriteit kan niet gewist worden. Stel a.u.b. een andere aanvraagprioriteit als standaard in en probeer het nog en keer.');
define('ERROR_PRIORITY_USED_IN_TICKET', 'Fout: Deze aanvraagprioriteit wordt op dit moment nog voor de aanvragen gebruikt.');
define('ERROR_PRIORITY_USED_IN_HISTORY', 'Fout: Deze aanvraagprioriteit wordt op dit moment nog in de aanvraaggeschiedenis gebruikt.');
?>
