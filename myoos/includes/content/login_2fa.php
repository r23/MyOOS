<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

if ($_SESSION['google2fa_count'] > 6) {
    oos_redirect(oos_href_link($aContents['403']));
}

if (!isset($_SESSION['customer_2fa_id'])) {
    oos_redirect(oos_href_link($aContents['login']));
}

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new oosUser();
    $_SESSION['user']->anonymous();
}

use PragmaRX\Google2FA\Google2FA;

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/login_2fa.php';


// Create the 2FA class
$google2fa = new PragmaRX\Google2FA\Google2FA();

if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $code = oos_db_prepare_input($_POST['code']);

    if (empty($code) || !is_string($code)) {
        $_SESSION['error_message'] = $aLang['text_code_error'];
        oos_redirect(oos_href_link($aContents['login']));
    }

    $code = str_replace(" ", "", (string) $code);

    if (strlen($code ?? '') < 6) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_code_error']);
    }

    $bError = false; // reset error flag

    // Check
    $customerstable = $oostable['customers'];
    $sql = "SELECT customers_2fa, customers_2fa_active
            FROM $customerstable
            WHERE customers_login = '1'
			  AND customers_2fa_active = '1'
              AND customers_id = '" . intval($_SESSION['customer_2fa_id']) . "'";
    $check_customer_result = $dbconn->Execute($sql);

    if (!$check_customer_result->RecordCount()) {
        $_SESSION['error_message'] = $aLang['text_code_error'];
        oos_redirect(oos_href_link($aContents['login']));
    } else {
        $check_customer = $check_customer_result->fields;

        $sKey = $check_customer['customers_2fa'];

        $window = 8; // 8 keys (respectively 4 minutes) past and future

        $valid = $google2fa->verifyKey($sKey, $code, $window);

        if ($valid) {
            oos_redirect(oos_href_link($aContents['login_process'], 'formid=' . $_SESSION['formid'] . '&action=process'));
        } else {
            $bError = true;

            if (!isset($_SESSION['google2fa_count'])) {
                $_SESSION['google2fa_count'] = 1;
            } else {
                $_SESSION['google2fa_count'] ++;
            }

            $oMessage->add('danger', $aLang['text_code_error']);
        }
    }
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['login']));
$sCanonical = oos_href_link($aContents['login'], '', false, true);


$aTemplate['page'] = $sTheme . '/page/login_2fa.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'    => $aLang['navbar_title'], 'robots'            => 'noindex,follow,noodp,noydir', 'login_active'    => 1, 'canonical'        => $sCanonical]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
