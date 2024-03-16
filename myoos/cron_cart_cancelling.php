<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Google XML Sitemap Feed Cron Script

   Bobby Easland
   Copyright 2005, Bobby Easland
   http://www.oscommerce-freelancers.com/
   ----------------------------------------------------------------------
   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


/**
 * Set the error reporting level. Unless you have a special need, E_ALL is a
 * good level for error reporting.
 */
error_reporting(E_ALL);
// error_reporting(E_ALL & ~E_STRICT);

//setting basic configuration parameters
if (function_exists('ini_set')) {
    ini_set('session.use_trans_sid', 0);
    ini_set('url_rewriter.tags', '');
    ini_set('xdebug.show_exception_trace', 0);
    ini_set('magic_quotes_runtime', 0);
    // ini_set('display_errors', false);
}


use Symfony\Component\HttpFoundation\Request;

$autoloader = include_once __DIR__ . '/vendor/autoload.php';
$request = Request::createFromGlobals();

define('MYOOS_INCLUDE_PATH', __DIR__ == '/' ? '' : __DIR__);

define('OOS_VALID_MOD', true);

require_once MYOOS_INCLUDE_PATH . '/includes/main.php';


//prevent script from running more than once a day
$configurationtable = $oostable['configuration'];
$sql = "SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'LASTBASKET_MAIL'";
$prevent_result = $dbconn->Execute($sql);

if ($prevent_result->RecordCount() > 0) {
	$prevent = $prevent_result->fields;
	if ($prevent['configuration_value'] == date("Ymd")) {
		die('Halt! Already executed - should not execute more than once a day.');
	}
}

if ($prevent_result->RecordCount() > 0) {
	$configurationtable = $oostable['configuration'];
	$dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . date("Ymd") . "' WHERE configuration_key = 'LASTBASKET_MAIL'");
} else {
	$configurationtable = $oostable['configuration'];
	# $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id) VALUES ('LASTBASKET_MAIL', '" . date("Ymd") . "', '6')");
}


$mail_file = 'basket_mail-' . date('YmdHis') . '.docx';



// Erstellen Sie ein neues PHPWord-Objekt
$phpWord = new \PhpOffice\PhpWord\PhpWord();

// Laden Sie die Vorlage mit den Platzhaltern
$template = $phpWord->loadTemplate('template.docx');

// Verbinden Sie sich mit der Datenbank oder laden Sie die Daten aus einer anderen Quelle
// Hier verwenden wir ein Array als Beispiel
$data = array(
    array('name' => 'Hans Müller', 'address' => 'Musterstraße 1, 12345 Musterstadt'),
    array('name' => 'Anna Schmidt', 'address' => 'Beispielweg 2, 67890 Beispielort'),
    array('name' => 'Peter Meier', 'address' => 'Testallee 3, 13579 Teststadt')
);

// Ersetzen Sie die Platzhalter mit den Daten
foreach ($data as $row) {
    // Klone die Vorlage für jeden Datensatz
    $template->cloneRow('name', 1);

    // Ersetzen Sie die Platzhalter mit den Werten aus dem Array
    $template->setValue('name#1', $row['name']);
    $template->setValue('address#1', $row['address']);
}

// Speichern Sie das Ergebnis als eine neue Datei
$template->save(OOS_EXPORT_PATH.$mail_file);




require_once MYOOS_INCLUDE_PATH . '/includes/nice_exit.php';
