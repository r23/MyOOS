<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.12 2002/06/17 23:10:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if (isset($_GET['origin']) && ($_GET['origin'] == $aFilename['checkout_payment'])) {
  $aLang['navbar_title'] = 'Bestellen';
  $aLang['heading_title'] = 'Een webwinkel bestelling is eenvoudig.';
} else {
  $aLang['navbar_title'] = 'Aanmelden';
  $aLang['heading_title'] = 'Meldt u zich aan';
}

$aLang['heading_new_customer'] = 'Nieuwe klant';
$aLang['text_new_customer'] = 'Ik ben een nieuwe klant.';
$aLang['text_new_customer_introduction'] = 'Door uw aanmelding bij ' . STORE_NAME . ' bent u in staat sneller te bestellen, weet u op ieder moment de status van uw bestelling en hebt altijd een actueel overzicht over uw voorgaande bestellingen.';

$aLang['heading_returning_customer'] = 'Bestaande klant';
$aLang['text_returning_customer'] = 'Ik ben al klant.';

$aLang['entry_remember_me'] = 'Auto inloggen<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon(\'' . oos_href_link($aModules['main'], $aFilename['info_autologon']) . '\');"><b><u>Allereerst hier lezen!</u></b></a>';
$aLang['text_password_forgotten'] = 'Heeft u uw wachtwoord vergeten? Klik dan <u>hier</u>';

$aLang['text_login_error'] = '<font color="#ff0000"><b>FOUT:</b></font> Geen overeenstemming met het ingevoerde \'emailadres\' en/of \'wachtwoord\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>ATTENTIE:</b></font> Uw invullingen als bezoeker worden automatisch met uw klantenrekening gekoppeld. <a href="javascript:session_win(\'' . oos_href_link($aModules['main'], $aFilename['info_shopping_cart']) . '\');">[Meer informatie]</a>';
?>
