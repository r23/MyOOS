<?php
/* ----------------------------------------------------------------------
   $Id: user_create_account_process.php,v 1.3 2007/06/12 17:09:44 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_process.php,v 1.15 2003/02/16 00:42:03 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$aLang['navbar_title_1'] = 'Rekening openen';
$aLang['navbar_title_2'] = 'Bewerken';
$aLang['heading_title'] = 'Informatie over uw klantenrekening';

$aLang['email_subject'] = 'Welkom bij ' . STORE_NAME;
$aLang['email_greet_mr'] = 'Geachte heer ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_ms'] = 'Geachte mevrouw ' . stripslashes($lastname) . ',' . "\n\n";
$aLang['email_greet_none'] = 'Beste ' . stripslashes($firstname) . ',' . "\n\n";
$aLang['email_welcome'] = 'welkom bij <b>' . STORE_NAME . '</b>.' . "\n\n";
$aLang['email_text'] = 'U kan nu van onze <b>webwinkel-service</b> gebruik maken. Deze service biedt onder andere:' . "\n\n" . '<li><b>Klanten winkelwagen</b> - Ieder artikel blijft geregistreerd todat u bij de kassa aankomt, of de produkten uit de winkelwagen verwijderd.' . "\n" . '<li><b>Adresboek</b> - Wij kunnen nu de produkten aan het door u  gekozen adres versturen. De perfekte manier om een verjaardagscadeau te versturen.' . "\n" . '<li><b>Vorige bestellingen</b> - U kan nu op ieder moment uw vorige bestellingen controleren.' . "\n" . '<li><b>Meningen over produkten</b> - Deel uw mening over onze produkten met anderen klanten.' . "\n\n";
$aLang['email_contact'] = 'Indien u vragen over onze klantenservice hebt, richt u zich aan de winkel: ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n\n";
$aLang['email_warning'] = '<b>Attentie:</b> Dit emailadres werd door een klant aan ons doorgegeven. Indien u zich niet aangemeld hebt, stuur dan a.u.b. een email aan ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n";

$aLang['email_gv_incentive_header'] = 'Om u als nieuwe klant te begroeten, hebben wij u een tegoedbon van %s gestuurd.';
$aLang['email_gv_redeem'] = 'De tegoedboncode is: %s. U kan deze, bij het afsluiten van uw bestelling invoeren';
$aLang['email_gv_link'] = 'Of u gebruikt de volgende link: ';
$aLang['email_coupon_incentive_header'] = 'Gefeliciteerd! Om uw bezoek aan onze webwinkel aantrekkelijker te maken ontvangt u deze tegoedbon!' . "\n" .
                                         'Er volgen verdere details over uw persoonlijke tegoedbon.' . "\n\n";
$aLang['email_coupon_redeem'] = 'Om de tegoedbon te gebruiken voert u a.u.b. de tegoedboncode %s ' . "\n" .
                               'bij het afronden van uw bestelling in!';

$aLang['email_password'] = 'Your password to \'' . STORE_NAME . '\' is:' . "\n\n" . '   %s' . "\n\n";

$aLang['email_disclaimer'] = '--- Vrijwaring ------------------------------------------------------------ ' . "\n\n" .
                            'Uw privegegevens' . "\n\n" .
                            'Wij verplichten ons uw gegevens (naam, email enz. ) in geen geval aan' . "\n" .
                            'derden door te geven, zover niet gerechtelijke afgedwongen worden.' . "\n" .
                            'Afgezien dat deze doorgave illegaal is, zien wij daarin geen enkel ' . "\n" .
                            'voordeel voor u of ons. Uw naam en emailadres zijn echter voor iedereen' . "\n" .
                            'zichtbaar, die uw toevoegingen op ' . oos_server_get_base_url() . ' lezen.' . "\n" .
                            'Omdat de communicatie en aanmelding in het World Wide Web in veel gevallen' . "\n" .
                            'ongecodeerd is, zodat ook uw gebruikersnaam en uw wachtwoord voor anderen' . "\n" .
                            'leesbaar overgebracht worden, gebruikt u a.u.b. voor de toegang tot' . "\n" .
                            oos_server_get_base_url() . ' uit veiligheidsoverweging een andere gebruikersnaam en' . "\n" .
                            'wachtwoord combinatie als b.v. voor uw computer of bankrekening.' . "\n\n" .
                            'U hebt niet om deze email gevraagd?' . "\n\n" .
                            'Deze email werd op ' . strftime(DATE_FORMAT_LONG) . ' van IPadres  ' . oos_server_get_remote() . ' ' . oos_server_get_var('REMOTE_HOST') . ' ' . "\n" .
                            'opgestart. Indien u niet uw ( ook niet tijdelijke ) IP adres is, wis dan deze email niet' . "\n" .
                            'maar wendt u zich dan aan de verantwoordelijke webmaster op ' . STORE_OWNER_EMAIL_ADDRESS . '.' . "\n". 
                            'Die kan u in de meeste gevallen verder helpen. Blijkt deze hulp onvoldoende te zijn wendt' . "\n" . 
                            'u zich dan rechtstreeks aan de provider van' . oos_server_get_var('REMOTE_HOST') . "\n\n" .
                            'Belangrijk: De beheerder van' . oos_server_get_base_url() . ' is slechts beperkt in staat' . "\n" .
                            'zulk misbruik te controleren, en is niet noodzakelijkerwijs verantwoordelijk voor deze email.';


$aLang['owner_email_subject'] = 'Nieuwe klant';
$aLang['owner_email_date'] = 'Datum:';
$aLang['owner_email_company_info'] = 'Bedrijfsgegevens';
$aLang['owner_email_contact'] = 'Contactinformatie';
$aLang['owner_email_options'] = 'Opties';
$aLang['owner_email_company'] = 'Bedrijfsnaam:';
$aLang['owner_email_owner'] = 'Eigenaar:';
$aLang['owner_email_number'] = 'Klantnummer:';
$aLang['owner_email_gender'] = 'Aanspreektitel:';
$aLang['owner_email_first_name'] = 'Voornaam:';
$aLang['owner_email_last_name'] = 'Achternaam:';
$aLang['owner_email_date_of_birth'] = 'Geboortedatum:';
$aLang['owner_email_address'] = 'Emailadres:';
$aLang['owner_email_street'] = 'Straat/Nr.:';
$aLang['owner_email_suburb'] = 'Stadsdeel:';
$aLang['owner_email_post_code'] = 'Postcode:';
$aLang['owner_email_city'] = 'Woonplaats:';
$aLang['owner_email_state'] = 'Provincie:';
$aLang['owner_email_country'] = 'Land:';
$aLang['owner_email_telephone_number'] = 'Telefoonnummer:';
$aLang['owner_email_fax_number'] = 'Faxnummer:';
$aLang['owner_email_newsletter'] = 'Nieuwsbrief:';
$aLang['owner_email_newsletter_yes'] = 'geabonneerd';
$aLang['owner_email_newsletter_no'] = 'niet geabonneerd';
$aLang['email_separator'] = '------------------------------------------------------';

?>
