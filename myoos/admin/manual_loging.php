<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: spg_manual_info.php
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
   P&G Shipping Module Version 0.4 12/03/2002
   osCommerce Shipping Management Module
   Copyright (c) 2002  - Oliver Baelde
   http://www.francecontacts.com
   dev@francecontacts.com
   - eCommerce Solutions development and integration -

   osCommerce, Open Source E-Commerce Solutions
   Copyright (c) 2002 osCommerce
   http://www.oscommerce.com

   IMPORTANT NOTE:
   This script is not part of the official osCommerce distribution
   but an add-on contributed to the osCommerce community. Please
   read the README and  INSTALL documents that are provided
   with this file for further information and installation notes.

   LICENSE:
   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.
   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.
   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

   All contributions are gladly accepted though Paypal.
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


function RandomPassword($passwordLength)
{
    $newkey2 = "";
    for ($index = 1; $index <= $passwordLength; $index++) {
        // Pick random number between 1 and 62
        $randomNumber = random_int(1, 62);
        // Select random character based on mapping.
        if ($randomNumber < 11) {
            $newkey2 .= Chr($randomNumber + 48 - 1);
        } // [ 1,10] => [0,9]
        elseif ($randomNumber < 37) {
            $newkey2 .= Chr($randomNumber + 65 - 10);
        } // [11,36] => [A,Z]
        else {
            $newkey2 .= Chr($randomNumber + 97 - 36);
        } // [37,62] => [a,z]
    }
    return $newkey2;
}

function oos_set_login_status($man_info_id, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = oosDBGetTables();

    $passwordLength = 24 ;
    $newkey = RandomPassword($passwordLength);
    $newkey2 = RandomPassword($passwordLength);
    if ($status == '1') {
        return $dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET status = '1', man_key  = '" . oos_db_input($newkey) . "', man_key2  = '" . oos_db_input($newkey2) . "', expires_date = NULL, manual_last_modified = now(), date_status_change =now() WHERE man_info_id = '" . $man_info_id . "'");
    } else {
        return $dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET status = '0', man_key = '', man_key2 = '', manual_last_modified = now() WHERE man_info_id = '" . $man_info_id . "'");
    }
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'setflag':
        oos_set_login_status($_GET['id'], $_GET['flag']);
        oos_redirect_admin(oos_href_link_admin($aContents['manual_loging'], ''));
        break;
}

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
            <div class="row gray-bg page-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['customers'], 'selected_box=customers') . '">' . BOX_HEADING_CUSTOMERS . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->

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
                            <th><?php echo TABLE_HEADING_MANUAL_ENTRY; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>
                    </thead>
<?php
    $payment_dai_result_raw = "SELECT man_info_id, man_name, status, manual_date_added, manual_last_modified, date_status_change FROM " . $oostable['manual_info'] . "";
$payment_dai_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $payment_dai_result_raw, $payment_dai_result_numrows);
$payment_dai_result = $dbconn->Execute($payment_dai_result_raw);
while ($palm_doa = $payment_dai_result->fields) {
    if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $palm_doa['man_info_id']))) && !isset($sInfo)) {
        $sInfo = new objectInfo($palm_doa);
    } ?>
            <tr>
                <td class="text-left"><?php echo $palm_doa['man_name']; ?></td>
                <td class="text-center">
    <?php
    if ($palm_doa['status'] == '1') {
        echo '&nbsp;<a href="' . oos_href_link_admin($aContents['manual_loging'], 'action=setflag&flag=0&id=' . $palm_doa['man_info_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10) . '</a>';
    } else {
        echo '&nbsp;<a href="' . oos_href_link_admin($aContents['manual_loging'], 'action=setflag&flag=1&id=' . $palm_doa['man_info_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10) . '</a>';
    } ?></td>
                <td class="text-right"><?php if (isset($sInfo) && is_object($sInfo) && ($palm_doa['man_info_id'] == $sInfo->man_info_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $nPage . '&sID=' . $palm_doa['man_info_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
      </tr>
    <?php
                // Move that ADOdb pointer!
                $payment_dai_result->MoveNext();
}
?>              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top">&nbsp;</td>
                    <td class="smallText" align="right"><?php echo $payment_dai_split->display_links($payment_dai_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
 if ($action == 'default') {
     ?>
                  <tr><td colspan="2" align="right">&nbsp;</td></tr>
    <?php
 }
?>
               </table></td></tr>
           </table></td>
<?php
$heading = [];
$contents = [];

switch ($action) {
    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_OVERSTOCK . '</b>'];
        $contents = ['form' => oos_draw_form('id', 'palm_daily', $aContents['manual_loging'], 'page=' . $nPage . '&sID=' . $sInfo->man_info_id . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $sInfo->contact_info_name . '</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $nPage . '&sID=' . $sInfo->man_info_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;
    default:
        if (isset($sInfo) && is_object($sInfo)) {
            $heading[] = ['text' => '<b>' . $sInfo->man_name . '</b>'];
            $contents[] = ['align' => 'center', 'text' => ''];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->manual_date_added)];
            $contents[] = ['text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->manual_last_modified)];
            $contents[] = ['text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change)];
        }
        break;
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
let element = document.getElementById('page');
if (element) {

	let form = document.getElementById('pages'); 

	element.addEventListener('change', function() { 
		form.submit(); 
	});
}
</script>
<?php

require 'includes/nice_exit.php';