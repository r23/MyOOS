<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.231 2003/07/09 01:15:48 hpdl
         general.php,v 1.212 2003/02/17 07:55:54 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/**
 * Stop from parsing any further PHP code
 */
function oos_exit() {
	exit();
}


/**
 * Redirect to another page or site
 *
 * @param $sUrl
 * @return string
 */
function oos_redirect($sUrl) {

	if ( (strstr($sUrl, "\n") != FALSE) || (strstr($sUrl, "\r") != FALSE) ) { 
		$aContents = oos_get_content();
		oos_redirect(oos_href_link($aContents['home'], '', FALSE, TRUE));
	}

    // clean URL
    if (strpos($sUrl, '&amp;') !== FALSE) $sUrl = str_replace('&amp;', '&', $sUrl);
    if (strpos($sUrl, '&&') !== FALSE) $sUrl = str_replace('&&', '&', $sUrl);

    header('Location: ' . $sUrl);
    oos_exit();
}


 /**
  * Return a random row from a database query
  *
  * @param $query
  * @param $limit
  * @return string
  */
function oos_random_select($query, $limit = '') {

    // Get database information
    $dbconn =& oosDBGetConn();

    $random_product = '';
    if (oos_is_not_null($limit)) {
		if (USE_CACHE == 'true') {
			$random_result = $dbconn->CacheSelectLimit(15, $query, $limit);
		} else {
			$random_result = $dbconn->SelectLimit($query, $limit);
		}
	} else {
		if (USE_CACHE == 'true') {
			$random_result = $dbconn->CacheExecute(15, $query);
		} else {
			$random_result = $dbconn->Execute($query);
		}
	}
    $num_rows = $random_result->RecordCount();
	if ($num_rows > 0) {
		$random_row = oos_rand(0, ($num_rows - 1));
		$random_result->Move($random_row);
		$random_product = $random_result->fields;
    }

    return $random_product;
}

  function oos_prepare_input($sStr) {
    if (!is_array($sStr)) {
       if (get_magic_quotes_gpc()) {
         $sStr = stripslashes($sStr);
       }
       $sStr = strip_tags($sStr);
       $sStr = trim($sStr);
    }
    return $sStr;
  }



 /**
  * strip slashes
  *
  * stripslashes on multidimensional arrays.
  * Used in conjunction with pnVarCleanFromInput
  * @author    PostNuke Content Management System
  * @copyright Copyright (C) 2001 by the Post-Nuke Development Team.
  * @version Revision: 2.0  - changed by Author: r23  on Date: 2004/01/12 06:02:08
  * @access private
  * @param any variables or arrays to be stripslashed
  */
  function oos_stripslashes (&$value) {
    if (!is_array($value)) {
      $value = stripslashes($value);
    } else {
      array_walk($value,'oos_stripslashes');
    }
  }


 /**
  * ready operating system output
  * <br />
  * Gets a variable, cleaning it up such that any attempts
  * to access files outside of the scope of the PostNuke
  * system is not allowed
  * @author    PostNuke Content Management System
  * @copyright Copyright (C) 2001 by the Post-Nuke Development Team.
  * @version Revision: 2.0  - changed by Author: r23  on Date: 2004/01/12 06:02:08
  * @access private
  * @param var variable to prepare
  * @param ...
  * @returns string/array
  * in, otherwise an array of prepared variables
  */
  function oos_var_prep_for_os() {
    static $search = array('!\.\./!si', // .. (directory traversal)
                           '!^.*://!si', // .*:// (start of URL)
                           '!/!si',     // Forward slash (directory traversal)
                           '!\\\\!si'); // Backslash (directory traversal)

    static $replace = array('',
                            '',
                            '_',
                            '_');

    $resarray = array();
    foreach (func_get_args() as $ourvar) {
      // Parse out bad things
      $ourvar = preg_replace($search, $replace, $ourvar);

      // Prepare var
      if (!get_magic_quotes_runtime()) {
        $ourvar = addslashes($ourvar);
      }

      // Add to array
      array_push($resarray, $ourvar);
    }

    // Return vars
    if (func_num_args() == 1) {
      return $resarray[0];
    } else {
      return $resarray;
    }
  }


 /**
  * Return Product's Name
  *
  * @param $nProductID
  * @return string
  */
  function oos_get_products_name($nProductID) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? intval( $_SESSION['language_id'] ) : DEFAULT_LANGUAGE_ID;

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($nProductID) . "'
                AND products_languages_id = '" .  intval($nLanguageID) . "'";
    $products_name = $dbconn->GetOne($query);

    return $products_name;
  }
  
 /**
  * Create a Wishlist Code. length may be between 1 and 16 Characters
  *
  * @param $salt
  * @param $length
  * @return string
  */
  function oos_create_wishlist_code($salt="secret", $length = SECURITY_CODE_LENGTH) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ccid = md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    $ccid .= md5(uniqid("","salt"));
    srand((double)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
      $id1 = substr($ccid, $random_start,$length);
      $customerstable = $oostable['customers'];
      $sql = "SELECT customers_wishlist_link_id
              FROM $customerstable
              WHERE customers_wishlist_link_id = '" . oos_db_input($id1) . "'";
      $query = $dbconn->Execute($sql);
      if ($query->RecordCount() == 0) $good_result = 1;
    }
    return $id1;
  }

 /**
  * Return Wishlist Customer Name
  *
  * @param $wlid
  * @return string
  */
  function oos_get_wishlist_name($wlid) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $customerstable = $oostable['customers'];
    $query = "SELECT customers_firstname, customers_lastname
              FROM $customerstable
              WHERE customers_wishlist_link_id = '" . oos_db_input($wlid) . "'";
    $result = $dbconn->Execute($query);

    $sCustomersName = $result->fields['customers_firstname'] . ' ' . $result->fields['customers_lastname'];

    return $sCustomersName;
  }


 /**
  * Return Products Special Price
  *
  * @param $nProductID
  * @return string
  */
  function oos_get_products_special_price($nProductID) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_new_products_price
              FROM $specialstable
              WHERE products_id = '" . intval($nProductID) . "'
                AND status";
    $specials_new_products_price = $dbconn->GetOne($query);

    return $specials_new_products_price;
  }


 /**
  * Return Products Quantity
  *
  * @param $sProductsId
  * @return string
  */
