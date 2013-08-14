<?php
/* ----------------------------------------------------------------------
   $Id: function_output.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.26 2002/08/06 14:48:54 hpdl
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
  * html output
  *
  * @link http://www.oos-shop.de/
  * @package Admin html output
  * @version $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 14:02:48 $
  */


 /**
  * The HTML href link wrapper function
  *
  * @param $page
  * @param $parameters
  * @param $connection
  * @return string
  */
  function oos_href_link_admin($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($page == '') {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><b>Error!</b></font><br /><br /><b>Unable to determine the page link!<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($connection == 'NONSSL') {
      $link = OOS_HTTP_SERVER . OOS_SHOP . 'admin/';
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL == 'true') {
        $link = OOS_HTTPS_SERVER . OOS_SHOP . 'admin/';
      } else {
        $link = OOS_HTTP_SERVER . OOS_SHOP . 'admin/';
      }
    } else {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><b>Error!</b></font><br /><br /><b>Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if ($parameters == '') {
      $link = $link . $page . '?' . SID;
    } else {
      $link = $link . $page . '?' . $parameters . '&' . SID;
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }


 /**
  * The HTML catalog href link wrapper function
  *
  * @param $modul
  * @param $page
  * @param $parameters
  * @param $connection
  * @return string
  */
  function oos_catalog_link($page = '', $parameters = '', $connection = 'NONSSL') {
    if ($connection == 'NONSSL') {
      $link = OOS_HTTP_SERVER . OOS_SHOP;
    } elseif ($connection == 'SSL') {
      if (ENABLE_SSL_SHOP == 'true') {
        $link = OOS_HTTPS_SERVER . OOS_SHOP;
      } else {
        $link = OOS_HTTP_SERVER . OOS_SHOP;
      }
    } else {
      die('</td></tr></table></td></tr></table><br /><br /><font color="#ff0000"><b>Error!</b></font><br /><br /><b>Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</b>');
    }
    if (oos_is_not_null($parameters)) {
      $link .= 'index.php?content=' . oos_output_string($page) . '&amp;' . oos_output_string($parameters);
    } else {
      $link .= 'index.php?content=' . oos_output_string($page);
    }

    while ( (substr($link, -1) == '&') || (substr($link, -1) == '?') ) $link = substr($link, 0, -1);

    return $link;
  }


 /**
  * The HTML catalog href link wrapper function
  *
  * @param $src
  * @param $alt
  * @param $width
  * @param $height
  * @param $params
  * @return string
  */
  function oos_image($src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . $src . '" border="0" alt="' . $alt . '"';
    if ($alt) {
      $image .= ' title=" ' . $alt . ' "';
    }
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= '>';

    return $image;
  }


 /**
  * The HTML form submit button wrapper function
  * Outputs a button in the selected language
  *
  * @param $image
  * @param $alt
  * @param $params
  * @return string
  */
  function oos_image_submit($image, $alt, $params = '') {
     return '<input type="image" src="' . OOS_IMAGES . 'buttons/' . $_SESSION['language'] . '/' . $image . '" border="0" alt="' . $alt . '"' . (($params) ? ' ' . $params : '') . '>';
  }


 /**
  * Draw a 1 pixel black line
  */
  function oos_black_line() {
    return oos_image(OOS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }


 /**
  * Output a separator either through whitespace, or with an image
  *
  * @param $image
  * @param $width
  * @param $height
  * @return string
  */
  function oos_draw_separator($image = 'pixel_black.gif', $width = '100%', $height = '1') {
    return oos_image(OOS_IMAGES . $image, '', $width, $height);
  }


 /**
  * Output a function button in the selected language
  *
  * @param $image
  * @param $alt
  * @param $params
  * @return string
  */
  function oos_image_button($image, $alt = '', $params = '') {
     return oos_image(OOS_IMAGES . 'buttons/' . $_SESSION['language'] . '/' . $image, $alt, '', '', $params);
  }


 /**
  * @return string
  */
  function oos_image_swap($id, $src, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img id="' . $id . '" src="' . $src . '" border="0" alt="' . $alt . '"';
    if ($alt) {
      $image .= ' title=" ' . $alt . ' "';
    }
    if ($width) {
      $image .= ' width="' . $width . '"';
    }
    if ($height) {
      $image .= ' height="' . $height . '"';
    }
    if ($params) {
      $image .= ' ' . $params;
    }
    $image .= ' onmouseover="imgSwap(this)" onmouseout="imgSwap(this)"';
    $image .= ' />';

    return $image;
  }

 /**
  * Output a function button in the selected language
  *
  * @param $image
  * @param $alt
  * @param $params
  * @return string
  */
  function oos_image_swap_button($id, $image, $alt = '', $params = '') {
     return oos_image_swap($id, OOS_IMAGES . 'buttons/' . $_SESSION['language'] . '/' . $image, $alt, '', '', $params);
  }


 /**
  * Outputs a submit button
  *
  * @param $image
  * @param $alt
  * @param $params
  * @return string
  */
  function oos_image_swap_submits($id,$image, $alt, $params = '') {
     return '<input type="image" id="' . $id . '" src="' . OOS_IMAGES . 'buttons/' . $_SESSION['language'] . '/' . $image . '" border="0" onmouseover="imgSwap(this)" onmouseout="imgSwap(this)" alt="' . $alt . '"' . (($params) ? ' ' . $params : '') . '>';
  }


 /**
  * javascript to dynamically update the states/provinces list when the country is changed
  *
  * @param $country
  * @param $form
  * @param $field
  * @return string
  */
  function oos_is_zone_list($country, $form, $field) {

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
        if ($num_state == '1') $output_string .= '    ' . $form . '.' . $field . '.options[0] = new Option("' . PLEASE_SELECT . '", "");' . "\n";
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
  * @param $name
  * @param $action
  * @param $parameters
  * @param $method
  * @param $params
  * @return string
  */
  function oos_draw_form($name, $action, $parameters = '', $method = 'post', $params = '') {
    $form = '<form name="' . $name . '" action="';
    if ($parameters) {
      $form .= oos_href_link_admin($action, $parameters);
    } else {
      $form .= oos_href_link_admin($action);
    }
    $form .= '" method="' . $method . '"';
    if ($params) {
      $form .= ' ' . $params;
    }
    $form .= '>';

    return $form;
  }


 /**
  * Output a form input field
  *
  * @param $name
  * @param $value
  * @param $parameters
  * @param $required
  * @param $type
  * @param $reinsert_value
  * @return string
  */
  function oos_draw_input_field($name, $value = '', $parameters = '', $required = false, $type = 'text', $reinsert_value = true) {
    $field = '<input type="' . $type . '" name="' . $name . '"';
    if ( ($GLOBALS[$name]) && ($reinsert_value) ) {
      $field .= ' value="' . htmlspecialchars(trim($GLOBALS[$name])) . '"';
    } elseif ($value != '') {
      $field .= ' value="' . htmlspecialchars(trim($value)) . '"';
    }
    if ($parameters != '') {
      $field .= ' ' . $parameters;
    }
    $field .= '>';

    if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }


 /**
  * Output a form password field
  *
  * @param $name
  * @param $value
  * @param $required
  * @return string
  */
  function oos_draw_password_field($name, $value = '', $parameters = 'maxlength="40"', $required = false) {
    $field = oos_draw_input_field($name, $value, $parameters, $required, 'password', false);

    return $field;
  }


 /**
  * Output a form filefield
  *
  * @param $name
  * @param $required
  * @return string
  */
  function oos_draw_file_field($name, $required = false) {
    $field = oos_draw_input_field($name, '', '', $required, 'file');

    return $field;
  }


 /**
  * Output a selection field - alias function for oos_draw_checkbox_field() and oos_draw_radio_field()
  *
  * @param $name
  * @param $type
  * @param $value
  * @param $checked
  * @param $compare
  * @param $parameter
  * @return string
  */
  function oos_draw_selection_field($name, $type, $value = '', $checked = false, $compare = '', $parameter = '') {
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') {
      $selection .= ' value="' . $value . '"';
    }
    if ( ($checked == true) || ($GLOBALS[$name] == 'on') || ($value && ($GLOBALS[$name] == $value)) || ($value && ($value == $compare)) ) {
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
  * @param $name
  * @param $value
  * @param $checked
  * @param $compare
  * @param $parameter
  * @return string
  */
  function oos_draw_checkbox_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return oos_draw_selection_field($name, 'checkbox', $value, $checked, $compare, $parameter);
  }


 /**
  * Output a form radio field
  *
  * @param $name
  * @param $value
  * @param $checked
  * @param $compare
  * @param $parameter
  * @return string
  */
  function oos_draw_radio_field($name, $value = '', $checked = false, $compare = '', $parameter = '') {
    return oos_draw_selection_field($name, 'radio', $value, $checked, $compare, $parameter);
  }


 /**
  * Output a form textarea field
  *
  * @param $name
  * @param $wrap
  * @param $width
  * @param $height
  * @param $text
  * @param $params
  * @param $reinsert_value
  * @return string
  */
  function oos_draw_textarea_field($name, $wrap, $width, $height, $text = '', $params = '', $reinsert_value = true) {
    $field = '<textarea name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '"';
    if ($params) $field .= ' ' . $params;
    $field .= '>';
    if ( ($GLOBALS[$name]) && ($reinsert_value) ) {
      $field .= htmlspecialchars(trim($GLOBALS[$name]));
    } elseif ($text != '') {
      $field .= htmlspecialchars(trim($text));
    }
    $field .= '</textarea>';

    return $field;
  }


 /**
  * Output a form hidden field
  *
  * @param $name
  * @param $value
  * @return string
  */
  function oos_draw_hidden_field($name, $value = '') {
    $field = '<input type="hidden" name="' . $name . '" value="';
    if ($value != '') {
      $field .= trim($value);
    } else {
      $field .= trim($GLOBALS[$name]);
    }
    $field .= '">';

    return $field;
  }

 /**
  * Hide form elements
  */
  function oos_hide_session_id() {
    if (defined('SID') && oos_is_not_null(SID)) return oos_draw_hidden_field(oos_session_name(), oos_session_id());
  }


 /**
  * Output a login form
  *
  * @param $name
  * @param $modul
  * @param $page
  * @param $parameters
  * @param $method
  * @param $params
  * @return string
  */
  function oos_draw_login_form($name, $page, $parameters = '', $method = 'post', $params = '') {
    $loginform = '<form name="' . $name . '" action="';
    if ($parameters) {
      $loginform .= oos_catalog_link($page, $parameters);
    } else {
      $loginform .= oos_catalog_link($page);
    }
    $loginform .= '" method="' . $method . '"';
    if ($params) {
      $loginform .= ' ' . $params;
    }
    $loginform .= '>';
    return $loginform;
  }


 /**
  * Output a form pull down menu
  *
  * @param $name
  * @param $values
  * @param $default
  * @param $params
  * @param $required
  * @return string
  */
  function oos_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = false) {
    $field = '<select name="' . $name . '"';
    if ($params) $field .= ' ' . $params;
    $field .= '>';
    for ($i=0; $i < count($values); $i++) {
      $field .= '<option value="' . $values[$i]['id'] . '"';
      if ( ((strlen($values[$i]['id']) > 0) && ($_GET[$name] == $values[$i]['id'])) || ($default == $values[$i]['id']) ) {
        $field .= ' selected="selected"';
      }
      $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';

    if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }


 /**
  * Output a form pull down menu
  *
  * @param $name
  * @param $values
  * @param $selected_vals
  * @param $params
  * @param $required
  * @return string
  */
  function oos_draw_m_select_menu($name, $values, $selected_vals, $params = '', $required = false) {
    $field = '<select name="' . $name . '"';
    if ($params) $field .= ' ' . $params;
    $field .= ' multiple>';
    for ($i=0; $i < count($values); $i++) {
      if ($values[$i]['id']) {
        $field .= '<option value="' . $values[$i]['id'] . '"';
        if ( ((strlen($values[$i]['id']) > 0) && ($GLOBALS[$name] == $values[$i]['id'])) ) {
          $field .= ' selected="selected"';
        } else {
          for ($j=0; $j < count($selected_vals); $j++) {
            if ($selected_vals[$j]['id'] == $values[$i]['id']) {
              $field .= ' selected="selected"';
            }
          }
        }
      }
      $field .= '>' . $values[$i]['text'] . '</option>';
    }
    $field .= '</select>';

    if ($required) $field .= TEXT_FIELD_REQUIRED;

    return $field;
  }

