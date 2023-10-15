<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
require 'includes/main.php';

$current_boxes = OOS_ABSOLUTE_PATH . 'admin/includes/boxes/';
$current_files = OOS_ABSOLUTE_PATH . OOS_ADMIN;

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'box_store':
        $sql_data_array = ['admin_files_name' => oos_db_prepare_input($_GET['box']), 'admin_files_is_boxes' => '1'];
        oos_db_perform($oostable['admin_files'], $sql_data_array);
        $admin_boxes_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aContents['admin_files'], 'cID=' . $admin_boxes_id));
        break;

    case 'box_remove':
        // NOTE: ALSO DELETE FILES STORED IN REMOVED BOX //
		$admin_boxes_id = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT) ?: 0; 
        $admin_filestable = $oostable['admin_files'];
        $query = "DELETE FROM " . $admin_filestable . " WHERE admin_files_id = '" . intval($admin_boxes_id) . "' or admin_files_to_boxes = '" . intval($admin_boxes_id) . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_files']));
        break;

    case 'file_store':
        $sql_data_array = ['admin_files_name' => oos_db_prepare_input($_POST['admin_files_name']), 'admin_files_to_boxes' => oos_db_prepare_input($_POST['admin_files_to_boxes'])];
        oos_db_perform($oostable['admin_files'], $sql_data_array);
        $admin_files_id = $dbconn->Insert_ID();

        oos_redirect_admin(oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $admin_files_id));
        break;

    case 'file_remove':
        $admin_files_id = oos_db_prepare_input($_POST['admin_files_id']);
        $admin_filestable = $oostable['admin_files'];
        $query = "DELETE FROM " . $admin_filestable . " WHERE admin_files_id = '" . intval($admin_files_id) . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath']));
        break;
}

require 'includes/header.php';


?>
<div class="wrapper">
    <!-- Header //-->
    <header class="topnavbar-wrapper">
        <!-- Top Navbar //-->
        <?php require 'includes/menue.php'; ?>
    </header>
    <!-- END Header //-->
    <aside class="aside">
        <!-- Sidebar //-->
        <div class="aside-inner">
            <?php require 'includes/blocks.php'; ?>
        </div>
        <!-- END Sidebar (left) //-->
    </aside>
    
    <!-- Main section //-->
    <section>
        <!-- Page content //-->
        <div class="content-wrapper">
            
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['admin_account'], 'selected_box=administrator') . '">' . BOX_HEADING_ADMINISTRATOR . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->    
            
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">            
<!-- body_text //-->
    <div class="table-responsive">
        <table class="table w-100">
          <tr>
            <td valign="top">
