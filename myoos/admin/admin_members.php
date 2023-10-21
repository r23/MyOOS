<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_members.php,v 1.29 2002/03/17 17:52:23 harley_vb
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

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$mID = filter_input(INPUT_GET, 'mID', FILTER_VALIDATE_INT);
$gID = filter_input(INPUT_GET, 'gID', FILTER_VALIDATE_INT);


switch ($action) {
    case 'member_new':
        $admintable = $oostable['admin'];
        $check_email_query = "SELECT admin_email_address FROM $admintable";
        $check_email_result = $dbconn->Execute($check_email_query);
        while ($check_email = $check_email_result->fields) {
            $stored_email[] = $check_email['admin_email_address'];

            // Move that ADOdb pointer!
            $check_email_result->MoveNext();
        }

        if (in_array($_POST['admin_email_address'], $stored_email)) {
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . 'mID=' . $mID . '&error=email&action=new_member'));
        } else {
            $newpass = oos_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
            $crypted_password = oos_encrypt_password($newpass);

            $sql_data_array = ['admin_groups_id' => oos_db_prepare_input($_POST['admin_groups_id']), 'admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']), 'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']), 'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']), 'admin_password' => $crypted_password, 'admin_created' => 'now()'];

            oos_db_perform($oostable['admin'], $sql_data_array);
            $admin_id = $dbconn->Insert_ID();

            $email_text = sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname']), OOS_HTTPS_SERVER . OOS_SHOP . OOS_ADMIN, $_POST['admin_email_address'], $newpass, STORE_OWNER);
            $email_html = $email_text;

            oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, $email_text, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin_id));
        }

        break;

    case 'member_edit':
        $admin_id = oos_db_prepare_input($_POST['admin_id']);
        $hiddenPassword = '-hidden-';
        $stored_email[] = 'NONE';

        $admintable = $oostable['admin'];
        $check_email_query = "SELECT admin_email_address FROM $admintable WHERE admin_id <> " . $admin_id . "";
        $check_email_result = $dbconn->Execute($check_email_query);
        while ($check_email = $check_email_result->fields) {
            $stored_email[] = $check_email['admin_email_address'];

            // Move that ADOdb pointer!
            $check_email_result->MoveNext();
        }

        if (in_array($_POST['admin_email_address'], $stored_email)) {
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . 'mID=' . $mID . '&error=email&action=edit_member'));
        } else {
            $sql_data_array = ['admin_groups_id' => oos_db_prepare_input($_POST['admin_groups_id']), 'admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']), 'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']), 'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']), 'admin_modified' => 'now()'];

            oos_db_perform($oostable['admin'], $sql_data_array, 'UPDATE', 'admin_id = \'' . $admin_id . '\'');

            $email_text = sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname']), OOS_HTTPS_SERVER . OOS_SHOP . OOS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER);
            $email_html = $email_text;

            oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, $email_text, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin_id));
        }
        break;

    case 'member_delete':
        $admin_id = oos_db_prepare_input($_POST['admin_id']);
        $query = "DELETE FROM ". $oostable['admin'] . " WHERE admin_id = '" . intval($admin_id) . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage));
        break;

    case 'group_define':
        $selected_checkbox = oos_db_prepare_input($_POST['groups_to_boxes']);

        $admin_filestable = $oostable['admin_files'];
        $define_files_query = "SELECT admin_files_id FROM $admin_filestable ORDER BY admin_files_id";
        $define_files_result = $dbconn->Execute($define_files_query);
        while ($define_files = $define_files_result->fields) {
            $admin_files_id = $define_files['admin_files_id'];

            if (in_array($admin_files_id, $selected_checkbox)) {
                $sql_data_array = ['admin_groups_id' => oos_db_prepare_input($_POST['checked_' . $admin_files_id])];
            } else {
                $sql_data_array = ['admin_groups_id' => oos_db_prepare_input($_POST['unchecked_' . $admin_files_id])];
            }
            oos_db_perform($oostable['admin_files'], $sql_data_array, 'UPDATE', 'admin_files_id = \'' . $admin_files_id . '\'');

            // Move that ADOdb pointer!
            $define_files_result->MoveNext();
        }

        oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_POST['admin_groups_id']));
        break;

    case 'group_delete':
        $set_groups_id = oos_db_prepare_input($_POST['set_groups_id']);

        $admin_groupstable = $oostable['admin_groups'];
        $query = "DELETE FROM $admin_groupstable WHERE admin_groups_id = '" . intval($gID) . "'";
        $dbconn->Execute($query);
        $admin_filestable = $oostable['admin_files'];
        $query = "alter table $admin_filestable change admin_groups_id admin_groups_id set( " . oos_db_input($set_groups_id) . " ) NOT NULL DEFAULT '1' ";
        $dbconn->Execute($query);
        $admintable = $oostable['admin'];
        $query = "DELETE FROM $admintable WHERE admin_groups_id = '" . intval($gID) . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=groups'));
        break;

    case 'group_edit':
        $admin_groups_name = ucwords(strtolower((string) oos_db_prepare_input($_POST['admin_groups_name'])));
        $name_replace = preg_replace("/ /", "%", $admin_groups_name);

        if (($admin_groups_name == '' || null) || (strlen($admin_groups_name) <= 5)) {
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET[\GID] . '&gName=false&action=action=edit_group'));
        } else {
            $admin_groupstable = $oostable['admin_groups'];
            $check_groups_name_query = "SELECT admin_groups_name as group_name_edit FROM $admin_groupstable WHERE admin_groups_id <> " . intval($gID) . " and admin_groups_name like '%" . oos_db_input($name_replace) . "%'";
            $check_groups_name_result = $dbconn->Execute($check_groups_name_query);
            $check_duplicate = $check_groups_name_result->RecordCount();
            if ($check_duplicate > 0) {
                oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $gID . '&gName=used&action=edit_group'));
            } else {
                $admin_groups_id = $gID;
                $query = "UPDATE " . $oostable['admin_groups'] . "
                        SET admin_groups_name = '" .  oos_db_input($admin_groups_name) . "'
                        WHERE admin_groups_id = '" . intval($admin_groups_id) . "'";
                $dbconn->Execute($query);
                oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $admin_groups_id));
            }
        }
        break;

    case 'group_new':
        $admin_groups_name = ucwords(strtolower((string) oos_db_prepare_input($_POST['admin_groups_name'])));
        $name_replace = preg_replace("/ /", "%", $admin_groups_name);

        if (($admin_groups_name == '' || null) || (strlen($admin_groups_name) <= 5)) {
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET[\GID] . '&gName=false&action=new_group'));
        } else {
            $check_groups_name_query = "SELECT admin_groups_name as group_name_new FROM ". $oostable['admin_groups'] . " WHERE admin_groups_name like '%" . oos_db_input($name_replace) . "%'";
            $check_groups_name_result = $dbconn->Execute($check_groups_name_query);
            $check_duplicate = $check_groups_name_result->RecordCount();
            if ($check_duplicate > 0) {
                oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $gID . '&gName=used&action=new_group'));
            } else {
                $sql_data_array = ['admin_groups_name' => $admin_groups_name];
                oos_db_perform($oostable['admin_groups'], $sql_data_array);
                $admin_groups_id = $dbconn->Insert_ID();

                $set_groups_id = oos_db_prepare_input($_POST['set_groups_id']);
                $add_group_id = $set_groups_id . ',\'' . $admin_groups_id . '\'';
                $query = "alter table " . $oostable['admin_files'] . " change admin_groups_id admin_groups_id set( " . oos_db_input($add_group_id) . ") NOT NULL DEFAULT '1' ";
                $dbconn->Execute($query);

                oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $admin_groups_id));
            }
        }
        break;
}

