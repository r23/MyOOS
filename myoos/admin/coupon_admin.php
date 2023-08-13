<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
    $_GET['oldaction'] = '';
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$oldaction = filter_input(INPUT_GET, 'oldaction', FILTER_SANITIZE_STRING);

if (($action == 'send_email_to_user') && ($_POST['customers_email_address']) && (!$_POST['back_x'])) {
    switch ($_POST['customers_email_address']) {
    case '***':
      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address
                                   FROM" . $oostable['customers']);
      $mail_sent_to = TEXT_ALL_CUSTOMERS;
      break;

/* ToDo Newsletter
    case '**D':
      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address
                                  FROM " . $oostable['customers'] . "
                                  WHERE customers_newsletter = '1'");
      $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
      break;
*/
    default:
      $customers_email_address = oos_db_prepare_input($_POST['customers_email_address']);

      $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address
                                  FROM " . $oostable['customers'] . "
                                  WHERE customers_email_address = '" . oos_db_input($customers_email_address) . "'");
      $mail_sent_to = oos_db_prepare_input($_POST['customers_email_address']);
      break;
    }
    $coupon_result = $dbconn->Execute("SELECT coupon_code
                                  FROM " . $oostable['coupons'] . "
                                  WHERE coupon_id = '" . intval($_GET['cID']) . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name
                                       FROM " . $oostable['coupons_description'] . "
                                       WHERE coupon_id = '" . intval($_GET['cID']) . "' AND
                                             coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $coupon_name = $coupon_name_result->fields;

    // Instantiate a new mail object
    $send_mail = new PHPMailer();

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');

    $send_mail->CharSet = CHARSET;
    $send_mail->IsMail();

    $send_mail->From = $from_mail ? $from_mail : STORE_OWNER_EMAIL_ADDRESS;
    $send_mail->FromName = $from_name ? $from_name : STORE_OWNER;
    $send_mail->Mailer = EMAIL_TRANSPORT;

    // Add smtp values if needed
    if (EMAIL_TRANSPORT == 'smtp') {
        $send_mail->IsSMTP(); // set mailer to use SMTP
      $send_mail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
      $send_mail->Username = OOS_SMTPUSER; // SMTP username
      $send_mail->Password = OOS_SMTPPASS; // SMTP password
      $send_mail->Host     = OOS_SMTPHOST; // specify main and backup server
    } else {
        // Set sendmail path
        if (EMAIL_TRANSPORT == 'sendmail') {
            if (!oos_empty(OOS_SENDMAIL)) {
                $send_mail->Sendmail = OOS_SENDMAIL;
                $send_mail->IsSendmail();
            }
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

    oos_redirect_admin(oos_href_link_admin($aContents['coupon_admin'], 'mail_sent_to=' . urlencode($mail_sent_to)));
}

if (($action == 'preview_email') && (!$_POST['customers_email_address'])) {
    $action = 'email';
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
}

if (isset($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
}

if (!empty($action)) {
    switch ($action) {
        case 'confirmdelete':
            $delete_result=$dbconn->Execute("UPDATE " . $oostable['coupons'] . " SET coupon_active = 'N' WHERE coupon_id='". intval($_GET['cID'])."'");
            break;

        case 'update':
            $update_errors = 0;

            $coupon_amount = isset($_POST['coupon_amount']) ? oos_db_prepare_input($_POST['coupon_amount']) : 0;

            if (($coupon_amount <= 0) && (!isset($_POST['coupon_free_ship']))) {
                $update_errors = 1;
                $messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
            }

            $aLanguages = oos_get_languages();
            $nLanguages = count($aLanguages);

            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                $language_id = $aLanguages[$i]['id'];

                if (empty($_POST['coupon_name'][$language_id])) {
                    $update_errors = 1;
                    $messageStack->add(ERROR_NO_COUPON_NAME, 'error');
                } else {
                    $coupon_name[$language_id] = oos_prepare_input($_POST['coupon_name'][$language_id]);
                }
            }

            if (empty($_POST['coupon_amount'])  && (!isset($_POST['coupon_free_ship']))) {
                $update_errors = 1;
                $messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
            }

            $coupon_code = empty($_POST['coupon_code']) ? oos_create_coupon_code() : oos_db_prepare_input($_POST['coupon_code']);
            $query = $dbconn->Execute("SELECT coupon_code
									FROM " . $oostable['coupons'] . "
									WHERE coupon_code = '" . oos_db_input($coupon_code) . "'");
            if ($query->RecordCount() && isset($_POST['coupon_code']) &&
                (isset($_GET['oldaction']) && ($_GET['oldaction'] != 'voucheredit'))) {
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
            $coupon_amount = isset($_POST['coupon_amount']) ? oos_db_prepare_input($_POST['coupon_amount']) : 0;

            $update_errors = 0;

            $aLanguages = oos_get_languages();
            $nLanguages = count($aLanguages);

            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                $language_id = $aLanguages[$i]['id'];

                if (empty($_POST['coupon_name'][$language_id])) {
                    $update_errors = 1;
                    $messageStack->add(ERROR_NO_COUPON_NAME, 'error');
                } else {
                    $coupon_name[$language_id] = oos_prepare_input($_POST['coupon_name'][$language_id]);
                }
            }

            $coupon_amount = isset($_POST['coupon_amount']) ? oos_db_prepare_input($_POST['coupon_amount']) : 0;


            if (isset($_POST['back']) && ($_POST['back'] == 'back')) {
                $action = 'new';
            } else {
                if (($coupon_amount <= 0) && (!isset($_POST['coupon_free_ship']))) {
                    $update_errors = 1;
                    $messageStack->add(ERROR_NO_COUPON_AMOUNT, 'error');
                }

                $coupon_type = "F";
                if (substr($_POST['coupon_amount'], -1) == '%') {
                    $coupon_type = 'P';
                }
                if (isset($_POST['coupon_free_ship']) && ($_POST['coupon_free_ship'] != 0)) {
                    $coupon_amount = 0;
                    $coupon_type = 'S'; // free shipping
                }
                $sql_data_array = array('coupon_code' => oos_db_prepare_input($_POST['coupon_code']),
                                        'coupon_amount' => $coupon_amount,
                                        'coupon_type' => oos_db_prepare_input($coupon_type),
                                        'uses_per_coupon' => oos_db_prepare_input($_POST['coupon_uses_coupon']),
                                        'uses_per_user' => '',
                                        'coupon_minimum_order' => oos_db_prepare_input($_POST['coupon_min_order']),
                                        'restrict_to_products' => oos_db_prepare_input($_POST['coupon_products']),
                                        'restrict_to_categories' => oos_db_prepare_input($_POST['coupon_categories']),
                                        'coupon_start_date' => oos_db_prepare_input($_POST['coupon_startdate']),
                                        'coupon_expire_date' => oos_db_prepare_input($_POST['coupon_finishdate']),
                                        'date_created' => 'now()',
                                        'date_modified' => 'now()');

                $aLanguages = oos_get_languages();
                $nLanguages = count($aLanguages);

                for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                    $language_id = $aLanguages[$i]['id'];

                    $sql_data_marray[$i] = array('coupon_name' => oos_db_prepare_input($_POST['coupon_name'][$language_id]),
                                                'coupon_description' => oos_db_prepare_input($_POST['coupon_desc'][$language_id])
                    );
                }

                if (isset($_GET['oldaction']) && ($_GET['oldaction'] == 'voucheredit')) {
                    oos_db_perform($oostable['coupons'], $sql_data_array, 'UPDATE', "coupon_id='" . intval($_GET['cID']) . "'");

                    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                        $language_id = $aLanguages[$i]['id'];

                        $update = $dbconn->Execute("UPDATE " . $oostable['coupons_description'] . " SET coupon_name = '" . oos_db_prepare_input($_POST['coupon_name'][$language_id]) . "', coupon_description = '" . oos_db_prepare_input($_POST['coupon_desc'][$language_id]) . "' WHERE coupon_id = '" . intval($_GET['cID']) . "' and coupon_languages_id = '" . intval($language_id) . "'");
                    }
                } else {
                    $query = oos_db_perform($oostable['coupons'], $sql_data_array);
                    $insert_id = $dbconn->Insert_ID();

                    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                        $language_id = $aLanguages[$i]['id'];

                        $sql_data_marray[$i]['coupon_id'] = $insert_id;
                        $sql_data_marray[$i]['coupon_languages_id'] = $language_id;
                        oos_db_perform($oostable['coupons_description'], $sql_data_marray[$i]);
                    }
                }
            }
    }
}
require 'includes/header.php';
?>
<!-- body //-->
<div class="wrapper">
	<!-- Header //-->
	<header class="topnavbar-wrapper">
		<!-- Top Navbar //-->
		<?php require 'includes/menue.php'; ?>
	</header>
	<!-- END Header //-->
	<aside class="aside">
		<!-- Sidebar //-->
		<div class="aside-inner">
			<?php require 'includes/blocks.php'; ?>
		</div>
		<!-- END Sidebar (left) //-->
	</aside>

	<!-- Main section //-->
	<section>
		<!-- Page content //-->
		<div class="content-wrapper">

<!-- body_text //-->
<?php
switch ($action) {
case 'voucherreport':
?>
			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">
<!-- body_text //-->
	<div class="table-responsive">
		<table class="table w-100">
          <tr>
            <td valign="top">

				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo CUSTOMER_ID; ?></th>
							<th class="text-center"><?php echo CUSTOMER_NAME; ?></th>
							<th class="text-center"><?php echo IP_ADDRESS; ?></th>
							<th class="text-center"><?php echo REDEEM_DATE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>
					</thead>
<?php
    $rows = 0;
    $cc_result_raw = "SELECT *
                      FROM " . $oostable['coupon_redeem_track'] . "
                     WHERE coupon_id = '" . intval($_GET['cID']) . "'";
    $cc_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $cc_result_raw, $cc_result_numrows);
    $cc_result = $dbconn->Execute($cc_result_raw);
    while ($cc_list = $cc_result->fields) {
        $rows++;
        if (strlen($rows) < 2) {
            $rows = '0' . $rows;
        }
        if ((!isset($_GET['uid']) || (isset($_GET['uid']) && ($_GET['uid'] == $cc_list['unique_id']))) && !isset($cInfo)) {
            $cInfo = new objectInfo($cc_list);
        }
        if (isset($cInfo) && is_object($cInfo) && ($cc_list['unique_id'] == $cInfo->unique_id)) {
            echo '          <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['coupon_admin'], oos_get_all_get_params(array('cID', 'action', 'uid')) . 'cID=' . $cInfo->coupon_id . '&action=voucherreport&uid=' . $cinfo->unique_id) . '\'">' . "\n";
        } else {
            echo '          <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['coupon_admin'], oos_get_all_get_params(array('cID', 'action', 'uid')) . 'cID=' . $cc_list['coupon_id'] . '&action=voucherreport&uid=' . $cc_list['unique_id']) . '\'">' . "\n";
        }
        $customer_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname
                                      FROM " . $oostable['customers'] . "
                                      WHERE customers_id = '" . $cc_list['customer_id'] . "'");
        $customer = $customer_result->fields; ?>
                <td><?php echo $cc_list['customer_id']; ?></td>
                <td class="text-center"><?php echo $customer['customers_firstname'] . ' ' . $customer['customers_lastname']; ?></td>
                <td class="text-center"><?php echo $cc_list['redeem_ip']; ?></td>
                <td class="text-center"><?php echo oos_date_short($cc_list['redeem_date']); ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($cc_list['unique_id'] == $cInfo->unique_id)) {
            echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'page=' . $nPage . '&cID=' . $cc_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
        } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $cc_result->MoveNext();
    }
