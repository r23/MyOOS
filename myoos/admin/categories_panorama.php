<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.138 2002/11/18 21:38:22 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/functions/function_categories.php';
require 'includes/classes/class_currencies.php';
require 'includes/classes/class_upload.php';

$currencies = new currencies();

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$cPath = filter_string_polyfill(filter_input(INPUT_GET, 'cPath')) ?: $current_category_id;
$cID = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT) ?: 0; 
$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

switch ($action) {
case 'insert_panorama':
case 'update_panorama':

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

    if (isset($_POST['panorama_id'])) {
        $panorama_id = intval($_POST['panorama_id']);
    }
    if (isset($_POST['categories_id'])) {
        $categories_id = intval($_POST['categories_id']);
    }

    if (isset($_GET['cID']) && (empty($categories_id))) {
        $categories_id = intval($_GET['cID']);
    }

    if (isset($_POST['scene_id'])) {
        $scene_id = intval($_POST['scene_id']);
    }

    $sql_data_array = [];
    $sql_data_array = ['categories_id' => intval($categories_id), 'panorama_author' => (isset($_POST['panorama_author']) ? oos_db_prepare_input($_POST['panorama_author']) : ''), 'panorama_autoload' => (isset($_POST['panorama_autoload']) ? oos_db_prepare_input($_POST['panorama_autoload']) : 'false'), 'panorama_autorotates' => (isset($_POST['panorama_autoload']) ? oos_db_prepare_input($_POST['panorama_autorotates']) : '-2')];

    if ($action == 'insert_panorama') {
        $insert_sql_data = [];
        $insert_sql_data = ['panorama_date_added' => 'now()'];

        $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

        oos_db_perform($oostable['categories_panorama'], $sql_data_array);

        $panorama_id = $dbconn->Insert_ID();
    } elseif ($action == 'update_panorama') {
        $update_sql_data = ['panorama_last_modified' => 'now()'];

        $sql_data_array = [...$sql_data_array, ...$update_sql_data];

        oos_db_perform($oostable['categories_panorama'], $sql_data_array, 'UPDATE', 'panorama_id = \'' . $panorama_id . '\'');
    }

    $aLanguages = oos_get_languages();
    $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        $language_id = $aLanguages[$i]['id'];

        $sql_data_array = ['panorama_name' => oos_db_prepare_input($_POST['panorama_name'][$language_id]), 'panorama_title' => oos_db_prepare_input($_POST['panorama_title'][$language_id]), 'panorama_description_meta' => oos_db_prepare_input($_POST['panorama_description_meta'][$language_id])];

        if ($action == 'insert_panorama') {
            $insert_sql_data = ['panorama_id' => intval($panorama_id), 'panorama_viewed' => '0', 'panorama_languages_id' => intval($aLanguages[$i]['id'])];

            $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

            oos_db_perform($oostable['categories_panorama_description'], $sql_data_array);
        } elseif ($action == 'update_panorama') {
            oos_db_perform($oostable['categories_panorama_description'], $sql_data_array, 'UPDATE', 'panorama_id = \'' . intval($panorama_id) . '\' AND panorama_languages_id = \'' . intval($language_id) . '\'');
        }
    }

    if ((isset($_POST['remove_image']) && ($_POST['remove_image'] == 'yes')) && (isset($_POST['panorama_preview_image']))) {
        $panorama_preview_image = oos_db_prepare_input($_POST['panorama_preview_image']);

        $categoriestable = $oostable['categories_panorama'];
        $dbconn->Execute(
            "UPDATE $categoriestable
								SET panorama_preview = NULL
								WHERE panorama_id = '" . intval($panorama_id) . "'"
        );

        oos_remove_panorama_preview_image($panorama_preview_image);
    }

    if ((isset($_POST['scene_image']) && ($_POST['scene_image'] == 'yes')) && (isset($_POST['scene_preview_image']))) {
        $scene_preview_image = oos_db_prepare_input($_POST['scene_preview_image']);

        $categories_panorama_scenetable = $oostable['categories_panorama_scene'];
        $dbconn->Execute(
            "UPDATE $categories_panorama_scenetable
								SET scene_image = NULL
								WHERE panorama_id = '" . intval($panorama_id) . "'"
        );

        oos_remove_scene_image($scene_preview_image);
    }


    // Panorama Preview
    $aPreviewOptions = ['image_versions' => [
        // The empty image version key defines options for the original image.
        // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
        // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
        '' => [
            // Automatically rotate images based on EXIF meta data:
            'auto_orient' => true,
        ],
        'large' => [
            // 'auto_orient' => TRUE,
            // 'crop' => TRUE,
            // 'jpeg_quality' => 82,
            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
            'max_width' => 1920,
            // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
            'max_height' => 1080,
        ],
        'medium' => [
            // 'auto_orient' => TRUE,
            // 'crop' => TRUE,
            // 'jpeg_quality' => 82,
            // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
            // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
            'max_width' => 675,
            // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
            'max_height' => 380,
        ],
    ]];

        $oPanoramaPreview = new upload('panorama_preview', $aPreviewOptions);

        $dir_fs_panorama_preview = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/';
        $oPanoramaPreview->set_destination($dir_fs_panorama_preview);

    if ($oPanoramaPreview->parse() && oos_is_not_null($oPanoramaPreview->filename)) {
        $categories_panoramatable = $oostable['categories_panorama'];
        $dbconn->Execute(
            "UPDATE $categories_panoramatable
								SET panorama_preview = '" . oos_db_input($oPanoramaPreview->filename) . "'
								WHERE panorama_id = '" . intval($panorama_id) . "'"
        );
    }



    if (isset($_FILES['scene_image'])) {
        if ($_FILES["scene_image"]["error"] == UPLOAD_ERR_OK) {
            $filename = oos_db_prepare_input($_FILES['scene_image']['name']);
            $source = $_FILES['scene_image']['tmp_name'];
            $type = oos_db_prepare_input($_FILES['scene_image']['type']);

            if (is_image($filename)) {
                $dir_fs_panoramas = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/';
                $filename = pathinfo((string) $_FILES['scene_image']['name'], PATHINFO_FILENAME);
                $extension = strtolower(pathinfo((string) $_FILES['scene_image']['name'], PATHINFO_EXTENSION));

                $scene_image = $filename.'.'.$extension;
                $new_path = $dir_fs_panoramas.$scene_image;

                //New file name if the file already exists
                if (file_exists($new_path)) {
                    $id = 1;
                    do {
                        // If file exists, append a number to the file name
                        $scene_image = $filename.'_'.$id.'.'.$extension;
                        $new_path = $dir_fs_panoramas.$scene_image;
                        $id++;
                    } while (file_exists($new_path));
                }

                move_uploaded_file($source, $new_path);

                $messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED, 'success');


                $sql_data_array = [];
                $sql_data_array = ['panorama_id' => intval($panorama_id), 'scene_image' => oos_db_prepare_input($scene_image), 'scene_type' => 'equirectangular'];

                if (!isset($scene_id)) {
                    oos_db_perform($oostable['categories_panorama_scene'], $sql_data_array);
                    $scene_id = $dbconn->Insert_ID();
                } else {
                    // todo 360 Tour
                    oos_db_perform($oostable['categories_panorama_scene'], $sql_data_array, 'UPDATE', 'scene_id = \'' .  intval($scene_id) . '\'');
                }
            } else {
                $messageStack->add_session(ERROR_NO_IMAGE_FILE, 'error');
            }
        }
    }

    // HOTSPOT
    if (isset($_POST['hotspot_count'])) {
        $nHotspots = is_countable($_POST['hotspot_count']) ? count($_POST['hotspot_count']) : 0;

        for ($h = 0, $nh = $nHotspots; $h < $nh; $h++) {
            $hotspot_action = 'insert_hotspot';
            if (isset($_POST['hotspot_id']) && (isset($_POST['hotspot_id'][$h]) || is_numeric($_POST['hotspot_id'][$h]))) {
                $hotspot_id = intval($_POST['hotspot_id'][$h]);
                $hotspot_action = 'update_hotspot';
            }

            if (isset($_POST['delete-hotspott'][$h])) {
                $hotspot_action = 'delete_hotspot';

                if (is_numeric($_POST['delete-hotspott'][$h])) {
                    $delete_hotspot = intval($_POST['delete-hotspott'][$h]);

                    if (isset($_POST['hotspot_id'][$h]) || is_numeric($_POST['hotspot_id'][$h])) {
                        $hotspot_id = intval($_POST['hotspot_id'][$h]);

                        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot'] . " WHERE hotspot_id = '" . intval($hotspot_id) . "'");
                        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot_text'] . " WHERE hotspot_id = '" . intval($hotspot_id) . "'");
                    }
                }
            }


            $sql_data_array = [];
            $sql_data_array = ['panorama_id' => intval($panorama_id), 'scene_id' => intval($scene_id), 'hotspot_pitch' => (isset($_POST['hotspot_pitch'][$h]) ? oos_db_prepare_input($_POST['hotspot_pitch'][$h]) : ''), 'hotspot_yaw' => (isset($_POST['hotspot_yaw'][$h]) ? oos_db_prepare_input($_POST['hotspot_yaw'][$h]) : ''), 'hotspot_type' => 'info', 'hotspot_icon_class' => '', 'products_id' => (isset($_POST['products_id'][$h]) ? oos_db_prepare_input($_POST['products_id'][$h]) : ''), 'categories_id' => (isset($_POST['categories_id'][$h]) ? oos_db_prepare_input($_POST['categories_id'][$h]) : ''), 'hotspot_url' => (isset($_POST['hotspot_url'][$h]) ? oos_db_prepare_input($_POST['hotspot_url'][$h]) : '')];

            if ($hotspot_action == 'insert_hotspot') {
                oos_db_perform($oostable['categories_panorama_scene_hotspot'], $sql_data_array);

                $hotspot_id = $dbconn->Insert_ID();
            } elseif ($hotspot_action == 'update_hotspot') {
                oos_db_perform($oostable['categories_panorama_scene_hotspot'], $sql_data_array, 'UPDATE', 'hotspot_id = \'' . intval($hotspot_id) . '\'');
            }


            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                $language_id = $aLanguages[$i]['id'];

                $sql_data_array = ['hotspot_text' => oos_db_prepare_input($_POST['hotspot_text'][$h][$language_id])];

                if ($hotspot_action == 'insert_hotspot') {
                    $insert_sql_data = ['hotspot_id' => intval($hotspot_id), 'hotspot_languages_id' => intval($aLanguages[$i]['id'])];

                    $sql_data_array = [...$sql_data_array, ...$insert_sql_data];
                    oos_db_perform($oostable['categories_panorama_scene_hotspot_text'], $sql_data_array);
                } elseif ($hotspot_action == 'update_hotspot') {
                    oos_db_perform($oostable['categories_panorama_scene_hotspot_text'], $sql_data_array, 'UPDATE', 'hotspot_id = \'' . intval($hotspot_id) . '\' AND hotspot_languages_id = \'' . intval($language_id) . '\'');
                }
            }
        }
    }

    $preview = ($_POST['preview'] ?? '');
    if (!empty($preview)) {
        switch ($preview) {
        case 'scene':
                oos_redirect_admin(oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&page=' . $nPage . '&action=update_panorama#scene'));
            break;
        case 'hotspot':
                    oos_redirect_admin(oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . (isset($_GET['cID']) ? '&cID=' . $cID : '')  . '&page=' . $nPage . '&action=update_panorama#hotspot'));
            break;
        }
    }

    oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories_id . '&page=' . $nPage));

    break;

