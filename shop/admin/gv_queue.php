<?php
/* ----------------------------------------------------------------------
   $Id: gv_queue.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

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
        $send_mail = new PHPMailer();

        $send_mail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';

        $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');
        $send_mail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );

        $send_mail->CharSet = CHARSET;
        $send_mail->IsMail();

        $send_mail->From = STORE_OWNER_EMAIL_ADDRESS; 
        $send_mail->FromName = STORE_OWNER;
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
  $no_js_general = true;
  require 'includes/header.php'; 
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMERS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDERS_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $customerstable = $oostable['customers'];
  $coupon_gv_queuetable = $oostable['coupon_gv_queue'];
  $gv_result_raw = "SELECT c.customers_firstname, c.customers_lastname, gv.unique_id,
                           gv.date_created, gv.amount, gv.order_id
                    FROM $customerstable c,
                         $coupon_gv_queuetable gv
                   WHERE (gv.customer_id = c.customers_id AND gv.release_flag = 'N')";
  $gv_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $gv_result_raw, $gv_result_numrows);
  $gv_result = $dbconn->Execute($gv_result_raw);
  while ($gv_list = $gv_result->fields) {
    if ((!isset($_GET['gid']) || (isset($_GET['gid']) && ($_GET['gid'] == $gv_list['unique_id']))) && !isset($gInfo)) {
      $gInfo = new objectInfo($gv_list);
    }
    if (isset($gInfo) && is_object($gInfo) && ($gv_list['unique_id'] == $gInfo->unique_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin('gv_queue.php', oos_get_all_get_params(array('gid', 'action')) . 'gid=' . $gInfo->unique_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin('gv_queue.php', oos_get_all_get_params(array('gid', 'action')) . 'gid=' . $gv_list['unique_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $gv_list['customers_firstname'] . ' ' . $gv_list['customers_lastname']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $gv_list['order_id']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format($gv_list['amount']); ?></td>
                <td class="dataTableContent" align="right"><?php echo oos_datetime_short($gv_list['date_created']); ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($gInfo) && is_object($gInfo) && ($gv_list['unique_id'] == $gInfo->unique_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aContents['gv_queue'], 'page=' . $_GET['page'] . '&gid=' . $gv_list['unique_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $gv_result->MoveNext();
  }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_links($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'release':
      $heading[] = array('text' => '[' . $gInfo->unique_id . '] ' . oos_datetime_short($gInfo->date_created) . ' ' . $currencies->format($gInfo->amount));

      $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin('gv_queue.php','action=confirmrelease&gid='.$gInfo->unique_id,'NONSSL').'">'.oos_image_swap_button('confirm_red','confirm_red_off.gif', IMAGE_CONFIRM) . '</a> <a href="' . oos_href_link_admin('gv_queue.php','action=cancel&gid=' . $gInfo->unique_id,'NONSSL') . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      $heading[] = array('text' => '[' . $gInfo->unique_id . '] ' . oos_datetime_short($gInfo->date_created) . ' ' . $currencies->format($gInfo->amount));

      $contents[] = array('align' => 'center','text' => '<a href="' . oos_href_link_admin('gv_queue.php','action=release&gid=' . $gInfo->unique_id,'NONSSL'). '">' . oos_image_swap_button('release','release_off.gif', IMAGE_RELEASE) . '</a>');
      break;
   }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/nice_exit.php'; ?>