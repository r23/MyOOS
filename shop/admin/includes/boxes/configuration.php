<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.16 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  include 'includes/languages/' . $_SESSION['language'] . '/configuration_group.php';
?>
<!-- configuration //-->
          <tr>
            <td>
<?php
  $heading = array();
  $contents = array();

  $heading[] = array('text'  => BOX_HEADING_CONFIGURATION,
                     'link'  => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=configuration'));

  if ($_SESSION['selected_box'] == 'configuration' ) {
    $cfg_groups = '';
    $configuration_groups_result = $dbconn->Execute("SELECT configuration_group_id as cgID FROM " . $oostable['configuration_group'] . " where visible = '1' ORDER BY sort_order");
    while ($configuration_groups = $configuration_groups_result->fields) {
      $cfg_groups .= '<a href="' . oos_href_link_admin($aFilename['configuration'], 'gID=' . $configuration_groups['cgID'], 'NONSSL') . '" class="menuBoxContentLink">' . constant(strtoupper($configuration_groups['cgID'] . '_TITLE')) . '</a><br />';

      // Move that ADOdb pointer!
      $configuration_groups_result->MoveNext();
    }
    $contents[] = array('text'  => $cfg_groups);
  }

  $box = new box;
  echo $box->menuBox($heading, $contents);
?>
            </td>
          </tr>
<!-- configuration_eof //-->
