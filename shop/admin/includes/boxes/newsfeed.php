<?php
/* ----------------------------------------------------------------------
   $Id: newsfeed.php 437 2013-06-22 15:33:30Z r23 $

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
<!-- newsfeed //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_NEWSFEED,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=newsfeed'));

  if ($_SESSION['selected_box'] == 'newsfeed' ) { 
    $contents[] = array('text'  => oos_admin_files_boxes('newsfeed_manager', 'selected_box=newsfeed', BOX_NEWSFEED_MANAGER) .
                                   oos_admin_files_boxes('newsfeed_categories', 'selected_box=newsfeed', BOX_NEWSFEED_CATEGORIES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- newsfeed_eof //-->
