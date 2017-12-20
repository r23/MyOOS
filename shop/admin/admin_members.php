<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2017 by the MyOOS Development Team.
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

//Import the PHPMailer class into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\POP3;   
   
   
define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$current_boxes = OOS_ABSOLUTE_PATH . 'admin/includes/boxes/';

$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);  
$action = (isset($_GET['action']) ? $_GET['action'] : '');
  
  if (!empty($action)) {
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
          oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . 'mID=' . $_GET['mID'] . '&error=email&action=new_member'));
        } else {
          $newpass = oos_create_random_value(ENTRY_PASSWORD_MIN_LENGTH);
          $crypted_password = oos_encrypt_password($newpass);

          $sql_data_array = array('admin_groups_id' => oos_db_prepare_input($_POST['admin_groups_id']),
                                  'admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']),
                                  'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']),
                                  'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']),
                                  'admin_password' => $crypted_password,
                                  'admin_created' => 'now()');

          oos_db_perform($oostable['admin'], $sql_data_array);
          $admin_id = $dbconn->Insert_ID();

          oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], OOS_HTTP_SERVER . OOS_SHOP . OOS_ADMIN, $_POST['admin_email_address'], $newpass, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

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
          oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . 'mID=' . $_GET['mID'] . '&error=email&action=edit_member'));
        } else {
          $sql_data_array = array('admin_groups_id' => oos_db_prepare_input($_POST['admin_groups_id']),
                                  'admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']),
                                  'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']),
                                  'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']),
                                  'admin_modified' => 'now()');

          oos_db_perform($oostable['admin'], $sql_data_array, 'UPDATE', 'admin_id = \'' . $admin_id . '\'');

          oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], OOS_HTTP_SERVER . OOS_SHOP . OOS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin_id));
        }
        break;

      case 'member_delete':
        $admin_id = oos_db_prepare_input($_POST['admin_id']);
        $query = "DELETE FROM ". $oostable['admin'] . " WHERE admin_id = '" . $admin_id . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage));
        break;

      case 'group_define':
        $selected_checkbox = $_POST['groups_to_boxes'];

        $admin_filestable = $oostable['admin_files'];
        $define_files_query = "SELECT admin_files_id FROM $admin_filestable ORDER BY admin_files_id";
        $define_files_result = $dbconn->Execute($define_files_query);
        while ($define_files = $define_files_result->fields) {
          $admin_files_id = $define_files['admin_files_id'];

          if (in_array ($admin_files_id, $selected_checkbox)) {
            $sql_data_array = array('admin_groups_id' => oos_db_prepare_input($_POST['checked_' . $admin_files_id]));
            //$set_group_id = $_POST['checked_' . $admin_files_id];
          } else {
            $sql_data_array = array('admin_groups_id' => oos_db_prepare_input($_POST['unchecked_' . $admin_files_id]));
            //$set_group_id = $_POST['unchecked_' . $admin_files_id];
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
        $query = "DELETE FROM $admin_groupstable WHERE admin_groups_id = '" . $_GET['gID'] . "'";
        $dbconn->Execute($query);
        $admin_filestable = $oostable['admin_files'];
        $query = "alter table $admin_filestable change admin_groups_id admin_groups_id set( " . $set_groups_id . " ) NOT NULL DEFAULT '1' ";
        $dbconn->Execute($query);
        $admintable = $oostable['admin'];
        $query = "DELETE FROM $admintable WHERE admin_groups_id = '" . $_GET['gID'] . "'";
        $dbconn->Execute($query);

        oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=groups'));
        break;

      case 'group_edit':
        $admin_groups_name = ucwords(strtolower(oos_db_prepare_input($_POST['admin_groups_name'])));
        $name_replace = preg_replace ("/ /", "%", $admin_groups_name);

        if (($admin_groups_name == '' || NULL) || (strlen($admin_groups_name) <= 5) ) {
          oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET[gID] . '&gName=false&action=action=edit_group'));
        } else {
          $admin_groupstable = $oostable['admin_groups'];
          $check_groups_name_query = "SELECT admin_groups_name as group_name_edit FROM $admin_groupstable WHERE admin_groups_id <> " . $_GET['gID'] . " and admin_groups_name like '%" . $name_replace . "%'";
          $check_groups_name_result = $dbconn->Execute($check_groups_name_query);
          $check_duplicate = $check_groups_name_result->RecordCount();
          if ($check_duplicate > 0){
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gID'] . '&gName=used&action=edit_group'));
          } else {
            $admin_groups_id = $_GET['gID'];
            $query = "UPDATE " . $oostable['admin_groups'] . "
                        SET admin_groups_name = '" . $admin_groups_name . "'
                        WHERE admin_groups_id = '" . $admin_groups_id . "'";
            $dbconn->Execute($query);
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $admin_groups_id));
          }
        }
        break;

      case 'group_new':
        $admin_groups_name = ucwords(strtolower(oos_db_prepare_input($_POST['admin_groups_name'])));
        $name_replace = preg_replace ("/ /", "%", $admin_groups_name);

        if (($admin_groups_name == '' || NULL) || (strlen($admin_groups_name) <= 5) ) {
          oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET[gID] . '&gName=false&action=new_group'));
        } else {
          $check_groups_name_query = "SELECT admin_groups_name as group_name_new FROM ". $oostable['admin_groups'] . " WHERE admin_groups_name like '%" . $name_replace . "%'";
          $check_groups_name_result = $dbconn->Execute($check_groups_name_query);
          $check_duplicate = $check_groups_name_result->RecordCount();
          if ($check_duplicate > 0){
            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gID'] . '&gName=used&action=new_group'));
          } else {
            $sql_data_array = array('admin_groups_name' => $admin_groups_name);
            oos_db_perform($oostable['admin_groups'], $sql_data_array);
            $admin_groups_id = $dbconn->Insert_ID();

            $set_groups_id = oos_db_prepare_input($_POST['set_groups_id']);
            $add_group_id = $set_groups_id . ',\'' . $admin_groups_id . '\'';
            $query = "alter table " . $oostable['admin_files'] . " change admin_groups_id admin_groups_id set( " . $add_group_id . ") NOT NULL DEFAULT '1' ";
            $dbconn->Execute($query);

            oos_redirect_admin(oos_href_link_admin($aContents['admin_members'], 'gID=' . $admin_groups_id));
          }
        }
        break;
    }
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
			<div class="row wrapper gray-bg page-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['admin_account'], 'selected_box=administrator') . '">' . BOX_HEADING_ADMINISTRATOR . '</a>'; ?>
						</li>
						<li class="active">
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
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
<?php
 if ($_GET['gPath']) {
   $admin_groupstable = $oostable['admin_groups'];
   $group_name_query = "SELECT admin_groups_name FROM $admin_groupstable WHERE admin_groups_id = " . $_GET['gPath'];
   $group_name = $dbconn->GetRow($group_name_query);

   if ($_GET['gPath'] == 1) {
     echo oos_draw_form('id', 'defineForm', $aContents['admin_members'], 'gID=' . $_GET['gPath'], 'post', FALSE);
   } elseif ($_GET['gPath'] != 1) {
     echo oos_draw_form('id', 'defineForm', $aContents['admin_members'], 'gID=' . $_GET['gPath'] . '&action=group_define', 'post',  FALSE, 'enctype="multipart/form-data"');
     echo oos_draw_hidden_field('admin_groups_id', $_GET['gPath']);
   }
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td colspan=2 class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_GROUPS_DEFINE; ?></td>
              </tr>
<?php
  $admin_filestable = $oostable['admin_files'];
  $db_boxes_query = "SELECT admin_files_id as admin_boxes_id, admin_files_name as admin_boxes_name, admin_groups_id as boxes_group_id FROM $admin_filestable WHERE admin_files_is_boxes = '1' ORDER BY admin_files_name";
  $db_boxes_result = $dbconn->Execute($db_boxes_query);
  while ($group_boxes = $db_boxes_result->fields) {
    $admin_filestable = $oostable['admin_files'];
    $group_boxes_files_query = "SELECT admin_files_id, admin_files_name, admin_groups_id FROM $admin_filestable WHERE admin_files_is_boxes = '0' and admin_files_to_boxes = '" . $group_boxes['admin_boxes_id'] . "' ORDER BY admin_files_name";
    $group_boxes_files_result = $dbconn->Execute($group_boxes_files_query);

    $selectedGroups = $group_boxes['boxes_group_id'];
    $groupsArray = explode(",", $selectedGroups);

    if (in_array($_GET['gPath'], $groupsArray)) {
      $del_boxes = array($_GET['gPath']);
      $result = array_diff ($groupsArray, $del_boxes);
      sort($result);
      $checkedBox = $selectedGroups;
      $uncheckedBox = implode (",", $result);
      $checked = true;
    } else {
      $add_boxes = array($_GET['gPath']);
      $result = array_merge ($add_boxes, $groupsArray);
      sort($result);
      $checkedBox = implode (",", $result);
      $uncheckedBox = $selectedGroups;
      $checked = false;
    }
?>
              <tr class="dataTableRowBoxes">
                <td class="dataTableContent" width="23"><?php echo oos_draw_checkbox_field('groups_to_boxes[]', $group_boxes['admin_boxes_id'], $checked, '', 'id="groups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkGroups(this)"'); ?></td>
                <td class="dataTableContent"><b><?php echo ucwords(substr_replace ($group_boxes['admin_boxes_name'], '', -4)) . ' ' . oos_draw_hidden_field('checked_' . $group_boxes['admin_boxes_id'], $checkedBox) . oos_draw_hidden_field('unchecked_' . $group_boxes['admin_boxes_id'], $uncheckedBox); ?></b></td>
              </tr>
              <tr class="dataTableRow">
                <td class="dataTableContent">&nbsp;</td>
                <td class="dataTableContent">
                  <table border="0" cellspacing="0" cellpadding="0">
<?php
     while($group_boxes_files = $group_boxes_files_result->fields) {
       $selectedGroups = $group_boxes_files['admin_groups_id'];
       $groupsArray = explode(",", $selectedGroups);

       if (in_array($_GET['gPath'], $groupsArray)) {
         $del_boxes = array($_GET['gPath']);
         $result = array_diff ($groupsArray, $del_boxes);
         sort($result);
         $checkedBox = $selectedGroups;
         $uncheckedBox = implode (",", $result);
         $checked = true;
       } else {
         $add_boxes = array($_GET['gPath']);
         $result = array_merge ($add_boxes, $groupsArray);
         sort($result);
         $checkedBox = implode (",", $result);
         $uncheckedBox = $selectedGroups;
         $checked = false;
       }
?>
                    <tr>
                      <td width="20"><?php echo oos_draw_checkbox_field('groups_to_boxes[]', $group_boxes_files['admin_files_id'], $checked, '', 'id="subgroups_' . $group_boxes['admin_boxes_id'] . '" onClick="checkSub(this)"'); ?></td>
                      <td class="dataTableContent"><?php echo $group_boxes_files['admin_files_name'] . ' ' . oos_draw_hidden_field('checked_' . $group_boxes_files['admin_files_id'], $checkedBox) . oos_draw_hidden_field('unchecked_' . $group_boxes_files['admin_files_id'], $uncheckedBox);?></td>
                    </tr>
<?php
        // Move that ADOdb pointer!
       $group_boxes_files_result->MoveNext();
     }

     // Close result set
     $group_boxes_files_result->Close();
?>
                  </table>
                </td>
              </tr>
<?php
     // Move that ADOdb pointer!
    $db_boxes_result->MoveNext();
  }

  // Close result set
  $db_boxes_result->Close();
?>
              <tr class="dataTableRowBoxes">
                <td colspan=2 class="dataTableContent" valign="top" align="right"><?php if ($_GET['gPath'] != 1) { echo  '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gPath']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a> ' . oos_submit_button('save', BUTTON_INSERT); } else { echo oos_submit_button('back', IMAGE_BACK); } ?>&nbsp;</td>
              </tr>
            </table></form>
<?php
 } elseif ($_GET['gID']) {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent">&nbsp;<?php echo TABLE_HEADING_GROUPS_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $db_groups_query = "SELECT * FROM ". $oostable['admin_groups'] . " ORDER BY admin_groups_id";
  $db_groups_result = $dbconn->Execute($db_groups_query);

  $add_groups_prepare = '\'0\'' ;
  $del_groups_prepare = '\'0\'' ;
  $count_groups = 0;
  while ($groups = $db_groups_result->fields) {
    $add_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
    if (((!$_GET['gID']) || ($_GET['gID'] == $groups['admin_groups_id']) || ($_GET['gID'] == 'groups')) && (!$gInfo) ) {
      $gInfo = new objectInfo($groups);
    }

    if (isset($gInfo) && is_object($gInfo) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id) ) {
      echo '                <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id'] . '&action=edit_group') . '\'">' . "\n";
    } else {
      echo '                <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id']) . '\'">' . "\n";
      $del_groups_prepare .= ',\'' . $groups['admin_groups_id'] . '\'' ;
    }
