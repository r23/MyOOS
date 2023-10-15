<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

function oos_get_products_status_name($products_status_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $products_statustable = $oostable['products_status'];
    $products_status = $dbconn->Execute("SELECT products_status_name FROM $products_statustable WHERE products_status_id = '" . intval($products_status_id) . "' AND products_status_languages_id = '" . intval($language_id)  . "'");

    return $products_status->fields['products_status_name'];
}


function oos_get_products_status()
{
    $products_status_array = [];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $products_statustable = $oostable['products_status'];
    $products_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM $products_statustable WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
    while ($products_status = $products_status_result->fields) {
        $products_status_array[] = ['id' => $products_status['products_status_id'], 'text' => $products_status['products_status_name']];

        // Move that ADOdb pointer!
        $products_status_result->MoveNext();
    }

    return $products_status_array;
}


$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
      case 'insert':
      case 'save':
        $products_status_id = oos_db_prepare_input($_GET['psID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = ['products_status_name' => oos_db_prepare_input($_POST['products_status_name'][$language_id])];

            if ($action == 'insert') {
                if (oos_empty($products_status_id)) {
                    $products_statustable = $oostable['products_status'];
                    $next_id_result = $dbconn->Execute("SELECT max(products_status_id) as products_status_id FROM $products_statustable");
                    $next_id = $next_id_result->fields;
                    $products_status_id = $next_id['products_status_id'] + 1;
                }

                $insert_sql_data = ['products_status_id' => $products_status_id, 'products_status_languages_id' => $language_id];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['products_status'], $sql_data_array);
            } elseif ($action == 'save') {
                oos_db_perform($oostable['products_status'], $sql_data_array, 'UPDATE', "products_status_id = '" . oos_db_input($products_status_id) . "' AND products_status_languages_id = '" . intval($language_id) . "'");
            }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
            $configurationtable = $oostable['configuration'];
            $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input($products_status_id) . "' WHERE configuration_key = 'DEFAULT_PRODUTS_STATUS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $products_status_id));
        break;

      case 'deleteconfirm':
        $psID = oos_db_prepare_input($_GET['psID']);

/*
      $products_status_result = $dbconn->Execute("SELECT configuration_value FROM " . $oostable['configuration'] . " WHERE configuration_key = 'DEFAULT_PRODUTS_STATUS_ID'");
      $products_status = $products_status_result->fields;
      if ($products_status['configuration_value'] == $psID) {
        $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '' WHERE configuration_key = 'DEFAULT_PRODUTS_STATUS_ID'");
      }
*/
        $products_statustable = $oostable['products_status'];
        $dbconn->Execute("DELETE FROM $products_statustable WHERE products_status_id = '" . oos_db_input($psID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['products_status'], 'page=' . $nPage));
        break;
		
    case 'delete':
        $psID = oos_db_prepare_input($_GET['psID']);

        $remove_status = true;
        if ($psID == DEFAULT_PRODUTS_STATUS_ID) {
            $remove_status = false;
            $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
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
							<th><?php echo TABLE_HEADING_PRODUCTS_STATUS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
  $products_statustable = $oostable['products_status'];
  $products_status_result_raw = "SELECT products_status_id, products_status_name
                                 FROM  $products_statustable
                                 WHERE  products_status_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY  products_status_id";
  $products_status_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $products_status_result_raw, $products_status_result_numrows);
  $products_status_result = $dbconn->Execute($products_status_result_raw);
  while ($products_status = $products_status_result->fields) {
      if (((!isset($_GET['psID'])) || ($_GET['psID'] == $products_status['products_status_id'])) && (!isset($psInfo)) && (!str_starts_with((string) $action, 'new'))) {
          $psInfo = new objectInfo($products_status);
      }

      if (isset($psInfo) && is_object($psInfo) && ($products_status['products_status_id'] == $psInfo->products_status_id)) {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id . '&action=edit') . '\'">' . "\n";
      } else {
          echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $products_status['products_status_id']) . '\'">' . "\n";
      }

      if (DEFAULT_PRODUTS_STATUS_ID == $products_status['products_status_id']) {
          echo '                <td><b>' . $products_status['products_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
      } else {
          echo '                <td>' . $products_status['products_status_name'] . '</td>' . "\n";
      } ?>
                <td class="text-right"><?php if (isset($psInfo) && is_object($psInfo) && ($products_status['products_status_id'] == $psInfo->products_status_id)) {
          echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
      } else {
          echo '<a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $products_status['products_status_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
      } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $products_status_result->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_status_split->display_count($products_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_PRODUCTS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_status_split->display_links($products_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
   if ($action == 'default') {
      ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_PRODUCT_STATUS) . '</a>'; ?></td>
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
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_PRODUCTS_STATUS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $nPage . '&action=insert', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];

      $products_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
          $products_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_status_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = ['text' => '<br>' . TEXT_INFO_PRODUCTS_STATUS_NAME . $products_status_inputs_string];
      $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
      $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      break;

    case 'edit':
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_PRODUCTS_STATUS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id  . '&action=save', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];

      $products_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
          $products_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_status_name[' . $languages[$i]['id'] . ']', oos_get_products_status_name($psInfo->products_status_id, $languages[$i]['id']));
      }

      $contents[] = ['text' => '<br>' . TEXT_INFO_PRODUCTS_STATUS_NAME . $products_status_inputs_string];
      if (DEFAULT_PRODUTS_STATUS_ID != $psInfo->products_status_id) {
          $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
      }
      $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      break;

    case 'delete':
      $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCTS_STATUS . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id  . '&action=deleteconfirm', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
      $contents[] = ['text' => '<br><b>' . $psInfo->products_status_name . '</b>'];
      if ($remove_status) {
          $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      }
      break;

    default:
      if (isset($psInfo) && is_object($psInfo)) {
          $heading[] = ['text' => '<b>' . $psInfo->products_status_name . '</b>'];

          $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $nPage . '&psID=' . $psInfo->products_status_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];

          $products_status_inputs_string = '';
          $languages = oos_get_languages();
          for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
              $products_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_get_products_status_name($psInfo->products_status_id, $languages[$i]['id']);
          }

          $contents[] = ['text' => $products_status_inputs_string];
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