<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {categories_link} function plugin
 *
 * Type:     function<br>
 * Name:     categories_link<br>
 * Date:     Oct 27, 2008<br>
 * Purpose:  URL for the categorie info<br>
 * Input:<br>
 *         - cPath
 *
 * Examples: {categories_link cPath=17}
 * Output:   http:// ... index.php?mp=mp&amp;file=shop&amp;cPath=17
 * @author   r23 <info@r23.de>
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_html_href_link()
 */
function smarty_function_categories_link($params, &$smarty)
{

  require_once(MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins/function.html_href_link.php');

  $aModules = oos_get_modules();
  $aFilename =  oos_get_filename();

  $result = array();
  $link_params = array();
  $link_params = array('modul' => $aModules['main'],
                       'file' => $aFilename['shop']);

  if (is_array($params)) {
    $result = array_merge($link_params, $params);
  } else {
    throw new SmartyException("categories_link: extra attribute '$params' must an array", E_USER_NOTICE);
  }

  return smarty_function_html_href_link($result, $smarty);

}

