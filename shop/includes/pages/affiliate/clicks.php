<?php
/* ----------------------------------------------------------------------
   $Id: clicks.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_clicks.php,v 1.9 2003/02/22 22:37:28 harley_vb
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

  require 'includes/languages/' . $sLanguage . '/affiliate_clicks.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_clicks'], '', 'SSL'));

  $affiliate_affiliatetable = $oostable['affiliate_affiliate'];

  $affiliate_clickthroughstable = $oostable['affiliate_clickthroughs'];
  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $affiliate_clickthroughs_raw = "SELECT a.*, pd.products_name
                                  FROM $affiliate_clickthroughstable a LEFT JOIN
                                       $productstable p ON (p.products_id = a.affiliate_products_id) LEFT JOIN
                                       $products_descriptiontable pd ON (pd.products_id = p.products_id AND pd.products_languages_id = '" . intval($nLanguageID) . "')
                                  WHERE a.affiliate_id = '" . intval($_SESSION['affiliate_id']) . "'
                                  ORDER BY a.affiliate_clientdate DESC";
  $affiliate_clickthroughs_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_clickthroughs_raw, $affiliate_clickthroughs_numrows);
  $affiliate_clickthroughs_result = $dbconn->Execute($affiliate_clickthroughs_raw);

  $nClickthroughs = $affiliate_clickthroughs_result->RecordCount();

  $aClickthroughs = array();
  while ($affiliate_clickthroughs = $affiliate_clickthroughs_result->fields) {
    $aClickthroughs[] = array('affiliate_clientdate' => $affiliate_clickthroughs['affiliate_clientdate'],
                              'affiliate_products_id' => $affiliate_clickthroughs['affiliate_products_id'],
                              'affiliate_products_id' => $affiliate_clickthroughs['affiliate_products_id'],
                              'products_name' => $affiliate_clickthroughs['products_name'],
                              'affiliate_clientreferer' => $affiliate_clickthroughs['affiliate_clientreferer']);
    $affiliate_clickthroughs_result->MoveNext();
  }
  // Close result set
  $affiliate_clickthroughs_result->Close();

  $aOption['template_main'] = $sTheme . '/modules/affiliate_clicks.html';
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
          'oos_breadcrumb'          => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'       => $aLang['heading_title'],
          'oos_heading_image'       => 'specials.gif',

          'oos_page_split'          => $affiliate_clickthroughs_split->display_count($affiliate_clickthroughs_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], $aLang['text_display_number_of_clicks']),
          'oos_display_links'       => $affiliate_clickthroughs_split->display_links($affiliate_clickthroughs_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
          'oos_page_numrows'        => $affiliate_clickthroughs_numrows,

          'number_of_clickthroughs' => $nClickthroughs,
          'clickthroughs_array'     => $aClickthroughs,

          'affiliate_payment_info'  => $affiliate_payment_info
      )
  );

  $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
