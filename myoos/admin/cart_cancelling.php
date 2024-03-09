<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.3.2.4 2003/05/12 22:54:01 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');

require 'includes/main.php';
require 'includes/classes/class_currencies.php';
$currencies = new currencies();

function check_letter_sent ($customer_id, $customers_basket_id)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();
	
	$customers_basket_mailtable = $oostable['customers_basket_mail'];
	$basket_query = "SELECT * FROM $customers_basket_mailtable WHERE customers_basket_id = '" . intval($customers_basket_id) . "' AND customers_id =  '" . intval($customer_id) . "'";
	$basket_result = $dbconn->Execute($basket_query);

	if ($basket_result->RecordCount() > 0) {	
		return true;
	} else {
		return false;
	}
}

/**
 * Find quantity discount
 *
 * @param  $product_id
 * @param  $qty
 * @param  $current_price
 * @return string
 */
function oos_get_product_qty_dis_price($product_id, $qty, $current_price = false)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_price, products_discount1, products_discount2, products_discount3,
                     products_discount4, products_discount1_qty, products_discount2_qty, products_discount3_qty,
                     products_discount4_qty
              FROM $productstable
              WHERE products_id = '" . intval($product_id) . "'";
    $product_discounts = $dbconn->GetRow($query);

    switch (true) {
        case ($qty == 1 or ($product_discounts['products_discount4_qty'] == 0 and $product_discounts['products_discount3_qty'] == 0 and $product_discounts['products_discount2_qty'] == 0 and $product_discounts['products_discount1_qty'] == 0)):
            if ($current_price) {
                $the_discount_price = $current_price;
            } else {
                $the_discount_price = $product_discounts['products_price'];
            }
            break;

        case ($qty >= $product_discounts['products_discount4_qty'] and $product_discounts['products_discount4_qty'] != 0):
            $the_discount_price = $product_discounts['products_discount4'];
            break;

        case ($qty >= $product_discounts['products_discount3_qty'] and $product_discounts['products_discount3_qty'] != 0):
            $the_discount_price = $product_discounts['products_discount3'];
            break;

        case ($qty >= $product_discounts['products_discount2_qty'] and $product_discounts['products_discount2_qty'] != 0):
            $the_discount_price = $product_discounts['products_discount2'];
            break;

        case ($qty >= $product_discounts['products_discount1_qty'] and $product_discounts['products_discount1_qty'] != 0):
            $the_discount_price = $product_discounts['products_discount1'];
            break;

        default:
            if ($current_price) {
                $the_discount_price = $current_price;
            } else {
                $the_discount_price = $product_discounts['products_price'];
            }
            break;
    }
    return $the_discount_price;
}



function products_price_actual($product_id, $actual_price, $products_qty)
{
	$new_price = $actual_price;

	if ($new_discounts_price = oos_get_product_qty_dis_price($product_id, $products_qty, $new_price)) {
		$new_price = $new_discounts_price;
	}
	return $new_price;
}


