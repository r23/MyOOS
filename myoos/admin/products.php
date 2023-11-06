<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.146 2003/07/11 14:40:27 hpdl
         categories.php,v 1.138 2002/11/18 21:38:22 dgw_
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
require 'includes/classes/class_upload.php';
require 'includes/classes/class_currencies.php';

$currencies = new currencies();

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : $current_category_id);
$pID = filter_input(INPUT_GET, 'pID', FILTER_VALIDATE_INT) ?: 0;
$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

switch ($action) {
    case 'insert_product':
    case 'update_product':

        $_POST['products_price'] = str_replace(',', '.', (string) $_POST['products_price']);
        $_POST['products_price_list'] = str_replace(',', '.', (string) $_POST['products_price_list']);
        $_POST['products_discount1'] = str_replace(',', '.', (string) $_POST['products_discount1']);
        $_POST['products_discount2'] = str_replace(',', '.', (string) $_POST['products_discount2']);
        $_POST['products_discount3'] = str_replace(',', '.', (string) $_POST['products_discount3']);
        $_POST['products_discount4'] = str_replace(',', '.', (string) $_POST['products_discount4']);

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
        $nImageCounter = (!isset($_POST['image_counter']) || !is_numeric($_POST['image_counter'])) ? 0 : intval($_POST['image_counter']);

        $nProductsQuantity = isset($_POST['products_quantity']) ? intval($_POST['products_quantity']) : 1;
        $nProductsStatus = isset($_POST['products_status']) ? intval($_POST['products_status']) : 1;
        $nProductsReorderLevel = isset($_POST['products_reorder_level']) ? intval($_POST['products_reorder_level']) : 5;

        $nProductsReplacementProductID = isset($_POST['products_replacement_product_id']) ? intval($_POST['products_replacement_product_id']) : '';
        if (isset($_POST['products_replacement_product_id']) && is_numeric($_POST['products_replacement_product_id']) && ($_POST['products_replacement_product_id'] > 0)) {
            $messageStack->add_session(ERROR_REPLACEMENT, 'error');
            $nProductsStatus = 4;
        }

        if (STOCK_CHECK == 'true') {
            if ($nProductsQuantity <= 0) {
                $messageStack->add_session(ERROR_OUTOFSTOCK, 'error');
                $nProductsStatus = 0;
            }
        }


        $products_id = filter_input(INPUT_GET, 'pID', FILTER_VALIDATE_INT);
        $products_date_available = isset($_POST['products_date_available']) ? oos_db_prepare_input($_POST['products_date_available']) : '';

        if (isset($_POST['products_base_price'])) {
            $products_base_price = oos_db_prepare_input($_POST['products_base_price']);
            $products_product_quantity = oos_db_prepare_input($_POST['products_product_quantity']);
            $products_base_quantity = oos_db_prepare_input($_POST['products_base_quantity']);
        } else {
            $products_base_price = 1.0;
            $products_product_quantity = 1;
            $products_base_quantity = 1;
        }


        if ((date('Y-m-d') < $products_date_available) && ($nProductsStatus == 3)) {
            $nProductsStatus = 2;
        }

        $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

        $sql_data_array = ['products_quantity' => intval($nProductsQuantity), 'products_reorder_level' => intval($nProductsReorderLevel), 'products_model' => oos_db_prepare_input($_POST['products_model']), 'products_replacement_product_id' => intval($nProductsReplacementProductID), 'products_ean' => oos_db_prepare_input($_POST['products_ean']), 'products_price' => oos_db_prepare_input($_POST['products_price']), 'products_base_price' => $products_base_price, 'products_product_quantity' => $products_product_quantity, 'products_base_quantity' => $products_base_quantity, 'products_base_unit' => $products_base_unit, 'products_date_available' => $products_date_available, 'products_weight' => oos_db_prepare_input($_POST['products_weight']), 'products_status' => $nProductsStatus, 'products_setting' => oos_db_prepare_input($_POST['products_setting']), 'products_tax_class_id' => oos_db_prepare_input($_POST['products_tax_class_id']), 'products_units_id' => (isset($_POST['products_units_id']) ? intval($_POST['products_units_id']) : 0), 'products_old_electrical_equipment' => (isset($_POST['products_old_electrical_equipment']) ? 1 : 0), 'products_used_goods' => (isset($_POST['products_used_goods']) ? 1 : 0), 'manufacturers_id' => oos_db_prepare_input($_POST['manufacturers_id']), 'products_price_list' => oos_db_prepare_input($_POST['products_price_list']), 'products_quantity_order_min' => oos_db_prepare_input($_POST['products_quantity_order_min']), 'products_quantity_order_units' => oos_db_prepare_input($_POST['products_quantity_order_units']), 'products_quantity_order_max' => oos_db_prepare_input($_POST['products_quantity_order_max']), 'products_discount1' => oos_db_prepare_input($_POST['products_discount1']), 'products_discount1_qty' => oos_db_prepare_input($_POST['products_discount1_qty']), 'products_discount2' => oos_db_prepare_input($_POST['products_discount2']), 'products_discount2_qty' => oos_db_prepare_input($_POST['products_discount2_qty']), 'products_discount3' => oos_db_prepare_input($_POST['products_discount3']), 'products_discount3_qty' => oos_db_prepare_input($_POST['products_discount3_qty']), 'products_discount4' => oos_db_prepare_input($_POST['products_discount4']), 'products_discount4_qty' => oos_db_prepare_input($_POST['products_discount4_qty'])];

        if ($action == 'insert_product') {
            $insert_sql_data = ['products_date_added' => 'now()'];

            $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

            oos_db_perform($oostable['products'], $sql_data_array);
            $products_id = $dbconn->Insert_ID();

            $products_to_categoriestable = $oostable['products_to_categories'];
            $dbconn->Execute("INSERT INTO $products_to_categoriestable (products_id, categories_id) VALUES ('" . intval($products_id) . "', '" . intval($current_category_id) . "')");

            // product price history
            $sql_price_array = ['products_id' => $products_id, 'products_price' => oos_db_prepare_input($_POST['products_price']), 'date_added' => 'now()'];
            oos_db_perform($oostable['products_price_history'], $sql_price_array);
        } elseif ($action == 'update_product') {
            // product price history
            $productstable = $oostable['products'];
            $products_price_sql = "SELECT products_price
                        FROM $productstable 
                        WHERE products_id = '" . intval($products_id) . "'";
            $products_price_result = $dbconn->Execute($products_price_sql);
            $products_price = $products_price_result->fields;
            $old_products_price = $products_price['products_price'];
            $new_products_price = oos_db_prepare_input($_POST['products_price']);

            $epsilon = 0.00001;

            # https://www.php.net/manual/en/language.types.float.php#language.types.float.casting
            if (abs($old_products_price - $new_products_price) > $epsilon) {
                $sql_price_array = ['products_id' => intval($products_id), 'products_price' => oos_db_prepare_input($_POST['products_price']), 'date_added' => 'now()'];
                oos_db_perform($oostable['products_price_history'], $sql_price_array);
            }


            $update_sql_data = ['products_last_modified' => 'now()'];

            $sql_data_array = [...$sql_data_array, ...$update_sql_data];

            oos_db_perform($oostable['products'], $sql_data_array, 'UPDATE', 'products_id = \'' . intval($products_id) . '\'');
        }


        $aLanguages = oos_get_languages();
        $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

        for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
            $language_id = $aLanguages[$i]['id'];


            $products_description = isset($_POST['products_description_' . $language_id]) ? oos_db_prepare_input($_POST['products_description_' . $language_id]) : '';
            $products_description_meta  = isset($_POST['products_description_meta_' . $language_id]) ? oos_db_prepare_input($_POST['products_description_meta_' . $language_id]) : '';


            if (empty($products_description_meta)) {
                $products_description_meta =  substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '', (string) $products_description)), 0, 250);
            }

            $products_facebook_title = oos_db_prepare_input($_POST['products_facebook_title'][$language_id]);
            $products_facebook_description = oos_db_prepare_input($_POST['products_facebook_description'][$language_id]);
            $products_twitter_title = oos_db_prepare_input($_POST['products_twitter_title'][$language_id]);
            $products_twitter_description = oos_db_prepare_input($_POST['products_twitter_description'][$language_id]);

            if (empty($products_facebook_title)) {
                $products_facebook_title = oos_db_prepare_input($_POST['products_name'][$language_id]);
            }
            if (empty($products_facebook_description)) {
                $products_facebook_description = $products_description_meta;
            }

            if (empty($products_twitter_title)) {
                $products_twitter_title = $products_facebook_title;
            }
            if (empty($products_twitter_description)) {
                $products_twitter_description = $products_facebook_description;
            }

            $products_short_description = isset($_POST['products_short_description_' . $language_id]) ? oos_db_prepare_input($_POST['products_short_description_' . $language_id]) : '';
            $products_essential_characteristics  = isset($_POST['products_essential_characteristics_' . $language_id]) ? oos_db_prepare_input($_POST['products_essential_characteristics_' . $language_id]) : '';
            $products_old_electrical_equipment_description = isset($_POST['products_old_electrical_equipment_description_' . $language_id]) ? oos_db_prepare_input($_POST['products_old_electrical_equipment_description_' . $language_id]) : '';
            $products_used_goods_description = isset($_POST['products_used_goods_description_' . $language_id]) ? oos_db_prepare_input($_POST['products_used_goods_description_' . $language_id]) : '';



            $sql_data_array = ['products_name' => oos_db_prepare_input($_POST['products_name'][$language_id]), 'products_title' => oos_db_prepare_input($_POST['products_title'][$language_id]), 'products_description' => $products_description, 'products_short_description' => $products_short_description, 'products_essential_characteristics' => $products_essential_characteristics, 'products_old_electrical_equipment_description' => $products_old_electrical_equipment_description, 'products_used_goods_description' => $products_used_goods_description, 'products_description_meta' => $products_description_meta, 'products_facebook_title' => $products_facebook_title, 'products_facebook_description' => $products_facebook_description, 'products_twitter_title' => $products_twitter_title, 'products_twitter_description' => $products_twitter_description, 'products_url' => oos_db_prepare_input($_POST['products_url'][$language_id])];

            if ($action == 'insert_product') {
                $insert_sql_data = ['products_id' => $products_id, 'products_languages_id' => $language_id];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['products_description'], $sql_data_array);
            } elseif ($action == 'update_product') {
                oos_db_perform($oostable['products_description'], $sql_data_array, 'UPDATE', 'products_id = \'' . intval($products_id) . '\' AND products_languages_id = \'' . intval($language_id) . '\'');
            }
        }


        if ((isset($_POST['remove_image']) && ($_POST['remove_image'] == 'yes')) && (isset($_POST['products_previous_image']))) {
            $products_previous_image = oos_db_prepare_input($_POST['products_previous_image']);

            $productsstable = $oostable['products'];
            $dbconn->Execute("UPDATE $productsstable
                                 SET products_image = NULL
                                 WHERE products_id = '" . intval($products_id) . "'");

            oos_remove_product_image($products_previous_image);
        }

        for ($i = 1, $n = $nImageCounter + 1; $i < $n; $i++) {
            if ((isset($_POST['remove_products_image']) && ($_POST['remove_products_image'][$i] == 'yes')) && (isset($_POST['products_previous_large_image'][$i]))) {
                $products_previous_large_image = oos_db_prepare_input($_POST['products_previous_large_image'][$i]);

                $dbconn->Execute("DELETE FROM " . $oostable['products_gallery'] . " WHERE image_name = '" . oos_db_input($products_previous_large_image) . "'");

                oos_remove_category_image($products_previous_large_image);
            }
        }


        $options = ['image_versions' => [
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
                'max_width' => 1200,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 1200,
            ],
            'medium_large' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 600,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 600,
            ],
            'medium' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 420,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 455,
            ],
            'small' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 150,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 150,
            ],
            'min' => [
                // 'auto_orient' => TRUE,
                // 'crop' => TRUE,
                // 'jpeg_quality' => 82,
                // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                'max_width' => 64,
                // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                'max_height' => 64,
            ],
        ]];

        $oProductImage = new upload('products_image', $options);

        $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/';
        $oProductImage->set_destination($dir_fs_catalog_images);

        if ($oProductImage->parse() && oos_is_not_null($oProductImage->filename)) {
            $productstable = $oostable['products'];
            $dbconn->Execute("UPDATE $productstable
                            SET products_image = '" . oos_db_input($oProductImage->filename) . "'
                            WHERE products_id = '" . intval($products_id) . "'");
        }

        if (isset($_FILES['files'])) {
            $oImage = new upload('files', $options);

            $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/';
            $oImage->set_destination($dir_fs_catalog_images);
            $oImage->parse();

            if (oos_is_not_null($oImage->response)) {
                $sort_order = 0 + $nImageCounter;
                foreach ($oImage->response as $index => $value) {
                    $sort_order++;
                    $sql_data_array = ['products_id' => intval($products_id), 'image_name' => oos_db_prepare_input($value), 'sort_order' => intval($sort_order)];
                    oos_db_perform($oostable['products_gallery'], $sql_data_array);
                }
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products_id));
        break;
}