?>


             </table></td>
<?php
    $heading = [];
    $contents = [];
        $coupon_description_result = $dbconn->Execute("SELECT coupon_name
                                                FROM " . $oostable['coupons_description'] . "
                                                WHERE coupon_id = '" . intval($_GET['cID']) . "' AND
                                                    coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $coupon_desc = $coupon_description_result->fields;
        // remove?
        if (isset($cInfo) && is_object($cInfo)) {
            $count_customers = $dbconn->Execute("SELECT *
												FROM " . $oostable['coupon_redeem_track'] . "
												WHERE coupon_id = '" . intval($_GET['cID']) . "' AND
												customer_id = '" . intval($cInfo->customer_id) . "'");
        }
        $heading[] = array('text' => '<b>[' . intval($_GET['cID']) . ']' . COUPON_NAME . ' ' . $coupon_desc['coupon_name'] . '</b>');
        $contents[] = array('text' => '<b>' . TEXT_REDEMPTIONS . '</b>');
        #  $contents[] = array('text' => TEXT_REDEMPTIONS_TOTAL . '=' . $cc_result->RecordCount();
        #  $contents[] = array('text' => TEXT_REDEMPTIONS_CUSTOMER . '=' . $count_customers->RecordCount();
        $contents[] = array('text' => '');
?>
    <td class="w-25" valign="top">
		<table class="table table-striped">
<?php
        $box = new box();
        echo $box->infoBox($heading, $contents);
?>
		</table>
	</td>
<?php
    break;
  case 'preview_email':
    $coupon_result = $dbconn->Execute("SELECT coupon_code
                                  FROM " .$oostable['coupons'] . "
                                  WHERE coupon_id = '" . intval($_GET['cID']) . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name
                                            FROM " . $oostable['coupons_description'] . "
                                            WHERE coupon_id = '" . intval($_GET['cID']) . "' AND
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
      $mail_sent_to = oos_db_prepare_input($_POST['customers_email_address']);
      break;
    }
?>
			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">

	<table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['coupon_admin'], 'action=send_email_to_user&cID=' . intval($_GET['cID']), 'post', false); ?>
            <td><table border="0" width="100%" cellpadding="0" cellspacing="2">
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_CUSTOMER; ?></b><br><?php echo $mail_sent_to; ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_COUPON; ?></b><br><?php echo $coupon_name['coupon_name']; ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM_NAME; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['from_name']), ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM_MAIL; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['from_mail']), ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['subject']), ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes((string)$_POST['message']), ENT_QUOTES, 'UTF-8')); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td>
<?php
/* Re-Post all POST'ed variables */
    reset($_POST);
    foreach ($_POST as $key => $value) {
        if (!is_array($_POST[$key])) {
            echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes((string)$value)), ENT_QUOTES, 'UTF-8');
        }
    }
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php ?>&nbsp;</td>
                    <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['coupon_admin']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>' . oos_submit_button(IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table>
          </form></td></tr>