$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'make_file_now':
	
		//prevent script from running more than once a day
		$configurationtable = $oostable['configuration'];
		$sql = "SELECT configuration_value FROM $configurationtable WHERE configuration_key = 'LASTBASKET_MAIL'";
		$prevent_result = $dbconn->Execute($sql);

		if ($prevent_result->RecordCount() > 0) {
			$prevent = $prevent_result->fields;
			if ($prevent['configuration_value'] == date("Ymd")) {
				die('Halt! Already executed - should not execute more than once a day.');
				// $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'error');
				// oos_redirect_admin(oos_href_link_admin($aContents['cart_cancelling']));				
			}
		}

		if ($prevent_result->RecordCount() > 0) {
			$configurationtable = $oostable['configuration'];
			$dbconn->Execute("UPDATE $configurationtable SET configuration_value = '" . date("Ymd") . "' WHERE configuration_key = 'LASTBASKET_MAIL'");
		} else {
			$configurationtable = $oostable['configuration'];
#        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id) VALUES ('LASTBASKET_MAIL', '" . date("Ymd") . "', '6')");
			$sql = "INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id) VALUES ('LASTBASKET_MAIL', '" . date("Ymd") . "', '6')";
			echo $sql;
		}
	
        $mail_file = 'basket_mail-' . date('YmdHis') . '.cvs';
        $fp = fopen(OOS_EXPORT_PATH . $mail_file, 'w');

		$indent = "\t";

        $schema = '';
        $schema .= 'Firma ' . $indent . ' Name ' . $indent . ' Straße ' . $indent . ' PLZ ' . $indent . ' Ort ' . $indent . ' Land ' . $indent . ' Warenborbdatum ' . $indent;
		$schema .= ' Produktname ' . $indent . ' Menge ' . $indent . ' Einzelpreis ' . $indent . ' Gültig bis ' . $indent . ' Grundpreis ' . $indent . ' Basismenge ' . $indent . ' Produkteinheit ' . $indent;
		$schema .= ' Produktname2 ' . $indent . ' Menge2 ' . $indent . ' Einzelpreis2 ' . $indent . ' Gültig bis2 ' . $indent . ' Grundpreis2 ' . $indent . ' Basismenge2 ' . $indent . ' Produkteinheit2 ' . $indent;

        $nLanguageID = intval($_SESSION['language_id'] ?? DEFAULT_LANGUAGE_ID);

        $aProducts = [];

		$products_unitstable = $oostable['products_units'];
		$query = "SELECT products_units_id, products_unit_name, unit_of_measure
					FROM $products_unitstable
					WHERE languages_id = '" . intval($nLanguageID) . "'";
		$products_unit_result = $dbconn->Execute($query);
		$products_units = []; // initialize a new array
		while ($products_unit = $products_unit_result->fields) {
			// get the products_units_id as the index for the new array
			$index = $products_unit['products_units_id'];
			// insert the products_unit_name and unit_of_measure as a numeric array under the index
			$products_units[$index] = [$products_unit['products_unit_name'], $products_unit['unit_of_measure']];
			// Move that ADOdb pointer!
			$products_unit_result->MoveNext();
		}

/*
todo customers_basket_mail
$table = $prefix_table . 'customers_basket_mail';
$flds = "
  customers_basket_mail I NOTNULL AUTO PRIMARY,
  customers_basket_id I NOTNULL,
  customers_id I NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_mail_date_added T,
  orders_id I NOTNULL PRIMARY,
  orders_date T  
";
*/		
	
