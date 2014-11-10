<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_banners.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_banners.php,v 1.5 2003/02/24 00:48:42 harley_vb  
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  $affiliate_banner_extension = oos_banner_image_extension();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setaffiliate_flag':
        if ( ($_GET['affiliate_flag'] == '0') || ($_GET['affiliate_flag'] == '1') ) {
          oos_set_banner_status($_GET['abID'], $_GET['affiliate_flag']);
          $messageStack->add_session(SUCCESS_BANNER_STATUS_UPDATED, 'success');
        } else {
          $messageStack->add_session(ERROR_UNKNOWN_STATUS_FLAG, 'error');
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $_GET['abID']));
        break;

      case 'insert':
      case 'update':
        $affiliate_banners_group = (empty($new_affiliate_banners_group)) ? oos_db_prepare_input($_POST['affiliate_banners_group']) : $new_affiliate_banners_group;
        $affiliate_banners_image = oos_get_uploaded_file('affiliate_banners_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES . $affiliate_banners_image_target);
        $db_image_location = '';

        $affiliate_banner_error = false;
        if (empty($affiliate_banners_title)) {
          $messageStack->add(ERROR_BANNER_TITLE_REQUIRED, 'error');
          $affiliate_banner_error = true;
        }

        if ( (isset($affiliate_banners_image)) && ($affiliate_banners_image['name'] != 'none') && (is_uploaded_file($affiliate_banners_image['tmp_name'])) ) {
          $store_image = false;
          if (!is_writeable($image_directory)) {
            if (is_dir($image_directory)) {
              $messageStack->add(sprintf(ERROR_IMAGE_DIRECTORY_NOT_WRITEABLE, $image_directory), 'error');
            } else {
              $messageStack->add(sprintf(ERROR_IMAGE_DIRECTORY_DOES_NOT_EXIST, $image_directory), 'error');
            }
            $affiliate_banner_error = true;
          } else {
            $store_image = true;
          }
        }

        if (!$affiliate_banner_error) {
          if ( (empty($affiliate_html_text)) && ($store_image == true) ) {
            oos_get_copy_uploaded_file($affiliate_banners_image, $image_directory);
          }
          $db_image_location = (oos_is_not_null($affiliate_banners_image_local)) ? $affiliate_banners_image_local : $affiliate_banners_image_target . $affiliate_banners_image['name'];
          if (!$affiliate_products_id) $affiliate_products_id="0";
          $sql_data_array = array('affiliate_banners_title' => $affiliate_banners_title,
                                  'affiliate_products_id' => $affiliate_products_id,
                                  'affiliate_banners_image' => $db_image_location,
                                  'affiliate_banners_group' => $affiliate_banners_group);

          if ($action == 'insert') {
            $insert_sql_data = array('affiliate_date_added' => 'now()',
                                     'affiliate_status' => '1');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['affiliate_banners'], $sql_data_array);
            $affiliate_banners_id = $dbconn->Insert_ID();

          // Banner ID 1 is generic Product Banner
            if ($affiliate_banners_id==1) $dbconn->Execute("UPDATE " . $oostable['affiliate_banners'] . " SET affiliate_banners_id = affiliate_banners_id + 1");
            $messageStack->add_session(SUCCESS_BANNER_INSERTED, 'success');
          } elseif ($action == 'update') {
            $insert_sql_data = array('affiliate_date_status_change' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['affiliate_banners'], $sql_data_array, 'update', 'affiliate_banners_id = \'' . $affiliate_banners_id . '\'');
            $messageStack->add_session(SUCCESS_BANNER_UPDATED, 'success');
          }

          oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $affiliate_banners_id));
        } else {
          $action = 'new';
        }
        break;

      case 'deleteconfirm':
        $affiliate_banners_id = oos_db_prepare_input($_GET['abID']);
        $delete_image = oos_db_prepare_input($_POST['delete_image']);

        if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
          $affiliate_banner_result = $dbconn->Execute("SELECT affiliate_banners_image FROM " . $oostable['affiliate_banners'] . " WHERE affiliate_banners_id = '" . oos_db_input($affiliate_banners_id) . "'");
          $affiliate_banner = $affiliate_banner_result->fields;
          if (is_file(OOS_ABSOLUTE_PATH . OOS_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
            if (is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES . $affiliate_banner['affiliate_banners_image'])) {
              unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $affiliate_banner['affiliate_banners_image']);
            } else {
              $messageStack->add_session(ERROR_IMAGE_IS_NOT_WRITEABLE, 'error');
            }
          } else {
            $messageStack->add_session(ERROR_IMAGE_DOES_NOT_EXIST, 'error');
          }
        }

        $dbconn->Execute("DELETE FROM " . $oostable['affiliate_banners'] . " WHERE affiliate_banners_id = '" . oos_db_input($affiliate_banners_id) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['affiliate_banners_history'] . " WHERE affiliate_banners_id = '" . oos_db_input($affiliate_banners_id) . "'");

        $messageStack->add_session(SUCCESS_BANNER_REMOVED, 'success');

        oos_redirect_admin(oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page']));
        break;
    }
  }
  $no_js_general = true;
  require 'includes/oos_header.php'; 
