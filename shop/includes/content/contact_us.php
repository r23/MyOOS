<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: contact_us.php,v 1.39 2003/02/14 05:51:15 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// require  validation functions (right now only email address)
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_validations.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/contact_us.php'; 
  
$error = 'false';
if (isset($_GET['action']) && ($_GET['action'] == 'send')) {

	$email_address = oos_prepare_input($_POST['email']);
	
	if ( empty( $email_address ) || !is_string( $email_address ) ) {
		oos_redirect(oos_href_link($aContents['403']));
	}

	if (oos_validate_is_email(trim($email))) {
		oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $aLang['email_subject'], $enquiry, $name, $email_address);
		oos_redirect(oos_href_link($aContents['contact_us'], 'action=success'));
	} else {
		$error = 'true';
	}
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['contact_us']));
$sCanonical = oos_href_link($aContents['contact_us'], '', 'NONSSL', FALSE, TRUE);

$aTemplate['page'] = $sTheme . '/page/contact_us.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
		array(
			'breadcrumb'	=> $oBreadcrumb->trail(),
			'heading_title' => $aLang['heading_title'],
			'canonical'		=> $sCanonical,

			'error' => $error
		)
);

// display the template
$smarty->display($aTemplate['page']);
