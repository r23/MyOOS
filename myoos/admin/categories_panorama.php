<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.138 2002/11/18 21:38:22 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/functions/function_categories.php';
require 'includes/classes/class_currencies.php';
require 'includes/classes/class_upload.php';

require_once MYOOS_INCLUDE_PATH . '/includes/lib/htmlpurifier/library/HTMLPurifier.auto.php';

$currencies = new currencies();

$action = (isset($_GET['action']) ? $_GET['action'] : '');
$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : $current_category_id);
$cID = (isset($_GET['cID']) ? intval($_GET['cID']) : 0);
$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);

if (!empty($action)) {
    switch ($action) {
		case 'insert_panorama':
		case 'update_panorama':
			if (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) {		
				$panorama_id = intval($_POST['panorama_id']);		
				
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

				if (isset($_POST['categories_id'])) $categories_id = intval($_POST['categories_id']);

				if ((isset($_GET['cID'])) && ($categories_id == '')) {
					$categories_id = intval($_GET['cID']);
				}
				
				if (isset($_POST['scene_id'])) $scene_id = intval($_POST['scene_id']);

				$sql_data_array = array();
				$sql_data_array = array('categories_id' => intval($categories_id),
										'panorama_author' => oos_db_prepare_input($_POST['panorama_author']),
										'panorama_autoload' => oos_db_prepare_input($_POST['panorama_autoload']),
										'panorama_autorotates' => oos_db_prepare_input($_POST['panorama_autorotates']));

				if ($action == 'insert_panorama') {
					$insert_sql_data = array();
					$insert_sql_data = array('panorama_date_added' => 'now()');

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

					oos_db_perform($oostable['categories_panorama'], $sql_data_array);

					$panorama_id = $dbconn->Insert_ID();
				} elseif ($action == 'update_panorama') {
					$update_sql_data = array('panorama_last_modified' => 'now()');

					$sql_data_array = array_merge($sql_data_array, $update_sql_data);

					oos_db_perform($oostable['categories_panorama'], $sql_data_array, 'UPDATE', 'panorama_id = \'' . $panorama_id . '\'');
				}
	
				$aLanguages = oos_get_languages();
				$nLanguages = count($aLanguages);

				for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
					$language_id = $aLanguages[$i]['id'];
				
					$sql_data_array = array('panorama_name' => oos_db_prepare_input($_POST['panorama_name'][$language_id]),
											'panorama_title' => oos_db_prepare_input($_POST['panorama_title'][$language_id]),
											'panorama_description_meta' => oos_db_prepare_input($_POST['panorama_description_meta'][$language_id]));

					if ($action == 'insert_panorama') {
						$insert_sql_data = array('panorama_id' => intval($panorama_id),
												'panorama_viewed' => '0',
												'panorama_languages_id' => intval($aLanguages[$i]['id']));

						$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
					
						oos_db_perform($oostable['categories_panorama_description'], $sql_data_array);
					} elseif ($action == 'update_panorama') {					
						oos_db_perform($oostable['categories_panorama_description'], $sql_data_array, 'UPDATE', 'panorama_id = \'' . intval($panorama_id) . '\' AND panorama_languages_id = \'' . intval($language_id) . '\'');
					}
				}

				if ( ($_POST['remove_image'] == 'yes') && (isset($_POST['panorama_preview_image'])) ) {
					$panorama_preview_image = oos_db_prepare_input($_POST['panorama_preview_image']);
				
					$categoriestable = $oostable['categories_panorama'];
					$dbconn->Execute("UPDATE $categoriestable
								SET panorama_preview = NULL
								WHERE panorama_id = '" . intval($panorama_id) . "'");				
				
					oos_remove_panorama_preview_image($panorama_preview_image);				
				}

				if ( ($_POST['scene_image'] == 'yes') && (isset($_POST['scene_preview_image'])) ) {
					$scene_preview_image = oos_db_prepare_input($_POST['scene_preview_image']);
				
					$categoriestable = $oostable['categories_panorama_scene'];
					$dbconn->Execute("UPDATE $categoriestable
								SET scene_image = NULL
								WHERE panorama_id = '" . intval($panorama_id) . "'");				
				
					oos_remove_scene_image($scene_preview_image);				
				}


				// Panorama Preview
				$aPreviewOptions = array(
					'image_versions' => array(
					// The empty image version key defines options for the original image.
					// Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
					// Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
						'' => array(
							// Automatically rotate images based on EXIF meta data:
							'auto_orient' => TRUE
						),
						'large' => array(
							// 'auto_orient' => TRUE,
							// 'crop' => TRUE,
							// 'jpeg_quality' => 82,
							// 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
							// 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
							'max_width' => 1920, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
							'max_height' => 1080, // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
						),
						'medium' => array(
							// 'auto_orient' => TRUE,
							// 'crop' => TRUE,
							// 'jpeg_quality' => 82,
							// 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
							// 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
							'max_width' => 675, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
							'max_height' => 380 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
						),
					),
				);

				$oPanoramaPreview = new upload('panorama_preview', $aPreviewOptions);

				$dir_fs_panorama_preview = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/';
				$oPanoramaPreview->set_destination($dir_fs_panorama_preview);	
			
				if ($oPanoramaPreview->parse() && oos_is_not_null($oPanoramaPreview->filename)) {

					$categories_panoramatable = $oostable['categories_panorama'];
					$dbconn->Execute("UPDATE $categories_panoramatable
								SET panorama_preview = '" . oos_db_input($oPanoramaPreview->filename) . "'
								WHERE panorama_id = '" . intval($panorama_id) . "'");
				}			
			

			
					if (isset($_FILES['scene_image'])) {
						if ($_FILES["scene_image"]["error"] == UPLOAD_ERR_OK) {

							$filename = $_FILES['scene_image']['name'];
							$source = $_FILES['scene_image']['tmp_name'];
							$type = $_FILES['scene_image']['type'];

							if (is_image($filename)) {
								
								$dir_fs_panoramas = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'panoramas/';								
								$filename = pathinfo($_FILES['scene_image']['name'], PATHINFO_FILENAME);
								$extension = strtolower(pathinfo($_FILES['scene_image']['name'], PATHINFO_EXTENSION));
 
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
									} while(file_exists($new_path));
								}
 
								move_uploaded_file($source, $new_path);
					
								$messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED, 'success');


								$sql_data_array = array();
								$sql_data_array = array('panorama_id' => intval($panorama_id),
														'scene_image' => oos_db_prepare_input($scene_image),
														'scene_type' => 'equirectangular');
			
								if (!isset($scene_id)) {	
									oos_db_perform($oostable['categories_panorama_scene'], $sql_data_array);
									$scene_id = $dbconn->Insert_ID();
								} else {
									// todo 360 Tour
									oos_db_perform($oostable['categories_panorama_scene'], $sql_data_array, 'UPDATE', 'scene_id = \'' . $scene_id . '\'');
								}
						
							} else {
								$messageStack->add_session(ERROR_NO_IMAGE_FILE, 'error');					
							}
						}
					}


					// HOTSPOT			
					for ($h = 1; $h <= 4; $h++) {
						
						if (isset($_POST['hotspot_id'][$h])) $hotspot_id = intval($_POST['hotspot_id'][$h]);						
						
						$sql_data_array = array();
						$sql_data_array = array('panorama_id' => intval($panorama_id),
												'scene_id' => intval($scene_id),
												'hotspot_pitch' => oos_db_prepare_input($_POST['hotspot_pitch'][$h]),
												'hotspot_yaw' => oos_db_prepare_input($_POST['hotspot_yaw'][$h]),
												'hotspot_type' => 'info',
												'hotspot_icon_class' => '',
												'products_id' => oos_db_prepare_input($_POST['products_id'][$h]),
												'categories_id' => oos_db_prepare_input($_POST['categories_id'][$h]),										
												'hotspot_url' => oos_db_prepare_input($_POST['hotspot_url'][$h]));

						if ($action == 'insert_panorama') {
							oos_db_perform($oostable['categories_panorama_scene_hotspot'], $sql_data_array);

							$hotspot_id = $dbconn->Insert_ID();
						} elseif ($action == 'update_panorama') {
							oos_db_perform($oostable['categories_panorama_scene_hotspot'], $sql_data_array, 'UPDATE', 'hotspot_id = \'' . $hotspot_id . '\'');
						}
		

						for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
							$language_id = $aLanguages[$i]['id'];
				
							$sql_data_array = array('hotspot_text' => oos_db_prepare_input($_POST['hotspot_text'][$h][$language_id]));

							if ($action == 'insert_panorama') {
								$insert_sql_data = array('hotspot_id' => intval($hotspot_id),
														'hotspot_languages_id' => intval($aLanguages[$i]['id']));

								$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
					
								oos_db_perform($oostable['categories_panorama_scene_hotspot_text'], $sql_data_array);
							} elseif ($action == 'update_panorama') {					
								oos_db_perform($oostable['categories_panorama_scene_hotspot_text'], $sql_data_array, 'UPDATE', 'hotspot_id = \'' . intval($hotspot_id) . '\' AND hotspot_languages_id = \'' . intval($language_id) . '\'');
							}
						}
					}

					$preview = (isset($_POST['preview']) ? $_POST['preview'] : '');
					if (!empty($preview)) {
						switch ($preview) {
							case 'scene':
								oos_redirect_admin(oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=update_panorama#scene'));
								break;
							case 'hotspot':
								oos_redirect_admin(oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=update_panorama#hotspot'));
								break;								
						}
					}

					oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories_id));

				break;
			
			}

    }
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


