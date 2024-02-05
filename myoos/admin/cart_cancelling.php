<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.3.2.4 2003/05/12 22:54:01 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');

require 'includes/main.php';
require 'includes/functions/function_coupon.php';
require 'includes/classes/class_currencies.php';

$currencies = new currencies();

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$sCustomer = isset($_GET['customer']) ? oos_prepare_input($_GET['customer']) : '';

if (($action == 'send_email_to_user') && ($_POST['customers_email_address'] || $_POST['email_to']) && (!isset($_POST['back_x']))) {
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
            $mail_sent_to = oos_db_prepare_input($_POST['customers_email_address']);
            if ((isset($_POST['email_to'])) && (!empty($_POST['email_to']))) {
                $mail_sent_to = oos_db_prepare_input($_POST['email_to']);
            }
            break;
    }

    if (($action == 'send_email_to_user') && ($_POST['customers_email_address']) && (!isset($_POST['back_x']))) {

        global $phpmailer;

        $phpmailer = new PHPMailer\PHPMailer\PHPMailer();

        $sLang = ($_SESSION['iso_639_1'] ?? DEFAULT_LANGUAGE_CODE);
        $phpmailer->setLanguage($sLang, MYOOS_INCLUDE_PATH . '/vendor/phpmailer/phpmailer/language/');


        $phpmailer->IsMail();

        $phpmailer->CharSet   = 'UTF-8';
        $phpmailer->Encoding  = 'base64';


        while ($mail = $mail_result->fields) {
            $id1 = oos_create_coupon_code($mail['customers_email_address']);
            $message =  oos_db_prepare_input($_POST['message']);
            $message .= "\n\n" . TEXT_GV_WORTH  . $currencies->format($_POST['amount']) . "\n\n";
            $message .= TEXT_TO_REDEEM;
            $message .= TEXT_WHICH_IS . $id1 . TEXT_IN_CASE . "\n\n";
            $message .= OOS_HTTPS_SERVER  . OOS_SHOP . 'index.php?content=' . $aCatalog['gv_redeem'] . '&gv_no=' . $id1 . "\n\n";
            $message .= TEXT_OR_VISIT . OOS_HTTPS_SERVER  . OOS_SHOP . TEXT_ENTER_CODE;


            // $phpmailer::$validator = static function ( $to_email_address ) {
            // return (bool) is_email( $to_email_address );
            // };

            // Empty out the values that may be set.
            $phpmailer->clearAllRecipients();
            $phpmailer->clearAttachments();
            $phpmailer->clearCustomHeaders();
            $phpmailer->clearReplyTos();

            $phpmailer->IsMail();
            $phpmailer->From = isset($_POST['from_mail']) ? oos_db_prepare_input($_POST['from_mail']) : STORE_OWNER_EMAIL_ADDRESS;
            $phpmailer->FromName = isset($_POST['from_name']) ? oos_db_prepare_input($_POST['from_name']) : STORE_OWNER;

            // Add smtp values if needed
            if (EMAIL_TRANSPORT == 'smtp') {
                $phpmailer->IsSMTP(); // set mailer to use SMTP

                // $phpmailer->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;

                $phpmailer->Host     = OOS_SMTPHOST; // specify main and backup server
                $phpmailer->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
                $phpmailer->Username = OOS_SMTPUSER; // SMTP username
                $phpmailer->Password = OOS_SMTPPASS; // SMTP password


                // Set the encryption mechanism to use:
                // - SMTPS (implicit TLS on port 465) or
                // - STARTTLS (explicit TLS on port 587)
                if (OOS_SMTPPORT == '465') {
                    $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                } elseif (OOS_SMTPPORT == '587') {
                    $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                }

                // Set the SMTP port number:
                // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
                // - 587 for SMTP+STARTTLS
                $phpmailer->Port = OOS_SMTPPORT;
            } else {
                // Set sendmail path
                if (EMAIL_TRANSPORT == 'sendmail') {
                    if (!oos_empty(OOS_SENDMAIL)) {
                        $phpmailer->Sendmail = OOS_SENDMAIL;
                        $phpmailer->IsSendmail();
                    }
                }
            }

            $phpmailer->Subject = isset($_POST['subject']) ? oos_db_prepare_input($_POST['subject']) : STORE_NAME;
            $phpmailer->Body = isset($_POST['message']) ? oos_db_prepare_input($_POST['message']) : '';

            $phpmailer->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
            $phpmailer->Send();
            $phpmailer->ClearAddresses();
            $phpmailer->ClearAttachments();

            // Now create the coupon main and email entry
            $couponstable = $oostable['coupons'];
            $insert_result = $dbconn->Execute("INSERT INTO $couponstable (coupon_code, coupon_type, coupon_amount, date_created) VALUES ('" . $id1 . "', 'G', '" . oos_db_input($_POST['amount']) . "', now())");
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

        // (Re)create it, if it's gone missing.
        $phpmailer = new PHPMailer\PHPMailer\PHPMailer();

        // load the appropriate language version
        $sLang = ($_SESSION['iso_639_1'] ?? DEFAULT_LANGUAGE_CODE);
        $phpmailer->setLanguage($sLang, MYOOS_INCLUDE_PATH . '/vendor/phpmailer/phpmailer/language/');

        // Empty out the values that may be set.
        $phpmailer->clearAllRecipients();
        $phpmailer->clearAttachments();
        $phpmailer->clearCustomHeaders();
        $phpmailer->clearReplyTos();

        $phpmailer->IsMail();

        $phpmailer->From = $from_mail ?: STORE_OWNER_EMAIL_ADDRESS;
        $phpmailer->FromName = $from_name ?: STORE_OWNER;
        $phpmailer->Mailer = EMAIL_TRANSPORT;

        // Add smtp values if needed
        if (EMAIL_TRANSPORT == 'smtp') {
            $phpmailer->IsSMTP(); // set mailer to use SMTP

            // $phpmailer->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;

            $phpmailer->Host     = OOS_SMTPHOST; // specify main and backup server
            $phpmailer->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
            $phpmailer->Username = OOS_SMTPUSER; // SMTP username
            $phpmailer->Password = OOS_SMTPPASS; // SMTP password


            // Set the encryption mechanism to use:
            // - SMTPS (implicit TLS on port 465) or
            // - STARTTLS (explicit TLS on port 587)
            if (OOS_SMTPPORT == '465') {
                $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            } elseif (OOS_SMTPPORT == '587') {
                $phpmailer->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            }

            // Set the SMTP port number:
            // - 465 for SMTP with implicit TLS, a.k.a. RFC8314 SMTPS or
            // - 587 for SMTP+STARTTLS
            $phpmailer->Port = OOS_SMTPPORT;

        } else {
            // Set sendmail path
            if (EMAIL_TRANSPORT == 'sendmail') {
                if (!oos_empty(OOS_SENDMAIL)) {
                    $phpmailer->Sendmail = OOS_SENDMAIL;
                    $phpmailer->IsSendmail();
                }
            }
        }

        $phpmailer->Subject = $subject;
        $phpmailer->Body = $message;
        $phpmailer->AddAddress($_POST['email_to'], 'Friend');
        $phpmailer->Send();
        $phpmailer->ClearAddresses();
        $phpmailer->ClearAttachments();


        // Now create the coupon email entry
        $couponstable = $oostable['coupons'];
        $insert_result = $dbconn->Execute("INSERT INTO $couponstable (coupon_code, coupon_type, coupon_amount, date_created) VALUES ('" . oos_db_input($id1) . "', 'G', '" . oos_db_input($_POST['amount']) . "', now())");
        $insert_id = $dbconn->Insert_ID();
        $coupon_email_tracktable = $oostable['coupon_email_track'];
        $insert_result = $dbconn->Execute("INSERT INTO $coupon_email_tracktable (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) VALUES ('" . $insert_id ."', '0', 'Admin', '" . oos_db_input($_POST['email_to']) . "', now() )");
    }

    oos_redirect_admin(oos_href_link_admin($aContents['gv_mail'], 'mail_sent_to=' . urlencode((string) $mail_sent_to)));
}