<?php
if (isset($_GET['fID']) || isset($_GET['cPath'])) {
    $admin_filestable = $oostable['admin_files'];
    $current_box_query = "SELECT admin_files_name as admin_box_name 
                          FROM $admin_filestable
                          WHERE admin_files_id = " . intval($_GET['cPath']);
    $current_box = $dbconn->GetRow($current_box_query); ?>
        <table class="table table-striped table-hover w-100">
            <thead class="thead-dark">
                <tr>
                    <th><?php echo TABLE_HEADING_FILENAME; ?><th>
                    <th align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;<th>
                </tr>    
            </thead>
    <?php
    $admin_filestable = $oostable['admin_files'];
    $db_file_result_raw = "SELECT admin_files_id, admin_files_name 
                           FROM $admin_filestable
                           WHERE admin_files_to_boxes = " . oos_db_input($_GET['cPath']) . " 
                           ORDER BY admin_files_name";
    $db_file_result = $dbconn->Execute($db_file_result_raw);
    $file_count = 0;

    while ($files = $db_file_result->fields) {
        $file_count++;

        if (((!isset($_GET['fID'])) || ($_GET['fID'] == $files['admin_files_id'])) && !isset($fInfo)) {
            $fInfo = new objectInfo($files);
        }

        if (isset($fInfo) && is_object($fInfo) && ($files['admin_files_id'] == $fInfo->admin_files_id)) {
            echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id'] . '&action=edit_file') . '\'">' . "\n";
        } else {
            echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '\'">' . "\n";
        } ?>
                <td><?php echo $files['admin_files_name']; ?></td>
                <td class="text-right"><?php if (isset($fInfo) && is_object($fInfo) && ($files['admin_files_id'] == $fInfo->admin_files_id)) {
            echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $files['admin_files_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
        } ?>&nbsp;</td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $db_file_result->MoveNext();
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_FILES . $file_count; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_files'], 'cID=' . $_GET['cPath']) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>&nbsp<a href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . oos_button(BUTTON_INSERT_FILE) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
    <?php
} else {
        ?>
        <thead class="thead-dark">
            <thead class="thead-dark">
                <tr>
                    <th width="60%"><?php echo TABLE_HEADING_BOXES; ?></th>
                    <th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>    
            </thead>              
    <?php
    $admin_filestable = $oostable['admin_files'];
        $installed_boxes_query = "SELECT admin_files_name AS admin_boxes_name
                              FROM $admin_filestable
                              WHERE admin_files_is_boxes = 1
                              ORDER BY admin_files_name";
        $installed_boxes_result = $dbconn->Execute($installed_boxes_query);

        $installed_boxes = [];
        while ($db_boxes = $installed_boxes_result->fields) {
            $installed_boxes[] = $db_boxes['admin_boxes_name'];

            // Move that ADOdb pointer!
            $installed_boxes_result->MoveNext();
        }


        $none = 0;
        $boxes = [];
        $dir = dir(OOS_ABSOLUTE_PATH . 'admin/includes/boxes/');
        while ($boxes_file = $dir->read()) {
            if ((str_ends_with("$boxes_file", '.php')) && !(in_array($boxes_file, $installed_boxes))) {
                $boxes[] = ['admin_boxes_name' => $boxes_file, 'admin_boxes_id' => 'b' . $none];
            } elseif ((str_ends_with("$boxes_file", '.php')) && (in_array($boxes_file, $installed_boxes))) {
                $db_boxes_id_query = "SELECT admin_files_id AS admin_boxes_id FROM " . $oostable['admin_files'] . " WHERE admin_files_is_boxes = 1 AND admin_files_name = '" . oos_db_input($boxes_file) . "'";			
                $db_boxes_id = $dbconn->GetRow($db_boxes_id_query);

                $boxes[] = ['admin_boxes_name' => $boxes_file, 'admin_boxes_id' => $db_boxes_id['admin_boxes_id'] ?? ''];
            }

            $none++;
        }
        $dir->close();
        sort($boxes);
        reset($boxes);

        $boxnum = count($boxes);
        $i = 0;
        while ($i < $boxnum) {
            if ((!isset($_GET['cID']) || (isset($_GET['none']) &&  $_GET['none'] == $boxes[$i]['admin_boxes_id']) || ($_GET['cID'] == $boxes[$i]['admin_boxes_id'])) && !isset($cInfo)) {
                $cInfo = new objectInfo($boxes[$i]);
            }
		
            if (isset($cInfo) && is_object($cInfo) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id)) {
                if (str_starts_with("$cInfo->admin_boxes_id", 'b')) {
                    echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
                } else {
                    echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $boxes[$i]['admin_boxes_id'] . '&action=store_file') . '\'">' . "\n";
                }
            } else {
                echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id']) . '\'">' . "\n";
            } ?>
                <td><?php echo '<i class="fa fa-folder text-navy"></i> <b>' . ucfirst(substr_replace($boxes[$i]['admin_boxes_name'], '', -4)) . '</b>'; ?></td>
                <td class="text-center">
        <?php

        if (isset($cInfo) && is_object($cInfo) && (isset($_GET['cID']) && ($_GET['cID'] == $boxes[$i]['admin_boxes_id']))) {
            if (str_starts_with((string) $boxes[$i]['admin_boxes_id'], 'b')) {
                echo oos_image(OOS_IMAGES . 'icon_status_red.gif', STATUS_BOX_NOT_INSTALLED, 10) . '&nbsp;<a href="' . oos_href_link_admin($aContents['admin_files'], 'cID=' . $boxes[$i]['admin_boxes_id'] . '&box=' . $boxes[$i]['admin_boxes_name'] . '&action=box_store') . '">' . oos_image(OOS_IMAGES . 'icon_status_green_light.gif', STATUS_BOX_INSTALL, 10) . '</a>';
            } else {
                echo '<a href="' . oos_href_link_admin($aContents['admin_files'], 'cID=' . $_GET['cID'] . '&action=box_remove') . '">' . oos_image(OOS_IMAGES . 'icon_status_red_light.gif', STATUS_BOX_REMOVE, 10) . '</a>&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green.gif', STATUS_BOX_INSTALLED, 10);
            }
        } else {
            if (str_starts_with((string) $boxes[$i]['admin_boxes_id'], 'b')) {
                echo oos_image(OOS_IMAGES . 'icon_status_red.gif', '', 10) . '&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green_light.gif', '', 10) . '</a>';
            } else {
                echo oos_image(OOS_IMAGES . 'icon_status_red_light.gif', '', 10) . '</a>&nbsp;' . oos_image(OOS_IMAGES . 'icon_status_green.gif', '', 10);
            }
        } ?>
                </td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($boxes[$i]['admin_boxes_id'] == $cInfo->admin_boxes_id)) {
            echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['admin_files'], 'cID=' . isset($boxes[$i]['admin_boxes_id']) ? $boxes[$i]['admin_boxes_id'] : '') . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
        } ?>&nbsp;</td>
              </tr>
        <?php
        $i++;
        } ?>
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
  $heading = [];
  $contents = [];

  switch ($action) {
case 'store_file':
    $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_FILE . '</b>'];

    $files_array = [];
    $admin_filestable = $oostable['admin_files'];
    $file_query = "SELECT admin_files_name FROM $admin_filestable WHERE admin_files_is_boxes = '0' ";
    $file_result = $dbconn->Execute($file_query);
    while ($fetch_files = $file_result->fields) {
        $files_array[] = $fetch_files['admin_files_name'];

        // Move that ADOdb pointer!
        $file_result->MoveNext();
    }

        $file_dir = [];
        $dir = dir(OOS_ABSOLUTE_PATH . OOS_ADMIN);

    while ($file = $dir->read()) {
        if ((str_ends_with("$file", '.php')) && $file != $aContents['default'] && $file != $aContents['login'] && $file != $aContents['logoff'] && $file != $aContents['forbiden'] && $file != $aContents['password_forgotten'] && $file != $aContents['admin_account'] && $file != 'invoice.php' && $file != 'packingslip.php') {
            $file_dir[] = substr($file, 0, -4);
        }
    }

        $result = $file_dir;
    if (count($files_array) > 0) {
        $result = array_values(array_diff($file_dir, $files_array));
    }

        sort($result);
        reset($result);
        $show = [];
    foreach ($result as $key => $val) {
        $show[] = ['id' => $val, 'text' => $val];
    }
	
		$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : '');	
		$admin_files_id = (isset($files['admin_files_id']) ? oos_prepare_input($files['admin_files_id']) : '');
		
        $contents = ['form' => oos_draw_form('id', 'store_file', $aContents['admin_files'], '&cPath=' . $cPath . '&admin_files_id=' . $admin_files_id . '&action=file_store', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace(($current_box['admin_box_name'] ?? ''), '', -4)) . '</b>'];
        $contents[] = ['text' => TEXT_INFO_NEW_FILE_INTRO];
        $contents[] = ['align' => 'left', 'text' => '<br>&nbsp;' . oos_draw_pull_down_menu('admin_files_name', $show, $show)];
        $contents[] = ['text' => oos_draw_hidden_field('admin_files_to_boxes', $_GET['cPath'])];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_SAVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

    break;

