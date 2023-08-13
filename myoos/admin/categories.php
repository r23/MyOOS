<?php
/** ---------------------------------------------------------------------

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
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');

require 'includes/main.php';

require 'includes/functions/function_categories.php';
require 'includes/classes/class_currencies.php';
require 'includes/classes/class_upload.php';

$currencies = new currencies();

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$cPath = filter_input(INPUT_GET, 'cPath', FILTER_SANITIZE_STRING) ?: $current_category_id; 
$cID = filter_input(INPUT_GET, 'cID', FILTER_VALIDATE_INT) ?: 0; 
$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;

$sSearch = (isset($_GET['search']) ? oos_prepare_input($_GET['search']) : '');

if (!empty($action)) {
    switch ($action) {
    case 'new_slave_product':
        $product_check = false;
        if (oos_is_not_null($_POST['slave_product_id'])) {
            //checks if the product actaully exists
            $check_product_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products'] . " WHERE products_id = " . intval($_POST['slave_product_id']) . " LIMIT 1");
            if ($check_product_result->RecordCount() == 1) {
                $product_check = true;
            }
            //checks if the product is already present
            $check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_POST['slave_product_id']) . " AND master_id = " . intval($_GET['pID']) . " LIMIT 1");
            if ($check_product_result->RecordCount() == 1) {
                $product_check = false;
            }
        }

        if ($product_check === true) {
            $sql_data_array = array('slave_id' => $_POST['slave_product_id'],
                                    'master_id' => $_GET['pID']);
            oos_db_perform($oostable['products_to_master'], $sql_data_array, 'INSERT');
            $messageStack->add_session(TEXT_SUCCESSFULLY_SLAVE, 'success');
        } else {
            $messageStack->add_session(TEXT_ERROR_SLAVE, 'error');
        }
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($_GET['pID']) . '&action=slave_products'));
        break;

    case 'slave_delete':
        $dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']) . " AND master_id = " . intval($_GET['master_id']) . " LIMIT 1");
        $check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']));
        if ($check_product_result->RecordCount() == 0) {
            $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = '1' WHERE products_id = " . intval($_GET['slave_id']));
        }
        $messageStack->add_session('Slave Deleted', 'success');
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($_GET['master_id']) . '&action=slave_products'));
        break;

    case 'slave_visible':
        $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = " . intval($_GET['visible']) . " WHERE products_id = " . intval($_GET['slave_id']));
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($_GET['master_id']) . '&action=slave_products'));
        break;

    case 'setflag':
        if (isset($_GET['flag']) && ($_GET['flag'] == '1') || ($_GET['flag'] == '2')) {
            if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
                oos_set_product_status($_GET['pID'], $_GET['flag']);
            } elseif (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
                oos_set_categories_status($_GET['cID'], $_GET['flag']);
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($_GET['pID']) . '&cID=' . intval($cID) . '&page=' . intval($nPage) . ((isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '')));
        break;

    case 'insert_category':
    case 'update_category':
        $nStatus = isset($_POST['categories_status']) ? intval($_POST['categories_status']) : 2;
        $color = isset($_POST['color']) ? oos_db_prepare_input($_POST['color']) : '';
        $menu_type  = isset($_POST['menu_type']) ? oos_db_prepare_input($_POST['menu_type']) : 'DEFAULT';
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 1;
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

        if (isset($_POST['categories_id'])) {
            $categories_id = oos_db_prepare_input($_POST['categories_id']);
        }

        if ((isset($_GET['cID'])) && (empty($categories_id))) {
            $categories_id = intval($_GET['cID']);
        }

        $sql_data_array = [];
        $sql_data_array = array('color' => oos_db_prepare_input($color),
                                'menu_type' => oos_db_prepare_input($menu_type),
                                'sort_order' => intval($sort_order));

        if ($action == 'insert_category') {
            $insert_sql_data = [];
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

            $categories_description = isset($_POST['categories_description'][$language_id]) ? oos_db_prepare_input($_POST['categories_description'][$language_id]) : '';
            $categories_description_meta = isset($_POST['categories_description_meta'][$language_id]) ? oos_db_prepare_input($_POST['categories_description_meta'][$language_id]) : '';

            if (empty($categories_description_meta) && !empty($categories_description)) {
                $categories_description_meta =  substr(strip_tags(preg_replace('!(\r\n|\r|\n)!', '', $categories_description)), 0, 250);
            }

            $categories_name = isset($_POST['categories_name'][$language_id]) ? oos_db_prepare_input($_POST['categories_name'][$language_id]) : '';
            $categories_page_title = isset($_POST['categories_page_title'][$language_id]) ? oos_db_prepare_input($_POST['categories_page_title'][$language_id]) : '';

            if (empty($categories_page_title) && !empty($categories_name)) {
                $categories_page_title =  strip_tags(preg_replace('!(\r\n|\r|\n)!', '', $categories_name));
            }

            $categories_heading_title = isset($_POST['categories_heading_title'][$language_id]) ? oos_db_prepare_input($_POST['categories_heading_title'][$language_id]) : $categories_page_title;
            $categories_facebook_title = isset($_POST['categories_facebook_title'][$language_id]) ? oos_db_prepare_input($_POST['categories_facebook_title'][$language_id]) : $categories_page_title;
            $categories_facebook_description = isset($_POST['categories_facebook_description'][$language_id]) ? oos_db_prepare_input($_POST['categories_facebook_description'][$language_id]) : $categories_description;
            $categories_twitter_title = isset($_POST['categories_twitter_title'][$language_id]) ? oos_db_prepare_input($_POST['categories_twitter_title'][$language_id]) : $categories_page_title;
            $categories_twitter_description = isset($_POST['categories_twitter_description'][$language_id]) ? oos_db_prepare_input($_POST['categories_twitter_description'][$language_id]) : $categories_description;

            $sql_data_array = array('categories_name' => $categories_name,
                                    'categories_page_title' => $categories_page_title,
                                    'categories_heading_title' => $categories_heading_title,
                                    'categories_description' => $categories_description,
                                    'categories_description_meta' => $categories_description_meta,
                                    'categories_facebook_title' => $categories_facebook_title,
                                    'categories_facebook_description' => $categories_facebook_description,
                                    'categories_twitter_title' => $categories_twitter_title,
                                    'categories_twitter_description' => $categories_twitter_description
                                    );

            if ($action == 'insert_category') {
                $insert_sql_data = array('categories_id' => intval($categories_id),
                                        'categories_languages_id' => intval($aLanguages[$i]['id']));

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);
                oos_db_perform($oostable['categories_description'], $sql_data_array);
            } elseif ($action == 'update_category') {
                oos_db_perform($oostable['categories_description'], $sql_data_array, 'UPDATE', 'categories_id = \'' . intval($categories_id) . '\' AND categories_languages_id = \'' . intval($language_id) . '\'');
            }
        }

        if ((isset($_POST['remove_image']) && ($_POST['remove_image'] == 'yes')) && (isset($_POST['categories_previous_image']))) {
            $categories_previous_image = oos_db_prepare_input($_POST['categories_previous_image']);

            $categoriestable = $oostable['categories'];
            $dbconn->Execute(
                "UPDATE $categoriestable
                            SET categories_image = NULL
                            WHERE categories_id = '" . intval($categories_id) . "'"
            );

            oos_remove_category_image($categories_previous_image);
        }

        if ((isset($_POST['remove_banner']) &&  ($_POST['remove_banner'] == 'yes')) && (isset($_POST['categories_previous_banner']))) {
            $categories_previous_banner = oos_db_prepare_input($_POST['categories_previous_banner']);

            $categoriestable = $oostable['categories'];
            $dbconn->Execute(
                "UPDATE $categoriestable
                            SET categories_banner = NULL
                            WHERE categories_id = '" . intval($categories_id) . "'"
            );

            oos_remove_category_banner($categories_previous_banner);
        }

        for ($i = 1, $n = $nImageCounter+1; $i < $n; $i++) {
            if (($_POST['remove_category_image'][$i] == 'yes') && (isset($_POST['categories_previous_large_image'][$i]))) {
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
                    'auto_orient' => true
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
            $dbconn->Execute(
                "UPDATE $categoriestable
                            SET categories_banner = '" . oos_db_input($oCategoriesBanner->filename) . "'
                            WHERE categories_id = '" . intval($categories_id) . "'"
            );
        }

        // Primary
        $options = array(
            'image_versions' => array(
            // The empty image version key defines options for the original image.
            // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
            // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                '' => array(
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true
                ),
                'large' => array(
                    // 'auto_orient' => TRUE,
                    // 'crop' => TRUE,
                    // 'jpeg_quality' => 82,
                    // 'no_cache' => TRUE, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                    // 'strip' => TRUE, (this strips EXIF tags, such as geolocation)
                    'max_width' => 1200, // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                    'max_height' => 1200, // either specify height, or set to 0. Then height is automatically adjusted - keeping aspect ratio to a specified max_width.
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
            $dbconn->Execute(
                "UPDATE $categoriestable
                            SET categories_image = '" . oos_db_input($oCategoriesImage->filename) . "'
                            WHERE categories_id = '" . intval($categories_id) . "'"
            );
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

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&cID=' . $categories_id));
        break;

    case 'delete_category_confirm':
        if (isset($_POST['categories_id']) && is_numeric($_POST['categories_id'])) {
            $categories_id = oos_db_prepare_input($_POST['categories_id']);

            $categories = oos_get_category_tree($categories_id, '', '0', '', true);
            $products = [];
            $products_delete = [];

            for ($i = 0, $n = count($categories); $i < $n; $i++) {
                $product_ids_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . intval($categories[$i]['id']) . "'");
                while ($product_ids = $product_ids_result->fields) {
                    $products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];

                    // Move that ADOdb pointer!
                    $product_ids_result->MoveNext();
                }
            }

            reset($products);
            foreach ($products as $key => $value) {
                $category_ids = '';
                for ($i = 0, $n = count($value['categories']); $i < $n; $i++) {
                    $category_ids .= '\'' . $value['categories'][$i] . '\', ';
                }
                $category_ids = substr($category_ids, 0, -2);

                $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($key) . "' AND categories_id not in (" . $category_ids . ")");
                $check = $check_result->fields;
                if ($check['total'] < '1') {
                    $products_delete[$key] = $key;
                }
            }

            for ($i = 0, $n = count($categories); $i < $n; $i++) {
                category_move_to_trash($categories[$i]['id']);
            }

            foreach ($products_delete as $key) {
                product_move_to_trash($key);
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage)));
        break;

    case 'delete_product_confirm':
        if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
            $product_id = oos_db_prepare_input($_POST['products_id']);

            product_move_to_trash($product_id);
        }
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage)));
        break;

    case 'move_category_confirm':
        if (isset($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id'])) {
            $categories_id = oos_db_prepare_input($_POST['categories_id']);
            $new_parent_id = oos_db_prepare_input($_POST['move_to_category_id']);

            $dbconn->Execute("UPDATE " . $oostable['categories'] . " SET parent_id = '" . intval($new_parent_id) . "', last_modified = now() WHERE categories_id = '" . intval($categories_id) . "'");
        }
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $new_parent_id . '&page=' . intval($nPage) . '&cID=' . $categories_id));
        break;

    case 'move_product_confirm':
        $products_id = oos_db_prepare_input($_POST['products_id']);
        $new_parent_id = oos_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($products_id) . "' and categories_id = '" . intval($new_parent_id) . "'");
        $duplicate_check = $duplicate_check_result->fields;
        if ($duplicate_check['total'] < 1) {
            $dbconn->Execute("UPDATE " . $oostable['products_to_categories'] . " SET categories_id = '" . intval($new_parent_id) . "' WHERE products_id = '" . intval($products_id) . "' and categories_id = '" . intval($current_category_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $new_parent_id . '&page=' . intval($nPage) . '&pID=' . $products_id));
        break;

    case 'copy_to_confirm':
        if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
            $products_id = oos_db_prepare_input($_POST['products_id']);
            $categories_id = oos_db_prepare_input($_POST['categories_id']);

            if ($_POST['copy_as'] == 'link') {
                if ($_POST['categories_id'] != $current_category_id) {
                    $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($products_id) . "' and categories_id = '" . intval($categories_id) . "'");
                    $check = $check_result->fields;
                    if ($check['total'] < '1') {
                        $dbconn->Execute("INSERT INTO " . $oostable['products_to_categories'] . " (products_id, categories_id) VALUES ('" . intval($products_id) . "', '" . intval($categories_id) . "')");
                    }
                } else {
                    $messageStack->add_session(ERROR_CANNOT_LINK_TO_SAME_CATEGORY, 'error');
                }
            } elseif ($_POST['copy_as'] == 'duplicate') {
                $product_result = $dbconn->Execute(
                    "SELECT products_quantity, products_reorder_level, products_model,
														products_replacement_product_id, products_ean, products_image,
														products_average_rating, products_price, products_base_price, 
														products_product_quantity, products_base_quantity, products_base_unit,														
                                                       products_date_available, products_weight, products_status,
													   products_setting, products_tax_class_id,
                                                       products_units_id, products_old_electrical_equipment, products_used_goods, 
													   manufacturers_id, products_price_list,
                                                       products_quantity_order_min, products_quantity_order_max,
                                                       products_quantity_order_units, products_discount1, products_discount2,
                                                       products_discount3, products_discount4, products_discount1_qty,
                                                       products_discount2_qty, products_discount3_qty, products_discount4_qty,
                                                       products_discounts_id, products_slave_visible, products_sort_order
													FROM " . $oostable['products'] . "
													WHERE products_id = '" . oos_db_input($products_id) . "'"
                );
                $product = $product_result->fields;

                $dbconn->Execute(
                    "INSERT INTO " . $oostable['products'] . "
                         (products_quantity,
                          products_reorder_level,
                          products_model,
						  products_replacement_product_id,
                          products_ean,
                          products_image,
						  products_average_rating,
                          products_price,
                          products_base_price,
                          products_product_quantity,
                          products_base_quantity,					  
                          products_base_unit,
                          products_date_added,
                          products_date_available,
                          products_weight,
                          products_status,
						  products_setting,
                          products_tax_class_id,
                          products_units_id,
						  products_old_electrical_equipment,
						  products_used_goods,
                          manufacturers_id,
                          products_price_list,
                          products_quantity_order_min,
						  products_quantity_order_max,
                          products_quantity_order_units,
                          products_discount1,
                          products_discount2,
                          products_discount3,
                          products_discount4,
                          products_discount1_qty,
                          products_discount2_qty,
                          products_discount3_qty,
                          products_discount4_qty,
                          products_discounts_id,
                          products_slave_visible,
                          products_sort_order)
                          VALUES ('" . $product['products_quantity'] . "',
                                  '" . $product['products_reorder_level'] . "',
                                  '" . $product['products_model'] . "',
								  '" . $product['products_replacement_product_id'] . "',
                                  '" . $product['products_ean'] . "',
                                  '" . $product['products_image'] . "',
								  '" . $product['products_average_rating'] . "',
                                  '" . $product['products_price'] . "',
                                  '" . $product['products_base_price'] . "',
                                  '" . $product['products_product_quantity'] . "',
                                  '" . $product['products_base_quantity'] . "',								  								  
                                  '" . $product['products_base_unit'] . "',
                                  now(),
								  '" . (empty($product['products_date_available']) ? null : "'" . oos_db_input($product['products_date_available']) . "'") . "',
                                  '" . $product['products_weight'] . "',
                                  '" . $product['products_status'] . "',
                                  '3',
                                  '" . $product['products_tax_class_id'] . "',
                                  '" . $product['products_units_id'] . "',
								  '" . $product['products_old_electrical_equipment'] . "',
								  '" . $product['products_used_goods'] . "',
                                  '" . $product['manufacturers_id'] . "',
                                  '" . $product['products_price_list'] . "',
                                  '" . $product['products_quantity_order_min'] . "',
								  '" . $product['products_quantity_order_max'] . "',
                                  '" . $product['products_quantity_order_units'] . "',
                                  '" . $product['products_discount1'] . "',
                                  '" . $product['products_discount2'] . "',
                                  '" . $product['products_discount3'] . "',
                                  '" . $product['products_discount4'] . "',
                                  '" . $product['products_discount1_qty'] . "',
                                  '" . $product['products_discount2_qty'] . "',
                                  '" . $product['products_discount3_qty'] . "',
                                  '" . $product['products_discount4_qty'] . "',
                                  '" . $product['products_discounts_id'] . "',
                                  '" . $product['products_slave_visible'] . "',
                                  '" . $product['products_sort_order'] . "')"
                );
                $dup_products_id = $dbconn->Insert_ID();
                $description_result = $dbconn->Execute("SELECT products_languages_id, products_name, products_title, products_description, products_short_description, products_essential_characteristics, products_old_electrical_equipment_description, products_used_goods_description, products_url, products_description_meta   FROM " . $oostable['products_description'] . " WHERE products_id = '" . oos_db_input($products_id) . "'");
                while ($description = $description_result->fields) {
                    $dbconn->Execute(
                        "INSERT INTO " . $oostable['products_description'] . "
									(products_id,
									products_languages_id,
									products_name,
									products_title,
									products_description,
									products_short_description,
									products_essential_characteristics,
									products_old_electrical_equipment_description,
									products_used_goods_description,
									products_url,
									products_viewed,
									products_description_meta)
									VALUES ('" . intval($dup_products_id) . "',
											'" . intval($description['products_languages_id']) . "',
											'" . oos_db_input($description['products_name']) . "',
											'" . oos_db_input($description['products_title']) . "',
											'" . oos_db_input($description['products_description']) . "',
											'" . oos_db_input($description['products_short_description']) . "',
											'" . oos_db_input($description['products_essential_characteristics']) . "',
											'" . oos_db_input($description['products_old_electrical_equipment_description']) . "',
											'" . oos_db_input($description['products_used_goods_description']) . "',
											'" . oos_db_input($description['products_url']) . "',
											'0',
											'" . oos_db_input($description['products_description_meta']). "')"
                    );

                    // Move that ADOdb pointer!
                    $description_result->MoveNext();
                }

                $products_id_from = oos_db_input($products_id);
                $products_id_to = $dup_products_id;
                $products_id = $dup_products_id;

                $dbconn->Execute(
                    "INSERT INTO " . $oostable['products_to_categories'] . "
                          (products_id,
                           categories_id)
                           VALUES ('" . intval($dup_products_id) . "',
                                   '" . intval($categories_id) . "')"
                );

                $products_images_copy_result= $dbconn->Execute("SELECT image_name, sort_order FROM " . $oostable['products_gallery'] . " WHERE products_id='" . intval($products_id_from) . "'");
                while ($products_images_copy = $products_images_copy_result->fields) {
                    $sql = "INSERT INTO " . $oostable['products_gallery'] . "
							(products_id,
							image_name,
							sort_order)
							VALUES ('" . intval($products_id_to) . "',
									'" . $products_images_copy['image_name'] . "',
									'" . $products_images_copy['sort_order'] . "')";
                    $dbconn->Execute($sql);

                    // Move that ADOdb pointer!
                    $products_images_copy_result->MoveNext();
                }

                if ($_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate') {
                    $products_copy_from_result= $dbconn->Execute("SELECT options_id,  options_values_model, options_values_image, options_values_id, options_values_status, options_values_price, options_values_quantity, options_values_base_price, options_values_base_quantity, options_values_base_unit, options_values_units_id, price_prefix, options_sort_order FROM " . $oostable['products_attributes'] . " WHERE products_id='" . intval($products_id_from) . "'");
                    while ($products_copy_from = $products_copy_from_result->fields) {
                        $sql = "INSERT INTO " . $oostable['products_attributes'] . "
							(products_id,
							options_id,
							options_values_model,
							options_values_image,
							options_values_id,
							options_values_status,
							options_values_price,
							options_values_quantity,
							options_values_base_price,
							options_values_base_quantity,
							options_values_base_unit,
							options_values_units_id,
							price_prefix,
							options_sort_order)
							VALUES ('" . intval($products_id_to) . "',
									'" . $products_copy_from['options_id'] . "',
									'" . $products_copy_from['options_values_model'] . "',
									'" . $products_copy_from['options_values_image'] . "',
									'" . $products_copy_from['options_values_id'] . "',
									'" . $products_copy_from['options_values_status'] . "',
									'" . $products_copy_from['options_values_price'] . "',
									'" . $products_copy_from['options_values_quantity'] . "',
									'" . $products_copy_from['options_values_base_price'] . "',
									'" . $products_copy_from['options_values_base_quantity'] . "',
									'" . $products_copy_from['options_values_base_unit'] . "',
									'" . $products_copy_from['options_values_units_id'] . "',	
									'" . $products_copy_from['price_prefix'] . "',
									'" . $products_copy_from['options_sort_order'] . "')";
                        $dbconn->Execute($sql);

                        // Move that ADOdb pointer!
                        $products_copy_from_result->MoveNext();
                    }
                }

                // products_model_viewer
                $products_model_viewer_copy_result = $dbconn->Execute("SELECT model_viewer_id, products_id, model_viewer_glb, model_viewer_usdz, model_viewer_background_color, model_viewer_auto_rotate, model_viewer_scale, model_viewer_hdr FROM " . $oostable['products_model_viewer'] . " WHERE products_id='" . intval($products_id_from) . "'");
                if ($products_model_viewer_copy_result->RecordCount()) {
                    $products_models_copy = $products_model_viewer_copy_result->fields;
                    $sql = "INSERT INTO " . $oostable['products_model_viewer'] . "
								(products_id,
								model_viewer_glb,
								model_viewer_usdz,
								model_viewer_background_color,
								model_viewer_auto_rotate,
								model_viewer_scale,
								model_viewer_hdr,
								model_viewer_date_added
								VALUES ('" . intval($products_id_to) . "',
										'" . $products_models_copy['model_viewer_glb'] . "',
										'" . $products_models_copy['model_viewer_usdz'] . "',
										'" . $products_models_copy['model_viewer_background_color'] . "',
										'" . $products_models_copy['model_viewer_auto_rotate'] . "',
										'" . $products_models_copy['model_viewer_scale'] . "',									
										'" . $products_models_copy['model_viewer_hdr']. "
										now())";
                    $dbconn->Execute($sql);
                    $dup_model_viewer_id = $dbconn->Insert_ID();

                    $model_viewer_description_result = $dbconn->Execute("SELECT model_viewer_languages_id, model_viewer_title, model_viewer_description, model_viewer_viewed, model_viewer_keywords FROM " . $oostable['products_model_viewer_description'] . " WHERE model_viewer_id = '" . intval($products_models_copy['model_viewer_id']) . "'");
                    while ($description = $model_viewer_description_result->fields) {
                        $dbconn->Execute(
                            "INSERT INTO " . $oostable['products_model_viewer_description'] . "
										(model_viewer_id,
										model_viewer_languages_id,
										model_viewer_title,
										model_viewer_description,
										model_viewer_viewed,
										model_viewer_keywords)
										VALUES ('" . intval($dup_model_viewer_id) . "',
												'" . intval($description['model_viewer_languages_id']) . "',
												'" . oos_db_input($description['model_viewer_title']) . "',
												'" . oos_db_input($description['model_viewer_description']) . "',
												'" . oos_db_input($description['model_viewer_viewed']) . "',
												'" . oos_db_input($description['model_viewer_keywords']). "')"
                        );

                        // Move that ADOdb pointer!
                        $model_viewer_description_result->MoveNext();
                    }
                }

                // product_webgl_gltf
                $products_model_copy_result = $dbconn->Execute("SELECT models_id, products_id, models_webgl_gltf, models_author, models_author_url, models_camera_pos, models_object_rotation, models_add_lights, models_add_ground, models_shadows, models_add_env_map, models_extensions, models_hdr FROM " . $oostable['products_models'] . " WHERE products_id='" . intval($products_id_from) . "'");
                if ($products_model_copy_result->RecordCount()) {
                    $products_models_copy = $products_model_copy_result->fields;
                    $sql = "INSERT INTO " . $oostable['products_models'] . "
								(products_id,
								models_webgl_gltf,
								models_author,
								models_author_url,
								models_camera_pos,
								models_object_rotation,
								models_add_lights,
								models_add_ground,
								models_shadows,
								models_add_env_map,
								models_extensions,
								models_hdr,							
								models_date_added
								VALUES ('" . intval($products_id_to) . "',
										'" . $products_models_copy['models_webgl_gltf'] . "',
										'" . $products_models_copy['models_author'] . "',
										'" . $products_models_copy['models_author_url'] . "',
										'" . $products_models_copy['models_camera_pos'] . "',
										'" . $products_models_copy['models_object_rotation'] . "',	
										'" . $products_models_copy['models_add_lights'] . "',
										'" . $products_models_copy['models_add_ground'] . "',
										'" . $products_models_copy['models_shadows'] . "',
										'" . $products_models_copy['models_add_env_map'] . "',	
										'" . $products_models_copy['models_extensions'] . "',
										'" . $products_models_copy['models_hdr'] . "',						
										now())";
                    $dbconn->Execute($sql);
                    $dup_models_id = $dbconn->Insert_ID();

                    $models_description_result = $dbconn->Execute("SELECT models_languages_id, models_name, models_title, models_viewed, models_description_meta, models_keywords FROM " . $oostable['products_models_description'] . " WHERE models_id = '" . intval($products_models_copy['models_id']) . "'");
                    while ($description = $models_description_result->fields) {
                        $dbconn->Execute(
                            "INSERT INTO " . $oostable['products_models_description'] . "
										(models_id,
										models_languages_id,
										models_name,
										models_title,
										models_viewed,
										models_description_meta,
										models_keywords)
										VALUES ('" . intval($dup_models_id) . "',
												'" . intval($description['models_languages_id']) . "',
												'" . oos_db_input($description['models_name']) . "',
												'" . oos_db_input($description['models_title']) . "',
												'" . oos_db_input($description['models_viewed']) . "',
												'" . oos_db_input($description['models_description_meta']) . "',
												'" . oos_db_input($description['models_keywords']). "')"
                        );

                        // Move that ADOdb pointer!
                        $models_description_result->MoveNext();
                    }
                }

                // products_video
                $products_video_copy_result = $dbconn->Execute("SELECT video_id, products_id, video_source, video_poster, video_preload, video_data_setup FROM " . $oostable['products_video'] . " WHERE products_id='" . intval($products_id_from) . "'");
                if ($products_video_copy_result->RecordCount()) {
                    $products_video_copy = $products_video_copy_result->fields;
                    $sql = "INSERT INTO " . $oostable['products_video'] . "
								(products_id,
								video_source,
								video_poster,
								video_preload,
								video_data_setup,
								video_date_added)
								VALUES ('" . intval($products_id_to) . "',
										'" . $products_video_copy['video_source'] . "',
										'" . $products_video_copy['video_poster'] . "',
										'" . $products_video_copy['video_preload'] . "',
										'" . $products_video_copy['video_data_setup'] . "',
										now())";
                    $result = $dbconn->Execute($sql);
                    $dup_video_viewer_id = $dbconn->Insert_ID();
                    $products_video_description_result = $dbconn->Execute("SELECT video_languages_id, video_title, video_description, video_viewed FROM " . $oostable['products_video_description'] . " WHERE video_id = '" . intval($products_video_copy['video_id']) . "'");
                    while ($description = $products_video_description_result->fields) {
                        $dbconn->Execute(
                            "INSERT INTO " . $oostable['products_video_description'] . "
										(video_id,
										video_languages_id,
										video_title,
										video_description,
										video_viewed)
										VALUES ('" . intval($dup_video_viewer_id) . "',
												'" . intval($description['video_languages_id']) . "',
												'" . oos_db_input($description['video_title']) . "',
												'" . oos_db_input($description['video_description']) . "',
												'" . oos_db_input($description['video_viewed']). "')"
                        );

                        // Move that ADOdb pointer!
                        $products_video_description_result->MoveNext();
                    }
                }
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $categories_id . '&page=' . intval($nPage) . '&pID=' . $products_id));
        break;
    }
}



$cPath_back = '';
if (isset($aPath) && is_array($aPath) && count($aPath) > 0) {
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
    if (!is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
        $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
    }
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
                       'categories_facebook_title' => '',
                       'categories_facebook_description' => '',
                       'categories_twitter_title' => '',
                       'categories_twitter_description' => '',
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
                         cd.categories_description, cd.categories_description_meta, cd.categories_facebook_title, 
						 cd.categories_facebook_description, cd.categories_twitter_title, cd.categories_twitter_description, 
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
                                                        'sort_order' => $categories_images['sort_order']);
            // Move that ADOdb pointer!
            $categories_images_result->MoveNext();
        }
    }

    $aLanguages = oos_get_languages();
    $nLanguages = count($aLanguages);

    $text_new_or_edit = ($action=='new_category') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;

    $aSetting = [];
    $settingstable = $oostable['setting'];
    $setting_result = $dbconn->Execute("SELECT setting_id, setting_name FROM $settingstable WHERE setting_languages_id = '" . intval($_SESSION['language_id']) . "'");
    while ($setting = $setting_result->fields) {
        $aSetting[] = array('id' => $setting['setting_id'],
                         'text' => $setting['setting_name']);
        // Move that ADOdb pointer!
        $setting_result->MoveNext();
    }

    $aColor = [];
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
    } ?>
<script src="js/ckeditor/ckeditor.js"></script>
    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)); ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                </li>
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
    echo oos_draw_form('fileupload', 'new_category', $aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . (isset($_GET['cID']) ? '&cID=' . $cID : '') . '&action=' . $form_action, 'post', true, 'enctype="multipart/form-data"');
    echo oos_draw_hidden_field('parent_id', $cInfo->parent_id);
    echo oos_hide_session_id(); ?>

               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified" id="myTab">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo TEXT_CATEGORY; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#data" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_DATA; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#social" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_SOCIAL; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#picture" aria-controls="picture" role="tab" data-toggle="tab"><?php echo TEXT_IMAGES; ?></a>
                     </li>
                  </ul>
                  <div class="tab-content">
                    <div class="text-right mt-3 mb-3">   
                        <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>        
                        <?php echo oos_reset_button(BUTTON_RESET); ?>
                        <?php echo oos_submit_button(BUTTON_SAVE); ?>                        
                    </div>                  
                    <div class="tab-pane active" id="edit" role="tabpanel">


    <?php
    for ($i = 0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_EDIT_CATEGORIES_NAME;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('categories_name[' . $aLanguages[$i]['id'] . ']', (empty($cInfo->categories_id) ? '' : oos_get_category_name($cInfo->categories_id, $aLanguages[$i]['id'])), '', false, 'text', true, false, ''); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_EDIT_CATEGORIES_PAGE_TITLE;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('categories_page_title[' . $aLanguages[$i]['id'] . ']', (empty($cInfo->categories_id) ? '' : oos_get_categories_page_title($cInfo->categories_id, $aLanguages[$i]['id'])), '', false, 'text', true, false, ''); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_EDIT_CATEGORIES_HEADING_TITLE;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('categories_heading_title[' . $aLanguages[$i]['id'] . ']', (empty($cInfo->categories_id) ? '' : oos_get_category_heading_title($cInfo->categories_id, $aLanguages[$i]['id'])), '', false, 'text', true, false, ''); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_EDIT_CATEGORIES_DESCRIPTION;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_editor_field('categories_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '15', (empty($cInfo->categories_id) ? '' : oos_get_category_description($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
            <script>
                CKEDITOR.replace( 'categories_description[<?php echo $aLanguages[$i]['id']; ?>]');
            </script>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_EDIT_CATEGORIES_DESCRIPTION_META;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_textarea_field('categories_description_meta[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (empty($cInfo->categories_id) ? '' : oos_get_category_description_meta($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    } ?>
                     </div>
                     <div class="tab-pane" id="data" role="tabpanel">
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">ID:</label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('categories_id', $cInfo->categories_id, '', false, 'text', true, true, ''); ?></div>
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
        if ($cInfo->color == $v) {
            echo ' checked="checked"';
        }
        echo  '>'; ?>
                                        <?php echo '<span class="' . $v . '">' . TEXT_CATEGORY . '</span>'; ?>
                                    </label>
                                </div>
        <?php
    } ?>                                
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
    if ($cInfo->menu_type == 'NEW') {
        echo ' checked="checked"';
    }
    echo  '>&nbsp;'; ?>
                                        <span class="badge badge-danger float-right"><?php echo TEXT_EDIT_NEW; ?></span>
                                    </label>
                                </div>
                                <div class="c-radio c-radio-nofont">
                                    <label>
                                        <?php
                                            echo '<input type="radio" name="menu_type" value="PROMO"';
    if ($cInfo->menu_type == 'PROMO') {
        echo ' checked="checked"';
    }
    echo  '>&nbsp;'; ?>
                                        <span class="badge badge-success float-right"><?php echo TEXT_EDIT_PROMO; ?></span>
                                    </label>
                                </div>
                                
                                
                                
                            </div>
                        </div>
                        
                     </div>
                     
                    <div class="tab-pane" id="social" role="tabpanel">

                        <div class="col-12 mt-3">
                            <h2><?php echo TEXT_HEADER_FACEBOOK; ?></h2>
                        </div>


    <?php
    for ($i = 0; $i < count($aLanguages); $i++) {
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
                                <?php echo oos_draw_input_field('categories_facebook_title[' . $aLanguages[$i]['id'] . ']', (empty($cInfo->categories_id) ? '' : oos_get_categories_facebook_title($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
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
                                <?php echo oos_draw_textarea_field('categories_facebook_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (empty($cInfo->categories_id) ? '' : oos_get_categories_facebook_description($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
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
    for ($i = 0; $i < count($aLanguages); $i++) {
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
                                <?php echo oos_draw_input_field('categories_twitter_title[' . $aLanguages[$i]['id'] . ']', (empty($cInfo->categories_id) ? '' : oos_get_categories_twitter_title($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i = 0; $i < count($aLanguages); $i++) {
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
                                <?php echo oos_draw_textarea_field('categories_twitter_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '2', (empty($cInfo->categories_id) ? '' : oos_get_categories_twitter_description($cInfo->categories_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    } ?>


                     </div>

                     <div class="tab-pane" id="picture" role="tabpanel">
    <script>
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
    } ?>    
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
    } ?>
            </div>
            <div class="col-9">
                <?php echo TEXT_INFO_BANNER; ?>
            </div>
        </div>        
    <?php
    $nCounter = 0;
    if (isset($cInfo->categories_larger_images) && (is_array($cInfo->categories_larger_images) || is_object($cInfo->categories_larger_images))) {
        foreach ($cInfo->categories_larger_images as $image) {
            $nCounter++; ?>

        <div class="row mb-3 pb-3 bb">
            <div class="col-6 col-md-3">

            <?php
            echo '<div class="text-center"><div class="d-block" style="width: 200px; height: 150px;">';
            echo oos_info_image('category/medium/' .  $image['image'], $cInfo->categories_name);
            echo '</div></div>';

            echo $image['image'];

            echo oos_draw_hidden_field('categories_previous_large_image['. $nCounter . ']', $image['image']);
            echo '<br>';
            echo oos_draw_checkbox_field('remove_category_image['. $nCounter . ']', 'yes') . ' ' . TEXT_IMAGE_REMOVE; ?>
            </div>
            <div class="col-9">
                <strong><?php echo TEXT_INFO_SLIDER; ?></strong>
            </div>    
        </div>
            <?php
        }
    }
    echo oos_draw_hidden_field('image_counter', $nCounter); ?>    
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
                <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
                <?php echo oos_reset_button(BUTTON_RESET); ?>
                <?php echo oos_submit_button(BUTTON_SAVE); ?>
            </div>
        </form>
    </div>

</div>
<!-- body_text_eof //-->
    <?php
} else {
        $image_icon_status_array = [];
        $image_icon_status_array = array(array('id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE));
        $image_icon_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM " . $oostable['products_status'] . " WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
        while ($image_icon_status = $image_icon_status_result->fields) {
            $image_icon_status_array[] = array('id' => $image_icon_status['products_status_id'],
                                        'text' => $image_icon_status['products_status_name']);

            // Move that ADOdb pointer!
            $image_icon_status_result->MoveNext();
        } ?>

    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo HEADING_TITLE; ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP; ?></a>
                </li>
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG; ?></a>
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
        echo((isset($aPath) && count($aPath) > 1) ? '<a href="' . oos_href_link_admin($aContents['categories'], $cPath_back . 'cID=' . $current_category_id) . '">' . oos_button('<i class="fa fa-chevron-left"></i> ' . BUTTON_BACK) . '</a> ' : '') .
        '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&action=new_category') . '">' . oos_button('<i class="fa fa-plus"></i> ' . IMAGE_NEW_CATEGORY) . '</a> ' .
        '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . oos_prepare_input($cPath) . '&action=new_product') . '">' . oos_button('<i class="fa fa-plus"></i> ' . IMAGE_NEW_PRODUCT) . '</a>'; ?>
            </div>
        </div>
        <?php
    } ?>

            <div class="row">
                <div class="col-sm-12">
                    <?php echo oos_draw_form('id', 'search', $aContents['categories'], '', 'get', false, 'class="form-inline"'); ?>
                        <div id="DataTables_Table_0_filter" class="dataTables_filter">
                            <label><?php echo HEADING_TITLE_SEARCH; ?></label>
                            <?php echo oos_draw_input_field('search', $sSearch); ?>
                        </div>
                    </form>
                    <?php echo oos_draw_form('id', 'goto', $aContents['categories'], '', 'get', false, 'class="form-inline"'); ?>
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
            if (isset($_GET['search'])) {
                $cPath = $categories['parent_id'];
            }

            if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                $category_childs = array('childs_count' => oos_childs_in_category_count($categories['categories_id']));
                $category_products = array('products_count' => oos_products_in_category_count($categories['categories_id']));

                $cInfo_array = array_merge($categories, $category_childs, $category_products);
                $cInfo = new objectInfo($cInfo_array);
            }

            if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id)) {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '\'">' . "\n";
            } else {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
            } ?>
                <td>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-folder"></i></button></a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="text-center">&nbsp;</td>
                 <td class="text-center">
        <?php
        if ($categories['categories_status'] == 2) {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=2&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
                <td class="text-center">&nbsp;<?php echo $categories['sort_order']; ?>&nbsp;</td>
                <td class="text-right"><?php echo
                '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories['categories_id'] . '&page=' . intval($nPage) . '&action=edit_category') . '"><i class="fas fa-pencil-alt" title="' . BUTTON_EDIT . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories['categories_id'] . '&page=' . intval($nPage) . '&action=delete_category') . '"><i class="fa fa-trash" title="' . BUTTON_DELETE . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories['categories_id'] . '&page=' . intval($nPage) . '&action=move_category') . '"><i class="fa fa-share" title="' .  BUTTON_MOVE  . '"></i></a>
					<a href="' . oos_href_link_admin($aContents['categories_panorama'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $categories['categories_id'] . '&page=' . intval($nPage) . '&action=panorama') . '"><i class="fa fa-street-view" title="' .  BUTTON_PANORAMA  . '"></i></a>'; ?>                &nbsp;</td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $categories_result->MoveNext();
        }


        $products_count = 0;
        if (isset($_GET['search'])) {
            $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_tax_class_id, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_setting, p2c.categories_id, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_max, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id AND products_setting != 0 AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND p.products_id = p2c.products_id AND pd.products_name like '%" . oos_db_input($_GET['search']) . "%' OR p.products_model like '%" . oos_db_input($_GET['search']) . "%' ORDER BY pd.products_name");
        } else {
            $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price,p.products_base_price, p.products_base_unit, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.products_setting, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_max, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id AND products_setting != 0 AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND p.products_id = p2c.products_id and p2c.categories_id = '" . intval($current_category_id) . "' ORDER BY pd.products_name");
        }

        while ($products = $products_result->fields) {
            $products_count++;
            $rows++;

            // Get categories_id for product if search
            if (isset($_GET['search'])) {
                $cPath=$products['categories_id'];
            }

            if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo)  && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
                // find out the rating average from customer reviews
                $reviews_result = $dbconn->Execute("SELECT (avg(reviews_rating) / 5 * 100) as average_rating FROM " . $oostable['reviews'] . " WHERE products_id = '" . $products['products_id'] . "'");
                $reviews = $reviews_result->fields;
                $pInfo_array = array_merge($products, $reviews);
                $pInfo = new objectInfo($pInfo_array);
            }

            if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
                echo '              <tr>' . "\n";
            } else {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products['products_id']) . '\'">' . "\n";
            } ?>
                <td><?php echo '<a href="' . oos_catalog_link($aCatalog['product_info'], 'products_id=' . $products['products_id']) . '" target="_blank" rel="noopener"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . '#' . $products['products_id'] . ' ' . $products['products_name']; ?></td>
                <td><?php echo oos_get_manufacturers_name($products['products_id']) ?></td>
                <td class="text-center">
        <?php
        if ($products['products_setting'] == 2) {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=1&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=2&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
                <td class="text-center"><?php echo $products['products_sort_order']; ?></td>
                <td class="text-right"><?php echo
                        '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=edit_product') . '"><i class="fas fa-pencil-alt" title="' .  BUTTON_EDIT . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=delete_product') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=move_product') . '"><i class="fa fa-share" title="' . BUTTON_MOVE . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=copy_to') . '"><i class="fa fa-copy" title="' . IMAGE_COPY_TO . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=slave_products') . '"><i class="fa fa-sticky-note" title="' . IMAGE_SLAVE . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['product_video'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=edit_video') . '"><i class="fas fa-film" title="' .  BUTTON_VIDEO . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['product_model_viewer'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=edit_3d') . '"><i class="fas fa-hand-spock" title="' .  BUTTON_AR . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['product_webgl_gltf'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products['products_id']) . '&action=edit_3d') . '"><i class="fa fa-cube" title="' .  BUTTON_CUBE . '"></i></a>'; ?>&nbsp;</td>
              </tr>
        <?php
        // Move that ADOdb pointer!
        $products_result->MoveNext();
        } ?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br>' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
    <?php
    $heading = [];
        $contents = [];

        switch ($action) {
    case 'slave_products':
        $heading[] = array('text' => '<b>' . oos_get_products_name($pInfo->products_id, $_SESSION['language_id']) . '</b>');

        $contents = array('form' => oos_draw_form('id', 'new_slave_product', $aContents['categories'], 'action=new_slave_product&cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id, 'post', false, 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br>' . TEXT_ADD_SLAVE_PRODUCT . '<br>' . oos_draw_input_field('slave_product_id', '', 'size="10"'));
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_SAVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');


        $contents[] = array('text' => '<br>' . TEXT_CURRENT_SLAVE_PRODUCTS);
        $slave_table_result = $dbconn->Execute("SELECT p2m.master_id, p2m.slave_id FROM " . $oostable['products_to_master'] . " p2m WHERE master_id = '" . $pInfo->products_id . "'");
        while ($slave_table = $slave_table_result->fields) {
            $slave_products_result = $dbconn->Execute("SELECT p.products_id, p.products_slave_visible, pd.products_name FROM " . $oostable['products'] . " p , " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND p.products_id = '" . $slave_table['slave_id'] . "' AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name LIMIT 1");
            $slave_products = $slave_products_result->fields;
            if ($slave_products['products_slave_visible'] == 1) {
                $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
                '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action')) . 'visible=0&slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_visible') . '">'.
                oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT) . '</a>');
            } else {
                $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
                '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action')) . 'visible=1&slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_visible') . '">'.
                oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT) . '</a>');
            }
            // Move that ADOdb pointer!
            $slave_table_result->MoveNext();
        }
        break;

    case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=delete_category_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) {
            $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        }
        if ($cInfo->products_count > 0) {
            $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        }
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=move_category_confirm', 'post', false) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br>' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_MOVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('id', 'products', $aContents['categories'], 'action=delete_product_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_MOVE_TRASH) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('id', 'products', $aContents['categories'], 'action=move_product_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br>' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_MOVE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => oos_draw_form('id', 'copy_to', $aContents['categories'], 'action=copy_to_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br>' . TEXT_INFO_CURRENT_CATEGORIES . '<br><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_CATEGORIES . '<br>' . oos_draw_pull_down_menu('categories_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br>' . TEXT_HOW_TO_COPY . '<br>' . oos_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br>' . oos_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('text' => '<br>' . oos_image(OOS_IMAGES . 'pixel_black.gif', '', '100%', '3'));
        $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES_ONLY);
        $contents[] = array('text' => '<br>' . TEXT_COPY_ATTRIBUTES . '<br>' . oos_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br>' . oos_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_COPY) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    default:
        if ($rows > 0) {
            if (isset($cInfo) && is_object($cInfo)) { // category info box contents
                $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . oos_button(BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . oos_button(BUTTON_MOVE) . '</a>');
                $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cID) . '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
                if (oos_is_not_null($cInfo->last_modified)) {
                    $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
                }
                $contents[] = array('text' => '<br>' . oos_info_image('category/medium/' . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
                $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
            } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
                $heading[] = array('text' => '<b>' . $pInfo->products_name . '</b>');

                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . oos_button(BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . oos_button(BUTTON_MOVE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . oos_button(IMAGE_COPY_TO) . '</a>');
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=slave_products') . '">' . oos_button(IMAGE_SLAVE) . '</a>');

                if (defined('MIN_DISPLAY_NEW_SPECILAS')) {
                    $productstable = $oostable['products'];
                    $specialstable = $oostable['specials'];
                    $query = "SELECT p.products_tax_class_id, p.products_id, s.specials_id, s.specials_new_products_price,
                               s.expires_date, s.status
                         FROM $productstable p,
                              $specialstable s
                        WHERE s.status = '1' AND
                              p.products_id = s.products_id AND
                              s.products_id = '" . $pInfo->products_id . "'";
                    $specials_result = $dbconn->Execute($query);
                    if (!$specials_result->RecordCount()) {
                        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'pID=' . $pInfo->products_id . '&action=new') . '">' . oos_button(IMAGE_SPECIALS) . '</a>');
                    } else {
                        $specials = $specials_result->fields;
                        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'sID=' . $specials['specials_id'] . '&action=edit') . '">' . oos_button(IMAGE_SPECIALS) . '</a>');
                    }
                }


                if (defined('MAX_DISPLAY_FEATURED_PRODUCTS')) {
                    $featuredtable = $oostable['featured'];
                    $query = "SELECT featured_id, products_id, status
                         FROM $featuredtable p
                        WHERE status = '1' AND
                              products_id = '" . $pInfo->products_id . "'";
                    $featured_result = $dbconn->Execute($query);
                    if (!$featured_result->RecordCount()) {
                        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'pID=' . $pInfo->products_id . '&action=new') . '">' . oos_button(IMAGE_FEATURED) . '</a>');
                    } else {
                        $featured = $featured_result->fields;
                        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'fID=' . $featured['featured_id'] . '&action=edit') . '">' . oos_button(IMAGE_FEATURED) . '</a>');
                    }
                }

                #slider
                $slidertable = $oostable['categories_slider'];
                $query = "SELECT slider_id, products_id, status
                         FROM $slidertable p
                        WHERE status = '1' AND
                              products_id = '" . $pInfo->products_id . "'";
                $slider_result = $dbconn->Execute($query);
                if (!$slider_result->RecordCount()) {
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'pID=' . $pInfo->products_id . '&action=new') . '">' . oos_button(IMAGE_SLIDER) . '</a>');
                } else {
                    $slider = $slider_result->fields;
                    $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories_slider'], 'sID=' . $slider['slider_id'] . '&action=edit') . '">' . oos_button(IMAGE_SLIDER) . '</a>');
                }


                $contents[] = array('text' => '#' . $pInfo->products_id . ' ' . TEXT_CATEGORIES . ' ' . oos_get_categories_name($current_category_id) . '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($pInfo->products_date_added));
                if (oos_is_not_null($pInfo->products_last_modified)) {
                    $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($pInfo->products_last_modified));
                }
                if (date('Y-m-d') < $pInfo->products_date_available) {
                    $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . oos_date_short($pInfo->products_date_available));
                }
                $contents[] = array('text' => '<br><a href="' . oos_catalog_link($aCatalog['product_info'], 'products_id=' . $pInfo->products_id) . '" target="_blank" rel="noopener">' . product_info_image($pInfo->products_image, $pInfo->products_name) . '</a><br>' . $pInfo->products_image);

                $sPrice = $pInfo->products_price;
                $sPriceList = $pInfo->products_price_list;

                if ($action != 'new_product_preview') {
                    $sPriceNetto = oos_round($sPrice, TAX_DECIMAL_PLACES);
                    $sPriceListNetto = oos_round($sPriceList, TAX_DECIMAL_PLACES);
                    $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . $pInfo->products_tax_class_id . "' ");
                    $tax = $tax_result->fields;
                    $sPrice = ($sPrice*($tax['tax_rate']+100)/100);
                    $sPriceList = ($sPriceList*($tax['tax_rate']+100)/100);

                    if (isset($specials) && is_array($specials)) {
                        $sSpecialsPriceNet = oos_round($specials['specials_new_products_price'], TAX_DECIMAL_PLACES);
                        $sSpecialsPrice = oos_round(($specials['specials_new_products_price']*($tax['tax_rate']+100)/100), TAX_DECIMAL_PLACES);
                    }
                }

                $sPrice = oos_round($sPrice, TAX_DECIMAL_PLACES);
                $sPriceList = oos_round($sPriceList, TAX_DECIMAL_PLACES);

                if (isset($specials) && is_array($specials)) {
                    $contents[] = array('text' => '<br><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="oldPrice">' . $currencies->format($sPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="oldPrice">' . $currencies->format($sPriceNetto) . '</span>');
                    $contents[] = array('text' => '<b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="specialPrice">' . $currencies->format($sSpecialsPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="specialPrice">' . $currencies->format($sSpecialsPriceNet) . '</span>');

                    $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sSpecialsPrice / $sPrice) * 100)) . '%');
                    if (date('Y-m-d') < $specials['expires_date']) {
                        $contents[] = array('text' => '' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($specials['expires_date']) . '</b>');
                    }
                } else {
                    $contents[] = array('text' => '<br><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> ' . $currencies->format($sPrice) . ' - ' . TEXT_TAX_INFO . $currencies->format($sPriceNetto));
                }
                if ($sPriceList > 0) {
                    $contents[] = array('text' => '' .  CAT_LIST_PRICE_TEXT . $currencies->format($sPriceList) . ' - ' . TEXT_TAX_INFO . $currencies->format($sPriceListNetto));
                }
                $contents[] = array('text' => '<br><br>' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
                $contents[] = array('text' => '' . CAT_QUANTITY_MIN_TEXT . $pInfo->products_quantity_order_min);
                $contents[] = array('text' => '' . CAT_QUANTITY_MAX_TEXT . $pInfo->products_quantity_order_max);
                $contents[] = array('text' => '' . CAT_QUANTITY_UNITS_TEXT . $pInfo->products_quantity_order_units);

                if ($pInfo->products_discount1_qty > 0) {
                    $sDiscount1 = $pInfo->products_discount1;
                    $sDiscount1 = round($sDiscount1, TAX_DECIMAL_PLACES);
                    $contents[] = array('text' => '<br><br><b>' . TEXT_DISCOUNTS_TITLE . ':</b>');
                    $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount1_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount1_qty . ' ' . $currencies->format($sDiscount1) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount1Netto));
                }
                if ($pInfo->products_discount2_qty > 0) {
                    $sDiscount2 = $pInfo->products_discount2;
                    $sDiscount2 = round($sDiscount2, TAX_DECIMAL_PLACES);
                    $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount2_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount2_qty . ' ' . $currencies->format($sDiscount2) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount2Netto));
                }
                if ($pInfo->products_discount3_qty > 0) {
                    $sDiscount3 = $pInfo->products_discount3;
                    $sDiscount3 = round($sDiscount3, TAX_DECIMAL_PLACES);
                    $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount3_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount3_qty . ' ' . $currencies->format($sDiscount3) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount3Netto));
                }
                if ($pInfo->products_discount4_qty > 0) {
                    $sDiscount4 = $pInfo->products_discount4;
                    $sDiscount4 = round($sDiscount4, TAX_DECIMAL_PLACES);
                    $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount4_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount4_qty . ' ' . $currencies->format($sDiscount4) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount4Netto));
                }
                $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . ((isset($pInfo->average_rating)) ? number_format($pInfo->average_rating, 2) . '%' : "") );
            }
        } else { // create category/product info
            $parent_categories_name = oos_output_generated_category_path($current_category_id);
            $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

            $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
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
