<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_edit.php,v 1.62 2003/02/13 01:58:23 hpdl 
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

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
// require  validation functions (right now only email address)
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/account_edit.php';


if ( isset($_POST['action']) && ($_POST['action'] == 'process') && 
	( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ){


    if (ACCOUNT_GENDER == 'true') {
		if (isset($_POST['gender'])) {
			$gender = oos_db_prepare_input($_POST['gender']);
		} else {
			$gender = FALSE;
		}
    }
    $firstname = oos_db_prepare_input($_POST['firstname']);
    $lastname = oos_db_prepare_input($_POST['lastname']);
    if (ACCOUNT_DOB == 'true') $dob = oos_db_prepare_input($_POST['dob']);
    $email_address = oos_db_prepare_input($_POST['email_address']);
    $telephone = oos_db_prepare_input($_POST['telephone']);
    $password = oos_db_prepare_input($_POST['password']);
    $confirmation = oos_db_prepare_input($_POST['confirmation']);
    if (isset($_POST['newsletter'])) {
		$newsletter = oos_db_prepare_input($_POST['newsletter']);
    } 
	
	$bError = FALSE; // reset error flag

    if (ACCOUNT_GENDER == 'true') {
		if ( ($gender != 'm') && ($gender != 'f') ) {
			$bError = TRUE;
			$oMessage->add('account_edit', $aLang['entry_gender_error']);
		}
    }

    if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_first_name_error'] );
    }	

	if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_last_name_error'] );
    }

	if (ACCOUNT_DOB == 'true') {
		if ((strlen($dob) < ENTRY_DOB_MIN_LENGTH) || (!empty($dob) && 
			(!is_numeric(oos_date_raw($dob)) ||
			!checkdate(substr(oos_date_raw($dob), 4, 2), substr(oos_date_raw($dob), 6, 2), substr(oos_date_raw($dob), 0, 4))))) {		
	
			$bError = TRUE;
			$oMessage->add('account_edit', $aLang['entry_date_of_birth_error'] );
		}
	}

    if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_email_address_error']);
    } elseif (oos_validate_is_email($email_address) == FALSE) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_email_address_check_error']);
    } else {
		$customerstable = $oostable['customers'];
		$check_email_sql = "SELECT customers_email_address
                      FROM $customerstable
                      WHERE customers_email_address = '" . oos_db_input($email_address) . "'
					  AND customers_id != '" . intval($_SESSION['customer_id']) . "'";
		$check_email = $dbconn->Execute($check_email_sql);
		if ($check_email->RecordCount()) {		
			$bError = TRUE;
			$oMessage->add('account_edit', $aLang['entry_email_address_error_exists']);
		}
    }

	if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_password_error']);
	} elseif ($password != $confirmation) {
		$bError = TRUE;
		$oMessage->add('account_edit', $aLang['entry_password_error_not_matching']);
	}


	if ($bError == FALSE) {
	
		$new_encrypted_password = oos_encrypt_password($password);
		$sql_data_array = array('customers_firstname' => $firstname,
                            'customers_lastname' => $lastname,
                            'customers_email_address' => $email_address,
                            'customers_telephone' => $telephone,
                            'customers_password' => $new_encrypted_password);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
		if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = oos_date_raw($dob);

		oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "'");

		$sql_data_array = array('entry_firstname' => $firstname,
								'entry_lastname' => $lastname);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;

		oos_db_perform($oostable['address_book'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "' AND address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'");

		$update_info_sql = "UPDATE " . $oostable['customers_info'] . " 
							SET customers_info_date_account_last_modified = now() 
							WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
		$dbconn->Execute($update_info_sql);


		if (SEND_CUSTOMER_EDIT_EMAILS == 'true') {
			$email_owner = $aLang['owner_email_subject'] . "\n" . 
			$aLang['email_separator'] . "\n" . 
			$aLang['owner_email_date'] . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n" .
			$aLang['email_separator'] . "\n";
			
			if (ACCOUNT_COMPANY == 'true') {
				$email_owner .= $aLang['owner_email_company_info'] . "\n" . 
				$aLang['owner_email_company'] . ' ' . $company . "\n";
				if (ACCOUNT_OWNER == 'true') {
					$email_owner .= $aLang['owner_email_owner'] . ' ' . $owner . "\n";
				}
				if (ACCOUNT_VAT_ID == 'true') {
					$email_owner .= $aLang['entry_vat_id'] . ' ' . $vat_id . "\n";
				}
			}
			if (ACCOUNT_GENDER == 'true') {
				if ($gender == 'm') {
					$email_owner .= $aLang['entry_gender'] . ' ' . $aLang['male'] . "\n";
				} else {
					$email_owner .= $aLang['entry_gender'] . ' ' . $aLang['female'] . "\n";
				}
			}

			$email_owner .= $aLang['owner_email_first_name'] . ' ' . $firstname . "\n" .
                      $aLang['owner_email_last_name'] . ' ' . $lastname . "\n\n" .
                      $aLang['owner_email_street'] . ' ' . $street_address . "\n" .
                      $aLang['owner_email_post_code'] . ' ' . $postcode . "\n" .
                      $aLang['owner_email_city'] . ' ' . $city . "\n" .
                      $aLang['email_separator'] . "\n\n" .
                      $aLang['owner_email_contact'] . "\n" .
                      $aLang['owner_email_telephone_number'] . ' ' . $telephone . "\n" .
                      $aLang['owner_email_address'] . ' ' . $email_address . "\n" .
                      $aLang['email_separator'] . "\n\n" .
                      $aLang['owner_email_options'] . "\n";
					  
			if ($newsletter == '1') {
				$email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_yes'] . "\n";
			} else {
				$email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_no'] . "\n";
			}
			oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['owner_email_subject'], nl2br($email_owner), $name, $email_address);  
		}

		if (NEWSLETTER == 'true') {
			if ( isset($newsletter) && ($newsletter == 'yes') ) {
				oos_newsletter_subscribe_mail($email_address);
			}
		}
		
		oos_redirect(oos_href_link($aContents['account'], '', 'SSL'));
	}
	
}