<?php
    break;
  case 'email':
    $coupon_result = $dbconn->Execute("SELECT coupon_code
                                  FROM " . $oostable['coupons'] . "
                                  WHERE coupon_id = '" . intval($_GET['cID']) . "'");
    $coupon_result = $coupon_result->fields;
    $coupon_name_result = $dbconn->Execute("SELECT coupon_name
                                       FROM " . $oostable['coupons_description'] . "
                                       WHERE coupon_id = '" . intval($_GET['cID']) . "' AND
                                             coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $coupon_name = $coupon_name_result->fields;
?>
			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">

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

          <tr><?php echo oos_draw_form('id', 'mail', $aContents['coupon_admin'], 'action=preview_email&cID='. intval($_GET['cID']), 'post', false); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"></td>
              </tr>
<?php
    $customers = [];
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
    $mail_result = $dbconn->Execute("SELECT customers_email_address, customers_firstname, customers_lastname
									FROM " . $oostable['customers'] . "
									ORDER BY customers_lastname");
    while ($customers_values = $mail_result->fields) {
        $customers[] = array('id' => $customers_values['customers_email_address'],
                            'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');

        // Move that ADOdb pointer!
        $mail_result->MoveNext();
    }

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
                <td><?php echo oos_draw_pull_down_menu('customers_email_address', $customers, isset($_GET['customer']) ? oos_db_prepare_input($_GET['customer']) : ''); ?></td>
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
                <td><?php echo oos_draw_input_field('from_mail', STORE_OWNER_EMAIL_ADDRESS); ?></td></td>
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
                <td colspan="2" align="right"><?php echo oos_submit_button(IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table>
          </form></td></tr>
      </td>
<?php
    break;
  case 'update_preview':
?>

			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">
	<table border="0" width="100%" cellspacing="0" cellpadding="2">

      <tr>
      <td>
<?php echo oos_draw_form('id', 'coupon', $aContents['coupon_admin'], 'action=update_confirm&oldaction=' . $oldaction . '&cID=' . intval($_GET['cID']), 'post', false); ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id']; ?>
      <tr>
        <td class="text-left"><?php echo COUPON_NAME; ?></td>
        <td class="text-left"><?php echo $_POST['coupon_name'][$language_id]; ?></td>
      </tr>
<?php
        }
?>
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id']; ?>
      <tr>
        <td class="text-left"><?php echo COUPON_DESC; ?></td>
        <td class="text-left"><?php echo oos_prepare_input($_POST['coupon_desc'][$language_id]); ?></td>
      </tr>
<?php
        }

        $coupon_amount = oos_prepare_input($_POST['coupon_amount']);
        if (isset($_POST['coupon_free_ship']) && ($_POST['coupon_free_ship'] != 0)) {
            $coupon_amount = 0;
        }
?>
      <tr>
        <td class="text-left"><?php echo COUPON_AMOUNT; ?></td>
        <td class="text-left"><?php echo $coupon_amount; ?></td>
      </tr>

      <tr>
        <td class="text-left"><?php echo COUPON_MIN_ORDER; ?></td>
        <td class="text-left"><?php echo oos_prepare_input($_POST['coupon_min_order']); ?></td>
      </tr>

      <tr>
        <td class="text-left"><?php echo COUPON_FREE_SHIP; ?></td>
<?php
    if (isset($_POST['coupon_free_ship'])) {
        ?>
        <td class="text-left"><?php echo TEXT_FREE_SHIPPING; ?></td>
<?php
    } else {
        ?>
        <td class="text-left"><?php echo TEXT_NO_FREE_SHIPPING; ?></td>
<?php
    }
?>
      </tr>
      <tr>
        <td class="text-left"><?php echo COUPON_CODE; ?></td>
        <td class="text-left"><?php echo $coupon_code; ?></td>
      </tr>

      <tr>
        <td class="text-left"><?php echo COUPON_USES_COUPON; ?></td>
        <td class="text-left"><?php echo oos_prepare_input($_POST['coupon_uses_coupon']); ?></td>
      </tr>

<?php
/*
    // For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.

      <tr>
        <td class="text-left"><?php echo COUPON_USES_USER; ?></td>
        <td class="text-left"><?php echo $_POST['coupon_uses_user']; ?></td>
      </tr>
*/
?>


       <tr>
        <td class="text-left"><?php echo COUPON_PRODUCTS; ?></td>
        <td class="text-left"><?php echo oos_prepare_input($_POST['coupon_products']); ?></td>
      </tr>


      <tr>
        <td class="text-left"><?php echo COUPON_CATEGORIES; ?></td>
        <td class="text-left"><?php echo oos_prepare_input($_POST['coupon_categories']); ?></td>
      </tr>
      <tr>
        <td class="text-left"><?php echo COUPON_STARTDATE; ?></td>
<?php
    $start_date = date(DATE_FORMAT, mktime(0, 0, 0, oos_prepare_input($_POST['coupon_startdate_month']), oos_prepare_input($_POST['coupon_startdate_day']), oos_prepare_input($_POST['coupon_startdate_year'])));
?>
        <td class="text-left"><?php echo $start_date; ?></td>
      </tr>

      <tr>
        <td class="text-left"><?php echo COUPON_FINISHDATE; ?></td>
<?php
    $finish_date = date(DATE_FORMAT, mktime(0, 0, 0, oos_prepare_input($_POST['coupon_finishdate_month']), oos_prepare_input($_POST['coupon_finishdate_day']), oos_prepare_input($_POST['coupon_finishdate_year'])));
?>
        <td class="text-left"><?php echo $finish_date; ?></td>
      </tr>
<?php
    $languages = oos_get_languages();
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $language_id = $languages[$i]['id'];
        echo oos_draw_hidden_field('coupon_name[' . $languages[$i]['id'] . ']', $_POST['coupon_name'][$language_id]);
        echo oos_draw_hidden_field('coupon_desc[' . $languages[$i]['id'] . ']', $_POST['coupon_desc'][$language_id]);
    }
    echo oos_draw_hidden_field('coupon_amount', isset($_POST['coupon_amount']) ? oos_db_prepare_input($_POST['coupon_amount']) : 0);
    echo oos_draw_hidden_field('coupon_min_order', isset($_POST['coupon_min_order']) ? oos_db_prepare_input($_POST['coupon_min_order']) : 0);
    if (isset($_POST['coupon_free_ship'])) {
        echo oos_draw_hidden_field('coupon_free_ship', 1);
    }
    echo oos_draw_hidden_field('coupon_code', $coupon_code);
    echo oos_draw_hidden_field('coupon_uses_coupon', $_POST['coupon_uses_coupon']);
    // For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.
    echo oos_draw_hidden_field('coupon_uses_user', '');
    echo oos_draw_hidden_field('coupon_products', $_POST['coupon_products']);
    echo oos_draw_hidden_field('coupon_categories', $_POST['coupon_categories']);
    echo oos_draw_hidden_field('coupon_startdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_startdate_month'], $_POST['coupon_startdate_day'], $_POST['coupon_startdate_year'])));
    echo oos_draw_hidden_field('coupon_finishdate', date('Y-m-d', mktime(0, 0, 0, $_POST['coupon_finishdate_month'], $_POST['coupon_finishdate_day'], $_POST['coupon_finishdate_year'])));
