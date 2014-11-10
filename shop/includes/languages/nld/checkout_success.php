<?php
/* ----------------------------------------------------------------------
   $Id: checkout_success.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_success.php,v 1.17 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Kassa';
$aLang['navbar_title_2'] = 'Succes';

$aLang['heading_title'] = 'Hartelijk dank!';

$aLang['text_success'] = 'Uw bestelling is aangenomen en wordt verwerkt! De levering geschiedt binnen ca. 2-5 Werkdagen.';
$aLang['text_notify_products'] = 'informeer mij a.u.b. over actuele zaken van de volgende produkten:';
$aLang['text_see_orders'] = 'U kan uw bestelling(en) op pagina <a href="' . oos_href_link($aModules['user'], $aFilename['account'], 'SSL') . '"><u>\'Uw rekening\'</a></u> ieder moment bekijken en daar ook uw <a href="' . oos_href_link($aModules['account'], $aFilename['account_history'], 'SSL') . '"><u>\'Besteloverzicht\'</u></a> bekijken.';
$aLang['text_contact_store_owner'] = 'Indien u vragen met betrekking tot uw bestelling heeft, richt u zich dan aan onze <a href="' . oos_href_link($aModules['main'], $aFilename['contact_us']) . '"><u>Bedrijf</u></a>.';
$aLang['text_thanks_for_shopping'] = 'Wij danken u voor uw aankoop via onze webwinkel!';

$aLang['table_heading_download_date'] = 'downloaden mogelijk tot:';
$aLang['table_heading_download_count'] = 'max. aantal. Downloads';
$aLang['heading_download'] = 'Artikel downloaden:';
$aLang['footer_download'] = 'U kan uw artikel ook later onder \'%s\' downloaden';
?>
