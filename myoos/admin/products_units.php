<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: products_units.php,v 1.19 2003/02/06 17:37:09 thomasamoulton
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
 * Return Products Units Name
 *
 * @param $products_units_id
 * @param $language
 * @return string
 */
function oos_get_products_units_name($products_units_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_unitstable = $oostable['products_units'];
    $query = "SELECT products_unit_name
				FROM $products_unitstable
				WHERE products_units_id = '" . intval($products_units_id) . "'
				AND languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    return $result->fields['products_unit_name'];
}


/**
 * Return Unit of measure
 *
 * @param $products_units_id
 * @param $language
 * @return string
 */
function oos_get_unit_of_measure($products_units_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_unitstable = $oostable['products_units'];
    $query = "SELECT unit_of_measure
			FROM $products_unitstable
			WHERE products_units_id = '" . intval($products_units_id) . "'
			AND languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    return $result->fields['unit_of_measure'];
}



$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
        case 'insert':
        case 'save':
            $products_units_id = oos_db_prepare_input($_GET['uID']);

            $languages = oos_get_languages();
            for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
                $products_unit_name_array = oos_db_prepare_input($_POST['products_unit_name']);
                $unit_of_measure_array = oos_db_prepare_input($_POST['unit_of_measure']);
                $language_id = $languages[$i]['id'];

                $sql_data_array = ['products_unit_name' => oos_db_prepare_input($products_unit_name_array[$language_id]), 'unit_of_measure' => oos_db_prepare_input($unit_of_measure_array[$language_id])];

                if ($action == 'insert') {
                    if (oos_empty($products_units_id)) {
                        $products_unitstable = $oostable['products_units'];
                        $next_id_result = $dbconn->Execute("SELECT max(products_units_id) as products_units_id FROM $products_unitstable");
                        $next_id = $next_id_result->fields;
                        $products_units_id = $next_id['products_units_id'] + 1;
                    }

                    $insert_sql_data = ['products_units_id' => $products_units_id, 'languages_id' => $language_id];

                    $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                    oos_db_perform($oostable['products_units'], $sql_data_array);
                } elseif ($action == 'save') {
                    oos_db_perform($oostable['products_units'], $sql_data_array, 'UPDATE', "products_units_id = '" . intval($products_units_id) . "' AND languages_id = '" . intval($language_id) . "'");
                }
            }

            oos_redirect_admin(oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $products_units_id));
            break;

        case 'deleteconfirm':
            $uID = oos_db_prepare_input($_GET['uID']);


            $products_unitstable = $oostable['products_units'];
            $dbconn->Execute("DELETE FROM $products_unitstable WHERE products_units_id = '" . intval($uID) . "'");

            oos_redirect_admin(oos_href_link_admin($aContents['products_units'], 'page=' . $nPage));
            break;

        case 'delete':
            $uID = oos_db_prepare_input($_GET['uID']);

            $productstable = $oostable['products'];
            $status_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM $productstable WHERE products_units_id = '" . intval($uID) . "'");
            $status = $status_result->fields;

            $remove_status = true;
            if ($status['total'] > 0) {
                $remove_status = false;
                $messageStack->add(ERROR_STATUS_USED_IN_PRODUCTS, 'error');
            }
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
							<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
							<th><?php echo TABLE_HEADING_PRODUCTS_UNITS; ?></th>
							<th><?php echo TABLE_HEADING_UNIT_OF_MEASURE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>		
			
<?php
  $products_unitstable = $oostable['products_units'];
  $products_units_result_raw = "SELECT products_units_id, products_unit_name, unit_of_measure
                                FROM $products_unitstable
                                WHERE languages_id = '" . intval($_SESSION['language_id']) . "'
                                ORDER BY products_units_id";
  $products_units_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $products_units_result_raw, $products_units_result_numrows);
  $products_units_result = $dbconn->Execute($products_units_result_raw);
  while ($products_units = $products_units_result->fields) {
      if ((!isset($_GET['uID']) || (isset($_GET['uID']) && ($_GET['uID'] == $products_units['products_units_id']))) && !isset($oInfo) && (!str_starts_with((string) $action, 'new'))) {
          $oInfo = new objectInfo($products_units);
      }

      if (isset($oInfo) && is_object($oInfo) && ($products_units['products_units_id'] == $oInfo->products_units_id)) {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id . '&action=edit') . '\'">' . "\n";
      } else {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $products_units['products_units_id']) . '\'">' . "\n";
      }

      echo '                <td>' . $products_units['products_unit_name'] . '</td>' . "\n";
      echo '                <td>' . $products_units['unit_of_measure'] . '</td>' . "\n"; ?>

                <td class="text-right"><?php if (isset($oInfo) && is_object($oInfo) && ($products_units['products_units_id'] == $oInfo->products_units_id)) {
          echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
      } else {
          echo '<a href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $products_units['products_units_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
      } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $products_units_result->MoveNext();
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_units_split->display_count($products_units_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_PRODUCTS_UNITS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_units_split->display_links($products_units_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
     if ($action == 'default') {
        ?>
                  <tr>
                    <td colspan="3" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
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
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_PRODUCTS_UNITS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_units'], 'page=' . $nPage . '&action=insert', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];

      $products_units_inputs_string = '';
      $unit_of_measure_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
          $products_units_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_unit_name[' . $languages[$i]['id'] . ']');
          $unit_of_measure_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('unit_of_measure[' . $languages[$i]['id'] . ']');
      }

      $contents[] = ['text' => '<br>' . TEXT_INFO_PRODUCTS_UNITS_NAME . $products_units_inputs_string];
      $contents[] = ['text' => '<br>' . TEXT_INFO_UNIT_OF_MEASURE . $unit_of_measure_inputs_string];

      $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      break;

    case 'edit':
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_PRODUCTS_UNITS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id  . '&action=save', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];

      $products_units_inputs_string = '';
      $unit_of_measure_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
          $products_units_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_unit_name[' . $languages[$i]['id'] . ']', oos_get_products_units_name($oInfo->products_units_id, $languages[$i]['id']));
          $unit_of_measure_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('unit_of_measure[' . $languages[$i]['id'] . ']', oos_get_unit_of_measure($oInfo->products_units_id, $languages[$i]['id']));
      }

      $contents[] = ['text' => '<br>' . TEXT_INFO_PRODUCTS_UNITS_NAME . $products_units_inputs_string];
      $contents[] = ['text' => '<br>' . TEXT_INFO_UNIT_OF_MEASURE . $unit_of_measure_inputs_string];

      $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

      break;

    case 'delete':
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCTS_UNITS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id  . '&action=deleteconfirm', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
      $contents[] = ['text' => '<br><b>' . $oInfo->products_unit_name . '</b>'];
      if ($remove_status) {
          $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      }

      break;

    default:
     if (isset($oInfo) && is_object($oInfo)) {
         $heading[] = ['text' => '<b>' . $oInfo->products_unit_name . '</b>'];

         $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['products_units'], 'page=' . $nPage . '&uID=' . $oInfo->products_units_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];

         $products_units_inputs_string = '';
         $languages = oos_get_languages();
         for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
             $products_units_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_get_products_units_name($oInfo->products_units_id, $languages[$i]['id']);
         }

         $contents[] = ['text' => $products_units_inputs_string];
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
    require 'includes/nice_exit.php';
?>