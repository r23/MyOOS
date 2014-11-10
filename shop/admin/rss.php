<?php
/* ----------------------------------------------------------------------
   $Id: rss.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

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
 * RDS/RSS Newsfeed
 * @link http://www.oos-shop.de/
 * @package Newsfeed
 * @author r23 <info@r23.de>
 * @copyright 2003 r23
 * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 17:14:42 $
 */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';
  require 'includes/functions/function_newsfeed.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $newsfeed_id = oos_db_prepare_input($_GET['nID']);

        $sql_data_array = array('newsfeed_type' => $newsfeed_type);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          oos_db_perform($oostable['newsfeed'], $sql_data_array);
          $newsfeed_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          oos_db_perform($oostable['newsfeed'], $sql_data_array, 'update', "newsfeed_id = '" . oos_db_input($newsfeed_id) . "'");
        }

        $newsfeed_image = oos_get_uploaded_file('newsfeed_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

        if (is_uploaded_file($newsfeed_image['tmp_name'])) {
          if (!is_writeable($image_directory)) {
            if (is_dir($image_directory)) {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $image_directory), 'error');
            } else {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_DOES_NOT_EXIST, $image_directory), 'error');
            }
          } else {
            $newsfeedtable = $oostable['newsfeed'];
            $dbconn->Execute("UPDATE $newsfeedtable SET newsfeed_image = '" . $newsfeed_image['name'] . "' WHERE newsfeed_id = '" . oos_db_input($newsfeed_id) . "'");
            oos_get_copy_uploaded_file($newsfeed_image, $image_directory);
          }
        }

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $newsfeed_name_array = $_POST['newsfeed_name'];
          $newsfeed_title_array = $_POST['newsfeed_title'];
          $newsfeed_description_array = $_POST['newsfeed_description'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('newsfeed_name' => oos_db_prepare_input($newsfeed_name_array[$lang_id]),
                                  'newsfeed_title' => oos_db_prepare_input($newsfeed_title_array[$lang_id]),
                                  'newsfeed_description' => oos_db_prepare_input($newsfeed_description_array[$lang_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('newsfeed_id' => $newsfeed_id,
                                     'newsfeed_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['newsfeed_info'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['newsfeed_info'], $sql_data_array, 'update', "newsfeed_id = '" . oos_db_input($newsfeed_id) . "' and newsfeed_languages_id = '" . intval($lang_id) . "'");
          }
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $newsfeed_id));
        break;

    case 'deleteconfirm':
        $newsfeed_id = oos_db_prepare_input($_GET['nID']);

        if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
          $newsfeedtable = $oostable['newsfeed'];
          $newsfeed_result = $dbconn->Execute("SELECT newsfeed_image FROM $newsfeedtable WHERE newsfeed_id = '" . oos_db_input($newsfeed_id) . "'");
          $newsfeed = $newsfeed_result->fields;
          $image_location = OOS_ABSOLUTE_PATH . OOS_IMAGES . $newsfeed['newsfeed_image'];
          if (file_exists($image_location)) @unlink($image_location);
        }

        $newsfeedtable = $oostable['newsfeed'];
        $dbconn->Execute("DELETE FROM $newsfeedtable WHERE newsfeed_id = '" . oos_db_input($newsfeed_id) . "'");
        $newsfeed_infotable = $oostable['newsfeed_info'];
        $dbconn->Execute("DELETE FROM $newsfeed_infotable WHERE newsfeed_id = '" . oos_db_input($newsfeed_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page']));
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
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_RSS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $newsfeedtable = $oostable['newsfeed'];
  $newsfeed_infotable = $oostable['newsfeed_info'];
  $newsfeed_sql_raw = "SELECT DISTINCT n.newsfeed_id, ni.newsfeed_name, ni.newsfeed_title, ni.newsfeed_description,
                              n.newsfeed_image, n.newsfeed_type, n.date_added, n.last_modified
                       FROM $newsfeedtable n,
                            $newsfeed_infotable ni
                       WHERE n.newsfeed_id = ni.newsfeed_id 
                         AND ni.newsfeed_languages_id = '" . intval($_SESSION['language_id']) . "'
                       ORDER BY newsfeed_name";
  $newsfeed_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsfeed_sql_raw, $newsfeed_numrows);
  $newsfeed_result = $dbconn->Execute($newsfeed_sql_raw);
  while ($newsfeed = $newsfeed_result->fields) {
    if ((!isset($_GET['nID']) || (isset($_GET['nID']) && ($_GET['nID'] == $newsfeed['newsfeed_id']))) && !isset($nInfo) && (substr($action, 0, 3) != 'new')) {
      $nInfo = new objectInfo($newsfeed);
    }

    if (isset($nInfo) && is_object($nInfo) && ($newsfeed['newsfeed_id'] == $nInfo->newsfeed_id)) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $newsfeed['newsfeed_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $newsfeed['newsfeed_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $newsfeed['newsfeed_type']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nInfo) && is_object($nInfo) && ($newsfeed['newsfeed_id'] == $nInfo->newsfeed_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif'); } else { echo '<a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $newsfeed['newsfeed_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $newsfeed_result->MoveNext();
  }

  // Close result set
  $newsfeed_result->Close();
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsfeed_split->display_count($newsfeed_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_RSS); ?></td>
                    <td class="smallText" align="right"><?php echo $newsfeed_split->display_links($newsfeed_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id . '&action=new') . '">' . oos_image_swap_button('insert','insert_off.gif', IMAGE_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_RSS . '</b>');

      $contents = array('form' => oos_draw_form('newsfeed', $aFilename['rss_conf'], 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_RSS_TYPE . '<br />' . oos_draw_input_field('newsfeed_type'));
      $contents[] = array('text' => '<br />' . TEXT_RSS_IMAGE . '<br />' . oos_draw_file_field('newsfeed_image'));

      $newsfeed_inputs_name_string = '';
      $newsfeed_inputs_title_string = '';
      $newsfeed_inputs_description_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $newsfeed_inputs_name_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_name[' . $languages[$i]['id'] . ']');
        $newsfeed_inputs_title_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_title[' . $languages[$i]['id'] . ']');
        $newsfeed_inputs_description_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_description[' . $languages[$i]['id'] . ']');
      }
      $contents[] = array('text' => '<br />' . TEXT_RSS_NAME . $newsfeed_inputs_name_string);
      $contents[] = array('text' => '<br />' . TEXT_RSS_TITLE . $newsfeed_inputs_title_string);
      $contents[] = array('text' => '<br />' . TEXT_RSS_DESCRIPTION . $newsfeed_inputs_description_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_RSS . '</b>');

      $contents = array('form' => oos_draw_form('newsfeed', $aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id . '&action=save', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_RSS_TYPE . '<br />' . oos_draw_input_field('newsfeed_type', $nInfo->newsfeed_type));
      $contents[] = array('text' => '<br />' . TEXT_RSS_IMAGE . '<br />' . oos_draw_file_field('newsfeed_image') . '<br />' . $nInfo->newsfeed_image);

      $newsfeed_inputs_name_string = '';
      $newsfeed_inputs_title_string = '';
      $newsfeed_inputs_description_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $newsfeed_inputs_name_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_name[' . $languages[$i]['id'] . ']', oos_get_newsfeed_name($nInfo->newsfeed_id, $languages[$i]['id']));
        $newsfeed_inputs_title_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_title[' . $languages[$i]['id'] . ']', oos_get_newsfeed_title($nInfo->newsfeed_id, $languages[$i]['id']));
        $newsfeed_inputs_description_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_description[' . $languages[$i]['id'] . ']', oos_get_newsfeed_description($nInfo->newsfeed_id, $languages[$i]['id']));
      }
      $contents[] = array('text' => '<br />' . TEXT_RSS_NAME . $newsfeed_inputs_name_string);
      $contents[] = array('text' => '<br />' . TEXT_RSS_TITLE . $newsfeed_inputs_title_string);
      $contents[] = array('text' => '<br />' . TEXT_RSS_DESCRIPTION . $newsfeed_inputs_description_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_RSS . '</b>');

      $contents = array('form' => oos_draw_form('newsfeed', $aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $nInfo->newsfeed_name . '</b>');
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($nInfo) && is_object($nInfo)) {
        $heading[] = array('text' => '<b>' . $nInfo->newsfeed_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['rss_conf'], 'page=' . $_GET['page'] . '&nID=' . $nInfo->newsfeed_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br /><b>' . $nInfo->newsfeed_type . '</b>');
        $contents[] = array('text' => '<br />' . oos_info_image($nInfo->newsfeed_image, $nInfo->newsfeed_name));

        $newsfeed_inputs_name_string = '';
        $newsfeed_inputs_title_string = '';
        $newsfeed_inputs_description_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $newsfeed_inputs_name_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_get_newsfeed_name($nInfo->newsfeed_id, $languages[$i]['id']);
          $newsfeed_inputs_title_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_get_newsfeed_title($nInfo->newsfeed_id, $languages[$i]['id']);
          $newsfeed_inputs_description_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_get_newsfeed_description($nInfo->newsfeed_id, $languages[$i]['id']);
        }
        $contents[] = array('text' => '<br />' . TEXT_RSS_NAME . $newsfeed_inputs_name_string);
        $contents[] = array('text' => '<br />' . TEXT_RSS_TITLE . $newsfeed_inputs_title_string);
        $contents[] = array('text' => '<br />' . TEXT_RSS_DESCRIPTION . $newsfeed_inputs_description_string);

        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($nInfo->date_added));
        if (oos_is_not_null($nInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($nInfo->last_modified));

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