require 'includes/header.php';
require 'includes/account_check.js.php';
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
if (isset($_GET['gPath']) && ($_GET['gPath'])) {
    $admin_groupstable = $oostable['admin_groups'];
    $group_name_query = "SELECT admin_groups_name FROM $admin_groupstable WHERE admin_groups_id = " . intval($_GET['gPath']);
    $group_name = $dbconn->GetRow($group_name_query);

    if (isset($_GET['gPath']) && ($_GET['gPath'] == 1)) {
        echo oos_draw_form('id', 'defineForm', $aContents['admin_members'], 'gID=' . $_GET['gPath'], 'post', false);
    } elseif (isset($_GET['gPath']) && ($_GET['gPath'] != 1)) {
        echo oos_draw_form('id', 'defineForm', $aContents['admin_members'], 'gID=' . $_GET['gPath'] . '&action=group_define', 'post', false, 'enctype="multipart/form-data"');
        echo oos_draw_hidden_field('admin_groups_id', $_GET['gPath']);
    } ?>
        <table class="table table-striped table-hover w-100">
            <thead class="thead-dark">
                <tr>
                    <th colspan=2>&nbsp;<?php echo TABLE_HEADING_GROUPS_DEFINE; ?></th>          
                </tr>    
            </thead>              
              
    <?php
    $admin_filestable = $oostable['admin_files'];
    $db_boxes_query = "SELECT admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_groups_id as boxes_group_id FROM $admin_filestable WHERE admin_files_is_boxes = '1' ORDER BY admin_files_name";
    $db_boxes_result = $dbconn->Execute($db_boxes_query);
    while ($group_boxes = $db_boxes_result->fields) {
        $admin_filestable = $oostable['admin_files'];
        $group_boxes_files_query = "SELECT admin_files_id, admin_files_name, admin_groups_id FROM $admin_filestable WHERE admin_files_is_boxes = '0' and admin_files_to_boxes = '" . intval($group_boxes['admin_boxes_id']) . "' ORDER BY admin_files_name";
        $group_boxes_files_result = $dbconn->Execute($group_boxes_files_query);

        $selectedGroups = $group_boxes['boxes_group_id'];
        $groupsArray = explode(",", (string) $selectedGroups);

        if (in_array($_GET['gPath'], $groupsArray)) {
            $del_boxes = [$_GET['gPath']];
            $result = array_diff($groupsArray, $del_boxes);
            sort($result);
            $checkedBox = $selectedGroups;
            $uncheckedBox = implode(",", $result);
            $checked = true;
        } else {
            $add_boxes = [$_GET['gPath']];
            $result = array_merge($add_boxes, $groupsArray);
            sort($result);
            $checkedBox = implode(",", $result);
            $uncheckedBox = $selectedGroups;
            $checked = false;
        } ?>
              <tr>
                <td width="23"><?php echo oos_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, ''); ?></td>
                <td><b><?php echo ucwords(substr_replace((string) $group_boxes['admin_boxes_name'], '', -4)) . ' ' . oos_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . oos_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox); ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td>&nbsp;</td>
                <td>
                  <table border="0" cellspacing="0" cellpadding="0">
        <?php
        while ($group_boxes_files = $group_boxes_files_result->fields) {
            $selectedGroups = $group_boxes_files['admin_groups_id'];
            $groupsArray = explode(",", (string) $selectedGroups);

            if (in_array($_GET['gPath'], $groupsArray)) {
                $del_boxes = [$_GET['gPath']];
                $result = array_diff($groupsArray, $del_boxes);
                sort($result);
                $checkedBox = $selectedGroups;
                $uncheckedBox = implode(",", $result);
                $checked = true;
            } else {
                $add_boxes = [$_GET['gPath']];
                $result = array_merge($add_boxes, $groupsArray);
                sort($result);
                $checkedBox = implode(",", $result);
                $uncheckedBox = $selectedGroups;
                $checked = false;
            } ?>
                    <tr>
                      <td width="20"><?php echo oos_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, ''); ?></td>
                      <td><?php echo $group_boxes_files['admin_files_name'] . ' ' . oos_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . oos_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox); ?></td>
                    </tr>
            <?php
            // Move that ADOdb pointer!
            $group_boxes_files_result->MoveNext();
        } ?>
                  </table>
                </td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $db_boxes_result->MoveNext();
    } ?>
              <tr>
                <td colspan="2" valign="top" align="right"><?php if (isset($_GET['gPath']) && ($_GET['gPath'] != '1')) {
                    echo  '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gPath']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>' . oos_submit_button(BUTTON_INSERT);
                } else {
                    echo oos_submit_button(BUTTON_BACK);
                } ?>&nbsp;</td>
              </tr>
            </table></form>
    <?php
} elseif (isset($_GET['gID']) && ($_GET['gID'])) {
    ?>
        <table class="table table-striped table-hover w-100">
            <thead class="thead-dark">
                <tr>
                    <th>&nbsp;<?php echo TABLE_HEADING_GROUPS_NAME; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>              
                </tr>    
            </thead>              
    <?php
    $db_groups_query = "SELECT * FROM ". $oostable['admin_groups'] . " ORDER BY admin_groups_id";
    $db_groups_result = $dbconn->Execute($db_groups_query);

    $add_groups_prepare = '\'0\'' ;
    $del_groups_prepare = '\'0\'' ;
    $count_groups = 0;
    while ($groups = $db_groups_result->fields) {
        $add_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
        if (((!$_GET['gID']) || ($_GET['gID'] == $groups['admin_groups_id']) || ($_GET['gID'] == 'groups')) && (!$gInfo)) {
            $gInfo = new objectInfo($groups);
        }

        if (isset($gInfo) && is_object($gInfo) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id)) {
            echo '                <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id'] . '&action=edit_group') . '\'">' . "\n";
        } else {
            echo '                <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id']) . '\'">' . "\n";
            $del_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
        } ?>
                <td>&nbsp;<b><?php echo $groups['admin_groups_name']; ?></b></td>
                <td class="text-right"><?php if (isset($gInfo) && is_object($gInfo) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
        <?php
        $count_groups++;
        // Move that ADOdb pointer!
        $db_groups_result->MoveNext();
    } ?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_GROUPS . $count_groups; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members']) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=new_group') . '">' . oos_button(IMAGE_NEW_GROUP) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
    <?php
} else {
    ?>
        <table class="table table-striped table-hover w-100">
            <thead class="thead-dark">
                <tr>
                    <th><?php echo TABLE_HEADING_NAME; ?></th>
                    <th><?php echo TABLE_HEADING_EMAIL; ?></th>
                    <th class="text-center"><?php echo TABLE_HEADING_GROUPS; ?></th>
                    <th class="text-center"><?php echo TABLE_HEADING_LOGNUM; ?></th>
                    <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                </tr>    
            </thead>
    <?php
    $db_admin_result_raw = "SELECT * FROM " . $oostable['admin'] . " ORDER BY admin_firstname";

    $db_admin_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $db_admin_result_raw, $db_admin_result_numrows);
    $db_admin_result = $dbconn->Execute($db_admin_result_raw);

    while ($admin = $db_admin_result->fields) {
        $admin_group_query = "SELECT admin_groups_name FROM ". $oostable['admin_groups'] . " WHERE admin_groups_id = '" . intval($admin['admin_groups_id']) . "'";
        $admin_group_result = $dbconn->Execute($admin_group_query);
        $admin_group = $admin_group_result->fields;
        if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $admin['admin_id']))) && !isset($mInfo)) {
            $mInfo_array = array_merge($admin, $admin_group);
            $mInfo = new objectInfo($mInfo_array);
        }

        if (isset($mInfo) && is_object($mInfo) && ($admin['admin_id'] == $mInfo->admin_id)) {
            echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id'] . '&action=edit_member') . '\'">' . "\n";
        } else {
            echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id']) . '\'">' . "\n";
        } ?>
                <td>&nbsp;<?php echo $admin['admin_firstname']; ?>&nbsp;<?php echo $admin['admin_lastname']; ?></td>
                <td><?php echo $admin['admin_email_address']; ?></td>
                <td class="text-center"><?php echo $admin_group['admin_groups_name']; ?></td>
                <td class="text-center"><?php echo $admin['admin_lognum']; ?></td>
                <td class="text-right"><?php if (isset($mInfo) && is_object($mInfo) && ($admin['admin_id'] == $mInfo->admin_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $db_admin_result->MoveNext();
    } ?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $db_admin_split->display_count($db_admin_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_MEMBERS); ?><br><?php echo $db_admin_split->display_links($db_admin_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=groups') . '">' . oos_button(IMAGE_GROUPS) . '</a>';
    echo ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=new_member') . '">' . oos_button(IMAGE_NEW_MEMBER) . '</a>'; ?>&nbsp;</td>
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
    case 'new_member':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'newmember', $aContents['admin_members'], 'action=member_new&page=' . $nPage, 'post', false, 'enctype="multipart/form-data"')];
        if (isset($_GET['error'])) {
            $contents[] = ['text' => TEXT_INFO_ERROR];
        }
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_FIRSTNAME . '<br>&nbsp;' . oos_draw_input_field('admin_firstname')];
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_LASTNAME . '<br>&nbsp;' . oos_draw_input_field('admin_lastname')];
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_EMAIL . '<br>&nbsp;' . oos_draw_input_field('admin_email_address')];

        $groups_array = [];
        $groups_array = [['id' => '0', 'text' => TEXT_NONE]];
        $groups_query = "SELECT admin_groups_id, admin_groups_name FROM ". $oostable['admin_groups'];
        $groups_result = $dbconn->Execute($groups_query);
        while ($groups = $groups_result->fields) {
            $groups_array[] = ['id' => $groups['admin_groups_id'], 'text' => $groups['admin_groups_name']];
            // Move that ADOdb pointer!
            $groups_result->MoveNext();
        }
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_GROUP . '<br>&nbsp;' . oos_draw_pull_down_menu('admin_groups_id', '', $groups_array, '0')];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'edit_member':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'newmember', $aContents['admin_members'], 'action=member_edit&page=' . $nPage . '&mID=' . $mID, 'post', false, 'enctype="multipart/form-data"')];
        if (isset($_GET['error'])) {
            $contents[] = ['text' => TEXT_INFO_ERROR];
        }
        $contents[] = ['text' => oos_draw_hidden_field('admin_id', $mInfo->admin_id)];
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_FIRSTNAME . '<br>&nbsp;' . oos_draw_input_field('admin_firstname', $mInfo->admin_firstname)];
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_LASTNAME . '<br>&nbsp;' . oos_draw_input_field('admin_lastname', $mInfo->admin_lastname)];
        $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_EMAIL . '<br>&nbsp;' . oos_draw_input_field('admin_email_address', $mInfo->admin_email_address)];
        if ($mInfo->admin_id == 1) {
            $contents[] = ['text' => oos_draw_hidden_field('admin_groups_id', $mInfo->admin_groups_id)];
        } else {
            $groups_array = [];
            $groups_array = [['id' => '0', 'text' => TEXT_NONE]];
            $groups_query = "SELECT admin_groups_id, admin_groups_name FROM ". $oostable['admin_groups'];
            $groups_result = $dbconn->Execute($groups_query);
            while ($groups = $groups_result->fields) {
                $groups_array[] = ['id' => $groups['admin_groups_id'], 'text' => $groups['admin_groups_name']];
                // Move that ADOdb pointer!
                $groups_result->MoveNext();
            }
            $contents[] = ['text' => '<br>&nbsp;' . TEXT_INFO_GROUP . '<br>&nbsp;' . oos_draw_pull_down_menu('admin_groups_id', '', $groups_array, $mInfo->admin_groups_id)];
        }
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'del_member':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE . '</b>'];
        if ($mInfo->admin_id == 1 || $mInfo->admin_email_address == STORE_OWNER_EMAIL_ADDRESS) {
            $contents[] = ['align' => 'center', 'text' => '<br><a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a></a><br>&nbsp;'];
        } else {
            $admin_id = (isset($admin['admin_id']) ? intval($admin['admin_id']) : '');
            $contents = ['form' => oos_draw_form('id', 'edit', $aContents['admin_members'], 'action=member_delete&page=' . $nPage . '&mID=' . $admin_id, 'post', false, 'enctype="multipart/form-data"')];
            $contents[] = ['text' => oos_draw_hidden_field('admin_id', $mInfo->admin_id)];
            $contents[] = ['align' => 'center', 'text' =>  sprintf(TEXT_INFO_DELETE_INTRO, $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname)];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        }
        break;

    case 'new_group':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_GROUPS . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'new_group', $aContents['admin_members'], 'action=group_new&gID=' . $gInfo->admin_groups_id, 'post', false, 'enctype="multipart/form-data"')];
        if (isset($_GET['gName']) && ($_GET['gName'] == 'false')) {
            $contents[] = ['text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;'];
        } elseif (isset($_GET['gName']) && ($_GET['gName'] == 'used')) {
            $contents[] = ['text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;'];
        }
        $contents[] = ['text' => oos_draw_hidden_field('set_groups_id', substr((string) $add_groups_prepare, 4))];
        $contents[] = ['text' => TEXT_INFO_GROUPS_NAME . '<br>'];
        $contents[] = ['align' => 'center', 'text' => oos_draw_input_field('admin_groups_name')];
        $contents[] = ['align' => 'center', 'text' => '<br><a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>' . oos_submit_button(IMAGE_NEXT)];
        break;

    case 'edit_group':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'edit_group', $aContents['admin_members'], 'action=group_edit&gID=' . $gID, 'post', false, 'enctype="multipart/form-data"')];
        if (isset($_GET['gName']) && ($_GET['gName'] == 'false')) {
            $contents[] = ['text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br>&nbsp;'];
        } elseif (isset($_GET['gName']) && ($_GET['gName'] == 'used')) {
            $contents[] = ['text' => TEXT_INFO_GROUPS_NAME_USED . '<br>&nbsp;'];
        }
        $contents[] = ['align' => 'center', 'text' => TEXT_INFO_EDIT_GROUP_INTRO . '<br>&nbsp;<br>' . oos_draw_input_field('admin_groups_name', $gInfo->admin_groups_name)];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_SAVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'del_group':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_GROUPS . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'delete_group', $aContents['admin_members'], 'action=group_delete&gID=' . $gInfo->admin_groups_id, 'post', false, 'enctype="multipart/form-data"')];
        if ($gInfo->admin_groups_id == 1) {
            $contents[] = ['align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO_NOT, $gInfo->admin_groups_name)];
            $contents[] = ['align' => 'center', 'text' => '<br><a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gID) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a><br>&nbsp;'];
        } else {
            $contents[] = ['text' => oos_draw_hidden_field('set_groups_id', substr((string) $del_groups_prepare, 4))];
            $contents[] = ['align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO, $gInfo->admin_groups_name)];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a><br>&nbsp;'];
        }
        break;

    case 'define_group':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>'];

        $contents[] = ['text' => sprintf(TEXT_INFO_DEFINE_INTRO, $group_name['admin_groups_name'])];
        if (isset($_GET['gPath']) && ($_GET['gPath'] == 1)) {
            $contents[] = ['align' => 'center', 'text' => '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gPath']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a><br>'];
        }
        break;

    case 'show_group':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>'];
        $check_email_query = "SELECT admin_email_address FROM ". $oostable['admin'] . "";
        $check_email_result = $dbconn->Execute($check_email_query);
        //$stored_email[];
        while ($check_email = $check_email_result->fields) {
            $stored_email[] = $check_email['admin_email_address'];

            // Move that ADOdb pointer!
            $check_email_result->MoveNext();
        }

        if (in_array($_POST['admin_email_address'], $stored_email)) {
            $checkEmail = "true";
        } else {
            $checkEmail = "false";
        }
        $contents = ['form' => oos_draw_form('id', 'show_group', $aContents['admin_members'], 'action=show_group&gID=groups', 'post', false, 'enctype="multipart/form-data"')];
        $contents[] = ['text' => $define_files['admin_files_name'] . oos_draw_input_field('level_edit', $checkEmail)];
        break;

    default:
        if (isset($mInfo) && is_object($mInfo)) {
            $heading[] = ['text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>'];
            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=edit_member') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=del_member') . '">' . oos_button(BUTTON_DELETE) . '</a><br>&nbsp;'];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_FULLNAME . '</b><br>&nbsp;' . $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_EMAIL . '</b><br>&nbsp;' . $mInfo->admin_email_address];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_GROUP . '</b>' . $mInfo->admin_groups_name];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_CREATED . '</b><br>&nbsp;' . $mInfo->admin_created];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_MODIFIED . '</b><br>&nbsp;' . $mInfo->admin_modified];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_LOGDATE . '</b><br>&nbsp;' . $mInfo->admin_logdate];
            $contents[] = ['text' => '&nbsp;<b>' . TEXT_INFO_LOGNUM . '</b>' . $mInfo->admin_lognum];
            $contents[] = ['text' => '<br>'];
        } elseif (isset($gInfo) && is_object($gInfo)) {
            $heading[] = ['text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT_GROUPS . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gPath=' . $gInfo->admin_groups_id . '&action=define_group') . '">' . oos_button(IMAGE_FILE_PERMISSION) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=edit_group') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=del_group') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DEFAULT_GROUPS_INTRO . '<br>&nbsp'];
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
?>
<script nonce="<?php echo NONCE; ?>">
let element = document.getElementById('page');
if (element) {

	let form = document.getElementById('pages'); 

	element.addEventListener('change', function() { 
		form.submit(); 
	});
}
</script>
<?php

require 'includes/nice_exit.php';
