<?php
/* ----------------------------------------------------------------------
   $Id: tools-dhtml.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

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
<!-- tools //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => 'selected_box=tools-dhtml');

  if ($menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'reportsMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_REPORTS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'toolsMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_TOOLS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'rss_adminMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_RSS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'exportMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_EXPORT . '</span><span class="menuItemArrow">&#9654;</span></a><br />');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- tools_eof //-->