// todo remove
  function oos_get_products_stock($sProductsId) {

    $nProductID = oos_get_product_id($sProductsId);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_quantity
              FROM $productstable
              WHERE products_id = '" . intval($nProductID) . "'";
    $products_quantity = $dbconn->GetOne($query);

    return $products_quantity;
  }


 /**
  * Return a product's minimum quantity
  *
  * @param $sProductsId
  * @return string
  */
  function oos_get_products_quantity_order_min($sProductsId) {

    $nProductID = oos_get_product_id($sProductsId);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_quantity_order_min
              FROM $productstable
              WHERE products_id = '" . intval($nProductID) . "'";
    $products_quantity_order_min = $dbconn->GetOne($query);

    return $products_quantity_order_min;
  }


 /**
  * Return a product's minimum unit order
  *
  * @param $sProductsId
  * @return string
  */
  function oos_get_products_quantity_order_units($sProductsId) {

    $nProductID = oos_get_product_id($sProductsId);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_quantity_order_units
              FROM $productstable
              WHERE products_id = '" . intval($nProductID) . "'";
    $products_quantity_order_units = $dbconn->GetOne($query);

    if ($products_quantity_order_units == 0) {
      $productstable = $oostable['products'];
      $dbconn->Execute("UPDATE $productstable
                    SET products_quantity_order_units = 1
                    WHERE products_id = '" . intval($nProductID) . "'");
      $products_quantity_order_units = 1;
    }

    return $products_quantity_order_units;


  }


 /**
  * Find quantity discount
  *
  * @param $product_id
  * @param $qty
  * @param $current_price
  * @return string
  */
  function oos_get_products_price_quantity_discount($product_id, $qty, $current_price = FALSE) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_price, products_discount1, products_discount2, products_discount3,
                     products_discount4, products_discount1_qty, products_discount2_qty, products_discount3_qty,
                     products_discount4_qty
              FROM $productstable
              WHERE products_id = '" . intval($product_id) . "'";
    $product_discounts = $dbconn->GetRow($query);

    switch ( true ) {
      case ( $qty==1 or ( $product_discounts['products_discount4_qty'] == 0 AND $product_discounts['products_discount3_qty'] == 0 AND $product_discounts['products_discount2_qty'] == 0 AND $product_discounts['products_discount1_qty'] == 0 ) ):
        if ($current_price) {
          $the_discount_price = $current_price;
        } else {
          $the_discount_price = $product_discounts['products_price'];
        }
        break;

      case ($qty >= $product_discounts['products_discount4_qty'] AND $product_discounts['products_discount4_qty'] !=0):
        $the_discount_price = $product_discounts['products_discount4'];
        break;

      case ($qty >= $product_discounts['products_discount3_qty'] AND $product_discounts['products_discount3_qty'] !=0 ):
        $the_discount_price = $product_discounts['products_discount3'];
        break;

      case ($qty >= $product_discounts['products_discount2_qty'] AND $product_discounts['products_discount2_qty'] !=0 ):
        $the_discount_price = $product_discounts['products_discount2'];
        break;

      case ($qty >= $product_discounts['products_discount1_qty'] AND $product_discounts['products_discount1_qty'] !=0 ):
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



 /**
  * Check if the required stock is available
  * If insufficent stock is available return an out of stock message
  *
  * @param $sProductsId
  * @param $nProductsQuantity
  * @return boolean
  */
  function oos_check_stock($sProductsId, $nProductsQuantity) {

    $stock_left = oos_get_products_stock($sProductsId) - $nProductsQuantity;

    $bOutOfStock = FALSE;
    if ($stock_left < 0) {
		$bOutOfStock = TRUE;
    }

    return $bOutOfStock;
  }


 /**
  * Return all GET variables, except those passed as a parameter
  *
  * @param  $aExclude
  * @return string
  */
  function oos_get_all_get_parameters($aExclude = '') {
	global $session;

    if (!is_array($aExclude)) $aExclude = array();
    $aParameters = array('p', 'error', 'rewrite', 'c', 'm', 'content', 'infex.php', 'history_back', 'formid', 'gclid', 'x', 'y');

	$urlValues = array();
	if (is_array($_GET)
		&& (count($_GET) > 0))
	{
		reset($_GET);
		foreach($_GET as $key => $value)
		{
			if(empty($value)
				|| $value === FALSE)
			{
				continue;
			}
			$urlValues[$key] = $value;
		}
	}
	
    $sUrl = '';
    if (is_array($urlValues) && (count($urlValues) > 0)) {
      reset($urlValues);
      foreach($urlValues as $sKey => $sValue) {	  
        if (!empty($sValue)) {
          if ( ($sKey != $session->getName()) && (!in_array($sKey, $aParameters)) && (!in_array($sKey, $aExclude)) ) {
            $sUrl .= $sKey . '=' . rawurlencode($sValue) . '&amp;';
          }
        }
      }
    }

    return $sUrl;
  }


 /**
  * Return all POST variables, except those passed as a parameter
  *
  * @param  $aExclude
  * @return string
  */
  function oos_get_all_post_parameters($aExclude = '') {
	global $session;
	
    if (!is_array($aExclude)) $aExclude = array();

    $aParameters = array('formid', 'content', 'x', 'y');
	
    $sUrl = '';
    if (is_array($_POST) && (count($_POST) > 0)) {
      reset($_POST);
	  foreach($_POST as $sKey => $sValue) {		  
		  
        if ( (!empty($sValue)) && (!is_array($sValue)) ) {
          if ( ($sKey != $session->getName())  && (!in_array($sKey, $aParameters))  && (!in_array($sKey, $aExclude)) ) {
            $sUrl .= $sKey . '=' . rawurlencode($sValue) . '&amp;';
          }
        }
      }
    }

    return $sUrl;
  }



 /**
  * Returns an array with countries
  *
  * @param $countries_id
  * @param $bWithIsoCodes
  * @return array
  */
  function oos_get_countries($countries_id = '', $bWithIsoCodes = FALSE) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aCountries = array();
    if (!empty($countries_id)) {
        if ($bWithIsoCodes == TRUE) {
            $countriestable = $oostable['billing_address_countries'];
            $query = "SELECT countries_name, countries_iso_code_2, countries_iso_code_3
                      FROM $countriestable
                      WHERE countries_id = '" . intval($countries_id) . "'
                      ORDER BY countries_name";
            $aCountries = $dbconn->GetRow($query);
        } else {
            $countriestable = $oostable['billing_address_countries'];
            $query = "SELECT countries_name
                      FROM $countriestable
                      WHERE countries_id = '" . intval($countries_id) . "'";
            $aCountries = $dbconn->GetRow($query);
        }
    } else {
        $countriestable = $oostable['billing_address_countries'];
        $query = "SELECT countries_id, countries_name
                  FROM $countriestable
                  ORDER BY countries_name";
        $aCountries = $dbconn->GetAll($query);
    }

    return $aCountries;
}


 /**
  * Returns the country name
  *
  * @param $country_id
  * @return string
  */
  function oos_get_country_name($country_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['billing_address_countries'];
    $query = "SELECT countries_name
              FROM $countriestable
              WHERE countries_id = '" . intval($country_id) . "'";
    $countries_name = $dbconn->GetOne($query);

    return $countries_name;
  }


/**
 * Returns the zone (State/Province) name
 *
 * @param $country_id
 * @param $zone_id
 * @param $default_zone
 * @return string
*/
function oos_get_zone_name($country_id, $zone_id, $default_zone) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonesstable = $oostable['zones'];
    $query = "SELECT zone_name
              FROM $zonesstable
              WHERE zone_country_id = '" . intval($country_id) . "' AND
                    zone_id = '" . intval($zone_id) . "'";
    $zone = $dbconn->Execute($query);
    if ($zone->RecordCount() > 0) {
		return $zone->fields['zone_name'];
    } else {
		return $default_zone;
    }
}


