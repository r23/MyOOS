<?php
/* ----------------------------------------------------------------------
   $Id: administrator.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: administrator.php,v 1.20 2002/03/16 00:20:11 hpdl
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

  $heading[] = array('text'  => BOX_HEADING_ADMINISTRATOR,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=administrator'));

  if ($_SESSION['selected_box'] == 'administrator' ) {
    $contents[] = array('text'  => oos_admin_files_boxes('admin_members', 'selected_box=administrator', BOX_ADMINISTRATOR_MEMBERS) .
                                   oos_admin_files_boxes('admin_files', 'selected_box=administrator', BOX_ADMINISTRATOR_BOXES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- catalog_eof //-->
