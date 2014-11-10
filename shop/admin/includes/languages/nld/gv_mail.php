<?php
/* ----------------------------------------------------------------------
   $Id: gv_mail.php,v 1.1 2007/06/13 16:39:15 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.1.2.1 2003/05/15 23:10:55 wilt 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Tegoedbon aan klanten versturen');

define('TEXT_CUSTOMER', 'Klant:');
define('TEXT_SUBJECT', 'Onderwerp:');
define('TEXT_FROM', 'Afzender:');
define('TEXT_TO', 'Email aan:');
define('TEXT_AMOUNT', 'Bedrag:');
define('TEXT_MESSAGE', 'Mededeling:');
define('TEXT_SELECT_CUSTOMER', 'Klant selecteren');
define('TEXT_ALL_CUSTOMERS', 'Alle klanten');
define('TEXT_NEWSLETTER_CUSTOMERS', 'Aan alle nieuwsbericht abonnees');
define('TEXT_FROM_NAME', 'Afzender naam:');
define('TEXT_FROM_MAIL', 'Afzender email:');

define('NOTICE_EMAIL_SENT_TO', 'Attentie: email werd verzonden aan: %s');
define('ERROR_NO_CUSTOMER_SELECTED', 'Fout: Er werd geen klant geselecteerd.');
define('ERROR_NO_AMOUNT_SELECTED', 'Fout: U hebt geen bedrag voor de tegoedbon ingevuld.');

define('TEXT_GV_WORTH', 'Tegoedbonwaarde ');
define('TEXT_TO_REDEEM', 'Om de tegoedbon in te wisselen, klik op de onderstaande link. Noteer a.u.b. de tegoedboncode.');
define('TEXT_WHICH_IS', 'die is');
define('TEXT_IN_CASE', ' indien u problemen hebt.');
define('TEXT_OR_VISIT', 'of bezoek ');
define('TEXT_ENTER_CODE', ' en voer de tegoedboncode in ');

define ('TEXT_REDEEM_COUPON_MESSAGE_HEADER', 'U hebt succesvol een tegoebon van onze winkel verkregen. Uit veiligheidsoverweging wordt de tegoedbonwaarde niet direkt op rekening verwerkt. De winkeleigenaar werd over de ontvangst geinformeerd.');
define ('TEXT_REDEEM_COUPON_MESSAGE_AMOUNT', "\n\n" . 'De waarde van de tegoedbon bedraagd: %s');
define ('TEXT_REDEEM_COUPON_MESSAGE_BODY', "\n\n" . 'U kan nu onze winkel bezoeken, uw inloggen en de tegoedbon aan ieder gewenste persoon versturen.');
define ('TEXT_REDEEM_COUPON_MESSAGE_FOOTER', "\n\n");
?>