/**
 * Returns the tax rate for a zone / class
 *
 * @param $class_id
 * @param $country_id
 * @param $zone_id
 */
function oos_get_tax_rate($class_id, $country_id = -1, $zone_id = -1) {

    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
		return 0;
    }

    static $tax_rates = array();

	if ( ($country_id == -1) && ($zone_id == -1) ) {
		if (!isset($_SESSION['customer_id'])) {
			$country_id = STORE_COUNTRY;
			$zone_id = STORE_ZONE;
		} else {
			$country_id = $_SESSION['customer_country_id'];
			$zone_id = $_SESSION['customer_zone_id'];
		}
	}

	if (!isset($tax_rates[$class_id][$country_id][$zone_id]['rate'])) {
	    // Get database information
		$dbconn =& oosDBGetConn();
		$oostable =& oosDBGetTables();

		$tax_ratestable = $oostable['tax_rates'];
		$geo_zonestable = $oostable['geo_zones'];
		$zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
		$query = "SELECT SUM(tax_rate) AS tax_rate
              FROM  $tax_ratestable tr LEFT JOIN
                    $zones_to_geo_zonestable za
                  ON (tr.tax_zone_id = za.geo_zone_id) LEFT JOIN
                      $geo_zonestable tz
                  ON (tz.geo_zone_id = tr.tax_zone_id)
              WHERE (za.zone_country_id is null or za.zone_country_id = '0' OR
                     za.zone_country_id = '" . intval($country_id) . "') AND
                    (za.zone_id is null or za.zone_id = '0' or za.zone_id = '" . intval($zone_id) . "') AND
                     tr.tax_class_id = '" . intval($class_id) . "'
            GROUP BY tr.tax_priority";
		$tax_result = $dbconn->Execute($query);
		if (!$tax_result) {return 0;}

		if ($tax_result->RecordCount() > 0) {			
			$tax_multiplier = 1.0;
			while ($tax = $tax_result->fields) {
				$tax_multiplier *= 1.0 + ($tax['tax_rate'] / 100);			
				$tax_result->MoveNext();
			}

			$tax_rates[$class_id][$country_id][$zone_id]['rate'] = ($tax_multiplier - 1.0) * 100;
		} else {
			$tax_rates[$class_id][$country_id][$zone_id]['rate'] = 0;
		}
    }
	
    return $tax_rates[$class_id][$country_id][$zone_id]['rate'];
}



