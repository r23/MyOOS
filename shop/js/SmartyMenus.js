//------------------------------------------------------------------------------
//  SmartyMenus Javascript Library version 1.1
//  http://www.phpinsider.com/php/code/SmartyMenus/
//
//  Copyright(c) 2004-2005 New Digital Group, Inc.. All rights reserved.
//
//  SmartyMenus are an implementation of the Suckerfish Dropdowns
//  by Patrick Griffiths and Dan Webb.
//  http://htmldog.com/articles/suckerfish/dropdowns/
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

sfHover = function() {
	var sfEls = document.getElementById("nav").getElementsByTagName("LI");
	for (var i=0; i<sfEls.length; i++) {
		sfEls[i].onmouseover=function() {
			this.className+=" sfhover";
		}
		sfEls[i].onmouseout=function() {
			this.className=this.className.replace(new RegExp(" sfhover\\b"), "");
		}
	}
}
if (window.attachEvent) window.attachEvent("onload", sfHover);
