<?php
/* ----------------------------------------------------------------------
   $Id: login.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );
 
  $_SESSION['navigation']->remove_current_page();
echo 'jeep';
exit;  
  require 'includes/languages/' . $sLanguage . '/user_login.php';
  if (isset($_GET['action']) && ($_GET['action'] == 'process')) {

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

        $cookie_url_array = parse_url((ENABLE_SSL == true ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . substr(OOS_SHOP, 0, -1));
        $cookie_path = $cookie_url_array['path'];

        $date_now = date('Ymd');
        $customers_infotable = $oostable['customers_info'];
        $dbconn->Execute("UPDATE $customers_infotable
                          SET customers_info_date_of_last_logon = now(),
                              customers_info_number_of_logons = customers_info_number_of_logons+1
                          WHERE customers_info_id = '" . intval($_SESSION['customer_id']) . "'");

        // restore cart contents
        $_SESSION['cart']->restore_contents();

        if (count($_SESSION['navigation']->snapshot) > 0) {
          $origin_href = oos_href_link($_SESSION['navigation']->snapshot['modules'], $_SESSION['navigation']->snapshot['file'], $_SESSION['navigation']->snapshot['get'], $_SESSION['navigation']->snapshot['mode']);
          $_SESSION['navigation']->clear_snapshot();
          $_SESSION['navigation']->remove_last_page();
          oos_redirect($origin_href);
        } else {
          if (ENABLE_SSL == 'true') { 
            oos_redirect(oos_href_link($aModules['user'], $aFilename['account'], '', 'SSL'));
          } else {
            oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
          }
        }
      }
    }
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));

  $info_message = '';
  if (isset($_GET['login']) && ($_GET['login'] == 'fail')) {
    $info_message = $aLang['text_login_error'];
  } elseif ($_SESSION['cart']->count_contents()) {
    $info_message = $aLang['text_visitors_cart'];
  }

  $aOption['template_main'] = $sTheme . '/modules/user_login.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_SERVICE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'login.gif',

          'popup_window' => 'popup_window.js',
          'info_message' => $info_message
      )
  );

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>