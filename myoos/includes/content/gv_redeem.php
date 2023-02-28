<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_redeem.php,v 1.3.2.1 2003/04/18 15:52:40 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001, 2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$b_gv_status = (defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true') ? true : false);
if ($b_gv_status === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// cookie-notice
if ($bNecessary === false) {
    oos_redirect(oos_href_link($aContents['home']));
}

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/gv_redeem.php';

$bError = true;
// check for a voucher number in the url
if ((isset($_GET['gv_no']) && !empty($_GET['gv_no']))) {
    $gv_no = oos_db_prepare_input($_GET['gv_no']);

    if (empty($gv_no) || !is_string($gv_no)) {
        oos_redirect(oos_href_link($aContents['403']));
    }

    $couponstable = $oostable['coupons'];
    $coupon_email_tracktable = $oostable['coupon_email_track'];
    $sql = "SELECT c.coupon_id, c.coupon_amount
            FROM $couponstable c,
                 $coupon_email_tracktable et
            WHERE coupon_code = '" . oos_db_input($gv_no) . "'
              AND c.coupon_id = et.coupon_id";
    $gv_result = $dbconn->Execute($sql);

    if ($gv_result->RecordCount() >0) {
        $coupon = $gv_result->fields;
        $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
        $sql = "SELECT coupon_id
              FROM $coupon_redeem_tracktable
              WHERE coupon_id = '" . oos_db_input($coupon['coupon_id']) . "'";
        $redeem_result = $dbconn->Execute($sql);
        if ($redeem_result->RecordCount() == 0) {
            $bError = false;
        }
    }
} else {
    // todo error-message
    oos_redirect(oos_href_link($aContents['home']));
}

if ((!$bError) && (isset($_SESSION['customer_id']))) {
    // Update redeem status
    $remote_addr = oos_server_get_remote();
    $coupon_redeem_tracktable = $oostable['coupon_redeem_track'];
    $gv_result = $dbconn->Execute(
        "INSERT INTO $coupon_redeem_tracktable
                            (coupon_id,
                             customer_id,
                             redeem_date,
                             redeem_ip) VALUES ('" . $coupon['coupon_id'] . "',
                                                '" . intval($_SESSION['customer_id']) . "',
                                                now(),
                                                '" . oos_db_input($remote_addr) . "')"
    );
    $couponstable = $oostable['coupons'];
    $gv_update = $dbconn->Execute(
        "UPDATE $couponstable
                               SET coupon_active = 'N' 
                               WHERE coupon_id = '" . $coupon['coupon_id'] . "'"
    );
    oos_gv_account_update($_SESSION['customer_id'], $coupon['coupon_id']);
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title']);

// if we get here then either the url gv_no was not set or it was invalid
// so output a message.
$sTextGiftVoucher = sprintf($aLang['text_valid_gv'], $oCurrencies->format($coupon['coupon_amount']));
if ($bError) {
    $sTextGiftVoucher =  sprintf($aLang['text_invalid_gv'], oos_href_link($aContents['contact_us']));
}

$aTemplate['page'] = $sTheme . '/page/redeem.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

$smarty->assign('text_information', sprintf($aLang['text_information'], oos_href_link($aContents['gv_faq'])));

// assign Smarty variables;
$smarty->assign(
    array(
          'breadcrumb'        => $oBreadcrumb->trail(),
          'heading_title'    => $aLang['heading_title'],
          'robots'            => 'noindex,nofollow,noodp,noydir',

          'text_gift_voucher'    => $sTextGiftVoucher
      )
);


$smarty->display($aTemplate['page']);
