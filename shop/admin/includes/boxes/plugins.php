<?php
/* ----------------------------------------------------------------------
   $Id: plugins.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

  if ($_SESSION['selected_box'] == 'plugins' ) {
    $contents[] = array('text'  => '<a href="' . oos_href_link_admin($aFilename['plugins'], '', 'NONSSL') . '" class="menuBoxContentLink">' . BOX_PLUGINS_EVENT . '</a>');
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- plugins_eof //-->
