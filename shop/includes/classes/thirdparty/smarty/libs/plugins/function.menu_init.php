<?php

//------------------------------------------------------------------------------
//  SmartyMenu 1.1
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

//  This library is distributed in the hope that it will be useful, but WITHOUT
//  ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
//  FITNESS FOR A PARTICULAR PURPOSE.  See the GNU Lesser General Public
//  License for more details.
//------------------------------------------------------------------------------

/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     menu_init
 * Purpose:  initialize menu, load css
 * -------------------------------------------------------------
 */
function smarty_function_menu_init($params, &$smarty)
{

    // optionally link in stylesheet
    if (!empty($params['css'])) {
    	$_css = '<link rel="stylesheet" type="text/css" href="'.$params['css'].'" />' . "\n";
    } else {
        $_css = null;
    }

    $_js = <<< EOT
<script type="text/javascript"><!--//--><![CDATA[//><!--

sfHover = function() {
    var sfEls = document.getElementById("nav").getElementsByTagName("LI");
    for (var i=0; i<sfEls.length; i++) {
        sfEls[i].onmouseover=function() {
            this.className+=" sfhover";
        }
        sfEls[i].onmouseout=function() {
            this.className=this.className.replace(new RegExp(" sfhover\\\\b"), "");
        }
    }
}
if (document.all) { //MS IE
    if (window.attachEvent)
        window.attachEvent("onload", sfHover);
    else { //IE 5.2 Mac does not support attachEvent
        var old = window.onload;
        window.onload = function() { if (old) old(); sfHover(); }
    }
}


//--><!]]></script>
EOT;

    return $_css . $_js;
}

/* vim: set expandtab: */

?>
