<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


function oos_set_slider_status($slider_id, $status)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();


    if ($status == '1') {
        $slidertable = $oostable['categories_slider'];
        return $dbconn->Execute("UPDATE $slidertable SET status = '1', expires_date = NULL, date_status_change = now() WHERE slider_id = '" . intval($slider_id) . "'");
    } elseif ($status == '0') {
        $slidertable = $oostable['categories_slider'];
        return $dbconn->Execute("UPDATE $slidertable SET status = '0', date_status_change = now() WHERE slider_id = '" . intval($slider_id) . "'");
    } else {
        return -1;
    }
}

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);
$action = (isset($_GET['action']) ? $_GET['action'] : '');
$sID = (isset($_GET['sID']) ? intval($_GET['sID']) : '');


if (!empty($action)) {
    switch ($action) {
    case 'setflag':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            oos_set_slider_status($_GET['id'], $_GET['flag']);
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories_slider'], 'sID=' . intval($_GET['id']) . '&page=' . $nPage));
        break;

    case 'insert':
    case 'update':

        if (isset($_SESSION['formid']) && isset($_POST['formid']) && ($_SESSION['formid'] == $_POST['formid'])) {
            $products_id = intval($_POST['products_id']);
            $expires_date = oos_db_prepare_input($_POST['expires_date']);

            if (isset($_FILES['files'])) {
                foreach ($_FILES['files']['name'] as $key => $name) {
                    if (empty($name)) {
                        // purge empty slots
                        unset($_FILES['files']['name'][$key]);
                        unset($_FILES['files']['type'][$key]);
                        unset($_FILES['files']['tmp_name'][$key]);
                        unset($_FILES['files']['error'][$key]);
                        unset($_FILES['files']['size'][$key]);
                    }
                }
            }

            $sql_data_array = ['products_id' => intval($products_id),
                               'expires_date' => oos_db_prepare_input($expires_date),
                               'status'       => '1'];

            if ($action == 'insert') {
                $insert_sql_data = ['slider_date_added' => 'now()'];

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                oos_db_perform($oostable['categories_slider'], $sql_data_array);
                $slider_id = $dbconn->Insert_ID();
            } elseif ($action == 'update') {
                $update_sql_data = ['slider_last_modified' => 'now()'];
                $slider_id = intval($_POST['slider_id']);

                $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                oos_db_perform($oostable['categories_slider'], $sql_data_array, 'UPDATE', 'slider_id = \'' . intval($slider_id) . '\'');
            }

            if ((isset($_POST['slider_image']) && ($_POST['slider_image'] == 'yes')) && (isset($_POST['slider_preview_image']))) {
                $slider_preview_image = oos_db_prepare_input($_POST['slider_preview_image']);

                $categories_slidertable = $oostable['categories_slider'];
                $dbconn->Execute(
                    "UPDATE $categories_slidertable
								SET slider_image = NULL
								WHERE slider_id = '" . intval($slider_id) . "'"
                );

                // todo remove file
                //oos_remove_slider_image($slider_preview_image);
            }

            if (isset($_FILES['slider_image'])) {
                if ($_FILES["slider_image"]["error"] == UPLOAD_ERR_OK) {
                    $filename = oos_db_prepare_input($_FILES['slider_image']['name']);
                    $source = $_FILES['slider_image']['tmp_name'];
                    $type = oos_db_prepare_input($_FILES['slider_image']['type']);

                    if (is_image($filename)) {
                        $dir_fs_slider = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'slider/';
                        $filename = pathinfo($_FILES['slider_image']['name'], PATHINFO_FILENAME);
                        $extension = strtolower(pathinfo($_FILES['slider_image']['name'], PATHINFO_EXTENSION));

                        $slider_image = $filename.'.'.$extension;
                        $new_path = $dir_fs_slider.$slider_image;

                        //New file name if the file already exists
                        if (file_exists($new_path)) {
                            $id = 1;
                            do {
                                // If file exists, append a number to the file name
                                $slider_image = $filename.'_'.$id.'.'.$extension;
                                $new_path = $dir_fs_slider.$slider_image;
                                $id++;
                            } while (file_exists($new_path));
                        }

                        move_uploaded_file($source, $new_path);

                        $messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED, 'success');


                        $sql_data_array = [];
                        $sql_data_array = array('slider_image' => oos_db_prepare_input($slider_image));

                        oos_db_perform($oostable['categories_slider'], $sql_data_array, 'UPDATE', 'slider_id = \'' .  intval($slider_id) . '\'');
                    }
                } else {
                    $messageStack->add_session(ERROR_NO_IMAGE_FILE, 'error');
                }
            }


            oos_redirect_admin(oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . intval($slider_id)));
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage));
        break;

    case 'deleteconfirm':
        $slider_id = oos_db_prepare_input($_GET['sID']);

        $slidertable = $oostable['categories_slider'];
        $dbconn->Execute("DELETE FROM $slidertable WHERE slider_id = '" . oos_db_input($slider_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage));
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
                            <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
