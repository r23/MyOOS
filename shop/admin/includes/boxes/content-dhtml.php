<?php
/* ----------------------------------------------------------------------
   $Id: content-dhtml.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

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
<!-- content-dhtml //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => 'selected_box=content-dhtml');

  if ($menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'contentMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_CONTENT . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'newsfeedMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_NEWSFEED . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'modulesMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_MODULES . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'pluginsMenu\');" class="menuBoxContentLink"><span class="menuItemText">' .  BOX_HEADING_PLUGINS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'customersMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_CUSTOMERS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'linksMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_LINKS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'rss_adminMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_RSS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'exportMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_EXPORT . '</span><span class="menuItemArrow">&#9654;</span></a><br />');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- content-dhtml_eof //-->
