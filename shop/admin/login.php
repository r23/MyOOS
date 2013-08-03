<?php
/* ----------------------------------------------------------------------
   $Id: login.php 437 2013-06-22 15:33:30Z r23 $

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
	$password = oos_prepare_input($_POST['password']);
	
    if ( empty( $email_address ) || !is_string( $email_address ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }

    if ( empty( $password ) || !is_string( $password ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }	

	// Check if email exists
	$check_admin_result = $dbconn->Execute("SELECT admin_id as login_id, admin_groups_id as login_groups_id, admin_firstname as login_firstname, admin_email_address as login_email_address, admin_password as login_password, admin_modified as login_modified, admin_logdate as login_logdate, admin_lognum as login_lognum FROM " . $oostable['admin'] . " WHERE admin_email_address = '" . oos_db_input($email_address) . "'");
	if (!$check_admin_result->RecordCount()) {
		$messageStack->add($aLang['text_login_error'], 'error');
	} else {
		$check_admin = $check_admin_result->fields;
		// Check that password is good
		if (!oos_validate_password($password, $check_admin['login_password'])) {
			$messageStack->add($aLang['text_login_error'], 'error');
		} else {

          $_SESSION['login_id'] = $check_admin['login_id'];
          $_SESSION['login_groups_id'] = $check_admin['login_groups_id'];
          $_SESSION['login_first_name'] = $check_admin['login_firstname'];

          //$date_now = date('Ymd');
          $dbconn->Execute("UPDATE " . $oostable['admin'] . "
                        SET admin_logdate = now(), admin_lognum = admin_lognum+1
                        WHERE admin_id = '" . intval($_SESSION['login_id']) . "'");

            oos_redirect_admin(oos_href_link_admin($aFilename['default']));

        }
	}
}

  
$aTemplate['page'] = 'default/page/login.tpl';

require_once 'includes/oos_system.php';

$smarty->assign('body', 'login-page');
$smarty->assign('form_action', oos_draw_form('login', $aFilename['login'], ''));
$smarty->assign('password_forgotten', oos_href_link_admin($aFilename['password_forgotten']));


// display the template
$smarty->display($aTemplate['page']);

require 'includes/oos_nice_exit.php'; 

