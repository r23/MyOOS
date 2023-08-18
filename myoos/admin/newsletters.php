<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newsletters.php,v 1.15 2002/11/22 14:45:47 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$nID = filter_input(INPUT_GET, 'nID', FILTER_VALIDATE_INT);

if (!empty($action)) {
    switch ($action) {
      case 'lock':
      case 'unlock':
        $status = (($action == 'lock') ? '1' : '0');

        $newsletterstable = $oostable['newsletters'];
        $dbconn->Execute("UPDATE $newsletterstable SET locked = '" . $status . "' WHERE newsletters_id = '" . oos_db_input($nID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID));
        break;

      case 'insert':
      case 'update':
        $module = oos_db_prepare_input($_POST['module']);
        $title = oos_db_prepare_input($_POST['title']);
        $content = oos_db_prepare_input($_POST['content']);

        $newsletter_error = false;
        if (empty($title)) {
            $messageStack->add(ERROR_NEWSLETTER_TITLE, 'error');
            $newsletter_error = true;
        }
        if (empty($module)) {
            $messageStack->add(ERROR_NEWSLETTER_MODULE, 'error');
            $newsletter_error = true;
        }

        if (!$newsletter_error) {
            $sql_data_array = ['title' => $title, 'content' => $content, 'module' => $module];

            if ($action == 'insert') {
                $sql_data_array['date_added'] = 'now()';
                $sql_data_array['status'] = '0';
                $sql_data_array['locked'] = '0';

                oos_db_perform($oostable['newsletters'], $sql_data_array);
                $nID  = $dbconn->Insert_ID();
            } elseif ($action == 'update') {
                oos_db_perform($oostable['newsletters'], $sql_data_array, 'UPDATE', 'newsletters_id = \'' . oos_db_input($nID) . '\'');
            }

            oos_redirect_admin(oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID));
        } else {
            $action = 'new';
        }
        break;

      case 'deleteconfirm':
        $newsletterstable = $oostable['newsletters'];
        $dbconn->Execute("DELETE FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage));
        break;

      case 'delete':
      case 'new': if (!isset($_GET['nID'])) {
          break;
      }
      // no break
      case 'send':
      case 'confirm_send':
        $newsletter_id = oos_db_prepare_input($nID);

        $newsletterstable = $oostable['newsletters'];
        $check_result = $dbconn->Execute("SELECT locked FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");
        $check = $check_result->fields;

        if ($check['locked'] < 1) {
            switch ($action) {
            case 'delete': $error = ERROR_REMOVE_UNLOCKED_NEWSLETTER; break;
            case 'new': $error = ERROR_EDIT_UNLOCKED_NEWSLETTER; break;
            case 'send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
            case 'confirm_send': $error = ERROR_SEND_UNLOCKED_NEWSLETTER; break;
          }
            $messageStack->add_session($error, 'error');
            oos_redirect_admin(oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID));
        }
        break;
    }
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
							<?php echo '<a href="' . oos_href_link_admin($aContents['mail'], 'selected_box=tools') . '">' . BOX_HEADING_TOOLS . '</a>'; ?>
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

