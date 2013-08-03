<?php
/* ----------------------------------------------------------------------
   $Id: function_kernel.php 300 2013-04-13 17:36:36Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

 /**
  * kernel
  *
  * @package kernel
  * @copyright (C) 2013 by the MyOOS Development Team.
  * @license GPL <http://www.gnu.org/licenses/gpl.html>
  * @link http://www.oos-shop.de/
  */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

 /**
  * Stop from parsing any further PHP code
  */
  function oos_exit() {
   oos_session_close();
   exit();
  }


 /**
  * Redirect to another page or site
  *
  * @param $sUrl
  * @return string
  */
  function oos_redirect($sUrl) {
    if (ENABLE_SSL == 'true'){
      if (strtolower(oos_server_get_var('HTTPS')) == 'on' || (oos_server_get_var('HTTPS') == '1') || oos_server_has_var('SSL_PROTOCOL')) { // We are loading an SSL page
        if (substr($sUrl, 0, strlen(OOS_HTTP_SERVER)) == OOS_HTTP_SERVER) { // NONSSL url
          $sUrl = OOS_HTTPS_SERVER . substr($sUrl, strlen(OOS_HTTP_SERVER)); // Change it to SSL
        }
      }
    }

    // clean URL
    if (strpos($sUrl, '&amp;') !== false) $sUrl = str_replace('&amp;', '&', $sUrl);
    if (strpos($sUrl, '&&') !== false) $sUrl = str_replace('&&', '&', $sUrl);

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
    $oostable =& oosDBGetTables();

    $random_product = '';
    if (oos_is_not_null($limit)) {
      if (USE_DB_CACHE == 'true') {
        $random_result = $dbconn->CacheSelectLimit(15, $query, $limit);
      } else {
        $random_result = $dbconn->SelectLimit($query, $limit);
      }
    } else {
      if (USE_DB_CACHE == 'true') {
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
  * @return prepared variable if only one variable passed
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

    $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : 1;

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($nProductID) . "'
                AND products_languages_id = '" .  intval($nLanguageID) . "'";
    $products_name = $dbconn->GetOne($query);

    return $products_name;
  }


 /**
  * Return News Author Name
  *
  * @param $nNewsAuthorId
  * @return string
  */
  function oos_get_news_author_name($nNewsAuthorId) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $admintable = $oostable['admin'];
    $query  = "SELECT admin_firstname, admin_lastname
               FROM $admintable
               WHERE admin_id  = '" . intval($nNewsAuthorId) . "'";
    $result = $dbconn->Execute($query);

    $sAdminName = $result->fields['admin_firstname'] . ' ' . $result->fields['admin_lastname'];

    // Close result set
    $result->Close();

    return $sAdminName;
  }


 /**
  * Return News Average Rating
  *
  * @param $nNewsId
  * @return string
  */
  function oos_get_news_reviews($nNewsId) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $news_reviewstable = $oostable['news_reviews'];
    $query  = "SELECT (avg(news_reviews_rating ) / 5 * 100) AS average_rating
               FROM $news_reviewstable
               WHERE news_id  = '" . intval($nNewsId)  . "'";
    $result = $dbconn->Execute($query);

    $sAverage = $result->fields['average_rating'];

    // Close result set
    $result->Close();

    return $sAverage;
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

    // Close result set
    $result->Close();

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
  function oos_get_products_price_quantity_discount($product_id, $qty, $current_price = false) {

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
          $the_discount_price= $current_price;
        } else {
          $the_discount_price= $product_discounts['products_price'];
        }
        break;

      case ($qty >= $product_discounts['products_discount4_qty'] and $product_discounts['products_discount4_qty'] !=0):
        $the_discount_price= $product_discounts['products_discount4'];
        break;

      case ($qty >= $product_discounts['products_discount3_qty'] and $product_discounts['products_discount3_qty'] !=0 ):
        $the_discount_price= $product_discounts['products_discount3'];
        break;

      case ($qty >= $product_discounts['products_discount2_qty'] and $product_discounts['products_discount2_qty'] !=0 ):
        $the_discount_price= $product_discounts['products_discount2'];
        break;

      case ($qty >= $product_discounts['products_discount1_qty'] and $product_discounts['products_discount1_qty'] !=0 ):
        $the_discount_price= $product_discounts['products_discount1'];
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
  * @return string
  */
  function oos_check_stock($sProductsId, $nProductsQuantity) {
    global $aLang;

    $stock_left = oos_get_products_stock($sProductsId) - $nProductsQuantity;

    $sOutOfStock = '';
    if ($stock_left < 0) {
      $sOutOfStock = '<span class="oos-MarkProductOutOfStock">' . STOCK_MARK_PRODUCT_OUT_OF_STOCK . '</span>';
    }

    return $sOutOfStock;
  }


 /**
  * Return all GET variables, except those passed as a parameter
  *
  * @param  $aExclude
  * @return string
  */
  function oos_get_all_get_parameters($aExclude = '') {

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
				|| $value === false)
			{
				continue;
			}
			$urlValues[$key] = $value;
		}
	}
	
    $sUrl = '';
    if (is_array($urlValues) && (count($urlValues) > 0)) {
      reset($urlValues);
      while (list($sKey, $sValue) = each($urlValues)) {
        if (!empty($sValue)) {
          if ( ($sKey != oos_session_name()) && (!in_array($sKey, $aParameters)) && (!in_array($sKey, $aExclude)) ) {
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

    if (!is_array($aExclude)) $aExclude = array();

    $aParameters = array('formid', 'page', 'x', 'y');

    $sUrl = '';
    if (is_array($_POST) && (count($_POST) > 0)) {
      reset($_POST);
      while (list($sKey, $sValue) = each($_POST)) {
        if ( (!empty($sValue)) && (!is_array($sValue)) ) {
          if ( ($sKey != oos_session_name())  && (!in_array($sKey, $aParameters))  && (!in_array($sKey, $aExclude)) ) {
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
  function oos_get_countries($countries_id = '', $bWithIsoCodes = false) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aCountries = array();
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
  * @param $country_id
  * @return string
  */
  function oos_get_country_name($country_id) {

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
  * Return Campaign Name
  *
  * @param $campaigns_id
  * @param $language
  * @return string
  */
  function oos_get_campaigns_name($campaigns_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $campaignstable = $oostable['campaigns'];
    $query = "SELECT campaigns_name
              FROM $campaignstable
              WHERE campaigns_id = '" . intval($campaigns_id) . "'
                AND campaigns_languages_id = '" . intval($_SESSION['language_id']) . "'";
    $campaigns_name = $dbconn->GetOne($query);

    return $campaigns_name;
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


    if ( ($country_id == -1) && ($zone_id == -1) ) {
      if (!isset($_SESSION['customer_id'])) {
        $country_id = STORE_COUNTRY;
        $zone_id = STORE_ZONE;
      } else {
        $country_id = $_SESSION['customer_country_id'];
        $zone_id = $_SESSION['customer_zone_id'];
      }
    }

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
    if (USE_DB_CACHE_LEVEL_HIGH == 'true') {
      $tax_result = $dbconn->CacheExecute(30, $query);
    } else {
      $tax_result = $dbconn->Execute($query);
    }
    if (!$tax_result) {return 0;}

    if ($tax_result->RecordCount() > 0) {
      $tax_multiplier = 0;
      while ($tax = $tax_result->fields) {
        $tax_multiplier += $tax['tax_rate'];
        // Move that ADOdb pointer!
        $tax_result->MoveNext();
      }
      // Close result set
      $tax_result->Close();

      return $tax_multiplier;
    } else {
      return 0;
    }
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

      // Close result set
      $tax_result->Close();

      $tax_description = substr($tax_description, 0, -3);

      return $tax_description;
    } else {
      return $aLang['text_unknown_tax_rate'];
    }
  }


 /**
  * Add tax to a products price
  *
  * @param $price
  * @param $tax
  */
  function oos_add_tax($price, $tax) {

    if( ($_SESSION['member']->group['show_price_tax'] == 1) && ($tax > 0) ) {
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

    if ($tax > 0) {
      return $price * $tax / 100;
    } else {
      return 0;
    }

  }


  function oos_get_categories($aCategories = '', $parent_id = '0', $indent = '') {

    $parent_id = oos_db_prepare_input($parent_id);
    $nGroupID = intval($_SESSION['member']->group['id']);

    if (!is_array($aCategories)) $aCategories = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $nLanguageID = isset($_SESSION['language_id']) ? $_SESSION['language_id']+0 : 1;

    $categoriestable = $oostable['categories'];
    $categories_descriptiontable = $oostable['categories_description'];
    $query = "SELECT c.categories_id, c.categories_status, cd.categories_name
              FROM $categoriestable c,
                   $categories_descriptiontable cd
              WHERE ( c.access = '0' OR c.access = '" . intval($nGroupID) . "' )
                AND c.categories_status = '1'
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

    // Close result set
    $result->Close();

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
      if ($parent_categories['parent_id'] == 0) return true;
      $categories[count($categories)] = $parent_categories['parent_id'];
      if ($parent_categories['parent_id'] != $categories_id) {
        oos_get_parent_categories($categories, $parent_categories['parent_id']);
      }

      // Move that ADOdb pointer!
      $result->MoveNext();
    }
    // Close result set
    $result->Close();
  }


 /**
  * Construct a category path to the product
  *
  * @param $products_id
  * @return string
  */
  function oos_get_product_path($products_id) {

    $category = '';

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
        if ($category != '') $category .= '_';
        $category .= $categories[$i];
      }
      if ($category != '') $category .= '_';
      $category .= $cat_id_data['categories_id'];
    }

    return $category;
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
  * @param $params
  * @return string
  */
  function oos_get_uprid($prid, $parameters) {
    if (is_numeric($prid)) {
      $uprid = $prid;

      if (is_array($parameters) && (count($parameters) > 0)) {
        $attributes_check = true;
        $attributes_ids = '';

        reset($parameters);
        while (list($option, $sValue) = each($parameters)) {
          if (is_numeric($option) && is_numeric($sValue)) {
            $attributes_ids .= '{' . intval($option) . '}' . intval($sValue);
          } elseif (strstr($option, TEXT_PREFIX)) {
            $text_option = substr($option, strlen(TEXT_PREFIX));
            $sLen = strlen($sValue);
            $attributes_ids .= '{' . intval($text_option) . '}' . intval($sLen);
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
      return true;
    } else {
      return false;
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
    return oos_count_modules($_SESSION['member']->group['payment']);
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
function oos_output_string($sStr, $aTranslate = null)
{

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

    $allowedTags = '<h1><b><i><a><ul><li><pre><hr><br><blockquote>';
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
      return false;
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
  * Return $aContents;
  */
  function oos_get_content() {
    GLOBAL $aContents;

    return $aContents;
  }



 /**
  * Parse and secure the category parameter values
  *
  * @param $category
  * @return array
  */
  function oos_parse_category_path($category) {
    // make sure the category IDs are integers
    $aCategoryPath = array_map('oos_string_to_int', explode('_', $category));

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
    $sStr =& trim($sStr);
    $sStr =& strtolower($sStr);

    return preg_match("/[^[:alnum:]._-]/", "", $sStr);
  }


  /**
   * Mail function (uses phpMailer)
   */
  function oos_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {

    global $oEvent;

    if (!$oEvent->installed_plugin('mail')) return false;

    if (preg_match('~[\r\n]~', $to_name)) return false;
    if (preg_match('~[\r\n]~', $to_email_address)) return false;
    if (preg_match('~[\r\n]~', $email_subject)) return false;
    if (preg_match('~[\r\n]~', $from_email_name)) return false;
    if (preg_match('~[\r\n]~', $from_email_address)) return false;

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : 'en');

    // Instantiate a new mail object
    $mail = new PHPMailer;

    $mail->PluginDir = OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/';
    $mail->SetLanguage( $sLang, OOS_ABSOLUTE_PATH . 'includes/lib/phpmailer/language/' );

    $mail->CharSet = CHARSET;
    $mail->IsMail();

    $mail->From = $from_email_address ? $from_email_address : STORE_OWNER_EMAIL_ADDRESS;
    $mail->FromName = $from_email_name ? $from_email_name : STORE_OWNER;
    $mail->Mailer = EMAIL_TRANSPORT;

    // Add smtp values if needed
    if ( EMAIL_TRANSPORT == 'smtp' ) {
      $mail->IsSMTP(); // set mailer to use SMTP
      $mail->SMTPAuth = OOS_SMTPAUTH; // turn on SMTP authentication
      $mail->Username = OOS_SMTPUSER; // SMTP username
      $mail->Password = OOS_SMTPPASS; // SMTP password
      $mail->Host     = OOS_SMTPHOST; // specify main and backup server
    } else
      // Set sendmail path
      if ( EMAIL_TRANSPORT == 'sendmail' ) {
        if (!oos_empty(OOS_SENDMAIL)) {
          $mail->Sendmail = OOS_SENDMAIL;
          $mail->IsSendmail();
        }
    }


    $mail->AddAddress($to_email_address, $to_name);
    $mail->Subject = $email_subject;


    // Build the text version
    $text = strip_tags($email_text);
    if (EMAIL_USE_HTML == 'true') {
      $mail->IsHTML(true);
      $mail->Body = $email_text;
      $mail->AltBody = $text;
    } else {
      $mail->Body = $text;
    }

    // Send message
    $mail->Send();
  }

