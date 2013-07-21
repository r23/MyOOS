<?php
/* ----------------------------------------------------------------------
   $Id: admin_files.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_files.php,v 1.29 2002/03/17 17:52:23 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $current_boxes = OOS_ABSOLUTE_PATH . 'admin/includes/boxes/';
  $current_files = OOS_ABSOLUTE_PATH . 'admin/';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'box_store':
        $sql_data_array = array('admin_files_name' => oos_db_prepare_input($_GET['box']),
                                'admin_files_is_boxes' => '1');
        oos_db_perform($oostable['admin_files'], $sql_data_array);
        $admin_boxes_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aFilename['admin_files'], 'cID=' . $admin_boxes_id));
        break;

      case 'box_remove':
        // NOTE: ALSO DELETE FILES STORED IN REMOVED BOX //
        $admin_boxes_id = oos_db_prepare_input($_GET['cID']);
        $admin_filestable = $oostable['admin_files'];
        $query = "DELETE FROM " . $admin_filestable . " WHERE admin_files_id = '" . $admin_boxes_id . "' or admin_files_to_boxes = '" . $admin_boxes_id . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aFilename['admin_files']));
        break;

      case 'file_store':
        $sql_data_array = array('admin_files_name' => oos_db_prepare_input($_POST['admin_files_name']),
                                'admin_files_to_boxes' => oos_db_prepare_input($_POST['admin_files_to_boxes']));
        oos_db_perform($oostable['admin_files'], $sql_data_array);
        $admin_files_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $admin_files_id));
        break;

      case 'file_remove':
        $admin_files_id = oos_db_prepare_input($_POST['admin_files_id']);
        $admin_filestable = $oostable['admin_files'];
        $query = "DELETE FROM " . $admin_filestable . " WHERE admin_files_id = '" . $admin_files_id . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath']));
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
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
<?php
  if ($_GET['fID'] || $_GET['cPath']) {
    $admin_filestable = $oostable['admin_files'];
    $current_box_query = "SELECT admin_files_name as admin_box_name 
                          FROM $admin_filestable
                          WHERE admin_files_id = " . $_GET['cPath'];
    $current_box = $dbconn->GetRow($current_box_query);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILENAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $admin_filestable = $oostable['admin_files'];
    $db_file_result_raw = "SELECT admin_files_id, admin_files_name 
                           FROM $admin_filestable
                           WHERE admin_files_to_boxes = " . $_GET['cPath'] . " 
                           ORDER BY admin_files_name";
    $db_file_result = $dbconn->Execute($db_file_result_raw);
    $file_count = 0;

    while ($files = $db_file_result->fields) {
      $file_count++;

      if (((!$_GET['fID']) || ($_GET['fID'] == $files['admin_files_id'])) && (!$fInfo) ) {
        $fInfo = new objectInfo($files);
      }

      if (isset($fInfo) && is_object($fInfo) && ($files['admin_files_id'] == $fInfo->admin_files_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=edit_file') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $files['admin_files_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($fInfo) && is_object($fInfo) && ($files['admin_files_id'] == $fInfo->admin_files_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $db_file_result->MoveNext();
    }
    // Close result set
    $db_file_result->Close();
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_FILES . $file_count; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $_GET['cPath']) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>&nbsp<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . oos_image_swap_button('admin_files','admin_files_off.gif', IMAGE_INSERT_FILE) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php
   } else {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="60%"><?php echo TABLE_HEADING_BOXES; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $admin_filestable = $oostable['admin_files'];
    $installed_boxes_query = "SELECT admin_files_name AS admin_boxes_name
                              FROM $admin_filestable
                              WHERE admin_files_is_boxes = 1
                              ORDER BY admin_files_name";
    $installed_boxes_result = $dbconn->Execute($installed_boxes_query);

    $installed_boxes = array();
    while($db_boxes = $installed_boxes_result->fields) {
      $installed_boxes[] = $db_boxes['admin_boxes_name'];

      // Move that ADOdb pointer!
      $installed_boxes_result->MoveNext();
    }


    $none = 0;
    $boxes = array();
    $dir = dir(OOS_ABSOLUTE_PATH . 'admin/includes/boxes/');
    while ($boxes_file = $dir->read()) {
      if ( (substr("$boxes_file", -4) == '.php') && !(in_array($boxes_file, $installed_boxes))){
        $boxes[] = array('admin_boxes_name' => $boxes_file,
                         'admin_boxes_id' => 'b' . $none);
      } elseif ( (substr("$boxes_file", -4) == '.php') && (in_array($boxes_file, $installed_boxes))) {
        $db_boxes_id_query = "SELECT admin_files_id as admin_boxes_id FROM " . $oostable['admin_files'] . " WHERE admin_files_is_boxes = 1 and admin_files_name = '" . $boxes_file . "'";
        $db_boxes_id = $dbconn->GetRow($db_boxes_id_query);

        $boxes[] = array('admin_boxes_name' => $boxes_file,
                         'admin_boxes_id' => $db_boxes_id['admin_boxes_id']);
      }

      $none++;
    }
    $dir->close();
    sort($boxes);
    reset ($boxes);

    $boxnum = count($boxes);
    $i = 0;
    while ($i < $boxnum) {
      if (((!$_GET['cID']) || ($_GET['none'] == $boxes[$i]['admin_boxes_id']) || ($_GET['cID'] == $boxes[$i]['admin_boxes_id'])) && (!$cInfo) ) {
        $cInfo = new objectInfo($boxes[$i]);
      }
      if (isset($cInfo) && is_object($cInfo) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id) ) {
        if ( substr("$cInfo->admin_boxes_id", 0,1) == 'b') {
          echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
        } else {
          echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $boxes[$i]['admin_boxes_id'] . '&action=store_file') . '\'">' . "\n";
        }
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo oos_image(OOS_IMAGES . 'icons/folder.gif', ICON_FOLDER) . ' <b>' . ucfirst (substr_replace ($boxes[$i]['admin_boxes_name'], '' , -4)) . '</b>'; ?></td>
                <td class="dataTableContent" align="center">
<?php
      if (isset($cInfo) && is_object($cInfo) && ($_GET['cID'] == $boxes[$i]['admin_boxes_id'])) {
        if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
          echo oos_image(OOS_IMAGES . 'icon_status_red.gif', STATUS_BOX_NOT_INSTALLED, 10, 10) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id'] . '&box=' . $boxes[$i]['admin_boxes_name'] . '&action=box_store') . '">' . oos_image(OOS_IMAGES . 'icon_status_green_light.gif', STATUS_BOX_INSTALL, 10, 10) . '</a>';
        } else {
          echo '<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $_GET['cID'] . '&action=box_remove') . '">' . oos_image(OOS_IMAGES . 'icon_status_red_light.gif', STATUS_BOX_REMOVE, 10, 10) . '</a>&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green.gif', STATUS_BOX_INSTALLED, 10, 10);
        }
      } else {
        if (substr($boxes[$i]['admin_boxes_id'], 0,1) == 'b') {
          echo oos_image(OOS_IMAGES . 'icon_status_red.gif', '', 10, 10) . '&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green_light.gif', '', 10, 10) . '</a>';
        } else {
          echo oos_image(OOS_IMAGES . 'icon_status_red_light.gif', '', 10, 10) . '</a>&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green.gif', '', 10, 10);
        }
      }
?>
                </td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cID=' . $db_cat['admin_boxes_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
     $i++;
   }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php  echo TEXT_COUNT_BOXES . $boxnum; ?></td>
                    <td class="smallText" valign="top" align="right">&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php
  }
?>
            </td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'store_file':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_FILE . '</b>');

      $files_array = array();
      $admin_filestable = $oostable['admin_files'];
      $file_query = "SELECT admin_files_name FROM $admin_filestable WHERE admin_files_is_boxes = '0' ";
      $file_result = $dbconn->Execute($file_query);
      while ($fetch_files = $file_result->fields) {
        $files_array[] = $fetch_files['admin_files_name'];

        // Move that ADOdb pointer!
        $file_result->MoveNext();
      }

      // Close result set
      $file_result->Close();

      $file_dir = array();
      $dir = dir(OOS_ABSOLUTE_PATH . 'admin/');

      while ($file = $dir->read()) {
        if ((substr("$file", -4) == '.php') && $file != $aFilename['default'] && $file != $aFilename['login'] && $file != $aFilename['logoff'] && $file != $aFilename['forbiden'] && $file != $aFilename['popup_image'] && $file != $aFilename['password_forgotten'] && $file != $aFilename['admin_account'] && $file != 'invoice.php' && $file != 'packingslip.php') {
          $file_dir[] = substr($file, 0, -4);
        }
      }

      $result = $file_dir;
      if (count($files_array) > 0) {
        $result = array_values (array_diff($file_dir, $files_array));
      }

      sort ($result);
      reset ($result);
      $show = array();
      while (list ($key, $val) = each ($result)) {
        $show[] = array('id' => $val,
                        'text' => $val);
      }

      $contents = array('form' => oos_draw_form('store_file', $aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=file_store', 'post', 'enctype="multipart/form-data"')); 
      $contents[] = array('text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</b>');
      $contents[] = array('text' => TEXT_INFO_NEW_FILE_INTRO );
      $contents[] = array('align' => 'left', 'text' => '<br />&nbsp;' . oos_draw_pull_down_menu('admin_files_name', $show, $show)); 
      $contents[] = array('text' => oos_draw_hidden_field('admin_files_to_boxes', $_GET['cPath']));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');    
      break;

    case 'remove_file': 
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FILE . '</b>');

      $contents = array('form' => oos_draw_form('remove_file', $aFilename['admin_files'], 'action=file_remove&cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'], 'post', 'enctype="multipart/form-data"')); 
      $contents[] = array('text' => oos_draw_hidden_field('admin_files_id', $_GET['fID']));
      $contents[] = array('text' =>  sprintf(TEXT_INFO_DELETE_FILE_INTRO, $fInfo->admin_files_name, ucfirst(substr_replace ($current_box['admin_box_name'], '', -4))) );    
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('confirm','confirm_off.gif', IMAGE_CONFIRM) . ' <a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $_GET['fID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');    
      break;

    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFAULT_BOXES . $cInfo->admin_boxes_name . '</b>');
        if ( substr($cInfo->admin_boxes_id, 0,1) == 'b') {
          $contents[] = array('text' => '<b>' . $cInfo->admin_boxes_name . ' ' . TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED . '</b><br />&nbsp;');
          $contents[] = array('text' => TEXT_INFO_DEFAULT_BOXES_INTRO);
        } else {
          $contents = array('form' => oos_draw_form('newfile', $aFilename['admin_files'], 'cPath=' . $cInfo->admin_boxes_id . '&action=store_file', 'post', 'enctype="multipart/form-data"')); 
          $contents[] = array('align' => 'center', 'text' => oos_image_swap_submits('admin_files','admin_files_off.gif', IMAGE_INSERT_FILE) );
          $contents[] = array('text' => oos_draw_hidden_field('this_category', $cInfo->admin_boxes_id));
          $contents[] = array('text' => '<br />' . TEXT_INFO_DEFAULT_BOXES_INTRO);
        }
        $contents[] = array('text' => '<br />');
      }
      if (isset($fInfo) && is_object($fInfo)) {
        $heading[] = array('text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)) . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . oos_image_swap_button('admin_files','admin_files_off.gif', IMAGE_INSERT_FILE) . '</a> <a href="' . oos_href_link_admin($aFilename['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->admin_files_id . '&action=remove_file') . '">' . oos_image_swap_button('admin_remove','admin_remove_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DEFAULT_FILE_INTRO . ucfirst(substr_replace ($current_box['admin_box_name'], '', -4)));
      }
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
