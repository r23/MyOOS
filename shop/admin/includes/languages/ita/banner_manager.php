<?php
/* ----------------------------------------------------------------------
   $Id: banner_manager.php,v 1.3 2007/06/13 16:39:11 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banner_manager.php,v 1.17 2002/08/18 18:54:47 hpdl
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

define('HEADING_TITLE', 'Banner Manager');

define('TABLE_HEADING_BANNERS', 'Banners');
define('TABLE_HEADING_GROUPS', 'Gruppi');
define('TABLE_HEADING_STATISTICS', 'Visti/Cliccati');
define('TABLE_HEADING_STATUS', 'Stato');
define('TABLE_HEADING_ACTION', 'Azione');

define('TEXT_BANNERS_TITLE', 'Titolo Banner:');
define('TEXT_BANNERS_URL', 'URL Banner:');
define('TEXT_BANNERS_GROUP', 'Gruppo Banner:');
define('TEXT_BANNERS_NEW_GROUP', ', o inserisci un nuovo Gruppo Banner sotto');
define('TEXT_BANNERS_IMAGE', 'Immagine:');
define('TEXT_BANNERS_IMAGE_LOCAL', ', o inserisci un file locale sotto');
define('TEXT_BANNERS_IMAGE_TARGET', 'Oggetto Immagine (Salva come):');
define('TEXT_BANNERS_HTML_TEXT', 'Testo HTML:');
define('TEXT_BANNERS_EXPIRES_ON', 'Scadenza:');
define('TEXT_BANNERS_OR_AT', ', o a');
define('TEXT_BANNERS_IMPRESSIONS', 'cliccati/visti.');
define('TEXT_BANNERS_SCHEDULED_AT', 'Programmato il:');
define('TEXT_BANNERS_BANNER_NOTE', '<b>Note Banner:</b><ul><li>Usa un\'immagine o testo HTML per il Banner - non entrambi.</li><li>Un testo HTML è prioritario rispetto ad una immagine.</li></ul>');
define('TEXT_BANNERS_INSERT_NOTE', '<b>Note Immagine:</b><ul><li>Le Directory di Upload (dove vengono caricati i file) devono essere abilitate in scrittura!</li><li>Non compilare il campo \'Salva come\' se non stai inviando un\'immagine nel Webserver (Es. stai usando un\'immagine sul computer locale).</li><li>Il campo \'Salva come\' deve essere in una Directory esistente con lo slash finale (Es. banners/).</li></ul>');
define('TEXT_BANNERS_EXPIRCY_NOTE', '<b>Note di scadenza:</b><ul><li>Solo uno dei due campi può essere inviato</li><li>Se il Banner non scade automaticamente lascia questi campi bianchi.</li></ul>');
define('TEXT_BANNERS_SCHEDULE_NOTE', '<b>Note Programma:</b><ul><li>Se un programma è già stato impostato, il banner si attiverà in quella data.</li><li>Tutti i Banner programmati saranno visualizzati come non attivi fino alla loro data di attivazione, durante la quale torneranno attivi.</li></ul>');

define('TEXT_BANNERS_DATE_ADDED', 'Data inserimento:');
define('TEXT_BANNERS_SCHEDULED_AT_DATE', 'Programmati il: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_DATE', 'Scadenza: <b>%s</b>');
define('TEXT_BANNERS_EXPIRES_AT_IMPRESSIONS', 'Scadenze: <b>%s</b> impresse');
define('TEXT_BANNERS_STATUS_CHANGE', 'Modifica Status: %s');

define('TEXT_BANNERS_DATA', 'D<br>A<br>T<br>A');
define('TEXT_BANNERS_LAST_3_DAYS', 'Ultimi 3 giorni');
define('TEXT_BANNERS_BANNER_VIEWS', 'Banner Visti');
define('TEXT_BANNERS_BANNER_CLICKS', 'Banner Cliccati');

define('TEXT_INFO_DELETE_INTRO', 'Sicuro di voler cancellare questo Banner?');
define('TEXT_INFO_DELETE_IMAGE', 'Cancella Immagine Banner');

define('SUCCESS_BANNER_INSERTED', 'Operazione Riuscita: Il Banner è stato inserito.');
define('SUCCESS_BANNER_UPDATED', 'Operazione Riuscita:Il Banner è stato aggiornato.');
define('SUCCESS_BANNER_REMOVED', 'Operazione Riuscita: Il Banner è stato rimosso.');
define('SUCCESS_BANNER_STATUS_UPDATED', 'Operazione Riuscita: Lo Status del Banner è stato aggiornato.');

define('ERROR_BANNER_TITLE_REQUIRED', 'Errore: Titolo Banner obbligatorio.');
define('ERROR_BANNER_GROUP_REQUIRED', 'Errore: Gruppo Banner obbligatorio.');
define('ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Errore: La Directory non esiste.');
define('ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Errore: La Directory  non è scrivibile.');
define('ERROR_IMAGE_DOES_NOT_EXIST', 'Errore: Immagine inesistente.');
define('ERROR_IMAGE_IS_NOT_WRITEABLE', 'Errore: L\'Immagine non puo essere rimossa.');
define('ERROR_UNKNOWN_STATUS_FLAG', 'Errore: Bandiera Stato sconosciuta.');

define('ERROR_GRAPHS_DIRECTORY_DOES_NOT_EXIST', 'Errore: La Directory graphs/ non esiste. Crea la Directory graphs/ all\' interno della Directory images/.');
define('ERROR_GRAPHS_DIRECTORY_NOT_WRITEABLE', 'Errore: La Directory graphs/ non è scrivibile.');

?>
