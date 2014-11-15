<?php
/*
  $Id: stats_recover_cart_sales.php,v 1.1 2007/06/08 17:14:42 r23 Exp $
  Recover Cart Sales Report v2.11

  Recover Cart Sales contribution: JM Ivler 11/20/03
  (c) Ivler / Ideas From the Deep / osCommerce
  
  Released under the GNU General Public License

 Modifed by Aalst (recover_cart_sales.php,v 1.2 .. 1.36)
 aalst@aalst.com

 Modified by Lane Roathe (recover_cart_sales.php,v 1.4d .. v2.11)
 lane@ifd.com www.osc-modsquad.com / www.ifd.com

 Optimized for use with OOS by Vexoid (vexoid@gmail.com)
*/

  define('OOS_VALID_MOD', 'yes');
  require('includes/oos_main.php');
  require('includes/classes/class_currencies.php');

  $currencies = new currencies();

  function oos_date_order_stat($raw_date) {
    if ($raw_date == '') return false;
    $year = substr($raw_date, 2, 2);
    $month = (int)substr($raw_date, 4, 2);
    $day = (int)substr($raw_date, 6, 2);
    return date(DATE_FORMAT, mktime('', '', '', $month, $day, $year));
  }

  function seadate($day) {
    $ts = date("U");
    $rawtime = strtotime("-".$day." days", $ts);
    $ndate = date("Ymd", $rawtime);
    return $ndate;
  }

  $no_js_general = true;
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
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
Working...
          <tr>
            <td colspan="6">
<!-- new header -->
              <table border="0" width="100%" cellspacing="0" cellpadding="2">
                <tr>
                  <td class="pageHeading" align="left"><?php echo HEADING_TITLE; ?></td>
                  <td class="pageHeading" align="right">
<?php
  $tdate = (!empty($_POST['tdate'])?$_POST['tdate']:RCS_REPORT_DAYS);
  $ndate = seadate($tdate);
?>

                    <form method=post action=<? echo $_SERVER['PHP_SELF'];?> >
                    <table align="right" width="100%">
                      <tr class="dataTableContent" align="right">
                        <td nowrap><?php echo DAYS_FIELD_PREFIX; ?><input type=text size=4 width=4 value=<? echo $tdate; ?> name=tdate><?php echo DAYS_FIELD_POSTFIX; ?><input type=submit value="<?php echo DAYS_FIELD_BUTTON; ?>"></td>
                      </tr>
                    </table>
                    </form>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
