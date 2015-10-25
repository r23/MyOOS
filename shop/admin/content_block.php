<?php
/* ----------------------------------------------------------------------
   $Id: content_block.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';
  require 'includes/functions/function_block.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        if (isset($_GET['bID'])) {
          if ($_GET['flag'] == '1') {
            $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_status = '1' WHERE block_id = '" . intval($_GET['bID']) . "'");
          } elseif ($_GET['flag'] == '0') {
            $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_status = '0' WHERE block_id = '" . intval($_GET['bID']) . "'");
          }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['content_block'], 'page=' . intval($_GET['page']) . '&bID=' . intval($_GET['bID'])));
        break;

      case 'setloginflag':
        if (isset($_GET['bID'])) {
          if ($_GET['login_flag'] == '1') {
            $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_login_flag = '1' WHERE block_id = '" . intval($_GET['bID']) . "'");
          } elseif ($_GET['login_flag'] == '0') {
            $dbconn->Execute("UPDATE " . $oostable['block'] . " SET block_login_flag = '0' WHERE block_id = '" . intval($_GET['bID']) . "'");
          }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['content_block'], 'page=' . intval($_GET['page']) . '&bID=' . intval($_GET['bID'])));
        break;

      case 'insert':
      case 'save':
        $block_content_id = oos_db_prepare_input($_GET['bID']);

        $sql_data_array = array('block_side' => $block_side,
                                'block_file' => $function,
                                'block_cache' => $block_cache,
                                'block_sort_order' => $sort_order,
                                'block_status' => $block_status,
                                'block_login_flag' => $block_login_flag);
        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()',
                                   'set_function' => 'oos_block_select_option(array(\'\', \'sidebar\'),');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          oos_db_perform($oostable['block'], $sql_data_array);
          $block_content_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          oos_db_perform($oostable['block'], $sql_data_array, 'update', "block_id = '" . intval($block_content_id) . "'");
          $dbconn->Execute("DELETE FROM " . $oostable['block_to_page_type'] . " WHERE block_id = '" . intval($block_content_id) . "'");
        }

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $block_content_name_array = $_POST['block_name'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('block_name' => oos_db_prepare_input($block_content_name_array[$lang_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('block_id' => $block_content_id,
                                     'block_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['block_info'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['block_info'], $sql_data_array, 'update', "block_id = '" . intval($block_content_id) . "' AND block_languages_id = '" . intval($lang_id) . "'");
          }
        }

        if (isset($_REQUEST['page_type'])) {
          reset($_REQUEST['page_type']);
          foreach($_REQUEST['page_type'] as $k => $id) {
            $sql = "INSERT INTO " . $oostable['block_to_page_type'] . "
                     (block_id,
                      page_type_id)
                      VALUES (" . $dbconn->qstr($block_content_id) . ','
                                . $dbconn->qstr($id) . ")";
            $dbconn->Execute($sql);
          }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $block_content_id));
        break;

      case 'deleteconfirm':
        $block_content_id = oos_db_prepare_input($_GET['bID']);

        $dbconn->Execute("DELETE FROM " . $oostable['block'] . " WHERE block_id = '" . intval($block_content_id) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['block_info'] . " WHERE block_id = '" . intval($block_content_id) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['block_to_page_type'] . " WHERE block_id = '" . intval($block_content_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page']));
        break;
    }
  }

  require 'includes/header.php'; 
?>
<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="gray-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>
			
			<!-- Breadcrumbs  -->
			<div class="row wrapper white-bg page-heading">
				<div class="col-lg-10">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['content_block'], 'selected_box=content') . '">' . BOX_HEADING_CONTENT . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
				<div class="col-lg-2">

				</div>
			</div><!--/ End Breadcrumbs -->	
			
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">		
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_BLOCK; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COLUMN; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LOGIN; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
   $blocktable = $oostable['block'];
   $block_infotable = $oostable['block_info'];
   $block_content_result_raw = "SELECT b.block_id, bi.block_name, b.block_side, b.block_file, b.block_sort_order,
                                       b.block_status, b.block_login_flag, b.block_cache, b.date_added,
                                       b.last_modified, b.set_function
                                FROM $blocktable b,
                                     " . $block_infotable . " bi
                                WHERE b.block_id = bi.block_id AND
                                      bi.block_languages_id = '" . intval($_SESSION['language_id']) . "'
                                ORDER BY b.block_sort_order, b.block_side";
  $block_content_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $block_content_result_raw, $block_content_result_numrows);
  $block_content_result = $dbconn->Execute($block_content_result_raw);
  while ($block = $block_content_result->fields) {
    if ((!isset($_GET['bID']) || (isset($_GET['bID']) && ($_GET['bID'] == $block['block_id']))) && !isset($bInfo) && (substr($action, 0, 3) != 'new')) {
      $bInfo = new objectInfo($block);
    }

    if (isset($bInfo) && is_object($bInfo) && ($block['block_id'] == $bInfo->block_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $block['block_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $block['block_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $block['block_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $block['block_side']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $block['block_sort_order']; ?></td>
                <td class="dataTableContent" align="center">
<?php
  if ($block['block_status'] == '1') {
    echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'action=setflag&flag=0&bID=' . $block['block_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
  } else {
    echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'action=setflag&flag=1&bID=' . $block['block_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
  }
?></td>
                <td class="dataTableContent" align="center">
<?php
  if ($block['block_login_flag'] == '1') {
    echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'action=setloginflag&login_flag=0&bID=' . $block['block_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
  } else {
    echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'action=setloginflag&login_flag=1&bID=' . $block['block_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
  }
?></td>
                <td class="dataTableContent" align="right"><?php if (isset($bInfo) && is_object($bInfo) && ($block['block_id'] == $bInfo->block_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $block['block_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $block_content_result->MoveNext();
  }

  // Close result set
  $block_content_result->Close();
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $block_content_split->display_count($block_content_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_BLOCKES); ?></td>
                    <td class="smallText" align="right"><?php echo $block_content_split->display_links($block_content_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="6" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id . '&action=new') . '">' . oos_button('insert', BUTTON_INSERT) . '</a>'; ?></td>
              </tr>
<?php
  }
?>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  $block_status_array = array();
  $block_login_flag_array = array();
  $block_login_flag_array = array(array('id' => '0', 'text' => ENTRY_NO),
                                  array('id' => '1', 'text' => ENTRY_YES));
  $block_status_array = array(array('id' => '0', 'text' => ENTRY_NO),
                              array('id' => '1', 'text' => ENTRY_YES));

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_BLOCK . '</b>');

      $contents = array('form' => oos_draw_form('id', 'block', $aContents['content_block'], 'action=insert', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);

      $block_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $block_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('block_name[' . $languages[$i]['id'] . ']');
      }
      $contents[] = array('text' => '<br /><b>' . TEXT_BLOCK_NAME . ':</b>' .$block_inputs_string);
      $contents[] = array('text' => '<br /><b>' . TEXT_BLOCK_FUNCTION . ':</b><br />' . oos_draw_input_field('function'));
      $contents[] = array('text' => '<br /><b>' . TEXT_BLOCK_CACHE . ':</b><br />' . oos_draw_input_field('block_cache'));
      $contents[] = array('text' => '<br /><b>' . TABLE_HEADING_COLUMN . ':</b><br />' . oos_block_select_option(array('', 'sidebar'), 'block_side'));
      $contents[] = array('text' => '<br /><b>'  . TABLE_HEADING_STATUS . ':</b> ' . oos_draw_pull_down_menu('block_status', $block_status_array));
      $contents[] = array('text' => '<br /><b>'  . TEXT_BLOCK_LOGIN . '</b> ' . oos_draw_pull_down_menu('block_login_flag', $block_login_flag_array));
      $contents[] = array('text' => '<br /><b>'  . TEXT_BLOCK_PAGE . '</b><br />' . oos_select_block_to_page());

      $contents[] = array('text' => '<br /><b>' . TABLE_HEADING_SORT_ORDER . ':</b> ' . oos_draw_input_field('sort_order', '', 'size="2"'));

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $_GET['bID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_BLOCK . '</b>');

      $contents = array('form' => oos_draw_form('id', 'block', $aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id . '&action=save', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);

      $block_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $block_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('block_name[' . $languages[$i]['id'] . ']', oos_get_block_name($bInfo->block_id, $languages[$i]['id']));
      }
      eval('$value_field = ' . $bInfo->set_function . '"' . htmlspecialchars($bInfo->block_side) . '");');

      $contents[] = array('text' => '<br />' . TEXT_BLOCK_NAME . $block_inputs_string);
      $contents[] = array('text' => '<br /><b>' . TEXT_BLOCK_FUNCTION . ':</b><br />' . oos_draw_input_field('function', $bInfo->block_file));
      $contents[] = array('text' => '<br /><b>' . TEXT_BLOCK_CACHE . ':</b><br />' . oos_draw_input_field('block_cache', $bInfo->block_cache));
      $contents[] = array('text' => '<br /><b>' . TABLE_HEADING_COLUMN . ':</b><br />' . $value_field);
      $contents[] = array('text' => '<br /><b>'  . TABLE_HEADING_STATUS . ':</b> ' . oos_draw_pull_down_menu('block_status', $block_status_array, $bInfo->block_status));
      $contents[] = array('text' => '<br /><b>'  . TEXT_BLOCK_LOGIN . ':</b> ' . oos_draw_pull_down_menu('block_login_flag', $block_login_flag_array, $bInfo->block_login_flag));
      $contents[] = array('text' => '<br /><b>'  . TEXT_BLOCK_PAGE . ':</b><br />' . oos_show_block_to_page($bInfo->block_id));

      $contents[] = array('text' => '<br /><b>' . TABLE_HEADING_SORT_ORDER . ':</b><br />' . oos_draw_input_field('sort_order', $bInfo->block_sort_order, 'size="2"'));

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_BLOCK . '</b>');

      $contents = array('form' => oos_draw_form('id', 'block', $aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $bInfo->block_name . '</b>');

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($bInfo) && is_object($bInfo)) {
        $heading[] = array('text' => '<b>' . $bInfo->block_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['content_block'], 'page=' . $_GET['page'] . '&bID=' . $bInfo->block_id . '&action=delete') . '">' . oos_button('delete',  IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($bInfo->date_added));
        if (oos_is_not_null($bInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($bInfo->last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br /><table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td class="infoBoxContent" valign="top"><b>' . TEXT_BLOCK_FUNCTION . ':</b></td><td class="infoBoxContent">' . $bInfo->block_file . '</td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td class="infoBoxContent" valign="top"><b>' . TEXT_BLOCK_CACHE . ':</b></td><td class="infoBoxContent">' . $bInfo->block_cache . '</td></tr><tr><td colspan="2">&nbsp;</td></tr><tr><td class="infoBoxContent" valign="top"><b>' . TEXT_BLOCK_PAGE . ':</b></td><td class="infoBoxContent">' . oos_info_block_to_page($bInfo->block_id) . '</td></tr></table>');
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
    </table>
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
