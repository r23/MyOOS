<?php
/* ----------------------------------------------------------------------
   $Id: tools.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: tools.php,v 1.20 2002/03/16 00:20:11 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  if ($_SESSION['selected_box'] == 'tools' ) {
    $contents[] = array('text'  => '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'administrator/mysqldumper/index.php' . '" class="menuBoxContentLink">' . BOX_TOOLS_BACKUP . '</a><br />' .
                                   oos_admin_files_boxes('mail', 'selected_box=tools', BOX_TOOLS_MAIL) .
                                   oos_admin_files_boxes('newsletters', 'selected_box=tools', BOX_TOOLS_NEWSLETTER_MANAGER) .
                                   oos_admin_files_boxes('whos_online', 'selected_box=tools', BOX_TOOLS_WHOS_ONLINE) .
                                   oos_admin_files_boxes('recover_cart_sales', 'selected_box=tools', BOX_TOOLS_RECOVER_CART));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