?>
                <td class="dataTableContent">&nbsp;<b><?php echo $groups['admin_groups_name']; ?></b></td>
                <td class="dataTableContent" align="right"><?php if (isset($gInfo) && is_object($gInfo) && ($groups['admin_groups_id'] == $gInfo->admin_groups_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $groups['admin_groups_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    $count_groups++;
    // Move that ADOdb pointer!
    $db_groups_result->MoveNext();
  }

  // Close result set
  $db_groups_result->Close();
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo TEXT_COUNT_GROUPS . $count_groups; ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['admin_members']) . '">' . oos_button('back', IMAGE_BACK) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=new_group') . '">' . oos_button('admin_group', IMAGE_NEW_GROUP) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table>
<?php
 } else {
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_GROUPS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LOGNUM; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $db_admin_result_raw = "SELECT * FROM " . $oostable['admin'] . " ORDER BY admin_firstname";

  $db_admin_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $db_admin_result_raw, $db_admin_result_numrows);
  $db_admin_result = $dbconn->Execute($db_admin_result_raw);

  while ($admin = $db_admin_result->fields) {
    $admin_group_query = "SELECT admin_groups_name FROM ". $oostable['admin_groups'] . " WHERE admin_groups_id = '" . $admin['admin_groups_id'] . "'";
    $admin_group_result = $dbconn->Execute($admin_group_query);
    $admin_group = $admin_group_result->fields;
    if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $admin['admin_id']))) && !isset($mInfo)) {
      $mInfo_array = array_merge($admin, $admin_group);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($admin['admin_id'] == $mInfo->admin_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id'] . '&action=edit_member') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent">&nbsp;<?php echo $admin['admin_firstname']; ?>&nbsp;<?php echo $admin['admin_lastname']; ?></td>
                <td class="dataTableContent"><?php echo $admin['admin_email_address']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $admin_group['admin_groups_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $admin['admin_lognum']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($admin['admin_id'] == $mInfo->admin_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $admin['admin_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $db_admin_result->MoveNext();
  }

  // Close result set
  $db_admin_result->Close();
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $db_admin_split->display_count($db_admin_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_MEMBERS); ?><br /><?php echo $db_admin_split->display_links($db_admin_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                    <td class="smallText" valign="top" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=groups') . '">' . oos_button('admin_groups', IMAGE_GROUPS) . '</a>'; echo ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=new_member') . '">' . oos_button('admin_member', IMAGE_NEW_MEMBER) . '</a>'; ?>&nbsp;</td>
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
    case 'new_member':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>');

      $contents = array('form' => oos_draw_form('id', 'newmember', $aContents['admin_members'], 'action=member_new&page=' . $nPage, 'post', FALSE, 'enctype="multipart/form-data"'));
      if ($_GET['error']) {
        $contents[] = array('text' => TEXT_INFO_ERROR);
      }
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_FIRSTNAME . '<br />&nbsp;' . oos_draw_input_field('admin_firstname'));
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_LASTNAME . '<br />&nbsp;' . oos_draw_input_field('admin_lastname'));
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_EMAIL . '<br />&nbsp;' . oos_draw_input_field('admin_email_address'));

      $groups_array = array();
      $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
      $groups_query = "SELECT admin_groups_id, admin_groups_name FROM ". $oostable['admin_groups'];
      $groups_result = $dbconn->Execute($groups_query);
      while ($groups = $groups_result->fields) {
        $groups_array[] = array('id' => $groups['admin_groups_id'],
                                'text' => $groups['admin_groups_name']);
        // Move that ADOdb pointer!
        $groups_result->MoveNext();
      }
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_GROUP . '<br />&nbsp;' . oos_draw_pull_down_menu('admin_groups_id', $groups_array, '0'));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('insert', BUTTON_INSERT, 'onClick="validateForm();return document.returnValue"') . ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $_GET['mID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit_member':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW . '</b>');

      $contents = array('form' => oos_draw_form('id', 'newmember', $aContents['admin_members'], 'action=member_edit&page=' . $nPage . '&mID=' . $_GET['mID'], 'post', FALSE, 'enctype="multipart/form-data"'));
      if ($_GET['error']) {
        $contents[] = array('text' => TEXT_INFO_ERROR);
      }
      $contents[] = array('text' => oos_draw_hidden_field('admin_id', $mInfo->admin_id));
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_FIRSTNAME . '<br />&nbsp;' . oos_draw_input_field('admin_firstname', $mInfo->admin_firstname));
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_LASTNAME . '<br />&nbsp;' . oos_draw_input_field('admin_lastname', $mInfo->admin_lastname));
      $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_EMAIL . '<br />&nbsp;' . oos_draw_input_field('admin_email_address', $mInfo->admin_email_address));
      if ($mInfo->admin_id == 1) {
        $contents[] = array('text' => oos_draw_hidden_field('admin_groups_id', $mInfo->admin_groups_id));
      } else {
        $groups_array = array();
        $groups_array = array(array('id' => '0', 'text' => TEXT_NONE));
        $groups_query = "SELECT admin_groups_id, admin_groups_name FROM ". $oostable['admin_groups'];
        $groups_result = $dbconn->Execute($groups_query);
        while ($groups = $groups_result->fields) {
          $groups_array[] = array('id' => $groups['admin_groups_id'],
                                  'text' => $groups['admin_groups_name']);
          // Move that ADOdb pointer!
          $groups_result->MoveNext();
        }
        $contents[] = array('text' => '<br />&nbsp;' . TEXT_INFO_GROUP . '<br />&nbsp;' . oos_draw_pull_down_menu('admin_groups_id', $groups_array, $mInfo->admin_groups_id));
      }
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('insert', BUTTON_INSERT, 'onClick="validateForm();return document.returnValue"') . ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $_GET['mID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'del_member':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE . '</b>');
      if ($mInfo->admin_id == 1 || $mInfo->admin_email_address == STORE_OWNER_EMAIL_ADDRESS) {
        $contents[] = array('align' => 'center', 'text' => '<br /><a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id) . '">' . oos_button('back', IMAGE_BACK) . '</a><br />&nbsp;');
      } else {
        $contents = array('form' => oos_draw_form('id', 'edit', $aContents['admin_members'], 'action=member_delete&page=' . $nPage . '&mID=' . $admin['admin_id'], 'post', FALSE, 'enctype="multipart/form-data"'));
        $contents[] = array('text' => oos_draw_hidden_field('admin_id', $mInfo->admin_id));
        $contents[] = array('align' => 'center', 'text' =>  sprintf(TEXT_INFO_DELETE_INTRO, $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $_GET['mID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      }
      break;

    case 'new_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_GROUPS . '</b>');

      $contents = array('form' => oos_draw_form('id', 'new_group', $aContents['admin_members'], 'action=group_new&gID=' . $gInfo->admin_groups_id, 'post', FALSE, 'enctype="multipart/form-data"'));
      if ($_GET['gName'] == 'false') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br />&nbsp;');
      } elseif ($_GET['gName'] == 'used') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br />&nbsp;');
      }
      $contents[] = array('text' => oos_draw_hidden_field('set_groups_id', substr($add_groups_prepare, 4)) );
      $contents[] = array('text' => TEXT_INFO_GROUPS_NAME . '<br />');
      $contents[] = array('align' => 'center', 'text' => oos_draw_input_field('admin_groups_name'));
      $contents[] = array('align' => 'center', 'text' => '<br /><a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a> ' . oos_submit_button('next', IMAGE_NEXT) );
      break;

    case 'edit_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');

      $contents = array('form' => oos_draw_form('id', 'edit_group', $aContents['admin_members'], 'action=group_edit&gID=' . $_GET['gID'], 'post', FALSE, 'enctype="multipart/form-data"'));
      if ($_GET['gName'] == 'false') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_FALSE . '<br />&nbsp;');
      } elseif ($_GET['gName'] == 'used') {
        $contents[] = array('text' => TEXT_INFO_GROUPS_NAME_USED . '<br />&nbsp;');
      }
      $contents[] = array('align' => 'center', 'text' => TEXT_INFO_EDIT_GROUP_INTRO . '<br />&nbsp;<br />' . oos_draw_input_field('admin_groups_name', $gInfo->admin_groups_name));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'del_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_GROUPS . '</b>');

      $contents = array('form' => oos_draw_form('id', 'delete_group', $aContents['admin_members'], 'action=group_delete&gID=' . $gInfo->admin_groups_id, 'post', FALSE, 'enctype="multipart/form-data"'));
      if ($gInfo->admin_groups_id == 1) {
        $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO_NOT, $gInfo->admin_groups_name));
        $contents[] = array('align' => 'center', 'text' => '<br /><a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gID']) . '">' . oos_button('back', IMAGE_BACK) . '</a><br />&nbsp;');
      } else {
        $contents[] = array('text' => oos_draw_hidden_field('set_groups_id', substr($del_groups_prepare, 4)) );
        $contents[] = array('align' => 'center', 'text' => sprintf(TEXT_INFO_DELETE_GROUPS_INTRO, $gInfo->admin_groups_name));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a><br />&nbsp;');
      }
      break;

    case 'define_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DEFINE . '</b>');

      $contents[] = array('text' => sprintf(TEXT_INFO_DEFINE_INTRO, $group_name['admin_groups_name']));
      if ($_GET['gPath'] == 1) {
        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $_GET['gPath']) . '">' . oos_button('back',BUTTON_CANCEL) . '</a><br />');
      }
      break;

    case 'show_group':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_GROUP . '</b>');
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
      $contents = array('form' => oos_draw_form('id', 'show_group', $aContents['admin_members'], 'action=show_group&gID=groups', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => $define_files['admin_files_name'] . oos_draw_input_field('level_edit', $checkEmail));
      break;

    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=edit_member') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'page=' . $nPage . '&mID=' . $mInfo->admin_id . '&action=del_member') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a><br />&nbsp;');
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_FULLNAME . '</b><br />&nbsp;' . $mInfo->admin_firstname . ' ' . $mInfo->admin_lastname);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_EMAIL . '</b><br />&nbsp;' . $mInfo->admin_email_address);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_GROUP . '</b>' . $mInfo->admin_groups_name);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_CREATED . '</b><br />&nbsp;' . $mInfo->admin_created);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_MODIFIED . '</b><br />&nbsp;' . $mInfo->admin_modified);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGDATE . '</b><br />&nbsp;' . $mInfo->admin_logdate);
        $contents[] = array('text' => '&nbsp;<b>' . TEXT_INFO_LOGNUM . '</b>' . $mInfo->admin_lognum);
        $contents[] = array('text' => '<br />');
      } elseif (isset($gInfo) && is_object($gInfo)) {
        $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT_GROUPS . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_members'], 'gPath=' . $gInfo->admin_groups_id . '&action=define_group') . '">' . oos_button('define', IMAGE_FILE_PERMISSION) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=edit_group') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['admin_members'], 'gID=' . $gInfo->admin_groups_id . '&action=del_group') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DEFAULT_GROUPS_INTRO . '<br />&nbsp');
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
    </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2017 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>