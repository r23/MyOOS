<?php
/* ----------------------------------------------------------------------
   $Id: products.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
  require 'includes/oos_main.php';

  require 'includes/functions/function_categories.php';
  require 'includes/functions/function_image_resize.php';

  require 'includes/classes/class_currencies.php';
  $currencies = new currencies();

  $action = (isset($_GET['action']) ? $_GET['action'] : '');

  if (!empty($action)) {
    switch ($action) {
      case 'insert_product':
      case 'update_product':

        $_POST['products_price'] = str_replace(',', '.', $_POST['products_price']);
        $_POST['products_price_list'] = str_replace(',', '.', $_POST['products_price_list']);
        $_POST['products_discount1'] = str_replace(',', '.', $_POST['products_discount1']);
        $_POST['products_discount2'] = str_replace(',', '.', $_POST['products_discount2']);
        $_POST['products_discount3'] = str_replace(',', '.', $_POST['products_discount3']);
        $_POST['products_discount4'] = str_replace(',', '.', $_POST['products_discount4']);

        $sProductsQuantity = oos_db_prepare_input($_POST['products_quantity']);
        $sProductsStatus = oos_db_prepare_input($_POST['products_status']);

        if (STOCK_CHECK == 'true') {
          if ($sProductsQuantity <=0) {
            $sProductsStatus = 0;
          }
        }

        if (NEW_PRODUCT_PREVIEW == 'false') {
          if ( ($_POST['products_image'] != 'none') && (isset($_FILES['products_image'])) ) {
            $products_image = oos_get_uploaded_file('products_image');
            $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_image['tmp_name'])) {
            $products_image = oos_copy_uploaded_file($products_image, $image_directory);
            if (OOS_IMAGE_SWF == 'true') {
              include 'includes/classes/class_image2swf.php';
              $swf = new Image2swf;
              $filename = explode("[/\\.]", $products_image_name);
              $swf->Main(OOS_ABSOLUTE_PATH . OOS_IMAGES . $products_image_name, $filename[0]);
            }
          } else {
            $products_image = oos_db_prepare_input($_POST['products_previous_image']);
          }

          // copy subimage1 only if modified
          if ( ($_POST['products_subimage1'] != 'none') && (isset($_FILES['products_subimage1'])) ) {
            $products_subimage1 = oos_get_uploaded_file('products_subimage1');
            $subimage1_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage1['tmp_name'])) {
            $products_subimage1 = oos_copy_uploaded_file($products_subimage1, $subimage1_directory);
          } else {
            $products_subimage1 = oos_db_prepare_input($_POST['products_previous_subimage1']);
          }

          // copy subimage2 only if modified
          if ( ($_POST['products_subimage2'] != 'none') && (isset($_FILES['products_subimage2'])) ) {
            $products_subimage2 = oos_get_uploaded_file('products_subimage2');
            $subimage2_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage2['tmp_name'])) {
            $products_subimage2 = oos_copy_uploaded_file($products_subimage2, $subimage2_directory);
          } else {
            $products_subimage2 = oos_db_prepare_input($_POST['products_previous_subimage2']);
          }

          // copy subimage3 only if modified
          if ( ($_POST['products_subimage3'] != 'none') && (isset($_FILES['products_subimage3'])) ) {
            $products_subimage3 = oos_get_uploaded_file('products_subimage3');
            $subimage3_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage3['tmp_name'])) {
            $products_subimage3 = oos_copy_uploaded_file($products_subimage3, $subimage3_directory);
          } else {
            $products_subimage3 = oos_db_prepare_input($_POST['products_previous_subimage3']);
          }

          // copy subimage4 only if modified
          if ( ($_POST['products_subimage4'] != 'none') && (isset($_FILES['products_subimage4'])) ) {
            $products_subimage4 = oos_get_uploaded_file('products_subimage4');
            $subimage4_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage4['tmp_name'])) {
            $products_subimage4 = oos_copy_uploaded_file($products_subimage4, $subimage4_directory);
          } else {
            $products_subimage4 = oos_db_prepare_input($_POST['products_previous_subimage4']);
          }

          // copy subimage5 only if modified
          if ( ($_POST['products_subimage5'] != 'none') && (isset($_FILES['products_subimage5'])) ) {
            $products_subimage5 = oos_get_uploaded_file('products_subimage5');
            $subimage5_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage5['tmp_name'])) {
            $products_subimage5 = oos_copy_uploaded_file($products_subimage5, $subimage5_directory);
          } else {
            $products_subimage5 = oos_db_prepare_input($_POST['products_previous_subimage5']);
          }

          // copy subimage6 only if modified
          if ( ($_POST['products_subimage6'] != 'none') && (isset($_FILES['products_subimage6'])) ) {
            $products_subimage6 = oos_get_uploaded_file('products_subimage6');
            $subimage6_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
          }
          if (is_uploaded_file($products_subimage6['tmp_name'])) {
            $products_subimage6 = oos_copy_uploaded_file($products_subimage6, $subimage6_directory);
          } else {
            $products_subimage6 = oos_db_prepare_input($_POST['products_previous_subimage6']);
          }
        }

        if (OOS_PRICE_IS_BRUTTO == 'true' && $_POST['products_price']){
          $tax_ratestable = $oostable['tax_rates'];
          $tax_result = $dbconn->Execute("SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '".$_POST['products_tax_class_id']."' ");
          $tax = $tax_result->fields;
          $_POST['products_price'] = ($_POST['products_price']/($tax[tax_rate]+100)*100);
          $_POST['products_price_list'] = ($_POST['products_price_list']/($tax[tax_rate]+100)*100);
          $_POST['products_discount1'] = ($_POST['products_discount1']/($tax[tax_rate]+100)*100);
          $_POST['products_discount2'] = ($_POST['products_discount2']/($tax[tax_rate]+100)*100);
          $_POST['products_discount3'] = ($_POST['products_discount3']/($tax[tax_rate]+100)*100);
          $_POST['products_discount4'] = ($_POST['products_discount4']/($tax[tax_rate]+100)*100);
        }
        if ( isset($_POST['edit_x']) || isset($_POST['edit_y']) ) {
          $action = 'new_product';
        } else {
          if ($_POST['delete_image'] == 'yes') {
            if (oos_duplicate_product_image_check($products_image)) {
              oos_remove_product_image($products_image);
            }
          }
          if ($_POST['delete_subimage1'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage1)) {
              oos_remove_product_subimage($products_subimage1);
            }
          }
          if ($_POST['delete_subimage2'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage2)) {
              oos_remove_product_subimage($products_subimage2);
            }
          }
          if ($_POST['delete_subimage3'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage3)) {
              oos_remove_product_subimage($products_subimage3);
            }
          }
          if ($_POST['delete_subimage4'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage4)) {
              oos_remove_product_subimage($products_subimage4);
            }
          }
          if ($_POST['delete_subimage5'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage5)) {
              oos_remove_product_subimage($products_subimage5);
            }
          }
          if ($_POST['delete_subimage6'] == 'yes') {
            if (oos_duplicate_product_subimage_check($products_subimage6)) {
              oos_remove_product_subimage($products_subimage6);
            }
          }
          if ( ($_POST['delete_image'] == 'yes') || ($_POST['remove_image'] == 'yes') ) {
            $products_image = 'none';
          }
          if ( ($_POST['delete_subimage1'] == 'yes') || ($_POST['remove_subimage1'] == 'yes') ) {
            $products_subimage1 = 'none';
          }
          if ( ($_POST['delete_subimage2'] == 'yes') || ($_POST['remove_subimage2'] == 'yes') ) {
            $products_subimage2 = 'none';
          }
          if ( ($_POST['delete_subimage3'] == 'yes') || ($_POST['remove_subimage3'] == 'yes') ) {
            $products_subimage3 = 'none';
          }
          if ( ($_POST['delete_subimage4'] == 'yes') || ($_POST['remove_subimage4'] == 'yes') ) {
            $products_subimage4 = 'none';
          }
          if ( ($_POST['delete_subimage5'] == 'yes') || ($_POST['remove_subimage5'] == 'yes') ) {
            $products_subimage5 = 'none';
          }
          if ( ($_POST['delete_subimage6'] == 'yes') || ($_POST['remove_subimage6'] == 'yes') ) {
            $products_subimage6 = 'none';
          }

          $products_id = oos_db_prepare_input($_GET['pID']);
          $products_date_available = oos_db_prepare_input($_POST['products_date_available']);

          if (isset($_POST['products_base_price']) ) {
            $products_base_price = oos_db_prepare_input($_POST['products_base_price']);
            $products_product_quantity = oos_db_prepare_input($_POST['products_product_quantity']);
            $products_base_quantity = oos_db_prepare_input($_POST['products_base_quantity']);
            $products_base_unit = oos_db_prepare_input($_POST['products_base_unit']);
          } else {
            $products_base_price = 1.0;
            $products_product_quantity = 1.0;
            $products_base_quantity = 1.0;
            $products_base_unit = '';
          }

          if (isset($_POST['products_quantity_decimal']) ) {
            $products_quantity_decimal = oos_db_prepare_input($_POST['products_quantity_decimal']);
          } else {
            $products_quantity_decimal = '0';
          }

          $products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

          $sql_data_array = array('products_quantity' => $sProductsQuantity,
                                  'products_reorder_level' => oos_db_prepare_input($_POST['products_reorder_level']),
                                  'products_model' => oos_db_prepare_input($_POST['products_model']),
                                  'products_ean' => oos_db_prepare_input($_POST['products_ean']),
                                  'products_image' => (($products_image == 'none') ? '' : oos_db_prepare_input($products_image)),
                                  'products_subimage1' => (($products_subimage1 == 'none') ? '' : oos_db_prepare_input($products_subimage1)),
                                  'products_subimage2' => (($products_subimage2 == 'none') ? '' : oos_db_prepare_input($products_subimage2)),
                                  'products_subimage3' => (($products_subimage3 == 'none') ? '' : oos_db_prepare_input($products_subimage3)),
                                  'products_subimage4' => (($products_subimage4 == 'none') ? '' : oos_db_prepare_input($products_subimage4)),
                                  'products_subimage5' => (($products_subimage5 == 'none') ? '' : oos_db_prepare_input($products_subimage5)),
                                  'products_subimage6' => (($products_subimage6 == 'none') ? '' : oos_db_prepare_input($products_subimage6)),
                                  'products_zoomify' => (($products_zoomify == 'none') ? '' : oos_db_prepare_input($products_zoomify)),
                                  'products_price' => oos_db_prepare_input($_POST['products_price']),
                                  'products_base_price' => $products_base_price,
                                  'products_product_quantity' => $products_product_quantity,
                                  'products_base_quantity' => $products_base_quantity,
                                  'products_base_unit' => $products_base_unit,
                                  'products_date_available' => $products_date_available,
                                  'products_weight' => oos_db_prepare_input($_POST['products_weight']),
                                  'products_status' => $sProductsStatus,
                                  'products_tax_class_id' => oos_db_prepare_input($_POST['products_tax_class_id']),
                                  'products_units_id' => oos_db_prepare_input($_POST['products_units_id']),
                                  'manufacturers_id' => oos_db_prepare_input($_POST['manufacturers_id']),
                                  'products_price_list' => oos_db_prepare_input($_POST['products_price_list']),
                                  'products_discount_allowed' => oos_db_prepare_input($_POST['products_discount_allowed']),
                                  'products_quantity_decimal' => $products_quantity_decimal,
                                  'products_quantity_order_min' => oos_db_prepare_input($_POST['products_quantity_order_min']),
                                  'products_quantity_order_units' => oos_db_prepare_input($_POST['products_quantity_order_units']),
                                  'products_discount1' => oos_db_prepare_input($_POST['products_discount1']),
                                  'products_discount1_qty' => oos_db_prepare_input($_POST['products_discount1_qty']),
                                  'products_discount2' => oos_db_prepare_input($_POST['products_discount2']),
                                  'products_discount2_qty' => oos_db_prepare_input($_POST['products_discount2_qty']),
                                  'products_discount3' => oos_db_prepare_input($_POST['products_discount3']),
                                  'products_discount3_qty' => oos_db_prepare_input($_POST['products_discount3_qty']),
                                  'products_discount4' => oos_db_prepare_input($_POST['products_discount4']),
                                  'products_discount4_qty' => oos_db_prepare_input($_POST['products_discount4_qty']),
                                  'products_sort_order' => oos_db_prepare_input($_POST['products_sort_order']),
                                  );

          if ($action == 'insert_product') {
            $insert_sql_data = array('products_date_added' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['products'], $sql_data_array);
            $products_id = $dbconn->Insert_ID();

            if (MULTIPLE_CATEGORIES_USE == 'false') {
              $products_to_categoriestable = $oostable['products_to_categories'];
              $dbconn->Execute("INSERT INTO $products_to_categoriestable (products_id, categories_id) VALUES ('" . $products_id . "', '" . $current_category_id . "')");
            }

          } elseif ($action == 'update_product') {
            $update_sql_data = array('products_last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            oos_db_perform($oostable['products'], $sql_data_array, 'update', 'products_id = \'' . oos_db_input($products_id) . '\'');

            if (MULTIPLE_CATEGORIES_USE == 'true') {
              $products_to_categoriestable = $oostable['products_to_categories'];
              $dbconn->Execute("DELETE FROM $products_to_categoriestable WHERE products_id = '". $products_id . "'");
            }
          }
          if (MULTIPLE_CATEGORIES_USE == 'true') {
            if (isset($_POST['categories_ids']) && !empty($_POST['categories_ids']) && is_array($_POST['categories_ids'])) {
              $selected_catids = $_POST['categories_ids'];
            } else {
              $selected_catids = array('0');
            }
            foreach ($selected_catids as $current_category_id)  {
              $products_to_categoriestable = $oostable['products_to_categories'];
              $dbconn->Execute("INSERT INTO $products_to_categoriestable (products_id, categories_id) values ('" . $products_id . "', '" . $current_category_id . "')");
            }
          }
          if (oos_empty($_GET['cPath'])) {
            $cPath = $current_category_id;
          }

          $languages = oos_get_languages();
          for ($i = 0, $n = count($languages); $i < $n; $i++) {
            $lang_id = $languages[$i]['id'];

            $sql_data_array = array('products_name' => oos_db_prepare_input($_POST['products_name'][$lang_id]),
									'products_short_description'=> oos_db_prepare_input($_POST['products_short_description_' .$languages[$i]['id']]),
                                    'products_description' => oos_db_prepare_input($_POST['products_description_' .$languages[$i]['id']]),
                                    'products_description_meta' => oos_db_prepare_input($_POST['products_description_meta_' .$languages[$i]['id']]),
                                    'products_keywords_meta' => oos_db_prepare_input($_POST['products_keywords_meta_' .$languages[$i]['id']]),
                                    'products_url' => oos_db_prepare_input($_POST['products_url'][$lang_id]));

            if ($action == 'insert_product') {
              $insert_sql_data = array('products_id' => $products_id,
                                       'products_languages_id' => $lang_id);

              $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

              oos_db_perform($oostable['products_description'], $sql_data_array);
            } elseif ($action == 'update_product') {
              oos_db_perform($oostable['products_description'], $sql_data_array, 'update', 'products_id = \'' . oos_db_input($products_id) . '\' and products_languages_id = \'' . $lang_id . '\'');
            }
          }
          oos_redirect_admin(oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $products_id));
        }
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
  window.open(url,'popupImageWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
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
  if ($action == 'new_product') {
    if (isset($_GET['pID']) && empty($_POST)) {
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $product_result = $dbconn->Execute("SELECT pd.products_name, pd.products_description, pd.products_short_description, pd.products_url,
                                                 pd.products_description_meta, pd.products_keywords_meta, p.products_id,
                                                 p.products_quantity, p.products_reorder_level, p.products_model,
                                                 p.products_ean, p.products_image, p.products_subimage1,
                                                 p.products_subimage2, p.products_subimage3, p.products_subimage4,
                                                 p.products_subimage5, p.products_subimage6, p.products_zoomify,
                                                 p.products_price, p.products_base_price, p.products_base_quantity,
                                                 p.products_product_quantity, p.products_base_unit,
                                                 p.products_weight, p.products_date_added, p.products_last_modified,
                                                 date_format(p.products_date_available, '%Y-%m-%d') AS products_date_available,
                                                 p.products_status, p.products_tax_class_id, p.products_units_id, p.manufacturers_id,
                                                 p.products_price_list, p.products_discount_allowed, p.products_quantity_decimal,
                                                 p.products_quantity_order_min, p.products_quantity_order_units,
                                                 p.products_discount1, p.products_discount2, p.products_discount3,
                                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty,
                                                 p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order
                                            FROM $productstable p,
                                                 $products_descriptiontable pd
                                           WHERE p.products_id = '" . $_GET['pID'] . "' AND
                                                 p.products_id = pd.products_id AND
                                                 pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $product = $product_result->fields;

      $pInfo = new objectInfo($product);
    } elseif (oos_is_not_null($_POST)) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_description = $_POST['products_description'];
      $products_description_meta = $_POST['products_description_meta'];
      $products_keywords_meta = $_POST['products_keywords_meta'];
      $products_url = $_POST['products_url'];
	  $products_short_description = $_POST['products_short_description'];
    } else {
      $pInfo = new objectInfo(array());
      $pInfo->products_status = DEFAULT_PRODUTS_STATUS_ID;
      $pInfo->products_base_price = 1.0;
      $pInfo->products_product_quantity = 1.0;
      $pInfo->products_base_quantity = 1.0;
      $pInfo->products_units_id = DEFAULT_PRODUCTS_UNITS_ID;
    }

    $manufacturers_array = array();
    $manufacturers_array = array(array('id' => '', 'text' => TEXT_NONE));
    $manufacturerstable = $oostable['manufacturers'];
    $manufacturers_result = $dbconn->Execute("SELECT manufacturers_id, manufacturers_name FROM $manufacturerstable ORDER BY manufacturers_name");
    while ($manufacturers = $manufacturers_result->fields) {
      $manufacturers_array[] = array('id' => $manufacturers['manufacturers_id'],
                                     'text' => $manufacturers['manufacturers_name']);

      // Move that ADOdb pointer!
      $manufacturers_result->MoveNext();
    }
    if (MULTIPLE_CATEGORIES_USE == 'true') {
      $categories_array_selected = array(array('id' => ''));
      if (isset($_GET['action']) && ($_GET['action'] == 'new_product') && (isset($_GET['cPath'])) )  {
        $categories_array_selected = array(array('id' => (int)$_GET['cPath']));
      }

      $products_to_categoriestable = $oostable['products_to_categories'];
      $categories_result_selected = $dbconn->Execute("SELECT categories_id FROM $products_to_categoriestable WHERE products_id = '" . $_GET['pID'] . "'");
      while ($categories_selected = $categories_result_selected->fields) {
        $categories_array_selected[] = array('id' => $categories_selected['categories_id']);

         // Move that ADOdb pointer!
        $categories_result_selected->MoveNext();
      }

      $categories_array = array(array('id' => '', 'text' => TEXT_NONE));
      $categories_array = oos_get_category_tree();
    }

    $tax_class_array = array();
    $tax_class_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $tax_classtable = $oostable['tax_class'];
    $tax_class_result = $dbconn->Execute("SELECT tax_class_id, tax_class_title FROM $tax_classtable ORDER BY tax_class_title");
    while ($tax_class = $tax_class_result->fields) {
      $tax_class_array[] = array('id' => $tax_class['tax_class_id'],
                                 'text' => $tax_class['tax_class_title']);

      // Move that ADOdb pointer!
      $tax_class_result->MoveNext();
    }


    $products_units_array = array();
    $products_units_array = array(array('id' => '0', 'text' => TEXT_NONE));
    $products_unitstable = $oostable['products_units'];
    $products_units_result = $dbconn->Execute("SELECT products_units_id, products_unit_name FROM $products_unitstable WHERE languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_unit_name");
    while ($products_units = $products_units_result->fields) {
      $products_units_array[] = array('id' => $products_units['products_units_id'],
                                      'text' => $products_units['products_unit_name']);

      // Move that ADOdb pointer!
      $products_units_result->MoveNext();
    }



    $products_status_array = array();
    $products_status_array = array(array('id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE));
    $products_statustable = $oostable['products_status'];
    $products_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM $products_statustable WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
    while ($products_status = $products_status_result->fields) {
      $products_status_array[] = array('id' => $products_status['products_status_id'],
                                       'text' => $products_status['products_status_name']);

      // Move that ADOdb pointer!
      $products_status_result->MoveNext();
    }

    $languages = oos_get_languages();

    $decimal_quantity_array = array();
    $decimal_quantity_array = array(array('id' => '1', 'text' => ENTRY_YES),
                                    array('id' => '0', 'text' => ENTRY_NO));


    if (OOS_SPAW == 'true') {
      include 'includes/classes/spaw/spaw_control.class.php';
    } elseif (OOS_SPAW == 'fck') {
      include 'includes/classes/fckeditor/fckeditor.php';
    }

    $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product';
    if (NEW_PRODUCT_PREVIEW == 'true') {
      $form_action = 'new_product_preview';
    }
?>
<link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
<script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
<script language="javascript">
  var dateAvailable = new ctlSpiffyCalendarBox("dateAvailable", "new_product", "products_date_available","btnDate1","<?php echo $pInfo->products_date_available; ?>",scBTNMODE_CUSTOMBLUE);
</script>
<?php
  if (OOS_BASE_PRICE == 'true') {
?>
<script language="javascript"><!--

function doRound(x, places) {
  return Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
}

function calcBasePriceFactor() {
  var pqty = document.forms["new_product"].products_product_quantity.value;
  var bqty = document.forms["new_product"].products_base_quantity.value;

  if ((pqty != 0) || (bqty != 0)) {
     document.forms["new_product"].products_base_price.value = doRound(bqty / pqty, 6);
  } else {
     document.forms["new_product"].products_base_price.value = 1.000000;
  }

}
//--></script>
<?php
  }
?>
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo sprintf(TEXT_NEW_PRODUCT, oos_output_generated_category_path($current_category_id)); ?></td>
            <td class="pageHeading" align="right"><?php echo oos_draw_separator('trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr><?php echo oos_draw_form('new_product', $aFilename['products'], 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"'); ?>
        <td><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_STATUS; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('products_status', $products_status_array, $pInfo->products_status); ?></td>
          </tr>
<?php
   if (MULTIPLE_CATEGORIES_USE == 'true') {
?>
          <tr>
            <td class="main"><?php echo TEXT_CATEGORIES; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_m_select_menu('categories_ids[]', $categories_array, $categories_array_selected, 'size=10'); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;'; ?><script language="javascript">dateAvailable.writeControl(); dateAvailable.dateFormat="yyyy-MM-dd";</script></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MANUFACTURER; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></td>
            <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('products_name[' . $languages[$i]['id'] . ']', (($products_name[$languages[$i]['id']]) ? stripslashes($products_name[$languages[$i]['id']]) : oos_get_products_name($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></td>
            <td><table border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                <td class="main">

<?php
      if (OOS_SPAW == 'true') {
        $sw = new SPAW_Wysiwyg('products_description_' . $languages[$i]['id'] /*name*/,(($_POST['products_description_' .$languages[$i]['id']]) ? stripslashes($_POST['products_description_' .$languages[$i]['id']]) : oos_get_products_description($pInfo->products_id, $languages[$i]['id'])) /*value*/,
                             $languages[$i]['iso_639_1'] /*language*/, 'sidetable' /*toolbar mode*/, 'default' /*theme*/,
                             '550px' /*width*/, '350px' /*height*/);
        $sw->show();
      } elseif (OOS_SPAW == 'fck') {
        $oFCKeditor = new FCKeditor('products_description_' . $languages[$i]['id']);
        $oFCKeditor->BasePath = 'includes/classes/fckeditor/';
        $oFCKeditor->Config['AutoDetectLanguage'] = false;
        $oFCKeditor->Config['DefaultLanguage'] = $languages[$i]['iso_639_1'];
        $oFCKeditor->Width = '550';
        $oFCKeditor->Height = '350';
        $oFCKeditor->Config['SkinPath'] = 'skins/silver/' ;
        $oFCKeditor->ToolbarSet = 'Oos';
        $oFCKeditor->Value = (($_POST['products_description_' .$languages[$i]['id']]) ? stripslashes($_POST['products_description_' .$languages[$i]['id']]) : oos_get_products_description($pInfo->products_id, $languages[$i]['id']));
        $oFCKeditor->Create();
      } else {
        echo oos_draw_textarea_field('products_description_' . $languages[$i]['id'], 'soft', '70', '15', ($_POST['products_description_' .$languages[$i]['id']] ? stripslashes($_POST['products_description_' .$languages[$i]['id']]) : oos_get_products_description($pInfo->products_id, $languages[$i]['id'])));
      }
