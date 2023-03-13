<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.26 2002/08/06 14:48:54 hpdl
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
  * The HTML href link wrapper function
  *
  * @param  $page
  * @param  $parameters
  * @param  $connection
  * @return string
  */
function oos_href_link_admin($page = '', $parameters = '', $connection = 'SSL', $add_session_id = true)
{
    $page = oos_output_string($page);

    if ($page == '') {
        die('<div class="alert alert-danger" role="alert"><strong>Error!</strong> Unable to determine the page link!<br><br>Function used:<br><br>oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
    }
    $link = OOS_HTTPS_SERVER . OOS_SHOP . OOS_ADMIN;

    if (oos_is_not_null($parameters)) {
        $link = $link . $page . '?' . oos_output_string($parameters) . '&' . SID;
    } else {
        $link = $link . $page . '?' . SID;
    }


    while ((substr($link, -1) == '&') || (substr($link, -1) == '?')) {
        $link = substr($link, 0, -1);
    }

    return $link;
}


 /**
  * The HTML catalog href link wrapper function
  *
  * @param  $modul
  * @param  $page
  * @param  $parameters
  * @param  $connection
  * @return string
  */
function oos_catalog_link($page = '', $parameters = '')
{
    $page = oos_output_string($page);

    if ($page == '') {
        die('<div class="alert alert-danger" role="alert"><strong>Error!</strong> Unable to determine the page link!<br><br>Function used:<br><br>oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
    }

    $link = OOS_HTTPS_SERVER . OOS_SHOP;

    if ($parameters == '') {
        $link .= 'index.php?content=' . $page;
    } else {
        $link .= 'index.php?content=' . $page . '&' . oos_output_string($parameters);
    }


    while ((substr($link, -1) == '&') || (substr($link, -1) == '?')) {
        $link = substr($link, 0, -1);
    }

    return $link;
}


 /**
  * The HTML catalog href link wrapper function
  *
  * @param  $src
  * @param  $alt
  * @param  $width
  * @param  $height
  * @param  $params
  * @return string
  */
function oos_image($src, $alt = '', $width = '', $height = '', $params = '')
{
    $image = '<img src="' . oos_output_string($src) . '" border="0" alt="' . oos_output_string($alt) . '"';

    if (oos_is_not_null($alt)) {
        $image .= ' title="' . oos_output_string($alt) . '"';
    }

    if (oos_is_not_null($width) && oos_is_not_null($height)) {
        $image .= ' width="' . oos_output_string($width) . '" height="' . oos_output_string($height) . '"';
    }

    if (oos_is_not_null($params)) {
        $image .= ' ' . $params;
    }

    $image .= '>';

    return $image;
}



function product_info_image($image, $alt, $type = 'medium', $width = '', $height = '')
{
    if (($image) && (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'product/' .  $type . '/' . $image))) {
        $image = oos_image(OOS_SHOP_IMAGES . 'product/' .  $type . '/' . $image, $alt, $width, $height);
    } else {
        $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
}


function oos_info_image($image, $alt, $width = '', $height = '')
{
    if (($image) && (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . $image))) {
        $image = oos_image(OOS_SHOP_IMAGES . $image, $alt, $width, $height);
    } else {
        $image = TEXT_IMAGE_NONEXISTENT;
    }

    return $image;
}


 /**
  * Draw a 1 pixel black line
  */
function oos_black_line()
{
    return oos_image(OOS_IMAGES . 'pixel_black.gif', '', '100%', '1');
}


/**
 * Output a function button in the selected language
 *
 * @param  $title
 * @return string
 */
function oos_button($title = '')
{
    return '<button class="btn btn-sm btn-primary mb-20"><strong>' . $title . '</strong></button>';
}

/**
 * Outputs a submit button
 *
 * @param  $title
 * @return string
 */
function oos_submit_button($title = '')
{
    return '<button class="btn btn-sm btn-success mb-20" type="submit"><strong><i class="fa fa-check-circle"></i> ' . $title . '</strong></button>';
}


/**
 * Outputs a submit button
 *
 * @param  $title
 * @return string
 */
function oos_preview_button($title = '', $value = '')
{
    return '<button class="btn btn-sm btn-success mb-20" type="submit" name="preview" value="' . $value . '"><strong>' . $title . '</strong></button>';
}

/**
 * Outputs a submit button
 *
 * @param  $title
 * @return string
 */
function oos_cancel_button($title = '', $value = '')
{
    return '<button class="btn btn-sm btn-warning mb-20" type="submit" name="back" value="' . $value . '"><strong>' . $title . '</strong></button>';
}



/**
 * Outputs a reset button
 *
 * @param  $title
 * @return string
 */
function oos_reset_button($title = '')
{
    return '<button class="btn btn-sm btn-primary mb-20" type="reset"><strong><i class="fa fa-plus-circle"></i> ' . $title . '</strong></button>';
}

/**
 * Outputs a back button
 *
 * @param  $title
 * @return string
 */
function oos_back_button($title = '')
{
    return '<button class="btn btn-sm btn-primary mb-20"><strong><i class="fa fa-chevron-left"></i> ' . $title . '</strong></button>';
}

 /**
  * javascript to dynamically update the states/provinces list when the country is changed
  *
  * @param  $country
  * @param  $form
  * @param  $field
  * @return string
  */
function oos_is_zone_list($country, $form, $field)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $countries_query = "SELECT distinct zone_country_id
                        FROM " . $oostable['zones'] . "
                        ORDER BY zone_country_id";
    $countries_result = $dbconn->Execute($countries_query);
    $num_country = 1;
    $output_string = '';
    while ($countries = $countries_result->fields) {
        if ($num_country == 1) {
            $output_string .= '  if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
        } else {
            $output_string .= '  } else if (' . $country . ' == "' . $countries['zone_country_id'] . '") {' . "\n";
        }

        $states_query = "SELECT zone_name, zone_id
                       FROM " . $oostable['zones'] . "
                       WHERE zone_country_id = '" . $countries['zone_country_id'] . "'
                       ORDER BY zone_name";
        $states_result = $dbconn->Execute($states_query);

        $num_state = 1;
        while ($states = $states_result->fields) {
            if ($num_state == '1') {
                $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
            }
            $output_string .= '    ' . $form . '.' . $field . '.options[' . $num_state . '] = new Option("' . $states['zone_name'] . '", "' . $states['zone_id'] . '");' . "\n";
            $num_state++;

            // Move that ADOdb pointer!
            $states_result->MoveNext();
        }
        $num_country++;

        // Move that ADOdb pointer!
        $countries_result->MoveNext();
    }

    $output_string .= '  } else {' . "\n" .
                      '    ' . $form . '.' . $field . '.options[0] = new Option("' . TYPE_BELOW . '", "");' . "\n" .
                      '  }' . "\n";

    return $output_string;
}


 /**
  * Output a form
  *
  * @param  $name
  * @param  $action
  * @param  $parameters
  * @param  $method
  * @param  $params
  * @return string
  */
