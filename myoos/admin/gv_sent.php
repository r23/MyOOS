<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_sent.php,v 1.2.2.1 2003/04/18 16:17:14 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;


require 'includes/header.php';
?>
<div class="wrapper">
    <!-- Header //-->
    <header class="topnavbar-wrapper">
        <!-- Top Navbar //-->
        <?php require 'includes/menue.php'; ?>
    </header>
    <!-- END Header //-->
    <aside class="aside">
        <!-- Sidebar //-->
        <div class="aside-inner">
            <?php require 'includes/blocks.php'; ?>
        </div>
        <!-- END Sidebar (left) //-->
    </aside>
    
    <!-- Main section //-->
    <section>
        <!-- Page content //-->
        <div class="content-wrapper">        

            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['coupon_admin'], 'selected_box=gv_admin') . '">' . BOX_HEADING_GV_ADMIN . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->
            
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">        
<!-- body_text //-->
    <div class="table-responsive">
        <table class="table w-100">
          <tr>
            <td valign="top">
            
                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_SENDERS_NAME; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_VOUCHER_VALUE; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_VOUCHER_CODE; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_DATE_SENT; ?></th>        
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
  $couponstable = $oostable['coupons'];
$coupon_email_tracktable = $oostable['coupon_email_track'];
$gv_result_raw = "SELECT c.coupon_amount, c.coupon_code, c.coupon_id, et.sent_firstname, et.sent_lastname,
                           et.customer_id_sent, et.emailed_to, et.date_sent, c.coupon_id
                    FROM $couponstable c,
                         $coupon_email_tracktable et
                    WHERE c.coupon_id = et.coupon_id";
$gv_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $gv_result_raw, $gv_result_numrows);
$gv_result = $dbconn->Execute($gv_result_raw);
while ($gv_list = $gv_result->fields) {
    if ((!isset($_GET['gid']) || (isset($_GET['gid']) && ($_GET['gid'] == $gv_list['coupon_id']))) && !isset($gInfo)) {
        $gInfo = new objectInfo($gv_list);
    }
    if (isset($gInfo) && is_object($gInfo) && ($gv_list['coupon_id'] == $gInfo->coupon_id)) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin('gv_sent.php', oos_get_all_get_params(['gid', 'action']) . 'gid=' . $gInfo->coupon_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin('gv_sent.php', oos_get_all_get_params(['gid', 'action']) . 'gid=' . $gv_list['coupon_id']) . '\'">' . "\n";
    } ?>
                <td><?php echo $gv_list['sent_firstname'] . ' ' . $gv_list['sent_lastname']; ?></td>
                <td class="text-center"><?php echo $currencies->format($gv_list['coupon_amount']); ?></td>
                <td class="text-center"><?php echo $gv_list['coupon_code']; ?></td>
                <td class="text-right"><?php echo oos_date_short($gv_list['date_sent']); ?></td>
                <td class="text-right"><?php if (isset($gInfo) && is_object($gInfo) && ($gv_list['coupon_id'] == $gInfo->coupon_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['gv_sent'], 'page=' . $nPage . '&gid=' . $gv_list['coupon_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
    <?php
                // Move that ADOdb pointer!
                $gv_result->MoveNext();
}
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $gv_split->display_count($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_GIFT_VOUCHERS); ?></td>
                    <td class="smallText" align="right"><?php echo $gv_split->display_links($gv_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
$coupon_id = $gInfo->coupon_id ?? '';
$coupon_amount = $gInfo->coupon_amount ?? 0;
$customer_id_sent = $gInfo->customer_id_sent ?? '';
$date_sent = $gInfo->date_sent ?? '';
$coupon_code = $gInfo->coupon_code ?? '';
$emailed_to = $gInfo->emailed_to ?? '';

$heading = [];
$contents = [];

$heading[] = ['text' => '[' . $coupon_id . '] ' . ' ' . $currencies->format($coupon_amount)];
$redeem_result = $dbconn->Execute("SELECT * FROM " . $oostable['coupon_redeem_track'] . " WHERE coupon_id = '" . intval($coupon_id) . "'");
$redeemed = 'No';
if ($redeem_result->RecordCount() > 0) {
    $redeemed = 'Yes';
}
$contents[] = ['text' => TEXT_INFO_SENDERS_ID . ' ' . $customer_id_sent];
$contents[] = ['text' => TEXT_INFO_AMOUNT_SENT . ' ' . $currencies->format($coupon_amount)];
$contents[] = ['text' => TEXT_INFO_DATE_SENT . ' ' . oos_date_short($date_sent)];
$contents[] = ['text' => TEXT_INFO_VOUCHER_CODE . ' ' . $coupon_code];
$contents[] = ['text' => TEXT_INFO_EMAIL_ADDRESS . ' ' . $emailed_to];
if ($redeemed == 'Yes') {
    $redeem = $redeem_result->fields;
    $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_REDEEMED . ' ' . oos_date_short($redeem['redeem_date'])];
    $contents[] = ['text' => TEXT_INFO_IP_ADDRESS . ' ' . $redeem['redeem_ip']];
    $contents[] = ['text' => TEXT_INFO_CUSTOMERS_ID . ' ' . $redeem['customer_id']];
} else {
    $contents[] = ['text' => '<br>' . TEXT_INFO_NOT_REDEEMED];
}

if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
    ?>
    <td class="w-25" valign="top">
        <table class="table table-striped">
    <?php
    $box = new box();
    echo $box->infoBox($heading, $contents); ?>
        </table> 
    </td> 
    <?php
}
?>
          </tr>
        </table>
    </div>
<!-- body_text_eof //-->

                </div>
            </div>
        </div>

        </div>
    </section>
    <!-- Page footer //-->
    <footer>
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>


<?php

require 'includes/bottom.php';
?>
<script nonce="<?php echo NONCE; ?>">
var form = document.getElementById('pages'); 
var select = document.getElementById('page'); 

select.addEventListener('change', function() { 
	form.submit(); 
});
</script>
<?php

require 'includes/nice_exit.php';
