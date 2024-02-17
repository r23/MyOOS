<?php
/* ----------------------------------------------------------------------
   $Id: rss.php,v 1.36 2007/01/31 16:33:42 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2009 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');



function export($text) {

	// $utf8_text = utf8_encode($text);
	// $utf8_text = iconv(mb_detect_encoding($text, mb_detect_order(), true), "UTF-8", $text); // Der String in UTF-8

	$utf8_text = str_replace ("<sup>®</sup>", "", $utf8_text);
	$utf8_text = str_replace ("®", "", $utf8_text);
	$utf8_text = str_replace ("<sup>&reg;</sup>", "", $utf8_text);
	$utf8_text = str_replace ("&reg;", "", $utf8_text);
	$utf8_text = str_replace (" & ", " &amp; ", $utf8_text);	
	// return the string
	return $utf8_text;
}


// Create HTMLPurifier config object
$config = HTMLPurifier_Config::createDefault();

// Set options for the HTMLPurifier configuration
$config->set('HTML.Allowed', 'p[style],span[style],b,strong,em,,br,ul,li,dl,dt,div,i,ul,li,ol,blockquote,br,h1,h2,h3,h4,h5,h6,code,pre,sub,sup,del,div,bull,reg,strong,#9989,');
$config->set('AutoFormat.AutoParagraph', true);

// Create HTMLPurifier object
$purifier = new HTMLPurifier($config);


$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$products_sql = "SELECT p.products_id, p.products_max, pd.products_name, pd.products_description, pd.products_url,
                              pd.products_description_meta, pd.products_keywords_meta, p.products_model, p.products_ean,
                              p.products_quantity, p.products_image, p.products_subimage1, p.products_subimage2,
                              p.products_subimage3, p.products_subimage4, p.products_subimage5, p.products_subimage6,
                              p.products_movie, p.products_zoomify, p.products_discount_allowed, p.products_price, p.products_status,
                              p.products_product_quantity, p.products_base_quantity,
                              p.products_base_price, p.products_base_unit, p.products_quantity_order_min, p.products_quantity_order_units,
                              p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                              p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                              p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                              p.products_date_available, p.manufacturers_id, p.products_price_list, p.products_delivery_time
                  FROM $productstable p,
                       $products_descriptiontable pd
                 WHERE p.products_status = 3
                   AND p.products_id = pd.products_id
                   AND pd.products_languages_id = 1
                 ORDER BY p.products_date_added DESC, pd.products_name";
$products_result = $dbconn->Execute($products_sql);

$indent = "\t\t";
$out = '';

$site_name = (!empty(SITE_NAME) ? SITE_NAME : STORE_NAME);

$out = '<?xml version="1.0"?>' . "\n";
$out .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . "\n";
$out .= "\t<channel>\n";
$out .= $indent . "<title>" . export($site_name) . "</title>\n";
$out .= $indent . "<link" . OOS_HTTPS_SERVER . OOS_SHOP . "</link>\n";
$out .= $indent . "<description>" . export(OOS_META_DESCRIPTION) . "</description>\n";

$rows = 0;
while ($products = $products_result->fields) {
	$rows++;

	//  If a condition is not fulfilled, skip the current data record
	if ($products['products_price'] < 1) {
		$products_result->MoveNext();
		continue;
	}
	
	$products_name = '';
	$products_description = '';
	$sUrl = '';
	$sImage = '';
	$sImage1 = '';
	$sImage2 = '';
	$sImage3 = '';
	$sImage4 = '';
	$sImage5 = '';
	$sImage6 = '';

	$info_product_price  = '';

	$products_product_quantity = 0;
	$products_base_quantity = 0;
	$division_result = 0;
	$rounded_result = 0;

	$brand = '';

	$products_name = export($products['products_name']);
	$products_name = substr($products_name, 0, 150); // returns the first 150 characters

	$products_description = export($products['products_description']);
	$products_description = substr($products_description, 0, 4980); // returns the first 4980 characters
	$products_description = $purifier->purify($products_description);


	$products_has_attributes = false;

	$products_optionstable = $oostable['products_options'];
	$products_attributestable = $oostable['products_attributes'];
	$attributes_sql = "SELECT COUNT(*) AS total
						FROM $products_optionstable popt,
                            $products_attributestable patrib
                       WHERE patrib.products_id = '" . intval($products['products_id']) . "'
                         AND patrib.options_id = popt.products_options_id
                         AND popt.products_options_languages_id = 1";
	$products_attributes = $dbconn->Execute($attributes_sql);
	if ($products_attributes->fields['total'] > 0) {
		$products_has_attributes = true; 
	}	


	$sUrl = OOS_HTTP_SERVER . OOS_SHOP . 'index.php?content=' . $aContents['product_info'] . '&products_id=' . $products['products_id'];
	$sImage = OOS_SHOP_IMAGES . 'product/large/' . $products['products_image'];

	$info_product_price = $oCurrencies->display_price($products['products_price'], oos_get_tax_rate($products['products_tax_class_id']));
	

	$manufacturerstable = $oostable['manufacturers'];
	$manufacturers_sql = "SELECT manufacturers_name
                  FROM $manufacturerstable
                 WHERE manufacturers_id = '". intval($products['manufacturers_id']) ."'";
	$manufacturers_result = $dbconn->Execute($manufacturers_sql);
	$manufacturers = $manufacturers_result->fields;

	$brand = export($manufacturers['manufacturers_name']);
	$brand  = str_replace ("- ", "", $brand);	
	$brand = substr($brand, 0,  70 ); // returns the first 70 characters

	if ($products_has_attributes == false) {

		$out .= $indent . "\n";	
		$out .= '<item>' . "\n";	
		$out .= '<g:id>' . $products['products_id'] . '</g:id>' . "\n";
		$out .= '<g:title>' . $products_name . '</g:title>' . "\n";
		$out .= '<g:description>' . $products_description . '</g:description>' . "\n";
		$out .= '<g:link>' . $sUrl . '</g:link>' . "\n";
		$out .= '<g:image_link>' . $sImage . '</g:image_link>' . "\n";
		
	
		if ($products['products_subimage1'] != '') {
			$sImage1 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage1'];

			$out .= '<g:additional_image_link>' . $sImage1 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage2'] != '') {
			$sImage2 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage2'];

			$out .= '<g:additional_image_link>' . $sImage2 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage3'] != '') {
			$sImage3 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage3'];

			$out .= '<g:additional_image_link>' . $sImage3 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage4'] != '') {
			$sImage4 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage4'];

			$out .= '<g:additional_image_link>' . $sImage4 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage5'] != '') {
			$sImage5 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage5'];

			$out .= '<g:additional_image_link>' . $sImage5 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage6'] != '') {
			$sImage6 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage6'];

			$out .= '<g:additional_image_link>' . $sImage6 . '</g:additional_image_link>' . "\n";
		}	
 
		if ($brand != '') {
			$out .= '<g:brand>'. $brand . '</g:brand>' . "\n";
			if ($products['products_ean'] != '') {
				$out .= '<g:gtin>'. $products['products_ean'] . '</g:gtin>' . "\n";
			}	
		}

		if ($products['products_status'] = 3) {
			$out .= '<g:availability>in_stock</g:availability>' . "\n";
		}		

		$out .= '<g:price>' . $info_product_price . '</g:price>' . "\n";
				

		$products_base_unit = $products['products_base_unit'];

		switch ($products_base_unit) {
		    case '1 Kilo':
			case '1 Kilogramm':
		    case '1 Kg':
		    case 'KG':
 		    case 'kg':	

			$products_product_quantity = $products['products_product_quantity'];
			$products_base_quantity = $products['products_base_quantity'];
			$division_result = $products_product_quantity / $products_base_quantity; // Das Ergebnis der Division
			$rounded_result = round($division_result, 4); // Das Ergebnis auf vier Dezimalstellen gerundet
			if ($rounded_result != 0) {
				$out .= '<g:unit_pricing_measure>' . $rounded_result . ' kg</g:unit_pricing_measure>' . "\n";
				$out .= '<g:unit_pricing_base_measure>1 kg</g:unit_pricing_base_measure>' . "\n";
			}
			break;

		    case '1 Liter':
			case 'Liter':

			$products_product_quantity = $products['products_product_quantity'];
			$products_base_quantity = $products['products_base_quantity'];
			$division_result = $products_product_quantity / $products_base_quantity; // Das Ergebnis der Division
			$rounded_result = round($division_result, 4); // Das Ergebnis auf vier Dezimalstellen gerundet
			if ($rounded_result != 0) {
				$out .= '<g:unit_pricing_measure>' . $rounded_result . ' l</g:unit_pricing_measure>' . "\n";
				$out .= '<g:unit_pricing_base_measure>1 l</g:unit_pricing_base_measure>' . "\n";
			}
			break;
		}

		$out .= '<g:shipping>' . "\n";
		$out .= '<g:country>DE</g:country>' . "\n";
		$out .= '<g:service>Versand</g:service>' . "\n";
		if ($products['products_price'] < 42.0131) {	
			$out .= '<g:price>4,95 EUR</g:price>' . "\n";
		} else {
			$out .= '<g:price>0 EUR</g:price>' . "\n";
	
		}
		$out .= '</g:shipping>' . "\n";
	} else {
		

      if (PRODUCTS_OPTIONS_SORT_BY_PRICE == 'true') {
        $options_sort_by = ' ORDER BY pa.options_sort_order, pa.options_values_price';
      } else {
        $options_sort_by = ' ORDER BY pa.options_sort_order, pov.products_options_values_name';
      }

      $products_optionstable = $oostable['products_options'];
      $products_attributestable = $oostable['products_attributes'];
      $options_name_sql = "SELECT DISTINCT popt.products_options_id, popt.products_options_name,
                                  popt.products_options_type, popt.products_options_length,
                                  popt.products_options_comment
                           FROM $products_optionstable popt,
                                $products_attributestable patrib
                           WHERE patrib.products_id = '" . intval($products['products_id']) . "'
                             AND patrib.options_id = popt.products_options_id
                             AND popt.products_options_languages_id = 1
                           ORDER BY popt.products_options_name";
      $products_options_name_result = $dbconn->Execute($options_name_sql);
	  $rows_b = 0;
	  
      while ($products_options_name = $products_options_name_result->fields) {
        $rows_b++;
		
        switch ($products_options_name['products_options_type']) {
          case PRODUCTS_OPTIONS_TYPE_RADIO:
            $products_attributestable = $oostable['products_attributes'];
            $products_options_valuestable = $oostable['products_options_values'];
            $products_options_sql = "SELECT pov.products_options_values_id, pov.products_options_values_name,
                                            pa.options_values_price, pa.price_prefix, pa.options_sort_order
                                     FROM $products_attributestable pa,
                                          $products_options_valuestable pov
                                     WHERE pa.products_id = '" . intval($products['products_id']) . "'
                                       AND pa.options_id = '" . $products_options_name['products_options_id'] . "' 
                                       AND pa.options_values_id = pov.products_options_values_id 
                                       AND pov.products_options_values_languages_id = 1
                                    " . $options_sort_by;
            $products_options_result = $dbconn->Execute($products_options_sql);
            $rows_c = 0;
            while ($products_options_array = $products_options_result->fields) {
              $rows_c++;

              $options .= '<tr>';
              if ($row == 1) {
                $options .= '<td class="main">' . $products_options_name['products_options_name'] . ': </td>';
              } else {
                $options .= '<td class="main"></td>';
              }
              $options .= '<td class="main">';


              $checked = false;
              if ($_SESSION['cart']->contents[$nProductsId]['attributes'][$products_options_name['products_options_id']] == $products_options_array['products_options_values_id']) {
                $checked = true;
              }
              $options .= oos_draw_radio_field('id[' . $products_options_name['products_options_id'] . ']', $products_options_array['products_options_values_id'], $checked);
              $options .= $products_options_array['products_options_values_name'];
              $options .= $products_options_name['products_options_comment'];

              if ($products_options_array['options_values_price'] > '0') {
                $options .= '(' . $products_options_array['price_prefix'] . $oCurrencies->display_price($products_options_array['options_values_price'], oos_get_tax_rate($product_info['products_tax_class_id'])) .')&nbsp';
              }
              $options .= '</td></tr>';

              // Move that ADOdb pointer!
              $products_options_result->MoveNext();
            }
            break;
        }
	
		###
		$out .= $indent . "\n";	
		$out .= '<item>' . "\n";	
		$out .= '<g:id>' . $products['products_id'] . $rows . $rows_b . $rows_c .'</g:id>' . "\n";
		$out .= '<g:title>' . $products_name . '</g:title>' . "\n";
		$out .= '<g:description>' . $products_description . '</g:description>' . "\n";
		$out .= '<g:link>' . $sUrl . '</g:link>' . "\n";
		$out .= '<g:image_link>' . $sImage . '</g:image_link>' . "\n";
		
	
		if ($products['products_subimage1'] != '') {
			$sImage1 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage1'];

			$out .= '<g:additional_image_link>' . $sImage1 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage2'] != '') {
			$sImage2 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage2'];

			$out .= '<g:additional_image_link>' . $sImage2 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage3'] != '') {
			$sImage3 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage3'];

			$out .= '<g:additional_image_link>' . $sImage3 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage4'] != '') {
			$sImage4 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage4'];

			$out .= '<g:additional_image_link>' . $sImage4 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage5'] != '') {
			$sImage5 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage5'];

			$out .= '<g:additional_image_link>' . $sImage5 . '</g:additional_image_link>' . "\n";
		}	
		if ($products['products_subimage6'] != '') {
			$sImage6 =  OOS_SHOP_IMAGES . 'product/large/' . $products['products_subimage6'];

			$out .= '<g:additional_image_link>' . $sImage6 . '</g:additional_image_link>' . "\n";
		}	
 
		if ($brand != '') {
			$out .= '<g:brand>'. $brand . '</g:brand>' . "\n";
			if ($products['products_ean'] != '') {
				$out .= '<g:gtin>'. $products['products_ean'] . '</g:gtin>' . "\n";
			}	
		}

		if ($products['products_status'] = 3) {
			$out .= '<g:availability>in_stock</g:availability>' . "\n";
		}		

		$out .= '<g:price>' . $info_product_price . '</g:price>' . "\n";
				


		$products_base_unit = $products['products_base_unit'];

		switch ($products_base_unit) {
		    case '1 Kilo':
			case '1 Kilogramm':
		    case '1 Kg':
		    case 'KG':
 		    case 'kg':	

			$products_product_quantity = $products['products_product_quantity'];
			$products_base_quantity = $products['products_base_quantity'];
			$division_result = $products_product_quantity / $products_base_quantity; // Das Ergebnis der Division
			$rounded_result = round($division_result, 4); // Das Ergebnis auf vier Dezimalstellen gerundet
			if ($rounded_result != 0) {
				$out .= '<g:unit_pricing_measure>' . $rounded_result . ' kg</g:unit_pricing_measure>' . "\n";
				$out .= '<g:unit_pricing_base_measure>1 kg</g:unit_pricing_base_measure>' . "\n";
			}
			break;

		    case '1 Liter':
			case 'Liter':

			$products_product_quantity = $products['products_product_quantity'];
			$products_base_quantity = $products['products_base_quantity'];
			$division_result = $products_product_quantity / $products_base_quantity; // Das Ergebnis der Division
			$rounded_result = round($division_result, 4); // Das Ergebnis auf vier Dezimalstellen gerundet
			if ($rounded_result != 0) {
				$out .= '<g:unit_pricing_measure>' . $rounded_result . ' l</g:unit_pricing_measure>' . "\n";
				$out .= '<g:unit_pricing_base_measure>1 l</g:unit_pricing_base_measure>' . "\n";
			}
			break;
		}

		$out .= '<g:shipping>' . "\n";
		$out .= '<g:country>DE</g:country>' . "\n";
		$out .= '<g:service>Versand</g:service>' . "\n";
		if ($products['products_price'] < 42.0131) {	
			$out .= '<g:price>4,95 EUR</g:price>' . "\n";
		} else {
			$out .= '<g:price>0 EUR</g:price>' . "\n";
	
		}
		$out .= '</g:shipping>' . "\n";




###	
	}
	
		$out .= '</item>' . "\n";
	$products_result->MoveNext();

	}

$out .= "\t<channel>\n";
$out .= '</rss>' . "\n";


// Output header for media RSS
header("content-type:text/xml;charset=UTF-8");
echo $out;