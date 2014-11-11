<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_image_submit} function plugin
 *
 * Type:     function<br>
 * Name:     html_image_submit<br>
 * Date:     September 15, 2003
 * Input:<br>
 *         - button = button (and path) of image (required)
 *         - border = border width (optional, default 0)
 *         - height = image height (optional, default actual height)
 *         - basedir = base directory 
 *
 * Examples: {html_image_submit image="masthead.gif"}
 * @author r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_html_customer_image_submit($params, &$smarty)
{


    if ($_SESSION['member']->group['show_price'] != 1) {
      return '';
    } 
   
    require_once $smarty->_get_plugin_filepath('function','html_image_submit');
    
    return smarty_function_html_image_submit($params, $smarty);

}

?>