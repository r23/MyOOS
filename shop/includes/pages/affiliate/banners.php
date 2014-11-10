<?php
/* ----------------------------------------------------------------------
   $Id: banners.php,v 1.1 2007/06/07 16:29:21 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_banners.php,v 1.13 2003/02/27 19:26:15 harley_vb
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

  require 'includes/languages/' . $sLanguage . '/affiliate_banners.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['affiliate'], $aFilename['affiliate_banners']));

  $affiliate_bannerstable = $oostable['affiliate_banners'];
  $sql = "SELECT affiliate_products_id, affiliate_banners_id, affiliate_banners_image, affiliate_banners_title
          FROM $affiliate_bannerstable
          ORDER BY affiliate_banners_title";
  $affiliate_banners_values = $dbconn->Execute($sql);
  if ($affiliate_banners_values->RecordCount()) {
    $banners_array = array();
    while ($affiliate_banners = $affiliate_banners_values->fields) {
      $products_descriptiontable = $oostable['products_description'];
      $sql = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . $affiliate_banners['affiliate_products_id'] . "' 
                AND products_languages_id = '" .  intval($nLanguageID) . "'";
      $affiliate_products_result = $dbconn->Execute($sql);
      $affiliate_products = $affiliate_products_result->fields;
      $prod_id = $affiliate_banners['affiliate_products_id'];
      $ban_id = $affiliate_banners['affiliate_banners_id'];
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['products'] . '&amp;file=' . $aFilename['product_info'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . OOS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
          } else { // generic_link
            $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['main'] . '&amp;file=' . $aFilename['main'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . OOS_IMAGES . $affiliate_banners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
          }
          break;
        case 2: // Link to Products
          if ($prod_id > 0) {
            $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['products'] . '&amp;file=' . $aFilename['product_info'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;products_id=' . $prod_id . '&affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . $aFilename['affiliate_show_banner'] . '?ref=' . $_SESSION['affiliate_id'] . '&amp;affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_products['products_name'] . '"></a>';
          } else { // generic_link
            $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['main'] . '&amp;file=' . $aFilename['main'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;affiliate_banner_id=' . $ban_id . '" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . $aFilename['affiliate_show_banner'] . '?ref=' . $_SESSION['affiliate_id'] . '&amp;affiliate_banner_id=' . $ban_id . '" border="0" alt="' . $affiliate_banners['affiliate_banners_title'] . '"></a>';
          }
          break;
      }
      $banners_array[] = array('link' => $link,
                               'affiliate_banners_title' => $affiliate_banners['affiliate_banners_title']);
      $affiliate_banners_values->MoveNext();
    }

    // Close result set
    $affiliate_banners_values->Close();
  }

  if (oos_is_not_null($_POST['individual_banner_id']) || oos_is_not_null($_GET['individual_banner_id'])) {
    if (oos_is_not_null($_POST['individual_banner_id'])) $individual_banner_id = $_POST['individual_banner_id'];
    if ($_GET['individual_banner_id']) $individual_banner_id = oos_var_prep_for_os($_GET['individual_banner_id']);

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT p.products_image, pd.products_name
            FROM $productstable p,
                 $products_descriptiontable pd
            WHERE p.products_id = '" . intval($individual_banner_id) . "'
              AND pd.products_id = '" . intval($individual_banner_id) . "'
              AND p.products_status = '3'
              AND pd.products_languages_id = '" .  intval($nLanguageID) . "'";
    $affiliate_pbanners_values = $dbconn->Execute($sql);
    if ($affiliate_pbanners = $affiliate_pbanners_values->fields) {
      switch (AFFILIATE_KIND_OF_BANNERS) {
        case 1:
          $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['products'] . '&amp;file=' . $aFilename['product_info'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;products_id=' . $individual_banner_id . '&amp;affiliate_banner_id=1" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . OOS_IMAGES . $affiliate_pbanners['affiliate_banners_image'] . '" border="0" alt="' . $affiliate_pbanners['products_name'] . '"></a>';
          break;
        case 2: // Link to Products
          $link = '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'index.php?mp=' . $aModules['products'] . '&amp;file=' . $aFilename['product_info'] . '&amp;ref=' . $_SESSION['affiliate_id'] . '&amp;products_id=' . $individual_banner_id . '&amp;affiliate_banner_id=1" target="_blank"><img src="' . OOS_HTTP_SERVER . OOS_SHOP . $aFilename['affiliate_show_banner'] . '?ref=' . $_SESSION['affiliate_id'] . '&affiliate_pbanner_id=' . $individual_banner_id . '" border="0" alt="' . $affiliate_pbanners['products_name'] . '"></a>';
          break;
      }
    }
  }

  $aOption['template_main'] = $sTheme . '/modules/affiliate_banners.html';
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
          'oos_breadcrumb'       => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'    => $aLang['heading_title'],
          'oos_heading_image'    => 'specials.gif',

          'individual_banner_id' =>  $individual_banner_id,
          'affiliate_pbanners'   => $affiliate_pbanners,
          'link'                 => $link,
          'banners_array'        => $banners_array
      )
  );

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>
