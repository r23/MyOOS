<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {tooltip} function plugin
 *
 * Type:     function<br>
 * Name:     tooltip<br>
 * Purpose:  make text pop up in windows via wz_tooltip
 * Author:   r23 <info at r23 dot de>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_tooltip($params, &$smarty)
{

    require_once(MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins/function.html_href_link.php');

    $aModules = oos_get_modules();
    $aFilename =  oos_get_filename();


    $basedir = OOS_IMAGES;
    $height = SMALL_IMAGE_HEIGHT;
    $width = SMALL_IMAGE_WIDTH;
    $align = 'right';
    $image = '';

    $sLanguage = isset($_SESSION['language']) ? $_SESSION['language'] : DEFAULT_LANGUAGE;

    foreach ($params as $_key=>$_val) {
      switch($_key) {
        case 'image':
        case 'height':
        case 'width':
        case 'basedir':
        case 'align':
        case 'products_description':
        case 'products_id':
           $$_key = (string)$_val;
           break;

        default:
                throw new SmartyException("[tooltip] unknown parameter $_key", E_USER_WARNING);
           break;
      }
    }

    $image = $basedir . $image;

    if ((empty($image) || ($image == OOS_IMAGES)) && (IMAGE_REQUIRED == 'false')) {
        return FALSE;
    }

    if ((empty($image) || ($image == OOS_IMAGES)) && (IMAGE_REQUIRED == 'true')) {
      if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif')) {
        $image = OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif';
      } elseif (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture.gif')) {
        $image = OOS_IMAGES . 'no_picture.gif';
      } else {
        return  false;
      }
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
        if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif')) {
          $image = OOS_IMAGES . 'no_picture_' . $sLanguage . '.gif';
        } elseif (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . 'no_picture.gif')) {
          $image = OOS_IMAGES . 'no_picture.gif';
        } else {
          return FALSE;
        }
        $image_size = @getimagesize($image);
        $width = $image_size[0];
        $height = $image_size[1];
      } else {
        return FALSE;
      }
    }

    $image = '<img src='.$image.' align='.$align.' width='.$width.' height='.$height.'>';

    $link_params = array();
    $link_params = array('modul' => $aModules['products'],
                         'file' => $aFilename['product_info'],
                         'products_id' => $params['products_id']);

    $link = smarty_function_html_href_link($link_params, $smarty);

    $products_description = strip_tags($products_description);
    $products_description = preg_replace(array("!'!","![\r\n]!"),array("\'",'\r'),$products_description);
    $products_description = str_replace('"', ' ', $products_description);

    return '<a onmouseover="Tip(\'' . $image .' '. $products_description .'\', WIDTH, 200)" onmouseout="UnTip()" href="' . $link .'" target="_top">';

    # return '<a onmouseover="this.T_WIDTH=200;this.T_SHADOWWIDTH=5;return escape(\'' . $image .' '. $products_description .'\')" href="' . $link .'" target="_top">';

}


?>