?>
                 </td>
              </tr>
            </table></td>
          </tr>
<?php
    }
?>
<?php

      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" width="100" valign="top"><?php if ($i == 0) echo 'wesentliche Merkmale der Ware';; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('products_short_description_' . $languages[$i]['id'], 'soft', '70', '4', ($_POST['products_short_description_' .$languages[$i]['id']] ? stripslashes($_POST['products_short_description_' .$languages[$i]['id']]) : oos_get_products_short_description($pInfo->products_id, $languages[$i]['id'])));
?></td>
                </tr>
              </table></td>
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
              <td class="main" width="100" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION_META; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('products_description_meta_' . $languages[$i]['id'], 'soft', '70', '4', ($_POST['products_description_meta_' .$languages[$i]['id']] ? stripslashes($_POST['products_description_meta_' .$languages[$i]['id']]) : oos_get_products_description_meta($pInfo->products_id, $languages[$i]['id'])));
?></td>
                </tr>
              </table></td>
            </tr>
<?php
      }
      for ($i=0; $i < count($languages); $i++) {
?>
            <tr>
              <td class="main" width="100" valign="top"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS_META; ?></td>
              <td><table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="main" valign="top"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']); ?>&nbsp;</td>
                  <td class="main"><?php echo oos_draw_textarea_field('products_keywords_meta_' . $languages[$i]['id'], 'soft', '70', '4', ($_POST['products_keywords_meta_' .$languages[$i]['id']] ? stripslashes($_POST['products_keywords_meta_' .$languages[$i]['id']]) : oos_get_products_keywords_meta($pInfo->products_id, $languages[$i]['id'])));
?></td>
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
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_quantity', $pInfo->products_quantity) . ' Min: ' . oos_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min==0 ? 1 : $pInfo->products_quantity_order_min)) . ' Units: ' . oos_draw_input_field('products_quantity_order_units', $pInfo->products_quantity_order_units); ?></td>
          </tr>
<?php
  if (STOCK_CHECK == 'true') {
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_REORDER_LEVEL; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_reorder_level', $pInfo->products_reorder_level); ?></td>
<?php
  }
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_EAN; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_ean', $pInfo->products_ean); ?></td>
          </tr>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
<?php
   if (oos_is_not_null($pInfo->products_image)) {
     echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_image_product'], 'bimage=' . $pInfo->products_image) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_image, $pInfo->products_name, '', '80') . '</a></td>';
     echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_image', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_image', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_image . '</span></td>';
   } else {
     echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
   }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_image') . oos_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
          </tr>

<?php
  if (is_dir(OOS_SHOP_ZOOMIFY)) {
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_ZOOMIFY; ?></td>
<?php
   if (oos_is_not_null($pInfo->products_zoomify)) {
// todo pupup window for zoomify
//     echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_image_product'], 'bimage=' . $pInfo->products_image) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_image, $pInfo->products_name, '', '80') . '</a></td>';
   } else {
     echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
   }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_input_field('products_zoomify', $pInfo->products_zoomify); ?></td>
          </tr>
<?php
  }
  if (OOS_MO_PIC == 'true') {
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE1; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage1)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage1) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage1, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage1', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage1', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage1 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage1') . oos_draw_hidden_field('products_previous_subimage1', $pInfo->products_subimage1); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE2; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage2)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage2) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage2, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage2', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage2', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage2 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage2') . oos_draw_hidden_field('products_previous_subimage2', $pInfo->products_subimage2); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE3; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage3)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage3) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage3, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage3', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage3', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage3 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage3') . oos_draw_hidden_field('products_previous_subimage3', $pInfo->products_subimage3); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE4; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage4)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage4) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage4, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage4', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage4', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage4 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage4') . oos_draw_hidden_field('products_previous_subimage4', $pInfo->products_subimage4); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE5; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage5)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage5) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage5, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage5', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage5', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage5 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage5') . oos_draw_hidden_field('products_previous_subimage5', $pInfo->products_subimage5); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SUBIMAGE6; ?></td>
<?php
     if (oos_is_not_null($pInfo->products_subimage6)) {
       echo '            <td align="center"><a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $pInfo->products_subimage6) . '\')">' . oos_image(OOS_SHOP_IMAGES . $pInfo->products_subimage6, $pInfo->products_name, '', '80') . '</a></td>';
       echo '            <td><span class="smallText">&nbsp;' . oos_draw_checkbox_field('remove_subimage6', 'yes') . TEXT_PRODUCTS_IMAGE_REMOVE . '<br />&nbsp;' . oos_draw_checkbox_field('delete_subimage6', 'yes') . TEXT_PRODUCTS_IMAGE_DELETE . '<br /><br />&nbsp;<b>' . TEXT_PRODUCTS_IMAGE . '</b>&nbsp;' .  $pInfo->products_subimage6 . '</span></td>';
     } else {
       echo '            <td colspan="2">' . oos_draw_separator('trans.gif', '80', '1') . '</td>';
     }
