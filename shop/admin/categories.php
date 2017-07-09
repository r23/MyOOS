<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2016 by the MyOOS Development Team.
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
require 'includes/functions/function_image_resize.php';
require 'includes/functions/function_gd.php';
require 'includes/classes/class_currencies.php';
require 'includes/classes/class_upload.php';


$currencies = new currencies();

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (!empty($action)) {
    switch ($action) {
		case 'new_slave_product':
			$product_check = false;
			if (oos_is_not_null($_POST['slave_product_id'])) {
				//checks if the product actaully exists
				$check_product_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products'] . " WHERE products_id = " . intval($_POST['slave_product_id']) . " LIMIT 1");
				if ($check_product_result->RecordCount() == 1) {
					$product_check = TRUE;
				}
				//checks if the product is already present
				$check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_POST['slave_product_id']) . " AND master_id = " . intval($_GET['pID']) . " LIMIT 1");
				if ($check_product_result->RecordCount() == 1) {
					$product_check = false;
				}
			}

			if ($product_check == TRUE) {
				$sql_data_array = array('slave_id' => $_POST['slave_product_id'],
										'master_id' => $_GET['pID']);
				oos_db_perform($oostable['products_to_master'], $sql_data_array, 'insert');
				$messageStack->add_session('This product was successfully added as a slave', 'success');
			} else {
				$messageStack->add_session('This product does not exist or is already a slave', 'error');
			}
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['pID'] . '&amp;action=slave_products'));
			break;

		case 'slave_delete':
			$dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']) . " AND master_id = " . intval($_GET['master_id']) . " LIMIT 1");
			$check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . $_GET['slave_id']);
			if ($check_product_result->RecordCount() == 0) {
				$dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = '1' WHERE products_id = " . $_GET['slave_id']);
			}
			$messageStack->add_session('Slave Deleted', 'success');
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['master_id'] . '&amp;action=slave_products'));
			break;

		case 'slave_visible':
			$dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = " . $_GET['visible'] . " WHERE products_id = " . $_GET['slave_id']);
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['master_id'] . '&amp;action=slave_products'));
			break;

		case 'setflag':
			if ( isset($_GET['flag']) && ($_GET['flag'] == '0') || ($_GET['flag'] == '1') ) {
				if (isset($_GET['pID'])) {
					oos_set_product_status($_GET['pID'], $_GET['flag']);
				} elseif (isset($_GET['cID'])) {
					oos_set_categories_status($_GET['cID'], $_GET['flag']);
				}
			}
			
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['pID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ((isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '')));
			break;

		case 'insert_category':
		case 'update_category':
		
			$nStatus = (isset($_POST['categories_status']) ? 1 : 0);
			$sort_order = oos_db_prepare_input($_POST['sort_order']);
			if (isset($_POST['categories_id'])) $categories_id = oos_db_prepare_input($_POST['categories_id']);
			if ((isset($_GET['cID'])) && ($categories_id == '')) {
				$categories_id = oos_db_prepare_input($_GET['cID']);
			}

			$sql_data_array = array();
			$sql_data_array = array('sort_order' => intval($sort_order));

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

				oos_db_perform($oostable['categories'], $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
			}

			$languages = oos_get_languages();
			for ($i = 0, $n = count($languages); $i < $n; $i++) {
				$categories_name_array = $_POST['categories_name'];
				$lang_id = $languages[$i]['id'];
				$sql_data_array = array('categories_name' => oos_db_prepare_input($categories_name_array[$lang_id]));
				$sql_data_array = array('categories_name' => oos_db_prepare_input($_POST['categories_name'][$lang_id]),
										'categories_heading_title' => oos_db_prepare_input($_POST['categories_heading_title'][$lang_id]),
										'categories_description' => oos_db_prepare_input($_POST['categories_description'][$lang_id]),
										'categories_description_meta' => oos_db_prepare_input($_POST['categories_description_meta'][$lang_id]),
										'categories_keywords_meta' => oos_db_prepare_input($_POST['categories_keywords_meta'][$lang_id]));


				if ($action == 'insert_category') {
					$insert_sql_data = array('categories_id' => intval($categories_id),
											'categories_languages_id' => intval($languages[$i]['id']));

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

					oos_db_perform($oostable['categories_description'], $sql_data_array);
				} elseif ($action == 'update_category') {
					oos_db_perform($oostable['categories_description'], $sql_data_array, 'update', 'categories_id = \'' . intval($categories_id) . '\' and categories_languages_id = \'' . intval($languages[$i]['id']) . '\'');
				}
			}
			
			$upload_handler = new UploadHandler();
            $categories_image = oos_get_uploaded_file('categories_image');
            $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

            if (is_uploaded_file($categories_image['tmp_name'])) {
              $dbconn->Execute("UPDATE " . $oostable['categories'] . " SET categories_image = '" . $categories_image['name'] . "' WHERE categories_id = '" . oos_db_input($categories_id) . "'");
              oos_get_copy_uploaded_file($categories_image, $image_directory);
            }
			
        $pi_sort_order = 0;
        $piArray = array(0);

        foreach ($_FILES as $key => $value) {
// Update existing large product images
          if (preg_match('/^products_image_large_([0-9]+)$/', $key, $matches)) {
            $pi_sort_order++;

            $sql_data_array = array('htmlcontent' => tep_db_prepare_input($_POST['products_image_htmlcontent_' . $matches[1]]),
                                    'sort_order' => $pi_sort_order);

            $t = new upload($key);
            $t->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($t->parse() && $t->save()) {
              $sql_data_array['image'] = tep_db_prepare_input($t->filename);
            }

            tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array, 'update', "products_id = '" . (int)$products_id . "' and id = '" . (int)$matches[1] . "'");

            $piArray[] = (int)$matches[1];
          } elseif (preg_match('/^products_image_large_new_([0-9]+)$/', $key, $matches)) {
// Insert new large product images
            $sql_data_array = array('products_id' => (int)$products_id,
                                    'htmlcontent' => tep_db_prepare_input($_POST['products_image_htmlcontent_new_' . $matches[1]]));

            $t = new upload($key);
            $t->set_destination(DIR_FS_CATALOG_IMAGES);
            if ($t->parse() && $t->save()) {
              $pi_sort_order++;

              $sql_data_array['image'] = tep_db_prepare_input($t->filename);
              $sql_data_array['sort_order'] = $pi_sort_order;

              tep_db_perform(TABLE_PRODUCTS_IMAGES, $sql_data_array);

              $piArray[] = tep_db_insert_id();
            }
          }
        }
			
			if (isset($_POST['add_image'])) {
				oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories_id . '&amp;action=' . 'edit_category' . (isset($_POST['tab']) ? '&tab=' . intval($_POST['tab']) : '')));				
			} else {
				oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories_id));
			}
			break;

		case 'delete_category_confirm':
			if (isset($_POST['categories_id'])) {
				$categories = oos_get_category_tree($categories_id, '', '0', '', TRUE);
				$products = array();
				$products_delete = array();

				for ($i = 0, $n = count($categories); $i < $n; $i++) {
					$product_ids_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . intval($categories[$i]['id']) . "'");
					while ($product_ids = $product_ids_result->fields) {
						$products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];

						// Move that ADOdb pointer!
						$product_ids_result->MoveNext();
					}
				}

				reset($products);
				while (list($key, $value) = each($products)) {
					$category_ids = '';
					for ($i = 0, $n = count($value['categories']); $i < $n; $i++) {
						$category_ids .= '\'' . $value['categories'][$i] . '\', ';
					}
					$category_ids = substr($category_ids, 0, -2);

					$check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($key) . "' and categories_id not in (" . $category_ids . ")");
					$check = $check_result->fields;
					if ($check['total'] < '1') {
						$products_delete[$key] = $key;
					}
				}

				// Removing categories can be a lengthy process
				oos_set_time_limit(0);
				for ($i = 0, $n = count($categories); $i < $n; $i++) {
					oos_remove_category($categories[$i]['id']);
				}

				reset($products_delete);
				while (list($key) = each($products_delete)) {
					oos_remove_product($key);
				}
			}

			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath));
			break;

		case 'delete_product_confirm':
			if ( ($_POST['products_id']) && ($_POST['product_categories']) && (is_array($_POST['product_categories'])) ) {
				$product_categories = $_POST['product_categories'];

				for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
					$dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . (int)$products_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
				}

				$product_categories_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . (int)$products_id . "'");
				$product_categories = $product_categories_result->fields;

				if ($product_categories['total'] == '0') {
					oos_remove_product($products_id);
				}
			}
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath));
			break;

		case 'move_category_confirm':
			if ( ($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id']) ) {
				$new_parent_id = $move_to_category_id;
				$dbconn->Execute("UPDATE " . $oostable['categories'] . " SET parent_id = '" . intval($new_parent_id) . "', last_modified = now() WHERE categories_id = '" . intval($categories_id) . "'");
			}
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $new_parent_id . '&amp;cID=' . $categories_id));
			break;

		case 'move_product_confirm':
			$products_id = oos_db_prepare_input($_POST['products_id']);
			$new_parent_id = oos_db_prepare_input($_POST['move_to_category_id']);

			$duplicate_check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($products_id) . "' and categories_id = '" . intval($new_parent_id) . "'");
			$duplicate_check = $duplicate_check_result->fields;
			if ($duplicate_check['total'] < 1) $dbconn->Execute("UPDATE " . $oostable['products_to_categories'] . " SET categories_id = '" . intval($new_parent_id) . "' WHERE products_id = '" . intval($products_id) . "' and categories_id = '" . intval($current_category_id) . "'");

			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $new_parent_id . '&amp;pID=' . $products_id));
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
				$product_result = $dbconn->Execute("SELECT products_quantity, products_reorder_level, products_model, products_ean,
                                                       products_image, products_price,
                                                       products_base_price, products_base_unit,
                                                       products_date_available, products_weight, products_tax_class_id,
                                                       products_units_id, manufacturers_id, products_price_list,
                                                       products_discount_allowed, products_quantity_decimal, products_quantity_order_min,
                                                       products_quantity_order_units, products_discount1, products_discount2,
                                                       products_discount3, products_discount4, products_discount1_qty,
                                                       products_discount2_qty, products_discount3_qty, products_discount4_qty,
                                                       products_discounts_id, products_slave_visible, products_sort_order
													FROM " . $oostable['products'] . "
													WHERE products_id = '" . oos_db_input($products_id) . "'");
				$product = $product_result->fields;
				$dbconn->Execute("INSERT INTO " . $oostable['products'] . "
                         (products_quantity,
                          products_reorder_level,
                          products_model,
                          products_ean,
                          products_image,
                          products_price,
                          products_base_price,
                          products_base_unit,
                          products_date_added,
                          products_date_available,
                          products_weight,
                          products_status,
                          products_tax_class_id,
                          products_units_id,
                          manufacturers_id,
                          products_price_list,
                          products_discount_allowed,
                          products_quantity_decimal,
                          products_quantity_order_min,
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
                                  '" . $product['products_ean'] . "',
                                  '" . $product['products_image'] . "',
                                  '" . $product['products_price'] . "',
                                  '" . $product['products_base_price'] . "',
                                  '" . $product['products_base_unit'] . "',
                                  now(),
                                  '" . $product['products_date_available'] . "',
                                  '" . $product['products_weight'] . "', '0',
                                  '" . $product['products_tax_class_id'] . "',
                                  '" . $product['products_units_id'] . "',
                                  '" . $product['manufacturers_id'] . "',
                                  '" . $product['products_price_list'] . "',
                                  '" . $product['products_discount_allowed'] . "',
                                  '" . $product['products_quantity_decimal'] . "',
                                  '" . $product['products_quantity_order_min'] . "',
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
                                  '" . $product['products_sort_order'] . "')");
				$dup_products_id = $dbconn->Insert_ID();
				$description_result = $dbconn->Execute("SELECT products_languages_id, products_name, products_description, products_url, products_description_meta, products_keywords_meta  FROM " . $oostable['products_description'] . " WHERE products_id = '" . oos_db_input($products_id) . "'");
				while ($description = $description_result->fields) {
					$dbconn->Execute("INSERT INTO " . $oostable['products_description'] . "
									(products_id,
                            products_languages_id,
                            products_name,
                            products_description,
                            products_url,
                            products_viewed,
                            products_description_meta,
                            products_keywords_meta)
                            VALUES ('" . $dup_products_id . "',
                                    '" . $description['products_languages_id'] . "',
                                    '" . addslashes($description['products_name']) . "',
                                    '" . addslashes($description['products_description']) . "',
                                    '" . $description['products_url'] . "', '0',
                                    '" . addslashes($description['products_description_meta']) . "',
                                    '" . addslashes($description['products_keywords_meta']) . "')");

					// Move that ADOdb pointer!
					$description_result->MoveNext();
				}

				$dbconn->Execute("INSERT INTO " . $oostable['products_to_categories'] . "
                          (products_id,
                           categories_id)
                           VALUES ('" . $dup_products_id . "',
                                   '" . oos_db_input($categories_id) . "')");

				$products_id_from = oos_db_input($products_id);
				$products_id_to = $dup_products_id;
				$products_id = $dup_products_id;

				if ( $_POST['copy_attributes']=='copy_attributes_yes' and $_POST['copy_as'] == 'duplicate' ) {
					$products_copy_from_result= $dbconn->Execute("SELECT options_id, options_values_id, options_values_price, price_prefix, options_sort_order FROM " . $oostable['products_attributes'] . " WHERE products_id='" . $products_id_from . "'");
					while ( $products_copy_from = $products_copy_from_result->fields) {
						$rows++;
						$sql = "INSERT INTO " . $oostable['products_attributes'] . "
							(products_id,
							options_id,
							options_values_id,
							options_values_price,
							price_prefix,
							options_sort_order)
							VALUES ('" . $products_id_to . "',
									'" . $products_copy_from['options_id'] . "',
									'" . $products_copy_from['options_values_id'] . "',
									'" . $products_copy_from['options_values_price'] . "',
									'" . $products_copy_from['price_prefix'] . "',
									'" . $products_copy_from['options_sort_order'] . "')";
						$dbconn->Execute($sql);

						// Move that ADOdb pointer!
						$products_copy_from_result->MoveNext();
					}

				}
			}
		}

		oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $categories_id . '&amp;pID=' . $products_id));
		break;
    }
}

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
    $parameters = array('categories_name' => '',
                       'categories_heading_title' => '',
                       'categories_description' => '',
                       'categories_description_meta' => '',
                       'categories_keywords_meta' => '',		   
                       'categories_id' => '',
                       'parent_id' => '',
                       'sort_order' => '',
                       'date_added' => '',
                       'categories_status' => 1,
                       'products_weight' => '',
                       'last_modified' => '');

	$cInfo = new objectInfo($parameters);	
	
	if (isset($_GET['cID']) && empty($_POST)) {
        $categoriestable = $oostable['categories'];
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT c.categories_id, cd.categories_name, cd.categories_heading_title,
                         cd.categories_description, cd.categories_description_meta, cd.categories_keywords_meta,
                         c.categories_image, c.parent_id, c.sort_order, c.date_added, c.categories_status, c.last_modified
                  FROM $categoriestable c,
                       $categories_descriptiontable cd
                  WHERE c.categories_id = '" . intval($_GET['cID']) . "' AND
                        c.categories_id = cd.categories_id AND
                        cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'
                  ORDER BY c.sort_order, cd.categories_name";
        $categories_result = $dbconn->Execute($query);
        $category = $categories_result->fields;

        $cInfo = new objectInfo($category);
	} elseif (oos_is_not_null($_POST)) {
        $cInfo = new objectInfo($_POST);
        $categories_name = $_POST['categories_name'];
        $categories_heading_title = $_POST['categories_heading_title'];
        $categories_description = $_POST['categories_description'];
        $categories_description_meta = $_POST['categories_description_meta'];
        $categories_keywords_meta = $_POST['categories_keywords_meta'];
        $categories_url = $_POST['categories_url'];
		$categories_status = $_POST['categories_status'];
	} 

	$languages = oos_get_languages();

	$text_new_or_edit = ($action=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
	  
	// form-validation
	$bForm = TRUE;
	$bUpload = TRUE;

?>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
	<!-- Breadcrumbs //-->
<?php //	<div class="row wrapper gray-bg page-heading"> ?>
	<div class="row wrapper gray-bg page-heading">
		<div class="col-lg-12">
			<h2><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)); ?></h2>
			<ol class="breadcrumb">
				<li>
					<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
				</li>
				<li>
					<a href="<?php echo oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
				</li>
				<li class="active">
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
	echo oos_draw_form('fileupload', 'new_category', $aContents['categories'], 'cPath=' . $cPath . (isset($_GET['cID']) ? '&amp;cID=' . $_GET['cID'] : '') . '&amp;action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');
		echo oos_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d')));
		echo oos_draw_hidden_field('parent_id', $cInfo->parent_id);
		echo oos_draw_hidden_field('categories_previous_image', $cInfo->categories_image);
		echo oos_hide_session_id();
