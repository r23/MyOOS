<?php

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     assign_array
 *
 *
 * Examples: {assign_array var="foo" values="bar1,bar2"}
 *           {assign_array var="foo" values="bar1;bar2;bar3" delimiter=";"}
 * -------------------------------------------------------------
 */
function smarty_function_assign_array($params, &$smarty)
{
    extract($params);

    if (empty($var)) {
        throw new SmartyException ("assign_array: missing 'var' parameter");
        return;
    }

    if (!in_array('values', array_keys($params))) {
        throw new SmartyException ("assign_array: missing 'values' parameter");
        return;
    }
    
    return $values[$var];
}


?>