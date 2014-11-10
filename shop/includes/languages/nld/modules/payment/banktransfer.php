<?php
/* ----------------------------------------------------------------------
   $Id: banktransfer.php,v 1.4 2007/10/24 23:38:34 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: banktransfer.php 126 2004-04-16 23:41:52Z dogu
   ----------------------------------------------------------------------

   OSC German Banktransfer
   (http://www.oscommerce.com/community/contributions,826)

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
/* ----------------------------------------------------------------------
   If you made a translation, please send to
      lang@oos-shop.de
   the translated file.
   ---------------------------------------------------------------------- */

define('MODULE_PAYMENT_BANKTRANSFER_STATUS_TITLE', 'Allow Banktranfer Payments');
define('MODULE_PAYMENT_BANKTRANSFER_STATUS_DESC', 'Do you want to accept banktransfer payments?');

define('MODULE_PAYMENT_BANKTRANSFER_ZONE_TITLE', 'Payment Zone');
define('MODULE_PAYMENT_BANKTRANSFER_ZONE_DESC', 'If a zone is selected, only enable this payment method for that zone.');

define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_TITLE', 'Allow Banktranfer to Max Order');
define('MODULE_PAYMENT_BANKTRANSFER_MAX_ORDER_DESC', 'Do you want to accept banktransfer to Credit Limit?');

define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_TITLE', 'Sort order of display.');
define('MODULE_PAYMENT_BANKTRANSFER_SORT_ORDER_DESC', 'Sort order of display. Lowest is displayed first.');

define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_TITLE', 'Set Order Status');
define('MODULE_PAYMENT_BANKTRANSFER_ORDER_STATUS_ID_DESC', 'Set the status of orders made with this payment module to this value');

define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_TITLE', 'Allow Fax Confirmation');
define('MODULE_PAYMENT_BANKTRANSFER_FAX_CONFIRMATION_DESC', 'Do you want to allow fax confirmation?');

define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_TITLE', 'Fax- File');
define('MODULE_PAYMENT_BANKTRANSFER_URL_NOTE_DESC', 'The fax-confirmation file. It must located in catalog-dir');


$aLang['module_payment_banktransfer_text_title'] = 'Automatische incasso';
$aLang['module_payment_banktransfer_text_description'] = 'Automatische incasso';
$aLang['module_payment_banktransfer_text_bank'] = 'Incasso';
$aLang['module_payment_banktransfer_text_email_footer'] = 'Attentie: U kan ons faxformulier via ' . OOS_HTTP_SERVER . OOS_SHOP . MODULE_PAYMENT_BANKTRANSFER_URL_NOTE . ' downloaden en het ingevuld aan ons terugsturen.';
$aLang['module_payment_banktransfer_text_bank_info'] = 'Let er a.u.b. op dat een automatische incasso <b>alleen</b> vanaf een nederlande bankrekening mogelijk is';
$aLang['module_payment_banktransfer_text_bank_owner'] = 'Rekeninghouder:';
$aLang['module_payment_banktransfer_text_bank_number'] = 'Rekeningnummer:';
$aLang['module_payment_banktransfer_text_bank_blz'] = 'IBAN nr:';
$aLang['module_payment_banktransfer_text_bank_name'] = 'Bank:';
$aLang['module_payment_banktransfer_text_bank_fax'] = 'Incassomachtiging wordt per fax bevestigd';

$aLang['module_payment_banktransfer_text_bank_error'] = '<font color="#FF0000">FOUT: </font>';
$aLang['module_payment_banktransfer_text_bank_error_1'] = 'Rekeningnummer und IBAN nummer komen niet met elkaar overeen!<br />Controleer a.u.b. de gegevens nog een keer.';
$aLang['module_payment_banktransfer_text_bank_error_2'] = 'Voor dit rekeningnummmer is geen cijfercontrole gedefinieerd!';
$aLang['module_payment_banktransfer_text_bank_error_3'] = 'Rekeningnummer niet te controleren!';
$aLang['module_payment_banktransfer_text_bank_error_4'] = 'Rekeningnummer niiet te controleren!<br />Controleer a.u.b. de gegevens nog een keer.';
$aLang['module_payment_banktransfer_text_bank_error_5'] = 'IBAN nummer!<br />Controleer a.u.b. de gegevens nog een keer.';
$aLang['module_payment_banktransfer_text_bank_error_8'] = 'Fout in het IBAN nummer of geen IBAN nummer ingevuld!';
$aLang['module_payment_banktransfer_text_bank_error_9'] = 'Geen rekeningnummer ingevuld!';

$aLang['module_payment_banktransfer_text_note'] = 'Attentie:';
$aLang['module_payment_banktransfer_text_note2'] = 'Als u uit veiligheidsoverweging geen bankgegevens via internet<br />wilt doorgeven, kan u ons ';
$aLang['module_payment_banktransfer_text_note3'] = 'Faxformulier';
$aLang['module_payment_banktransfer_text_note4'] = ' downloaden en aan ons ingevuld terugsturen.';

$aLang['js_bank_blz'] = 'Vul a.u.b. het IBAN nummr van uw bank in!\n';
$aLang['js_bank_name'] = 'Vul a.u.b. de naam van uw bank in!\n';
$aLang['js_bank_number'] = 'Vul a.u.b. uw rekeningnummer in!\n';
$aLang['js_bank_owner'] = 'Vul a.u.b. de naam van de rekeninghouder in!\n';

?>