/**
 * Add tax to a products price
 *
 * @param $class_id
 * @param $country_id
 * @param $zone_id
 */
function oos_get_tax_description($class_id, $country_id, $zone_id) {
    global $aLang;

	static $tax_rates = array();

	if (!isset($tax_rates[$class_id][$country_id][$zone_id]['description'])) {
		// Get database information
		$dbconn =& oosDBGetConn();
		$oostable =& oosDBGetTables();

		$tax_ratestable = $oostable['tax_rates'];
		$geo_zonestable = $oostable['geo_zones'];
		$zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
		$query = "SELECT tax_description
				FROM $tax_ratestable tr LEFT JOIN
					$zones_to_geo_zonestable za
					ON (tr.tax_zone_id = za.geo_zone_id) LEFT JOIN
                   $geo_zonestable tz
					ON (tz.geo_zone_id = tr.tax_zone_id)
				WHERE  (za.zone_country_id is null or za.zone_country_id = '0' OR
						za.zone_country_id = '" . intval($country_id) . "') AND
					(za.zone_id is null or za.zone_id = '0' OR
						za.zone_id = '" . intval($zone_id) . "') AND
						tr.tax_class_id = '" . intval($class_id) . "'
			ORDER BY tr.tax_priority";
		$tax_result = $dbconn->Execute($query);

		if ($tax_result->RecordCount() > 0) {
			$tax_description = '';
			while ($tax = $tax_result->fields) {
				$tax_description .= $tax['tax_description'] . ' + ';

				// Move that ADOdb pointer!
				$tax_result->MoveNext();
			}

			$tax_description = substr($tax_description, 0, -3);

			$tax_rates[$class_id][$country_id][$zone_id]['description'] = $tax_description;
		} else {
			$tax_rates[$class_id][$country_id][$zone_id]['description'] = $aLang['text_unknown_tax_rate'];
		}
	}

    return $tax_rates[$class_id][$country_id][$zone_id]['description'];
	
}


