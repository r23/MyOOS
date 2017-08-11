<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: address_book_process.php,v 1.73 2003/02/13 01:58:23 hpdl 
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_address_book_process.php';

if (isset($_GET['action']) && ($_GET['action'] == 'delete') && isset($_GET['entry_id']) && is_numeric($_GET['entry_id']) ) {
	  
    $entry_id = oos_db_prepare_input($_GET['entry_id']);

    if ($entry_id == $_SESSION['customer_default_address_id']) {
		$oMessage->add_session('addressbook', $aLang['warning_primary_address_deletion'], 'warning');
    } else {	
		$address_booktable = $oostable['address_book'];
		$query = "DELETE FROM $address_booktable
					WHERE address_book_id = '" . intval($entry_id) . "' 
					AND	customers_id = '" . intval($_SESSION['customer_id']) . "'";
		$dbconn->Execute($query);

		$oMessage->add_session('addressbook', $aLang['success_address_book_entry_deleted'], 'success');
	}

	oos_redirect(oos_href_link($aContents['account_address_book'], '', 'SSL'));
}

// Post-entry error checking when updating or adding an entry
$bProcess = FALSE;
if ( isset($_POST['action']) && ($_POST['action'] == 'process') || ($_POST['action'] == 'update') && 
	( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ){	  
	  
    $bProcess = TRUE;

    if (ACCOUNT_GENDER == 'true') {
		if (isset($_POST['gender'])) {
			$gender = oos_db_prepare_input($_POST['gender']);
		} else {
			$gender = FALSE;
		}
    }
    $firstname = oos_db_prepare_input($_POST['firstname']);
    $lastname = oos_db_prepare_input($_POST['lastname']);	
    if (ACCOUNT_COMPANY == 'true') $company = oos_db_prepare_input($_POST['company']);
    if (ACCOUNT_OWNER == 'true') $owner = oos_db_prepare_input($_POST['owner']);
    if (ACCOUNT_VAT_ID == 'true') $vat_id = oos_db_prepare_input($_POST['vat_id']);
    $street_address = oos_db_prepare_input($_POST['street_address']);
    $postcode = oos_db_prepare_input($_POST['postcode']);
    $city = oos_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
		$state = oos_db_prepare_input($_POST['state']);
		if (isset($_POST['zone_id'])) {
			$zone_id = oos_db_prepare_input($_POST['zone_id']);
		} else {
			$zone_id = FALSE;
		}
    }
    $country = oos_db_prepare_input($_POST['country']);

	$bError = FALSE; // reset error flag
    if (ACCOUNT_GENDER == 'true') {
		if ( ($gender != 'm') && ($gender != 'f') ) {
			$bError = TRUE;
			$oMessage->add('addressbook', $aLang['entry_gender_error']);
		}
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_first_name_error'] );
    }	

	if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_last_name_error'] );
    }


	if (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true'){
		if (!empty($vat_id) && (!oos_validate_is_vatid($vat_id))) {
			$bError = TRUE;
			$oMessage->add('addressbook', $aLang['entry_vat_id_error']);
		} else {
			$vatid_check_error = FALSE;
		}
	}

	if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_street_address_error']);
	}	

	if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_post_code_error']);
	}
 
	if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_city_error']);
	}

	if (is_numeric($country) == FALSE) {
		$bError = TRUE;
		$oMessage->add('addressbook', $aLang['entry_country_error']);
    }
	
	if (ACCOUNT_STATE == 'true') {
		$zone_id = 0;
		$zonestable = $oostable['zones'];
		$country_check_sql = "SELECT COUNT(*) AS total
								FROM $zonestable
								WHERE zone_country_id = '" . intval($country) . "'";
		$country_check = $dbconn->Execute($country_check_sql);
		$entry_state_has_zones = ($country_check->fields['total'] > 0);
		if ($entry_state_has_zones == TRUE) {
			$zonestable = $oostable['zones'];
			$zone_query = "SELECT DISTINCT zone_id
                           FROM $zonestable
                           WHERE zone_country_id = '" . intval($country) . "'
                             AND (zone_name = '" . oos_db_input($state) . "'
							OR zone_code = '" . oos_db_input($state) . "')";							
			$zone_result = $dbconn->Execute($zone_query);
			if ($zone_result->RecordCount() == 1) {
				$zone = $zone_result->fields;
				$zone_id = $zone['zone_id'];
			} else {
				$bError = TRUE;
				$oMessage->add('addressbook', $aLang['entry_state_error_select']);
			}
		} else {
			if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
				$bError = TRUE;
				$oMessage->add('addressbook', $aLang['entry_state_error']);
			}
		}
	}	
	

    if ($bError == FALSE) {
		$sql_data_array = array('entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
		if (ACCOUNT_OWNER == 'true') $sql_data_array['entry_owner'] = $owner;	  
	  
	  
      if (ACCOUNT_STATE == 'true') {
        if ($zone_id > 0) {
          $sql_data_array['entry_zone_id'] = $zone_id;
          $sql_data_array['entry_state'] = '';
        } else {
          $sql_data_array['entry_zone_id'] = '0';
          $sql_data_array['entry_state'] = $state;
        }
      }

      if ($_POST['action'] == 'update') {
        oos_db_perform($oostable['address_book'], $sql_data_array, 'UPDATE', "address_book_id = '" . oos_db_input($entry_id) . "' AND customers_id ='" . intval($_SESSION['customer_id']) . "'");
      } else {
        $sql_data_array['customers_id'] = $_SESSION['customer_id'];
        $sql_data_array['address_book_id'] = $entry_id;
        oos_db_perform($oostable['address_book'], $sql_data_array);

      }

      oos_redirect(oos_href_link($aContents['account_address_book'], '', 'SSL'));
    }
  }

  if (isset($_GET['action']) && ($_GET['action'] == 'modify') && isset($_GET['entry_id']) && is_numeric($_GET['entry_id'])) {
    $address_booktable = $oostable['address_book'];
    $sql = "SELECT entry_gender, entry_company, entry_firstname, entry_lastname,
                   entry_street_address, entry_postcode, entry_city,
                   entry_state, entry_zone_id, entry_country_id
            FROM $address_booktable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'
              AND address_book_id = '" . intval($_GET['entry_id']) . "'";
    $entry = $dbconn->GetRow($sql);
  } else {
    $entry = array('entry_country_id' => STORE_COUNTRY);
  }
  if (!isset($bProcess)) {
    $bProcess = FALSE;
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_address_book'], '', 'SSL'));


  if ( isset($_GET['action']) && ($_GET['action'] == 'modify') && isset($_GET['entry_id']) && is_numeric($_GET['entry_id']) || isset($_POST['action']) && ($_POST['action'] == 'update') ) {
		   
    $oBreadcrumb->add($aLang['navbar_title_modify_entry'], oos_href_link($aContents['account_address_book_process'], 'action=modify&amp;entry_id=' . ((isset($_GET['entry_id'])) ? $_GET['entry_id'] : $_POST['entry_id']), 'SSL'));
  } else {
    $oBreadcrumb->add($aLang['navbar_title_add_entry'], oos_href_link($aContents['account_address_book_process'], '', 'SSL'));
  }

   $back_link = oos_href_link($aContents['account_address_book'], '', 'SSL');
  if (isset($_GET['entry_id'])) {
    $entry_id = oos_var_prep_for_os($_GET['entry_id']);
  }

$aTemplate['page'] = $sTheme . '/page/address_book_process.html';

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
		'breadcrumb' => $oBreadcrumb->trail(),

			
		'back_link'      => $back_link,
		'entry_id'       => $entry_id,
		'process'        => $process

	)
);