$customerstable = $oostable['customers'];
$sql = "SELECT customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address,
			customers_vat_id, customers_vat_id_status, customers_telephone
          FROM $customerstable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
$account = $dbconn->GetRow($sql);

if (ACCOUNT_GENDER == 'true') {
	if (isset($gender)) {
		$male = ($gender == 'm') ? TRUE : FALSE;
	} else {
		$male = ($account['customers_gender'] == 'm') ? TRUE : FALSE;
	}
	$female = !$male;
}

$bNewsletter = FALSE;
if (NEWSLETTER == 'true') {
	if (!isset($email_address)) {
		$email_address = $account['customers_email_address'];
	}		
	
	$newsletter_recipients = $oostable['newsletter_recipients'];
	$sql = "SELECT recipients_id
              FROM $newsletter_recipients
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'
			  AND status = '1'";
	$check_recipients_result = $dbconn->Execute($sql);

	if (!$check_recipients_result->RecordCount()) {	
		$bNewsletter = TRUE;
	}
}	


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
$oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_edit'], '', 'SSL'));
  
$aTemplate['page'] = $sTheme . '/page/account_edit.html';
$aTemplate['javascript'] = $sTheme . '/js/account_edit.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

if ($oMessage->size('account_edit') > 0) {
	$aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('account_edit') );
}
require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,nofollow,noodp,noydir',

		'account_active'	=> 1,
		'account'           => $account,
		'female'			=> $female,
     	'male' 				=> $male,
		'bNewsletter'		=> $bNewsletter
	)
);

$smarty->assign('javascript', $smarty->fetch($aTemplate['javascript']));

// display the template
$smarty->display($aTemplate['page']);
