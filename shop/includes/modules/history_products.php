<?php
/* ----------------------------------------------------------------------
   $Id: history_products.php,v 1.2 2008/08/29 16:53:12 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $products_history_block = 'false';

  if ($_SESSION['products_history']->count_history() > 0) {
    $products_history_block = 'true';

    $product_ids = $_SESSION['products_history']->get_product_id_list();

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products_sql = "SELECT p.products_id, p.products_image, pd.products_name,
                            substring(pd.products_description, 1, 150) AS products_description
                     FROM $productstable p,
                          $products_descriptiontable pd
                     WHERE p.products_id IN (" . $product_ids . ")
                       AND p.products_id = pd.products_id
                       AND pd.products_languages_id = '" .  intval($nLanguageID) . "'
                     ORDER BY products_name";
    $oSmarty->assign('history_products', $dbconn->GetAll($products_sql));
  }

  $oSmarty->assign('products_history_block', $products_history_block);

?>