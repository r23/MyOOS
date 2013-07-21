<?php
/* ----------------------------------------------------------------------
   $Id: newsletters.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  require 'includes/oos_main.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'lock':
      case 'unlock':
        $newsletter_id = oos_db_prepare_input($_GET['nID']);
        $status = (($action == 'lock') ? '1' : '0');

        $newsletterstable = $oostable['newsletters'];
        $dbconn->Execute("UPDATE $newsletterstable SET locked = '" . $status . "' WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));
        break;

      case 'insert':
      case 'update':
        $newsletter_module = oos_db_prepare_input($_POST['module']);

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
          $sql_data_array = array('title' => $title,
                                  'content' => $content,
                                  'module' => $newsletter_module);

          if ($action == 'insert') {
            $sql_data_array['date_added'] = 'now()';
            $sql_data_array['status'] = '0';
            $sql_data_array['locked'] = '0';

            oos_db_perform($oostable['newsletters'], $sql_data_array);
            $newsletter_id = $dbconn->Insert_ID();
          } elseif ($action == 'update') {
            oos_db_perform($oostable['newsletters'], $sql_data_array, 'update', 'newsletters_id = \'' . oos_db_input($newsletter_id) . '\'');
          }

          oos_redirect_admin(oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $newsletter_id));
        } else {
          $action = 'new';
        }
        break;

      case 'deleteconfirm':
        $newsletter_id = oos_db_prepare_input($_GET['nID']);

        $newsletterstable = $oostable['newsletters'];
        $dbconn->Execute("DELETE FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($newsletter_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page']));
        break;

      case 'delete':
      case 'new': if (!$_GET['nID']) break;
      case 'send':
      case 'confirm_send':
        $newsletter_id = oos_db_prepare_input($_GET['nID']);

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
          oos_redirect_admin(oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']));
        }
        break;
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
<?php
  if ($action == 'new') {
    $form_action = 'insert';
    if (isset($_GET['nID'])) {
      $nID = oos_db_prepare_input($_GET['nID']);
      $form_action = 'update';

      $newsletterstable = $oostable['newsletters'];
      $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
      $newsletter = $newsletter_result->fields;

      $nInfo = new objectInfo($newsletter);
    } elseif (oos_is_not_null($_POST)) {
      $nInfo = new objectInfo($_POST);
    } else {
      $nInfo = new objectInfo(array());
    }

    $file_extension = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    $directory_array = array();
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
      $modules_array[] = array('id' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')), 'text' => substr($directory_array[$i], 0, strrpos($directory_array[$i], '.')));
    }
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('newsletter', $aFilename['newsletters'], 'page=' . $_GET['page'] . '&action=' . $form_action); if ($form_action == 'update') echo oos_draw_hidden_field('newsletter_id', $nID); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_MODULE; ?></td>
            <td class="main"><?php echo oos_draw_pull_down_menu('module', $modules_array, $nInfo->module); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSLETTER_TITLE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('title', $nInfo->title, '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_NEWSLETTER_CONTENT; ?></td>
            <td class="main"><?php echo oos_draw_textarea_field('content', 'soft', '100%', '20', $nInfo->content); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right"><?php echo (($form_action == 'insert') ? oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) : oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } elseif ($action == 'preview') {
    $nID = oos_db_prepare_input($_GET['nID']);

    $newsletterstable = $oostable['newsletters'];
    $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
    $newsletter = $newsletter_result->fields;

    $nInfo = new objectInfo($newsletter);
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
      <tr>
        <td><tt><?php echo nl2br($nInfo->content); ?></tt></td>
      </tr>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } elseif ($action == 'send') {
    $nID = oos_db_prepare_input($_GET['nID']);

    $newsletterstable = $oostable['newsletters'];
    $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
    $newsletter = $newsletter_result->fields;

    $nInfo = new objectInfo($newsletter);

    include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    include 'includes/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><?php if ($module->show_choose_audience) { echo $module->choose_audience(); } else { echo $module->confirm(); } ?></td>
      </tr>
<?php
  } elseif ($action == 'confirm') {
    $nID = oos_db_prepare_input($_GET['nID']);

    $newsletterstable = $oostable['newsletters'];
    $newsletter_result = $dbconn->Execute("SELECT title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
    $newsletter = $newsletter_result->fields;

    $nInfo = new objectInfo($newsletter);

    include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    include 'includes/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><?php echo $module->confirm(); ?></td>
      </tr>
<?php
  } elseif ($action == 'confirm_send') {
    $nID = oos_db_prepare_input($_GET['nID']);

    $newsletterstable = $oostable['newsletters'];
    $newsletter_result = $dbconn->Execute("SELECT newsletters_id, title, content, module FROM $newsletterstable WHERE newsletters_id = '" . oos_db_input($nID) . "'");
    $newsletter = $newsletter_result->fields;

    $nInfo = new objectInfo($newsletter);

    include 'includes/languages/' . $_SESSION['language'] . '/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    include 'includes/modules/newsletters/' . $nInfo->module . substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], '.'));
    $module_name = $nInfo->module;
    $module = new $module_name($nInfo->title, $nInfo->content);
?>
      <tr>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" valign="middle"><?php echo oos_image(OOS_IMAGES . 'ani_send_email.gif', IMAGE_ANI_SEND_EMAIL); ?></td>
            <td class="main" valign="middle"><b><?php echo TEXT_PLEASE_WAIT; ?></b></td>
          </tr>
        </table></td>
      </tr>
<?php
  oos_set_time_limit(0);
  flush();
  $module->send($nInfo->newsletters_id);
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><font color="#ff0000"><b><?php echo TEXT_FINISHED_SENDING_EMAILS; ?></b></font></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><?php echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWSLETTERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_SIZE; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_MODULE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SENT; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $newsletterstable = $oostable['newsletters'];
    $newsletters_result_raw = "SELECT newsletters_id, title, length(content) as content_length,
                                      module, date_added, date_sent, status, locked
                              FROM $newsletterstable
                              ORDER BY date_added desc";
    $newsletters_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsletters_result_raw, $newsletters_result_numrows);
    $newsletters_result = $dbconn->Execute($newsletters_result_raw);
    while ($newsletters = $newsletters_result->fields) {
      if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $newsletters['newsletters_id']))) && !isset($nInfo) && (substr($action, 0, 3) != 'new')) {
        $nInfo = new objectInfo($newsletters);
      }

      if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id'] . '&action=preview') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . $newsletters['title']; ?></td>
                <td class="dataTableContent" align="right"><?php echo number_format($newsletters['content_length']) . ' bytes'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $newsletters['module']; ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['status'] == '1') { echo oos_image(OOS_IMAGES . 'icons/tick.gif', ICON_TICK); } else { echo oos_image(OOS_IMAGES . 'icons/cross.gif', ICON_CROSS); } ?></td>
                <td class="dataTableContent" align="center"><?php if ($newsletters['locked'] > 0) { echo oos_image(OOS_IMAGES . 'icons/locked.gif', ICON_LOCKED); } else { echo oos_image(OOS_IMAGES . 'icons/unlocked.gif', ICON_UNLOCKED); } ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nInfo) && is_object($nInfo) && ($newsletters['newsletters_id'] == $nInfo->newsletters_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $newsletters['newsletters_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
        // Move that ADOdb pointer!
       $newsletters_result->MoveNext();
    }
    // Close result set
    $newsletters_result->Close();
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsletters_split->display_count($newsletters_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NEWSLETTERS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsletters_split->display_links($newsletters_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'action=new') . '">' . oos_image_swap_button('new_newsletter','new_newsletter_off.gif', IMAGE_NEW_NEWSLETTER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

      $contents = array('form' => oos_draw_form('newsletters', $aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $nInfo->title . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($nInfo) && is_object($nInfo)) {
        $heading[] = array('text' => '<b>' . $nInfo->title . '</b>');

        if ($nInfo->locked > 0) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=new') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . oos_image_swap_button('preview','preview_off.gif', IMAGE_PREVIEW) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=send') . '">' . oos_image_swap_button('send','send_off.gif', IMAGE_SEND) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=unlock') . '">' . oos_image_swap_button('unlock','unlock_off.gif', IMAGE_UNLOCK) . '</a>');
        } else {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=preview') . '">' . oos_image_swap_button('preview','preview_off.gif', IMAGE_PREVIEW) . '</a> <a href="' . oos_href_link_admin($aFilename['newsletters'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsletters_id . '&action=lock') . '">' . oos_image_swap_button('lock','lock_off.gif', IMAGE_LOCK) . '</a>');
        }
        $contents[] = array('text' => '<br />' . TEXT_NEWSLETTER_DATE_ADDED . ' ' . oos_date_short($nInfo->date_added));
        if ($nInfo->status == '1') $contents[] = array('text' => TEXT_NEWSLETTER_DATE_SENT . ' ' . oos_date_short($nInfo->date_sent));
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