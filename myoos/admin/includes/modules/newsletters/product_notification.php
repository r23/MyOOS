<?php
/**
   ----------------------------------------------------------------------
   $Id: product_notification.php,v 1.1 2007/06/08 14:09:43 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_notification.php,v 1.6 2002/11/22 18:56:08 dgw_
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

class product_notification
{
    public $show_choose_audience;
    public $title;
    public $content;

    public function product_notification($title, $content)
    {
        $this->show_choose_audience = true;
        $this->title = $title;
        $this->content = $content;
    }

    public function choose_audience()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        $products_array = [];
        $products_result = $dbconn->Execute("SELECT pd.products_id, pd.products_name FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND pd.products_id = p.products_id AND p.products_status >= '1' ORDER BY pd.products_name");
        while ($products = $products_result->fields) {
            $products_array[] = array('id' => $products['products_id'],
                                'text' => $products['products_name']);

            // Move that ADOdb pointer!
            $products_result->MoveNext();
        }

        $choose_audience_string = '<script>
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {
  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
</script>';

        $choose_audience_string .= '<form name="notifications" action="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                               '  <tr>' . "\n" .
                               '    <td align="center" class="main"><b>' . TEXT_PRODUCTS . '</b><br>' . oos_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                               '    <td align="center" class="main">&nbsp;<br><a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm&GLOBAL=true') . '"><input type="button" value="' . BUTTON_GLOBAL . '" style="width: 8em;"></a><br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br><input type="submit" value="' . BUTTON_SUBMIT . '" style="width: 8em;"><br><br><a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '"><input type="button" value="' . BUTTON_CANCEL . '" style="width: 8em;"></a></td>' . "\n" .
                               '    <td align="center" class="main"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . oos_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                               '  </tr>' . "\n" .
                               '</table></form>';

        return $choose_audience_string;
    }

    public function confirm()
    {
        $audience = [];

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $aContents = oos_get_content();

        if ($_GET['GLOBAL'] == 'true') {
            $products_result = $dbconn->Execute("SELECT distinct customers_id FROM " . $oostable['products_notifications']);
            while ($products = $products_result->fields) {
                $audience[$products['customers_id']] = '1';

                // Move that ADOdb pointer!
                $products_result->MoveNext();
            }

            $customers_result = $dbconn->Execute("SELECT customers_info_id FROM " . $oostable['customers_info'] . " WHERE GLOBAL_product_notifications = '1'");
            while ($customers = $customers_result->fields) {
                $audience[$customers['customers_info_id']] = '1';

                // Move that ADOdb pointer!
                $customers_result->MoveNext();
            }
        } else {
            $chosen =  oos_db_prepare_input($_POST['chosen']);

            $ids = implode(',', $chosen);

            $products_result = $dbconn->Execute("SELECT DISTINCT customers_id FROM " . $oostable['products_notifications'] . " WHERE products_id in (" . $ids . ")");
            while ($products = $products_result->fields) {
                $audience[$products['customers_id']] = '1';
                $products_result->MoveNext();
            }

            $customers_result = $dbconn->Execute("SELECT customers_info_id FROM " . $oostable['customers_info'] . " WHERE GLOBAL_product_notifications = '1'");
            while ($customers = $customers_result->fields) {
                $audience[$customers['customers_info_id']] = '1';

                // Move that ADOdb pointer!
                $customers_result->MoveNext();
            }
        }

        $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, count($audience)) . '</b></font></td>' . "\n" .
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
                      '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . "\n" .
                      '    <td></td>' . "\n" .
                      '  </tr>' . "\n" .
                      '  <tr>' . oos_draw_form('id', 'confirm', $aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send', 'post', false) . "\n" .
                      '    <td align="right">';
        if (count($audience) > 0) {
            if ($_GET['GLOBAL'] == 'true') {
                $confirm_string .= oos_draw_hidden_field('GLOBAL', 'true');
            } else {
                for ($i = 0, $n = count($chosen); $i < $n; $i++) {
                    $confirm_string .= oos_draw_hidden_field('chosen[]', $chosen[$i]);
                }
            }
            $confirm_string .= oos_button(IMAGE_SEND) . ' ';
        }
        $confirm_string .= '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=send') . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a></td>' . "\n" .
                       '  </tr>' . "\n" .
                       '</table>';

        return $confirm_string;
    }

    public function send($newsletter_id)
    {
        $audience = [];

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        if ($_POST['GLOBAL'] == 'true') {
            $products_result = $dbconn->Execute("SELECT distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM " . $oostable['customers'] . " c, " . $oostable['products_notifications'] . " pn WHERE c.customers_id = pn.customers_id");
            while ($products = $products_result->fields) {
                $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                     'lastname' => $products['customers_lastname'],
                                                     'email_address' => $products['customers_email_address']);
                // Move that ADOdb pointer!
                $products_result->MoveNext();
            }

            $customers_result = $dbconn->Execute("SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM " . $oostable['customers'] . " c, " . $oostable['customers_info'] . " ci WHERE c.customers_id = ci.customers_info_id AND ci.GLOBAL_product_notifications = '1'");
            while ($customers = $customers_result->fields) {
                $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                      'lastname' => $customers['customers_lastname'],
                                                      'email_address' => $customers['customers_email_address']);
                // Move that ADOdb pointer!
                $customers_result->MoveNext();
            }
        } else {
            $chosen =  oos_db_prepare_input($_POST['chosen']);

            $ids = implode(',', $chosen);

            $products_result = $dbconn->Execute("SELECT distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM " . $oostable['customers'] . " c, " . $oostable['products_notifications'] . " pn WHERE c.customers_id = pn.customers_id AND pn.products_id in (" . $ids . ")");
            while ($products = $products_result->fields) {
                $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                     'lastname' => $products['customers_lastname'],
                                                     'email_address' => $products['customers_email_address']);
                // Move that ADOdb pointer!
                $products_result->MoveNext();
            }

            $customers_result = $dbconn->Execute("SELECT c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address FROM " . $oostable['customers'] . " c, " . $oostable['customers_info'] . " ci WHERE c.customers_id = ci.customers_info_id AND ci.GLOBAL_product_notifications = '1'");
            while ($customers = $customers_result->fields) {
                $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                      'lastname' => $customers['customers_lastname'],
                                                      'email_address' => $customers['customers_email_address']);
                // Move that ADOdb pointer!
                $customers_result->MoveNext();
            }
        }

        $send_mail = new PHPMailer();

        $send_mail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';

        $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');
        $send_mail->SetLanguage($sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/');

        $send_mail->CharSet = CHARSET;
        $send_mail->IsMail();

        $send_mail->From = STORE_OWNER_EMAIL_ADDRESS;
        $send_mail->FromName = STORE_OWNER;
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

        $send_mail->Subject = $this->title;

        reset($audience);
        foreach ($audience as $key => $value) {
            $send_mail->Body = $this->content;
            $send_mail->AddAddress($value['email_address'], $value['firstname'] . ' ' . $value['lastname']);
            $send_mail->Send();
            // Clear all addresses and attachments for next loop
            $send_mail->ClearAddresses();
            $send_mail->ClearAttachments();
        }

        $newsletter_id = oos_db_prepare_input($newsletter_id);
        $dbconn->Execute("UPDATE " . $oostable['newsletters'] . " SET date_sent = now(), status = '1' WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");
    }
}
