<?php
/*
 $Id: recover_cart_sales.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

 Recover Cart Sales Tool v2.11

 Copyright (c) 2003-2005 JM Ivler / Ideas From the Deep / OSCommerce
 Released under the GNU General Public License

 Based on an original release of unsold carts by: JM Ivler

 That was modifed by Aalst (aalst@aalst.com) until v1.7 of stats_unsold_carts.php

 Then, the report was turned into a sales tool (recover_cart_sales.php) by
 JM Ivler based on the scart.php program that was written off the Oct 8 unsold carts code release.

 Modifed by Aalst (recover_cart_sales.php,v 1.2 ... 1.36)
 aalst@aalst.com

 Modifed by willross (recover_cart_sales.php,v 1.4)
 reply@qwest.net
 - don't forget to flush the 'scart' db table every so often

 Modified by Lane Roathe (recover_cart_sales.php,v 1.4d .. v2.11)
 lane@ifd.com www.osc-modsquad.com / www.ifd.com

 Optimized for use with OOS by Vexoid (vexoid@gmail.com)

   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');

  require 'includes/oos_main.php';
  require 'includes/classes/class_currencies.php';


  function seadate($day) {
    $rawtime = strtotime("-" . $day . " days");
    $ndate = date("Ymd", $rawtime);
    return $ndate;
  }

  function cart_date_short($raw_date) {
    if ( ($raw_date == '00000000') || (empty($raw_date)) ) return false;

    $year = substr($raw_date, 0, 4);
    $month = (int)substr($raw_date, 4, 2);
    $day = (int)substr($raw_date, 6, 2);

    if (@date('Y', mktime(0, 0, 0, $month, $day, $year)) == $year) {
      return date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, $year));
    } else {
      return ereg_replace('2037' . '$', $year, date(DATE_FORMAT, mktime(0, 0, 0, $month, $day, 2037)));
    }

  }


 /**
  * This will return a list of customers with sessions. Handles
  * either the mysql or file ase
  *
  * Returns an empty array if the check sessions flag is not true 
  * (empty array means same SQL statement can be used) !!!!!
  *
  *
  * @return string
  */
  function _GetCustomerSessions() {

    $cust_ses_ids = 0;

    if (RCS_CHECK_SESSIONS == 'true') {
      $dbconn =& oosDBGetConn();
      $oostable =& oosDBGetTables();

      // remove entries that have expired
      $xx_mins_ago = (time() - 900);
      $whos_onlinetable = $oostable['whos_online'];
      $dbconn->Execute("DELETE FROM $whos_onlinetable WHERE time_last_click < '" . $xx_mins_ago . "'");

      $whos_onlinetable = $oostable['whos_online'];
      $sql = "SELECT customer_id FROM $whos_onlinetable";
      $whos_online_result = $dbconn->Execute($sql);
      while ($whos_online = $whos_online_result->fields) {
        $cust_ses_ids .= ', ' . $whos_online['customer_id'];

         // Move that ADOdb pointer!
        $whos_online_result->MoveNext();
      }

     # $cust_ses_ids = substr($cust_ses_ids, 2);
    }

    return $cust_ses_ids;
  }


  $currencies = new currencies();

  // Delete Entry Begin
  if ($_GET['action'] == 'delete') {
    $customers_baskettable = $oostable['customers_basket'];
    $sql = "DELETE FROM $customers_baskettable WHERE customers_id = '" . (int)$_GET['customer_id'] . "'";
    $dbconn->Execute($sql);

    $customers_basket_attributestable = $oostable['customers_basket_attributes'];
    $sql = "DELETE FROM $customers_basket_attributestable WHERE customers_id = '" . (int)$_GET['customer_id'] . "'";
    $dbconn->Execute($sql);

    oos_redirect_admin(oos_href_link_admin($aFilename['recover_cart_sales'], 'delete=1&customer_id=' . (int)$_GET['customer_id'] . '&tdate=' . $_GET['tdate']));
  }

  if ($_GET['delete']) {
    $messageStack->add(MESSAGE_STACK_CUSTOMER_ID . (int)$_GET['customer_id'] . MESSAGE_STACK_DELETE_SUCCESS, 'success'); 
  }
  // Delete Entry End

  $tdate = ($_POST['tdate']?$_POST['tdate']:RCS_BASE_DAYS);

  require 'includes/oos_header.php';
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
      <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require('includes/oos_blocks.php'); ?>
      </table></td>