case 'delete_panorama_confirm':
    if (isset($_POST['panorama_id']) && is_numeric($_POST['panorama_id'])) {
        $panorama_id = oos_db_prepare_input($_POST['panorama_id']);

        oos_remove_panorama($panorama_id);
    }

    oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . $nPage));
    break;
}


$cPath_back = '';
if (is_array($aPath) && count($aPath) > 0) {
    for ($i = 0, $n = count($aPath) - 1; $i < $n; $i++) {
        if (empty($cPath_back)) {
            $cPath_back .= $aPath[$i];
        } else {
            $cPath_back .= '_' . $aPath[$i];
        }
    }
}

$cPath_back = (oos_is_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';


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
<?php
    $parameters = ['panorama_id' => '', 'categories_id' => '', 'panorama_preview' => '', 'panorama_author' => '', 'panorama_autoload' => 'false', 'panorama_autorotates' => '-2', 'panorama_name' => '', 'panorama_title' => '', 'panorama_description_meta' => '', 'categories_panorama_scene' => [], 'panorama_date_added' => '', 'panorama_last_modified' => ''];
    $pInfo = new objectInfo($parameters);

if (isset($_GET['cID'])) {
    $categories_panoramatable = $oostable['categories_panorama'];
    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $categories_panorama_scenetable = $oostable['categories_panorama_scene'];
    $query = "SELECT c.panorama_id, c.categories_id, c.panorama_preview, c.panorama_author, 
						 c.panorama_autoload, c.panorama_autorotates, c.panorama_date_added, c.panorama_last_modified,
						 cd.panorama_name, cd.panorama_title, cd.panorama_description_meta, cd.panorama_keywords,
						 s.scene_id, s.scene_image, s.scene_type, s.scene_hfov, s.scene_pitch, s.scene_yaw, s.scene_default
                  FROM $categories_panoramatable c,
                       $categories_panorama_descriptiontable cd,
					   $categories_panorama_scenetable s
                  WHERE c.categories_id = '" . intval($cID) . "' AND
                        c.panorama_id = cd.panorama_id AND
						s.panorama_id = c.panorama_id AND
                        cd.panorama_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $panorama_result = $dbconn->Execute($query);
    if (!$panorama_result->RecordCount()) {
        $form_action = 'insert_panorama';
    } else {
        $form_action = 'update_panorama';
        $panorama = $panorama_result->fields;

        $pInfo = new objectInfo($panorama);
    }
}


if ($action == 'delete_panorama') {
    ?>
    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo sprintf(TEXT_INFO_HEADING_DELETE_PANORAMA, oos_get_category_name($pInfo->categories_id)); ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
        <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                </li>
                <li class="breadcrumb-item">
        <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
                </li>
                <li class="breadcrumb-item active">
                    <strong><?php echo sprintf(TEXT_INFO_HEADING_DELETE_PANORAMA, oos_get_category_name($pInfo->categories_id)); ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <!-- END Breadcrumbs //-->

    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
        <?php
            echo oos_draw_form('delete', 'panorama', $aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cID . '&action=delete_panorama_confirm&page=' . $nPage, 'post', false);
        echo oos_draw_hidden_field('panorama_id', $pInfo->panorama_id); ?>
                <div class="row  mt-3 mb-5">
                    <div class="col-lg-12">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading"><?php echo TEXT_HEADING_DELETE_PANORAMA; ?></h4>
                            <p><?php echo TEXT_DELETE_PANORAMA_INTRO; ?></p>
                            <hr>
                            <p class="mb-0"></p>
                        </div>                
                    </div>
                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <p>        
           <?php echo  oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cID . '&page=' . $nPage . '&action=panorama') . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?>
                        </p>
                    </div>
                </div>
                </form>
                <!-- end row -->        

            </div>
        </div>
    </div>
        <?php
} elseif ($action == 'panorama' || $action == 'update_panorama') {
    $aLanguages = oos_get_languages();
    $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

    $text_new_or_edit = ($form_action == 'insert_panorama') ? TEXT_INFO_HEADING_NEW_PANORAMA : TEXT_INFO_HEADING_EDIT_PANORAMA;

    $back_url = $aContents['categories'];
    $back_url_params = 'cPath=' . oos_prepare_input($cPath) . '&page=' . $nPage;
    if (oos_is_not_null($pInfo->categories_id)) {
        $back_url_params .= '&cID=' . $pInfo->categories_id;
    }

    $aAutorotates = [];
    $aAutorotates = ['-3', '-2', '-1', '1', '2', '3']; ?>
    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo sprintf($text_new_or_edit, oos_get_category_name($cID)); ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
         <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                </li>
                <li class="breadcrumb-item">
         <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
                </li>
                <li class="breadcrumb-item active">
                    <strong><?php echo sprintf($text_new_or_edit, oos_get_category_name($cID)); ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <!-- END Breadcrumbs //-->

            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">
        <?php
        echo oos_draw_form('fileupload', 'panorama', $aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=' . $form_action, 'post', true, 'enctype="multipart/form-data"');

        $sFormid = md5(uniqid(random_int(0, mt_getrandmax()), true));
        $_SESSION['formid'] = $sFormid;
        echo oos_draw_hidden_field('formid', $sFormid);
        echo oos_draw_hidden_field('panorama_id', $pInfo->panorama_id);
        echo oos_hide_session_id(); ?>

               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified" id="myTab">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab" aria-selected="true"><?php echo TEXT_PANORAMA_SETTINGS; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#scene" aria-controls="scene" role="tab" data-toggle="tab" aria-selected="true"><?php echo TEXT_SCENE; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#hotspot" aria-controls="hotspot" role="tab" data-toggle="tab" aria-selected="true"><?php echo TEXT_HOTSPOT; ?></a>
                     </li>
                  </ul>
                  <div class="tab-content">
                    <div class="text-right mt-3 mb-3">   
          <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>        
          <?php echo oos_submit_button(BUTTON_SAVE); ?>
          <?php echo oos_reset_button(BUTTON_RESET); ?>               
                    </div>                  
                    <div class="tab-pane active" id="edit" role="tabpanel">

                        <div class="row  mt-3 mb-5">
                            <div class="col-lg-10">
                                <h2>            
             <?php echo sprintf(TEXT_EDIT_PANORAMA, oos_get_category_name($cID)); ?>
                                </h2>
                            </div>
                            <div class="col-lg-2">        
                                <div class="text-right">
          <?php
            if ($form_action == 'update_panorama') {
                echo '<a href="' . oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cID . '&page=' . $nPage . '&action=delete_panorama') . '"><i class="fa fa-trash" title="' . BUTTON_DELETE . '"></i> ' . TEXT_PANORAMA_DELETE . '</a>';
            } ?>
                                </div>
                            </div>
                        </div>



        <?php
        for ($i=0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
            ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                echo TEXT_EDIT_PANORAMA_NAME;
                                                                   } ?></label>
            <?php if ($nLanguages > 1) {
                echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
            } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('panorama_name[' . $aLanguages[$i]['id'] . ']', (isset($panorama_name[$aLanguages[$i]['id']]) ? stripslashes((string) $panorama_name[$aLanguages[$i]['id']]) : oos_get_panorama_name($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
            <?php
        }
        for ($i=0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
            ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                echo TEXT_EDIT_PANORAMA_TITLE;
                                                                   } ?></label>
            <?php if ($nLanguages > 1) {
                echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
            } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('panorama_title[' . $aLanguages[$i]['id'] . ']', (isset($panorama_title[$aLanguages[$i]['id']]) ? stripslashes((string) $panorama_title[$aLanguages[$i]['id']]) : oos_get_panorama_title($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
            <?php
        }
        for ($i=0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
            ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                echo TEXT_EDIT_PANORAMA_DESCRIPTION_META;
                                                                   } ?></label>
            <?php if ($nLanguages > 1) {
                echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
            } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_textarea_field('panorama_description_meta[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (isset($panorama_description_meta[$aLanguages[$i]['id']]) ? stripslashes((string) $panorama_description_meta[$aLanguages[$i]['id']]) : oos_get_panorama_description_meta($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
            <?php
        } ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php echo TEXT_PANORAMA_AUTHOR; ?></label>
                            <div class="col-lg-10">
            <?php echo oos_draw_input_field('panorama_author', ($panorama['panorama_author'] ?? '')); ?>
                            </div>
                        </div>
                    </fieldset>
                    <div class="row mb-3 pb-3 bb">
                        <div class="col-lg-2">        
           <?php echo TEXT_PANORAMA_PREVIEW; ?>
                        </div>
                        <div class="col-lg-10">
        <?php
        if (oos_is_not_null($pInfo->panorama_preview)) {
            echo '<div class="text-center"><div class="d-block" style="width: 675px; height: 380px;">';
            echo oos_info_image('panoramas/medium/' . $pInfo->panorama_preview, $pInfo->panorama_name);
            echo '</div></div>';

            echo oos_draw_hidden_field('panorama_preview_image', $pInfo->panorama_preview);
            echo '<br>';
            echo oos_draw_checkbox_field('remove_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;
        } else {
            ?>

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 675px; height: 380px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

    <input type="file" size="40" name="panorama_preview"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
            <?php
        } ?>
                            </div>
                        </div>

                        <fieldset>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"><?php echo TEXT_PANORAMA_AUTOLOAD; ?></label>
                                <div class="col-lg-10">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
                                    <?php
                                        echo '<input type="radio" name="panorama_autoload" value="true"';
                                    if ((isset($panorama['panorama_autoload']) && $panorama['panorama_autoload'] == 'true')) {
                                        echo ' checked="checked"';
                                    }
                                    echo  '>&nbsp;'; ?>
                                            <span class="badge badge-success float-right"><?php echo ENTRY_ON; ?></span>
                                        </label>
                                    </div>
                                    <div class="c-radio c-radio-nofont">
                                        <label>
              <?php
                                        echo '<input type="radio" name="panorama_autoload" value="false"';
                if ((isset($panorama['panorama_autoload']) && $panorama['panorama_autoload'] == 'false')) {
                    echo ' checked="checked"';
                }
                echo  '>&nbsp;'; ?>
                                        <span class="badge badge-danger float-right"><?php echo ENTRY_OFF; ?></span>
                                        </label>
                                    </div>
                                </div>
                            </div>                              
                        </fieldset>                    
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_EDIT_AUTOROTATES; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_select_menu('panorama_autorotates', $aAutorotates, $pInfo->panorama_autorotates); ?></div>
                           </div>
                        </fieldset>
                    </div>
                     
                     
                     <div class="tab-pane" id="scene" role="tabpanel">

        <?php if (!empty($panorama['scene_image'])) {
            echo oos_draw_hidden_field('scene_id', $panorama['scene_id']);
        } ?>
    
                        <div class="row mb-3 pb-3 bb">
                            <div class="col-lg-2">        
            <?php echo TEXT_SCENE_IMAGE; ?>
                            </div>
                            <div class="col-lg-10">        


        <?php
        if (isset($pInfo->scene_image) && (!empty($pInfo->scene_image))) {
            echo '<div class="text-center"><div class="d-block" style="width: 460px; height: 260px;">';
            echo oos_info_image('panoramas/' . $pInfo->scene_image, $pInfo->panorama_name, '460px', '260px');
            echo '</div></div>';

            echo oos_draw_hidden_field('scene_preview_image', $pInfo->scene_image);
            echo '<br>';
            echo oos_draw_checkbox_field('scene_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;
        } else {
            ?>

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 460px; height: 260px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

    <input type="file" size="40" name="scene_image"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
            <?php
        } ?>    
                            </div>
                        </div>
        <?php
        if (isset($pInfo->scene_image) && (oos_is_not_null($pInfo->scene_image))) {
            // if (isset($pInfo->scene_image) && (!empty($pInfo->scene_image))) {?>
                        <div class="text-right mt-3 mb-5">
            <?php echo oos_preview_button(BUTTON_PREVIEW, 'scene'); ?>    
                        </div>

                        <div class="row mb-3 pb-3 bb">
                            <div class="col-lg-2">        
                                    <?php echo TEXT_PANORAMA_PREVIEW; ?>
                            </div>

                            <div class="col-lg-10">

                                <div id="panorama"></div>

<script nonce="<?php echo NONCE; ?>">
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "<?php echo OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/' . oos_output_string($panorama['scene_image']); ?>",
            <?php if (!empty($panorama['panorama_pitch'])) {
                echo '"pitch": "' . $panorama['panorama_pitch'] . '," ';
            } ?>    
            <?php if (!empty($panorama['panorama_yaw'])) {
                echo '"yaw": "' . $panorama['panorama_yaw'] . '," ';
            } ?>
            <?php if (!empty($panorama['panorama_hfov'])) {
                echo '"hfov": "' . $panorama['panorama_hfov'] . '," ';
            } ?>            
            <?php if (!empty($panorama['panorama_preview'])) {
                echo '"preview": "' . OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/large/' . oos_output_string($panorama['panorama_preview']) . '",';
            } ?>
            <?php if (!empty($panorama['panorama_autoload']) && ($panorama['panorama_autoload'] == 'true')) {
                echo '"autoLoad": true, ';
            } ?>                                
            <?php if (!empty($panorama['panorama_autorotates'])) {
                echo '"autoRotate": ' . $panorama['panorama_autorotates']. ',';
            } ?>
            <?php if (!empty($panorama['panorama_author'])) { ?>
    "title": "<?php echo $panorama['panorama_title']; ?>",
    "author": "<?php echo $panorama['panorama_author']; ?>"
            <?php } ?>    
});
</script>

                            </div>
                        </div>

            <?php
        } else {
            ?>
                        <div class="text-right mt-3 mb-5">
            <?php echo oos_preview_button(BUTTON_PREVIEW, 'scene'); ?>
                        </div>
            
                        <div id="panorama"></div>            
            
            <?php
        } ?>    
        
                    </div>
                    <div class="tab-pane" id="hotspot" role="tabpanel">

        <?php

        $spot_array = [];
        $array = [];
        $array[] = '';

        if (!empty($pInfo->panorama_id)) {
            $categories_panorama_scene_hotspot = $oostable['categories_panorama_scene_hotspot'];
            $categories_panorama_scene_hotspot_texttable = $oostable['categories_panorama_scene_hotspot_text'];
            $query = "SELECT h.hotspot_id, h.scene_id, h.hotspot_pitch, h.hotspot_yaw, h.hotspot_type,
                 h.hotspot_icon_class, h.products_id, h.categories_id, h.hotspot_url, 
				 ht.hotspot_text
          FROM $categories_panorama_scene_hotspot h,
               $categories_panorama_scene_hotspot_texttable ht
          WHERE h.scene_id = '" . intval($panorama['scene_id']) . "'
			AND h.panorama_id = '" . intval($pInfo->panorama_id) . "'
            AND h.hotspot_id = ht.hotspot_id
            AND ht.hotspot_languages_id = '" . intval($nLanguageID) . "'";
            $hotspot_result = $dbconn->Execute($query);

            if ($hotspot_result->RecordCount() >= 1) {
                $html = "\n";
                $html .= '"hotSpots": [' . "\n";

                while ($hotspot = $hotspot_result->fields) {
                    if (($hotspot['hotspot_pitch'] != '0.00') && ($hotspot['hotspot_yaw'] != '0.00')) {
                        $html .= '       {' . "\n";
                        $html .= '            "pitch": ' . $hotspot['hotspot_pitch'] . ',' . "\n";
                        $html .= '            "yaw": ' . $hotspot['hotspot_yaw'] . ',' . "\n";
                        $html .= '            "type": "' . $hotspot['hotspot_type'] . '",' . "\n";
                        if (!empty($hotspot['hotspot_text'])) {
                            $html .= '            "text": "' . $hotspot['hotspot_text'] . '",' . "\n";
                        }
                        if (!empty($hotspot['products_id'])) {
                            $html .= '            "URL":  "' .  oos_catalog_link($aCatalog['product_info'], 'products_id=' . $hotspot['products_id']) . '",' . "\n";
                        }
                        $html .= '        },' . "\n";
                    }

                    $spot_array[] = ['hotspot_id' => $hotspot['hotspot_id'], 'scene_id' => $hotspot['scene_id'], 'hotspot_pitch' => $hotspot['hotspot_pitch'], 'hotspot_yaw' => $hotspot['hotspot_yaw'], 'hotspot_type' => $hotspot['hotspot_type'], 'hotspot_icon_class' => $hotspot['hotspot_icon_class'], 'categories_id' => $hotspot['categories_id'], 'hotspot_url' => $hotspot['hotspot_url'], 'hotspot_text' => $hotspot['hotspot_text'], 'hotspot_id' => $hotspot['hotspot_id']];
                    // Move that ADOdb pointer!
                    $hotspot_result->MoveNext();
                }

                $html .= ']' . "\n";
            } ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div id="section_hotspot" class="card-box">

                                    <ul id="hotspot-setup" class="nav nav-pills navtab-bg">
            <?php
            $nSpot = count($spot_array);
            if ($nSpot < 1) {
                ?>
            <li class="nav-item">
                <a href="#hotspot1" data-toggle="tab" aria-expanded="true" class="nav-link active">
                    <i class="fa fa-dot-circle-o"></i> 1
                </a>
            </li>
                <?php
            } else {
                for ($i = 1, $n = $nSpot; $i <= $n; $i++) {
                    ?>
            <li class="nav-item">
                <a href="#hotspot<?php echo $i; ?>" data-toggle="tab" aria-expanded="true" class="nav-link <?php if ($i == 1) {
                        echo 'active';
                                 } ?>">
                    <i class="fa fa-dot-circle-o"></i> <?php echo $i; ?>
                </a>
            </li>
                    <?php
                }
            } ?>        
                                    </ul>
                                    <ul class="nav nav-pills navtab-bg">
                                        <li class="nav-item">
                                            <a href="#" class="add" data-action="addNewHotspotForm"><i class="fa fa-plus"></i>
                                                &nbsp;<?php echo TEXT_ADD_HOTSPOT; ?>
                                            </a>
                                        </li>
                                    </ul>
                                    <div id="hotspot-content" class="tab-content">
            <?php
            $nSpot = count($spot_array);
            if ($nSpot < 1) {
                echo oos_draw_hidden_field('hotspot_count[0]', $id); ?>
                    <div class="tab-pane fade show active" id="hotspot1">
                    
                        <div class="row  mt-3 mb-5">
                            <div class="col-lg-10">
                                <h2>            
                <?php echo TEXT_HOTSPOT_ID  . '  1'; ?>
                                </h2>
                            </div>
                            <div class="col-lg-2">        
                                <div class="text-right">
                                    <div class="form-check">
                <?php echo oos_draw_checkbox_field('delete-hotspott[0]', '1') . ' ' . TEXT_HOTSPOT_REMOVE; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->                    
    
                <?php
                for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                    ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                    echo TEXT_HOTSPOT_TEXT;
                                                                     } ?></label>
                    <?php if ($nLanguages > 1) {
                        echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                    } ?>
                              <div class="col-lg-9">
                    <?php echo oos_draw_input_field('hotspot_text[0][' . $aLanguages[$i]['id'] . ']', ''); ?>
                              </div>
                           </div>
                        </fieldset>                        
                    <?php
                } ?>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_PITCH; ?></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_input_field('hotspot_pitch[0]', ''); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_input_field('hotspot_yaw[0]', ''); ?>
                              </div>
                           </div>
                        </fieldset>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
                <?php echo oos_draw_products_pull_down('products_id[0]', $array, '', ($id ?? '')); ?>
                           </div>
                        </div>
                     </fieldset>
                </div>        
                <?php
            } else {
                for ($id = 0, $nh = $nSpot; $id < $nh; $id++) {
                    echo oos_draw_hidden_field('hotspot_id['. $id . ']', $spot_array[$id]['hotspot_id']);
                    echo oos_draw_hidden_field('hotspot_count['. $id . ']', $id);

                    $nHotspot = $id+1; ?>
                    <div class="tab-pane fade <?php if ($id == 0) {
                        echo 'show active';
                                              } ?>" id="hotspot<?php echo $nHotspot; ?>">
                        <div class="row  mt-3 mb-5">
                            <div class="col-lg-10">
                                <h2>            
                    <?php echo TEXT_HOTSPOT_ID . ' ' . $nHotspot; ?>
                                </h2>
                            </div>
                            <div class="col-lg-2">        
                                <div class="text-right">
                                    <div class="form-check">
                    <?php echo oos_draw_checkbox_field('delete-hotspott['. $id . ']', $spot_array[$id]['hotspot_id']) . ' ' . TEXT_HOTSPOT_REMOVE; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->                        
                    <?php
                    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                    echo TEXT_HOTSPOT_TEXT;
                                                                     } ?></label>
                        <?php if ($nLanguages > 1) {
                            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                        } ?>
                              <div class="col-lg-9">
                        <?php echo oos_draw_input_field('hotspot_text['. $id . '][' . $aLanguages[$i]['id'] . ']', oos_get_hotspot_text($spot_array[$id]['hotspot_id'], $aLanguages[$i]['id'])); ?>
                              </div>
                           </div>
                        </fieldset>                        
                        <?php
                    } ?>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_PITCH; ?></label>
                              <div class="col-lg-10">
                    <?php echo oos_draw_input_field('hotspot_pitch['. $id . ']', $spot_array[$id]['hotspot_pitch']); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
                    <?php echo oos_draw_input_field('hotspot_yaw['. $id . ']', $spot_array[$id]['hotspot_yaw']); ?>
                              </div>
                           </div>
                        </fieldset>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
                    <?php echo oos_draw_products_pull_down('products_id['. $id . ']', $array, ($spot_array[$id]['products_id'] ?? ''), $id); ?>
                           </div>
                        </div>
                     </fieldset>
                </div>
                    <?php
                }
            } ?>
                
                                        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->


