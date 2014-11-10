<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {swap_product_image} function plugin
 *
 * Type:     function<br>
 * Name:     swap_product_image<br>
 * Date:     Oct 09, 2006<br>
 * Purpose:  format HTML tags for the image<br>
 * Input:<br>
 *         - image = image width (optional, default actual width)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *
 * Examples: {swap_product_image id="featured" image=$random.products_image alt=$random.products_name|strip_tags}
 * Output:   <img id="featured" src="images/product_image.jpg" border="0" alt="products_name" onmouseover="imgSwap(this)" onmouseout="imgSwap(this)"/>
 * @author   r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_swap_product_image($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $basedir = OOS_IMAGES;
    $height = SMALL_IMAGE_HEIGHT;
    $width = SMALL_IMAGE_WIDTH;
    $border = 0;
    $alt = '';
    $image = '';
    $extra = '';

    $sLanguage = oos_var_prep_for_os($_SESSION['language']);

    foreach($params as $_key => $_val) {
      switch($_key) {
        case 'id':
        case 'image':
        case 'border':
        case 'height':
        case 'width':
        case 'basedir':
        case 'alt':
           if (!is_array($_val)) {
             $$_key = smarty_function_escape_special_chars($_val);
           } else {
             $smarty->trigger_error("small_product_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
           }
           break;

        default:
           if (!is_array($_val)) {
             $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
           } else {
             $smarty->trigger_error("small_product_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
           }
           break;
      }
    }

    $image = $basedir . $image;

    if ((empty($image) || ($image == OOS_IMAGES)) && (IMAGE_REQUIRED == 'false')) {
        return false;
    }

    return '<img id="'.$id.'" src="'.$image.'" alt="'.$alt.'" border="'.$border.'" width="'.$width.'" height="'.$height.'"'.$extra.' onmouseover="imgSwap(this)" onmouseout="imgSwap(this)" />';

}
?>