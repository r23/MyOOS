<?php
/* ----------------------------------------------------------------------
   $Id: manual_loging.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
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

  function oos_set_login_status($man_info_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    if ($status == '1') {
      return $dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET status = '1', expires_date = NULL, manual_last_modified = now(), date_status_change =now() WHERE man_info_id = '" . $man_info_id . "'");
    } elseif ($status == '0') {
      return $dbconn->Execute("UPDATE " . $oostable['manual_info'] . " SET status = '0', man_key = '', man_key2 = '', manual_last_modified = now() WHERE man_info_id = '" . $man_info_id . "'");
    } else {
      return -1;
    }
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        oos_set_login_status($_GET['id'], $_GET['flag']);
        oos_redirect_admin(oos_href_link_admin($aContents['manual_loging'], '', 'NONSSL'));
        break;
    }
  }
  require 'includes/header.php'; 
?>
<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>		
			
			<!-- Breadcrumbs  -->
			<div class="row wrapper white-bg page-heading">
				<div class="col-lg-10">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['customers'], 'selected_box=customers') . '">' . BOX_HEADING_CUSTOMERS . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
				<div class="col-lg-2">

				</div>
			</div><!--/ End Breadcrumbs -->
			
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">	
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUAL_ENTRY; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $payment_dai_result_raw = "SELECT man_info_id, man_name, status, manual_date_added, manual_last_modified, date_status_change FROM " . $oostable['manual_info'] . "";
    $payment_dai_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $payment_dai_result_raw, $payment_dai_result_numrows);
    $payment_dai_result = $dbconn->Execute($payment_dai_result_raw);
    while ($palm_doa = $payment_dai_result->fields) {
      if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $palm_doa['man_info_id']))) && !isset($sInfo)) {
        $sInfo = new objectInfo($palm_doa);
      }
      if (isset($sInfo) && is_object($sInfo) && ($palm_doa['man_info_id'] == $sInfo->man_info_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->man_info_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $_GET['page'] . '&sID=' . $palm_doa['man_info_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent" align="left"><?php echo $palm_doa['man_name']; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($palm_doa['status'] == '1') {
        echo '&nbsp;<a href="' . oos_href_link_admin($aContents['manual_loging'], 'action=setflag&flag=0&id=' . $palm_doa['man_info_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_GREEN, 10, 10) . '</a>';
      } else {
        echo '&nbsp;<a href="' . oos_href_link_admin($aContents['manual_loging'], 'action=setflag&flag=1&id=' . $palm_doa['man_info_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_RED, 10, 10) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($sInfo) && is_object($sInfo) && ($palm_doa['man_info_id'] == $sInfo->man_info_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $_GET['page'] . '&sID=' . $palm_doa['man_info_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
      </tr>
<?php
    // Move that ADOdb pointer!
    $payment_dai_result->MoveNext();
  }
?>              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top">&nbsp;</td>
                    <td class="smallText" align="right"><?php echo $payment_dai_split->display_links($payment_dai_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr><td colspan="2" align="right">&nbsp;</td></tr>
<?php
  }
?>
               </table></td></tr>
           </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_OVERSTOCK . '</b>');
      $contents = array('form' => oos_draw_form('id', 'palm_daily', $aContents['manual_loging'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->man_info_id . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $sInfo->contact_info_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['manual_loging'], 'page=' . $_GET['page'] . '&sID=' . $sInfo->man_info_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;
    default:
      if (isset($sInfo) && is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->man_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->manual_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->manual_last_modified));
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";
    $box = new box;
    echo $box->infoBox($heading, $contents);
    echo '            </td>' . "\n";
 }
?>
          </tr>
        </table></td>
      </tr>

   </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

	</div>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>