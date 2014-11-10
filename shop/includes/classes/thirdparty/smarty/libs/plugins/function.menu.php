<?php

//------------------------------------------------------------------------------
//  SmartyMenu version 1.1
//  http://www.phpinsider.com/php/code/SmartyMenu/
//
//  SmartyMenu is an implementation of the Suckerfish Dropdowns
//  by Patrick Griffiths and Dan Webb.
//  http://htmldog.com/articles/suckerfish/dropdowns/
//
//  Copyright(c) 2004-2005 New Digital Group, Inc.. All rights reserved. 
//
//  This library is free software; you can redistribute it and/or modify it
//  under the terms of the GNU Lesser General Public License as published by
//  the Free Software Foundation; either version 2.1 of the License, or (at 
//  your option) any later version.
//
//  This library is distributed in the hope that it will be useful, but WITHOUT
//  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
//  FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
//  License for more details. 
//------------------------------------------------------------------------------

// number of chars to indent unordered list level
define('SMARTYMENU_INDENT', 3);

function smarty_function_menu_render_element($element,$level) {

    $_output = '';

    if(isset($element['link']))
        $_text = "<a href=\"" . htmlspecialchars($element['link']) . "\">" . htmlspecialchars($element['text']) . "</a>";
    else
        $_text = '<span class="nolink">' . htmlspecialchars($element['text']). '</span>';

    if(isset($element['submenu'])) {

        $_class = isset($element['class']) ? $element['class'] : 'nav_parent';

        $_output .= str_repeat(' ', $level * SMARTYMENU_INDENT) . "<li class=\"$_class\">" . $_text . "\n";
        $_output .= str_repeat(' ', $level * SMARTYMENU_INDENT) . "<ul>\n";

        foreach($element['submenu'] as $_submenu) {
            $_output .=  smarty_function_menu_render_element($_submenu, $level + 1);
        }

        $_output .= str_repeat(' ', $level * SMARTYMENU_INDENT) . "</ul>\n";
        $_output .= str_repeat(' ', $level * SMARTYMENU_INDENT) . "</li>\n";

    } else {
        $_class = isset($element['class']) ? $element['class'] : 'nav_child';
        $_output .= str_repeat(' ', $level * SMARTYMENU_INDENT) . "<li class=\"$_class\">" . $_text . "</li>\n";
    }

    return $_output;
}

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     menu
 * Purpose:  generate menu
 * -------------------------------------------------------------
 */
function smarty_function_menu($params, &$smarty)
{
    if(empty($params['data'])) {
        $smarty->trigger_error("menu_init: missing 'data' parameter");
        return false;
    }

    $_id = isset($params['id']) ? $params['id'] : 'nav';

    $_output = "<ul id=\"$_id\">\n";

    foreach($params['data'] as $_element) {
        $_output .= smarty_function_menu_render_element($_element, 1);   
    }

    $_output .= "</ul>\n";

    return $_output;
}

/* vim: set expandtab: */

?>
