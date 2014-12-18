<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Newsletter Module
   P&G developmment

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   Copyright (c) 2000,2001 The Exchange Project
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

if(!defined('OOS_VALID_MOD'))die('Direct Access to this location is not allowed.');

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/newsletters.php';
// require  validation functions (right now only email address)
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';

if ( isset($_POST['action']) && ($_POST['action'] == 'process'){

    $email_address = oos_prepare_input($_POST['email_address']);

    if ( empty( $email_address ) || !is_string( $email_address ) ) {
		// start the session
		if ( $session->hasStarted() === FALSE ) $session->start();	
	
        $_SESSION['error_message'] = $aLang['entry_email_address_error'];
        oos_redirect(oos_href_link($aContents['newsletters'], '', 'SSL'));
    }

	
    if (!oos_validate_is_email($email_address)) {
		// start the session
		if ( $session->hasStarted() === FALSE ) $session->start();	
	
        $_SESSION['error_message'] = $aLang['entry_email_address_check_error'];	
		oos_redirect(oos_href_link($aContents['newsletters'], '', 'SSL'));
    } else {

	/*

	$oostable['newsletter_recipients'] = $prefix_table . 'newsletter_recipients';
$flds = "
	recipients_id I NOTNULL AUTO PRIMARY,
	customers_gender C(1) NOTNULL,
	customers_firstname C(32) NOTNULL,
	customers_lastname C(32) NOTNULL,
	date_added T,
	man_key C(32) NOTNULL,
	key_sent T,
	status I1 DEFAULT '0'
";
dosql($table, $flds);


$oostable['newsletter_recipients_history'] = $prefix_table . 'newsletter_recipients_history';
$flds = "
  recipients_status_history_id I NOTNULL AUTO PRIMARY,
  recipients_id I NOTNULL DEFAULT '0',
  new_value I1 NOTNULL DEFAULT '0',
  old_value I1 DEFAULT NULL,
  date_added T,
  customer_notified I1 DEFAULT '0'
";
dosql($table, $flds);

*/
	
	
      $customerstable = $oostable['customers'];
      $sql = "SELECT customers_firstname, customers_lastname, customers_id
              FROM " .$customerstable . "
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
      $check_customer_result = $dbconn->Execute($sql);

      if ($check_customer_result->RecordCount()) {
        $check_customer = $check_customer_result->fields;
//todo opt - in 
        $customerstable = $oostable['customers'];
        $dbconn->Execute("UPDATE $customerstable
						  SET customers_newsletter = '1'
						  WHERE customers_id = '" . $check_customer['customers_id'] . "'");
        oos_redirect(oos_href_link($aContents['newsletters_subscribe_success']));
      } else {
        $newsletter_recipientstable = $oostable['newsletter_recipients'];
        $sql = "SELECT customers_firstname
                FROM " . $newsletter_recipientstable . "
                WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
        $check_mail_customer_result = $dbconn->Execute($sql);
        if ($check_mail_customer_result->RecordCount()) {
          $newsletter_recipientstable = $oostable['newsletter_recipients'];
          $dbconn->Execute("UPDATE " . $newsletter_recipientstable . "
                            SET customers_newsletter = '1'
                            WHERE customers_email_address = '" . oos_db_input($email_address) . "'");
          oos_redirect(oos_href_link($aContents['newsletters_subscribe_success']));
        } else {
          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_email_address' => $email_address,
                                  'customers_newsletter' => 1);
          oos_db_perform($oostable['newsletter_recipients'], $sql_data_array);
          oos_redirect(oos_href_link($aContents['newsletters_subscribe_success']));
        }
      }
    }
  } else {

    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['newsletters'], '', 'SSL'));

    $aTemplate['page'] = $sTheme . '/page/newsletters.html';

    $nPageType = OOS_PAGE_TYPE_SERVICE;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
      require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
      require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb' => $oBreadcrumb->trail(),
            'heading_title' => $aLang['heading_title']
        )
    );

  

    // display the template
	$smarty->display($aTemplate['page']);
}
