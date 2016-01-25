<?php
/* ----------------------------------------------------------------------
   $Id: search_advanced.php,v 1.4 2008/01/15 10:17:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
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

$aLang['navbar_title'] = 'Erweiterte Suche';
$aLang['heading_title'] = 'Geben Sie Ihre Suchkriterien ein';

$aLang['heading_search_criteria'] = 'Geben Sie Ihre Stichworte ein';

$aLang['text_search_in_description'] = 'Auch in den Beschreibungen suchen';
$aLang['entry_categories'] = 'Kategorien:';
$aLang['entry_include_subcategories'] = 'Unterkategorien mit einbeziehen';
$aLang['entry_manufacturers'] = 'Hersteller:';
$aLang['entry_price_from'] = 'Preis ab:';
$aLang['entry_price_to'] = 'Preis bis:';
$aLang['entry_date_from'] = 'hinzugef&uuml;gt von:';
$aLang['entry_date_to'] = 'hinzugef&uuml;gt bis:';

$aLang['text_search_help_link'] = '<u>Hilfe zur erweiterten Suche</u> [?]';

$aLang['text_all_categories'] = 'Alle Kategorien';
$aLang['text_all_manufacturers'] = 'Alle Hersteller';

$aLang['heading_search_help'] = 'Hilfe zur erweiterten Suche';
$aLang['text_search_help'] = 'Die Suchfunktion erm&ouml;glicht Ihnen die Suche in den Produktnamen, Produktbeschreibungen, Herstellern und Artikelnummern.<br /><br />Sie haben die M&ouml;glichkeit logische Operatoren wie "AND" (Und) und "OR" (oder) zu verwenden.<br /><br />Als Beispiel k&ouml;nnten Sie also angeben: <u>Microsoft AND Maus</u>.<br /><br />Desweiteren k&ouml;nnen Sie Klammern verwenden um die Suche zu verschachteln, also z.B.:<br /><br /><u>Microsoft AND (Maus OR Tastatur OR "Visual Basic")</u>.<br /><br />Mit Anf&uuml;hrungszeichen k&ouml;nnen Sie mehrere Worte zu einem Suchbegriff zusammenfassen.';
$aLang['text_close_window'] = '<u>Fenster schliessen</u> [x]';

$aLang['js_at_least_one_input'] = '* Eines der folgenden Felder muss ausgefüllt werden:\n    Stichworte\n    Datum hinzugefügt von\n    Datum hinzugefügt bis\n    Preis ab\n    Preis bis\n';
$aLang['js_invalid_from_date'] = '* Unzulässiges von Datum\n';
$aLang['js_invalid_to_date'] = '* Unzulässiges bis jetzt\n';
$aLang['js_to_date_less_than_from_date'] = '* Das Datum von muss größer oder gleich bis jetzt sein\n';
$aLang['js_price_from_must_be_num'] = '* Preis ab, muss eine Zahl sein\n';
$aLang['js_price_to_must_be_num'] = '* Preis bis, muss eine Zahl sein\n';
$aLang['js_price_to_less_than_price_from'] = '* Preis bis muss größer oder gleich Preis ab sein.\n';
$aLang['js_invalid_keywords'] = '* Suchbegriff unzulässig\n';

