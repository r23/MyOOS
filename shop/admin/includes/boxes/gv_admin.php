<?php
/* ----------------------------------------------------------------------
   $Id: gv_admin.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_admin.php,v 1.2.2.1 2003/04/18 21:13:51 wilt 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- gv_admin //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_GV_ADMIN,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=gv_admin'));

  if ($_SESSION['selected_box'] == 'gv_admin' || $menu_dhtml == true) {
    $contents[] = array('text'  => oos_admin_files_boxes('coupon_admin', BOX_COUPON_ADMIN) .
                                   oos_admin_files_boxes('gv_queue', BOX_GV_ADMIN_QUEUE) .
                                   oos_admin_files_boxes('gv_mail', BOX_GV_ADMIN_MAIL) . 
                                   oos_admin_files_boxes('gv_sent', BOX_GV_ADMIN_SENT));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- gv_admin_eof //-->
