<?php
/*
Plugin Name: Remove IP
Plugin URI: http://wordpress.org/plugins/remove-ip/
Description: A simple plugin to not lop IPs from comments.
Version: 0.1
Author: guido
Author URI: http://www.bruo.org
*/

/*  Copyright 2009-2013  Remove IP  (email : guido@bruo.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

add_filter('pre_comment_user_ip', 'pre_comment_anon_ip');

function pre_comment_anon_ip()
{    
	$REMOTE_ADDR = "127.0.0.1";
	return $REMOTE_ADDR;
}


?>
