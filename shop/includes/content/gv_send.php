<?php
/* ----------------------------------------------------------------------
   $Id: gv_send.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_send.php,v 1.1.2.3 2003/05/12 22:57:20 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001, 2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/gv_send.php';
  require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_http_client.php';

// if the customer is not logged on, redirect them to the login page
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (($_POST['back_x']) || ($_POST['back_y'])) {
    $action = '';
  }

  if ($action == 'send') {
    $error = 'false';
    if (!oos_validate_is_email(trim($email))) {
      $error = 'true';
      $error_email = $aLang['error_entry_email_address_check'];
    }

    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
    $sql = "SELECT amount
            FROM $coupon_gv_customertable
            WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
    $gv_result = $dbconn->Execute($sql);
    $gv_result = $gv_result->fields;
    $customer_amount = round($gv_result['amount'], $oCurrencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    $gv_amount = trim($amount);


    if (preg_match('/[^0-9/.]/', $gv_amount)) {
      $error = 'true';
      $error_amount = $aLang['error_entry_amount_check'];
    }
    $gv_amount = round($gv_amount, $oCurrencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
    if ($gv_amount>$customer_amount || $gv_amount == 0) {
      $error = 'true';
      $error_amount = $aLang['error_entry_amount_check'];
    }
  }

  if ($action == 'process') {
    $id1 = oos_create_coupon_code($mail['customers_email_address']);

    $coupon_gv_customertable = $oostable['coupon_gv_customer'];
    $sql = "SELECT amount
            FROM $coupon_gv_customertable
            WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'";
    $gv_result = $dbconn->Execute($sql);
    $gv_result = $gv_result->fields;

    $new_amount = round($gv_result['amount'], $oCurrencies->currencies[DEFAULT_CURRENCY]['decimal_places'])-$amount;
    if ($new_amount<0) {
      $error = 'true';
      $error_amount = $aLang['error_entry_amount_check'];
      $action = 'send';
    } else {
      $coupon_gv_customertable = $oostable['coupon_gv_customer'];
      $gv_result=$dbconn->Execute("UPDATE $coupon_gv_customertable
                               SET amount = '" . oos_db_input($new_amount) . "'
                               WHERE customer_id = '" . intval($_SESSION['customer_id']) . "'");


      $customerstable = $oostable['customers'];
      $sql = "SELECT customers_firstname, customers_lastname
              FROM $customerstable
              WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
      $gv_result = $dbconn->Execute($sql);
      $gv_customer = $gv_result->fields;

      $couponstable = $oostable['coupons'];
      $gv_result = $dbconn->Execute("INSERT INTO $couponstable
                                (coupon_type,
                                 coupon_code,
                                 date_created,
                                 coupon_amount) VALUES ('G',
                                                        '" . oos_db_input($id1) . "',
                                                         now(),
                                                        '" . oos_db_input($amount) . "')");
      $insert_id = $dbconn->Insert_ID();

      $coupon_email_tracktable = $oostable['coupon_email_track'];
      $gv_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable
                                (coupon_id,
                                 customer_id_sent,
                                 sent_firstname,
                                 sent_lastname,
                                 emailed_to,
                                 date_sent) VALUES ('" . intval($insert_id) . "',
                                                    '" . intval($_SESSION['customer_id']) . "',
                                                    '" . $gv_customer['customers_firstname'] . "',
                                                    '" . $gv_customer['customers_lastname'] . "',
                                                    '" . oos_db_input($email) . "',
                                                    now())");

      $gv_email = STORE_NAME . "\n" .
              $aLang['email_separator'] . "\n" .
              sprintf($aLang['email_gv_text_header'], $oCurrencies->format($amount)) . "\n" .
              $aLang['email_separator'] . "\n" .
              sprintf($aLang['email_gv_from'], $send_name) . "\n";
      if (isset($_POST['message'])) {
        $gv_email .= $aLang['email_gv_message'] . "\n";
        if (isset($to_name)) {
          $gv_email .= sprintf($aLang['email_gv_send_to'], $to_name) . "\n\n";
        }
        $gv_email .= stripslashes($message) . "\n\n";
      }
      $gv_email .= sprintf($aLang['email_gv_redeem'], $id1) . "\n\n";
      $gv_email .= $aLang['email_gv_link'] . oos_href_link($aContents['gv_redeem'], 'gv_no=' . $id1, 'NONSSL', false, false);
      $gv_email .= "\n\n";
      $gv_email .= $aLang['email_gv_fixed_footer'] . "\n\n";
      $gv_email .= $aLang['email_gv_shop_footer'] . "\n\n";
      // $gv_email_subject = sprintf($aLang['email_gv_text_subject'], $send_name);
      oos_mail('', $email, $aLang['email_subject'], nl2br($gv_email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, '');

    }
  }

  if ($action == 'send' && $error == 'false') {
    // validate entries
    $gv_amount = (double) $gv_amount;
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_firstname, customers_lastname
            FROM $customerstable
            WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $gv_result = $dbconn->Execute($sql);
    $gv = $gv_result->fields;
    $send_name = $gv['customers_firstname'] . ' ' . $gv['customers_lastname'];
  }

  $back = count($_SESSION['navigation']->path)-2;
  if (isset($_SESSION['navigation']->path[$back])) {
    $back_link = oos_href_link($_SESSION['navigation']->path[$back]['modules'], $_SESSION['navigation']->path[$back]['file'], $_SESSION['navigation']->path[$back]['get'], $_SESSION['navigation']->path[$back]['mode']);
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title']);

  $aTemplate['page'] = $sTheme . '/modules/send.tpl';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

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
          'oos_heading_image' => 'specials.gif',

          'error'             => $error,
          'gv_result'         => $gv_result,
          'send_name'         => $send_name,
          'to_name'           => $to_name,
          'email'             => $email,
          'gv_amount'         => $gv_amount,
          'message'           => $message,
          'error_email'       => $error_email,
          'error_amount'      => $error_amount,
          'back_link'         => $back_link,
          'id1'               => $id1,
          'main_message'      => sprintf($aLang['main_message'], $oCurrencies->format($amount), $to_name, $email, $to_name, $oCurrencies->format($amount), $send_name)

      )
  );

// display the template
$smarty->display($aTemplate['page']);

