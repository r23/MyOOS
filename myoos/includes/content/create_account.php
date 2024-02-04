<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account.php,v 1.59 2003/02/14 05:51:17 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_word_cleaner.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/create_account.php';

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

// navigation history
if (!isset($_SESSION['navigation'])) {
    $_SESSION['navigation'] = new navigationHistory();
}

if ($_SESSION['login_count'] > 20) {
    oos_redirect(oos_href_link($aContents['403']));
}


if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    if (ACCOUNT_GENDER == 'true') {
        $gender = filter_string_polyfill(filter_input(INPUT_POST, 'gender'));
    }
    $firstname = filter_string_polyfill(filter_input(INPUT_POST, 'firstname'));
    $lastname = filter_string_polyfill(filter_input(INPUT_POST, 'lastname'));

    if (ACCOUNT_DOB == 'true') {
        $dob = filter_string_polyfill(filter_input(INPUT_POST, 'dob'));
    }
    $email_address = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);
    if (ACCOUNT_COMPANY == 'true') {
        $company = filter_string_polyfill(filter_input(INPUT_POST, 'company'));
    }
    if (ACCOUNT_OWNER == 'true') {
        $owner = filter_string_polyfill(filter_input(INPUT_POST, 'owner'));
    }
    if (ACCOUNT_VAT_ID == 'true') {
        $vat_id = filter_string_polyfill(filter_input(INPUT_POST, 'vat_id'));
    }
    $vat_id = filter_string_polyfill(filter_input(INPUT_POST, 'vat_id'));
    $vat_id = filter_string_polyfill(filter_input(INPUT_POST, 'vat_id'));
    $vat_id = filter_string_polyfill(filter_input(INPUT_POST, 'vat_id'));
    $street_address = oos_db_prepare_input($_POST['street_address']);
    $postcode = oos_db_prepare_input($_POST['postcode']);
    $city = oos_db_prepare_input($_POST['city']);
    if (ACCOUNT_STATE == 'true') {
        $state = filter_string_polyfill(filter_input(INPUT_POST, 'state'));
        $zone_id = filter_input(INPUT_POST, 'zone_id', FILTER_VALIDATE_INT);
    }
    $country = filter_string_polyfill(filter_input(INPUT_POST, 'country'));
    if (ACCOUNT_TELEPHONE  == 'true') {
        $telephone = filter_string_polyfill(filter_input(INPUT_POST, 'telephone'));
    }
    $password = filter_string_polyfill(filter_input(INPUT_POST, 'password'));
    $confirmation = filter_string_polyfill(filter_input(INPUT_POST, 'confirmation'));
    $newsletter = filter_string_polyfill(filter_input(INPUT_POST, 'newsletter'));
    $agree = filter_string_polyfill(filter_input(INPUT_POST, 'agree'));



    $firstname = oos_remove_shouting($firstname, true);
    $lastname = oos_remove_shouting_name($lastname, true);
    $email_address = strtolower($email_address);
    $street_address = oos_remove_shouting($street_address);
    $postcode = strtoupper((string) $postcode);
    $city = oos_remove_shouting($city);


    $bError = false; // reset error flag
    if (ACCOUNT_GENDER == 'true') {
        if (($gender != 'm') && ($gender != 'f') && ($gender != 'd')) {
            $bError = true;
            $oMessage->add($aLang['entry_gender_error']);
        }
    }

    if (strlen($firstname ?? '') < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_first_name_error']);
    }

    if (strlen($lastname ?? '') < ENTRY_LAST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_last_name_error']);
    }

    if (ACCOUNT_DOB == 'true') {
        if ((strlen($dob ?? '') < ENTRY_DOB_MIN_LENGTH) || (!empty($dob)
            && (!is_numeric(oos_date_raw($dob))
            || !checkdate(substr((string) oos_date_raw($dob), 4, 2), substr((string) oos_date_raw($dob), 6, 2), substr((string) oos_date_raw($dob), 0, 4))))
        ) {
            $bError = true;
            $oMessage->add($aLang['entry_date_of_birth_error']);
        }
    }

    if (strlen($email_address ?? '') < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_email_address_error']);
    } elseif (is_email($email_address) == false) {
        $bError = true;
        $oMessage->add($aLang['entry_email_address_check_error']);
    } else {
        if ($_SESSION['guest_account'] == 1) {
            $email_address_exists = false;
        } else {
            $customerstable = $oostable['customers'];
            $check_email_sql = "SELECT customers_email_address
                      FROM $customerstable
                      WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
            $check_email = $dbconn->Execute($check_email_sql);
            if ($check_email->RecordCount()) {
                $bError = true;
                $oMessage->add($aLang['entry_email_address_error_exists']);
            }
        }
    }

    if (ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') {
        if (!empty($vat_id) && (!oos_validate_is_vatid($vat_id))) {
            $bError = true;
            $oMessage->add($aLang['entry_vat_id_error']);
        } else {
            $vatid_check_error = false;
        }
    }

    if (strlen($street_address ?? '') < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_street_address_error']);
    }

    if (strlen($postcode ?? '') < ENTRY_POSTCODE_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_post_code_error']);
    }

    if (strlen($city ?? '') < ENTRY_CITY_MIN_LENGTH) {
        $bError = true;
        $oMessage->add($aLang['entry_city_error']);
    }

    if (is_numeric($country ?? '') == false) {
        $bError = true;
        $oMessage->add($aLang['entry_country_error']);
    }

    if (ACCOUNT_STATE == 'true') {
        $zone_id = 0;
        $zonestable = $oostable['zones'];
        $country_check_sql = "SELECT COUNT(*) AS total
								FROM $zonestable
								WHERE zone_country_id = '" . intval($country) . "'";
        $country_check = $dbconn->Execute($country_check_sql);
        $entry_state_has_zones = ($country_check->fields['total'] > 0);
        if ($entry_state_has_zones == true) {
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
                $bError = true;
                $oMessage->add($aLang['entry_state_error_select']);
            }
        } else {
            if (strlen($state ?? '') < ENTRY_STATE_MIN_LENGTH) {
                $bError = true;
                $oMessage->add($aLang['entry_state_error']);
            }
        }
    }

    if ($_SESSION['guest_account'] == 1) {
        $password_error = false;
    } else {
        if (CUSTOMER_NOT_LOGIN == 'false') {
            if (strlen($password ?? '') < ENTRY_PASSWORD_MIN_LENGTH) {
                $bError = true;
                $oMessage->add($aLang['entry_password_error']);
            } elseif ($password != $confirmation) {
                $bError = true;
                $oMessage->add($aLang['entry_password_error_not_matching']);
            }
        }
    }

    if (empty($agree)) {
        $bError = true;
        $oMessage->add($aLang['entry_agree_error']);
    }

    if ($bError == false) {
        $customer_max_order = DEFAULT_MAX_ORDER;
        $customers_status = DEFAULT_CUSTOMERS_STATUS_ID;

        if (CUSTOMER_NOT_LOGIN == 'true') {
            $customers_login = '0';
        } else {
            $customers_login = '1';
        }

        $wishlist_link_id = oos_create_wishlist_code();

        if ($_SESSION['guest_account'] == 1) {
            $sql_data_array = ['customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_email_address' => '', 'guest_email_address' => $email_address, 'customers_status' => $customers_status, 'customers_login' => $customers_login, 'customers_language' => $sLanguage, 'customers_max_order' => $customer_max_order, 'customers_password' => oos_encrypt_password($wishlist_link_id), 'customers_wishlist_link_id' => $wishlist_link_id, 'customers_default_address_id' => 1];
        } else {
            $sql_data_array = ['customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_email_address' => $email_address, 'customers_status' => $customers_status, 'customers_login' => $customers_login, 'customers_language' => $sLanguage, 'customers_max_order' => $customer_max_order, 'customers_password' => oos_encrypt_password($password), 'customers_wishlist_link_id' => $wishlist_link_id, 'customers_default_address_id' => 1];
        }

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['customers_gender'] = $gender;
        }
        if (ACCOUNT_DOB == 'true') {
            $sql_data_array['customers_dob'] = oos_date_raw($dob);
        }
        if (ACCOUNT_TELEPHONE  == 'true') {
            $sql_data_array['customers_telephone'] = $telephone;
        }

        oos_db_perform($oostable['customers'], $sql_data_array);

        $customer_id = $dbconn->Insert_ID();

        $sql_data_array = ['customers_id' => $customer_id, 'entry_firstname' => $firstname, 'entry_lastname' => $lastname, 'entry_street_address' => $street_address, 'entry_postcode' => $postcode, 'entry_city' => $city, 'entry_country_id' => $country];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['entry_gender'] = $gender;
        }
        if (ACCOUNT_COMPANY == 'true') {
            $sql_data_array['entry_company'] = $company;
        }
        if (ACCOUNT_OWNER == 'true') {
            $sql_data_array['entry_owner'] = $owner;
        }
        if (ACCOUNT_VAT_ID == 'true') {
            $sql_data_array['entry_vat_id'] = $vat_id;
            if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false) && ($country != STORE_COUNTRY)) {
                $sql_data_array['entry_vat_id_status'] = 1;
            } else {
                $sql_data_array['entry_vat_id_status'] = 0;
            }
        }

        if (ACCOUNT_STATE == 'true') {
            if ($zone_id > 0) {
                $sql_data_array['entry_zone_id'] = $zone_id;
                $sql_data_array['entry_state'] = '';
            } else {
                $sql_data_array['entry_zone_id'] = '0';
                $sql_data_array['entry_state'] = $state;
            }
        }

        oos_db_perform($oostable['address_book'], $sql_data_array);

        $address_id = $dbconn->Insert_ID();

        $customers_table = $oostable['customers'];
        $dbconn->Execute("UPDATE $customers_table SET customers_default_address_id = '" . intval($address_id) . "' WHERE customers_id = '" . intval($customer_id) . "'");

        $customers_infotable = $oostable['customers_info'];
        $dbconn->Execute(
            "INSERT INTO $customers_infotable
						(customers_info_id,
						customers_info_number_of_logons, 
						customers_info_date_account_created) VALUES ('" . intval($customer_id) . "',
																	'0',
																	now())"
        );

		if ($_SESSION['guest_account'] == 1) {
			$guest_accounttable = $oostable['guest_account'];
			$dbconn->Execute(
				"INSERT INTO $guest_accounttable
							(customers_id,
							date_added) VALUES ('" . intval($customer_id) . "',
												now())"
			);
		}


        if (CUSTOMER_NOT_LOGIN != 'true') {
            $_SESSION['customer_id'] = $customer_id;
            if (ACCOUNT_GENDER == 'true') {
                $_SESSION['customer_gender'] = $gender;
            }
            $_SESSION['customer_first_name'] = $firstname;
            $_SESSION['customer_lastname'] = $lastname;
            $_SESSION['customer_default_address_id'] = $address_id;
            $_SESSION['customer_country_id'] = $country;
            $_SESSION['customer_zone_id'] = $zone_id;
            $_SESSION['customer_wishlist_link_id'] = $wishlist_link_id;
            $_SESSION['customer_max_order'] = $customer_max_order;

            if (ACCOUNT_VAT_ID == 'true') {
                if ((ACCOUNT_COMPANY_VAT_ID_CHECK == 'true') && ($vatid_check_error == false)) {
                    $_SESSION['customers_vat_id_status'] = 1;
                } else {
                    $_SESSION['customers_vat_id_status'] = 0;
                }
            }

            // restore cart contents
            $_SESSION['cart']->restore_contents();

            $_SESSION['user']->restore_group();
            $aUser = $_SESSION['user']->group;
        }

        // build the message content
        $name = $firstname . " " . $lastname;

        if (ACCOUNT_GENDER == 'true') {
            if ($gender == 'm') {
                $email_text = $aLang['email_greet_mr'];
            } elseif ($gender == 'f') {
                $email_text = $aLang['email_greet_ms'];
            } elseif ($gender == 'd') {
                $email_text = $aLang['email_greet_diverse'];
            }
        } else {
            $email_text = $aLang['email_greet_none'];
        }


        if (isset($_SESSION['guest_account']) && ($_SESSION['guest_account'] == '1')) {
            // todo coupons for guest account
        } else {
            $email_text .= $aLang['email_welcome'];

            $b_gv_status = (defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true') ? true : false);
            if ($b_gv_status === true) {
                if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
                    $coupon_code = oos_create_coupon_code();
                    $couponstable = $oostable['coupons'];
                    $insert_result = $dbconn->Execute(
                        "INSERT INTO $couponstable
                                    (coupon_code,
                                     coupon_type,
                                     coupon_amount,
                                     date_created) VALUES ('" . oos_db_input($coupon_code) . "',
                                                           'G',
                                                           '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "',
                                                           now())"
                    );
                    $insert_id = $dbconn->Insert_ID();
                    $coupon_email_tracktable = $oostable['coupon_email_track'];
                    $insert_result = $dbconn->Execute(
                        "INSERT INTO $coupon_email_tracktable
                                    (coupon_id,
                                     customer_id_sent,
                                     sent_firstname,
                                     emailed_to,
                                     date_sent) VALUES ('" . oos_db_input($insert_id) ."',
                                                        '0',
                                                        'Admin',
                                                        '" . $email_address . "',
                                                        now() )"
                    );

                    $email_text .= sprintf($aLang['email_gv_incentive_header'], $oCurrencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) . "\n\n" .
                       sprintf($aLang['email_gv_redeem'], $coupon_code) . "\n\n" .
                       $aLang['email_gv_link'] . oos_href_link($aContents['gv_redeem'], 'gv_no=' . $coupon_code, false, false) .
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
                    $insert_result = $dbconn->Execute(
                        "INSERT INTO $coupon_email_tracktable
                                          (coupon_id,
                                           customer_id_sent,
                                           sent_firstname,
                                           emailed_to,
                                           date_sent) VALUES ('" . oos_db_input($coupon_id) ."',
                                                              '0',
                                                              'Admin',
                                                              '" . oos_db_input($email_address) . "',
                                                              now() )"
                    );

                    $email_text .= $aLang['email_coupon_incentive_header'] .  "\n\n" .
                                $coupon_desc['coupon_description'] .
                            sprintf($aLang['email_coupon_redeem'], $coupon['coupon_code']) . "\n\n" .
                        "\n\n";
                }
            }

            $email_text .= $aLang['email_text'] . $aLang['email_contact'] . $aLang['email_warning'];
            oos_mail($name, $email_address, $aLang['email_subject'], nl2br($email_text), nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '3');


            if (SEND_CUSTOMER_EDIT_EMAILS == 'true') {
                $formatter = new IntlDateFormatter(THE_LOCALE, IntlDateFormatter::FULL, IntlDateFormatter::NONE);

                $email_owner = $aLang['owner_email_subject'] . "\n" .
                        $aLang['email_separator'] . "\n" .
                        $aLang['owner_email_date'] . ' ' . $formatter->format(time()) . "\n\n" .
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
                    } elseif ($gender == 'f') {
                        $email_owner .= $aLang['entry_gender'] . ' ' . $aLang['female'] . "\n";
                    } else {
                        $email_owner .= $aLang['entry_gender'] . ' ' . $aLang['diverse'] . "\n";
                    }
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

            oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['owner_email_subject'], nl2br($email_owner), nl2br($email_owner), $name, $email_address, '1');
        }


        if (NEWSLETTER == 'true') {
            if (isset($newsletter) && ($newsletter == 'yes')) {
                oos_newsletter_subscribe_mail($email_address);
            }
        }

        if (isset($_SESSION['guest_account']) && ($_SESSION['guest_account'] == '1')) {
            $_SESSION['customers_email_address'] = $email_address;

            oos_redirect(oos_href_link($aContents['checkout_shipping']));
        } else {
            if ((is_countable($_SESSION['navigation']->snapshot) ? count($_SESSION['navigation']->snapshot) : 0) > 0) {
                $origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get']);
                $_SESSION['navigation']->clear_snapshot();

                oos_redirect($origin_href);
            }

            oos_redirect(oos_href_link($aContents['create_account_success']));
        }
    }
}