// check if the catalog image directory exists
if (is_dir(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
    if (!is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
        $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
    }
} else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
}

require 'includes/header.php';
?>
<!-- body //-->
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

				<div class="row">
					<div class="col-lg-12">
<?php
if ($action == 'new_product' || $action == 'edit_product') {
    defined('DEFAULT_SETTING_ID') or define('DEFAULT_SETTING_ID', '2');
    defined('DEFAULT_PRODUTS_STATUS_ID') or define('DEFAULT_PRODUTS_STATUS_ID', '3');
    defined('DEFAULT_TAX_CLASS_ID') or define('DEFAULT_TAX_CLASS_ID', '1');


    $parameters = ['products_id' => '', 'products_name' => '', 'products_title' => '', 'products_description' => '', 'products_short_description' => '', 'products_essential_characteristics' => '', 'products_url' => '', 'products_quantity' => '', 'products_model' => '', 'products_image' => '', 'products_larger_images' => [], 'products_price' => 0.0, 'products_base_price' => 1.0, 'products_base_unit' => 0, 'products_product_quantity' => 1.0, 'products_base_quantity' => 1.0, 'products_weight' => '', 'products_date_added' => '', 'products_last_modified' => '', 'products_date_available' => '', 'products_setting' => DEFAULT_SETTING_ID, 'products_status' => DEFAULT_PRODUTS_STATUS_ID, 'products_tax_class_id' => DEFAULT_TAX_CLASS_ID, 'products_units_id' => 0, 'products_old_electrical_equipment' => 0, 'products_used_goods' => 0, 'manufacturers_id' => ''];

    $pInfo = new objectInfo($parameters);

    if (isset($_GET['pID']) && empty($_POST)) {
        $pID = intval($_GET['pID']);

        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $product_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, pd.products_title, pd.products_description, pd.products_short_description,
												pd.products_essential_characteristics, pd.products_url, pd.products_description_meta, pd.products_facebook_title,
												pd.products_facebook_description, pd.products_twitter_title, pd.products_twitter_description,
                                                 p.products_quantity, p.products_reorder_level, p.products_model,
                                                 p.products_replacement_product_id, p.products_ean, p.products_image,
                                                 p.products_price, p.products_base_price, p.products_base_quantity,
                                                 p.products_product_quantity, p.products_base_unit,
                                                 p.products_weight, p.products_date_added, p.products_last_modified,
                                                 date_format(p.products_date_available, '%Y-%m-%d') AS products_date_available,
                                                 p.products_status, p.products_setting, p.products_tax_class_id, p.products_units_id,
												 p.products_old_electrical_equipment, p.products_used_goods, p.manufacturers_id, p.products_price_list,
                                                 p.products_quantity_order_min, p.products_quantity_order_units, p.products_quantity_order_max,
                                                 p.products_discount1, p.products_discount2, p.products_discount3,
                                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty,
                                                 p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order
                                            FROM $productstable p,
                                                 $products_descriptiontable pd
                                           WHERE p.products_id = '" . intval($pID) . "' AND
                                                 p.products_id = pd.products_id AND
                                                 pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'");
        $product = $product_result->fields;

        $pInfo = new objectInfo($product);

        $product_gallerytable = $oostable['products_gallery'];
        $product_gallery_result =  $dbconn->Execute("SELECT products_id, image_name, sort_order FROM $product_gallerytable WHERE products_id = '" . intval($product['products_id']) . "' ORDER BY sort_order");

        while ($product_images = $product_gallery_result->fields) {
            $pInfo->products_larger_images[] = ['products_id' => $product_images['products_id'],
                                                    'image' => $product_images['image_name'],
                                                    'sort_order' => $product_images['sort_order']];
            // Move that ADOdb pointer!
            $product_gallery_result->MoveNext();
        }
    }

    $manufacturers_array = [];
    $manufacturers_array = [['id' => '', 'text' => TEXT_NONE]];
    $manufacturerstable = $oostable['manufacturers'];
    $manufacturers_result = $dbconn->Execute("SELECT manufacturers_id, manufacturers_name FROM $manufacturerstable ORDER BY manufacturers_name");
    while ($manufacturers = $manufacturers_result->fields) {
        $manufacturers_array[] = ['id' => $manufacturers['manufacturers_id'],
                                  'text' => $manufacturers['manufacturers_name']];

        // Move that ADOdb pointer!
        $manufacturers_result->MoveNext();
    }

    $tax_class_array = [];
    $tax_class_array = [['id' => '0', 'text' => TEXT_NONE]];
    $tax_classtable = $oostable['tax_class'];
    $tax_class_result = $dbconn->Execute("SELECT tax_class_id, tax_class_title FROM $tax_classtable ORDER BY tax_class_title");
    while ($tax_class = $tax_class_result->fields) {
        $tax_class_array[] = ['id' => $tax_class['tax_class_id'],
                              'text' => $tax_class['tax_class_title']];

        // Move that ADOdb pointer!
        $tax_class_result->MoveNext();
    }


    $products_units_array = [];
    $unit_of_measure = [];
    $products_units_array = [['id' => '0', 'text' => TEXT_NONE]];
    $products_unitstable = $oostable['products_units'];
    $products_units_result = $dbconn->Execute("SELECT products_units_id, products_unit_name, unit_of_measure FROM $products_unitstable WHERE languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_unit_name");
    while ($products_units = $products_units_result->fields) {
        $products_units_array[] = ['id' => $products_units['products_units_id'],
                                   'text' => $products_units['products_unit_name']];
        if ((!empty($products_units['unit_of_measure'])) && (!in_array($products_units['unit_of_measure'], $unit_of_measure))) {
            $unit_of_measure[] = $products_units['unit_of_measure'];
        }

        // Move that ADOdb pointer!
        $products_units_result->MoveNext();
    }



    $products_status_array = [];
    $products_status_array = [['id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE]];
    $products_statustable = $oostable['products_status'];
    $products_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM $products_statustable WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
    while ($products_status = $products_status_result->fields) {
        $products_status_array[] = ['id' => $products_status['products_status_id'],
                                    'text' => $products_status['products_status_name']];

        // Move that ADOdb pointer!
        $products_status_result->MoveNext();
    }

    $aLanguages = oos_get_languages();
    $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

    $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';

    $aSetting = [];
    $settingstable = $oostable['setting'];
    $setting_result = $dbconn->Execute("SELECT setting_id, setting_name FROM $settingstable WHERE setting_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY setting_id");
    while ($setting = $setting_result->fields) {
        $aSetting[] = ['id' => $setting['setting_id'],
                       'text' => $setting['setting_name']];
        // Move that ADOdb pointer!
        $setting_result->MoveNext();
    }


    if (isset($_GET['origin'])) {
        $sOrigin = oos_db_prepare_input($_GET['origin']);
        $pos_params = strpos((string) $sOrigin, '?', 0);
        if ($pos_params != false) {
            $back_url = substr((string) $sOrigin, 0, $pos_params);
            $back_url_params = substr((string) $sOrigin, $pos_params + 1);
        } else {
            $back_url = $sOrigin;
            $back_url_params = '';
        }
    } else {
        $back_url = $aContents['categories'];
        $back_url_params = 'cPath=' . $cPath;
        if (oos_is_not_null($pInfo->products_id)) {
            $back_url_params .= '&pID=' . $pInfo->products_id;
        }
    } ?>
<script nonce="<?php echo NONCE; ?>" src="js/tinymce/tinymce.min.js"></script>
<script nonce="<?php echo NONCE; ?>">
    var $j = jQuery.noConflict();
  </script>
<script nonce="<?php echo NONCE; ?>">
let tax_rates = new Array();
<?php
    $n = is_countable($tax_class_array) ? count($tax_class_array) : 0;
    for ($i = 0, $n; $i < $n; $i++) {
        if ($tax_class_array[$i]['id'] > 0) {
            echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . oos_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
        }
    } ?>

function doRound(x, places) {
  num = Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
  return num.toFixed(places);    
}

function getTaxRate() {
  let selected_value = document.forms["new_product"].products_tax_class_id.selectedIndex;
  let parameterVal = document.forms["new_product"].products_tax_class_id[selected_value].value;

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateWithTax() {
  let taxRate = getTaxRate();
  let grossValue = document.forms["new_product"].products_price.value;
  let grossListValue = document.forms["new_product"].products_price_list.value;
  let grossDiscount1Value = document.forms["new_product"].products_discount1.value;
  let grossDiscount2Value = document.forms["new_product"].products_discount2.value;
  let grossDiscount3Value = document.forms["new_product"].products_discount3.value;
  let grossDiscount4Value = document.forms["new_product"].products_discount4.value;
  
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
	grossListValue = grossListValue * ((taxRate / 100) + 1);
	grossDiscount1Value = grossDiscount1Value * ((taxRate / 100) + 1)
	grossDiscount2Value = grossDiscount2Value * ((taxRate / 100) + 1)
	grossDiscount3Value = grossDiscount3Value * ((taxRate / 100) + 1)
	grossDiscount4Value = grossDiscount4Value * ((taxRate / 100) + 1)	
  }

  document.forms["new_product"].products_price_gross.value = doRound(grossValue, 2);
  document.forms["new_product"].products_price_list_gross.value = doRound(grossListValue, 2);
  document.forms["new_product"].products_discount_gross1.value = doRound(grossDiscount1Value, 2); 
  document.forms["new_product"].products_discount_gross2.value = doRound(grossDiscount2Value, 2);
  document.forms["new_product"].products_discount_gross3.value = doRound(grossDiscount3Value, 2); 
  document.forms["new_product"].products_discount_gross4.value = doRound(grossDiscount4Value, 2); 
}

function updateNet() {
  let taxRate = getTaxRate();
  let netValue = document.forms["new_product"].products_price_gross.value;
  let netListValue = document.forms["new_product"].products_price_list_gross.value;
  let netDiscount1Value = document.forms["new_product"].products_discount_gross1.value;
  let netDiscount2Value = document.forms["new_product"].products_discount_gross2.value;
  let netDiscount3Value = document.forms["new_product"].products_discount_gross3.value; 
  let netDiscount4Value = document.forms["new_product"].products_discount_gross4.value; 
  
  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
	netListValue = netListValue / ((taxRate / 100) + 1);
	netDiscount1Value = netDiscount1Value / ((taxRate / 100) + 1);
	netDiscount2Value = netDiscount2Value / ((taxRate / 100) + 1);
	netDiscount3Value = netDiscount3Value / ((taxRate / 100) + 1);
	netDiscount4Value = netDiscount4Value / ((taxRate / 100) + 1);	
  }

  document.forms["new_product"].products_price.value = doRound(netValue, 2);
  document.forms["new_product"].products_price_list.value = doRound(netListValue, 2);
  document.forms["new_product"].products_discount1.value = doRound(netDiscount1Value, 2);
  document.forms["new_product"].products_discount2.value = doRound(netDiscount2Value, 2);
  document.forms["new_product"].products_discount3.value = doRound(netDiscount3Value, 2);
  document.forms["new_product"].products_discount4.value = doRound(netDiscount4Value, 2);  
}

function calcBasePriceFactor() {
  let pqty = document.forms["new_product"].products_product_quantity.value;
  let bqty = document.forms["new_product"].products_base_quantity.value;

  if ((pqty != 0) || (bqty != 0)) {
     document.forms["new_product"].products_base_price.value = doRound(bqty / pqty, 6);
  } else {
     document.forms["new_product"].products_base_price.value = 1.000000;
  }

}
</script>
	<!-- Breadcrumbs //-->
<?php
    if ($action == 'new_product') {
        $sTitle = sprintf(TEXT_NEW_PRODUCT, oos_output_generated_category_path($current_category_id));
    } else {
        $sTitle = sprintf(TEXT_EDIT_PRODUCT, oos_output_generated_category_path($current_category_id));
    } ?>	
	<div class="content-heading">
		<div class="col-lg-12">
			<h2><?php echo $sTitle; ?></h2>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
				</li>
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
				</li>
				<li class="breadcrumb-item active">

					<strong><?php echo $sTitle; ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<!-- END Breadcrumbs //-->

	<?php

    echo oos_draw_form('id', 'new_product', $aContents['products'], 'cPath=' . oos_prepare_input($cPath) . (!empty($pID) ? '&pID=' . intval($pID) : '') . '&action=' . $form_action, 'post', false, 'enctype="multipart/form-data"'); ?>
		<?php echo oos_draw_hidden_field('products_date_added', ($pInfo->products_date_added ?: date('Y-m-d')));
    echo oos_hide_session_id(); ?>
               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified" id="myTab">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo TEXT_PRODUCTS; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#data" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_PRODUCTS_DATA; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#obligation" aria-controls="obligation" role="tab" data-toggle="tab"><?php echo TEXT_PRODUCTS_INFORMATION_OBLIGATIONS; ?></a>
                     </li>					 
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#social" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_SOCIAL; ?></a>
                     </li>					 
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#picture" aria-controls="picture" role="tab" data-toggle="tab"><?php echo TEXT_PRODUCTS_IMAGE; ?></a>
                     </li>
                  </ul>
                  <div class="tab-content">
					<div class="text-right mt-3 mb-3">
						<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
						<?php echo oos_reset_button(BUTTON_RESET); ?>
						<?php echo oos_submit_button(BUTTON_SAVE); ?>						
					</div>			  
                     <div class="tab-pane active" id="edit" role="tabpanel">

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo ENTRY_STATUS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('products_setting', '', $aSetting, $pInfo->products_setting); ?></div>
                           </div>
                        </fieldset>
<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_NAME;
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('products_name[' . $aLanguages[$i]['id'] . ']', (isset($products_name[$aLanguages[$i]['id']]) ? stripslashes((string) $products_name[$aLanguages[$i]['id']]) : oos_get_products_name($pInfo->products_id))); ?>
                              </div>
                           </div>
                        </fieldset>						
<?php
    }
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_TITLE;
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('products_title[' . $aLanguages[$i]['id'] . ']', (isset($products_title[$aLanguages[$i]['id']]) ? stripslashes((string) $products_title[$aLanguages[$i]['id']]) : oos_get_products_title($pInfo->products_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>						
<?php
    } ?>


                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br><small>(YYYY-MM-DD)</small></label>
                              <div class="col-lg-10">
								<div class="input-group date" id="datetimepicker1">
									<input class="form-control" type="text" name="products_date_available" value="<?php echo $pInfo->products_date_available; ?>">
									<span class="input-group-addon">
									<span class="fa fa-calendar"></span>
								</span>
								</div>
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('manufacturers_id', '', $manufacturers_array, $pInfo->manufacturers_id); ?></div>
                           </div>
                        </fieldset>

<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_DESCRIPTION;
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9">
<?php
       echo oos_draw_textarea_field('description' . $aLanguages[$i]['id'], 'products_description_' . $aLanguages[$i]['id'], 'soft', '70', '15', (isset($_POST['products_description_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_description_' .$aLanguages[$i]['id']]) : oos_get_products_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
			<script nonce="<?php echo NONCE; ?>">
				tinymce.init({
						selector: 'textarea#description<?php echo $aLanguages[$i]['id']; ?>',
						language: '<?php echo LANG; ?>',
						promotion: false
				});
			</script>

<?php
    }
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_SHORT_DESCRIPTION;
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9">
<?php
       echo oos_draw_textarea_field('short'. $aLanguages[$i]['id'], 'products_short_description_' . $aLanguages[$i]['id'], 'soft', '70', '15', (isset($_POST['products_short_description_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_short_description_' .$aLanguages[$i]['id']]) : oos_get_products_short_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
			<script nonce="<?php echo NONCE; ?>">
				tinymce.init({
						selector: 'textarea#short<?php echo $aLanguages[$i]['id']; ?>',
						language: '<?php echo LANG; ?>',
						promotion: false
				});
			</script>

<?php
    }
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_ESSENTIAL_CHARACTERISTICS;
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9">
<?php
       echo oos_draw_textarea_field('essential'. $aLanguages[$i]['id'], 'products_essential_characteristics_' . $aLanguages[$i]['id'], 'soft', '70', '15', (isset($_POST['products_essential_characteristics_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_essential_characteristics_' .$aLanguages[$i]['id']]) : oos_get_products_essential_characteristicsn($pInfo->products_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
			<script nonce="<?php echo NONCE; ?>">
				tinymce.init({
						selector: 'textarea#essential<?php echo $aLanguages[$i]['id']; ?>',
						language: '<?php echo LANG; ?>',
						promotion: false
				});
			</script>

<?php
    }
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_PRODUCTS_DESCRIPTION_META;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('', 'products_description_meta_' . $aLanguages[$i]['id'], 'soft', '70', '4', (isset($_POST['products_description_meta_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_description_meta_' .$aLanguages[$i]['id']]) : oos_get_products_description_meta($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
    } ?>


<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                  echo TEXT_PRODUCTS_URL . '<br><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>';
                              } ?></label>
							  <?php if ($nLanguages > 1) {
							      echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							  } ?>
                              <div class="col-lg-9"><?php echo oos_draw_input_field('products_url[' . $aLanguages[$i]['id'] . ']', (isset($products_url[$aLanguages[$i]['iso_639_2']]) ? stripslashes((string) $products_url[$aLanguages[$i]['id']]) : oos_get_products_url($pInfo->products_id, $aLanguages[$i]['id']))); ?></div>
                           </div>
                        </fieldset>
<?php
    } ?>
                     </div>
				 
                     <div class="tab-pane" id="data" role="tabpanel">
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRICE; ?></label>
                              <div class="col-lg-10">
                                <?php
    $sPrice = number_format($pInfo->products_price, TAX_DECIMAL_PLACES, '.', '');
    echo oos_draw_input_field('products_price', $sPrice, 'onkeyup="updateWithTax()"'); ?>								
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRICE_WITH_TAX; ?></label>
                              <div class="col-lg-10">
                                <?php
    $sPrice = number_format($pInfo->products_price, TAX_DECIMAL_PLACES, '.', '');
    echo oos_draw_input_field('products_price_gross', $sPrice, 'onkeyup="updateNet()"'); ?>
                              </div>
                           </div>
                        </fieldset>						
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_LIST_PRICE; ?></label>
                              <div class="col-lg-10">
                                <?php
    $sPriceList = number_format($pInfo->products_price_list ?? 0, TAX_DECIMAL_PLACES, '.', '');
    echo oos_draw_input_field('products_price_list', $sPriceList, 'onkeyup="updateWithTax()"'); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_LIST_PRICE_WITH_TAX; ?></label>
                              <div class="col-lg-10">
                                <?php
    $sPriceList = number_format($pInfo->products_price_list ?? 0, TAX_DECIMAL_PLACES, '.', '');
    echo oos_draw_input_field('products_price_list_gross', $sPriceList, 'onkeyup="updateNet()"'); ?>
                              </div>
                           </div>
                        </fieldset>						
<?php
  if (BASE_PRICE == 'true') {
      ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRODUCT_QUANTITY; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_product_quantity', $pInfo->products_product_quantity, 'onkeyup="calcBasePriceFactor()"'); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_UNIT; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_pull_down_menu('products_units_id', '', $products_units_array, $pInfo->products_units_id); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_BASE_QUANTITY; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_base_quantity', $pInfo->products_base_quantity, 'onkeyup="calcBasePriceFactor()"'); ?>
                              </div>
                           </div>
                        </fieldset>					
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_BASE_UNIT; ?></label>
                              <div class="col-lg-10">
                                 <?php echo implode(", ", array_values($unit_of_measure)); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_BASE_PRICE_FACTOR; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_base_price', $pInfo->products_base_price); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
  } ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_pull_down_menu('products_tax_class_id', '', $tax_class_array, $pInfo->products_tax_class_id, 'onchange="updateWithTax()"') ?>
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_DISCOUNTS_TITLE; ?></label>
                              <div class="col-lg-10">
 <?php

    $sDiscount1 = number_format($pInfo->products_discount1 ?? 0, 2, '.', '');
    $sDiscount2 = number_format($pInfo->products_discount2 ?? 0, 2, '.', '');
    $sDiscount3 = number_format($pInfo->products_discount3 ?? 0, 2, '.', '');
    $sDiscount4 = number_format($pInfo->products_discount4 ?? 0, 2, '.', ''); ?>
 
<table class="table table-striped">
  <thead class="thead-dark">
    <tr>
      <th scope="col"><?php echo TEXT_DISCOUNTS_BREAKS; ?></th>
      <th scope="col">1</th>
      <th scope="col">2</th>
      <th scope="col">3</th>
	  <th scope="col">4</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><?php echo TEXT_DISCOUNTS_QTY; ?></th>
      <td><?php echo oos_draw_input_field('products_discount1_qty', $pInfo->products_discount1_qty ?? 0); ?></td>
      <td><?php echo oos_draw_input_field('products_discount2_qty', $pInfo->products_discount2_qty ?? 0); ?></td>
      <td><?php echo oos_draw_input_field('products_discount3_qty', $pInfo->products_discount3_qty ?? 0); ?></td>
      <td><?php echo oos_draw_input_field('products_discount4_qty', $pInfo->products_discount4_qty ?? 0); ?></td>
    </tr>
    <tr>
      <th scope="row"><?php echo TEXT_DISCOUNTS_PRICE; ?></th>
      <td><?php echo oos_draw_input_field('products_discount1', $sDiscount1, 'onkeyup="updateWithTax()"'); ?></td>
      <td><?php echo oos_draw_input_field('products_discount2', $sDiscount2, 'onkeyup="updateWithTax()"'); ?></td>
      <td><?php echo oos_draw_input_field('products_discount3', $sDiscount3, 'onkeyup="updateWithTax()"'); ?></td>
	  <td><?php echo oos_draw_input_field('products_discount4', $sDiscount4, 'onkeyup="updateWithTax()"'); ?></td>
    </tr>
    <tr>
      <th scope="row"><?php echo TEXT_DISCOUNTS_PRICE_WITH_TAX; ?></th>
      <td><?php echo oos_draw_input_field('products_discount_gross1', $sDiscount1, 'onkeyup="updateNet()"'); ?></td>
      <td><?php echo oos_draw_input_field('products_discount_gross2', $sDiscount2, 'onkeyup="updateNet()"'); ?></td>
      <td><?php echo oos_draw_input_field('products_discount_gross3', $sDiscount3, 'onkeyup="updateNet()"'); ?></td>
	  <td><?php echo oos_draw_input_field('products_discount_gross4', $sDiscount4, 'onkeyup="updateNet()"'); ?></td>
    </tr>	
	
  </tbody>
</table>
 
                            </div>
                           </div>
                        </fieldset>
<script nonce="<?php echo NONCE; ?>">
updateWithTax();
</script>

                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_MODEL; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('products_model', $pInfo->products_model ?? ''); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_EAN; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_ean', $pInfo->products_ean ?? ''); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_QUANTITY; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_quantity', $pInfo->products_quantity ?? ''); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRODUCT_MINIMUM_ORDER; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_quantity_order_min', $pInfo->products_quantity_order_min ?? 1); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRODUCT_PACKAGING_UNIT; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_quantity_order_units', $pInfo->products_quantity_order_units ?? 1); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_PRODUCT_MAXIMUM_ORDER; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_quantity_order_max', $pInfo->products_quantity_order_max ?? 30); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
    if (STOCK_CHECK == 'true') {
        ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_REORDER_LEVEL; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_reorder_level', $pInfo->products_reorder_level ?? 5); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
    } ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_WEIGHT; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('products_weight', $pInfo->products_weight); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_STATUS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('products_status', '', $products_status_array, $pInfo->products_status); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_REPLACEMENT_PRODUCT; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_replacement_product_id', $pInfo->products_replacement_product_id ?? ''); ?>
                              </div>
                           </div>
                        </fieldset>



                     </div>
                     <div class="tab-pane" id="obligation" role="tabpanel">

						<div class="col-12 mt-3">
							<h2><?php echo TEXT_HEADER_INFORMATION_OBLIGATIONS; ?></h2>
						</div>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_checkbox_field('products_old_electrical_equipment', '', ($pInfo->products_old_electrical_equipment ?? '0')); ?></div>
                           </div>
                        </fieldset>


<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_OLD_ELECTRICAL_EQUIPMENT_OBLIGATIONS_NOTE;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('', 'products_old_electrical_equipment_description_' . $aLanguages[$i]['id'], 'soft', '70', '4', (isset($_POST['products_old_electrical_equipment_description_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_old_electrical_equipment_description_' .$aLanguages[$i]['id']]) : oos_get_products_old_electrical_equipment_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
    } ?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_OFFER_B_WARE_INFO; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_checkbox_field('products_used_goods', '', ($pInfo->products_used_goods ?? '0')); ?></div>
                           </div>
                        </fieldset>

<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_OFFER_B_WARE_INFO_NOTE;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('', 'products_used_goods_description_' . $aLanguages[$i]['id'], 'soft', '70', '4', (isset($_POST['products_used_goods_description_' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['products_used_goods_description_' .$aLanguages[$i]['id']]) : oos_get_products_used_goods_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
    } ?>
                     </div>
					 
					 
					<div class="tab-pane" id="social" role="tabpanel">

						<div class="col-12 mt-3">
							<h2><?php echo TEXT_HEADER_FACEBOOK; ?></h2>
						</div>


<?php
        for ($i = 0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
            ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_TITLE;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('products_facebook_title[' . $aLanguages[$i]['id'] . ']', (empty($pInfo->products_id) ? '' : oos_get_products_facebook_title($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
        }
    for ($i = 0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
        ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_DESCRIPTION;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('', 'products_facebook_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (empty($pInfo->products_id) ? '' : oos_get_products_facebook_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
    } ?>


						<div class="col-12 mt-3">
							<h2><?php echo TEXT_HEADER_TWITTER; ?></h2>
						</div>

                        <div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php echo TEXT_DATA_FROM_FACEBOOK; ?></label>
							<div class="col-lg-10">
								<div class="c-radio c-radio-nofont">
									<label>
										<input type="radio" name="facebook-data" value="YES" checked="checked">&nbsp;
										<span class="badge badge-danger float-right"><?php echo ENTRY_YES; ?></span>
									</label>
								</div>
								<div class="c-radio c-radio-nofont">
									<label>
										<input type="radio" name="facebook-data" value="NO" >&nbsp;
										<span class="badge badge-success float-right"><?php echo ENTRY_NO; ?></span>
									</label>
								</div>
							</div>
						</div>

<?php
        for ($i = 0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
            ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_TITLE;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_input_field('products_twitter_title[' . $aLanguages[$i]['id'] . ']', (empty($pInfo->products_id) ? '' : oos_get_products_twitter_title($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
        }
    for ($i = 0; $i < (is_countable($aLanguages) ? count($aLanguages) : 0); $i++) {
        ?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) {
							    echo TEXT_DESCRIPTION;
							} ?></label>
							<?php if ($nLanguages > 1) {
							    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
							} ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('', 'products_twitter_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (empty($pInfo->products_id) ? '' : oos_get_products_twitter_description($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
    } ?>


                     </div>
				 				 
                     <div class="tab-pane" id="picture" role="tabpanel">
		<script nonce="<?php echo NONCE; ?>">
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
    if (oos_is_not_null($pInfo->products_image)) {
        echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
        echo oos_info_image('product/small/' . $pInfo->products_image, $pInfo->products_name);
        echo '</div></div>';


        echo oos_draw_hidden_field('products_previous_image', $pInfo->products_image);
        echo '<br>';
        echo oos_draw_checkbox_field('remove_image', 'yes') . ' ' . TEXT_IMAGE_REMOVE;
    } else {
        ?>


<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>
	
	<input type="file" size="40" name="products_image"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>
<?php
    } ?>
			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_DETAILS; ?></strong>
			</div>	
		</div>
		
<?php
    $nCounter = 0;
    if ((isset($pInfo->products_larger_images)) && (is_array($pInfo->products_larger_images) || is_object($pInfo->products_larger_images))) {
        foreach ($pInfo->products_larger_images as $image) {
            $nCounter++; ?>

		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

<?php
        echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
            echo oos_info_image('product/small/' .  $image['image'], $pInfo->products_name);
            echo '</div></div>';

            echo $image['image'];

            echo oos_draw_hidden_field('products_previous_large_image['. $nCounter . ']', $image['image']);
            echo '<br>';
            echo oos_draw_checkbox_field('remove_products_image['. $nCounter . ']', 'yes') . ' ' . TEXT_IMAGE_REMOVE; ?>
			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_DETAILS; ?></strong>
			</div>	
		</div>
<?php
        }
    }
    echo oos_draw_hidden_field('image_counter', $nCounter); ?>		
		
		
		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>
	
	<input type="file" size="40" name="files[]"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>

			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_DETAILS; ?></strong>
			</div>	
		</div>

		<div class="row mb-3 pb-3 bb">
			<div class="col-6 col-md-3">

<div class="fileinput fileinput-new" data-provides="fileinput">
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>
	
	<input type="file" size="40" name="files[]"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>

			</div>
			<div class="col-9">
				<strong><?php echo TEXT_INFO_DETAILS; ?></strong>
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
  <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
  <div>
    <span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em><?php echo BUTTON_SELECT_IMAGE; ?></span><span class="fileinput-exists"><?php echo BUTTON_CHANGE; ?></span>
	
	<input type="file" size="40" name="files[]"></span>
    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em><?php echo BUTTON_DELETE; ?></a>
  </div>
</div>		

                           </div>
                           <div class="col-9">
                              <strong><?php echo TEXT_INFO_DETAILS; ?></strong>
                           </div>	
						</div>
		</div>
	</div>
	<p id="addUploadBoxes"><a href="javascript:addUploadBoxes('place','filetemplate',3)" title="<?php echo TEXT_NOT_RELOAD; ?>">+ <?php echo TEXT_ADD_MORE_UPLOAD; ?></a></p>

                     </div>
                  </div>
               </div>
            <div class="text-right mt-3">
				<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
				<?php echo oos_reset_button(BUTTON_RESET); ?>
				<?php echo oos_submit_button(BUTTON_SAVE); ?>		   
			</div>				
			
            </form>
<!-- body_text_eof //-->
<?php
} elseif ($action == 'new_product_preview') {
    $product_result = $dbconn->Execute("SELECT pd.products_name, pd.products_description, pd.products_description_meta, pd.products_url, p.products_id, p.products_quantity, p.products_reorder_level, p.products_model, p.products_replacement_product_id, p.products_ean, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_units_id, p.products_old_electrical_equipment, p.manufacturers_id, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE p.products_id = '" . intval($pID) . "' and p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'");
    $product = $product_result->fields;

    $pInfo = new objectInfo($product);
    $products_image_name = $pInfo->products_image;
    $aLanguages = oos_get_languages();
    $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        $pInfo->products_name = oos_get_products_name($pID);
        $pInfo->products_description = oos_get_products_description($pID, $aLanguages[$i]['id']);
        $pInfo->products_description_meta = oos_get_products_description_meta($pID, $aLanguages[$i]['id']);
        $pInfo->products_url = oos_get_products_url($pInfo->products_id, $aLanguages[$i]['id']);
    } ?>
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php
    $id = 0;
    echo oos_flag_icon($aLanguages[$id]) . '&nbsp;' . $pInfo->products_name; ?></td>
			<td class="pageHeading" align="right"><?php echo $currencies->format($pInfo->products_price); ?></td>
          </tr>
<?php

if (!($pInfo->products_discount1_qty == 0 and $pInfo->products_discount2_qty == 0 and $pInfo->products_discount3_qty == 0 and $pInfo->products_discount4_qty == 0)) {
    $the_special = oos_get_products_special_price($_GET['pID']);

    $q0 = $pInfo->products_quantity_order_min;
    $q1 = $pInfo->products_discount1_qty;
    $q2 = $pInfo->products_discount2_qty;
    $q3 = $pInfo->products_discount3_qty;
    $q4 = $pInfo->products_discount4_qty;

    $col_cnt = 1;
    if ($pInfo->products_discount1 > 0) {
        $col_cnt = $col_cnt + 1;
    }
    if ($pInfo->products_discount2 > 0) {
        $col_cnt = $col_cnt + 1;
    }
    if ($pInfo->products_discount3 > 0) {
        $col_cnt = $col_cnt + 1;
    }
    if ($pInfo->products_discount4 > 0) {
        $col_cnt = $col_cnt + 1;
    } ?>

  <tr>
    <td colspan="2" class="main" align="right">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" class="main" align="right">
      <table width="<?php echo 50 * $col_cnt; ?>" border="1" cellpadding="2" cellspacing="2" align="right">
        <tr>
          <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2" align="center">
<?php
if ($q1 < $q0) {
    ?>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center">WARNING: Quanties Minimum &gt;<br> Price Break 1</td>
              </tr>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center">&nbsp;</td>
              </tr>

<?php
} ?>
              <tr>
                <td colspan="<?php echo $col_cnt; ?>" class="DiscountPriceTitle" align="center"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
              </tr>
              <tr>
<?php
    echo '      <td class="DiscountPriceQty" align="center">';
    echo(($q1 - 1) > $q0 ? $q0 . '-' . ($q1 - 1) : $q0);
    echo '      </td>';

    if ($q1 > 0) {
        echo '<td class="DiscountPriceQty" align="center">';
        echo($q2 > 0 ? (($q2 - 1) > $q1 ? $q1 . '-' . ($q2 - 1) : $q1) : $q1 . '+');
        echo '</td>';
    }

    if ($q2 > 0) {
        echo '<td class="DiscountPriceQty" align="center">';
        echo($q3 > 0 ? (($q3 - 1) > $q2 ? $q2 . '-' . ($q3 - 1) : $q2) : $q2 . '+');
        echo '</td>';
    }

    if ($q3 > 0) {
        echo '<td class="DiscountPriceQty" align="center">';
        echo($q4 > 0 ? (($q4 - 1) > $q3 ? $q3 . '-' . ($q4 - 1) : $q3) : $q3 . '+');
        echo '</td>';
    }

    if ($q4 > 0) {
        echo '<td class="DiscountPriceQty" align="center">';
        echo($q4 > 0 ? $q4 . '+' : '');
        echo '</td>';
    } ?>
              </tr>

              <tr>
<?php
    echo '<td class="DiscountPrice" align="center">';
    echo(($the_special == 0) ? $currencies->format($pInfo->products_price) : $currencies->format($the_special));
    echo '</td>';

    if ($q1 > 0) {
        echo '<td class="DiscountPrice" align="center">';
        echo $currencies->format($pInfo->products_discount1);
        echo '</td>';
    }

    if ($q2 > 0) {
        echo '<td class="DiscountPrice" align="center">';
        echo $currencies->format($pInfo->products_discount2);
        echo '</td>';
    }

    if ($q3 > 0) {
        echo '<td class="DiscountPrice" align="center">';
        echo $currencies->format($pInfo->products_discount3);
        echo '</td>';
    }

    if ($q4 > 0) {
        echo '<td class="DiscountPrice" align="center">';
        echo $currencies->format($pInfo->products_discount4);
        echo '</td>';
    } ?>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </td>
  </tr>
<?php
} ?>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main">
<?php
    echo(($products_image_name) ? oos_image(OOS_SHOP_IMAGES . 'product/large/' . $products_image_name, $pInfo->products_name, '') : '');
    echo $pInfo->products_description; ?></td>
      </tr>
<?php
    if ($pInfo->products_url) {
        ?>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
    } ?>
<?php
    if ($pInfo->products_date_available > date('Y-m-d')) {
        ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_AVAILABLE, oos_date_long($pInfo->products_date_available)); ?></td>
      </tr>
<?php
    } else {
        ?>
      <tr>
        <td align="center" class="smallText"><?php echo sprintf(TEXT_PRODUCT_DATE_ADDED, oos_date_long($pInfo->products_date_added)); ?></td>
      </tr>
<?php
    } ?>
      <tr>
        <td></td>
      </tr>
<?php

    if (isset($_GET['origin'])) {
        $sOrigin = oos_prepare_input($_GET['origin']);
        $pos_params = strpos((string) $sOrigin, '?', 0);
        if ($pos_params != false) {
            $back_url = substr((string) $sOrigin, 0, $pos_params);
            $back_url_params = substr((string) $sOrigin, $pos_params + 1);
        } else {
            $back_url = $sOrigin;
            $back_url_params = '';
        }
    } else {
        $back_url = $aContents['categories'];
        $back_url_params = 'cPath=' . $cPath;
        if (oos_is_not_null($pInfo->products_id)) {
            $back_url_params .= '&pID=' . $pInfo->products_id;
        }
    } ?>
      <tr>
        <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
      </tr>

	      </table>
<!-- body_text_eof //-->
<?php
}
?>


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