<?php
  // Init vars
  $custknt = 0;
  $total_recovered = 0;
  $custlist = '';

  // Query database for abandoned carts within our timeframe
  $recovercartsalestable = $oostable['recovercartsales'];
  $sql = "SELECT * FROM $recovercartsalestable WHERE recovercartsales_date_added >= '" . $ndate . "' ORDER BY recovercartsales_date_added desc";
  $result = $dbconn->Execute($sql);

  // Loop though each one and process it
  while ($inrec = $result->fields) {
    $cid = $inrec['customers_id'];

    $customerstable = $oostable['customers'];
    $sql1 = "SELECT c.customers_firstname, c.customers_lastname FROM $customerstable c WHERE c.customers_id = '" . $cid . "'";
    $result1 = $dbconn->Execute($sql1);
    $crec = $result1->fields;

    // Query DB for the FIRST order that matches this customer ID and came after the abandoned cart
    $orderstable = $oostable['orders'];
    $orders_totaltable = $oostable['orders_total'];
    $orders_statustable = $oostable['orders_status'];
    $orders_sql = "SELECT o.orders_id, o.customers_id, o.date_purchased,
                          s.orders_status_name, ot.text as order_total, ot.value
                     FROM $orderstable o
                LEFT JOIN $orders_totaltable ot ON (o.orders_id = ot.orders_id),
                          $orders_statustable s
                    WHERE (o.customers_id = '" . (int)$cid . "'
                       OR o.customers_email_address like '" . $crec['customers_email_address'] . "'
                       OR o.customers_name like '" . $crec['customers_firstname'] . ' ' . $crec['customers_lastname'] . "')
                      AND o.orders_status > " . RCS_PENDING_SALE_STATUS . "
                      AND o.orders_status = s.orders_status_id
                      AND o.date_purchased >= '" . $inrec['recovercartsales_date_added'] . "'
                      AND ot.class = 'ot_total'";
    $orders_result = $dbconn->Execute($orders_sql);
    $orders = $orders_result->fields;

    // If we got a match, create the table entry to display the information
    if (isset($orders) && !empty($orders)) {
      $custknt++;
      $total_recovered += $orders['value'];
      $custknt % 2 ? $class = RCS_REPORT_EVEN_STYLE : $class = RCS_REPORT_ODD_STYLE;
      $custlist .= '<tr class="' . $class . '">' .
                   '<td class="datatablecontent" align="right">' . $inrec['recovercartsales_id'] . '</td>' .
                   '<td>&nbsp;</td>' .
                   '<td class="datatablecontent" align="center">' . oos_date_order_stat($inrec['recovercartsales_date_added']) . '</td>' .
                   '<td>&nbsp;</td>' .
                   '<td class="datatablecontent"><a href="' . oos_href_link_admin($aContents['customers'], 'search=' . $crec['customers_lastname'], 'NONSSL') . '">' . $crec['customers_firstname'] . ' ' . $crec['customers_lastname'] . '</a></td>' .
                   '<td class="datatablecontent">' . oos_date_short($orders['date_purchased']) . '</td>' .
                   '<td class="datatablecontent" align="center">' . $orders['orders_status_name'] . '</td>' .
                   '<td class="datatablecontent" align="right">' . strip_tags($orders['order_total']) . '</td>' .
                   '<td>&nbsp;</td>'.
                   '</tr>';
    }

    // Move that ADOdb pointer!
    $result->MoveNext();
  }

  // Close result set
  $result->Close();

  $cline =  "<tr><td height=\"15\" COLSPAN=8> </td></tr>".
          "<tr>".
          "<td align=right COLSPAN=3 class=main><b>". TOTAL_RECORDS ."</b></td>".
          "<td>&nbsp;</td>".
          "<td align=left COLSPAN=5 class=main>". $rc_cnt ."</td>".
        "</tr>".
        "<tr>".
          "<td align=right COLSPAN=3 class=main><b>". TOTAL_SALES ."</b></td>".
          "<td>&nbsp;</td>".
          "<td align=left COLSPAN=5 class=main>". $custknt . TOTAL_SALES_EXPLANATION ." </td>".
        "</tr>".
        "<tr><td height=\"12\" COLSPAN=6> </td></tr>";
   echo $cline;
?>
       <tr class="dataTableHeadingRow"> <!-- Header -->
        <td width="7%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SCART_ID ?></td>
        <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
        <td width="10%" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SCART_DATE ?></td>
        <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
        <td width="50%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_CUSTOMER ?></td>
        <td width="10%" class="dataTableHeadingContent"><?php echo TABLE_HEADING_ORDER_DATE ?></td>
        <td width="10%" class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER_STATUS ?></td>
        <td width="10%" class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_AMOUNT ?></td>
        <td width="1%" class="dataTableHeadingContent">&nbsp;</td>
        </tr>
<?php
   echo $custlist;  // BODY: <tr> sections with recovered cart data
?>
    <tr>
        <td colspan=9 valign="bottom"><hr width="100%" size="1" color="#800000" noshade></td>
       </tr>
    <tr class="main">
      <td align="right" valign="center" colspan=4 class="main"><b><?php echo TOTAL_RECOVERED ?>&nbsp;</b></font></td>
      <td align=left colspan=3 class="main"><b><?php echo $rc_cnt ? round(($custknt / $rc_cnt) * 100, 2) : 0 ?>%</b></font></td>
      <td class="main" align="right"><b><?php echo $currencies->format(round($total_recovered, 2)) ?></b></font></td>
      <td class="main">&nbsp;</td>
    </tr>
Done!
    </table>
<!-- body_text_eof //-->
  </td>
 </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>