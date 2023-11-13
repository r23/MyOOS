<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {product_image_link} function plugin
 *
 * Type:     function<br>
 * Name:     product_image_link<br>
 * Date:     April 12, 2019<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image = image
 *
 * Examples: {product_image_link image="images/masthead.gif"}
 * Output:   http://example.org/images/product/large/products.jpg
 *
 * @author  r23 <info@r23.de>
 * @version 1.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_escape_special_chars()
 */
function smarty_function_product_image_link($params, &$smarty)
{
    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $basedir = OOS_SHOP_IMAGES . 'product/';
    $dir = 'large';

    foreach ($params as $_key => $_val) {
        switch ($_key) {
            case 'src':
            case 'basedir':
            case 'dir':
                if (!is_array($_val)) {
                    ${$_key} = smarty_function_escape_special_chars($_val);
                } else {
                    throw new SmartyException("small_product_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if (!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    throw new SmartyException("small_product_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($src)) {
        return false;
    }

    $image = $basedir . $dir . '/' . $src;


    if (isset($template->smarty->security_policy)) {
        // local file
        if (!$template->smarty->security_policy->isTrustedResourceDir($image)) {
            return;
        }
    }

    return $image;
}
