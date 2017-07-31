<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: create_account.php,v 1.59 2003/02/14 05:51:17 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account_process.php';

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
// require  validation functions (right now only email address)
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';  
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validate_vatid.php';

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_create_account_process.php';

// start the session
if ( $session->hasStarted() === FALSE ) $session->start();

if ( $_SESSION['login_count'] > 3) {
	oos_redirect(oos_href_link($aContents['403']));
}

if ( isset($_POST['action']) && ($_POST['action'] == 'process') && 
	( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) ){








	if (NEWSLETTER == 'true') {
		if ( isset($_POST['newsletter']) && ($_POST['newsletter'] == 'subscriber') ) {
			oos_newsletter_subscribe_mail($email_address);
		}
	}

}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['create_account']));
$sCanonical = oos_href_link($aContents['create_account'], '', 'SSL', FALSE, TRUE);

$snapshot = count($_SESSION['navigation']->snapshot);


if (isset($_GET['email_address'])) {
	$email_address = oos_db_prepare_input($_GET['email_address']);
}
$account['entry_country_id'] = STORE_COUNTRY;

ob_start();
require 'js/form_check.js.php';
$javascript = ob_get_contents();
ob_end_clean();

$aTemplate['page'] = $sTheme . '/page/user_create_account.html';
$aTemplate['javascript'] = $sTheme . '/js/create_account.html';

$nPageType = OOS_PAGE_TYPE_ACCOUNT;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$bRead = 'false';
$smarty->assign('read', $bRead); 
$smarty->assign('oos_js', $javascript);

// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'	=> $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,follow,noodp,noydir',
		'canonical'		=> $sCanonical
	)
);

$smarty->assign('account', $account);
$smarty->assign('email_address', $email_address);

if (CUSTOMER_NOT_LOGIN == 'true') {
	$bShowPassword = FALSE;
} else {
	$bShowPassword = TRUE;
}
$smarty->assign('$bShowPassword', $bShowPassword);

$smarty->assign('snapshot', $snapshot);
$smarty->assign('login_orgin_text', sprintf($aLang['text_origin_login'], oos_href_link($aContents['login'], '', 'SSL')));

$smarty->assign('newsletter_ids', array(0,1));
$smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));

$smarty->assign('javascript', $smarty->fetch($aTemplate['javascript']));

// display the template
$smarty->display($aTemplate['page']);
