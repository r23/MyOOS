<?php
/* ----------------------------------------------------------------------
   $Id: localization.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: localization.php,v 1.15 2002/03/16 00:20:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- localization //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LOCALIZATION,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=localization'));

  if ($_SESSION['selected_box'] == 'localization' ) {
    $contents[] = array('text'  => oos_admin_files_boxes('currencies', 'selected_box=localization', BOX_LOCALIZATION_CURRENCIES) .
                                   oos_admin_files_boxes('languages', 'selected_box=localization', BOX_LOCALIZATION_LANGUAGES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- localization_eof //-->
