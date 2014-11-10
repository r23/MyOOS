<?php
/* ----------------------------------------------------------------------
   $Id: sales.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_sales.php,v 1.8 2003/02/22 23:41:16 harley_vb 
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

  require 'includes/languages/' . $sLanguage . '/affiliate_sales.php';

  $affiliate_salestable = $oostable['affiliate_sales'];
  $orderstable = $oostable['orders'];
  $orders_statustable = $oostable['orders_status'];
  $affiliate_sales_raw = "SELECT a.*, o.orders_status AS orders_status_id, os.orders_status_name AS orders_status
                          FROM $affiliate_salestable a LEFT JOIN
                               $orderstable o ON (a.affiliate_orders_id = o.orders_id) LEFT JOIN
                               $orders_statustable os ON (o.orders_status = os.orders_status_id AND
                               os.orders_languages_id = '" .  intval($nLanguageID) . "')
                          WHERE a.affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'
                          ORDER BY affiliate_date DESC";
  $affiliate_sales_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_sales_raw, $affiliate_sales_numrows);
  $affiliate_sales_result = $dbconn->Execute($affiliate_sales_raw);

  $number_of_sales = $affiliate_sales_result->RecordCount();
  $sum_of_earnings = 0;
  $sales_array = array();
  while ($affiliate_sales = $affiliate_sales_result->fields) {
    if ($affiliate_sales['orders_status_id'] >= AFFILIATE_PAYMENT_ORDER_MIN_STATUS) $sum_of_earnings += $affiliate_sales['affiliate_payment'];
    $sales_array[] = array('affiliate_date' => $affiliate_sales['affiliate_date'],
                           'affiliate_value' => $oCurrencies->display_price($affiliate_sales['affiliate_value'], ''),
                           'affiliate_percent' => $affiliate_sales['affiliate_percent'],
                           'affiliate_payment' => $oCurrencies->display_price($affiliate_sales['affiliate_payment'], ''),
                           'orders_status' => $affiliate_sales['orders_status']);
    $affiliate_sales_result->MoveNext();
  }
  // Close result set
  $affiliate_sales_result->Close();

  $oos_sum_of_earnings = $oCurrencies->display_price($sum_of_earnings,'');

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_sales'], '', 'SSL'));

  $aOption['template_main'] = $sTheme . '/modules/affiliate_sales.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

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
          'oos_breadcrumb'      => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'   => $aLang['heading_title'],
          'oos_heading_image'   => 'specials.gif',

          'oos_page_split'      => $affiliate_sales_split->display_count($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], $aLang['text_display_number_of_sales']),
          'oos_display_links'   => $affiliate_sales_split->display_links($affiliate_sales_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
          'oos_page_numrows'    => $affiliate_sales_numrows,

          'number_of_sales'     => $number_of_sales,
          'oos_sum_of_earnings' => $oos_sum_of_earnings,
          'sales_array'         => $sales_array
      )
  );

  $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
