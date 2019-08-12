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

				if (isset($_POST['categories_id'])) $categories_id = oos_db_prepare_input($_POST['categories_id']);

				if ((isset($_GET['cID'])) && ($categories_id == '')) {
					$categories_id = intval($_GET['cID']);
				}

				$sql_data_array = array();
				$sql_data_array = array('panorama_author' => oos_db_prepare_input($_POST['panorama_author']),
									'panorama_type' => 'equirectangular',
									'panorama_hfov' => oos_db_prepare_input($_POST['panorama_hfov']),
									'sort_order' => intval($sort_order));

			if ($action == 'insert_panorama') {
				$insert_sql_data = array();
				$insert_sql_data = array('parent_id' => intval($current_category_id),
										'date_added' => 'now()',
										'categories_status' => intval($nStatus));

				$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

				oos_db_perform($oostable['categories_panorama'], $sql_data_array);

				$categories_id = $dbconn->Insert_ID();
			} elseif ($action == 'update_panorama') {
				$update_sql_data = array('last_modified' => 'now()',
										'categories_status' => intval($nStatus));

				$sql_data_array = array_merge($sql_data_array, $update_sql_data);

				oos_db_perform($oostable['categories_panorama'], $sql_data_array, 'UPDATE', 'categories_id = \'' . $categories_id . '\'');
			}

			$aLanguages = oos_get_languages();
			$nLanguages = count($aLanguages);

			for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
				$language_id = $aLanguages[$i]['id'];
				
				$sql_data_array = array('panorama_name' => oos_db_prepare_input($_POST['panorama_name'][$language_id]),
										'panorama_title' => oos_db_prepare_input($_POST['panorama_title'][$language_id]),
										'panorama_description_meta' => oos_db_prepare_input($_POST['panorama_description_meta'][$language_id]));

				if ($action == 'insert_panorama') {
					$insert_sql_data = array('categories_id' => intval($categories_id),
											'panorama_languages_id' => intval($aLanguages[$i]['id']));

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
					oos_db_perform($oostable['categories_panorama_description'], $sql_data_array);
				} elseif ($action == 'update_panorama') {
					oos_db_perform($oostable['categories_panorama_description'], $sql_data_array, 'UPDATE', 'categories_id = \'' . intval($categories_id) . '\' AND panorama_languages_id = \'' . intval($language_id) . '\'');
				}
			}

			if ( ($_POST['remove_image'] == 'yes') && (isset($_POST['panorama_preview_image'])) ) {
				$panorama_preview_image = oos_db_prepare_input($_POST['panorama_preview_image']);
				
				$categoriestable = $oostable['categories_panorama'];
				$dbconn->Execute("UPDATE $categoriestable
                            SET categories_image = NULL
                            WHERE categories_id = '" . intval($categories_id) . "'");				
				
				oos_remove_category_image($panorama_preview_image);				
			}
			
			if ( ($_POST['remove_banner'] == 'yes') && (isset($_POST['categories_previous_banner'])) ) {
				$categories_previous_banner = oos_db_prepare_input($_POST['categories_previous_banner']);
				
				$categoriestable = $oostable['categories_panorama'];
				$dbconn->Execute("UPDATE $categoriestable
                            SET categories_banner = NULL
                            WHERE categories_id = '" . intval($categories_id) . "'");				
				
				oos_remove_category_banner($categories_previous_banner);				
			}
		
			for ($i = 1, $n = $nImageCounter+1; $i < $n; $i++) {
				if ( ($_POST['remove_category_image'][$i] == 'yes') && (isset($_POST['categories_previous_large_image'][$i])) ) {
					$categories_previous_large_image = oos_db_prepare_input($_POST['categories_previous_large_image'][$i]);

					$dbconn->Execute("DELETE FROM " . $oostable['categories_images'] . " WHERE categories_image = '" . oos_db_input($categories_previous_large_image) . "'");		
				
					oos_remove_category_image($categories_previous_large_image);				
				}
			}
		
			// Banner
			$aBannerOptions = array(
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
						'max_width' => 300, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
						'max_height' => 120 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
					),
				),
			);

			$oCategoriesBanner = new upload('categories_banner', $aBannerOptions);

			$dir_fs_catalog_banner = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'banners/';
			$oCategoriesBanner->set_destination($dir_fs_catalog_banner);	
			
			if ($oCategoriesBanner->parse() && oos_is_not_null($oCategoriesBanner->filename)) {

				$categoriestable = $oostable['categories_panorama'];
				$dbconn->Execute("UPDATE $categoriestable
                            SET categories_banner = '" . oos_db_input($oCategoriesBanner->filename) . "'
                            WHERE categories_id = '" . intval($categories_id) . "'");
			}			
			
			// Primary
			$options = array(
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
						'max_width' => 1024, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
						'max_height' => 1024, // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
					),
					'medium' => array(
						// 'auto_orient' => TRUE,
						// 'crop' => TRUE,
						// 'jpeg_quality' => 82,
						// 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
						// 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
						'max_width' => 300, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
						'max_height' => 300 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
					),
					'small' => array(
						// 'auto_orient' => TRUE,
						// 'crop' => TRUE,
						// 'jpeg_quality' => 82,
						// 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
						// 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
						'max_width' => 150, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
						'max_height' => 150 // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
					),
				),
			);

			$oCategoriesImage = new upload('panorama_image', $options);

			$dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/';
			$oCategoriesImage->set_destination($dir_fs_catalog_images);	
			
			if ($oCategoriesImage->parse() && oos_is_not_null($oCategoriesImage->filename)) {

				$categoriestable = $oostable['categories_panorama'];
				$dbconn->Execute("UPDATE $categoriestable
                            SET categories_image = '" . oos_db_input($oCategoriesImage->filename) . "'
                            WHERE categories_id = '" . intval($categories_id) . "'");
			}
			
			if (isset($_FILES['files'])) {
				$oImage = new upload('files', $options);

				$dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/';
				$oImage->set_destination($dir_fs_catalog_images);
				$oImage->parse();

				if (oos_is_not_null($oImage->response)) {
					$sort_order = 0 + $nImageCounter;
					foreach ($oImage->response as $index => $value) {
						$sort_order++;
						$sql_data_array = array('categories_id' => intval($categories_id),
												'panorama_image' => oos_db_prepare_input($value),
												'sort_order' => intval($sort_order));
						oos_db_perform($oostable['categories_images'], $sql_data_array);
					}
				}
			}



				if ($action == 'update_panorama') {
					oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories_id));
				}
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
if ($action == 'panorama' || $action == 'edit_panorama') {

    $parameters = array('panorama_id' => '',
						'categories_id' => '',
						'panorama_preview' => '',
                        'panorama_author' => '',
						'panorama_autoload' => '',
						'panorama_autorotates' => '-2',
						'panorama_name' => '',
                        'panorama_title' => '',
                        'panorama_description_meta' => '',
						'categories_panorama_scene' => array(),						
                        'panorama_date_added' => '',
                        'panorama_last_modified' => '');
	$pInfo = new objectInfo($parameters);

/*
$table = $prefix_table . 'categories_panorama';
$flds = "
  panorama_id I I NOTNULL AUTO PRIMARY,
  categories_id I NOTNULL DEFAULT '1' PRIMARY,
  panorama_preview C(255) NULL,
  panorama_author C(255) NULL,
  panorama_autoload C(5) DEFAULT 'false',  
  panorama_autorotates C(5) DEFAULT '-2',  
  panorama_date_added T,
  panorama_last_modified T 
";
dosql($table, $flds);

$table = $prefix_table . 'categories_panorama_description';
$flds = "
  panorama_id I DEFAULT '0' NOTNULL PRIMARY,
  panorama_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  panorama_name C(64) NOTNULL,
  panorama_title C(255) NULL,
  panorama_viewed I2 DEFAULT '0',
  panorama_description_meta C(250) NULL,
  panorama_keywords C(250) NULL
";
dosql($table, $flds);

$idxname = 'idx_panorama_name';
$idxflds = 'panorama_name';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_panorama_scene';
$flds = "
  scene_id I NOTNULL AUTO PRIMARY,
  panorama_id I NOTNULL DEFAULT '1' PRIMARY,
  scene_image C(255) NULL,
  scene_type C(24) NULL,
  scene_hfov C(3) NULL,
  scene_pitch C(3) NULL,
  scene_yaw C(3) NULL,
  scene_default I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_scene_image';
$idxflds = 'scene_image';
idxsql($idxname, $table, $idxflds);


$table = $prefix_table . 'categories_panorama_scene_hotspot';
$flds = "
  hotspot_id I I NOTNULL AUTO PRIMARY,
  scene_id I NOTNULL DEFAULT '1' PRIMARY,
  hotspot_pitch N '4.2' NOTNULL DEFAULT '0.0',
  hotspot_yaw N '4.2' NOTNULL DEFAULT '0.0',
  hotspot_type C(24) NULL,
  hotspot_icon_class C(24) NULL,
  hotspot_product_id I,
  hotspot_categories_id I
  hotspot_url C(255) NULL
";
dosql($table, $flds);


$table = $prefix_table . 'categories_panorama_scene_hotspot_text';
$flds = "
  hotspot_id I DEFAULT '0' NOTNULL PRIMARY,
  hotspot_languages_id I NOTNULL DEFAULT '1' PRIMARY,
  hotspot_text C(255) NULL
";
dosql($table, $flds);

*/

	if (isset($_GET['cID']) && empty($_POST)) {	
        $categories_panoramatable = $oostable['categories_panorama'];
        $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
        $query = "SELECT c.panorama_id, c.categories_id, c.panorama_preview, c.panorama_author, 
						  c.panorama_autoload, c.panorama_autorotates, c.panorama_date_added, c.panorama_last_modified
                  FROM $categories_panoramatable c,
                       $categories_panorama_descriptiontable cd
                  WHERE c.categories_id = '" . intval($cID) . "' AND
                        c.panorama_id = cd.panorama_id AND
                        cd.panorama_languages_id = '" . intval($_SESSION['language_id']) . "'";				
        $panorama_result = $dbconn->Execute($query);
		if ($panorama_result->RecordCount()) {		
			$panorama = $panorama_result->fields;

			$pInfo = new objectInfo($panorama);
		}
	}

	$aLanguages = oos_get_languages();
	$nLanguages = count($aLanguages);

	$text_new_or_edit = ($action=='panorama') ? TEXT_INFO_HEADING_NEW_PANORAMA : TEXT_INFO_HEADING_EDIT_CATEGORY;

	$back_url = $aContents['categories'];
	$back_url_params = 'cPath=' . $cPath;
	if (oos_is_not_null($pInfo->panorama_id)) {
		$back_url_params .= '&cID=' . $pInfo->panorama_id;
	}			

	$aAutorotates = array();
	$aAutorotates = array('-3', '-2', '-1', '1', '2', '3');

?>
<link rel="stylesheet" href="css/pannellum.css"/>
<script type="text/javascript" src="js/pannellum/pannellum.js"></script>
    <style>
    #panorama {
        width: 600px;
        height: 400px;
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
	$form_action = (isset($_GET['cID'])) ? 'update_panorama' : 'insert_panorama';
	echo oos_draw_form('fileupload', 'panorama', $aContents['categories_panorama'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');
	
		$sFormid = md5(uniqid(rand(), true));
		$_SESSION['formid'] = $sFormid;
		echo oos_draw_hidden_field('formid', $sFormid);
		echo oos_draw_hidden_field('panorama_id', $pInfo->panorama_id);	
		echo oos_hide_session_id();
?>

               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo TEXT_PANORAMA_SETTINGS; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#scene" aria-controls="scene" role="tab" data-toggle="tab"><?php echo TEXT_SCENE; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#hotspot" aria-controls="hotspot" role="tab" data-toggle="tab"><?php echo TEXT_HOTSPOT; ?></a>
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
								<?php echo oos_draw_textarea_field('panorama_description_meta[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (($panorama_description_meta[$aLanguages[$i]['id']]) ? stripslashes($panorama_description_meta[$aLanguages[$i]['id']]) : oos_get_category_description_meta($pInfo->panorama_id, $aLanguages[$i]['id']))); ?>
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
		echo '<div class="text-center"><div class="d-block" style="width: 460px; height: 260px;">';
        echo oos_info_image('category/medium/' . $pInfo->panorama_preview, $pInfo->panorama_name);
		echo '</div></div>';

		echo oos_draw_hidden_field('panorama_preview_image', $pInfo->panorama_preview);
		echo '<br>';
		echo oos_draw_checkbox_field('remove_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;	
	} else {	
?>

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 460px; height: 260px;"></div>
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
											echo '<input type="radio" name="panorama_autoload['. $nCounter . ']" value="true"'; 
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

						<div class="row mb-3 pb-3 bb">
							<div class="col-lg-2">		
								<?php echo TEXT_SCENE_IMAGE; ?>
							</div>
							<div class="col-lg-10">		


<?php
	if (oos_is_not_null($pInfo->scene_image)) {
		echo '<div class="text-center"><div class="d-block" style="width: 460px; height: 260px;;">';
        echo oos_info_image('category/medium/' . $pInfo->scene_image, $pInfo->panorama_name);
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
	if  ($action == 'edit_panorama') {
?>	
						<div class="row mb-3 pb-3 bb">
							<div class="col-lg-2">		
								<?php echo TEXT_PANORAMA_PREVIEW; ?>
							</div>
							
			<div class="col-lg-10">

<div id="panorama"></div>
<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "/images/bma-1.jpg",
    /*
     * Uncomment the next line to print the coordinates of mouse clicks
     * to the browser's developer console, which makes it much easier
     * to figure out where to place hot spots. Always remove it when
     * finished, though.
     */
    //"hotSpotDebug": true,
    "hotSpots": [
        {
            "pitch": 14.1,
            "yaw": 1.5,
            "type": "info",
            "text": "Baltimore Museum of Art",
            "URL": "https://artbma.org/"
        },
        {
            "pitch": -9.4,
            "yaw": 222.6,
            "type": "info",
            "text": "Art Museum Drive"
        },
        {
            "pitch": -0.9,
            "yaw": 144.4,
            "type": "info",
            "text": "North Charles Street"
        }
    ]
});
</script>

							</div>
						</div>
	
<?php
} else {
?>
            <div class="text-right mt-3 mb-5">
				<?php echo oos_submit_button(IMAGE_PREVIEW); ?>	
            </div>
			
<div id="panorama"></div>			
			
<?php	
}
?>	
		
                     </div>
                     <div class="tab-pane" id="hotspot" role="tabpanel">
				 
	<script type="text/javascript">
	// <!-- <![CDATA[
		window.totalinputs = 3;
		function addUploadBoxes(placeholderid, copyfromid, num) {
			for (i = 0; i < num; i++) {
				jQuery('#' + copyfromid).clone().insertBefore('#' + placeholderid);
				window.totalinputs++;
				if (window.totalinputs >= 30) {
					jQuery('#addUploadBoxes').toggle('slow');
					return;
				}
			}
		}
		function resetBoxes() {
			window.totalinputs = 3
			$('#uploadboxes').html('<div id="place" style="display: none;"></div>');
			addUploadBoxes('place', 'filetemplate', 3);
		}
	// ]]> -->
	</script>


<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_HOTSPOT_TEXT; ?></label>
							  <?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('hotspot_text['. $nCounter . '][' . $aLanguages[$i]['id'] . ']', (($hotspot_text[$aLanguages[$i]['id']]) ? stripslashes($hotspot_text[$aLanguages[$i]['id']]) : oos_get_hotspot_text($models['models_id'], $aLanguages[$i]['id']))); ?>
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
								<?php echo oos_draw_input_field('hotspot_pitch',  $panorama['hotspot_pitch']); ?>
                              </div>
                           </div>
                        </fieldset>
                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_HOTSPOT_YAW; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('hotspot_yaw',  $panorama['hotspot_yaw']); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
		$array = array();
		$array[] = '';
?>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_HOTSPOT_PRODUCT; ?></label>
                           <div class="col-md-10">
								<?php echo oos_draw_products_pull_down('hotspot_product_id', '', $array); ?>
                           </div>
                        </div>
                     </fieldset>

	<div id="uploadboxes">
		<div id="place" style="display: none;"></div>
		<!-- New boxes get inserted before this -->
	</div>

	<div style="display:none">
		<!-- This is the template that others are copied from -->
		<div id="filetemplate" >
                        <div class="row mb-3">
                           <div class="col-3">
<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 300px; height: 110px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

	<input type="file" size="40" name="files[]"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>

                           </div>
                           <div class="col-9">
                             
                           </div>
						</div>
		</div>
	</div>
	<p id="addUploadBoxes"><a href="javascript:addUploadBoxes('place','filetemplate',3)" title="<?php echo TEXT_NOT_RELOAD; ?>">+ <?php echo TEXT_ADD_MORE_UPLOAD; ?></a></p>



<?php
	if  ($action == 'edit_panorama') {
?>	
						<div class="row mb-3 pb-3 bb">
							<div class="col-lg-2">		
								<?php echo TEXT_PANORAMA_PREVIEW; ?>
							</div>
							
			<div class="col-lg-10">

<div id="panorama"></div>
<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "/images/bma-1.jpg",
    /*
     * Uncomment the next line to print the coordinates of mouse clicks
     * to the browser's developer console, which makes it much easier
     * to figure out where to place hot spots. Always remove it when
     * finished, though.
     */
    //"hotSpotDebug": true,
    "hotSpots": [
        {
            "pitch": 14.1,
            "yaw": 1.5,
            "type": "info",
            "text": "Baltimore Museum of Art",
            "URL": "https://artbma.org/"
        },
        {
            "pitch": -9.4,
            "yaw": 222.6,
            "type": "info",
            "text": "Art Museum Drive"
        },
        {
            "pitch": -0.9,
            "yaw": 144.4,
            "type": "info",
            "text": "North Charles Street"
        }
    ]
});
</script>

							</div>
						</div>
	
<?php
} else {
?>
            <div class="text-right mt-3">			
				<?php echo oos_submit_button(IMAGE_PREVIEW); ?>	
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
