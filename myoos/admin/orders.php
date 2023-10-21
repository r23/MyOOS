<?php
/**
 * ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: orders.php,v 1.107 2003/02/06 17:37:08 thomasamoulton
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
 * Remove Order
 *
 * @param $order_id
 * @param $restock
 */
function oos_remove_order($order_id, $restock = false)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if (is_numeric($order_id)) {
        if ($restock == 'on') {
            $orders_productstable = $oostable['orders_products'];
            $order_sql = "SELECT products_id, products_quantity
                      FROM $orders_productstable
                      WHERE orders_id = '" . intval($order_id) . "'";
            $order_result = $dbconn->Execute($order_sql);
            while ($order = $order_result->fields) {
                $productstable = $oostable['products'];
                $dbconn->Execute(
                    "UPDATE $productstable
                            SET products_quantity = products_quantity + " . $order['products_quantity'] . ",
                                products_ordered = products_ordered - " . $order['products_quantity'] . "
                          WHERE products_id = '" . $order['products_id'] . "'"
                );

                // Move that ADOdb pointer!
                $order_result->MoveNext();
            }
        }

        $orderstable = $oostable['orders'];
        $dbconn->Execute("DELETE FROM $orderstable WHERE orders_id = '" . oos_db_input($order_id) . "'");
        $orders_productstable = $oostable['orders_products'];
        $dbconn->Execute("DELETE FROM $orders_productstable WHERE orders_id = '" . oos_db_input($order_id) . "'");
        $orders_products_attributesstable = $oostable['orders_products_attributes'];
        $dbconn->Execute("DELETE FROM $orders_products_attributesstable WHERE orders_id = '" . oos_db_input($order_id) . "'");
        $orders_status_historytable = $oostable['orders_status_history'];
        $dbconn->Execute("DELETE FROM $orders_status_historytable WHERE orders_id = '" . oos_db_input($order_id) . "'");
        $orders_totaltable = $oostable['orders_total'];
        $dbconn->Execute("DELETE FROM $orders_totaltable WHERE orders_id = '" . oos_db_input($order_id) . "'");
    }
}


function oos_get_languages_id($iso_639_2)
{
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $languagestable = $oostable['languages'];
    $languages_result = $dbconn->Execute("SELECT languages_id, iso_639_2 FROM $languagestable WHERE iso_639_2 = '" . oos_db_input($iso_639_2) . "'");
    if (!$languages_result->RecordCount()) {
        $LangID = $_SESSION['language_id'];
    } else {
        $LangID = $languages_result->fields['languages_id'];
    }

    return $LangID;
}


