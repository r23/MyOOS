<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_reviews_write.php,v 1.51 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (!$oEvent->installed_plugin('reviews')) {
    oos_redirect(oos_href_link($aContents['main']));
}

// start the session
if ( $session->hasStarted() === FALSE ) $session->start();  
  
if (!isset($_SESSION['customer_id'])) {
	// navigation history
	if (!isset($_SESSION['navigation'])) {
		$_SESSION['navigation'] = new oosNavigationHistory();
	}   
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], ''));
}

if (isset($_GET['products_id'])) {
	if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_GET['products_id']);
} elseif (isset($_POST['products_id'])) {
	if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_POST['products_id']);
} else {
	oos_redirect(oos_href_link($aContents['main']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/reviews_product_write.php';

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$sql = "SELECT p.products_id, pd.products_name, p.products_image
          FROM $productstable p,
               $products_descriptiontable pd
          WHERE p.products_id = '" . intval($nProductsID) . "'
            AND pd.products_id = p.products_id
            AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
            AND p.products_status >= '1'";
$product_result = $dbconn->Execute($sql);
$valid_product = ($product_result->RecordCount() > 0);
$product_info = $product_result->fields;

if ( isset($_POST['action']) && ($_POST['action'] == 'reviews-write-process') && 
	( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) && 
	( $valid_product == TRUE ) ) { 
	
		$review = oos_prepare_input($_POST['review']);
		$rating = oos_prepare_input($_POST['rating']);
		$headline = oos_prepare_input($_POST['headline']);

		$bError = FALSE;
		if (strlen($review) < REVIEW_TEXT_MIN_LENGTH) {
			$oMessage->add('product_reviews_write', $aLang['review_text']);
			$bError = TRUE;
		}

		if (!isset($_POST['rating'])) {
			$oMessage->add('product_reviews_write', $aLang['review_rating']);
			$bError = TRUE;
		}

		if (strlen($headline) < 10) {
			$oMessage->add('product_reviews_write', $aLang['review_headline']);
			$bError = TRUE;
		}
 
		if ($bError === FALSE) {

			$customerstable = $oostable['customers'];
			$sql = "SELECT customers_firstname, customers_lastname
					FROM $customerstable
					WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
			$customer_info_result = $dbconn->Execute($sql);
			$customer_info = $customer_info_result->fields;

			$firstname = ltrim($customer_info['customers_firstname']);
			$firstname = substr($firstname, 0, 1);

			$lastname = ltrim($customer_info['customers_lastname']);
			$lastname = substr($lastname, 0, 1);
			$customers_name = $firstname . '. ' . $lastname . '. ';

			$date_now = date('Ymd');
			$reviewstable  = $oostable['reviews'];
			$dbconn->Execute("INSERT INTO $reviewstable
							(products_id,
							customers_id,
							customers_name,
							reviews_rating,
							date_added) VALUES ('" . intval($nProductsID) . "',
												'" . intval($_SESSION['customer_id']) . "',
												'" . oos_db_input($customers_name) . "',
												'" . oos_db_input($rating) . "',
												now())");
			$insert_id = $dbconn->Insert_ID();
			$reviews_descriptiontable  = $oostable['reviews_description'];
			$dbconn->Execute("INSERT INTO $reviews_descriptiontable
							(reviews_id,
							reviews_languages_id,
							reviews_headline,
							reviews_text) VALUES ('" . intval($insert_id) . "',
												'" . intval($nLanguageID) . "',
												'" . oos_db_input($headline) . "',
												'" . oos_db_input($review) . "')");

			$email_subject = 'Review: ' . $product_info['products_name'];

			$email_text = "\n";
			$email_text .= "Firstname: ". $customer_values['customers_firstname'] . "\n";
			$email_text .= "Lastname:  ". $customer_values['customers_lastname'] . "\n";
			$email_text .= "E-Mail:    ". $customer_values['customers_email_address'] . "\n";
			$email_text .= "\n";
			$email_text .= "Text:         ". $review . "\n";

			oos_mail(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $email_subject, nl2br($email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

			// clear cache
			require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
			$smarty = new myOOS_Smarty();
			$smarty->clearCache(NULL, $sTheme.'|products|reviews');

			oos_redirect(oos_href_link($aContents['product_reviews'], 'products_id=' . intval($nProductsID)));

	}
}


$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['product_reviews'], 'products_id=' . intval($nProductsID)));
$sCanonical = oos_href_link($aContents['product_reviews_write'], 'products_id=' . intval($nProductsID), FALSE, TRUE);

$customerstable = $oostable['customers'];
$sql = "SELECT customers_firstname, customers_lastname
		FROM $customerstable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
$customer_info_result = $dbconn->Execute($sql);
$customer_info = $customer_info_result->fields;

$firstname = ltrim($customer_info['customers_firstname']);
$firstname = substr($firstname, 0, 1);

$lastname = ltrim($customer_info['customers_lastname']);
$lastname = substr($lastname, 0, 1);
$customers_name = $firstname . '. ' . $lastname . '. ';

$aTemplate['page'] = $sTheme . '/page/product_reviews_write.html';
$aTemplate['javascript'] = $sTheme . '/js/product_reviews_write.html';

$nPageType = OOS_PAGE_TYPE_REVIEWS;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

if ($oMessage->size('product_reviews_write') > 0) {
    $aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('product_reviews_write') );
}

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$smarty->assign(
	array(
		'breadcrumb'		=> $oBreadcrumb->trail(),
		'heading_title'		=> $aLang['heading_title'],
		'canonical'			=> $sCanonical,

		'valid_product'     => $valid_product,
		'product_info'      => $product_info,
		'customers_name'	=> $customers_name
	)
);

$smarty->assign('javascript', $smarty->fetch($aTemplate['javascript']));

// display the template
$smarty->display($aTemplate['page']);