/**
 * Add tax to a products price
 *
 * @param $price
 * @param $tax
 */
function oos_add_tax($price, $tax) {
	global $aUser;
	
  
    if( ($aUser['price_with_tax'] == 1) && ($tax > 0) ) {
		return $price + oos_calculate_tax($price, $tax);
    } else {
		return $price;
    }
}


/**
 * Calculates Tax rounding the result
 *
 * @param $price
 * @param $tax
 */
function oos_calculate_tax($price, $tax) {
	return $price * $tax / 100;
}

/**
 * rounding the price
 */
function oos_round($number, $precision) {
	if (strpos($number, '.') && (strlen(substr($number, strpos($number, '.')+1)) > $precision)) {
		$number = substr($number, 0, strpos($number, '.') + 1 + $precision + 1);

		if (substr($number, -1) >= 5) {
			if ($precision > 1) {
				$number = substr($number, 0, -1) + ('0.' . str_repeat(0, $precision-1) . '1');
			} elseif ($precision == 1) {
				$number = substr($number, 0, -1) + 0.1;
			} else {
				$number = substr($number, 0, -1) + 1;
			}
		} else {
			$number = substr($number, 0, -1);
		}
	}

    return $number;
}


function oos_get_categories($aCategories = '', $parent_id = '0', $indent = '') {

    $parent_id = oos_db_prepare_input($parent_id);

    if (!is_array($aCategories)) $aCategories = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? intval( $_SESSION['language_id'] ) : DEFAULT_LANGUAGE_ID;

    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, c.categories_status, cd.categories_name
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_status = '2'
                AND c.parent_id = '" . oos_db_input($parent_id) . "'
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
              ORDER BY sort_order, cd.categories_name";
    $result = $dbconn->Execute($query);

    while ($categories = $result->fields) {
		$aCategories[] = array('id' => $categories['categories_id'],
                             'text' => $indent . $categories['categories_name']);

		if ($categories['categories_id'] != $parent_id) {
			$aCategories = oos_get_categories($aCategories, $categories['categories_id'], $indent . '&nbsp;&nbsp;');
		}

		// Move that ADOdb pointer!
		$result->MoveNext();
    }

    return $aCategories;
  }


 /**
  * Recursively go through the categories and retreive all parent categories IDs
  *
  * @param $categories
  * @param $categories_id
  */
