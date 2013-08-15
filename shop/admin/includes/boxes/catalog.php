<?php
/* ----------------------------------------------------------------------
   $Id: catalog.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: catalog.php,v 1.20 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- catalog //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CATALOG,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=catalog'));

  if ($_SESSION['selected_box'] == 'catalog' ) {
    if (STOCK_CHECK == 'true') {
      $contents[] = array('text'  => oos_admin_files_boxes('categories', 'selected_box=catalog', BOX_CATALOG_CATEGORIES_PRODUCTS) .
                                     '<a href="' . oos_href_link_admin($aFilename['products'], 'action=new_product', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_QADD_PRODUCT . '</a><br />'.
                                     oos_admin_files_boxes('specials', 'selected_box=catalog', BOX_CATALOG_SPECIALS) .
                                     oos_admin_files_boxes('products_expected', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_EXPECTED) .
                                     oos_admin_files_boxes('featured', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_FEATURED) .
                                     oos_admin_files_boxes('products_attributes', 'selected_box=catalog', BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
                                     oos_admin_files_boxes('products_status', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_STATUS) .
                                     oos_admin_files_boxes('products_units', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_UNITS) .
                                     oos_admin_files_boxes('xsell_products', 'selected_box=catalog', BOX_CATALOG_XSELL_PRODUCTS) .
                                     oos_admin_files_boxes('up_sell_products', 'selected_box=catalog', BOX_CATALOG_UP_SELL_PRODUCTS) .
                                     oos_admin_files_boxes('easypopulate','selected_box=catalog',  BOX_CATALOG_EASYPOPULATE) .
                                     oos_admin_files_boxes('export_excel', 'selected_box=catalog', BOX_CATALOG_EXPORT_EXCEL) .
                                     oos_admin_files_boxes('import_excel', 'selected_box=catalog', BOX_CATALOG_IMPORT_EXCEL) .
                                     oos_admin_files_boxes('manufacturers', 'selected_box=catalog', BOX_CATALOG_MANUFACTURERS) .
                                     oos_admin_files_boxes('reviews', 'selected_box=catalog', BOX_CATALOG_REVIEWS) .
                                     oos_admin_files_boxes('quick_stockupdate', 'selected_box=catalog', BOX_CATALOG_QUICK_STOCKUPDATE));
    } else {
      $contents[] = array('text'  => oos_admin_files_boxes('categories', 'selected_box=catalog', BOX_CATALOG_CATEGORIES_PRODUCTS) .
                                     '<a href="' . oos_href_link_admin($aFilename['products'], 'action=new_product', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_CATALOG_QADD_PRODUCT . '</a><br />'.
                                     oos_admin_files_boxes('specials', 'selected_box=catalog', BOX_CATALOG_SPECIALS) .
                                     oos_admin_files_boxes('products_expected', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_EXPECTED) .
                                     oos_admin_files_boxes('featured', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_FEATURED) .
                                     oos_admin_files_boxes('products_units', 'selected_box=catalog', BOX_CATALOG_PRODUCTS_UNITS) .
                                     oos_admin_files_boxes('products_attributes', 'selected_box=catalog', BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES) .
                                     oos_admin_files_boxes('xsell_products', 'selected_box=catalog', BOX_CATALOG_XSELL_PRODUCTS) . 
                                     oos_admin_files_boxes('up_sell_products', 'selected_box=catalog', BOX_CATALOG_UP_SELL_PRODUCTS) .
                                     oos_admin_files_boxes('easypopulate', 'selected_box=catalog', BOX_CATALOG_EASYPOPULATE) .
                                     oos_admin_files_boxes('export_excel', 'selected_box=catalog', BOX_CATALOG_EXPORT_EXCEL) .
                                     oos_admin_files_boxes('import_excel', 'selected_box=catalog', BOX_CATALOG_IMPORT_EXCEL) .
                                     oos_admin_files_boxes('manufacturers', 'selected_box=catalog', BOX_CATALOG_MANUFACTURERS) .
                                     oos_admin_files_boxes('reviews', 'selected_box=catalog', BOX_CATALOG_REVIEWS));

    }
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
