<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: edit_orders.php,v 1.25 2003/08/07 00:28:44 jwh
   ----------------------------------------------------------------------
   Order Editor

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

// define our edit_orders functions
require 'includes/functions/function_categories.php';
require 'includes/functions/function_edit_orders.php';


require 'includes/classes/class_currencies.php';
$currencies = new currencies();

require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_order.php';

$language = oos_db_prepare_input($_SESSION['language']);

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$step = $_GET['step'] ?? $_POST['step'] ?? 1;
$oID = filter_input(INPUT_GET, 'oID', FILTER_VALIDATE_INT);
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'edit';



$update_products = isset($_POST['update_products']) ? oos_db_prepare_input($_POST['update_products']) : '';
$update_totals = isset($_POST['update_totals']) ? oos_db_prepare_input($_POST['update_totals']) : '';
$add_product_options = isset($_POST['add_product_options']) ? oos_db_prepare_input($_POST['add_product_options']) : '';

  // New "Status History" table has different format.
  $OldNewStatusValues = (oos_field_exists($oostable['orders_status_history'], "old_value") && oos_field_exists($oostable['orders_status_history'], "new_value"));
  $CommentsWithStatus = oos_field_exists($oostable['orders_status_history'], "comments");
  $SeparateBillingFields = oos_field_exists($oostable['orders'], "billing_name");

  // Optional Tax Rate/Percent
  $AddShippingTax = "19.0"; // e.g. shipping tax of 17.5% is "17.5"

  $orders_statuses = [];
  $orders_status_array = [];
  $orders_status_result = $dbconn->Execute("SELECT orders_status_id, orders_status_name FROM " . $oostable['orders_status'] . " WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'");
while ($orders_status = $orders_status_result->fields) {
    $orders_statuses[] = ['id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']];
    $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];

    // Move that ADOdb pointer!
    $orders_status_result->MoveNext();
}


//UPDATE_INVENTORY_QUANTITY_START#
$order_result = $dbconn->Execute("SELECT products_id, products_quantity FROM " . $oostable['orders_products'] . " WHERE orders_id = '" . intval($oID) . "'");