function oos_get_parent_categories(&$categories, $categories_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT parent_id
              FROM $categoriestable
              WHERE categories_id = '" . intval($categories_id) . "'";
    $result = $dbconn->Execute($query);

    while ($parent_categories = $result->fields) {
		if ($parent_categories['parent_id'] == 0) return TRUE;
		
		$categories[count($categories)] = $parent_categories['parent_id'];
		if ($parent_categories['parent_id'] != $categories_id) {
			oos_get_parent_categories($categories, $parent_categories['parent_id']);
		}

		// Move that ADOdb pointer!
		$result->MoveNext();
    }

}


 /**
  * Construct a category path to the product
  *
  * @param $products_id
  * @return string
  */
  function oos_get_product_path($products_id) {

    $sCategory = '';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_to_categoriestable = $oostable['products_to_categories'];
    $query = "SELECT COUNT(*) AS total
              FROM $products_to_categoriestable
              WHERE products_id = '" . intval($products_id) . "'";
    $cat_count_data = $dbconn->Execute($query);

    if ($cat_count_data->fields['total'] > 0) {
      $categories = array();
      $products_to_categoriestable = $oostable['products_to_categories'];
      $query = "SELECT categories_id
                FROM $products_to_categoriestable
                WHERE products_id = '" . intval($products_id) . "'";
      $cat_id_sql = $dbconn->Execute($query);
      $cat_id_data = $cat_id_sql->fields;

      oos_get_parent_categories($categories, $cat_id_data['categories_id']);

      $size = count($categories)-1;
      for ($i = $size; $i >= 0; $i--) {
        if ($sCategory != '') $sCategory .= '_';
        $sCategory .= $categories[$i];
      }
      if ($sCategory != '') $sCategory .= '_';
      $sCategory .= $cat_id_data['categories_id'];
    }

    return $sCategory;
  }





 /**
  * Return string (without trailing &  &amp;)
  *
  * @param $sParameters
  * @return string
  */
  function oos_remove_trailing($sParameters) {
    if (substr($sParameters, -5) == '&amp;') $sParameters = substr($sParameters, 0, -5);
    if (substr($sParameters, -1) == '&') $sParameters = substr($sParameters, 0, -1);

    return $sParameters;
  }


 /**
  * Return a product ID with attributes
  *
  * @param $prid
  * @param $parameters
  * @return string
  */
  function oos_get_uprid($prid, $parameters) {
    if (is_numeric($prid)) {
      $uprid = $prid;

      if (is_array($parameters) && (count($parameters) > 0)) {
        $attributes_check = TRUE;
        $attributes_ids = '';

		foreach($parameters as $option => $sValue) {	
          if (is_numeric($option) && is_numeric($sValue)) {
            $attributes_ids .= '{' . intval($option) . '}' . intval($sValue);
          } elseif (strstr($option, TEXT_PREFIX)) {
            $text_option = substr($option, strlen(TEXT_PREFIX));
            $sLen = strlen($sValue);
            $attributes_ids .= '{' . intval($text_option) . '}' . intval($sLen);
          }
        }

        if ($attributes_check == TRUE) {
          $uprid .= $attributes_ids;
        }
      }
    } else {
      $uprid = oos_get_product_id($prid);

      if (is_numeric($uprid)) {
        if (strpos($prid, '{') !== FALSE) {
          $attributes_check = TRUE;
          $attributes_ids = '';

          // strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
          $attributes = explode('{', substr($prid, strpos($prid, '{')+1));

          for ($i=0, $n=count($attributes); $i<$n; $i++) {
            $pair = explode('}', $attributes[$i]);

            if (is_numeric($pair[0]) && is_numeric($pair[1])) {
              $attributes_ids .= '{' . intval($pair[0]) . '}' . intval($pair[1]);
            } else {
              $attributes_check = FALSE;
              break;
            }
          }

          if ($attributes_check == TRUE) {
            $uprid .= $attributes_ids;
          }
        }
      } else {
        return FALSE;
      }
    }

    return $uprid;
  }


 /**
  * Check if product has attributes
  *
  * @param $products_id
  * @return boolean
  */
  function oos_has_product_attributes($products_id) {

    $products_id = oos_get_product_id($products_id);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_attributestable = $oostable['products_attributes'];
    $query = "SELECT COUNT(*) AS total
              FROM $products_attributestable
              WHERE products_id = '" . intval($products_id) . "'";
    $attributes = $dbconn->Execute($query);
    if ($attributes->fields['total'] > 0) {
      return TRUE;
    } else {
      return FALSE;
    }
  }


  function oos_count_modules($modules = '') {

    $nCount = 0;

    if (empty($modules)) return $nCount;

    $aModules = explode(';', $modules);

    for ($i=0, $n=count($aModules); $i<$n; $i++) {
      $class = substr($aModules[$i], 0, strrpos($aModules[$i], '.'));

      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $nCount++;
        }
      }
    }

    return $nCount;
  }

  function oos_count_payment_modules() {

    return oos_count_modules($_SESSION['user']->group['payment']);
  }

  function oos_count_shipping_modules() {
    return oos_count_modules(MODULE_SHIPPING_INSTALLED);
  }



