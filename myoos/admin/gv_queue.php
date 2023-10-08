<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_queue.php,v 1.2.2.5 2003/05/05 12:46:52 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

if ($action == 'confirmrelease' && isset($_GET['gid'])) {
    $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
    $gv_result = $dbconn->Execute("SELECT release_flag FROM $coupon_gv_queuetable WHERE unique_id='".$_GET['gid']."'");
    $gv_result = $gv_result->fields;
    if ($gv_result['release_flag'] == 'N') {
        $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
        $gv_result = $dbconn->Execute("SELECT customer_id, amount FROM $coupon_gv_queuetable WHERE unique_id='".$_GET['gid']."'");
        if ($gv_resulta = $gv_result->fields) {
            $gv_amount = $gv_resulta['amount'];
            //Let's build a message object using the email class
            $customerstable = $oostable['customers'];
            $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM $customerstable WHERE customers_id = '" . $gv_resulta['customer_id'] . "'");
            $mail = $mail_result->fields;

            $message = TEXT_REDEEM_COUPON_MESSAGE_HEADER;
            $message .= sprintf(TEXT_REDEEM_COUPON_MESSAGE_AMOUNT, $currencies->format($gv_amount));
            $message .= TEXT_REDEEM_COUPON_MESSAGE_BODY;
            $message .= TEXT_REDEEM_COUPON_MESSAGE_FOOTER;

            // Instantiate a new mail object
			$send_mail = new PHPMailer\PHPMailer\PHPMailer();    

            $sLang = ($_SESSION['iso_639_1'] ?? DEFAULT_LANGUAGE_CODE);
            $send_mail->setLanguage($sLang, MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/language/');

            $send_mail->IsMail();

            $send_mail->CharSet   = 'UTF-8';
            $send_mail->Encoding  = 'base64';

            $send_mail->From = STORE_OWNER_EMAIL_ADDRESS;
            $send_mail->FromName = STORE_OWNER;
            $send_mail->Mailer = EMAIL_TRANSPORT;

            // Add smtp values if needed
			if (EMAIL_TRANSPORT == 'smtp') {
				$send_mail->IsSMTP(); // set mailer to use SMTP

				// $send_mail->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
        
				$send_mail->Host     = OOS_SMTPHOST; // specify main and backup server        
				$send_mail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
				$send_mail->Username = OOS_SMTPUSER; // SMTP username
				$send_mail->Password = OOS_SMTPPASS; // SMTP password
        
        
				// Set the encryption mechanism to use:
				// - SMTPS (implicit TLS on port 465) or
				// - STARTTLS (explicit TLS on port 587)
				if (OOS_SMTPPORT == '465') {
					$send_mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
				} elseif (OOS_SMTPPORT == '587') { {
					$send_mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
				}

				// Set the SMTP port number:
				// - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
				// - 587 for SMTP+STARTTLS
				$send_mail->Port = OOS_SMTPPORT;         
        
			} else {
				// Set sendmail path
				if (EMAIL_TRANSPORT == 'sendmail') {
					if (!oos_empty(OOS_SENDMAIL)) {
						$send_mail->Sendmail = OOS_SENDMAIL;
						$send_mail->IsSendmail();
					}
				}
			}

            $send_mail->Subject = TEXT_REDEEM_COUPON_SUBJECT;
            $send_mail->Body = $message;
            $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
            $send_mail->Send();
            $send_mail->ClearAddresses();
            $send_mail->ClearAttachments();

            $gv_amount = $gv_resulta['amount'];

            $coupon_gv_customertable = $oostable['coupon_gv_customer'];
            $gv_result = $dbconn->Execute("SELECT amount FROM $coupon_gv_customertable WHERE customer_id='".$gv_resulta['customer_id']."'");
            $customer_gv = false;
            $total_gv_amount = 0;
            if ($gv_result = $gv_result->fields) {
                $total_gv_amount=$gv_result['amount'];
                $customer_gv = true;
            }
            $total_gv_amount=$total_gv_amount+$gv_amount;
            if ($customer_gv) {
                $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                $gv_update = $dbconn->Execute("UPDATE $coupon_gv_customertable SET amount='".$total_gv_amount."' WHERE customer_id='".$gv_resulta['customer_id']."'");
            } else {
                $coupon_gv_customertable = $oostable['coupon_gv_customer'];
                $gv_insert = $dbconn->Execute("INSERT INTO $coupon_gv_customertable (customer_id, amount) VALUES ('".$gv_resulta['customer_id']."','".$total_gv_amount."')");
            }
            $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
            $gv_update = $dbconn->Execute("UPDATE $coupon_gv_queuetable SET release_flag='Y' WHERE unique_id='".$_GET['gid']."'");
        }
    }
}

  require 'includes/header.php';
?>
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
                            <th><?php echo TABLE_HEADING_CUSTOMERS; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_ORDERS_ID; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
  $customerstable = $oostable['customers'];
  $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
  $gv_result_raw = "SELECT c.customers_firstname, c.customers_lastname, gv.unique_id,
                           gv.date_created, gv.amount, gv.order_id
                    FROM $customerstable c,
                         $coupon_gv_queuetable gv
                   WHERE (gv.customer_id = c.customers_id AND gv.release_flag = 'N')";
  $gv_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $gv_result_raw, $gv_result_numrows);
  $gv_result = $dbconn->Execute($gv_result_raw);
while ($gv_list = $gv_result->fields) {
    if ((!isset($_GET['gid']) || (isset($_GET['gid']) && ($_GET['gid'] == $gv_list['unique_id']))) && !isset($gInfo)) {
        $gInfo = new objectInfo($gv_list);
    }
    if (isset($gInfo) && is_object($gInfo) && ($gv_list['unique_id'] == $gInfo->unique_id)) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['gv_queue'], oos_get_all_get_params(['gid', 'action']) . 'gid=' . $gInfo->unique_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['gv_queue'], oos_get_all_get_params(['gid', 'action']) . 'gid=' . $gv_list['unique_id']) . '\'">' . "\n";
    } ?>
                <td><?php echo $gv_list['customers_firstname'] . ' ' . $gv_list['customers_lastname']; ?></td>
                <td class="text-center"><?php echo $gv_list['order_id']; ?></td>
                <td class="text-right"><?php echo $currencies->format($gv_list['amount']); ?></td>
                <td class="text-right"><?php echo oos_datetime_short($gv_list['date_created']); ?></td>
                <td class="text-right"><?php if (isset($gInfo) && is_object($gInfo) && ($gv_list['unique_id'] == $gInfo->unique_id)) {
        echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
    } else {
        echo '<a href="' . oos_href_link_admin($aContents['gv_queue'], 'page=' . $nPage . '&gid=' . $gv_list['unique_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
    } ?>&nbsp;</td>
              </tr>
    <?php
    // Move that ADOdb pointer!
    $gv_result->MoveNext();
}
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_links($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $unique_id = $gInfo->unique_id ?? '';
    $date_created = $gInfo->date_created ?? '';
    $amount = $gInfo->amount ?? 0;

  $heading = [];
  $contents = [];
  switch ($action) {
case 'release':
    $heading[] = ['text' => '[' . $unique_id . '] ' . oos_datetime_short($date_created) . ' ' . $currencies->format($amount)];

    $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['gv_queue'], 'action=confirmrelease&gid=' . $unique_id) . '">'.oos_button(BUTTON_CONFIRM) . '</a> <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['gv_queue'], 'action=cancel&gid=' . $unique_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
    break;

default:
    $heading[] = ['text' => '[' . $unique_id . '] ' . oos_datetime_short($date_created) . ' ' . $currencies->format($amount)];

    $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['gv_queue'], 'action=release&gid=' . $unique_id) . '">' . oos_button(IMAGE_RELEASE) . '</a>'];
    break;
  }

  if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
      ?>
    <td class="w-25" valign="top">
        <table class="table table-striped">
      <?php
        $box = new box();
      echo $box->infoBox($heading, $contents); ?>
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