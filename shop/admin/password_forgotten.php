<?php
/* ----------------------------------------------------------------------
   $Id: password_forgotten.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.17 2003/02/14 12:57:29 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/oos_main.php';

if ( (isset($_POST['action']) && ($_POST['action'] == 'process')) && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ) {

    $email_address = oos_prepare_input($_POST['email_address']);
	$firstname = oos_prepare_input($_POST['firstname']);
	
    if ( empty( $email_address ) || !is_string( $email_address ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }

	if ( empty( $firstname ) || !is_string( $firstname ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }	

// Check if email exists
    $admintable = $oostable['admin'];
	$sql = "SELECT admin_id, admin_firstname as firstname, admin_lastname as lastname
			FROM $admintable 
			WHERE admin_email_address = '" . oos_db_input($email_address) . "'";
    $check_admin_result = $dbconn->Execute($sql);
    if (!$check_admin_result->RecordCount()) {
		$messageStack->add($aLang['text_forgotten_error'], 'error');
    } else {
		$check_admin = $check_admin_result->fields;
		if ($check_admin['firstname'] != $firstname) {
			$messageStack->add($aLang['text_forgotten_error'], 'error');
		} else {
		    $_SESSION['success_new_password'] = true; 
			$make_password = oos_create_random_value(7);
			$crypted_password = oos_encrypt_password($make_password);


			$email_text = sprintf($aLang['admin_email_text'], $check_admin['firstname'] . ' ' . $check_admin['lastname'], oos_server_get_var('REMOTE_ADDR'), STORE_NAME, $make_password);
		
			oos_mail($check_admin['firstname'] . ' ' . $check_admin['lastname'], $email_address, $aLang['admin_password_subject'], nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS); 

			$admintable = $oostable['admin'];
			$dbconn->Execute("UPDATE $admintable
							  SET admin_password = '" . oos_db_input($crypted_password) . "'
							  WHERE admin_id = '" . intval($check_admin['admin_id']) . "'");

			oos_redirect_admin(oos_href_link_admin($aFilename['login']));	
			
		}
    }
}
  
  
$aTemplate['page'] = 'default/page/password_forgotten.tpl';

require_once 'includes/oos_system.php';

$smarty->assign('body', 'login-page');
$smarty->assign('form_action', oos_draw_form('password_forgotten', $aFilename['password_forgotten']));
$smarty->assign('login', oos_href_link_admin($aFilename['login']));


// display the template
$smarty->display($aTemplate['page']);

require 'includes/oos_nice_exit.php'; 
  
