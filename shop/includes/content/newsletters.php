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
// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';


if ( isset($_POST['action']) && ($_POST['action'] == 'process') {

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
	
	
		$newsletter_recipients = $oostable['newsletter_recipients'];
		$sql = "SELECT recipients_id
              FROM $newsletter_recipients
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
		$check_recipients_result = $dbconn->Execute($sql);

		if ($check_recipients_result->RecordCount()) {
			$check_recipients = $check_customer_result->fields;

			// start the session
			if ( $session->hasStarted() === FALSE ) $session->start();	
	
			$_SESSION['error_message'] = $aLang['entry_email_address_error_exists'];
			oos_redirect(oos_href_link($aContents['newsletters'], '', 'SSL'));		
		} else {
			$sRandom = oos_create_random_value(25);
			$sBefor = oos_create_random_value(4);
	
			$newsletter_recipients = $oostable['newsletter_recipients'];
			$dbconn->Execute("INSERT INTO $newsletter_recipients 
                            (customers_email_address,
							mail_key,
							key_sent,
							status) VALUES ('" . oos_db_input($email_address) . "'
											'" . oos_db_input($sRandom) . "'
											now(),
											'0')");

			$nInsert_ID = $dbconn->Insert_ID();	  
			$newsletter_recipients = $oostable['newsletter_recipients_history'];
			$dbconn->Execute("INSERT INTO $newsletter_recipients 
                          (recipients_id,
						  date_added) VALUES ('" . intval($nInsert_ID) . "',
                                               now())");
											   
			$sStr =  $sBefor . $nInsert_ID . 'f00d';
            $sSha1 = sha1($sStr);

            $newsletter_recipients = $oostable['newsletter_recipients'];
            $dbconn->Execute("UPDATE $newsletter_recipients
                          SET mail_sha1 = '" . oos_db_input($sSha1) . "'
                          WHERE recipients_id = ('" . intval($nInsert_ID) . "'");			
			//smarty
			require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
			$smarty = new myOOS_Smarty();						

			// dont allow cache
			$smarty->caching = false;

			$smarty->assign(
				array(
					'shop_name'		=> STORE_NAME,
					'shop_url'		=> OOS_HTTP_SERVER . OOS_SHOP,
					'shop_logo'		=> STORE_LOGO,
					'services_url'	=> COMMUNITY,
					'blog_url'		=> BLOG_URL,
					'imprint_url'	=> oos_href_link($aContents['information'], 'information_id=1', 'NONSSL', FALSE, TRUE),
					'subscribe'		=> oos_href_link($aContents['newsletters_subscribe'], 'subscribe=confirm&u=' .  $sSha1 . '&id=' . $sStr '&e=' . $random, 'SSL', FALSE, TRUE)
				)
			);

			// create mails	
			$email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletters_subscribe.html');
			$email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletters_subscribe.tpl');
		
			oos_mail($check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], $email_address, $aLang['email_password_reminder_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
 
		$_SESSION['password_forgotten_count'] = 1;
        $_SESSION['success_message'] = $aLang['text_password_sent'];
        oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));	  
	  
	  
	  
	  
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
          oos_redirect(oos_href_link($aContents['newsletters_subscribe']));
        } else {
          $sql_data_array = array('customers_firstname' => $firstname,
                                  'customers_lastname' => $lastname,
                                  'customers_email_address' => $email_address,
                                  'customers_newsletter' => 1);
          oos_db_perform($oostable['newsletter_recipients'], $sql_data_array);
          oos_redirect(oos_href_link($aContents['newsletters_subscribe']));
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
