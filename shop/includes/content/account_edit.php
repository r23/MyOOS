<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: account_edit.php,v 1.62 2003/02/13 01:58:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );
  
// start the session
if ( $session->hasStarted() === FALSE ) $session->start();   

 
if (!isset($_SESSION['customer_id'])) {
	// navigation history
	if (!isset($_SESSION['navigation'])) {
		$_SESSION['navigation'] = new oosNavigationHistory();
	}   
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
}

  
  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_account_edit.php';

  $customerstable = $oostable['customers'];
  $address_bookstable = $oostable['address_book'];
  $sql = "SELECT c.customers_gender, c.customers_firstname, c.customers_lastname,
                 c.customers_dob, c.customers_number, c.customers_email_address,
                 c.customers_vat_id, c.customers_vat_id_status, c.customers_telephone, c.customers_fax, c.customers_newsletter,
                 a.entry_company, a.entry_owner, a.entry_street_address, a.entry_suburb,
                 a.entry_postcode, a.entry_city, a.entry_zone_id, a.entry_state, a.entry_country_id
          FROM $customerstable c,
               $address_bookstable a
          WHERE c.customers_id = '" . intval($_SESSION['customer_id']) . "'
            AND a.customers_id = c.customers_id
            AND a.address_book_id = '" . intval($_SESSION['customer_default_address_id']) . "'";
  $account = $dbconn->GetRow($sql);

  $email_address = $account['customers_email_address'];
  $number = $account['customers_number'];

  $no_edit = TRUE;
  $show_password = TRUE;

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['account'], '', 'SSL'));
  $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['account_edit'], '', 'SSL'));
  
  ob_start();
  require 'js/form_check.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $aTemplate['page'] = $sTheme . '/page/user_account_edit.html';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;
  $sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

  require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,nofollow,noodp,noydir',

		'account'           => $account,
		'email_address'     => $email_address,
		'show_password'     => $show_password

      )
  );

  // JavaScript
  $smarty->assign('oos_js', $javascript);

  $smarty->assign('newsletter_ids', array(0,1));
  $smarty->assign('newsletter', array($aLang['entry_newsletter_no'],$aLang['entry_newsletter_yes']));


  // display the template
$smarty->display($aTemplate['page']);
