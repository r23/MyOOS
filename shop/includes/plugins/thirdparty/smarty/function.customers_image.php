<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {customers_image} function plugin
 *
 * Type:     function<br>
 * Name:     customers_image<br>
 * Date:     Apr 16, 2007<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image =image width (optional, default actual width)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *
 * Examples: {customers_image file="images/masthead.gif"}
 * Output:   <img src="images/masthead.gif" border=0 width=100 height=80>
 * @author   r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_customers_image($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $basedir = OOS_IMAGES . OOS_CUSTOMERS_IMAGES;
    $height = '150';
    $width = '150';
    $border = 0;
    $alt = '';
    $image = '';
    $extra = '';

    $sLanguage = oos_var_prep_for_os($_SESSION['language']);

    foreach($params as $_key => $_val) {
      switch($_key) {
        case 'image':
        case 'border':
        case 'height':
        case 'width':
        case 'basedir':
        case 'alt':
           if (!is_array($_val)) {
             $$_key = smarty_function_escape_special_chars($_val);
           } else {
             $smarty->trigger_error("customers_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
           }
           break;

        default:
           if (!is_array($_val)) {
             $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
           } else {
             $smarty->trigger_error("customers_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
           }
           break;
      }
    }

    $image = $basedir . $image;


    if ((empty($image) || ($image == $basedir)) && (IMAGE_REQUIRED == 'false')) {
        return false;
    }

    if ((empty($image) || ($image == $basedir)) && (IMAGE_REQUIRED == 'true')) {
      $image = OOS_IMAGES . 'member.jpg';

      $image_size = @getimagesize($image);
      $width = $image_size[0];
      $height = $image_size[1];
    } elseif ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
      if (file_exists(OOS_ABSOLUTE_PATH . $image)) {
        $image_size = @getimagesize($image);
        if (empty($width) && oos_is_not_null($height)) {
          $ratio = $height / $image_size[1];
          $width = $image_size[0] * $ratio;
        } elseif (oos_is_not_null($width) && empty($height)) {
          $ratio = $width / $image_size[0];
          $height = $image_size[1] * $ratio;
        } elseif (empty($width) && empty($height)) {
          $width = $image_size[0];
          $height = $image_size[1];
        }
      } elseif (IMAGE_REQUIRED == 'true') {
        $image = OOS_IMAGES . 'member.jpg';

        $image_size = @getimagesize($image);
        $width = $image_size[0];
        $height = $image_size[1];
      } else {
        return false;
      }
    }

    $sSlash = (defined('OOS_XHTML') && (OOS_XHTML == 'true') ? ' /' : '');

    return '<img src="'.$image.'" alt="'.$alt.'" border="'.$border.'" width="'.$width.'" height="'.$height.'"'.$extra.$sSlash.' />';

}
?>