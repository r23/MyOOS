<?php
/* ----------------------------------------------------------------------
   $Id: checkout_success.php,v 1.1 2007/06/13 15:54:25 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:
  
   File: checkout_success.php,v 1.10 2002/11/12 00:45:21 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Pedido';
$aLang['navbar_title_2'] = 'Realizado con Exito';

$aLang['heading_title'] = 'Su Pedido ha sido Procesado!';

$aLang['text_success'] = 'Su pedido ha sido realizado con exito! Sus productos llegaran a su destino de 2 a 5 dias laborales.';
$aLang['text_notify_products'] = 'Por favor notifiqueme de cambios realizados a los productos seleccionados:';
$aLang['text_see_orders'] = 'Puede ver sus pedidos viendo la pagina de <a href="' . oos_href_link($aModules['user'], $aFilename['account'], '', 'SSL') . '">\'Su Cuenta\'</a> y pulsando sobre <a href="' . oos_href_link($aModules['account'], $aFilename['account_history'], '', 'SSL') . '">\'Historial\'</a>.';
$aLang['text_contact_store_owner'] = 'Dirija sus preguntas al <a href="' . oos_href_link($aModules['main'], $aFilename['contact_us']) . '">administrador</a>.';
$aLang['text_thanks_for_shopping'] = 'Gracias por comprar con nosotros!';

$aLang['table_heading_download_date'] = 'Caducidad';
$aLang['table_heading_download_count'] = 'Descargas Maximas';
$aLang['heading_download'] = 'Descargue sus productos aqui:';
$aLang['footer_download'] = 'Puede descargar sus productos mas tarde en \'%s\'';
?>
