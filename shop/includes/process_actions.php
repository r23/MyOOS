<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$bError = FALSE;

// Newsletter	
$email_address = oos_prepare_input($_POST['email_address']);

if ( empty( $email_address ) || !is_string( $email_address ) ) {
	$bError = TRUE;
	$aInfoMessage[] = array(
						'type' => 'danger',
						'text' => $aLang['error_email_address']
					);
} 

if ( ($bError === FALSE) && (!oos_validate_is_email($email_address)) ) {
	$bError = TRUE;
	$aInfoMessage[] = array(
						'type' => 'danger',
						'text' => $aLang['error_email_address']
					);
} 

if ( isset($_POST['newsletter']) 
	&& ($_POST['newsletter'] == 'subscriber') 
	&& ($bError === FALSE) ) {

	$newsletter_recipients = $oostable['newsletter_recipients'];
	$sql = "SELECT recipients_id
              FROM $newsletter_recipients
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
	$check_recipients_result = $dbconn->Execute($sql);

	if ($check_recipients_result->RecordCount()) {
		$bError = TRUE;
		$aInfoMessage[] = array(
						'type' => 'danger',
						'text' => $aLang['entry_email_address_error_exists']
					);

	} else {
		$sRandom = oos_create_random_value(25);
		$sBefor = oos_create_random_value(4);
	
		$newsletter_recipients = $oostable['newsletter_recipients'];
		$dbconn->Execute("INSERT INTO $newsletter_recipients 
                            (customers_email_address,
							mail_key,
							key_sent,
							status) VALUES ('" . oos_db_input($email_address) . "',
											'" . oos_db_input($sRandom) . "',
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
                          WHERE recipients_id = '" . intval($nInsert_ID) . "'");			
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
					'subscribe'		=> oos_href_link($aContents['newsletter'], 'subscribe=confirm&u=' .  $sSha1 . '&id=' . $sStr . '&e=' . $random, 'SSL', FALSE, TRUE)
				)
		);

		// create mails	
		$email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.html');
		$email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.tpl');
		
		oos_mail('', $email_address, $aLang['newsletter_email_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

		$aInfoMessage[] = array(
						'type' => 'success',
						'text' => $aLang['newsletter_email_info']
					);

	}
} 
