<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: mail.php,v 1.30 2002/03/16 01:07:28 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$action = (isset($_GET['action']) ? oos_prepare_input($_GET['action']) : '');
$sCustomer = isset($_GET['customer']) ? oos_prepare_input($_GET['customer']) : '';


if (($action == 'send_email_to_user') && isset($_POST['customers_email_address']) && !isset($_POST['back_x'])) {
    switch ($_POST['customers_email_address']) {
    case '***':
        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . $oostable['customers']);
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;
    /* toto Newsletter
      case '**D':
        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . $oostable['customers'] . " WHERE customers_newsletter = '1'");
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;
    */
    default:
        $customers_email_address = oos_db_prepare_input($_POST['customers_email_address']);

        $mail_result = $dbconn->Execute("SELECT customers_firstname, customers_lastname, customers_email_address FROM " . $oostable['customers'] . " WHERE customers_email_address = '" . oos_db_input($customers_email_address) . "'");
        $mail_sent_to = oos_db_prepare_input($_POST['customers_email_address']);
        break;
    }

    // Instantiate a new mail object
    // (Re)create it, if it's gone missing.
    if (! ($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/PHPMailer.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/SMTP.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/Exception.php';
        $send_mail = new PHPMailer\PHPMailer\PHPMailer(true);
    }




    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');
    $send_mail->SetLanguage($sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/');

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
    } elseif // Set sendmail path
      (EMAIL_TRANSPORT == 'sendmail') {
        if (!oos_empty(OOS_SENDMAIL)) {
            $send_mail->Sendmail = OOS_SENDMAIL;
            $send_mail->IsSendmail();
        }
    }

    $send_mail->Subject = $subject;

    while ($mail = $mail_result->fields) {
        $send_mail->Body = $message;
        $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
        $send_mail->Send();
        $send_mail->ClearAddresses();
        $send_mail->ClearAttachments();

        // Move that ADOdb pointer!
        $mail_result->MoveNext();
    }
    oos_redirect_admin(oos_href_link_admin($aContents['mail'], 'mail_sent_to=' . urlencode($mail_sent_to)));
}

if (($action == 'preview') && !isset($_POST['customers_email_address'])) {
    $messageStack->add(ERROR_NO_CUSTOMER_SELECTED, 'error');
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
                            <?php echo '<a href="' . oos_href_link_admin($aContents['mail'], 'selected_box=tools') . '">' . BOX_HEADING_TOOLS . '</a>'; ?>
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
if (($action == 'preview') && isset($_POST['customers_email_address'])) {
    switch ($_POST['customers_email_address']) {
    case '***':
        $mail_sent_to = TEXT_ALL_CUSTOMERS;
        break;

    case '**D':
        $mail_sent_to = TEXT_NEWSLETTER_CUSTOMERS;
        break;

    default:
        $mail_sent_to =  oos_db_prepare_input($_POST['customers_email_address']);
        break;
    } ?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['mail'], 'action=send_email_to_user', 'post', true); ?>
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
                <td class="smallText"><b><?php echo TEXT_MESSAGE; ?></b><br><?php echo nl2br(htmlspecialchars(stripslashes((string)$_POST['message'])), ENT_QUOTES, 'UTF-8'); ?></td>
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
    } ?>
                <table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td><?php echo oos_submit_button(BUTTON_BACK); ?></td>
                    <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['mail']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>' . oos_submit_button(IMAGE_SEND_EMAIL); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </form></tr>
    <?php
} else {
        ?>
          <tr><?php echo oos_draw_form('id', 'mail', $aContents['mail'], 'action=preview', 'post', false); ?>
            <td><table border="0" cellpadding="0" cellspacing="2">
              <tr>
                <td colspan="2"></td>
              </tr>
    <?php
    $customers = [];
        $customers[] = array('id' => '', 'text' => TEXT_SELECT_CUSTOMER);
        $customers[] = array('id' => '***', 'text' => TEXT_ALL_CUSTOMERS);
        $customers[] = array('id' => '**D', 'text' => TEXT_NEWSLETTER_CUSTOMERS);
        $mail_result = $dbconn->Execute("SELECT customers_email_address, customers_firstname, customers_lastname FROM " . $oostable['customers'] . " ORDER BY customers_lastname");
        while ($customers_values = $mail_result->fields) {
            $customers[] = array('id' => $customers_values['customers_email_address'],
                     'text' => $customers_values['customers_lastname'] . ', ' . $customers_values['customers_firstname'] . ' (' . $customers_values['customers_email_address'] . ')');

            // Move that ADOdb pointer!
            $mail_result->MoveNext();
        } ?>
              <tr>
                <td class="main"><?php echo TEXT_CUSTOMER; ?></td>
                <td><?php echo oos_draw_pull_down_menu('customers_email_address', $customers, $sCustomer); ?></td>
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
                <td valign="top" class="main"><?php echo TEXT_MESSAGE; ?></td>
                <td><?php echo oos_draw_textarea_field('message', 'soft', '60', '15'); ?></td>
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
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>

<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>