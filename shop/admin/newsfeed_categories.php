<?php
/* ----------------------------------------------------------------------
   $Id: newsfeed_categories.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

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
 * News Feed Categories
 * @link http://www.oos-shop.de/
 * @package Newsfeed
 * @author r23 <info@r23.de>
 * @copyright 2003 r23
 * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 17:14:41 $
 */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  function oosGetNewsfeedCategoriesName($newsfeed_categories_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $newsfeed_categoriestable = $oostable['newsfeed_categories'];
    $newsfeed_categories = $dbconn->Execute("SELECT newsfeed_categories_name FROM $newsfeed_categoriestable WHERE newsfeed_categories_id = '" . $newsfeed_categories_id . "' AND newsfeed_categories_languages_id = '" . intval($lang_id) . "'");

    return $newsfeed_categories->fields['newsfeed_categories_name'];
  }

  function oosGetNewsfeedCategories() {

    $newsfeed_categories_array = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $newsfeed_categoriestable = $oostable['newsfeed_categories'];
    $newsfeed_categories_result = $dbconn->Execute("SELECT newsfeed_categories_id, newsfeed_categories_name FROM $newsfeed_categoriestable WHERE newsfeed_categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY newsfeed_categories_id");
    while ($newsfeed_categories = $newsfeed_categories_result->fields) {
      $newsfeed_categories_array[] = array('id' => $newsfeed_categories['newsfeed_categories_id'],
                                           'text' => $newsfeed_categories['newsfeed_categories_name']);

      // Move that ADOdb pointer!
      $newsfeed_categories_result->MoveNext();
    }

    // Close result set
    $newsfeed_categories_result->Close();

    return $newsfeed_categories_array;
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) { 
      case 'insert':
      case 'save':
        $newsfeed_categories_id = oos_db_prepare_input($_GET['ncID']);

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $newsfeed_categories_name_array = $_POST['newsfeed_categories_name'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('newsfeed_categories_name' => oos_db_prepare_input($newsfeed_categories_name_array[$lang_id]));

          if ($action == 'insert') {
            if (oos_empty($newsfeed_categories_id)) {
              $newsfeed_categoriestable = $oostable['newsfeed_categories'];
              $next_id_result = $dbconn->Execute("SELECT max(newsfeed_categories_id) as newsfeed_categories_id FROM $newsfeed_categoriestable");
              $next_id = $next_id_result->fields;
              $newsfeed_categories_id = $next_id['newsfeed_categories_id'] + 1;
            }

            $insert_sql_data = array('newsfeed_categories_id' => $newsfeed_categories_id,
                                     'newsfeed_categories_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['newsfeed_categories'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['newsfeed_categories'], $sql_data_array, 'update', "newsfeed_categories_id = '" . oos_db_input($newsfeed_categories_id) . "' and newsfeed_categories_languages_id = '" . intval($lang_id) . "'");
          }
        }

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . oos_db_input($newsfeed_categories_id) . "' WHERE configuration_key = 'DEFAULT_NEWSFEED_CATEGOREIS_ID'");
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $newsfeed_categories_id));
        break;

      case 'deleteconfirm':
        $ncID = oos_db_prepare_input($_GET['ncID']);

        $configurationtable = $oostable['configuration'];
        $newsfeed_categories_result = $dbconn->Execute("SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'DEFAULT_NEWSFEED_CATEGOREIS_ID'");
        $newsfeed_categories = $newsfeed_categories_result->fields;
        if ($newsfeed_categories['configuration_value'] == $ncID) {
          $configurationtable = $oostable['configuration'];
          $dbconn->Execute("UPDATE $configurationtable SET configuration_value = '' WHERE configuration_key = 'DEFAULT_NEWSFEED_CATEGOREIS_ID'");
        }

        $newsfeed_categoriestable = $oostable['newsfeed_categories'];
        $dbconn->Execute("DELETE FROM $newsfeed_categoriestable WHERE newsfeed_categories_id = '" . oos_db_input($ncID) . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page']));
        break;

      case 'delete':
        $ncID = oos_db_prepare_input($_GET['ncID']);

        $newsfeed_managertable = $oostable['newsfeed_manager'];
        $status_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM $newsfeed_managertable WHERE newsfeed_categories_id = '" . oos_db_input($ncID) . "'");
        $status = $status_result->fields;

        $remove_status = true;
        if ($ncID == DEFAULT_NEWSFEED_CATEGOREIS_ID) {
          $remove_status = false;
          $messageStack->add(ERROR_REMOVE_DEFAULT_NEWSFEED_CATEGOREIS, 'error');
        } elseif ($status['total'] > 0) {
          $remove_status = false;
          $messageStack->add(ERROR_STATUS_USED_IN_NEWSFEED_MANAGER, 'error');
        } 
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
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NEWSFEED_CATEGORIES; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $newsfeed_categoriestable = $oostable['newsfeed_categories'];
  $newsfeed_categories_result_raw = "SELECT newsfeed_categories_id, newsfeed_categories_name
                                     FROM $newsfeed_categoriestable
                                     WHERE newsfeed_categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                                     ORDER BY newsfeed_categories_id";
  $newsfeed_categories_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $newsfeed_categories_result_raw, $newsfeed_categories_result_numrows);
  $newsfeed_categories_result = $dbconn->Execute($newsfeed_categories_result_raw);
  while ($newsfeed_categories = $newsfeed_categories_result->fields) {
    if ((!isset($_GET['ncID']) || (isset($_GET['ncID']) && ($_GET['ncID'] == $newsfeed_categories['newsfeed_categories_id']))) && !isset($ncInfo) && (substr($action, 0, 3) != 'new')) {
      $ncInfo = new objectInfo($newsfeed_categories);
    }

    if (isset($ncInfo) && is_object($ncInfo) && ($newsfeed_categories['newsfeed_categories_id'] == $ncInfo->newsfeed_categories_id) ) {
      echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id . '&action=edit') . '\'">' . "\n";
    } else {
      echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $newsfeed_categories['newsfeed_categories_id']) . '\'">' . "\n";
    }

    if (DEFAULT_NEWSFEED_CATEGOREIS_ID == $newsfeed_categories['newsfeed_categories_id']) {
      echo '                <td class="dataTableContent"><b>' . $newsfeed_categories['newsfeed_categories_name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
      echo '                <td class="dataTableContent">' . $newsfeed_categories['newsfeed_categories_name'] . '</td>' . "\n";
    }
?>
                <td class="dataTableContent" align="right"><?php if (isset($ncInfo) && is_object($ncInfo) && ($newsfeed_categories['newsfeed_categories_id'] == $ncInfo->newsfeed_categories_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $newsfeed_categories['newsfeed_categories_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $newsfeed_categories_result->MoveNext();
  }
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $newsfeed_categories_split->display_count($newsfeed_categories_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_NEWSFEED_CATEGORIES); ?></td>
                    <td class="smallText" align="right"><?php echo $newsfeed_categories_split->display_links($newsfeed_categories_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&action=new') . '">' . oos_image_swap_button('insert','insert_off.gif', IMAGE_INSERT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_NEWSFEED_CATEGORIES . '</b>');

      $contents = array('form' => oos_draw_form('status', $aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);

      $newsfeed_categories_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $newsfeed_categories_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_categories_name[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_NEWSFEED_CATEGORIES_NAME . $newsfeed_categories_inputs_string);
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) . ' <a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_NEWSFEED_CATEGORIES . '</b>');

      $contents = array('form' => oos_draw_form('status', $aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id  . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);

      $newsfeed_categories_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $newsfeed_categories_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('newsfeed_categories_name[' . $languages[$i]['id'] . ']', oosGetNewsfeedCategoriesName($ncInfo->newsfeed_categories_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_INFO_NEWSFEED_CATEGORIES_NAME . $newsfeed_categories_inputs_string);
      if (DEFAULT_NEWSFEED_CATEGOREIS_ID != $ncInfo->newsfeed_categories_id) $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . ' <a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_NEWSFEED_CATEGORIES . '</b>');

      $contents = array('form' => oos_draw_form('status', $aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id  . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $ncInfo->newsfeed_categories_name . '</b>');
      if ($remove_status) $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($ncInfo) && is_object($ncInfo)) {
        $heading[] = array('text' => '<b>' . $ncInfo->newsfeed_categories_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['newsfeed_categories'], 'page=' . $_GET['page'] . '&ncID=' . $ncInfo->newsfeed_categories_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');

        $newsfeed_categories_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $newsfeed_categories_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oosGetNewsfeedCategoriesName($ncInfo->newsfeed_categories_id, $languages[$i]['id']);
        }

        $contents[] = array('text' => $newsfeed_categories_inputs_string);
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