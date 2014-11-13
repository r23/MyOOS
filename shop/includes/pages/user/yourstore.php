<?php
/* ----------------------------------------------------------------------
   $Id: yourstore.php,v 1.2 2007/10/21 01:23:35 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

// start the session
if ( is_session_started() === FALSE ) oos_session_start();  
  
  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'));
  }

  require 'includes/languages/' . $sLanguage . '/user_yourstore.php';

  $customerstable = $oostable['customers'];
  $sql = "SELECT customers_gender, customers_firstname, customers_lastname,
                 customers_image, customers_dob, customers_number, customers_email_address,
                 customers_vat_id, customers_telephone, customers_fax, customers_newsletter
          FROM $customerstable
          WHERE customers_id = '" . intval($_SESSION['customer_id']) . "'";
  $account = $dbconn->GetRow($sql);

  if ($account['customers_gender'] == 'm') {
    $gender = $aLang['male'];
  } elseif ($account['customers_gender'] == 'f') {
    $gender = $aLang['female'];
  }


  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['user'], $aFilename['yourstore'], '', 'SSL'));

  $aOption['template_main'] = $sTheme . '/modules/user_yourstore.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'       => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title'    => $aLang['heading_title'],
          'oos_heading_image'    => 'account.gif',

          'account'              => $account,
          'gender'               => $gender
      )
  );

  if ($_SESSION['cart']->count_contents() > 0) {
    $product_ids = $_SESSION['cart']->get_numeric_product_id_list();

    $products_up_selltable = $oostable['products_up_sell'];
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];

    $sql = "SELECT DISTINCT p.products_id, p.products_image, pd.products_name,
                 substring(pd.products_description, 1, 150) AS products_description
            FROM $products_up_selltable up,
                 $productstable p,
                 $products_descriptiontable pd
           WHERE up.products_id IN (" . $product_ids . ")
              AND up.up_sell_id = p.products_id
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
              AND p.products_status >= '1'
            ORDER BY up.products_id ASC";
    $up_sell_products_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_XSELL_PRODUCTS);

    if ($up_sell_products_result->RecordCount() >=  0) {
      $smarty->assign('oos_up_sell_products_array', $up_sell_products_result->GetArray());
    }
  }


  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
