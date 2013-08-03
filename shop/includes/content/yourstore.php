<?php
/* ----------------------------------------------------------------------
   $Id: yourstore.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  if (!isset($_SESSION['customer_id'])) {
    $_SESSION['navigation']->set_snapshot();
    oos_redirect(oos_href_link($aContents['login'], '', 'SSL'));
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/user_yourstore.php';

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
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['yourstore'], '', 'SSL'));

  $aTemplate['page'] = $sTheme . '/modules/user_yourstore.tpl';

  $nPageType = OOS_PAGE_TYPE_ACCOUNT;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
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

// display the template
$smarty->display($aTemplate['page']);