?>
     <tr>
        <td class="text-left"><?php echo oos_submit_button(BUTTON_CONFIRM); ?></td>
        <td class="text-left"><?php echo oos_cancel_button('<i class="fa fa-chevron-left"></i> ' . BUTTON_BACK, 'back'); ?></td>
      </tr>

	</table></form></td>
<?php

    break;
        case 'voucheredit':
            $aLanguages = oos_get_languages();
            $nLanguages = count($aLanguages);

            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                $language_id = $aLanguages[$i]['id'];

                $coupon_result = $dbconn->Execute("SELECT coupon_name,coupon_description
											FROM " . $oostable['coupons_description'] . "
											WHERE coupon_id = '" .  intval($_GET['cID']) . "' AND
											coupon_languages_id = '" . intval($language_id) . "'");
                $coupon = $coupon_result->fields;
                $coupon_name[$language_id] = $coupon['coupon_name'];
                $coupon_desc[$language_id] = $coupon['coupon_description'];
            }

            $coupon_result = $dbconn->Execute("SELECT coupon_code, coupon_amount, coupon_type, coupon_minimum_order, coupon_start_date,
                                          coupon_expire_date, uses_per_coupon, uses_per_user, restrict_to_products, restrict_to_categories
                                   FROM " . $oostable['coupons'] . "
                                   WHERE coupon_id = '" . intval($_GET['cID']) . "'");
            $coupon = $coupon_result->fields;
            $coupon_amount = $coupon['coupon_amount'];
            if ($coupon['coupon_type']=='P') {
                $coupon_amount .= '%';
            }

            if ($coupon['coupon_type']=='S') {
                $coupon_free_ship = true;
            }

            $coupon_min_order = $coupon['coupon_minimum_order'];
            $coupon_code = $coupon['coupon_code'];
            $coupon_uses_coupon = $coupon['uses_per_coupon'];
            $coupon_uses_user = $coupon['uses_per_user'];
            $coupon_products = $coupon['restrict_to_products'];
            $coupon_categories = $coupon['restrict_to_categories'];

            // no break
        case 'new':

// set some defaults
// For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.
#    if (!isset($coupon_uses_user)) $coupon_uses_user=1;
?>

			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">


	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
      <td>
<?php
    echo oos_draw_form('id', 'coupon', $aContents['coupon_admin'], 'action=update&oldaction='.$action . '&cID=' . intval($_GET['cID']), 'post', false);
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="6">
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id']; ?>
      <tr>
        <td align="left" class="main"><?php if ($i==0) {
                echo COUPON_NAME;
            } ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_name[' . $languages[$i]['id'] . ']', (empty($coupon_name[$language_id]) ? '' : $coupon_name[$language_id])) . '&nbsp;' . oos_flag_icon($languages[$i]); ?></td>
        <td align="left" class="main" width="40%"><?php if ($i==0) {
                echo COUPON_NAME_HELP;
            } ?></td>
      </tr>
<?php
        }
