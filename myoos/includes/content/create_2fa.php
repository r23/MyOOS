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

if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = new oosUser();
    $_SESSION['user']->anonymous();
}

if (isset($_SESSION['customer_id'])) {
    $_SESSION['customer_2fa_id'] = intval($_SESSION['customer_id']);
}

if (!isset($_SESSION['customer_2fa_id'])) {
    oos_redirect(oos_href_link($aContents['login']));
}

use PragmaRX\Google2FA\Google2FA;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/create_2fa.php';


// Create the 2FA class
$google2fa = new PragmaRX\Google2FA\Google2FA();

if (isset($_POST['action']) && ($_POST['action'] == 'process')
    && (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']))
) {
    $code = oos_db_prepare_input($_POST['code']);
    $sKey = oos_db_prepare_input($_SESSION['secretKey']);

    if (empty($code) || !is_string($code)) {
        $_SESSION['error_message'] = $aLang['text_code_error'];
        oos_redirect(oos_href_link($aContents['login']));
    }

    if (empty($sKey) || !is_string($sKey)) {
        $_SESSION['error_message'] = $aLang['text_code_error'];
        oos_redirect(oos_href_link($aContents['login']));
    }

    $bError = false; // reset error flag

    $code = str_replace(" ", "", (string) $code);

    if (strlen($code ?? '') < 6) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_code_error']);
    }

    $window = 8; // 8 keys (respectively 4 minutes) past and future

    $valid = $google2fa->verifyKey($sKey, $code, $window);

    if (!$valid) {
        $bError = true;
        $oMessage->add('danger', $aLang['entry_code_error']);
    }

    if ($bError == false) {
        $_SESSION['success_message'] = $aLang['entry_2fa_success'];
        $sql_data_array = ['customers_2fa' => $sKey, 'customers_2fa_active' => 1];
        oos_db_perform($oostable['customers'], $sql_data_array, 'UPDATE', "customers_id = '" . intval($_SESSION['customer_2fa_id']) . "'");

        oos_redirect(oos_href_link($aContents['login_process'], 'formid=' . $_SESSION['formid'] . '&action=process'));
    }
}


$customerstable = $oostable['customers'];
$sql = "SELECT customers_email_address
		FROM $customerstable
		WHERE customers_login = '1'
			AND customers_id = '" . intval($_SESSION['customer_2fa_id']) . "'";
$check_customer_result = $dbconn->Execute($sql);
if (!$check_customer_result->RecordCount()) {
    oos_redirect(oos_href_link($aContents['403']));
} else {
    $check_customer = $check_customer_result->fields;

    $companyName = STORE_NAME;
    $companyEmail = $check_customer['customers_email_address'];
    $secretKey = $google2fa->generateSecretKey();

    $_SESSION['secretKey'] = $secretKey;



    $g2faUrl = $google2fa->getQRCodeUrl(
        $companyName,
        $companyEmail,
        $secretKey
    );

    $writer = new PngWriter();

    // Create QR code
    $qrCode = QrCode::create($g2faUrl)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
        ->setSize(100)
        ->setMargin(10)
        ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
        ->setForegroundColor(new Color(0, 0, 0))
        ->setBackgroundColor(new Color(255, 255, 255));

    $result = $writer->write($qrCode);
    $dataUri = $result->getDataUri();
}

// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['login']));
$sCanonical = oos_href_link($aContents['login'], '', false, true);


$aTemplate['page'] = $sTheme . '/page/create_2fa.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'    => $aLang['navbar_title'], 'robots'            => 'noindex,follow,noodp,noydir', 'login_active'    => 1, 'canonical'        => $sCanonical, 'secretKey'        => $secretKey, 'qrcode'             => $dataUri]
);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");

// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
