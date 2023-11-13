<?php
/**
   ----------------------------------------------------------------------
   $Id: function.oos_get_country_list.php,v 1.1 2007/06/08 13:34:16 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: html_output.php,v 1.49 2003/02/11 01:31:02 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {oos_get_country_list} function plugin
 *
 * Type:     function
 * Name:     oos_get_country_list
 * Version:  1.0
 * -------------------------------------------------------------
 */

function smarty_function_oos_get_country_list($params, &$smarty)
{
    global $aLang;

    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';
    include_once SMARTY_PLUGINS_DIR . 'function.html_options.php';

    /* Set the name of the <select> tag. */
    $name  = 'country';


    /* <select size>'s of <select> tag.
       If not set, uses default dropdown. */
    $size  = null;

    /* Unparsed attributes common to *ALL* the <select>/<input> tags.
       An example might be in the template: extra ='class ="foo"'. */
    $extra = null;

    foreach ($params as $_key => $_val) {
        ${$_key} = smarty_function_escape_special_chars($_val);
    }

    $countries = [];
    $countries_names = [];
    $countries_values = [];

    $countries = oos_get_countries();

    $countries_values[] = '';
    $countries_names[] = $aLang['pull_down_default'];

    $n = is_countable($countries) ? count($countries) : 0;
    for ($i = 0, $n; $i < $n; $i++) {
        $countries_values[] = $countries[$i]['countries_id'];
        $countries_names[] = $countries[$i]['countries_name'];
    }

    $html_result .= '<select required name="' . $name . '"';
    if (null !== $size) {
        $html_result .= ' size="' . $size . '"';
    }
    if (null !== $extra) {
        $html_result .= ' ' . $extra;
    }

    $html_result .= ' class="form-control pointer">'."\n";


    $html_result .= smarty_function_html_options(
        ['output'       => $countries_names, 'values'       => $countries_values, 'selected'     => $selected, 'print_result' => false],
        $smarty
    );

    $html_result .= '</select>';

    print $html_result;
}
