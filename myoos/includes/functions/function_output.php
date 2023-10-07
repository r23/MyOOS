<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.49 2003/02/11 01:31:02 hpdl
         html_output.php 1498 2007-03-29 14:04:50Z hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

 /**
  * html output
  *
  * @link    https://www.oos-shop.de
  * @package html output
  * @version $Revision: 1.3 $ - changed by $Author: r23 $ on $Date: 2008/08/14 10:24:05 $
  */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

 /**
  * The HTML href link wrapper function
  *
  * @param  $modul
  * @param  $page
  * @param  $parameters
  * @param  $add_session_id
  * @param  $search_engine_safe
  * @return string
  */
function oos_href_link($page = '', $parameters = '', $add_session_id = true, $search_engine_safe = true)
{
    global $session, $oEvent, $spider_flag;

    $page = oos_output_string($page);

    $link = OOS_HTTPS_SERVER . OOS_SHOP;

    if (oos_is_not_null($parameters)) {
        $link .= 'index.php?content=' . $page . '&amp;' . oos_output_string($parameters);
    } else {
        $link .= 'index.php?content=' . $page;
    }

    $separator = '&amp;';

    while ((str_ends_with($link, '&amp;')) || (str_ends_with($link, '?'))) {
        if (str_ends_with($link, '?')) {
            $link = substr($link, 0, -1);
        } else {
            $link = substr($link, 0, -5);
        }
    }

    if (isset($_SESSION)) {

        // Add the session ID when moving from HTTP and HTTPS servers or when SID is defined
        if ($add_session_id == true) {
            $_sid = $session->getName() . '=' . $session->getId();
        }

        if ($spider_flag === false) {
            $_sid = null;
        }
    }

    if (is_object($oEvent)) {
        if (($search_engine_safe == true) &&  $oEvent->installed_plugin('sefu')) {
            $link = str_replace(['?', '&amp;', '='], '/', $link);

            $separator = '?';

            $pos = strpos($link, 'action');
            if ($pos === false) {
                $url_rewrite = new url_rewrite();
                $link = $url_rewrite->transform_uri($link);
            }
        }
    }

    if (isset($_sid)) {
        $link .= $separator . oos_output_string($_sid);
    }

    return $link;
}

 /**
  * It begets a link to get data
  *
  * @param  $modul
  * @param  $page
  * @param  $parameters
  * @param  $add_session_id
  * @param  $search_engine_safe
  * @return string
  */
function oos_get_data($page = '', $parameters = '', $add_session_id = true, $search_engine_safe = true)
{
    global $session, $oEvent, $spider_flag;

    $page = oos_output_string($page);

    $link = OOS_HTTPS_SERVER . OOS_SHOP;

    if (oos_is_not_null($parameters)) {
        $link .= 'get_data.php?content=' . $page . '&amp;' . oos_output_string($parameters);
    } else {
        $link .= 'get_data.php?content=' . $page;
    }

    $separator = '&amp;';

    while ((str_ends_with($link, '&amp;')) || (str_ends_with($link, '?'))) {
        if (str_ends_with($link, '?')) {
            $link = substr($link, 0, -1);
        } else {
            $link = substr($link, 0, -5);
        }
    }

    if (isset($_SESSION)) {

        // Add the session ID when moving from HTTP and HTTPS servers or when SID is defined
        if ($add_session_id == true) {
            $_sid = $session->getName() . '=' . $session->getId();
        }

        if ($spider_flag === false) {
            $_sid = null;
        }
    }

    if (is_object($oEvent)) {
        if (($search_engine_safe == true) &&  $oEvent->installed_plugin('sefu')) {
            $link = str_replace(['?', '&amp;', '='], '/', $link);

            $separator = '?';

            $pos = strpos($link, 'action');
            if ($pos === false) {
                $url_rewrite = new url_rewrite();
                $link = $url_rewrite->transform_uri($link);
            }
        }
    }

    if (isset($_sid)) {
        $link .= $separator . oos_output_string($_sid);
    }

    return $link;
}


 /**
  * The HTML image wrapper function
  *
  * @param  $src
  * @param  $title
  * @param  $width
  * @param  $height
  * @param  $parameters
  * @return string
  */
function oos_image($src, $title = null, $parameters = null)
{
    if (empty($src) || ($src == OOS_IMAGES)) {
        return false;
    }

    $image = '<img class="img-fluid" src="' . oos_output_string($src) . '" alt="' . oos_output_string($title) . '"';

    if (!empty($title)) {
        $image .= ' title="' . oos_output_string($title) . '"';
    }

    if (!empty($parameters)) {
        $image .= ' ' . oos_output_string($parameters);
    }

    $image .= '>';

    return $image;
}



 /**
  * Output a form input field
  *
  * @param  $name
  * @param  $value
  * @param  $parameters
  * @param  $type
  * @param  $reinsert_value
  * @return string
  */
