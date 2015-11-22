<?php
/* ----------------------------------------------------------------------
   $Id: manufacturers.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: manufacturers.php,v 1.51 2003/01/29 23:21:48 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  function oos_get_manufacturer_url($manufacturer_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $manufacturers_infotable = $oostable['manufacturers_info'];
    $manufacturer = $dbconn->Execute("SELECT manufacturers_url FROM $manufacturers_infotable WHERE manufacturers_id = '" . $manufacturer_id . "' AND manufacturers_languages_id = '" . intval($lang_id) . "'");

    return $manufacturer->fields['manufacturers_url'];
  }

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert':
      case 'save':
        $manufacturers_id = oos_db_prepare_input($_GET['mID']);

        $sql_data_array = array('manufacturers_name' => $manufacturers_name);

        if ($action == 'insert') {
          $insert_sql_data = array('date_added' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

          oos_db_perform($oostable['manufacturers'], $sql_data_array);
          $manufacturers_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
          $update_sql_data = array('last_modified' => 'now()');

          $sql_data_array = array_merge($sql_data_array, $update_sql_data);

          oos_db_perform($oostable['manufacturers'], $sql_data_array, 'update', "manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
        }

        $manufacturers_image = oos_get_uploaded_file('manufacturers_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

        if (is_uploaded_file($manufacturers_image['tmp_name'])) {
          if (!is_writeable($image_directory)) {
            if (is_dir($image_directory)) {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_NOT_WRITEABLE, $image_directory), 'error');
            } else {
              $messageStack->add_session(sprintf(ERROR_DIRECTORY_DOES_NOT_EXIST, $image_directory), 'error');
            }
          } else {
            $dbconn->Execute("UPDATE " . $oostable['manufacturers'] . " SET manufacturers_image = '" . $manufacturers_image['name'] . "' WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
            oos_get_copy_uploaded_file($manufacturers_image, $image_directory);
          }
        }

        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $manufacturers_url_array = $_POST['manufacturers_url'];
          $lang_id = $languages[$i]['id'];

          $sql_data_array = array('manufacturers_url' => oos_db_prepare_input($manufacturers_url_array[$lang_id]));

          if ($action == 'insert') {
            $insert_sql_data = array('manufacturers_id' => $manufacturers_id,
                                     'manufacturers_languages_id' => $lang_id);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['manufacturers_info'], $sql_data_array);
          } elseif ($action == 'save') {
            oos_db_perform($oostable['manufacturers_info'], $sql_data_array, 'update', "manufacturers_id = '" . oos_db_input($manufacturers_id) . "' and manufacturers_languages_id = '" . intval($lang_id) . "'");
          }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $manufacturers_id));
        break;

      case 'deleteconfirm':
        $manufacturers_id = oos_db_prepare_input($_GET['mID']);

        if (isset($_POST['delete_image']) && ($_POST['delete_image'] == 'on')) {
          $manufacturerstable = $oostable['manufacturers'];
          $manufacturer_result = $dbconn->Execute("SELECT manufacturers_image FROM $manufacturerstable WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
          $manufacturer = $manufacturer_result->fields;
          $image_location = OOS_ABSOLUTE_PATH . OOS_IMAGES . $manufacturer['manufacturers_image'];
          if (file_exists($image_location)) @unlink($image_location);
        }

        $manufacturerstable = $oostable['manufacturers'];
        $dbconn->Execute("DELETE FROM $manufacturerstable WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
        $manufacturers_infotable = $oostable['manufacturers_info'];
        $dbconn->Execute("DELETE FROM $manufacturers_infotable WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");

        if (isset($_POST['delete_products']) && ($_POST['delete_products'] == 'on')) {
          $productstable = $oostable['products'];
          $products_result = $dbconn->Execute("SELECT products_id FROM $productstable WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
          while ($products = $products_result->fields) {
            oos_remove_product($products['products_id']);

            // Move that ADOdb pointer!
            $products_result->MoveNext();
          }
          // Close result set
          $products_result->Close();
        } else {
          $productstable = $oostable['products'];
          $dbconn->Execute("UPDATE $productstable SET manufacturers_id = '' WHERE manufacturers_id = '" . oos_db_input($manufacturers_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page']));
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
							<a href="<?php echo oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
  $manufacturerstable = $oostable['manufacturers'];
  $manufacturers_result_raw = "SELECT manufacturers_id, manufacturers_name, manufacturers_image, date_added, last_modified
                               FROM $manufacturerstable
                               ORDER BY manufacturers_name";
  $manufacturers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $manufacturers_result_raw, $manufacturers_result_numrows);
  $manufacturers_result = $dbconn->Execute($manufacturers_result_raw);
  while ($manufacturers = $manufacturers_result->fields) {
    if ((!isset($_GET['mID']) || (isset($_GET['mID']) && ($_GET['mID'] == $manufacturers['manufacturers_id']))) && !isset($mInfo) && (substr($action, 0, 3) != 'new')) {
      $manufacturer_products_result = $dbconn->Execute("SELECT COUNT(*) AS products_count FROM " . $oostable['products'] . " WHERE manufacturers_id = '" . $manufacturers['manufacturers_id'] . "'");
      $manufacturer_products = $manufacturer_products_result->fields;

      $mInfo_array = array_merge($manufacturers, $manufacturer_products);
      $mInfo = new objectInfo($mInfo_array);
    }

    if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id) ) {
      echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id'] . '&action=edit') . '\'">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo $manufacturers['manufacturers_name']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($mInfo) && is_object($mInfo) && ($manufacturers['manufacturers_id'] == $mInfo->manufacturers_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $manufacturers['manufacturers_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $manufacturers_result->MoveNext();
  }
  // Close result set
  $manufacturers_result->Close();
?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $manufacturers_split->display_count($manufacturers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MANUFACTURERS); ?></td>
                    <td class="smallText" align="right"><?php echo $manufacturers_split->display_links($manufacturers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
<?php
  if (empty($action)) {
?>
              <tr>
                <td align="right" colspan="2" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=new') . '">' . oos_button('insert', BUTTON_INSERT) . '</a>'; ?></td>
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
      $heading[] = array('text' => '<b>' . TEXT_HEADING_NEW_MANUFACTURER . '</b>');

      $contents = array('form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'action=insert', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_NEW_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_NAME . '<br />' . oos_draw_input_field('manufacturers_name'));
      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_IMAGE . '<br />' . oos_draw_file_field('manufacturers_image'));

      $manufacturer_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $manufacturer_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']');
      }

      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $_GET['mID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_EDIT_MANUFACTURER . '</b>');

      $contents = array('form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=save', 'post', FALSE, 'enctype="multipart/form-data"'));
      $contents[] = array('text' => TEXT_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_NAME . '<br />' . oos_draw_input_field('manufacturers_name', $mInfo->manufacturers_name));
      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_IMAGE . '<br />' . oos_draw_file_field('manufacturers_image') . '<br />' . $mInfo->manufacturers_image);

      $manufacturer_inputs_string = '';
      $languages = oos_get_languages();
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        $manufacturer_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('manufacturers_url[' . $languages[$i]['id'] . ']', oos_get_manufacturer_url($mInfo->manufacturers_id, $languages[$i]['id']));
      }

      $contents[] = array('text' => '<br />' . TEXT_MANUFACTURERS_URL . $manufacturer_inputs_string);
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_MANUFACTURER . '</b>');

      $contents = array('form' => oos_draw_form('id', 'manufacturers', $aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $mInfo->manufacturers_name . '</b>');
      $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('delete_image', '', true) . ' ' . TEXT_DELETE_IMAGE);

      if ($mInfo->products_count > 0) {
        $contents[] = array('text' => '<br />' . oos_draw_checkbox_field('delete_products') . ' ' . TEXT_DELETE_PRODUCTS);
        $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $mInfo->products_count));
      }

      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($mInfo) && is_object($mInfo)) {
        $heading[] = array('text' => '<b>' . $mInfo->manufacturers_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['manufacturers'], 'page=' . $_GET['page'] . '&mID=' . $mInfo->manufacturers_id . '&action=delete') . '">' . oos_button('delete',  IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($mInfo->date_added));
        if (oos_is_not_null($mInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($mInfo->last_modified));
        $contents[] = array('text' => '<br />' . oos_info_image($mInfo->manufacturers_image, $mInfo->manufacturers_name));
        $contents[] = array('text' => '<br />' . TEXT_PRODUCTS . ' ' . $mInfo->products_count);
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
		<span>&copy; 2015 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>