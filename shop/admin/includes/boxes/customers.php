<?php
/* ----------------------------------------------------------------------
   $Id: customers.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

  if ($_SESSION['selected_box'] == 'customers' || $menu_dhtml == true) {
    $contents[] = array('text'  => oos_admin_files_boxes('customers', BOX_CUSTOMERS_CUSTOMERS) .
                                   oos_admin_files_boxes('orders', BOX_CUSTOMERS_ORDERS) .
                                   oos_admin_files_boxes('customers_status', BOX_LOCALIZATION_CUSTOMERS_STATUS) .
                                   oos_admin_files_boxes('orders_status', BOX_LOCALIZATION_ORDERS_STATUS) .
                                   oos_admin_files_boxes('campaigns', BOX_CAMPAIGNS) .
                                   oos_admin_files_boxes('manual_loging', BOX_ADMIN_LOGIN));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers_eof //-->