?>
<?php
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $language_id = $languages[$i]['id']; ?>

      <tr>
        <td align="left" valign="top" class="main"><?php if ($i==0) {
                echo COUPON_DESC;
            } ?></td>
        <td align="left" valign="top"><?php echo oos_draw_textarea_field('coupon_desc[' . $languages[$i]['id'] . ']', 'physical', '24', '3', (empty($coupon_desc[$language_id]) ? '' : $coupon_desc[$language_id])) . '&nbsp;' . oos_flag_icon($languages[$i]); ?></td>
        <td align="left" valign="top" class="main"><?php if ($i==0) {
                echo COUPON_DESC_HELP;
            } ?></td>
      </tr>
<?php
        }
?>
      <tr>
        <td align="left" class="main"><?php echo COUPON_AMOUNT; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_amount', (empty($coupon_amount) ? '' : $coupon_amount)); ?></td>
        <td align="left" class="main"><?php echo COUPON_AMOUNT_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_min_order', (empty($coupon_min_order) ? '' : $coupon_min_order)); ?></td>
        <td align="left" class="main"><?php echo COUPON_MIN_ORDER_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP; ?></td>
        <td class="text-left"><?php echo oos_draw_checkbox_field('coupon_free_ship', '', (isset($coupon_free_ship) ? $coupon_free_ship : false)); ?></td>
        <td align="left" class="main"><?php echo COUPON_FREE_SHIP_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CODE; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_code', (empty($coupon_code) ? '' : $coupon_code)); ?></td>
        <td align="left" class="main"><?php echo COUPON_CODE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_uses_coupon', (empty($coupon_uses_coupon) ? '' : $coupon_uses_coupon)); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_COUPON_HELP; ?></td>
      </tr>
