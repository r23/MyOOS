<?php
/* ----------------------------------------------------------------------
   $Id: taxes.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: taxes.php,v 1.16 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- taxes //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCATION_AND_TAXES,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=taxes'));

  if ($_SESSION['selected_box'] == 'taxes' ) {
    $contents[] = array('text'  => oos_admin_files_boxes('countries', BOX_TAXES_COUNTRIES) .
                                   oos_admin_files_boxes('zones', BOX_TAXES_ZONES) .
                                   oos_admin_files_boxes('geo_zones', BOX_TAXES_GEO_ZONES) .
                                   oos_admin_files_boxes('tax_classes', BOX_TAXES_TAX_CLASSES) .
                                   oos_admin_files_boxes('tax_rates', BOX_TAXES_TAX_RATES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- taxes_eof //-->
