<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_image_submit} function plugin
 *
 * Type:     function<br>
 * Name:     html_image_submit<br>
 * Date:     September 15, 2003
 * Input:<br>
 *         - button = button (and path) of image (required)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *         - basedir = base directory 
 *
 * Examples: {html_image_submit image="masthead.gif"}
 * @author r23 <info@r23.de>
 * @author credits to Monte Ohrt <monte@ispi.net>
 * @author credits to Duda <duda@big.hu> - wrote first image function
 *           in repository, helped with lots of functionality
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_image_submit($params, &$smarty)
{

    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $image = '';
    $alt = '';
    $border = 0;
    $extra = '';
    $sTheme = oos_var_prep_for_os($_SESSION['theme']);
    $sLanguage = oos_var_prep_for_os($_SESSION['language']);

    $basedir = 'themes/' . $sTheme . '/images/buttons/' . $sLanguage . '/';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'image':
            case 'basedir':
                $$_key = $_val;
                break;

            case 'alt':
                if(!is_array($_val)) {
                    $$_key = smarty_function_escape_special_chars($_val);
                } else {
                    $smarty->trigger_error("html_image_submit: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_image_submit: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($image)) {
        $smarty->trigger_error("html_image_submit: missing 'button' parameter", E_USER_NOTICE);
        return;
    }

    $sSlash = (defined('OOS_XHTML') && (OOS_XHTML == 'true') ? ' /' : '');

    return '<input type="image" src="'.$basedir.$image.'" alt="'.$alt.'" '.$extra.$sSlash.'>';

}

?>