<?php
  if ($action == 'new') {
      $form_action = 'insert';
      if (isset($_GET['nID'])) {
          $form_action = 'update';

          $newsletterstable = $oostable['newsletters'];
          $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
          $newsletter = $newsletter_result->fields;

          $nInfo = new objectInfo($newsletter);
      } elseif (oos_is_not_null($_POST)) {
          $nInfo = new objectInfo($_POST);
      } else {
          $nInfo = new objectInfo([]);
      }

		$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
		$file_extension = substr($php_self, strrpos($php_self, '.'));

      $directory_array = [];
      if ($dir = dir('includes/modules/newsletters/')) {
          while ($file = $dir->read()) {
              if (!is_dir('includes/modules/newsletters/' . $file)) {
                  if (substr($file, strrpos($file, '.')) == $file_extension) {
                      $directory_array[] = $file;
                  }
              }
          }
          sort($directory_array);
          $dir->close();
      }

      for ($i = 0, $n = count($directory_array); $i < $n; $i++) {
          $modules_array[] = ['id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.'))];
      } ?>
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td></td>
      </tr>
      <tr><?php echo oos_draw_form('id', 'newsletter', $aContents['newsletters'], 'page=' . $nPage . '&action=' . $form_action, 'post', false);
      if ($form_action == 'update') {
          echo oos_draw_hidden_field('newsletter_id', $nID);
      } ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
            <td class="main"><?php echo oos_draw_pull_down_menu('module', $modules_array, $nInfo->module ?? ''); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_TITLE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('title', $nInfo->title ?? '', '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_NEWSLETTER_CONTENT; ?></td>
            <td class="main"><?php echo oos_draw_textarea_field('content', 'soft', '100%', '20', $nInfo->content ?? ''); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right"><?php echo(($form_action == 'insert') ? oos_submit_button(BUTTON_SAVE) : oos_submit_button(BUTTON_UPDATE)). '&nbsp;&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
	 </table>
<?php
  } elseif ($action == 'preview') {
      $newsletterstable = $oostable['newsletters'];
      $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
      $newsletter = $newsletter_result->fields;

      $nInfo = new objectInfo($newsletter); ?>
      <tr>
        <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
      </tr>
      <tr>
        <td><tt><?php echo nl2br((string) $nInfo->content); ?></tt></td>
      </tr>
      <tr>
        <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' .$nID) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
      </tr>
<?php
  } elseif ($action == 'send') {
      $newsletterstable = $oostable['newsletters'];
      $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
      $newsletter = $newsletter_result->fields;

      $nInfo = new objectInfo($newsletter);

      $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
      include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      include 'includes/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      $module_name = $nInfo->module;
      $module = new $module_name($nInfo->title, $nInfo->content); ?>
      <tr>
        <td><?php if ($module->show_choose_audience) {
          echo $module->choose_audience();
      } else {
          echo $module->confirm();
      } ?></td>
      </tr>
<?php
  } elseif ($action == 'confirm') {
      $newsletterstable = $oostable['newsletters'];
      $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
      $newsletter = $newsletter_result->fields;

      $nInfo = new objectInfo($newsletter);

      $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
      include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      include 'includes/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      $module_name = $nInfo->module;
      $module = new $module_name($nInfo->title, $nInfo->content); ?>
      <tr>
        <td><?php echo $module->confirm(); ?></td>
      </tr>
<?php
  } elseif ($action == 'confirm_send') {
      $newsletterstable = $oostable['newsletters'];
      $newsletter_result = $dbconn->Execute("SELECT newsletters_id, title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
      $newsletter = $newsletter_result->fields;

      $nInfo = new objectInfo($newsletter);

      $php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
      include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      include 'includes/modules/newsletters/' . $nInfo->module . substr($php_self, strrpos($php_self, '.'));
      $module_name = $nInfo->module;
      $module = new $module_name($nInfo->title, $nInfo->content); ?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="middle"><?php echo oos_image(OOS_IMAGES . 'ani_send_email.gif', IMAGE_ANI_SEND_EMAIL); ?></td>
            <td class="main" valign="middle"><b><?php echo TEXT_PLEASE_WAIT; ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php
  flush();
      $module->send($nInfo->newsletters_id); ?>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main"><font color="#ff0000"><b><?php echo TEXT_FINISHED_SENDING_EMAILS; ?></b></font></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td><?php echo '<a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID) . '">' . oos_button(BUTTON_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
      ?>

<!-- body_text //-->				
	<div class="table-responsive">
		<table class="table w-100">
          <tr>
            <td valign="top">
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_NEWSLETTERS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_SIZE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_MODULE; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_SENT; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $newsletterstable = $oostable['newsletters'];
      $newsletters_result_raw = "SELECT newsletters_id, title, length(content) as content_length,
                                      module, date_added, date_sent, status, locked
                              FROM $newsletterstable
                              ORDER BY date_added desc";
      $newsletters_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $newsletters_result_raw, $newsletters_result_numrows);
      $newsletters_result = $dbconn->Execute($newsletters_result_raw);
      while ($newsletters = $newsletters_result->fields) {
          if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $newsletters['newsletters_id']))) && !isset($nInfo) && (!str_starts_with((string) $action, 'new'))) {
              $nInfo = new objectInfo($newsletters);
          }

          if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id)) {
              echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '\'">' . "\n";
          } else {
              echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $newsletters['newsletters_id']) . '\'">' . "\n";
          } ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $newsletters['newsletters_id'] . '&action=preview') . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . $newsletters['title']; ?></td>
                <td class="text-right"><?php echo number_format($newsletters['content_length']) . ' bytes'; ?></td>
                <td class="text-right"><?php echo $newsletters['module']; ?></td>
                <td class="text-center"><?php if ($newsletters['status'] == '1') {
              echo '<i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i>';
          } else {
              echo oos_image(OOS_IMAGES . 'icons/cross.gif', ICON_CROSS);
          } ?></td>
                <td class="text-center"><?php if ($newsletters['locked'] > 0) {
              echo oos_image(OOS_IMAGES . 'icons/locked.gif', ICON_LOCKED);
          } else {
              echo oos_image(OOS_IMAGES . 'icons/unlocked.gif', ICON_UNLOCKED);
          } ?></td>
                <td class="text-right"><?php if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id)) {
              echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
          } else {
              echo '<a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $newsletters['newsletters_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
          } ?>&nbsp;</td>
              </tr>
<?php
        // Move that ADOdb pointer!
       $newsletters_result->MoveNext();
      } ?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsletters_split->display_count($newsletters_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsletters_split->display_links($newsletters_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aContents['newsletters'], 'action=new') . '">' . oos_button(IMAGE_NEW_NEWSLETTER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = [];
      $contents = [];

      switch ($action) {
    case 'delete':
      $heading[] = ['text' => '<b>' . $nInfo->title . '</b>'];

      $contents = ['form' => oos_draw_form('id', 'newsletters', $aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=deleteconfirm', 'post', false)];
      $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
      $contents[] = ['text' => '<br><b>' . $nInfo->title . '</b>'];
      $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
      break;

    default:
      if (isset($nInfo) && is_object($nInfo)) {
          $heading[] = ['text' => '<b>' . $nInfo->title . '</b>'];

          if ($nInfo->locked > 0) {
              $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=new') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . oos_button('preview') . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=send') . '">' . oos_button(IMAGE_SEND) . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=unlock') . '">' . oos_button('unlock') . '</a>'];
          } else {
              $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . oos_button(BUTTON_PREVIEW) . '</a> <a href="' . oos_href_link_admin($aContents['newsletters'], 'page=' . $nPage . '&nID=' . $nInfo->newsletters_id . '&action=lock') . '">' . oos_button(IMAGE_LOCK) . '</a>'];
          }
          $contents[] = ['text' => '<br>' . TEXT_NEWSLETTER_DATE_ADDED . ' ' . oos_date_short($nInfo->date_added)];
          if ($nInfo->status == '1') {
              $contents[] = ['text' => TEXT_NEWSLETTER_DATE_SENT . ' ' . oos_date_short($nInfo->date_sent)];
          }
      }
      break;
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
      } ?>
          </tr>
        </table>
	</div>
<?php
  }
?>
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