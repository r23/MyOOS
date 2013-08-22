<?php
/* ----------------------------------------------------------------------
   $Id: login.php 469 2013-07-08 12:16:01Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: login.php,v 1.75 2003/02/13 03:01:49 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce

   Max Order - 2003/04/27 JOHNSON - Copyright (c) 2003 Matti Ressler - mattifinn@optusnet.com.au
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_login.php';
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {

	$email_address = oos_prepare_input($_POST['email_address']);
	$password = oos_prepare_input($_POST['password']);
	
    if ( empty( $email_address ) || !is_string( $email_address ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }

    if ( empty( $password ) || !is_string( $password ) ) {
		oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
    }
  
  
    // Check if email exists
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname,
                   customers_password, customers_wishlist_link_id, customers_language,
                   customers_vat_id_status, customers_email_address, customers_default_address_id, customers_max_order
            FROM $customerstable
            WHERE customers_login = '1'
              AND customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if (!$check_customer_result->RecordCount()) {
      $_GET['login'] = 'fail';
    } else {
      $check_customer = $check_customer_result->fields;

      // Check that password is good
      if (!oos_validate_password($password, $check_customer['customers_password'])) {
        $_GET['login'] = 'fail';
      } else {
        $address_booktable = $oostable['address_book'];
        $sql = "SELECT entry_country_id, entry_zone_id
                FROM $address_booktable
                WHERE customers_id = '" . $check_customer['customers_id'] . "'
                  AND address_book_id = '1'";
        $check_country = $dbconn->GetRow($sql);

        if ($check_customer['customers_language'] == '') {
          $customerstable = $oostable['customers'];
          $dbconn->Execute("UPDATE $customerstable
                            SET customers_language = '" . oos_db_input($sLanguage) . "'
                            WHERE customers_id = '" . intval($check_customer['customers_id']) . "'");
        }


        $_SESSION['customer_wishlist_link_id'] = $check_customer['customers_wishlist_link_id'];
        $_SESSION['customer_id'] = $check_customer['customers_id'];
        $_SESSION['customer_default_address_id'] = $check_customer['customers_default_address_id'];
        if (ACCOUNT_GENDER == 'true') $_SESSION['customer_gender'] = $check_customer['customers_gender'];
        $_SESSION['customer_first_name'] = $check_customer['customers_firstname'];
        $_SESSION['customer_lastname'] = $check_customer['customers_lastname'];
        $_SESSION['customer_max_order'] = $check_customer['customers_max_order'];
        $_SESSION['customer_country_id'] = $check_country['entry_country_id'];
        $_SESSION['customer_zone_id'] = $check_country['entry_zone_id'];
        if (ACCOUNT_VAT_ID == 'true') $_SESSION['customers_vat_id_status'] = $check_customer['customers_vat_id_status'];

        $_SESSION['member']->restore_group();

        $date_now = date('Ymd');
        $customers_infotable = $oostable['customers_info'];
        $dbconn->Execute("UPDATE $customers_infotable
                          SET customers_info_date_of_last_logon = now(),
                              customers_info_number_of_logons = customers_info_number_of_logons+1
                          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'");

        // restore cart contents
        $_SESSION['cart']->restore_contents();

        if (count($_SESSION['navigation']->snapshot) > 0) {
          $origin_href = oos_href_link($_SESSION['navigation']->snapshot['content'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
          $_SESSION['navigation']->clear_snapshot();
          oos_redirect($origin_href);
        } else {
            oos_redirect(oos_href_link($aContents['account'], '', 'SSL'));
        }
      }
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['login'], '', 'SSL'));

  $info_message = '';
  if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {
    $info_message = $aLang['text_login_error'];
  } elseif ($_SESSION['cart']->count_contents()) {
    $info_message = $aLang['text_visitors_cart'];
  }

  $aTemplate['page'] = $sTheme . '/modules/user_login.tpl';

  $nPageType = OOS_PAGE_TYPE_SERVICE;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'login.gif',
          'info_message' => $info_message
      )
  );


// display the template
$smarty->display($aTemplate['page']);