<!-- body_text //-->
    <td width="100%" valign="top">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if (is_array($_REQUEST['custid']) && !empty($_REQUEST['custid'])) {
?>
            <tr>
              <td class="pageHeading" align="left" colspan=2 width="50%"><? echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="left" colspan=4 width="50%"><? echo HEADING_EMAIL_SENT; ?></td>
            </tr>
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap><?php echo TABLE_HEADING_CUSTOMER; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap>&nbsp;</td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="25%" nowrap>&nbsp;</td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="10%" nowrap>&nbsp;</td>
            </tr><tr>&nbsp;<br /></tr>
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left"   colspan="1"  width="15%" nowrap><?php echo TABLE_HEADING_MODEL; ?></td>
              <td class="dataTableHeadingContent" align="left"   colspan="2"  width="55%" nowrap><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
              <td class="dataTableHeadingContent" align="center" colspan="1"  width="10%" nowrap> <?php echo TABLE_HEADING_QUANTY; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1"  width="10%" nowrap><?php echo TABLE_HEADING_PRICE; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1"  width="10%" nowrap><?php echo TABLE_HEADING_TOTAL; ?></td>
            </tr>

<?php
    foreach ($_REQUEST['custid'] as $cid) {
      $customerstable = $oostable['customers'];
      $customers_baskettable = $oostable['customers_basket'];
      $sql = "SELECT cb.products_id AS pid, cb.customers_basket_quantity AS qty, cb.customers_basket_date_added AS bdate,
                       c.customers_firstname AS fname, c.customers_lastname AS lname, c.customers_email_address AS email
                  FROM $customers_baskettable cb,
                       $customerstable c
                 WHERE cb.customers_id = c.customers_id
                   AND c.customers_id = '" . (int)$cid . "'
              ORDER BY cb.customers_basket_date_added desc";
      $result = $dbconn->Execute($sql);

      for ($i = 0, $knt = $result->RecordCount(); $i < $knt; $i++) {
        $inrec = $result->fields;
           // set new cline and curcus
        if ($lastcid != $cid) {
          if (!empty($lastcid)) {
            $cline .= '
              <tr>
                <td class="dataTableContent" align="right" colspan="6" nowrap><b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice) . '</td>
              </tr>
              <tr>
                <td colspan="6" align="right"><a href=' . oos_href_link_admin($aFilename['recover_cart_sales'], 'action=delete&customer_id=' . $cid . '&tdate=' . $tdate) . '>' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a></td>
              </tr>' . "\n";
            echo $cline;
          }
          $cline = '<tr> <td class="dataTableContent" align="left" colspan="6" nowrap><a href="' . oos_href_link_admin($aFilename['customers'], 'search=' . $inrec['lname'], 'NONSSL') . '">' . $inrec['fname'] . ' ' . $inrec['lname'] . '</a>' . $customer . '</td></tr>';
          $tprice = 0;
        }
        $lastcid = $cid;

        // get the shopping cart
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql2 = "SELECT p.products_price AS price, p.products_model AS model, pd.products_name AS name
                   FROM $productstable p,
                        $products_descriptiontable pd
                  WHERE p.products_id = '" . $inrec['pid'] . "'
                    AND pd.products_id = p.products_id
                    AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'";
        $result2 = $dbconn->Execute($sql2);
        $inrec2 = $result2->fields;

        $sprice = oos_get_products_special_price($inrec['pid']);
        if ($sprice < 1) {
          $sprice = $inrec2['price'];
        }

        $tprice = $tprice + ($inrec['qty'] * $sprice);
        $pprice_formated  = $currencies->format($sprice);
        $tpprice_formated = $currencies->format(($inrec['qty'] * $sprice));

        $cline .= '<tr class="dataTableRow">
                  <td class="dataTableContent" align="left"   width="15%" nowrap>' . $inrec2['model'] . '</td>
                  <td class="dataTableContent" align="left"  colspan="2" width="55%"><a href="' . oos_href_link_admin($aFilename['products'], 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . $aFilename['recover_cart_sales'] . '?page=' . (int)$_GET['page'], 'NONSSL') . '">' . $inrec2['name'] . '</a></td>
                  <td class="dataTableContent" align="center" width="10%" nowrap>' . $inrec['qty'] . '</td>
                  <td class="dataTableContent" align="right"  width="10%" nowrap>' . $pprice_formated . '</td>
                  <td class="dataTableContent" align="right"  width="10%" nowrap>' . $tpprice_formated . '</td>
               </tr>';

        $mline .= $inrec['qty'] . ' x ' . $inrec2['name'] . "\n";

        if (EMAIL_USE_HTML == 'true') {
          $mline .= '   <blockquote><a href="' . oos_catalog_link($oosModules['products'], $oosCatalogFilename['product_info'], 'products_id='. $inrec['pid']) . '">' . oos_catalog_link($oosModules['products'], $oosCatalogFilename['product_info'], 'products_id='. $inrec['pid']) . "</a></blockquote>\n\n";
        } else {
          $mline .= '   (' . oos_catalog_link($oosModules['products'], $oosCatalogFilename['product_info'], 'products_id='. $inrec['pid']).")\n\n";
        }

        // Move that ADOdb pointer!
        $result->MoveNext();
      }

      $cline .= '</td></tr>';

      // E-mail Processing - Requires EMAIL_* defines in the
      // includes/languages/english/recover_cart_sales.php file
      $orderstable = $oostable['orders'];
      $sql3 = "SELECT orders_id FROM $orderstable WHERE customers_id = '" . (int)$cid . "'";
      $result3 = $dbconn->Execute($sql3);

      $email = EMAIL_TEXT_LOGIN;

      if (EMAIL_USE_HTML == 'true') {
        $email .= '  <a href="' . oos_catalog_link($oosModules['user'], $oosCatalogFilename['user_login'], '', 'SSL') . '">' . oos_catalog_link($oosModules['user'], $oosCatalogFilename['user_login'], '', 'SSL')  . '</a>';
      } else {
        $email .= '  (' . oos_catalog_link($oosModules['user'], $oosCatalogFilename['user_login'], '', 'SSL') . ')';
      }

      $email .= "\n" . EMAIL_SEPARATOR . "\n\n";

      if (RCS_EMAIL_FRIENDLY == 'true'){
        $email .= EMAIL_TEXT_SALUTATION . $inrec['fname'] . ',';
      } else {
        $email .= STORE_NAME . "\n" . EMAIL_SEPARATOR . "\n";
      }

      if ($result3->RecordCount() < 1) {
        $email .= sprintf(EMAIL_TEXT_NEWCUST_INTRO, $mline);
      } else {
        $email .= sprintf(EMAIL_TEXT_CURCUST_INTRO, $mline);
      }

      $email .= EMAIL_TEXT_BODY_HEADER . $mline . EMAIL_TEXT_BODY_FOOTER;

      if (EMAIL_USE_HTML == 'true') {
        $email .= '<a href="' . oos_catalog_link($oosModules['main'], $oosCatalogFilename['default']) . '">' . STORE_OWNER . "\n" . OOS_HTTP_SERVER . OOS_SHOP . '</a>';
      } else {
        $email .= STORE_OWNER . "\n" . OOS_HTTP_SERVER . OOS_SHOP;
      }

      $email .= "\n\n" . $_POST['message'];
      $custname = $inrec['fname'] . ' ' . $inrec['lname'];

      $outEmailAddr = '"' . $custname . '" <' . $inrec['email'] . '>';
      if (oos_is_not_null(RCS_EMAIL_COPIES_TO)) {
        $outEmailAddr .= ', ' . RCS_EMAIL_COPIES_TO;
      }

      oos_mail($custname, $outEmailAddr, EMAIL_TEXT_SUBJECT, $email, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

      $mline = '';

      // See if a record for this customer already exists; if not create one and if so update it
      $recovercartsalestable = $oostable['recovercartsales'];
      $done_result = $dbconn->Execute("SELECT customers_id FROM $recovercartsalestable WHERE customers_id = '" . $cid . "'");
      if ($done_result->RecordCount() == 0) {
        $recovercartsalestable = $oostable['recovercartsales'];
        $dbconn->Execute("INSERT INTO $recovercartsalestable (customers_id, recovercartsales_date_added, recovercartsales_date_modified ) VALUES ('" . $cid . "', '" . seadate('0') . "', '" . seadate('0') . "')");
     } else {
        $recovercartsalestable = $oostable['recovercartsales'];
        $dbconn->Execute("UPDATE $recovercartsalestable SET recovercartsales_date_modified = '" . seadate('0') . "' WHERE customers_id = '" . $cid . "'");
      }

      echo $cline;
      $cline = '';
    }

    echo '<tr><td colspan="8" align="right" class="dataTableContent"><b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice) . "</td></tr>";
    echo '<tr><td colspan="6" align="right"><a href="' . oos_href_link_admin($aFilename['recover_cart_sales'], 'action=delete&customer_id=' . $cid . '&tdate=' . $tdate) . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a></td></tr>' . "\n";
    echo '<tr><td colspan="6" align="center"><a href="' . $_SERVER['PHP_SELF'] . '">' . TEXT_RETURN . '</a></td></tr>';
  } else {
//
//we are NOT doing an e-mail to some customers
?>
        <!-- REPORT TABLE BEGIN //-->
            <tr>
              <td class="pageHeading" align="left" width="50%" colspan="4"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right" width="50%" colspan="4">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                  <table align="right" width="100%">
                    <tr class="dataTableContent" align="right">
                      <td><?php echo DAYS_FIELD_PREFIX; ?><input type="text" size="4" width="4" value="<?php echo $tdate; ?>" name="tdate"><?php echo DAYS_FIELD_POSTFIX; ?><input type="submit" value="<?php echo DAYS_FIELD_BUTTON; ?>"></td>
                    </tr>
                  </table>
                </form>
              </td>
            </tr>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left" colspan="2" width="10%" nowrap><?php echo TABLE_HEADING_CONTACT; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="15%" nowrap><?php echo TABLE_HEADING_DATE; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="1" width="30%" nowrap><?php echo TABLE_HEADING_CUSTOMER; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="2" width="30%" nowrap><?php echo TABLE_HEADING_EMAIL; ?></td>
              <td class="dataTableHeadingContent" align="left" colspan="2" width="15%" nowrap><?php echo TABLE_HEADING_PHONE; ?></td>
            </tr><tr>&nbsp;<br /></tr>
            <tr class="dataTableHeadingRow">
              <td class="dataTableHeadingContent" align="left"   colspan="2"  width="10%" nowrap>&nbsp; </td>
              <td class="dataTableHeadingContent" align="left"   colspan="1"  width="15%" nowrap><?php echo TABLE_HEADING_MODEL; ?></td>
              <td class="dataTableHeadingContent" align="left"   colspan="2" width="55%" nowrap><?php echo TABLE_HEADING_DESCRIPTION; ?></td>
              <td class="dataTableHeadingContent" align="center" colspan="1" width="5%" nowrap> <?php echo TABLE_HEADING_QUANTY; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1"  width="5%" nowrap><?php echo TABLE_HEADING_PRICE; ?></td>
              <td class="dataTableHeadingContent" align="right"  colspan="1" width="10%" nowrap><?php echo TABLE_HEADING_TOTAL; ?></td>
            </tr>

<?php
    $cust_ses_ids = _GetCustomerSessions();
    $ndate = seadate($tdate);

    $customers_baskettable = $oostable['customers_basket'];
    $customerstable = $oostable['customers'];
    $sql = "SELECT cb.customers_id AS cid, cb.products_id AS pid, cb.customers_basket_quantity AS qty,
                   cb.customers_basket_date_added AS bdate, c.customers_firstname AS fname, c.customers_lastname AS lname,
                   c.customers_telephone AS phone, c.customers_email_address AS email
              FROM $customers_baskettable cb,
                   $customerstable c
             WHERE cb.customers_basket_date_added >= '" . $ndate . "'
               AND c.customers_id NOT IN (" . $cust_ses_ids . ")
               AND cb.customers_id = c.customers_id
          ORDER BY cb.customers_basket_date_added desc,
                   cb.customers_id";
    $result = $dbconn->Execute($sql);

    $results = 0;
    $curcus = '';
    $tprice = 0;
    $totalAll = 0;
    $first_line = true;
    $skip = false;

    while ($inrec = $result->fields) {

      // If this is a new customer, create the appropriate HTML
      if ($curcus != $inrec['cid']) {
        // output line
        $totalAll += $tprice;
        $cline .= '
                          <tr>
                            <td class="dataTableContent" align="right" colspan="8"><b>' . TABLE_CART_TOTAL . '</b>' . $currencies->format($tprice) . '</td>
                          </tr>
                          <tr>
                            <td colspan="6" align="right"><a href="' . oos_href_link_admin($aFilename['recover_cart_sales'], 'action=delete&customer_id=' . $curcus . '&tdate=' . $tdate) . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a></td>
                          </tr>' . "\n";

        if (!empty($curcus) && !$skip) {
          echo $cline;
        }

        // set new cline and curcus
        $curcus = $inrec['cid'];
        if (!empty($curcus)) {
          $tprice = 0;


          // change the color on those we have contacted add customer tag to customers
          $fcolor = RCS_UNCONTACTED_COLOR;
          $checked = 1; // assume we'll send an email
          $new = 1;
          $skip = false;
          $sentdate = '';
          $beforeDate = RCS_CARTS_MATCH_ALL_DATES ? '0' : $inrect['bdate'];
          $customer = $inrec['fname'] . ' ' . $inrec['lname'];
          $status = '';

          $recovercartsalestable = $oostable['recovercartsales'];
          $sql2 = "SELECT recovercartsales_date_added, recovercartsales_date_modified FROM $recovercartsalestable WHERE customers_id = '" . (int)$curcus . "'";
          $result2 = $dbconn->Execute($sql2);
          $emailttl = seadate(RCS_EMAIL_TTL);

          if ($result2->RecordCount() > 0) {
            $ttl = $result2->fields;
            if (isset($ttl) && !empty($ttl)) {
              if (oos_is_not_null($ttl['recovercartsales_date_modified'])) { // allow for older scarts that have no datemodified
                $ttldate = $ttl['recovercartsales_date_modified'];
              } else {
                $ttldate = $ttl['recovercartsales_date_added'];
              }
              if ($emailttl <= $ttldate) {
                $sentdate = $ttldate;
                $fcolor = RCS_CONTACTED_COLOR;
                $checked = 0;
                $new = 0;
              }
            }
          }

          // See if the customer has purchased from us before
          // Customers are identified by either their customer ID or name or email address
          // If the customer has an order with items that match the current order, assume order completed, bail on this entry!
          $orderstable = $oostable['orders'];
          $sql3 = "SELECT orders_id, orders_status FROM $orderstable WHERE (customers_id = " . (int)$curcus . " OR customers_email_address LIKE '" . $inrec['email'] . "' OR customers_name LIKE '" . $inrec['fname'] . ' ' . $inrec['lname'] . "') AND date_purchased >= '" . $beforeDate . "'";
          $result3 = $dbconn->Execute($sql3);

          if ($result3->RecordCount() > 0) {
            // We have a matching order; assume current customer but not for this order
            $customer = '<font color=' . RCS_CURCUST_COLOR . '><b>' . $customer . '</b></font>';

            // Now, look to see if one of the orders matches this current order's items
            while($orec = $result3->fields) {
              $orders_productstable = $oostable['orders_products'];
              $sql4 = "SELECT products_id FROM $orders_productstable WHERE orders_id = '" . (int)$orec['orders_id'] . "' AND products_id = '" . (int)$inrec['pid'] . "'";
              $result4 = $dbconn->Execute($sql4);
              if ($result4->RecordCount() > 0) {
                if ($orec['orders_status'] > RCS_PENDING_SALE_STATUS) {
                  $checked = 0;
                }
                // OK, we have a matching order; see if we should just skip this or show the status
                if (RCS_SKIP_MATCHED_CARTS == 'true' && !$checked) {
                  $skip = true; // reset flag & break us out of the while loop!
                  break;
                } else {
                  // It's rare for the same customer to order the same item twice, so we probably have a matching order, show it
                  $fcolor = RCS_MATCHED_ORDER_COLOR;
                  $orders_statustable = $oostable['orders_status'];
                  $sql5 = "SELECT orders_status_name FROM $orders_statustable WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "' AND orders_status_id = '" . (int)$orec['orders_status'] . "'";
                  $result5 = $dbconn->Execute($sql5);

                  if ($srec = $result5->fields) {
                    $status = ' [' . $srec['orders_status_name'] . ']';
                  } else {
                    $status = ' ['. TEXT_CURRENT_CUSTOMER . ']';
                  }
                }
              }

              // Move that ADOdb pointer!
              $result3->MoveNext();
            }
          }


          $sentInfo = TEXT_NOT_CONTACTED;

          if (!empty($sentdate)) {
            $sentInfo = cart_date_short($sentdate);
          }

          $cline = '
          <tr bgcolor="' . $fcolor . '">
            <td class="dataTableContent" align="center" width="1%">' . oos_draw_checkbox_field('custid[]', $curcus, RCS_AUTO_CHECK == 'true' ? $checked : 0) . '</td>
            <td class="dataTableContent" align="left" width="9%" nowrap><b>' . $sentInfo . '</b></td>
            <td class="dataTableContent" align="left" width="15%" nowrap>' . cart_date_short($inrec['bdate']) . '</td>
            <td class="dataTableContent" align="left" width="30%" nowrap><a href="' . oos_href_link_admin($aFilename['customers'], 'search=' . $inrec['lname'], 'NONSSL') . '">' . $customer . '</a>' . $status . '</td>
            <td class="dataTableContent" align="left" colspan="2" width="30%" nowrap><a href="' . oos_href_link_admin($aFilename['mail'], 'selected_box=tools&customer=' . $inrec['email']) . '">' . $inrec['email'] . '</a></td>
            <td class="dataTableContent" align="left" colspan="2" width="15%" nowrap>' . $inrec['phone'] . '</td>
          </tr>';
          echo $cline;
        }
      }

      // We only have something to do for the product if the quantity selected was not zero!
      if ($inrec['qty'] != 0) {
        // Get the product information (name, price, etc)
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql6 = "SELECT p.products_price AS price,
                        p.products_model AS model,
                        pd.products_name AS name
                   FROM $productstable p,
                        $products_descriptiontable pd
                  WHERE p.products_id = '" . (int)$inrec['pid'] . "' AND
                        pd.products_id = p.products_id AND
                        pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'";
        $result6 = $dbconn->Execute($sql6);

        $inrec6 = $result6->fields;

        // Check to see if the product is on special, and if so use that pricing
        $sprice = oos_get_products_special_price($inrec['pid']);
        if ($sprice < 1) {
          $sprice = $inrec6['price'];
        }

        // BEGIN OF ATTRIBUTE DB CODE
        $prodAttribs = ''; // DO NOT DELETE

        if (RCS_SHOW_ATTRIBUTES == 'true') {
          $customers_basket_attributestable = $oostable['customers_basket_attributes'];
          $products_optionstable = $oostable['products_options'];
          $products_options_valuestable = $oostable['products_options_values'];
          $attrib_sql = "SELECT cba.products_id AS pid,
                                po.products_options_name AS poname,
                                pov.products_options_values_name AS povname
                           FROM $customers_basket_attributestable cba,
                                $products_optionstable po,
                                $products_options_valuestable pov
                          WHERE cba.products_id = '" . $inrec['pid'] . "' AND
                                cba.customers_id = " . $curcus . " AND
                                po.products_options_id = cba.products_options_id AND
                                pov.products_options_values_id = cba.products_options_value_id AND
                                po.products_options_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                pov.products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "'";
          $attrib_result = $dbconn->Execute($attrib_sql);
          $hasAttributes = false;

          if ($attrib_result->RecordCount() > 0) {
            $hasAttributes = true;
            $prodAttribs = '<br />';

            while ($attribrecs = $attrib_result->fields) {
              $prodAttribs .= '<small><i> - ' . $attribrecs['poname'] . ' ' . $attribrecs['povname'] . '</i></small><br />';

              // Move that ADOdb pointer!
              $attrib_result->MoveNext();
            }
          }
        }
        // END OF ATTRIBUTE DB CODE

        $tprice = $tprice + ($inrec['qty'] * $sprice);
        $pprice_formated  = $currencies->format($sprice);
        $tpprice_formated = $currencies->format(($inrec['qty'] * $sprice));

        $cline = '<tr class="dataTableRow">
                      <td class="dataTableContent" align="left" vAlign="top" colspan="2" width="12%" nowrap> &nbsp;</td>
                      <td class="dataTableContent" align="left" vAlign="top" width="13%" nowrap>' . $inrec6['model'] . '</td>
                      <td class="dataTableContent" align="left" vAlign="top" colspan="2" width="55%"><a href="' . oos_href_link_admin($aFilename['products'], 'action=new_product_preview&read=only&pID=' . $inrec['pid'] . '&origin=' . $aFilename['recover_cart_sales'] . '?page=' . (int)$_GET['page'], 'NONSSL') . '"><b>' . $inrec6['name'] . '</b></a>
                      ' . $prodAttribs . '
                      </td>
                      <td class="dataTableContent" align="center" vAlign="top" width="5%" nowrap>' . $inrec['qty'] . '</td>
                      <td class="dataTableContent" align="right"  vAlign="top" width="5%" nowrap>' . $pprice_formated . '</td>
                      <td class="dataTableContent" align="right"  vAlign="top" width="10%" nowrap>' . $tpprice_formated . '</td>
                   </tr>';
        echo $cline;
      }

      $curcus = '';

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    $totalAll_formated = $currencies->format($totalAll);
    $cline = '<tr></tr><td class="dataTableContent" align="right" colspan="8"><hr align="right" width="55"><b>' . TABLE_GRAND_TOTAL . '</b>' . $totalAll_formated . '</td>
                </tr>';

    echo $cline;
    echo '<tr><td colspan="8"><hr size="1" color="#000080"><b>' . PSMSG . '</b><br />' . oos_draw_textarea_field('message', 'soft', '80', '5') . '<br />' . oos_draw_selection_field('submit_button', 'submit', TEXT_SEND_EMAIL) . '</td></tr>';
?>
</form>
<?php
  }
?>

      </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->


<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>