if (isset($_GET['guest'])) {
    $_SESSION['guest_account'] = 1;
} else {
    if (isset($_SESSION['guest_account']) && ($_SESSION['guest_account'] == '1')) {
        unset($_SESSION['customers_email_address']);
        unset($_SESSION['customer_id']);
    }
    $_SESSION['guest_account'] = 0;
}

// $oMessage->add(sprintf($aLang['text_origin_login'], oos_href_link($aContents['login'])));


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['create_account']));
$sCanonical = oos_href_link($aContents['create_account'], '', false, true);

$snapshot = is_countable($_SESSION['navigation']->snapshot) ? count($_SESSION['navigation']->snapshot) : 0;

$email_address = filter_input(INPUT_GET, 'email_address', FILTER_VALIDATE_EMAIL);

$account['entry_country_id'] = STORE_COUNTRY;


$aTemplate['page'] = $sTheme . '/page/create_account.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,follow,noodp,noydir', 'canonical'        => $sCanonical]
);

$smarty->assign('account', $account);
$smarty->assign('email_address', $email_address);

$smarty->assign('snapshot', $snapshot);
$smarty->assign('login_agree', sprintf($aLang['agree'], oos_href_link($aContents['information'], 'information_id=2'), oos_href_link($aContents['information'], 'information_id=4')));

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