<script nonce="<?php echo NONCE; ?>" id="templateNavItem" type="x-tmpl-mustache">
    <li class="nav-item">
            <a href="#hotspot{{counter}}" data-toggle="tab" aria-expanded="true" class="nav-link">
                <i class="fa fa-dot-circle-o"></i> {{counter}}
            </a>
    </li>
</script>

<script id="templateHotspot" type="x-tmpl-mustache">
<div class="tab-pane fade" id="hotspot{{counter}}">
    <div class="row  mt-3 mb-5">
        <div class="col-lg-10">
            <h2>            
            <?php echo TEXT_HOTSPOT_ID; ?> {{counter}}
            <?php echo oos_draw_hidden_field('hotspot_count[{{id}}]', '{{counter}}'); ?>
            </h2>
        </div>
        <div class="col-lg-2">        
            <div class="text-right">
                <div class="form-check">
            <?php echo oos_draw_checkbox_field('delete-hotspott[{{id}}]', 'YES') . ' ' . TEXT_HOTSPOT_REMOVE; ?>
                </div>            
            </div>
        </div>
    </div>
    <!-- end row -->

            <?php
            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                    echo TEXT_HOTSPOT_TEXT;
                                                                     } ?></label>
                <?php if ($nLanguages > 1) {
                    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                } ?>
                              <div class="col-lg-9">
                <?php echo oos_draw_input_field('hotspot_text[{{id}}][' . $aLanguages[$i]['id'] . ']', ''); ?>
                              </div>
                           </div>
                        </fieldset>                        
                <?php
            } ?>    
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_PITCH; ?></label>
                              <div class="col-lg-10">
                                <?php echo oos_draw_input_field('hotspot_pitch[{{id}}]', ''); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
                                <?php echo oos_draw_input_field('hotspot_yaw[{{id}}]', ''); ?>
                              </div>
                           </div>
                        </fieldset>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
                                <?php echo oos_draw_products_pull_down('products_id[{{id}}]', $array, '', '{{counter}}'); ?>
                           </div>
                        </div>
                     </fieldset>
                </div>
