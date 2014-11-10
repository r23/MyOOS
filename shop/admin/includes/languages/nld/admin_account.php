<?php
/* ----------------------------------------------------------------------
   $Id: admin_account.php,v 1.1 2007/06/13 16:39:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.13 2002/08/19 01:45:58 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('HEADING_TITLE', 'Beheer rekeningen');

define('TABLE_HEADING_ACCOUNT', 'Mijn rekening');

define('TEXT_INFO_FULLNAME', '<b>Naam: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>Voornaam: </b>');
define('TEXT_INFO_LASTNAME', '<b>Achternaam: </b>');
define('TEXT_INFO_EMAIL', '<b>Emailadres: </b>');
define('TEXT_INFO_PASSWORD', '<b>Wachtwoord: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Verborgen-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Bevestig wachtwoord: </b>');
define('TEXT_INFO_CREATED', '<b>Rekening aangemaakt: </b>');
define('TEXT_INFO_LOGDATE', '<b>Laatste selectie: </b>');
define('TEXT_INFO_LOGNUM', '<b>Selectienummer: </b>');
define('TEXT_INFO_GROUP', '<b>Groepsniveau: </b>');
define('TEXT_INFO_ERROR', '<font color="red">Emailadres wordt al gebruikt! Probeer het nog een keer.</font>');
define('TEXT_INFO_MODIFIED', 'Veranderd: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Rekening bewerken ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Wachtwoord bevestigen ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Wachtwoord:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', '<font color="red"><b>FOUT:</b> verkeerd wachtwoord!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Klik op <b>Aanpassen</b> onderaan om de gegevens te veranderen.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', '<br /><b>WAARSCHUWING:</b><br />Hallo <b>%s</b>, u heeft zich hier voor het eerst aangemeld. Wij raden u aan uw wachtwoord te veranderen!');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', '<br /><b>WAARSCHUWING:</b><br />Hallo <b>%s</b>, wij raden u aan uw emailadres te veranderen (<font color="red">email@adrsnaam.nl</font>) en tevens het wachtwoord!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'Volgende velden zijn verplicht. Klik beveiligen voor dataoverdracht.');

define('JS_ALERT_FIRSTNAME',        '- Verplicht: Voornaam \n');
define('JS_ALERT_LASTNAME',         '- Verplicht: Achternaam \n');
define('JS_ALERT_EMAIL',            '- Verplicht: Emailadres \n');
define('JS_ALERT_PASSWORD',         '- Verplicht: Wachtwoord \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Aantal tekens voornaam moet meer zijn dan ');
define('JS_ALERT_LASTNAME_LENGTH',  '- Aantal tekens achternaam moet meer zijn dan ');
define('JS_ALERT_PASSWORD_LENGTH',  '- Aantal tekens wachtwoord moet meer zijn dan  ');
define('JS_ALERT_EMAIL_FORMAT',     '- Het formaat van het emailadres is ongeldig! \n');
define('JS_ALERT_EMAIL_USED',       '- Dit emailadres wordt al gebruikt! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- In het wachtwoord bevestigingsveld is niets ingevuld! \n');

?>
