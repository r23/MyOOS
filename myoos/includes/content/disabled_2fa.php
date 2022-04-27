<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: password_forgotten.php,v 1.48 2003/02/13 03:10:55 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if (!isset($_SESSION['customer_id'])) {
    oos_redirect(oos_href_link($aContents['login']));
}


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/disabled_2fa.php';


if (isset($_POST['action']) && ($_POST['action'] == 'process') &&
    (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))) {



    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_gender, customers_firstname, customers_lastname, 
                   customers_language, customers_email_address
            FROM $customerstable
            WHERE customers_login = '1'
              AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if ($check_customer_result->RecordCount()) {
		$sKey = '';		
		$sql_data_array = array('customers_2fa' => $sKey,
								'customers_2fa_active' => 0);
		oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_id']) . "'");		

		
/*
todo sent mail to user!

        $customers_name = $check_customer['customers_firstname'] . '. ' . $check_customer['customers_lastname'];

        switch ($check_customer['customers_gender']) {
            case 'm':
                $sGreet = sprintf($aLang['email_greet_mr'], $customers_name);
                break;
            case 'f':
                $sGreet = sprintf($aLang['email_greet_ms'], $customers_name);
                break;
            default:
                $sGreet = $aLang['email_greet_none'];
        }

        //smarty
        require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
        $smarty = new myOOS_Smarty();

        // dont allow cache
        $smarty->caching = false;

        $smarty->assign(
            array(
                'shop_name'		=> STORE_NAME,
                'shop_url'		=> OOS_HTTPS_SERVER . OOS_SHOP,
                'shop_logo'		=> STORE_LOGO,
                'services_url'	=> PHPBB_URL,
                'blog_url'		=> BLOG_URL,
                'imprint_url'	=> oos_href_link($aContents['information'], 'information_id=1', false, true),
                'login_url'		=> oos_href_link($aContents['login'], '', false, true),
                'greet'			=> $sGreet,
                'password' 		=> $newpass
            )
        );

        // create mails
        $email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/password_forgotten.html');
        $email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/password_forgotten.tpl');

        oos_mail($check_customer['customers_firstname'] . " " . $check_customer['customers_lastname'], $email_address, $aLang['email_password_reminder_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

        $_SESSION['password_forgotten_count'] = 1;
        $_SESSION['success_message'] = $aLang['text_password_sent'];
*/

		$_SESSION['error_message'] = $aLang['entry_2fa_success'];
		oos_redirect(oos_href_link($aContents['account']));

	}
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['account']));
$sCanonical = oos_href_link($aContents['disabled_2fa'], '', false, true);

$aTemplate['page'] = $sTheme . '/page/disabled_2fa.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
	require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
	require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
	array(
		'breadcrumb'    => $oBreadcrumb->trail(),
		'heading_title' => $aLang['heading_title'],
		'robots'		=> 'noindex,follow,noodp,noydir',
		'canonical'		=> $sCanonical
	)
);


// display the template
$smarty->display($aTemplate['page']);

