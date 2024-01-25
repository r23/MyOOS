<?php
/**
 * ---------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: products_attributes.php,v 1.52 2003/07/10 20:46:01 dgw_
         products_attributes.php,v 1.48 2002/11/22 14:45:49 dgw_
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
require 'includes/functions/function_products_attributes.php';
require 'includes/classes/class_upload.php';

/*
echo '<pre>';
print_r($_GET);
print_r($_POST);
echo '</pre>';

if (isset($_GET['action'])) {
	 exit;
}
*/

$cPath = filter_string_polyfill(filter_input(INPUT_GET, 'cPath'));
$categories_page = filter_input(INPUT_GET, 'categories_page', FILTER_VALIDATE_INT) ?: 1;

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

$attribute_id = filter_input(INPUT_GET, 'attribute_id', FILTER_VALIDATE_INT) ?: 0;


$aLanguages = oos_get_languages();
$nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

$page_info = '';
if (isset($_GET['option_page'])) {
    $option_page = intval($_GET['option_page']);
    $page_info .= 'option_page=' . $option_page . '&';
}

if (isset($_GET['cPath'])) {
    $cPath = filter_string_polyfill(filter_input(INPUT_GET, 'cPath'));
    $page_info .= 'cPath=' . $cPath . '&';
}

if (isset($_GET['pID'])) {
    $pID = intval($_GET['pID']);
    $page_info .= 'pID=' . $pID . '&';
}


if (isset($_GET['value_page'])) {
    $value_page =  intval($_GET['value_page']);
    $page_info .= 'value_page=' . $value_page . '&';
}

if (isset($_GET['attribute_page'])) {
    $attribute_page = intval($_GET['attribute_page']);
    $page_info .= 'attribute_page=' . $attribute_page . '&';
}

if (oos_is_not_null($page_info)) {
    $page_info = substr($page_info, 0, -1);
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
        'max_width' => 45,
        // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
        'max_height' => 45,
    ],
]];




