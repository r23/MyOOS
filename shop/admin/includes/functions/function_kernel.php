<?php
/* ----------------------------------------------------------------------
   $Id: function_kernel.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_
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
  * Admin Kernel
  *
  * @link http://www.oos-shop.de/
  * @package Admin Kernel
  * @author r23 <info@r23.de>
  * @copyright 2003 r23
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */


  function oos_admin_check_login() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aFilename = oos_get_filename();

    if (!isset($_SESSION['login_id'])) {
      oos_redirect_admin(oos_href_link_admin($aFilename['login'], '', 'SSL'));
    } else {
      $filename = preg_split('/\?/', basename($_SERVER['SCRIPT_NAME']));
      $filename = $filename[0];
      $page_key = array_search($filename, $aFilename);

      if ($filename != $aFilename['default'] && $filename != $aFilename['forbiden'] && $filename != $aFilename['logoff'] && $filename != $aFilename['admin_account']  && $filename != $aFilename['popup_image'] && $filename != $aFilename['packingslip'] && $filename != $aFilename['popup_image_product']  && $filename != $aFilename['popup_image_news'] && $filename != $aFilename['popup_subimage_product'] && $filename != $aFilename['invoice'] && $filename != $aFilename['edit_orders']) {
        $admin_filestable = $oostable['admin_files'];
        $query = "SELECT admin_files_name
                  FROM $admin_filestable
                  WHERE FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id)
                    AND admin_files_name = '" . $page_key . "'";
        $result = $dbconn->Execute($query);

        if (!$result->RecordCount()) {
          oos_redirect_admin(oos_href_link_admin($aFilename['forbiden']));
        }
      }
    }
  }

  function oos_admin_check_boxes($filename, $boxes='') {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $is_boxes = 1;
    if ($boxes == 'sub_boxes') {
      $is_boxes = 0;
    }

    $admin_filestable = $oostable['admin_files'];
    $query = "SELECT admin_files_id
              FROM $admin_filestable
              WHERE FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id)
                AND admin_files_is_boxes = '" . $is_boxes . "'
                AND admin_files_name = '" . $filename . "'";
    $result = $dbconn->Execute($query);

    $return_value = false;
    if ($result->RecordCount()) {
      $return_value = true;
    }

    // Close result set
    $result->Close();

    return $return_value;
  }