/*
$table = $prefix_table . 'customers_basket';
$flds = "
  customers_basket_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  to_wishlist_id C(32) NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_quantity I2 NOTNULL DEFAULT '1',
  free_redemption C(1) DEFAULT '',
  final_price N '10.4' NOTNULL DEFAULT '0.0000',
  customers_basket_date_added C(8)
";
dosql($table, $flds);
*/

		$days = 2;
        $sd = mktime(0, 0, 0, date("m"), date("d") - $days, date("Y"));

        $customers_baskettable = $oostable['customers_basket'];
        $basket_result = $dbconn->Execute("SELECT customers_basket_id, customers_id, customers_basket_date_added  FROM $customers_baskettable WHERE customers_basket_date_added <= '" . oos_db_input(date("Ymd", $sd)) . "'");
 
		if ($basket_result->RecordCount() > 0) {
			while ($basket = $basket_result->fields) {
                echo $basket['customers_id'];
				$customers_basket_id = $basket['customers_basket_id'];
				$customer_id = $basket['customers_id'];	
				
				if (!check_letter_sent($customer_id, $customers_basket_id )) {

					$customerstable = $oostable['customers'];
					$address_booktable = $oostable['address_book'];
					$customers_result = $dbconn->Execute("SELECT c.customers_gender, c.customers_firstname, c.customers_lastname, c.customers_dob, 
																c.customers_email_address, c.customers_wishlist_link_id, c.customers_2fa_active,
																a.entry_company, a.entry_owner, a.entry_vat_id, a.entry_vat_id_status, 
																a.entry_street_address, a.entry_postcode, a.entry_city, a.entry_state, a.entry_zone_id,
																a.entry_country_id, c.customers_telephone,
																c.customers_default_address_id, c.customers_status, c.customers_max_order
														FROM  $customerstable c LEFT JOIN
																$address_booktable a
																ON c.customers_default_address_id = a.address_book_id
														WHERE a.customers_id = c.customers_id AND
																c.customers_id = '" .  intval($customer_id) . "'");
					$customers = $customers_result->fields;

					$schema .= $customers['entry_company'] . $indent . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . $indent;
					$schema .= $customers['entry_street_address'] . $indent . $customers['entry_postcode'] . $indent . $customers['entry_city'] . $indent;

					$countriestable = $oostable['countries'];
					$country_result = $dbconn->Execute("SELECT countries_name
														FROM $countriestable
														WHERE countries_id = '" . intval($customers['entry_country_id']) . "'");
					$country = $country_result->fields;

					$schema .= $country['countries_name'] . $indent;

					$rows = 0;
					$aProducts = [];
					$customers_baskettable = $oostable['customers_basket'];
					$sql = "SELECT customers_basket_id, customers_id, products_id, customers_basket_quantity
							FROM $customers_baskettable
							WHERE customers_id = '" . intval($customer_id) . "'
							AND customers_basket_date_added <= '" . oos_db_input(date("Ymd", $sd)) . "'";
					$products_result = $dbconn->Execute($sql);
					while ($products = $products_result->fields) {
						$rows++;

						if ($rows >= 2) {
							break; // ends the loop
						}						
						$aProducts[$products['products_id']] = ['customers_basket_id' => $products['customers_basket_id'],
																'qty' => $products['customers_basket_quantity']];
						// attributes
						$customers_basket_attributestable = $oostable['customers_basket_attributes'];
						$sql = "SELECT products_options_id, products_options_value_id, products_options_value_text
								FROM $customers_basket_attributestable
								WHERE customers_id = '" . intval($customer_id) . "'
								AND products_id = '" . $products['products_id'] . "'";
						$attributes_result = $dbconn->Execute($sql);
						while ($attributes = $attributes_result->fields) {
							$aProducts[$products['products_id']]['attributes'][$attributes['products_options_id']] = $attributes['products_options_value_id'];
							if ($attributes['products_options_value_id'] == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
								$aProducts[$products['products_id']]['attributes_values'][$attributes['products_options_id']] = $attributes['products_options_value_text'];
							}

							// Move that ADOdb pointer!
							$attributes_result->MoveNext();
						}

						// Move that ADOdb pointer!
						$products_result->MoveNext();
					}

					$aGroup = [];
					$customerstable = $oostable['customers'];
					$customers_statustable = $oostable['customers_status'];
					$sql = "SELECT c.customers_status, cs.customers_status_id, cs.customers_status_name, cs.customers_status_public, 
							cs.customers_status_show_price, cs.customers_status_show_price_tax, 
							cs.customers_status_ot_discount_flag, cs.customers_status_ot_minimum, 
							cs.customers_status_ot_discount, cs.customers_status_qty_discounts, cs.customers_status_payment
						FROM $customerstable AS c LEFT JOIN
							$customers_statustable AS cs
						ON customers_status = customers_status_id
						WHERE c.customers_id='" . intval($customer_id) . "' AND
							cs.customers_status_languages_id = '" .  intval($nLanguageID) . "'";
					$customer_status = $dbconn->GetRow($sql);

					$aGroup = ['id' => $customer_status['customers_status_id'], 
								'text' => $customer_status['customers_status_name'], 
								'public' => $customer_status['customers_status_public'],
								'show_price' => $customer_status['customers_status_show_price'], 
								'price_with_tax' => $customer_status['customers_status_show_price_tax'],
								'ot_discount_flag' => $customer_status['customers_status_ot_discount_flag'], 
								'ot_discount' => $customer_status['customers_status_ot_discount'],
								'ot_minimum' => $customer_status['customers_status_ot_minimum'], 
								'qty_discounts' => $customer_status['customers_status_qty_discounts'], 
								'payment' => $customer_status['customers_status_payment']];
 
					reset($aProducts);
					foreach (array_keys($aProducts) as $products_id) {
						$product_price = '';
						$base_product_price = '';				
						$nQuantity = $aProducts[$products_id]['qty'];

						$productstable = $oostable['products'];
						$products_descriptiontable = $oostable['products_description'];
						$sql = "SELECT p.products_id, pd.products_name, pd.products_essential_characteristics, p.products_image, p.products_model, 
								p.products_ean, p.products_price, p.products_base_price, p.products_product_quantity, p.products_units_id, 
								p.products_base_unit, p.products_weight, p.products_tax_class_id, p.products_quantity, p.products_quantity_order_min, 
								p.products_quantity_order_max, p.products_quantity_order_units, p.products_old_electrical_equipment
						FROM $productstable p,
							$products_descriptiontable pd
						WHERE p.products_setting >= '1' AND 
							p.products_id = '" . oos_get_product_id($products_id) . "' AND
							pd.products_id = p.products_id AND
							pd.products_languages_id = '" .  intval($nLanguageID) . "'";
						$products_result = $dbconn->Execute($sql);
				
						if ($products = $products_result->fields) {
							$prid = $products['products_id'];
							if ($aGroup['qty_discounts'] == 1) {
								$products_price = products_price_actual($prid, $products['products_price'], $nQuantity);
							} else {
								$products_price = $products['products_price'];
							}


							$until = '';
							$specialstable = $oostable['specials'];
							$sql = "SELECT specials_new_products_price, specials_cross_out_price, expires_date
									FROM $specialstable
									WHERE products_id = '" . intval($prid) . "' AND
									status = '1'";
							$specials_result = $dbconn->Execute($sql);
							if ($specials_result->RecordCount()) {
								$specials = $specials_result->fields;
								$products_price = $specials['specials_new_products_price'];
								$until = oos_date_short($specials_result['expires_date']);
							}

							if (isset($aProducts[$products_id]['attributes'])) {
								reset($aProducts[$products_id]['attributes']);


								foreach ($aProducts[$products_id]['attributes'] as $option => $value) {
									$products_attributestable = $oostable['products_attributes'];
									$attribute_price_sql = "SELECT options_values_price
															FROM $products_attributestable
															WHERE products_id = '" . intval($products_id) . "'
															AND options_id = '" . intval($option) . "'
															AND options_values_id = '" . intval($value) . "'";
									$attribute_price = $dbconn->GetRow($attribute_price_sql);
									$products_price = $attribute_price['options_values_price'];
								}
							}
##
					$products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
											pa.options_values_model, pa.options_values_image, pa.options_values_base_price,
											pa.products_product_quantity, pa.options_values_base_quantity, pa.options_values_base_unit,	
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($nProductsId) . "' 
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "' 
									   AND pa.options_values_status = 1
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = '" . intval($nLanguageID) . "'  
                                    " . $options_sort_by;
					$products_options_result = $dbconn->Execute($products_options_sql);

##
							$final_price = $currencies->display_price($products_price, oos_get_tax_rate($products['products_tax_class_id']));

							if ($products['products_base_price'] != 1) {
								$base_product_price = $currencies->display_price($products_price * $products['products_base_price'], oos_get_tax_rate($products['products_tax_class_id']));
							}

echo '<pre>';
print_r($products_units);
echo '</pre>';

							<span class="units-desc">{$featur.product_quantity|cut_number}&nbsp;{$products_units[$featur.products_units].0}</span>
							<span class="base_price">({$products_units[$featur.products_units].1} = {$featur.featured_base_product_price})</span>

/*

				$final_price = $products_price;

                $base_product_price = null;
                $products_product_quantity = null;
                $cart_base_product_price = null;


                if ($products['products_base_price'] != 1) {
                    $base_product_price = $products_price;
                    $products_product_quantity = $products['products_product_quantity'];
                    $cart_base_product_price = $currencies->display_price($base_product_price * $products['products_base_price'], oos_get_tax_rate($products['products_tax_class_id']));
                }


                $aProducts[] = ['id' => $products_id,
                                'name' => $products['products_name'],
                                'essential_characteristics' => $products['products_essential_characteristics'],
                                'model' => $model,
                                'image' => $image,
                                'ean' => $products['products_ean'],
                                'products_quantity_order_min' => $products['products_quantity_order_min'],
                                'products_quantity_order_max' => $products['products_quantity_order_max'],
                                'products_quantity_order_units' => $products['products_quantity_order_units'],
                                'price' => $products_price,
                                'spezial' => $bSpezialPrice,
                                'quantity' => $aProducts[$products_id]['qty'],
                                'stock' => $products['products_quantity'],
                                'weight' => $products['products_weight'],
                                'final_price' => $final_price,
                                'tax_class_id' => $products['products_tax_class_id'],
                                'products_base_price' => $products['products_base_price'],
                                'base_product_price' => $cart_base_product_price,
                                'products_product_quantity' => $products_product_quantity,
                                'products_units_id' => $products['products_units_id'],
                                'attributes' => ($aProducts[$products_id]['attributes'] ?? ''),
                                'attributes_values' => ($aProducts[$products_id]['attributes_values'] ?? ''),
                                'old_electrical_equipment' => $products['products_old_electrical_equipment'],
                                'return_free_of_charge' => ($aProducts[$products_id]['return_free_of_charge'] ?? ''),
                                'towlid' => $aProducts[$products_id]['towlid']];
            }
 */

# $schema .= ' Produktname ' . $indent . ' Menge ' . $indent . ' Einzelpreis ' . $indent . ' Gültig bis ' . $indent . ' Grundpreis ' . $indent . ' Basismenge ' . $indent . ' Produkteinheit ' . $indent;

$schema .= $products['products_name'] . $indent . $nQuantity . $indent . $final_price . $indent . $until . $indent . $base_product_price . $indent;



							// Move that ADOdb pointer!
							$products_result->MoveNext();
 
 
						}	
					}
					
					 $schema .= "\n";
				}				

                // Move that ADOdb pointer!
                $basket_result->MoveNext();
            }
        }

echo $schema;
exit;


/*	
todo customers_basket_mail
$table = $prefix_table . 'customers_basket_mail';
$flds = "
  customers_basket_mail I NOTNULL AUTO PRIMARY,
  customers_basket_id I NOTNULL,
  customers_id I NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_mail_date_added T,
  orders_id I NOTNULL PRIMARY,
  orders_date T  
";
*/
/*

*/

        fputs($fp, $schema);
        fclose($fp);

        if (isset($_POST['download']) && ($_POST['download'] == 'yes')) {
            header('Content-type: application/x-octet-stream');
            header('Content-disposition: attachment; filename=' . $mail_file);

            readfile(OOS_EXPORT_PATH . $mail_file);
            @unlink(OOS_EXPORT_PATH . $mail_file);

            exit;
        } else {
            $messageStack->add_session(SUCCESS_DATABASE_SAVED, 'success');
        }
        oos_redirect_admin(oos_href_link_admin($aContents['cart_cancelling']));
        break;

    case 'download':
        $sFile = oos_db_prepare_input($_GET['file']);
        $extension = substr((string) $_GET['file'], -3);
        if (($extension == 'zip') || ($extension == '.gz') || ($extension == 'cvs')) {
            if ($fp = fopen(OOS_EXPORT_PATH . $sFile, 'rb')) {
                $buffer = fread($fp, filesize(OOS_EXPORT_PATH . $sFile));
                fclose($fp);
                header('Content-type: application/x-octet-stream');
                header('Content-disposition: attachment; filename=' . $sFile);
                echo $buffer;
                exit;
            }
        } else {
            $messageStack->add(ERROR_DOWNLOAD_LINK_NOT_ACCEPTABLE, 'error');
        }
        break;
    case 'deleteconfirm':
        if (strstr((string) $_GET['file'], '..')) {
            oos_redirect_admin(oos_href_link_admin($aContents['cart_cancelling']));
        }

        oos_remove(OOS_EXPORT_PATH . '/' . oos_db_prepare_input($_GET['file']));
        if (!$oos_remove_error) {
            $messageStack->add_session(SUCCESS_EXPORT_DELETED, 'success');
            oos_redirect_admin(oos_href_link_admin($aContents['cart_cancelling']));
        }
        break;
}


// check if the backup directory exists
$dir_ok = false;
if (is_dir(oos_get_local_path(OOS_EXPORT_PATH))) {
    if (is_writeable(oos_get_local_path(OOS_EXPORT_PATH))) {
        $dir_ok = true;
    } else {
        $messageStack->add(ERROR_EXPORT_DIRECTORY_NOT_WRITEABLE, 'error');
    }
} else {
    $messageStack->add(ERROR_EXPORT_DIRECTORY_DOES_NOT_EXIST, 'error');
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
                
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['stats_products_purchased'], 'selected_box=reports') . '">' . BOX_HEADING_REPORTS . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->
            
            <div class="wrapper wrapper-content">
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
							<th><?php echo TABLE_HEADING_TITLE; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_FILE_DATE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_FILE_SIZE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
if ($dir_ok) {
	$dir = OOS_EXPORT_PATH;
	$files = scandir($dir);
	$contents = array_filter($files, function($file) {
		return strpos($file, "db_export-") === 0;
	});
	rsort($contents);

    $rows = 0;
    $aDocument = [];

    for ($files = 0, $count = count($contents); $files < $count; $files++) {
        $rows = $files;
        $entry = $contents[$files];

        $check = 0;

        if ((!isset($_GET['file']) || (isset($_GET['file']) && ($_GET['file'] == $entry))) && !isset($buInfo) && ($action != 'backup')) {
            $file_array['file'] = $entry;
            $file_array['date'] = date(PHP_DATE_TIME_FORMAT, filemtime(OOS_EXPORT_PATH . $entry));
            $file_array['size'] = number_format(filesize(OOS_EXPORT_PATH . $entry)) . ' bytes';

            $file_array['compression'] = match (substr($entry, -3)) {
                'zip' => 'ZIP',
                '.gz' => 'GZIP',
                default => TEXT_NO_EXTENSION,
            };

            $buInfo = new objectInfo($file_array);
        }

        $onclick_link = 'file=' . $entry;
        if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
            echo '              <tr id="row-' . $rows .'">' . "\n";
        } else {
            $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['cart_cancelling'], $onclick_link)];
            echo '              <tr id="row-' . $rows .'">' . "\n";
        }
        ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['cart_cancelling'], 'action=download&file=' . $entry) . '"><button class="btn btn-default" type="button"><i class="fa fa-download" title="' . ICON_FILE_DOWNLOAD . '" aria-hidden="true"></i></button></a>&nbsp;' . $entry; ?></td>
                <td align="center"><?php echo date(PHP_DATE_TIME_FORMAT, filemtime(OOS_EXPORT_PATH . $entry)); ?></td>
                <td align="right"><?php echo number_format(filesize(OOS_EXPORT_PATH . $entry)); ?> bytes</td>
                <td class="text-right"><?php if (isset($buInfo) && is_object($buInfo) && ($entry == $buInfo->file)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['cart_cancelling'], 'file=' . $entry) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    }
}
?>
              <tr>
                <td class="smallText" colspan="3"><?php echo TEXT_EXPORT_DIRECTORY . ' ' . OOS_EXPORT_PATH; ?></td>
                <td align="right" class="smallText"><?php if ($action != 'backup') {
                    echo '<a href="' . oos_href_link_admin($aContents['cart_cancelling'], 'action=backup') . '">' . oos_button(BUTTON_CART_CANCELLING_EXPORT) . '</a>';
                } ?></td>
             </tr>
            </table></td>
