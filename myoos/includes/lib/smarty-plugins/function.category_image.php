<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {large_category_image} function plugin
 *
 * Type:     function<br>
 * Name:     large_category_image<br>
 * Date:     Aug 24, 2004<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image =image width (optional, default actual width)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *
 * Examples: {large_category_image file="images/masthead.gif"}
 * Output:   <img src="images/masthead.gif" border=0 width=100 height=80>
 *
 * @author  r23 <info@r23.de>
 * @version 1.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_escape_special_chars()
 */
function smarty_function_category_image($params, &$smarty)
{
    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $basedir = OOS_IMAGES . 'category/';
    $dir = 'large';
    $border = 0;
    $alt = '';
    $image = '';
    $extra = '';

    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'image':
            case 'basedir':
            case 'dir':
            case 'alt':
            case 'class':
                if (!is_array($_val)) {
                    ${$_key} = smarty_function_escape_special_chars($_val);
                } else {
                    throw new SmartyException("large_category_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if (!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    throw new SmartyException("large_category_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($image)) {
        return false;
    }

    $image = $basedir . $dir . '/' . $image;



    if (isset($template->smarty->security_policy)) {
        // local file
        if (!$template->smarty->security_policy->isTrustedResourceDir($image)) {
            return;
        }
    }

    return '<img class="img-fluid ' . $class . '" src="' . $image . '" alt="' . strip_tags((string) $alt) . '" ' . $extra . '>';
}
