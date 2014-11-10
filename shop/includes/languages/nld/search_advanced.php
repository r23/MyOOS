<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.18 2003/02/16 00:42:02 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title'] = 'Uitgebreid zoeken';
$aLang['heading_title'] = 'Voer uw zoekcriteria in';

$aLang['heading_search_criteria'] = 'Voer uw zoekbegrippen in';

$aLang['text_search_in_description'] = 'Ook in de beschrijvingen zoeken';
$aLang['entry_categories'] = 'Categorie&euml;n:';
$aLang['entry_include_subcategories'] = 'Sub-categorie&euml;n meenemen';
$aLang['entry_manufacturers'] = 'Fabrikant:';
$aLang['entry_price_from'] = 'Prijs vanaf:';
$aLang['entry_price_to'] = 'Prijs tot:';
$aLang['entry_date_from'] = 'toegevoegd vanaf:';
$aLang['entry_date_to'] = 'toegevoegd tot:';

$aLang['text_search_help_link'] = '<u>Hulp bij uitgebreid zoeken</u> [?]';

$aLang['text_all_categories'] = 'Alle Categorie&euml;n';
$aLang['text_all_manufacturers'] = 'Alle fabrikanten';

$aLang['heading_search_help'] = 'Hulp bij uitgebreid zoeken';
$aLang['text_search_help'] = 'De zoekfunctie biedt u de mogelijkheid te zoeken in produktnamen, produktbeschrijvingen, Fabrikanten en artikelnummers.<br /><br />U hebt de mogelijkheid logisch operatoren zoals "AND" (en) en "OR" (of) te gebruiken.<br /><br />Als voorbeeld kan u dus invoeren: <u>broeken AND blauw</u>.<br /><br />Ook kan u haakjes gebruiken het zoeken verder uit te breiden, dus b.v.:<br /><br /><u>broeken AND (blauw OR groen OR "wit")</u>.<br /><br />Met aanhalingstekens kan u meerdere woorden tot een zoekbegrip samenvoegen.';
$aLang['text_close_window'] = '<u>Venster sluiten</u> [x]';

$aLang['js_at_least_one_input'] = '* Een van de volgende velden moet ingevuld worden:\n    Zoekwoorden\n    Datum toegevoegd vanaf\n    Datum toegevoegd tot\n    Prijs vanaf\n    Prijs tot\n';
$aLang['js_invalid_from_date'] = '* Ongeldige datum vanaf\n';
$aLang['js_invalid_to_date'] = '* Ongeldige datum tot\n';
$aLang['js_to_date_less_than_from_date'] = '* De datum vanaf moet lager zijn dan dataum tot\n';
$aLang['js_price_from_must_be_num'] = '* Prijs vanaf, moet een getal zijn\n';
$aLang['js_price_to_must_be_num'] = '* Prijs tot, moet een getal zijn\n';
$aLang['js_price_to_less_than_price_from'] = '* Prijs tot moet hoger of gelijk zijn dan prijs vanaf.\n';
$aLang['js_invalid_keywords'] = '* Zoekbegrip ongeldig\n';
?>
