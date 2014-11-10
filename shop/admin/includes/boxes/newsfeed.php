<?php
/* ----------------------------------------------------------------------
   $Id: newsfeed.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

  if ($_SESSION['selected_box'] == 'newsfeed' || $menu_dhtml == true) { 
    $contents[] = array('text'  => oos_admin_files_boxes('newsfeed_manager', BOX_NEWSFEED_MANAGER) .
                                   oos_admin_files_boxes('newsfeed_categories', BOX_NEWSFEED_CATEGORIES));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- newsfeed_eof //-->
