<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {product_info_link} function plugin
 *
 * Type:     function<br>
 * Name:     product_info_link<br>
 * Date:     Aug 24, 2004<br>
 * Purpose:  URL for the products info<br>
 * Input:<br>
 *         - products_id
 *
 * Examples: <{product_info_link products_id=17}>
 * Output:   http:// ... index.php?mp=products&amp;file=info&amp;products_id=17
 * @author   r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_html_href_link()
 */
function smarty_function_product_info_link($params, &$smarty)
{

  require_once $smarty->_get_plugin_filepath('function','html_href_link');

  $aModules = oos_get_modules();
  $aFilename =  oos_get_filename(); 

  $result = array();
  $link_params = array();
  $link_params = array('modul' => $aModules['products'],
                       'file' => $aFilename['product_info']);

  if (is_array($params)) {
    $result = array_merge($link_params, $params);
  } else {
    $smarty->trigger_error("products_info_link: extra attribute '$params' must an array", E_USER_NOTICE);
  }

  return smarty_function_html_href_link($result, $smarty);

}

?>