<?php
/*
    // For this type of voucher the customer would need to be logged in. But we must allow guest orders in the store.

      <tr>
        <td align="left" class="main"><?php echo COUPON_USES_USER; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_uses_user', (empty($coupon_uses_user) ? '' : $coupon_uses_user)); ?></td>
        <td align="left" class="main"><?php echo COUPON_USES_USER_HELP; ?></td>
      </tr>
*/
?>
       <tr>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_products', (empty($coupon_products) ? '' : $coupon_products)); ?> <?php echo '<a href="' . oos_href_link_admin($aContents['validproducts']); ?>" TARGET="_blank" ONCLICK="window.open('<?php echo oos_href_link_admin($aContents['validproducts']); ?>', 'Valid_Products', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</a></td>
        <td align="left" class="main"><?php echo COUPON_PRODUCTS_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES; ?></td>
        <td class="text-left"><?php echo oos_draw_input_field('coupon_categories', (empty($coupon_categories) ? '' : $coupon_categories)); ?> <?php echo '<a href="' . oos_href_link_admin($aContents['validcategories']); ?>" TARGET="_blank" ONCLICK="window.open('<?php echo oos_href_link_admin($aContents['validcategories']); ?>', 'Valid_Categories', 'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600'); return false">View</a></td>
        <td align="left" class="main"><?php echo COUPON_CATEGORIES_HELP; ?></td>
      </tr>
      <tr>
<?php
    if (isset($coupon['coupon_start_date'])) {
        $year = (int)substr($coupon['coupon_start_date'], 0, 4);
        $month = (int)substr($coupon['coupon_start_date'], 5, 2);
        $day = (int)substr($coupon['coupon_start_date'], 8, 2);
        $coupon_startdate  = [$year, $month, $day ];
    } elseif (isset($_POST['coupon_startdate'])) {
        $coupon_startdate = preg_split("/[-]/", $_POST['coupon_startdate']);
    } else {
        $coupon_startdate = preg_split("/[-]/", date('Y-m-d'));
    }

    if (isset($coupon['coupon_expire_date'])) {
        $year = (int)substr($coupon['coupon_expire_date'], 0, 4);
        $month = (int)substr($coupon['coupon_expire_date'], 5, 2);
        $day = (int)substr($coupon['coupon_expire_date'], 8, 2);
        $coupon_finishdate  = [$year, $month, $day ];
    } elseif (isset($_POST['coupon_finishdate'])) {
        $coupon_finishdate = preg_split("/[-]/", $_POST['coupon_finishdate']);
    } else {
        $coupon_finishdate = preg_split("/[-]/", date('Y-m-d'));
        $coupon_finishdate[0] = $coupon_finishdate[0] + 1;
    }

?>
        <td align="left" class="main"><?php echo COUPON_STARTDATE; ?></td>
        <td class="text-left"><?php echo oos_draw_date_selector('coupon_startdate', mktime(0, 0, 0, $coupon_startdate[1], $coupon_startdate[2], $coupon_startdate[0])); ?></td>
        <td align="left" class="main"><?php echo COUPON_STARTDATE_HELP; ?></td>
      </tr>
      <tr>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE; ?></td>
        <td class="text-left"><?php echo oos_draw_date_selector('coupon_finishdate', mktime(0, 0, 0, $coupon_finishdate[1], $coupon_finishdate[2], $coupon_finishdate[0])); ?></td>
        <td align="left" class="main"><?php echo COUPON_FINISHDATE_HELP; ?></td>
      </tr>
      <tr>
        <td class="text-left"><?php echo oos_submit_button(BUTTON_PREVIEW); ?></td>
        <td class="text-left"><?php echo '&nbsp;&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['coupon_admin']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?></td>
      </tr>
      </table></form></td>
<?php
    break;
  default:
