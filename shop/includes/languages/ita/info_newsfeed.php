<?php
/* ----------------------------------------------------------------------
   $Id: info_newsfeed.php,v 1.3 2007/06/12 17:25:13 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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

$aLang['navbar_title'] = 'RDS/RSS Newsfeed';
$aLang['heading_title'] = 'RDS/RSS Newsfeed';

$aLang['text_information'] = 'Put here your information..<br /><b>URL:</b><br />';
if ($oEvent->installed_plugin('sefu')) {
  $aLang['text_information'] .= '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php/mp/' .$aModules['products'] . '/file/' . $aFilename['products_rss'] . ' "target="_blank">' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php/mp/' .$aModules['products'] . '/file/' . $aFilename['products_rss'] .'</a>';
} else {
  $aLang['text_information'] .= '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' .$aModules['products'] . '&file=' . $aFilename['products_rss'] . ' "target="_blank">' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' .$aModules['products'] . '&file=' . $aFilename['products_rss'] .'</a>';
}
$aLang['text_information'] .= '<br /><br /><br />
<b>PHP Client</b><br />
<a href="http://download.juretta.com/fase4/" target="_blank">RDF/RSS Klasse</a> Stefan Saasen';

?>
