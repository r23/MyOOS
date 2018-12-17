<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.3.2.4 2003/05/12 22:54:01 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';
  require 'includes/functions/function_coupon.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if ( ($action == 'send_email_to_user') && ($_POST['customers_email_address'] || $_POST['email_to']) && (!$_POST['back_x']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $customerstable = $oostable['customers'];
        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM $customerstable");
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;

/* todo Newsletter
      case '**D':
        $customerstable = $oostable['customers'];
        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM $customerstable WHERE customers_newsletter = '1'");
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
*/
      default:
        $customers_email_address = oos_db_prepare_input($_POST['customers_email_address']);

        $customerstable = $oostable['customers'];
        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM $customerstable WHERE customers_email_address = '" . oos_db_input($customers_email_address) . "'");
        $mail_sent_to = $_POST['customers_email_address'];
        if ( (isset($_POST['email_to'])) && (!empty($_POST['email_to'])) ) {
          $mail_sent_to = $_POST['email_to'];
        }
        break;
    }

    if ( ($action == 'send_email_to_user') && ($_POST['customers_email_address']) && (!$_POST['back_x']) ) {
      while ($mail = $mail_result->fields) {
        $id1 = oos_create_coupon_code($mail['customers_email_address']);
        $message = $_POST['message'];
        $message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount']) . "\n\n";
        $message .= TEXT_TO_REDEEM;
        $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
        $message .= OOS_HTTPS_SERVER  . OOS_SHOP . 'index.php?content=' . $aCatalog['gv_redeem'] . '&gv_no=' . $id1 . "\n\n";
        $message .= TEXT_OR_VISIT . OOS_HTTPS_SERVER  . OOS_SHOP . TEXT_ENTER_CODE;

        //Let's build a message object using the email class
        $send_mail = new PHPMailer();

/*
        $send_mail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';

        $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');
        $send_mail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );
*/
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
        $send_mail->Body = $message;
        $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
        $send_mail->Send();
        $send_mail->ClearAddresses();
        $send_mail->ClearAttachments();

        // Now create the coupon main and email entry
        $couponstable = $oostable['coupons'];
        $insert_result = $dbconn->Execute("INSERT INTO $couponstable (coupon_code, coupon_type, coupon_amount, date_created) VALUES ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
        $insert_id = $dbconn->Insert_ID();
        $coupon_email_tracktable = $oostable['coupon_email_track'];
        $insert_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) VALUES ('" . $insert_id ."', '0', 'Admin', '" . $mail['customers_email_address'] . "', now() )");
        // Move that ADOdb pointer!
        $mail_result->MoveNext();
      }
    } elseif (isset($_POST['email_to']) && (!$_POST['back_x'])) {
      $id1 = oos_create_coupon_code($_POST['email_to']);
      $message = oos_db_prepare_input($_POST['message']);
      $message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount']) . "\n\n";
      $message .= TEXT_TO_REDEEM;
      $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
      $message .= OOS_HTTPS_SERVER  . OOS_SHOP . 'index.php?content=' . $aCatalog['gv_redeem'] . '&gv_no=' . $id1 . "\n\n";
      $message .= TEXT_OR_VISIT . OOS_HTTPS_SERVER  . OOS_SHOP  . TEXT_ENTER_CODE;

      //Let's build a message object using the email class
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
      $send_mail->Body = $message;
      $send_mail->AddAddress($_POST['email_to'], 'Friend');
      $send_mail->Send();
      $send_mail->ClearAddresses();
      $send_mail->ClearAttachments();
      // Now create the coupon email entry
      $couponstable = $oostable['coupons'];
      $insert_result = $dbconn->Execute("INSERT INTO $couponstable (coupon_code, coupon_type, coupon_amount, date_created) VALUES ('" . $id1 . "', 'G', '" . $_POST['amount'] . "', now())");
      $insert_id = $dbconn->Insert_ID();
      $coupon_email_tracktable = $oostable['coupon_email_track'];
      $insert_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) VALUES ('" . $insert_id ."', '0', 'Admin', '" . $_POST['email_to'] . "', now() )"); 
    }

    oos_redirect_admin(oos_href_link_admin($aContents['gv_mail'], 'mail_sent_to=' . urlencode($mail_sent_to)));
  }

  if ( ($action == 'preview') && (!$_POST['customers_email_address']) && (!$_POST['email_to']) ) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
  }

  if ( ($action == 'preview') && (!$_POST['amount']) ) {
    $messageStack->add(ERROR_NO_AMOUNT_SELECTED, 'error');
  }

  if (isset($_GET['mail_sent_to'])) {
    $messageStack->add(sprintf(NOTICE_EMAIL_SENT_TO, $_GET['mail_sent_to']), 'notice');
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
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<a href="<?php echo oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
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
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($action == 'preview') && ($_POST['customers_email_address'] || $_POST['email_to']) ) {
    switch ($_POST['customers_email_address']) {
      case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;

      case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;

      default:
        $mail_sent_to = $_POST['customers_email_address'];
        if ( (isset($_POST['email_to'])) && (!empty($_POST['email_to'])) ) {
          $mail_sent_to = $_POST['email_to'];
        }
        break;
    }
?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['gv_mail'], 'action=send_email_to_user', 'post', FALSE); ?>
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
                <td class="smallText"><b><?php echo TEXT_AMOUNT; ?></b><br /><?php echo nl2br(htmlspecialchars(stripslashes($_POST['amount']))); ?></td>
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
    foreach ($_POST as $key => $value) {		
      if (!is_array($_POST[$key])) {
        echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
      }
    }
?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php echo oos_submit_button('back', IMAGE_BACK, 'name="back"'); ?></td>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['gv_mail']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a> ' . oos_submit_button('send_mail', IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
<?php
  } else {
?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['gv_mail'], 'action=preview', 'post', FALSE); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"></td>
              </tr>
<?php
    $customers = array();
    $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
    $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
    $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);

    $customerstable = $oostable['customers'];
    $mail_result = $dbconn->Execute("SELECT customers_email_address, customers_firstname, customers_lastname FROM $customerstable ORDER BY customers_lastname");
    while($customers_values = $mail_result->fields) {
      $customers[] = array('id' => $customers_values['customers_email_address'],
                           'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');

      // Move that ADOdb pointer!
      $mail_result->MoveNext();
    }
?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo oos_draw_pull_down_menu('customers_email_address', $customers, $_GET['customer']);?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
               <tr>
                <td class="main"><?php echo TEXT_TO; ?></td>
                <td><?php echo oos_draw_input_field('email_to'); ?></td>
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
                <td class="main"><?php echo TEXT_SUBJECT; ?></td>
                <td><?php echo oos_draw_input_field('subject'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_AMOUNT; ?></td>
                <td><?php echo oos_draw_input_field('amount'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
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
<?php
  }
?>

        </table></td>
      </tr>
    </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2018 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>