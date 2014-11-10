<?php
/* ----------------------------------------------------------------------
   $Id: admin_login.php,v 1.3 2007/06/12 17:09:43 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.11 2002/06/03 13:19:42 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


$aLang['navbar_title'] = 'Inloggen';
$aLang['heading_title'] = 'Inloggen beheerder';
$aLang['entry_key'] = 'Veiligheidssleutel'; // should be empty

$aLang['heading_admin_login'] = 'Aanmelden onder een klantenrekening';
$aLang['entry_email_address'] = 'Emailadres:';

$aLang['text_login_error'] = '<font color="#ff0000"><b>FOUT:</b></font> Geen overeenkomst met \'Emailadres\' en/of \'Wachtwoord\'.';
$aLang['text_login_error2'] = '<font color="#ff0000"><b>GEEN TOEGANG VERKREGEN: Alsgevolg van eerdere onregelmatigheden of anderzijds frauduleuze handelingen, is uw rekening geblokkeerd voor onze winkel.<br /><br /> Wij accepteren geen bestellingen voor deze rekening meer.<br /><br />Als u bestellingen wilt plaatsen, dient u contact op te nemen met ons via de telefoon en vooraf te betalen via de bank of onder remboursement.<br /><br />Bestelling worden verstuurd met ontvangstbevesting.<br /><br /> Wij hebben een schriftelijke opdracht van uw bestelling nodig voordat wij verzenden.<br /><br />De bestelling wordt alleen verstuurd naar een geverifi&eumlerd adres. </b></font><br /><br />';
$aLang['text_visitors_cart'] = '<font color="#ff0000"><b>NOTE:</b></font> Uw &quot;bezoekerswinkelwagen&quot; inhoud zal toegevoegd worden  aan uw &quot;Leden winkelwagen&quot; inhoud als u ingelogd bent. <a href="javascript:session_win(\'' . oos_href_link($aModules['main'], $aFilename['info_shopping_cart']) . '\');">[Meer info]</a>';

?>
