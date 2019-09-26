<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/contact_us.php'; 
  
$bError = FALSE;

if ( isset($_POST['action']) && ($_POST['action'] == 'send')  ) { 
	
	$email_address = oos_prepare_input($_POST['email']);
	$name = oos_prepare_input($_POST['name']);
	$phone = oos_prepare_input($_POST['phone']);
	$subject = oos_prepare_input($_POST['subject']);
	$enquiry = oos_prepare_input($_POST['enquiry']);

	$email_address = strtolower($email_address);
		
	if (oos_validate_is_email(trim($email_address))) {

		if ( empty( $subject )) {
			$subject = $aLang['email_subject'];
		}	

		$email_text = "\n";
		$email_text .= $aLang['entry_name'] . ' ' .  $name . "\n";
		$email_text .= $aLang['entry_telephone_number'] . ' ' .  $phone . "\n";
		$email_text .= $aLang['entry_email'] . ' ' .  $email_address . "\n";
		$email_text .= "\n";
		$email_text .= $aLang['entry_enquiry']  . ' ' . $enquiry . "\n";		
		
		oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $subject, $email_text, '', $name, $email_address);
		oos_redirect(oos_href_link($aContents['contact_us'], 'action=success'));
	} else {
		$oMessage->add('contact_us', $aLang['error_email_address']);
		$bError = TRUE;
	}
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['contact_us']));
$sCanonical = oos_href_link($aContents['contact_us'], '', FALSE, TRUE);

$aTemplate['page'] = $sTheme . '/page/contact_us.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

if ($oMessage->size('contact_us') > 0) {
    $aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('contact_us') );
}

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

			'error' => $bError
		)
);

// display the template
$smarty->display($aTemplate['page']);
