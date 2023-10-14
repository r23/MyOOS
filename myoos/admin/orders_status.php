<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders_status.php,v 1.19 2003/02/06 17:37:09 thomasamoulton
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

 /**
  * Return Orders Status Name
  *
  * @param  $orders_status_id
  * @param  $language
  * @return string
  */
function oos_get_orders_status_name($orders_status_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $orders_statustable = $oostable['orders_status'];
    $query = "SELECT orders_status_name
                FROM $orders_statustable
               WHERE orders_status_id = '" . intval($orders_status_id) . "'
                 AND orders_languages_id = '" . intval($language_id)  . "'";
    $orders_status_name = $dbconn->GetOne($query);

    return $orders_status_name;
}

 /**
  * Return Orders Status
  *
  * @param  $orders_status_id
  * @param  $language
  * @return array
  */
function oos_get_orders_status()
{
    $orders_status_array = [];

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $orders_statustable = $oostable['orders_status'];
    $orders_status_sql = "SELECT orders_status_id, orders_status_name
                          FROM $orders_statustable
                          WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'
                          ORDER BY orders_status_id";
    $orders_status_result = $dbconn->Execute($orders_status_sql);
    while ($orders_status = $orders_status_result->fields) {
        $orders_status_array[] = ['id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']];

        // Move that ADOdb pointer!
        $orders_status_result->MoveNext();
    }

    return $orders_status_array;
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

if (!empty($action)) {
    switch ($action) {
    case 'insert':
    case 'save':
        $orders_status_id = oos_db_prepare_input($_GET['oID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $language_id = $languages[$i]['id'];

            $sql_data_array = ['orders_status_name' => oos_db_prepare_input($_POST['orders_status_name'][$language_id])];

            if ($action == 'insert') {
                if (!oos_is_not_null($orders_status_id)) {
                    $next_id_result = $dbconn->Execute("SELECT max(orders_status_id) as orders_status_id FROM " . $oostable['orders_status'] . "");
                    $next_id = $next_id_result->fields;
                    $orders_status_id = $next_id['orders_status_id'] + 1;
                }

                $insert_sql_data = ['orders_status_id' => $orders_status_id, 'orders_languages_id' => $language_id];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['orders_status'], $sql_data_array);
            } elseif ($action == 'save') {
                oos_db_perform($oostable['orders_status'], $sql_data_array, 'UPDATE', "orders_status_id = '" . intval($orders_status_id) . "' AND orders_languages_id = '" . intval($language_id) . "'");
            }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
            $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . intval($orders_status_id) . "' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $orders_status_id));
        break;

    case 'deleteconfirm':
        $oID = oos_db_prepare_input($_GET['oID']);

        $orders_status_result = $dbconn->Execute("SELECT configuration_value FROM " . $oostable['configuration'] . " WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        $orders_status = $orders_status_result->fields;
        if ($orders_status['configuration_value'] == $oID) {
            $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '' WHERE configuration_key = 'DEFAULT_ORDERS_STATUS_ID'");
        }

        $dbconn->Execute("DELETE FROM " . $oostable['orders_status'] . " WHERE orders_status_id = '" . intval($oID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage));
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
                            <?php echo '<a href="' . oos_href_link_admin($aContents['customers'], 'selected_box=customers') . '">' . BOX_HEADING_CUSTOMERS . '</a>'; ?>
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
                            <th><?php echo TABLE_HEADING_ORDERS_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>    
<?php
  $orders_statustable = $oostable['orders_status'];
  $orders_status_result_raw = "SELECT orders_status_id, orders_status_name
                               FROM $orders_statustable
                              WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY orders_status_id";
  $orders_status_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $orders_status_result_raw, $orders_status_result_numrows);
  $orders_status_result = $dbconn->Execute($orders_status_result_raw);
while ($orders_status = $orders_status_result->fields) {
    if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders_status['orders_status_id']))) && !isset($oInfo) && (!str_starts_with((string) $action, 'new'))) {
        $oInfo = new objectInfo($orders_status);
    }

    if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
        echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $orders_status['orders_status_id']) . '\'">' . "\n";
    }

    if (DEFAULT_ORDERS_STATUS_ID == $orders_status['orders_status_id']) {
        echo '                <td><b>' . $orders_status['orders_status_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
        echo '                <td>' . $orders_status['orders_status_name'] . '</td>' . "\n";
    } ?>

                <td class="text-right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders_status['orders_status_id'] == $oInfo->orders_status_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
} else {
                                           echo '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $orders_status['orders_status_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                                       } ?>&nbsp;</td>
              </tr>
    <?php
    // Move that ADOdb pointer!
    $orders_status_result->MoveNext();
}
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_status_split->display_count($orders_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_ORDERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_status_split->display_links($orders_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
 if ($action == 'default') {
    ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
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
    $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_ORDERS_STATUS . '</b>'];

    $contents = ['form' => oos_draw_form('id', 'status', $aContents['orders_status'], 'page=' . $nPage . '&action=insert', 'post', false)];
    $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];

    $orders_status_inputs_string = '';
    $languages = oos_get_languages();
    for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
        $orders_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']');
    }

        $contents[] = ['text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string];
        $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

    break;

case 'edit':
    $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_ORDERS_STATUS . '</b>'];

    $contents = ['form' => oos_draw_form('id', 'status', $aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id  . '&action=save', 'post', false)];
    $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];

    $orders_status_inputs_string = '';
    $languages = oos_get_languages();
    for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
        $orders_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('orders_status_name[' . $languages[$i]['id'] . ']', oos_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']));
    }

        $contents[] = ['text' => '<br>' . TEXT_INFO_ORDERS_STATUS_NAME . $orders_status_inputs_string];
    if (DEFAULT_ORDERS_STATUS_ID != $oInfo->orders_status_id) {
        $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
    }
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

    break;

case 'delete':
    $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDERS_STATUS . '</b>'];

    $contents = ['form' => oos_draw_form('id', 'status', $aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id  . '&action=deleteconfirm', 'post', false)];
    $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
    $contents[] = ['text' => '<br><b>' . $oInfo->orders_status_name . '</b>'];
    if ($remove_status) {
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
    }

    break;

default:
    if (isset($oInfo) && is_object($oInfo)) {
        $heading[] = ['text' => '<b>' . $oInfo->orders_status_name . '</b>'];

        $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['orders_status'], 'page=' . $nPage . '&oID=' . $oInfo->orders_status_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];

        $orders_status_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $orders_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_get_orders_status_name($oInfo->orders_status_id, $languages[$i]['id']);
        }

        $contents[] = ['text' => $orders_status_inputs_string];
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