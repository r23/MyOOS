<?php
/* ----------------------------------------------------------------------
   $Id: tax_rates.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: tax_rates.php,v 1.27 2002/06/07 01:17:46 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

 /**
  * Output a form pull down menu with tax classes
  *
  * @param $parameters
  * @param $selected
  * @return string
  */
  function oosTaxClassesPullDown($parameters, $selected = '') {

    $select_string = '<select ' . $parameters . '>';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $tax_classtable = $oostable['tax_class'];
    $classes_sql = "SELECT tax_class_id, tax_class_title
                    FROM $tax_classtable
                    ORDER BY tax_class_title";
    $result = $dbconn->Execute($classes_sql);
    while ($classes = $result->fields) {
      $select_string .= '<option value="' . $classes['tax_class_id'] . '"';
      if ($selected == $classes['tax_class_id']) $select_string .= ' selected="selected"';
      $select_string .= '>' . $classes['tax_class_title'] . '</option>';

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    $select_string .= '</select>';

    return $select_string;
  }


 /**
  * Output a form pull down menu with geo zones
  *
  * @param $parameters
  * @param $selected
  * @return string
  */ 
  function oosGeoZonesPullDown($parameters, $selected = '') {

    $select_string = '<select ' . $parameters . '>';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $geo_zonestable = $oostable['geo_zones'];
    $zones_sql = "SELECT geo_zone_id, geo_zone_name
                  FROM $geo_zonestable
                  ORDER BY geo_zone_name";
    $result = $dbconn->Execute($zones_sql);
    while ($zones = $result->fields) {
      $select_string .= '<option value="' . $zones['geo_zone_id'] . '"';
      if ($selected == $zones['geo_zone_id']) $select_string .= ' selected="selected"';
      $select_string .= '>' . $zones['geo_zone_name'] . '</option>';

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    $select_string .= '</select>';

    return $select_string;
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
        $tax_ratestable = $oostable['tax_rates'];
        $dbconn->Execute("INSERT INTO $tax_ratestable (tax_zone_id, tax_class_id, tax_rate, tax_description, date_added) VALUES ('" . oos_db_input($tax_zone_id) . "', '" . oos_db_input($tax_class_id) . "', '" . oos_db_input($tax_rate) . "', '" . oos_db_input($tax_description) . "', now())");
        oos_redirect_admin(oos_href_link_admin($aFilename['tax_rates']));
        break;

      case 'save':
        $tax_rates_id = oos_db_prepare_input($_GET['tID']);

        $tax_ratestable = $oostable['tax_rates'];
        $dbconn->Execute("UPDATE $tax_ratestable SET tax_rates_id = '" . oos_db_input($tax_rates_id) . "', tax_zone_id = '" . oos_db_input($tax_zone_id) . "', tax_class_id = '" . oos_db_input($tax_class_id) . "', tax_rate = '" . oos_db_input($tax_rate) . "', tax_description = '" . oos_db_input($tax_description) . "', tax_priority = '" . oos_db_input($tax_priority) . "', last_modified = now() WHERE tax_rates_id = '" . oos_db_input($tax_rates_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $tax_rates_id));
        break;

      case 'deleteconfirm':
        $tax_rates_id = oos_db_prepare_input($_GET['tID']);

        $tax_ratestable = $oostable['tax_rates'];
        $dbconn->Execute("DELETE FROM $tax_ratestable WHERE tax_rates_id = '" . oos_db_input($tax_rates_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page']));
        break;
    }
  }
  require 'includes/oos_header.php'; 
?>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_RATE_PRIORITY; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_CLASS_TITLE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ZONE; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_TAX_RATE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $tax_classtable = $oostable['tax_class'];
  $tax_ratestable = $oostable['tax_rates'];
  $geo_zonestable = $oostable['geo_zones'];
  $rates_result_raw = "SELECT r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id,
                              r.tax_priority, r.tax_rate, r.tax_description, r.date_added, r.last_modified
                      FROM $tax_classtable tc,
                           $tax_ratestable r LEFT JOIN
                           $geo_zonestable z
                       ON r.tax_zone_id = z.geo_zone_id
                      WHERE r.tax_class_id = tc.tax_class_id";
  $rates_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $rates_result_raw, $rates_result_numrows);
  $rates_result = $dbconn->Execute($rates_result_raw);
  while ($rates = $rates_result->fields) {
    if ((!isset($_GET['tID']) || (isset($_GET['tID']) && ($_GET['tID'] == $rates['tax_rates_id']))) && !isset($trInfo) && (substr($action, 0, 3) != 'new')) {
      $trInfo = new objectInfo($rates);
    }

    if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id'] == $trInfo->tax_rates_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $rates['tax_rates_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $rates['tax_priority']; ?></td>
                <td class="dataTableContent"><?php echo $rates['tax_class_title']; ?></td>
                <td class="dataTableContent"><?php echo $rates['geo_zone_name']; ?></td>
                <td class="dataTableContent"><?php echo oos_display_tax_value($rates['tax_rate']); ?>%</td>
                <td class="dataTableContent" align="right"><?php if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id'] == $trInfo->tax_rates_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $rates['tax_rates_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $rates_result->MoveNext();
  }

  // Close result set
  $rates_result->Close();
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $rates_split->display_count($rates_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_TAX_RATES); ?></td>
                    <td class="smallText" align="right"><?php echo $rates_split->display_links($rates_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="5" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_tex_rate','new_tax_rate_off.gif', IMAGE_NEW_TAX_RATE) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TAX_RATE . '</b>');

      $contents = array('form' => oos_draw_form('rates', $aFilename['tax_rates'], 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_CLASS_TITLE . '<br />' . oosTaxClassesPullDown('name="tax_class_id" style="font-size:10px"'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONE_NAME . '<br />' . oosGeoZonesPullDown('name="tax_zone_id" style="font-size:10px"'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE . '<br />' . oos_draw_input_field('tax_rate'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . oos_draw_input_field('tax_description'));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TAX_RATE . '</b>');

      $contents = array('form' => oos_draw_form('rates', $aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_CLASS_TITLE . '<br />' . oosTaxClassesPullDown('name="tax_class_id" style="font-size:10px"', $trInfo->tax_class_id));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONE_NAME . '<br />' . oosGeoZonesPullDown('name="tax_zone_id" style="font-size:10px"', $trInfo->geo_zone_id));
      $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE . '<br />' . oos_draw_input_field('tax_rate', $trInfo->tax_rate));
      $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . oos_draw_input_field('tax_description', $trInfo->tax_description));
      $contents[] = array('text' => '<br />' . TEXT_INFO_TAX_RATE_PRIORITY . '<br />' . oos_draw_input_field('tax_priority', $trInfo->tax_priority));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TAX_RATE . '</b>');

      $contents = array('form' => oos_draw_form('rates', $aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $trInfo->tax_class_title . ' ' . number_format($trInfo->tax_rate, TAX_DECIMAL_PLACES) . '%</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($trInfo) && is_object($trInfo)) {
        $heading[] = array('text' => '<b>' . $trInfo->tax_class_title . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['tax_rates'], 'page=' . $_GET['page'] . '&tID=' . $trInfo->tax_rates_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($trInfo->date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($trInfo->last_modified));
        $contents[] = array('text' => '<br />' . TEXT_INFO_RATE_DESCRIPTION . '<br />' . $trInfo->tax_description);
      }
      break;
  }

  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
?>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>