function oos_draw_form($id, $name, $action, $parameters = '', $method = 'post', $parsley_validate = true, $params = '')
{
	
    $form = '<form name="' . oos_output_string($name) . '" action="';
    if (oos_is_not_null($parameters)) {
        $form .= oos_href_link_admin($action, $parameters);
    } else {
        $form .= oos_href_link_admin($action);
    }
    $form .= '" method="' . oos_output_string($method) . '"';

    if ($parsley_validate == true) {
        $form .= ' data-parsley-validate ';
    }

    if (oos_is_not_null($params)) {
        $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
}


 /**
  * Output a form input field
  *
  * @param  $name
  * @param  $value
  * @param  $parameters
  * @param  $required
  * @param  $type
  * @param  $reinsert_value
  * @param  $placeholder
  * @return string
  */
function oos_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true, $disabled = false, $placeholder = '')
{
    $field = '<input class="form-control" type="' . $type . '" name="' . $name . '"';

    if (($reinsert_value == true) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $value = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $value = stripslashes($_POST[$name]);
        }
    }
    $field .= ' value="' . oos_output_string($value) . '"';

    if (oos_is_not_null($parameters)) {
        $field .= ' ' . $parameters;
    }

    if ($disabled == true) {
        $field .= ' disabled="disabled"';
    }

    if (oos_is_not_null($placeholder)) {
        $field .= ' placeholder="' . oos_output_string($placeholder) . '"';
    }

    if ($required) {
        $field .= ' required';
    }

    $field .= ' />';

    // if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
}


 /**
  * Output a form password field
  *
  * @param  $name
  * @param  $value
  * @param  $required
  * @return string
  */
function oos_draw_password_field($name, $value = '', $parameters = 'maxlength="40"', $required = false)
{
    $field = oos_draw_input_field($name, $value, $parameters, $required, 'password', false);

    return $field;
}


 /**
  * Output a form filefield
  *
  * @param  $name
  * @param  $required
  * @return string
  */