?>
			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
						</li>
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">

		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"></td>
            <td class="main"><?php echo oos_draw_form('id', 'status', $aContents['coupon_admin'], '', 'get', false); ?>
<?php
    $status_array[] = array('id' => 'Y', 'text' => TEXT_COUPON_ACTIVE);
    $status_array[] = array('id' => 'N', 'text' => TEXT_COUPON_INACTIVE);
    $status_array[] = array('id' => '*', 'text' => TEXT_COUPON_ALL);

    $status = isset($_GET['status']) ? oos_db_prepare_input($_GET['status']) : 'Y';

    echo HEADING_TITLE_STATUS . ' ' . oos_draw_pull_down_menu('status', $status_array, $status, 'onChange="this.form.submit();"');
?>
              </form>
           </td>
          </tr>
        </table>

	<div class="table-responsive">
		<table class="table w-100">
          <tr>
            <td valign="top">
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo COUPON_NAME; ?></th>
							<th class="text-center"><?php echo COUPON_AMOUNT; ?></th>
							<th class="text-center"><?php echo COUPON_CODE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>
					</thead>
<?php
    $rows = 0;
    if (isset($nPage) && ($nPage > 1)) {
        $rows = $nPage * 20 - 20;
    }
    if ($status != '*') {
        $cc_result_raw = "SELECT coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date, coupon_expire_date, uses_per_user, uses_per_coupon, restrict_to_products, restrict_to_categories, date_created, date_modified FROM " . $oostable['coupons'] . " WHERE coupon_active='" . oos_db_input($status) . "' AND coupon_type != 'G'";
    } else {
        $cc_result_raw = "SELECT coupon_id, coupon_code, coupon_amount, coupon_type, coupon_start_date, coupon_expire_date, uses_per_user, uses_per_coupon, restrict_to_products, restrict_to_categories, date_created, date_modified FROM " . $oostable['coupons'] . " WHERE coupon_type != 'G'";
    }
    $cc_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $cc_result_raw, $cc_result_numrows);
    $cc_result = $dbconn->Execute($cc_result_raw);
    while ($cc_list = $cc_result->fields) {
        $rows++;
        if (strlen($rows) < 2) {
            $rows = '0' . $rows;
        }
        if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $cc_list['coupon_id']))) && !isset($cInfo)) {
            $cInfo = new objectInfo($cc_list);
        }
        if (isset($cInfo) && is_object($cInfo) && ($cc_list['coupon_id'] == $cInfo->coupon_id)) {
            echo '          <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['coupon_admin'], oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->coupon_id . '&action=edit') . '\'">' . "\n";
        } else {
            echo '          <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['coupon_admin'], oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cc_list['coupon_id']) . '\'">' . "\n";
        }
        $coupon_description_result = $dbconn->Execute("SELECT coupon_name
                                                 FROM " . $oostable['coupons_description'] . "
                                                 WHERE coupon_id = '" . $cc_list['coupon_id'] . "'
                                                   AND coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $coupon_desc = $coupon_description_result->fields; ?>
                <td><?php echo $coupon_desc['coupon_name']; ?></td>
                <td class="text-center">
<?php
        if ($cc_list['coupon_type'] == 'P') {
            echo $cc_list['coupon_amount'] . '%';
        } elseif ($cc_list['coupon_type'] == 'S') {
            echo TEXT_FREE_SHIPPING;
        } else {
            echo $currencies->format($cc_list['coupon_amount']);
        } ?>
            &nbsp;</td>
                <td class="text-center"><?php echo $cc_list['coupon_code']; ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($cc_list['coupon_id'] == $cInfo->coupon_id)) {
            echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'page=' . $nPage . '&status=' . $status . '&cID=' . $cc_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
        } ?>&nbsp;</td>
              </tr>
<?php
        // Move that ADOdb pointer!
        $cc_result->MoveNext();
    }

    $coupon_id = isset($cInfo->coupon_id) ? $cInfo->coupon_id : '';
