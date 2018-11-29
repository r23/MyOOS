<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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
			$sProductsReplacementProductID = oos_db_prepare_input($_POST['products_replacement_product_id']);
			if (oos_is_not_null($sProductsReplacementProductID)) {
				$messageStack->add_session(ERROR_REPLACEMENT, 'error');
				$sProductsStatus = 4;
			}
			
			if (STOCK_CHECK == 'true') {
				if ($sProductsQuantity <=0) {
					$messageStack->add_session(ERROR_OUTOFSTOCK, 'error');
					$sProductsStatus = 0;
				}
			}


			if ( ($_POST['products_image'] != 'none') && (isset($_FILES['products_image'])) ) {
				$products_image = oos_get_uploaded_file('products_image');
				$image_directory = oos_get_local_path(OOS_ABSOLUTE_PATH . OOS_IMAGES);
			}
			if (is_uploaded_file($products_image['tmp_name'])) {
				$products_image = oos_copy_uploaded_file($products_image, $image_directory);
			} else {
				$products_image = oos_db_prepare_input($_POST['products_previous_image']);
			}


			if ( isset($_POST['edit_x']) || isset($_POST['edit_y']) ) {
				$action = 'new_product';
			} else {
				if ($_POST['delete_image'] == 'yes') {
					if (oos_duplicate_product_image_check($products_image)) {
						oos_remove_product_image($products_image);
					}
				}

				if ( ($_POST['delete_image'] == 'yes') || ($_POST['remove_image'] == 'yes') ) {
					$products_image = 'none';
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
					$products_product_quantity = 1;
					$products_base_quantity = 1;
					$products_base_unit = '';
				}

				$products_date_available = (date('Y-m-d') < $products_date_available) ? $products_date_available : 'null';

				$sql_data_array = array('products_quantity' => $sProductsQuantity,
                                  'products_reorder_level' => oos_db_prepare_input($_POST['products_reorder_level']),
                                  'products_model' => oos_db_prepare_input($_POST['products_model']),
								  'products_replacement_product_id' => $sProductsReplacementProductID,
                                  'products_ean' => oos_db_prepare_input($_POST['products_ean']),
                                  'products_image' => (($products_image == 'none') ? '' : oos_db_prepare_input($products_image)),
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
                                  );

				if ($action == 'insert_product') {
					$insert_sql_data = array('products_date_added' => 'now()');

					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

					oos_db_perform($oostable['products'], $sql_data_array);
					$products_id = $dbconn->Insert_ID();

					$products_to_categoriestable = $oostable['products_to_categories'];
					$dbconn->Execute("INSERT INTO $products_to_categoriestable (products_id, categories_id) VALUES ('" . $products_id . "', '" . $current_category_id . "')");

				} elseif ($action == 'update_product') {
					$update_sql_data = array('products_last_modified' => 'now()');

					$sql_data_array = array_merge($sql_data_array, $update_sql_data);

					oos_db_perform($oostable['products'], $sql_data_array, 'UPDATE', 'products_id = \'' . oos_db_input($products_id) . '\'');

				}

				if (oos_empty($_GET['cPath'])) {
					$cPath = $current_category_id;
				}

				$aLanguages = oos_get_languages();
				$nLanguages = count($aLanguages);
				for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
					$lang_id = $aLanguages[$i]['id'];

					$sql_data_array = array('products_name' => oos_db_prepare_input($_POST['products_name'][$lang_id]),
                                    'products_description' => oos_db_prepare_input($_POST['products_description_' .$aLanguages[$i]['id']]),
                                    'products_description_meta' => oos_db_prepare_input($_POST['products_description_meta_' .$aLanguages[$i]['id']]),
                                    'products_keywords_meta' => oos_db_prepare_input($_POST['products_keywords_meta_' .$aLanguages[$i]['id']]),
                                    'products_url' => oos_db_prepare_input($_POST['products_url'][$lang_id]));

					if ($action == 'insert_product') {
						$insert_sql_data = array('products_id' => $products_id,
                                       'products_languages_id' => $lang_id);

						$sql_data_array = array_merge($sql_data_array, $insert_sql_data);

						oos_db_perform($oostable['products_description'], $sql_data_array);
					} elseif ($action == 'update_product') {
						oos_db_perform($oostable['products_description'], $sql_data_array, 'UPDATE', 'products_id = \'' . oos_db_input($products_id) . '\' AND products_languages_id = \'' . $lang_id . '\'');
					}
				}
				oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&pID=' . $products_id));
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
  if ($action == 'new_product') {
    if (isset($_GET['pID']) && empty($_POST)) {
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $product_result = $dbconn->Execute("SELECT pd.products_name, pd.products_description, pd.products_url,
                                                 pd.products_description_meta, pd.products_keywords_meta, p.products_id,
                                                 p.products_quantity, p.products_reorder_level, p.products_model,
                                                 p.products_replacement_product_id, p.products_ean, p.products_image,
                                                 p.products_price, p.products_base_price, p.products_base_quantity,
                                                 p.products_product_quantity, p.products_base_unit,
                                                 p.products_weight, p.products_date_added, p.products_last_modified,
                                                 date_format(p.products_date_available, '%Y-%m-%d') AS products_date_available,
                                                 p.products_status, p.products_tax_class_id, p.products_units_id, p.manufacturers_id,
                                                 p.products_price_list, 
                                                 p.products_quantity_order_min, p.products_quantity_order_units,
                                                 p.products_discount1, p.products_discount2, p.products_discount3,
                                                 p.products_discount4, p.products_discount1_qty, p.products_discount2_qty,
                                                 p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order
                                            FROM $productstable p,
                                                 $products_descriptiontable pd
                                           WHERE p.products_id = '" . intval($_GET['pID']) . "' AND
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
    } else {
      $pInfo = new objectInfo(array());
      $pInfo->products_status = DEFAULT_PRODUTS_STATUS_ID;
      $pInfo->products_base_price = 1.0;
      $pInfo->products_product_quantity = 1.0;
      $pInfo->products_base_quantity = 1.0;
      $pInfo->products_units_id = DEFAULT_PRODUCTS_UNITS_ID;
	  $pInfo->products_tax_class_id = 1; // DEFAULT_TAX_CLASS_ID
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

    $aLanguages = oos_get_languages();
	$nLanguages = count($aLanguages);
	
    $form_action = (isset($_GET['pID'])) ? 'update_product' : 'insert_product';

?>
<script type="text/javascript" src="js/ckeditor/ckeditor.js"></script>
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

++ 	
	<!-- Breadcrumbs //-->
	<div class="content-heading">
		<div class="col-lg-12">
			<h2><?php echo sprintf(TEXT_NEW_PRODUCT, oos_output_generated_category_path($current_category_id)); ?></h2>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
				</li>
				<li class="breadcrumb-item">
					<a href="<?php echo oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
				</li>
				<li class="breadcrumb-item active">
					<strong><?php echo sprintf(TEXT_NEW_PRODUCT, oos_output_generated_category_path($current_category_id)); ?></strong>
				</li>
			</ol>
		</div>
	</div>
	<!-- END Breadcrumbs //-->

	<?php echo oos_draw_form('id', 'new_product', $aContents['products'], 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"'); ?>	
               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab">Product Edit</a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#seo" aria-controls="seo" role="tab" data-toggle="tab">SEO Metadata</a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#picture" aria-controls="picture" role="tab" data-toggle="tab">Pictures</a>
                     </li>
                  </ul>
                  <div class="tab-content">
                     <div class="tab-pane active" id="edit" role="tabpanel">

<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>
				 
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_PRODUCTS_NAME; ?></label>
							  <?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
                              <div class="col-lg-9">
								<?php echo oos_draw_input_field('products_name[' . $aLanguages[$i]['id'] . ']', (($products_name[$aLanguages[$i]['id']]) ? stripslashes($products_name[$aLanguages[$i]['id']]) : oos_get_products_name($pInfo->products_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
<?php
    }
?>						
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_DATE_AVAILABLE; ?><br /><small>(YYYY-MM-DD)</small></label>
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
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('manufacturers_id', $manufacturers_array, $pInfo->manufacturers_id); ?></div>
                           </div>
                        </fieldset>			
						
<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION; ?></label>
							  <?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
                              <div class="col-lg-9">
<?php
       echo oos_draw_textarea_field('products_description_' . $aLanguages[$i]['id'], 'soft', '70', '15', ($_POST['products_description_' .$aLanguages[$i]['id']] ? stripslashes($_POST['products_description_' .$aLanguages[$i]['id']]) : oos_get_products_description($pInfo->products_id, $aLanguages[$i]['id'])));
?>
                              </div>
                           </div>
                        </fieldset>
		<script>
			CKEDITOR.replace( 'products_description_<?php echo $aLanguages[$i]['id']; ?>');
		</script>
<?php
    }
?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">Price:</label>
                              <div class="col-lg-10">
                                 <input class="form-control" type="text" placeholder="$ 123.20">
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">Quantity:</label>
                              <div class="col-lg-10">
                                 <input class="form-control" type="number" placeholder="0" min="0">
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">Tax :</label>
                              <div class="col-lg-10">
                                 <input class="form-control" type="text" placeholder="20%">
                              </div>
                           </div>
                        </fieldset>					
					
                        <fieldset>					
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_PRODUCTS_STATUS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_pull_down_menu('products_status', $products_status_array, $pInfo->products_status); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_REPLACEMENT_PRODUCT; ?></label>
                              <div class="col-lg-10">
                                 <?php echo oos_draw_input_field('products_replacement_product_id', $pInfo->products_replacement_product_id); ?>
                              </div>
                           </div>
                        </fieldset>						
						
						
                     </div>
                     <div class="tab-pane" id="seo" role="tabpanel">
<?php
	for ($i = 0, $n = $nLanguages; $i < $n; $i++) {	  
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_PRODUCTS_DESCRIPTION_META; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('products_description_meta_' . $aLanguages[$i]['id'], 'soft', '70', '4', ($_POST['products_description_meta_' .$aLanguages[$i]['id']] ? stripslashes($_POST['products_description_meta_' .$aLanguages[$i]['id']]) : oos_get_products_description_meta($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
	}  
	for ($i = 0, $n = $nLanguages; $i < $n; $i++) {	
?>
					<fieldset>
						<div class="form-group row">
							<label class="col-lg-2 col-form-label"><?php if ($i == 0) echo TEXT_PRODUCTS_KEYWORDS_META; ?></label>
							<?php if ($nLanguages > 1) echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>'; ?>
							<div class="col-lg-9">
								<?php echo oos_draw_textarea_field('products_keywords_meta_' . $aLanguages[$i]['id'], 'soft', '70', '4', ($_POST['products_keywords_meta_' .$aLanguages[$i]['id']] ? stripslashes($_POST['products_keywords_meta_' .$aLanguages[$i]['id']]) : oos_get_products_keywords_meta($pInfo->products_id, $aLanguages[$i]['id']))); ?>
							</div>
						</div>
					</fieldset>
<?php
	}
?>
                     </div>
                     <div class="tab-pane" id="picture" role="tabpanel">
                        <div class="row mb-3">
                           <div class="col-3">
                              <strong>Preview</strong>
                           </div>
                           <div class="col-9">
                              <strong>Details</strong>
                           </div>
                        </div>
                        <div class="row mb-3 pb-3 bb">
                           <div class="col-6 col-md-3">
                              <a href="#" title="Product 1">
                                 <img class="img-fluid" src="img/bg7.jpg" alt="Product 1">
                              </a>
                           </div>
                           <div class="col-6 col-md-9">
                              <fieldset>
                                 <div class="form-group row">
                                    <input class="form-control" type="text" placeholder="Brief description..">
                                 </div>
                              </fieldset>
                              <p>
                                 <strong>Picture type</strong>
                              </p>
                              <div class="c-radio c-radio-nofont">
                                 <label>
                                    <input type="radio" name="prod1-pic" value="option1" checked="">
                                    <span></span>Primary</label>
                              </div>
                              <div class="c-radio c-radio-nofont">
                                 <label>
                                    <input type="radio" name="prod1-pic" value="option2">
                                    <span></span>Thumbnail</label>
                              </div>
                              <div class="c-radio c-radio-nofont">
                                 <label>
                                    <input type="radio" name="prod1-pic" value="option3">
                                    <span></span>Gallery</label>
                              </div>
                              <div class="text-right">
                                 <button class="btn btn-sm btn-danger" type="button">
                                    <em class="fa fa-times-circle fa-fw"></em>Remove</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <div class="text-right mt-3">
               <button class="btn btn-warning" type="button">Discard</button>
               <button class="btn btn-success" type="button">Save</button>
            </div>
            </form>
		

<?php
##
?>

<table>

      <tr>
        <td></td>
      </tr>
      <tr>
	  
        <td><table border="0" cellspacing="0" cellpadding="2">


          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_QUANTITY; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_quantity', $pInfo->products_quantity) . ' Min: ' . oos_draw_input_field('products_quantity_order_min', ($pInfo->products_quantity_order_min==0 ? 1 : $pInfo->products_quantity_order_min)) . ' Units: ' . oos_draw_input_field('products_quantity_order_units', $pInfo->products_quantity_order_units); ?></td>
          </tr>
<?php
	if (STOCK_CHECK == 'true') {
?>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_REORDER_LEVEL; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_reorder_level', $pInfo->products_reorder_level); ?></td>
<?php
	}
?>
          <tr>
            <td class="main"><?php echo TEXT_REPLACEMENT_PRODUCT; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_replacement_product_id', $pInfo->products_replacement_product_id); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_MODEL; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_model', $pInfo->products_model); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_EAN; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_ean', $pInfo->products_ean); ?></td>
          </tr>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_IMAGE; ?></td>
            <td class="main"><?php echo '&nbsp;' . oos_draw_file_field('products_image') . oos_draw_hidden_field('products_previous_image', $pInfo->products_image); ?></td>
          </tr>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"></td>
          </tr>
<?php
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
?>
          <tr>
            <td class="main"><?php if ($i == 0) echo TEXT_PRODUCTS_URL . '<br /><small>' . TEXT_PRODUCTS_URL_WITHOUT_HTTP . '</small>'; ?></td>
            <td class="main"><?php echo oos_flag_icon($aLanguages[$i]) . '&nbsp;' . oos_draw_input_field('products_url[' . $aLanguages[$i]['id'] . ']', (($products_url[$aLanguages[$i]['iso_639_2']]) ? stripslashes($products_url[$aLanguages[$i]['id']]) : oos_get_products_url($pInfo->products_id, $aLanguages[$i]['id']))); ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_PRICE; ?></td>
            <td class="main">
<?php
	$sPrice = number_format($pInfo->products_price, TAX_DECIMAL_PLACES, '.', '');
	echo '&nbsp;' . oos_draw_input_field('products_price', $sPrice);
?>
      </td>
    </tr>
    <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_LIST_PRICE; ?></td>
            <td class="main">
<?php
	$sPriceList = number_format($pInfo->products_price_list, TAX_DECIMAL_PLACES, '.', '');
	echo '&nbsp;' . oos_draw_input_field('products_price_list', $sPriceList);
?>
            </td>
          </tr>
<?php
  if (OOS_BASE_PRICE == 'true') {
?>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_BASE_PRICE_FACTOR; ?></td>
            <td class="main"><table border="0">
                <tr>
                  <td class="main"><br />&nbsp;<?php echo oos_draw_input_field('products_base_price', $pInfo->products_base_price); ?></td>
                  <td class="main"><br /> <- </td>
                  <td class="main"><?php echo TEXT_PRODUCTS_PRODUCT_QUANTITY . '<br />' . oos_draw_input_field('products_product_quantity', $pInfo->products_product_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                  <td class="main"><?php echo TEXT_PRODUCTS_BASE_QUANTITY . '<br />' . oos_draw_input_field('products_base_quantity', $pInfo->products_base_quantity, 'OnKeyUp="calcBasePriceFactor()"'); ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
             <td class="main"><?php echo TEXT_PRODUCTS_BASE_UNIT; ?></td>
             <td class="main">&nbsp;<?php echo oos_draw_input_field('products_base_unit', $pInfo->products_base_unit); ?></td>
           </tr>
        </table><br />
        <table border="0" cellspacing="0" cellpadding="2">
<?php
  }
?>

          <tr>
            <td colspan="2"></td>
          </tr>
           <tr>
             <td class="main"><?php echo TEXT_PRODUCTS_UNIT; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_pull_down_menu('products_units_id', $products_units_array, $pInfo->products_units_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
            <td class="main">
               <table border='2'>
                 <tr>
                   <td colspan="5" class="main" align="center"><?php echo TEXT_DISCOUNTS_TITLE; ?></td>
                 </tr>
				 <tr>
                   <td class="main" align="center" width="75"><?php echo TEXT_DISCOUNTS_BREAKS; ?></td>
                   <td class="main" align="center" width="75">1</td>
                   <td class="main" align="center" width="75">2</td>
                   <td class="main" align="center" width="75">3</td>
                   <td class="main" align="center" width="75">4</td>                
                 </tr>
                 <tr>
                   <td class="main" align="center" width="75"><?php echo TEXT_DISCOUNTS_QTY; ?><br /><?php echo TEXT_DISCOUNTS_PRICE; ?></td>
 <?php
   $sDiscount1 = number_format($pInfo->products_discount1, TAX_DECIMAL_PLACES, '.', '');
   $sDiscount2 = number_format($pInfo->products_discount2, TAX_DECIMAL_PLACES, '.', '');
   $sDiscount3 = number_format($pInfo->products_discount3, TAX_DECIMAL_PLACES, '.', '');
   $sDiscount4 = number_format($pInfo->products_discount4, TAX_DECIMAL_PLACES, '.', '');
 ?>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount1_qty', $pInfo->products_discount1_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount1', $sDiscount1, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount2_qty', $pInfo->products_discount2_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount2', $sDiscount2, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount3_qty', $pInfo->products_discount3_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount3', $sDiscount3, 'size="10"'); ?></td>
                   <td class="main" width="75"><?php echo oos_draw_input_field('products_discount4_qty', $pInfo->products_discount4_qty, 'size="10"'); ?><br /><?php echo oos_draw_input_field('products_discount4', $sDiscount4, 'size="10"'); ?></td>
                  </tr>
               </table>
            </td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_TAX_CLASS; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_pull_down_menu('products_tax_class_id', $tax_class_array, $pInfo->products_tax_class_id); ?></td>
          </tr>
          <tr>
            <td colspan="2"></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_PRODUCTS_WEIGHT; ?></td>
            <td class="main">&nbsp;<?php echo oos_draw_input_field('products_weight', $pInfo->products_weight); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main" align="right"><?php echo oos_draw_hidden_field('products_date_added', (($pInfo->products_date_added) ? $pInfo->products_date_added : date('Y-m-d'))) . oos_submit_button('save', IMAGE_SAVE); ?></td>
      </form></tr>
	  	      </table>
<!-- body_text_eof //-->
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
      } else {
        $products_image_name = $_POST['products_previous_image'];
      }
    } else {
      $product_result = $dbconn->Execute("SELECT pd.products_name, pd.products_description, pd.products_description_meta, products_keywords_meta, pd.products_url, p.products_id, p.products_quantity, p.products_reorder_level, p.products_model, p.products_replacement_product_id, p.products_ean, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_weight, p.products_date_added, p.products_last_modified, date_format(p.products_date_available, '%Y-%m-%d') as products_date_available, p.products_status, p.products_tax_class_id, p.products_units_id, p.manufacturers_id, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE p.products_id = '" . $_GET['pID'] . "' and p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $product = $product_result->fields;

      $pInfo = new objectInfo($product);
      $products_image_name = $pInfo->products_image;
    }

    $form_action = ($_GET['pID']) ? 'update_product' : 'insert_product';

    echo oos_draw_form('id', $form_action, $aContents['products'], 'cPath=' . $cPath . (isset($_GET['pID']) ? '&pID=' . $_GET['pID'] : '') . '&action=' . $form_action, 'post', TRUE, 'enctype="multipart/form-data"');

    $aLanguages = oos_get_languages();
	$nLanguages = count($aLanguages);
    for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
      if (isset($_GET['read']) && ($_GET['read'] == 'only')) {
        $pInfo->products_name = oos_get_products_name($pInfo->products_id, $aLanguages[$i]['id']);
        $pInfo->products_description = oos_get_products_description($pInfo->products_id, $aLanguages[$i]['id']);
        $pInfo->products_description_meta = oos_get_products_description_meta($pInfo->products_id, $aLanguages[$i]['id']);
        $pInfo->products_keywords_meta = oos_get_products_keywords_meta($pInfo->products_id, $aLanguages[$i]['id']);
        $pInfo->products_url = oos_get_products_url($pInfo->products_id, $aLanguages[$i]['id']);
      } else {
        $pInfo->products_name = oos_db_prepare_input($products_name[$aLanguages[$i]['id']]);
        $pInfo->products_description = oos_db_prepare_input($_POST['products_description_' .$aLanguages[$i]['id']]);
        $pInfo->products_description_meta = oos_db_prepare_input($_POST['products_description_meta_' .$aLanguages[$i]['id']]);
        $pInfo->products_keywords_meta = oos_db_prepare_input($_POST['products_keywords_meta_' .$aLanguages[$i]['id']]);
        $pInfo->products_url = oos_db_prepare_input($products_url[$aLanguages[$i]['id']]);
      }
?>
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo oos_flag_icon($aLanguages[$i]) . '&nbsp;' . $pInfo->products_name; ?></td>
<?php
  $oosPrice = $pInfo->products_price;
  $oosPrice=round($oosPrice,TAX_DECIMAL_PLACES);
?>
      <td class="pageHeading" align="right"><?php echo $currencies->format($oosPrice); ?></td>
          </tr>
<?php
  include 'includes/categories_discounts_price.php';
?>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main">
<?php
      if ( (!$delete_image) && (!$remove_image) ) {
        echo (($products_image_name) ? oos_image(OOS_SHOP_IMAGES . $products_image_name, $pInfo->products_name, '', '80', 'align="right" hspace="5" vspace="5"') : '');
      }
      echo $pInfo->products_description;
?></td>
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
      }
?>
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
        <td></td>
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
        $back_url_params = 'cPath=' . $cPath;
        if (oos_is_not_null($pInfo->products_id)) {
          $back_url_params .= '&pID=' . $pInfo->products_id;
        }
      }
?>
      <tr>
        <td align="right"><?php echo '<a href="' . oos_href_link_admin($back_url, $back_url_params) . '">' . oos_button('back', IMAGE_BACK) . '</a>'; ?></td>
      </tr>
<?php
    } else {
?>
      <tr>
        <td align="right" class="smallText">
<?php
/* Re-Post all POST'ed variables */
      reset($_POST);
      foreach ($_POST as $key => $value) {		  
        if (!is_array($_POST[$key])) {
          echo oos_draw_hidden_field($key, htmlspecialchars(stripslashes($value)));
        }
      }
      $aLanguages = oos_get_languages();
	  $nLanguages = count($aLanguages);
      for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
        echo oos_draw_hidden_field('products_name[' . $aLanguages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_name[$aLanguages[$i]['id']])));
        echo oos_draw_hidden_field('products_description[' . $aLanguages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description[$aLanguages[$i]['id']])));
        echo oos_draw_hidden_field('products_description_meta[' . $aLanguages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_description_meta[$aLanguages[$i]['id']])));
        echo oos_draw_hidden_field('products_keywords_meta[' . $aLanguages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_keywords_meta[$aLanguages[$i]['id']])));
        echo oos_draw_hidden_field('products_url[' . $aLanguages[$i]['id'] . ']', htmlspecialchars(stripslashes($products_url[$aLanguages[$i]['id']])));
      }
      echo oos_draw_hidden_field('products_image', stripslashes($products_image_name));

      if (isset($_POST['categories_ids'])) {
        $selected_catids = $_POST['categories_ids'];
        foreach ($selected_catids as $current_category_id)  {
          echo oos_draw_hidden_field('categories_ids[]', stripslashes($current_category_id));
        }
      }

      echo oos_submit_button('back', IMAGE_BACK, 'name="edit"') . '&nbsp;&nbsp;';

      if (isset($_GET['pID'])) {
        echo oos_submit_button('update', IMAGE_UPDATE);
      } else {
        echo oos_submit_button('insert', BUTTON_INSERT);
      }

?></td>
      </form></tr>

<?php
    }
?>
	      </table>
<!-- body_text_eof //-->
<?php
  }
?>


				</div>
			</div>
        </div>

		</div>
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2018 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>

<?php
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>
