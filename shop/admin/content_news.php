<?php
/* ----------------------------------------------------------------------
   $Id: content_news.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';
  require 'includes/functions/function_news.php';

  $nPath = $_GET['nPath'];
  if (strlen($nPath) > 0) {
    $nPath_array = explode('_', $nPath);
    $current_news_category_id = $nPath_array[(count($nPath_array)-1)];
  } else {
    $current_news_category_id = 0;
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        if ( ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
          if (isset($_GET['nID'])) {
            oos_set_news_status($_GET['nID'], $_GET['flag']);
          }
    if (isset($_GET['ncID'])) {
            oos_set_news_categories_status($_GET['ncID'], $_GET['flag']);
          }
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $_GET['nPath']));
        break;

      case 'new_news_category':
      case 'edit_news_category':
        if (ALLOW_NEWS_CATEGORY_DESCRIPTIONS == 'true')
          $action = $action . '_ACD';
        break; 

      case 'insert_news_category':
      case 'update_news_category':
        if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
          $action = 'edit_news_category_ACD';
        } else {
          if ($news_categories_id == '') {
            $news_categories_id = oos_db_prepare_input($_GET['ncID']);
          }
          $sql_data_array = array('sort_order' => $sort_order, 'news_categories_status' => $news_categories_status);

          if ($action == 'insert_news_category') {
            $insert_sql_data = array('parent_id' => $current_news_category_id,
                                     'date_added' => 'now()');

           $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

           oos_db_perform($oostable['news_categories'], $sql_data_array);
           $news_categories_id = $dbconn->Insert_ID();
          } elseif ($action == 'update_news_category') {
            $update_sql_data = array('last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            oos_db_perform($oostable['news_categories'], $sql_data_array, 'update', 'news_categories_id = \'' . $news_categories_id . '\'');
          }

          $languages = oos_get_languages();
          for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $news_categories_name_array = $_POST['news_categories_name'];
            $lang_id = $languages[$i]['id'];

            $sql_data_array = array('news_categories_name' => oos_db_prepare_input($news_categories_name_array[$lang_id]));
            if (ALLOW_NEWS_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('news_categories_name' => oos_db_prepare_input($_POST['news_categories_name'][$lang_id]),
                                      'news_categories_heading_title' => oos_db_prepare_input($_POST['news_categories_heading_title'][$lang_id]),
                                      'news_categories_description' => oos_db_prepare_input($_POST['news_categories_description'][$lang_id]));
            }
            if ($action == 'insert_news_category') {
              $insert_sql_data = array('news_categories_id' => $news_categories_id,
                                       'news_categories_languages_id' => $lang_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              oos_db_perform($oostable['news_categories_description'], $sql_data_array);
            } elseif ($action == 'update_news_category') {
              oos_db_perform($oostable['news_categories_description'], $sql_data_array, 'update', 'news_categories_id = \'' . $news_categories_id . '\' AND news_categories_languages_id = \'' . $languages[$i]['id'] . '\'');
            }
          }
          if (ALLOW_NEWS_CATEGORY_DESCRIPTIONS == 'true') {
            $dbconn->Execute("UPDATE " . $oostable['news_categories'] . " SET news_categories_image = '" . $_POST['news_categories_image'] . "' WHERE news_categories_id = '" .  oos_db_input($news_categories_id) . "'");
            $news_categories_image = '';
          } else {
            $news_categories_image = oos_get_uploaded_file('news_categories_image');
            $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

            if (is_uploaded_file($news_categories_image['tmp_name'])) {
              $dbconn->Execute("UPDATE " . $oostable['news_categories'] . " SET news_categories_image = '" . $news_categories_image['name'] . "' WHERE news_categories_id = '" . oos_db_input($news_categories_id) . "'");
              oos_get_copy_uploaded_file($news_categories_image, $image_directory);
            }
          }
          oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $news_categories_id));
        }
        break;

      case 'delete_news_category_confirm':
        if (isset($_POST['news_categories_id'])) {
          $news_categories = oos_get_news_category_tree($news_categories_id, '', '0', '', true);
          $news = array();
          $news_delete = array();

          for ($i = 0, $n = count($news_categories); $i < $n; $i++) {
            $news_ids_result = $dbconn->Execute("SELECT news_id FROM " . $oostable['news_to_categories'] . " WHERE news_categories_id = '" . $news_categories[$i]['id'] . "'");
            while ($news_ids = $news_ids_result->fields) {
              $news[$news_ids['news_id']]['categories'][] = $news_categories[$i]['id'];

              // Move that ADOdb pointer!
              $news_ids_result->MoveNext();
            }

            // Close result set
            $news_ids_result->Close();
          }

          reset($news);
          while (list($key, $value) = each($news)) {
            $news_category_ids = '';
            for ($i = 0, $n = count($value['categories']); $i < $n; $i++) {
              $news_category_ids .= '\'' . $value['categories'][$i] . '\', ';
            }
            $news_category_ids = substr($news_category_ids, 0, -2);

            $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . $key . "' AND news_categories_id not in (" . $news_category_ids . ")");
            $check = $check_result->fields;
            if ($check['total'] < '1') {
              $news_delete[$key] = $key;
            }
          }

          // Removing categories can be a lengthy process
          oos_set_time_limit(0);
          for ($i = 0, $n = count($news_categories); $i < $n; $i++) {
            oos_remove_newsCategory($news_categories[$i]['id']);
          }

          reset($news_delete);
          while (list($key) = each($news_delete)) {
            oos_remove_news($key);
          }
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath));
        break;

      case 'delete_news_confirm':
        if ( ($_POST['news_id']) && ($_POST['news_categories']) && (is_array($_POST['news_categories'])) ) {
          $news_categories = $_POST['news_categories'];

          for ($i = 0, $n = count($news_categories); $i < $n; $i++) {
            $dbconn->Execute("DELETE FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . (int)$news_id . "' AND news_categories_id = '" . (int)$news_categories[$i] . "'");
          }

          $news_categories = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . (int)$news_id . "'");

          if ($news_categories->fields['total'] == '0') {
            oos_remove_news($news_id);
          }
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath));
        break;

      case 'move_category_confirm':
        if ( ($_POST['news_categories_id']) && ($_POST['news_categories_id'] != $_POST['move_to_category_id']) ) {
          $new_parent_id = $move_to_category_id;
          $dbconn->Execute("UPDATE " . $oostable['news_categories'] . " SET parent_id = '" . oos_db_input($new_parent_id) . "', last_modified = now() WHERE news_categories_id = '" . oos_db_input($news_categories_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $new_parent_id . '&ncID=' . $news_categories_id));
        break;

      case 'move_news_confirm':
        $news_id = oos_db_prepare_input($_POST['news_id']);
        $new_parent_id = oos_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . oos_db_input($news_id) . "' AND news_categories_id = '" . oos_db_input($new_parent_id) . "'");
        $duplicate_check = $duplicate_check_result->fields;
        if ($duplicate_check['total'] < 1) $dbconn->Execute("UPDATE " . $oostable['news_to_categories'] . " SET news_categories_id = '" . oos_db_input($new_parent_id) . "' WHERE news_id = '" . oos_db_input($news_id) . "' AND news_categories_id = '" . $current_news_category_id . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $new_parent_id . '&nID=' . $news_id));
        break;

      case 'insert_news':
      case 'update_news':
  if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
          $action = 'new_news';
        } else {
          $news_id = oos_db_prepare_input($_GET['nID']);
          $news_expires_date = oos_db_prepare_input($_POST['news_expires_date ']);

          $news_expires_date = (date('Y-m-d') < $news_expires_date ) ? $news_expires_date : 'null';

          $sql_data_array = array('news_image' => (($_POST['news_image'] == 'none') ? '' : oos_db_prepare_input($_POST['news_image'])),
                                  'news_expires_date ' => $news_expires_date ,
                                  'news_status' => oos_db_prepare_input($_POST['news_status']),
                                  'newsfeed_categories_id' => oos_db_prepare_input($_POST['newsfeed_categories_id']),
                                  );

          if ($action == 'insert_news') {
            $insert_sql_data = array('news_date_added' => 'now()',
                                     'news_added_by' => $_SESSION['login_id']);
            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
            oos_db_perform($oostable['news'], $sql_data_array);
            $news_id = $dbconn->Insert_ID();
            $dbconn->Execute("INSERT INTO " . $oostable['news_to_categories'] . " (news_id, news_categories_id) VALUES ('" . $news_id . "', '" . $current_news_category_id . "')");
          } elseif ($action == 'update_news') {
            $update_sql_data = array('news_last_modified' => 'now()',
                                     'news_modified_by' => $_SESSION['login_id']);

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            oos_db_perform($oostable['news'], $sql_data_array, 'update', 'news_id = \'' . oos_db_input($news_id) . '\'');
          }

          $languages = oos_get_languages();
          for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $lang_id = $languages[$i]['id'];

            $sql_data_array = array('news_name' => oos_db_prepare_input($_POST['news_name'][$lang_id]),
                                    'news_description' => oos_db_prepare_input($_POST['news_description_' .$languages[$i]['id']]),
                                    'news_url' => oos_db_prepare_input($_POST['news_url'][$lang_id]));

            if ($action == 'insert_news') {
              $insert_sql_data = array('news_id' => $news_id,
                                       'news_languages_id' => $lang_id);
              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              oos_db_perform($oostable['news_description'], $sql_data_array);
            } elseif ($action == 'update_news') {
              oos_db_perform($oostable['news_description'], $sql_data_array, 'update', 'news_id = \'' . oos_db_input($news_id) . '\' AND news_languages_id = \'' . $lang_id . '\'');
            }
          }
          oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $news_id));
        }
        break;

      case 'copy_to_confirm':
        if (isset($_POST['news_id']) && isset($_POST['news_categories_id'])) {
          $news_id = oos_db_prepare_input($_POST['news_id']);
          $news_categories_id = oos_db_prepare_input($_POST['news_categories_id']);

          if ($_POST['copy_as'] == 'link') {
            if ($_POST['news_categories_id'] != $current_news_category_id) {
              $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['news_to_categories'] . " WHERE news_id = '" . oos_db_input($news_id) . "' AND news_categories_id = '" . oos_db_input($news_categories_id) . "'");
              if ($check_result->fields['total'] < '1') {
                $dbconn->Execute("INSERT INTO " . $oostable['news_to_categories'] . " (news_id, news_categories_id) VALUES ('" . oos_db_input($news_id) . "', '" . oos_db_input($news_categories_id) . "')");
              }
            } else {
              $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
            }
          } elseif ($_POST['copy_as'] == 'duplicate') {
            $news_result = $dbconn->Execute("SELECT news_id, news_image, news_date_added, news_added_by,
                                                news_expires_date, news_status, newsfeed_categories_id
                                          FROM " . $oostable['news'] . " 
                                          WHERE news_id = '" . oos_db_input($news_id) . "'");
            $news = $news_result->fields;
            $dbconn->Execute("INSERT INTO " . $oostable['news'] . " 
                         (news_image, 
                          news_date_added, 
                          news_added_by,
                          news_last_modified, 
                          news_modified_by, 
                          news_expires_date,
                          news_status, 
                          newsfeed_categories_id)
                          VALUES ('" . $news['news_image'] . "',
                                  '" . $news['news_date_added'] . "',
                                  '" . $news['news_added_by'] . "',
                                  now(),
                                  '" . $_SESSION['login_id'] . "',
                                  '" . $news['news_expires_date '] . "',
                                  '" . $news['news_status'] . "',
                                  '" . $news['newsfeed_categories_id'] . "')");
            $dup_news_id = $dbconn->Insert_ID();

            $description_result = $dbconn->Execute("SELECT news_languages_id, news_name, news_description, news_url
                                                FROM " . $oostable['news_description'] . "
                                                WHERE news_id = '" . oos_db_input($news_id) . "'");
            while ($description = $description_result->fields) {
              $dbconn->Execute("INSERT INTO " . $oostable['news_description'] . "
                            (news_id,
                             news_languages_id,
                             news_name,
                             news_description,
                             news_url,
                             news_viewed)
                             VALUES ('" . $dup_news_id . "',
                                     '" . $description['news_languages_id'] . "',
                                     '" . oos_db_input($description['news_name']) . "',
                                     '" . oos_db_input($description['news_description']) . "',
                                     '" . $description['news_url'] . "',
                                     '0')");

              // Move that ADOdb pointer!
              $description_result->MoveNext();
            }
            $dbconn->Execute("INSERT INTO " . $oostable['news_to_categories'] . "
                          (news_id,
                           news_categories_id) 
                           VALUES ('" . $dup_news_id . "',
                                   '" . oos_db_input($news_categories_id) . "')");
            $news_id = $dup_news_id;
          }
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['content_news'], 'nPath=' . $news_categories_id . '&nID=' . $news_id));
        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
    if (!is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
  $no_js_general = true;
  require 'includes/oos_header.php';

?>
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if ($action == 'new_news_category_ACD' || $action == 'edit_news_category_ACD') {
      if (isset($_GET['ncID']) && empty($_POST)) {
        $news_categories_result = $dbconn->Execute("SELECT 
                                                   n.news_categories_id, ncd.news_categories_name, ncd.news_categories_heading_title, 
                                                   ncd.news_categories_description, n.news_categories_image, n.parent_id, n.sort_order, 
                                                   n.date_added, n.last_modified 
                                               FROM 
                                                  " . $oostable['news_categories'] . " n, 
                                                  " . $oostable['news_categories_description'] . " ncd 
                                               WHERE
                                                  n.news_categories_id = '" . $_GET['ncID'] . "' AND 
                                                  n.news_categories_id = ncd.news_categories_id AND 
                                                  ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "' 
                                               ORDER BY 
                                                  n.sort_order, ncd.news_categories_name");
        $news_category = $news_categories_result->fields;

        $cInfo = new objectInfo($news_category);
      } elseif (oos_is_not_null($_POST)) {
        $cInfo = new objectInfo($_POST);
        $news_categories_name = $_POST['news_categories_name'];
        $news_categories_heading_title = $_POST['news_categories_heading_title'];
        $news_categories_description = $_POST['news_categories_description'];
        $news_categories_url = $_POST['categories_url'];
      } else {
        $cInfo = new objectInfo(array());
      }

      $languages = oos_get_languages();

      $text_new_or_edit = ($action=='new_news_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo sprintf($text_new_or_edit, oos_output_generated_news_category_path($current_news_category_id)); ?></td>
              <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
        </tr>
        <tr><?php echo oos_draw_form('new_news_category', $aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $_GET['ncID'] . '&action=new_news_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
          <td><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
              <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_categories_name[' . $languages[$i]['id'] . ']', (($news_categories_name[$languages[$i]['id']]) ? stripslashes($news_categories_name[$languages[$i]['id']]) : oos_get_news_categories_title($cInfo->news_categories_id, $languages[$i]['id']))); ?></td>
            </tr>
<?php
      }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
              <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_categories_heading_title[' . $languages[$i]['id'] . ']', (($news_categories_name[$languages[$i]['id']]) ? stripslashes($news_categories_name[$languages[$i]['id']]) : oos_get_news_category_heading_title($cInfo->news_categories_id, $languages[$i]['id']))); ?></td>
            </tr>
<?php
      }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('news_categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($news_categories_description[$languages[$i]['id']]) ? stripslashes($news_categories_description[$languages[$i]['id']]) : oos_get_news_category_description($cInfo->news_categories_id, $languages[$i]['id']))); ?></td>
                </tr>
              </table></td>
            </tr>
<?php
      }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
            <tr>
              <td class="main"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
              <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_file_field('news_categories_image') . '<br />' . oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . $cInfo->news_categories_image . oos_draw_hidden_field('categories_previous_image', $cInfo->news_categories_image); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
              <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="main" align="right"><?php echo oos_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . oos_draw_hidden_field('parent_id', $cInfo->parent_id) . oos_image_swap_submits('preview','preview_off.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $_GET['ncID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
        </form></tr>
<?php 
  } elseif ($action == 'new_news_category_preview') {
    if (oos_is_not_null($_POST)) {
      $cInfo = new objectInfo($_POST);
      $news_categories_name = $_POST['news_categories_name'];
      $news_categories_heading_title = $_POST['news_categories_heading_title'];
      $news_categories_description = $_POST['news_categories_description'];

// copy image only if modified
      $news_categories_image = oos_get_uploaded_file('news_categories_image');
      $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

      if (is_uploaded_file($news_categories_image['tmp_name'])) {
        oos_get_copy_uploaded_file($news_categories_image, $image_directory);
        $news_categories_image_name = $news_categories_image['name'];
      } else {
        $news_categories_image_name = $_POST['categories_previous_image'];
      }
    } else {
      $news_category_result = $dbconn->Execute("SELECT 
                                          c.news_categories_id, cd.news_categories_languages_id, cd.news_categories_name, 
                                          cd.news_categories_heading_title, cd.news_categories_description, 
                                          c.news_categories_image, c.sort_order, c.date_added, c.last_modified 
                                      FROM 
                                          " . $oostable['news_categories'] . " c, 
                                          " . $oostable['news_categories_description'] . " cd 
                                      WHERE 
                                          c.news_categories_id = cd.news_categories_id AND
                                          c.news_categories_id = '" . $_GET['ncID'] . "'");
      $news_category = $news_category_result->fields;

      $cInfo = new objectInfo($news_category);
      $news_categories_image_name = $cInfo->news_categories_image;
    }

    $form_action = ($_GET['ncID']) ? 'update_news_category' : 'insert_news_category';

    echo oos_draw_form($form_action, $aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $_GET['ncID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = oos_get_languages();
    for ($i=0; $i < count($languages); $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $cInfo->news_categories_name = oos_get_news_categories_title($cInfo->news_categories_id, $languages[$i]['id']);
        $cInfo->news_categories_heading_title = oos_get_news_category_heading_title($cInfo->news_categories_id, $languages[$i]['id']);
        $cInfo->news_categories_description = oos_get_news_category_description($cInfo->news_categories_id, $languages[$i]['id']);
      } else {
        $cInfo->news_categories_name = oos_db_prepare_input($news_categories_name[$languages[$i]['id']]);
        $cInfo->news_categories_heading_title = oos_db_prepare_input($news_categories_heading_title[$languages[$i]['id']]);
        $cInfo->news_categories_description = oos_db_prepare_input($news_categories_description[$languages[$i]['id']]);
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . $cInfo->news_categories_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . $news_categories_image_name, $cInfo->news_categories_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') . $cInfo->news_categories_description; ?></td>
      </tr>

<?php
    }
    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
      if (isset($_GET['origin'])) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else {
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = $aFilename['content_news'];
        $back_url_params = 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params, 'NONSSL') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (!is_array($_POST[$key])) {
          echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = oos_get_languages();
      for ($i=0; $i < count($languages); $i++) {
        echo oos_draw_hidden_field('news_categories_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_categories_name[$languages[$i]['id']])));
        echo oos_draw_hidden_field('news_categories_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_categories_heading_title[$languages[$i]['id']])));
        echo oos_draw_hidden_field('news_categories_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_categories_description[$languages[$i]['id']])));
      }
      echo oos_draw_hidden_field('X_news_categories_image', stripslashes($news_categories_image_name));
      echo oos_draw_hidden_field('news_categories_image', stripslashes($news_categories_image_name));

      echo oos_image_swap_submits('back','back_off.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['ncID'])) {
        echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE);
      } else {
        echo oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $_GET['ncID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }
  } elseif ($action == 'new_news') {
    if (isset($_GET['nID']) && empty($_POST)) {
      $news_result = $dbconn->Execute("SELECT 
                                      nd.news_name, nd.news_description, nd.news_url, n.news_id,
                                      n.news_image, n.news_date_added, n.news_added_by,
                                      n.news_last_modified, news_modified_by,
                                      date_format(n.news_expires_date, '%Y-%m-%d') AS news_expires_date,
                                      n.news_status, n.newsfeed_categories_id
                                  FROM 
                                     " . $oostable['news'] . " n, 
                                     " . $oostable['news_description'] . " nd 
                                  WHERE 
                                     n.news_id = '" . $_GET['nID'] . "' AND
                                     n.news_id = nd.news_id AND
                                     nd.news_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $news = $news_result->fields;

      $nInfo = new objectInfo($news);
    } elseif (oos_is_not_null($_POST)) {
      $nInfo = new objectInfo($_POST);
      $news_name = $_POST['news_name'];
      $news_description = $_POST['news_description'];
      $news_url = $_POST['news_url'];
    } else {
      $nInfo = new objectInfo(array());
      $nInfo->news_status = '1';
    }

    $newsfeed_array = array();
    $newsfeed_array = array(array('id' => '', 'text' => TEXT_NONE));
    $newsfeed_result = $dbconn->Execute("SELECT newsfeed_categories_id, newsfeed_categories_name FROM " . $oostable['newsfeed_categories'] . " WHERE newsfeed_categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY newsfeed_categories_name");
    while ($newsfeed = $newsfeed_result->fields) {
      $newsfeed_array[] = array('id' => $newsfeed['newsfeed_categories_id'],
                                'text' => $newsfeed['newsfeed_categories_name']);

      // Move that ADOdb pointer!
      $newsfeed_result->MoveNext();
    }

    // Close result set
    $newsfeed_result->Close();

    $languages = oos_get_languages();

    if (OOS_SPAW == 'true') {
      include 'includes/classes/spaw/spaw_control.class.php';
    } elseif (OOS_SPAW == 'fck') {
      include 'includes/classes/fckeditor/fckeditor.php';
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
   var dateExpires = new ctlSpiffyCalendarBox("dateExpires", "new_news", "news_expires_date","btnDate1","<?php echo $nInfo->news_expires_date; ?>",scBTNMODE_CUSTOMBLUE);
</script>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_NEWS, oos_output_generated_news_category_path($current_news_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('new_news', $aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $_GET['nID'] . '&action=new_news_preview', 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_NEWS_NAME; ?></td>
            <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_name[' . $languages[$i]['id'] . ']', (($news_name[$languages[$i]['iso_639_2']]) ? stripslashes($news_name[$languages[$i]['id']]) : oos_get_news_title($nInfo->news_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_NEWS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">
<?php 
      if (OOS_SPAW == 'true') {
        $sw = new SPAW_Wysiwyg('news_description_' . $languages[$i]['id'] /*name*/,(($_POST['news_description_' .$languages[$i]['id']]) ? stripslashes($_POST['news_description_' .$languages[$i]['id']]) : oos_get_news_description($nInfo->news_id, $languages[$i]['id'])) /*value*/,
                             $languages[$i]['iso_639_1'] /*language*/, 'sidetable' /*toolbar mode*/, 'default' /*theme*/,
                             '550px' /*width*/, '350px' /*height*/);
        $sw->show();
      } elseif (OOS_SPAW == 'fck') {
        $oFCKeditor = new FCKeditor('news_description_' . $languages[$i]['id']);
        $oFCKeditor->BasePath = 'includes/classes/fckeditor/';
        $oFCKeditor->Config['AutoDetectLanguage'] = false;
        $oFCKeditor->Config['DefaultLanguage'] = $languages[$i]['iso_639_1'];
        $oFCKeditor->Width = '550';
        $oFCKeditor->Height = '350';
        $oFCKeditor->Config['SkinPath'] = 'skins/silver/' ;
        $oFCKeditor->ToolbarSet = 'Oos';
        $oFCKeditor->Value = (($_POST['news_description_' .$languages[$i]['id']]) ? stripslashes($_POST['news_description_' .$languages[$i]['id']]) : oos_get_news_description($nInfo->news_id, $languages[$i]['id']));
        $oFCKeditor->Create();
      } else {
        echo oos_draw_textarea_field('news_description_' . $languages[$i]['id'], 'soft', '70', '15', (($_POST['news_description_' .$languages[$i]['id']]) ? stripslashes($_POST['news_description_' .$languages[$i]['id']]) : oos_get_news_description($nInfo->news_id, $languages[$i]['id'])));
      }