</script>

    


            <?php
            if (oos_is_not_null($pInfo->scene_image)) {
                ?>    
                        <div class="text-right mt-3 mb-5">
                <?php echo oos_preview_button(BUTTON_PREVIEW, 'hotspot'); ?>    
                        </div>
                        
                        <div class="row mb-3 pb-3 bb">
                                                
                            <div class="col-lg-2">        
                <?php echo TEXT_PANORAMA_PREVIEW; ?>
                            </div>
                            
                        <div class="col-lg-10">

                            <div id="panorama_hot"></div>
<script nonce="<?php echo NONCE; ?>">
pannellum.viewer('panorama_hot', {
    "type": "equirectangular",
    "panorama": "<?php echo OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/' . oos_output_string($panorama['scene_image']); ?>",
                <?php if (!empty($panorama['panorama_pitch'])) {
                    echo '"pitch": "' . $panorama['panorama_pitch'] . '," ';
                } ?>    
                <?php if (!empty($panorama['panorama_yaw'])) {
                    echo '"yaw": "' . $panorama['panorama_yaw'] . '," ';
                } ?>
                <?php if (!empty($panorama['panorama_hfov'])) {
                    echo '"hfov": "' . $panorama['panorama_hfov'] . '," ';
                } ?>            
                <?php if (!empty($panorama['panorama_preview'])) {
                    echo '"preview": "' . OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/large/' . oos_output_string($panorama['panorama_preview']) . '",';
                } ?>
                <?php if (!empty($panorama['panorama_autoload']) && ($panorama['panorama_autoload'] == 'true')) {
                    echo '"autoLoad": true, ';
                } ?>                        
                <?php if (!empty($panorama['panorama_autorotates'])) {
                    echo '"autoRotate": ' . $panorama['panorama_autorotates']. ',';
                } ?>
                <?php if (!empty($panorama['panorama_author'])) { ?>
    "title": "<?php echo $panorama['panorama_title']; ?>",
    "author": "<?php echo $panorama['panorama_author']; ?>",
                    <?php
                }
                echo $html; ?>        
});
</script>

                                <div id="panoramadata" style="font-weight: bold;"></div>
  
                            </div>
                        </div>
    
                <?php
            } else {
                ?>
                        <div class="text-right mt-3 mb-5">
                <?php echo oos_preview_button(BUTTON_PREVIEW, 'hotspot'); ?>    
                        </div>
            
                        <div id="panorama"></div>
                <?php
            }
        } else {
            ?>

                    <div class="alert alert-danger" role="alert">
            <?php echo ERROR_NO_PANORAMA; ?>    
                    </div>
            <?php
        } ?>


                     </div> <!-- tabpanel_eof //-->
                  </div>
               </div>
            <div class="text-right mt-3">            
        <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
        <?php echo oos_submit_button(BUTTON_SAVE); ?>
        <?php echo oos_reset_button(BUTTON_RESET); ?>            
            </div>
        </form>
    </div>
</div>
        <?php
}
?>
<!-- body_text_eof //-->
            </div>
        </div>

    </div>
</div>

<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>