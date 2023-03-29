<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: general.php,v 1.151 2003/02/07 21:46:49 dgw_
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

function oos_admin_check_login()
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aContents = oos_get_content();

    if (!isset($_SESSION['login_id'])) {
        oos_redirect_admin(oos_href_link_admin($aContents['login'], ''));
    } else {
        $filename = preg_split('/\?/', basename($_SERVER['SCRIPT_NAME']));
        $filename = $filename[0];
        $page_key = array_search($filename, $aContents);

        if ($filename != $aContents['default'] && $filename != $aContents['forbiden'] && $filename != $aContents['logoff'] && $filename != $aContents['admin_account']  && $filename != $aContents['packingslip'] && $filename != $aContents['invoice'] && $filename != $aContents['edit_orders']) {
            $admin_filestable = $oostable['admin_files'];
            $query = "SELECT admin_files_name
                  FROM $admin_filestable
                  WHERE FIND_IN_SET( '" . intval($_SESSION['login_groups_id']) . "', admin_groups_id)
                    AND admin_files_name = '" . oos_db_input($page_key) . "'";
            $result = $dbconn->Execute($query);

            if (!$result->RecordCount()) {
                oos_redirect_admin(oos_href_link_admin($aContents['forbiden']));
            }
        }
    }
}


function oos_check_is_access_protected()
{
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        return 1;
    }

    $rc = false;
    $url = OOS_HTTPS_SERVER . OOS_SHOP . OOS_ADMIN;
    $headers = get_headers($url);
    if (is_array($headers) && count($headers) > 0) {
        $rc = (preg_match('/\s+(?:401|403)\s+/', $headers[0])) ? 1 : 0;
    }
    return $rc;
}


function oos_admin_check_boxes($filename, $boxes ='')
{

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
              WHERE FIND_IN_SET( '" . intval($_SESSION['login_groups_id']) . "', admin_groups_id)
                AND admin_files_is_boxes = '" . intval($is_boxes) . "'
                AND admin_files_name = '" . oos_db_input($filename) . "'";
    $result = $dbconn->Execute($query);

    $return_value = false;
    if ($result->RecordCount()) {
        $return_value = true;
    }

    return $return_value;
}


function oos_admin_files_boxes($filename, $parameters)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aContents = oos_get_content();

    $admin_filestable = $oostable['admin_files'];
    $query = "SELECT admin_files_name
              FROM $admin_filestable
              WHERE FIND_IN_SET( '" . intval($_SESSION['login_groups_id']) . "', admin_groups_id)
                AND admin_files_is_boxes = '0'
                AND admin_files_name = '" . oos_db_input($filename) . "'";
    $result = $dbconn->Execute($query);

    if ($result->RecordCount()) {
        return oos_href_link_admin($aContents[$filename], $parameters);
    }

    return;
}


/**
 * Redirect to another page or site
 *
 * @param $url
 */
function oos_redirect_admin($url)
{
    if ((strstr($url, "\n") != false) || (strstr($url, "\r") != false)) {
        $aContents = oos_get_content();
        oos_redirect_admin(oos_href_link_admin($aContents['default'], '', false));
    }

    if (strpos($url, '&amp;') !== false) {
        $url = str_replace('&amp;', '&', $url);
    }

    header('Location: ' . $url);

    exit;
}


function oos_customers_name($customers_id)
{
    $sName = '';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $customerstable = $oostable['customers'];
    $query = "SELECT customers_firstname, customers_lastname
              FROM $customerstable
              WHERE customers_id = '" . intval($customers_id) . "'";
    $result = $dbconn->Execute($query);

    $firstname = isset($result->fields['customers_firstname']) ? $result->fields['customers_firstname'] : '';
    $lastname = isset($result->fields['customers_lastname']) ? $result->fields['customers_lastname'] : '';

    $sName = $firstname . ' ' . $lastname;

    return $sName;
}