case 'remove_file':
    $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_FILE . '</b>'];

	$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : '');	
	$admin_files_id = (isset($files['admin_files_id']) ? oos_prepare_input($files['admin_files_id']) : '');


    $contents = ['form' => oos_draw_form('id', 'remove_file', $aContents['admin_files'], 'action=file_remove&cPath=' .$cPath . '&admin_files_id=' . $admin_files_id, 'post', false, 'enctype="multipart/form-data"')];
    $contents[] = ['text' => oos_draw_hidden_field('admin_files_id', $_GET['fID'])];
    $contents[] = ['text' =>  sprintf(TEXT_INFO_DELETE_FILE_INTRO, $fInfo->admin_files_name, ucfirst(substr_replace((string) $current_box['admin_box_name'], '', -4)))];
    $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_CONFIRM) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $_GET['fID']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

    break;

default:
    if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DEFAULT_BOXES . $cInfo->admin_boxes_name . '</b>'];
        if (str_starts_with((string) $cInfo->admin_boxes_id, 'b')) {
            $contents[] = ['text' => '<b>' . $cInfo->admin_boxes_name . ' ' . TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED . '</b><br>&nbsp;'];
            $contents[] = ['text' => TEXT_INFO_DEFAULT_BOXES_INTRO];
        } else {
            $contents = ['form' => oos_draw_form('id', 'newfile', $aContents['admin_files'], 'cPath=' . $cInfo->admin_boxes_id . '&action=store_file', 'post', false, 'enctype="multipart/form-data"')];
            $contents[] = ['align' => 'center', 'text' => oos_submit_button(BUTTON_INSERT_FILE)];
            $contents[] = ['text' => oos_draw_hidden_field('this_category', $cInfo->admin_boxes_id)];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DEFAULT_BOXES_INTRO];
        }
        $contents[] = ['text' => '<br>'];
    }
    if (isset($fInfo) && is_object($fInfo)) {
        $heading[] = ['text' => '<b>' . TEXT_INFO_NEW_FILE_BOX .  ucfirst(substr_replace((string) $current_box['admin_box_name'], '', -4)) . '</b>'];

        $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&action=store_file') . '">' . oos_button(BUTTON_INSERT_FILE) . '</a> <a href="' . oos_href_link_admin($aContents['admin_files'], 'cPath=' . $_GET['cPath'] . '&fID=' . $fInfo->admin_files_id . '&action=remove_file') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
        $contents[] = ['text' => '<br>' . TEXT_INFO_DEFAULT_FILE_INTRO . ucfirst(substr_replace((string) $current_box['admin_box_name'], '', -4))];
    }
  }

  if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
      ?>
    <td class="w-25" valign="top">
        <table class="table table-striped">
      <?php
        $box = new box();
      echo $box->infoBox($heading, $contents); ?>
        </table> 
    </td> 
      <?php
  }
    ?>
          </tr>
        </table>
    </div>
<!-- body_text_eof //-->
                </div>
            </div>
        </div>

        </div>
    </section>
    <!-- Page footer //-->
    <footer>
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>


<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>
