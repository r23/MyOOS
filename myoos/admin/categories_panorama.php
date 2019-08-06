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
		case 'setflag':
			if ( isset($_GET['flag']) && ($_GET['flag'] == '1') || ($_GET['flag'] == '2') ) {
				if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
					oos_set_product_status($_GET['pID'], $_GET['flag']);				
				} elseif (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
					oos_set_categories_status($_GET['cID'], $_GET['flag']);
				}
			}

			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&pID=' . intval($_GET['pID']) . '&cID=' . intval($cID) . '&page=' . $nPage . ((isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '')));
			break;

		case 'insert_category':
		case 'update_category':
			$nStatus = oos_db_prepare_input($_POST['categories_status']);
			$color = oos_db_prepare_input($_POST['color']);
			$menu_type  = oos_db_prepare_input($_POST['menu_type']);
			$sort_order = oos_db_prepare_input($_POST['sort_order']);
			$nImageCounter = (!isset($_POST['image_counter']) || !is_numeric($_POST['image_counter'])) ? 0 : intval($_POST['image_counter']);

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
			$sql_data_array = array('color' => oos_db_prepare_input($color),
									'menu_type' => oos_db_prepare_input($menu_type),
									'sort_order' => intval($sort_order));

			if ($action == 'insert_category') {
				$insert_sql_data = array();
				$insert_sql_data = array('parent_id' => intval($current_category_id),
										'date_added' => 'now()',
										'categories_status' => intval($nStatus));

				$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

				oos_db_perform($oostable['categories'], $sql_data_array);

				$categories_id = $dbconn->Insert_ID();
			} elseif ($action == 'update_category') {
				$update_sql_data = array('last_modified' => 'now()',
										'categories_status' => intval($nStatus));

				$sql_data_array = array_merge($sql_data_array, $update_sql_data);

				oos_db_perform($oostable['categories'], $sql_data_array, 'UPDATE', 'categories_id = \'' . $categories_id . '\'');
			}

			$aLanguages = oos_get_languages();
			$nLanguages = count($aLanguages);

			for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
				$language_id = $aLanguages[$i]['id'];
				
				$categories_description = oos_db_prepare_input($_POST['categories_description'][$language_id]);		
				$categories_description_meta = oos_db_prepare_input($_POST['categories_description_meta'][$language_id]);

				if (empty($categories_description_meta)) {				
					$categories_description_meta =  substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '',$categories_description)),0 , 160);
				}

				$sql_data_array = array('categories_name' => oos_db_prepare_input($_POST['categories_name'][$language_id]),
										'categories_page_title' => oos_db_prepare_input($_POST['categories_page_title'][$language_id]),
										'categories_heading_title' => oos_db_prepare_input($_POST['categories_heading_title'][$language_id]),
										'categories_description' => $categories_description,
										'categories_description_meta' => $categories_description_meta);

				if ($action == 'insert_category') {
					$insert_sql_data = array('categories_id' => intval($categories_id),
											'categories_languages_id' => intval($aLanguages[$i]['id']));

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
					oos_db_perform($oostable['categories_description'], $sql_data_array);
				} elseif ($action == 'update_category') {
					oos_db_perform($oostable['categories_description'], $sql_data_array, 'UPDATE', 'categories_id = \'' . intval($categories_id) . '\' AND categories_languages_id = \'' . intval($language_id) . '\'');
				}
			}

			if ( ($_POST['remove_image'] == 'yes') && (isset($_POST['categories_previous_image'])) ) {
				$categories_previous_image = oos_db_prepare_input($_POST['categories_previous_image']);
				
				$categoriestable = $oostable['categories'];
				$dbconn->Execute("UPDATE $categoriestable
                            SET categories_image = NULL
                            WHERE categories_id = '" . intval($categories_id) . "'");				
				
				oos_remove_category_image($categories_previous_image);				
			}
			
			if ( ($_POST['remove_banner'] == 'yes') && (isset($_POST['categories_previous_banner'])) ) {
				$categories_previous_banner = oos_db_prepare_input($_POST['categories_previous_banner']);
				
				$categoriestable = $oostable['categories'];
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
						'max_width' => 440, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
						'max_height' => 500, // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
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

				$categoriestable = $oostable['categories'];
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

			$oCategoriesImage = new upload('categories_image', $options);

			$dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'category/';
			$oCategoriesImage->set_destination($dir_fs_catalog_images);	
			
			if ($oCategoriesImage->parse() && oos_is_not_null($oCategoriesImage->filename)) {

				$categoriestable = $oostable['categories'];
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
												'categories_image' => oos_db_prepare_input($value),
												'sort_order' => intval($sort_order));
						oos_db_perform($oostable['categories_images'], $sql_data_array);
					}
				}
			}

			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories_id));
			break;

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
if ($action == 'new_category' || $action == 'edit_category') {
	$categoriestable = $oostable['categories'];
	$query = "SELECT COUNT(*) AS total
                  FROM $categoriestable c
                  WHERE parent_id = '" . intval($current_category_id) . "'";
	$categories_count_result = $dbconn->Execute($query);
	$categories_count = $categories_count_result->fields['total'];
	$categories_count++;
	
    $parameters = array('categories_id' => '',
						'categories_name' => '',
						'categories_page_title' => '',
                       'categories_heading_title' => '',
                       'categories_description' => '',
                       'categories_description_meta' => '',
                       'categories_image' => '',
					   'categories_banner' => '',
					   'categories_larger_images' => array(),
                       'parent_id' => '',
					   'color' => '',
					   'menu_type'  => '',
                       'sort_order' => $categories_count,
                       'date_added' => '',
                       'categories_status' => 2,
                       'last_modified' => '');
	$cInfo = new objectInfo($parameters);

	if (isset($_GET['cID']) && empty($_POST)) {
        $categoriestable = $oostable['categories'];
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT c.categories_id, cd.categories_name, cd.categories_page_title, cd.categories_heading_title,
                         cd.categories_description, cd.categories_description_meta,
                         c.categories_image, c.categories_banner, c.parent_id, c.color, c.menu_type, c.sort_order,
						 c.date_added, c.categories_status, c.last_modified
                  FROM $categoriestable c,
                       $categories_descriptiontable cd
                  WHERE c.categories_id = '" . intval($cID) . "' AND
                        c.categories_id = cd.categories_id AND
                        cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                  ORDER BY c.sort_order, cd.categories_name";
        $categories_result = $dbconn->Execute($query);
        $category = $categories_result->fields;

        $cInfo = new objectInfo($category);
		
		$categories_imagestable = $oostable['categories_images'];
		$categories_images_result =  $dbconn->Execute("SELECT categories_id, categories_image, sort_order FROM $categories_imagestable WHERE categories_id = '" . intval($category['categories_id']) . "' ORDER BY sort_order");
			
		while ($categories_images = $categories_images_result->fields) {
			$cInfo->categories_larger_images[] = array('categories_id' => $categories_images['categories_id'],
														'image' => $categories_images['categories_image'],
														'sort_order' => $product_images['sort_order']);
			// Move that ADOdb pointer!
			$categories_images_result->MoveNext();
		}
	}

	$aLanguages = oos_get_languages();
	$nLanguages = count($aLanguages);

	$text_new_or_edit = ($action=='new_category') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;

	$aSetting = array();
	$settingstable = $oostable['setting'];
	$setting_result = $dbconn->Execute("SELECT setting_id, setting_name FROM $settingstable WHERE setting_languages_id = '" . intval($_SESSION['language_id']) . "'");
	while ($setting = $setting_result->fields) {
		$aSetting[] = array('id' => $setting['setting_id'],
                         'text' => $setting['setting_name']);
		// Move that ADOdb pointer!
		$setting_result->MoveNext();
	}

	$aColor = array();
	$aColor = array('text-primary', 'text-success', 'text-danger', 'text-warning', 'text-dark', 'text-muted');

	if (isset($_GET['origin'])) {
		$sOrigin = oos_db_prepare_input($_GET['origin']);
        $pos_params = strpos($sOrigin, '?', 0);
        if ($pos_params != false) {
          $back_url = substr($sOrigin, 0, $pos_params);
          $back_url_params = substr($sOrigin, $pos_params + 1);
        } else {
          $back_url = $sOrigin;
          $back_url_params = '';
        }
	} else {
        $back_url = $aContents['categories'];
		$back_url_params = 'cPath=' . $cPath;
        if (oos_is_not_null($cInfo->categories_id)) {
			$back_url_params .= '&cID=' . $cInfo->categories_id;
        }			
	}
?>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
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
	$form_action = (isset($_GET['cID'])) ? 'update_category' : 'insert_category';
	echo oos_draw_form('fileupload', 'new_category', $aContents['categories'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');
		echo oos_draw_hidden_field('parent_id', $cInfo->parent_id);
		echo oos_hide_session_id();
?>

               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo TEXT_CATEGORY; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#data" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_DATA; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#picture" aria-controls="picture" role="tab" data-toggle="tab"><?php echo TEXT_IMAGES; ?></a>
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
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('categories_name[' . $aLanguages[$i]['id'] . ']', (($categories_name[$aLanguages[$i]['id']]) ? stripslashes($categories_name[$aLanguages[$i]['id']]) : oos_get_category_name($cInfo->categories_id, $aLanguages[$i]['id'])), '', FALSE, 'text', TRUE, FALSE, TEXT_EDIT_CATEGORIES_NAME); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_PAGE_TITLE; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('categories_page_title[' . $aLanguages[$i]['id'] . ']', (($categories_page_title[$aLanguages[$i]['id']]) ? stripslashes($categories_page_title[$aLanguages[$i]['id']]) : oos_get_categories_page_title($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('categories_heading_title[' . $aLanguages[$i]['id'] . ']', (($categories_heading_title[$aLanguages[$i]['id']]) ? stripslashes($categories_heading_title[$aLanguages[$i]['id']]) : oos_get_category_heading_title($cInfo->categories_id, $aLanguages[$i]['id'])), '', FALSE, 'text', TRUE, FALSE, ''); ?>
							</div>
						</div>
					</fieldset>
<?php
		}		
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_editor_field('categories_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$aLanguages[$i]['id']]) ? stripslashes($categories_description[$aLanguages[$i]['id']]) : oos_get_category_description($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
			<script>
				CKEDITOR.replace( 'categories_description[<?php echo $aLanguages[$i]['id']; ?>]');
			</script>
<?php
		}
		for ($i=0; $i < count($aLanguages); $i++) {
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION_META; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('categories_description_meta[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (($categories_description_meta[$aLanguages[$i]['id']]) ? stripslashes($categories_description_meta[$aLanguages[$i]['id']]) : oos_get_category_description_meta($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
		}
?>
                     </div>
                     <div class="tab-pane" id="data" role="tabpanel">
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">ID:</label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('categories_id', $cInfo->categories_id, '', FALSE, 'text', TRUE, TRUE, ''); ?></div>
                           </div>
                        </fieldset>
						<fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_EDIT_STATUS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('categories_status', $aSetting, $cInfo->categories_status); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_EDIT_SORT_ORDER; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('sort_order', $cInfo->sort_order); ?></div>
                           </div>
                        </fieldset>
                        <div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php echo TEXT_EDIT_COLOR; ?></label>
							<div class="col-lg-10">
<?php
		foreach ($aColor as $v) {
?>		
								<div class="c-radio c-radio-nofont">
									<label>
										<?php
											echo '<input type="radio" name="color" value="' . $v  . '"'; 
											if ($cInfo->color == $v) echo ' checked="checked"';
											echo  '>';
									   ?>
										<?php echo '<span class="' . $v . '">' . TEXT_CATEGORY . '</span>'; ?>
									</label>
								</div>
<?php
		}
?>								
							</div>
						</div>
                        <div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php echo TEXT_EDIT_MENU_TYPE; ?></label>
							<div class="col-lg-10">
								<div class="c-radio c-radio-nofont">
									<label>
										<input type="radio" name="menu_type" value="DEFAULT" checked="checked">
										<span></span>
									</label>
								</div>	
								<div class="c-radio c-radio-nofont">
									<label>
										<?php
											echo '<input type="radio" name="menu_type" value="NEW"'; 
											if ($cInfo->menu_type == 'NEW') echo ' checked="checked"';
											echo  '>&nbsp;';
									   ?>
										<span class="badge badge-danger float-right"><?php echo TEXT_EDIT_NEW; ?></span>
									</label>
								</div>
								<div class="c-radio c-radio-nofont">
									<label>
										<?php
											echo '<input type="radio" name="menu_type" value="PROMO"'; 
											if ($cInfo->menu_type == 'PROMO') echo ' checked="checked"';
											echo  '>&nbsp;';
									   ?>
										<span class="badge badge-success float-right"><?php echo TEXT_EDIT_PROMO; ?></span>
									</label>
								</div>
								
								
								
							</div>
						</div>
						
                     </div>
                     <div class="tab-pane" id="picture" role="tabpanel">
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

		<div class="row mb-3">
			<div class="col-3">
				<strong><?php echo TEXT_INFO_PREVIEW; ?></strong>
			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_DETAILS; ?></strong>
			</div>
		</div>

		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">		

<?php
	if (oos_is_not_null($cInfo->categories_image)) {
		echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
        echo oos_info_image('category/medium/' . $cInfo->categories_image, $cInfo->categories_name);
		echo '</div></div>';

		echo oos_draw_hidden_field('categories_previous_image', $cInfo->categories_image);
		echo '<br>';
		echo oos_draw_checkbox_field('remove_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;	
	} else {	
?>

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

	<input type="file" size="40" name="categories_image"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
<?php
	}
?>	
			</div>
			<div class="col-9">
				<div class="c-radio c-radio-nofont">
					<?php echo TEXT_INFO_PRIMARY; ?>
				</div>				
			</div>
		</div>
		
		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">
<?php
	if (oos_is_not_null($cInfo->categories_banner)) {
		echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
        echo oos_info_image('banners/medium/' . $cInfo->categories_banner, $cInfo->categories_name);
		echo '</div></div>';

		echo oos_draw_hidden_field('categories_previous_banner', $cInfo->categories_banner);
		echo '<br>';
		echo oos_draw_checkbox_field('remove_banner', 'yes') . ' ' . TEXT_IMAGE_REMOVE;	
	} else {
?>	
<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>

	<input type="file" size="40" name="categories_banner"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
<?php
	}
?>
			</div>
			<div class="col-9">
				<?php echo TEXT_INFO_BANNER; ?>
			</div>
		</div>		
<?php
	if (is_array($cInfo->categories_larger_images) || is_object($cInfo->categories_larger_images)) {
	    $nCounter = 0;
		foreach ($cInfo->categories_larger_images as $image) {
			$nCounter++;
?>

		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

<?php	
		echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
		echo oos_info_image('category/medium/' .  $image['image'], $cInfo->categories_name);
	    echo '</div></div>';
			
		echo oos_draw_hidden_field('categories_previous_large_image['. $nCounter . ']', $image['image']);
		echo '<br>';
		echo oos_draw_checkbox_field('remove_category_image['. $nCounter . ']', 'yes') . ' ' . TEXT_IMAGE_REMOVE;	
?>
			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_SLIDER; ?></strong>
			</div>	
		</div>
<?php
		}
		echo oos_draw_hidden_field('image_counter', $nCounter);
	}
?>	
		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

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
				<strong><?php echo TEXT_INFO_SLIDER; ?></strong>
			</div>
		</div>

		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

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
				<strong><?php echo TEXT_INFO_SLIDER; ?></strong>
			</div>
		</div>



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
                              <strong><?php echo TEXT_INFO_SLIDER; ?></strong>
                           </div>
						</div>
		</div>
	</div>
	<p id="addUploadBoxes"><a href="javascript:addUploadBoxes('place','filetemplate',3)" title="<?php echo TEXT_NOT_RELOAD; ?>">+ <?php echo TEXT_ADD_MORE_UPLOAD; ?></a></p>


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
} else {
	$image_icon_status_array = array();
	$image_icon_status_array = array(array('id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE));
	$image_icon_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM " . $oostable['products_status'] . " WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
	while ($image_icon_status = $image_icon_status_result->fields) {
		$image_icon_status_array[] = array('id' => $image_icon_status['products_status_id'],
											'text' => $image_icon_status['products_status_name']);

		// Move that ADOdb pointer!
		$image_icon_status_result->MoveNext();
    }
?>

	<!-- Breadcrumbs //-->
	<div class="content-heading">
		<div class="col-lg-12">
			<h2><?php echo HEADING_TITLE; ?></h2>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP; ?></a>
				</li>
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG; ?></a>
				</li>
				<li class="breadcrumb-item active">
					<strong><?php echo HEADING_TITLE; ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<!-- END Breadcrumbs //-->

		<div class="wrapper wrapper-content">

<?php
	if (empty($action)) {
?>
		<div class="col-lg-12">
			<div class="float-right">
<?php
	echo ((isset($aPath) && count($aPath) > 1) ? '<a href="' . oos_href_link_admin($aContents['categories'], $cPath_back . 'cID=' . $current_category_id) . '">' . oos_button('<i class="fa fa-chevron-left"></i> ' . IMAGE_BACK) . '</a> ' : '') .
	'<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&action=new_category') . '">' . oos_button('<i class="fa fa-plus"></i> ' . IMAGE_NEW_CATEGORY) . '</a> ' .
	'<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&action=new_product') . '">' . oos_button('<i class="fa fa-plus"></i> ' . IMAGE_NEW_PRODUCT) . '</a>'; ?>
			</div>
		</div>
<?php
	}
?>

			<div class="row">
				<div class="col-sm-12">
					<?php echo oos_draw_form('id', 'search', $aContents['categories'], '', 'get', FALSE, 'class="form-inline"'); ?>
						<div id="DataTables_Table_0_filter" class="dataTables_filter">
							<label><?php echo HEADING_TITLE_SEARCH; ?></label>
							<?php echo oos_draw_input_field('search', $_GET['search']); ?>
						</div>
					</form>
					<?php echo oos_draw_form('id', 'goto', $aContents['categories'], '', 'get', FALSE, 'class="form-inline"'); ?>
						<div class="dataTables_filter">
							<label><?php echo HEADING_TITLE_GOTO; ?></label>
							<?php echo oos_draw_pull_down_menu('cPath', oos_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?>
						</div>
					</form>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">

<!-- body_text //-->
<div class="table-responsive">
	<table class="table w-100">
          <tr>
            <td valign="top">
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
							<th><?php echo TABLE_HEADING_MANUFACTURERS; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>
					</thead>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.categories_status != 0 AND c.categories_id = cd.categories_id AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' AND cd.categories_name like '%" . oos_db_input($_GET['search']) . "%' ORDER BY c.sort_order, cd.categories_name");
    } else {
      $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.categories_status != 0 AND c.parent_id = '" . intval($current_category_id) . "' AND c.categories_id = cd.categories_id AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY c.sort_order, cd.categories_name");
    }
    while ($categories = $categories_result->fields) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($_GET['search'])) $cPath = $categories['parent_id'];

      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => oos_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => oos_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-folder"></i></button></a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="text-center">&nbsp;</td>
                 <td class="text-center">
 <?php
		if ($categories['categories_status'] == 2) {
			echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-o text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
		} else {
			echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=2&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-o text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
		}
?></td>
                <td class="text-center">&nbsp;<?php echo $categories['sort_order']; ?>&nbsp;</td>
                <td class="text-right"><?php echo
					'<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=edit_category') . '"><i class="fa fa-pencil" title="' . BUTTON_EDIT . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=delete_category') . '"><i class="fa fa-trash" title="' . BUTTON_DELETE . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=move_category') . '"><i class="fa fa-share" title="' .  IMAGE_MOVE  . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id'] . '&action=move_category') . '"><i class="fa fa-street-view" title="' .  IMAGE_MOVE  . '"></i></a>
					';
?>				&nbsp;</td>
              </tr>
<?php
		// Move that ADOdb pointer!
		$categories_result->MoveNext();
    }
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br />' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=delete_category_confirm&cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=move_category_confirm', 'post', FALSE) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button(IMAGE_MOVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => oos_draw_form('id', 'copy_to', $aContents['categories'], 'action=copy_to_confirm&cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES . '<br />' . oos_draw_pull_down_menu('categories_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . oos_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . oos_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('text' => '<br />' . oos_image(OOS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES_ONLY);
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button(IMAGE_COPY) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . oos_button(BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . oos_button(IMAGE_MOVE) . '</a>');
            $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cID) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            if (oos_is_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br />' . oos_info_image('category/medium/' . $cInfo->categories_image, $cInfo->categories_name) . '<br />' . $cInfo->categories_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          }
        } else { // create category/product info
		  $parent_categories_name = oos_output_generated_category_path($current_category_id);
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
        }
        break;
    }

    if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
?>
	<td class="w-25">
		<table class="table table-striped">
<?php
		$box = new box;
		echo $box->infoBox($heading, $contents);
?>
		</table>
	</td>
<?php
  }
?>
          </tr>
        </table>
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
