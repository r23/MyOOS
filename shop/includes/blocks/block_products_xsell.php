<?php
/* ----------------------------------------------------------------------
   $Id: block_products_xsell.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $xsell_block = 'false';

  if (isset($_GET['products_id'])) {
    if (!isset($nProductsId)) $nProductsId = oos_get_product_id($_GET['products_id']);

    $productstable = $oostable['products'];
    $products_xselltable = $oostable['products_xsell'];
    $products_descriptiontable = $oostable['products_description'];
    $sql = "SELECT DISTINCT p.products_id, p.products_image, pd.products_name,
                  substring(pd.products_description, 1, 150) AS products_description
            FROM $products_xselltable xp,
                 $productstable p,
                 $products_descriptiontable pd
            WHERE xp.products_id = '" . intval($nProductsId) . "'
              AND xp.xsell_id = p.products_id
              AND p.products_id = pd.products_id
              AND pd.products_languages_id = '" . intval($nLanguageID) . "'
              AND p.products_status >= '1'
            ORDER BY xp.products_id ASC";
    $xsell_products_result = $dbconn->SelectLimit($sql, MAX_DISPLAY_XSELL_PRODUCTS);

    if ($xsell_products_result->RecordCount()) {
      $xsell_block = 'true';
      $smarty->assign('block_xsell_products', $xsell_products_result->GetArray());
    }
  }
  $smarty->assign('block_heading_xsell', $block_heading);

  $smarty->assign('xsell_block', $xsell_block);


