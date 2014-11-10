<?php
/* ----------------------------------------------------------------------
   $Id: links_categories.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: link_categories.php,v 1.00 2003/10/02
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

// define our link functions
  require 'includes/functions/function_links.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  $error = false;
  $processed = false;

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        $status = oos_db_prepare_input($_GET['flag']);

        if ($status == '1') {
          $dbconn->Execute("UPDATE " . $oostable['link_categories'] . " SET link_categories_status = '1' WHERE link_categories_id = '" . (int)$_GET['cID'] . "'");
        } elseif ($status == '0') {
          $dbconn->Execute("UPDATE " . $oostable['link_categories'] . " SET link_categories_status = '0' WHERE link_categories_id = '" . (int)$_GET['cID'] . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['links_categories'], '&cID=' . $_GET['cID']));
        break;

      case 'insert':
      case 'update':
        if (isset($_POST['link_categories_id'])) $link_categories_id = oos_db_prepare_input($_POST['link_categories_id']);
        $link_categories_sort_order = oos_db_prepare_input($_POST['link_categories_sort_order']);
        $link_categories_status = ((oos_db_prepare_input($_POST['link_categories_status']) == 'on') ? '1' : '0');

        $sql_data_array = array('link_categories_sort_order' => $link_categories_sort_order, 
                                'link_categories_status' => $link_categories_status);

        if ($action == 'insert') {
          $insert_sql_data = array('link_categories_date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          oos_db_perform($oostable['link_categories'], $sql_data_array);

          $link_categories_id = $dbconn->Insert_ID();
        } elseif ($action == 'update') {
          $update_sql_data = array('link_categories_last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          oos_db_perform($oostable['link_categories'], $sql_data_array, 'update', "link_categories_id = '" . (int)$link_categories_id . "'");
        }

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $link_categories_name_array = $_POST['link_categories_name'];
          $link_categories_description_array = $_POST['link_categories_description'];

          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('link_categories_name' => oos_db_prepare_input($link_categories_name_array[$lang_id]), 
                                  'link_categories_description' => oos_db_prepare_input($link_categories_description_array[$lang_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('link_categories_id' => $link_categories_id,
                                     'link_categories_languages_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['link_categories_description'], $sql_data_array);
          } elseif ($action == 'update') {
            oos_db_perform($oostable['link_categories_description'], $sql_data_array, 'update', 'categories_id = \'' . (int)$link_categories_id . '\' AND link_categories_languages_id = \'' . $languages[$i]['id'] . '\'');
          }
        }

        $link_categories_image = oos_get_uploaded_file('link_categories_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

        if (is_uploaded_file($link_categories_image['tmp_name'])) {
          $dbconn->Execute("UPDATE " . $oostable['link_categories'] . " SET link_categories_image = '" . $link_categories_image['name'] . "' WHERE link_categories_id = '" . (int)$link_categories_id . "'");
          oos_get_copy_uploaded_file($link_categories_image, $image_directory);
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['links_categories'], '&cID=' . $link_categories_id));
        break;

      case 'delete_confirm':
        if (isset($_POST['link_categories_id'])) {
          $link_categories_id = oos_db_prepare_input($_POST['link_categories_id']);

          $link_ids_result = $dbconn->Execute("SELECT links_id FROM " . $oostable['links_to_link_categories'] . " WHERE link_categories_id = '" . (int)$link_categories_id . "'");

          while ($link_ids = $link_ids_result->fields) {
            oos_remove_link($link_ids['links_id']);
          }

          oos_remove_linkCategory($link_categories_id);
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['links_categories']));
        break;

      default:
        $link_categories_sql = "SELECT 
                                    lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, 
                                    lc.link_categories_sort_order, lc.link_categories_date_added, 
                                    lc.link_categories_last_modified, lcd.link_categories_name, 
                                    lcd.link_categories_description 
                                FROM 
                                   " . $oostable['link_categories'] . " lc LEFT JOIN 
                                   " . $oostable['link_categories_description'] . " lcd 
                                ON lc.link_categories_id = lcd.link_categories_id 
                                WHERE 
                                   lcd.link_categories_id = lc.link_categories_id AND 
                                   lc.link_categories_id = '" . (int)$_GET['cID'] . "' AND 
                                   lcd.link_categories_languages_id = '" . intval($_SESSION['language_id']) . "'";
        $link_categories_result = $dbconn->Execute($link_categories_sql);
        $link_categories = $link_categories_result->fields;

        $links_count_result = $dbconn->Execute("SELECT COUNT(*) AS link_categories_count FROM " . $oostable['links_to_link_categories'] . " WHERE link_categories_id = '" . (int)$_GET['cID'] . "'");
        $links_count = $links_count_result->fields;

        $cInfo_array = array_merge($link_categories, $links_count);
        $cInfo = new objectInfo($cInfo_array);
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
          <tr><?php echo oos_draw_form('search', $aFilename['links_categories'], '', 'get'); ?>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . oos_draw_input_field('search'); ?></td>
          </form></tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $search = '';
    if (isset($_GET['search']) && oos_is_not_null($_GET['search'])) {
      $keywords = oos_db_input(oos_db_prepare_input($_GET['search']));
      $search = " AND lcd.link_categories_name LIKE '%" . $keywords . "%'";

      $link_categories_result_raw = "SELECT 
                                         lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, 
                                         lc.link_categories_sort_order, lc.link_categories_date_added, 
                                         lc.link_categories_last_modified, lcd.link_categories_name, lcd.link_categories_description 
                                     FROM 
                                        " . $oostable['link_categories'] . " lc LEFT JOIN 
                                        " . $oostable['link_categories_description'] . " lcd 
                                     ON lc.link_categories_id = lcd.link_categories_id 
                                     WHERE 
                                        lcd.link_categories_languages_id = '" . intval($_SESSION['language_id']) . "'" . $search . " 
                                     ORDER BY 
                                        lc.link_categories_sort_order, lcd.link_categories_name";
    } else {
      $link_categories_result_raw = "SELECT 
                                         lc.link_categories_id, lc.link_categories_image, lc.link_categories_status, 
                                         lc.link_categories_sort_order, lc.link_categories_date_added, 
                                         lc.link_categories_last_modified, lcd.link_categories_name, lcd.link_categories_description 
                                     FROM 
                                         " . $oostable['link_categories'] . " lc LEFT JOIN 
                                         " . $oostable['link_categories_description'] . " lcd 
                                     ON lc.link_categories_id = lcd.link_categories_id  
                                     WHERE 
                                        lcd.link_categories_languages_id = '" . intval($_SESSION['language_id']) . "' 
                                     ORDER BY 
                                        lc.link_categories_sort_order, lcd.link_categories_name";
    }

    $link_categories_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $link_categories_result_raw, $link_categories_result_numrows);
    $link_categories_result = $dbconn->Execute($link_categories_result_raw);
    while ($link_categories = $link_categories_result->fields) {
      if ((!isset($_GET['cID']) || (isset($_GET['cID']) && ($_GET['cID'] == $link_categories['link_categories_id']))) && !isset($cInfo)) { 
        $links_count_result = $dbconn->Execute("SELECT COUNT(*) AS link_categories_count FROM " . $oostable['links_to_link_categories'] . " WHERE link_categories_id = '" . (int)$link_categories['link_categories_id'] . "'");
        $links_count = $links_count_result->fields;

        $cInfo_array = array_merge($link_categories, $links_count);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id)) {
        echo '          <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['links_categories'], oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '          <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['links_categories'], oos_get_all_get_params(array('cID')) . 'cID=' . $link_categories['link_categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo $link_categories['link_categories_name']; ?></td>
                <td  class="dataTableContent" align="right">
<?php
      if ($link_categories['link_categories_status'] == '1') {
        echo '<a href="' . oos_href_link_admin($aFilename['links_categories'], 'action=setflag&flag=0&cID=' . $link_categories['link_categories_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . oos_href_link_admin($aFilename['links_categories'], 'action=setflag&flag=1&cID=' . $link_categories['link_categories_id'], 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($link_categories['link_categories_id'] == $cInfo->link_categories_id)) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['links_categories'], oos_get_all_get_params(array('cID')) . 'cID=' . $link_categories['link_categories_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $link_categories_result->MoveNext();
    }
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $link_categories_split->display_count($link_categories_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_LINK_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $link_categories_split->display_links($link_categories_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_params(array('page', 'info', 'x', 'y', 'cID'))); ?></td>
                  </tr>
                  <tr>
<?php
    if (isset($_GET['search']) && oos_is_not_null($_GET['search'])) {
?>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['links_categories']) . '">' . oos_image_swap_button('reset','reset_off.gif', IMAGE_RESET) . '</a>'; ?></td>
                    <td align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['links_categories'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_categories','new_category_off.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    } else {
?>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aFilename['links_categories'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('new_categorie','new_category_off.gif', IMAGE_NEW_CATEGORY) . '</a>'; ?></td>
<?php
    }
?>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LINK_CATEGORY . '</b>');

      $contents = array('form' => oos_draw_form('new_link_categories', $aFilename['links_categories'], 'action=insert', 'post', 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_LINK_CATEGORIES_INTRO);

      $link_category_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $link_category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']');
      }

      $link_category_description_inputs_string = '';
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $link_category_description_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;<br />' . oos_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5');
      }

      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_NAME . $link_category_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('link_categories_image'));
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_SORT_ORDER . '<br />' . oos_draw_input_field('link_categories_sort_order', '', 'size="2"'));
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . oos_draw_radio_field('link_categories_status', 'on', true) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . oos_draw_radio_field('link_categories_status', 'off') . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['links_categories']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LINK_CATEGORY . '</b>');

      $contents = array('form' => oos_draw_form('edit_link_categories', $aFilename['links_categories'], 'action=update', 'post', 'enctype="multipart/form-data"') . oos_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
      $contents[] = array('text' => TEXT_EDIT_LINK_CATEGORIES_INTRO);

      $link_category_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $link_category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('link_categories_name[' . $languages[$i]['id'] . ']', oos_get_link_category_name($cInfo->link_categories_id, $languages[$i]['id']));
      }

      $link_category_description_inputs_string = '';
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $link_category_description_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;<br />' . oos_draw_textarea_field('link_categories_description[' . $languages[$i]['id'] . ']', 'soft', '40', '5', oos_get_link_category_description($cInfo->link_categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_NAME . $link_category_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_DESCRIPTION . $link_category_description_inputs_string);
      $contents[] = array('text' => '<br />' . oos_info_image($cInfo->link_categories_image, $cInfo->link_categories_name) . '<br />' . $cInfo->link_categories_image);
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('link_categories_image'));
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_SORT_ORDER . '&nbsp;' . oos_draw_input_field('link_categories_sort_order', $cInfo->link_categories_sort_order, 'size="2"'));
      $contents[] = array('text' => '<br />' . TEXT_LINK_CATEGORIES_STATUS . '&nbsp;&nbsp;' . oos_draw_radio_field('link_categories_status', 'on', ($cInfo->link_categories_status == '1') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_ENABLE . '&nbsp;&nbsp;' . oos_draw_radio_field('link_categories_status', 'off', ($cInfo->link_categories_status == '0') ? true : false) . ' ' . TEXT_LINK_CATEGORIES_STATUS_DISABLE);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['links_categories'], 'cID=' . $cInfo->link_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LINK_CATEGORY . '</b>');

      $contents = array('form' => oos_draw_form('delete_link_categories', $aFilename['links_categories'], 'action=delete_confirm') . oos_draw_hidden_field('link_categories_id', $cInfo->link_categories_id));
      $contents[] = array('text' => TEXT_DELETE_LINK_CATEGORIES_INTRO);
      $contents[] = array('text' => '<br /><b>' . $cInfo->link_categories_name . '</b>');
      if ($cInfo->link_categories_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_LINKS, $cInfo->link_categories_count));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['links_categories'], 'cID=' . $cInfo->link_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->link_categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['links_categories'], oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['links_categories'], oos_get_all_get_params(array('cID', 'action')) . 'cID=' . $cInfo->link_categories_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');

        $contents[] = array('text' => '<br />' . oos_info_image($cInfo->link_categories_image, $cInfo->link_categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $cInfo->link_categories_image);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CATEGORY_DESCRIPTION . ' ' . $cInfo->link_categories_description);
        $contents[] = array('text' => '<br />' . TEXT_DATE_LINK_CATEGORY_CREATED . ' ' . oos_date_short($cInfo->link_categories_date_added));
        if (oos_is_not_null($cInfo->link_categories_last_modified)) {
          $contents[] = array('text' => '<br />' . TEXT_DATE_LINK_CATEGORY_LAST_MODIFIED . ' ' . oos_date_short($cInfo->link_categories_last_modified));
        }
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CATEGORY_COUNT . ' '  . $cInfo->link_categories_count);
        $contents[] = array('text' => '<br />' . TEXT_INFO_LINK_CATEGORY_SORT_ORDER . ' '  . $cInfo->link_categories_sort_order);
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
