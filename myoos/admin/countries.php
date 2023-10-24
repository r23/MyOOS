<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: countries.php,v 1.25 2002/03/17 17:34:47 harley_vb
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

switch ($action) {
    case 'insert':
        $countries_name = (isset($_POST['countries_name']) ? oos_prepare_input($_POST['countries_name']) : '');
        $countries_iso_code_2 = (isset($_POST['countries_iso_code_2']) ? oos_prepare_input($_POST['countries_iso_code_2']) : '');
        $countries_iso_code_3 = (isset($_POST['countries_iso_code_3']) ? oos_prepare_input($_POST['countries_iso_code_3']) : '');
        $address_format_id = filter_input(INPUT_GET, 'address_format_id', FILTER_VALIDATE_INT) ?: 1;

        $dbconn->Execute("INSERT INTO " . $oostable['countries'] . " (countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id) VALUES ('" . oos_db_input($countries_name) . "', '" . oos_db_input($countries_iso_code_2) . "', '" . oos_db_input($countries_iso_code_3) . "', '" . oos_db_input($address_format_id) . "')");
        oos_redirect_admin(oos_href_link_admin($aContents['countries']));
        break;

    case 'save':
        $countries_name = (isset($_POST['countries_name']) ? oos_prepare_input($_POST['countries_name']) : '');
        $countries_iso_code_2 = (isset($_POST['countries_iso_code_2']) ? oos_prepare_input($_POST['countries_iso_code_2']) : '');
        $countries_iso_code_3 = (isset($_POST['countries_iso_code_3']) ? oos_prepare_input($_POST['countries_iso_code_3']) : '');
        $address_format_id = filter_input(INPUT_GET, 'address_format_id', FILTER_VALIDATE_INT) ?: 1;

        $countries_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT);

        $dbconn->Execute("UPDATE " . $oostable['countries'] . " SET countries_name = '" . oos_db_input($countries_name) . "', countries_iso_code_2 = '" . oos_db_input($countries_iso_code_2) . "', countries_iso_code_3 = '" . oos_db_input($countries_iso_code_3) . "', address_format_id = '" . oos_db_input($address_format_id) . "' WHERE countries_id = '" . oos_db_input($countries_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $countries_id));
        break;

    case 'deleteconfirm':
        $countries_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT);

        $dbconn->Execute("DELETE FROM " . $oostable['countries'] . " WHERE countries_id = '" . oos_db_input($countries_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['countries'], 'page=' . $nPage));
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
							<th class="text-center" colspan="2"><?php echo TABLE_HEADING_COUNTRY_CODES; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php

$rows = 0;
$aDocument = [];
$countries_result_raw = "SELECT countries_id, countries_name, countries_iso_code_2, countries_iso_code_3, address_format_id 
                          FROM " . $oostable['countries'] . " 
                          ORDER BY countries_name";
$countries_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $countries_result_raw, $countries_result_numrows);
$countries_result = $dbconn->Execute($countries_result_raw);
while ($countries = $countries_result->fields) {
    $rows++;
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $countries['countries_id']))) && !isset($cInfo) && (!str_starts_with((string) $action, 'new'))) {
        $cInfo = new objectInfo($countries);
    }

    if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
        $aDocument[] = ['id' => $rows,
                    'link' => oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=edit')];
        echo '              <tr id="row-' . $rows .'">' . "\n";
    } else {
        $aDocument[] = ['id' => $rows,
                        'link' => oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $countries['countries_id'])];
        echo '              <tr id="row-' . $rows .'">' . "\n";
    } ?>
                <td><?php echo $countries['countries_name']; ?></td>
                <td align="center" width="40"><?php echo $countries['countries_iso_code_2']; ?></td>
                <td align="center" width="40"><?php echo $countries['countries_iso_code_3']; ?></td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($countries['countries_id'] == $cInfo->countries_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $countries['countries_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $countries_result->MoveNext();
}

?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $countries_split->display_count($countries_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_COUNTRIES); ?></td>
                    <td class="smallText" align="right"><?php echo $countries_split->display_links($countries_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
   if ($action == 'default') {
       ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_COUNTRY) . '</a>'; ?></td>
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
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_COUNTRY . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'countries', $aContents['countries'], 'page=' . $nPage . '&action=insert', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . oos_draw_input_field('countries_name')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . oos_draw_input_field('countries_iso_code_2')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . oos_draw_input_field('countries_iso_code_3')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . oos_draw_input_field('address_format_id')];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'edit':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_COUNTRY . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'countries', $aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=save', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . oos_draw_input_field('countries_name', $cInfo->countries_name)];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . '<br>' . oos_draw_input_field('countries_iso_code_2', $cInfo->countries_iso_code_2)];
        $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . '<br>' . oos_draw_input_field('countries_iso_code_3', $cInfo->countries_iso_code_3)];
        $contents[] = ['text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . '<br>' . oos_draw_input_field('address_format_id', $cInfo->address_format_id)];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_COUNTRY . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'countries', $aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $cInfo->countries_name . '</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    default:
        if (isset($cInfo) && is_object($cInfo)) {
            $heading[] = ['text' => '<b>' . $cInfo->countries_name . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['countries'], 'page=' . $nPage . '&cID=' . $cInfo->countries_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_NAME . '<br>' . $cInfo->countries_name];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_2 . ' ' . $cInfo->countries_iso_code_2];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COUNTRY_CODE_3 . ' ' . $cInfo->countries_iso_code_3];
            $contents[] = ['text' => '<br>' . TEXT_INFO_ADDRESS_FORMAT . ' ' . $cInfo->address_format_id];
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
</script>
<?php

require 'includes/nice_exit.php';
