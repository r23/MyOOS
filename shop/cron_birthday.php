<?php
/* ----------------------------------------------------------------------
   $Id: cron_birthday.php,v 1.1 2007/06/13 17:33:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: cron_birthday.php,v 1.0.1.2 2005/02/03 12:46:52 davistan
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2005 Davis Tan - www.datumcorp.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) {
    require 'includes/local/configure.php';
  }
  require 'includes/configure.php';

// include server parameters
  require 'includes/oos_define.php';
  require 'includes/oos_tables.php';
  require 'includes/functions/function_global.php';
  require 'includes/functions/function_kernel.php';

// include the database functions
  if (!defined('ADODB_LOGSQL_TABLE')) {
    define('ADODB_LOGSQL_TABLE', $oostable['adodb_logsql']);
  }
  require 'includes/classes/thirdparty/adodb/adodb-errorhandler.inc.php';
  require 'includes/classes/thirdparty/adodb/adodb.inc.php';
  require 'includes/functions/function_db.php';


// make a connection to the database... now
  if (!oosDBInit()) {
    die('Unable to connect to database server!');
  }

  $dbconn =& oosDBGetConn();
  oosDB_importTables($oostable);

  function matheval($equation){
    $equation = preg_replace("/[^0-9+\-.*\/()%]/","",$equation);
    $equation = preg_replace("/([+-])([0-9]+)(%)/","*(1\$1.\$2)",$equation);
    // you could use str_replace on this next line
    // if you really, really want to fine-tune this equation
    $equation = preg_replace("/([0-9]+)(%)/",".\$1",$equation);
    if ( $equation == "" ) {
      $return = 0;
    } else {
      eval("\$return=" . $equation . ";");
    }
    return $return;
  }

  //Settings - changes made here
  $offset = '+2'; //Send birthday email how many days after(-)/during(blank)/before(+)
  $subject = 'Happy Birthday %s!';
  $msg = "Dear %s,\n\nFrom our records, we found that your birthday is on %s. Therefore, we from " . STORE_NAME . " would like take this opportunity to wish you Happy Birthday!!!\n\n  ** MAY ALL YOUR DREAMS COMES TRUE ! **\n** and buy more for less money **\n";
#  $msg .= "\nWe have a birthday present for you :)\n\n" . STORE_NAME . " would like to give you a birthday present:\n";
  $msg .= "\nWe hope that this little email have lighten up your day (a little if not much) :-)\n\nHave a nice day and hope to see you again at " . STORE_NAME . "!\n\n\nYours truly,\n\n" . STORE_OWNER . "\n" . STORE_NAME . ' - ' . OOS_HTTP_SERVER . OOS_SHOP . "\n" . TEXT_SLOGAN . "\n";

  $day    = date("d");
  $month  = date("m");
  $year   = date("Y");

  if (($month > 1) && (matheval($day.$offset) < 1)) {
    $mday = date("t", mktime(0, 0, 0, ($month - 1), 1, $year));
  } elseif (($month == 1) && (matheval($day.$offset) < 1)) {
    $mday = '31';
  } else {
    $mday = date("t");
  }

  if (matheval($day . $offset) > $mday) {
    $sql_day    = matheval($day . $offset) - $mday;
    if ($month < 12) {
      $sql_month  = $month + 1;
      $sql_year   = $year;
    } else {
      $sql_month  = 1;
      $sql_year   = $year + 1;
    }
  } elseif (matheval($day.$offset) < 1) {
    $sql_day    = matheval(($mday + $day) . $offset);
    if ($month > 1) {
      $sql_month  = $month - 1;
      $sql_year   = $year;
    } else {
      $sql_month  = 12;
      $sql_year   = $year - 1;
    }
  } else {
    $sql_day    = matheval($day . $offset);
    $sql_month  = $month;
    $sql_year   = $year;
  }

  $sql_day = ((strlen($sql_day) == 1)?"0".$sql_day:$sql_day);
  $sql_month = ((strlen($sql_month) == 1)?"0".$sql_month:$sql_month);

  //prevent script from running more than once a day
  $configurationtable = $oostable['configuration'];
  $sql = "SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'CRON_BD_RUN'";
  $prevent_result = $dbconn->Execute($sql);

  if ($prevent_result->RecordCount() > 0) {
    $prevent = $prevent_result->fields;
    if ($prevent['configuration_value'] == date("Ymd")) {
      die('Halt! Already executed - should not execute more than once a day.');
    } else {
      $configurationtable = $oostable['configuration'];
      $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . date("Ymd") . "' WHERE configuration_key = 'CRON_BD_RUN'");
    }
  } else {
    $configurationtable = $oostable['configuration'];
    $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id) VALUES ('CRON_BD_RUN', '" . date("Ymd") . "', '6')");
  }

  $customerstable = $oostable['customers'];
  $sql = "SELECT customers_gender, customers_firstname, customers_lastname, customers_dob, customers_email_address, customers_language FROM $customerstable WHERE MONTH(customers_dob) = MONTH('" . $sql_year . "-" . $sql_month . "-" . $sql_day . "') AND DAYOFMONTH(customers_dob) = DAYOFMONTH('" . $sql_year . "-" . $sql_month . "-" . $sql_day . "')";
  $customers_result = $dbconn->Execute($sql);

  if ($customers_result->RecordCount() > 0) {
    while($customers = $customers_result->fields) {
/*
      $sLanguage = oos_var_prep_for_os($customers['customers_language']);
      require 'includes/languages/' . $sLanguage . '/cron_birthday.php';
*/

      $name = $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];


      echo 'Sent email to ' . $name . ' ' . oos_date_short($customers['customers_dob']) . "\n";

      $subject = sprintf($aLang['email_subject'], $customers['customers_firstname']);
      $bd_msg = sprintf($aLang['email_text'] $customers['customers_firstname'], oos_date_short($customers['customers_dob']));


 // build the message content
    $name = $customers['customers_firstname'] . ' ' . $customers['customers_lastname'];

/*
    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $email_text = sprintf($aLang['email_greet_mr'], $customers['customers_lastname']);
      } else {
        $email_text = sprintf($aLang['email_greet_ms'], $customers['customers_lastname']);
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
                       $aLang['email_gv_link'] . oos_href_link($aModules['gv'], $aFilename['gv_redeem'], 'gv_no=' . $coupon_code, 'NONSSL', false, false) . 
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
    if (MAKE_PASSWORD == 'true') {
      $email_text .= sprintf($aLang['email_password'], $password) . "\n\n";
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
        if (ACCOUNT_OWNER == 'true') {
          $email_owner .= $aLang['owner_email_owner'] . ' ' . $owner . "\n";
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

*/


      oos_mail($name, $customers['customers_email_address'], $subject, $bd_msg, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, '');

      // send emails to other people
      if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
        oos_mail('', SEND_EXTRA_ORDER_EMAILS_TO, $subject, $bd_msg, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, '');
      }

      // Move that ADOdb pointer!
      $customers_result->MoveNext();
    }
  } else {
    //no birthdays for today
    if (SEND_EXTRA_ORDER_EMAILS_TO != '') {
      oos_mail('', SEND_EXTRA_ORDER_EMAILS_TO, . ' Birthday Cron', 'No birthday for ' . date("Y-m-d") . ' offset: '. $offset, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS, '');
    }
  }
  require 'includes/oos_nice_exit.php';

?>
