<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

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
            $zone_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT);

            $zonestable = $oostable['zones'];
            $dbconn->Execute("UPDATE $zonestable SET zone_country_id = '" . oos_db_input($zone_country_id) . "', zone_code = '" . oos_db_input($zone_code) . "', zone_name = '" . oos_db_input($zone_name) . "' WHERE zone_id = '" . oos_db_input($zone_id) . "'");
            oos_redirect_admin(oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zone_id));
            break;

        case 'deleteconfirm':
            $zone_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT);

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
			
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_COUNTRY_NAME; ?></th>
							<th><?php echo TABLE_HEADING_ZONE_NAME; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_ZONE_CODE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>	
<?php
  $zonestable = $oostable['zones'];
$countriestable = $oostable['countries'];
$zones_query_raw = "SELECT z.zone_id, c.countries_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id 
                      FROM $zonestable z,
                           $countriestable c
                      WHERE z.zone_country_id = c.countries_id 
                      ORDER BY c.countries_name, z.zone_name";
$zones_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $zones_query_raw, $zones_result_numrows);
$zones_result = $dbconn->Execute($zones_query_raw);

while ($zones = $zones_result->fields) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $zones['zone_id']))) && !isset($cInfo) && (!str_starts_with((string) $action, 'new'))) {
        $cInfo = new objectInfo($zones);
    }

    if (isset($cInfo) && is_object($cInfo) && ($zones['zone_id'] == $cInfo->zone_id)) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zones['zone_id']) . '\'">' . "\n";
    } ?>
                <td><?php echo $zones['countries_name']; ?></td>
                <td><?php echo $zones['zone_name']; ?></td>
                <td class="text-center"><?php echo $zones['zone_code']; ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($zones['zone_id'] == $cInfo->zone_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $zones['zone_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $zones_result->MoveNext();
}

?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $zones_split->display_count($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_ZONES); ?></td>
                    <td class="smallText" align="right"><?php echo $zones_split->display_links($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
   if ($action == 'default') {
       ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_ZONE) . '</a>'; ?></td>
                  </tr>
<?php
   }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = [];
$contents = [];

switch ($action) {
    case 'new':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&action=insert', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . oos_draw_input_field('zone_name')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ZONES_CODE . '<br>' . oos_draw_input_field('zone_code')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . oos_draw_pull_down_menu('zone_country_id', '', oos_get_countries())];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    case 'edit':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=save', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . oos_draw_input_field('zone_name', $cInfo->zone_name)];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ZONES_CODE . '<br>' . oos_draw_input_field('zone_code', $cInfo->zone_code)];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . oos_draw_pull_down_menu('zone_country_id', '', oos_get_countries(), $cInfo->countries_id)];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'zones', $aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $cInfo->zone_name . '</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    default:
        if (isset($cInfo) && is_object($cInfo)) {
            $heading[] = ['text' => '<b>' . $cInfo->zone_name . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ZONES_NAME . '<br>' . $cInfo->zone_name . ' (' . $cInfo->zone_code . ')'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . ' ' . $cInfo->countries_name];
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
