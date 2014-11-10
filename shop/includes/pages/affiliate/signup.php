<?php
/* ----------------------------------------------------------------------
   $Id: signup.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_signup.php,v 1.10 2003/02/19 11:55:03 simarilius 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('affiliate')) {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  require 'includes/languages/' . $sLanguage . '/affiliate_signup.php';

  $error = 'false'; // reset error flag
  if (isset($_POST['action'])) {
    if (ACCOUNT_GENDER == 'true') {
      if (($a_gender == 'm') || ($a_gender == 'f')) {
        $gender_error = 'false';
      } else {
        $error = 'true';
        $gender_error = 'true';
      }
    }

    if (strlen($a_firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = 'true';
      $firstname_error = 'true';
    } else {
      $firstname_error = 'false';
    }

    if (strlen($a_lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = 'true';
      $lastname_error = 'true';
    } else {
      $lastname_error = 'false';
    }

    if (ACCOUNT_DOB == 'true') {
      if (checkdate(substr(oos_date_raw($a_dob), 4, 2), substr(oos_date_raw($a_dob), 6, 2), substr(oos_date_raw($a_dob), 0, 4))) {
        $date_of_birth_error = 'false';
      } else {
        $error = 'true';
        $date_of_birth_error = 'true';
      }
    }

    if (strlen($a_email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
      $error = 'true';
      $email_address_error = 'true';
    } else {
      $email_address_error = 'false';
    }

    if (!oos_validate_is_email($a_email_address)) {
      $error = 'true';
      $email_address_check_error = 'true';
    } else {
      $email_address_check_error = 'false';
    }

    if (strlen($a_street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
      $error = 'true';
      $street_address_error = 'true';
    } else {
      $street_address_error = 'false';
    }

    if (strlen($a_postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
      $error = 'true';
      $post_code_error = 'true';
    } else {
      $post_code_error = 'false';
    } 

    if (strlen($a_city) < ENTRY_CITY_MIN_LENGTH) {
      $error = 'true';
      $city_error = 'true';
    } else {
      $city_error = 'false';
    }

    if (!isset($a_country)) {
      $error = 'true';
      $country_error = 'true';
    } else {
      $country_error = 'false';
    }

    if (strlen($a_telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
      $error = 'true';
      $telephone_error = 'true';
    } else {
      $telephone_error = 'false';
    }

    $passlen = strlen($a_password);
    if ($passlen < ENTRY_PASSWORD_MIN_LENGTH) {
      $error = 'true';
      $password_error = 'true';
    } else {
      $password_error = 'false';
    }

    if ($a_password != $a_confirmation) {
      $error = 'true';
      $password_error = 'true';
    }

    $sql = "SELECT affiliate_email_address 
            FROM " . $oostable['affiliate_affiliate'] . " 
            WHERE affiliate_email_address = '" . oos_db_input($a_email_address) . "'";
    $check_email = $dbconn->Execute($sql);
    if ($check_email->RecordCount()) {
      $error = 'true';
      $email_address_exists = 'true';
    } else {
      $email_address_exists = 'false';
    }

    // Check Suburb
    $suburb_error = 'false';

    // Check Fax
    $fax_error = 'false';

    if (!oos_validate_is_url($a_homepage)) {
      $error = 'true';
      $homepage_error = 'true';
    } else {
      $homepage_error = 'false';
    }

    if (!isset($a_agb)) {
      $error = 'true';
      $agb_error = 'true';
    }

    // Check Company 
    $company_error = 'false';


    if ($error == 'false') {

      $sql_data_array = array('affiliate_firstname' => $a_firstname,
                              'affiliate_lastname' => $a_lastname,
                              'affiliate_email_address' => $a_email_address,
                              'affiliate_payment_check' => $a_payment_check,
                              'affiliate_payment_paypal' => $a_payment_paypal,
                              'affiliate_payment_bank_name' => $a_payment_bank_name,
                              'affiliate_payment_bank_branch_number' => $a_payment_bank_branch_number,
                              'affiliate_payment_bank_swift_code' => $a_payment_bank_swift_code,
                              'affiliate_payment_bank_account_name' => $a_payment_bank_account_name,
                              'affiliate_payment_bank_account_number' => $a_payment_bank_account_number,
                              'affiliate_street_address' => $a_street_address,
                              'affiliate_postcode' => $a_postcode,
                              'affiliate_city' => $a_city,
                              'affiliate_country_id' => $a_country,
                              'affiliate_telephone' => $a_telephone,
                              'affiliate_fax' => $a_fax,
                              'affiliate_homepage' => $a_homepage,
                              'affiliate_password' => oos_encrypt_password($a_password),
                              'affiliate_agb' => '1');

      if (ACCOUNT_GENDER == 'true') $sql_data_array['affiliate_gender'] = $a_gender;
      if (ACCOUNT_DOB == 'true') $sql_data_array['affiliate_dob'] = oos_date_raw($a_dob);
      if (ACCOUNT_COMPANY == 'true') {
        $sql_data_array['affiliate_company'] = $a_company;
        $sql_data_array['affiliate_company_taxid'] = $a_company_taxid;
      }
      if (ACCOUNT_SUBURB == 'true') $sql_data_array['affiliate_suburb'] = $a_suburb;

      $sql_data_array['affiliate_zone_id'] = '';
      $sql_data_array['affiliate_state'] = '';


      $sql_data_array['affiliate_date_account_created'] = 'now()';

      oos_db_perform($oostable['affiliate_affiliate'], $sql_data_array);

      $affiliate_id = $dbconn->Insert_ID();

      $aemailbody = $aLang['mail_affiliate_header'] . "\n"
                  . $aLang['mail_affiliate_id'] . $affiliate_id . "\n"
                  . $aLang['mail_affiliate_username'] . $a_email_address . "\n"
                  . $aLang['mail_affiliate_password'] . $a_password . "\n\n"
                  . $aLang['mail_affiliate_link']
                  . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' .$aModules['affiliate'] . '&file=' . $aFilename['affiliate_affiliate'] . "\n\n"
                  . $aLang['mail_affiliate_footer'];
      oos_mail($a_firstname . ' ' . $a_lastname, $a_email_address, $aLang['mail_affiliate_subject'], nl2br($aemailbody), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);

      $_SESSION['affiliate_id'] = $affiliate_id;
      $_SESSION['affiliate_email'] = $a_email_address;
      $_SESSION['affiliate_name'] = $a_firstname . ' ' . $a_lastname;

      oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_signup_ok'], '', 'SSL'));
    }
  }

  if (isset($_GET['affiliate_email_address'])) $a_email_address = oos_db_prepare_input($_GET['affiliate_email_address']);
  $affiliate['affiliate_country_id'] = STORE_COUNTRY;
  if (!isset($is_read_only)) $is_read_only = 'false';
  if (!isset($processed)) $processed = 'false';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_signup'], '', 'SSL'));

  ob_start();
  require 'js/affiliate_form_check.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aOption['template_main'] = $sTheme . '/modules/affiliate_signup.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_AFFILIATE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'login.gif',

          'affiliate'         => $affiliate,
          'is_read_only'      => $is_read_only,
          'processed'         => $processed,

          'error'             => $error,
          'gender_error'      => $gender_error,
          'firstname_error'   => $firstname_error,
          'lastname_error'    => $lastname_error,
          'date_of_birth_error' => $date_of_birth_error,
          'email_address_error' => $email_address_error,
          'email_address_check_error' => $email_address_check_error,

          'email_address_exists' => $email_address_exists,
          'street_address_error' => $street_address_error,
          'homepage_error'       => $homepage_error,
          'post_code_error'      => $post_code_error,
          'city_error'           => $city_error,

          'country_error'   => $country_error,
          'telephone_error' => $telephone_error,
          'password_error'  => $password_error,
          'agb_error'       => $agb_error,

          'a_gender'        => $a_gender,
          'a_firstname'     => $a_firstname,
          'a_lastname'      => $a_lastname,
          'a_dob'           => $a_dob,
          'a_email_address' => $a_email_address,
          'a_company_taxid' => $a_company_taxid,
          'a_payment_check' => $a_payment_check,
          'a_payment_paypal'              => $a_payment_paypal,
          'a_payment_bank_name'           => $a_payment_bank_name,
          'a_payment_bank_branch_number'  => $a_payment_bank_branch_number,
          'a_payment_bank_swift_code'     => $a_payment_bank_swift_code,
          'a_payment_bank_account_name'   => $a_payment_bank_account_name,
          'a_payment_bank_account_number' => $a_payment_bank_account_number,

          'a_company'          => $a_company,
          'a_street_address'   => $a_street_address,
          'a_suburb'           => $a_suburb,
          'a_postcode'         => $a_postcode,
          'a_city'             => $a_city,
          'a_country'          => $a_country,
          'a_telephone'        => $a_telephone,
          'a_fax'              => $a_fax,
          'a_homepage'         => $a_homepage,
          'a_password'         => $a_password,
          'a_confirmation'     => $a_confirmation
      )
  );

  // JavaScript
  $oSmarty->assign('oos_js', $javascript);

  $country_name = oos_get_country_name($a_country);
  $oSmarty->assign('country_name', $country_name);

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>