<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_image_button} function plugin
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
 * Examples: {html_image_button image="images/masthead.gif"}
 * Output:   <img src="images/masthead.gif" border=0 width=400 height=23>
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
function smarty_function_html_image_button($params, &$smarty)
{

    require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

    $image = '';
    $alt = '';
    $border = 0;
    $height = '';
    $width = '';
    $extra = '';

    $sTheme = STORE_TEMPLATES;
    $sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;

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
                    throw new SmartyException ("html_image_button: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    throw new SmartyException ("html_image_button: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($image)) {
        throw new SmartyException ("html_image_button: missing 'button' parameter", E_USER_NOTICE);
        return;
    }

    $_image_path = $basedir . $image;

/*
	if (stripos($params['file'], 'file://') === 0) {
        $params['file'] = substr($params['file'], 7);
    }
    
    $protocol = strpos($params['file'], '://');
    if ($protocol !== FALSE) {
        $protocol = strtolower(substr($params['file'], 0, $protocol));
    }
    
    if (isset($template->smarty->security_policy)) {
        if ($protocol) {
            // remote resource (or php stream, …)
            if(!$template->smarty->security_policy->isTrustedUri($params['file'])) {
                return;
            }
        } else {
            // local file
            if(!$template->smarty->security_policy->isTrustedResourceDir($params['file'])) {
                return;
            }
        }
    }
*/	

    if (!isset($params['width']) || !isset($params['height'])) {
        // FIXME: (rodneyrehm) getimagesize() loads the complete file off a remote resource, use custom [jpg,png,gif]header reader!
        if (!$_image_data = @getimagesize($_image_path)) {
            if (!file_exists($_image_path)) {
                trigger_error("html_image_button: unable to find '$_image_path'", E_USER_NOTICE);
                return;
            } else if (!is_readable($_image_path)) {
                trigger_error("html_image_button: unable to read '$_image_path'", E_USER_NOTICE);
                return;
            } else {
                trigger_error("html_image_button: '$_image_path' is not a valid image file", E_USER_NOTICE);
                return;
            } 
        }

        if (!isset($params['width'])) {
            $width = $_image_data[0];
        } 
        if (!isset($params['height'])) {
            $height = $_image_data[1];
        } 
    } 
	


    $sSlash = (defined('OOS_XHTML') && (OOS_XHTML == 'true') ? ' /' : '');

    return '<img src="'.$basedir.$image.'" alt="'.$alt.'" border="'.$border.'" width="'.$width.'" height="'.$height.'"'.$extra.$sSlash.'>';

}