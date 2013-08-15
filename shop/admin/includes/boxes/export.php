<?php
/* ----------------------------------------------------------------------
   $Id: export.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- export //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_EXPORT,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=export'));

  if ($_SESSION['selected_box'] == 'export' ) {
    $contents[] = array('text'  => oos_admin_files_boxes('export_googlebase', 'selected_box=export', BOX_EXPORT_GOOGLEBASE) .
                                   oos_admin_files_boxes('export_preissuchmaschine', 'selected_box=export', BOX_EXPORT_PREISSUCHMASCHINE));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- export_eof //-->
