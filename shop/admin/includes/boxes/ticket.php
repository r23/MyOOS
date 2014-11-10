<?php
/* ----------------------------------------------------------------------
   $Id: ticket.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: ticket.php,v 1.5 2003/04/25 21:37:11 hook 
   ----------------------------------------------------------------------
   OSC-SupportTicketSystem
   Copyright (c) 2003 Henri Schmidhuber IN-Solution

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- tickets //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TICKET,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=ticket'));

  if ($_SESSION['selected_box'] == 'ticket' || $menu_dhtml == true) {
    $contents[] = array('text'  => oos_admin_files_boxes('ticket_view', BOX_TICKET_VIEW) .
                                   oos_admin_files_boxes('ticket_reply', BOX_TEXT_REPLY) .
                                   oos_admin_files_boxes('ticket_admin', BOX_TEXT_ADMIN) .
                                   oos_admin_files_boxes('ticket_department', BOX_TEXT_DEPARTMENT) .
                                   oos_admin_files_boxes('ticket_status', BOX_TEXT_STATUS) .
                                   oos_admin_files_boxes('ticket_priority', BOX_TEXT_PRIORITY));
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tickets_eof //-->
