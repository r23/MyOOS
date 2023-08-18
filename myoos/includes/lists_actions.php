<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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

$bError = false;

// Newsletter
if (isset($_GET['email_address'])) {
	$email_address = filter_input(INPUT_GET, 'email_address', FILTER_VALIDATE_EMAIL);
} else {
	$email_address = filter_input(INPUT_POST, 'email_address', FILTER_VALIDATE_EMAIL);
}

if (empty($email_address) || !is_string($email_address)) {
    $bError = true;
    $aInfoMessage[] = ['type' => 'danger', 'text' => $aLang['error_email_address']];
}

if (($bError === false) && (!is_email($email_address))) {
    $bError = true;
    $aInfoMessage[] = ['type' => 'danger', 'text' => $aLang['error_email_address']];
}

if (isset($_POST['newsletter'])
    && ($_POST['newsletter'] == 'subscriber')
    && ($bError === false)
) {
    $newsletter_recipients = $oostable['newsletter_recipients'];
    $sql = "SELECT recipients_id
              FROM $newsletter_recipients
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'
			  AND status = '1'";
    $check_recipients_result = $dbconn->Execute($sql);

    if ($check_recipients_result->RecordCount()) {
        $bError = true;
        $aInfoMessage[] = ['type' => 'danger', 'text' => $aLang['entry_email_address_error_exists']];
    } else {
        oos_newsletter_subscribe_mail($email_address);

        $aInfoMessage[] = ['type' => 'success', 'text' => $aLang['newsletter_email_info']];
    }
}



if (isset($_GET['newsletter'])
    && ($_GET['newsletter'] == 'remove')
    && ($bError === false)
) {
    $newsletter_recipients = $oostable['newsletter_recipients'];
    $sql = "SELECT recipients_id
              FROM $newsletter_recipients
              WHERE customers_email_address = '" . oos_db_input($email_address) . "'
			  AND status = '1'";
    $check_recipients_result = $dbconn->Execute($sql);

    if ($check_recipients_result->RecordCount()) {
        $result = $check_recipients_result->fields;
        $recipients_id = $result['recipients_id'];
        $newsletter_recipients = $oostable['newsletter_recipients'];
        $sql = "UPDATE $newsletter_recipients
               SET status = '0'
				WHERE recipients_id = '" . intval($recipients_id) . "'";
        $dbconn->Execute($sql);

        $newsletter_recipients_history = $oostable['newsletter_recipients_history'];
        $dbconn->Execute(
            "INSERT INTO $newsletter_recipients_history 
					(recipients_id,
					new_value,
					date_added) VALUES ('" . intval($recipients_id) . "',
									  '0',
                                      now())"
        );

        oos_redirect(oos_href_link($aContents['newsletter'], 'unsubscribe=success'));
    } else {
        $bError = true;
        $aInfoMessage[] = ['type' => 'danger', 'text' => $aLang['text_email_del_error']];
    }
}
