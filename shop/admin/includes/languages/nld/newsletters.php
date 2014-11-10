<?php
/* ----------------------------------------------------------------------
   $Id: newsletters.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletters.php,v 1.7 2002/03/11 14:13:05 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Nieuwsbrieven beheer');

define('TABLE_HEADING_NEWSLETTERS', 'Nieuwsbrief');
define('TABLE_HEADING_SIZE', 'Grootte');
define('TABLE_HEADING_MODULE', 'Module');
define('TABLE_HEADING_SENT', 'Verstuurd');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Actie');

define('TEXT_NEWSLETTER_MODULE', 'Module:');
define('TEXT_NEWSLETTER_TITLE', 'Titel van de nieuwsbrief:');
define('TEXT_NEWSLETTER_CONTENT', 'Inhoud:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'Toegevoegd op:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Datum versturen:');

define('TEXT_INFO_DELETE_INTRO', 'Weet u zeker dat u deze nieuwsbrief wissen wilt?');

define('TEXT_PLEASE_WAIT', 'Een ogenblik geduld .. emails worden verstuurd ..<br /><br />Proces niet onderbreken a.u.b.!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Emails zijn verstuurd!');

define('ERROR_NEWSLETTER_TITLE', 'Fout: Er is een titel voor de nieuwsbrief noodzakelijk.');
define('ERROR_NEWSLETTER_MODULE', 'Fout: Het nieuwsbrief modul is noodzakelijk.');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Fout: Bitte sperren Sie das Rundschreiben bevor Sie es l&ouml;schen.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Fout: Blokker a.u.b. de nieuwsbrief voordat u deze bewerkt.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Fout: Blokker a.u.b. de nieuwsbrief voordat u deze verstuurd.');
?>
