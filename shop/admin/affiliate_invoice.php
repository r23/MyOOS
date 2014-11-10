<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_invoice.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_invoice.php,v 1.7 2003/02/17 17:00:37 harley_vb 
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

  $payments_result = $dbconn->Execute("SELECT * FROM " . $oostable['affiliate_payment'] . " WHERE affiliate_payment_id = '" . $_GET['pID'] . "'");
  $payments = $payments_result->fields;

  $affiliate_address['firstname'] = $payments['affiliate_firstname'];
  $affiliate_address['lastname'] = $payments['affiliate_lastname'];
  $affiliate_address['street_address'] = $payments['affiliate_street_address'];
  $affiliate_address['suburb'] = $payments['affiliate_suburb'];
  $affiliate_address['city'] = $payments['affiliate_city'];
  $affiliate_address['state'] = $payments['affiliate_state'];
  $affiliate_address['country'] = $payments['affiliate_country'];
  $affiliate_address['postcode'] = $payments['affiliate_postcode']
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?> - Administration [OOS]</title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">

<!-- body_text //-->
<table border="0" width="100%" cellspacing="0" cellpadding="2">
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td class="pageHeading"><?php echo nl2br(STORE_NAME_ADDRESS); ?></td>
        <td class="pageHeading" align="center"><?php echo HEADING_TITLE; ?></td>
        <td class="pageHeading" align="right"><?php echo oos_image(OOS_IMAGES . 'oos.gif', STORE_NAME, '130', '49'); ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td><?php echo oos_draw_separator(); ?></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="top"><b><?php echo TEXT_AFFILIATE; ?></b></td>
            <td class="main"><?php echo oos_address_format($payments['affiliate_address_format_id'], $affiliate_address, 1, '&nbsp;', '<br />'); ?></td>
          </tr>
          <tr>
            <td><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
          </tr>
	      <tr>
             <td class="main"><b><?php echo TEXT_AFFILIATE_PAYMENT; ?></b></td>
             <td class="main">&nbsp;<?php echo $currencies->format($payments['affiliate_payment_total']); ?></td>
          </tr>
          <tr>
             <td><?php echo oos_draw_separator('trans.gif', '1', '5'); ?></td>
          </tr>
          <tr>
             <td class="main"><b><?php echo TEXT_AFFILIATE_BILLED; ?></b></td>
             <td class="main">&nbsp;<?php echo oos_date_short($payments['affiliate_payment_date']); ?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><?php echo oos_draw_separator('trans.gif', '1', '20'); ?></td>
  </tr>
  <tr>
    <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr class="dataTableHeadingRow">
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_ID; ?></td>
        <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_ORDER_DATE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ORDER_VALUE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION_RATE; ?></td>
        <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_COMMISSION_VALUE; ?></td>
      </tr>
<?php
  $affiliate_payment_result = $dbconn->Execute("SELECT * FROM " . $oostable['affiliate_payment'] . " WHERE affiliate_payment_id = '" . $_GET['pID'] . "'");
  $affiliate_payment = $affiliate_payment_result->fields;
  $affiliate_sales_result = $dbconn->Execute("SELECT * FROM " . $oostable['affiliate_sales'] . " WHERE affiliate_payment_id = '" . $payments['affiliate_payment_id'] . "' ORDER BY affiliate_payment_date desc");
  while ($affiliate_sales = $affiliate_sales_result->fields) {
?>

      <tr class="dataTableRow">
        <td class="dataTableContent" align="right" valign="top"><?php echo $affiliate_sales['affiliate_orders_id']; ?></td>
        <td class="dataTableContent" align="center" valign="top"><?php echo oos_date_short($affiliate_sales['affiliate_date']); ?></td>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo $currencies->display_price($affiliate_sales['affiliate_value'], ''); ?></b></td>
        <td class="dataTableContent" align="right" valign="top"><?php echo $affiliate_sales['affiliate_percent']; ?><?php echo ENTRY_PERCENT; ?></td>
        <td class="dataTableContent" align="right" valign="top"><b><?php echo $currencies->display_price($affiliate_sales['affiliate_payment'], ''); ?></b></td>
      </tr>
<?php
    // Move that ADOdb pointer!
    $affiliate_sales_result->MoveNext();
  }

  // Close result set
  $affiliate_sales_result->Close();
?>
    </table></td>
  </tr>
  <tr>
    <td align="right" colspan="5"><table border="0" cellspacing="0" cellpadding="2">
      <tr>
        <td align="right" class="smallText"><?php echo TEXT_SUB_TOTAL; ?></td>
        <td align="right" class="smallText"><?php echo $currencies->display_price($affiliate_payment['affiliate_payment'], ''); ?></td>
      </tr>
      <tr>
        <td align="right" class="smallText"><?php echo TEXT_TAX; ?></td>
        <td align="right" class="smallText"><?php echo $currencies->display_price($affiliate_payment['affiliate_payment_tax'], ''); ?></td>
      </tr>
      <tr>
        <td align="right" class="smallText"><b><?php echo TEXT_TOTAL; ?></b></td>
        <td align="right" class="smallText"><b><?php echo $currencies->display_price($affiliate_payment['affiliate_payment_total'], ''); ?></b></td>
      </tr>
    </table></td>
  </tr>
</table>
<!-- body_text_eof //-->
</body>
</html>
<?php require 'includes/oos_nice_exit.php';?>