<?php
/* ----------------------------------------------------------------------
   $Id: admin_account.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_account.php,v 1.29 2002/03/17 17:52:23 harley_vb
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

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'check_password':
        $admintable = $oostable['admin'];
        $check_pass_query = "SELECT admin_password as confirm_password FROM $admintable WHERE admin_id = '" . oos_db_input($_POST['id_info']) . "'";
        $check_pass = $dbconn->GetRow($check_pass_query);

        // Check that password is good
        if (!oos_validate_password($_POST['password_confirmation'], $check_pass['confirm_password'])) {
          oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'action=check_account&error=password'));
        } else {
          //$confirm = 'confirm_account';
          $_SESSION['confirm_account'] = 'confirm';
          oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'action=edit_process'));
        }
        break;

      case 'save_account':
        $admin_id = oos_db_prepare_input($_POST['id_info']);
        $admin_email_address = oos_db_prepare_input($_POST['admin_email_address']);
        $stored_email[] = 'NONE';

        $admintable = $oostable['admin'];
        $check_email_query = "SELECT admin_email_address FROM " . $admintable . " WHERE admin_id <> " . $admin_id . "";
        $check_email_result = $dbconn->Execute($check_email_query);
        while ($check_email = $check_email_result->fields) {
          $stored_email[] = $check_email['admin_email_address'];

          // Move that ADOdb pointer!
          $check_email_result->MoveNext();
        }

        // Close result set
        $check_email_result->Close();

        if (in_array($_POST['admin_email_address'], $stored_email)) {
          oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'action=edit_process&error=email'));
        } else {
          $sql_data_array = array('admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']),
                                  'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']),
                                  'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']),
                                  'admin_password' => oos_encrypt_password(oos_db_prepare_input($_POST['admin_password'])),
                                  'admin_modified' => 'now()');

          oos_db_perform($oostable['admin'], $sql_data_array, 'update', 'admin_id = \'' . $admin_id . '\'');

        //oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], OOS_HTTP_SERVER . OOS_SHOP . 'admin/', $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

          oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'page=' . $_GET['page'] . '&mID=' . $admin_id));
        }
        break;
    }
  }
  require 'includes/header.php';
  require 'includes/account_check.js.php';
?>

<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="white-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">

				<!-- body_text //-->
      <?php if ($action == 'edit_process') { echo oos_draw_form('account', $aContents['admin_account'], 'action=save_account', 'post', 'enctype="multipart/form-data"'); } elseif ($action == 'check_account') { echo oos_draw_form('account', $aContents['admin_account'], 'action=check_password', 'post', 'enctype="multipart/form-data"'); } else { echo oos_draw_form('account', $aContents['admin_account'], 'action=check_account', 'post', 'enctype="multipart/form-data"'); } ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td valign="top">
<?php
  $my_account_query = "SELECT a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name FROM " . $oostable['admin'] . " a, " . $oostable['admin_groups'] . " g WHERE a.admin_id= " . $_SESSION['login_id'] . " AND g.admin_groups_id= " . $_SESSION['login_groups_id'] . "";
  $myAccount = $dbconn->GetRow($my_account_query);
?>
            <table border="0" width="100%" cellspacing="0" cellpadding="2" align="center">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ACCOUNT; ?>
                </td>
              </tr>
              <tr class="dataTableRow">
                <td>
                  <table border="0" cellspacing="0" cellpadding="3">
<?php
    if ( ($action == 'edit_process') && (isset($_SESSION['confirm_account'])) ) {
?>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FIRSTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo oos_draw_input_field('admin_firstname', $myAccount['admin_firstname']); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LASTNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo oos_draw_input_field('admin_lastname', $myAccount['admin_lastname']); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php if ($_GET['error']) { echo oos_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_INFO_ERROR . '</nobr>'; } else { echo oos_draw_input_field('admin_email_address', $myAccount['admin_email_address']); } ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo oos_draw_password_field('admin_password'); ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD_CONFIRM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo oos_draw_password_field('admin_password_confirm'); ?></td>
                    </tr>
<?php
    } else {
      if (isset($_SESSION['confirm_account'])) {
        unset($_SESSION['confirm_account']);
      }
?>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_FULLNAME; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_EMAIL; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_email_address']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_PASSWORD; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo TEXT_INFO_PASSWORD_HIDDEN; ?></td>
                    </tr>
                    <tr class="dataTableRowSelected">
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_GROUP; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_groups_name']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_CREATED; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_created']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGNUM; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_lognum']; ?></td>
                    </tr>
                    <tr>
                      <td class="dataTableContent"><nobr><?php echo TEXT_INFO_LOGDATE; ?>&nbsp;&nbsp;&nbsp;</nobr></td>
                      <td class="dataTableContent"><?php echo $myAccount['admin_logdate']; ?></td>
                    </tr>
<?php
  }
?>
                  </table>
                </td>
              </tr>
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td align="right"><?php if ($action == 'edit_process') { echo '<a href="' . oos_href_link_admin($aContents['admin_account']) . '">' . oos_button('back', IMAGE_BACK) . '</a> '; if (isset($_SESSION['confirm_account'])) { echo oos_submit_button('save', IMAGE_SAVE, 'onClick="validateForm();return document.returnValue"'); } } elseif ($action == 'check_account') { echo '&nbsp;'; } else { echo oos_submit_button('edit', BUTTON_EDIT); } ?></td><tr></table></td>
              </tr>
            </table>
            </td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'edit_process':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');

      $contents[] = array('text' => TEXT_INFO_INTRO_EDIT_PROCESS . oos_draw_hidden_field('id_info', $myAccount['admin_id']));
      break;

    case 'check_account':
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</b>');

      $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . oos_draw_hidden_field('id_info', $myAccount['admin_id']));
      if ($_GET['error']) {
        $contents[] = array('text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR);
      }
      $contents[] = array('align' => 'center', 'text' => oos_draw_password_field('password_confirmation'));
      $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['admin_account']) . '">' . oos_button('back', IMAGE_BACK) . '</a> ' . oos_submit_button('confirm', IMAGE_CONFIRM) . '<br />&nbsp');
      break;

    default:
      $heading[] = array('text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>');

      $contents[] = array('text' => TEXT_INFO_INTRO_DEFAULT);
      if ($myAccount['admin_email_address'] == 'none@none.com') {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br />&nbsp');
      } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1) ) {
        $contents[] = array('text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br />&nbsp');
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
    </table></form>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

	</div>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>