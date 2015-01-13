<?php
/* ----------------------------------------------------------------------
   $Id: orders_status.php,v 1.3 2008/11/03 23:13:58 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders_status.php,v 1.19 2003/02/06 17:37:09 thomasamoulton
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
  * Return Orders Status Name
  *
  * @param $orders_status_id
  * @param $language
  * @return string
  */
  function oos_get_orders_status_name($orders_status_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $orders_statustable = $oostable['orders_status'];
    $query = "SELECT orders_status_name
                FROM $orders_statustable
               WHERE orders_status_id = '" . intval($orders_status_id) . "'
                 AND orders_languages_id = '" . intval($lang_id)  . "'";
    $orders_status_name = $dbconn->GetOne($query);

    return $orders_status_name;
  }

 /**
  * Return Orders Status
  *
  * @param $orders_status_id
  * @param $language
  * @return array
  */
  function oos_get_orders_status() {

    $orders_status_array = array();

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $orders_statustable = $oostable['orders_status'];
    $orders_status_sql = "SELECT orders_status_id, orders_status_name
                          FROM $orders_statustable
                          WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'
                          ORDER BY orders_status_id";
    $orders_status_result = $dbconn->Execute($orders_status_sql);
    while ($orders_status = $orders_status_result->fields) {
      $orders_status_array[] = array('id' => $orders_status['orders_status_id'],
                                     'text' => $orders_status['orders_status_name']);

      // Move that ADOdb pointer!
      $orders_status_result->MoveNext();
    }

    return $orders_status_array;
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $orders_status_id = oos_db_prepare_input($_GET['oID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $orders_status_name_array = $_POST['orders_status_name'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('orders_status_name' => oos_db_prepare_input($orders_status_name_array[$lang_id]));

          if ($action == 'insert') {
            if (!oos_is_not_null($orders_status_id)) {
              $next_id_result = $dbconn->Execute("SELECT max(orders_status_id) as orders_status_id FROM " . $oostable['orders_status'] . "");
              $next_id = $next_id_result->fields;
              $orders_status_id = $next_id['orders_status_id'] + 1;
            }

            $insert_sql_data = array('orders_status_id' => $orders_status_id,
                                     'orders_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['orders_status'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['orders_status'], $sql_data_array, 'update', "orders_status_id = '" . intval($orders_status_id) . "' and orders_languages_id = '" . intval($lang_id) . "'");
          }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . intval($orders_status_id) . "' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $orders_status_id));
        break;

    case 'deleteconfirm':
        $oID = oos_db_prepare_input($_GET['oID']);

        $orders_status_result = $dbconn->Execute("SELECT configuration_value FROM " . $oostable['configuration'] . " WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        $orders_status = $orders_status_result->fields;
        if ($orders_status['configuration_value'] == $oID) {
          $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        }

        $dbconn->Execute("DELETE FROM " . $oostable['orders_status'] . " WHERE orders_status_id = '" . intval($oID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page']));
        break;

    case 'delete':
        $oID = oos_db_prepare_input($_GET['oID']);

        $orderstable = $oostable['orders'];
        $status_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM $orderstable WHERE orders_status = '" . intval($oID) . "'");
        $status = $status_result->fields;

        $remove_status = true;
        if ($oID == DEFAULT_ORDERS_STATUS_ID) {
          $remove_status = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_ORDER_STATUS, 'error');
        } elseif ($status['total'] > 0) {
          $remove_status = false;
          $messageStack->add(ERROR_STATUS_USED_IN_ORDERS, 'error');
        } else {
          $orders_status_historytable = $oostable['orders_status_history'];
          $history_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM $orders_status_historytable WHERE orders_status_id = '" . oos_db_input($oID) . "'");
          $history = $history_result->fields;
          if ($history['count'] > 0) {
            $remove_status = false;
            $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
          }
        }
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

			<div class="wrapper wrapper-content animated fadeInRight">
				<div class="row">
					<div class="col-lg-12">
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDERS_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $orders_statustable = $oostable['orders_status'];
  $orders_status_result_raw = "SELECT orders_status_id, orders_status_name
                               FROM $orders_statustable
                              WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY orders_status_id";
  $orders_status_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $orders_status_result_raw, $orders_status_result_numrows);
  $orders_status_result = $dbconn->Execute($orders_status_result_raw);
  while ($orders_status = $orders_status_result->fields) {
    if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders_status['orders_status_id']))) && !isset($oInfo) && (substr($action, 0, 3) != 'new')) {
      $oInfo = new objectInfo($orders_status);
    }

    if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $orders_status['orders_status_id']) . '\'">' . "\n";
    }

    if (DEFAULT_ORDERS_STATUS_ID == $orders_status['orders_status_id']) {
      echo '                <td class="dataTableContent"><b>' . $orders_status['orders_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $orders_status['orders_status_name'] . '</td>' . "\n";
    }
?>

                <td class="dataTableContent" align="right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $orders_status['orders_status_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $orders_status_result->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
    if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('insert','insert_off.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['orders_status'], 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $orders_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $orders_status_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) . ' <a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $orders_status_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $orders_status_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']', oos_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string);
      if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</b>');

      $contents = array('form' => oos_draw_form('status', $aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $oInfo->orders_status_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete', 'delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
     if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = array('text' => '<b>' . $oInfo->orders_status_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $_GET['page'] . '&oID=' . $oInfo->orders_status_id . '&action=delete') . '">' . oos_image_swap_button('delete', 'delete_off.gif', IMAGE_DELETE) . '</a>');

        $orders_status_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $orders_status_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $orders_status_inputs_string);
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