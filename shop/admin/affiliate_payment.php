<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_payment.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_payment.php,v 1.15 2003/02/21 14:33:36 simarilius
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $payments_statuses = array();
  $payments_status_array = array();
  $payments_status_result = $dbconn->Execute("SELECT affiliate_payment_status_id, affiliate_payment_status_name 
                                         FROM " . $oostable['affiliate_payment_status'] . " 
                                         WHERE affiliate_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($payments_status = $payments_status_result->fields) {
    $payments_statuses[] = array('id' => $payments_status['affiliate_payment_status_id'],
                                 'text' => $payments_status['affiliate_payment_status_name']);
    $payments_status_array[$payments_status['affiliate_payment_status_id']] = $payments_status['affiliate_payment_status_name'];

     // Move that ADOdb pointer!
    $payments_status_result->MoveNext();
  }

  // Close result set
  $payments_status_result->Close();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'start_billing':
        // Billing can be a lengthy process
        oos_set_time_limit(0);
        // We are only billing orders which are AFFILIATE_BILLING_TIME days old
        $time = mktime(1, 1, 1, date("m"), date("d") - AFFILIATE_BILLING_TIME, date("Y"));
        $oldday = date("Y-m-d", $time);
        // Select all affiliates who earned enough money since last payment
        $sql = "SELECT 
                   a.affiliate_id, sum(a.affiliate_payment) 
               FROM 
                   " . $oostable['affiliate_sales'] . " a, 
                   " . $oostable['orders'] . " o 
               WHERE 
                   a.affiliate_billing_status != 1 AND
                   a.affiliate_orders_id = o.orders_id AND
                   o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " AND
                   a.affiliate_date <= '" . $oldday . "' 
            GROUP BY 
                   a.affiliate_id 
                   having sum(a.affiliate_payment) >= '" . AFFILIATE_THRESHOLD . "'";
        $affiliate_payment_result = $dbconn->Execute($sql);

        // Start Billing:
        while ($affiliate_payment = $affiliate_payment_result->fields) {

       // mysql does not support joins in update (planned in 4.x)

       // Get all orders which are AFFILIATE_BILLING_TIME days old
          $sql = "SELECT 
                      a.affiliate_orders_id 
                  FROM 
                      " . $oostable['affiliate_sales'] . " a, 
                      " . $oostable['orders'] . " o 
                  WHERE 
                      a.affiliate_billing_status!=1 AND
                      a.affiliate_orders_id=o.orders_id AND
                      o.orders_status>=" . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . " AND
                      a.affiliate_id='" . $affiliate_payment['affiliate_id'] . "' AND
                      a.affiliate_date <= '" . $oldday . "'";
          $affiliate_orders_result = $dbconn->Execute ($sql);
          $orders_id ="(";
          while ($affiliate_orders = $affiliate_orders_result->fields) {
            $orders_id .= $affiliate_orders['affiliate_orders_id'] . ",";

            // Move that ADOdb pointer!
            $affiliate_orders_result->MoveNext();
          }
          $orders_id = substr($orders_id, 0, -1) .")";

          // Set the Sales to Temp State (it may happen that an order happend while billing)
          $sql="UPDATE " . $oostable['affiliate_sales'] . "
                SET affiliate_billing_status=99 
                WHERE affiliate_id='" .  $affiliate_payment['affiliate_id'] . "'
                AND affiliate_orders_id in " . $orders_id . " 
          ";
          $dbconn->Execute($sql);

          // Get Sum of payment (Could have changed since last selects);
          $sql = "SELECT 
                      sum(affiliate_payment) as affiliate_payment
                  FROM 
                      " . $oostable['affiliate_sales'] . " 
                  WHERE 
                      affiliate_id='" .  $affiliate_payment['affiliate_id'] . "' AND
                      affiliate_billing_status=99";
          $affiliate_billing_result = $dbconn->Execute($sql);
          $affiliate_billing = $affiliate_billing_result->fields;
          // Get affiliate Informations
          $sql = "SELECT 
                      a.*, c.countries_id, c.countries_name, c.countries_iso_code_2, c.countries_iso_code_3, 
                      c.address_format_id 
                  FROM 
                      " . $oostable['affiliate_affiliate'] . " a LEFT JOIN
                      " . $oostable['zones'] . " z ON
                      (a.affiliate_zone_id  = z.zone_id) LEFT JOIN
                      " . $oostable['countries'] . " c ON
                      (a.affiliate_country_id = c.countries_id)
                  WHERE 
                      affiliate_id = '" . $affiliate_payment['affiliate_id'] . "'";
          $affiliate_result = $dbconn->Execute($sql);
          $affiliate = $affiliate_result->fields;

          // Get need tax informations for the affiliate
          $affiliate_tax_rate = oos_get_affiliate_tax_rate(AFFILIATE_TAX_ID, $affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id']);
          $affiliate_tax = round(($affiliate_billing['affiliate_payment'] * $affiliate_tax_rate / 100), 2); // Netto-Provision
          $affiliate_payment_total = $affiliate_billing['affiliate_payment'] + $affiliate_tax;
          // Bill the order
          $affiliate['affiliate_state'] = oos_get_zone_code($affiliate['affiliate_country_id'], $affiliate['affiliate_zone_id'], $affiliate['affiliate_state']);
          $sql_data_array = array('affiliate_id' => $affiliate_payment['affiliate_id'],
                                  'affiliate_payment' => $affiliate_billing['affiliate_payment'],
                                  'affiliate_payment_tax' => $affiliate_tax,
                                  'affiliate_payment_total' => $affiliate_payment_total,
                                  'affiliate_payment_date' => 'now()',
                                  'affiliate_payment_status' => '0',
                                  'affiliate_firstname' => $affiliate['affiliate_firstname'],
                                  'affiliate_lastname' => $affiliate['affiliate_lastname'],
                                  'affiliate_street_address' => $affiliate['affiliate_street_address'],
                                  'affiliate_suburb' => $affiliate['affiliate_suburb'],
                                  'affiliate_city' => $affiliate['affiliate_city'],
                                  'affiliate_country' => $affiliate['countries_name'],
                                  'affiliate_postcode' => $affiliate['affiliate_postcode'],
                                  'affiliate_company' => $affiliate['affiliate_company'],
                                  'affiliate_state' => $affiliate['affiliate_state'],
                                  'affiliate_address_format_id' => $affiliate['address_format_id']);
          oos_db_perform($oostable['affiliate_payment'], $sql_data_array);
          $insert_id = $dbconn->Insert_ID();
          // Set the Sales to Final State 
          $dbconn->Execute("UPDATE " . $oostable['affiliate_sales'] . "
                      SET affiliate_payment_id = '" . $insert_id . "',
                          affiliate_billing_status = 1,
                          affiliate_payment_date = now()
                      WHERE affiliate_id = '" . $affiliate_payment['affiliate_id'] . "' AND
                            affiliate_billing_status = 99");

          // Notify Affiliate
          if (AFFILIATE_NOTIFY_AFTER_BILLING == 'true') {
            $check_status_result = $dbconn->Execute("SELECT
                                                    af.affiliate_email_address, ap.affiliate_lastname,
                                                    ap.affiliate_firstname, ap.affiliate_payment_status,
                                                    ap.affiliate_payment_date, ap.affiliate_payment_date
                                                FROM
                                                    " . $oostable['affiliate_payment'] . " ap,
                                                    " . $oostable['affiliate_affiliate'] . " af
                                                WHERE
                                                    affiliate_payment_id  = '" . $insert_id . "' AND
                                                    af.affiliate_id = ap.affiliate_id ");
            $check_status = $check_status_result->fields;
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $insert_id . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_payment'], 'payment_id=' . $insert_id, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . oos_date_long($check_status['affiliate_payment_date']) . "\n\n" . EMAIL_TEXT_NEW_PAYMENT;
            oos_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
          }

          // Move that ADOdb pointer!
          $affiliate_payment_result->MoveNext();
        }
        // Close result set
        $affiliate_payment_result->Close();

        $messageStack->add_session(SUCCESS_BILLING, 'success');

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'update_payment':
        $pID = oos_db_prepare_input($_GET['pID']);
        $status = oos_db_prepare_input($_POST['status']);

        $payment_updated = false;
        $check_status_result = $dbconn->Execute("SELECT
                                                af.affiliate_email_address, ap.affiliate_lastname, ap.affiliate_firstname,
                                                ap.affiliate_payment_status, ap.affiliate_payment_date, 
                                                ap.affiliate_payment_date
                                            FROM
                                                " . $oostable['affiliate_payment'] . " ap,
                                                " . $oostable['affiliate_affiliate'] . " af
                                            WHERE 
                                                affiliate_payment_id = '" . oos_db_input($pID) . "' AND
                                                af.affiliate_id = ap.affiliate_id ");
        $check_status = $check_status_result->fields;
        if ($check_status['affiliate_payment_status'] != $status) {
          $dbconn->Execute("UPDATE " . $oostable['affiliate_payment'] . " 
                      SET affiliate_payment_status = '" . oos_db_input($status) . "', 
                          affiliate_last_modified = now() 
                      WHERE affiliate_payment_id = '" . oos_db_input($pID) . "'");
          $affiliate_notified = '0';
          // Notify Affiliate
          if (isset($_POST['notify']) && ($_POST['notify'] == 'on')) {
            $email = STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n" . EMAIL_TEXT_AFFILIATE_PAYMENT_NUMBER . ' ' . $pID . "\n" . EMAIL_TEXT_INVOICE_URL . ' ' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_payment'], 'payment_id=' . $pID, 'SSL') . "\n" . EMAIL_TEXT_PAYMENT_BILLED . ' ' . oos_date_long($check_status['affiliate_payment_date']) . "\n\n" . sprintf(EMAIL_TEXT_STATUS_UPDATE, $payments_status_array[$status]);
            oos_mail($check_status['affiliate_firstname'] . ' ' . $check_status['affiliate_lastname'], $check_status['affiliate_email_address'], EMAIL_TEXT_SUBJECT, nl2br($email), STORE_OWNER, AFFILIATE_EMAIL_ADDRESS);
            $affiliate_notified = '1';
          }

          $dbconn->Execute("INSERT INTO " . $oostable['affiliate_payment_status_history'] . "
                      (affiliate_payment_id,
                       affiliate_new_value,
                       affiliate_old_value,
                       affiliate_date_added,
                       affiliate_notified)
                       VALUES ('" . oos_db_input($pID) . "', 
                               '" . oos_db_input($status) . "', 
                               '" . $check_status['affiliate_payment_status'] . "',
                               now(), 
                               '" . $affiliate_notified . "')");
          $order_updated = true;
        }

        if ($order_updated) {
          $messageStack->add_session(SUCCESS_PAYMENT_UPDATED, 'success');
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('action')) . 'action=edit'));
        break;
      case 'deleteconfirm':
        $pID = oos_db_prepare_input($_GET['pID']);

        $dbconn->Execute("DELETE FROM " . $oostable['affiliate_payment'] . " WHERE affiliate_payment_id = '" . oos_db_input($pID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['affiliate_payment_status_history'] . " WHERE affiliate_payment_id = '" . oos_db_input($pID) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action'))));
        break;
    }
  }
  if ( ($action == 'edit') && oos_is_not_null($_GET['pID']) ) {
    $pID = oos_db_prepare_input($_GET['pID']);
    $payments_result = $dbconn->Execute("SELECT 
                                        p.*,  a.affiliate_payment_check, a.affiliate_payment_paypal, 
                                        a.affiliate_payment_bank_name, a.affiliate_payment_bank_branch_number, 
                                        a.affiliate_payment_bank_swift_code, a.affiliate_payment_bank_account_name, 
                                        a.affiliate_payment_bank_account_number 
                                    FROM 
                                        " .  $oostable['affiliate_payment'] . " p, 
                                        " . $oostable['affiliate_affiliate'] . " a 
                                    WHERE 
                                        affiliate_payment_id = '" . oos_db_input($pID) . "' AND 
                                        a.affiliate_id = p.affiliate_id");
    $payments_exists = true;
    if (!$payments = $payments_result->fields) {
      $payments_exists = false;
      $messageStack->add(sprintf(ERROR_PAYMENT_DOES_NOT_EXIST, $pID), 'error');
    }
  }
  $no_js_general = true;
  require 'includes/oos_header.php'; 
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ( ($action == 'edit') && ($payments_exists) ) {
    $affiliate_address['firstname'] = $payments['affiliate_firstname'];
    $affiliate_address['lastname'] = $payments['affiliate_lastname'];
    $affiliate_address['street_address'] = $payments['affiliate_street_address'];
    $affiliate_address['suburb'] = $payments['affiliate_suburb'];
    $affiliate_address['city'] = $payments['affiliate_city'];
    $affiliate_address['state'] = $payments['affiliate_state'];
    $affiliate_address['country'] = $payments['affiliate_country'];
    $affiliate_address['postcode'] = $payments['affiliate_postcode'];
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('action'))) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo oos_draw_separator(); ?></td>
          </tr>
          <tr>
            <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_AFFILIATE; ?></b></td>
                <td class="main"><?php echo oos_address_format($payments['affiliate_address_format_id'], $affiliate_address, 1, '&nbsp;', '<br />'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_AFFILIATE_PAYMENT; ?></b></td>
                <td class="main">&nbsp;<?php echo $currencies->format($payments['affiliate_payment_total']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo TEXT_AFFILIATE_BILLED; ?></b></td>
                <td class="main">&nbsp;<?php echo oos_date_short($payments['affiliate_payment_date']); ?></td>
              </tr>
              <tr>
                <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
              </tr>
              <tr>
                <td class="main" valign="top"><b><?php echo TEXT_AFFILIATE_PAYING_POSSIBILITIES; ?></b></td>
                <td class="main"><table border="1" cellspacing="0" cellpadding="5">
                  <tr>
<?php
  if (AFFILIATE_USE_BANK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_BANK_TRANSFER . '</b><br /><br />' . TEXT_AFFILIATE_PAYMENT_BANK_NAME . ' ' . $payments['affiliate_payment_bank_name'] . '<br />' . TEXT_AFFILIATE_PAYMENT_BANK_BRANCH_NUMBER . ' ' . $payments['affiliate_payment_bank_branch_number'] . '<br />' . TEXT_AFFILIATE_PAYMENT_BANK_SWIFT_CODE . ' ' . $payments['affiliate_payment_bank_swift_code'] . '<br />' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NAME . ' ' . $payments['affiliate_payment_bank_account_name'] . '<br />' . TEXT_AFFILIATE_PAYMENT_BANK_ACCOUNT_NUMBER . ' ' . $payments['affiliate_payment_bank_account_number'] . '<br />'; ?></td>
<?php
  }
  if (AFFILIATE_USE_PAYPAL == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_PAYPAL . '</b><br /><br />' . TEXT_AFFILIATE_PAYMENT_PAYPAL_EMAIL . '<br />' . $payments['affiliate_payment_paypal'] . '<br />'; ?></td>
<?php
  }
  if (AFFILIATE_USE_CHECK == 'true') {
?>
                    <td class="main"  valign="top"><?php echo '<b>' . TEXT_AFFILIATE_PAYMENT_CHECK . '</b><br /><br />' . TEXT_AFFILIATE_PAYMENT_CHECK_PAYEE . '<br />' . $payments['affiliate_payment_check'] . '<br />'; ?></td>
<?php
  }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
<?php echo oos_draw_form('status', $aFilename['affiliate_payment'], oos_get_all_get_params(array('action')) . 'action=update_payment'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td><table border="0" cellspacing="0" cellpadding="2">
              <tr>
                <td class="main"><b><?php echo PAYMENT_STATUS; ?></b> <?php echo oos_draw_pull_down_menu('status', $payments_statuses, $payments['affiliate_payment_status']); ?></td>
              </tr>
              <tr>
                <td class="main"><b><?php echo PAYMENT_NOTIFY_AFFILIATE; ?></b><?php echo oos_draw_checkbox_field('notify', '', true); ?></td>
              </tr>
            </table></td>
            <td valign="top"><?php echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE); ?></td>
          </tr>
        </table></td>
      </form></tr>

      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><table border="1" cellspacing="0" cellpadding="5">
          <tr>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_NEW_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_OLD_VALUE; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_DATE_ADDED; ?></b></td>
            <td class="smallText" align="center"><b><?php echo TABLE_HEADING_AFFILIATE_NOTIFIED; ?></b></td>
          </tr>
<?php
    $affiliate_history_result = $dbconn->Execute("SELECT 
                                                 affiliate_new_value, affiliate_old_value, affiliate_date_added, 
                                                 affiliate_notified 
                                             FROM 
                                                 " . $oostable['affiliate_payment_status_history'] . " 
                                             WHERE 
                                                 affiliate_payment_id = '" . oos_db_input($pID) . "' 
                                             ORDER BY
                                                 affiliate_status_history_id DESC");
    if ($affiliate_history_result->RecordCount()) {
      while ($affiliate_history = $affiliate_history_result->fields) {
        echo '          <tr>' . "\n" .
             '            <td class="smallText">' . $payments_status_array[$affiliate_history['affiliate_new_value']] . '</td>' . "\n" .
             '            <td class="smallText">' . (oos_is_not_null($affiliate_history['affiliate_old_value']) ? $payments_status_array[$affiliate_history['affiliate_old_value']] : '&nbsp;') . '</td>' . "\n" .
             '            <td class="smallText" align="center">' . oos_date_short($affiliate_history['affiliate_date_added']) . '</td>' . "\n" .
             '            <td class="smallText" align="center">';
        if ($affiliate_history['affiliate_notified'] == '1') {
          echo oos_image(OOS_IMAGES . 'icons/tick.gif', ICON_TICK);
        } else {
          echo oos_image(OOS_IMAGES . 'icons/cross.gif', ICON_CROSS);
        }
        echo '          </tr>' . "\n";

        // Move that ADOdb pointer!
        $affiliate_history_result->MoveNext();
      }

      // Close result set
      $affiliate_history_result->Close();
    } else {
      echo '          <tr>' . "\n" .
           '            <td class="smallText" colspan="4">' . TEXT_NO_PAYMENT_HISTORY . '</td>' . "\n" .
           '          </tr>' . "\n";
    }
?>
        </table></td>
      </tr>
      <tr>
        <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_invoice'], 'pID=' . $_GET['pID']) . '" TARGET="_blank">' . oos_image_swap_button('invoice','invoice_off.gif', IMAGE_ORDERS_INVOICE) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('action'))) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_payment'], 'pID=' . $pInfo->affiliate_payment_id. '&action=start_billing' ) . '">' . oos_image_swap_button('affiliate_billing','affiliate_billing_off.gif', IMAGE_AFFILIATE_BILLING) . '</a>'; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo oos_draw_form('orders', $aFilename['affiliate_payment'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . oos_draw_input_field('sID', '', 'size="12"') . oos_draw_hidden_field('action', 'edit'); ?></td>
              </form></tr>
              <tr><?php echo oos_draw_form('status', $aFilename['affiliate_payment'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_STATUS . ' ' . oos_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_PAYMENTS)), $payments_statuses), '', 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AFILIATE_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_NET_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PAYMENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_DATE_BILLED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    if (isset($_GET['sID'])) {
      // Search only payment_id by now
      $sID = oos_db_prepare_input($_GET['sID']);
      $payments_result_raw = "SELECT p.* , s.affiliate_payment_status_name FROM " . $oostable['affiliate_payment'] . " p , " . $oostable['affiliate_payment_status'] . " s WHERE p.affiliate_payment_id = '" . oos_db_input($sID) . "' AND p.affiliate_payment_status = s.affiliate_payment_status_id AND s.affiliate_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY p.affiliate_payment_id DESC";
    } elseif (is_numeric($_GET['status'])) {
      $status = oos_db_prepare_input($_GET['status']);
      $payments_result_raw = "SELECT p.* , s.affiliate_payment_status_name FROM " . $oostable['affiliate_payment'] . " p , " . $oostable['affiliate_payment_status'] . " s WHERE s.affiliate_payment_status_id = '" . oos_db_input($status) . "' AND p.affiliate_payment_status = s.affiliate_payment_status_id AND s.affiliate_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY p.affiliate_payment_id DESC";
    } else {
      $payments_result_raw = "SELECT p.* , s.affiliate_payment_status_name FROM " . $oostable['affiliate_payment'] . " p , " . $oostable['affiliate_payment_status'] . " s WHERE p.affiliate_payment_status = s.affiliate_payment_status_id AND s.affiliate_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY p.affiliate_payment_id DESC";
    }
    $payments_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $payments_result_raw, $payments_result_numrows);
    $payments_result = $dbconn->Execute($payments_result_raw);
    while ($payments = $payments_result->fields) {
      if ((!isset($_GET['pID']) || (isset($_GET['pID']) && ($_GET['pID'] == $payments['affiliate_payment_id']))) && !isset($pInfo)) {
        $pInfo = new objectInfo($payments);
      }

      if (isset($pInfo) && is_object($pInfo) && ($payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $payments['affiliate_firstname'] . ' ' . $payments['affiliate_lastname']; ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['affiliate_payment'])); ?></td>
                <td class="dataTableContent" align="right"><?php echo $currencies->format(strip_tags($payments['affiliate_payment'] + $payments['affiliate_payment_tax'])); ?></td>
                <td class="dataTableContent" align="center"><?php echo oos_date_short($payments['affiliate_payment_date']); ?></td>
                <td class="dataTableContent" align="right"><?php echo $payments['affiliate_payment_status_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ( $payments['affiliate_payment_id'] == $pInfo->affiliate_payment_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID')) . 'pID=' . $payments['affiliate_payment_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $payments_result->MoveNext();
    }

    // Close result set
    $payments_result->Close();
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $payments_split->display_count($payments_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PAYMENTS); ?></td>
                    <td class="smallText" align="right"><?php echo $payments_split->display_links($payments_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'pID', 'action'))); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PAYMENT . '</b>');

      $contents = array('form' => oos_draw_form('payment', $aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id. '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO . '<br />');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin(AFFILIATE_PAYMENT, oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($pInfo) && is_object($pInfo)) {
        $heading[] = array('text' => '<b>[' . $pInfo->affiliate_payment_id . ']&nbsp;&nbsp;' . oos_datetime_short($pInfo->affiliate_payment_date) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate_payment'], oos_get_all_get_params(array('pID', 'action')) . 'pID=' . $pInfo->affiliate_payment_id  . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['affiliate_invoice'], 'pID=' . $pInfo->affiliate_payment_id ) . '" TARGET="_blank">' . oos_image_swap_button('invoice','invoice_off.gif', IMAGE_ORDERS_INVOICE) . '</a> ');
      }
      break;
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td  width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>