<?php
/* ----------------------------------------------------------------------
   $Id: categories.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  require 'includes/oos_main.php';

  if (!defined('CATEGORIES_DEFAULT_STATUS')) {
    define('DEFAULT_CATEGORIES_STATUS', '1');
  }

  require 'includes/functions/function_categories.php';
  require 'includes/functions/function_image_resize.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'new_slave_product':
        $product_check = false;
        if (oos_is_not_null($_POST['slave_product_id'])) {
          //checks if the product actaully exists
          $check_product_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products'] . " WHERE products_id = " . $_POST['slave_product_id'] . " LIMIT 1");
          if ($check_product_result->RecordCount() == 1) {
            $product_check = true;
          }
          //checks if the product is already present
          $check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . $_POST['slave_product_id'] . " AND master_id = " . $_GET['pID'] . " LIMIT 1");
          if ($check_product_result->RecordCount() == 1) {
            $product_check = false;
          }
        }

        if ($product_check == true) {
          $sql_data_array = array('slave_id' => $_POST['slave_product_id'],
                                  'master_id' => $_GET['pID']);
          oos_db_perform($oostable['products_to_master'], $sql_data_array, 'insert');
          $messageStack->add_session('This product was successfully added as a slave', 'success');
        } else { 
          $messageStack->add_session('This product does not exist or is already a slave', 'error');
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID'] . '&action=slave_products'));
        break;

      case 'slave_delete':
        $dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . $_GET['slave_id'] . " AND master_id = " . $_GET['master_id'] . " LIMIT 1");
        $check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . $_GET['slave_id']);
        if ($check_product_result->RecordCount() == 0) {
          $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = '1' WHERE products_id = " . $_GET['slave_id']);
        }
        $messageStack->add_session('Slave Deleted', 'success');
        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['master_id'] . '&action=slave_products'));
        break;

      case 'slave_visible':
        $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = " . $_GET['visible'] . " WHERE products_id = " . $_GET['slave_id']);
        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['master_id'] . '&action=slave_products'));
        break;

      case 'setflag':
        if ( ($_GET['flag'] >= '0') || ($_GET['flag'] <= '5') ) {
          if (isset($_GET['pID'])) {
            oos_set_product_status($_GET['pID'], $_GET['flag']);
          }
    if (isset($_GET['cID'])) {
            oos_set_categories_status($_GET['cID'], $_GET['flag']);
          }
        }
        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $_GET['cPath']));
        break;

      case 'new_category':
      case 'edit_category':
        if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
          $action = $action . '_ACD';
        }
        break;

      case 'insert_category':
      case 'update_category':
        if (isset($_POST['edit_x']) || isset($_POST['edit_y'])) {
          $action = 'edit_category_ACD';
        } else {
          if ($categories_id == '') {
            $categories_id = oos_db_prepare_input($_GET['cID']);
          }
          $sql_data_array = array('sort_order' => $sort_order);

          if ($action == 'insert_category') {
            $categories_status = 
            $insert_sql_data = array('parent_id' => $current_category_id,
                                     'date_added' => 'now()', 
                                     'categories_status' => DEFAULT_CATEGORIES_STATUS);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['categories'], $sql_data_array);

            $categories_id = $dbconn->Insert_ID();
          } elseif ($action == 'update_category') {
            $update_sql_data = array('last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            oos_db_perform($oostable['categories'], $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\'');
          }

          $languages = oos_get_languages();
          for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $categories_name_array = $_POST['categories_name'];
            $lang_id = $languages[$i]['id'];
            $sql_data_array = array('categories_name' => oos_db_prepare_input($categories_name_array[$lang_id]));
            if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
              $sql_data_array = array('categories_name' => oos_db_prepare_input($_POST['categories_name'][$lang_id]),
                                      'categories_heading_title' => oos_db_prepare_input($_POST['categories_heading_title'][$lang_id]),
                                      'categories_description' => oos_db_prepare_input($_POST['categories_description'][$lang_id]),
                                      'categories_description_meta' => oos_db_prepare_input($_POST['categories_description_meta'][$lang_id]),
                                      'categories_keywords_meta' => oos_db_prepare_input($_POST['categories_keywords_meta'][$lang_id]));
            }

            if ($action == 'insert_category') {
              $insert_sql_data = array('categories_id' => $categories_id,
                                       'categories_languages_id' => $languages[$i]['id']);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              oos_db_perform($oostable['categories_description'], $sql_data_array);
            } elseif ($action == 'update_category') {
              oos_db_perform($oostable['categories_description'], $sql_data_array, 'update', 'categories_id = \'' . $categories_id . '\' and categories_languages_id = \'' . $languages[$i]['id'] . '\'');
            }
          }

          if (ALLOW_CATEGORY_DESCRIPTIONS == 'true') {
            $categories_image = (($categories_image == 'none') ? '' : oos_db_prepare_input($categories_image));
            $dbconn->Execute("UPDATE " . $oostable['categories'] . " SET categories_image = '" . oos_db_input($categories_image) . "' WHERE categories_id = '" .  oos_db_input($categories_id) . "'");
            $categories_image = '';
          } else {
            $categories_image = oos_get_uploaded_file('categories_image');
            $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);

            if (is_uploaded_file($categories_image['tmp_name'])) {
              $dbconn->Execute("UPDATE " . $oostable['categories'] . " SET categories_image = '" . $categories_image['name'] . "' WHERE categories_id = '" . oos_db_input($categories_id) . "'");
              oos_get_copy_uploaded_file($categories_image, $image_directory);
            }
          }
          oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $categories_id));
        }
        break;

      case 'delete_category_confirm':
        if (isset($_POST['categories_id'])) {
          $categories = oos_get_category_tree($categories_id, '', '0', '', true);
          $products = array();
          $products_delete = array();

          for ($i = 0, $n = count($categories); $i < $n; $i++) {
            $product_ids_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . $categories[$i]['id'] . "'");
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

            $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . $key . "' and categories_id not in (" . $category_ids . ")");
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

        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath));
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
        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath));
        break;

      case 'move_category_confirm':
        if ( ($_POST['categories_id']) && ($_POST['categories_id'] != $_POST['move_to_category_id']) ) {
          $new_parent_id = $move_to_category_id;
          $dbconn->Execute("UPDATE " . $oostable['categories'] . " SET parent_id = '" . oos_db_input($new_parent_id) . "', last_modified = now() WHERE categories_id = '" . oos_db_input($categories_id) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $new_parent_id . '&cID=' . $categories_id));
        break;

      case 'move_product_confirm':
        $products_id = oos_db_prepare_input($_POST['products_id']);
        $new_parent_id = oos_db_prepare_input($_POST['move_to_category_id']);

        $duplicate_check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . oos_db_input($products_id) . "' and categories_id = '" . oos_db_input($new_parent_id) . "'");
        $duplicate_check = $duplicate_check_result->fields;
        if ($duplicate_check['total'] < 1) $dbconn->Execute("UPDATE " . $oostable['products_to_categories'] . " SET categories_id = '" . oos_db_input($new_parent_id) . "' WHERE products_id = '" . oos_db_input($products_id) . "' and categories_id = '" . $current_category_id . "'");

        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $new_parent_id . '&pID=' . $products_id));
        break;

      case 'copy_to_confirm':
        if (isset($_POST['products_id']) && isset($_POST['categories_id'])) {
          $products_id = oos_db_prepare_input($_POST['products_id']);
          $categories_id = oos_db_prepare_input($_POST['categories_id']);

          if ($_POST['copy_as'] == 'link') {
            if ($_POST['categories_id'] != $current_category_id) {
              $check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . oos_db_input($products_id) . "' and categories_id = '" . oos_db_input($categories_id) . "'");
              $check = $check_result->fields;
              if ($check['total'] < '1') {
                $dbconn->Execute("INSERT INTO " . $oostable['products_to_categories'] . " (products_id, categories_id) VALUES ('" . oos_db_input($products_id) . "', '" . oos_db_input($categories_id) . "')");
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
            $description_result = $dbconn->Execute("SELECT products_languages_id, products_name, products_description, products_short_description, products_url, products_description_meta, products_keywords_meta  FROM " . $oostable['products_description'] . " WHERE products_id = '" . intval($products_id) . "'");
            while ($description = $description_result->fields) {
              $dbconn->Execute("INSERT INTO " . $oostable['products_description'] . "
                           (products_id,
                            products_languages_id,
                            products_name,
                            products_description,
							products_short_description,
                            products_url,
                            products_viewed,
                            products_description_meta,
                            products_keywords_meta)
                            VALUES ('" . $dup_products_id . "',
                                    '" . $description['products_languages_id'] . "',
                                    '" . addslashes($description['products_name']) . "',
                                    '" . addslashes($description['products_description']) . "',
                                    '" . addslashes($description['products_short_description']) . "',
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

              // Close result set
              $products_copy_from_result->Close();

            }
          }
        }

        oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $categories_id . '&pID=' . $products_id));
        break;
    }
  }

// check if the catalog image directory exists
  if (is_dir(OOS_ABSOLUTE_PATH . OOS_IMAGES)) {
    if (!is_writeable(OOS_ABSOLUTE_PATH . OOS_IMAGES)) $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE, 'error');
  } else {
    $messageStack->add(ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST, 'error');
  }
  $no_js_general = true;
  require 'includes/oos_header.php';
?>
<script language="javascript"><!--
function popupImageWindow(url) {
  window.open(url,'popupImageWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<div id="spiffycalendar" class="text"></div>
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require 'includes/oos_blocks.php'; ?>
    </table></td>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
    if ($action == 'new_category_ACD' || $action == 'edit_category_ACD') {
      if (isset($_GET['cID']) && empty($_POST)) {
        $categoriestable = $oostable['categories'];
        $categories_descriptiontable = $oostable['categories_description'];
        $query = "SELECT c.categories_id, cd.categories_name, cd.categories_heading_title,
                         cd.categories_description, cd.categories_description_meta, cd.categories_keywords_meta,
                         c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified
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
      } else {
        $cInfo = new objectInfo(array());
      }

      $languages = oos_get_languages();

      $text_new_or_edit = ($action=='new_category_ACD') ? TEXT_INFO_HEADING_NEW_CATEGORY : TEXT_INFO_HEADING_EDIT_CATEGORY;
?>
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo sprintf($text_new_or_edit, oos_output_generated_category_path($current_category_id)); ?></td>
              <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
        </tr>
        <tr><?php echo oos_draw_form('new_category', $aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=new_category_preview', 'post', 'enctype="multipart/form-data"'); ?>
          <td><table border="0" cellspacing="0" cellpadding="2">
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_NAME; ?></td>
              <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', (($categories_name[$languages[$i]['id']]) ? stripslashes($categories_name[$languages[$i]['id']]) : oos_get_category_name($cInfo->categories_id, $languages[$i]['id']))); ?></td>
            </tr>
<?php
      }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_HEADING_TITLE; ?></td>
              <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('categories_heading_title[' . $languages[$i]['id'] . ']', (($categories_heading_title[$languages[$i]['id']]) ? stripslashes($categories_heading_title[$languages[$i]['id']]) : oos_get_category_heading_title($cInfo->categories_id, $languages[$i]['id']))); ?></td>
            </tr>
<?php
      }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
<?php
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('categories_description[' . $languages[$i]['id'] . ']', 'soft', '70', '15', (($categories_description[$languages[$i]['id']]) ? stripslashes($categories_description[$languages[$i]['id']]) : oos_get_category_description($cInfo->categories_id, $languages[$i]['id']))); ?></td>
                </tr>
              </table></td>
            </tr>
<?php
      }
	  
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" width="33%" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_DESCRIPTION_META; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('categories_description_meta[' . $languages[$i]['id'] . ']', 'soft', '70', '4', (($categories_description_meta[$languages[$i]['id']]) ? stripslashes($categories_description_meta[$languages[$i]['id']]) : oos_get_category_description_meta($cInfo->categories_id, $languages[$i]['id']))); ?></td>
                </tr>
              </table></td>
            </tr>
<?php
      }
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" width="33%" valign="top"><?php if ($i == 0) echo TEXT_EDIT_CATEGORIES_KEYWORDS_META; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('categories_keywords_meta[' . $languages[$i]['id'] . ']', 'soft', '70', '4', (($categories_keywords_meta[$languages[$i]['id']]) ? stripslashes($categories_keywords_meta[$languages[$i]['id']]) : oos_get_category_keywords_meta($cInfo->categories_id, $languages[$i]['id']))); ?></td>
                </tr>
              </table></td>
            </tr>
<?php
     }
?>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
            <tr>
              <td class="main"><?php echo TEXT_EDIT_CATEGORIES_IMAGE; ?></td>
              <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_file_field('categories_image') . '<br />' . oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . $cInfo->categories_image . oos_draw_hidden_field('categories_previous_image', $cInfo->categories_image); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
            <tr>
              <td class="main"><?php echo TEXT_EDIT_SORT_ORDER; ?></td>
              <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'); ?></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
        </tr>
        <tr>
          <td class="main" align="right"><?php echo oos_draw_hidden_field('categories_date_added', (($cInfo->date_added) ? $cInfo->date_added : date('Y-m-d'))) . oos_draw_hidden_field('parent_id', $cInfo->parent_id) . oos_image_swap_submits('preview','preview_off.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
        </form></tr>
<?php 
  } elseif ($action == 'new_category_preview') {
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

    $form_action = ($_GET['cID']) ? 'update_category' : 'insert_category';

    echo oos_draw_form($form_action, $aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $_GET['cID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

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
            <td class="pageHeading"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . $cInfo->categories_heading_title; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
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
        $back_url = $aFilename['categories'];
        $back_url_params = 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id;
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params, 'NONSSL') . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
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

      echo oos_image_swap_submits('back','back_off.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['cID'])) {
        echo oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE);
      } else {
        echo oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $_GET['cID']) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }
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

    // Close result set
    $image_icon_status_result->Close();
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
            <td align="right"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr><?php echo oos_draw_form('search', $aFilename['categories'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_SEARCH . ' ' . oos_draw_input_field('search', $_GET['search']); ?></td>
              </form></tr>
              <tr><?php echo oos_draw_form('goto', $aFilename['categories'], '', 'get'); ?>
                <td class="smallText" align="right"><?php echo HEADING_TITLE_GOTO . ' ' . oos_draw_pull_down_menu('cPath', oos_get_category_tree(), $current_category_id, 'onChange="this.form.submit();"'); ?></td>
              </form></tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
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
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['categories'], oos_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['categories'], oos_get_path($categories['categories_id'])) . '">' . oos_image(OOS_IMAGES . 'icons/folder.gif', ICON_FOLDER) . '</a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="dataTableContent" align="center">&nbsp;</td>
                 <td class="dataTableContent" align="center">
 <?php
       if ($categories['categories_status'] == '1') {
         echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=0&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
       } else {
         echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=1&cID=' . $categories['categories_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
       }
?></td>
                <td class="dataTableContent" align="center">&nbsp;<?php echo $categories['sort_order']; ?>&nbsp;</td>
                <td class="dataTableContent" align="right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $categories['categories_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }

    // Close result set
    $categories_result->Close();

    $products_count = 0;
    if (isset($_GET['search'])) {
      $products_result = $dbconn->Execute("SELECT p.products_id, p.products_model, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_tax_class_id, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p2c.categories_id, p.products_price_list, p.products_discount_allowed, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' and p.products_id = p2c.products_id and pd.products_name like '%" . $_GET['search'] . "%' ORDER BY pd.products_name");
    } else {
      $products_result = $dbconn->Execute("SELECT p.products_id, p.products_model, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.products_price_list, p.products_discount_allowed, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' ORDER BY pd.products_name");
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
        echo '              <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['products'], 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '\'">' . "\n";
      } else {
        echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aFilename['products'], 'cPath=' . $cPath . '&pID=' . $products['products_id'] . '&action=new_product_preview&read=only') . '">' . oos_image(OOS_IMAGES . 'icons/preview.gif', ICON_PREVIEW) . '</a>&nbsp;' . '#' . $products['products_id'] . ' ' . $products['products_name']; ?></td>
                <td class="dataTableContent"><?php echo oos_get_manufacturers_name($products['products_id']) ?></td>
                <td class="dataTableContent" align="center">
<?php
/*
  if (STOCK_CHECK == 'true') {
    switch ($products['products_status']) {
     case '0':
          echo oos_image(OOS_IMAGES . 'icon_status_red.gif', $image_icon_status_array[0]['text'], 10, 10);
        break;

     case '1': 
          echo oos_image(OOS_IMAGES . 'icon_status_blue.gif', $image_icon_status_array[1]['text'], 10, 10);
       break;

     case '2':
          echo oos_image(OOS_IMAGES . 'icon_status_yellow.gif', $image_icon_status_array[2]['text'], 10, 10);
        break;

     case '3':
          echo oos_image(OOS_IMAGES . 'icon_status_green.gif', $image_icon_status_array[3]['text'], 10, 10);
         break;

     case '4':
          echo oos_image(OOS_IMAGES . 'icon_status_hot.gif', $image_icon_status_array[4]['text'], 10, 10);
        break;

     default :
          echo oos_image(OOS_IMAGES . 'icon_status_info.gif', $image_icon_status_array[5]['text'], 10, 10);
        break;
    }
  } else {
    if ($products['products_status'] == '0') {
      echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=3&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    }
  }
*/
    if ($products['products_status'] == '0') {
      echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=3&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'action=setflag&flag=0&pID=' . $products['products_id'] . '&cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    }

?></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_sort_order']; ?></td>
                <td class="dataTableContent" align="right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) { echo oos_image(OOS_IMAGES . 'icon_arrow_right.gif', ''); } else { echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $products['products_id']) . '">' . oos_image(OOS_IMAGES . 'icon_information.gif', IMAGE_ICON_INFO) . '</a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $products_result->MoveNext();
    }

    if ($cPath_array) {
      $cPath_back = '';
      for($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
        if ($cPath_back == '') {
          $cPath_back .= $cPath_array[$i];
        } else {
          $cPath_back .= '_' . $cPath_array[$i];
        }
      }
    }

    $cPath_back = ($cPath_back) ? 'cPath=' . $cPath_back : '';