?>

        <div class="wrapper wrapper-content">

            <div class="row">
                <div class="col-lg-12">
                    <div class="tabs-container">
                            <ul class="nav nav-tabs">
							
<?php
		if (isset($_GET['tab'])) {
			$active_tab = oos_db_prepare_input($_GET['tab']);
		}
		for ($i=0; $i < count($languages); $i++) {
?>									
                                <li <?php if ($i == $active_tab) echo 'class="active"'; ?>><a data-toggle="tab" href="#tab-<?php echo $i; ?>"><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)) . '&nbsp;(' . $languages[$i]['name'] . ')&nbsp;'; ?></a></li>
<?php
		}
		$nTab = $i;
		$nImageTab = $i+1;
?>
                                <li <?php if ($nTab == $active_tab) echo 'class="active"'; ?>><a data-toggle="tab" href="#tab-<?php echo $nTab; ?>"><?php echo TEXT_DATA; ?></a></li>
                                <li <?php if ($nImageTab == $active_tab) echo 'class="active"'; ?>><a data-toggle="tab" href="#tab-<?php echo $nImageTab; ?>"><?php echo TEXT_IMAGES; ?></a></li>
                            </ul>
                            <div class="tab-content">
<?php
		for ($i=0; $i < count($languages); $i++) {
?>		  
                                <div id="tab-<?php echo $i; ?>" class="tab-pane <?php if ($i == $active_tab) echo 'active'; ?>" >
                                    <div class="panel-body">

                                        <fieldset class="form-horizontal">							
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_CATEGORIES_NAME; ?>:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : oos_get_category_name($cInfo->categories_id, $languages[$i]['id'])), '', FALSE, 'text', TRUE, FALSE, TEXT_EDIT_CATEGORIES_NAME); ?></div>
                                            </div>

                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?>:</label>
                                                <div class="col-sm-10">
													<?php echo oos_draw_editor_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : oos_get_category_description($cInfo->categories_id, $languages[$i]['id']))); ?>
                                                </div>
                                            </div>
												<script>
													CKEDITOR.replace( 'categories_description[<?php echo $languages[$i]['id']; ?>]');
												</script>											
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?>:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : oos_get_category_heading_title($cInfo->categories_id, $languages[$i]['id'])), '', FALSE, 'text', TRUE, FALSE, '...'); ?></div>
                                            </div>
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_CATEGORIES_DESCRIPTION_META; ?>:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_textarea_field('categories_description_meta[' . $languages[$i]['id'] . ']', 'soft', '70', '2', (($categories_description_meta[$languages[$i]['id']]) ? stripslashes($categories_description_meta[$languages[$i]['id']]) : oos_get_category_description_meta($cInfo->categories_id, $languages[$i]['id']))); ?></div>
                                            </div>
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_CATEGORIES_KEYWORDS_META; ?>:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_textarea_field('categories_keywords_meta[' . $languages[$i]['id'] . ']', 'soft', '70', '2', (($categories_keywords_meta[$languages[$i]['id']]) ? stripslashes($categories_keywords_meta[$languages[$i]['id']]) : oos_get_category_keywords_meta($cInfo->categories_id, $languages[$i]['id']))); ?></div>
                                            </div>
                                        </fieldset>
			
										
                                    </div>
                                </div>
