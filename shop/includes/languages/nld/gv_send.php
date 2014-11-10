<?php
/* ----------------------------------------------------------------------
   $Id: gv_send.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_send.php,v 1.1.2.1 2003/05/15 23:04:32 wilt
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['heading_title'] = 'Tegoedbon versturen';
$aLang['navbar_title'] = 'Tegoedbon versturen';
$aLang['email_subject'] = 'Tegoedbon ' . STORE_NAME;
$aLang['heading_text'] = '<br />Hier kan u makkelijk en ongecompliceerd een bekende een tegoedbon voor de inkoop bij ' . STORE_NAME . ' laten versturen.<br /> Eenvoudig de naam en het emailadres van de persoon invullen, aan wie u tegoedbon schenken wilt, dan nog de
 het bedrag van de tegoedbon invullen, op <b><tt>weiter</tt></b> klikken, en weg er mee. Verdere informatie over de tegoedbonfunctie vindt u op de pagina <a href="' . oos_href_link($aModules['gv'], $aFilename['gv_faq']).'">Tegoedbon FAQ\'s</a><br />';
$aLang['entry_name'] = 'Ontvanger - naam:';
$aLang['entry_email'] = 'Ontvanger - emailadres:';
$aLang['entry_message'] = 'Uw bericht (wordt met de tegoedbon meegestuurd):';
$aLang['entry_amount'] = 'Bedrag van de bon:';
$aLang['error_entry_amount_check'] = '&nbsp;&nbsp;<span class="errorText">Ongeldig bedrag</span>';
$aLang['error_entry_email_address_check'] = '&nbsp;&nbsp;<span class="errorText">Ongeldig emailadres</span>';
$aLang['main_message'] = 'U wilt een tegoedbon ter waarde van %s aan %s  Emailadres  %s versturen.<br /><br />Navolgende tekst zal in de email staan: <br /><br />Hallo %s<br /><br /> Aan u werd een tegoedbon ter waarde van %s door %s gestuurd.';

$aLang['personal_message'] = '%s schrijft';
$aLang['text_success'] = 'Gefeliciteerd, uw tegoedbon werd succesvol verstuurd.';

$aLang['email_separator'] = '----------------------------------------------------------------------------------------';
$aLang['email_gv_text_header'] = 'Gefeliciteerd, u krijgt een tegoedbon ter waarde van %s';
$aLang['email_gv_text_subject'] = 'Dit is een tegoedbon van %s';
$aLang['email_gv_from'] = 'Deze tegoedbon werd u gestuurd door %s';
$aLang['email_gv_message'] = 'met het volgende bericht ';
$aLang['email_gv_send_to'] = 'Hallo, %s';
$aLang['email_gv_redeem'] = 'Om deze tegoedbon te gebruiken, klikt u a.u.b. op de onderstaande link. Noteer a.u.b. ook de tegoedboncode welke % s is. Indien u problemen hebt.';
$aLang['email_gv_link'] = 'Om te gebruiken a.u.b. klikken ';
$aLang['email_gv_visit'] = ' of bezoeken ';
$aLang['email_gv_enter'] = ' en de tegoedboncode invoeren ';
$aLang['email_gv_fixed_footer'] = 'Indien u problemen hebt om de tegoedbon met de geautomatiseerde link te gebruiken, ' . "\n" . 
                                'kan u de tegoedbonccode ook bij de bestelling invoeren, wanneer u aan de kassa afrekent.' . "\n\n";
$aLang['email_gv_shop_footer'] = '';
?>
