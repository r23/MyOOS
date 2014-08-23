<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.48 2003/02/13 03:10:55 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );
   
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_password_forgotten.php';
// require the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';

if (isset($_POST['action']) && ($_POST['action'] == 'process')) {

    $email_address = oos_prepare_input($_POST['email_address']);
	
    if ( empty( $email_address ) || !is_string( $email_address ) ) {
        oos_redirect(oos_href_link($aContents['forbiden']));
    }

    // start the session
    if ( is_session_started() === FALSE ) oos_session_start();

    if (!isset($_SESSION['password_forgotten_count'])) {
        $_SESSION['password_forgotten_count'] = 1;
    } else {
        $_SESSION['password_forgotten_count'] += 1;
    }
	
    if ( $_SESSION['password_forgotten_count'] > 3) {
        oos_redirect(oos_href_link($aContents['forbiden']));
    }
	
    $customerstable = $oostable['customers'];
    $check_customer_sql = "SELECT customers_gender, customers_firstname, customers_lastname, customers_password, customers_id
                           FROM $customerstable
                           WHERE customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($check_customer_sql);

    if ($check_customer_result->RecordCount()) {
        $check_customer = $check_customer_result->fields;

        // Crypted password mods - create a new password, update the database and mail it to them
        $newpass = oos_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
        $crypted_password = oos_encrypt_password($newpass);

        $customerstable = $oostable['customers'];
        $dbconn->Execute("UPDATE $customerstable
                        SET customers_password = '" . oos_db_input($crypted_password) . "'
                        WHERE customers_id = '" . $check_customer['customers_id'] . "'");

		$customers_name = $check_customer['customers_firstname'] . '. ' . $check_customer['customers_lastname'];				
						
		switch ($check_customer['customers_gender']) {
			case 'm':
				$sGreet = sprintf ($aLang['email_greet_mr'], $customers_name);
				break;
			case 'f':
				$sGreet = sprintf ($aLang['email_greet_ms'], $customers_name);
				break;
			default:
				$sGreet = $aLang['email_greet_none'];
		}
		if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
						
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
				'password' 		=> $newpass
			)
		);

		// create mails
		$email_html = $smarty->fetch('/email-templates/' . $sLanguage . '/password_verification_mail.html');
		$email_txt = $smarty->fetch('/email-templates/' . $sLanguage . '/password_verification_mail.tpl');
		
        oos_mail($check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], $email_address, $aLang['email_password_reminder_subject'], nl2br(sprintf($aLang['email_password_reminder_body'], $newpass)), $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
 
        $_SESSION['success_message'] = $aLang['text_password_sent'];
        oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
    } else {
        $_SESSION['error_search_msg'] = $aLang['text_no_email_address_found'];
        oos_redirect(oos_href_link($aContents['password_forgotten'], '', 'SSL'));
    }
} else {

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['login'], '', 'SSL'));
    $oBreadcrumb->add($aLang['navbar_title_2']);
    $sCanonical = oos_href_link($aContents['password_forgotten'], '', 'SSL', FALSE, TRUE);
    $sPagetitle = $aLang['heading_title'];
    
    $aTemplate['page'] = $sTheme . '/page/user_password_forgotten.tpl';

    $nPageType = OOS_PAGE_TYPE_SERVICE;

    require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
    if (!isset($option)) {
        require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
        require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'heading_title' => $aLang['heading_title'],

            'pagetitle'         => htmlspecialchars($sPagetitle),
            'canonical'         => $sCanonical,
        )
    );

    // display the template
    $smarty->display($aTemplate['page']);

}
