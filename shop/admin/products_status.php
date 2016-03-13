<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
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

  function oos_get_products_status_name($products_status_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $products_statustable = $oostable['products_status'];
    $products_status = $dbconn->Execute("SELECT products_status_name FROM $products_statustable WHERE products_status_id = '" . intval($products_status_id) . "' AND products_status_languages_id = '" . intval($lang_id)  . "'");

    return $products_status->fields['products_status_name'];
  }

  function oos_get_products_status() {

    $products_status_array = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $products_statustable = $oostable['products_status'];
    $products_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM $products_statustable WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
    while ($products_status = $products_status_result->fields) {
      $products_status_array[] = array('id' => $products_status['products_status_id'],
                                       'text' => $products_status['products_status_name']
                                       );

      // Move that ADOdb pointer!
      $products_status_result->MoveNext();
    }

    // Close result set
    $products_status_result->Close();

    return $products_status_array;
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $products_status_id = oos_db_prepare_input($_GET['psID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $products_status_name_array = $_POST['products_status_name'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('products_status_name' => oos_db_prepare_input($products_status_name_array[$lang_id]));

          if ($action == 'insert') {
            if (oos_empty($products_status_id)) {
              $products_statustable = $oostable['products_status'];
              $next_id_result = $dbconn->Execute("SELECT max(products_status_id) as products_status_id FROM $products_statustable");
              $next_id = $next_id_result->fields;
              $products_status_id = $next_id['products_status_id'] + 1;
            }

            $insert_sql_data = array('products_status_id' => $products_status_id,
                                     'products_status_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['products_status'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['products_status'], $sql_data_array, 'update', "products_status_id = '" . oos_db_input($products_status_id) . "' and products_status_languages_id = '" . intval($lang_id) . "'");
          }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input($products_status_id) . "' WHERE configuration_key = 'DEFAULT_PRODUTS_STATUS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $products_status_id));
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

        oos_redirect_admin(oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page']));
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
							<a href="<?php echo oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $products_statustable = $oostable['products_status'];
  $products_status_result_raw = "SELECT products_status_id, products_status_name
                                 FROM  $products_statustable
                                 WHERE  products_status_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY  products_status_id";
  $products_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_status_result_raw, $products_status_result_numrows);
  $products_status_result = $dbconn->Execute($products_status_result_raw);
  while ($products_status = $products_status_result->fields) {
    if (((!$_GET['psID']) || ($_GET['psID'] == $products_status['products_status_id'])) && (!$psInfo) && (substr($action, 0, 3) != 'new')) {
      $psInfo = new objectInfo($products_status);
    }

    if (isset($psInfo) && is_object($psInfo) && ($products_status['products_status_id'] == $psInfo->products_status_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $products_status['products_status_id']) . '\'">' . "\n";
    }

    if (DEFAULT_PRODUTS_STATUS_ID == $products_status['products_status_id']) {
      echo '                <td class="dataTableContent"><b>' . $products_status['products_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $products_status['products_status_name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if (isset($psInfo) && is_object($psInfo) && ($products_status['products_status_id'] == $psInfo->products_status_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $products_status['products_status_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $products_status_result->MoveNext();
  }

  // Close result set
  $products_status_result->Close();
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $products_status_split->display_count($products_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $products_status_split->display_links($products_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_PRODUCTS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $_GET['page'] . '&action=insert', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $products_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $products_status_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_status_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_PRODUCTS_STATUS_NAME . $products_status_inputs_string);
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('insert', BUTTON_INSERT) . ' <a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_PRODUCTS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id  . '&action=save', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $products_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $products_status_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('products_status_name[' . $languages[$i]['id'] . ']', oos_get_products_status_name($psInfo->products_status_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_PRODUCTS_STATUS_NAME . $products_status_inputs_string);
      if (DEFAULT_PRODUTS_STATUS_ID != $psInfo->products_status_id) $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('update', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCTS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('id', 'status', $aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id  . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $psInfo->products_status_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($psInfo) && is_object($psInfo)) {
        $heading[] = array('text' => '<b>' . $psInfo->products_status_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['products_status'], 'page=' . $_GET['page'] . '&psID=' . $psInfo->products_status_id . '&action=delete') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a>');

        $products_status_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $products_status_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_get_products_status_name($psInfo->products_status_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $products_status_inputs_string);
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
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2016 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>