if (($action == 'new') || ($action == 'edit')) {
    $form_action = 'insert';
    if (($action == 'edit') && isset($_GET['sID'])) {
        $form_action = 'update';

        $slidertable = $oostable['categories_slider'];
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $query = "SELECT p.products_id, p.products_image, pd.products_name, s.slider_image, s.expires_date
                FROM $productstable p,
                     $products_descriptiontable pd,
                     $slidertable s
                WHERE p.products_setting = '2' AND
					p.products_id = pd.products_id AND
                     pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                      p.products_id = s.products_id AND
                      s.slider_id = '" . intval($sID) . "'
                   ORDER BY pd.products_name";
        $product = $dbconn->GetRow($query);

        $sInfo = new objectInfo($product);
    } elseif (($action == 'new') && isset($_GET['pID'])) {
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql = "SELECT p.products_id, p.products_image, pd.products_name
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_setting = '2' AND
					p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    p.products_id = '" . intval($_GET['pID']) . "'";
        $product = $dbconn->GetRow($sql);

        $sInfo = new objectInfo($product);
    } else {
        $sInfo = new objectInfo(array());

        $slider_array = [];
        $slidertable = $oostable['categories_slider'];
        $productstable = $oostable['products'];
        $slider_result = $dbconn->Execute("SELECT p.products_id FROM $productstable p, $slidertable s WHERE s.products_id = p.products_id");
        while ($slider = $slider_result->fields) {
            $slider_array[] = $slider['products_id'];

            // Move that ADOdb pointer!
            $slider_result->MoveNext();
        }
    } ?>
<!-- body_text //-->
    <div class="card card-default">
        <div class="card-header"><?php echo HEADING_TITLE; ?></div>
            <div class="card-body">
	
<?php

    echo oos_draw_form('fileupload', 'new_slider', $aContents['categories_slider'], (isset($_GET['sID']) ? 'info=' . $sID : '') . '&action=' . $form_action, 'post', true, 'enctype="multipart/form-data"');

    $sFormid = md5(uniqid(rand(), true));
    $_SESSION['formid'] = $sFormid;
    echo oos_draw_hidden_field('formid', $sFormid);
    echo oos_hide_session_id();

    if ($form_action == 'update') {
        echo oos_draw_hidden_field('slider_id', intval($sID));

        if (!empty($sInfo->products_id)) {
            echo oos_draw_hidden_field('products_id', $sInfo->products_id);
        }
    } elseif (isset($_GET['pID'])) {
        echo oos_draw_hidden_field('products_id', $sInfo->products_id);
    }

    if (!empty($sInfo->products_name)) {
        echo '<br><a href="' . oos_catalog_link($aCatalog['product_info'], 'products_id=' . $sInfo->products_id) . '" target="_blank" rel="noopener">' . product_info_image($sInfo->products_image, $sInfo->products_name) . '</a><br>';
    } else {
        ?>
                    <fieldset>
                        <div class="form-group row mb-3 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_SLIDER_PRODUCT; ?></label>
                           <div class="col-md-10">
                                <?php echo oos_draw_products_pull_down('products_id', $slider_array); ?>
                           </div>
                        </div>
                     </fieldset>
        <?php
    } ?>
                     <fieldset>
                        <div class="form-group row mb-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_SLIDER_EXPIRES_DATE; ?></label>
                           <div class="col-xl-6 col-10">
                              <div class="input-group date" id="datetimepicker1">
                                 <input class="form-control" type="text" name="expires_date" value="<?php echo(isset($sInfo->expires_date) ? $sInfo->expires_date : ''); ?>">

                                 <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </fieldset>

                        <div class="row mb-3 pb-3 bb">
                            <div class="col-lg-2">        
            <?php echo TEXT_SLIDER_IMAGE; ?>
                            </div>
                            <div class="col-lg-10">        


        <?php

        if (isset($sInfo->slider_image) && (!empty($sInfo->slider_image))) {
            echo '<div class="text-center"><div class="d-block" style="width: 460px; height: 345px;">';
            echo oos_info_image('slider/' . $sInfo->slider_image, $sInfo->products_name, '460px', '345px');
            echo '</div></div>';

            echo oos_draw_hidden_field('slider_preview_image', $sInfo->slider_image);
            echo '<br>';
            echo oos_draw_checkbox_field('slider_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;
        } else {
            ?>

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 460px; height: 345px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

    <input type="file" size="40" name="slider_image"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
<p><?php echo TEXT_IMAGE_SIZE; ?></p>


            <?php
        } ?>  
		
                            </div>
                        </div>

                    <div class="clearfix mt-120"></div>
                    
                    <div class="text-right mt-3">
                        <?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?>
                        <?php echo(($form_action == 'insert') ? oos_submit_button(BUTTON_INSERT) : oos_submit_button(BUTTON_UPDATE)); ?>
                    </div>                    
                    
            </form>    
        </div>
    </div>

    <?php
} else {
            ?>
    <div class="table-responsive">
        <table class="table w-100">
          <tr>
            <td valign="top">
            
                <table class="table table-striped w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                            <th class="text-right">&nbsp;</th>
                            <th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
    <?php
    $slider_result_raw = "SELECT p.products_id, pd.products_name, s.slider_id, s.slider_date_added, s.slider_last_modified, s.expires_date, s.date_status_change, s.status FROM " . $oostable['products'] . " p, " . $oostable['categories_slider'] . " s, " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND p.products_id = s.products_id ORDER BY pd.products_name";
            $slider_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $slider_result_raw, $slider_result_numrows);
            $slider_result = $dbconn->Execute($slider_result_raw);
            while ($slider = $slider_result->fields) {
                if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $slider['slider_id']))) && !isset($sInfo)) {
                    $productstable = $oostable['products'];
                    $products_result = $dbconn->Execute("SELECT products_image FROM " . $oostable['products'] . " WHERE products_id = '" . $slider['products_id'] . "'");
                    $products = $products_result->fields;
                    $sInfo_array = array_merge($slider, $products);
                    $sInfo = new objectInfo($sInfo_array);
                }

                if (isset($sInfo) && is_object($sInfo) && ($slider['slider_id'] == $sInfo->slider_id)) {
                    echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=edit') . '\'">' . "\n";
                } else {
                    echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $slider['slider_id']) . '\'">' . "\n";
                } ?>
                <td><?php echo $slider['products_name']; ?></td>
                <td  align="right">&nbsp;</td>
                <td  align="right">
        <?php
        if ($slider['status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['categories_slider'], 'action=setflag&flag=0&id=' . $slider['slider_id']) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'action=setflag&flag=1&id=' . $slider['slider_id']) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
                <td class="text-right">
        <?php
        if (isset($sInfo) && is_object($sInfo) && ($slider['slider_id'] == $sInfo->slider_id)) {
            echo '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=edit') . '"><i class="fas fa-pencil-alt" title="' . BUTTON_EDIT . '"></i></a>
				<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=delete') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $slider['slider_id']. '&action=edit') . '"><i class="fas fa-pencil-alt" title="' . BUTTON_EDIT . '"></i></a>
				<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $slider['slider_id'] . '&action=delete') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>';
        } ?>
            &nbsp;</td>                

              </tr>
        <?php
        // Move that ADOdb pointer!
        $slider_result->MoveNext();
            } ?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $slider_split->display_count($slider_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
                    <td class="smallText" align="right"><?php echo $slider_split->display_links($slider_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
    <?php
    if (empty($action)) {
        ?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_TAB) . '</a>'; ?></td>
                  </tr>
        <?php
    } ?>
                </table></td>
              </tr>
            </table></td>
    <?php
    $heading = [];
            $contents = [];

            switch ($action) {
    case 'delete':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_SLIDER . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories_slider', $aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=deleteconfirm', 'post', false));
        $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
        $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    default:
        if (isset($sInfo) && is_object($sInfo)) {
            $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories_slider'], 'page=' . $nPage . '&sID=' . $sInfo->slider_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->slider_date_added));
            $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->slider_last_modified));
            $contents[] = array('align' => 'center', 'text' => '<br>' . product_info_image($sInfo->products_image, $sInfo->products_name));
            $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($sInfo->expires_date) . '</b>');
            $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change));
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