switch ($action) {

    // Update Order
case 'update_order':

    $order = new order($oID);
    $status = isset($_POST['status']) ? oos_db_prepare_input($_POST['status']) : '';

    // Update Order Info
    $UpdateOrders = "update " . $oostable['orders'] . " set 
      customers_name = '" . oos_db_input(stripslashes((string) $update_customer_name)) . "',
      customers_company = '" . oos_db_input(stripslashes((string) $update_customer_company)) . "',
      customers_street_address = '" . oos_db_input(stripslashes((string) $update_customer_street_address)) . "',
      customers_city = '" . oos_db_input(stripslashes((string) $update_customer_city)) . "',
      customers_state = '" . oos_db_input(stripslashes((string) $update_customer_state)) . "',
      customers_postcode = '" . oos_db_input($update_customer_postcode) . "',
      customers_country = '" . oos_db_input(stripslashes((string) $update_customer_country)) . "',
      customers_telephone = '" . oos_db_input($update_customer_telephone) . "',
      customers_email_address = '" . oos_db_input($update_customer_email_address) . "',";

    if ($SeparateBillingFields) {
        $UpdateOrders .= "billing_name = '" . oos_db_input(stripslashes((string) $update_billing_name)) . "',
      billing_company = '" . oos_db_input(stripslashes((string) $update_billing_company)) . "',
      billing_street_address = '" . oos_db_input(stripslashes((string) $update_billing_street_address)) . "',
      billing_city = '" . oos_db_input(stripslashes((string) $update_billing_city)) . "',
      billing_state = '" . oos_db_input(stripslashes((string) $update_billing_state)) . "',
      billing_postcode = '" . oos_db_input($update_billing_postcode) . "',
      billing_country = '" . oos_db_input(stripslashes((string) $update_billing_country)) . "',";
    }

    $UpdateOrders .= "delivery_name = '" . oos_db_input(stripslashes((string) $update_delivery_name)) . "',
      delivery_company = '" . oos_db_input(stripslashes((string) $update_delivery_company)) . "',
      delivery_street_address = '" . oos_db_input(stripslashes((string) $update_delivery_street_address)) . "',
      delivery_city = '" . oos_db_input(stripslashes((string) $update_delivery_city)) . "',
      delivery_state = '" . oos_db_input(stripslashes((string) $update_delivery_state)) . "',
      delivery_postcode = '" . oos_db_input($update_delivery_postcode) . "',
      delivery_country = '" . oos_db_input(stripslashes((string) $update_delivery_country)) . "',
      payment_method = '" . oos_db_input($update_info_payment_method) . "',";


    if (!str_starts_with((string) $update_info_cc_number, "(Last 4)")) {
        $UpdateOrders .= "cc_number = '$update_info_cc_number',";
    }

    $UpdateOrders .= "cc_expires = '$update_info_cc_expires',
      orders_status = '" . oos_db_input($status) . "'";

    if (!$CommentsWithStatus) {
        $UpdateOrders .= ", comments = '" . oos_db_input($comments) . "'";
    }

    $UpdateOrders .= " where orders_id = '" . oos_db_input($oID) . "';";

    $dbconn->Execute($UpdateOrders);
    $order_updated = true;


      $check_status_result = $dbconn->Execute("select customers_name, customers_email_address, orders_status, date_purchased from " . $oostable['orders'] . " where orders_id = '" . intval($oID) . "'");
      $check_status = $check_status_result->fields;

    // Update Status History & Email Customer if Necessary
    if ($order->info['orders_status'] != $status) {
        // Notify Customer
        $customer_notified = '0';
        if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $notify_comments = '';
            if (isset($_POST['notify_comments']) && ($_POST['notify_comments'] == 'on')) {
                $notify_comments = sprintf(EMAIL_TEXT_COMMENTS_UPDATE, $comments) . "\n\n";
            }
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_ORDER_NUMBER . ' ' . $oID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . oos_catalog_link($aContents['catalog_account_history_info'], 'order_id=' . $oID) . "\n" . EMAIL_TEXT_DATE_ORDERED . ' ' . oos_date_long($check_status['date_purchased']) . "\n\n" . $notify_comments . sprintf(EMAIL_TEXT_STATUS_UPDATE, $orders_status_array[$status]);
            oos_mail($check_status['customers_name'], $check_status['customers_email_address'], EMAIL_TEXT_SUBJECT, nl2br((string) $email_text), nl2br((string) $email_text), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
            $customer_notified = '1';
        }

        // "Status History" table has gone through a few
        // different changes, so here are different versions of
        // the status update.

        // NOTE: Theoretically, there shouldn't be a
        //       orders_status field in the ORDERS table. It
        //       should really just use the latest value from
        //       this status history table.

        if ($CommentsWithStatus) {
            $dbconn->Execute(
                "insert into " . $oostable['orders_status_history'] . " 
        (orders_id, orders_status_id, date_added, customer_notified, comments) 
        values ('" . oos_db_input($oID) . "', '" . oos_db_input($status) . "', now(), " . oos_db_input($customer_notified) . ", '" . oos_db_input($comments)  . "')"
            );
        } else {
            if ($OldNewStatusValues) {
                $dbconn->Execute(
                    "insert into " . $oostable['orders_status_history'] . " 
          (orders_id, new_value, old_value, date_added, customer_notified) 
          values ('" . oos_db_input($oID) . "', '" . oos_db_input($status) . "', '" . $order->info['orders_status'] . "', now(), " . oos_db_input($customer_notified) . ")"
                );
            } else {
                $dbconn->Execute(
                    "insert into " . $oostable['orders_status_history'] . " 
          (orders_id, orders_status_id, date_added, customer_notified) 
          values ('" . oos_db_input($oID) . "', '" . oos_db_input($status) . "', now(), " . oos_db_input($customer_notified) . ")"
                );
            }
        }
    }

    // Update Products
    $RunningSubTotal = 0;
    $RunningTax = 0;
    foreach ($update_products as $orders_products_id => $products_details) {
        // Update orders_products Table
        //UPDATE_INVENTORY_QUANTITY_START
        $order = $order_result->fields;
        if ($products_details['qty'] != $order['products_quantity']) {
            $differenza_quantita = ($products_details['qty'] - $order['products_quantity']);
            $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_quantity = products_quantity - " . intval($differenza_quantita) . ", products_ordered = products_ordered + " . intval($differenza_quantita) . " WHERE products_id = '" . intval($order['products_id']) . "'");
        }
        //UPDATE_INVENTORY_QUANTITY_END
        if ($products_details["qty"] > 0) {
            $Query = "update " . $oostable['orders_products'] . " set
          products_model = '" . $products_details["model"] . "',
          products_name = '" . str_replace("'", "&#39;", (string) $products_details["name"]) . "',
          final_price = '" . $products_details["final_price"] . "',
          products_tax = '" . $products_details["tax"] . "',
          products_quantity = '" . $products_details["qty"] . "'
          where orders_products_id = '$orders_products_id';";
            $dbconn->Execute($Query);

            // Update Tax and Subtotals
            $RunningSubTotal += $products_details["qty"] * $products_details["final_price"];
            $RunningTax += (($products_details["tax"]/100) * ($products_details["qty"] * $products_details["final_price"]));

            // Update Any Attributes
            if (isset($products_details[\ATTRIBUTES])) {
                foreach ($products_details["attributes"] as $orders_products_attributes_id => $attributes_details) {
                    $Query = "update " . $oostable['orders_products_attributes'] . " set
              products_options = '" . $attributes_details["option"] . "',
              products_options_values = '" . $attributes_details["value"] . "'
              where orders_products_attributes_id = '$orders_products_attributes_id';";
                    $dbconn->Execute($Query);
                }
            }
        } else {
            // 0 Quantity = Delete
            $Query = "DELETE FROM " . $oostable['orders_products'] . " where orders_products_id = '$orders_products_id';";
            $dbconn->Execute($Query);
            //UPDATE_INVENTORY_QUANTITY_START
            $order = $order_result->fields;
            if ($products_details['qty'] != $order['products_quantity']) {
                $differenza_quantita = ($products_details['qty'] - $order['products_quantity']);
                $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_quantity = products_quantity - " . intval($differenza_quantita) . ", products_ordered = products_ordered + " . intval($differenza_quantita) . " WHERE products_id = '" . intval($order['products_id']) . "'");
            }
            //UPDATE_INVENTORY_QUANTITY_END
            $Query = "DELETE FROM " . $oostable['orders_products_attributes'] . " WHERE orders_products_id = '$orders_products_id';";
            $dbconn->Execute($Query);
        }
    }

    // Shipping Tax
    foreach ($update_totals as $total_index => $total_details) {
        extract($total_details, EXTR_PREFIX_ALL, "ot");
        if ($ot_class == "ot_shipping") {
            $RunningTax += (($AddShippingTax / 100) * $ot_value);
        }
    }

    // Update Totals

    $RunningTotal = 0;
    $sort_order = 0;

    // Do pre-check for Tax field existence
    $ot_tax_found = 0;
    foreach ($update_totals as $total_details) {
        extract($total_details, EXTR_PREFIX_ALL, "ot");
        if ($ot_class == "ot_tax") {
            $ot_tax_found = 1;
            break;
        }
    }

    foreach ($update_totals as $total_index => $total_details) {
        extract($total_details, EXTR_PREFIX_ALL, "ot");

        if (trim(strtolower((string) $ot_title)) == "tax" || trim(strtolower((string) $ot_title)) == "tax:") {
            if ($ot_class != "ot_tax" && $ot_tax_found == 0) {
                // Inserting Tax
                $ot_class = "ot_tax";
                $ot_value = "x"; // This gets updated in the next step
                $ot_tax_found = 1;
            }
        }

        if (trim((string) $ot_title) && trim((string) $ot_value)) {
            $sort_order++;

            // Update ot_subtotal, ot_tax, and ot_total classes
            if ($ot_class == "ot_subtotal") {
                $ot_value = $RunningSubTotal;
            }

            if ($ot_class == "ot_tax") {
                $ot_value = $RunningTax;
                // print "ot_value = $ot_value<br>\n";
            }

            if ($ot_class == "ot_total") {
                $ot_value = $RunningTotal;
            }

            // Set $ot_text (display-formatted value)
            // $ot_text = "\$" . number_format($ot_value, 2, '.', ',');

            $order = new order($oID);
            $ot_text = $currencies->format($ot_value, true, $order->info['currency'], $order->info['currency_value']);

            if ($ot_class == "ot_total") {
                $ot_text = "<b>" . $ot_text . "</b>";
            }

            if ($ot_total_id > 0) {
                // In Database Already - Update
                $Query = "update " . $oostable['orders_total'] . " set
              title = '$ot_title',
              text = '$ot_text',
              value = '$ot_value',
              sort_order = '$sort_order'
              WHERE orders_total_id = '$ot_total_id'";
                $dbconn->Execute($Query);
            } else {

                // New Insert
                $Query = "insert into " . $oostable['orders_total'] . " set
              orders_id = '$oID',
              title = '$ot_title',
              text = '$ot_text',
              value = '$ot_value',
              class = '$ot_class',
              sort_order = '$sort_order'";
                $dbconn->Execute($Query);
            }

            $RunningTotal += $ot_value;
        } elseif ($ot_total_id > 0) {
            // Delete Total Piece
            $Query = "DELETE FROM " . $oostable['orders_total'] . " WHERE orders_total_id = '$ot_total_id'";
            $dbconn->Execute($Query);
        }
    }

    if ($order_updated) {
        $messageStack->add_session(SUCCESS_ORDER_UPDATED, 'success');
    }

    oos_redirect_admin(oos_href_link_admin("edit_orders.php", oos_get_all_get_params(['action']) . 'action=edit'));

    break;

    // Add a Product
case 'add_product':
    if ($step == 5) {
        // Get Order Info
        $oID = oos_db_prepare_input($_GET['oID']);
        $order = new order($oID);

        $AddedOptionsPrice = 0;

        // Get Product Attribute Info
        if (isset($add_product_options)) {
            foreach ($add_product_options as $option_id => $option_value_id) {
                $result = $dbconn->Execute("SELECT * FROM " . $oostable['products_attributes'] . " pa LEFT JOIN " . $oostable['products_options'] . " po ON po.products_options_id=pa.options_id LEFT JOIN " . $oostable['products_options_values'] . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id='$add_product_products_id' and options_id=$option_id and options_values_id=$option_value_id");
                $row = $result->fields;
                extract($row, EXTR_PREFIX_ALL, "opt");
                $AddedOptionsPrice += $opt_options_values_price;
                $option_value_details[$option_id][$option_value_id] = ["options_values_price" => $opt_options_values_price];
                $option_names[$option_id] = $opt_products_options_name;
                $option_values_names[$option_value_id] = $opt_products_options_values_name;
            }
        }

        // Get Product Info
        $InfoQuery = "select p.products_model,p.products_price,pd.products_name,p.products_tax_class_id from " . $oostable['products'] . " p left join " . $oostable['products_description'] . " pd on pd.products_id=p.products_id WHERE p.products_id='$add_product_products_id'";
        $result = $dbconn->Execute($InfoQuery);
        $row = $result->fields;
        extract($row, EXTR_PREFIX_ALL, "p");

        // Following functions are defined at the bottom of this file
        $CountryID = oos_get_country_id($order->delivery["country"]);
        $ZoneID = oos_get_zone_id($CountryID, $order->delivery["state"]);

        $ProductsTax = oos_get_tax_rate($p_products_tax_class_id, $CountryID, $ZoneID);

        $Query = "insert into " . $oostable['orders_products'] . " set
        orders_id = $oID,
        products_id = $add_product_products_id,
        products_model = '$p_products_model',
        products_name = '" . str_replace("'", "&#39;", (string) $p_products_name) . "',
        products_price = '$p_products_price',
        final_price = '" . ($p_products_price + $AddedOptionsPrice) . "',
        products_tax = '$ProductsTax',
        products_quantity = $add_product_quantity;";
        $dbconn->Execute($Query);
        $new_product_id = $dbconn->Insert_ID();
        //UPDATE_INVENTORY_QUANTITY_START
        $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_quantity = products_quantity - " . $add_product_quantity . ", products_ordered = products_ordered + " . $add_product_quantity . " WHERE products_id = '" . $add_product_products_id . "'");
        //UPDATE_INVENTORY_QUANTITY_END
        if (isset($add_product_options)) {
            foreach ($add_product_options as $option_id => $option_value_id) {
                $Query = "insert into " . $oostable['orders_products_attributes'] . " set
            orders_id = $oID,
            orders_products_id = $new_product_id,
            products_options = '" . $option_names[$option_id] . "',
            products_options_values = '" . $option_values_names[$option_value_id] . "',
            options_values_price = '" . $option_value_details[$option_id][$option_value_id]["options_values_price"] . "',
            price_prefix = '+';";
                $dbconn->Execute($Query);
            }
        }

        // Calculate Tax and Sub-Totals
        $order = new order($oID);
        $RunningSubTotal = 0;
        $RunningTax = 0;

        for ($i=0; $i < (is_countable($order->products) ? count($order->products) : 0); $i++) {
            $RunningSubTotal += ($order->products[$i]['qty'] * $order->products[$i]['final_price']);
            $RunningTax += (($order->products[$i]['tax'] / 100) * ($order->products[$i]['qty'] * $order->products[$i]['final_price']));
        }


        // Tax
        $Query = "update " . $oostable['orders_total'] . " set
        text = '\$" . number_format($RunningTax, 2, '.', ',') . "',
        value = '" . $RunningTax . "'
        WHERE class='ot_tax' and orders_id=$oID";
        $dbconn->Execute($Query);

        // Sub-Total
        $Query = "update " . $oostable['orders_total'] . " set
        text = '\$" . number_format($RunningSubTotal, 2, '.', ',') . "',
        value = '" . $RunningSubTotal . "'
        WHERE class='ot_subtotal' and orders_id=$oID";
        $dbconn->Execute($Query);

        // Total
        $Query = "select sum(value) as total_value from " . $oostable['orders_total'] . " WHERE class != 'ot_total' and orders_id=$oID";
        $result = $dbconn->Execute($Query);
        $row = $result->fields;
        $Total = $row["total_value"];

        $Query = "update " . $oostable['orders_total'] . " set
        text = '<b>\$" . number_format($Total, 2, '.', ',') . "</b>',
        value = '" . $Total . "'
        WHERE class='ot_total' and orders_id=$oID";
        $dbconn->Execute($Query);

        oos_redirect_admin(oos_href_link_admin("edit_orders.php", oos_get_all_get_params(['action']) . 'action=edit'));
    }
    break;
}

if (($action == 'edit') && isset($_GET['oID'])) {
    $oID = oos_db_prepare_input($_GET['oID']);

    $orders_result = $dbconn->Execute("SELECT orders_id FROM " . $oostable['orders'] . " WHERE orders_id = '" . intval($oID) . "'");
    $order_exists = true;
    if (!$orders_result->RecordCount()) {
        $order_exists = false;
        $messageStack->add(sprintf(ERROR_ORDER_DOES_NOT_EXIST, $oID), 'error');
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

            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
<!-- body_text //-->
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
if (($action == 'edit') && ($order_exists == true)) {
    $order = new order($oID); ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"></td>
            <td class="pageHeading" align="right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action'])) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
          </tr>
        </table></td>
      </tr>

<!-- Begin Addresses Block -->
      <tr><?php echo oos_draw_form('id', 'edit_order', "edit_orders.php", oos_get_all_get_params(['action', 'paycc']) . 'action=update_order', 'post', false); ?>
      <td>
      <table width="100%" border="0"><tr> <td><div align="center">
      <table width="548" border="0" align="center">
  <!--DWLayoutTable-->
  <tr>
    <td colspan="2" valign="top"><b> <?php echo ENTRY_CUSTOMER; ?> </b></td>
    <td width="6" rowspan="9" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="150" valign="top"><b> <?php echo ENTRY_BILLING_ADDRESS; ?></b></td>
    <td width="6" rowspan="9" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
    <td width="150" valign="top"><span class="main"><b><?php echo ENTRY_SHIPPING_ADDRESS; ?></b></span></td>
    <td width="1">&nbsp;</td>
  </tr>
  <tr>
    <td width="60" valign="top"> <?php echo ENTRY_CUSTOMER_NAME; ?>:</td>
    <td width="150" valign="top"><span class="main">
      <input name="update_customer_name" size="25" value="<?php echo oos_html_quotes($order->customer['name']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_name" size="25" value="<?php echo oos_html_quotes($order->billing['name']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_name" size="25" value="<?php echo oos_html_quotes($order->delivery['name']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_COMPANY; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_company" size="25" value="<?php echo oos_html_quotes($order->customer['company']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_company" size="25" value="<?php echo oos_html_quotes($order->billing['company']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_company" size="25" value="<?php echo oos_html_quotes($order->delivery['company']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_ADDRESS; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_street_address" size="25" value="<?php echo oos_html_quotes($order->customer['street_address']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_street_address" size="25" value="<?php echo oos_html_quotes($order->billing['street_address']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_street_address" size="25" value="<?php echo oos_html_quotes($order->delivery['street_address']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_CITY; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_city" size="25" value="<?php echo oos_html_quotes($order->customer['city']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_city" size="25" value="<?php echo oos_html_quotes($order->billing['city']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_city" size="25" value="<?php echo oos_html_quotes($order->delivery['city']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><?php echo ENTRY_CUSTOMER_STATE; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_state" size="25" value="<?php echo oos_html_quotes($order->customer['state']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_state" size="25" value="<?php echo oos_html_quotes($order->billing['state']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_state" size="25" value="<?php echo oos_html_quotes($order->delivery['state']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_POSTCODE; ?>:</td>
    <td valign="top"><span class="main">
      <input name="update_customer_postcode" size="25" value="<?php echo $order->customer['postcode']; ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_postcode" size="25" value="<?php echo $order->billing['postcode']; ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_postcode" size="25" value="<?php echo $order->delivery['postcode']; ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"> <?php echo ENTRY_CUSTOMER_COUNTRY; ?></td>
    <td valign="top"><span class="main">
      <input name="update_customer_country" size="25" value="<?php echo oos_html_quotes($order->customer['country']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_billing_country" size="25" value="<?php echo oos_html_quotes($order->billing['country']); ?>">
    </span></td>
    <td valign="top"><span class="main">
      <input name="update_delivery_country" size="25" value="<?php echo oos_html_quotes($order->delivery['country']); ?>">
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</div></td></tr></table>
<!-- End Addresses Block -->

      <tr>
  <td></td>
      </tr>

<!-- Begin Phone/Email Block -->
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_TELEPHONE_NUMBER; ?></b></td>
            <td class="main"><input name='update_customer_telephone' size='15' value='<?php echo $order->customer['telephone']; ?>'></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_EMAIL_ADDRESS; ?></b></td>
            <td class="main"><input name='update_customer_email_address' size='35' value='<?php echo $order->customer['email_address']; ?>'></td>
          </tr>
        </table></td>
      </tr>
<!-- End Phone/Email Block -->
      <tr>
  <td></td>
      </tr>

<!-- Begin Payment Block -->
      <tr>
  <td><table border="0" cellspacing="0" cellpadding="2">
    <tr>
      <td class="main"><b><?php echo ENTRY_PAYMENT_METHOD; ?></b></td>
      <td class="main"><input name='update_info_payment_method' size='20' value='<?php echo $order->info['payment_method']; ?>'>
      <?php
        if ($order->info['payment_method'] != "Credit Card") {
            echo ENTRY_UPDATE_TO_CC;
        } ?></td>
    </tr>

  </table></td>
      </tr>
<!-- End Payment Block -->
      <tr>
  <td></td>
      </tr>

<!-- Begin Products Listing Block -->
      <tr>
  <td>
                  <table class="table table-striped w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th colspan="2"><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                            <th><?php echo TABLE_HEADING_PRODUCTS_MODEL; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_TAX; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_UNIT_PRICE; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_TOTAL_PRICE; ?></th>
                        </tr>    
                    </thead>

  <!-- Begin Products Listings Block -->
    <?php
      // Override order.php Class's Field Limitations
    $index = 0;
    $order->products = [];
    $orders_products_result = $dbconn->Execute("select * from " . $oostable['orders_products'] . " WHERE orders_id = '" . intval($oID) . "'");
    while ($orders_products = $orders_products_result->fields) {
        $order->products[$index] = ['qty' => $orders_products['products_quantity'], 'name' => str_replace("'", "&#39;", (string) $orders_products['products_name']), 'model' => $orders_products['products_model'], 'tax' => $orders_products['products_tax'], 'price' => $orders_products['products_price'], 'final_price' => $orders_products['final_price'], 'orders_products_id' => $orders_products['orders_products_id']];

        $subindex = 0;
        $attributes_result_string = "select * from " . $oostable['orders_products_attributes'] . " WHERE orders_id = '" . intval($oID) . "' and orders_products_id = '" . intval($orders_products['orders_products_id']) . "'";
        $attributes_result = $dbconn->Execute($attributes_result_string);

        if ($attributes_result->RecordCount()) {
            while ($attributes = $attributes_result->fields) {
                $order->products[$index]['attributes'][$subindex] = ['option' => $attributes['products_options'], 'value' => $attributes['products_options_values'], 'prefix' => $attributes['price_prefix'], 'price' => $attributes['options_values_price'], 'orders_products_attributes_id' => $attributes['orders_products_attributes_id']];
                $subindex++;

                // Move that ADOdb pointer!
                $attributes_result->MoveNext();
            }
        }
        $index++;

        // Move that ADOdb pointer!
        $orders_products_result->MoveNext();
    }

    for ($i=0; $i < count($order->products); $i++) {
        $orders_products_id = $order->products[$i]['orders_products_id'];

        $RowStyle = "dataTableContent";

        echo '    <tr class="dataTableRow">' . "\n" .
        '      <td class="' . $RowStyle . '" valign="top" align="right">' . "<input name='update_products[$orders_products_id][qty]' size='2' value='" . $order->products[$i]['qty'] . "'>&nbsp;x</td>\n" .
        '      <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][name]' size='25' value='" . $order->products[$i]['name'] . "'>";

        // Has Attributes?
        if (isset($order->products[$i]['attributes']) && (count($order->products[$i]['attributes']) > 0)) {
            for ($j=0; $j < count($order->products[$i]['attributes']); $j++) {
                $orders_products_attributes_id = $order->products[$i]['attributes'][$j]['orders_products_attributes_id'];
                echo '<br><nobr><small>&nbsp;<i> - ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][option]' size='6' value='" . $order->products[$i]['attributes'][$j]['option'] . "'>" . ': ' . "<input name='update_products[$orders_products_id][attributes][$orders_products_attributes_id][value]' size='10' value='" . $order->products[$i]['attributes'][$j]['value'] . "'>";
                echo '</i></small></nobr>';
            }
        }

        echo '      </td>' . "\n" .
        '      <td class="' . $RowStyle . '" valign="top">' . "<input name='update_products[$orders_products_id][model]' size='12' value='" . $order->products[$i]['model'] . "'>" . '</td>' . "\n" .
        '      <td class="' . $RowStyle . '" align="center" valign="top">' . "<input name='update_products[$orders_products_id][tax]' size='3' value='" . oos_display_tax_value($order->products[$i]['tax']) . "'>" . '%</td>' . "\n" .
        '      <td class="' . $RowStyle . '" align="right" valign="top">' . "<input name='update_products[$orders_products_id][final_price]' size='5' value='" . number_format($order->products[$i]['final_price'], 2, '.', '') . "'>" . '</td>' . "\n" .
        '      <td class="' . $RowStyle . '" align="right" valign="top">' . $currencies->format($order->products[$i]['final_price'] * $order->products[$i]['qty'], true, $order->info['currency'], $order->info['currency_value']) . '</td>' . "\n" .
        '    </tr>' . "\n";
    } ?>
  <!-- End Products Listings Block -->

  <!-- Begin Order Total Block -->
    <tr>
      <td align="right" colspan="6">
        <table border="0" cellspacing="0" cellpadding="2" width="100%">
        <tr>
        <td align='center' valign='top'><br><a href="<?php print $php_self . "?oID=$oID&action=add_product&step=1"; ?>"><u><b><font size='3'><?php echo TEXT_DATE_ORDER_ADDNEW; ?> </font></b></u></a></td>
        <td align='right'>
        <table border="0" cellspacing="0" cellpadding="2">
    <?php

      // Override order.php Class's Field Limitations
    $totals_result = $dbconn->Execute("select * from " . $oostable['orders_total'] . " WHERE orders_id = '" . intval($oID) . "' order by sort_order");
    $order->totals = [];
    while ($totals = $totals_result->fields) {
        $order->totals[] = ['title' => $totals['title'], 'text' => $totals['text'], 'class' => $totals['class'], 'value' => $totals['value'], 'orders_total_id' => $totals['orders_total_id']];
        $totals_result->MoveNext();
    }

    $TotalsArray = [];
    for ($i=0; $i < count($order->totals); $i++) {
        $TotalsArray[] = ["Name" => $order->totals[$i]['title'], "Price" => number_format($order->totals[$i]['value'], 2, '.', ''), "Class" => $order->totals[$i]['class'], "TotalID" => $order->totals[$i]['orders_total_id']];
        $TotalsArray[] = ["Name" => "          ", "Price" => "", "Class" => "ot_custom", "TotalID" => "0"];
    }

    array_pop($TotalsArray);
    foreach ($TotalsArray as $TotalIndex => $TotalDetails) {
        $TotalStyle = "smallText";
        if (($TotalDetails["Class"] == "ot_subtotal") || ($TotalDetails["Class"] == "ot_total")) {
            echo  '       <tr>' . "\n" .
            '   <td class="main" align="right"><b>' . $TotalDetails["Name"] . '</b></td>' .
            '   <td class="main"><b>' . $TotalDetails["Price"] .
            "<input name='update_totals[$TotalIndex][title]' type='hidden' value='" . trim((string) $TotalDetails["Name"]) . "' size='" . strlen((string) $TotalDetails["Name"]) . "' >" .
            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
            '       </tr>' . "\n";
        } elseif ($TotalDetails["Class"] == "ot_tax") {
            echo  '       <tr>' . "\n" .
            '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim((string) $TotalDetails["Name"])) . "' value='" . trim((string) $TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
            '   <td class="main"><b>' . $TotalDetails["Price"] .
            "<input name='update_totals[$TotalIndex][value]' type='hidden' value='" . $TotalDetails["Price"] . "' size='6' >" .
            "<input name='update_totals[$TotalIndex][class]' type='hidden' value='" . $TotalDetails["Class"] . "'>\n" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" . '</b></td>' .
            '       </tr>' . "\n";
        } else {
            echo  '       <tr>' . "\n" .
            '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][title]' size='" . strlen(trim((string) $TotalDetails["Name"])) . "' value='" . trim((string) $TotalDetails["Name"]) . "'>" . '</td>' . "\n" .
            '   <td align="right" class="' . $TotalStyle . '">' . "<input name='update_totals[$TotalIndex][value]' size='6' value='" . $TotalDetails["Price"] . "'>" .
            "<input type='hidden' name='update_totals[$TotalIndex][class]' value='" . $TotalDetails["Class"] . "'>" .
            "<input type='hidden' name='update_totals[$TotalIndex][total_id]' value='" . $TotalDetails["TotalID"] . "'>" .
            '</td>' . "\n" .
            '       </tr>' . "\n";
        }
    } ?>
        </table>
        </td>
        </tr>
        </table>
      </td>
    </tr>
  <!-- End Order Total Block -->

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
          <?php if ($CommentsWithStatus) { ?>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_COMMENTS; ?></b></td>
          <?php } ?>
          </tr>
    <?php
    $orders_history_result = $dbconn->Execute("select * from " . $oostable['orders_status_history'] . " WHERE orders_id = '" . oos_db_input($oID) . "' order by date_added");
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
            echo '            <td class="smallText">' . $orders_status_array[$orders_history['orders_status_id']] . '</td>' . "\n";

            if ($CommentsWithStatus) {
                echo '            <td class="smallText">' . nl2br((string) oos_db_output($orders_history['comments'])) . '&nbsp;</td>' . "\n";
            }

            echo '          </tr>' . "\n";

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
      <tr>
        <td class="main">
        <?php
        if ($CommentsWithStatus) {
            echo oos_draw_textarea_field('comments', 'soft', '60', '5');
        } else {
            echo oos_draw_textarea_field('comments', 'soft', '60', '5', $order->info['comments']);
        } ?>
        </td>
      </tr>
      <tr>
        <td></td>
      </tr>

      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><b><?php echo ENTRY_STATUS; ?></b> <?php echo oos_draw_pull_down_menu('status', '', $orders_statuses, $order->info['orders_status']); ?></td>
          </tr>
          <tr>
            <td class="main"><b><?php echo ENTRY_NOTIFY_CUSTOMER; ?></b> <?php echo oos_draw_checkbox_field('notify', '', true); ?></td>
          </tr>
          <?php if ($CommentsWithStatus) { ?>
          <tr>
                <td class="main"><b><?php echo ENTRY_NOTIFY_COMMENTS; ?></b> <?php echo oos_draw_checkbox_field('notify_comments', '', true); ?></td>
          </tr>
          <?php } ?>
        </table></td>
      </tr>

      <tr>
  <td align='center' valign="top"><?php echo oos_submit_button(BUTTON_UPDATE); ?></td>
      </tr>
      </form>
    <?php
}
/*vx*/
if ($action == "add_product") {
    ?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo ADDING_TITLE; ?> #<?php echo $oID; ?></td>
            <td class="pageHeading" align="right"></td>
            <td class="pageHeading" align="right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['orders'], oos_get_all_get_params(['action'])) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
          </tr>
        </table></td>
      </tr>

    <?php

    //$result = $dbconn->Execute("SELECT products_name, p.products_id, cd.categories_name, ptc.categories_id FROM " . $oostable['products'] . " p LEFT JOIN " . $oostable['products_description'] . " pd ON pd.products_id=p.products_id LEFT JOIN " . $oostable['products_to_categories'] . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . $oostable['categories_description'] . " cd ON cd.categories_id=ptc.categories_id LEFT JOIN " . $oostable['categories_description'] . " x ON x.categories_name=cd.categories_name ORDER BY categories_id");
    $result = $dbconn->Execute("SELECT products_name, p.products_id, cd.categories_name, ptc.categories_id FROM " . $oostable['products'] . " p LEFT JOIN " . $oostable['products_description'] . " pd ON pd.products_id=p.products_id LEFT JOIN " . $oostable['products_to_categories'] . " ptc ON ptc.products_id=p.products_id LEFT JOIN " . $oostable['categories_description'] . " cd ON cd.categories_id=ptc.categories_id ORDER BY categories_id");
    while ($row = $result->fields) {
        extract($row, EXTR_PREFIX_ALL, "db");
        $ProductList[$db_categories_id][$db_products_id] = $db_products_name;
        $CategoryList[$db_categories_id] = $db_categories_name;
        $LastCategory = $db_categories_name;
        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    // ksort($ProductList);

    $LastOptionTag = "";
    $ProductSelectOptions = "<option value='0'>Don't Add New Product" . $LastOptionTag . "\n";
    $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
    foreach ($ProductList as $Category => $Products) {
        $ProductSelectOptions .= "<option value='0'>$Category" . $LastOptionTag . "\n";
        $ProductSelectOptions .= "<option value='0'>---------------------------" . $LastOptionTag . "\n";
        asort($Products);
        foreach ($Products as $Product_ID => $Product_Name) {
            $ProductSelectOptions .= "<option value='$Product_ID'> &nbsp; $Product_Name" . $LastOptionTag . "\n";
        }

        if ($Category != $LastCategory) {
            $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
            $ProductSelectOptions .= "<option value='0'>&nbsp;" . $LastOptionTag . "\n";
        }
    }

    echo "<tr><td><table border='0'>\n";

    // Set Defaults
    if (!isset($add_product_categories_id)) {
        $add_product_categories_id = 0;
    }

    if (!isset($add_product_products_id)) {
        $add_product_products_id = 0;
    }

    // Step 1: Choose Category
    echo '<tr class="dataTableRow"><form action="'.$php_self.'?oID='.$oID.'&action='.$action.'" method="POST">'."\n";
    echo '<td class="dataTableContent" align="right"><b>STEP 1:</b></td><td class="dataTableContent" valign="top">';
    echo ' ' . oos_draw_pull_down_menu('add_product_categories_id', '', oos_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"');
    echo '<input type="hidden" name="step" value="2">';
    echo '</td>' . "\n";
    echo '</form></tr>' . "\n";
    echo '<tr><td colspan="3">&nbsp;</td></tr>' . "\n";

    // Step 2: Choose Product
    if (($step > 1) && ($add_product_categories_id > 0)) {
        echo '<tr class="dataTableRow"><form action="'.$php_self.'?oID='.$oID.'&action='.$action.'" method="POST">' . "\n";
        echo '<td class="dataTableContent" align="right"><b>STEP 2:</b></td><td class="dataTableContent" valign="top"><select name="add_product_products_id" onChange="this.form.submit();">';
        $ProductOptions = '<option value="0">' .  ADDPRODUCT_TEXT_SELECT_PRODUCT . "\n";
        asort($ProductList[$add_product_categories_id]);
        foreach ($ProductList[$add_product_categories_id] as $ProductID => $ProductName) {
            $ProductOptions .= '<option value="'.$ProductID.'"> '.$ProductName."\n";
        }
        $ProductOptions = str_replace('value="'.$add_product_products_id.'"', 'value="'.$add_product_products_id.'" selected', $ProductOptions);
        echo $ProductOptions;
        echo '</select></td>'."\n";
        echo '<input type="hidden" name="add_product_categories_id" value="'.$add_product_categories_id.'">';
        echo '<input type="hidden" name="step" value="3">';
        echo '</form></tr>'."\n";
        echo '<tr><td colspan="3">&nbsp;</td></tr>'."\n";
    }

    // Step 3: Choose Options
    if (($step > 2) && ($add_product_products_id > 0)) {
        // Get Options for Products
        $result = $dbconn->Execute("SELECT * FROM " . $oostable['products_attributes'] . " pa LEFT JOIN " . $oostable['products_options'] . " po ON po.products_options_id=pa.options_id LEFT JOIN " . $oostable['products_options_values'] . " pov ON pov.products_options_values_id=pa.options_values_id WHERE products_id = '" . $add_product_products_id . "'");

        // Skip to Step 4 if no Options
        if ($result->RecordCount() == 0) {
            echo '<tr class="dataTableRow">' . "\n";
            echo '<td class="dataTableContent" align="right"><b>STEP 3:</b></td><td class="dataTableContent" valign="top" colspan="2"><i>No Options - Skipped...</i></td>';
            echo '</tr>'."\n";
            $step = 4;
        } else {
            while ($row = $result->fields) {
                extract($row, EXTR_PREFIX_ALL, "db");
                $Options[$db_products_options_id] = $db_products_options_name;
                $ProductOptionValues[$db_products_options_id][$db_products_options_values_id] = $db_products_options_values_name;

                // Move that ADOdb pointer!
                $result->MoveNext();
            }

            echo '<tr class="dataTableRow"><form action="'.$php_self.'?oID='.$oID.'&action='.$action.'" method="POST">'."\n";
            echo '<td class="dataTableContent" align="right"><b>STEP 3:</b></td><td class="dataTableContent" valign="top">';
            foreach ($ProductOptionValues as $OptionID => $OptionValues) {
                $OptionOption = '<b>' . $Options[$OptionID] . '</b> - <select name="add_product_options['.$OptionID.']">';
                foreach ($OptionValues as $OptionValueID => $OptionValueName) {
                    $OptionOption .= '<option value="'.$OptionValueID.'"> '.$OptionValueName."\n";
                }
                $OptionOption .= '</select><br>'."\n";

                if (isset($add_product_options)) {
                    $OptionOption = str_replace('value="' . $add_product_options[$OptionID] . '"', 'value="' . $add_product_options[$OptionID] . '" selected', $OptionOption);
                }

                echo $OptionOption;
            }
            echo '</td>';
            echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_OPTIONS_CONFIRM . '">';
            echo '<input type="hidden" name="add_product_categories_id" value="'.$add_product_categories_id.'">';
            echo '<input type="hidden" name="add_product_products_id" value="'.$add_product_products_id.'">';
            echo '<input type="hidden" name="step" value="4">';
            echo '</td>'."\n";
            echo '</form></tr>'."\n";
        }

        echo '<tr><td colspan="3">&nbsp;</td></tr>'."\n";
    }

    // Step 4: Confirm
    if ($step > 3) {
        echo '<tr class="dataTableRow"><form action="'.$php_self.'?oID='.$oID.'&action='.$action.'" method="POST">'."\n";
        echo '<td class="dataTableContent" align="right"><b>STEP 4:</b></td>';
        echo '<td class="dataTableContent" valign="top"><input name="add_product_quantity" size="2" value="1">' . ADDPRODUCT_TEXT_CONFIRM_QUANTITY . '</td>';
        echo '<td class="dataTableContent" align="center"><input type="submit" value="' . ADDPRODUCT_TEXT_CONFIRM_ADDNOW . '">';

        if (isset($add_product_options)) {
            foreach ($add_product_options as $option_id => $option_value_id) {
                echo '<input type="hidden" name="add_product_options['.$option_id.']" value="'.$option_value_id.'">';
            }
        }
        echo '<input type="hidden" name="add_product_categories_id" value="'.$add_product_categories_id.'">';
        echo '<input type="hidden" name="add_product_products_id" value="'.$add_product_products_id.'">';
        echo '<input type="hidden" name="step" value="5">';
        echo '</td>'."\n";
        echo '</form></tr>'."\n";
    }
    echo '</table></td></tr>'."\n";
}
?>
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
    require 'includes/nice_exit.php';
?>