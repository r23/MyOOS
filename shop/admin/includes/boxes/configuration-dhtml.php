<?php
/* ----------------------------------------------------------------------
   $Id: configuration-dhtml.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

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
<!-- configuration-dhtml //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_TOOLS,
                     'link'  => 'selected_box=configuration-dhtml');

  if ($menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'administratorMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_ADMINISTRATORS . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'configurationMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_CONFIGURATION . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'modulesMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_MODULES . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'taxesMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_LOCATION_AND_TAXES . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'localizationMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_LOCALIZATION . '</span><span class="menuItemArrow">&#9654;</span></a><br />' .
                                   '<a href="#" onclick="return false;" onmouseover="menuItemMouseover(event, \'informationMenu\');" class="menuBoxContentLink"><span class="menuItemText">' . BOX_HEADING_INFORMATION . '</span><span class="menuItemArrow">&#9654;</span></a><br />');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- configuration-dhtml_eof //-->