?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br />' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"><?php if ($cPath) echo '<a href="' . oos_href_link_admin($aFilename['categories'], $cPath_back . '&cID=' . $current_category_id) . '">' . oos_image_swap_button('back','back_off.gif', IMAGE_BACK) . '</a>&nbsp;'; if (!$_GET['search']) echo '<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&action=new_category') . '">' . oos_image_swap_button('newcategorie','new_category_off.gif', IMAGE_NEW_CATEGORY) . '</a>&nbsp;<a href="' . oos_href_link_admin($aFilename['products'], 'cPath=' . $cPath . '&action=new_product') . '">' . oos_image_swap_button('newprodukt','new_product_off.gif', IMAGE_NEW_PRODUCT) . '</a>'; ?>&nbsp;</td>
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

        $contents = array('form' => oos_draw_form('new_slave_product', $aFilename['categories'], 'action=new_slave_product&cPath=' . $cPath . '&pID=' . $pInfo->products_id, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => '<br />' . TEXT_ADD_SLAVE_PRODUCT . '<br />' . oos_draw_input_field('slave_product_id', '', 'size="10"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');

        $contents[] = array('text' => '<br />' . TEXT_CURRENT_SLAVE_PRODUCTS);
        $slave_table_result = $dbconn->Execute("SELECT p2m.master_id, p2m.slave_id FROM " . $oostable['products_to_master'] . " p2m WHERE master_id = '" . $pInfo->products_id . "'");
        while ($slave_table = $slave_table_result->fields){
          $slave_products_result = $dbconn->Execute("SELECT p.products_id, p.products_slave_visible, pd.products_name FROM " . $oostable['products'] . " p , " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND p.products_id = '" . $slave_table['slave_id'] . "' AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name LIMIT 1");
          $slave_products = $slave_products_result->fields;
          if($slave_products['products_slave_visible'] == 1){
            $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aFilename['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
            '<a href="' . oos_href_link_admin($aFilename['categories'], oos_get_all_get_params(array('action')) . 'visible=0&slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_visible') . '">'. 
            oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT) . '</a>');
          } else {
            $contents[] = array('text' => ' ' . $slave_products['products_name'] . ' ' . '<a href="' . oos_href_link_admin($aFilename['categories'], oos_get_all_get_params(array('action', 'pID')) . 'slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_delete') . '">' . oos_image(OOS_IMAGES . 'delete_slave_off.gif', 'Delete Slave') . '</a>'.
            '<a href="' . oos_href_link_admin($aFilename['categories'], oos_get_all_get_params(array('action')) . 'visible=1&slave_id=' . $slave_table['slave_id'] . '&master_id=' . $pInfo->products_id . '&action=slave_visible') . '">'. 
            oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT) . '</a>');
          }
          // Move that ADOdb pointer!
          $slave_table_result->MoveNext();
        }
        break;

      case 'new_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('newcategory', $aFilename['categories'], 'action=insert_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"'));
        $contents[] = array('text' => TEXT_NEW_CATEGORY_INTRO);

        $category_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('categories_name[' . $languages[$i]['id'] . ']');
        }

        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br />' . TEXT_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order', '', 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'edit_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['categories'], 'action=update_category&cPath=' . $cPath, 'post', 'enctype="multipart/form-data"') . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_EDIT_INTRO);

        $category_inputs_string = '';
        $languages = oos_get_languages();
        for ($i = 0, $n = count($languages); $i < $n; $i++) {
          $category_inputs_string .= '<br />' . oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('categories_name[' . $languages[$i]['id'] . ']', oos_get_category_name($cInfo->categories_id, $languages[$i]['id']));
        }

        $contents[] = array('text' => '<br />' . TEXT_EDIT_CATEGORIES_NAME . $category_inputs_string);
        $contents[] = array('text' => '<br />' . oos_image(OOS_SHOP_IMAGES . $cInfo->categories_image, $cInfo->categories_name) . '<br />' . OOS_SHOP_IMAGES . '<br /><b>' . $cInfo->categories_image . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_EDIT_CATEGORIES_IMAGE . '<br />' . oos_draw_file_field('categories_image'));
        $contents[] = array('text' => '<br />' . TEXT_EDIT_SORT_ORDER . '<br />' . oos_draw_input_field('sort_order', $cInfo->sort_order, 'size="2"'));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('save','save_off.gif', IMAGE_SAVE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['categories'], 'action=delete_category_confirm&cPath=' . $cPath) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'move_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('categories', $aFilename['categories'], 'action=move_category_confirm') . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_CATEGORIES_INTRO, $cInfo->categories_name));
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $cInfo->categories_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree('0', '', $cInfo->categories_id), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('move','move_off.gif', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('products', $aFilename['categories'], 'action=delete_product_confirm&cPath=' . $cPath) . oos_draw_hidden_field('products_id', $pInfo->products_id));
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
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'move_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_MOVE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('products', $aFilename['categories'], 'action=move_product_confirm&cPath=' . $cPath) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => sprintf(TEXT_MOVE_PRODUCTS_INTRO, $pInfo->products_name));
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . sprintf(TEXT_MOVE, $pInfo->products_name) . '<br />' . oos_draw_pull_down_menu('move_to_category_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('move','move_off.gif', IMAGE_MOVE) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      case 'copy_to':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_COPY_TO . '</b>');

        $contents = array('form' => oos_draw_form('copy_to', $aFilename['categories'], 'action=copy_to_confirm&cPath=' . $cPath) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_INFO_COPY_TO_INTRO);
        $contents[] = array('text' => '<br />' . TEXT_INFO_CURRENT_CATEGORIES . '<br /><b>' . oos_output_generated_category_path($pInfo->products_id, 'product') . '</b>');
        $contents[] = array('text' => '<br />' . TEXT_CATEGORIES . '<br />' . oos_draw_pull_down_menu('categories_id', oos_get_category_tree(), $current_category_id));
        $contents[] = array('text' => '<br />' . TEXT_HOW_TO_COPY . '<br />' . oos_draw_radio_field('copy_as', 'link', true) . ' ' . TEXT_COPY_AS_LINK . '<br />' . oos_draw_radio_field('copy_as', 'duplicate') . ' ' . TEXT_COPY_AS_DUPLICATE);
        $contents[] = array('text' => '<br />' . oos_image(OOS_IMAGES . 'pixel_black.gif','','100%','3'));
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES_ONLY);
        $contents[] = array('text' => '<br />' . TEXT_COPY_ATTRIBUTES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_yes', true) . ' ' . TEXT_COPY_ATTRIBUTES_YES . '<br />' . oos_draw_radio_field('copy_attributes', 'copy_attributes_no') . ' ' . TEXT_COPY_ATTRIBUTES_NO);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('copy','copy_off.gif', IMAGE_COPY) . ' <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&cID=' . $cInfo->categories_id . '&action=move_category') . '">' . oos_image_swap_button('move','move_off.gif', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cID) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            if (oos_is_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br />' . oos_info_image($cInfo->categories_image, $cInfo->categories_name, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT) . '<br />' . $cInfo->categories_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . $pInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['products'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a> <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=move_product') . '">' . oos_image_swap_button('move','move_off.gif', IMAGE_MOVE) . '</a> <a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=copy_to') . '">' . oos_image_swap_button('copy_to','copy_to_off.gif', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=slave_products') . '">' . oos_image_swap_button('slave','slave_off.gif', IMAGE_SLAVE) . '</a>');

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
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['specials'], 'pID=' . $pInfo->products_id . '&action=new') . '">' . oos_image_swap_button('specials','specials_off.gif', IMAGE_SPECIALS) . '</a>');
              } else {
                $specials = $specials_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['specials'], 'sID=' . $specials['specials_id'] . '&action=edit') . '">' . oos_image_swap_button('specials','specials_off.gif', IMAGE_SPECIALS) . '</a>');
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
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['featured'], 'pID=' . $pInfo->products_id . '&action=new') . '">' . oos_image_swap_button('featured','featured_off.gif', IMAGE_FEATURED) . '</a>');
              } else {
                $featured = $featured_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['featured'], 'fID=' . $featured['featured_id'] . '&action=edit') . '">' . oos_image_swap_button('featured','featured_off.gif', IMAGE_FEATURED) . '</a>');
              }
            }

            $contents[] = array('text' => '#' . $pInfo->products_id . ' ' . TEXT_CATEGORIES . ' ' . oos_get_categories_name($current_category_id) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($pInfo->products_date_added));
            if (oos_is_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . oos_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br /><a href="' . oos_href_link_admin($aFilename['products'], 'cPath=' . $cPath . '&pID=' . $pInfo->products_id . '&action=new_product_preview&read=only') . '">' . oos_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br />' . $pInfo->products_image);

            $oosPrice = $pInfo->products_price;
            $oosPriceList = $pInfo->products_price_list;

            if (OOS_PRICE_IS_BRUTTO == 'true' && ($_GET['read'] == 'only' || $action != 'new_product_preview') ){
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
              if (OOS_PRICE_IS_BRUTTO == 'true') {
                $oosDiscount1Netto = round($oosDiscount1,TAX_DECIMAL_PLACES);
                $oosDiscount1 = ($oosDiscount1*($tax[tax_rate]+100)/100); 
              }
              $oosDiscount1 = round($oosDiscount1,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '<br /><br /><b>' . TEXT_DISCOUNTS_TITLE . ':</b>');
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount1_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount1_qty . ' ' . $currencies->format($oosDiscount1) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount1Netto));
            }
            if ( $pInfo->products_discount2_qty > 0 ) {
              $oosDiscount2 = $pInfo->products_discount2; 
              if (OOS_PRICE_IS_BRUTTO == 'true') {
                $oosDiscount2Netto = round($oosDiscount2,TAX_DECIMAL_PLACES);
                $oosDiscount2 = ($oosDiscount2*($tax[tax_rate]+100)/100); 
              }
              $oosDiscount2 = round($oosDiscount2,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount2_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount2_qty . ' ' . $currencies->format($oosDiscount2) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount2Netto));
            }
            if ( $pInfo->products_discount3_qty > 0 ) {
              $oosDiscount3 = $pInfo->products_discount3; 
              if (OOS_PRICE_IS_BRUTTO == 'true') {
                $oosDiscount3Netto = round($oosDiscount3,TAX_DECIMAL_PLACES);
                $oosDiscount3 = ($oosDiscount3*($tax[tax_rate]+100)/100); 
              }
              $oosDiscount3 = round($oosDiscount3,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount3_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount3_qty . ' ' . $currencies->format($oosDiscount3) . ' - ' . TEXT_TAX_INFO . $currencies->format($oosDiscount3Netto));
            }
            if ( $pInfo->products_discount4_qty > 0 ) {
               $oosDiscount4 = $pInfo->products_discount4; 
               if (OOS_PRICE_IS_BRUTTO == 'true') {
                 $oosDiscount4Netto = round($oosDiscount4,TAX_DECIMAL_PLACES);
                 $oosDiscount4 = ($oosDiscount4*($tax[tax_rate]+100)/100);  
               }
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
<?php
  }
?>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->


<?php require 'includes/oos_footer.php'; ?>
<br />
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>