?>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_subimage6') . oos_draw_hidden_field('products_previous_subimage6', $pInfo->products_subimage6); ?></td>
          </tr>
<?php
  }
?>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
<?php
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . oos_draw_input_field('products_url[' . $languages[$i]['id'] . ']', (($products_url[$languages[$i]['iso_639_2']]) ? stripslashes($products_url[$languages[$i]['id']]) : oos_get_products_url($pInfo->products_id, $languages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE; ?></td>
            <td class="main">
<?php
   $oosPrice = $pInfo->products_price;
   if (OOS_PRICE_IS_BRUTTO == 'true'){
     $oosPriceNetto = round($oosPrice,TAX_DECIMAL_PLACES);
     $tax_ratestable = $oostable['tax_rates'];
     $tax_result = $dbconn->Execute("SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '" . $pInfo->products_tax_class_id . "' ");
     $tax = $tax_result->fields;
     $oosPrice = ($oosPrice*($tax[tax_rate]+100)/100);
   }
   $oosPrice = round($oosPrice,TAX_DECIMAL_PLACES);
   echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_price', $oosPrice);
   if (OOS_PRICE_IS_BRUTTO == 'true') echo " - " . TEXT_TAX_INFO . $oosPriceNetto;
?>
      </td>
    </tr>
    <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_LIST_PRICE; ?></td>
            <td class="main">
<?php
   $oosPriceList = $pInfo->products_price_list;
   if (OOS_PRICE_IS_BRUTTO == 'true'){
     $oosPriceListNetto = round($oosPriceList,TAX_DECIMAL_PLACES);
     $oosPriceList = ($oosPriceList*($tax[tax_rate]+100)/100);
   }
   $oosPriceList = round($oosPriceList,TAX_DECIMAL_PLACES);
   echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_price_list', $oosPriceList);
   if (OOS_PRICE_IS_BRUTTO == 'true') echo " - " . TEXT_TAX_INFO . $oosPriceListNetto;
?>
            </td>
          </tr>
<?php
  if (OOS_BASE_PRICE == 'true') {
?>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_BASE_PRICE_FACTOR; ?></td>
            <td class="main"><table border="0">
                <tr>
                  <td class="main"><br /><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_base_price', $pInfo->products_base_price); ?></td>
                  <td class="main"><br /> <- </td>
                  <td class="main"><?php echo TEXT_PRODUCTS_PRODUCT_QUANTITY . '<br />' . oos_draw_input_field('products_product_quantity', $pInfo->products_product_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                  <td class="main"><?php echo TEXT_PRODUCTS_BASE_QUANTITY . '<br />' . oos_draw_input_field('products_base_quantity', $pInfo->products_base_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
             <td class="main"><?php echo TEXT_PRODUCTS_BASE_UNIT; ?></td>
             <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_base_unit', $pInfo->products_base_unit); ?></td>
           </tr>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
<?php
  }
?>

          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
             <td class="main"><?php echo TEXT_PRODUCTS_UNIT; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('products_units_id', $products_units_array, $pInfo->products_units_id); ?></td>
          </tr>
<?php
  if (DECIMAL_CART_QUANTITY == 'true') {
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_DECIMAL_QUANTITY; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('products_quantity_decimal', $decimal_quantity_array, $pInfo->products_quantity_decimal); ?></td>
          </tr>
<?php
  }
?>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
           <tr>
             <td class="main"><?php echo TEXT_PRODUCTS_DISCOUNT_ALLOWED; ?></td>
             <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_discount_allowed', number_format($pInfo->products_discount_allowed, 2)); ?> %</td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
            <td class="main">
               <table border='2'>
                 <tr>
                   <td colspan="5" class="main" align="center"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
                 </tr>
                   <td class="main" align="center" width="75"><?php echo TEXT_DISCOUNTS_BREAKS; ?></td>
                   <td class="main" align="center" width="75">1</td>
                   <td class="main" align="center" width="75">2</td>
                   <td class="main" align="center" width="75">3</td>
                   <td class="main" align="center" width="75">4</td>
                 <tr>
                 </tr>
                 <tr>
                   <td class="main" align="center" width="75"><?php echo TEXT_DISCOUNTS_QTY; ?><br /><?php echo TEXT_DISCOUNTS_PRICE; ?></td>
 <?php
   $oosDiscount1 = $pInfo->products_discount1;
   $oosDiscount2 = $pInfo->products_discount2;
   $oosDiscount3 = $pInfo->products_discount3;
   $oosDiscount4 = $pInfo->products_discount4;
   if (OOS_PRICE_IS_BRUTTO == 'true'){
     $oosDiscount1 = ($oosDiscount1*($tax[tax_rate]+100)/100);
     $oosDiscount2 = ($oosDiscount2*($tax[tax_rate]+100)/100);
     $oosDiscount3 = ($oosDiscount3*($tax[tax_rate]+100)/100);
     $oosDiscount4 = ($oosDiscount4*($tax[tax_rate]+100)/100);
   }
   $oosDiscount1 = round($oosDiscount1,TAX_DECIMAL_PLACES);
   $oosDiscount2 = round($oosDiscount2,TAX_DECIMAL_PLACES);
   $oosDiscount3 = round($oosDiscount3,TAX_DECIMAL_PLACES);
   $oosDiscount4 = round($oosDiscount4,TAX_DECIMAL_PLACES);
 ?>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount1_qty', $pInfo->products_discount1_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount1', $oosDiscount1, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount2_qty', $pInfo->products_discount2_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount2', $oosDiscount2, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount3_qty', $pInfo->products_discount3_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount3', $oosDiscount3, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount4_qty', $pInfo->products_discount4_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount4', $oosDiscount4, 'size="10"'); ?></td>
                  </tr>
               </table>
            </td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
          <tr>
            <td colspan="2"><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_SORT_ORDER; ?></td>
            <td class="main"><?php echo oos_draw_separator('trans.gif', '24', '15') . '&nbsp;' . oos_draw_input_field('products_sort_order', $pInfo->products_sort_order); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
<?php
    if (NEW_PRODUCT_PREVIEW == 'true') {
?>
        <td class="main" align="right"><?php echo oos_draw_hidden_field('products_date_added', (($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . oos_image_swap_submits('preview', 'preview_off.gif', IMAGE_PREVIEW) . '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
<?php
    } else {
?>
        <td class="main" align="right"><?php echo oos_draw_hidden_field('products_date_added', (($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . oos_image_swap_submits('save', 'save_off.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>'; ?></td>
<?php
    }
?>
      </form></tr>
<?php
  } elseif ($action == 'new_product_preview') {
    if (oos_is_not_null($_POST)) {
      $pInfo = new objectInfo($_POST);
      $products_name = $_POST['products_name'];
      $products_url = $_POST['products_url'];

      if ( ($_POST['products_image'] != 'none') && (isset($_FILES['products_image'])) ) {
         $products_image = oos_get_uploaded_file('products_image');
         $image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_image['tmp_name'])) {
        $products_image_name = oos_copy_uploaded_file($products_image, $image_directory);
        if (OOS_IMAGE_SWF == 'true') {
          include 'includes/classes/class_image2swf.php';
          $swf = new Image2swf;
          $filename = explode("[/\\.]", $products_image_name);
          $swf->Main(OOS_ABSOLUTE_PATH . OOS_IMAGES . $products_image_name, $filename[0]);
        }
      } else {
        $products_image_name = $_POST['products_previous_image'];
      }

      // copy subimage1 only if modified
      if ( ($_POST['products_subimage1'] != 'none') && (isset($_FILES['products_subimage1'])) ) {
        $products_subimage1 = oos_get_uploaded_file('products_subimage1');
        $subimage1_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage1['tmp_name'])) {
        $products_subimage1_name = oos_copy_uploaded_file($products_subimage1, $subimage1_directory);
      } else {
        $products_subimage1_name = $_POST['products_previous_subimage1'];
      }

      // copy subimage2 only if modified
      if ( ($_POST['products_subimage2'] != 'none') && (isset($_FILES['products_subimage2'])) ) {
        $products_subimage2 = oos_get_uploaded_file('products_subimage2');
        $subimage2_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage2['tmp_name'])) {
        $products_subimage2_name = oos_copy_uploaded_file($products_subimage2, $subimage2_directory);
      } else {
        $products_subimage2_name = $_POST['products_previous_subimage2'];
      }

      // copy subimage3 only if modified
      if ( ($_POST['products_subimage3'] != 'none') && (isset($_FILES['products_subimage3'])) ) {
        $products_subimage3 = oos_get_uploaded_file('products_subimage3');
        $subimage3_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage3['tmp_name'])) {
        $products_subimage3_name = oos_copy_uploaded_file($products_subimage3, $subimage3_directory);
      } else {
        $products_subimage3_name = $_POST['products_previous_subimage3'];
      }

      // copy subimage4 only if modified
      if ( ($_POST['products_subimage4'] != 'none') && (isset($_FILES['products_subimage4'])) ) {
        $products_subimage4 = oos_get_uploaded_file('products_subimage4');
        $subimage4_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage4['tmp_name'])) {
        $products_subimage4_name = oos_copy_uploaded_file($products_subimage4, $subimage4_directory);
      } else {
        $products_subimage4_name = $_POST['products_previous_subimage4'];
      }

      // copy subimage5 only if modified
      if ( ($_POST['products_subimage5'] != 'none') && (isset($_FILES['products_subimage5'])) ) {
        $products_subimage5 = oos_get_uploaded_file('products_subimage5');
        $subimage5_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage5['tmp_name'])) {
        $products_subimage5_name = oos_copy_uploaded_file($products_subimage5, $subimage5_directory);
      } else {
        $products_subimage5_name = $_POST['products_previous_subimage5'];
      }

      // copy subimage6 only if modified
      if ( ($_POST['products_subimage6'] != 'none') && (isset($_FILES['products_subimage6'])) ) {
        $products_subimage6 = oos_get_uploaded_file('products_subimage6');
        $subimage6_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
      }
      if (is_uploaded_file($products_subimage6['tmp_name'])) {
        $products_subimage6_name = oos_copy_uploaded_file($products_subimage6, $subimage6_directory);
      } else {
        $products_subimage6_name = $_POST['products_previous_subimage6'];
      }

      $products_sort_order = $_POST['products_sort_order'];
    } else {
      $product_result = $dbconn->Execute("SELECT pd.products_name, pd.products_description, pd.products_short_description, pd.products_description_meta, products_keywords_meta, pd.products_url, p.products_id, p.products_quantity, p.products_reorder_level, p.products_model, p.products_ean, p.products_image, p.products_subimage1, p.products_subimage2, p.products_subimage3, p.products_subimage4, p.products_subimage5, p.products_subimage6, p.products_zoomify, p.products_price, p.products_base_price, p.products_base_unit, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_units_id, p.manufacturers_id, p.products_price_list, p.products_discount_allowed, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE p.products_id = '" . $_GET['pID'] . "' and p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $product = $product_result->fields;

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
      $products_subimage1_name = $pInfo->products_subimage1;
      $products_subimage2_name = $pInfo->products_subimage2;
      $products_subimage3_name = $pInfo->products_subimage3;
      $products_subimage4_name = $pInfo->products_subimage4;
      $products_subimage5_name = $pInfo->products_subimage5;
      $products_subimage6_name = $pInfo->products_subimage6;

    }

    $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product';

    echo oos_draw_form($form_action, $aFilename['products'], 'cPath=' . $cPath . '&pID=' . $_GET['pID'] . '&action=' . $form_action, 'post', 'enctype="multipart/form-data"');

    $languages = oos_get_languages();
    for ($i = 0, $n = count($languages); $i < $n; $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $pInfo->products_name = oos_get_products_name($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description = oos_get_products_description($pInfo->products_id, $languages[$i]['id']);
		$pInfo->products_short_description = oos_get_products_short_description($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_description_meta = oos_get_products_description_meta($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_keywords_meta = oos_get_products_keywords_meta($pInfo->products_id, $languages[$i]['id']);
        $pInfo->products_url = oos_get_products_url($pInfo->products_id, $languages[$i]['id']);
      } else {
        $pInfo->products_name = oos_db_prepare_input($products_name[$languages[$i]['id']]);
        $pInfo->products_description = oos_db_prepare_input($_POST['products_description_' .$languages[$i]['id']]);
        $pInfo->products_short_description = oos_db_prepare_input($_POST['products_short_description_' .$languages[$i]['id']]);
        $pInfo->products_description_meta = oos_db_prepare_input($_POST['products_description_meta_' .$languages[$i]['id']]);
        $pInfo->products_keywords_meta = oos_db_prepare_input($_POST['products_keywords_meta_' .$languages[$i]['id']]);
        $pInfo->products_url = oos_db_prepare_input($products_url[$languages[$i]['id']]);
      }
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo oos_image(OOS_SHOP_IMAGES . 'flags/' . $languages[$i]['iso_639_2'] . '.gif', $languages[$i]['name']) . '&nbsp;' . $pInfo->products_name; ?></td>
<?php
  $oosPrice = $pInfo->products_price;
  if (OOS_PRICE_IS_BRUTTO == 'true' && ($_GET['read'] == 'only' || $action != 'new_product_preview') ){
    $oosPriceNetto=round($oosPrice,TAX_DECIMAL_PLACES);
    $tax_ratestable = $oostable['tax_rates'];
    $tax_result = $dbconn->Execute("SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '" . $pInfo->products_tax_class_id . "' ");
    $tax = $tax_result->fields;
    $oosPrice= ($oosPrice*($tax[tax_rate]+100)/100);
  }
  $oosPrice=round($oosPrice,TAX_DECIMAL_PLACES);
?>
      <td class="pageHeading" align="right"><?php echo $currencies->format($oosPrice); ?></td>
          </tr>
          <tr>
            <td colspan="2" class="main" align="right"><?php echo TEXT_PRODUCTS_DISCOUNT_ALLOWED . ' ' . number_format($products_discount_allowed, 2); ?>%</td>
          </tr>
<?php
  include 'includes/categories_discounts_price.php';
?>
        </table></td>
      </tr>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main">
<?php
      if ( (!$delete_image) && (!$remove_image) ) {
        echo '<a href="javascript:popupImageWindow(\'' . oos_href_link_admin($aFilename['popup_image_product'], 'bimage=' . $products_image_name) . '\')">' . (($products_image_name) ? oos_image(OOS_SHOP_IMAGES . $products_image_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a>';
      }
      echo $pInfo->products_description;
?></td>
      </tr>
<?php
      if ($pInfo->products_url) {
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
      </tr>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_PRODUCT_MORE_INFORMATION, $pInfo->products_url); ?></td>
      </tr>
<?php
      }
?>
          <tr>
            <td colspan="2" class="main" align="right">
<table align="center">
      <tr>
<?php
    if ( (!$delete_subimage1) && (!$remove_subimage1) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $products_subimage1_name) . '\')">' . (($products_subimage1_name) ? oos_image(OOS_SHOP_IMAGES . $products_subimage1_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
    if ( (!$delete_subimage2) && (!$remove_subimage2) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' . $products_subimage2_name) . '\')">' . (($products_subimage2_name) ? oos_image(OOS_SHOP_IMAGES . $products_subimage2_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
    if ( (!$delete_subimage3) && (!$remove_subimage3) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' .  $products_subimage3_name) . '\')">' . (($products_subimage3_name) ?oos_image(OOS_SHOP_IMAGES . $products_subimage3_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
?>
      </tr>
      <tr>
<?php
    if ( (!$delete_subimage4) && (!$remove_subimage4) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' .  $products_subimage4_name) . '\')">' . (($products_subimage4_name) ? oos_image(OOS_SHOP_IMAGES . $products_subimage4_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
    if ( (!$delete_subimage5) && (!$remove_subimage5) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' .  $products_subimage5_name) . '\')">' . (($products_subimage5_name) ? oos_image(OOS_SHOP_IMAGES . $products_subimage5_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
    if ( (!$delete_subimage6) && (!$remove_subimage6) ) {
      echo '<td align="center" class="main"><a href="javascript:popupImageWindow(\''. oos_href_link_admin($aFilename['popup_subimage_product'], 'bimage=' .  $products_subimage6_name) . '\')">' . (($products_subimage6_name) ? oos_image(OOS_SHOP_IMAGES . $products_subimage6_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '') . '</a></td>';
    } else {
      echo '<td></td>';
    }
?>
      </tr>
</table>

          </td>
        </tr>
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
      }
?>
      <tr>
        <td><?php echo oos_draw_separator('trans.gif', '1', '10'); ?></td>
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
        $back_url_params = 'cPath=' . $cPath;
        if (oos_is_not_null($pInfo->products_id)) {
          $back_url_params .= '&pID=' . $pInfo->products_id;
        }
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params, 'NONSSL') . '">' . oos_image_swap_button('back', 'back_off.gif', IMAGE_BACK) . '</a>'; ?></td>
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
      for ($i = 0, $n = count($languages); $i < $n; $i++) {
        echo oos_draw_hidden_field('products_name[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$languages[$i]['id']])));
        echo oos_draw_hidden_field('products_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$languages[$i]['id']])));
        echo oos_draw_hidden_field('products_short_description[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_short_description[$languages[$i]['id']])));
        echo oos_draw_hidden_field('products_description_meta[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description_meta[$languages[$i]['id']])));
        echo oos_draw_hidden_field('products_keywords_meta[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_keywords_meta[$languages[$i]['id']])));
        echo oos_draw_hidden_field('products_url[' . $languages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$languages[$i]['id']])));
      }
      echo oos_draw_hidden_field('products_image', stripslashes($products_image_name));
      echo oos_draw_hidden_field('products_subimage1', stripslashes($products_subimage1_name));
      echo oos_draw_hidden_field('products_subimage2', stripslashes($products_subimage2_name));
      echo oos_draw_hidden_field('products_subimage3', stripslashes($products_subimage3_name));
      echo oos_draw_hidden_field('products_subimage4', stripslashes($products_subimage4_name));
      echo oos_draw_hidden_field('products_subimage5', stripslashes($products_subimage5_name));
      echo oos_draw_hidden_field('products_subimage6', stripslashes($products_subimage6_name));

      if (isset($_POST['categories_ids'])) {
        $selected_catids = $_POST['categories_ids'];
        foreach ($selected_catids as $current_category_id)  {
          echo oos_draw_hidden_field('categories_ids[]', stripslashes($current_category_id));
        }
      }

      echo oos_image_swap_submits('back', 'back_off.gif', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['pID'])) {
        echo oos_image_swap_submits('update', 'update_off.gif', IMAGE_UPDATE);
      } else {
        echo oos_image_swap_submits('insert', 'insert_off.gif', IMAGE_INSERT);
      }
      echo '&nbsp;&nbsp;<a href="' . oos_href_link_admin($aFilename['categories'], 'cPath=' . $cPath . '&pID=' . $_GET['pID']) . '">' . oos_image_swap_button('cancel', 'cancel_off.gif', IMAGE_CANCEL) . '</a>';
?></td>
      </form></tr>
<?php
    }
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