?>

          <tr>
            <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText">&nbsp;<?php echo $cc_split->display_count($cc_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_COUPONS); ?>&nbsp;</td>
                <td align="right" class="smallText">&nbsp;<?php echo $cc_split->display_links($cc_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?>&nbsp;</td>
              </tr>

              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'page=' . $nPage . '&cID=' . $coupon_id . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>

<?php

    $heading = [];
    $contents = [];

    switch ($action) {
    case 'release':
        break;

    case 'voucherreport':
        $heading[] = array('text' => '<b>' . TEXT_HEADING_COUPON_REPORT . '</b>');
        $contents[] = array('text' => TEXT_NEW_INTRO);
        break;

    case 'new':
        $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_COUPON . '</b>');
        $contents[] = array('text' => TEXT_NEW_INTRO);
        $contents[] = array('text' => '<br>' . COUPON_NAME . '<br>' . oos_draw_input_field('name'));
        $contents[] = array('text' => '<br>' . COUPON_AMOUNT . '<br>' . oos_draw_input_field('voucher_amount'));
        $contents[] = array('text' => '<br>' . COUPON_CODE . '<br>' . oos_draw_input_field('voucher_code'));
        $contents[] = array('text' => '<br>' . COUPON_USES_COUPON . '<br>' . oos_draw_input_field('voucher_number_of'));
        break;

    default:
		$coupon_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT);
        $coupon_code = isset($cInfo->coupon_code) ? $cInfo->coupon_code : '';
        $coupon_start_date = isset($cInfo->coupon_start_date) ? $cInfo->coupon_start_date : '';
        $coupon_expire_date = isset($cInfo->coupon_expire_date) ? $cInfo->coupon_expire_date : '';
        $uses_per_coupon = isset($cInfo->uses_per_coupon) ? $cInfo->uses_per_coupon : '';
        $uses_per_user = isset($cInfo->uses_per_user) ? $cInfo->uses_per_user : '';
        $date_created = isset($cInfo->date_created) ? $cInfo->date_created : '';
        $date_modified = isset($cInfo->date_modified) ? $cInfo->date_modified : '';

        $heading[] = array('text'=>'['.$coupon_id.']  '.$coupon_code);
        $amount = isset($cInfo->coupon_amount) ? $cInfo->coupon_amount : 0;

        if (isset($cInfo->coupon_type) && $cInfo->coupon_type == 'P') {
            $amount .= '%';
        } else {
            $amount = $currencies->format($amount);
        }
        if ($action == 'voucherdelete') {
            $contents[] = array('text'=> TEXT_CONFIRM_DELETE . '</br></br>' .
                        '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'action=confirmdelete&cID='  . intval($_GET['cID'])) . '">' . oos_button(BUTTON_CONFIRM_DELETE_VOUCHER) . '</a>' .
                        '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['coupon_admin'], 'cID=' . $coupon_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'
                    );
        } else {
            $prod_details = '';
            if (isset($cInfo->restrict_to_products) && $cInfo->restrict_to_products) {
                $prod_details = '<a href="' . oos_href_link_admin($aContents['listproducts'], 'cID=' . $coupon_id) . '" TARGET="_blank" ONCLICK="window.open(\'' . $aContents['listproducts'] . '?cID=' . $coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</a>';
            }
            $cat_details = '';
            if (isset($cInfo->restrict_to_categories) && $cInfo->restrict_to_categories) {
                $cat_details = '<a href="' . oos_href_link_admin($aContents['listcategories'], 'cID=' . $coupon_id) . '" TARGET="_blank" ONCLICK="window.open(\'' . $aContents['listcategories'] . '?cID=' . $coupon_id . '\', \'Valid_Categories\', \'scrollbars=yes,resizable=yes,menubar=yes,width=600,height=600\'); return false">View</a>';
            }
            $coupon_name_result = $dbconn->Execute("SELECT coupon_name
												FROM " . $oostable['coupons_description'] . "
												WHERE coupon_id = '" . oos_db_input($coupon_id) . "' AND
													coupon_languages_id = '" . intval($_SESSION['language_id']) . "'");
            $coupon_name = $coupon_name_result->fields;
            $name = isset($coupon_name['coupon_name']) ? $coupon_name['coupon_name'] : '';

            $contents[] = array('text'=>COUPON_NAME . ':&nbsp;' . $name . '<br>' .
                     COUPON_AMOUNT . ':&nbsp;' . $amount . '<br>' .
                     COUPON_STARTDATE . ':&nbsp;' . oos_date_short($coupon_start_date) . '<br>' .
                     COUPON_FINISHDATE . ':&nbsp;' . oos_date_short($coupon_expire_date) . '<br>' .
                     COUPON_USES_COUPON . '&nbsp;' . $uses_per_coupon . '<br>' .
                     COUPON_USES_USER . '&nbsp;' . $uses_per_user . '<br>' .
                     COUPON_PRODUCTS . ':&nbsp;' . $prod_details . '<br>' .
                     COUPON_CATEGORIES . ':&nbsp;' . $cat_details . '<br>' .
                     DATE_CREATED . ':&nbsp;' . oos_date_short($date_created) . '<br>' .
                     DATE_MODIFIED . ':&nbsp;' . oos_date_short($date_modified) . '<br><br>' .
                     '<center><a href="' . oos_href_link_admin($aContents['coupon_admin'], 'action=email&cID='.$coupon_id).'">'.oos_button(BUTTON_EMAIL_VOUCHER).'</a>' .
                     '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'action=voucheredit&cID='.$coupon_id).'">'.oos_button(BUTTON_EDIT_VOUCHER).'</a>' .
                     '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'action=voucherdelete&cID='.$coupon_id).'">'.oos_button(BUTTON_DELETE_VOUCHER).'</a>' .
                     '<br><a href="' . oos_href_link_admin($aContents['coupon_admin'], 'action=voucherreport&cID='.$coupon_id).'">'.oos_button(BUTTON_REPORT_VOUCHER).'</a></center>'
                     );
        }
        break;
    }
?>
	<td class="w-25" valign="top">
		<table class="table table-striped">
<?php
        $box = new box();
        echo $box->infoBox($heading, $contents);
?>
		</table>
	</td>
<?php
    }
?>
          </tr>
        </table>
	</div>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>