switch ($action) {
    case 'add_product_options':
        for ($i = 0, $n = is_countable($aLanguages) ? count($aLanguages) : 0; $i < $n; $i ++) {
            $option_name =  oos_db_prepare_input($_POST['option_name']);
            $option_type = oos_db_prepare_input($_POST['option_type']);

            $products_optionstable = $oostable['products_options'];
            $dbconn->Execute("INSERT INTO $products_optionstable (products_options_id, products_options_name, products_options_languages_id,products_options_type) VALUES ('" . intval($_POST['products_options_id']) . "', '" . oos_db_input($option_name[$aLanguages[$i]['id']]) . "', '" . oos_db_input($aLanguages[$i]['id']) . "', '" . oos_db_input($option_type) . "')");
        }
        switch ($option_type) {
            case PRODUCTS_OPTIONS_TYPE_TEXT:
            case PRODUCTS_OPTIONS_TYPE_FILE:

                $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
                $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable (products_options_values_id, products_options_id) values ('" . PRODUCTS_OPTIONS_VALUES_TEXT_ID .  "', '" .  intval($products_options_id) .  "')");
                break;
        }
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'add_product_option_values':
        for ($i = 0, $n = is_countable($aLanguages) ? count($aLanguages) : 0; $i < $n; $i ++) {
            $value_name = oos_db_prepare_input($_POST['value_name']);

            $products_options_valuestable = $oostable['products_options_values'];
            $dbconn->Execute("INSERT INTO $products_options_valuestable (products_options_values_id, products_options_values_languages_id, products_options_values_name) VALUES ('" . intval($_POST['value_id']) . "', '" . intval($aLanguages[$i]['id']) . "', '" . oos_db_input($value_name[$aLanguages[$i]['id']]) . "')");
        }

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable (products_options_id, products_options_values_id) VALUES ('" . $_POST['option_id'] . "', '" . $_POST['value_id'] . "')");
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'setflag':
        if (isset($_GET['aID'])) {
            oos_set_attributes_status($_GET['aID'], $_GET['flag']);
        }
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'add_product_attributes':
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

        $oProductImage = new upload('options_values_image', $options);

        $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/';
        $oProductImage->set_destination($dir_fs_catalog_images);

        if ($oProductImage->parse() && oos_is_not_null($oProductImage->filename)) {
            $options_values_image = $oProductImage->filename;
        } else {
            $options_values_image = '';
        }

        $value_price = str_replace(',', '.', (string) $_POST['value_price']);

        // 0 = Download id
        $values_id = (isset($_POST['values_id'])) ? intval($_POST['values_id']) : 0;

        /*
        $products_optionstable = $oostable['products_options'];
        $products_options_result = $dbconn->Execute("SELECT products_options_type FROM $products_optionstable WHERE products_options_id = '" . intval($_POST['options_id']) . "'");
        $products_options_array = $products_options_result->fields;
        $values_id = (($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_TEXT) or ($products_options_array['products_options_type'] == PRODUCTS_OPTIONS_TYPE_FILE)) ? PRODUCTS_OPTIONS_VALUE_TEXT_ID : $values_id;
        */

        // todo remove options_values_base_unit
        $options_values_base_unit = '';
        if (isset($_POST['options_values_base_price'])) {
            $options_values_base_price = oos_db_prepare_input($_POST['options_values_base_price']);
            $options_values_quantity = oos_db_prepare_input($_POST['options_values_quantity']);
            $options_values_base_quantity = oos_db_prepare_input($_POST['options_values_base_quantity']);
            $options_values_units_id = intval($_POST['options_values_units_id']);
        } else {
            $options_values_base_price = 1;
            $options_values_quantity = 1;
            $options_values_base_quantity = 1;

            $options_values_units_id = 0;
        }

        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute(
            "INSERT INTO $products_attributestable 
						(products_attributes_id, 
						products_id,
						options_id,
						options_values_model,
						options_values_image,
						options_values_id,
						options_values_price,
						options_values_base_price,
						options_values_quantity,
						options_values_base_quantity,
						options_values_base_unit,
						options_values_units_id,
						options_sort_order) 
						VALUES ('', 
								'" . oos_db_prepare_input($_POST['products_id']) . "', 
								'" . oos_db_prepare_input($_POST['options_id']) . "', 
								'" . oos_db_prepare_input($_POST['options_values_model']) . "', 
								'" . oos_db_prepare_input($options_values_image) . "',
								'" . oos_db_prepare_input($values_id) . "', 
								'" . oos_db_prepare_input($value_price) . "', 
								'" . oos_db_prepare_input($options_values_base_price) . "',
								'" . oos_db_prepare_input($options_values_quantity) . "',
								'" . oos_db_prepare_input($options_values_base_quantity) . "',
								'" . oos_db_prepare_input($options_values_base_unit) . "',
								'" . oos_db_prepare_input($options_values_units_id) . "', 								
								'" . oos_db_prepare_input($_POST['sort_order']) . "')"
        );
        $products_attributes_id = $dbconn->Insert_ID();

        if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
            $products_attributes_maxdays  = $_POST['products_attributes_maxdays'] ?? DOWNLOAD_MAX_DAYS;
            $products_attributes_maxcount = $_POST['products_attributes_maxcount'] ?? DOWNLOAD_MAX_COUNT;

            $products_attributes_downloadtable = $oostable['products_attributes_download'];
            $dbconn->Execute(
                "INSERT INTO $products_attributes_downloadtable 
							(products_attributes_id,
							products_attributes_filename,
							products_attributes_maxdays,
							products_attributes_maxcount)
							VALUES (" . oos_db_prepare_input($products_attributes_id) . ", 
								'" . oos_db_prepare_input($_POST['products_attributes_filename']) . "', 
								'" . oos_db_prepare_input($products_attributes_maxdays) . "', 
								'" . oos_db_prepare_input($products_attributes_maxcount) . "')"
            );
        }
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'update_option_name':
        for ($i = 0, $n = is_countable($aLanguages) ? count($aLanguages) : 0; $i < $n; $i ++) {
            $option_name = oos_db_prepare_input($_POST['option_name']);
            $option_type = oos_db_prepare_input($_POST['option_type']);
            $products_optionstable = $oostable['products_options'];
            $dbconn->Execute("UPDATE $products_optionstable SET products_options_name = '" . $option_name[$aLanguages[$i]['id']] . "', products_options_type = '" . $option_type . "' WHERE products_options_id = '" . intval($_POST['option_id']) . "' AND products_options_languages_id = '" . $aLanguages[$i]['id'] . "'");
        }
        switch ($option_type) {
            case PRODUCTS_OPTIONS_TYPE_TEXT:
            case PRODUCTS_OPTIONS_TYPE_FILE:
                $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
                $dbconn->Execute("INSERT INTO $products_options_values_to_products_optionstable VALUES (NULL, '" . intval($_POST['option_id']) . "', '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "')");
                break;
            default:
                $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
                $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'update_value':
        for ($i = 0, $n = is_countable($aLanguages) ? count($aLanguages) : 0; $i < $n; $i ++) {
            $value_name = oos_db_prepare_input($_POST['value_name']);

            $products_options_valuestable = $oostable['products_options_values'];
            $dbconn->Execute("UPDATE $products_options_valuestable SET products_options_values_name = '" . $value_name[$aLanguages[$i]['id']] . "' WHERE products_options_values_id = '" . intval($_POST['value_id']) . "' AND  products_options_values_languages_id= '" . $aLanguages[$i]['id'] . "'");
        }

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        // $dbconn->Execute("UPDATE $products_options_values_to_products_optionstable SET products_options_id = '" . intval($_POST['option_id']) . "', products_options_values_id = '" . intval($_POST['value_id']) . "'  WHERE products_options_values_to_products_options_id = '" . intval($_POST['value_id']) . "'");
        $dbconn->Execute("UPDATE $products_options_values_to_products_optionstable SET products_options_id = '" . intval($_POST['option_id']) . "' WHERE products_options_values_id = '" . intval($_POST['value_id']) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;

    case 'update_product_attribute':
        if (isset($_POST['products_previous_image'])) {
            $options_values_image = oos_db_prepare_input($_POST['products_previous_image']);
        }

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

        $oProductImage = new upload('options_values_image', $options);

        $dir_fs_catalog_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/';
        $oProductImage->set_destination($dir_fs_catalog_images);

        if ($oProductImage->parse() && oos_is_not_null($oProductImage->filename)) {
            $options_values_image = $oProductImage->filename;
        }

        if ((isset($_POST['remove_image']) && ($_POST['remove_image'] == 'yes')) && (isset($_POST['products_previous_image']))) {
            $image = oos_db_prepare_input($_POST['products_previous_image']);
            oos_remove_product_image($image);
            $options_values_image = '';
        }


        $_POST['value_price'] = str_replace(',', '.', (string) $_POST['value_price']);


        /*
        $products_optionstable = $oostable['products_options'];
        $products_options_result = $dbconn->Execute("SELECT products_options_type FROM $products_optionstable WHERE products_options_id = '" . intval($_POST['options_id']) . "'");
        $products_options_array = $products_options_result->fields;

        if (!empty($products_options_array['products_options_type'])) {
        switch ($products_options_array['products_options_type']) {
            case PRODUCTS_OPTIONS_TYPE_TEXT:
            case PRODUCTS_OPTIONS_TYPE_FILE:
                $values_id = PRODUCTS_OPTIONS_VALUE_TEXT_ID;
                break;
        default:
                $values_id = oos_db_prepare_input($_POST['values_id']);
        }
        }
        */
        // 0 = Download id
        $values_id = (isset($_POST['values_id'])) ? intval($_POST['values_id']) : 0;

        // todo remove options_values_base_unit
        $options_values_base_unit = '';

        if (isset($_POST['options_values_base_price'])) {
            $options_values_base_price = oos_db_prepare_input($_POST['options_values_base_price']);
            $options_values_quantity = oos_db_prepare_input($_POST['options_values_quantity']);
            $options_values_base_quantity = oos_db_prepare_input($_POST['options_values_base_quantity']);
            $options_values_units_id = intval($_POST['options_values_units_id']);
        } else {
            $options_values_base_price = 1;
            $options_values_quantity = 1;
            $options_values_base_quantity = 1;
            $options_values_units_id = 0;
        }


        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute(
            "UPDATE $products_attributestable 
						SET products_id = '" . oos_db_prepare_input($_POST['products_id']) . "',
						options_id = '" . oos_db_prepare_input($_POST['options_id']) . "',
						options_values_model = '" . oos_db_prepare_input($_POST['options_values_model']) . "',
						options_values_image  = '" . oos_db_prepare_input($options_values_image) . "',
						options_values_id = '" . oos_db_prepare_input($values_id) . "',
						options_values_price = '" . oos_db_prepare_input($_POST['value_price']) . "',
						options_values_base_price = '" . oos_db_prepare_input($options_values_base_price) . "',
						options_values_quantity = '" . oos_db_prepare_input($options_values_quantity) . "',
						options_values_base_quantity = '" . oos_db_prepare_input($options_values_base_quantity) . "',
						options_values_base_unit = '" . oos_db_prepare_input($options_values_base_unit) . "',
						options_values_units_id = '" . oos_db_prepare_input($options_values_units_id) . "',						
						 options_sort_order = '" . oos_db_prepare_input($_POST['sort_order']) . "' WHERE products_attributes_id = '" . intval($_POST['attribute_id']) . "'"
        );


        if ((DOWNLOAD_ENABLED == 'true') && $_POST['products_attributes_filename'] != '') {
            $products_attributes_maxdays  = $_POST['products_attributes_maxdays'] ?? DOWNLOAD_MAX_DAYS;
            $products_attributes_maxcount = $_POST['products_attributes_maxcount'] ?? DOWNLOAD_MAX_COUNT;

            $products_attributes_downloadtable = $oostable['products_attributes_download'];
            $download_result = $dbconn->Execute("SELECT products_attributes_filename FROM $products_attributes_downloadtable WHERE products_attributes_id = '" . intval($_POST['attribute_id']) . "'");
            if (!$download_result->RecordCount()) {
                $dbconn->Execute(
                    "INSERT INTO $products_attributes_downloadtable 
							(products_attributes_id,
							products_attributes_filename,
							products_attributes_maxdays,
							products_attributes_maxcount)
							VALUES ('" . intval($_POST['attribute_id']) . "', 
								'" . oos_db_prepare_input($_POST['products_attributes_filename']) . "', 
								'" . oos_db_prepare_input($products_attributes_maxdays) . "', 
								'" . oos_db_prepare_input($products_attributes_maxcount) . "')"
                );
            } else {
                $products_attributes_downloadtable = $oostable['products_attributes_download'];
                $dbconn->Execute(
                    "UPDATE $products_attributes_downloadtable
                        SET products_attributes_filename ='" . oos_db_input($_POST['products_attributes_filename']) . "',
                            products_attributes_maxdays ='" . oos_db_input($products_attributes_maxdays) . "',
                            products_attributes_maxcount ='" . oos_db_input($products_attributes_maxcount) . "'
                        WHERE products_attributes_id = '" . intval($_POST['attribute_id']) . "'"
                );
            }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;
    case 'delete_option':
        $products_optionstable = $oostable['products_options'];
        $dbconn->Execute("DELETE FROM $products_optionstable WHERE products_options_id = '" . intval($_GET['option_id']) . "'");

        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        //  $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_id = '" . intval($_GET['option_id']) . "' AND products_options_values_id = '" . PRODUCTS_OPTIONS_VALUES_TEXT_ID . "'");
        $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_id = '" . intval($_GET['option_id']) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;
    case 'delete_value':
        $products_options_valuestable = $oostable['products_options_values'];
        $dbconn->Execute("DELETE FROM $products_options_valuestable WHERE products_options_values_id = '" . intval($_GET['value_id']) . "'");
        $products_options_values_to_products_optionstable = $oostable['products_options_values_to_products_options'];
        $dbconn->Execute("DELETE FROM $products_options_values_to_products_optionstable WHERE products_options_values_id = '" . intval($_GET['value_id']) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;
    case 'delete_attribute':
        $products_attributestable = $oostable['products_attributes'];
        $dbconn->Execute("DELETE FROM $products_attributestable WHERE products_attributes_id = '" . intval($attribute_id) . "'");
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $dbconn->Execute("DELETE FROM $products_attributes_downloadtable WHERE products_attributes_id = '" . intval($attribute_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['product_options'], $page_info));
        break;
}


$products_options_types_list = [];
$products_options_typestable = $oostable['products_options_types'];
$products_options_types_sql = "SELECT products_options_types_id, products_options_types_name
                                 FROM $products_options_typestable
                                 WHERE products_options_types_languages_id = '" . intval($_SESSION['language_id']) . "'
                                 ORDER BY products_options_types_id";
$products_options_types_result = $dbconn->Execute($products_options_types_sql);
while ($products_options_type_array = $products_options_types_result->fields) {
    $products_options_types_list[$products_options_type_array['products_options_types_id']] = $products_options_type_array['products_options_types_name'];

    // Move that ADOdb pointer!
    $products_options_types_result->MoveNext();
}

$products_units_array = [];
$unit_of_measure = [];
$products_units_array = [['id' => '0', 'text' => TEXT_NONE]];
$products_unitstable = $oostable['products_units'];
$products_units_result = $dbconn->Execute("SELECT products_units_id, products_unit_name, unit_of_measure FROM $products_unitstable WHERE languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_unit_name");
while ($products_units = $products_units_result->fields) {
    $products_units_array[] = ['id' => $products_units['products_units_id'], 'text' => $products_units['products_unit_name']];
    if ((!empty($products_units['unit_of_measure'])) && (!in_array($products_units['unit_of_measure'], $unit_of_measure))) {
        $unit_of_measure[] = $products_units['unit_of_measure'];
    }

    // Move that ADOdb pointer!
    $products_units_result->MoveNext();
}



$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_title, pd.products_description, pd.products_short_description, pd.products_url,
                              pd.products_description_meta, pd.products_facebook_title, pd.products_facebook_description, pd.products_twitter_title,
							  pd.products_twitter_description, pd.products_old_electrical_equipment_description, pd.products_used_goods_description,
							  p.products_model, p.products_replacement_product_id, p.products_used_goods,
                              p.products_quantity, p.products_image, p.products_price, p.products_base_price,
							  p.products_product_quantity, p.products_base_unit, p.products_quantity_order_min, 
							  p.products_quantity_order_max, p.products_quantity_order_units,
                              p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                              p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                              p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                              p.products_date_available, p.products_last_modified, p.manufacturers_id, p.products_price_list, p.products_status
                        FROM $productstable p,
                             $products_descriptiontable pd
                        WHERE p.products_id = '" . intval($pID) . "'
                          AND pd.products_id = p.products_id
                          AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'";
$product_info_result = $dbconn->Execute($product_info_sql);
$product_info = $product_info_result->fields;


if (!isset($value_page)) {
    $value_page = 1;
}

if (!isset($attribute_page)) {
    $attribute_page = 1;
}


require 'includes/header.php';
?>
<script nonce="<?php echo NONCE; ?>">
function go_option() {
  if (document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value != "none") {
    location = "<?php echo oos_href_link_admin($aContents['product_options'], 'option_page=' . (isset($_GET['option_page']) ? intval($_GET['option_page']) : 1)); ?>&option_order_by="+document.option_order_by.selected.options[document.option_order_by.selected.selectedIndex].value;
  }
}
</script>

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
<?php
    $sTitle = sprintf(TEXT_EDIT_PRODUCT, oos_output_generated_category_path($cPath));
	$products_name_only = $product_info['products_name'];
?>							
			<!-- Breadcrumbs //-->
			<div class="content-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE_ATRIB. ': ' . $products_name_only; ?></h2>
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
						</li>
						<li class="breadcrumb-item">
							<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&page=' . $categories_page) . '">' . $sTitle . '</a>'; ?>
						</li>						
						<li class="breadcrumb-item active">
							<strong><?php echo HEADING_TITLE_ATRIB . ': ' . $products_name_only; ?></strong>
						</li>
					</ol>
				</div>
			</div>
			<!-- END Breadcrumbs //-->
			
			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">	
<!-- body_text //-->


<!-- products_attributes //-->

<?php
if ($action == 'update_attribute') {
    $form_action = 'update_product_attribute';
} else {
    $form_action = 'add_product_attributes';
}

$tax_class_array = [];
$tax_classtable = $oostable['tax_class'];
$tax_class_result = $dbconn->Execute("SELECT tax_class_id, tax_class_title FROM $tax_classtable ORDER BY tax_class_title");
while ($tax_class = $tax_class_result->fields) {
	$tax_class_array[] = ['id' => $tax_class['tax_class_id'],
					     'text' => $tax_class['tax_class_title']];

	// Move that ADOdb pointer!
	$tax_class_result->MoveNext();
}



?>
<script nonce="<?php echo NONCE; ?>">

function doRound(x, places) {
  num = Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
  return num.toFixed(places);    
}

let tax_rates = new Array();
<?php
    $n = is_countable($tax_class_array) ? count($tax_class_array) : 0;
    for ($i = 0, $n; $i < $n; $i++) {
        if ($tax_class_array[$i]['id'] > 0) {
            echo 'tax_rates["' . $tax_class_array[$i]['id'] . '"] = ' . oos_get_tax_rate_value($tax_class_array[$i]['id']) . ';' . "\n";
        }
    } 
?>

function getTaxRate() {
  let parameterVal = <?php echo $product_info['products_tax_class_id']; ?>

  if ( (parameterVal > 0) && (tax_rates[parameterVal] > 0) ) {
    return tax_rates[parameterVal];
  } else {
    return 0;
  }
}

function updateWithTax() {
  let taxRate = getTaxRate();
  let grossValue = document.forms["attributes"].value_price.value;
  
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["attributes"].value_price_gross.value = doRound(grossValue, 2);
}

function updateNet() {
  let taxRate = getTaxRate();
  let netValue = document.forms["attributes"].value_price_gross.value;
  
  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["attributes"].value_price.value = doRound(netValue, 2);
  
}

function calcBasePriceFactor() {
  let pqty = document.forms["attributes"].options_values_quantity.value;
  let bqty = document.forms["attributes"].options_values_base_quantity.value;

  if ((pqty != 0) || (bqty != 0)) {
     document.forms["attributes"].options_values_base_price.value = doRound(bqty / pqty, 6);
  } else {
     document.forms["attributes"].options_values_base_price.value = 1.00000;
  }

}
</script>

<form name="attributes" action="<?php echo oos_href_link_admin($aContents['product_options'], 'action=' . $form_action . '&cPath=' . $cPath . '&pID=' . $pID . (isset($option_page) ? '&option_page=' . $option_page : '') . (isset($value_page) ? '&value_page=' . $value_page : '') . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')); ?>" method="post" enctype="multipart/form-data">
		<?php echo oos_draw_hidden_field('products_id', $pID);
    echo oos_hide_session_id(); ?>	
	
					<div class="text-right mt-3 mb-3">
						<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&page=' . $categories_page) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
						<?php echo oos_reset_button(BUTTON_RESET); ?>
						<?php echo oos_submit_button(BUTTON_SAVE); ?>						
					</div>		
	
		<table class="table table-hover w-100">
          <tr>
            <td colspan="11" class="smallText">
<?php
$per_page = 20;
$products_attributestable = $oostable['products_attributes'];
$products_descriptiontable = $oostable['products_description'];
$attributes = "SELECT pa.*
				FROM $products_attributestable pa,  
					$products_descriptiontable pd 
				WHERE pa.products_id = '" . intval($pID) . "'
				AND pa.products_id = pd.products_id 
				AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'";

if (!isset($attribute_page)) {
    $attribute_page = 1;
}
$prev_attribute_page = $attribute_page - 1;
$next_attribute_page = $attribute_page + 1;

$attribute_page_start = ($per_page * $attribute_page) - $per_page;

$attributes = $attributes . " LIMIT $attribute_page_start, $per_page";
$attribute_result = $dbconn->Execute($attributes);

$num_rows = $attribute_result->RecordCount();

if ($num_rows <= $per_page) {
    $num_pages = 1;
} elseif (($num_rows % $per_page) == 0) {
    $num_pages = ($num_rows / $per_page);
} else {
    $num_pages = ($num_rows / $per_page) + 1;
}
$num_pages = (int) $num_pages;

// Previous
if ($prev_attribute_page) {
    echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
}

for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
        echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
        echo '<b><font color="red">' . $i . '</font></b> | ';
    }
}

// Next
if ($attribute_page != $num_pages) {
    echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
}
?>
            </td>
          </tr>

			<thead class="thead-dark">
				<tr>			
					<th>&nbsp;<?php echo TABLE_HEADING_ID; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_IMAGE; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_PRODUCT; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_MODEL; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_OPT_NAME; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_OPT_VALUE; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_SORT_ORDER_VALUE; ?>&nbsp;</th>
					<th>&nbsp;<?php echo TABLE_HEADING_STATUS; ?>&nbsp;</th>
					<th class="text-right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE; ?>&nbsp;</th>
					<th class="text-right">&nbsp;<?php echo TABLE_HEADING_OPT_PRICE_WITH_TAX; ?>&nbsp;</th>									
					<th class="text-center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>					
				</tr>
			</thead>
<?php
$next_id = 1;
$rows = 0;

$attribute_result = $dbconn->Execute($attributes);
while ($attributes_values = $attribute_result->fields) {
    $options_name = oos_options_name($attributes_values['options_id']);
    $values_name = oos_values_name($attributes_values['options_values_id']);
    $rows++; ?>
          <tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>"> 
    <?php
    if (($action == 'update_attribute') && ($attribute_id == $attributes_values['products_attributes_id'])) {
        ?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?><input type="hidden" name="attribute_id" value="<?php echo $attributes_values['products_attributes_id']; ?>">&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo product_info_image($attributes_values['options_values_image'], $products_name_only, 'small'); ?><br>&nbsp;<?php echo $attributes_values['options_values_image']; ?>&nbsp;
        <?php if ($attributes_values['options_values_image'] != '') {  ?>
            <br>&nbsp;<?php echo oos_draw_checkbox_field('remove_image', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE; ?><br>
        <?php } ?>
            <br><br>        
        <?php echo '&nbsp;' . oos_draw_file_field('options_values_image') . oos_draw_hidden_field('products_previous_image', $attributes_values['options_values_image']); ?></td>
            <td class="smallText">&nbsp;
        <?php
		echo $products_name_only; ?>
				</td>
            <td class="smallText"><?php echo oos_draw_input_field('options_values_model', $attributes_values['options_values_model']); ?></td>
            <td class="smallText">&nbsp;<select name="options_id">
        <?php
        if ($options_values['products_options_id'] == 0) {
            echo "\n" . '<option name="id" value="0" selected="selected">' . PULL_DOWN_DEFAULT . '</option>';
        } else {
            echo "\n" . '<option name="id" value="0">' . PULL_DOWN_DEFAULT . '</option>';
        }

        $products_optionstable = $oostable['products_options'];
        $options = $dbconn->Execute("SELECT * FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
        while ($options_values = $options->fields) {
            if ($attributes_values['options_id'] == $options_values['products_options_id']) {
                echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '" selected="selected">' . $options_values['products_options_name'] . '</option>';
            } else {
                echo "\n" . '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';
            }

            // Move that ADOdb pointer!
            $options->MoveNext();
        } ?>
            </select>&nbsp;</td>
            <td class="smallText">&nbsp;<select name="values_id">
        <?php
        if ($values_values['products_options_values_id'] == 0) {
            echo "\n" . '<option name="id" value="0" selected="selected">' . PULL_DOWN_DEFAULT . '</option>';
        } else {
            echo "\n" . '<option name="id" value="0">' . PULL_DOWN_DEFAULT . '</option>';
        }


        $products_options_valuestable = $oostable['products_options_values'];
        $values = $dbconn->Execute("SELECT * FROM $products_options_valuestable WHERE products_options_values_languages_id='" . intval($_SESSION['language_id']) . "' ORDER BY products_options_values_name");
        while ($values_values = $values->fields) {
            if ($attributes_values['options_values_id'] == $values_values['products_options_values_id']) {
                echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '" selected="selected">' . $values_values['products_options_values_name'] . '</option>';
            } else {
                echo "\n" . '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';
            }

            // Move that ADOdb pointer!
            $values->MoveNext();
        } ?>
            </select>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<input type="text" name="sort_order" value="<?php echo $attributes_values['options_sort_order']; ?>" size="2">&nbsp;</td>
            <td class="smallText">&nbsp;<?php 
        if ($attributes_values['options_values_status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=0&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=1&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>

        <?php
			$in_price = $attributes_values['options_values_price'];
			$in_price = number_format($in_price ?? 0, TAX_DECIMAL_PLACES, '.', '');
		?>
            <td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $in_price; ?>" size="6">&nbsp;</td>
			<td align="right" class="smallText">&nbsp;<input type="text" name="value_price" value="<?php echo $in_price; ?>" size="6">&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo oos_submit_button(BUTTON_UPDATE); ?>&nbsp;<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['product_options'], 'cPath=' . $cPath . '&pID=' . $pID . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?></a>&nbsp;</td>
          </tr>
        <?php
        if (BASE_PRICE == 'true') {
            $options_values_base_price = (!isset($attributes_values['options_values_base_price'])) ? 1 : $attributes_values['options_values_base_price'];
            $options_values_quantity = $attributes_values['options_values_quantity'] ?? '';
            $options_values_base_quantity = $attributes_values['options_values_base_quantity'] ?? '';
            $options_values_units_id = $attributes_values['options_values_units_id'] ?? ''; ?>
		<tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>">
            <td>&nbsp;</td>
            <td>&nbsp;</td>			
            <td colspan="8">
              <table class="table w-100">
                <tr>
                  <td class="main"><?php echo TEXT_PRODUCTS_BASE_PRICE_FACTOR . '<br>' . oos_draw_input_field('options_values_base_price', $options_values_base_price); ?></td>
                  <td class="main"><br> <- </td>
                  <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY . '<br>' . oos_draw_input_field('options_values_quantity', $options_values_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                  <td class="main"><?php echo TEXT_PRODUCTS_UNIT . '<br>' . oos_draw_pull_down_menu('options_values_units_id', '', $products_units_array, $options_values_units_id); ?></td>
                </tr>
                <tr>
                    <td class="main"></td>
                    <td class="main"></td>
                    <td class="main"><?php echo TEXT_PRODUCTS_BASE_QUANTITY . '<br>' . oos_draw_input_field('options_values_base_quantity', $options_values_base_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                    <td class="main"><?php echo TEXT_PRODUCTS_BASE_UNIT . '<br>' . implode(", ", array_values($unit_of_measure)); ?> </td>
                </tr>
              </table>

            </td>
            <td>&nbsp;</td>
          </tr>       
            <?php
        }

        if (DOWNLOAD_ENABLED == 'true') {
            $products_attributes_filename = '';
            $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
            $products_attributes_maxcount = DOWNLOAD_MAX_COUNT;

            $products_attributes_downloadtable = $oostable['products_attributes_download'];
            $download_result_raw = "SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
								FROM $products_attributes_downloadtable
								WHERE products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'";
            $download_result = $dbconn->Execute($download_result_raw);
            if ($download_result->RecordCount() > 0) {
                $download = $download_result->fields;
                $products_attributes_filename = $download['products_attributes_filename'];
                $products_attributes_maxdays  = $download['products_attributes_maxdays'];
                $products_attributes_maxcount = $download['products_attributes_maxcount'];
            } ?>
		<tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="8">
              <table class="table w-100">
                <tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>">
                  <td><?php echo TABLE_HEADING_DOWNLOAD; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_filename', $products_attributes_filename, 'size="15"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo oos_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>    
            <?php
        }
    } elseif (($action == 'delete_product_attribute') && ($attribute_id == $attributes_values['products_attributes_id'])) {
        ?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values['products_attributes_id']; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo product_info_image($attributes_values['options_values_image'], $products_name_only, 'small'); ?><br>&nbsp;<?php echo $attributes_values['options_values_image']; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $products_name_only; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $attributes_values['options_values_model']; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $options_name; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $values_name; ?></b>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values['options_sort_order']; ?></b></td>
            <td class="smallText">&nbsp;<?php			
        if ($attributes_values['options_values_status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=0&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=1&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
        <?php
			$in_price = $attributes_values['options_values_price'];
			$in_price = number_format($in_price ?? 0, TAX_DECIMAL_PLACES, '.', '');
		?>	
            <td align="right" class="smallText">&nbsp;<b><?php echo $in_price; ?></b>&nbsp;</td>
			<td align="right" class="smallText">&nbsp;<b><?php echo $in_price; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a class="btn btn-sm btn-success mb-20" href="' . oos_href_link_admin($aContents['product_options'], 'action=delete_attribute&cPath=' . $cPath . '&pID=' . $pID . '&attribute_id=' . $attribute_id) . '" role="button"><strong>' . BUTTON_CONFIRM . '</strong></a>'; ?>&nbsp;&nbsp;<?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['product_options'], 'cPath=' . $cPath . '&pID=' . $pID . (isset($option_page) ? (isset($option_page) ? '&option_page=' . $option_page : '') : '') . (isset($value_page) ? (isset($value_page) ? '&value_page=' . $value_page : '') : '') . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?>&nbsp;</b></td>

        <?php
    } else {
        ?>
            <td class="smallText">&nbsp;<?php echo $attributes_values['products_attributes_id']; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo product_info_image($attributes_values['options_values_image'], $products_name_only, 'small'); ?><br>&nbsp;<?php echo $attributes_values['options_values_image']; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $products_name_only; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $attributes_values['options_values_model']; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $options_name; ?>&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo $values_name; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values['options_sort_order']; ?></b></td>
            <td class="smallText">&nbsp;
        <?php
        if ($attributes_values['options_values_status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=0&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['product_options'], 'action=setflag&flag=1&cPath=' . $cPath . '&pID=' . $pID . '&aID=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
        <?php
			$in_price = $attributes_values['options_values_price'];
			$in_price = number_format($in_price ?? 0, TAX_DECIMAL_PLACES, '.', '');
		?>
            <td align="right" class="smallText">&nbsp;<?php echo $in_price; ?>&nbsp;</td>
			<td align="right" class="smallText">&nbsp;<?php echo $in_price; ?>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<?php echo '<a class="btn btn-sm btn-primary mb-20" href="' . oos_href_link_admin($aContents['product_options'], 'action=update_attribute&cPath=' . $cPath . '&pID=' . intval($pID) . '&attribute_id=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) . '" role="button"><strong>' . BUTTON_EDIT . '</strong></a>'; ?>&nbsp;&nbsp;<?php echo '<a class="btn btn-sm btn-danger mb-20" href="' . oos_href_link_admin($aContents['product_options'], 'action=delete_product_attribute&cPath=' . $cPath . '&pID=' . intval($pID) . '&attribute_id=' . $attributes_values['products_attributes_id'] . (isset($attribute_page) ? '&attribute_page=' . $attribute_page : '')) , '" role="button"><strong>' . BUTTON_DELETE . '</strong></a>'; ?>&nbsp;</td>
        <?php
    } ?>
          </tr>
    <?php
    if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_downloadtable = $oostable['products_attributes_download'];
        $download_result_raw = "SELECT products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount
								FROM $products_attributes_downloadtable
								WHERE products_attributes_id = '" . $attributes_values['products_attributes_id'] . "'";
        $download_result = $dbconn->Execute($download_result_raw);
        if ($download_result->RecordCount() > 0) {
            $download = $download_result->fields;
            $products_attributes_filename = $download['products_attributes_filename'];
            $products_attributes_maxdays  = $download['products_attributes_maxdays'];
            $products_attributes_maxcount = $download['products_attributes_maxcount']; ?>
          <tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>">
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td colspan="8">
              <table class="table w-100">
                <tr class="<?php echo(floor($rows / 2) == ($rows / 2) ? 'table-secondary' : 'table-light'); ?>">
                  <td>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_FILENAME; ?>&nbsp;</td>
                  <td class="smallText"><b><?php echo $products_attributes_filename; ?></b>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_DAYS; ?></td>
                  <td class="smallText"><?php echo $products_attributes_maxdays; ?>&nbsp;</td>
                  <td class="smallText"><?php echo TABLE_TEXT_MAX_COUNT; ?></td>
                  <td class="smallText"><?php echo $products_attributes_maxcount; ?>&nbsp;</td>
                </tr>
              </table>
            </td>
            <td>&nbsp;</td>
          </tr>
            <?php
        }
    } // end of DOWNLOAD_ENABLED section

      // Move that ADOdb pointer!
      $attribute_result->MoveNext();
}
?>
		</table>
			
<?php
if ($action != 'update_attribute') {
    $products_attributestable = $oostable['products_attributes'];
    $max_attributes_id_result = $dbconn->Execute("SELECT max(products_attributes_id) + 1 as next_id FROM $products_attributestable");
    $max_attributes_id_values = $max_attributes_id_result->fields;
    $next_id = $max_attributes_id_values['next_id']; ?>
	
	
	                    <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_IMAGE; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_file_field('options_values_image'); ?></div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_MODEL; ?></label>
                              <div class="col-lg-10">
<?php
	if (!is_array($attributes_values)) {
		$attributes_values = [];
	}
	$attributes_values['options_values_model'] ??= '';
	echo oos_draw_input_field('options_values_model', $attributes_values['options_values_model']); 
?>
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_OPT_NAME; ?></label>
                              <div class="col-lg-10">
									<select name="options_id">
<?php
    echo '<option name="id" value="0" selected="selected">' . PULL_DOWN_DEFAULT . '</option>';

    $products_optionstable = $oostable['products_options'];
    $options = $dbconn->Execute("SELECT * FROM $products_optionstable WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_name");
    while ($options_values = $options->fields) {
        echo '<option name="' . $options_values['products_options_name'] . '" value="' . $options_values['products_options_id'] . '">' . $options_values['products_options_name'] . '</option>';

        // Move that ADOdb pointer!
        $options->MoveNext();
    } 
?>
								</select></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_OPT_VALUE; ?></label>
                              <div class="col-lg-10">						  
								<select name="values_id">
<?php
    echo '<option name="id" value="0" selected="selected">' . PULL_DOWN_DEFAULT . '</option>';

    $products_options_valuestable = $oostable['products_options_values'];
    $values = $dbconn->Execute("SELECT * FROM $products_options_valuestable WHERE products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_options_values_name");
    while ($values_values = $values->fields) {
        echo '<option name="' . $values_values['products_options_values_name'] . '" value="' . $values_values['products_options_values_id'] . '">' . $values_values['products_options_values_name'] . '</option>';

        // Move that ADOdb pointer!
        $values->MoveNext();
	} 
?>
								</select></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_SORT_ORDER_VALUE; ?></label>
                              <div class="col-lg-10"><input type="text" name="sort_order" value="<?php echo $attributes_values['options_sort_order'] ?? ''; ?>" size="4"></div>
                           </div>
                        </fieldset>					
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_OPT_PRICE; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('value_price', '0.00', 'onkeyup="updateWithTax()"'); ?></div>
                           </div>
                        </fieldset>			
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_OPT_PRICE_WITH_TAX; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('value_price_gross', '0.00', 'onkeyup="updateNet()"'); ?></div>
                           </div>
                        </fieldset>										
<?php
    if (BASE_PRICE == 'true') {
		$options_values_base_price = (!isset($attributes_values['options_values_base_price'])) ? 1 : $attributes_values['options_values_base_price'];
		$options_values_units_id = $attributes_values['options_values_units_id'] ?? ''; 
?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_QUANTITY; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('options_values_quantity', 1, 'OnKeyUp="calcBasePriceFactor()"'); ?>
                                 <?php # echo oos_draw_input_field('products_product_quantity', $pInfo->products_product_quantity, 'onkeyup="calcBasePriceFactor()"'); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_UNIT; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_pull_down_menu('options_values_units_id', '', $products_units_array, $options_values_units_id); ?>
								<?php # echo oos_draw_pull_down_menu('products_units_id', '', $products_units_array, $pInfo->products_units_id); ?>
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_BASE_QUANTITY; ?></label>
                              <div class="col-lg-10">
								<?php echo oos_draw_input_field('options_values_base_quantity', 1, 'OnKeyUp="calcBasePriceFactor()"'); ?>
								<?php # echo oos_draw_input_field('products_base_quantity', $pInfo->products_base_quantity, 'onkeyup="calcBasePriceFactor()"'); ?>
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
								<?php echo oos_draw_input_field('options_values_base_price', $options_values_base_price); ?>
								<?php # echo oos_draw_input_field('products_base_price', $pInfo->products_base_price); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
	} 
    if (DOWNLOAD_ENABLED == 'true') {
        $products_attributes_maxdays  = DOWNLOAD_MAX_DAYS;
        $products_attributes_maxcount = DOWNLOAD_MAX_COUNT; ?>	

						<div class="col-12 mt-3">
							<h2><?php echo TABLE_HEADING_DOWNLOAD; ?></h2>
						</div>
 
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_TEXT_FILENAME; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_attributes_filename', '', 'size="15"'); ?>
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_TEXT_MAX_DAYS; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_attributes_maxdays', $products_attributes_maxdays, 'size="5"'); ?>
                              </div>
                           </div>
                        </fieldset>		
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_TEXT_MAX_COUNT; ?></label>
                              <div class="col-lg-10">
                                  <?php echo oos_draw_input_field('products_attributes_maxcount', $products_attributes_maxcount, 'size="5"'); ?>
                              </div>
                           </div>
                        </fieldset>		
<?php
    } // end of DOWNLOAD_ENABLED section
}
?>	

					<div class="text-right mt-3 mb-3">
						<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&page=' . $categories_page) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
						<?php echo oos_reset_button(BUTTON_RESET); ?>
						<?php echo oos_submit_button(BUTTON_SAVE); ?>						
					</div>	

</form>
<!-- products_attributes_eof //-->
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