<?php
		}
?>				
								<div id="tab-<?php echo $nTab; ?>" class="tab-pane <?php if ($nTab == $active_tab) echo 'active'; ?>">				
                                    <div class="panel-body">

										<fieldset class="form-horizontal">
                                            <div class="form-group"><label class="col-sm-2 control-label">ID:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_input_field('categories_id', $cInfo->categories_id, '', FALSE, 'text', TRUE, TRUE, '...'); ?></div>
                                            </div>
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_SORT_ORDER; ?>:</label>
                                                <div class="col-sm-10"><?php echo oos_draw_input_field('sort_order', $cInfo->sort_order); ?></div>
										
                                            </div>
                                            <div class="form-group"><label class="col-sm-2 control-label"><?php echo TEXT_EDIT_STATUS; ?>:</label>
                                                <div class="col-sm-10">
													<label class="switch">
														<?php echo oos_draw_checkbox_field('categories_status', '', ($cInfo->categories_status == 1 ? TRUE : FALSE)); ?>
													<span></span>
													</label>					
					  
                                                </div>
                                            </div>
																				
                                        </fieldset>


                                    </div>
                                </div>

								<div id="tab-<?php echo $nTab+1; ?>" class="tab-pane <?php if ($nTab+1 == $active_tab) echo 'active'; ?>">									
									<div class="panel-body">
