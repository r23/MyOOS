<?php
/* ----------------------------------------------------------------------
   $Id: payment.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_payment.php,v 1.6 2003/02/22 23:30:37 harley_vb 
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

  require 'includes/languages/' . $sLanguage . '/affiliate_payment.php';


  $affiliate_paymenttable = $oostable['affiliate_payment'];
  $affiliate_payment_statustable = $oostable['affiliate_payment_status'];
  $affiliate_payment_raw = "SELECT p.* , s.affiliate_payment_status_name
                            FROM $affiliate_paymenttable p,
                                 $affiliate_payment_statustable s
                            WHERE p.affiliate_payment_status = s.affiliate_payment_status_id
                              AND s.affiliate_languages_id = '" . intval($nLanguageID) . "'
                              AND p.affiliate_id =  '" . intval($_SESSION['affiliate_id']) . "'
                            ORDER BY p.affiliate_payment_id DESC";
  $affiliate_payment_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_payment_raw, $affiliate_payment_numrows);
  $affiliate_payment_result = $dbconn->Execute($affiliate_payment_raw);

  $number_of_payment = $affiliate_payment_result->RecordCount();
  $payment_array = array();
  while ($affiliate_payment = $affiliate_payment_result->fields) {
    $payment_array[] = array('affiliate_payment_id' => $affiliate_payment['affiliate_payment_id'],
                             'affiliate_payment_date' => $affiliate_payment['affiliate_payment_date'],
                             'affiliate_payment_total' => $oCurrencies->display_price($affiliate_payment['affiliate_payment_total'], ''),
                             'affiliate_payment_status_name' => $affiliate_payment['affiliate_payment_status_name']);
    $affiliate_payment_result->MoveNext();  
  }

  // Close result set
  $affiliate_payment_result->Close();

  $affiliate_paymenttable = $oostable['affiliate_payment'];
  $sql = "SELECT sum(affiliate_payment_total) AS total
          FROM $affiliate_paymenttable
          WHERE affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'";
  $affiliate_payment_values = $dbconn->Execute($sql);
  $affiliate_payment_info = $affiliate_payment_values->fields;

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_payment'], '', 'SSL'));

  $aOption['template_main'] = $sTheme . '/modules/affiliate_payment.html';
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
          'oos_breadcrumb'         => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'      => sprintf($aLang['heading_title'], $product_info['products_name']),
          'oos_heading_image'      => 'specials.gif',

          'oos_page_split'         => $affiliate_payment_split->display_count($affiliate_payment_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], $aLang['text_display_number_of_payments']),
          'oos_display_links'      => $affiliate_payment_split->display_links($affiliate_payment_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
          'oos_page_numrows'       => $affiliate_payment_numrows,

          'number_of_payment'      =>  $number_of_payment,
          'payment_array'          => $payment_array,
          'affiliate_payment_info' => $affiliate_payment_info
      )
  );

  $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
