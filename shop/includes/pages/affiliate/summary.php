<?php
/* ----------------------------------------------------------------------
   $Id: summary.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_summary.php,v 1.11 2003/03/02 23:44:50 simarilius 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  if (!$oEvent->installed_plugin('affiliate')) {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main']));
  }

  if (!isset($_SESSION['affiliate_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['affiliate'], $aFilename['affiliate_affiliate'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/affiliate_summary.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_summary']));

  $affiliate_banners_historytable = $oostable['affiliate_banners_history'];
  $affiliate_banner_history_raw = "SELECT sum(affiliate_banners_shown) AS count 
                                   FROM $affiliate_banners_historytable
                                   WHERE affiliate_banners_affiliate_id  = '" . intval($_SESSION['affiliate_id']) . "'";
  $affiliate_banner_history_result = $dbconn->Execute($affiliate_banner_history_raw);
  $affiliate_banner_history = $affiliate_banner_history_result->fields;
  $affiliate_impressions = $affiliate_banner_history['count'];
  if ($affiliate_impressions == 0) $affiliate_impressions="n/a"; 

  $affiliate_clickthroughstable = $oostable['affiliate_clickthroughs'];
  $affiliate_clickthroughs_raw = "SELECT COUNT(*) AS count 
                                  FROM $affiliate_clickthroughstable
                                  WHERE affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'";
  $affiliate_clickthroughs_result = $dbconn->Execute($affiliate_clickthroughs_raw);
  $affiliate_clickthroughs = $affiliate_clickthroughs_result->fields;
  $affiliate_clickthroughs = $affiliate_clickthroughs['count'];

  $affiliate_salestable = $oostable['affiliate_sales'];
  $ordersstable = $oostable['orders'];
  $affiliate_sales_raw = "SELECT COUNT(*) AS count, sum(affiliate_value) AS total, 
                                 sum(affiliate_payment) AS payment
                          FROM $affiliate_salestable a
                          LEFT JOIN $ordersstable o
                           ON (a.affiliate_orders_id = o.orders_id) 
                          WHERE a.affiliate_id = '" . intval($_SESSION['affiliate_id']) . "' 
                            AND o.orders_status >= " . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . "";
  $affiliate_sales_result = $dbconn->Execute($affiliate_sales_raw);
  $affiliate_sales = $affiliate_sales_result->fields;

  $affiliate_transactions = $affiliate_sales['count'];
  if ($affiliate_clickthroughs > 0) {
    $affiliate_conversions = round($affiliate_transactions / $affiliate_clickthroughs, 6) . "%";
  } else {
    $affiliate_conversions = "n/a";
  }
  $affiliate_amount = $affiliate_sales['total'];
  if ($affiliate_transactions>0) {
    $affiliate_average = round($affiliate_amount / $affiliate_transactions, 2);
  } else {
    $affiliate_average = "n/a";
  }
  $affiliate_commission = $affiliate_sales['payment'];

  $affiliate_affiliatestable = $oostable['affiliate_affiliate'];
  $sql = "SELECT *
          FROM $affiliate_affiliatestable
          WHERE affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'";
  $affiliate_values = $dbconn->Execute($sql);
  $affiliate = $affiliate_values->fields;

  $affiliate_percent = 0;
  $affiliate_percent = $affiliate['affiliate_commission_percent'];
  if ($affiliate_percent < AFFILIATE_PERCENT) $affiliate_percent = AFFILIATE_PERCENT;

  $aOption['template_main'] = $sTheme . '/modules/affiliate_summary.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_AFFILIATE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb'             => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'          => $aLang['heading_title'],
          'oos_heading_image'          => 'specials.gif',

          'affiliate'                  => $affiliate,
          'affiliate_impressions'      => $affiliate_impressions,
          'affiliate_clickthroughs'    => $affiliate_clickthroughs,
          'affiliate_transactions'     => $affiliate_transactions,
          'affiliate_conversions'      => $affiliate_conversions,

          'price_affiliate_amount'     => $oCurrencies->display_price($affiliate_amount, ''),
          'price_affiliate_average'    => $oCurrencies->display_price($affiliate_average, ''),
          'price_affiliate_commission' => $oCurrencies->display_price($affiliate_commission, ''),

          'round_affiliate_percent'    => round($affiliate_percent, 2)
      )
  );

  // JavaScript
  $oSmarty->assign('popup_window', 'popup_window.js');

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>