/**
 * Parse and output a user submited value
 *
 * @param string $sStr The string to parse and output
 * @param array $aTranslate An array containing the characters to parse
 * @access public
 */
function oos_output_string($sStr, $aTranslate = null) {

    if (empty($aTranslate)) {
        $aTranslate = array('"' => '&quot;');
    }

    return strtr(trim($sStr), $aTranslate);
}


 /**
  * Strip forbidden tags
  *
  * @param string
  * @return string
  */
  function oos_remove_tags($source) {

    $allowedTags = '<h1><strong><i><a><ul><li><pre><hr><br><blockquote><p>';
    $source = strip_tags($source, $allowedTags);

    return $source;
  }


 /**
  * Replace international chars
  *
  * @param string
  * @return string
  */
  function oos_replace_chars ($sStr) {
    return oos_make_filename($sStr);
  }


 /**
  * Checks to see if the currency code exists as a currency
  */
  function oos_currency_exits($code) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $currenciestable = $oostable['currencies'];
    $query = "SELECT currencies_id
              FROM $currenciestable
              WHERE code = '" . oos_db_input($code) . "'";
    $result = $dbconn->Execute($query);

    if ($result->RecordCount() > 0) {
      return $code;
    } else {
      return FALSE;
    }
  }



 /**
  * Return secure string
  *
  * @param $sStr
  * @return string
  */
  function oos_string_to_int($sStr) {
    return intval($sStr);
  }


 /**
  * Return $aContents
  */
  function oos_get_content() {
    GLOBAL $aContents;

    return $aContents;
  }



 /**
  * Parse and secure the cPath parameter values
  *
  * @param $sCategory
  * @return array
  */
  function oos_parse_category_path($sCategory) {
    // make sure the category IDs are integers
    $aCategoryPath = array_map('oos_string_to_int', explode('_', $sCategory));

    // make sure no duplicate category IDs exist which could lock the server in a loop
    $aTmp = array();
    for ($i=0, $n=count($aCategoryPath); $i<$n; $i++) {
      if (!in_array($aCategoryPath[$i], $aTmp)) {
        $aTmp[] = $aCategoryPath[$i];
      }
    }

    return $aTmp;
  }



 /**
  * Return File Extension
  *
  * @param $filename
  * @return string
  */
  function oos_get_extension($filename) {

    $filename  = strtolower($filename);
    $extension = explode("[/\\.]", $filename);
    $n = count($extension)-1;
    $extension = $extension[$n];

    return $extension;
  }


/**
 * Strip non-alpha & non-numeric except ._-:
 *
 * @param $sStr
 * @return string
 */
function oos_strip_all ($sStr) {
	$sStr = trim($sStr);
    $sStr = strtolower($sStr);

    return preg_match("/[^[:alnum:]._-]/", "", $sStr);
}


/**
 * Mail function (uses phpMailer)
 */
