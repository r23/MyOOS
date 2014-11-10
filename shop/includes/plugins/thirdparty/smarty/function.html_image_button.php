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

    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $image = '';
    $alt = '';
    $border = 0;
    $height = '';
    $width = '';
    $extra = '';

    $sTheme = oos_var_prep_for_os($_SESSION['theme']);
    $sLanguage = oos_var_prep_for_os($_SESSION['language']);

    $basedir = 'themes/' . $sTheme . '/images/buttons/' . $sLanguage . '/';

    if(strstr($GLOBALS['HTTP_SERVER_VARS']['HTTP_USER_AGENT'], 'Mac')) {
        $dpi_default = 72;
    } else {
        $dpi_default = 96;
    }

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
                    $smarty->trigger_error("html_image_button: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_image_button: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (empty($image)) {
        $smarty->trigger_error("html_image_button: missing 'button' parameter", E_USER_NOTICE);
        return;
    }

    $_image_path = $basedir . $image;

    if (!isset($params['width']) || !isset($params['height'])) {
      if ($smarty->security &&
        ($_params = array('resource_type' => 'file', 'resource_name' => $_image_path)) &&
        (require_once(SMARTY_CORE_DIR . 'core.is_secure.php')) &&
        (!smarty_core_is_secure($_params, $smarty)) ) {
          $smarty->trigger_error("html_image_button:: (secure) '$_image_path' not in secure directory", E_USER_NOTICE);

      } elseif (!$_image_data = @getimagesize($_image_path)) {
        if(!file_exists($_image_path)) {
          $smarty->trigger_error("html_image_button: unable to find '$_image_path'", E_USER_NOTICE);
          return;
        } elseif(!is_readable($_image_path)) {
          $smarty->trigger_error("html_image_button: unable to read '$_image_path'", E_USER_NOTICE);
          return;
        } else {
          $smarty->trigger_error("html_image_button: '$_image_path' is not a valid image button", E_USER_NOTICE);
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

    if(isset($params['dpi'])) {
      $_resize = $dpi_default/$params['dpi'];
      $width = round($width * $_resize);
      $height = round($height * $_resize);
    }

    $sSlash = (defined('OOS_XHTML') && (OOS_XHTML == 'true') ? ' /' : '');

    return '<img src="'.$basedir.$image.'" alt="'.$alt.'" border="'.$border.'" width="'.$width.'" height="'.$height.'"'.$extra.$sSlash.'>';

}

?>