require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$orders_statuses = [];
$orders_status_array = [];
$orders_statustable = $oostable['orders_status'];
$orders_status_result = $dbconn->Execute("SELECT orders_status_id, orders_status_name FROM $orders_statustable WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'");
while ($orders_status = $orders_status_result->fields) {
    $orders_statuses[] = ['id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']];
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];

    // Move that ADOdb pointer!
    $orders_status_result->MoveNext();
}

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'update_order':
        $oID = oos_db_prepare_input($_GET['oID']);
        $comments = isset($_POST['comments']) ? oos_db_prepare_input($_POST['comments']) : '';
        $status = isset($_POST['status']) ? oos_db_prepare_input($_POST['status']) : '';

        $order_updated = false;

        $orderstable = $oostable['orders'];
        $check_status_result = $dbconn->Execute("SELECT customers_name, customers_email_address, orders_status, date_purchased, orders_language FROM $orderstable WHERE orders_id = '" . oos_db_input($oID) . "'");
        $check_status = $check_status_result->fields;

        if ($check_status['orders_status'] != $status || $comments != '') {
            $orderstable = $oostable['orders'];
            $dbconn->Execute("UPDATE $orderstable SET orders_status = '" . oos_db_input($status) . "', last_modified = now() WHERE orders_id = '" . oos_db_input($oID) . "'");

            $orderstable = $oostable['orders'];
            $check_status_result2 = $dbconn->Execute("SELECT customers_name, customers_email_address, orders_status, date_purchased FROM $orderstable WHERE orders_id = '" . oos_db_input($oID) . "'");
            $check_status2 = $check_status_result2->fields;

            $orders_products_downloadtable = $oostable['orders_products_download'];
            $dbconn->Execute("UPDATE $orders_products_downloadtable SET download_maxdays = '" . oos_db_input(DOWNLOAD_MAX_DAYS) . "', download_count = '" . oos_db_input(DOWNLOAD_MAX_COUNT) . "' WHERE orders_id = '" . oos_db_input($oID) . "'");

            $customer_notified = '0';

            if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
                if (oos_is_not_null($check_status['orders_language'])) {
                    include 'includes/languages/' . $check_status['orders_language'] . '/email_orders.php';
                    $nLangID = oos_get_languages_id($check_status['orders_language']);
                    $orders_statustable = $oostable['orders_status'];
                    $orders_status_result = $dbconn->Execute("SELECT orders_status_id, orders_status_name FROM $orders_statustable WHERE orders_languages_id = '" . intval($nLangID) . "'");
                } else {
                    $orders_statustable = $oostable['orders_status'];
                    include 'includes/languages/' . $_SESSION['language'] . '/email_orders.php';
                    $orders_status_result = $dbconn->Execute("SELECT orders_status_id, orders_status_name FROM $orders_statustable WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'");
                }

                $orders_statuses = [];
                $orders_status_array = [];
                while ($orders_status = $orders_status_result->fields) {
                    $orders_statuses[] = ['id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']];
                    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
                    // Move that ADOdb pointer!
                    $orders_status_result->MoveNext();
                }

                // status query
                $orders_statustable = $oostable['orders_status'];
                $orders_status_result = $dbconn->Execute("SELECT orders_status_name FROM $orders_statustable WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "' AND orders_status_id = '" . oos_db_input($status) . "'");
                $o_status = $orders_status_result->fields;
                $o_status = $o_status['orders_status_name'];

                $notify_comments = '';
                if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
                    if (isset($comments)) {
                        $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
                    }
                }
                $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . oos_catalog_link($aCatalog['account_history_info'], 'order_id=' . $oID) . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . oos_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
                oos_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), nl2br($email), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
                $customer_notified = '1';
            }

            $orders_status_historytable = $oostable['orders_status_history'];
            $dbconn->Execute("INSERT INTO $orders_status_historytable (orders_id, orders_status_id, date_added, customer_notified, comments) VALUES ('" . oos_db_input($oID) . "', '" . oos_db_input($status) . "', now(), '" . $customer_notified . "', '" . oos_db_input($comments)  . "')");

            $order_updated = true;
        }

        if ($order_updated) {
            $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
        } else {
            $messageStack->add_session(WARNING_ORDER_NOT_UPDATED, 'warning');
        }

        oos_redirect_admin(oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action']) . 'action=edit'));
        break;

    case 'update_serial':
        $oID = oos_db_prepare_input($_GET['oID']);

        $serial_number = oos_db_prepare_input($_POST['serial_number']);
        $serial = oos_db_prepare_input($_GET['serial']);

        $orders_productstable = $oostable['orders_products'];
        $query = "UPDATE $orders_productstable SET products_serial_number = '" . oos_db_input($serial_number) . "' WHERE orders_id = '" . oos_db_input($oID) . "' AND products_id = '" . oos_db_input($serial) . "'";
        $dbconn->Execute($query);

        $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');

        oos_redirect_admin(oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action']) . 'action=edit&serial_updated=1'));
        break;

    case 'deleteconfirm':
        $oID = oos_db_prepare_input($_GET['oID']);

        oos_remove_order($oID, $_POST['restock']);

        oos_redirect_admin(oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action'])));
        break;
}

if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = oos_db_prepare_input($_GET['oID']);

    $orderstable = $oostable['orders'];
    $orders_result = $dbconn->Execute("SELECT orders_id FROM $orderstable WHERE orders_id = '" . oos_db_input($oID) . "'");
    $order_exists = true;
    if (!$orders_result->RecordCount()) {
        $order_exists = false;
        $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
    }
}

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';

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
            
<?php
if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID);
    $the_customers_id = $order->customer['id']; ?>
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
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"></td>
            <td class="pageHeading" align="right"></td>
            <td class="pageHeading" align="right">
            <?php echo '<a href="' . oos_href_link_admin($aContents['edit_orders'], oos_get_all_get_params(['action'])) . '">' . oos_button(BUTTON_EDIT) . '</a> &nbsp; '; ?>
            <?php echo '<a href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action'])) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
            </td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="3"></td>
          </tr>
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_CUSTOMER; ?></b></td>
                <td class="main"><?php echo oos_address_format($order->customer['format_id'], $order->customer, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
              <tr>
                <td colspan="2"></td>
              </tr>

              <tr>
                <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
                <td class="main"><?php echo $order->customer['telephone']; ?></td>
              </tr>
            <tr>
                <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
                <td class="main"><?php echo '<a href="mailto:' . $order->customer['email_address'] . '"><u>' . $order->customer['email_address'] . '</u></a>'; ?></td>
              </tr>
            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></td>
                <td class="main"><?php
                if (!isset($order->delivery['name'])) {
                    echo oos_address_format($order->delivery['format_id'], $order->delivery, 1, '&nbsp;', '<br>');
                } ?></td>
              </tr>

            </table></td>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
                <td class="main"><?php echo oos_address_format($order->billing['format_id'], $order->billing, 1, '&nbsp;', '<br>'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_ORDER_NUMBER; ?></b></td>
            <td class="main"><?php echo intval($oID); ?></td>
          <tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_ORDER_DATE; ?></b></td>
            <td class="main"><?php echo oos_datetime_short($order->info['date_purchased']); ?></td>
          <tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
            <td class="main"><?php echo $order->info['payment_method']; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td>
                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                            <th><?php echo TABLE_HEADING_PRODUCTS_SERIAL_NUMBER; ?></th>
                            <th><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_TAX; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_PRICE_EXCLUDING_TAX; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_PRICE_INCLUDING_TAX; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_TOTAL_EXCLUDING_TAX; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_TOTAL_INCLUDING_TAX; ?></th>
                        </tr>    
                    </thead>
    <?php
    for ($i = 0, $n = is_countable($order->products) ? count($order->products) : 0; $i < $n; $i++) {
        echo '          <tr class="dataTableRow">' . "\n" .
           '            <td valign="top" align="right">' . $order->products[$i]['qty'] . '&nbsp;x</td>' . "\n" .
           '            <td valign="top">' . $order->products[$i]['name'];

        if (isset($order->products[$i]['attributes']) && ((is_countable($order->products[$i]['attributes']) ? count($order->products[$i]['attributes']) : 0) > 0)) {
            for ($j = 0, $k = is_countable($order->products[$i]['attributes']) ? count($order->products[$i]['attributes']) : 0; $j < $k; $j++) {
                echo '<br><nobr><small>&nbsp;<i> - ' . $order->products[$i]['attributes'][$j]['option'] . ': ' . $order->products[$i]['attributes'][$j]['value'];
                if ($order->products[$i]['attributes'][$j]['price'] != '0') {
                    echo ' (' . $order->products[$i]['attributes'][$j]['prefix'] . $currencies->format($order->products[$i]['attributes'][$j]['price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . ')';
                }
                echo '</i></small></nobr>';
            }
        }

        if (isset($order->products[$i]['old_electrical_equipment']) && ($order->products[$i]['old_electrical_equipment'] == 1)) {
            if ($order->products[$i]['return_free_of_charge'] == 1) {
                echo '<br>' .TEXT_YES;
            } elseif ($order->products[$i]['return_free_of_charge'] == 0) {
                echo '<br>' .TEXT_NO;
            }
        }
        echo '            </td>' . "\n";
        $serial_number = ENTRY_ADD_SERIAL;

        if (oos_is_not_null($order->products[$i]['serial_number'])) {
            $serial_number = $order->products[$i]['serial_number'];
        }
        echo '            <td valign="top"><a href="' . oos_href_link_admin($aContents['orders'], 'action=edit&oID=' . $oID . '&serial=' . $i) . '">' . $serial_number . '</a></td>' . "\n" .
           '            <td valign="top">' . $order->products[$i]['model'] . '</td>' . "\n" .
           '            <td align="right" valign="top">' . oos_display_tax_value($order->products[$i]['tax']) . '%</td>' . "\n" .
           '            <td align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td align="right" valign="top"><b>' . $currencies->format(oos_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']), true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td align="right" valign="top"><b>' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n" .
           '            <td align="right" valign="top"><b>' . $currencies->format(oos_add_tax($order->products[$i]['final_price'], $order->products[$i]['tax']) * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</b></td>' . "\n";
        echo '          </tr>' . "\n";

        if ((isset($_GET['serial']) && ($_GET['serial'] == $i)) || (isset($_GET['serial_updated']) && ($_GET['serial_updated'] <> 1))) {
            echo '          <tr class="dataTableRow">' . "\n" .
             '            <td colspan="2" valign="top" align="right">' . ENTRY_ENTER_SERIAL . ':&nbsp;</td>' . "\n";

            echo '            <td colspan="7" valign="top">' .
                          oos_draw_form('id', 'serial_form', $aContents['orders'], 'action=update_serial&oID=' . $oID . '&serial=' . $order->products[$i]['id'], 'post', false) .
                          oos_draw_input_field('serial_number', $serial_number, '', false, 'text') . '&nbsp;&nbsp;' . oos_submit_button(BUTTON_UPDATE) . '</td>' . "\n" .
             '          </tr>' . "\n";
        }
    } ?>
          <tr>
            <td align="right" colspan="9"><table border="0" cellspacing="0" cellpadding="2">
    <?php
    for ($i = 0, $n = is_countable($order->totals) ? count($order->totals) : 0; $i < $n; $i++) {
        echo '              <tr>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['title'] . '</td>' . "\n" .
           '                <td align="right" class="smallText">' . $order->totals[$i]['text'] . '</td>' . "\n" .
           '              </tr>' . "\n";
    } ?>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_CUSTOMER_NOTIFIED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_STATUS; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          </tr>
    <?php
    $orders_status_historytable = $oostable['orders_status_history'];
    $orders_history_result = $dbconn->Execute("SELECT orders_status_id, date_added, customer_notified, comments FROM $orders_status_historytable WHERE orders_id = '" . oos_db_input($oID) . "' ORDER BY date_added");
    if ($orders_history_result->RecordCount()) {
        while ($orders_history = $orders_history_result->fields) {
            echo '          <tr>' . "\n" .
            '            <td class="smallText" align="center">' . oos_datetime_short($orders_history['date_added']) . '</td>' . "\n" .
            '            <td class="smallText" align="center">';
            if ($orders_history['customer_notified'] == '1') {
                echo '<i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i>' . "</td>\n";
            } else {
                echo oos_image(OOS_IMAGES . 'icons/cross.gif', ICON_CROSS) . "</td>\n";
            }
            echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n" .
            '            <td class="smallText">' . nl2br((string) oos_output_string($orders_history['comments'])) . '&nbsp;</td>' . "\n" .
            '          </tr>' . "\n";
            // Move that ADOdb pointer!
            $orders_history_result->MoveNext();
        }
    } else {
        echo '          <tr>' . "\n" .
           '            <td class="smallText" colspan="5">' . TEXT_NO_ORDER_HISTORY . '</td>' . "\n" .
           '          </tr>' . "\n";
    } ?>
        </table></td>
      </tr>
      <tr>
        <td class="main"><br><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr><?php echo oos_draw_form('id', 'status', $aContents['orders'], oos_get_all_get_params(['action']) . 'action=update_order', 'post', false); ?>
        <td class="main"><?php echo oos_draw_textarea_field('comments', 'soft', '60', '5'); ?></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo oos_draw_pull_down_menu('status', '', $orders_statuses, $order->info['orders_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo oos_draw_checkbox_field('notify', '', true); ?></td>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo oos_draw_checkbox_field('notify_comments', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo oos_submit_button(BUTTON_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>
      <tr>
        <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['invoice'], 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . oos_button(IMAGE_ORDERS_INVOICE) . '</a> <a href="' . oos_href_link_admin($aContents['packingslip'], 'oID=' . $_GET['oID']) . '" TARGET="_blank">' . oos_button(IMAGE_ORDERS_PACKINGSLIP) . '</a> <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action'])) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
      </tr>
          </table>
    <?php
} else {
    ?>
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
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">        
                        <?php echo oos_draw_form('id', 'orders', $aContents['orders'], '', 'get', false, 'class="form-inline"'); ?>
                            <div id="DataTables_Table_0_filter" class="dataTables_filter">        
                                <label><?php echo HEADING_TITLE_SEARCH; ?></label>
                                <?php echo oos_draw_input_field('oID', '', 'size="12"') . oos_draw_hidden_field('action', 'edit'); ?>
                            </div>
                        </form>
                        <?php echo oos_draw_form('id', 'status', $aContents['orders'], '', 'get', false, 'class="form-inline"'); ?>
                            <div class="dataTables_filter">            
                                <label><?php echo HEADING_TITLE_STATUS; ?></label>
                                <?php echo oos_draw_pull_down_menu('status', '', [['id' => '', 'text' => TEXT_ALL_ORDERS], ...$orders_statuses], '', 'onChange="this.form.submit();"'); ?>
                            </div>                            
                        </form>                
                    </div>
                </div>            
            
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
                            <th><?php echo TABLE_HEADING_CUSTOMERS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ORDER_TOTAL; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_DATE_PURCHASED; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
                    <tbody>
          
    <?php
    if (isset($_GET['cID'])) {
        $cID = oos_db_prepare_input($_GET['cID']);

        $orderstable = $oostable['orders'];
        $orders_totaltable = $oostable['orders_total'];
        $orders_statustable = $oostable['orders_status'];
        $orders_result_raw = "SELECT o.orders_id, o.customers_name, o.customers_id, o.payment_method, o.date_purchased,
                                   o.last_modified, o.currency, o.currency_value, s.orders_status_name, ot.text AS order_total
                             FROM  $orderstable o LEFT JOIN
                                   $orders_totaltable ot
                                ON (o.orders_id = ot.orders_id),
                                   $orders_statustable s
                             WHERE o.customers_id = '" . oos_db_input($cID) . "' AND
                                   o.orders_status = s.orders_status_id AND
                                   s.orders_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                   ot.class = 'ot_total'
                             ORDER BY orders_id DESC";
    } elseif (isset($_GET['status'])) {
        $status = oos_db_prepare_input($_GET['status']);

        $orderstable = $oostable['orders'];
        $orders_totaltable = $oostable['orders_total'];
        $orders_statustable = $oostable['orders_status'];
        $orders_result_raw = "SELECT o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, 
                                   o.currency, o.currency_value, s.orders_status_name, ot.text as order_total
                             FROM $orderstable o LEFT JOIN
                                  $orders_totaltable ot
                               ON (o.orders_id = ot.orders_id),
                                  $orders_statustable s
                            WHERE o.orders_status = s.orders_status_id AND
                                  s.orders_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                  s.orders_status_id = '" . oos_db_input($status) . "' AND
                                  ot.class = 'ot_total' 
                            ORDER BY o.orders_id DESC";
    } else {
        $orderstable = $oostable['orders'];
        $orders_totaltable = $oostable['orders_total'];
        $orders_statustable = $oostable['orders_status'];
        $orders_result_raw = "SELECT o.orders_id, o.customers_name, o.payment_method, o.date_purchased, o.last_modified, 
                                   o.currency, o.currency_value, s.orders_status_name, ot.text as order_total
                             FROM $orderstable o LEFT JOIN
                                  $orders_totaltable ot
                               ON (o.orders_id = ot.orders_id),
                                  $orders_statustable s
                            WHERE o.orders_status = s.orders_status_id AND
                                  s.orders_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                  ot.class = 'ot_total'
                           ORDER BY o.orders_id DESC";
    }
    $orders_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $orders_result_raw, $orders_result_numrows);
    $orders_result = $dbconn->Execute($orders_result_raw);
    while ($orders = $orders_result->fields) {
        if ((!isset($_GET['oID']) || (isset($_GET['oID']) && ($_GET['oID'] == $orders['orders_id']))) && !isset($oInfo)) {
            $oInfo = new objectInfo($orders);
        }

        if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
            echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $oInfo->orders_id . '&action=edit') . '\'">' . "\n";
        } else {
            echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID']) . 'oID=' . $orders['orders_id']) . '\'">' . "\n";
        } ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $orders['orders_id'] . '&action=edit') . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . $orders['customers_name']; ?></td>
                <td class="text-right"><?php echo strip_tags((string) $orders['order_total']); ?></td>
                <td class="text-center"><?php echo oos_datetime_short($orders['date_purchased']); ?></td>
                <td class="text-right"><?php echo $orders['orders_status_name']; ?></td>
                <td class="text-right"><?php if (isset($oInfo) && is_object($oInfo) && ($orders['orders_id'] == $oInfo->orders_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID']) . 'oID=' . $orders['orders_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $orders_result->MoveNext();
    } ?>
            </tbody>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $orders_split->display_count($orders_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_ORDERS); ?></td>
                    <td class="smallText" align="right"><?php echo $orders_split->display_links($orders_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage, oos_get_all_get_params(['page', 'oID', 'action'])); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
    <?php
    $heading = [];
    $contents = [];

    switch ($action) {
        case 'delete':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_ORDER . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'orders', $aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $oInfo->orders_id . '&action=deleteconfirm', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_DELETE_INTRO . '<br><br><b>' . $cInfo->customers_firstname . ' ' . $cInfo->customers_lastname . '</b>'];
            $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('restock') . ' ' . TEXT_INFO_RESTOCK_PRODUCT_QUANTITY];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $oInfo->orders_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
            break;

        default:
            if (isset($oInfo) && is_object($oInfo)) {
                $heading[] = ['text' => '<b>[' . $oInfo->orders_id . ']&nbsp;&nbsp;' . oos_datetime_short($oInfo->date_purchased) . '</b>'];

                $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $oInfo->orders_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['oID', 'action']) . 'oID=' . $oInfo->orders_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
                $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['invoice'], 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . oos_button(IMAGE_ORDERS_INVOICE) . '</a> <a href="' . oos_href_link_admin($aContents['packingslip'], 'oID=' . $oInfo->orders_id) . '" TARGET="_blank">' . oos_button(IMAGE_ORDERS_PACKINGSLIP) . '</a>'];

                $contents[] = ['text' => '<br>' . TEXT_DATE_ORDER_CREATED . ' ' . oos_date_short($oInfo->date_purchased)];
                if (oos_is_not_null($oInfo->last_modified)) {
                    $contents[] = ['text' => TEXT_DATE_ORDER_LAST_MODIFIED . ' ' . oos_date_short($oInfo->last_modified)];
                }
                $contents[] = ['text' => '<br>' . TEXT_INFO_PAYMENT_METHOD . ' '  . $oInfo->payment_method];
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
    } ?>
          </tr>
        </table>
    </div>
    <?php
}
?>

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
