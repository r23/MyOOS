<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_oos_image} function plugin
 *
 * Type:     function<br>
 * Name:     html_oos_image<br>
 * Date:     Feb 24, 2003<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - file = file (and path) of image (required)
 *         - image =image width (optional, default actual width)
 *         - basedir = base directory for absolute paths, default
 *                     is environment variable DOCUMENT_ROOT
 *
 * Examples: {html_oos_image file="images/masthead.gif"}
 * Output:   <img  class="img-fluid"src="images/masthead.gif" alt=" " />
 *
 * @link    http://smarty.php.net/manual/en/language.function.html.image.php {html_oos_image}
 *      (Smarty online manual)
 * @author  Monte Ohrt <monte@ispi.net>
 * @author  credits to Duda <duda@big.hu> - wrote first image function
 *           in repository, helped with lots of functionality
 * @version 2.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_escape_special_chars()
 */
function smarty_function_html_oos_image($params, &$smarty)
{
    include_once SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php';

    $alt = '';
    $image = '';
    $extra = '';

    $basedir = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_DEFAULT);

    foreach ($params as $_key => $_val) {
        switch ($_key) {
        case 'image':
        case 'basedir':
            $$_key = $_val;
            break;

        case 'alt':
            if (!is_array($_val)) {
                $$_key = smarty_function_escape_special_chars($_val);
            } else {
                throw new SmartyException("html_oos_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
            }
            break;

        default:
            if (!is_array($_val)) {
                $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
            } else {
                throw new SmartyException("html_oos_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
            }
            break;
        }
    }

    $image = $basedir . $image;

    if (empty($image) || ($image == OOS_IMAGES)) {
        return false;
    }

    if (isset($template->smarty->security_policy)) {
        // local file
        if (!$template->smarty->security_policy->isTrustedResourceDir($image)) {
            return;
        }
    }

    return '<img class="img-fluid" src="'.$image.'" alt="'.$alt.'"'.$extra.' />';
}
