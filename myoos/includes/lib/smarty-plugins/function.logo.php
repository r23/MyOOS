<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {small_category_image} function plugin
 *
 * Type:     function<br>
 * Name:     small_category_image<br>
 * Date:     Aug 24, 2004<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image =image width (optional, default actual width)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *
 * Examples: {small_category_image file="images/masthead.gif"}
 * Output:   <img src="images/masthead.gif" border=0 width=100 height=80>
 *
 * @author  r23 <info@r23.de>
 * @version 1.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_escape_special_chars()
 */
function smarty_function_logo($params, &$smarty)
{
    if (empty(STORE_LOGO)) {
        return '';
    }

    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $basedir = OOS_IMAGES . 'logo/';

    foreach ($params as $_key => $_val) {
        switch ($_key) {
        case 'dir':
            if (!is_array($_val)) {
                ${$_key} = smarty_function_escape_special_chars($_val);
            } else {
                throw new SmartyException("small_category_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
            }
            break;
        }
    }

    $image = $basedir . $dir . '/' . STORE_LOGO;

    return '<img id="logo-header" class="img-fluid" src="' . $image . '" alt="' . STORE_NAME. '" title="' . STORE_NAME . '">';
}
