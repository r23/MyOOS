<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_get_link} function plugin
 *
 * Type:     function
 * Name:     html_get_link
 * @Version: $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/08 13:34:16 $
 * -------------------------------------------------------------
 */

function smarty_function_html_get_link($params, &$smarty)
{
    $link = OOS_HTTPS_SERVER . OOS_SHOP . 'index.php';

    return $link;
}
