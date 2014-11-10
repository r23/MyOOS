<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_statistics.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_statistics.php,v 1.8 2003/02/14 03:16:50 harley_vb 
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

  $affiliate_banner_history_raw = "SELECT sum(affiliate_banners_shown) as count FROM " . $oostable['affiliate_banners_history'] .  " WHERE affiliate_banners_affiliate_id  = '" .  $_GET['acID'] . "'";
  $affiliate_banner_history_result = $dbconn->Execute($affiliate_banner_history_raw);
  $affiliate_banner_history = $affiliate_banner_history_result->fields;
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions = "n/a"; 

  $affiliate_result = $dbconn->Execute("SELECT * FROM " . $oostable['affiliate_affiliate'] . " WHERE affiliate_id ='" . $_GET['acID'] . "'");

  $affiliate = $affiliate_result->fields;
  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;

  $affiliate_clickthroughs_raw = "SELECT COUNT(*) AS count FROM " . $oostable['affiliate_clickthroughs'] . " WHERE affiliate_id = '" . $_GET['acID'] . "'";
  $affiliate_clickthroughs_result = $dbconn->Execute($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = $affiliate_clickthroughs_result->fields;
  $affiliate_clickthroughs = $affiliate_clickthroughs['count'];

  $affiliate_sales_raw = "SELECT 
                              COUNT(*) AS count, sum(affiliate_value) as total, 
                              sum(affiliate_payment) as payment
                          FROM 
                              " . $oostable['affiliate_sales'] . " a LEFT JOIN 
                              " . $oostable['orders'] . " o 
                           ON (a.affiliate_orders_id=o.orders_id)
                          WHERE 
                              a.affiliate_id = '" . $_GET['acID'] . "' AND
                              o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . "";
  $affiliate_sales_result = $dbconn->Execute($affiliate_sales_raw);
  $affiliate_sales = $affiliate_sales_result->fields;

  // Close result set
  $affiliate_sales_result->Close();

  if ($affiliate_sales['count'] > 0) {
    $affiliate_conversions = round($affiliate_clickthroughs / $affiliate_sales['count'], 2);
  } else {
    $affiliate_conversions = "n/a";
  }

  if ($affiliate_sales['total'] > 0) {
    $affiliate_average = $affiliate_sales['total'] / $affiliate_sales['count'];
  } else {
    $affiliate_average = 0;
  }
  $no_js_general = true;

  require 'includes/oos_header.php';
?>  
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=120,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate'], oos_get_all_get_params(array('action'))) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TEXT_SUMMARY_TITLE; ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellpadding="4" cellspacing="2" class="dataTableContent">
              <center>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><b><?php echo TEXT_AFFILIATE_NAME; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate['affiliate_firstname'] . ' ' . $affiliate['affiliate_lastname']; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AFFILIATE_JOINDATE; ?>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td width="15%" class="dataTableContent"><?php echo oos_date_short($affiliate['affiliate_date_account_created']); ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_IMPRESSIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help1']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_impressions; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_VISITS; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help2']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_clickthroughs; ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_TRANSACTIONS; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help3']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_sales['count']; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_CONVERSION; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help4']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_conversions.' %';?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AMOUNT; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help5']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_sales['total'], ''); ?></td>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_AVERAGE; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help6']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $currencies->display_price($affiliate_average, ''); ?></td>
                </tr>
                <tr>
                  <td width="35%" align="right" class="dataTableContent"><?php echo TEXT_COMMISSION_RATE; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help7']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></td>
                  <td width="15%" class="dataTableContent"><?php echo $affiliate_percent, ' %'; ?></td>
                  <td width="35%" align="right" class="dataTableContent"><b><?php echo TEXT_COMMISSION; ?><?php echo '<a href="javascript:popupWindow(\'' . oos_catalog_link($oosModules['affiliate'], $oosCatalogFilename['affiliate_help8']) . '\')">' . TEXT_SUMMARY_HELP . '</a>'; ?></b></td>
                  <td width="15%" class="dataTableContent"><b><?php echo $currencies->display_price($affiliate_sales['payment'], ''); ?></b></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo oos_draw_separator(); ?></td>
                </tr>
                <tr>
                  <td align="center" class="dataTableContent" colspan="4"><b><?php echo TEXT_SUMMARY; ?></b></td>
                </tr>
                <tr>
                  <td colspan="4"><?php echo oos_draw_separator(); ?></td>
                </tr>
                <tr>
                  <td align="right" class="dataTableContent" colspan="4"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_clicks'], 'acID=' . $_GET['acID']) . '">' . oos_image_swap_button('affiliate_clickthroughs','affiliate_clickthroughs_off.gif', IMAGE_CLICKTHROUGHS) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate_sales'], 'acID=' . $_GET['acID']) . '">' . oos_image_swap_button('affiliate_sales','affiliate_sales_off.gif', IMAGE_SALES) . '</a>'; ?></td>
                </tr>
              </center>
            </table></td>
          </tr>
        </table></td>
      </tr>
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