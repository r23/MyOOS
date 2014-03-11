<?php
/* ----------------------------------------------------------------------
   $Id: user_login.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

if (isset($_GET['origin']) && ($_GET['origin'] == $aContents['checkout_payment'])) {
  $aLang['navbar_title'] = 'Bestellen';
  $aLang['heading_title'] = 'Eine Online-Bestellung ist einfach.';
} else {
  $aLang['navbar_title'] = 'Anmelden';
  $aLang['heading_title'] = 'Melden Sie sich an';
}

$aLang['heading_new_customer'] = 'Neuer Kunde';
$aLang['text_new_customer'] = 'Ich bin ein neuer Kunde.';
$aLang['text_new_customer_introduction'] = 'Durch Ihre Anmeldung bei ' . STORE_NAME . ' sind Sie in der Lage schneller zu bestellen, kennen jederzeit den Status Ihrer Bestellungen und haben immer eine aktuelle Übersicht über Ihre bisherigen Bestellungen.';

$aLang['heading_returning_customer'] = 'Bereits Kunde';
$aLang['text_returning_customer'] = 'Ich bin bereits Kunde.';

$aLang['title_guest'] = 'Ich möchte ohne Registrierung bestellen';
$aLang['text_guest'] = 'Bei uns können Sie auch ohne Registrierung bestellen.<br />Beachten Sie bitte, dass Sie bei jeder weiteren Bestellung Ihre Daten erneut eingeben müssen.';


$aLang['entry_remember_me'] = 'Einlogautomatik<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:win_autologon(\'' . oos_href_link($aContents['info_autologon']) . '\');"><b><u>Zuerst hier lesen!</u></b></a>';
$aLang['text_password_forgotten'] = 'Sie haben Ihr Passwort vergessen? Dann klicken Sie <u>hier</u>';

$aLang['text_login_error'] = 'Keine Übereinstimmung der eingebenen \'eMail-Adresse\' und/oder dem \'Passwort\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>ACHTUNG:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win(\'' . oos_href_link($aContents['info_shopping_cart']) . '\');">[Mehr Information]</a>';

