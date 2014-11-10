<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_iframe} function plugin
 *
 * Type:     function<br>
 * Name:     html_image_button<br>
 * Date:     September 15, 2003
 * Input:<br>
 *         - button = button (and path) of image (required)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *         - basedir = base directory 
 *
 * Examples: {html_iframe doc="gpl.html" class="license" frameborder="0" scrolling="auto"}
 * @author r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_iframe($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $doc = '';
    $class = 'license';
    $frameborder = 0;
    $height = '';
    $scrolling = 'auto';
    $extra = '';
    // $sTheme = oos_var_prep_for_os($_SESSION['theme']);
    $sLanguage = oos_var_prep_for_os($_SESSION['language']);
    $dir = OOS_SHOP . OOS_MEDIA . $sLanguage . '/';

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'doc':
            case 'class':
            case 'frameborder':
            case 'scrolling':
                if(!is_array($_val)) {
                 $$_key = smarty_function_escape_special_chars($_val);
                } else {
                  $smarty->trigger_error("html_iframe: attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_iframe: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($doc)) {
        $smarty->trigger_error("html_iframe: missing 'doc' parameter", E_USER_NOTICE);
        return;
    }

    return '<iframe src="'.$dir.$doc.'" class="'.$class.'" frameborder="'.$frameborder.'" scrolling="'.$scrolling.'"'.$extra.' /></iframe>';


}

?>