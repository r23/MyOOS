<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage plugins
 */

/**
 * Smarty {product_link} function plugin
 *
 * Type:     function<br>
 * Name:     product_info_link<br>
 * Date:     Aug 24, 2004<br>
 * Purpose:  URL for the products info<br>
 * Input:<br>
 *         - products_id
 *
 * Examples: <{product_link products_id=17}>
 * Output:   http:// ... index.php?content=product_info&amp;products_id=17
 *
 * @author  r23 <info@r23.de>
 * @version 1.0
 * @param   array
 * @param   Smarty
 * @return  string
 * @uses    smarty_function_html_href_link()
 */
function smarty_function_product_link($params, &$smarty)
{
    include_once MYOOS_INCLUDE_PATH . '/includes/lib/smarty-plugins/function.html_href_link.php';

    $aContents =  oos_get_content();

    $result = [];
    $link_params = [];
    $link_params = ['content' => $aContents['product_info']];

    if (is_array($params)) {
        $result = array_merge($link_params, $params);
    } else {
        throw new SmartyException("products_info_link: extra attribute '$params' must an array", E_USER_NOTICE);
    }

    return smarty_function_html_href_link($result, $smarty);
}
