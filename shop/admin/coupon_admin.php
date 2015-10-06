<?php
/* ----------------------------------------------------------------------
   $Id: coupon_admin.php,v 1.2 2007/11/15 20:24:55 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: coupon_admin.php,v 1.1.2.24 2003/05/10 21:45:20 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

// define our coupon functions
  require 'includes/functions/function_coupon.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  if (isset($_GET['selected_box'])) {
    $_GET['action'] = '';
    $_GET['old_action'] = '';
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : ''); 

  if (($action == 'send_email_to_user') && ($_POST['customers_email_address']) && (!$_POST['back_x'])) {
    switch ($_POST['customers_email_address']) {
    case '***':
      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address 
                                   FROM" . $oostable['customers']);
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;

    case '**D':
      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address 
                                  FROM " . $oostable['customers'] . " 
                                  WHERE customers_newsletter = '1'");
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;

    default:
      $customers_email_address = oos_db_prepare_input($_POST['customers_email_address']);

      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address 
                                  FROM " . $oostable['customers'] . " 
                                  WHERE customers_email_address = '" . oos_db_input($customers_email_address) . "'");
      $mail_sent_to = $_POST['customers_email_address'];
      break;
    }
    $coupon_result = $dbconn->Execute("SELECT coupon_code 
                                  FROM " . $oostable['coupons'] . " 
                                  WHERE coupon_id = '" . $_GET['cID'] . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name 
                                       FROM " . $oostable['coupons_description'] . " 
                                       WHERE coupon_id = '" . $_GET['cID'] . "' AND
                                             coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $coupon_name = $coupon_name_result->fields;

    // Instantiate a new mail object
    $send_mail = new PHPMailer();

    $send_mail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');
    $send_mail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );

    $send_mail->CharSet = CHARSET;
    $send_mail->IsMail();

    $send_mail->From = $from_mail ? $from_mail : STORE_OWNER_EMAIL_ADDRESS;
    $send_mail->FromName = $from_name ? $from_name : STORE_OWNER;
    $send_mail->Mailer = EMAIL_TRANSPORT;

    // Add smtp values if needed
    if ( EMAIL_TRANSPORT == 'smtp' ) {
      $send_mail->IsSMTP(); // set mailer to use SMTP
      $send_mail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
      $send_mail->Username = OOS_SMTPUSER; // SMTP username
      $send_mail->Password = OOS_SMTPPASS; // SMTP password
      $send_mail->Host     = OOS_SMTPHOST; // specify main and backup server
    } else
      // Set sendmail path
      if ( EMAIL_TRANSPORT == 'sendmail' ) {
        if (!oos_empty(OOS_SENDMAIL)) {
          $send_mail->Sendmail = OOS_SENDMAIL;
          $send_mail->IsSendmail();
        }
    }

    $send_mail->Subject = $subject;

    while ($mail = $mail_result->fields) {
      $message = $message;
      $message .= "\n\n" . TEXT_TO_REDEEM . "\n\n";
      $message .= TEXT_VOUCHER_IS . $coupon_result['coupon_code'] . "\n\n";
      $message .= TEXT_REMEMBER . "\n\n";
      $message .= TEXT_VISIT . "\n\n";

      $send_mail->Body = $message;
      $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
      $send_mail->Send();
      $send_mail->ClearAddresses();
      $send_mail->ClearAttachments();

      // Move that ADOdb pointer!
      $mail_result->MoveNext();
    }
    // Close result set
    $mail_result->Close();

    oos_redirect_admin(oos_href_link_admin($aContents['coupon_admin'], 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview_email') && (!$_POST['customers_email_address']) ) {
    $action = 'email';
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if (isset($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
  }

  if (!empty($action)) {
    switch ($action) {
      case 'confirmdelete':
        $delete_result=$dbconn->Execute("UPDATE " . $oostable['coupons'] . " SET coupon_active = 'N' WHERE coupon_id='".$_GET['cID']."'");
        break;

      case 'update':
        // get all HTTP_POST_VARS and validate
        $_POST['coupon_code'] = trim($_POST['coupon_code']);
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $lang_id = $languages[$i]['id'];
          $_POST['coupon_name'][$iso_639_2] = trim($_POST['coupon_name'][$lang_id]);
          $_POST['coupon_desc'][$iso_639_2] = trim($_POST['coupon_desc'][$lang_id]);
        }
        $_POST['coupon_amount'] = trim($_POST['coupon_amount']);
        $update_errors = 0;
        if (!$_POST['coupon_name']) {
          $update_errors = 1;
          $messageStack->add(ERROR_NO_COUPON_NAME, 'error');
        }
        if ((!$_POST['coupon_amount']) && (!$_POST['coupon_free_ship'])) {
          $update_errors = 1;
          $messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
        }
        if (!$_POST['coupon_code']) {
          $coupon_code = oos_create_coupon_code(); 
        }
        if ($_POST['coupon_code']) $coupon_code = $_POST['coupon_code'];
        $query1 = $dbconn->Execute("SELECT coupon_code
                                FROM " . $oostable['coupons'] . "
                                WHERE coupon_code = '" . oos_db_prepare_input($coupon_code) . "'");
        if ($query1->RecordCount() && $_POST['coupon_code'] && $_GET['oldaction'] != 'voucheredit')  {
          $update_errors = 1;
          $messageStack->add(ERROR_COUPON_EXISTS, 'error');
        }
        if ($update_errors != 0) {
          $action = 'new';
        } else {
          $action = 'update_preview';
        }
        break;

      case 'update_confirm':
        if ( ($_POST['back_x']) || ($_POST['back_y']) ) {
          $action = 'new';
        } else {
          $coupon_type = "F";
          if (substr($_POST['coupon_amount'], -1) == '%') $coupon_type='P';
          if ($_POST['coupon_free_ship']) $coupon_type = 'S';
          $sql_data_array = array('coupon_code' => oos_db_prepare_input($_POST['coupon_code']),
                                  'coupon_amount' => oos_db_prepare_input($_POST['coupon_amount']),
                                  'coupon_type' => oos_db_prepare_input($coupon_type),
                                  'uses_per_coupon' => oos_db_prepare_input($_POST['coupon_uses_coupon']),
                                  'uses_per_user' => oos_db_prepare_input($_POST['coupon_uses_user']),
                                  'coupon_minimum_order' => oos_db_prepare_input($_POST['coupon_min_order']),
                                  'restrict_to_products' => oos_db_prepare_input($_POST['coupon_products']),
                                  'restrict_to_categories' => oos_db_prepare_input($_POST['coupon_categories']),
                                  'coupon_start_date' => $_POST['coupon_startdate'],
                                  'coupon_expire_date' => $_POST['coupon_finishdate'],
                                  'date_created' => 'now()',
                                  'date_modified' => 'now()');
          $languages = oos_get_languages();
          for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $lang_id = $languages[$i]['id'];
            $sql_data_marray[$i] = array('coupon_name' => oos_db_prepare_input($_POST['coupon_name'][$lang_id]),
                                         'coupon_description' => oos_db_prepare_input($_POST['coupon_desc'][$lang_id])
                                   );
          }
          if (isset($_GET['oldaction']) && ($_GET['oldaction'] == 'voucheredit')) {
            oos_db_perform($oostable['coupons'], $sql_data_array, 'update', "coupon_id='" . $_GET['cID']."'"); 
            for ($i = 0, $n = count($languages); $i < $n; $i++) {
              $lang_id = $languages[$i]['id'];
              $update = $dbconn->Execute("UPDATE " . $oostable['coupons_description'] . " SET coupon_name = '" . oos_db_prepare_input($_POST['coupon_name'][$lang_id]) . "', coupon_description = '" . oos_db_prepare_input($_POST['coupon_desc'][$lang_id]) . "' WHERE coupon_id = '" . $_GET['cID'] . "' and coupon_languages_id = '" . intval($lang_id) . "'");
            }
          } else {
            $query = oos_db_perform($oostable['coupons'], $sql_data_array);
            $insert_id = $dbconn->Insert_ID();

            for ($i = 0, $n = count($languages); $i < $n; $i++) {
              $lang_id = $languages[$i]['id'];
              $sql_data_marray[$i]['coupon_id'] = $insert_id;
              $sql_data_marray[$i]['coupon_languages_id'] = $lang_id;
              oos_db_perform($oostable['coupons_description'], $sql_data_marray[$i]);
            }
          }
        }
    }
  }
  require 'includes/header.php';
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="white-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">
					
<!-- body_text //-->
<?php 
  switch ($action) {
  case 'voucherreport':
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo CUSTOMER_NAME; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo IP_ADDRESS; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo REDEEM_DATE; ?></td>	
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $cc_result_raw = "SELECT * 
                      FROM " . $oostable['coupon_redeem_track'] . " 
                     WHERE coupon_id = '" . $_GET['cID'] . "'";
    $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_result_raw, $cc_result_numrows);
    $cc_result = $dbconn->Execute($cc_result_raw);
    while ($cc_list = $cc_result->fields) {
      $rows++;
      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
      if ((!isset($_GET['uid']) || (isset($_GET['uid']) && ($_GET['uid'] == $cc_list['unique_id']))) && !isset($cInfo)) {
        $cInfo = new objectInfo($cc_list);
      }
      if (isset($cInfo) && is_object($cInfo) && ($cc_list['unique_id'] == $cInfo->unique_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin('coupon_admin.php', oos_get_all_get_params(array('cID', 'action', 'uid')) . 'cID=' . $cInfo->coupon_id . '&action=voucherreport&uid=' . $cinfo->unique_id) . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin('coupon_admin.php', oos_get_all_get_params(array('cID', 'action', 'uid')) . 'cID=' . $cc_list['coupon_id'] . '&action=voucherreport&uid=' . $cc_list['unique_id']) . '\'">' . "\n";
      }
      $customer_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname 
                                      FROM " . $oostable['customers'] . "
                                      WHERE customers_id = '" . $cc_list['customer_id'] . "'");
      $customer = $customer_result->fields;
?>
                <td class="dataTableContent"><?php echo $cc_list['customer_id']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $customer['customers_firstname'] . ' ' . $customer['customers_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $cc_list['redeem_ip']; ?></td>
                <td class="dataTableContent" align="center"><?php echo oos_date_short($cc_list['redeem_date']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($cc_list['unique_id'] == $cInfo->unique_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'page=' . $_GET['page'] . '&cID=' . $cc_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $cc_result->MoveNext();
    }

    // Close result set
    $cc_result->Close();
?>


             </table></td>
<?php
    $heading = array();
    $contents = array();
      $coupon_description_result = $dbconn->Execute("SELECT coupon_name 
                                                FROM " . $oostable['coupons_description'] . " 
                                                WHERE coupon_id = '" . $_GET['cID'] . "' AND
                                                    coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $coupon_desc = $coupon_description_result->fields;
      $count_customers = $dbconn->Execute("SELECT * 
                                       FROM " . $oostable['coupon_redeem_track'] . " 
                                       WHERE coupon_id = '" . $_GET['cID'] . "' AND
                                             customer_id = '" . $cInfo->customer_id . "'");
      $heading[] = array('text' => '<b>[' . $_GET['cID'] . ']' . COUPON_NAME . ' ' . $coupon_desc['coupon_name'] . '</b>');
      $contents[] = array('text' => '<b>' . TEXT_REDEMPTIONS . '</b>');
    #  $contents[] = array('text' => TEXT_REDEMPTIONS_TOTAL . '=' . $cc_result->RecordCount();
    #  $contents[] = array('text' => TEXT_REDEMPTIONS_CUSTOMER . '=' . $count_customers->RecordCount();
      $contents[] = array('text' => '');
?>
    <td width="25%" valign="top">
<?php
      $box = new box;
      echo $box->infoBox($heading, $contents);
      echo '            </td>' . "\n";
?>
<?php
    break;
  case 'preview_email': 
    $coupon_result = $dbconn->Execute("SELECT coupon_code 
                                  FROM " .$oostable['coupons'] . "
                                  WHERE coupon_id = '" . $_GET['cID'] . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name 
                                            FROM " . $oostable['coupons_description'] . "
                                            WHERE coupon_id = '" . $_GET['cID'] . "' AND
                                                  coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $coupon_name = $coupon_name_result->fields;
    switch ($_POST['customers_email_address']) {
    case '***':
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;
    case '**D':
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
    default:
      $mail_sent_to = $_POST['customers_email_address'];
      break;
    }
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
          <tr><?php echo oos_draw_form('mail', $aContents['coupon_admin'], 'action=send_email_to_user&cID=' . $_GET['cID']); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br /><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br /><?php echo $coupon_name['coupon_name']; ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM_NAME; ?></b><br /><?php echo htmlspecialchars(stripslashes($_POST['from_name'])); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM_MAIL; ?></b><br /><?php echo htmlspecialchars(stripslashes($_POST['from_mail'])); ?></td>
              </tr> 
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br /><?php echo htmlspecialchars(stripslashes($_POST['subject'])); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br /><?php echo nl2br(htmlspecialchars(stripslashes($_POST['message']))); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($_POST);
    while (list($key, $value) = each($_POST)) {
      if (!is_array($_POST[$key])) {
        echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php ?>&nbsp;</td>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a> ' . oos_submit_button('send_mail', IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
<?php 
    break;
  case 'email':
    $coupon_result = $dbconn->Execute("SELECT coupon_code
                                  FROM " . $oostable['coupons'] . "
                                  WHERE coupon_id = '" . $_GET['cID'] . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name
                                       FROM " . $oostable['coupons_description'] . "
                                       WHERE coupon_id = '" . $_GET['cID'] . "' AND
                                             coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $coupon_name = $coupon_name_result->fields;
?>
      <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>

          <tr><?php echo oos_draw_form('mail', $aContents['coupon_admin'], 'action=preview_email&cID='. $_GET['cID']); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_result = $dbconn->Execute("SELECT customers_email_address, customers_firstname, customers_lastname 
                                FROM " . $oostable['customers'] . " 
                                ORDER BY customers_lastname");
    while($customers_values = $mail_result->fields) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');

      // Move that ADOdb pointer!
      $mail_result->MoveNext();
    }

    // Close result set
    $mail_result->Close();
?>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_COUPON; ?>&nbsp;&nbsp;</td>
                <td><?php echo $coupon_name['coupon_name']; ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?>&nbsp;&nbsp;</td>
                <td><?php echo oos_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FROM_NAME; ?></td>
                <td><?php echo oos_draw_input_field('from_name', STORE_OWNER); ?></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_FROM_MAIL; ?></td>
                <td><?php echo oos_draw_input_field('from_mail',STORE_OWNER_EMAIL_ADDRESS); ?></td></td>
              </tr>

              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td class="main"><?php echo TEXT_SUBJECT; ?>&nbsp;&nbsp;</td>
                <td><?php echo oos_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?>&nbsp;&nbsp;</td>
                <td><?php echo oos_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php echo oos_submit_button('send_mail', IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table></td>
          </form></tr>

      </tr>
      </td>
<?php
    break;
  case 'update_preview':
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php echo oos_draw_form('coupon', 'coupon_admin.php', 'action=update_confirm&oldaction=' . $_GET['oldaction'] . '&cID=' . $_GET['cID']); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
           $lang_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_NAME; ?></td>
        <td align="left"><?php echo $_POST['coupon_name'][$lang_id]; ?></td>
      </tr>
<?php
}
?>
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $lang_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left"><?php echo COUPON_DESC; ?></td>
        <td align="left"><?php echo $_POST['coupon_desc'][$lang_id]; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo $_POST['coupon_amount']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo $_POST['coupon_min_order']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_FREE_SHIP; ?></td>
<?php
    if (isset($_POST['coupon_free_ship'])) {
?>
        <td align="left"><?php echo TEXT_FREE_SHIPPING; ?></td>
<?php
    } else {
?>
        <td align="left"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
<?php
    }
?>
      </tr>
      <tr>
        <td align="left"><?php echo COUPON_CODE; ?></td>
<?php
    if (isset($_POST['coupon_code'])) {
      $c_code = $_POST['coupon_code'];
    } else {
      $c_code = $coupon_code;
    }
?>
        <td align="left"><?php echo $coupon_code; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_coupon']; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo $_POST['coupon_uses_user']; ?></td>
      </tr>

       <tr>
        <td align="left"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo $_POST['coupon_products']; ?></td>
      </tr>


      <tr>
        <td align="left"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo $_POST['coupon_categories']; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo COUPON_STARTDATE; ?></td>
<?php
    $start_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $start_date; ?></td>
      </tr>

      <tr>
        <td align="left"><?php echo COUPON_FINISHDATE; ?></td>
<?php
    $finish_date = date(DATE_FORMAT, mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] ));
    echo date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] ));
?>
        <td align="left"><?php echo $finish_date; ?></td>
      </tr>
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $lang_id = $languages[$i]['id'];
          echo oos_draw_hidden_field('coupon_name[' . $languages[$i]['id'] . ']', $_POST['coupon_name'][$lang_id]);
          echo oos_draw_hidden_field('coupon_desc[' . $languages[$i]['id'] . ']', $_POST['coupon_desc'][$lang_id]);
       }
    echo oos_draw_hidden_field('coupon_amount', $_POST['coupon_amount']);
    echo oos_draw_hidden_field('coupon_min_order', $_POST['coupon_min_order']);
    echo oos_draw_hidden_field('coupon_free_ship', $_POST['coupon_free_ship']);
    echo oos_draw_hidden_field('coupon_code', $c_code);
    echo oos_draw_hidden_field('coupon_uses_coupon', $_POST['coupon_uses_coupon']);
    echo oos_draw_hidden_field('coupon_uses_user', $_POST['coupon_uses_user']);
    echo oos_draw_hidden_field('coupon_products', $_POST['coupon_products']);
    echo oos_draw_hidden_field('coupon_categories', $_POST['coupon_categories']);
    echo oos_draw_hidden_field('coupon_startdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'],$_POST['coupon_startdate_day'] ,$_POST['coupon_startdate_year'] )));
    echo oos_draw_hidden_field('coupon_finishdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'],$_POST['coupon_finishdate_day'] ,$_POST['coupon_finishdate_year'] )));
?>
     <tr>
        <td align="left"><?php echo oos_submit_button('confirm', COUPON_BUTTON_CONFIRM); ?></td>
        <td align="left"><?php echo oos_submit_button('back', COUPON_BUTTON_BACK, 'name=back'); ?></td>
      </td>
      </tr>

      </td></table></form>
      </tr>

      </table></td>
<?php

    break;
  case 'voucheredit':
  // warum? 
    $languages = oos_get_languages();
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
      $lang_id = $languages[$i]['id'];
      $coupon_result = $dbconn->Execute("SELECT coupon_name,coupon_description 
                                    FROM " . $oostable['coupons_description'] . "
                                    WHERE coupon_id = '" .  $_GET['cID'] . "' AND
                                          coupon_languages_id = '" . intval($lang_id) . "'");
      $coupon = $coupon_result->fields;
      $coupon_name[$lang_id] = $coupon['coupon_name'];
      $coupon_desc[$lang_id] = $coupon['coupon_description'];
    }
    $coupon_result = $dbconn->Execute("SELECT coupon_code, coupon_amount, coupon_type, coupon_minimum_order, coupon_start_date, 
                                          coupon_expire_date, uses_per_coupon, uses_per_user, restrict_to_products, 
                                          restrict_to_categories 
                                   FROM " . $oostable['coupons'] . " 
                                   WHERE coupon_id = '" . $_GET['cID'] . "'");
    $coupon = $coupon_result->fields;
    $coupon_amount = $coupon['coupon_amount'];
    if ($coupon['coupon_type']=='P') {
      $coupon_amount .= '%';
    }
    if ($coupon['coupon_type']=='S') {
      $coupon_free_ship .= true;
    }
    $coupon_min_order = $coupon['coupon_minimum_order'];
    $coupon_code = $coupon['coupon_code'];
    $coupon_uses_coupon = $coupon['uses_per_coupon'];
    $coupon_uses_user = $coupon['uses_per_user'];
    $coupon_products = $coupon['restrict_to_products'];
    $coupon_categories = $coupon['restrict_to_categories'];
  case 'new':
// set some defaults
    if (!$coupon_uses_user) $coupon_uses_user=1;
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
      <td>
<?php 
    echo oos_draw_form('coupon', 'coupon_admin.php', 'action=update&oldaction='.$action . '&cID=' . $_GET['cID']); 
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $lang_id = $languages[$i]['id'];
?>
      <tr>
        <td align="left" class="main"><?php if ($i==0) echo COUPON_NAME; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', $coupon_name[$lang_id]) . '&nbsp;' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?></td>
        <td align="left" class="main" width="40%"><?php if ($i==0) echo COUPON_NAME_HELP; ?></td>
      </tr>
<?php
}
?>
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $lang_id = $languages[$i]['id'];
?>

      <tr>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC; ?></td>
        <td align="left" valign="top"><?php echo oos_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']','physical','24','3', $coupon_desc[$lang_id]) . '&nbsp;' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?></td>
        <td align="left" valign="top" class="main"><?php if ($i==0) echo COUPON_DESC_HELP; ?></td>
      </tr>
<?php
}
?>
      <tr>
        <td align="left" class="main"><?php echo COUPON_AMOUNT; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_amount', $coupon_amount); ?></td>
        <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_min_order', $coupon_min_order); ?></td>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP; ?></td>
        <td align="left"><?php echo oos_draw_checkbox_field('coupon_free_ship', $coupon_free_ship); ?></td>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CODE; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_code', $coupon_code); ?></td>
        <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_uses_coupon', $coupon_uses_coupon); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_USER; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_uses_user', $coupon_uses_user); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
      </tr>
       <tr>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_products', $coupon_products); ?> <a href="<?php echo oos_href_link_admin($aContents['validproducts']); ?>" TARGET="_blank" ONCLICK="window.open('<?php echo oos_href_link_admin($aContents['validproducts']); ?>', 'Valid_Products', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES; ?></td>
        <td align="left"><?php echo oos_draw_input_field('coupon_categories', $coupon_categories); ?> <a href="<?php echo oos_href_link_admin($aContents['validcategories']); ?>" TARGET="_blank" ONCLICK="window.open('<?php echo oos_href_link_admin($aContents['validcategories']); ?>', 'Valid_Categories', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</A></td>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES_HELP; ?></td>
      </tr>
      <tr>
<?php
    if (!$_POST['coupon_startdate']) {
      $coupon_startdate = preg_split("/[-]/", date('Y-m-d'));
    } else {
      $coupon_startdate = preg_split("/[-]/", $_POST['coupon_startdate']);
    }
    if (!$_POST['coupon_finishdate']) {
      $coupon_finishdate = preg_split("/[-]/", date('Y-m-d'));
      $coupon_finishdate[0] = $coupon_finishdate[0] + 1;
    } else {
      $coupon_finishdate = preg_split("/[-]/", $_POST['coupon_finishdate']);
    }
?>
        <td align="left" class="main"><?php echo COUPON_STARTDATE; ?></td>
        <td align="left"><?php echo oos_draw_date_selector('coupon_startdate', mktime(0,0,0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0], 0)); ?></td>
        <td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE; ?></td>
        <td align="left"><?php echo oos_draw_date_selector('coupon_finishdate', mktime(0,0,0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0], 0)); ?></td>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left"><?php echo oos_submit_button('preview', COUPON_BUTTON_PREVIEW); ?></td>
        <td align="left"><?php echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin('coupon_admin.php', ''); ?>"><?php echo oos_button('cancel', BUTTON_CANCEL); ?></a>
      </td>
      </tr>
      </td></table></form>
      </tr>

      </table></td>
<?php
    break;
  default:
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="main"><?php echo oos_draw_form('status', $aContents['coupon_admin'], '', 'get'); ?>
<?php
    $status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
    $status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
    $status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);

    if (isset($_GET['status'])) {
      $status = oos_db_prepare_input($_GET['status']);
    } else {
      $status = 'Y';
    }
    echo HEADING_TITLE_STATUS . ' ' . oos_draw_pull_down_menu('status', $status_array, $status, 'onChange="this.form.submit();"'); 
?>
              </form>
           </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo COUPON_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_AMOUNT; ?></td>	
                <td class="dataTableHeadingContent" align="center"><?php echo COUPON_CODE; ?></td>	
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * 20 - 20;
    if ($status != '*') {
      $cc_result_raw = "SELECT 
                           coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date,
                           coupon_expire_date, uses_per_user, uses_per_coupon, restrict_to_products, 
                           restrict_to_categories, date_created,date_modified 
                       FROM 
                           " . $oostable['coupons'] ." 
                       WHERE 
                           coupon_active='" . oos_db_input($status) . "' AND
                           coupon_type != 'G'";
    } else {
      $cc_result_raw = "SELECT 
                           coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date,
                           coupon_expire_date, uses_per_user, uses_per_coupon, restrict_to_products, 
                           restrict_to_categories, date_created,date_modified 
                       FROM 
                           " . $oostable['coupons'] . " 
                       WHERE 
                           coupon_type != 'G'";
    }
    $cc_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $cc_result_raw, $cc_result_numrows);
    $cc_result = $dbconn->Execute($cc_result_raw);
    while ($cc_list = $cc_result->fields) {
      $rows++;
      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $cc_list['coupon_id']))) && !isset($cInfo)) {
        $cInfo = new objectInfo($cc_list);
      }
      if (isset($cInfo) && is_object($cInfo) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin('coupon_admin.php', oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin('coupon_admin.php', oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cc_list['coupon_id']) . '\'">' . "\n";
      }
      $coupon_description_result = $dbconn->Execute("SELECT coupon_name 
                                                 FROM " . $oostable['coupons_description'] . " 
                                                 WHERE coupon_id = '" . $cc_list['coupon_id'] . "' 
                                                   AND coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $coupon_desc = $coupon_description_result->fields;
?>
                <td class="dataTableContent"><?php echo $coupon_desc['coupon_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php  
      if ($cc_list['coupon_type'] == 'P') {
        echo $cc_list['coupon_amount'] . '%';
      } elseif ($cc_list['coupon_type'] == 'S') {
        echo TEXT_FREE_SHIPPING;
      } else {
        echo $currencies->format($cc_list['coupon_amount']);
      }
?>
            &nbsp;</td>
                <td class="dataTableContent" align="center"><?php echo $cc_list['coupon_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($cc_list['coupon_id'] == $cInfo->coupon_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'page=' . $_GET['page'] . '&cID=' . $cc_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $cc_result->MoveNext();
    }

    // Close result set
    $cc_result->Close();
?>
          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText">&nbsp;<?php echo $cc_split->display_count($cc_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_COUPONS); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo $cc_split->display_links($cc_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>

              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . oos_href_link_admin('coupon_admin.php', 'page=' . $_GET['page'] . '&cID=' . $cInfo->coupon_id . '&action=new') . '">' . oos_button('insert', BUTTON_INSERT) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>

<?php

    $heading = array();
    $contents = array();

    switch ($action) {
    case 'release':
      break;

    case 'voucherreport':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_COUPON_REPORT . '</b>');
      $contents[] = array('text' => TEXT_NEW_INTRO);
      break;

    case 'neww':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br />' . COUPON_NAME . '<br />' . oos_draw_input_field('name'));
      $contents[] = array('text' => '<br />' . COUPON_AMOUNT . '<br />' . oos_draw_input_field('voucher_amount'));
      $contents[] = array('text' => '<br />' . COUPON_CODE . '<br />' . oos_draw_input_field('voucher_code'));
      $contents[] = array('text' => '<br />' . COUPON_USES_COUPON . '<br />' . oos_draw_input_field('voucher_number_of'));
      break;

    default:
      $heading[] = array('text'=>'['.$cInfo->coupon_id.']  '.$cInfo->coupon_code);
      $amount = $cInfo->coupon_amount;
      if ($cInfo->coupon_type == 'P') {
        $amount .= '%';
      } else {
        $amount = $currencies->format($amount);
      }
      if ($action == 'voucherdelete') {
        $contents[] = array('text'=> TEXT_CONFIRM_DELETE . '</br></br>' . 
                '<a href="' . oos_href_link_admin('coupon_admin.php','action=confirmdelete&cID='.$_GET['cID'],'NONSSL').'">'.oos_button('confirm','Confirm Delete Voucher').'</a>' .
                '<a href="' . oos_href_link_admin('coupon_admin.php','cID='.$cInfo->coupon_id,'NONSSL').'">'.oos_button('cancel', BUTTON_CANCEL).'</a>'
                );
      } else { 
        $prod_details = NONE;
        if ($cInfo->restrict_to_products) {
          $prod_details = '<a href="' . oos_href_link_admin($aContents['listproducts'], 'cID=' . $cInfo->coupon_id) . '" TARGET="_blank" ONCLICK="window.open(\'' . $aContents['listproducts'] . '?cID=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
        }
        $cat_details = NONE;
        if ($cInfo->restrict_to_categories) {
          $cat_details = '<a href="' . oos_href_link_admin($aContents['listcategories'], 'cID=' . $cInfo->coupon_id) . '" TARGET="_blank" ONCLICK="window.open(\'' . $aContents['listcategories'] . '?cID=' . $cInfo->coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</A>';
        }
        $coupon_name_result = $dbconn->Execute("SELECT coupon_name
                                           FROM " . $oostable['coupons_description'] . " 
                                           WHERE coupon_id = '" . $cInfo->coupon_id . "' AND
                                                 coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $coupon_name = $coupon_name_result->fields;
        $contents[] = array('text'=>COUPON_NAME . '&nbsp;::&nbsp; ' . $coupon_name['coupon_name'] . '<br />' .
                     COUPON_AMOUNT . '&nbsp;::&nbsp; ' . $amount . '<br />' .
                     COUPON_STARTDATE . '&nbsp;::&nbsp; ' . oos_date_short($cInfo->coupon_start_date) . '<br />' .
                     COUPON_FINISHDATE . '&nbsp;::&nbsp; ' . oos_date_short($cInfo->coupon_expire_date) . '<br />' .
                     COUPON_USES_COUPON . '&nbsp;::&nbsp; ' . $cInfo->uses_per_coupon . '<br />' .
                     COUPON_USES_USER . '&nbsp;::&nbsp; ' . $cInfo->uses_per_user . '<br />' .
                     COUPON_PRODUCTS . '&nbsp;::&nbsp; ' . $prod_details . '<br />' .
                     COUPON_CATEGORIES . '&nbsp;::&nbsp; ' . $cat_details . '<br />' .
                     DATE_CREATED . '&nbsp;::&nbsp; ' . oos_date_short($cInfo->date_created) . '<br />' .
                     DATE_MODIFIED . '&nbsp;::&nbsp; ' . oos_date_short($cInfo->date_modified) . '<br /><br />' .
                     '<center><a href="' . oos_href_link_admin('coupon_admin.php','action=email&cID='.$cInfo->coupon_id,'NONSSL').'">'.oos_button('email', 'Email Voucher').'</a>' .
                     '<a href="' . oos_href_link_admin('coupon_admin.php','action=voucheredit&cID='.$cInfo->coupon_id,'NONSSL').'">'.oos_button('edit', 'Edit Voucher').'</a>' .
                     '<a href="' . oos_href_link_admin('coupon_admin.php','action=voucherdelete&cID='.$cInfo->coupon_id,'NONSSL').'">'.oos_button('delete', 'Delete Voucher').'</a>' .
                     '<br /><a href="' . oos_href_link_admin('coupon_admin.php','action=voucherreport&cID='.$cInfo->coupon_id,'NONSSL').'">'.oos_button('report', 'Voucher Report').'</a></center>'
                     );
        }
        break;
      }
?>
    <td width="25%" valign="top">
<?php
      $box = new box;
      echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
    }
?>
      </tr>
    </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

	</div>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>