if (isset($_GET['action']) && $_GET['action'] == 'modify') {
	$smarty->assign(
		array(
            'heading_title' => $aLang['heading_title_modify_entry']
        )
    );
} else {
	$smarty->assign(
		array(
			'heading_title' => $aLang['heading_title_add_entry']
		)
	);
}

$smarty->assign(
	array(
		'robots'			=> 'noindex,nofollow,noodp,noydir',
		'account_active'	=> 1,
		
          'gender'         => $gender,
          'firstname'      => $firstname,
          'lastname'       => $lastname,
          'company'        => $company,
          'street_address' => $street_address,
          'suburb'         => $suburb,
          'postcode'       => $postcode,
          'city'           => $city,
          'country'        => $country
      )
  );

  $smarty->assign(
      array(
          'error'                => $error,
          'gender_error'         => $gender_error,
          'firstname_error'      => $firstname_error,
          'lastname_error'       => $lastname_error,
          'street_address_error' => $street_address_error,
          'post_code_error'      => $post_code_error,
          'city_error'           => $city_error,
          'country_error'        => $country_error,
          'state_error'          => $state_error,
          'state_has_zones'      => $state_has_zones
      )
  );

  if ($state_has_zones == 'true') {
     $aZonesNames = array();
     $aZonesValues = array();
     $zonestable = $oostable['zones'];
     $zones_query = "SELECT zone_name FROM $zonestable
                     WHERE zone_country_id = '" . oos_db_input($country) . "'
                     ORDER BY zone_name";
     $zones_result = $dbconn->Execute($zones_query);
     while ($zones = $zones_result->fields) {
       $aZonesNames[] =  $zones['zone_name'];
       $aZonesValues[] = $zones['zone_name'];
       $zones_result->MoveNext();
     }
     $smarty->assign('zones_names', $aZonesNames);
     $smarty->assign('zones_values', $aZonesValues);
   } else {
     $state = oos_get_zone_name($country, $zone_id, $state);
     $smarty->assign('state', $state);
     $smarty->assign('zone_id', $zone_id);
   }
   $country_name = oos_get_country_name($country);
   $smarty->assign('country_name', $country_name);

   $smarty->assign('entry', $entry);



// display the template
$smarty->display($aTemplate['page']);