<?php
		if (isset($_GET['cID'])) {
			$aTypes  = array();
			$imgtypes = ImageTypes();
			$aTypes['GIF'] = ($imgtypes & IMG_GIF) ? 'gif' : false;
			$aTypes['JPG'] = ($imgtypes & IMG_JPG) ? 'jpg' : false;
			$aTypes['JPEG'] = ($imgtypes & IMG_JPG) ? 'jpg' : false;
			$aTypes['PNG'] = ($imgtypes & IMG_PNG) ? 'png' : false;
			unset($imgtypes);
?>
										<h3><?php echo TEXT_UPLOAD; ?></h3>
											<blockquote class="box-placeholder">
												<p>
<?php
				natcasesort($aTypes );
				$types =  array_keys($aTypes );
		
				// todo Zip Upload
				// $types[] = 'ZIP';
				natcasesort($types);
				$upload_extensions = $types;
				$last = strtoupper(array_pop($types));
				$s1 = strtoupper(implode(', ', $types));
				$used = 0;

				printf(TEXT_GRAPHICS_INFO, $s1, $last);

?>
												</p>

												<div class="alert alert-info" role="alert">
													<p>
														<strong><?php echo TEXT_GRAPHICS_NOTE; ?></strong><br>
<?php
			if ($last == 'ZIP') {
				echo TEXT_GRAPHICS_ZIP;
?>
			<br>
<?php
			}
			
			$maxupload = ini_get('upload_max_filesize');
			$maxpost = ini_get('post_max_size');
			$maxuploadint = parse_size($maxupload);
			$maxpostint = parse_size($maxpost);
			
			if ($maxuploadint < $maxpostint) {
				echo sprintf(TEXT_GRAPHICS_MAXIMUM, $maxupload, $maxpost);
			} else {
				echo sprintf(TEXT_GRAPHICS_MAX_SIZE, $maxpost);
			}
?>
			<br>
													</p>
												</div>
											</blockquote>
			
            <br>
               <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload-->
               <div class="row fileupload-buttonbar">
                  <div class="col-lg-7">
                     <!-- The fileinput-button span is used to style the file input field as button-->
                     <span class="btn btn-success fileinput-button"><i class="fa fa-fw fa-plus"></i>
                        <span><?php echo BUTTON_ADD_FILES; ?></span>
                        <input type="file" name="files[]" multiple="">
                     </span>
                     <button type="submit" class="btn btn-primary start"><i class="fa fa-fw fa-upload"></i>
                        <span><?php echo BUTTON_START_UPLOAD; ?></span>
                     </button>
                     <button type="reset" class="btn btn-warning cancel"><i class="fa fa-fw fa-times"></i>
                        <span><?php echo BUTTON_CANCEL_UPLOAD; ?></span>
                     </button>
                     <button type="reset" class="btn btn-danger cancel"><i class="fa fa-fw fa-trash"></i>
                        <span><?php echo BUTTON_DELETE; ?></span>
                     </button>
                     <!-- The global file processing state-->
                     <span class="fileupload-process"></span>
                  </div>
                  <!-- The global progress state-->
                  <div class="col-lg-5 fileupload-progress fade">
                     <!-- The global progress bar-->
                     <div role="progressbar" aria-valuemin="0" aria-valuemax="100" class="progress progress-striped active">
                        <div style="width:0%;" class="progress-bar progress-bar-success"></div>
                     </div>
                     <!-- The extended global progress state-->
                     <div class="progress-extended">&nbsp;</div>
                  </div>
               </div>
			   
			   
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>			   
			   
               <!-- The table listing the files available for upload/download-->
               <table role="presentation" class="table table-striped">
                  <tbody class="files"></tbody>
               </table>

            <!-- The template to display files available for upload-->
            <script id="template-upload" type="text/x-tmpl">
               {% for (var i=0, file; file=o.files[i]; i++) { %}
                   <tr class="template-upload fade">
                       <td>
                           <span class="preview"></span>
                       </td>
                       <td>
                           <p class="name">{%=file.name%}</p>
                           <strong class="error text-danger"></strong>
                       </td>
                       <td>
                           <p class="size">Processing...</p>
                           <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
                       </td>
                       <td>
                           {% if (!i) { %}
                               <button class="btn btn-warning cancel">
                                   <em class="fa fa-fw fa-times"></em>
                                   <span><?php echo BUTTON_CANCEL; ?></span>
                               </button>
                           {% } %}
                       </td>
                   </tr>
               {% } %}
            </script>
            <!-- The template to display files available for download-->
            <script id="template-download" type="text/x-tmpl">
               {% for (var i=0, file; file=o.files[i]; i++) { %}
                   <tr class="template-download fade">
                       <td>
                           <span class="preview">
                               {% if (file.thumbnailUrl) { %}
                                   <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                               {% } %}
                           </span>
                       </td>
                       <td>
                           <p class="name">
                               {% if (file.url) { %}
                                   <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                               {% } else { %}
                                   <span>{%=file.name%}</span>
                               {% } %}
                           </p>
                           {% if (file.error) { %}
                               <div><span class="label label-danger"><?php echo TEXT_ERROR; ?></span> {%=file.error%}</div>
                           {% } %}
                       </td>
                       <td>
                           <span class="size">{%=o.formatFileSize(file.size)%}</span>
                       </td>
                       <td>
                           {% if (file.deleteUrl) { %}
                               <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                                   <em class="fa fa-fw fa-trash"></em>
                                   <span><?php echo BUTTON_DELETE; ?></span>
                               </button>
                           {% } else { %}
                               <button class="btn btn-warning cancel">
                                   <em class="fa fa-fw fa-times"></em>
                                   <span><?php echo BUTTON_CANCEL; ?></span>
                               </button>
                           {% } %}
                       </td>
                   </tr>
               {% } %}
            </script>

                                    </div>
<?php
		} else {
			echo oos_draw_hidden_field('add_image', '1');
			echo oos_draw_hidden_field('tab', $nImageTab);
?>
<button class="btn btn-sm btn-primary margin-bottom-100" type="submit"><strong><?php echo BUTTON_UPLOAD_IMAGES; ?></strong></button>
								</div>
<?php 
		}
