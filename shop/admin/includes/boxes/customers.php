<?php
/* ----------------------------------------------------------------------
   $Id: customers.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: customers.php,v 1.15 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- customers //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CUSTOMERS,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=customers'));

  if ($_SESSION['selected_box'] == 'customers' ) {
    $contents[] = array('text'  => oos_admin_files_boxes('customers', 'selected_box=customers', BOX_CUSTOMERS_CUSTOMERS) .
                                   oos_admin_files_boxes('orders', 'selected_box=customers', BOX_CUSTOMERS_ORDERS) .
                                   oos_admin_files_boxes('customers_status','selected_box=customers', BOX_LOCALIZATION_CUSTOMERS_STATUS) .
                                   oos_admin_files_boxes('orders_status', 'selected_box=customers', BOX_LOCALIZATION_ORDERS_STATUS) .
                                   oos_admin_files_boxes('campaigns', 'selected_box=customers', BOX_CAMPAIGNS) .
                                   oos_admin_files_boxes('manual_loging', 'selected_box=customers', BOX_ADMIN_LOGIN));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
