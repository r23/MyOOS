<?php
/* ----------------------------------------------------------------------
   $Id: ticket_department.php,v 1.1 2007/06/13 16:39:16 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket_department.php,v 1.3 2003/04/25 21:37:11 hook
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


define('HEADING_TITLE', 'Afdelingen');

define('TABLE_HEADING_TEXT_DEPARTMENT', 'Afdelingen');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_DISPLAY_NUMBER_OF_TEXT_DEPARTMENT','Toon <b>%d</b> tot <b>%d</b> (van <b>%d</b> afdelingen)');

define('TEXT_INFO_EDIT_INTRO', 'Voer a.u.b. alle noodzakelijke veranderingen in.');
define('TEXT_INFO_TEXT_DEPARTMENT_NAME', 'Aanvraagafdeling:');
define('TEXT_INFO_INSERT_INTRO', 'Voer a.u.b. de nieuwe afdeling met alle relevante gegevens in');
define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze afdeling wilt wissen?');
define('TEXT_INFO_HEADING_NEW_TEXT_DEPARTMENT', 'Nieuwe aanvraagafdeling');
define('TEXT_INFO_HEADING_EDIT_TEXT_DEPARTMENT', 'Aanvraagafdeling bewerken');
define('TEXT_INFO_HEADING_DELETE_TEXT_DEPARTMENT', 'Aanvraagafdeling wissen');

define('ERROR_REMOVE_DEFAULT_TEXT_DEPARTMENT', 'Fout: De standaard-aanvraagafdeling kan niet gewist worden. Stel a.u.b. een andere aanvraagafdeling als standaard in en probeer het nog en keer.');
define('ERROR_DEPARTMENT_USED_IN_TICKET', 'Fout: Deze aanvraagafdeling wordt op dit moment nog door hulpaanvragen gebruikt.');
define('ERROR_DEPARTMENT_USED_IN_HISTORY', 'Fout: Deze aanvraagafdeling wrdt op dit moment nog in de hulpaanvraag geschiedenis gebruikt.');
?>
