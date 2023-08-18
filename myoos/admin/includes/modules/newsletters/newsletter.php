<?php
/**
   ----------------------------------------------------------------------
   $Id: newsletter.php,v 1.1 2007/06/08 14:09:43 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletter.php,v 1.1 2002/03/08 18:38:18 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// todo:
// remove link http://exampel.org/shop/index.php?action=process&newsletter=remove&email_address=

class newsletter
{
    public $show_choose_audience = false;

    public function __construct(public $title, public $content)
    {
    }

    public function choose_audience()
    {
        return false;
    }

    public function confirm()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        $mail_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['newsletter_recipients'] . " WHERE status = '1'");

        $mail = $mail_result->fields;

        $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $mail['total']) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br((string) $this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td align="right"><a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . '">' . oos_button(IMAGE_SEND) . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_button(BUTTON_CANCEL) . '</a></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

        return $confirm_string;
    }

    public function send($newsletter_id)
    {


        // (Re)create it, if it's gone missing
        if (!is_object($mail) || !is_a($mail, 'PHPMailer')) {
            include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/class.phpmailer.php';
            include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/class.smtp.php';
            // Instantiate a new mail object
            $mail = new PHPMailer(true);
        }

        // Empty out the values that may be set
        $oEmail->ClearAllRecipients();
        $oEmail->ClearAttachments();
        $oEmail->ClearCustomHeaders();
        $oEmail->ClearReplyTos();

        /*
                $sLang = ...;
                $oEmail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';
                $oEmail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );
        */
        $oEmail->CharSet = CHARSET;

        $oEmail->IsMail();
        $oEmail->From = STORE_OWNER_EMAIL_ADDRESS;
        $oEmail->FromName = STORE_OWNER;
        $oEmail->Mailer = EMAIL_TRANSPORT;

        // Add smtp values if needed
        if (EMAIL_TRANSPORT == 'smtp') {
            $oEmail->IsSMTP(); // set mailer to use SMTP
            $oEmail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
            $oEmail->Username = OOS_SMTPUSER; // SMTP username
            $oEmail->Password = OOS_SMTPPASS; // SMTP password
            $oEmail->Host     = OOS_SMTPHOST; // specify main and backup server
        } elseif // Set sendmail path
            (EMAIL_TRANSPORT == 'sendmail') {
            if (!oos_empty(OOS_SENDMAIL)) {
                $oEmail->Sendmail = OOS_SENDMAIL;
                $oEmail->IsSendmail();
            }
        }

        $oEmail->Subject = $this->title;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $sql = "SELECT customers_gender, customers_firstname, customers_lastname, customers_email_address 
              FROM " . $oostable['newsletter_recipients'] . " 
              WHERE status = '1'";
        $mail_result = $dbconn->Execute($sql);

        while ($mail = $mail_result->fields) {
            $oEmail->Body = $this->content;
            $oEmail->AddAddress($mail['customers_email_address'], $mail['customers_firstname'] . ' ' . $mail['customers_lastname']);
            $oEmail->Send();
            // Clear all addresses and attachments for next loop
            $oEmail->ClearAddresses();
            $oEmail->ClearAttachments();

            // Move that ADOdb pointer!
            $mail_result->MoveNext();
        }

        $newsletter_id = oos_db_prepare_input($newsletter_id);
        $dbconn->Execute("UPDATE " . $oostable['newsletters'] . " SET date_sent = now(), status = '1' WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");
    }
}
