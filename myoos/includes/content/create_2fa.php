<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
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

# Create the 2FA class
$google2fa = new PragmaRX\Google2FA\Google2FA();

# Create data that will go into the QR code
# The title will show up first in the authenticator app, 
# followed by the username wrapped in `()`

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

	$customerstable = $oostable['customers'];
	$dbconn->Execute("UPDATE $customerstable
					SET customers_2fa = '" . oos_db_input($secretKey) . "'
					WHERE customers_id = '" . intval($_SESSION['customer_2fa_id']) . "'");

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
		->setSize(300)
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
    require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    array(
          'breadcrumb'		=> $oBreadcrumb->trail(),
          'heading_title'	=> $aLang['navbar_title'],
          'robots'			=> 'noindex,follow,noodp,noydir',
          'login_active'	=> 1,

          'canonical'		=> $sCanonical,
		  'secretKey'		=> $secretKey,
		  'qrcode' 			=> $dataUri
      )
);

// display the template
$smarty->display($aTemplate['page']);
