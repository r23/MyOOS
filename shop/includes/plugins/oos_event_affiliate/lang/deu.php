<?php
/* ----------------------------------------------------------------------
   $Id: deu.php,v 1.1 2007/06/07 17:29:24 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('PLUGIN_EVENT_AFFILIATE_NAME', 'Partnerprogramm');
define('PLUGIN_EVENT_AFFILIATE_DESC', 'Partnerprogramm');


define('AFFILIATE_OWNER_TITLE', 'Eigent&uuml;mer des Partnerprogramms');
define('AFFILIATE_OWNER_DESC', 'Der Administrator des Partnerprogramms dieses Shop.');

define('AFFILIATE_EMAIL_ADDRESS_TITLE', 'E-Mail Adresse');
define('AFFILIATE_EMAIL_ADDRESS_DESC', 'Die E-Mail Adresse f&uuml;r das Partnerprogramm');

define('AFFILIATE_PERCENT_TITLE', 'prozentuale Provision pro verkauftem Artikel');
define('AFFILIATE_PERCENT_DESC', 'Prozentuale Provision f&uuml;r Artikel, die &uuml;ber das Partnerprogramm verkauft werden.');

define('AFFILIATE_THRESHOLD_TITLE', 'Auszahlungsgrenze');
define('AFFILIATE_THRESHOLD_DESC', 'Partner werden erst ausgezahlt, wenn die Provision diesen Wert &uuml;bersteigt.');

define('AFFILIATE_COOKIE_LIFETIME_TITLE', 'Lebensdauer des Cookies');
define('AFFILIATE_COOKIE_LIFETIME_DESC', 'Wenn ein Kunde erneut die Seite besucht z&auml;hlt der Klick erst nach Ablauf dieser Zeit (in Sekunden) als neuer Besucher.');

define('AFFILIATE_BILLING_TIME_TITLE', 'Auszahlungszeit');
define('AFFILIATE_BILLING_TIME_DESC', 'Provisionsauszahlungen finden fr&uuml;hestens \"30\" Tage nach Rechnungsstellung statt.<br>Dies ist notwendig, falss eine Bestellung zur&uuml;ckgeschickt wird.');

define('AFFILIATE_PAYMENT_ORDER_MIN_STATUS_TITLE', 'Minimaler Bestellstatus');
define('AFFILIATE_PAYMENT_ORDER_MIN_STATUS_DESC', 'Der Status, den eine Bestellung wenigstens haben mu&szlig;, um als best&auml;tigt zu gelten.');

define('AFFILIATE_USE_CHECK_TITLE', 'Bezahle Partner per Scheck');
define('AFFILIATE_USE_CHECK_DESC', 'Die Provision wird per Scheck an die Partner ausgezahlt.');

define('AFFILIATE_USE_PAYPAL_TITLE', 'Bezahle Partner &uuml;ber PayPal');
define('AFFILIATE_USE_PAYPAL_DESC', 'Die Provision wird &uuml;ber PayPal an die Partner ausgezahlt.');

define('AFFILIATE_USE_BANK_TITLE', 'Bezahle Partner per Bank&uuml;berweisung');
define('AFFILIATE_USE_BANK_DESC', 'Die Provision wird &Uuml;berweisung an die Partner ausgezahlt.');

define('AFFILATE_INDIVIDUAL_PERCENTAGE_TITLE', 'Individueller Prozentsatz');
define('AFFILATE_INDIVIDUAL_PERCENTAGE_DESC', 'Erlaube individuelle Provisionen f&uuml;r die Partner.');

?>
