<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book.php,v 1.55 2003/02/13 01:58:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// start the session
if ( $session->hasStarted() === FALSE ) $session->start();  
  
if (!isset($_SESSION['customer_id'])) {
  	// navigation history
	if (!isset($_SESSION['navigation'])) {
		$_SESSION['navigation'] = new oosNavigationHistory();
	} 
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_address_book.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_address.php';

 /**
  * Returns Adress
  *
  * @param $customers_id
  * @param $address_id
  * @return string
  */
  function oos_address_summary($nCustomersId, $nAddressId) {

    $nCustomersId = intval($nCustomersId);
    $nAddressId = intval($nAddressId);

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_booktable = $oostable['address_book'];
    $countriestable = $oostable['countries'];
    $sql = "SELECT ab.entry_street_address, ab.entry_postcode, ab.entry_city,
                   ab.entry_state, ab.entry_country_id, ab.entry_zone_id, c.countries_name, c.address_format_id
            FROM $address_booktable ab,
                 $countriestable c
            WHERE ab.address_book_id = '" . intval($nAddressId) . "'
              AND ab.customers_id = '" . intval($nCustomersId) . "'
              AND ab.entry_country_id = c.countries_id";
    $address = $dbconn->GetRow($sql);

    $street_address = $address['entry_street_address'];
    $postcode = $address['entry_postcode'];
    $city = $address['entry_city'];
    $state = oos_get_zone_code($address['entry_country_id'], $address['entry_zone_id'], $address['entry_state']);
    $country = $address['countries_name'];

    $address_formattable = $oostable['address_format'];
    $address_format_query = "SELECT address_summary
                             FROM $address_formattable 
                             WHERE address_format_id = '" . intval($address['address_format_id']) . "'";
    $address_format = $dbconn->GetRow($address_format_query);

    $address_summary = $address_format['address_summary'];
    eval("\$address = \"$address_summary\";");

    return $address;
  }
  

  $address_booktable = $oostable['address_book'];
  $sql = "SELECT address_book_id, entry_company, entry_firstname, entry_lastname,
				entry_street_address, entry_postcode, entry_city, entry_state,
				entry_country_id, entry_zone_id
          FROM $address_booktable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
          ORDER BY entry_firstname, entry_lastname";
  $address_book_result = $dbconn->Execute($sql);

  $aAddressBook = array();
  while ($address_book = $address_book_result->fields) {
	$state = $address_book['entry_state'];
    $country_id = $address_book['entry_country_id'];
    $zone_id = $address_book['entry_zone_id'];
    $country = oos_get_country_name($country_id);

	if (ACCOUNT_STATE == 'true') {
		$state = oos_get_zone_code($country_id, $zone_id, $state);		  
	} 
	  
    $aAddressBook[] = array('address_book_id' => $address_book['address_book_id'],
                            'entry_firstname' => $address_book['entry_firstname'],
                            'entry_lastname' => $address_book['entry_lastname']);
    $address_book_result->MoveNext();
  }

  // Close result set
  $address_book_result->Close();

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_address_book'], '', 'SSL'));
 
  $aTemplate['page'] = $sTheme . '/page/address_book.html';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;
  $sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

  require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
  }

// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'		=> $oBreadcrumb->trail(),
		'heading_title'		=> $aLang['heading_title'],
		'robots'			=> 'noindex,nofollow,noodp,noydir',
		'account_active'	=> 1,

		'oos_address_book_array' => $aAddressBook
	)
);


  // display the template
$smarty->display($aTemplate['page']);
