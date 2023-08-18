<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
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
 * Examples: {categories_link category=17}
 * Output:   http:// ... index.php?content=shop&amp;category=17
 *
 * @author  r23 <info@r23.de>
 * @version 1.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_html_href_link()
 */
function smarty_function_categories_link($params, &$smarty)
{
    include_once MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins/function.html_href_link.php';

    $aContents =  oos_get_content();

    $result = [];
    $link_params = [];
    $link_params = ['content' => $aContents['shop']];

    if (is_array($params)) {
        $result = array_merge($link_params, $params);
    } else {
        throw new SmartyException("categories_link: extra attribute '$params' must an array", E_USER_NOTICE);
    }

    return smarty_function_html_href_link($result, $smarty);
}
