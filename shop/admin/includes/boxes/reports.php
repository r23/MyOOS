<?php
/* ----------------------------------------------------------------------
   $Id: reports.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reports.php,v 1.4 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- reports //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_REPORTS,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=reports'));

  if ($_SESSION['selected_box'] == 'reports' ) {
      $contents[] = array('text'  => oos_admin_files_boxes('stats_referer', 'selected_box=reports', BOX_REPORTS_REFERER) .
                                     oos_admin_files_boxes('stats_products_viewed', 'selected_box=reports', BOX_REPORTS_PRODUCTS_VIEWED) .
                                     oos_admin_files_boxes('stats_products_purchased', 'selected_box=reports', BOX_REPORTS_PRODUCTS_PURCHASED) .
                                     oos_admin_files_boxes('stats_low_stock', 'selected_box=reports', BOX_REPORTS_STOCK_LEVEL) . 
                                     oos_admin_files_boxes('stats_customers', 'selected_box=reports', BOX_REPORTS_ORDERS_TOTAL) .
                                     oos_admin_files_boxes('stats_sales_report2', 'selected_box=reports', BOX_REPORTS_SALES_REPORT2) .
                                     oos_admin_files_boxes('stats_recover_cart_sales', 'selected_box=reports', BOX_REPORTS_RECOVER_CART_SALES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- reports_eof //-->