function oos_get_all_get_params($exclude_array = '')
{
    global $session;

    if ($exclude_array == '') {
        $exclude_array = [];
    }

    $get_url = '';

    reset($_GET);
    foreach ($_GET as $key => $value) {
        if (($key != $session->getName()) && ($key != 'error') && (!oos_in_array($key, $exclude_array))) {
            $get_url .= $key . '=' . $value . '&';
        }
    }

    return $get_url;
}

 /**
  * ready operating system output
  * <br>
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
    static $search = array('!\.\./!si', // .. (directory traversal)
                           '!^.*://!si', // .*:// (start of URL)
                           '!/!si',     // Forward slash (directory traversal)
                           '!\\\\!si'); // Backslash (directory traversal)

    static $replace = array('',
                            '',
                            '_',
                            '_');

    $resarray = [];
    foreach (func_get_args() as $ourvar) {
        // Parse out bad things
        $ourvar = preg_replace($search, $replace, $ourvar);

        // Prepare var
        $ourvar = oos_sanitize_string($ourvar);


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


function oos_get_content()
{
    global $aContents;

    return $aContents;
}


function oos_datetime_short($raw_datetime)
{
    if (($raw_datetime == '0000-00-00 00:00:00') || ($raw_datetime == '')) {
        return false;
    }

    $locale = THE_LOCALE;
    $dateType = IntlDateFormatter::LONG; //type of date formatting
    $timeType = IntlDateFormatter::SHORT; //type of time formatting setting to none, will give you date itself
    $formatter = new IntlDateFormatter($locale, $dateType, $timeType);
    $dateTime = new DateTime($raw_datetime);

    return $formatter->format($dateTime);
}



function oos_in_array($lookup_value, $lookup_array)
{
    if (in_array($lookup_value, $lookup_array)) {
        return true;
    }

    return false;
}



function oos_break_string($string, $len, $break_char = '-')
{
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


function oos_browser_detect($component)
{
    return stristr($_SERVER['HTTP_USER_AGENT'], $component);
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
        $aTranslate = array('"' => '&quot;');
    }

    return strtr(trim((string) $sStr), $aTranslate);
}


function oos_address_format($address_format_id, $address, $html, $boln, $eoln)
{
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $address_formattable = $oostable['address_format'];
    $query = "SELECT address_format as format
              FROM $address_formattable
              WHERE address_format_id = '" . intval($address_format_id) . "'";
    $result = $dbconn->Execute($query);

    $address_format = $result->fields;

    $company = (isset($address['company'])) ? addslashes($address['company']) : '';
    $firstname = (isset($address['firstname'])) ? addslashes($address['firstname']) : '';
    $lastname = (isset($address['lastname'])) ? addslashes($address['lastname']) : '';
    $street = (isset($address['street_address'])) ? addslashes($address['street_address']) : '';
    $city = (isset($address['city'])) ? addslashes($address['city']) : '';
    $state = (isset($address['state'])) ? addslashes($address['state']) : '';
    $country_id = (isset($address['country_id'])) ? addslashes($address['country_id']) : '';
    $zone_id = (isset($address['zone_id'])) ? addslashes($address['zone_id']) : '';
    $postcode = (isset($address['postcode'])) ? addslashes($address['postcode']) : '';
    $zip = $postcode;
    $country = oos_get_country_name($country_id);
    $state = oos_get_zone_code($country_id, $zone_id, $state);

    if ($html) {
        // HTML Mode
        $HR = '<hr>';
        $hr = '<hr>';
        if (($boln == '') && ($eoln == "\n")) { // Values not specified, use rational defaults
            $CR = '<br>';
            $cr = '<br>';
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
    if ($firstname == '') {
        $firstname = addslashes($address['name']);
    }
    if ($country == '') {
        $country = addslashes($address['country']);
    }
    if ($state != '') {
        $statecomma = $state . ', ';
    }

    $fmt = $address_format['format'];
    eval("\$address = \"$fmt\";");
    $address = stripslashes($address);

    if ((ACCOUNT_COMPANY == 'true') && (oos_is_not_null($company))) {
        $address = $company . $cr . $address;
    }

    return $boln . $address . $eoln;
}


function oos_get_zone_code($country, $zone, $def_state)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonestable = $oostable['zones'];
    $query = "SELECT zone_code
              FROM $zonestable
              WHERE zone_country_id = '" . intval($country) . "'
                AND zone_id = '" . intval($zone) . "'";
    $result = $dbconn->Execute($query);
    $state_prov_code = isset($result->fields['zone_code']) ? $result->fields['zone_code'] : $def_state;

    return $state_prov_code;
}


function oos_get_country_name($country_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countriestable = $oostable['countries'];
    $query = "SELECT countries_name
              FROM $countriestable
              WHERE countries_id = '" . $country_id . "'";
    $result = $dbconn->Execute($query);

    $countries_name = isset($result->fields['countries_name']) ? $result->fields['countries_name'] : $country_id;
    return $countries_name;
}


function oos_get_uprid($prid, $params)
{
    $uprid = $prid;
    if ((is_array($params)) && (!strstr($prid, '{'))) {
        foreach ($params as $option => $value) {
            $uprid = $uprid . '{' . $option . '}' . $value;
        }
    }
    return $uprid;
}

function oos_get_prid($uprid)
{
    $pieces = explode('{', $uprid);

    return $pieces[0];
}


function oos_get_languages()
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $aLanguages = [];

    $languagestable = $oostable['languages'];
    $query = "SELECT languages_id, name, iso_639_2, iso_639_1, iso_3166_1
              FROM $languagestable
              WHERE status = '1'
              ORDER BY sort_order";
    $result = $dbconn->Execute($query);

    while ($languages = $result->fields) {
        $aLanguages[] = array('id' => $languages['languages_id'],
                                    'name' => $languages['name'],
                                    'iso_639_2' => $languages['iso_639_2'],
                                    'iso_639_1' => $languages['iso_639_1'],
                                    'iso_3166_1' => $languages['iso_3166_1']
                                );

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return $aLanguages;
}

 /**
  * Return Products Name
  *
  * @param  $product_id
  * @param  $language
  * @return string
  */
