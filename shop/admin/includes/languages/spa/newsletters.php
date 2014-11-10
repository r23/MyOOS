<?php
/* ----------------------------------------------------------------------
   $Id: newsletters.php,v 1.3 2007/06/13 16:51:45 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletters.php,v 1.6 2002/03/09 20:18:24 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Administrador de Boletines');

define('TABLE_HEADING_NEWSLETTERS', 'Boletines');
define('TABLE_HEADING_SIZE', 'Tama�');
define('TABLE_HEADING_MODULE', 'Modulo');
define('TABLE_HEADING_SENT', 'Enviado');
define('TABLE_HEADING_STATUS', 'Estado');
define('TABLE_HEADING_ACTION', 'Accion');

define('TEXT_NEWSLETTER_MODULE', 'Modulo:');
define('TEXT_NEWSLETTER_TITLE', 'Titulo del Boletin:');
define('TEXT_NEWSLETTER_CONTENT', 'Contenido:');

define('TEXT_NEWSLETTER_DATE_ADDED', 'A�dido el:');
define('TEXT_NEWSLETTER_DATE_SENT', 'Fecha Envio:');

define('TEXT_INFO_DELETE_INTRO', 'Seguro que quiere eliminar este boletin?');

define('TEXT_PLEASE_WAIT', 'Por favor espere .. enviando emails ..<br /><br />No interrumpa este proceso!');
define('TEXT_FINISHED_SENDING_EMAILS', 'Finalizado el envio de emails!');

define('ERROR_NEWSLETTER_TITLE', 'Error: Se requiere un titulo para el boletin');
define('ERROR_NEWSLETTER_MODULE', 'Error: Se requiere un module para el boletin');
define('ERROR_REMOVE_UNLOCKED_NEWSLETTER', 'Error: Bloquee el boletin antes de eliminarlo.');
define('ERROR_EDIT_UNLOCKED_NEWSLETTER', 'Error: Bloquee el boletin antes de editarlo.');
define('ERROR_SEND_UNLOCKED_NEWSLETTER', 'Error: Bloquee el boletin antes de enviarlo.');
?>