?>					
								</div>
									
                            </div>
					</div>
				</div><!--/col-lg-12-->
			</div>
			
			<div class="row">
				<div class="col-lg-12">
					<button class="btn btn-sm btn-primary margin-bottom-20 pull-right" type="submit"><strong><?php echo IMAGE_SAVE; ?></strong></button>
				</div>
			</div>			
			
		</div>
	</form>

<!-- body_text_eof //-->
<?php
} elseif ($action == 'new_category_preview') {
	$form_action = ($_GET['cID']) ? 'update_category' : 'insert_category';

    echo oos_draw_form('id', $form_action, $aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $_GET['cID'] . '&amp;action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');	  
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if (oos_is_not_null($_POST)) {

      $cInfo = new objectInfo($_POST);
      $categories_name = $_POST['categories_name'];
      $categories_heading_title = $_POST['categories_heading_title'];
      $categories_description = $_POST['categories_description'];
      $categories_description_meta = $_POST['categories_description_meta'];
      $categories_keywords_meta = $_POST['categories_keywords_meta'];

      if ( ($_POST['categories_image'] != 'none') && (isset($_FILES['categories_image'])) ) {
        $categories_image = oos_get_uploaded_file('categories_image');
        $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }

      // copy image only if modified
      if (is_uploaded_file($categories_image['tmp_name'])) {
        oos_get_copy_uploaded_file($categories_image, $image_directory);
        $categories_image_name = $categories_image['name'];
      } else {
        $categories_image_name = $_POST['categories_previous_image'];
      }
    } else {
      $categoriestable = $oostable['categories'];
      $categories_descriptiontable = $oostable['categories_description'];
      $query = "SELECT c.categories_id, cd.categories_languages_id, cd.categories_name,
                       cd.categories_heading_title, cd.categories_description,
                       cd.categories_description_meta, cd.categories_keywords_meta,
                       c.categories_image, c.sort_order, c.date_added, c.last_modified
                FROM $categoriestable c,
                     $categories_descriptiontable cd
                WHERE c.categories_id = cd.categories_id AND
                      c.categories_id = '" . intval($_GET['cID']) . "'";
      $category_result = $dbconn->Execute($query);
      $category = $category_result->fields;

      $cInfo = new objectInfo($category);
      $categories_image_name = $cInfo->categories_image;
    }


    $languages = oos_get_languages();
    for ($i=0; $i < count($languages); $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $cInfo->categories_name = oos_get_category_name($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_heading_title = oos_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description = oos_get_category_description($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_description_meta = oos_get_category_description_meta($cInfo->categories_id, $languages[$i]['id']);
        $cInfo->categories_keywords_meta = oos_get_category_keywords_meta($cInfo->categories_id, $languages[$i]['id']);
      } else {
        $cInfo->categories_name = oos_db_prepare_input($categories_name[$languages[$i]['id']]);
        $cInfo->categories_heading_title = oos_db_prepare_input($categories_heading_title[$languages[$i]['id']]);
        $cInfo->categories_description = oos_db_prepare_input($categories_description[$languages[$i]['id']]);
        $cInfo->categories_description_meta = oos_db_prepare_input($categories_description_meta[$languages[$i]['id']]);
        $cInfo->categories_keywords_meta = oos_db_prepare_input($categories_keywords_meta[$languages[$i]['id']]);
      }
	  
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo oos_flag_icon($languages[$i]) . '&nbsp;' . $cInfo->categories_name; ?></td>
          </tr>		
          <tr>
            <td class="pageHeading">&nbsp;<?php echo $cInfo->categories_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main">
<?php
  if (oos_is_not_null($categories_image_name))  {
    echo oos_image(OOS_SHOP_IMAGES . $categories_image_name, $cInfo->categories_name, '', '', 'align="right" hspace="5" vspace="5"');
  } else {
    $categories_image_name = 'none';
  }
  echo $cInfo->categories_description;
?>
         </td>
      </tr>
<?php
    }
    if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
      if (isset($_GET['origin'])) {
        $pos_params = strpos($_GET['origin'], '?', 0);
        if ($pos_params != false) {
          $back_url = substr($_GET['origin'], 0, $pos_params);
          $back_url_params = substr($_GET['origin'], $pos_params + 1);
        } else {
          $back_url = $_GET['origin'];
          $back_url_params = '';
        }
      } else {
        $back_url = $aContents['categories'];
        $back_url_params = 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params, 'NONSSL') . '">' . oos_button('back', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
      while (list($key, $value) = each($_POST)) {
        if (!is_array($_POST[$key])) {
          echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));

        }
      }
      $languages = oos_get_languages();
      for ($i=0; $i < count($languages); $i++) {
        echo oos_draw_hidden_field('categories_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_name[$languages[$i]['id']])));
        echo oos_draw_hidden_field('categories_heading_title[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_heading_title[$languages[$i]['id']])));
        echo oos_draw_hidden_field('categories_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_description[$languages[$i]['id']])));
        echo oos_draw_hidden_field('categories_description_meta[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_description_meta[$languages[$i]['id']])));
        echo oos_draw_hidden_field('categories_keywords_meta[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($categories_keywords_meta[$languages[$i]['id']])));
      }
   echo "\n";
   echo "\n";
      echo oos_draw_hidden_field('categories_image', stripslashes($categories_image_name));

      echo oos_submit_button('back', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';
      echo oos_submit_button('save', IMAGE_SAVE);
 
      echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $_GET['cID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>';
?></td>
      </form></tr>	  
<?php
    }
?>
	    </table>
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
				<div class="col-sm-6"></div>
				<div class="col-sm-6">		
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
				
<table border="0" width="100%" cellspacing="0" cellpadding="2">	
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $categories_count = 0;
    $rows = 0;
    if (isset($_GET['search'])) {
      $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.categories_id = cd.categories_id and cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' and cd.categories_name like '%" . $_GET['search'] . "%' ORDER BY c.sort_order, cd.categories_name");
    } else {
      $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY c.sort_order, cd.categories_name");
    }
    while ($categories = $categories_result->fields) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if (isset($_GET['search'])) $cPath= $categories['parent_id'];

      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => oos_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => oos_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-folder"></i></button></a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                 <td class="dataTableContent" align="center">
 <?php
       if ($categories['categories_status'] == '1') {
         echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&amp;flag=0&amp;cID=' . $categories['categories_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
       } else {
         echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&amp;flag=1&amp;cID=' . $categories['categories_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
       }
?></td>
                <td class="dataTableContent" align="center">&nbsp;<?php echo $categories['sort_order']; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories['categories_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }


    $products_count = 0;
    if (isset($_GET['search'])) {
      $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_tax_class_id, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id, p.products_price_list, p.products_discount_allowed, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' and p.products_id = p2c.products_id and pd.products_name like '%" . $_GET['search'] . "%' ORDER BY pd.products_name");
    } else {
      $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price,p.products_base_price, p.products_base_unit, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.products_price_list, p.products_discount_allowed, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' ORDER BY pd.products_name");
    }

    while ($products = $products_result->fields) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if (isset($_GET['search'])) $cPath=$products['categories_id'];

      if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo)  && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        // find out the rating average from customer reviews
        $reviews_result = $dbconn->Execute("SELECT (avg(reviews_rating) / 5 * 100) as average_rating FROM " . $oostable['reviews'] . " WHERE products_id = '" . $products['products_id'] . "'");
        $reviews = $reviews_result->fields;
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id'] . '&amp;action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id'] . '&amp;action=new_product_preview&read=only') . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . '#' . $products['products_id'] . ' ' . $products['products_name']; ?></td>
                <td class="dataTableContent"><?php echo oos_get_manufacturers_name($products['products_id']) ?></td>
                <td class="dataTableContent" align="center">
<?php
    if ($products['products_status'] == '0') {
      echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=3&amp;pID=' . $products['products_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=0&amp;pID=' . $products['products_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    }

?></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_sort_order']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $products_result->MoveNext();
    }

    $cPath_back = '';
	if (count($cPath_array) > 0) {
		for ($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
			if (empty($cPath_back)) {
				$cPath_back .= $cPath_array[$i];
			} else {
				$cPath_back .= '_' . $cPath_array[$i];
			}
		}
    }

    $cPath_back = (oos_is_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';	
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br />' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if ($cPath) echo '<a href="' . oos_href_link_admin($aContents['categories'], $cPath_back . 'cID=' . $current_category_id) . '">' . oos_button('back', IMAGE_BACK) . '</a>&nbsp;'; if (!$_GET['search']) echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;action=new_category') . '">' . oos_button('newcategorie', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;action=new_product') . '">' . oos_button('newprodukt', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {
      case 'slave_products':
        $heading[] = array('text' => '<b>' . oos_get_products_name($pInfo->products_id, $_SESSION['language_id']) . '</b>');

        $contents = array('form' => oos_draw_form('id', 'new_slave_product', $aContents['categories'], 'action=new_slave_product&amp;cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id, 'post', FALSE, 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br />' . TEXT_ADD_SLAVE_PRODUCT . '<br />' . oos_draw_input_field('slave_product_id', '', 'size="10"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('save', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');

        $contents[] = array('text' => '<br />' . TEXT_CURRENT_SLAVE_PRODUCTS);
        $slave_table_result = $dbconn->Execute("SELECT p2m.master_id, p2m.slave_id FROM " . $oostable['products_to_master'] . " p2m WHERE master_id = '" . $pInfo->products_id . "'");
        while ($slave_table = $slave_table_result->fields){
          $slave_products_result = $dbconn->Execute("SELECT p.products_id, p.products_slave_visible, pd.products_name FROM " . $oostable['products'] . " p , " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND p.products_id = '" . $slave_table['slave_id'] . "' AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name LIMIT 1");
          $slave_products = $slave_products_result->fields;
          if($slave_products['products_slave_visible'] == 1){
            $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&amp;action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
            '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action')) . 'visible=0&amp;slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&amp;action=slave_visible') . '">'.
            oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT) . '</a>');
          } else {
            $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&amp;action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
            '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_all_get_params(array('action')) . 'visible=1&amp;slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&amp;action=slave_visible') . '">'.
            oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT) . '</a>');
          }
          // Move that ADOdb pointer!
          $slave_table_result->MoveNext();
        }
        break;

      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=delete_category_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['categories'], 'action=move_category_confirm', 'post', FALSE) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('move', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('id', 'products', $aContents['categories'], 'action=delete_product_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br /><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = oos_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = count($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= oos_draw_checkbox_field('product_categories[]', $product_categories[$i][count($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br />';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br />' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('id', 'products', $aContents['categories'], 'action=move_product_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('move', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => oos_draw_form('id', 'copy_to', $aContents['categories'], 'action=copy_to_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES . '<br />' . oos_draw_pull_down_menu('categories_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . oos_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . oos_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('text' => '<br />' . oos_image(OOS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES_ONLY);
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('copy', IMAGE_COPY) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=edit_category') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=delete_category') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=move_category') . '">' . oos_button('move', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cID) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            if (oos_is_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br />' . oos_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br />' . $cInfo->categories_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . $pInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=new_product') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=delete_product') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=move_product') . '">' . oos_button('move', IMAGE_MOVE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=copy_to') . '">' . oos_button('copy_to', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=slave_products') . '">' . oos_button('slave', IMAGE_SLAVE) . '</a>');

            if (defined('MIN_DISPLAY_NEW_SPEZILAS')) {
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
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'pID=' . $pInfo->products_id . '&amp;action=new') . '">' . oos_button('specials', IMAGE_SPECIALS) . '</a>');
              } else {
                $specials = $specials_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'sID=' . $specials['specials_id'] . '&amp;action=edit') . '">' . oos_button('specials', IMAGE_SPECIALS) . '</a>');
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
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'pID=' . $pInfo->products_id . '&amp;action=new') . '">' . oos_button('featured', IMAGE_FEATURED) . '</a>');
              } else {
                $featured = $featured_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'fID=' . $featured['featured_id'] . '&amp;action=edit') . '">' . oos_button('featured', IMAGE_FEATURED) . '</a>');
              }
            }

            $contents[] = array('text' => '#' . $pInfo->products_id . ' ' . TEXT_CATEGORIES . ' ' . oos_get_categories_name($current_category_id) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($pInfo->products_date_added));
            if (oos_is_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . oos_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br /><a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=new_product_preview&read=only') . '">' . oos_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br />' . $pInfo->products_image);

            $oosPrice = $pInfo->products_price;
            $oosPriceList = $pInfo->products_price_list;

            if ($_GET['read'] == 'only' || $action != 'new_product_preview'){
              $oosPriceNetto = round($oosPrice,TAX_DECIMAL_PLACES);
              $oosPriceListNetto = round($oosPriceList,TAX_DECIMAL_PLACES);
              $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . $pInfo->products_tax_class_id . "' ");
              $tax = $tax_result->fields;
              $oosPrice = ($oosPrice*($tax[tax_rate]+100)/100);
              $oosPriceList = ($oosPriceList*($tax[tax_rate]+100)/100);

              if (isset($specials) && is_array($specials)) {
                $oosSpecialsPriceNetto = round($specials['specials_new_products_price'],TAX_DECIMAL_PLACES);
                $oosSpecialsPrice = round(($specials['specials_new_products_price']*($tax[tax_rate]+100)/100),TAX_DECIMAL_PLACES);
              }
            }			
			
            $oosPrice = round($oosPrice,TAX_DECIMAL_PLACES);
            $oosPriceList = round($oosPriceList,TAX_DECIMAL_PLACES);			
			
            if (isset($specials) && is_array($specials)) {
              $contents[] = array('text' => '<br /><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="oldPrice">' . $currencies->format($oosPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="oldPrice">' . $currencies->format($oosPriceNetto) . '</span>');
              $contents[] = array('text' => '<b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="specialPrice">' . $currencies->format($oosSpecialsPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="specialPrice">' . $currencies->format($oosSpecialsPriceNetto) . '</span>');

              $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($oosSpecialsPrice / $oosPrice) * 100)) . '%');
              if (date('Y-m-d') < $specials['expires_date']) {
                $contents[] = array('text' => '' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($specials['expires_date']) . '</b>');
              }
            } else {
              $contents[] = array('text' => '<br /><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> ' . $currencies->format($oosPrice) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosPriceNetto));
            }

            $contents[] = array('text' => '' .  CAT_LIST_PRICE_TEXT . $currencies->format($oosPriceList) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosPriceListNetto) . '<br /><br /><b>' . TEXT_PRODUCTS_DISCOUNT_ALLOWED . '</b> ' . number_format($pInfo->products_discount_allowed, 2) . '%<br /><br />' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity . CAT_QUANTITY_MIN_TEXT . $pInfo->products_quantity_order_min . CAT_QUANTITY_UNITS_TEXT . $pInfo->products_quantity_order_units );

            if ( $pInfo->products_discount1_qty > 0 ) {
              $oosDiscount1 = $pInfo->products_discount1;
              $oosDiscount1 = round($oosDiscount1,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '<br /><br /><b>' . TEXT_DISCOUNTS_TITLE . ':</b>');
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount1_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount1_qty . ' ' . $currencies->format($oosDiscount1) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount1Netto));
            }
            if ( $pInfo->products_discount2_qty > 0 ) {
              $oosDiscount2 = $pInfo->products_discount2;
              $oosDiscount2 = round($oosDiscount2,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount2_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount2_qty . ' ' . $currencies->format($oosDiscount2) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount2Netto));
            }
            if ( $pInfo->products_discount3_qty > 0 ) {
              $oosDiscount3 = $pInfo->products_discount3;
              $oosDiscount3 = round($oosDiscount3,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount3_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount3_qty . ' ' . $currencies->format($oosDiscount3) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount3Netto));
            }
            if ( $pInfo->products_discount4_qty > 0 ) {
               $oosDiscount4 = $pInfo->products_discount4;
              $oosDiscount4 = round($oosDiscount4,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount4_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount4_qty . ' ' . $currencies->format($oosDiscount4) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount4Netto));
            }
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
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