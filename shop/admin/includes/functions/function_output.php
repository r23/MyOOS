<?php
/* ----------------------------------------------------------------------
   $Id: function_output.php,v 1.1 2007/06/08 14:02:48 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
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
defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

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
  
	$page = oos_output_string($page);

	if ($page == '') {
		die('<div class="alert alert-danger" role="alert"><strong>Error!</strong> Unable to determine the page link!<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
	}
	if ($connection == 'NONSSL') {
		$link = OOS_HTTP_SERVER . OOS_SHOP . 'admin/';
	} elseif ($connection == 'SSL') {
		if (ENABLE_SSL == 'TRUE') {
			$link = OOS_HTTPS_SERVER . OOS_SHOP . 'admin/';
		} else {
			$link = OOS_HTTP_SERVER . OOS_SHOP . 'admin/';
		}
	} else {
		die('<div class="alert alert-danger" role="alert"><strong>Error!</strong> Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
	}

	if (oos_is_not_null($parameters)) {
		$link = $link . $page . '?' . oos_output_string($parameters) . '&' . SID;
	} else {
		$link = $link . $page . '?' . SID;		
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
	
	$page = oos_output_string($page);

	if ($page == '') {
		die('<div class="alert alert-danger" role="alert"><strong>Error!</strong> Unable to determine the page link!<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
	}	  
	  
	if ($connection == 'NONSSL') {
		$link = OOS_HTTP_SERVER . OOS_SHOP;
	} elseif ($connection == 'SSL') {
		if (ENABLE_SSL_SHOP == 'TRUE') {
			$link = OOS_HTTPS_SERVER . OOS_SHOP;
		} else {
			$link = OOS_HTTP_SERVER . OOS_SHOP;
		}
	} else {
		die('<div class="alert alert-danger" role="alert"><strong>Error!</strong>Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL<br /><br />Function used:<br /><br />oos_href_link_admin(\'' . $page . '\', \'' . $parameters . '\', \'' . $connection . '\')</div>');
    }

	if (oos_is_not_null($parameters)) {
		$link .= 'index.php?content=' . $page . '&' . oos_output_string($parameters);
	} else {
		$link .= 'index.php?content=' . $page;
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
  * Draw a 1 pixel black line
  */
  function oos_black_line() {
    return oos_image(OOS_IMAGES . 'pixel_black.gif', '', '100%', '1');
  }


/**
 * Output a function button in the selected language
 *
 * @param $title
 * @param $params
 * @return string
 */
function oos_button($id, $title = '', $params = '') {
	return '<button class="btn btn-sm btn-primary margin-bottom-20"><strong>' . $title . '</strong></button>';
}


/**
 * Outputs a submit button
 *
 * @param $title
 * @param $params
 * @return string
 */