function oos_mail($to_name, $to_email_address, $subject, $email_text, $email_html, $from_email_name, $from_email_address, $attachments = array() ) {

	global $oEvent, $oEmail;

	if ( !is_object( $oEvent ) || (!$oEvent->installed_plugin('mail')) ) {
		return FALSE;
	}

    if (preg_match('~[\r\n]~', $to_name)) return FALSE;
    if (preg_match('~[\r\n]~', $to_email_address)) return FALSE;
    if (preg_match('~[\r\n]~', $subject)) return FALSE;
    if (preg_match('~[\r\n]~', $from_email_name)) return FALSE;
    if (preg_match('~[\r\n]~', $from_email_address)) return FALSE;

	if ( !is_array($attachments) ) {
		$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );
	}

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');

	// (Re)create it, if it's gone missing
	if ( !is_object( $oEmail ) || !is_a( $oEmail, 'PHPMailer' ) ) {
		require_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/class.phpmailer.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/class.smtp.php';
		// Instantiate a new mail object
		$oEmail = new PHPMailer( true );
	}

	// Empty out the values that may be set
	$oEmail->ClearAllRecipients();
	$oEmail->ClearAttachments();
	$oEmail->ClearCustomHeaders();
	$oEmail->ClearReplyTos();

    $oEmail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';
    $oEmail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );
    $oEmail->CharSet = CHARSET;

    $oEmail->IsMail();

    $oEmail->From = $from_email_address ? $from_email_address : STORE_OWNER_EMAIL_ADDRESS;
    $oEmail->FromName = $from_email_name ? $from_email_name : STORE_OWNER;
    $oEmail->Mailer = EMAIL_TRANSPORT;

    // Add smtp values if needed
    if ( EMAIL_TRANSPORT == 'smtp' ) {
      $oEmail->IsSMTP(); // set mailer to use SMTP
      $oEmail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
      $oEmail->Username = OOS_SMTPUSER; // SMTP username
      $oEmail->Password = OOS_SMTPPASS; // SMTP password
      $oEmail->Host     = OOS_SMTPHOST; // specify main and backup server
    } elseif ( EMAIL_TRANSPORT == 'sendmail' ) {
        if (!oos_empty(OOS_SENDMAIL)) {
          $oEmail->Sendmail = OOS_SENDMAIL;
          $oEmail->IsSendmail();
        }
    }


    $oEmail->AddAddress($to_email_address, $to_name);
    $oEmail->Subject = $subject;


    // Build the text version
    if (EMAIL_USE_HTML == 'true') {
		$oEmail->IsHTML(true);
		$oEmail->Body = $email_html;
		$oEmail->AltBody = $email_text;
    } else {
		$oEmail->IsHTML(false);
		$oEmail->Body = $email_text;
    }


	if ( !empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			try {
				$oEmail->AddAttachment($attachment);
			} catch ( phpmailerException $e ) {
				continue;
			}
		}
	}

	// Send!
	try {
		return $oEmail->Send();
	} catch ( phpmailerException $e ) {
		return FALSE;
	}
}

function oos_newsletter_subscribe_mail ($email_address) {
    global $aLang, $sTheme;
	
	if (empty($email_address)) {
      return FALSE;
    }
	
	$sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;
	
	if (oos_validate_is_email($email_address)) {	
	
		$aContents = oos_get_content();

		// Get database information
		$dbconn =& oosDBGetConn();
		$oostable =& oosDBGetTables();

		$newsletter_recipients = $oostable['newsletter_recipients'];
		$dbconn->Execute("DELETE FROM $newsletter_recipients WHERE customers_email_address = '" . oos_db_input($email_address) . "'"); 

		$sRandom = oos_create_random_value(25);
		$sBefor = oos_create_random_value(4);
	
		$dbconn->Execute("INSERT INTO $newsletter_recipients 
                            (customers_email_address,
							mail_key,
							key_sent,
							status) VALUES ('" . oos_db_input($email_address) . "',
											'" . oos_db_input($sRandom) . "',
											now(),
											'0')");

		$nInsert_ID = $dbconn->Insert_ID();	  
		$newsletter_recipients = $oostable['newsletter_recipients_history'];
		$dbconn->Execute("INSERT INTO $newsletter_recipients 
                                    (recipients_id,
                                    date_added) VALUES ('" . intval($nInsert_ID) . "',
                                                        now())");
											   
		$sStr =  $sBefor . $nInsert_ID . 'f00d';
		$sSha1 = sha1($sStr);

		$newsletter_recipients = $oostable['newsletter_recipients'];
		$dbconn->Execute("UPDATE $newsletter_recipients
                          SET mail_sha1 = '" . oos_db_input($sSha1) . "'
                          WHERE recipients_id = '" . intval($nInsert_ID) . "'");			
		//smarty
		require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
		$smarty = new myOOS_Smarty();						

		// dont allow cache
		$smarty->caching = FALSE;

		$smarty->assign(
				array(
					'shop_name'		=> STORE_NAME,
					'shop_url'		=> OOS_HTTPS_SERVER . OOS_SHOP,
					'shop_logo'		=> STORE_LOGO,
					'services_url'	=> COMMUNITY,
					'blog_url'		=> BLOG_URL,
					'imprint_url'	=> oos_href_link($aContents['information'], 'information_id=1', FALSE, TRUE),
					'subscribe'		=> oos_href_link($aContents['newsletter'], 'action=lists&subscribe=confirm&u=' .  $sSha1 . '&id=' . $sStr . '&e=' . $sRandom, FALSE, TRUE)
				)
		);

		// create mails	
		$email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.html');
		$email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.tpl');
		
		oos_mail('', $email_address, $aLang['newsletter_email_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
	}
}
