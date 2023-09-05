<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.48 2003/02/13 03:10:55 hpdl
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

// require  the password crypto functions
require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_password.php';
require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_password_forgotten.php';

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}


// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $email_address = oos_db_prepare_input($_POST['email_address']);

    if (empty($email_address) || !is_string($email_address)) {
        $_SESSION['error_message'] = $aLang['text_no_email_address_found'];
        oos_redirect(oos_href_link($aContents['password_forgotten']));
    }

    if (!isset($_SESSION['password_forgotten_count'])) {
        $_SESSION['password_forgotten_count'] = 1;
    } else {
        $_SESSION['password_forgotten_count'] ++;
    }

    if ($_SESSION['password_forgotten_count'] > 3) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $customerstable = $oostable['customers'];
    $check_customer_sql = "SELECT customers_id, customers_gender, customers_firstname, customers_lastname
                           FROM $customerstable
						   WHERE customers_login = '1'
							AND customers_email_address = '" . oos_db_input($email_address) . "'";
    $check_customer_result = $dbconn->Execute($check_customer_sql);

    if ($check_customer_result->RecordCount()) {
        $check_customer = $check_customer_result->fields;

        // Crypted password mods - create a new password, update the database and mail it to them
        $newpass = oos_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
        $crypted_password = oos_encrypt_password($newpass);

        $customerstable = $oostable['customers'];
        $dbconn->Execute(
            "UPDATE $customerstable
                        SET customers_password = '" . oos_db_input($crypted_password) . "'
                        WHERE customers_id = '" . intval($check_customer['customers_id']) . "'"
        );

        $customers_name = $check_customer['customers_firstname'] . '. ' . $check_customer['customers_lastname'];

        $sGreet = match ($check_customer['customers_gender']) {
            'm' => sprintf($aLang['email_greet_mr'], $customers_name),
            'f' => sprintf($aLang['email_greet_ms'], $customers_name),
            default => $aLang['email_greet_none'],
        };

        //smarty
        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
        $smarty = new myOOS_Smarty();

        // dont allow cache
        $smarty->caching = false;

        $smarty->assign(
            ['shop_name'       => STORE_NAME, 'shop_url'        => OOS_HTTPS_SERVER . OOS_SHOP, 'shop_logo'       => STORE_LOGO, 'services_url'    => PHPBB_URL, 'blog_url'        => BLOG_URL, 'imprint_url'     => oos_href_link($aContents['information'], 'information_id=1', false, true), 'login_url'       => oos_href_link($aContents['login'], '', false, true), 'greet'           => $sGreet, 'password'        => $newpass]
        );

        // create mails
        $email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/password_forgotten.html');
        $email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/password_forgotten.tpl');

        oos_mail($check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], $email_address, $aLang['email_password_reminder_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        $_SESSION['password_forgotten_count'] = 1;
        $_SESSION['success_message'] = $aLang['text_password_sent'];
        oos_redirect(oos_href_link($aContents['login']));
    } else {
		#  $_SESSION['error_message'] = $aLang['text_no_email_address_found'];
		#  oos_redirect(oos_href_link($aContents['password_forgotten']));
		# no info for attackers
		$_SESSION['success_message'] = $aLang['text_password_sent'];
        oos_redirect(oos_href_link($aContents['login']));	
		
    }
} else {

    // links breadcrumb
    $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['login']));
    $oBreadcrumb->add($aLang['navbar_title_2'], oos_href_link($aContents['password_forgotten']));
    $sCanonical = oos_href_link($aContents['password_forgotten'], '', false, true);

    $aTemplate['page'] = $sTheme . '/page/user_password_forgotten.html';

    $nPageType = OOS_PAGE_TYPE_SERVICE;
    $sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    // assign Smarty variables;
    $smarty->assign(
        ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['heading_title'], 'robots'        => 'noindex,follow,noodp,noydir', 'canonical'        => $sCanonical]
    );

	// Send the CSP header with the nonce RANDOM_VALUE
	header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval'");


    // register the outputfilter
    $smarty->loadFilter('output', 'trimwhitespace');

    // display the template
    $smarty->display($aTemplate['page']);
}