function oos_draw_file_field($name, $required = false)
{
    $field = '<div class="fileinput fileinput-new" data-provides="fileinput">' . "\n" .
    '<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>' . "\n" .
    '<div>' . "\n" .

    '<span class="btn btn-warning btn-file"><span class="fileinput-new"><em class="fa fa-plus-circle fa-fw"></em>' . BUTTON_SELECT_IMAGE . '</span><span class="fileinput-exists">' . BUTTON_CHANGE . '</span>' . "\n" .
    '<input type="file" size="40" name="' . $name . '"></span>' . "\n" .
    '<a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"><em class="fa fa-times-circle fa-fw"></em>' . BUTTON_DELETE . '</a>' . "\n" .
    '</div>' . "\n" .
    '</div>';

    return $field;
}


 /**
  * Output a selection field - alias function for oos_draw_checkbox_field() and oos_draw_radio_field()
  *
  * @param  $name
  * @param  $type
  * @param  $value
  * @param  $checked
  * @param  $compare
  * @param  $parameter
  * @return string
  */
function oos_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '')
{
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') {
        $selection .= ' value="' . $value . '"';
    }
    if (($checked == true) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value))) || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value))) || (oos_is_not_null($compare) && ($value == $compare))) {
        $selection .= ' checked="checked"';
    }
    if ($parameter != '') {
        $selection .= ' ' . $parameter;
    }
    $selection .= '>';

    return $selection;
}


 /**
  * Output a form checkbox field
  *
  * @param  $name
  * @param  $value
  * @param  $checked
  * @param  $compare
  * @param  $parameter
  * @return string
  */
function oos_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '')
{
    return oos_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
}


 /**
  * Output a form radio field
  *
  * @param  $name
  * @param  $value
  * @param  $checked
  * @param  $compare
  * @param  $parameter
  * @return string
  */
function oos_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '')
{
    return oos_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
}


 /**
  * Output a form textarea field
  *
  * @param  $name
  * @param  $wrap
  * @param  $width
  * @param  $height
  * @param  $text
  * @param  $params
  * @param  $reinsert_value
  * @return string
  */
function oos_draw_textarea_field($name, $wrap, $width, $height, $text = '', $params = '', $reinsert_value = true)
{
    $field = '<textarea class="form-control" name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '"';

    if (oos_is_not_null($params)) {
        $field .= ' ' . $params;
    }

    $field .= '>';

    if (($reinsert_value == true) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $text = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $text = stripslashes($_POST[$name]);
        }
    }

    if (oos_is_not_null($text)) {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $clean_html = $purifier->purify($text);
        $field .= $clean_html;
    }

    $field .= '</textarea>';


    return $field;
}

 /**
  * Output a form textarea field
  *
  * @param  $name
  * @param  $wrap
  * @param  $width
  * @param  $height
  * @param  $text
  * @param  $params
  * @param  $reinsert_value
  * @return string
  */
function oos_draw_editor_field($name, $wrap, $width, $height, $text = '', $params = '', $reinsert_value = true)
{
    $field = '<textarea name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '"';
    if (oos_is_not_null($params)) {
        $field .= ' ' . $params;
    }

    $field .= '>';

    if (($reinsert_value == true) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $field .= htmlspecialchars(stripslashes($_GET[$name]), ENT_QUOTES, 'UTF-8');
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $field .= htmlspecialchars(stripslashes($_POST[$name]), ENT_QUOTES, 'UTF-8');
        }
    } elseif (oos_is_not_null($text)) {
        $field .= htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
    $field .= '</textarea>';

    return $field;
}

 /**
  * Output a form hidden field
  *
  * @param  $name
  * @param  $value
  * @return string
  */
function oos_draw_hidden_field($name, $value = '')
{
    $field = '<input type="hidden" name="' . $name . '"';

    if (oos_is_not_null($value)) {
        $field .= ' value="' . oos_output_string($value) . '"';
    } elseif ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name]))) {
        if ((isset($_GET[$name]) && is_string($_GET[$name]))) {
            $field .= ' value="' . oos_output_string(stripslashes($_GET[$name])) . '"';
        } elseif ((isset($_POST[$name]) && is_string($_POST[$name]))) {
            $field .= ' value="' . oos_output_string(stripslashes($_POST[$name])) . '"';
        }
    }

    $field .= '>';

    return $field;
}

 /**
  * Hide form elements
  */
function oos_hide_session_id()
{
    if (defined('SID') && oos_is_not_null(SID)) {
        return oos_draw_hidden_field(oos_session_name(), oos_session_id());
    }
}


 /**
  * Output a login form
  *
  * @param  $name
  * @param  $modul
  * @param  $page
  * @param  $parameters
  * @param  $method
  * @param  $params
  * @return string
  */