?>
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
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
    if (isset($_GET['abID'])) {
      $abID = oos_db_prepare_input($_GET['abID']);
      $form_action = 'update';

      $affiliate_banner_result = $dbconn->Execute("SELECT * FROM " . $oostable['affiliate_banners'] . " WHERE affiliate_banners_id = '" . oos_db_input($abID) . "'");
      $affiliate_banner = $affiliate_banner_result->fields;

      $abInfo = new objectInfo($affiliate_banner);
    } elseif (oos_is_not_null($_POST)) {
      $abInfo = new objectInfo($_POST);
    } else {
      $abInfo = new objectInfo(array());
    }

    $groups_array = array();
    $groups_result = $dbconn->Execute("SELECT distinct affiliate_banners_group FROM " . $oostable['affiliate_banners'] . " ORDER BY affiliate_banners_group");
    while ($groups = $groups_result->fields) {
      $groups_array[] = array('id' => $groups['affiliate_banners_group'], 'text' => $groups['affiliate_banners_group']);

      // Move that ADOdb pointer!
      $groups_result->MoveNext();
    }

    // Close result set
    $groups_result->Close();
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('new_banner', $aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); if ($form_action == 'update') echo oos_draw_hidden_field('affiliate_banners_id', $abID); ?>
        <td><table border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_TITLE; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_banners_title', $abInfo->affiliate_banners_title, '', true); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_LINKED_PRODUCT; ?></td>
            <td class="main"><?php echo oos_draw_input_field('affiliate_products_id', $abInfo->affiliate_products_id, '', false); ?></td>
          </tr>
          <tr>
            <td class="main" colspan=2><?php echo TEXT_BANNERS_LINKED_PRODUCT_NOTE ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main" valign="top"><?php echo TEXT_BANNERS_IMAGE; ?></td>
            <td class="main"><?php echo oos_draw_file_field('affiliate_banners_image') . ' ' . TEXT_BANNERS_IMAGE_LOCAL . '<br />' . OOS_ABSOLUTE_PATH . OOS_IMAGES . oos_draw_input_field('affiliate_banners_image_local', $abInfo->affiliate_banners_image); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_BANNERS_IMAGE_TARGET; ?></td>
            <td class="main"><?php echo OOS_ABSOLUTE_PATH . OOS_IMAGES . oos_draw_input_field('affiliate_banners_image_target'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top" nowrap><?php echo (($form_action == 'insert') ? oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) : oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE)). '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $_GET['abID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BANNERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_PRODUCT_ID; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATISTICS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $affiliate_banners_result_raw = "SELECT * FROM " . $oostable['affiliate_banners'] . " ORDER BY affiliate_banners_title, affiliate_banners_group";
    $affiliate_banners_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $affiliate_banners_result_raw, $affiliate_banners_result_numrows);
    $affiliate_banners_result = $dbconn->Execute($affiliate_banners_result_raw);
    while ($affiliate_banners = $affiliate_banners_result->fields) {
      $info_result = $dbconn->Execute("SELECT sum(affiliate_banners_shown) as affiliate_banners_shown, sum(affiliate_banners_clicks) as affiliate_banners_clicks FROM " . $oostable['affiliate_banners_history'] . " WHERE affiliate_banners_id = '" . $affiliate_banners['affiliate_banners_id'] . "'");
      $info = $info_result->fields;

      if (((!$_GET['abID']) || ($_GET['abID'] == $affiliate_banners['affiliate_banners_id'])) && (!$abInfo) && (substr($action, 0, 3) != 'new')) {
        $abInfo_array = array_merge($affiliate_banners, $info);
        $abInfo = new objectInfo($abInfo_array);
      }

      $affiliate_banners_shown = ($info['affiliate_banners_shown'] != '') ? $info['affiliate_banners_shown'] : '0';
      $affiliate_banners_clicked = ($info['affiliate_banners_clicks'] != '') ? $info['affiliate_banners_clicks'] : '0';

      if (isset($abInfo) && is_object($abInfo) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate_banners'],'abID=' . $abInfo->affiliate_banners_id . '&action=new')  . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['affiliate_banners'], 'abID=' . $affiliate_banners['affiliate_banners_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="javascript:popupImageWindow(\'' . $aFilename['affiliate_popup_image'] . '?banner=' . $affiliate_banners['affiliate_banners_id'] . '\')">' . oos_image(OOS_IMAGES . 'icon_popup.gif', ICON_PREVIEW) . '</a>&nbsp;' . $affiliate_banners['affiliate_banners_title']; ?></td>
                <td class="dataTableContent" align="right"><?php if ($affiliate_banners['affiliate_products_id']>0) echo $affiliate_banners['affiliate_products_id']; else echo '&nbsp;'; ?></td>
                <td class="dataTableContent" align="right"><?php echo $affiliate_banners_shown . ' / ' . $affiliate_banners_clicked; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($abInfo) && is_object($abInfo) && ($affiliate_banners['affiliate_banners_id'] == $abInfo->affiliate_banners_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $affiliate_banners['affiliate_banners_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $affiliate_banners_result->MoveNext();
    }

    // Close result set
    $affiliate_banners_result->Close();
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $affiliate_banners_split->display_count($affiliate_banners_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_BANNERS); ?></td>
                    <td class="smallText" align="right"><?php echo $affiliate_banners_split->display_links($affiliate_banners_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'action=new') . '">' . oos_image_swap_button('new_banner','new_banner_off.gif', IMAGE_NEW_BANNER) . '</a>'; ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');

      $contents = array('form' => oos_draw_form('affiliate_banners', $aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $abInfo->affiliate_banners_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $abInfo->affiliate_banners_title . '</b>');
      if ($abInfo->affiliate_banners_image) $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('delete_image', 'on', true) . ' ' . TEXT_INFO_DELETE_IMAGE);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $_GET['abID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($abInfo) && is_object($abInfo)) {
        $sql = "SELECT products_name FROM " . $oostable['products_description'] . " WHERE products_id = '" . $abInfo->affiliate_products_id . "' and products_languages_id = '" . intval($_SESSION['language_id']) . "'"; 
        $product_description_result = $dbconn->Execute($sql);
        $product_description = $product_description_result->fields;
        $heading[] = array('text' => '<b>' . $abInfo->affiliate_banners_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $abInfo->affiliate_banners_id . '&action=new') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['affiliate_banners_manager'], 'page=' . $_GET['page'] . '&abID=' . $abInfo->affiliate_banners_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => $product_description['products_name']);
        $contents[] = array('text' => '<br />' . TEXT_BANNERS_DATE_ADDED . ' ' . oos_date_short($abInfo->affiliate_date_added));
        $contents[] = array('text' => '' . sprintf(TEXT_BANNERS_STATUS_CHANGE, oos_date_short($abInfo->affiliate_date_status_change)));
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