function oos_submit_button($id, $title = '', $params = '') {
	return '<button class="btn btn-sm btn-primary margin-bottom-20" type="submit"><strong>' . $title . '</strong></button>';
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

      // Close result set
      $states_result->Close();

      // Move that ADOdb pointer!
      $countries_result->MoveNext();
    }

    // Close result set
    $countries_result->Close();

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
  function oos_draw_form($id, $name, $action, $parameters = '', $method = 'post', $parsley_validate = TRUE, $params = '') {
    $form = '<form id="' . oos_output_string($id) . '" name="' . oos_output_string($name) . '" action="';
    if (oos_is_not_null($parameters)) {
      $form .= oos_href_link_admin($action, $parameters);
    } else {
      $form .= oos_href_link_admin($action);
    }
    $form .= '" method="' . oos_output_string($method) . '"';

	if ($parsley_validate == TRUE) {
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
  * @param $name
  * @param $value
  * @param $parameters
  * @param $required
  * @param $type
  * @param $reinsert_value
  * @param $placeholder
  * @return string
  */
function oos_draw_input_field($name, $value = '', $parameters = '', $required = FALSE, $type = 'text', $reinsert_value = TRUE, $disabled = FALSE, $placeholder = '') {
    $field = '<input type="' . $type . '" name="' . $name . '"';
	
	if ( ($reinsert_value == TRUE) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
		if (isset($_GET[$name]) && is_string($_GET[$name])) {
			$value = stripslashes($_GET[$name]);
		} elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
			$value = stripslashes($_POST[$name]);
		}
	}
    if (oos_is_not_null($value)) {
		$field .= ' value="' . oos_output_string($value) . '"';
    }
    if ($required) $field .= ' required';	

    if (oos_is_not_null($parameters)) {
		$field .= ' ' . $parameters;
    }
	if ($disabled == TRUE) {	
		$field .= ' disabled="disabled"';
	}
    if (oos_is_not_null($placeholder)) {
		$field .= ' placeholder="' . oos_output_string($placeholder) . '"';
    }	
	
    $field .= ' class="form-control">';

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
  function oos_draw_password_field($name, $value = '', $parameters = 'maxlength="40"', $required = FALSE) {
    $field = oos_draw_input_field($name, $value, $parameters, $required, 'password', FALSE);

    return $field;
  }


 /**
  * Output a form filefield
  *
  * @param $name
  * @param $required
  * @return string
  */
  function oos_draw_file_field($name, $required = FALSE) {
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
function oos_draw_selection_field($name, $type, $value = '', $checked = FALSE, $compare = '', $parameter = '') {
    $selection = '<input type="' . $type . '" name="' . $name . '"';
    if ($value != '') {
      $selection .= ' value="' . $value . '"';
    }
    if ( ($checked == TRUE) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value))) || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value))) || (oos_is_not_null($compare) && ($value == $compare)) ) {
	
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
  function oos_draw_checkbox_field($name, $value = '', $checked = FALSE, $compare = '', $parameter = '') {
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
  function oos_draw_radio_field($name, $value = '', $checked = FALSE, $compare = '', $parameter = '') {
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
  function oos_draw_textarea_field($name, $wrap, $width, $height, $text = '', $params = '', $reinsert_value = TRUE) {

    $field = '<textarea class="form-control" name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '"';

	if (oos_is_not_null($params)) $field .= ' ' . $params;
	
    $field .= '>';
	
	if ( ($reinsert_value == TRUE) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {
		if (isset($_GET[$name]) && is_string($_GET[$name])) {
			$field .= htmlspecialchars(stripslashes($_GET[$name]));
		} elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
			$field .= htmlspecialchars(stripslashes($_POST[$name]));
		}		
	} elseif (oos_is_not_null($text)) {
		$field .= htmlspecialchars($text);
	}
    $field .= '</textarea>';

    return $field;
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
function oos_draw_editor_field($name, $wrap, $width, $height, $text = '', $params = '', $reinsert_value = TRUE) {

    $field = '<textarea name="' . $name . '" wrap="' . $wrap . '" cols="' . $width . '" rows="' . $height . '"';
	if (oos_is_not_null($params)) $field .= ' ' . $params;
	
    $field .= '>';
	
	if ( ($reinsert_value == TRUE) && ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) ) {

		if (isset($_GET[$name]) && is_string($_GET[$name])) {
			$field .= htmlspecialchars(stripslashes($_GET[$name]));
		} elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
			$field .= htmlspecialchars(stripslashes($_POST[$name]));
		}		
	} elseif (oos_is_not_null($text)) {
		$field .= htmlspecialchars($text);
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
    $field = '<input type="hidden" name="' . $name . '"';

    if (oos_is_not_null($value)) {
		$field .= ' value="' . oos_output_string($value) . '"';
	} elseif ( (isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])) ) {
		if ( (isset($_GET[$name]) && is_string($_GET[$name])) ) {
			$field .= ' value="' . oos_output_string(stripslashes($_GET[$name])) . '"';
		} elseif ( (isset($_POST[$name]) && is_string($_POST[$name])) ) {
			$field .= ' value="' . oos_output_string(stripslashes($_POST[$name])) . '"';
		}
    }

    $field .= '>';

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
      $loginform .= oos_catalog_link($page, $parameters);
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
  function oos_draw_pull_down_menu($name, $values, $default = '', $params = '', $required = FALSE) {
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
