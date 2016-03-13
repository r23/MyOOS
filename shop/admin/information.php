<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: information.php,v 1.51 2003/01/29 23:21:48 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  require 'includes/functions/function_informations.php';

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {

       case 'setflag':
         $lID = oos_db_prepare_input($_GET['lID']);

         if ($_GET['flag'] == '0') {
           $dbconn->Execute("UPDATE " . $oostable['information'] . " 
                         SET status = '0'
                         WHERE information_id = '" . intval($lID) . "'");
         } elseif ($_GET['flag'] == '1') {
           $dbconn->Execute("UPDATE " . $oostable['information'] . " 
                         SET status = '1'
                         WHERE information_id = '" . intval($lID) . "'");
         }
         oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $_GET['page']. '&lID=' . $_GET['lID']));
      break;

      case 'insert':
      case 'save':
        $information_id = oos_db_prepare_input($_GET['mID']);

        $sql_data_array_sort = array('sort_order' => $sort_order);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($insert_sql_data, $sql_data_array_sort);

          oos_db_perform($oostable['information'], $sql_data_array);
          $information_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($update_sql_data, $sql_data_array_sort);

          oos_db_perform($oostable['information'], $sql_data_array, 'update', "information_id = '" . oos_db_input($information_id) . "'");
        }

        $information_image = oos_get_uploaded_file('information_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

        if (is_uploaded_file($information_image['tmp_name'])) {
          if (!is_writeable($image_directory)) {
            if (is_dir($image_directory)) {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $image_directory), 'error');
            } else {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_DOES_NOT_EXIST, $image_directory), 'error');
            }
          } else {
            $dbconn->Execute("UPDATE " . $oostable['information'] . " SET information_image = '" . $information_image['name'] . "' WHERE information_id = '" . oos_db_input($information_id) . "'");
            oos_get_copy_uploaded_file($information_image, $image_directory);
          }
        }


        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $information_name_array = $_POST['information_name'];
          $information_url_array = $_POST['information_url'];
          $information_heading_title_array = $_POST['information_heading_title'];
          $information_description_array = $_POST['information_description'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('information_name' => oos_db_prepare_input($information_name_array[$lang_id]));
          $sql_data_array_url = array('information_url' => oos_db_prepare_input($information_url_array[$lang_id]));
          $sql_data_array_head = array('information_heading_title' => oos_db_prepare_input($information_heading_title_array[$lang_id]));
          $sql_data_array_desc = array('information_description' => oos_db_prepare_input($information_description_array[$lang_id]));

          $sql_data_array = array_merge($sql_data_array, $sql_data_array_url, $sql_data_array_desc , $sql_data_array_head);

          if ($action == 'insert') {
            $insert_sql_data = array('information_id' => $information_id,
                                     'information_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['information_description'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['information_description'], $sql_data_array, 'update', "information_id = '" . oos_db_input($information_id) . "' AND information_languages_id = '" . intval($lang_id) . "'");
          }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $information_id));
        break;

      case 'deleteconfirm':
        $information_id = oos_db_prepare_input($_GET['mID']);

        if ($information_id > 5) {
           if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
             $informationtable = $oostable['information'];
             $informations_result = $dbconn->Execute("SELECT information_image FROM $informationtable WHERE information_id = '" . oos_db_input($information_id) . "'");
             $informations = $informations_result->fields;
             $image_location = OOS_ABSOLUTE_PATH . OOS_IMAGES . $informations['information_image'];
             if (file_exists($image_location)) @unlink($image_location);
           }

           $informationtable = $oostable['information'];
           $dbconn->Execute("DELETE FROM $informationtable WHERE information_id = '" . oos_db_input($information_id) . "'");

           $information_descriptiontable = $oostable['information_description'];
           $dbconn->Execute("DELETE FROM $information_descriptiontable WHERE information_id = '" . oos_db_input($information_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $_GET['page']));
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
			<div class="row wrapper gray-bg page-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['information'], 'selected_box=information') . '">' . BOX_HEADING_INFORMATION . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->
			
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
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_INFORMATION; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $informationtable = $oostable['information'];
  $information_descriptiontable = $oostable['information_description'];
  $information_result_raw = "SELECT i.information_id, id.information_name, i.information_image, i.sort_order, i.date_added, i.last_modified, status 
                              FROM $informationtable i,
                                   $information_descriptiontable id
                              WHERE i.information_id = id.information_id AND
                                    id.information_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY i.sort_order, id.information_name";
  $information_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $information_result_raw, $information_result_numrows);
  $information_result = $dbconn->Execute($information_result_raw);
  while ($information = $information_result->fields) {
    if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $information['information_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {

      $mInfo_array = array_merge($information);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($information['information_id'] == $mInfo->information_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $information['information_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $information['information_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $information['information_name']; ?></td>
                <td class="dataTableContent">
<?php
  if ($information['status'] == '1') {
    echo '<a href="' . oos_href_link_admin($aContents['information'], 'action=setflag&flag=0&lID=' . $information['information_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
  } else {
    echo '<a href="' . oos_href_link_admin($aContents['information'], 'action=setflag&flag=1&lID=' . $information['information_id'] . '&page=' . $_GET['page']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
  }
?></td>

                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($information['information_id'] == $mInfo->information_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $information['information_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
                </tr>


<?php
    // Move that ADOdb pointer!
    $information_result->MoveNext();
  }
?>
              <tr>
                <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $information_split->display_count($information_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_INFORMATION); ?></td>
                    <td class="smallText" align="right"><?php echo $information_split->display_links($information_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="3" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=new') . '">' . oos_button('insert', BUTTON_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_INFORMATION . '</b>');

      $contents = array('form' => oos_draw_form('id', 'information', $aContents['information'], 'action=insert', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);

      $informations_name_inputs_string = '';
      $informations_inputs_string = '';
      $informations_description_inputs_string = '';
      $informations_heading_title_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $informations_name_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_name[' . $languages[$i]['id'] . ']');
        $informations_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_url[' . $languages[$i]['id'] . ']');
        $informations_heading_title_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_heading_title[' . $languages[$i]['id'] . ']');
        $informations_description_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_textarea_field('information_description[' . $languages[$i]['id'] . ']' , 'soft', '100', '10' );
      }

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_NAME . $informations_name_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_IMAGE . '<br />' . oos_draw_file_field('information_image'));

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_URL . $informations_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_HEADING_TITLE . $informations_heading_title_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_DESCRIPTION . $informations_description_inputs_string);

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order'));


      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;


    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_INFORMATION . '</b>');

      $contents = array('form' => oos_draw_form('id', 'information', $aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=save', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);

      $informations_name_inputs_string = '';
      $informations_inputs_string = '';
      $informations_description_inputs_string = '';
      $informations_heading_title_inputs_string = '';

      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $informations_name_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_name[' . $languages[$i]['id'] . ']', oos_get_informations_name($mInfo->information_id, $languages[$i]['id']));
        $informations_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_url[' . $languages[$i]['id'] . ']', oos_get_informations_url($mInfo->information_id, $languages[$i]['id']));
        $informations_heading_title_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_input_field('information_heading_title[' . $languages[$i]['id'] . ']', oos_get_informations_heading_title($mInfo->information_id, $languages[$i]['id']));
        $informations_description_inputs_string .= '<br />' . oos_flag_icon($languages[$i]) . '&nbsp;' . oos_draw_textarea_field('information_description[' . $languages[$i]['id'] . ']', 'soft', '100', '10',  oos_get_informations_description($mInfo->information_id, $languages[$i]['id']));
     }

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_NAME . $informations_name_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_IMAGE . '<br />' . oos_draw_file_field('information_image') . '<br />' . $mInfo->information_image);

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_URL . $informations_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_HEADING_TITLE . $informations_heading_title_inputs_string);
      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_DESCRIPTION . $informations_description_inputs_string);

      $contents[] = array('text' => '<br />' . TEXT_INFORMATION_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order') . '<br />' . $mInfo->sort_order);

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      if ($mInfo->information_id > 5) {
        $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_INFORMATION . '</b>');

        $contents = array('form' => oos_draw_form('id', 'information', $aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=deleteconfirm', 'post', FALSE));
        $contents[] = array('text' => TEXT_DELETE_INTRO);
        $contents[] = array('text' => '<br /><b>' . $mInfo->information_name . '</b>');
        $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      }
      break;

    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->information_name . '</b>');
        if ($mInfo->information_id > 5) {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=delete') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a>');
        } else {
          $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->information_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a>');
        }
        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($mInfo->date_added));
        if (oos_is_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br />' . oos_info_image($mInfo->information_image, $mInfo->information_name));
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
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2016 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>