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


use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

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

$recipients = [];

$days = 2;
$sd = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));

$customers_baskettable = $oostable['customers_basket'];
$basket_result = $dbconn->Execute("SELECT customers_basket_id, customers_id, customers_basket_date_added  FROM $customers_baskettable WHERE customers_basket_date_added <= '" . oos_db_input(date("Ymd", $sd)) . "'");
 
if ($basket_result->RecordCount() > 0) {
	while ($basket = $basket_result->fields) {
		$customers_basket_id = $basket['customers_basket_id'];
		$customer_id = $basket['customers_id'];	
				
		if (!check_letter_sent($customer_id, $customers_basket_id )) {

			$customerstable = $oostable['customers'];
			$address_booktable = $oostable['address_book'];
			$customers_result = $dbconn->Execute("SELECT c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, 
																c.customers_email_address, c.customers_wishlist_link_id, c.customers_language,
																a.entry_company, a.entry_owner, a.entry_vat_id, a.entry_vat_id_status, 
																a.entry_street_address, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id,
																a.entry_country_id, c.customers_telephone,
																c.customers_default_address_id, c.customers_status, c.customers_max_order
														FROM  $customerstable c LEFT JOIN
																$address_booktable a
																ON c.customers_default_address_id = a.address_book_id
														WHERE a.customers_id = c.customers_id AND
															  a.entry_country_id = 81 AND
																c.customers_id = '" .  intval($customer_id) . "'");
			$customers = $customers_result->fields;

			$sName = $customers['customers_firstname'] . ' ' . $customers['customers_lastname']
			$schema .= $customers['entry_street_address'] . $indent . $customers['entry_postcode'] . $indent . $customers['entry_city'] . $indent;
			$recipients = [
						['name' => $sName, 'address' => 'Musterstraße 1'],
				];


			

		}				

		// Move that ADOdb pointer!
		$basket_result->MoveNext();
	}
}



echo $schema;
exit;







// Create a new instance of PhpWord
$phpWord = new PhpWord();

// Add new section
$section = $phpWord->addSection();

// Define placeholders for form letters
$section->addText('Date: ${date}');
$section->addText('Company: ${company}');
$section->addText('Name: ${name}');

$section->addText('Adresse: ${address}');

// Data for the form letters
$recipients = [
    ['name' => 'Max Mustermann', 'address' => 'Musterstraße 1'],
    ['name' => 'Erika Mustermann', 'address' => 'Musterweg 2'],
];

// Replace placeholders with actual data
foreach ($recipients as $recipient) {
	// Instantiate TemplateProcessor
	$docx_template = MYOOS_INCLUDE_PATH . '/includes/languages/deu/cart_cancelling.docx';
	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($docx_template);

    $templateProcessor->setValue('name', $recipient['name']);
    $templateProcessor->setValue('address', $recipient['address']);

    // Save document
    $fileName = 'Serienbrief_' . $recipient['name'] . date('YmdHis') . '.docx';
    $templateProcessor->saveAs(OOS_EXPORT_PATH. $fileName);
	
	unset($templateProcessor);
}

echo 'Serial letters were created';



require_once MYOOS_INCLUDE_PATH . '/includes/nice_exit.php';
