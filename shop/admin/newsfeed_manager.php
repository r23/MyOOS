<?php
/* ----------------------------------------------------------------------
   $Id: newsfeed_manager.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

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

/**
 * News Feed Manager
 * @link http://www.oos-shop.de/
 * @package Newsfeed
 * @author r23 <info@r23.de>
 * @copyright 2003 r23
 * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 17:14:41 $
 */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $newsfeed_categories = array();
  $newsfeed_categories_array = array();
  $newsfeed_categories_result = $dbconn->Execute("SELECT newsfeed_categories_id, newsfeed_categories_name FROM " . $oostable['newsfeed_categories'] . " WHERE newsfeed_categories_languages_id = '" . intval($_SESSION['language_id']) . "'");
  while ($newsfeed_categories_info = $newsfeed_categories_result->fields) {
    $newsfeed_categories[] = array('id' => $newsfeed_categories_info['newsfeed_categories_id'],
                                   'text' => $newsfeed_categories_info['newsfeed_categories_name']);
    $newsfeed_categories_array[$newsfeed_categories_info['newsfeed_categories_id']] = $newsfeed_categories_info['newsfeed_categories_name'];

    // Move that ADOdb pointer!
    $newsfeed_categories_result->MoveNext();
  }

  $languages_array = array();
  $languages_result = $dbconn->Execute("SELECT languages_id, name FROM " . $oostable['languages'] . " WHERE status = '1' ORDER BY sort_order");
  while ($languages_info = $languages_result->fields) {
    $languages_array[] = array('id' => $languages_info['languages_id'],
                               'text' => $languages_info['name']);

    // Move that ADOdb pointer!
    $languages_result->MoveNext();
  }


  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) { 
      case 'setflag':
        $nmID = oos_db_prepare_input($_GET['nmID']);

        if ($_GET['statusflag'] == '0') {
          $dbconn->Execute("UPDATE " . $oostable['newsfeed_manager'] . " 
                        SET newsfeed_manager_status = '0' 
                        WHERE newsfeed_manager_id = '" . oos_db_input($nmID) . "'");
        } elseif ($_GET['statusflag'] == '1') {
          $dbconn->Execute("UPDATE " . $oostable['newsfeed_manager'] . " 
                        SET newsfeed_manager_status = '1' 
                        WHERE newsfeed_manager_id = '" . oos_db_input($nmID) . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['newsfeed_manager'], 'selected_box=newsfeed_manager&page=' . $_GET['page'] . '&nmID=' . $_GET['nmID']));
        break;

      case 'statusconfirm':
        $newsfeed_manager_id = oos_db_prepare_input($_GET['nmID']);
        $customer_updated = false;
        $check_status_result = $dbconn->Execute("SELECT newsfeed_manager_firstname, newsfeed_manager_lastname, newsfeed_manager_email_address , newsfeed_manager_status FROM " . $oostable['newsfeed_manager'] . " WHERE newsfeed_manager_id = '" . oos_db_input($nmID) . "'");
        $check_status = $check_status_result->fields;
        if ($check_status['newsfeed_manager_status'] != $status) {
          $dbconn->Execute("UPDATE " . $oostable['newsfeed_manager'] . " SET newsfeed_manager_status = '" . oos_db_input($status) . "' WHERE newsfeed_manager_id = '" . oos_db_input($nmID) . "'");
          $dbconn->Execute("INSERT INTO " . $oostable['newsfeed_manager_status_history'] . " (newsfeed_manager_id, new_value, old_value, date_added, customer_notified) values ('" . oos_db_input($nmID) . "', '" . oos_db_input($status) . "', '" . $check_status['newsfeed_manager_status'] . "', now(), '" . $customer_notified . "')");
          $customer_updated = true;
        }
        break;

      case 'insert':
      case 'update':
        $newsfeed_manager_id = oos_db_prepare_input($_GET['nmID']);

        $sql_data_array = array('newsfeed_categories_id' => $newsfeed_categories_id, 
                                'newsfeed_manager_name' => $newsfeed_manager_name,
                                'newsfeed_manager_link' => $newsfeed_manager_link,
                                'newsfeed_manager_languages_id' => $newsfeed_manager_languages_id,
                                'newsfeed_manager_numarticles' => $newsfeed_manager_numarticles,
                                'newsfeed_manager_refresh' => $newsfeed_manager_refresh);

        if ($action == 'insert') {
          $insert_sql_data = array('newsfeed_manager_date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          oos_db_perform($oostable['newsfeed_manager'], $sql_data_array);
        } elseif ($action == 'update') {
          $update_sql_data = array('newsfeed_manager_last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          oos_db_perform($oostable['newsfeed_manager'], $sql_data_array, 'update', "newsfeed_manager_id = '" . oos_db_input($newsfeed_manager_id) . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $newsfeed_manager_id));
        break;
      case 'deleteconfirm':
        $newsfeed_manager_id = oos_db_prepare_input($_GET['nmID']);
        $dbconn->Execute("DELETE FROM " . $oostable['newsfeed_manager'] . " WHERE newsfeed_manager_id = '" . oos_db_input($newsfeed_manager_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')))); 
        break;
    }
  }
  require 'includes/oos_header.php';

?>
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=200,height=680,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
  if ($action == 'new' || $action == 'edit') {
    if (isset($_GET['nmID'])) {
      $newsfeed_manager_result = $dbconn->Execute("SELECT 
                                                  newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name,
                                                  newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles,
                                                  newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added,
                                                  newsfeed_manager_last_modified, newsfeed_manager_sort_order
                                              FROM 
                                                  " . $oostable['newsfeed_manager'] . " 
                                              WHERE 
                                                  newsfeed_manager_id = '" . $_GET['nmID'] . "'");
      $newsfeed_manager = $newsfeed_manager_result->fields;
      $nmInfo = new objectInfo($newsfeed_manager);
    } else {
      $nmInfo = new objectInfo(array());
    }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE . ' : ' . $nmInfo->newsfeed_manager_name; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
<?php 
    $form_action = ($_GET['nmID']) ? 'update' : 'insert';

    echo oos_draw_form($form_action, $aFilename['newsfeed_manager'], oos_get_all_get_params(array('action')) . 'action='. $form_action, 'post') . oos_draw_hidden_field('newsfeed_manager_status', $nmInfo->newsfeed_manager_status); 
?>
         <td><table border="0" cellspacing="0" cellpadding="2">
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
             <tr>
              <tr>
                <td class="main"><?php echo TEXT_EDIT_NAME; ?></td>
                <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('newsfeed_manager_name', $nmInfo->newsfeed_manager_name, 'size="41"'); ?></td>
             </tr>
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
             <tr>
               <td class="main"><?php echo TEXT_EDIT_URL; ?></td>
               <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('newsfeed_manager_link', $nmInfo->newsfeed_manager_link, 'size="41"'); ?></td>
             </tr>
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
              <tr>
                <td class="main"><?php echo TEXT_EDIT_CATEGORIES; ?></td>
                <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('newsfeed_categories_id', $newsfeed_categories, $nmInfo->newsfeed_categories_id); ?></td>
             </tr>
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
             <tr>
               <td class="main"><?php echo TEXT_EDIT_LANGUAGES; ?></td>
               <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('newsfeed_manager_languages_id', $languages_array, $nmInfo->newsfeed_manager_languages_id); ?></td>
             </tr>
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
              <tr>
                <td class="main"><?php echo TEXT_EDIT_ARTICLES; ?></td>
                <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('newsfeed_manager_numarticles', $nmInfo->newsfeed_manager_numarticles, 'size="1"'); ?></td>
             </tr>
             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
             <tr>
               <td class="main"><?php echo TEXT_EDIT_CACHE_TIME; ?></td>
               <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('newsfeed_manager_refresh', $nmInfo->newsfeed_manager_refresh, 'size="4"'); ?></td>
             </tr>

             <tr>
               <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
             </tr>
           </table></td>
         </tr>
         <tr>
           <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
        </tr>
      <tr>
        <td align="right" class="main">
<?php 
      if (isset($_GET['nmID'])) {
        echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE);
      } else {
        echo oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT);
      }
      echo ' <a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('action'))) .'">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; 
?></td>
      </tr></form>
<?php
  } else {
?>
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo oos_draw_form('categories', $aFilename['newsfeed_manager'], '', 'get'); ?>
              <td class="smallText" align="right"><?php echo HEADING_TITLE_SHOW . ' ' . oos_draw_pull_down_menu('categories', array_merge(array(array('id' => '', 'text' => TEXT_ALL_CATEGORIES)), $newsfeed_categories), '', 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>
      </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PUBLISHED; ?></td>
                <td class="dataTableHeadingContent" align="left"><?php echo TABLE_HEADING_ARTICLES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($_GET['categories'])) {
      $categories = oos_db_prepare_input($_GET['categories']);
      $search ="WHERE newsfeed_categories_id = '". $categories . "'";
    }
    $newsfeed_manager_result_raw = "SELECT 
                                       newsfeed_manager_id, newsfeed_categories_id, newsfeed_manager_name,
                                       newsfeed_manager_link, newsfeed_manager_languages_id, newsfeed_manager_numarticles,
                                       newsfeed_manager_refresh, newsfeed_manager_status, newsfeed_manager_date_added,
                                       newsfeed_manager_last_modified, newsfeed_manager_sort_order
                                   FROM 
                                      " . $oostable['newsfeed_manager'] . " 
                                      " . $search . " 
                                   ORDER BY
                                      newsfeed_manager_name";
    $newsfeed_manager_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsfeed_manager_result_raw, $newsfeed_manager_result_numrows);
    $newsfeed_manager_result = $dbconn->Execute($newsfeed_manager_result_raw);
    while ($newsfeed_manager = $newsfeed_manager_result->fields) {
      if ((!isset($_GET['nmID']) || (isset($_GET['nmID']) && ($_GET['nmID'] == $newsfeed_manager['newsfeed_manager_id']))) && !isset($nmInfo) && (substr($action, 0, 3) != 'new')) {
        $nmInfo = new objectInfo($newsfeed_manager);
      }

      if (isset($nmInfo) && is_object($nmInfo) && ($newsfeed_manager['newsfeed_manager_id'] == $nmInfo->newsfeed_manager_id) ) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $nmInfo->newsfeed_manager_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID')) . 'nmID=' . $newsfeed_manager['newsfeed_manager_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><a href="javascript:popupImageWindow('<?php echo oos_href_link_admin($aFilename['newsfeed_view'], 'nmID=' . $newsfeed_manager['newsfeed_manager_id']); ?>')"><?php echo oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $orders['customers_name']; ?></td>
                <td class="dataTableContent"><?php echo $newsfeed_manager['newsfeed_manager_id']; ?></td>
                <td class="dataTableContent"><?php echo $newsfeed_manager['newsfeed_manager_name']; ?></td>
                <td class="dataTableContent"><?php echo $newsfeed_categories_array[$newsfeed_manager['newsfeed_categories_id']]; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if ($newsfeed_manager['newsfeed_manager_status'] == '1') {
        echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], 'selected_box=newsfeed_manager&page=' . $_GET['page'] . '&action=setflag&statusflag=0&nmID=' . $newsfeed_manager['newsfeed_manager_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], 'selected_box=newsfeed_manager&page=' . $_GET['page'] . '&action=setflag&statusflag=1&nmID=' . $newsfeed_manager['newsfeed_manager_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
      }
?></td>

                <td class="dataTableContent" align="left"><?php echo $newsfeed_manager['newsfeed_manager_numarticles']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nmInfo) && is_object($nmInfo) && ($newsfeed_manager['newsfeed_manager_id'] == $nmInfo->newsfeed_manager_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID')) . 'nmID=' . $newsfeed_manager['newsfeed_manager_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $newsfeed_manager_result->MoveNext();
    }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsfeed_manager_split->display_count($newsfeed_manager_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_RSS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsfeed_manager_split->display_links($newsfeed_manager_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'info', 'x', 'y', 'nmID'))); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('insert','insert_off.gif', IMAGE_INSERT) . '</a>'; ?></td>
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
    case 'confirm':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_NEWSFEED . '</b>');

      $contents = array('form' => oos_draw_form('newsfeed_manager', $aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $nmInfo->newsfeed_manager_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO . '<br /><br /><b>' . $nmInfo->newsfeed_manager_firstname . ' ' . $nmInfo->newsfeed_manager_lastname . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $nmInfo->newsfeed_manager_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($nmInfo) && is_object($nmInfo)) {
        $heading[] = array('text' => '<b>' . $nmInfo->newsfeed_manager_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $nmInfo->newsfeed_manager_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['newsfeed_manager'], oos_get_all_get_params(array('nmID', 'action')) . 'nmID=' . $nmInfo->newsfeed_manager_id . '&action=confirm') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>'); 
        $contents[] = array('text' => '<br />' . TEXT_INFO_URL . ' ' . $nmInfo->newsfeed_manager_link);
        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($nmInfo->newsfeed_manager_date_added));
        $contents[] = array('text' => '<br />' . TEXT_LAST_MODIFIED . ' ' . oos_date_short($nmInfo->newsfeed_manager_last_modified));
        $contents[] = array('text' => '<br />' . TEXT_CACHE_TIME . ' '  . $nmInfo->newsfeed_manager_refresh);
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
<?php
  }
?>
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