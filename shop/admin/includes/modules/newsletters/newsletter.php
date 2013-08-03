<?php
/* ----------------------------------------------------------------------
   $Id: newsletter.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletter.php,v 1.1 2002/03/08 18:38:18 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class newsletter {
    var $show_choose_audience, $title, $content;

    function newsletter($title, $content) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      return false;
    }

    function confirm() {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      $aFilename = oos_get_filename();

      $mail_result1 = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['customers'] . " WHERE customers_newsletter = '1'");
      $mail_result2 = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['maillist'] . " WHERE customers_newsletter = '1'");

      $mail1 = $mail_result1->fields;
      $mail2 = $mail_result2->fields;


      $mail['total'] = ($mail1['total'] + $mail2['total'] );

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['total']) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . oos_draw_separator('trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . oos_draw_separator('trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . oos_draw_separator('trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . '">' . oos_image_button('send_off.gif', IMAGE_SEND) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_button('cancel_off.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {

      // Get database information
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables(); 

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

      $send_mail->Subject = $this->title;

      $sql = "SELECT customers_firstname, customers_lastname, customers_email_address 
              FROM " . $oostable['customers'] . " 
              WHERE customers_newsletter = '1'";
      $mail_result = $dbconn->Execute($sql);

      while ($mail = $mail_result->fields) {
        $send_mail->Body = $this->content;
        $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
        $send_mail->Send();
        // Clear all addresses and attachments for next loop
        $send_mail->ClearAddresses();
        $send_mail->ClearAttachments();

        // Move that ADOdb pointer!
        $mail_result->MoveNext();
      }

      $sql = "SELECT customers_firstname, customers_lastname, customers_email_address 
              FROM " . $oostable['maillist'] . " 
              WHERE customers_newsletter = '1'";
      $mail_result2 = $dbconn->Execute($sql);

      while ($mail = $mail_result2->fields) {
        $send_mail->Body = $this->content;
        $send_mail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
        $send_mail->Send();
        // Clear all addresses and attachments for next loop
        $send_mail->ClearAddresses();
        $send_mail->ClearAttachments();

        // Move that ADOdb pointer!
        $mail_result2->MoveNext();
      }


      $newsletter_id = oos_db_prepare_input($newsletter_id);
      $dbconn->Execute("UPDATE " . $oostable['newsletters'] . " SET date_sent = now(), status = '1' WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");
    }
  }
?>
