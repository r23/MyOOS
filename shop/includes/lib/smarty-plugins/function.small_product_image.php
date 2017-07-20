<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {small_product_image} function plugin
 *
 * Type:     function<br>
 * Name:     small_product_image<br>
 * Date:     Aug 24, 2004<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image =image width (optional, default actual width)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *
 * Examples: {small_product_image file="images/masthead.gif"}
 * Output:   <img src="images/masthead.gif" border=0 width=100 height=80>
 * @author   r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_small_product_image($params, &$smarty)
{
	require_once(SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');

    $basedir = OOS_IMAGES;
    $border = 0;
    $alt = '';
    $image = '';
    $extra = '';

    $sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;

    foreach($params as $_key => $_val) {
      switch($_key) {
        case 'image':
        case 'basedir':
        case 'alt':
           if (!is_array($_val)) {
             $$_key = smarty_function_escape_special_chars($_val);
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

    $image = $basedir . $image;

    if ((empty($image) || ($image == OOS_IMAGES)) && (IMAGE_REQUIRED == 'false')) {
        return FALSE;
    }

    if (isset($template->smarty->security_policy)) {
        // local file
		if (!$template->smarty->security_policy->isTrustedResourceDir($image)) {
			return;
        }
    }		
	
    if ((empty($image) || ($image == OOS_IMAGES)) && (IMAGE_REQUIRED == 'true')) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif')) {
        $image = OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif';
      } elseif (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture.gif')) {
        $image = OOS_IMAGES . 'no_picture.gif';
      } else {
        return  false;
      }
    } 

    return '<img class="img-responsive" src="' . $image . '"' . $extra . ' />';

}