function oos_get_products_name($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_name
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_name = isset($result->fields['products_name']) ? $result->fields['products_name'] : '';

    return $products_name;
}


 /**
  * Return Products Page Title for SEO
  *
  * @param  $product_id
  * @param  $language
  * @return string
  */
function oos_get_products_title($product_id, $language_id = '')
{
    if (empty($language_id) || !is_numeric($language_id)) {
        $language_id = intval($_SESSION['language_id']);
    }

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $products_descriptiontable = $oostable['products_description'];
    $query = "SELECT products_title
              FROM $products_descriptiontable
              WHERE products_id = '" . intval($product_id) . "'
                AND products_languages_id = '" . intval($language_id) . "'";
    $result = $dbconn->Execute($query);

    $products_title = isset($result->fields['products_title']) ? $result->fields['products_title'] : '';

    return $products_title;
}



function oos_get_countries($default = '')
{
    $countries_array = [];
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

    return $countries_array;
}


function oos_get_country_zones($country_id)
{
    $zones_array = [];

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $zonestable = $oostable['zones'];
    $query = "SELECT zone_id, zone_name
              FROM $zonestable
              WHERE zone_country_id = '" . intval($country_id) . "'
              ORDER BY zone_name";
    $result = $dbconn->Execute($query);

    while ($zones = $result->fields) {
        $zones_array[] = array('id' => $zones['zone_id'],
                             'text' => $zones['zone_name']);

        // Move that ADOdb pointer!
        $result->MoveNext();
    }

    return $zones_array;
}


function oos_prepare_country_zones_pull_down($country_id = '')
{
    // preset the width of the drop-down for Netscape
    $pre = '';
    if ((!oos_browser_detect('MSIE')) && (oos_browser_detect('Mozilla/4'))) {
        for ($i=0; $i<45; $i++) {
            $pre .= '&nbsp;';
        }
    }

    $zones = oos_get_country_zones($country_id);

    if (count($zones) > 0) {
        $zones_select = array(array('id' => '', 'text' => PLEASE_SELECT));
        $zones = array_merge($zones_select, $zones);
    } else {
        $zones = array(array('id' => '', 'text' => TYPE_BELOW));
        // create dummy options for Netscape to preset the height of the drop-down
        if ((!oos_browser_detect('MSIE')) && (oos_browser_detect('Mozilla/4'))) {
            for ($i=0; $i<9; $i++) {
                $zones[] = array('id' => '', 'text' => $pre);
            }
        }
    }

    return $zones;
}


function oos_get_uploaded_file($filename)
{
    if (isset($_FILES[$filename])) {
        $uploaded_file = array('name' => $_FILES[$filename]['name'],
                             'type' => $_FILES[$filename]['type'],
                             'size' => $_FILES[$filename]['size'],
                             'tmp_name' => $_FILES[$filename]['tmp_name']);
    }

    return $uploaded_file;
}


function oos_get_copy_uploaded_file($filename, $target)
{
    if (substr($target, -1) != '/') {
        $target .= '/';
    }

    $target .= $filename['name'];

    move_uploaded_file($filename['tmp_name'], $target);
    @chmod($target, 0644);
}



