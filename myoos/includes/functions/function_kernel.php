<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/**
 * Stop from parsing any further PHP code
 */
function oos_exit()
{
    exit();
}


/**
 * Redirect to another page or site
 *
 * @param  $sUrl
 * @return string
 */
function oos_redirect($sUrl)
{
    if ((strstr($sUrl, "\n") != false) || (strstr($sUrl, "\r") != false)) {
        $aContents = oos_get_content();
        oos_redirect(oos_href_link($aContents['home'], '', false, true));
    }

    // clean URL
    if (strpos($sUrl, '&amp;') !== false) {
        $sUrl = str_replace('&amp;', '&', $sUrl);
    }
    if (strpos($sUrl, '&&') !== false) {
        $sUrl = str_replace('&&', '&', $sUrl);
    }

    header('Location: ' . $sUrl);
    oos_exit();
}


 /**
  * Return a random row from a database query
  *
  * @param  $query
  * @param  $limit
  * @return string
  */
function oos_random_select($query, $limit = '')
{

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


function oos_prepare_input($sStr)
{
    if (is_string($sStr)) {
        return trim(oos_sanitize_string(stripslashes($sStr)));
    } elseif (is_array($sStr)) {
        foreach ($sStr as $key => $value) {
            $sStr[$key] = oos_prepare_input($value);
        }
        return $sStr;
    } else {
        return $sStr;
    }
}


function oos_sanitize_string($sStr)
{
    $aPatterns = ['/ +/','/[<>]/'];
    $aReplace = [' ', '_'];
    return preg_replace($aPatterns, $aReplace, trim($sStr));
}


 /**
  * strip slashes
  *
  * stripslashes on multidimensional arrays.
  * Used in conjunction with pnVarCleanFromInput
  *
  * @author    PostNuke Content Management System
  * @copyright Copyright (C) 2001 by the Post-Nuke Development Team.
  * @version   Revision: 2.0  - changed by Author: r23  on Date: 2004/01/12 06:02:08
  * @access    private
  * @param     any variables or arrays to be stripslashed
  */
function oos_stripslashes(&$value)
{
    if (!is_array($value)) {
        $value = stripslashes($value);
    } else {
        array_walk($value, 'oos_stripslashes');
    }
}


 /**
  * ready operating system output
  * <br />
  * Gets a variable, cleaning it up such that any attempts
  * to access files outside of the scope of the PostNuke
  * system is not allowed
  *
  * @author    PostNuke Content Management System
  * @copyright Copyright (C) 2001 by the Post-Nuke Development Team.
  * @version   Revision: 2.0  - changed by Author: r23  on Date: 2004/01/12 06:02:08
  * @access    private
  * @param     var variable to prepare
  * @param     ...
  * @returns   string/array
  * in, otherwise an array of prepared variables
  */
function oos_var_prep_for_os()
{
    static $search = ['!\.\./!si', // .. (directory traversal)
                           '!^.*://!si', // .*:// (start of URL)
                           '!/!si',     // Forward slash (directory traversal)
                           '!\\\\!si']; // Backslash (directory traversal)

    static $replace = ['',
                       '',
                       '_',
                       '_'];

    $resarray = [];
    foreach (func_get_args() as $ourvar) {
        // Parse out bad things
        $ourvar = preg_replace($search, $replace, $ourvar);

        // Prepare var
        $ourvar = addslashes($ourvar);

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
  * @param  $nProductID
  * @return string
  */
function oos_get_products_name($nProductID)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($nProductID) . "'
                AND products_languages_id = '" .  intval($nLanguageID) . "'";
    $products_name = $dbconn->GetOne($query);

    return $products_name;
}

/**
 * Return Product's StatusName
 *
 * @param  $nProductID
 * @return string
 */
function oos_get_products_status($nProductID)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_status
               FROM $productstable 
              WHERE products_id = '" . intval($nProductID) . "'";
    $products_status = $dbconn->GetOne($query);

    return $products_status;
}


 /**
  * Create a Wishlist Code. length may be between 1 and 16 Characters
  *
  * @param  $salt
  * @param  $length
  * @return string
  */
function oos_create_wishlist_code($salt = "secret", $length = SECURITY_CODE_LENGTH)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ccid = md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    $ccid .= md5(uniqid("", "salt"));
    srand((float)microtime()*1000000); // seed the random number generator
    $random_start = @rand(0, (128-$length));
    $good_result = 0;
    while ($good_result == 0) {
        $id1 = substr($ccid, $random_start, $length);
        $customerstable = $oostable['customers'];
        $sql = "SELECT customers_wishlist_link_id
              FROM $customerstable
              WHERE customers_wishlist_link_id = '" . oos_db_input($id1) . "'";
        $query = $dbconn->Execute($sql);
        if ($query->RecordCount() == 0) {
            $good_result = 1;
        }
    }
    return $id1;
}

 /**
  * Return Wishlist Customer Name
  *
  * @param  $wlid
  * @return string
  */
