<?php
/* ----------------------------------------------------------------------
   $Id: zones.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: zones.php,v 1.21 2002/03/17 18:07:48 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


if (isset($_GET['page']) && is_numeric($_GET['page'])) {
	$nPage =  intval($_GET['page']);
} else {
	$nPage = 1; 
}  
  
$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (!empty($action)) {
	switch ($action) {
		case 'insert':
			$zone_country_id = oos_db_prepare_input($_POST['zone_country_id']);
			$zone_code = oos_db_prepare_input($_POST['zone_code']);
			$zone_name = oos_db_prepare_input($_POST['zone_name']);
			$zonestable = $oostable['zones'];

			$dbconn->Execute("INSERT INTO $zonestable (zone_country_id, zone_code, zone_name) VALUES ('" . oos_db_input($zone_country_id) . "', '" . oos_db_input($zone_code) . "', '" . oos_db_input($zone_name) . "')");
			oos_redirect_admin(oos_href_link_admin($aContents['zones']));
			break;

		case 'save':
			$zone_id = oos_db_prepare_input($_GET['cID']);

			$zonestable = $oostable['zones'];
			$dbconn->Execute("UPDATE $zonestable SET zone_country_id = '" . oos_db_input($zone_country_id) . "', zone_code = '" . oos_db_input($zone_code) . "', zone_name = '" . oos_db_input($zone_name) . "' WHERE zone_id = '" . oos_db_input($zone_id) . "'");
			oos_redirect_admin(oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zone_id));
			break;

		case 'deleteconfirm':
			$zone_id = oos_db_prepare_input($_GET['cID']);

			$zonestable = $oostable['zones'];
			$dbconn->Execute("DELETE FROM $zonestable WHERE zone_id = '" . oos_db_input($zone_id) . "'");
			oos_redirect_admin(oos_href_link_admin($aContents['zones'], 'page=' . $nPage));
			break;
	}
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
			<div class="row wrapper gray-bg page-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['countries'], 'selected_box=taxes') . '">' . BOX_HEADING_LOCATION_AND_TAXES . '</a>'; ?>
						</li>
						<li class="active">
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
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_COUNTRY_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ZONE_CODE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $zonestable = $oostable['zones'];
  $countriestable = $oostable['countries'];
  $zones_result_raw = "SELECT z.zone_id, c.countries_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id 
                      FROM $zonestable z,
                           $countriestable c
                      WHERE z.zone_country_id = c.countries_id 
                      ORDER BY c.countries_name, z.zone_name";
  $zones_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $zones_result_raw, $zones_result_numrows);
  $zones_result = $dbconn->Execute($zones_result_raw);
  while ($zones = $zones_result->fields) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $zones['zone_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
      $cInfo = new objectInfo($zones);
    }

    if (isset($cInfo) && is_object($cInfo) && ($zones['zone_id'] == $cInfo->zone_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zones['zone_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $zones['countries_name']; ?></td>
                <td class="dataTableContent"><?php echo $zones['zone_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $zones['zone_code']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($zones['zone_id'] == $cInfo->zone_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zones['zone_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $zones_result->MoveNext();
  }

  // Close result set
  $zones_result->Close();
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_ZONES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&action=new') . '">' . oos_button('new_zone', IMAGE_NEW_ZONE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&action=insert', 'post',  FALSE));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . oos_draw_input_field('zone_name'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_CODE . '<br />' . oos_draw_input_field('zone_code'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_pull_down_menu('zone_country_id', oos_get_countries()));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('insert', BUTTON_INSERT) . '&nbsp;<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=save', 'post',  FALSE));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . oos_draw_input_field('zone_name', $cInfo->zone_name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_CODE . '<br />' . oos_draw_input_field('zone_code', $cInfo->zone_code));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_pull_down_menu('zone_country_id', oos_get_countries(), $cInfo->countries_id));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('update', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=deleteconfirm', 'post',  FALSE));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $cInfo->zone_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->zone_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=delete') . '">' . oos_button('delete', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo->zone_name . ' (' . $cInfo->zone_code . ')');
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . ' ' . $cInfo->countries_name);
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
    </table></td>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2015 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>