function oos_draw_input_field($name, $value = '', $parameters = '', $type = 'text', $reinsert_value = true)
{
    $field = '<input type="' . oos_output_string($type) . '" name="' . oos_output_string($name) . '"';

    if (($reinsert_value == true) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $value = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $value = stripslashes($_POST[$name]);
        }
    }


    if (oos_is_not_null($value)) {
        $field .= ' value="' . oos_output_string($value) . '"';
    }

    if (oos_is_not_null($parameters)) {
        $field .= ' ' . $parameters;
    }

    $field .= ' />';

    return $field;
}



/**
 * Output a selection field - alias function for oos_draw_checkbox_field() and oos_draw_radio_field()
 *
 * @param  $name
 * @param  $type
 * @param  $value
 * @param  $checked
 * @param  $parameters
 * @return string
 */
function oos_draw_select_field($name, $type, $value = null, $checked = false, $parameters = null)
{
    $selection = '<input type="' . oos_output_string($type) . '" name="' . oos_output_string($name) . '"';

    if (!empty($value)) {
        $selection .= ' value="' . oos_output_string($value) . '"';
    }

    if (($checked == true) || (isset($_GET[$name]) && is_string($_GET[$name]) && (($_GET[$name] == 'on') || (stripslashes($_GET[$name]) == $value)))
        || (isset($_POST[$name]) && is_string($_POST[$name]) && (($_POST[$name] == 'on') || (stripslashes($_POST[$name]) == $value)))
    ) {
        $selection .= ' checked';
    }

    if (!empty($parameters) && is_string($parameters)) {
        $selection .= ' ' . $parameters;
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
  * @param $parameters
  */
function oos_draw_checkbox_field($name, $value = '', $checked = false, $parameters = '')
{
    return oos_draw_select_field($name, 'checkbox', $value, $checked, $parameters);
}


 /**
  * Output a form radio field
  *
  * @param $name
  * @param $value
  * @param $checked
  * @param $parameters
  */
function oos_draw_radio_field($name, $value = '', $checked = false, $parameters = '')
{
    return oos_draw_select_field($name, 'radio', $value, $checked, $parameters);
}



/**
 * Output a form hidden field
 *
 * @param $name
 * @param $value
 * @param $parameters
 */
function oos_draw_hidden_field($name, $value = '', $parameters = '')
{
    $field = '<input type="hidden" name="' . oos_output_string($name) . '"';


    if (strlen($value ?? '') > 0) {
        $field .= ' value="' . oos_output_string($value) . '"';
    } elseif ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name]))) {
        if ((isset($_GET[$name]) && is_string($_GET[$name]))) {
            $field .= ' value="' . oos_output_string(stripslashes($_GET[$name])) . '"';
        } elseif ((isset($_POST[$name]) && is_string($_POST[$name]))) {
            $field .= ' value="' . oos_output_string(stripslashes($_POST[$name])) . '"';
        }
    }

    if (!empty($parameters)) {
        $field .= ' ' . $parameters;
    }

    $field .= '>';

    return $field;
}



/**
 * Output a form pull down menu
 *
 * @param $name
 * @param $values
 * @param $default
 * @param $parameters
 * @param $required
 */
function oos_draw_pull_down_menu($name, $values, $default = null, $parameters = null, $required = false)
{
    $field = '<select class="form-control custom-select" name="' . oos_output_string($name) . '"';

    if (!empty($parameters) && is_string($parameters)) {
        $field .= ' ' . $parameters;
    }

    if ($required == true) {
        $field .= ' required';
    }

    $field .= '>';

    if (empty($default) && ((isset($_GET[$name]) && is_string($_GET[$name])) || (isset($_POST[$name]) && is_string($_POST[$name])))) {
        if (isset($_GET[$name]) && is_string($_GET[$name])) {
            $default = stripslashes($_GET[$name]);
        } elseif (isset($_POST[$name]) && is_string($_POST[$name])) {
            $default = stripslashes($_POST[$name]);
        }
    }

	$n = is_countable($values) ? count($values) : 0;
    for ($i=0, $n; $i<$n; $i++) {
        $field .= '<option value="' . oos_output_string($values[$i]['id']) . '"';
        if ($default == $values[$i]['id']) {
            $field .= ' selected';
        }

        $field .= '>' . oos_output_string($values[$i]['text']) . '</option>';
    }
    $field .= '</select>';

    if ($required == true) {
        $field .= TEXT_FIELD_REQUIRED;
    }

    return $field;
}