function oos_admin_files_boxes($filename, $parameters, $sub_box_name)
{

	$sub_boxes = '';

	// Get database information
	$dbconn =& oosDBGetConn();
	$oostable =& oosDBGetTables();

	$aFilename = oos_get_filename();

	$admin_filestable = $oostable['admin_files'];
    $query = "SELECT admin_files_name
              FROM $admin_filestable
              WHERE FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id)
                AND admin_files_is_boxes = '0'
                AND admin_files_name = '" . $filename . "'";
	$result = $dbconn->Execute($query);

	if ($result->RecordCount())
	{
		if(defined('NEW_MYOOS'))
		{
			$sub_boxes = '<a href="' . oos_href_link_admin($aFilename[$filename]) . '" class="menuBoxContentLink">' . $sub_box_name . '</a><br />';
		} else {
			$sub_boxes = '<a href="' . oos_href_link_admin($aFilename[$filename], $parameters) . '" title="' . $sub_box_name . '">' . $sub_box_name . '</a>';
		}
    }

    return $sub_boxes;
}


  function oos_selected_file($filename) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aFilename = oos_get_filename();

    $randomize = 'admin_account';

    $admin_filestable = $oostable['admin_files'];
    $query = "SELECT admin_files_id AS boxes_id
              FROM $admin_filestable
              WHERE FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id)
                AND admin_files_is_boxes = '1'
                AND admin_files_name = '" . $filename . "'";
    $result = $dbconn->Execute($query);

    if ($result->RecordCount()) {
      $boxes_id = $result->fields;

      $admin_filestable = $oostable['admin_files'];
      $randomize_query = "SELECT admin_files_name
                           FROM $admin_filestable
                           WHERE FIND_IN_SET( '" . $_SESSION['login_groups_id'] . "', admin_groups_id)
                             AND admin_files_is_boxes = '0'
                             AND admin_files_to_boxes = '" . $boxes_id['boxes_id'] . "'";
      $randomize_result = $dbconn->Execute($randomize_query);

      if ($randomize_result->RecordCount()) {
        $randomize = $randomize_result->fields['admin_files_name'];
      }
    }
    return $aFilename[$randomize];
  }


  function oos_redirect_admin($url) {
    global $logger;

    header('Location: ' . $url);

    exit;
  }

  function oos_customers_name($customers_id) {

    $sName = '';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $customerstable = $oostable['customers'];
    $query = "SELECT customers_firstname, customers_lastname
              FROM $customerstable
              WHERE customers_id = '" . $customers_id . "'";
    $result = $dbconn->Execute($query);

    $sName = $customers->fields['customers_firstname'] . ' ' . $customers->fields['customers_lastname'];

    // Close result set
    $result->Close();

    return $sName;
  }


  function oos_get_all_get_params($exclude_array = '') {
    if ($exclude_array == '') $exclude_array = array();

    $get_url = '';

    reset($_GET);
    while (list($key, $value) = each($_GET)) {
      if (($key != oos_session_name()) && ($key != 'error') && (!oos_in_array($key, $exclude_array))) $get_url .= $key . '=' . $value . '&';
    }

    return $get_url;
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




  function oos_get_filename() {
    GLOBAL $aFilename;

    return $aFilename;
  }




  function oos_datetime_short($raw_datetime) {
    if ( ($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '') ) return false;

    $year = (int)substr($raw_datetime, 0, 4);
    $month = (int)substr($raw_datetime, 5, 2);
    $day = (int)substr($raw_datetime, 8, 2);
    $hour = (int)substr($raw_datetime, 11, 2);
    $minute = (int)substr($raw_datetime, 14, 2);
    $second = (int)substr($raw_datetime, 17, 2);

    return strftime(DATE_TIME_FORMAT, mktime($hour, $minute, $second, $month, $day, $year));
  }



  function oos_in_array($lookup_value, $lookup_array) {
    if (in_array($lookup_value, $lookup_array)) return true;

    return false;
  }


  function oos_info_image($image, $alt, $width = '', $height = '') {
    if ( ($image) && (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $image)) ) {
      $image = oos_image(OOS_SHOP_IMAGES . $image, $alt, $width, $height);
    } else {
      $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
  }


  function oos_break_string($string, $len, $break_char = '-') {
    $l = 0;
    $output = '';
    for ($i = 0; $i < strlen($string); $i++) {
      $char = substr($string, $i, 1);
      if ($char != ' ') {
        $l++;
      } else {
        $l = 0;
      }
      if ($l > $len) {
        $l = 1;
        $output .= $break_char;
      }
      $output .= $char;
    }

    return $output;
  }


  function oos_browser_detect($component) {
    return stristr($_SERVER['HTTP_USER_AGENT'], $component);
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
  
  
  function oos_address_format($address_format_id, $address, $html, $boln, $eoln) {

    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_formattable = $oostable['address_format'];
    $query = "SELECT address_format as format
              FROM $address_formattable
              WHERE address_format_id = '" . $address_format_id . "'";
    $result = $dbconn->Execute($query);

    $address_format = $result->fields;

    // Close result set
    $result->Close();

    $company = addslashes($address['company']);
    $firstname = addslashes($address['firstname']);
    $lastname = addslashes($address['lastname']);
    $street = addslashes($address['street_address']);
    $suburb = addslashes($address['suburb']);
    $city = addslashes($address['city']);
    $state = addslashes($address['state']);
    $country_id = $address['country_id'];
    $zone_id = $address['zone_id'];
    $postcode = addslashes($address['postcode']);
    $zip = $postcode;
    $country = oos_get_country_name($country_id);
    $state = oos_get_zone_code($country_id, $zone_id, $state);

    if ($html) {
// HTML Mode
      $HR = '<hr>';
      $hr = '<hr>';
      if ( ($boln == '') && ($eoln == "\n") ) { // Values not specified, use rational defaults
        $CR = '<br />';
        $cr = '<br />';
        $eoln = $cr;
      } else { // Use values supplied
        $CR = $eoln . $boln;
        $cr = $CR;
      }
    } else {
// Text Mode
      $CR = $eoln;
      $cr = $CR;
      $HR = '----------------------------------------';
      $hr = '----------------------------------------';
    }

    $statecomma = '';
    $streets = $street;
    if ($suburb != '') $streets = $street . $cr . $suburb;
    if ($firstname == '') $firstname = addslashes($address['name']);
    if ($country == '') $country = addslashes($address['country']);
    if ($state != '') $statecomma = $state . ', ';

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes($address);

    if ( (ACCOUNT_COMPANY == 'true') && (oos_is_not_null($company)) ) {
      $address = $company . $cr . $address;
    }

    return $boln . $address . $eoln;
  }


  function oos_get_zone_code($country, $zone, $def_state) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonestable = $oostable['zones'];
    $query = "SELECT zone_code
              FROM $zonestable
              WHERE zone_country_id = '" . $country . "'
                AND zone_id = '" . $zone . "'";
    $result = $dbconn->Execute($query);

    if (!$result->RecordCount()) {
      $state_prov_code = $def_state;
    } else {
      $state_prov_values = $result->fields;
      $state_prov_code = $state_prov_values['zone_code'];
    }

    // Close result set
    $result->Close();

    return $state_prov_code;
  }


  function oos_get_country_name($country_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['countries'];
    $query = "SELECT countries_name
              FROM $countriestable
              WHERE countries_id = '" . $country_id . "'";
    $result = $dbconn->Execute($query);

    if (!$result->RecordCount()) {
      return $country_id;
    } else {
      return $result->fields['countries_name'];
    }
  }


  function oos_get_uprid($prid, $params) {
    $uprid = $prid;
    if ( (is_array($params)) && (!strstr($prid, '{')) ) {
      while (list($option, $value) = each($params)) {
        $uprid = $uprid . '{' . $option . '}' . $value;
      }
    }
    return $uprid;
  }

  function oos_get_prid($uprid) {
    $pieces = explode ('{', $uprid);

    return $pieces[0];
  }


  function oos_get_languages() {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $languages_array = array();

    $languagestable = $oostable['languages'];
    $query = "SELECT languages_id, name, iso_639_2, iso_639_1
              FROM $languagestable
              WHERE status = '1'
              ORDER BY sort_order";
    $result = $dbconn->Execute($query);

    while ($languages = $result->fields) {
      $languages_array[] = array('id' => $languages['languages_id'],
                                 'name' => $languages['name'],
                                 'iso_639_2' => $languages['iso_639_2'],
                                 'iso_639_1' => $languages['iso_639_1']
                                );

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $languages_array;
  }

 /**
  * Return Products Name
  *
  * @param $product_id
  * @param $language
  * @return string
  */
  function oos_get_products_name($product_id, $lang_id = '') {

    if (!$lang_id) $lang_id = $_SESSION['language_id'];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . $product_id . "'
                AND products_languages_id = '" . intval($lang_id) . "'";
    $result = $dbconn->Execute($query);

    $products_name = $result->fields['products_name'];

    // Close result set
    $result->Close();

    return $products_name;
  }



  function oos_get_countries($default = '') {

    $countries_array = array();
    if ($default) {
      $countries_array[] = array('id' => '',
                                 'text' => $default);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['countries'];
    $query = "SELECT countries_id, countries_name
              FROM $countriestable
              ORDER BY countries_name";
    $result = $dbconn->Execute($query);

    while ($countries = $result->fields) {
      $countries_array[] = array('id' => $countries['countries_id'],
                                 'text' => $countries['countries_name']);

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $countries_array;
  }


  function oos_get_country_zones($country_id) {

    $zones_array = array();

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonestable = $oostable['zones'];
    $query = "SELECT zone_id, zone_name
              FROM $zonestable
              WHERE zone_country_id = '" . $country_id . "'
              ORDER BY zone_name";
    $result = $dbconn->Execute($query);

    while ($zones = $result->fields) {
      $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    return $zones_array;
  }


  function oos_prepare_country_zones_pull_down($country_id = '') {
// preset the width of the drop-down for Netscape
    $pre = '';
    if ( (!oos_browser_detect('MSIE')) && (oos_browser_detect('Mozilla/4')) ) {
      for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
    }

    $zones = oos_get_country_zones($country_id);

    if (count($zones) > 0) {
      $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
      $zones = array_merge($zones_select, $zones);
    } else {
      $zones = array(array('id' => '', 'text' => TYPE_BELOW));
// create dummy options for Netscape to preset the height of the drop-down
      if ( (!oos_browser_detect('MSIE')) && (oos_browser_detect('Mozilla/4')) ) {
        for ($i=0; $i<9; $i++) {
          $zones[] = array('id' => '', 'text' => $pre);
        }
      }
    }

    return $zones;
  }



  function oos_set_time_limit($limit) {
    if (strlen(ini_get("safe_mode"))< 1) {
      @set_time_limit($limit);
    }
  }


  function oos_get_uploaded_file($filename) {
    if (isset($_FILES[$filename])) {
      $uploaded_file = array('name' => $_FILES[$filename]['name'],
                             'type' => $_FILES[$filename]['type'],
                             'size' => $_FILES[$filename]['size'],
                             'tmp_name' => $_FILES[$filename]['tmp_name']);
    } elseif (isset($GLOBALS['HTTP_POST_FILES'][$filename])) {
      global $HTTP_POST_FILES;

      $uploaded_file = array('name' => $HTTP_POST_FILES[$filename]['name'],
                             'type' => $HTTP_POST_FILES[$filename]['type'],
                             'size' => $HTTP_POST_FILES[$filename]['size'],
                             'tmp_name' => $HTTP_POST_FILES[$filename]['tmp_name']);
    } else {
      $uploaded_file = array('name' => $GLOBALS[$filename . '_name'],
                             'type' => $GLOBALS[$filename . '_type'],
                             'size' => $GLOBALS[$filename . '_size'],
                             'tmp_name' => $GLOBALS[$filename]);
    }

    return $uploaded_file;
  }


  function oos_get_copy_uploaded_file($filename, $target) {
    if (substr($target, -1) != '/') $target .= '/';

    $target .= $filename['name'];

    move_uploaded_file($filename['tmp_name'], $target);
    @chmod($target, 0644);
  }



  function oos_remove_product($product_id) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $product_image_query = "SELECT products_image
                            FROM $productstable
                            WHERE products_id = '" . intval($product_id) . "'";
    $product_image_result = $dbconn->Execute($product_image_query);
    $product_image = $product_image_result->fields;

    $productstable = $oostable['products'];
    $duplicate_query = "SELECT COUNT(*) AS total
                        FROM $productstable
                        WHERE products_image = '" . oos_db_input($product_image['products_image']) . "'";
    $duplicate_result = $dbconn->Execute($duplicate_query);

    if ($duplicate_result->fields['total'] < 2) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $product_image['products_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . $product_image['products_image']);
      }
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_POPUP_IMAGES . $product_image['products_image'])) {
        @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_POPUP_IMAGES . $product_image['products_image']);
      }
    }


    $dbconn->Execute("DELETE FROM " . $oostable['specials'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_description'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
	$dbconn->Execute("DELETE FROM " . $oostable['products_images'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE master_id = '" . intval($product_id) . "' OR slave_id = '" . intval($product_id) . "'");

    $reviewstable = $oostable['reviews'];
    $reviews_query = "SELECT reviews_id
                      FROM $reviewstable
                      WHERE products_id = '" . intval($product_id) . "'";
    $reviews_result = $dbconn->Execute($reviews_query);

    while ($product_reviews = $reviews_result->fields) {
      $dbconn->Execute("DELETE FROM " . $oostable['reviews_description'] . " WHERE reviews_id = '" . intval($product_reviews['reviews_id']) . "'");

      // Move that ADOdb pointer!
      $reviews_result->MoveNext();
    }

    // Close result set
    $reviews_result->Close();

    $dbconn->Execute("DELETE FROM " . $oostable['reviews'] . " WHERE products_id = '" . intval($product_id) . "'");

  }


  function oos_class_exits($class_name) {
    if (function_exists('class_exists')) {
      return class_exists($class_name);
    } else {
      return true;
    }
  }


  function oos_remove($source) {
    global $messageStack, $oos_remove_error;

    if (isset($oos_remove_error)) $oos_remove_error = false;

    if (is_dir($source)) {
      $dir = dir($source);
      while ($file = $dir->read()) {
        if ( ($file != '.') && ($file != '..') ) {
          if (is_writeable($source . '/' . $file)) {
            oos_remove($source . '/' . $file);
          } else {
            $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source . '/' . $file), 'error');
            $oos_remove_error = true;
          }
        }
      }
      $dir->close();

      if (is_writeable($source)) {
        rmdir($source);
      } else {
        $messageStack->add(sprintf(ERROR_DIRECTORY_NOT_REMOVEABLE, $source), 'error');
        $oos_remove_error = true;
      }
    } else {
      if (is_writeable($source)) {
        unlink($source);
      } else {
        $messageStack->add(sprintf(ERROR_FILE_NOT_REMOVEABLE, $source), 'error');
        $oos_remove_error = true;
      }
    }
  }


  function oos_display_tax_value($value, $padding = TAX_DECIMAL_PLACES) {
    if (strpos($value, '.')) {
      $loop = true;
      while ($loop) {
        if (substr($value, -1) == '0') {
          $value = substr($value, 0, -1);
        } else {
          $loop = false;
          if (substr($value, -1) == '.') {
            $value = substr($value, 0, -1);
          }
        }
      }
    }

    if ($padding > 0) {
      if ($decimal_pos = strpos($value, '.')) {
        $decimals = strlen(substr($value, ($decimal_pos+1)));
        for ($i=$decimals; $i<$padding; $i++) {
          $value .= '0';
        }
      } else {
        $value .= '.';
        for ($i=0; $i<$padding; $i++) {
          $value .= '0';
        }
      }
    }

    return $value;
  }


  function oos_banner_image_extension() {
    if (function_exists('imagetypes')) {
      if (imagetypes() & IMG_PNG) {
        return 'png';
      } elseif (imagetypes() & IMG_JPG) {
        return 'jpg';
      } elseif (imagetypes() & IMG_GIF) {
        return 'gif';
      }
    } elseif (function_exists('imagecreatefrompng') && function_exists('imagepng')) {
      return 'png';
    } elseif (function_exists('imagecreatefromjpeg') && function_exists('imagejpeg')) {
      return 'jpg';
    } elseif (function_exists('imagecreatefromgif') && function_exists('imagegif')) {
      return 'gif';
    }

    return false;
  }


  function oos_add_tax($price, $tax) {
    global $currencies;

    if (DISPLAY_PRICE_WITH_TAX == 'true') {
      return round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']) + oos_calculate_tax($price, $tax);
    } else {
      return round($price, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
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
    $zones_to_geo_zonestable = $oostable['zones_to_geo_zones'];
    $geo_zonestable = $oostable['geo_zones'];
    $query = "SELECT SUM(tax_rate) AS tax_rate
              FROM $tax_ratestable tr LEFT JOIN
                   $zones_to_geo_zonestable za
                 ON (tr.tax_zone_id = za.geo_zone_id) LEFT JOIN
                   $geo_zonestable tz
                 ON (tz.geo_zone_id = tr.tax_zone_id)
              WHERE (za.zone_country_id IS null or za.zone_country_id = '0'
                  OR za.zone_country_id = '" . (int)$country_id . "')
                AND (za.zone_id is null OR za.zone_id = '0'
                  OR za.zone_id = '" . (int)$zone_id . "')
                AND tr.tax_class_id = '" . (int)$class_id . "'
            GROUP BY tr.tax_priority";
    $result = $dbconn->Execute($query);

    if ($result->RecordCount()) {
      $tax_multiplier = 0;
      while ($tax = $result->fields) {
        $tax_multiplier += $tax['tax_rate'];

        // Move that ADOdb pointer!
        $result->MoveNext();
      }

      // Close result set
      $result->Close();

      return $tax_multiplier;
    } else {
      return 0;
    }
  }


  function oos_calculate_tax($price, $tax) {
    global $currencies;

    return round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
  }


  function oos_call_function($function, $parameter, $object = '') {
    if ($object == '') {
      return call_user_func($function, $parameter);
    } else {
      return call_user_func(array($object, $function), $parameter);
    }
  }


  function oos_get_serialized_variable(&$serialization_data, $variable_name, $variable_type = 'string') {
    $serialized_variable = '';

    switch ($variable_type) {
      case 'string':
        $start_position = strpos($serialization_data, $variable_name . '|s');

        $serialized_variable = substr($serialization_data, strpos($serialization_data, '|', $start_position) + 1, strpos($serialization_data, '|', $start_position) - 1);
        break;

      case 'array':
      case 'object':
        if ($variable_type == 'array') {
          $start_position = strpos($serialization_data, $variable_name . '|a');
        } else {
          $start_position = strpos($serialization_data, $variable_name . '|O');
        }

        $tag = 0;

        for ($i=$start_position, $n=sizeof($serialization_data); $i<$n; $i++) {
          if ($serialization_data[$i] == '{') {
            $tag++;
          } elseif ($serialization_data[$i] == '}') {
            $tag--;
          } elseif ($tag < 1) {
            break;
          }
        }

        $serialized_variable = substr($serialization_data, strpos($serialization_data, '|', $start_position) + 1, $i - strpos($serialization_data, '|', $start_position) - 1);
        break;
    }

    return $serialized_variable;
  }


  function oos_prepare_input($string) {
    if (is_array ($string))  return $string;

    if (get_magic_quotes_gpc()) {
      $string = stripslashes($string);
    }
    $string = trim($string);

    return $string;
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
   * Mail function (uses phpMailer)
   */
  function oos_mail($to_name, $to_email_address, $email_subject, $email_text, $from_email_name, $from_email_address) {


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

