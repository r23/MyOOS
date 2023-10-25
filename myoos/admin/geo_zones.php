<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: geo_zones.php,v 1.25 2002/04/17 23:09:03 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

/**
* Return Geo Zone Name
*
* @param $geo_zone_id
* @return string
*/
function oosGetGeoZoneName($geo_zone_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $geo_zonestable = $oostable['geo_zones'];
    $zones_sql = "SELECT geo_zone_name
                  FROM $geo_zonestable
                  WHERE geo_zone_id = '" . $geo_zone_id . "'";
    $result = $dbconn->Execute($zones_sql);

    if (!$result->RecordCount()) {
        $geo_zone_name = $geo_zone_id;
    } else {
        $zones = $result->fields;
        $geo_zone_name = $zones['geo_zone_name'];
    }

    return $geo_zone_name;
}

$nsPage = filter_input(INPUT_GET, 'spage', FILTER_VALIDATE_INT) ?: 1;
$nzPage = filter_input(INPUT_GET, 'zpage', FILTER_VALIDATE_INT) ?: 1;

$saction = filter_string_polyfill(filter_input(INPUT_GET, 'saction')) ?: 'default';

switch ($saction) {
    case 'insert_sub':
        $zID = oos_db_prepare_input($_GET['zID']);
        $zone_country_id = oos_db_prepare_input($_POST['zone_country_id']);
        $zone_id = oos_db_prepare_input($_POST['zone_id']);

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $dbconn->Execute("INSERT INTO $zones_to_geo_zonestable (zone_country_id, zone_id, geo_zone_id, date_added) VALUES ('" . oos_db_input($zone_country_id) . "', '" . oos_db_input($zone_id) . "', '" . oos_db_input($zID) . "', now())");
        $new_subzone_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $new_subzone_id));
        break;

    case 'save_sub':
        $sID = oos_db_prepare_input($_GET['sID']);
        $zID = oos_db_prepare_input($_GET['zID']);
        $zone_country_id = oos_db_prepare_input($_POST['zone_country_id']);
        $zone_id = oos_db_prepare_input($_POST['zone_id']);

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $dbconn->Execute("UPDATE $zones_to_geo_zonestable SET geo_zone_id = '" . oos_db_input($zID) . "', zone_country_id = '" . oos_db_input($zone_country_id) . "', zone_id = " . ((oos_db_input($zone_id)) ? "'" . oos_db_input($zone_id) . "'" : 'null') . ", last_modified = now() WHERE association_id = '" . oos_db_input($sID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $_GET['sID']));
        break;

    case 'deleteconfirm_sub':
        $sID = oos_db_prepare_input($_GET['sID']);

        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $dbconn->Execute("DELETE FROM $zones_to_geo_zonestable WHERE association_id = '" . oos_db_input($sID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage));
        break;
}

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'insert_zone':
        $geo_zone_name = oos_db_prepare_input($_POST['geo_zone_name']);
        $geo_zone_description = oos_db_prepare_input($_POST['geo_zone_description']);

        $geo_zonestable = $oostable['geo_zones'];
        $dbconn->Execute("INSERT INTO $geo_zonestable (geo_zone_name, geo_zone_description, date_added) VALUES ('" . oos_db_input($geo_zone_name) . "', '" . oos_db_input($geo_zone_description) . "', now())");
        $new_zone_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $new_zone_id));
        break;

    case 'save_zone':
        $zID = oos_db_prepare_input($_GET['zID']);
        $geo_zone_name = oos_db_prepare_input($_POST['geo_zone_name']);
        $geo_zone_description = oos_db_prepare_input($_POST['geo_zone_description']);

        $geo_zonestable = $oostable['geo_zones'];
        $dbconn->Execute("UPDATE $geo_zonestable SET geo_zone_name = '" . oos_db_input($geo_zone_name) . "', geo_zone_description = '" . oos_db_input($geo_zone_description) . "', last_modified = now() WHERE geo_zone_id = '" . oos_db_input($zID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID']));
        break;

    case 'deleteconfirm_zone':
        $zID = oos_db_prepare_input($_GET['zID']);

        $geo_zonestable = $oostable['geo_zones'];
        $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
        $dbconn->Execute("DELETE FROM $geo_zonestable WHERE geo_zone_id = '" . oos_db_input($zID) . "'");
        $dbconn->Execute("DELETE FROM $zones_to_geo_zonestable WHERE geo_zone_id = '" . oos_db_input($zID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage));
        break;
}

require 'includes/header.php';

if (isset($_GET['zID'])  && (($saction == 'edit') || ($saction == 'new'))) {
    ?>
<script nonce="<?php echo NONCE; ?>">
function resetZoneSelected(theForm) {
  if (theForm.state.value != '') {
    theForm.zone_id.selectedIndex = '0';
    if (theForm.zone_id.options.length > 0) {
      theForm.state.value = '<?php echo JS_STATE_SELECT; ?>';
    }
  }
}

function update_zone(theForm) {
  let NumState = theForm.zone_id.options.length;
  let SelectedCountry = "";

  while(NumState > 0) {
    NumState--;
    theForm.zone_id.options[NumState] = null;
  }

  SelectedCountry = theForm.zone_country_id.options[theForm.zone_country_id.selectedIndex].value;

<?php echo oos_is_zone_list('SelectedCountry', 'theForm', 'zone_id'); ?>

}
</script>
<?php
}
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
							<?php echo '<a href="' . oos_href_link_admin($aContents['countries'], 'selected_box=taxes') . '">' . BOX_HEADING_LOCATION_AND_TAXES . '</a>'; ?>
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
<?php
  if ($action == 'list') {
      ?>

				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_COUNTRY; ?></th>
							<th><?php echo TABLE_HEADING_COUNTRY_ZONE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
      $rows = 0;
      $zones_result_raw = "SELECT a.association_id, a.zone_country_id, c.countries_name, a.zone_id, a.geo_zone_id, a.last_modified, a.date_added, z.zone_name FROM " . $oostable['zones_to_geo_zones'] . " a left join " . $oostable['countries'] . " c on a.zone_country_id = c.countries_id left join " . $oostable['zones'] . " z on a.zone_id = z.zone_id WHERE a.geo_zone_id = " . intval($_GET['zID']) . " ORDER BY association_id";
      $zones_split = new splitPageResults($nsPage, MAX_DISPLAY_SEARCH_RESULTS, $zones_result_raw, $zones_result_numrows);
      $zones_result = $dbconn->Execute($zones_result_raw);
      while ($zones = $zones_result->fields) {
          $rows++;
          if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $zones['association_id']))) && !isset($sInfo) && (!str_starts_with((string) $saction, 'new'))) {
              $sInfo = new objectInfo($zones);
          }

          if (isset($sInfo) && is_object($sInfo) && ($zones['association_id'] == $sInfo->association_id)) {
              $aDocument[] = ['id' => $rows,
                              'link' => oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id . '&saction=edit')];
              echo '              <tr id="row-' . $rows .'">' . "\n";
          } else {
              $aDocument[] = ['id' => $rows,
                          'link' => oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $zones['association_id'])];
              echo '              <tr id="row-' . $rows .'">' . "\n";
          } ?>
                <td><?php echo($zones['countries_name'] ?: TEXT_ALL_COUNTRIES); ?></td>
                <td><?php echo(($zones['zone_id']) ? $zones['zone_name'] : PLEASE_SELECT); ?></td>
                <td class="text-right"><?php if (isset($sInfo) && is_object($sInfo) && ($zones['association_id'] == $sInfo->association_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . intval($nzPage) . '&zID=' . intval($_GET['zID']) . '&action=list&spage=' . $nsPage . '&sID=' . $zones['association_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $zones_result->MoveNext();
      } ?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nsPage, TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nsPage, 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list', 'spage'); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="3">
<?php
if (empty($saction)) {
    echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID']) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>';

    $szID = (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '');
    $sID = (empty($sInfo->association_id) ? '' : '&sID=' . $sInfo->association_id);
    echo '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . $szID . '&action=list&spage=' . $nsPage . $sID . '&saction=new') . '">' . oos_button(BUTTON_INSERT) . '</a>';
} ?>	  
	  </td>
              </tr>
            </table>
<?php
  } else {
      ?>
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_TAX_ZONES; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
      $rows = 0;
      $aDocument = [];
      $geo_zonestable = $oostable['geo_zones'];
      $zones_result_raw = "SELECT geo_zone_id, geo_zone_name, geo_zone_description, last_modified, date_added 
                        FROM $geo_zonestable
                        ORDER BY geo_zone_name";
      $zones_split = new splitPageResults($nzPage, MAX_DISPLAY_SEARCH_RESULTS, $zones_result_raw, $zones_result_numrows);
      $zones_result = $dbconn->Execute($zones_result_raw);
      while ($zones = $zones_result->fields) {
          $rows++;
          if ((!isset($_GET['zID']) || (isset($_GET['zID']) && ($_GET['zID'] == $zones['geo_zone_id']))) && !isset($zInfo) && (!str_starts_with((string) $action, 'new'))) {
              $zInfo = new objectInfo($zones);
          }
          if (isset($zInfo) && is_object($zInfo) && ($zones['geo_zone_id'] == $zInfo->geo_zone_id)) {
              $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id . '&action=list')];
              echo '              <tr id="row-' . $rows .'">' . "\n";
          } else {
              $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zones['geo_zone_id'])];
              echo '              <tr id="row-' . $rows .'">' . "\n";
          } ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zones['geo_zone_id'] . '&action=list') . '"><i class="fa fa-folder"></i></button></a>&nbsp;' . $zones['geo_zone_name']; ?></td>
                <td class="text-right"><?php if (isset($zInfo) && is_object($zInfo) && ($zones['geo_zone_id'] == $zInfo->geo_zone_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zones['geo_zone_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $zones_result->MoveNext();
      } ?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo $zones_split->display_count($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nzPage, TEXT_DISPLAY_NUMBER_OF_TAX_ZONES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nzPage, '', 'zpage'); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td align="right" colspan="2"><?php  if ($action == 'default') {
                    echo '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . (empty($zInfo->geo_zone_id) ? '' : '&zID=' . $zInfo->geo_zone_id) . '&action=new_zone') . '">' . oos_button(BUTTON_INSERT) . '</a>';
                } ?></td>
              </tr>
            </table>
<?php
  }
?>
            </td>
<?php

$heading = [];
$contents = [];

if ($action == 'list') {
    switch ($saction) {
        case 'new':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_SUB_ZONE . '</b>'];


            $contents = ['form' => oos_draw_form('zones', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '') . '&action=list&spage=' . $nsPage . (isset($_GET['sID']) ? '&sID=' . intval($_GET['sID']) : '') . '&saction=insert_sub', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_NEW_SUB_ZONE_INTRO];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . oos_draw_pull_down_menu('zone_country_id', 'update_zone', oos_get_countries(TEXT_ALL_COUNTRIES), '')];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . oos_draw_pull_down_menu('zone_id', '', oos_prepare_country_zones_pull_down())];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . (isset($_GET['sID']) ? '&sID=' . intval($_GET['sID']) : '')) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        case 'edit':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_SUB_ZONE . '</b>'];

            $sID = (empty($sInfo->association_id) ? '' : '&sID=' . $sInfo->association_id);

            $contents = ['form' => oos_draw_form('zones', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '') . '&action=list&spage=' . $nsPage . $sID . '&saction=save_sub', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_EDIT_SUB_ZONE_INTRO];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY . '<br>' . oos_draw_pull_down_menu('zone_country_id', 'update_zone', oos_get_countries(TEXT_ALL_COUNTRIES), $sInfo->zone_country_id)];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_ZONE . '<br>' . oos_draw_pull_down_menu('zone_id', '', oos_prepare_country_zones_pull_down($sInfo->zone_country_id), $sInfo->zone_id)];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        case 'delete':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_SUB_ZONE . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '') . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id . '&saction=deleteconfirm_sub', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_DELETE_SUB_ZONE_INTRO];
            $contents[] = ['text' => '<br><b>' . $sInfo->countries_name . '</b>'];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        default:
            if (isset($sInfo) && is_object($sInfo)) {
                $heading[] = ['text' => '<b>' . $sInfo->countries_name . '</b>'];

                $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '') . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id . '&saction=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID'] . '&action=list&spage=' . $nsPage . '&sID=' . $sInfo->association_id . '&saction=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
                $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->date_added)];
                if (oos_is_not_null($sInfo->last_modified)) {
                    $contents[] = ['text' => TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->last_modified)];
                }
            }
            break;
    }
} else {
    switch ($action) {
        case 'new_zone':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . (isset($_GET['zID']) ? '&zID=' . intval($_GET['zID']) : '') . '&action=insert_zone', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_NEW_ZONE_INTRO];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . oos_draw_input_field('geo_zone_name')];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . oos_draw_input_field('geo_zone_description')];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $_GET['zID']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        case 'edit_zone':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id . '&action=save_zone', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_EDIT_ZONE_INTRO];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ZONE_NAME . '<br>' . oos_draw_input_field('geo_zone_name', $zInfo->geo_zone_name)];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . oos_draw_input_field('geo_zone_description', $zInfo->geo_zone_description)];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        case 'delete_zone':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'zones', $aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id . '&action=deleteconfirm_zone', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_DELETE_ZONE_INTRO];
            $contents[] = ['text' => '<br><b>' . $zInfo->geo_zone_name . '</b>'];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        default:
            if (isset($zInfo) && is_object($zInfo)) {
                $heading[] = ['text' => '<b>' . $zInfo->geo_zone_name . '</b>'];

                $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id . '&action=edit_zone') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['geo_zones'], 'zpage=' . $nzPage . '&zID=' . $zInfo->geo_zone_id . '&action=delete_zone') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
                $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($zInfo->date_added)];
                if (oos_is_not_null($zInfo->last_modified)) {
                    $contents[] = ['text' => TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($zInfo->last_modified)];
                }
                $contents[] = ['text' => '<br>' . TEXT_INFO_ZONE_DESCRIPTION . '<br>' . $zInfo->geo_zone_description];
            }
            break;
    }
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

if (isset($aDocument) || !empty($aDocument)) {
    echo '<script nonce="' . NONCE . '">' . "\n";
    $nDocument = is_countable($aDocument) ? count($aDocument) : 0;
    for ($i = 0, $n = $nDocument; $i < $n; $i++) {
        echo 'document.getElementById(\'row-'. $aDocument[$i]['id'] . '\').addEventListener(\'click\', function() { ' . "\n";
        echo 'document.location.href = "' . $aDocument[$i]['link'] . '";' . "\n";
        echo '});' . "\n";
    }
    echo '</script>' . "\n";
}

?>
<script nonce="<?php echo NONCE; ?>">
let element = document.getElementById('page');
if (element) {

	let form = document.getElementById('pages'); 

	element.addEventListener('change', function() { 
		form.submit(); 
	});
}

let zoneElement = document.getElementById('zones');
if (zoneElement) {

	let form = document.getElementById('update_zone'); 

	zoneElement.addEventListener('change', function() { 
		form.submit(); 
	});
}

</script>
<?php

require 'includes/nice_exit.php';