function oos_get_wishlist_name($wlid)
{

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
  * @param  $nProductID
  * @return string
  */
function oos_get_products_special_price($nProductID)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_new_products_price
              FROM $specialstable
              WHERE products_id = '" . intval($nProductID) . "'
                AND status = 1";
    $specials_new_products_price = $dbconn->GetOne($query);

    return $specials_new_products_price;
}


/**
 * Return Products Special Price
 *
 * @param  $nProductID
 * @return array
 */
function oos_get_products_special($nProductID)
{
    $aSpecial = [];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $specialstable = $oostable['specials'];
    $query = "SELECT specials_new_products_price, specials_cross_out_price, expires_date
              FROM $specialstable
              WHERE products_id = '" . intval($nProductID) . "'
                AND status = 1";
    $aSpecial = $dbconn->GetRow($query);

    return $aSpecial;
}


 /**
  * Return Products Quantity
  *
  * @param  $sProductsId
  * @return string
  */
// todo remove
function oos_get_products_stock($sProductsId)
{
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
  * @param  $sProductsId
  * @return string
  */
function oos_get_products_quantity_order_min($sProductsId)
{
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
  * @param  $sProductsId
  * @return string
  */
function oos_get_products_quantity_order_units($sProductsId)
{
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
        $dbconn->Execute(
            "UPDATE $productstable
                    SET products_quantity_order_units = 1
                    WHERE products_id = '" . intval($nProductID) . "'"
        );
        $products_quantity_order_units = 1;
    }

    return $products_quantity_order_units;
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
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_price, products_discount1, products_discount2, products_discount3,
                     products_discount4, products_discount1_qty, products_discount2_qty, products_discount3_qty,
                     products_discount4_qty
              FROM $productstable
              WHERE products_id = '" . intval($product_id) . "'";
    $product_discounts = $dbconn->GetRow($query);

    switch (true) {
    case ($qty==1 or ($product_discounts['products_discount4_qty'] == 0 and $product_discounts['products_discount3_qty'] == 0 and $product_discounts['products_discount2_qty'] == 0 and $product_discounts['products_discount1_qty'] == 0)):
        if ($current_price) {
            $the_discount_price = $current_price;
        } else {
            $the_discount_price = $product_discounts['products_price'];
        }
        break;

    case ($qty >= $product_discounts['products_discount4_qty'] and $product_discounts['products_discount4_qty'] !=0):
        $the_discount_price = $product_discounts['products_discount4'];
        break;

    case ($qty >= $product_discounts['products_discount3_qty'] and $product_discounts['products_discount3_qty'] !=0):
        $the_discount_price = $product_discounts['products_discount3'];
        break;

    case ($qty >= $product_discounts['products_discount2_qty'] and $product_discounts['products_discount2_qty'] !=0):
        $the_discount_price = $product_discounts['products_discount2'];
        break;

    case ($qty >= $product_discounts['products_discount1_qty'] and $product_discounts['products_discount1_qty'] !=0):
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
  * @param  $sProductsId
  * @param  $nProductsQuantity
  * @return boolean
  */
function oos_check_stock($sProductsId, $nProductsQuantity)
{
    $stock_left = oos_get_products_stock($sProductsId) - $nProductsQuantity;

    $bOutOfStock = false;
    if ($stock_left < 0) {
        $bOutOfStock = true;
    }

    return $bOutOfStock;
}


 /**
  * Return all GET variables, except those passed as a parameter
  *
  * @param  $aExclude
  * @return string
  */
function oos_get_all_get_parameters($aExclude = '')
{
    global $session;

    if (!is_array($aExclude)) {
        $aExclude = [];
    }
    $aParameters = ['p', 'error', 'rewrite', 'c', 'm', 'content', 'infex.php', 'history_back', 'formid', 'gclid', 'x', 'y'];

    $urlValues = [];
    if (is_array($_GET)
        && (count($_GET) > 0)
    ) {
        reset($_GET);
        foreach ($_GET as $key => $value) {
            if (empty($value)
                || $value === false
            ) {
                continue;
            }
            $urlValues[$key] = $value;
        }
    }

    $sUrl = '';
    if (is_array($urlValues) && (count($urlValues) > 0)) {
        reset($urlValues);
        foreach ($urlValues as $sKey => $sValue) {
            if (!empty($sValue)) {
                if (($sKey != $session->getName()) && (!in_array($sKey, $aParameters)) && (!in_array($sKey, $aExclude))) {
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
function oos_get_all_post_parameters($aExclude = '')
{
    global $session;

    if (!is_array($aExclude)) {
        $aExclude = [];
    }

    $aParameters = ['formid', 'content', 'x', 'y'];

    $sUrl = '';
    if (is_array($_POST) && (count($_POST) > 0)) {
        reset($_POST);
        foreach ($_POST as $sKey => $sValue) {
            if ((!empty($sValue)) && (!is_array($sValue))) {
                if (($sKey != $session->getName())  && (!in_array($sKey, $aParameters))  && (!in_array($sKey, $aExclude))) {
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
  * @param  $countries_id
  * @param  $bWithIsoCodes
  * @return array
  */
function oos_get_countries($countries_id = '', $bWithIsoCodes = false)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aCountries = [];
    if (!empty($countries_id)) {
        if ($bWithIsoCodes == true) {
            $countriestable = $oostable['countries'];
            $query = "SELECT countries_name, countries_iso_code_2, countries_iso_code_3
                      FROM $countriestable
                      WHERE countries_id = '" . intval($countries_id) . "'
                      ORDER BY countries_name";
            $aCountries = $dbconn->GetRow($query);
        } else {
            $countriestable = $oostable['countries'];
            $query = "SELECT countries_name
                      FROM $countriestable
                      WHERE countries_id = '" . intval($countries_id) . "'";
            $aCountries = $dbconn->GetRow($query);
        }
    } else {
        $countriestable = $oostable['countries'];
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
  * @param  $country_id
  * @return string
  */
function oos_get_country_name($country_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['countries'];
    $query = "SELECT countries_name
              FROM $countriestable
              WHERE countries_id = '" . intval($country_id) . "'";
    $countries_name = $dbconn->GetOne($query);

    return $countries_name;
}


/**
 * Returns the zone (State/Province) name
 *
 * @param  $country_id
 * @param  $zone_id
 * @param  $default_zone
 * @return string
 */
function oos_get_zone_name($country_id, $zone_id, $default_zone)
{

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
function oos_get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
{
    if (isset($_SESSION['customers_vat_id_status']) && ($_SESSION['customers_vat_id_status'] == 1)) {
        return 0;
    }

    static $tax_rates = [];

    if (($country_id == -1) && ($zone_id == -1)) {
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
        if (!$tax_result) {
            return 0;
        }

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
function oos_get_tax_description($class_id, $country_id, $zone_id)
{
    global $aLang;

    static $tax_rates = [];

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
function oos_add_tax($price, $tax)
{
    global $aUser;

    if (($aUser['price_with_tax'] == '1') && ($tax > 0)) {
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
function oos_calculate_tax($price, $tax)
{
    return $price * $tax / 100;
}

/**
 * rounding the price
 */
function oos_round($number, $precision)
{
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



function oos_get_categories($aCategories = '', $parent_id = 0, $indent = '')
{
    if (!is_array($aCategories)) {
        $aCategories = [];
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, c.categories_status, cd.categories_name
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE c.categories_status = '2'
                AND c.parent_id = '" . intval($parent_id) . "'
                AND c.categories_id = cd.categories_id
                AND cd.categories_languages_id = '" .  intval($nLanguageID) . "'
              ORDER BY sort_order, cd.categories_name";
    $result = $dbconn->Execute($query);

    while ($categories = $result->fields) {
        $aCategories[] = ['id' => $categories['categories_id'],
                             'text' => $indent . $categories['categories_name']];

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
function oos_get_parent_categories(&$categories, $categories_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $categoriestable = $oostable['categories'];
    $query = "SELECT parent_id
              FROM $categoriestable
              WHERE categories_id = '" . intval($categories_id) . "'";
    $result = $dbconn->Execute($query);

    while ($parent_categories = $result->fields) {
        if ($parent_categories['parent_id'] == 0) {
            return true;
        }

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
  * @param  $products_id
  * @return string
  */
function oos_get_product_path($products_id)
{
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
        $categories = [];
        $products_to_categoriestable = $oostable['products_to_categories'];
        $query = "SELECT categories_id
                FROM $products_to_categoriestable
                WHERE products_id = '" . intval($products_id) . "'";
        $cat_id_sql = $dbconn->Execute($query);
        $cat_id_data = $cat_id_sql->fields;

        oos_get_parent_categories($categories, $cat_id_data['categories_id']);

        $size = count($categories)-1;
        for ($i = $size; $i >= 0; $i--) {
            if ($sCategory != '') {
                $sCategory .= '_';
            }
            $sCategory .= $categories[$i];
        }
        if ($sCategory != '') {
            $sCategory .= '_';
        }
        $sCategory .= $cat_id_data['categories_id'];
    }

    return $sCategory;
}


 /**
  * Construct a category path to the product
  *
  * @param  $products_id
  * @return array
  */
function oos_get_category_path($nProductsId)
{
    $aCategory = [];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

    $products_to_categoriestable = $oostable['products_to_categories'];
    $query = "SELECT categories_id
              FROM $products_to_categoriestable
              WHERE products_id = '" . intval($nProductsId) . "'";
    $cat_id_sql = $dbconn->SelectLimit($query, 1);
    $cat_id_data = $cat_id_sql->fields;


    $categories_arr = [];
    oos_get_parent_categories($categories_arr, $cat_id_data['categories_id']);

    $sCategory = '';
    $size = count($categories_arr)-1;
    for ($i = $size; $i >= 0; $i--) {
        if ($sCategory != '') {
            $sCategory .= '_';
        }
        $sCategory .= $categories_arr[$i];
    }
    if ($sCategory != '') {
        $sCategory .= '_';
    }
    $sCategory .= $cat_id_data['categories_id'];

    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT categories_name
              FROM $categories_descriptiontable
              WHERE categories_id = '" .  intval($cat_id_data['categories_id']) . "'
                AND categories_languages_id = '" .  intval($nLanguageID) . "'";
    $result = $dbconn->SelectLimit($query, 1);
    $result_data = $result->fields;

    $aCategory = ['path' => $sCategory,
                  'name' => $result_data['categories_name']];

    return $aCategory;
}




 /**
  * Return string (without trailing &  &amp;)
  *
  * @param  $sParameters
  * @return string
  */
function oos_remove_trailing($sParameters)
{
    if (substr($sParameters, -5) == '&amp;') {
        $sParameters = substr($sParameters, 0, -5);
    }
    if (substr($sParameters, -1) == '&') {
        $sParameters = substr($sParameters, 0, -1);
    }

    return $sParameters;
}



 /**
  * Return a product ID with attributes
  *
  * @param  $prid
  * @param  $parameters
  * @return string
  */
function oos_get_uprid($prid, $parameters)
{
    if (is_numeric($prid)) {
        $uprid = $prid;

        if (is_array($parameters) && (count($parameters) > 0)) {
            $attributes_check = true;
            $attributes_ids = '';

            reset($parameters);
            foreach ($parameters as $option => $sValue) {
                if (is_numeric($option) && is_numeric($sValue)) {
                    $attributes_ids .= '{' . intval($option) . '}' . intval($sValue);
                } elseif (strstr($option, TEXT_PREFIX)) {
                    $text_option = substr($option, strlen(TEXT_PREFIX));
                    $sLen = strlen($sValue);
                    $attributes_ids .= '{' . intval($text_option) . '}' . intval($sLen);
                } else {
                    $attributes_check = false;
                    break;
                }
            }

            if ($attributes_check == true) {
                $uprid .= $attributes_ids;
            }
        }
    } else {
        $uprid = oos_get_product_id($prid);

        if (is_numeric($uprid)) {
            if (strpos($prid, '{') !== false) {
                $attributes_check = true;
                $attributes_ids = '';

                // strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
                $attributes = explode('{', substr($prid, strpos($prid, '{')+1));

                for ($i=0, $n=count($attributes); $i<$n; $i++) {
                    $pair = explode('}', $attributes[$i]);

                    if (is_numeric($pair[0]) && is_numeric($pair[1])) {
                        $attributes_ids .= '{' . intval($pair[0]) . '}' . intval($pair[1]);
                    } else {
                        $attributes_check = false;
                        break;
                    }
                }

                if ($attributes_check == true) {
                    $uprid .= $attributes_ids;
                }
            }
        } else {
            return false;
        }
    }

    return $uprid;
}


 /**
  * Return attributes ID
  *
  * @param  $sProductsId
  * @return array
  */
function oos_get_attributes($sProductsId)
{
    $real_ids = [];

    $uprid = oos_get_product_id($sProductsId);

    if (is_numeric($uprid)) {
        if (strpos($sProductsId, '{') !== false) {
            $attributes_check = true;
            $attributes_ids = [];

            // strpos()+1 to remove up to and including the first { which would create an empty array element in explode()
            $attributes = explode('{', substr($sProductsId, strpos($sProductsId, '{')+1));

            for ($i=0, $n=count($attributes); $i<$n; $i++) {
                $pair = explode('}', $attributes[$i]);

                if (is_numeric($pair[0]) && is_numeric($pair[1])) {
                    $attributes_ids += [intval($pair[0]) => intval($pair[1])];
                } else {
                    $attributes_check = false;
                    break;
                }
            }

            if ($attributes_check == true) {
                $real_ids = $attributes_ids;
            }
        }
    } else {
        return false;
    }

    return $real_ids;
}



 /**
  * Check if product has attributes
  *
  * @param  $sProductsId
  * @return boolean
  */
function oos_has_product_attributes($sProductsId)
{
    $nProductID = oos_get_product_id($sProductsId);
	$return_value = false;
	
    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_attributestable = $oostable['products_attributes'];
    $query = "SELECT COUNT(*) AS total
              FROM $products_attributestable
              WHERE products_id = '" . intval($nProductID) . "'";
    $attributes = $dbconn->Execute($query);
    if ($attributes->fields['total'] > 0) {
		if (DOWNLOAD_ENABLED == 'true') {
			$products_attributestable = $oostable['products_attributes'];
			$products_attributes_downloadtable = $oostable['products_attributes_download'];			
			$download_sql = "SELECT COUNT(*) AS total
                             FROM $products_attributestable pa,
                                  $products_attributes_downloadtable pad 
						     WHERE pa.products_id = '" . intval($nProductID) . "'
						       AND pa.options_values_id = 0
						       AND pa.products_attributes_id = pad.products_attributes_id";
		    $downloads = $dbconn->Execute($download_sql);
			$nDownloads	= $downloads->fields['total'];
			$nAattributes = $attributes->fields['total'];
			if (($nAattributes - $nDownloads) > 0) {
				$return_value = true;
			} 
		} else {
			$return_value = true;
		}
    }
        
	return $return_value;

}

 /**
  * Check if product has information obligation
  *
  * @param  $sProductsId
  * @return boolean
  */
function oos_has_product_information_obligation($sProductsId)
{
    if (TAKE_BACK_OBLIGATION != 'true') {
        return false;
    }

    $nProductID = oos_get_product_id($sProductsId);

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_old_electrical_equipment
              FROM $productstable
              WHERE products_id = '" . intval($nProductID) . "'";
    $result = $dbconn->Execute($query);
    if ($result->fields['products_old_electrical_equipment'] == 0) {
        return false;
    } else {
        return true;
    }
}

 /**
  * Check if the product is B-ware
  *
  * @param  $sProductsId
  * @return boolean
  */
function oos_is_the_product_b_ware($sProductsId)
{
    if (OFFER_B_WARE != 'true') {
        return false;
    }

    if (isset($sProductsId) && is_numeric($sProductsId)) {
        $nProductID = intval($sProductsId);
    } else {
        $nProductID = oos_get_product_id($sProductsId);
    }
    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $query = "SELECT products_used_goods
              FROM $productstable
              WHERE products_id = '" . intval($nProductID) . "'";
    $result = $dbconn->Execute($query);
    if ($result->fields['products_used_goods'] == 0) {
        return false;
    } else {
        return true;
    }
}


 /**
  * Check if product has attributes
  *
  * @param  $nProductsId
  * @return boolean
  */
function get_options_values_price($nProductsId)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $ADODB_GETONE_EOF = "-1";

    $products_optionstable = $oostable['products_options'];
    $products_attributestable = $oostable['products_attributes'];
    $options_name_sql = "SELECT MIN(patrib.options_values_price)
                           FROM $products_optionstable popt,
                                $products_attributestable patrib
                           WHERE patrib.products_id='" . intval($nProductsId) . "'
                             AND patrib.options_id = popt.products_options_id
							 AND popt.products_options_type = 3";
    return $dbconn->GetOne($options_name_sql);
}


function oos_count_modules($modules = '')
{
    $nCount = 0;

    if (empty($modules)) {
        return $nCount;
    }

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


function oos_count_payment_modules()
{
    return oos_count_modules($_SESSION['user']->group['payment']);
}


function oos_count_shipping_modules()
{
    return oos_count_modules(MODULE_SHIPPING_INSTALLED);
}


function rmdir_recursive($dir)
{
    foreach (scandir($dir) as $file) {
        if ('.' === $file || '..' === $file) {
            continue;
        }
        if (is_dir("$dir/$file")) {
            rmdir_recursive("$dir/$file");
        } else {
            unlink("$dir/$file");
        }
    }

    rmdir($dir);
}



/**
 * Parse and output a user submited value
 *
 * @param  string $sStr       The string to parse and output
 * @param  array  $aTranslate An array containing the characters to parse
 * @access public
 */
function oos_output_string($sStr, $aTranslate = null)
{
    if (empty($aTranslate)) {
        $aTranslate = ['"' => '&quot;'];
    }

    return strtr(trim($sStr), $aTranslate);
}


 /**
  * Strip forbidden tags
  *
  * @param  string
  * @return string
  */
function oos_remove_tags($sStr)
{
    $allowedTags = '<h1><strong><i><a><ul><li><pre><hr><br><blockquote><p>';
    $source = strip_tags($sStr, $allowedTags);

    return $source;
}


 /**
  * Replace international chars
  *
  * @param  string
  * @return string
  */
function oos_replace_chars($sStr)
{
    return oos_make_filename($sStr);
}


 /**
  * Checks to see if the currency code exists as a currency
  */
function oos_currency_exits($code)
{

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
        return false;
    }
}



 /**
  * Return secure string
  *
  * @param  $sStr
  * @return string
  */
function oos_string_to_int($sStr)
{
    return intval($sStr);
}


 /**
  * Return $aContents
  */
function oos_get_content()
{
    global $aContents;

    return $aContents;
}



 /**
  * Parse and secure the cPath parameter values
  *
  * @param  $sCategory
  * @return array
  */
function oos_parse_category_path($sCategory)
{
    // make sure the category IDs are integers
    $aCategoryPath = array_map('oos_string_to_int', explode('_', $sCategory));

    // make sure no duplicate category IDs exist which could lock the server in a loop
    $aTmp = [];
    for ($i=0, $n=count($aCategoryPath); $i<$n; $i++) {
        if (!in_array($aCategoryPath[$i], $aTmp)) {
            $aTmp[] = $aCategoryPath[$i];
        }
    }

    return $aTmp;
}


/**
 * Output the locale, doing some conversions to make sure the proper Facebook locale is outputted.
 *
 * @see  http://www.facebook.com/translations/FacebookLocales.xml for the list of supported locales
 * @link https://developers.facebook.com/docs/reference/opengraph/object-type/article/
 *
 * Function from Contributors: rankmath Plugin link: https://s.rankmath.com/homepage
 *
 * @param  string lang.
 * @return string
 */
function locale($locale)
{

    // Catch some weird locales served out by WP that are not easily doubled up.
    $fix_locales = [
       'ca' => 'ca_ES',
       'en' => 'en_US',
       'el' => 'el_GR',
       'et' => 'et_EE',
       'ja' => 'ja_JP',
       'sq' => 'sq_AL',
       'uk' => 'uk_UA',
       'vi' => 'vi_VN',
       'zh' => 'zh_CN',
    ];

    if (isset($fix_locales[ $locale ])) {
        $locale = $fix_locales[ $locale ];
    }

    // Convert locales like "es" to "es_ES", in case that works for the given locale (sometimes it does).
    if (2 === strlen($locale)) {
        $locale = strtolower($locale) . '_' . strtoupper($locale);
    }

    // These are the locales FB supports.
    $fb_valid_fb_locales = [
           'af_ZA', // Afrikaans.
           'ak_GH', // Akan.
           'am_ET', // Amharic.
           'ar_AR', // Arabic.
           'as_IN', // Assamese.
           'ay_BO', // Aymara.
           'az_AZ', // Azerbaijani.
           'be_BY', // Belarusian.
           'bg_BG', // Bulgarian.
           'bn_IN', // Bengali.
           'br_FR', // Breton.
           'bs_BA', // Bosnian.
           'ca_ES', // Catalan.
           'cb_IQ', // Sorani Kurdish.
           'ck_US', // Cherokee.
           'co_FR', // Corsican.
           'cs_CZ', // Czech.
           'cx_PH', // Cebuano.
           'cy_GB', // Welsh.
           'da_DK', // Danish.
           'de_DE', // German.
           'el_GR', // Greek.
           'en_GB', // English (UK).
           'en_IN', // English (India).
           'en_PI', // English (Pirate).
           'en_UD', // English (Upside Down).
           'en_US', // English (US).
           'eo_EO', // Esperanto.
           'es_CL', // Spanish (Chile).
           'es_CO', // Spanish (Colombia).
           'es_ES', // Spanish (Spain).
           'es_LA', // Spanish.
           'es_MX', // Spanish (Mexico).
           'es_VE', // Spanish (Venezuela).
           'et_EE', // Estonian.
           'eu_ES', // Basque.
           'fa_IR', // Persian.
           'fb_LT', // Leet Speak.
           'ff_NG', // Fulah.
           'fi_FI', // Finnish.
           'fo_FO', // Faroese.
           'fr_CA', // French (Canada).
           'fr_FR', // French (France).
           'fy_NL', // Frisian.
           'ga_IE', // Irish.
           'gl_ES', // Galician.
           'gn_PY', // Guarani.
           'gu_IN', // Gujarati.
           'gx_GR', // Classical Greek.
           'ha_NG', // Hausa.
           'he_IL', // Hebrew.
           'hi_IN', // Hindi.
           'hr_HR', // Croatian.
           'hu_HU', // Hungarian.
           'hy_AM', // Armenian.
           'id_ID', // Indonesian.
           'ig_NG', // Igbo.
           'is_IS', // Icelandic.
           'it_IT', // Italian.
           'ja_JP', // Japanese.
           'ja_KS', // Japanese (Kansai).
           'jv_ID', // Javanese.
           'ka_GE', // Georgian.
           'kk_KZ', // Kazakh.
           'km_KH', // Khmer.
           'kn_IN', // Kannada.
           'ko_KR', // Korean.
           'ku_TR', // Kurdish (Kurmanji).
           'ky_KG', // Kyrgyz.
           'la_VA', // Latin.
           'lg_UG', // Ganda.
           'li_NL', // Limburgish.
           'ln_CD', // Lingala.
           'lo_LA', // Lao.
           'lt_LT', // Lithuanian.
           'lv_LV', // Latvian.
           'mg_MG', // Malagasy.
           'mi_NZ', // Maori.
           'mk_MK', // Macedonian.
           'ml_IN', // Malayalam.
           'mn_MN', // Mongolian.
           'mr_IN', // Marathi.
           'ms_MY', // Malay.
           'mt_MT', // Maltese.
           'my_MM', // Burmese.
           'nb_NO', // Norwegian (bokmal).
           'nd_ZW', // Ndebele.
           'ne_NP', // Nepali.
           'nl_BE', // Dutch (Belgie).
           'nl_NL', // Dutch.
           'nn_NO', // Norwegian (nynorsk).
           'ny_MW', // Chewa.
           'or_IN', // Oriya.
           'pa_IN', // Punjabi.
           'pl_PL', // Polish.
           'ps_AF', // Pashto.
           'pt_BR', // Portuguese (Brazil).
           'pt_PT', // Portuguese (Portugal).
           'qu_PE', // Quechua.
           'rm_CH', // Romansh.
           'ro_RO', // Romanian.
           'ru_RU', // Russian.
           'rw_RW', // Kinyarwanda.
           'sa_IN', // Sanskrit.
           'sc_IT', // Sardinian.
           'se_NO', // Northern Sami.
           'si_LK', // Sinhala.
           'sk_SK', // Slovak.
           'sl_SI', // Slovenian.
           'sn_ZW', // Shona.
           'so_SO', // Somali.
           'sq_AL', // Albanian.
           'sr_RS', // Serbian.
           'sv_SE', // Swedish.
           'sw_KE', // Swahili.
           'sy_SY', // Syriac.
           'sz_PL', // Silesian.
           'ta_IN', // Tamil.
           'te_IN', // Telugu.
           'tg_TJ', // Tajik.
           'th_TH', // Thai.
           'tk_TM', // Turkmen.
           'tl_PH', // Filipino.
           'tl_ST', // Klingon.
           'tr_TR', // Turkish.
           'tt_RU', // Tatar.
           'tz_MA', // Tamazight.
           'uk_UA', // Ukrainian.
           'ur_PK', // Urdu.
           'uz_UZ', // Uzbek.
           'vi_VN', // Vietnamese.
           'wo_SN', // Wolof.
           'xh_ZA', // Xhosa.
           'yi_DE', // Yiddish.
           'yo_NG', // Yoruba.
           'zh_CN', // Simplified Chinese (China).
           'zh_HK', // Traditional Chinese (Hong Kong).
           'zh_TW', // Traditional Chinese (Taiwan).
           'zu_ZA', // Zulu.
           'zz_TR', // Zazaki.
    ];

    // Check to see if the locale is a valid FB one, if not, use en_US as a fallback.
    if (! in_array($locale, $fb_valid_fb_locales, true)) {
        $locale = strtolower(substr($locale, 0, 2)) . '_' . strtoupper(substr($locale, 0, 2));
        if (!in_array($locale, $fb_valid_fb_locales, true)) {
            $locale = 'en_US';
        }
    }

    return $locale;
}


 /**
  * Return File Extension
  *
  * @param  $filename
  * @return string
  */
function oos_get_extension($filename)
{
    $filename  = strtolower($filename);
    $extension = explode("[/\\.]", $filename);
    $n = count($extension)-1;
    $extension = $extension[$n];

    return $extension;
}



/**
 * Returns the suffix of a file name
 *
 * @param  string $filename
 * @return string
 */
function oos_get_suffix($filename)
{
    return strtolower(substr(strrchr($filename, "."), 1));
}

/**
 * returns a file name sans the suffix
 *
 * @param  string $filename
 * @return string
 */
function oos_strip_suffix($filename)
{
    return str_replace(strrchr($filename, "."), '', $filename);
}


/**
 * Strip non-alpha & non-numeric except ._-:
 *
 * @param  $sStr
 * @return string
 */
function oos_strip_all($sStr)
{
    $sStr = trim($sStr);
    $sStr = strtolower($sStr);

    return preg_match("/[^[:alnum:]._-]/", "", $sStr);
}


/**
 * remove digits after the comma
 *
 * @param  $number
 * @return int
 */
function oos_cut_number($number)
{
    $number = explode(".", $number, 2);

    if ($number[1] == '0000') {
        return $number[0];
    }

    return $number;
}


/**
 * Mail function (uses phpMailer)
 */
function oos_mail($to_name, $to_email_address, $email_subject, $email_text, $email_html, $from_email_name, $from_email_address, $attachments = [])
{
    global $oEvent;

    if (!is_object($oEvent) || (!$oEvent->installed_plugin('mail'))) {
        return false;
    }

    if (preg_match('~[\r\n]~', $to_name)) {
        return false;
    }
    if (preg_match('~[\r\n]~', $to_email_address)) {
        return false;
    }
    if (preg_match('~[\r\n]~', $email_subject)) {
        return false;
    }
    if (preg_match('~[\r\n]~', $from_email_name)) {
        return false;
    }
    if (preg_match('~[\r\n]~', $from_email_address)) {
        return false;
    }

    if (empty($to_email_address)) {
        return false;
    }
    if (empty($email_subject)) {
        return false;
    }
    if (empty($from_email_address)) {
        return false;
    }


    if (!is_array($attachments)) {
        $attachments = explode("\n", str_replace("\r\n", "\n", $attachments));
    }

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : DEFAULT_LANGUAGE_CODE);

    global $phpmailer;

    // (Re)create it, if it's gone missing
    if (! ($phpmailer instanceof PHPMailer\PHPMailer\PHPMailer)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/src/PHPMailer.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/src/SMTP.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/src/Exception.php';
        $phpmailer = new PHPMailer\PHPMailer\PHPMailer(true);

        $phpmailer::$validator = static function ($to_email_address) {
            return (bool) is_email($to_email_address);
        };
    }

    //To load the French version
    $phpmailer->setLanguage($sLang, MYOOS_INCLUDE_PATH . '/includes/lib/phpmailer/language/');

    // Empty out the values that may be set.
    $phpmailer->clearAllRecipients();
    $phpmailer->clearAttachments();
    $phpmailer->clearCustomHeaders();
    $phpmailer->clearReplyTos();

    $phpmailer->IsMail();


    $phpmailer->CharSet   = 'UTF-8';
    $phpmailer->Encoding  = 'base64';

    $phpmailer->From = $from_email_address ? $from_email_address : STORE_OWNER_EMAIL_ADDRESS;
    $phpmailer->FromName = $from_email_name ? $from_email_name : STORE_OWNER;
    $phpmailer->Mailer = EMAIL_TRANSPORT;

    // Add smtp values if needed
    if (EMAIL_TRANSPORT == 'smtp') {
        $phpmailer->IsSMTP(); // set mailer to use SMTP
        $phpmailer->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
        $phpmailer->Username = OOS_SMTPUSER; // SMTP username
        $phpmailer->Password = OOS_SMTPPASS; // SMTP password
        $phpmailer->Host     = OOS_SMTPHOST; // specify main and backup server
    } else {
        // Set sendmail path
        if (EMAIL_TRANSPORT == 'sendmail') {
            if (!oos_empty(OOS_SENDMAIL)) {
                $phpmailer->Sendmail = OOS_SENDMAIL;
                $phpmailer->IsSendmail();
            }
        }
    }

    $phpmailer->AddAddress($to_email_address, $to_name);
    $phpmailer->Subject = $email_subject;


    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
        $phpmailer->IsHTML(true);
        $phpmailer->Body = $email_html;
        $phpmailer->AltBody = $text;
    } else {
        $phpmailer->Body = $text;
    }

    // Send message
    $phpmailer->Send();
}

function oos_newsletter_subscribe_mail($email_address)
{
    global $aLang, $sTheme;

    if (empty($email_address)) {
        return false;
    }

    $sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;

    if (is_email($email_address)) {
        $aContents = oos_get_content();

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $newsletter_recipients = $oostable['newsletter_recipients'];
        $dbconn->Execute("DELETE FROM $newsletter_recipients WHERE customers_email_address = '" . oos_db_input($email_address) . "'");

        $sRandom = oos_create_random_value(25);
        $sBefor = oos_create_random_value(4);

        $dbconn->Execute(
            "INSERT INTO $newsletter_recipients 
                            (customers_email_address,
							mail_key,
							key_sent,
							status) VALUES ('" . oos_db_input($email_address) . "',
											'" . oos_db_input($sRandom) . "',
											now(),
											'0')"
        );

        $nInsert_ID = $dbconn->Insert_ID();
        $newsletter_recipients = $oostable['newsletter_recipients_history'];
        $dbconn->Execute(
            "INSERT INTO $newsletter_recipients 
                                    (recipients_id,
                                    date_added) VALUES ('" . intval($nInsert_ID) . "',
                                                        now())"
        );

        $sStr =  $sBefor . $nInsert_ID . 'f00d';
        $sSha1 = sha1($sStr);

        $newsletter_recipients = $oostable['newsletter_recipients'];
        $dbconn->Execute(
            "UPDATE $newsletter_recipients
                          SET mail_sha1 = '" . oos_db_input($sSha1) . "'
                          WHERE recipients_id = '" . intval($nInsert_ID) . "'"
        );
        //smarty
        include_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';
        $smarty = new myOOS_Smarty();

        // dont allow cache
        $smarty->caching = false;

        $smarty->assign(
            [
                    'shop_name'        => STORE_NAME,
                    'shop_url'        => OOS_HTTPS_SERVER . OOS_SHOP,
                    'shop_logo'        => STORE_LOGO,
                    'services_url'    => PHPBB_URL,
                    'blog_url'        => BLOG_URL,
                    'imprint_url'    => oos_href_link($aContents['information'], 'information_id=1', false, true),
                    'subscribe'        => oos_href_link($aContents['newsletter'], 'action=lists&subscribe=confirm&u=' .  $sSha1 . '&id=' . $sStr . '&e=' . $sRandom, false, true)
                ]
        );

        // create mails
        $email_html = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.html');
        $email_txt = $smarty->fetch($sTheme . '/email/' . $sLanguage . '/newsletter_subscribe.tpl');

        oos_mail('', $email_address, $aLang['newsletter_email_subject'], $email_txt, $email_html, STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS);
    }
}