?>
                 </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEW_DATE_EXPIRES; ?><br /><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateExpires.writeControl(); dateExpires.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWSFEED_CATEGORIES; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('newsfeed_categories_id', $newsfeed_array, $pInfo->newsfeed_categories_id); ?></td>
          </tr>
           <tr>
             <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_NEWS_IMAGE; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_file_field('news_image') . ' &nbsp; Current:' . oos_draw_separator('trans.gif', '2', '15') . '&nbsp;' . $nInfo->news_image . oos_draw_hidden_field('news_previous_image', $nInfo->news_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_NEWS_URL . '<br /><small>' . TEXT_NEWS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_url[' . $languages[$i]['id'] . ']', (($news_url[$languages[$i]['id']]) ? stripslashes($news_url[$languages[$i]['id']]) : oos_get_news_url($nInfo->news_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo oos_draw_hidden_field('news_date_added', (($nInfo->news_date_added) ? $nInfo->news_date_added : date('Y-m-d'))) . oos_image_swap_submits('preview','preview_off.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
      </form></tr>
<?php
  } elseif ($action == 'new_news_preview') {
    if (oos_is_not_null($_POST)) {
      $nInfo = new objectInfo($_POST);
      $news_name = $_POST['news_name'];
      $news_url = $_POST['news_url'];

      $news_image = oos_get_uploaded_file('news_image');
      $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

      if (is_uploaded_file($news_image['tmp_name'])) {
  oos_get_copy_uploaded_file($news_image, $image_directory);
  $news_image_name = $news_image['name'];
      } else {
        $news_image_name = $_POST['news_previous_image'];
      }
    } else {
      $news_result = $dbconn->Execute("SELECT 
                                       nd.news_name, nd.news_description, nd.news_url, 
                                       n.news_id, n.news_image, n.news_date_added, n.news_added_by,
                                       n.news_last_modified, n.news_modified_by,
                                       date_format(n.news_expires_date , '%Y-%m-%d') as news_expires_date ,
                                       n.news_status, n.newsfeed_categories_id
                                    FROM 
                                       " . $oostable['news'] . " n, 
                                       " . $oostable['news_description'] . " nd 
                                    WHERE 
                                       n.news_id = '" . $_GET['nID'] . "' AND
                                       n.news_id = nd.news_id AND
                                       nd.news_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $news = $news_result->fields;

      $nInfo = new objectInfo($news);
      $news_image_name = $nInfo->news_image;
    }

    $form_action = ($_GET['nID']) ? 'update_news' : 'insert_news';

    echo oos_draw_form($form_action, $aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $_GET['nID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = oos_get_languages();
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $nInfo->news_name = oos_get_news_title($nInfo->news_id, $languages[$i]['id']);
        $nInfo->news_description = oos_get_news_description($nInfo->news_id, $languages[$i]['id']);
        $nInfo->news_url = oos_get_news_url($nInfo->news_id, $languages[$i]['id']);
      } else {
        $nInfo->news_name = oos_db_prepare_input($news_name[$languages[$i]['id']]);
        $nInfo->news_description = oos_db_prepare_input($_POST['news_description_' .$languages[$i]['id']]);
        $nInfo->news_url = oos_db_prepare_input($news_url[$languages[$i]['id']]);
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . $nInfo->news_name; ?></td>  
      <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><a href="javascript:popupImageWindow('<?php echo oos_href_link_admin($aFilename['popup_image_news'], 'n_image=' . $news_image_name . '&title=' . $nInfo->news_name); ?>')"><?php echo oos_image(OOS_SHOP_IMAGES . $news_image_name, $nInfo->news_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'align="right" hspace="5" vspace="5"') .'</a>' . $nInfo->news_description; ?></td>
      </tr>
<?php
      if ($nInfo->news_url) {
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_NEWS_MORE_INFORMATION, $nInfo->news_url); ?></td>
      </tr>
<?php
      }
      if ($nInfo->news_expires_date  > date('Y-m-d')) {
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_NEWS_DATE_EXPIRES, oos_date_long($nInfo->news_expires_date )); ?></td>
      </tr>
<?php
      }
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_NEWS_DATE_ADDED, oos_date_long($nInfo->news_date_added)); ?></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
<?php
    }
    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
      if (isset($_GET['origin'])) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else {
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = $aFilename['content_news'];
        $back_url_params = 'nPath=' . $nPath . '&nID=' . $nInfo->news_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params, 'NONSSL') . '">' . oos_image_swap_button('back','back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (!is_array($_POST[$key])) {
          echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        echo oos_draw_hidden_field('news_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_name[$languages[$i]['id']])));
        echo oos_draw_hidden_field('news_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_description[$languages[$i]['id']])));
        echo oos_draw_hidden_field('news_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($news_url[$languages[$i]['id']])));
      }
      echo oos_draw_hidden_field('news_image', stripslashes($news_image_name));
      echo oos_image_swap_submits('back','back_off.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['nID'])) {
        echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE);
      } else {
        echo oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $_GET['nID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo oos_draw_form('search', $aFilename['content_news'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . oos_draw_input_field('search', $_GET['search']); ?></td>
              </form></tr>
              <tr><?php echo oos_draw_form('goto', $aFilename['content_news'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_GOTO . ' ' . oos_draw_pull_down_menu('nPath', oos_get_news_category_tree(), $current_news_category_id, 'onChange="this.form.submit();"'); ?></td>
              </form></tr>            
            </table></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_NEWS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_AUTHOR; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PUBLISHED; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $news_categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $news_categories_result = $dbconn->Execute("SELECT nc.news_categories_id, ncd.news_categories_name, nc.news_categories_image, nc.parent_id, nc.sort_order, nc.date_added, nc.last_modified, nc.news_categories_status FROM " . $oostable['news_categories'] . " nc, " . $oostable['news_categories_description'] . " ncd WHERE nc.news_categories_id = ncd.news_categories_id AND ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "' AND ncd.news_categories_name like '%" . $_GET['search'] . "%' ORDER BY nc.sort_order, ncd.news_categories_name");
    } else {
      $news_categories_result = $dbconn->Execute("SELECT nc.news_categories_id, ncd.news_categories_name, nc.news_categories_image, nc.parent_id, nc.sort_order, nc.date_added, nc.last_modified, nc.news_categories_status FROM " . $oostable['news_categories'] . " nc, " . $oostable['news_categories_description'] . " ncd WHERE nc.parent_id = '" . $current_news_category_id . "' AND nc.news_categories_id = ncd.news_categories_id AND ncd.news_categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY nc.sort_order, ncd.news_categories_name");
    }
    while ($news_categories = $news_categories_result->fields) {
      $news_categories_count++;
      $rows++;

// Get parent_id for subcategories if search 
      if (isset($_GET['search'])) $nPath= $news_categories['parent_id'];

      if ((!isset($_GET['ncID']) && !isset($_GET['nID']) || (isset($_GET['ncID']) && ($_GET['ncID'] == $news_categories['news_categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $news_category_childs = array('childs_count' => oos_childs_in_news_category_count($news_categories['news_categories_id']));
        $news_category_news = array('news_count' => oos_news_in_category_count($news_categories['news_categories_id']));

        $cInfo_array = array_merge($news_categories, $news_category_childs, $news_category_news);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($news_categories['news_categories_id'] == $cInfo->news_categories_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['content_news'], oos_get_news_path($news_categories['news_categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $news_categories['news_categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['content_news'], oos_get_news_path($news_categories['news_categories_id'])) . '">' . oos_image(OOS_IMAGES . 'icons/folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . ' #' . $news_categories['news_categories_id'] . ' ' . $news_categories['news_categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                 <td class="dataTableContent" align="center">
 <?php
       if ($news_categories['news_categories_status'] == '1') {
         echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'action=setflag&flag=0&ncID=' . $news_categories['news_categories_id'] . '&nPath=' . $nPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
       } else {
         echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'action=setflag&flag=1&ncID=' . $news_categories['news_categories_id'] . '&nPath=' . $nPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
       }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($news_categories['news_categories_id'] == $cInfo->news_categories_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $news_categories['news_categories_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $news_categories_result->MoveNext();
    }

    $news_count = 0;
    if (isset($_GET['search'])) {
      $news_result = $dbconn->Execute("SELECT n.news_id, nd.news_name, n.news_image, n.news_date_added, n.news_added_by, n.news_last_modified, n.news_modified_by, n.news_expires_date, n.news_status, n.newsfeed_categories_id FROM " . $oostable['news'] . " n, " . $oostable['news_description'] . " nd, " . $oostable['news_to_categories'] . " n2c WHERE n.news_id = nd.news_id AND nd.news_languages_id = '" . intval($_SESSION['language_id']) . "' AND n.news_id = n2c.news_id AND nd.news_name like '%" . $_GET['search'] . "%' ORDER BY nd.news_name");
    } else {
      $news_result = $dbconn->Execute("SELECT n.news_id, nd.news_name, n.news_image, n.news_date_added, n.news_added_by, n.news_last_modified, n.news_modified_by, n.news_expires_date, n.news_status, n.newsfeed_categories_id FROM " . $oostable['news'] . " n, " . $oostable['news_description'] . " nd, " . $oostable['news_to_categories'] . " n2c WHERE n.news_id = nd.news_id AND nd.news_languages_id = '" . intval($_SESSION['language_id']) . "' AND n.news_id = n2c.news_id AND n2c.news_categories_id = '" . $current_news_category_id . "' ORDER BY nd.news_name");
    }

    while ($news = $news_result->fields) {
      $news_count++;
      $rows++;

// Get news_categories_id for news if search 
      if (isset($_GET['search'])) $nPath=$news['news_categories_id'];

      if ((!isset($_GET['nID']) && !isset($_GET['ncID']) || (isset($_GET['nID']) && ($_GET['nID'] == $news['news_id']))) && !isset($nInfo) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
// find out the rating average from customer reviews
        $reviews_result = $dbconn->Execute("SELECT (avg(news_reviews_rating) / 5 * 100) as average_rating FROM " . $oostable['news_reviews'] . " WHERE news_id = '" . $news['news_id'] . "'");
        $reviews = $reviews_result->fields;
        $nInfo_array = array_merge($news, $reviews);
        $nInfo = new objectInfo($nInfo_array);
      }

      if (isset($nInfo) && is_object($nInfo) &&($news['news_id'] == $nInfo->news_id)) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $news['news_id'] . '&action=new_news_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $news['news_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $news['news_id'] . '&action=new_news_preview&read=only') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . '#' . $news['news_id'] . ' ' . $news['news_name']; ?></td>
                <td class="dataTableContent"><?php echo oos_get_news_author($news['news_added_by']) ?></td>
                <td class="dataTableContent" align="center">
<?php
    if ($news['news_status'] == '0') {
      echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'action=setflag&flag=1&nID=' . $news['news_id'] . '&nPath=' . $nPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'action=setflag&flag=0&nID=' . $news['news_id'] . '&nPath=' . $nPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($nInfo) && is_object($nInfo) && ($news['news_id'] == $nInfo->news_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $news['news_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $news_result->MoveNext();
    }

    if ($nPath_array) {
      $nPath_back = '';
      for($i = 0, $n = count($nPath_array) - 1; $i < $n; $i++) {
        if ($nPath_back == '') {
          $nPath_back .= $nPath_array[$i];
        } else {
          $nPath_back .= '_' . $nPath_array[$i];
        }
      }
    }

    $nPath_back = ($nPath_back) ? 'nPath=' . $nPath_back : '';
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TABLE_NEWS_CATEGORIES . '&nbsp;' . $news_categories_count . '<br />' . TEXT_NEWS . '&nbsp;' . $news_count; ?></td>
                    <td align="right" class="smallText"><?php if ($nPath) echo '<a href="' . oos_href_link_admin($aFilename['content_news'], $nPath_back . '&ncID=' . $current_news_category_id) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!$_GET['search']) echo '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&action=new_news_category') . '">' . oos_image_swap_button('new_new_categorie','new_news_category_off.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&action=new_news') . '">' . oos_image_swap_button('new_news','new_news_off.gif', IMAGE_NEW_NEWS) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'new_news_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('newcategory', $aFilename['content_news'], 'action=insert_news_category&nPath=' . $nPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $news_category_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $news_category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_categories_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br />' . TABLE_NEWS_CATEGORIES_NAME . $news_category_inputs_string);
        $contents[] = array('text' => '<br />' . TABLE_NEWS_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('news_categories_image'));
        $contents[] = array('text' => '<br />' . TEXT_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'edit_news_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['content_news'], 'action=update_news_category&nPath=' . $nPath, 'post', 'enctype="multipart/form-data"') . oos_draw_hidden_field('news_categories_id', $cInfo->news_categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $news_category_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $news_category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('news_categories_name[' . $languages[$i]['id'] . ']', oos_get_news_categories_title($cInfo->news_categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br />' . TEXT_EDIT_CATEGORIES_NAME . $news_category_inputs_string);
        $contents[] = array('text' => '<br />' . oos_image(OOS_SHOP_IMAGES . $cInfo->news_categories_image, $cInfo->news_categories_name) . '<br />' . OOS_SHOP_IMAGES . '<br /><b>' . $cInfo->news_categories_image . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_EDIT_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('news_categories_image'));
        $contents[] = array('text' => '<br />' . TEXT_EDIT_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['content_news'], 'action=delete_news_category_confirm&nPath=' . $nPath) . oos_draw_hidden_field('news_categories_id', $cInfo->news_categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->news_categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->news_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_NEWS, $cInfo->news_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['content_news'], 'action=move_category_confirm') . oos_draw_hidden_field('news_categories_id', $cInfo->news_categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->news_categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->news_categories_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_news_category_tree('0', '', $cInfo->news_categories_id), $current_news_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('move','move_off.gif', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete_news':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_NEWS . '</b>');

        $contents = array('form' => oos_draw_form('news', $aFilename['content_news'], 'action=delete_news_confirm&nPath=' . $nPath) . oos_draw_hidden_field('news_id', $nInfo->news_id));
        $contents[] = array('text' => TEXT_DELETE_NEWS_INTRO);
        $contents[] = array('text' => '<br /><b>' . $nInfo->news_name . '</b>');

        $news_categories_string = '';
        $news_categories = oos_generate_news_category_path($nInfo->news_id, 'news');
        for ($i = 0, $n = count($news_categories); $i < $n; $i++) {
          $news_category_path = '';
          for ($j = 0, $k = count($news_categories[$i]); $j < $k; $j++) {
            $news_category_path .= $news_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $news_category_path = substr($news_category_path, 0, -16);
          $news_categories_string .= oos_draw_checkbox_field('news_categories[]', $news_categories[$i][count($news_categories[$i])-1]['id'], true) . '&nbsp;' . $news_category_path . '<br />';
        }
        $news_categories_string = substr($news_categories_string, 0, -4);

        $contents[] = array('text' => '<br />' . $news_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'move_news':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_NEWS . '</b>');

        $contents = array('form' => oos_draw_form('news', $aFilename['content_news'], 'action=move_news_confirm&nPath=' . $nPath) . oos_draw_hidden_field('news_id', $nInfo->news_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_NEWS_INTRO, $nInfo->news_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_news_category_path($nInfo->news_id, 'news') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $nInfo->news_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_news_category_tree(), $current_news_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('move','move_off.gif', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => oos_draw_form('copy_to', $aFilename['content_news'], 'action=copy_to_confirm&nPath=' . $nPath) . oos_draw_hidden_field('news_id', $nInfo->news_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_news_category_path($nInfo->news_id, 'news') . '</b>');
        $contents[] = array('text' => '<br />' . TABLE_NEWS_CATEGORIES . '<br />' . oos_draw_pull_down_menu('news_categories_id', oos_get_news_category_tree(), $current_news_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . oos_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . oos_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('copy','copy_off.gif', IMAGE_COPY) . ' <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;
      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // news category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->news_categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id . '&action=edit_news_category') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id . '&action=delete_category') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&ncID=' . $cInfo->news_categories_id . '&action=move_category') . '">' . oos_image_swap_button('move','move_off.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' =>  TABLE_NEWS_CATEGORIES . ' ' . $cInfo->news_categories_name . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            if (oos_is_not_null($cInfo->last_modified)) {
              $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
            }
            $contents[] = array('text' => '<br />' . oos_info_image($cInfo->news_categories_image, $cInfo->news_categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $cInfo->news_categories_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_NEWS . ' ' . $cInfo->news_count);
          } elseif (isset($nInfo) && is_object($nInfo)) { // news info box contents
            $heading[] = array('text' => '<b>' . $nInfo->news_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id . '&action=new_news') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id . '&action=delete_news') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id . '&action=move_news') . '">' . oos_image_swap_button('move','move_off.gif', IMAGE_MOVE) . '</a> <a href="' . oos_href_link_admin($aFilename['content_news'], 'nPath=' . $nPath . '&nID=' . $nInfo->news_id . '&action=copy_to') . '">' . oos_image_swap_button('copy_to','copy_to_off.gif', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('text' => '#' . $nInfo->news_id . ' ' . TABLE_NEWS_CATEGORIES . ' ' . oos_get_news_categories_title($current_news_category_id) . '<br /><br />' . TEXT_DATE_ADDED_BY . ' ' . oos_get_news_author($nInfo->news_added_by) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($nInfo->news_date_added));
            if (oos_is_not_null($nInfo->news_last_modified)) {
               $contents[] = array('text' => '<br /><br />' . TEXT_LAST_MODIFIED_BY . ' ' . oos_get_news_author($nInfo->news_modified_by));
               $contents[] = array('text' =>  TEXT_LAST_MODIFIED . ' ' . oos_date_short($nInfo->news_last_modified));
            }
            if (date('Y-m-d') < $nInfo->news_expires_date ) $contents[] = array('text' => TEXT_DATE_EXPIRES . ' ' . oos_date_short($nInfo->news_expires_date ));
            $contents[] = array('text' => '<br />' . oos_info_image($nInfo->news_image, $nInfo->news_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '<br />' . $nInfo->news_image);

            $contents[] = array('text' => '<br />' . TEXT_NEWS_AVERAGE_RATING . ' ' . number_format($nInfo->average_rating, 2) . '%');
          }
        } else { // create category/news info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_NEWS, $parent_news_categories_name));
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