if (($action == 'preview') && (!$_POST['customers_email_address']) && (!$_POST['email_to'])) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
}

if (($action == 'preview') && (!$_POST['amount'])) {
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
<?php
if (($action == 'preview') && ($_POST['customers_email_address'] || $_POST['email_to'])) {
    switch ($_POST['customers_email_address']) {
        case '***':
            $mail_sent_to = TEXT_ALL_CUSTOMERS;
            break;

        case '**D':
            $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
            break;

        default:
            $mail_sent_to = oos_db_prepare_input($_POST['customers_email_address']);
            if ((isset($_POST['email_to'])) && (!empty($_POST['email_to']))) {
                $mail_sent_to =  oos_db_prepare_input($_POST['email_to']);
            }
            break;
    } ?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['gv_mail'], 'action=send_email_to_user', 'post', false); ?>
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
                <td class="smallText"><b><?php echo TEXT_FROM_NAME; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['from_name'])); ?></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_FROM_MAIL; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['from_mail'])); ?></td>
              </tr> 
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_SUBJECT; ?></b><br><?php echo htmlspecialchars(stripslashes((string)$_POST['subject'])); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_AMOUNT; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes((string)$_POST['amount']))); ?></td>
              </tr>
              <tr>
                <td></td>
              </tr>
              <tr>
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes((string)$_POST['message']))); ?></td>
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
            echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes((string)$value)));
        }
    } ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php echo oos_submit_button('back'); ?></td>
                    <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['gv_mail']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>' . oos_submit_button(IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
    <?php
} else {
    ?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['gv_mail'], 'action=preview', 'post', false); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"></td>
              </tr>
    <?php
    $customers = [];
    $customers[] = ['id' => '', 'text' => TEXT_SELECT_CUSTOMER];
    $customers[] = ['id' => '***', 'text' => TEXT_ALL_CUSTOMERS];
    $customers[] = ['id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS];

    $customerstable = $oostable['customers'];
    $mail_result = $dbconn->Execute("SELECT customers_email_address, customers_firstname, customers_lastname FROM $customerstable ORDER BY customers_lastname");
    while ($customers_values = $mail_result->fields) {
        $customers[] = ['id' => $customers_values['customers_email_address'], 'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')'];

        // Move that ADOdb pointer!
        $mail_result->MoveNext();
    } ?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo oos_draw_pull_down_menu('customers_email_address', '', $customers, $sCustomer); ?></td>
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
                <td><?php echo oos_draw_input_field('from_mail', STORE_OWNER_EMAIL_ADDRESS); ?></td></td>
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
                <td><?php echo oos_draw_textarea_field('', 'message', 'soft', '60', '15'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>
              <tr>
                <td colspan="2" align="right"><?php echo oos_submit_button(IMAGE_SEND_EMAIL); ?></td>
              </tr>
            </table></td>
          </form></tr>
    <?php
}
?>

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