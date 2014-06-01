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

$aLang['text_new_customer'] = 'Sind Sie Neukunde?';
$aLang['text_new_customer_introduction'] = 'Jetzt registrieren.';

$aLang['heading_returning_customer'] = 'Bereits Kunde';
$aLang['text_returning_customer'] = 'Ich bin bereits Kunde.';

$aLang['title_guest'] = 'Ich möchte ohne Registrierung bestellen';
$aLang['text_guest'] = 'Bei uns können Sie auch ohne Registrierung bestellen.<br />Beachten Sie bitte, dass Sie bei jeder weiteren Bestellung Ihre Daten erneut eingeben müssen.';

$aLang['entry_remember_me'] = 'Angemeldet bleiben';

$aLang['text_password_lost'] = 'Sie haben Ihr Passwort vergessen?';
$aLang['text_password_forgotten'] = 'Dann klicken Sie hier';

$aLang['text_login_error'] = 'Keine Übereinstimmung der eingebenen \'eMail-Adresse\' und/oder dem \'Passwort\'.';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>ACHTUNG:</b></font> Ihre Besuchereingaben werden automatisch mit Ihrem Kundenkonto verbunden. <a href="javascript:session_win(\'' . oos_href_link($aContents['info_shopping_cart']) . '\');">[Mehr Information]</a>';