// check if the catalog image directory exists
if (is_dir(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
    if (!is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
} else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
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
<?php
if ($action == 'panorama' || $action == 'update_panorama') {

    $parameters = array('panorama_id' => '',
						'categories_id' => '',
						'panorama_preview' => '',
                        'panorama_author' => '',
						'panorama_autoload' => 'false',
						'panorama_autorotates' => '-2',
						'panorama_name' => '',
                        'panorama_title' => '',
                        'panorama_description_meta' => '',
						'categories_panorama_scene' => array(),						
                        'panorama_date_added' => '',
                        'panorama_last_modified' => '');
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


	$aLanguages = oos_get_languages();
	$nLanguages = count($aLanguages);

	$text_new_or_edit = ($action=='panorama') ? TEXT_INFO_HEADING_NEW_PANORAMA : TEXT_INFO_HEADING_EDIT_PANORAMA;

	$back_url = $aContents['categories'];
	$back_url_params = 'cPath=' . $cPath;
	if (oos_is_not_null($pInfo->categories_id)) {
		$back_url_params .= '&cID=' . $pInfo->categories_id;
	}			

	$aAutorotates = array();
	$aAutorotates = array('-3', '-2', '-1', '1', '2', '3');

?>
<link rel="stylesheet" href="css/pannellum.css"/>
<script type="text/javascript" src="js/pannellum/libpannellum.js"></script>
<script type="text/javascript" src="js/pannellum/pannellum.js"></script>
    <style>
	#panorama_hot,
    #panorama {
        width: 800px;
        height: 450px;
    }
    </style>


	<!-- Breadcrumbs //-->
	<div class="content-heading">
		<div class="col-lg-12">
			<h2><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)); ?></h2>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
				</li>
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
				</li>
				<li class="breadcrumb-item active">
					<strong><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)); ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<!-- END Breadcrumbs //-->

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">
<?php
	echo oos_draw_form('fileupload', 'panorama', $aContents['categories_panorama'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');
	
		$sFormid = md5(uniqid(rand(), true));
		$_SESSION['formid'] = $sFormid;
		echo oos_draw_hidden_field('formid', $sFormid);
		echo oos_draw_hidden_field('panorama_id', $pInfo->panorama_id);	
		echo oos_hide_session_id();
?>

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
						<?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . IMAGE_BACK . '</strong></a>'; ?>		
						<?php echo oos_submit_button(IMAGE_SAVE); ?>
						<?php echo oos_reset_button(BUTTON_RESET); ?>			   
					</div>				  
					<div class="tab-pane active" id="edit" role="tabpanel">
<?php
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_PANORAMA_NAME; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('panorama_name[' . $aLanguages[$i]['id'] . ']', (($panorama_name[$aLanguages[$i]['id']]) ? stripslashes($panorama_name[$aLanguages[$i]['id']]) : oos_get_panorama_name($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_PANORAMA_TITLE; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('panorama_title[' . $aLanguages[$i]['id'] . ']', (($panorama_title[$aLanguages[$i]['id']]) ? stripslashes($panorama_title[$aLanguages[$i]['id']]) : oos_get_panorama_title($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_PANORAMA_DESCRIPTION_META; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('panorama_description_meta[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (($panorama_description_meta[$aLanguages[$i]['id']]) ? stripslashes($panorama_description_meta[$aLanguages[$i]['id']]) : oos_get_panorama_description_meta($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php echo TEXT_PANORAMA_AUTHOR; ?></label>
							<div class="col-lg-10">
								<?php echo oos_draw_input_field('panorama_author',  $panorama['panorama_author']); ?>
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
	}
?>
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
											if ($panorama['panorama_autoload'] == 'true') echo ' checked="checked"';
											echo  '>&nbsp;';
									   ?>
											<span class="badge badge-success float-right"><?php echo ENTRY_ON; ?></span>
										</label>
									</div>
									<div class="c-radio c-radio-nofont">
										<label>
										<?php
											echo '<input type="radio" name="panorama_autoload" value="false"'; 
											if ($panorama['panorama_autoload'] == 'false') echo ' checked="checked"';
											echo  '>&nbsp;';
									   ?>
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

<?php if (!empty($panorama['scene_image']))  echo oos_draw_hidden_field('scene_id', $panorama['scene_id']); ?>
	
						<div class="row mb-3 pb-3 bb">
							<div class="col-lg-2">		
								<?php echo TEXT_SCENE_IMAGE; ?>
							</div>
							<div class="col-lg-10">		


<?php
	if (oos_is_not_null($pInfo->scene_image)) {
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
	}
?>	
							</div>
						</div>
<?php
	if (oos_is_not_null($pInfo->scene_image)) {
?>
						<div class="text-right mt-3 mb-5">
							<?php echo oos_preview_button(IMAGE_PREVIEW, 'scene'); ?>	
						</div>
						
						<div class="row mb-3 pb-3 bb">
							<div class="col-lg-2">		
								<?php echo TEXT_PANORAMA_PREVIEW; ?>
							</div>
							
			<div class="col-lg-10">

<div id="panorama"></div>

<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "<?php echo OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/' . oos_output_string($panorama['scene_image']); ?>",
<?php if (!empty($panorama['panorama_pitch']))  echo '"pitch": "' . $panorama['panorama_pitch'] . '," '; ?>	
<?php if (!empty($panorama['panorama_yaw']))  echo '"yaw": "' . $panorama['panorama_yaw'] . '," '; ?>
<?php if (!empty($panorama['panorama_hfov']))  echo '"hfov": "' . $panorama['panorama_hfov'] . '," '; ?>			
<?php if (!empty($panorama['panorama_preview']))  echo '"preview": "' . OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/large/' . oos_output_string($panorama['panorama_preview']) . '",'; ?>
<?php if (!empty($panorama['panorama_autoload']) && ($panorama['panorama_autoload'] == 'true'))  echo '"autoLoad": true, '; ?>								
<?php if (!empty($panorama['panorama_autorotates']))  echo '"autoRotate": ' . $panorama['panorama_autorotates']. ','; ?>
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
				<?php echo oos_preview_button(IMAGE_PREVIEW, 'scene'); ?>
            </div>
			
<div id="panorama"></div>			
			
<?php	
}
?>	
		
                     </div>
                     <div class="tab-pane" id="hotspot" role="tabpanel">
	 
<?php

if (!empty($pInfo->panorama_id)) {
	$id = 0;

	$html = "\n";
	$html .= '"hotSpots": [' . "\n";
	
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
	while ($hotspot = $hotspot_result->fields) {
		
		if (($hotspot['hotspot_pitch'] != 0) || ($hotspot['hotspot_yaw'] != 0)) {
					$html .= '       {' . "\n";
			$html .= '            "pitch": ' . $hotspot['hotspot_pitch'] . ',' . "\n";
			$html .= '            "yaw": ' . $hotspot['hotspot_yaw'] . ',' . "\n";
			$html .= '            "type": "' . $hotspot['hotspot_type'] . '",' . "\n";
			if (!empty($hotspot['hotspot_text'])) $html .= '            "text": "' . $hotspot['hotspot_text'] . '",' . "\n";
			if (!empty($hotspot['products_id'])) $html .= '            "URL":  "' .  oos_catalog_link($aCatalog['product_info'], 'products_id=' . $hotspot['products_id']) . '",' . "\n";
			$html .= '        },' . "\n";
		}
		
		$id++;
		echo oos_draw_hidden_field('hotspot_id['. $id . ']', $hotspot['hotspot_id']);

?>
					<div class="mb-3 pb-3 bb">
						<h2>			
							<?php echo TEXT_HOTSPOT_ID . ' ' . $id; ?>
						</h2>		
<?php
		for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_HOTSPOT_TEXT; ?></label>
							  <?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('hotspot_text['. $id . '][' . $aLanguages[$i]['id'] . ']', oos_get_hotspot_text($hotspot['hotspot_id'], $aLanguages[$i]['id'])); ?>
                              </div>
                           </div>
                        </fieldset>						
<?php
		}
?>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_PITCH; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('hotspot_pitch['. $id . ']',  $hotspot['hotspot_pitch']); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('hotspot_yaw['. $id . ']',  $hotspot['hotspot_yaw']); ?>
                              </div>
                           </div>
                        </fieldset>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
								<?php
									$array = array();
									$array[] = $hotspot['products_id'];
								?>
								<?php echo oos_draw_products_pull_down('products_id['. $id . ']', '', $array); ?>
                           </div>
                        </div>
                     </fieldset>
				</div>
<?php 
		// Move that ADOdb pointer!
		$hotspot_result->MoveNext();
	}
	
	$html .= ']' . "\n";
	
} else {

	$array = array();
	$array[] = '';
	$html = '';
	
	for ($h = 0; $h <= 3; $h++) {
	    $id = 1+ $h;
?>
					<div class="mb-3 pb-3 bb">
						<h2>			
							<?php echo TEXT_HOTSPOT_ID . ' ' . $id; ?>
						</h2>		
<?php
     for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_HOTSPOT_TEXT; ?></label>
							  <?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('hotspot_text['. $h . '][' . $aLanguages[$i]['id'] . ']', ''); ?>
                              </div>
                           </div>
                        </fieldset>						
<?php
    }
?>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_PITCH; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('hotspot_pitch['. $h . ']',  ''); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('hotspot_yaw['. $h . ']', ''); ?>
                              </div>
                           </div>
                        </fieldset>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
								<?php echo oos_draw_products_pull_down('products_id['. $h . ']', '', $array); ?>
                           </div>
                        </div>
                     </fieldset>
				</div>
<?php
    }
}
?>		


<?php
	if (oos_is_not_null($pInfo->scene_image)) {
?>	
						<div class="text-right mt-3 mb-5">
							<?php echo oos_preview_button(IMAGE_PREVIEW, 'hotspot'); ?>	
						</div>
						
						<div class="row mb-3 pb-3 bb">
												
							<div class="col-lg-2">		
								<?php echo TEXT_PANORAMA_PREVIEW; ?>
							</div>
							
			<div class="col-lg-10">

<div id="panorama_hot"></div>
<script>
pannellum.viewer('panorama_hot', {
    "type": "equirectangular",
    "panorama": "<?php echo OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/' . oos_output_string($panorama['scene_image']); ?>",
<?php if (!empty($panorama['panorama_pitch']))  echo '"pitch": "' . $panorama['panorama_pitch'] . '," '; ?>	
<?php if (!empty($panorama['panorama_yaw']))  echo '"yaw": "' . $panorama['panorama_yaw'] . '," '; ?>
<?php if (!empty($panorama['panorama_hfov']))  echo '"hfov": "' . $panorama['panorama_hfov'] . '," '; ?>			
<?php if (!empty($panorama['panorama_preview']))  echo '"preview": "' . OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/large/' . oos_output_string($panorama['panorama_preview']) . '",'; ?>
<?php if (!empty($panorama['panorama_autoload']) && ($panorama['panorama_autoload'] == 'true'))  echo '"autoLoad": true, '; ?>								
<?php if (!empty($panorama['panorama_autorotates']))  echo '"autoRotate": ' . $panorama['panorama_autorotates']. ','; ?>
<?php if (!empty($panorama['panorama_author'])) { ?>
    "title": "<?php echo $panorama['panorama_title']; ?>",
    "author": "<?php echo $panorama['panorama_author']; ?>",
<?php 
	} 

	echo $html;
?>		
});
</script>

      <div id="panoramadata" style="font-weight: bold;"></div>
  
  </div>
</div>
							</div>
						</div>
	
<?php
} else {
?>
						<div class="text-right mt-3 mb-5">
							<?php echo oos_preview_button(IMAGE_PREVIEW, 'hotspot'); ?>	
						</div>
			
						<div id="panorama"></div>
<?php	
}
?>	



                     </div>
                  </div>
               </div>
            <div class="text-right mt-3">			
				<?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . IMAGE_BACK . '</strong></a>'; ?>
				<?php echo oos_submit_button(IMAGE_SAVE); ?>
				<?php echo oos_reset_button(BUTTON_RESET); ?>			
            </div>
		</form>
	</div>

</div>
<!-- body_text_eof //-->
<?php
  }
?>


				</div>
			</div>
        </div>

	</div>
</div>

<?php
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>