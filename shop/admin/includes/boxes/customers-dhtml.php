<?php
/* ----------------------------------------------------------------------
   $Id: customers-dhtml.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

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
<!-- customers-dhtml //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => 'selected_box=customers-dhtml');

  if ($menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'customersMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_CUSTOMERS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'ticketMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_TICKET . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'affiliateMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_AFFILIATE . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'gv_adminMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_GV_ADMIN . '</span><span class="menuItemArrow">&#9654;</span></a><br />');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- customers-dhtml_eof //-->
