<?php
/**
   ----------------------------------------------------------------------
   $Id: checkout_success.php,v 1.3 2007/06/12 16:36:39 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: checkout_success.php,v 1.17 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

$aLang['navbar_title_1'] = 'Kasse';
$aLang['navbar_title_2'] = 'Erfolg';

$aLang['heading_title'] = 'Vielen Dank!';

$aLang['text_success'] = 'Ihre Bestellung ist eingegangen und wird bearbeitet! Die Lieferung erfolgt innerhalb von ca. 2-5 Werktagen.';
$aLang['text_notify_products'] = 'Bitte benachrichtigen Sie mich über Aktuelles zu folgenden Produkten:';
$aLang['text_see_orders'] = 'Sie können Ihre Bestellung(en) auf der Seite <a href="' . oos_href_link($aContents['account'], '') . '"><u>\'Ihr Konto\'</a></u> jederzeit einsehen und sich dort auch Ihre <a href="' . oos_href_link($aContents['account_history'], '') . '"><u>\'Bestellübersicht\'</u></a> anzeigen lassen.';
$aLang['text_contact_store_owner'] = 'Falls Sie Fragen bezüglich Ihrer Bestellung haben, wenden Sie sich an unseren <a href="' . oos_href_link($aContents['contact_us']) . '"><u>Vertrieb</u></a>.';
$aLang['text_thanks_for_shopping'] = 'Wir danken Ihnen für Ihren Online-Einkauf!';

$aLang['table_heading_download_date'] = 'herunterladen möglich bis:';
$aLang['table_heading_download_count'] = 'max. Anz. Downloads';
$aLang['heading_download'] = 'Artikel herunterladen:';
$aLang['footer_download'] = 'Sie können Ihre Artikel auch später unter \'%s\' herunterladen';

$aLang['text_sincere_regards'] = 'Vielen Dank, dass Sie sich für uns entschieden haben.';