<?php
                  $heading = [];
$contents = [];

switch ($action) {
    case 'backup':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_NEW_EXPORT . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'backup', $aContents['cart_cancelling'], 'action=make_file_now', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_NEW_EXPORT];

        # todo
        #if (file_exists(LOCAL_EXE_ZIP)) {
        #$contents[] = array('text' => oos_draw_radio_field('compress', 'zip') . ' ' . TEXT_INFO_USE_ZIP);
        #}

        if ($dir_ok == true) {
            $contents[] = ['text' => '<br>' . oos_draw_checkbox_field('download', 'yes') . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS];
        } else {
            $contents[] = ['text' => '<br>' . oos_draw_radio_field('download', 'yes', true) . ' ' . TEXT_INFO_DOWNLOAD_ONLY . '*<br><br>*' . TEXT_INFO_BEST_THROUGH_HTTPS];
        }

        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_EXPORT) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['cart_cancelling']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    case 'delete':
        $heading[] = ['text' => '<b>' . $buInfo->date . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'delete', $aContents['cart_cancelling'], 'file=' . $buInfo->file . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $buInfo->file . '</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['cart_cancelling'], 'file=' . $buInfo->file) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

        break;

    default:
        if (isset($buInfo) && is_object($buInfo)) {
            $heading[] = ['text' => '<b>' . $buInfo->date . '</b>'];

            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['cart_cancelling'], 'file=' . $buInfo->file . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DATE . ' ' . $buInfo->date];
            $contents[] = ['text' => TEXT_INFO_SIZE . ' ' . $buInfo->size];
            $contents[] = ['text' => '<br>' . TEXT_INFO_COMPRESSION . ' ' . $buInfo->compression];
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
}
?>
          </tr>
        </table>
	</div>
<!-- body_text_eof //-->

                </div>
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