function oos_remove_product($product_id)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $product_image_query = "SELECT products_image
                            FROM $productstable
                            WHERE products_id = '" . intval($product_id) . "'";
    $product_image_result = $dbconn->Execute($product_image_query);
    $product_image = $product_image_result->fields;

    $orders_productstable = $oostable['orders_products'];
    $orders_image_query = "SELECT products_id
                      FROM $orders_productstable
                       WHERE products_image = '" . oos_db_input($product_image['products_image']) . "'";
    $orders_result = $dbconn->Execute($orders_image_query);
    if (!$orders_result->RecordCount()) {
        $productstable = $oostable['products'];
        $duplicate_query = "SELECT COUNT(*) AS total
                        FROM $productstable
                        WHERE products_image = '" . oos_db_input($product_image['products_image']) . "'";
        $duplicate_result = $dbconn->Execute($duplicate_query);
        $duplicate_image = $duplicate_result->fields;

        if ($duplicate_image['total'] < 2) {
            if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' . $product_image['products_image'])) {
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/large/' . $product_image['products_image']);
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium/' . $product_image['products_image']);
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium_large/' . $product_image['products_image']);
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/small/' . $product_image['products_image']);
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/min/' . $product_image['products_image']);
                @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' . $product_image['products_image']);
            }
        }

        $products_imagestable = $oostable['products_images'];
        $product_image_query = "SELECT image_name
								FROM $products_imagestable
								WHERE products_id = '" . intval($product_id) . "'";
        $products_image_result = $dbconn->Execute($product_image_query);
        while ($product_image = $products_image_result->fields) {
            $duplicate_query = "SELECT COUNT(*) AS total
								FROM $products_imagestable
                              WHERE image_name = '" . oos_db_input($product_image['image_name']) . "'";
            $duplicate_image_result = $dbconn->Execute($duplicate_query);
            $duplicate_image = $duplicate_image_result->fields;

            if ($duplicate_image['total'] < 2) {
                if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' . $product_image['image_name'])) {
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/large/' . $product_image['image_name']);
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium/' . $product_image['image_name']);
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/medium_large/' . $product_image['image_name']);
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/small/' . $product_image['image_name']);
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/min/' . $product_image['image_name']);
                    @unlink(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/originals/' . $product_image['image_name']);
                }
            }
            // Move that ADOdb pointer!
            $products_image_result->MoveNext();
        }
    }

    $dbconn->Execute("DELETE FROM " . $oostable['specials'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_description'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_basket_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['customers_wishlist_attributes'] . " WHERE products_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE master_id = '" . intval($product_id) . "' OR slave_id = '" . intval($product_id) . "'");
    $dbconn->Execute("DELETE FROM " . $oostable['products_images'] . " WHERE products_id = '" . intval($product_id) . "'");

    $categories_panorama_scene_hotspot = $oostable['categories_panorama_scene_hotspot'];
    $hotspot_query = "SELECT hotspot_id
						FROM $categories_panorama_scene_hotspot
						WHERE products_id = '" . intval($product_id) . "'";
    $hotspot_result = $dbconn->Execute($hotspot_query);

    while ($hotspot = $hotspot_result->fields) {
        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot_text'] . " WHERE hotspot_id = '" . intval($hotspot['hotspot_id']) . "'");

        // Move that ADOdb pointer!
        $hotspot_result->MoveNext();
    }

    $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot'] . " WHERE products_id = '" . intval($product_id) . "'");

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

    $dbconn->Execute("DELETE FROM " . $oostable['reviews'] . " WHERE products_id = '" . intval($product_id) . "'");
}


function oos_class_exits($class_name)
{
    if (function_exists('class_exists')) {
        return class_exists($class_name);
    } else {
        return true;
    }
}


function oos_remove($source)
{
    global $messageStack, $oos_remove_error;

    if (isset($oos_remove_error)) {
        $oos_remove_error = false;
    }

    if (is_dir($source)) {
        $dir = dir($source);
        while ($file = $dir->read()) {
            if (($file != '.') && ($file != '..')) {
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


/*
 * Creates a clean floating point number without taking into account thousand separators, currency or other characters.
 *
 * brewal dot renault at gmail dot com
 * https://www.php.net/manual/de/function.floatval.php
*/
function oos_tofloat($num)
{
    $dotPos = strrpos($num, '.');
    $commaPos = strrpos($num, ',');
    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos :
        ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

    if (!$sep) {
        return floatval(preg_replace("/[^0-9]/", "", $num));
    }

    return floatval(
        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
    );
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


function oos_get_tax_rate_value($class_id)
{
	
	echo 'ja';
    return oos_get_tax_rate($class_id, -1, -1);
}


function oos_display_tax_value($value, $padding = TAX_DECIMAL_PLACES)
{
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


function oos_add_tax($price, $tax)
{
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
function oos_get_tax_rate($class_id, $country_id = -1, $zone_id = -1)
{
    if (($country_id == -1) && ($zone_id == -1)) {
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
                  OR za.zone_country_id = '" . intval($country_id) . "')
                AND (za.zone_id is null OR za.zone_id = '0'
                  OR za.zone_id = '" . intval($zone_id) . "')
                AND tr.tax_class_id = '" . intval($class_id) . "'";
    $result = $dbconn->Execute($query);

/*
echo 'Ralf';
SELECT SUM(tax_rate) AS tax_rate
              FROM dwq_tax_rates tr LEFT JOIN
                   dwq_zones_to_geo_zones za
                 ON (tr.tax_zone_id = za.geo_zone_id) LEFT JOIN
                   dwq_geo_zones tz
                 ON (tz.geo_zone_id = tr.tax_zone_id)
              WHERE (za.zone_country_id IS null or za.zone_country_id = '0'
                  OR za.zone_country_id = '81')
                AND (za.zone_id is null OR za.zone_id = '0'
                  OR za.zone_id = '88')
                AND tr.tax_class_id = '2'


Ergebnis  tax_rate = 105 ?!
echo $query;
print_r($result);
exit;
*/
    if ($result->RecordCount()) {
        $tax_multiplier = 0;
        while ($tax = $result->fields) {
            $tax_multiplier += $tax['tax_rate'];

            // Move that ADOdb pointer!
            $result->MoveNext();
        }

        return $tax_multiplier;
    } else {
        return 0;
    }
}


function oos_calculate_tax($price, $tax)
{
    global $currencies;

    return round($price * $tax / 100, $currencies->currencies[DEFAULT_CURRENCY]['decimal_places']);
}


function oos_call_function($function, $parameter, $object = '')
{
    if ($object == '') {
        return call_user_func($function, $parameter);
    } else {
        return call_user_func(array($object, $function), $parameter);
    }
}


function oos_get_serialized_variable(&$serialization_data, $variable_name, $variable_type = 'string')
{
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


function oos_prepare_input($sStr)
{
    if (is_string($sStr)) {
        return trim((string) oos_sanitize_string(stripslashes($sStr)));
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
    $aPatterns = array('/ +/','/[<>]/');
    $aReplace = array(' ', '_');
    return preg_replace($aPatterns, $aReplace, trim((string) $sStr));
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


function oos_strtolower($sStr)
{
    $sStr = strtolower($sStr);
    // Strip non-alpha & non-numeric except ._-:
    return preg_replace("/[^[:alnum:]]/", "", $sStr);
}

function oos_strtoupper($sStr)
{
    $sStr = strtoupper($sStr);
    // Strip non-alpha & non-numeric except ._-:
    return preg_replace("/[^[:alnum:]]/", "", $sStr);
}

function oos_set_review_status($reviews_id, $status)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $reviewstable = $oostable['reviews'];
    $query = "UPDATE $reviewstable
              SET reviews_status = '" . intval($status) . "'
              WHERE reviews_id = '" . intval($reviews_id) . "'";
    $result = $dbconn->Execute($query);

    return;
}


/**
 * Parses a byte size from a size value (eg: 100M) for comparison.
 */
function parse_size($size)
{
    $suffixes = array(
                    ''     => 1,
                    'k'     => 1024,
                    'm'     => 1048576, // 1024 * 1024
                    'g'     => 1073741824, // 1024 * 1024 * 1024
    );
    if (preg_match('/([0-9]+)\s*(k|m|g)?(b?(ytes?)?)/i', $size, $match)) {
        return $match[1] * $suffixes[strtolower($match[2])];
    }
}



/**
 * Checks for a zip file
 *
 * @param  string $filename name of the file
 * @return bool
 */
function is_zip($filename)
{
    $ext = oos_get_suffix($filename);
    return ($ext == "zip");
}

/**
 * Checking the file extension
 *
 * @param  string $filename name of the file
 * @return bool
 */
function is_image($filename)
{
    $ext = oos_get_suffix($filename);
    $allowed_extensions = array('png', 'jpg', 'jpeg', 'gif', 'webp');

    return in_array($ext, $allowed_extensions);
}



/**
 * Mail function (uses phpMailer)
 */
function oos_mail($to_name, $to_email_address, $email_subject, $email_text, $email_html, $from_email_name, $from_email_address, $attachments = array())
{
    global $phpmailer;

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

    if (!is_array($attachments)) {
        $attachments = explode("\n", str_replace("\r\n", "\n", $attachments));
    }

    $sLang = (isset($_SESSION['iso_639_1']) ? $_SESSION['iso_639_1'] : DEFAULT_LANGUAGE_CODE);

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
