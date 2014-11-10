<?php
/* ----------------------------------------------------------------------
   $Id: links.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/02 
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- links //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_LINKS,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=links'));


  if ($_SESSION['selected_box'] == 'links' || $menu_dhtml == true) {
        $contents[] = array('text'  => oos_admin_files_boxes('links', BOX_CONTENT_LINKS) .
                                       oos_admin_files_boxes('links_categories', BOX_CONTENT_LINK_CATEGORIES) .
                                       oos_admin_files_boxes('links_contact', BOX_CONTENT_LINKS_CONTACT));

  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- links_eof //-->
