<?php
/* ----------------------------------------------------------------------
   $Id: create_account_process.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account_process.php,v 1.1.2.4 2003/05/02 22:23:01 wilt
   orig: create_account_process.php,v 1.85 2003/02/13 04:23:23 hpdl
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
if ( is_session_started() === FALSE ) oos_session_start();

// if the customer is logged on, redirect them to the account page
if (isset($_SESSION['customer_id'])) {
   oos_redirect(oos_href_link($aContents['account'], '', 'SSL'));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account_process.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';


$snapshot = count($_SESSION['navigation']->snapshot);
if (isset($_GET['email_address'])) {
	$email_address = oos_db_prepare_input($_GET['email_address']);
}

$country = isset($_POST['country']) ? (int)$_POST['country'] : STORE_COUNTRY;

$bProcess = FALSE;
if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])){
	$bProcess = TRUE;

	if (ACCOUNT_GENDER == 'true') {
		$gender = isset($_POST['gender']) ? oos_db_prepare_input($_POST['gender']) : '';
	}
	$firstname = oos_db_prepare_input($_POST['firstname']);
	$lastname = oos_db_prepare_input($_POST['lastname']);	
	if (ACCOUNT_DOB == 'true') {
		$dob = oos_db_prepare_input($_POST['dob']);
	}
	$email_address = oos_db_prepare_input($_POST['email_address']);
    if (ACCOUNT_NUMBER == 'true') {
		$number = oos_db_prepare_input($_POST['number']);
	}
	if (ACCOUNT_COMPANY == 'true') {
		$company = oos_db_prepare_input($_POST['company']);
	}
	if (ACCOUNT_VAT_ID == 'true')	{ 
		$vat_id = oos_db_prepare_input($_POST['vat_id']);
	}
	$street_address = oos_db_prepare_input($_POST['street_address']);
	$postcode = oos_db_prepare_input($_POST['postcode']);
	$city = oos_db_prepare_input($_POST['city']);
	$zone_id = isset($_POST['zone_id']) ? oos_db_prepare_input($_POST['zone_id']) : 0;
	$telephone = oos_db_prepare_input($_POST['telephone']);
	$fax = oos_db_prepare_input($_POST['fax']);
	$newsletter = isset($_POST['newsletter']) ? (int)$_POST['newsletter'] : '';
	$password = oos_db_prepare_input($_POST['password']);
	$confirmation = oos_db_prepare_input($_POST['confirmation']);	
	
	$bError = FALSE; // reset error flag

	if (ACCOUNT_GENDER == 'true' && $gender != 'm' && $gender != 'f') {
		$bError = TRUE;
		$gender_error = 'true';
        }
	
	if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$firstname_error = 'true';
	}

	if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
		$bError = TRUE;
		$lastname_error = 'true';
	}
	
	if (ACCOUNT_DOB == 'true' && ( is_numeric(oos_date_raw($dob)) == false ||
		(@checkdate(substr(oos_date_raw($dob), 4, 2), substr(oos_date_raw($dob), 6, 2), substr(oos_date_raw($dob), 0, 4)) == false))) {
			$bError = TRUE;
			$date_of_birth_error = 'true';
	}


	if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
		$bError = TRUE;
		$email_address_error = 'true';
	}

	if (!oos_validate_is_email($email_address)) {
		$bError = TRUE;
		$email_address_check_error = 'true';
	} 

	if ((ACCOUNT_VAT_ID == 'true') && (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && oos_is_not_null($vat_id)) {
		if (!oos_validate_is_vatid($vat_id)) {
			$bError = TRUE;
			$vatid_check_error = 'true';
		}
	}

	if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
		$bError = TRUE;
		$street_address_error = 'true';
	}

	if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
		$bError = TRUE;
		$post_code_error = 'true';
	}

	if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
		$bError = TRUE;
		$city_error = 'true';
	}

	if (isset($_POST['country']) && is_numeric($_POST['country']) && ($_POST['country'] >= 1)) {
		$country_error = FALSE;
	} else {
		$country = 0;
		$bError = TRUE;
		$country_error = 'true';
	}


	if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
		$bError = TRUE;
		$telephone_error = 'true';
	}

	if (CUSTOMER_NOT_LOGIN == 'false') {
		$passlen = strlen($password);
		if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
			$bError = TRUE;
			$password_error = 'true';
		} 
		if ($password != $confirmation) {
			$bError = TRUE;
			$password_error = 'true';
		}
	}

    if ( empty( $email_address ) || !is_string( $email_address ) ) {
        oos_redirect(oos_href_link($aContents['forbiden']));
    }
	
	$customerstable = $oostable['customers'];
	$check_email_sql = "SELECT customers_email_address
                      FROM $customerstable
                      WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
	$check_email = $dbconn->Execute($check_email_sql);
	if ($check_email->RecordCount()) {
		$bError = TRUE;
		$email_address_exists = 'true';
	} 

	if ($bError == FALSE) {
		$customer_max_order = DEFAULT_MAX_ORDER;
		$customers_status = DEFAULT_CUSTOMERS_STATUS_ID;

		if (CUSTOMER_NOT_LOGIN == 'true') {
			$customers_login = '0';
		} else {
			$customers_login = '1';
		}

		$time = mktime();
		$wishlist_link_id = '';
		for ($x=3;$x<10;$x++) {
			$wishlist_link_id .= substr($time,$x,1) . oos_create_random_value(1, $type = 'chars');
		}
		$sql_data_array = array('customers_firstname' => $firstname,
								'customers_lastname' => $lastname,
								'customers_email_address' => $email_address,
								'customers_telephone' => $telephone,
								'customers_fax' => $fax,
								'customers_newsletter' => $newsletter,
								'customers_status' => $customers_status,
								'customers_login' => $customers_login,
								'customers_language' => $sLanguage,
								'customers_max_order' => $customer_max_order,
								'customers_password' => oos_encrypt_password($password),
								'customers_wishlist_link_id' => $wishlist_link_id,
								'customers_default_address_id' => 1);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
		if (ACCOUNT_NUMBER == 'true') $sql_data_array['customers_number'] = $number;
		if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = oos_date_raw($dob);
		if (ACCOUNT_VAT_ID == 'true') {
			$sql_data_array['customers_vat_id'] = $vat_id;
			if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === FALSE) && ($country != STORE_COUNTRY)) {
				$sql_data_array['customers_vat_id_status'] = 1;
			} else {
				$sql_data_array['customers_vat_id_status'] = 0;
			}
		}
		oos_db_perform($oostable['customers'], $sql_data_array);

		$customer_id = $dbconn->Insert_ID();

		$sql_data_array = array('customers_id' => $customer_id,
								'address_book_id' => 1,
								'entry_firstname' => $firstname,
								'entry_lastname' => $lastname,
								'entry_street_address' => $street_address,
								'entry_postcode' => $postcode,
								'entry_city' => $city,
								'entry_country_id' => $country);

		if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
		if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;

		oos_db_perform($oostable['address_book'], $sql_data_array);

		$customers_infotable = $oostable['customers_info'];
		$dbconn->Execute("INSERT INTO $customers_infotable
                (customers_info_id,
                 customers_info_number_of_logons,
                 customers_info_date_account_created) VALUES ('" . intval($customer_id) . "',
                                                              '0',
                                                              now())");

		$maillisttable = $oostable['maillist'];
		$sql = "SELECT customers_firstname
				FROM $maillisttable
				WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
		$check_mail_customer_result = $dbconn->Execute($sql);
		if ($check_mail_customer_result->RecordCount()) {
			$dbconn->Execute("UPDATE " . $oostable['maillist'] . "
						SET customers_newsletter = '0'
						WHERE customers_email_address = '" . oos_db_input($email_address) . "'");
		}

		if (CUSTOMER_NOT_LOGIN != 'true') {
			$_SESSION['customer_id'] = $customer_id;
			if (ACCOUNT_GENDER == 'true') $_SESSION['customer_gender'] = $gender;
			$_SESSION['customer_first_name'] = $firstname;
			$_SESSION['customer_lastname'] = $lastname;
			$_SESSION['customer_default_address_id'] = 1;
			$_SESSION['customer_country_id'] = $country;
			$_SESSION['customer_zone_id'] = $zone_id;
			$_SESSION['customer_wishlist_link_id'] = $wishlist_link_id;
			$_SESSION['customer_max_order'] = $customer_max_order;

			if (ACCOUNT_VAT_ID == 'true') {
				if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error === FALSE)) {
					$_SESSION['customers_vat_id_status'] = 1;
				} else {
					$_SESSION['customers_vat_id_status'] = 0;
				}
			}

			// restore cart contents
			$_SESSION['cart']->restore_contents();

			$_SESSION['member']->restore_group();
		}

		// build the message content
		$name = $firstname . " " . $lastname;

		if (ACCOUNT_GENDER == 'true') {
			if ($gender == 'm') {
				$email_text = $aLang['email_greet_mr'];
			} else {
				$email_text = $aLang['email_greet_ms'];
			}
		} else {
			$email_text = $aLang['email_greet_none'];
		}

		$email_text .= $aLang['email_welcome'];
		if (MODULE_ORDER_TOTAL_GV_STATUS == 'true') {
			if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
				$coupon_code = oos_create_coupon_code();
				$couponstable = $oostable['coupons'];
				$insert_result = $dbconn->Execute("INSERT INTO $couponstable
                                    (coupon_code,
                                     coupon_type,
                                     coupon_amount,
                                     date_created) VALUES ('" . oos_db_input($coupon_code) . "',
                                                           'G',
                                                           '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "',
                                                           now())");
				$insert_id = $dbconn->Insert_ID();
				$coupon_email_tracktable = $oostable['coupon_email_track'];
				$insert_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable
                                    (coupon_id,
                                     customer_id_sent,
                                     sent_firstname,
                                     emailed_to,
                                     date_sent) VALUES ('" . oos_db_input($insert_id) ."',
                                                        '0',
                                                        'Admin',
                                                        '" . $email_address . "',
                                                        now() )");

				$email_text .= sprintf($aLang['email_gv_incentive_header'], $oCurrencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
                       sprintf($aLang['email_gv_redeem'], $coupon_code) . "\n\n" .
                       $aLang['email_gv_link'] . oos_href_link($aContents['gv_redeem'], 'gv_no=' . $coupon_code, 'NONSSL', false, false) .
                       "\n\n";
			}
			if (NEW_SIGNUP_DISCOUNT_COUPON != '') {
				$coupon_id = NEW_SIGNUP_DISCOUNT_COUPON;
				$couponstable = $oostable['coupons'];
				$sql = "SELECT *
						FROM $couponstable
						WHERE coupon_id = '" . oos_db_input($coupon_id) . "'";
				$coupon_result = $dbconn->Execute($sql);

				$coupons_descriptiontable = $oostable['coupons_description'];
				$sql = "SELECT *
						FROM " . $coupons_descriptiontable . "
						WHERE coupon_id = '" . oos_db_input($coupon_id) . "'
						AND coupon_languages_id = '" .  intval($nLanguageID) . "'";
				$coupon_desc_result = $dbconn->Execute($sql);
				$coupon = $coupon_result->fields;
				$coupon_desc = $coupon_desc_result->fields;
				$coupon_email_tracktable = $oostable['coupon_email_track'];
				$insert_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable
                                          (coupon_id,
                                           customer_id_sent,
                                           sent_firstname,
                                           emailed_to,
                                           date_sent) VALUES ('" . oos_db_input($coupon_id) ."',
                                                              '0',
                                                              'Admin',
                                                              '" . oos_db_input($email_address) . "',
                                                              now() )");

				$email_text .= $aLang['email_coupon_incentive_header'] .  "\n\n" .
                       $coupon_desc['coupon_description'] .
                       sprintf($aLang['email_coupon_redeem'], $coupon['coupon_code']) . "\n\n" .
                       "\n\n";
			}
		}

		$email_text .= $aLang['email_text'] . $aLang['email_contact'] . $aLang['email_warning'] . $aLang['email_disclaimer'];

		oos_mail($name, $email_address, $aLang['email_subject'], nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '3');

		if (SEND_CUSTOMER_EDIT_EMAILS == 'true') {
			$email_owner = $aLang['owner_email_subject'] . "\n" .
                     $aLang['email_separator'] . "\n" .
                     $aLang['owner_email_date'] . ' ' . strftime(DATE_FORMAT_LONG) . "\n\n" .
                     $aLang['email_separator'] . "\n";
			if (ACCOUNT_NUMBER == 'true') {
				$email_owner .= $aLang['owner_email_number'] . ' ' . $number . "\n" .
                        $aLang['email_separator'] . "\n\n";
			}
			if (ACCOUNT_COMPANY == 'true') {
				$email_owner .= $aLang['owner_email_company_info'] . "\n" .
								$aLang['owner_email_company'] . ' ' . $company . "\n";

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
							$aLang['owner_email_fax_number'] . ' ' . $fax . "\n" .
							$aLang['owner_email_address'] . ' ' . $email_address . "\n" .
							$aLang['email_separator'] . "\n\n" .
							$aLang['owner_email_options'] . "\n";
			if ($newsletter == '1') {
				$email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_yes'] . "\n";
			} else {
				$email_owner .= $aLang['owner_email_newsletter'] . $aLang['entry_newsletter_no'] . "\n";
			}
			oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['owner_email_subject'], nl2br($email_owner), $name, $email_address, '1');
		}

		if (count($_SESSION['navigation']->snapshot) > 0) {
			$origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
			$_SESSION['navigation']->clear_snapshot();
		} else {
			$origin_href = oos_href_link($aContents['main']);
		}
		
		oos_redirect($origin_href);
	}
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title']);
$sCanonical = oos_href_link($aContents['create_account'], '', 'SSL', FALSE, TRUE);
$sPagetitle = $aLang['heading_title'];

	
ob_start();
require 'includes/js/create_account.js.php';
$javascript = ob_get_contents();
ob_end_clean();


$aTemplate['page'] = $sTheme . '/page/create_account.tpl';
$nPageType = OOS_PAGE_TYPE_ACCOUNT;

require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
}

$read = 'false';
$smarty->assign('read', $read);
$smarty->assign('javascript', $javascript);

// assign Smarty variables;
$smarty->assign(
      array(
         'breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
         'heading_title' => $aLang['heading_title'],

            'pagetitle'         => htmlspecialchars($sPagetitle),
            'canonical'         => $sCanonical

         ));

$smarty->assign('email_address', $email_address);


if ($bError == TRUE) {
    $smarty->assign(array('error' => $bError,
                          'gender_error' => $gender_error,
                          'firstname_error' => $firstname_error,
                          'lastname_error' => $lastname_error,
                          'date_of_birth_error' => $date_of_birth_error,
                          'email_address_error' => $email_address_error,
                          'email_address_check_error' => $email_address_check_error,
                          'email_address_exists' => $email_address_exists,
                          'vatid_check_error' => $vatid_check_error,
                          'street_address_error' => $street_address_error,
                          'post_code_error' => $post_code_error,
                          'city_error' => $city_error,
                          'country_error' => $country_error,
                          'state_error' => $state_error,
                          'state_has_zones' => $state_has_zones,
                          'telephone_error' => $telephone_error,
                          'password_error' => $password_error));
    $smarty->assign(array('gender' => $gender,
                          'firstname' => $firstname,
                          'lastname' => $lastname,
                          'dob' => $dob,
                          'number' => $number,
                          'email_address' => $email_address,
                          'company' => $company,
                          'owner' => $owner,
                          'vat_id' => $vat_id,
                          'street_address' => $street_address,
                          'suburb' => $suburb,
                          'postcode' => $postcode,
                          'city' => $city,
                          'country' => $country,
                          'telephone' => $telephone,
                          'fax' => $fax,
                          'newsletter' => $newsletter,
                          'password' => $password,
                          'confirmation' => $confirmation));

    if ($state_has_zones == 'true') {
		$zones_names = array();
		$zones_values = array();

		$zonestable = $oostable['zones'];
		$zones_result = $dbconn->Execute("SELECT zone_name FROM $zonestable WHERE zone_country_id = '" . intval($country) . "' ORDER BY zone_name");
		while ($zones = $zones_result->fields) {
			$zones_names[] =  $zones['zone_name'];
			$zones_values[] = $zones['zone_name'];
			$zones_result->MoveNext();
		}
		$smarty->assign('zones_names', $zones_names);
		$smarty->assign('zones_values', $zones_values);
    } else {
		$state = oos_get_zone_name($country, $zone_id, $state);
		$smarty->assign('state', $state);
		$smarty->assign('zone_id', $zone_id);
    }
}	

if (CUSTOMER_NOT_LOGIN == 'true') {
	$show_password = FALSE;
} else {
	$show_password = 'true';
}

$smarty->assign('snapshot', $snapshot);
$smarty->assign('login_orgin_text', sprintf($aLang['text_origin_login'], oos_href_link($aContents['login'], oos_get_all_get_parameters(), 'SSL')));

$smarty->assign('newsletter_ids', array(0,1));
$smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

	
$country_name = oos_get_country_name($country);
$smarty->assign('country_name', $country_name);
if ($newsletter == '1') {
	$news = $aLang['entry_newsletter_yes'];
} else {
	$news = $aLang['entry_newsletter_no'];
}
$smarty->assign('news', $news);

$smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'heading_title' => $aLang['heading_title'],
 
            'email_address'     => $email_address,
            'show_password'     => $show_password

        )
);

$smarty->assign('newsletter_ids', array(0,1));
$smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

// display the template
$smarty->display($aTemplate['page']);
  

 