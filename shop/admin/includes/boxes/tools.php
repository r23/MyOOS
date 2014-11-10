<?php
/* ----------------------------------------------------------------------
   $Id: tools.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
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

  if ($_SESSION['selected_box'] == 'tools' || $menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="' . OOS_HTTP_SERVER . OOS_SHOP . 'administrator/mysqldumper/index.php' . '" class="menuBoxContentLink">' . BOX_TOOLS_BACKUP . '</a><br />' .
                                   oos_admin_files_boxes('box_content', BOX_TOOLS_CONTENT) .
                                   oos_admin_files_boxes('define_language', BOX_TOOLS_DEFINE_LANGUAGE) .
                                   oos_admin_files_boxes('mail', BOX_TOOLS_MAIL) .
                                   oos_admin_files_boxes('newsletters', BOX_TOOLS_NEWSLETTER_MANAGER) .
                                   oos_admin_files_boxes('server_info', BOX_TOOLS_SERVER_INFO) .
                                   oos_admin_files_boxes('whos_online', BOX_TOOLS_WHOS_ONLINE) .
                                   oos_admin_files_boxes('recover_cart_sales', BOX_TOOLS_RECOVER_CART));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
