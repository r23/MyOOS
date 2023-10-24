<?php
/**
 * ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers_status.php,v 1.1 2003/01/08 10:53:01 elarifr
   ----------------------------------------------------------------------
   For Customers Status v3.x
   Based on original module from OSCommerce CVS 2.2 2002/08/28
   Copyright elari@free.fr

   Download area : www.unlockgsm.com/dload-osc/
   CVS : http://cvs.sourceforge.net/cgi-bin/viewcvs.cgi/elari/?sortby=date#dirlist
   Released under the GNU General Public License.
   You have no rights to remove any greetings or copyrights notice or my name elari

   Bugs fixed by Guido.winger@post.rwth-aachen.de
   In version 3.x images will be uploaded into the catalog/images/icons instead of admin/images/icons
   If the directory doesn't exists, create it with your FTP program and chmod it to :
   (guido : chmod 777)
   (elari : chmod 744 is more secure if it works  for you)

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';
require 'includes/functions/function_customer.php';

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'insert':
    case 'save':
        $customers_status_id = oos_db_prepare_input($_GET['cID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $customers_status_name_array = oos_db_prepare_input($_POST['customers_status_name']);
            $customers_status_public = oos_db_prepare_input($_POST['customers_status_public']);
            $customers_status_show_price = oos_db_prepare_input($_POST['customers_status_show_price']);
            $customers_status_show_price_tax = oos_db_prepare_input($_POST['customers_status_show_price_tax']);
            $customers_status_ot_discount_flag = oos_db_prepare_input($_POST['customers_status_ot_discount_flag']);
            $customers_status_ot_discount = oos_db_prepare_input($_POST['customers_status_ot_discount']);
            $customers_status_ot_minimum = oos_db_prepare_input($_POST['customers_status_ot_minimum']);
            $customers_status_qty_discounts = oos_db_prepare_input($_POST['customers_status_qty_discounts']);

            $language_id = $languages[$i]['id'];

            if (isset($_REQUEST['payment'])) {
                $customers_status_payment = implode(';', $_REQUEST['payment']);
            }

            $sql_data_array = ['customers_status_name' => $customers_status_name_array[$language_id], 'customers_status_public' => $customers_status_public, 'customers_status_show_price' => $customers_status_show_price, 'customers_status_show_price_tax' => $customers_status_show_price_tax, 'customers_status_ot_discount_flag' => $customers_status_ot_discount_flag, 'customers_status_ot_discount' => $customers_status_ot_discount, 'customers_status_ot_minimum' => $customers_status_ot_minimum, 'customers_status_qty_discounts' => $customers_status_qty_discounts, 'customers_status_payment' => $customers_status_payment];
            if ($action == 'insert') {
                if (oos_empty($customers_status_id)) {
                    $next_id_result = $dbconn->Execute("SELECT max(customers_status_id) as customers_status_id FROM " . $oostable['customers_status'] . "");
                    $next_id = $next_id_result->fields;
                    $customers_status_id = $next_id['customers_status_id'] + 1;
                }

                $insert_sql_data = ['customers_status_id' => oos_db_prepare_input($customers_status_id), 'customers_status_languages_id' => $language_id];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['customers_status'], $sql_data_array);
            } elseif ($action == 'save') {
                oos_db_perform($oostable['customers_status'], $sql_data_array, 'UPDATE', "customers_status_id = '" . oos_db_input($customers_status_id) . "' AND customers_status_languages_id = '" . intval($language_id) . "'");
            }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
            $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '" . oos_db_input($customers_status_id) . "' WHERE configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $customers_status_id));
        break;

    case 'deleteconfirm':
        $cID = oos_db_prepare_input($_GET['cID']);

        $customers_status_result = $dbconn->Execute("SELECT configuration_value FROM " . $oostable['configuration'] . " WHERE configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'");
        $customers_status = $customers_status_result->fields;
        if ($customers_status['configuration_value'] == $cID) {
            $dbconn->Execute("UPDATE " . $oostable['configuration'] . " SET configuration_value = '' WHERE configuration_key = 'DEFAULT_CUSTOMERS_STATUS_ID'");
        }

        $dbconn->Execute("DELETE FROM " . $oostable['customers_status'] . " WHERE customers_status_id = '" . oos_db_input($cID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage));
        break;

    case 'delete':
        $cID = oos_db_prepare_input($_GET['cID']);

        $status_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['customers'] . " WHERE customers_status = '" . oos_db_input($cID) . "'");
        $status = $status_result->fields;

        $remove_status = true;
        if ($cID == DEFAULT_CUSTOMERS_STATUS_ID) {
            $remove_status = false;
            $messageStack->add(ERROR_REMOVE_DEFAULT_CUSTOMERS_STATUS, 'error');
        } elseif ($status['count'] > 0) {
            $remove_status = false;
            $messageStack->add(ERROR_STATUS_USED_IN_CUSTOMERS, 'error');
        } else {
            $history_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['customers_status_history'] . " WHERE '" . oos_db_input($cID) . "' in (new_value, old_value)");
            $history = $history_result->fields;
            if ($history['count'] > 0) {
                $remove_status = false;
                $messageStack->add(ERROR_STATUS_USED_IN_HISTORY, 'error');
            }
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
                            <th align="left" width="40%"><?php echo TABLE_HEADING_CUSTOMERS_STATUS; ?></th>
                            <th align="center" width="10%"></th>
                            <th class="text-center"><?php echo TABLE_HEADING_AMOUNT; ?></th>
                            <th class="text-center"><?php echo '%'; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_CUSTOMERS_QTY_DISCOUNTS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
  // Add V1.1  //Change from V3 i reuse same string entry_yes entry_no instead of entry_ot_xmember_yes/no these change are also reported in language file
  $customers_status_ot_discount_flag_array = [['id' => '0', 'text' => ENTRY_NO], ['id' => '1', 'text' => ENTRY_YES]];
$customers_status_qty_discounts_array =    [['id' => '0', 'text' => ENTRY_NO], ['id' => '1', 'text' => ENTRY_YES]];
$customers_status_public_array =           [['id' => '0', 'text' => ENTRY_NO], ['id' => '1', 'text' => ENTRY_YES]];
$customers_status_show_price_array =       [['id' => '0', 'text' => ENTRY_NO], ['id' => '1', 'text' => ENTRY_YES]];
$customers_status_show_price_tax_array =   [['id' => '0', 'text' => ENTRY_TAX_NO], ['id' => '1', 'text' => ENTRY_TAX_YES]];
$customers_status_result_raw = "SELECT
                                     customers_status_id, customers_status_name, customers_status_public,
                                     customers_status_show_price, customers_status_show_price_tax,
                                     customers_status_ot_discount_flag , customers_status_ot_discount,
                                     customers_status_ot_minimum, customers_status_qty_discounts,
                                     customers_status_payment
                                  FROM
                                     " . $oostable['customers_status'] . "
                                  WHERE
                                     customers_status_languages_id = '" . intval($_SESSION['language_id']) . "'
                                  ORDER BY
                                     customers_status_id";
$customers_status_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $customers_status_result_raw, $customers_status_result_numrows);
$customers_status_result = $dbconn->Execute($customers_status_result_raw);
while ($customers_status = $customers_status_result->fields) {
    if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $customers_status['customers_status_id']))) && !isset($cInfo) && (!str_starts_with((string) $action, 'new'))) {
        $cInfo = new objectInfo($customers_status);
    }

    if (isset($cInfo) && is_object($cInfo) && ($customers_status['customers_status_id'] == $cInfo->customers_status_id)) {
        echo '<tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '<tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $customers_status['customers_status_id']) . '\'">' . "\n";
    }
    if ($customers_status['customers_status_id'] == DEFAULT_CUSTOMERS_STATUS_ID) {
        echo '<td class="text-left"><b>' . $customers_status['customers_status_name'];
        echo ' (' . TEXT_DEFAULT . ')';
    } else {
        echo '<td class="text-left">' . $customers_status['customers_status_name'];
    }
    if ($customers_status['customers_status_public'] == '1') {
        echo ', public ';
    }
    echo '</b></td>';
    if ($customers_status['customers_status_show_price'] == '1') {
        echo '<td class="smallText" align="left">&euro;';
        if ($customers_status['customers_status_show_price_tax'] == '0') {
            echo '+';
        }
    } else {
        echo '<td class="smallText" align="left"> ';
    }

    echo '</td>';
    echo '<td class="text-center">' . $currencies->format($customers_status['customers_status_ot_minimum']) . '</td>';
    echo '<td class="text-center">' . $customers_status['customers_status_ot_discount'] . '%</td>';
    echo '<td class="text-center">';

    if ($customers_status['customers_status_qty_discounts'] == '1') {
        echo ENTRY_YES;
    } else {
        echo ENTRY_NO;
    }
    echo '</td>';
    echo "\n"; ?>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($customers_status['customers_status_id'] == $cInfo->customers_status_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $customers_status['customers_status_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
    <?php
    // Move that ADOdb pointer!
    $customers_status_result->MoveNext();
}

?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $customers_status_split->display_count($customers_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_CUSTOMERS_STATUS); ?></td>
                    <td class="smallText" align="right"><?php echo $customers_status_split->display_links($customers_status_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
 if ($action == 'default') {
     ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
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
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_CUSTOMERS_STATUS . '</b>'];
        $contents = ['form' => oos_draw_form('id', 'status', $aContents['customers_status'], 'page=' . $nPage . '&action=insert', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => TEXT_INFO_INSERT_INTRO];
        $customers_status_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $customers_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('customers_status_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_NAME . $customers_status_inputs_string];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_PUBLIC . '</b> ' . oos_draw_pull_down_menu('customers_status_public', '', $customers_status_public_array, $cInfo->customers_status_public ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO     . '<br><b>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE . '</b> ' . oos_draw_pull_down_menu('customers_status_show_price', '', $customers_status_show_price_array, $cInfo->customers_status_show_price ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX . '</b> ' . oos_draw_pull_down_menu('customers_status_show_price_tax', '', $customers_status_show_price_tax_array, $cInfo->customers_status_show_price_tax ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br><b>' . ENTRY_OT_XMEMBER . '</b> ' . oos_draw_pull_down_menu('customers_status_ot_discount_flag', '', $customers_status_ot_discount_flag_array, $cInfo->customers_status_ot_discount_flag ?? '') . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_MINIMUM_AMOUNT_OT_XMEMBER_INTRO . '<br><b>' . ENTRY_MINIMUM_AMOUNT_OT_XMEMBER . '</b><br>' . oos_draw_input_field('customers_status_ot_minimum', $cInfo->customers_status_ot_minimum ?? '')  . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . '<br>' . oos_draw_input_field('customers_status_ot_discount', $cInfo->customers_status_ot_discount ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO . '<br><b>' . ENTRY_STAFFELPREIS . '</b> ' . oos_draw_pull_down_menu('customers_status_qty_discounts', '', $customers_status_qty_discounts_array, $cInfo->customers_status_qty_discounts ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_PAYMENT . '</b><br> ' . oos_installed_payment($cInfo->customers_status_payment ?? '')];
        $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'edit':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_CUSTOMERS_STATUS . '</b>'];
        $contents = ['form' => oos_draw_form('id', 'status', $aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id  .'&action=save', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => TEXT_INFO_EDIT_INTRO];
        $customers_status_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
            $customers_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('customers_status_name[' . $languages[$i]['id'] . ']', oos_get_customer_status_name($cInfo->customers_status_id, $languages[$i]['id']));
        }
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_NAME . $customers_status_inputs_string];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PUBLIC_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_PUBLIC . '</b> ' . oos_draw_pull_down_menu('customers_status_public', '', $customers_status_public_array, $cInfo->customers_status_public ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_INTRO     . '<br><b>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE . '</b> ' . oos_draw_pull_down_menu('customers_status_show_price', '', $customers_status_show_price_array, $cInfo->customers_status_show_price ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_SHOW_PRICE_TAX_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_SHOW_PRICE_TAX . '</b> ' . oos_draw_pull_down_menu('customers_status_show_price_tax', '', $customers_status_show_price_tax_array, $cInfo->customers_status_show_price_tax ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br><b>' . ENTRY_OT_XMEMBER . '</b> ' . oos_draw_pull_down_menu('customers_status_ot_discount_flag', '', $customers_status_ot_discount_flag_array, $cInfo->customers_status_ot_discount_flag ?? ''). '<br>' . TEXT_INFO_CUSTOMERS_STATUS_MINIMUM_AMOUNT_OT_XMEMBER_INTRO . '<br><b>' . ENTRY_MINIMUM_AMOUNT_OT_XMEMBER . '</b><br>' . oos_draw_input_field('customers_status_ot_minimum', $cInfo->customers_status_ot_minimum ?? '') . '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_PRICE . '<br>' . oos_draw_input_field('customers_status_ot_discount', $cInfo->customers_status_ot_discount ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO . '<br><b>' . ENTRY_STAFFELPREIS . '</b> ' . oos_draw_pull_down_menu('customers_status_qty_discounts', '', $customers_status_qty_discounts_array, $cInfo->customers_status_qty_discounts ?? '')];
        $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_PAYMENT . '</b><br>'.  oos_installed_payment($cInfo->customers_status_payment ?? '')];
        if (DEFAULT_CUSTOMERS_STATUS_ID != $cInfo->customers_status_id) {
            $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT];
        }
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_CUSTOMERS_STATUS . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'status', $aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id  . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $cInfo->customers_status_name . '</b>'];
        if ($remove_status) {
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        }

        break;

    default:
        if (isset($cInfo) && is_object($cInfo)) {
            $heading[] = ['text' => '<b>' . $cInfo->customers_status_name . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['customers_status'], 'page=' . $nPage . '&cID=' . $cInfo->customers_status_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $customers_status_inputs_string = '';
            $languages = oos_get_languages();
            for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
                $customers_status_inputs_string .= '<br>' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_get_customer_status_name($cInfo->customers_status_id, $languages[$i]['id']);
            }
            $contents[] = ['text' => $customers_status_inputs_string];
            $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_DISCOUNT_OT_XMEMBER_INTRO . '<br><b>' . ENTRY_OT_XMEMBER . ' ' . $customers_status_ot_discount_flag_array[$cInfo->customers_status_ot_discount_flag]['text'] . ' (' . $cInfo->customers_status_ot_discount_flag . ')' . '</b><br>' . ENTRY_MINIMUM_AMOUNT_OT_XMEMBER . ':<br><b>' .  $currencies->format($cInfo->customers_status_ot_minimum) . ' - ' . $cInfo->customers_status_ot_discount . '%</b>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_STAFFELPREIS_INTRO . '<br><b>' . ENTRY_STAFFELPREIS . ' ' . $customers_status_qty_discounts_array[$cInfo->customers_status_qty_discounts]['text'] . ' (' . $cInfo->customers_status_qty_discounts . ')</b>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_CUSTOMERS_STATUS_PAYMENT_INTRO . '<br><b>' . ENTRY_CUSTOMERS_STATUS_PAYMENT . '</b>'];
            $contents[] = ['text' => '<br>'  . oos_customers_payment($cInfo->customers_status_payment)];
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