function oos_draw_login_form($name, $page, $parameters = '', $method = 'post', $params = '')
{
    $loginform = '<form name="' . oos_output_string($name) . '" action="';
    if (oos_is_not_null($parameters)) {
        $loginform .= oos_catalog_link($page, $parameters);
    } else {
        $loginform .= oos_catalog_link($page);
    }
    $loginform .= '" method="' . oos_output_string($method) . '"';

    if (oos_is_not_null($params)) {
        $loginform .= ' ' . $params;
    }
    $loginform .= '>';

    return $loginform;
}


 /**
  * Output a form pull down menu
  *
  * @param  $name
  * @param  $values
  * @param  $default
  * @param  $params
  * @param  $required
  * @return string
  */
function oos_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false)
{
    $field = '<select class="form-control" name="' . $name . '"';
    if ($params) {
        $field .= ' ' . $params;
    }
    $field .= '>';

    if (empty($default) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $default = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $default = stripslashes($_POST[$name]);
        }
    }

    for ($i=0, $n=count($values); $i<$n; $i++) {
        $field .= '<option value="' . $values[$i]['id'] . '"';
        if ($default == $values[$i]['id']) {
            $field .= ' selected="selected"';
        }

        $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';

    if ($required) {
        $field .= TEXT_FIELD_REQUIRED;
    }

    return $field;
}


 /**
  * Output a form pull down menu
  *
  * @param  $name
  * @param  $values
  * @param  $default
  * @param  $params
  * @param  $required
  * @return string
  */
function oos_draw_select_menu($name, $values, $default = '', $params = '', $required = false)
{
    $field = '<select class="form-control" name="' . $name . '"';
    if ($params) {
        $field .= ' ' . $params;
    }
    $field .= '>';

    if (empty($default) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $default = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $default = stripslashes($_POST[$name]);
        }
    }

    for ($i=0, $n=count($values); $i<$n; $i++) {
        $field .= '<option value="' . $values[$i] . '"';
        if ($default == $values[$i]) {
            $field .= ' selected="selected"';
        }

        $field .= '>' . $values[$i] . '</option>';
    }
    $field .= '</select>';

    if ($required) {
        $field .= TEXT_FIELD_REQUIRED;
    }

    return $field;
}





 /**
  * Output a form pull down menu
  *
  * @param  $name
  * @param  $values
  * @param  $default
  * @param  $params
  * @param  $required
  * @return string
  */
function oos_draw_extensions_menu($name, $values, $default = '', $params = '', $required = false)
{
    $field = '<select class="form-control" name="' . $name . '"';
    if ($params) {
        $field .= ' ' . $params;
    }
    $field .= '>';

    if (empty($default) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $default = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $default = stripslashes($_POST[$name]);
        }
    }

    for ($i=0, $n=count($values); $i<$n; $i++) {
        $field .= '<option value="' . $values[$i] . '"';
        if ($default == $values[$i]) {
            $field .= ' selected="selected"';
        }

        $field .= '>' . $values[$i] . '</option>';
    }
    $field .= '</select>';

    if ($required) {
        $field .= TEXT_FIELD_REQUIRED;
    }

    return $field;
}




/**
 * Output a form pull down menu
 *
 * @param  $name
 * @param  $exclude
 * @return string
 */
function oos_draw_products_pull_down($name, $exclude, $default = null, $id = 1)
{
    global $currencies;

    if (!is_array($exclude)) {
        $exclude = [];
    }

    $select_string = '<select class="form-control" id="select2-' . $id . '" name="' . $name . '">';


    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_price FROM $productstable p, $products_descriptiontable pd WHERE p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_name");
    while ($products = $products_result->fields) {
        if (!oos_in_array($products['products_id'], $exclude)) {
            $select_string .= '<option value="' . $products['products_id'] . '"';
            if ($default == $products['products_id']) {
                $select_string .= ' selected="selected"';
            }
            $select_string .= '>' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
        }

        // Move that ADOdb pointer!
        $products_result->MoveNext();
    }
    $select_string .= '</select>';

    return $select_string;
}



 /**
  * Output a flag-icon
  *
  * @param $name
  * @param $iso_3166_1
  */
function oos_flag_icon($aLanguages)
{
    if (empty($aLanguages['name'])) {
        return;
    }
    if (empty($aLanguages['iso_3166_1'])) {
        return oos_output_string($name);
    }
    return '<div title="' . oos_output_string($aLanguages['name']) . '" class="flag flag-icon flag-icon-' . oos_output_string($aLanguages['iso_3166_1']) . ' width-full"></div>&nbsp;' . oos_output_string($aLanguages['name']) . '&nbsp;';
}
