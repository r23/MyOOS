<?php
/* ----------------------------------------------------------------------
   $Id: file_manager.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: file_manager.php,v 1.38 2002/11/22 14:45:47 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  function oos_get_file_permissions($mode) {
// determine type
    if ( ($mode & 0xC000) == 0xC000) { // unix domain socket
      $type = 's';
    } elseif ( ($mode & 0x4000) == 0x4000) { // directory
      $type = 'd';
    } elseif ( ($mode & 0xA000) == 0xA000) { // symbolic link
      $type = 'l';
    } elseif ( ($mode & 0x8000) == 0x8000) { // regular file
      $type = '-';
    } elseif ( ($mode & 0x6000) == 0x6000) { //bBlock special file
      $type = 'b';
    } elseif ( ($mode & 0x2000) == 0x2000) { // character special file
      $type = 'c';
    } elseif ( ($mode & 0x1000) == 0x1000) { // named pipe
      $type = 'p';
    } else { // unknown
      $type = '?';
    }

// determine permissions
    $owner['read']    = ($mode & 00400) ? 'r' : '-';
    $owner['write']   = ($mode & 00200) ? 'w' : '-';
    $owner['execute'] = ($mode & 00100) ? 'x' : '-';
    $group['read']    = ($mode & 00040) ? 'r' : '-';
    $group['write']   = ($mode & 00020) ? 'w' : '-';
    $group['execute'] = ($mode & 00010) ? 'x' : '-';
    $world['read']    = ($mode & 00004) ? 'r' : '-';
    $world['write']   = ($mode & 00002) ? 'w' : '-';
    $world['execute'] = ($mode & 00001) ? 'x' : '-';

// adjust for SUID, SGID and sticky bit
    if ($mode & 0x800 ) $owner['execute'] = ($owner['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x400 ) $group['execute'] = ($group['execute'] == 'x') ? 's' : 'S';
    if ($mode & 0x200 ) $world['execute'] = ($world['execute'] == 'x') ? 't' : 'T';

    return $type .
           $owner['read'] . $owner['write'] . $owner['execute'] .
           $group['read'] . $group['write'] . $group['execute'] .
           $world['read'] . $world['write'] . $world['execute'];
  }


  define('OOS_FILE_MANAGER_ROOT_PATH', realpath('../'));

  if (!isset($_SESSION['current_path'])) {
    $_SESSION['current_path'] = OOS_FILE_MANAGER_ROOT_PATH;
  }

  if (isset($_GET['goto'])) {
    $_SESSION['current_path'] = $_GET['goto'];
    oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));
  }

  if (strstr($_SESSION['current_path'], '..')) $_SESSION['current_path'] = OOS_FILE_MANAGER_ROOT_PATH;

  if (!is_dir($_SESSION['current_path'])) $_SESSION['current_path'] = OOS_FILE_MANAGER_ROOT_PATH;

  if (!ereg('^' . OOS_FILE_MANAGER_ROOT_PATH, $_SESSION['current_path'])) $_SESSION['current_path'] = OOS_FILE_MANAGER_ROOT_PATH;

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {  
      case 'reset':
        unset($_SESSION['current_path']);
        oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));
        break;

      case 'deleteconfirm':
        if (strstr($_GET['info'], '..')) oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));

        oos_remove($_SESSION['current_path'] . '/' . $_GET['info']);
        if (!$oos_remove_error) oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));
        break;

      case 'insert':
        if (mkdir($_SESSION['current_path'] . '/' . $_POST['folder_name'], 0777)) {
          oos_redirect_admin(oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_POST['folder_name'])));
        }
        break;

      case 'save':
        if ($fp = fopen($_SESSION['current_path'] . '/' . $_POST['filename'], 'w+')) {
          fputs($fp, stripslashes($_POST['file_contents']));
          fclose($fp);
          oos_redirect_admin(oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_POST['filename'])));
        }
        break;

      case 'processuploads':
        $_current_path = oos_get_local_path($_SESSION['current_path']);

        if (!is_writeable($_current_path)) {
          if (is_dir($_current_path)) {
            $messageStack->add_session(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $_current_path), 'error');
          } else {
            $messageStack->add_session(sprintf(ERROR_DIRECTORY_DOES_NOT_EXIST, $_current_path), 'error');
          }
        } else {
          for ($i=1; $i<6; $i++) {
            $file = oos_get_uploaded_file('file_' . $i);

            if (is_uploaded_file($file['tmp_name'])) {
              oos_get_copy_uploaded_file($file, $_current_path);
            }
          }
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));
        break;

      case 'download':
        header('Content-type: application/x-octet-stream');
        header('Content-disposition: attachment; filename=' . urldecode($_GET['filename']));
        readfile($_SESSION['current_path'] . '/' . urldecode($_GET['filename']));
        exit;
        break;

      case 'upload':
      case 'new_folder':
      case 'new_file':
        $directory_writeable = true;
        if (!is_writeable($_SESSION['current_path'])) {
          $directory_writeable = false;
          $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $_SESSION['current_path']), 'error');
        }
        break;

      case 'edit':
        if (strstr($_GET['info'], '..')) oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));

        $file_writeable = true;
        if (!is_writeable($_SESSION['current_path'] . '/' . $_GET['info'])) {
          $file_writeable = false;
          $messageStack->add(sprintf(ERROR_FILE_NOT_WRITEABLE, $_SESSION['current_path'] . '/' . $_GET['info']), 'error');
        }
        break;

      case 'delete':
        if (strstr($_GET['info'], '..')) oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));
        break;

    }
  }

  $in_directory = substr(substr(OOS_FILE_MANAGER_ROOT_PATH, strrpos(OOS_FILE_MANAGER_ROOT_PATH, '/')), 1);
  $current_path_array = explode('/', $_SESSION['current_path']);
  $document_root_array = explode('/', OOS_FILE_MANAGER_ROOT_PATH);
  $goto_array = array(array('id' => OOS_FILE_MANAGER_ROOT_PATH, 'text' => $in_directory));
  for ($i = 0, $n = count($current_path_array); $i < $n; $i++) {
    if ($current_path_array[$i] != $document_root_array[$i]) {
      $goto_array[] = array('id' => implode('/', array_slice($current_path_array, 0, $i+1)), 'text' => $current_path_array[$i]);
    }
  }
  $no_js_general = true;
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
          <tr><?php echo oos_draw_form('goto', $aFilename['file_manager'], '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE . '<br /><span class="smallText">' . $_SESSION['current_path'] . '</span>'; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', '1', HEADING_IMAGE_HEIGHT); ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_pull_down_menu('goto', $goto_array, $_SESSION['current_path'], 'onChange="this.form.submit();"'); ?></td>
          </form></tr>
        </table></td>
      </tr>
<?php
  if ( ($directory_writeable) && ($action == 'new_file') || ($action == 'edit') ) {
    if (strstr($_GET['info'], '..')) oos_redirect_admin(oos_href_link_admin($aFilename['file_manager']));

    if (!isset($file_writeable)) $file_writeable = true;
    $file_contents = '';
    if ($action == 'new_file') {
      $filename_input_field = oos_draw_input_field('filename');
    } elseif ($action == 'edit') {
      if ($file_array = file($_SESSION['current_path'] . '/' . $_GET['info'])) {
        $file_contents = htmlspecialchars(implode('', $file_array));
      }
      $filename_input_field = $_GET['info'] . oos_draw_hidden_field('filename', $_GET['info']);
    }
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('new_file', $aFilename['file_manager'], 'action=save'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_FILE_NAME; ?></td>
            <td class="main"><?php echo $filename_input_field; ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_FILE_CONTENTS; ?></td>
            <td class="main"><?php echo oos_draw_textarea_field('file_contents', 'soft', '80', '20', $file_contents, (($file_writeable) ? '' : 'readonly')); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td align="right" class="main" colspan="2"><?php if ($file_writeable) echo oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . '&nbsp;'; echo '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info'])) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
    $showuser = (function_exists('posix_getpwuid') ? true : false);
    $contents = array();
    $dir = dir($_SESSION['current_path']);
    while ($file = $dir->read()) {
      if ( ($file != '.') && ($file != 'CVS') && ( ($file != '..') || ($_SESSION['current_path'] != OOS_FILE_MANAGER_ROOT_PATH) ) ) {
        $file_size = number_format(filesize($_SESSION['current_path'] . '/' . $file)) . ' bytes';

        $permissions = oos_get_file_permissions(fileperms($_SESSION['current_path'] . '/' . $file));
        if ($showuser) {
          $user = @posix_getpwuid(fileowner($_SESSION['current_path'] . '/' . $file));
          $group = @posix_getgrgid(filegroup($_SESSION['current_path'] . '/' . $file));
        } else {
          $user = $group = array();
        }

        $contents[] = array('name' => $file,
                            'is_dir' => is_dir($_SESSION['current_path'] . '/' . $file),
                            'last_modified' => strftime(DATE_TIME_FORMAT, filemtime($_SESSION['current_path'] . '/' . $file)),
                            'size' => $file_size,
                            'permissions' => $permissions,
                            'user' => $user['name'],
                            'group' => $group['name']);
      }
    }

    function oosCmp($a, $b) {
      return strcmp( ($a['is_dir'] ? 'D' : 'F') . $a['name'], ($b['is_dir'] ? 'D' : 'F') . $b['name']);
    }
    usort($contents, 'oosCmp');
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FILENAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PERMISSIONS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_USER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_GROUP; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAST_MODIFIED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  for ($i = 0, $n = count($contents); $i < $n; $i++) {
    if ((!isset($_GET['info']) || (isset($_GET['info']) && ($_GET['info'] == $contents[$i]['name']))) && !isset($fInfo) && ($action != 'upload') && ($action != 'new_folder')) {
      $fInfo = new objectInfo($contents[$i]);
    }

    if ($contents[$i]['name'] == '..') {
      $goto_link = substr($_SESSION['current_path'], 0, strrpos($_SESSION['current_path'], '/'));
    } else {
      $goto_link = $_SESSION['current_path'] . '/' . $contents[$i]['name'];
    }

    if (isset($fInfo) && is_object($fInfo) && ($contents[$i]['name'] == $fInfo->name) ) {
      if ($fInfo->is_dir) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'">' . "\n";
        $onclick_link = 'goto=' . $goto_link;
      } else {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'">' . "\n";
        $onclick_link = 'info=' . urlencode($fInfo->name) . '&action=edit';
      }
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'">' . "\n";
      $onclick_link = 'info=' . urlencode($contents[$i]['name']);
    }

    if ($contents[$i]['is_dir']) {
      if ($contents[$i]['name'] == '..') {
        $icon = oos_image(OOS_IMAGES . 'icons/previous_level.gif', ICON_PREVIOUS_LEVEL);
      } else {
        $icon = ((is_object($fInfo)) && ($contents[$i]['name'] == $fInfo->name) ? oos_image(OOS_IMAGES . 'icons/current_folder.gif', ICON_CURRENT_FOLDER) : oos_image(OOS_IMAGES . 'icons/folder.gif', ICON_FOLDER));
      }
      $link = oos_href_link_admin($aFilename['file_manager'], 'goto=' . $goto_link);
    } else {
      $icon = oos_image(OOS_IMAGES . 'icons/file_download.gif', ICON_FILE_DOWNLOAD);
      $link = oos_href_link_admin($aFilename['file_manager'], 'action=download&filename=' . urlencode($contents[$i]['name']));
    }
?>
                <td class="dataTableContent" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><?php echo '<a href="' . $link . '">' . $icon . '</a>&nbsp;' . $contents[$i]['name']; ?></td>
                <td class="dataTableContent" align="right" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><?php echo ($contents[$i]['is_dir'] ? '&nbsp;' : $contents[$i]['size']); ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><tt><?php echo $contents[$i]['permissions']; ?></tt></td>
                <td class="dataTableContent" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><?php echo $contents[$i]['user']; ?></td>
                <td class="dataTableContent" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><?php echo $contents[$i]['group']; ?></td>
                <td class="dataTableContent" align="center" onclick="document.location.href='<?php echo oos_href_link_admin($aFilename['file_manager'], $onclick_link); ?>'"><?php echo $contents[$i]['last_modified']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($contents[$i]['name'] != '..') echo '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($contents[$i]['name']) . '&action=delete') . '">' . oos_image(OOS_IMAGES . 'icons/delete.gif', ICON_DELETE) . '</a>&nbsp;'; if (is_object($fInfo) && ($fInfo->name == $contents[$i]['name'])) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($contents[$i]['name'])) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
  }
?>
              <tr>
                <td colspan="7"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr valign="top">
                    <td class="smallText"><?php echo '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'action=reset') . '">' . oos_image_swap_button('reset','reset_off.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td class="smallText" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info']) . '&action=upload') . '">' . oos_image_swap_button('upload','upload_off.gif', IMAGE_UPLOAD) . '</a>&nbsp;<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info']) . '&action=new_file') . '">' . oos_image_swap_button('new_file','new_file_off.gif', IMAGE_NEW_FILE) . '</a>&nbsp;<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info']) . '&action=new_folder') . '">' . oos_image_swap_button('new_folder','new_folder_off.gif', IMAGE_NEW_FOLDER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'delete':
        $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

        $contents = array('form' => oos_draw_form('file', $aFilename['file_manager'], 'info=' . urlencode($fInfo->name) . '&action=deleteconfirm'));
        $contents[] = array('text' => TEXT_DELETE_INTRO);
        $contents[] = array('text' => '<br /><b>' . $fInfo->name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($fInfo->name)) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'new_folder':
        $heading[] = array('text' => '<b>' . TEXT_NEW_FOLDER . '</b>');

        $contents = array('form' => oos_draw_form('folder', $aFilename['file_manager'], 'action=insert'));
        $contents[] = array('text' => TEXT_NEW_FOLDER_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_FILE_NAME . '<br />' . oos_draw_input_field('folder_name'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . (($directory_writeable) ? oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) : '') . ' <a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info'])) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'upload':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_UPLOAD . '</b>');

        $contents = array('form' => oos_draw_form('file', $aFilename['file_manager'], 'action=processuploads', 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_UPLOAD_INTRO);
        for ($i=1; $i<6; $i++) $file_upload .= oos_draw_file_field('file_' . $i) . '<br />';
        $contents[] = array('text' => '<br />' . $file_upload);
        $contents[] = array('align' => 'center', 'text' => '<br />' . (($directory_writeable) ? oos_image_swap_submits('upload','upload_off.gif', IMAGE_UPLOAD) : '') . ' <a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($_GET['info'])) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if (isset($fInfo) && is_object($fInfo)) {
          $heading[] = array('text' => '<b>' . $fInfo->name . '</b>');

          if (!$fInfo->is_dir) $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['file_manager'], 'info=' . urlencode($fInfo->name) . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a>');
          $contents[] = array('text' => '<br />' . TEXT_FILE_NAME . ' <b>' . $fInfo->name . '</b>');
          if (!$fInfo->is_dir) $contents[] = array('text' => '<br />' . TEXT_FILE_SIZE . ' <b>' . $fInfo->size . '</b>');
          $contents[] = array('text' => '<br />' . TEXT_LAST_MODIFIED . ' ' . $fInfo->last_modified);
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