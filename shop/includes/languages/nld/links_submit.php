<?php
/* ----------------------------------------------------------------------
   $Id: links_submit.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links_submit.php,v 1.00 2003/10/03 
   ----------------------------------------------------------------------
   Links Manager
   
   Contribution based on:
   
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Links';
$aLang['navbar_title_2'] = 'Vraag een link aan';

$aLang['heading_title'] = 'Link informatie';

$aLang['text_main'] = 'Vul a.u.b. het volgende formulier in om uw website aan te nelden.';

$aLang['email_subject'] = 'Welkome bij ' . STORE_NAME . ' link uitwisseling.';
$aLang['email_greet_none'] = 'Hallo %s' . "\n\n";
$aLang['email_welcome'] = 'We verwelkomen u bij het <b>' . STORE_NAME . '</b> link uitwissel programma.' . "\n\n";
$aLang['email_text'] = 'Uw link is succesvol aangevraagd bij ' . STORE_NAME . '. Hij zal toegevoegd worden aan onze lijst zodra hij goedgekeurd is. U zal een email ontvangen over de status van uw aanvraag. Als u nog geen email ontvangen heeft binnen 48 uur, neem dan a.u.b. contact met ons op voordat u uw link weer aanvraagd.' . "\n\n";
$aLang['email_contact'] = 'voor hulp met ons link uitwisselprogramma email a.u.b. de winkeleigenaar: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<b>Opmerking:</b> Dit emailadres was aan ons gegeven tijdens een link aanvraag. Als u een probleem heeft, stuur dan a.u.b. een email naar ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";
$aLang['email_owner_subject'] = 'Link aanvraag op ' . STORE_NAME;
$aLang['email_owner_text'] = 'Een nieuwe link werd aangevraagd op ' . STORE_NAME . '. Deze is nog niet goedgekeurd. Verifie&euml;r deze link en bevestig.' . "\n\n";

$aLang['text_links_help_link'] = '&nbsp;Hulp&nbsp;[?]';

$aLang['heading_links_help'] = 'Links hulp';
$aLang['text_links_help'] = '<b>Site titel:</b> Een beschrijvende tekst voor uw website.<br><br><b>URL:</b> Het absolute webadres van uw website, inclusief de \'http://\'.<br><br><b>Categorie:</b> Meestt geschikte categorie waaronder uw website valt.<br><br><b>Beschrijving:</b> Een korte beschrijving van uw website.<br><br><b>Afbeelding URL:</b> De absolute URL van de afbeelding die u wilt aanvragen, inclusief \'http://\'. Deze afbeelding wordt getoond samen met uw website link.<br>B.v.: http://uw-domein.nl/pad/naar/uw/afbeelding.gif <br><br><b>Volledige naam:</b> Uw volledige naam.<br><br><b>Email:</b> Uw emailadres. Voer a.u.b. een geldig emailadres in, omdat u bericht krijgt via email.<br><br><b>Wederzijdse pagina:</b> De absolute URL van uw linkpagina, waar een link naar onze web getoond wordt.<br>B.v.: http://uw-domein.nl/pad/naar/uwr/link_pagina.php';
$aLang['text_close_window'] = '<u>Venster sluiten</u> [x]';

// VJ todo - move to common language file
$aLang['category_website'] = 'Website details';
$aLang['category_reciprocal'] = 'Wederzijdse paginadetails';

$aLang['entry_links_title'] = 'Site titel:';
$aLang['entry_links_title_error'] = 'Link titel moet minstens ' . ENTRY_LINKS_TITLE_MIN_LENGTH . ' karakters bevatten.';
$aLang['entry_links_title_text'] = '*';
$aLang['entry_links_url'] = 'URL:';
$aLang['entry_links_url_error'] = 'URL must moet minstens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' karakters bevatten.';
$aLang['entry_links_url_text'] = '*';
$aLang['entry_links_category'] = 'Categorie:';
$aLang['entry_links_category_text'] = '*';
$aLang['entry_links_description'] = 'Beschrijving:';
$aLang['entry_links_description_error'] = 'Beschrijving moet minstens ' . ENTRY_LINKS_DESCRIPTION_MIN_LENGTH . ' karakters bevatten.';
$aLang['entry_links_description_text'] = '*';
$aLang['entry_links_image'] = 'Afbeelding URL:';
$aLang['entry_links_image_text'] = '';
$aLang['entry_links_contact_name'] = 'Volledige naam:';
$aLang['entry_links_contact_name_error'] = 'Uw volledige naam moet minstens ' . ENTRY_LINKS_CONTACT_NAME_MIN_LENGTH . ' karakters bevatten.';
$aLang['entry_links_contact_name_text'] = '*';
$aLang['entry_links_reciprocal_url'] = 'Wederzijdse pagina:';
$aLang['entry_links_reciprocal_url_error'] = 'Wederzijdse pagina moet minstens ' . ENTRY_LINKS_URL_MIN_LENGTH . ' karakters bevatten.';
$aLang['entry_links_reciprocal_url_text'] = '*';
?>
