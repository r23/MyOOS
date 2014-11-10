<?php
/* ----------------------------------------------------------------------
   $Id: plugins.php,v 1.1 2007/06/08 14:03:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!-- plugins //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_PLUGINS,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=plugins'));

  if ($_SESSION['selected_box'] == 'plugins' || $menu_dhtml == true) {
    $contents[] = array('text'  => '<a href="' . oos_href_link_admin($aFilename['plugins'], '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PLUGINS_EVENT . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- plugins_eof //-->
