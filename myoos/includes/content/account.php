<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account.php,v 1.58 2003/02/13 01:58:22 hpdl
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

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['customer_id'])) {
    // navigation history
    if (!isset($_SESSION['navigation'])) {
        $_SESSION['navigation'] = new navigationHistory();
    }

    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login']));
}

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_word_cleaner.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_account.php';

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
    if (ACCOUNT_TELEPHONE  == 'true') {
        $telephone = filter_string_polyfill(filter_input(INPUT_POST, 'telephone'));
    }
    $password = filter_string_polyfill(filter_input(INPUT_POST, 'password'));
    $confirmation = filter_string_polyfill(filter_input(INPUT_POST, 'confirmation'));
    $newsletter = filter_string_polyfill(filter_input(INPUT_POST, 'newsletter'));

    $firstname = oos_remove_shouting($firstname, true);
    $lastname = oos_remove_shouting_name($lastname, true);
    $email_address = strtolower($email_address);


    $bError = false; // reset error flag

    if (ACCOUNT_GENDER == 'true') {
        if (($gender != 'm') && ($gender != 'f') && ($gender != 'd')) {
            $bError = true;
            $oMessage->add('danger', $aLang['entry_gender_error']);
        }
    }

    if (strlen($firstname ?? '') < ENTRY_FIRST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_first_name_error']);
    }

    if (strlen($lastname ?? '') < ENTRY_LAST_NAME_MIN_LENGTH) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_last_name_error']);
    }

    if (ACCOUNT_DOB == 'true') {
        if ((strlen($dob ?? '') < ENTRY_DOB_MIN_LENGTH) || (!empty($dob)
            && (!is_numeric(oos_date_raw($dob))
            || !checkdate(substr((string) oos_date_raw($dob), 4, 2), substr((string) oos_date_raw($dob), 6, 2), substr((string) oos_date_raw($dob), 0, 4))))
        ) {
            $bError = true;
            $oMessage->add('danger', $aLang['entry_date_of_birth_error']);
        }
    }


    if (strlen($email_address ?? '') < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_email_address_error']);
    } elseif (is_email($email_address) == false) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_email_address_check_error']);
    } else {
        $customerstable = $oostable['customers'];
        $check_email_sql = "SELECT customers_email_address
                      FROM $customerstable
                      WHERE customers_email_address = '" . oos_db_input($email_address) . "'
					  AND customers_id != '" . intval($_SESSION['customer_id']) . "'";
        $check_email = $dbconn->Execute($check_email_sql);
        if ($check_email->RecordCount()) {
            $bError = true;
            $oMessage->add('danger', $aLang['entry_email_address_error_exists']);
        }
    }


    if (strlen($password ?? '') < ENTRY_PASSWORD_MIN_LENGTH) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_password_error']);
    } elseif ($password != $confirmation) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_password_error_not_matching']);
    }

    if ($bError == false) {
        $new_encrypted_password = oos_encrypt_password($password);

        $sql_data_array = ['customers_firstname' => $firstname, 'customers_lastname' => $lastname, 'customers_email_address' => $email_address, 'customers_password' => $new_encrypted_password];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['customers_gender'] = $gender;
        }
        if (ACCOUNT_DOB == 'true') {
            $sql_data_array['customers_dob'] = oos_date_raw($dob);
        }
        if (ACCOUNT_TELEPHONE  == 'true') {
            $sql_data_array['customers_telephone'] = $telephone;
        }

        oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "'");

        $sql_data_array = ['entry_firstname' => $firstname, 'entry_lastname' => $lastname];

        if (ACCOUNT_GENDER == 'true') {
            $sql_data_array['entry_gender'] = $gender;
        }

        oos_db_perform($oostable['address_book'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "' AND address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'");

        $update_info_sql = "UPDATE " . $oostable['customers_info'] . " 
							SET customers_info_date_account_last_modified = now() 
							WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'";
        $dbconn->Execute($update_info_sql);


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


            $email_owner .= $aLang['owner_email_first_name'] . ' ' . $firstname . "\n" .
                      $aLang['owner_email_last_name'] . ' ' . $lastname . "\n\n" .
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
            oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['owner_email_subject'], nl2br($email_owner), nl2br($email_owner), $name, $email_address);
        }

        if (NEWSLETTER == 'true') {
            if (isset($newsletter) && ($newsletter == 'yes')) {
                oos_newsletter_subscribe_mail($email_address);
            }
        }

        // todo: successful
        // oos_redirect(oos_href_link($aContents['account']));
    }
}


$customerstable = $oostable['customers'];
$address_bookstable = $oostable['address_book'];
$sql = "SELECT c.customers_gender, c.customers_firstname, c.customers_lastname,
                 c.customers_dob, c.customers_email_address, c.customers_2fa,
                 c.customers_2fa_active, c.customers_telephone, 
                 a.entry_company, a.entry_owner, a.entry_vat_id, a.entry_vat_id_status,
				 a.entry_street_address, a.entry_postcode, a.entry_city, 
				 a.entry_zone_id, a.entry_state, a.entry_country_id
          FROM $customerstable c,
             $address_bookstable a
          WHERE c.customers_id = '" . intval($_SESSION['customer_id']) . "'
            AND a.customers_id = c.customers_id
            AND a.address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'";
$account = $dbconn->GetRow($sql);

if ($account['customers_gender'] == 'm') {
    $gender = $aLang['male'];
} elseif ($account['customers_gender'] == 'f') {
    $gender = $aLang['female'];
} else {
    $gender = $aLang['diverse'];
}
$sCountryName = oos_get_country_name($account['entry_country_id']);

if ($account['customers_2fa_active'] == '1') {
    $status = $aLang['text_activated'];
} else {
    $status = $aLang['text_disabled'];
}
$text_2fa_status = sprintf($aLang['text_2fa_status'], $status);

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['account']));

$aTemplate['page'] = $sTheme . '/page/user_account.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}


// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'            => $oBreadcrumb->trail(), 'heading_title'            => $aLang['heading_title'], 'account_active'        => 1, 'robots'                => 'noindex,follow,noodp,noydir', 'account'              => $account, 'gender'               => $gender, 'oos_get_country_name' => $sCountryName, 'text_2fa_status'      => $text_2fa_status, 'newsletter'           => $newsletter]
);

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
