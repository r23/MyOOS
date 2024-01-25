<?php
/**
 * ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_account.php,v 1.29 2002/03/17 17:52:23 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$current_boxes = OOS_ABSOLUTE_PATH . 'admin/includes/boxes/';

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

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

        if (in_array($_POST['admin_email_address'], $stored_email)) {
            oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'action=edit_process&error=email'));
        } else {
            $sql_data_array = ['admin_firstname' => oos_db_prepare_input($_POST['admin_firstname']), 'admin_lastname' => oos_db_prepare_input($_POST['admin_lastname']), 'admin_email_address' => oos_db_prepare_input($_POST['admin_email_address']), 'admin_password' => oos_encrypt_password(oos_db_prepare_input($_POST['admin_password'])), 'admin_modified' => 'now()'];

            oos_db_perform($oostable['admin'], $sql_data_array, 'UPDATE', 'admin_id = \'' . $admin_id . '\'');

            oos_mail($_POST['admin_firstname'] . ' ' . $_POST['admin_lastname'], $_POST['admin_email_address'], ADMIN_EMAIL_SUBJECT, sprintf(ADMIN_EMAIL_TEXT, $_POST['admin_firstname'], OOS_HTTPS_SERVER . OOS_SHOP . OOS_ADMIN, $_POST['admin_email_address'], $hiddenPassword, STORE_OWNER), STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);

            oos_redirect_admin(oos_href_link_admin($aContents['admin_account'], 'page=' . $nPage . '&mID=' . $admin_id));
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
            
      <?php if ($action == 'edit_process') {
          echo oos_draw_form('id', 'account', $aContents['admin_account'], 'action=save_account', 'post', false, 'enctype="multipart/form-data"');
      } elseif ($action == 'check_account') {
          echo oos_draw_form('id', 'account', $aContents['admin_account'], 'action=check_password', 'post', false, 'enctype="multipart/form-data"');
      } else {
          echo oos_draw_form('id', 'account', $aContents['admin_account'], 'action=check_account', 'post', false, 'enctype="multipart/form-data"');
      } ?>

        <div class="row wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">    
<!-- body_text //-->
<div class="table-responsive">
    <table class="table w-100">
          <tr>
            <td valign="top">
<?php
  $my_account_query = "SELECT a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name FROM " . $oostable['admin'] . " a, " . $oostable['admin_groups'] . " g WHERE a.admin_id= " . $_SESSION['login_id'] . " AND g.admin_groups_id= " . $_SESSION['login_groups_id'] . "";
$myAccount = $dbconn->GetRow($my_account_query);
?>
                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_ACCOUNT; ?></th>
                        </tr>    
                    </thead>

              <tr class="dataTableRow">
                <td>
                  <table border="0" cellspacing="0" cellpadding="3">
<?php
if (($action == 'edit_process') && (isset($_SESSION['confirm_account']))) {
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
                      <td class="dataTableContent"><?php if (isset($_GET['error'])) {
                          echo oos_draw_input_field('admin_email_address', $myAccount['admin_email_address']) . ' <nobr>' . TEXT_INFO_ERROR . '</nobr>';
                      } else {
                          echo oos_draw_input_field('admin_email_address', $myAccount['admin_email_address']);
                      } ?></td>
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
    } ?>
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
                <td><table width="100%" border="0" cellspacing="0" cellpadding="3"><tr><td class="smallText" valign="top"><?php echo TEXT_INFO_MODIFIED . $myAccount['admin_modified']; ?></td><td class="text-right"><?php if ($action == 'edit_process') {
                    echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_account']) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a> ';
                    if (isset($_SESSION['confirm_account'])) {
                        echo oos_submit_button(BUTTON_SAVE);
                    }
                } elseif ($action == 'check_account') {
                    echo '&nbsp;';
                } else {
                    echo oos_submit_button(BUTTON_EDIT);
                } ?></td><tr></table></td>
              </tr>
            </table>
            </td>
<?php
  $heading = [];
$contents = [];

switch ($action) {
    case 'edit_process':
        $heading[] = ['text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>'];

        $contents[] = ['text' => TEXT_INFO_INTRO_EDIT_PROCESS . oos_draw_hidden_field('id_info', $myAccount['admin_id'])];
        break;

    case 'check_account':
        $heading[] = ['text' => '<b>&nbsp;' . TEXT_INFO_HEADING_CONFIRM_PASSWORD . '</b>'];

        $contents[] = ['text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD . oos_draw_hidden_field('id_info', $myAccount['admin_id'])];

        if (isset($_GET['error'])) {
            $contents[] = ['text' => '&nbsp;' . TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR];
        }
        $contents[] = ['align' => 'center', 'text' => oos_draw_password_field('password_confirmation')];
        $contents[] = ['align' => 'center', 'text' => '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['admin_account']) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a> ' . oos_submit_button(BUTTON_CONFIRM) . '<br>&nbsp'];
        break;

    default:
        $heading[] = ['text' => '<b>&nbsp;' . TEXT_INFO_HEADING_DEFAULT . '</b>'];

        $contents[] = ['text' => TEXT_INFO_INTRO_DEFAULT];
        if ($myAccount['admin_email_address'] == 'none@none.com') {
            $contents[] = ['text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST, $myAccount['admin_firstname']) . '<br>&nbsp'];
        } elseif (($myAccount['admin_modified'] == '0000-00-00 00:00:00') || ($myAccount['admin_logdate'] <= 1)) {
            $contents[] = ['text' => sprintf(TEXT_INFO_INTRO_DEFAULT_FIRST_TIME, $myAccount['admin_firstname']) . '<br>&nbsp'];
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
    </table></form>
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