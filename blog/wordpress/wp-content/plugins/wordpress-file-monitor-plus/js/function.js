/*  Copyright 2012  Scott Cariss  (email : scott@l3rady.com)

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

jQuery(function ($) {
	$('select[name="sc_wpfmp_settings[cron_method]"]').change(function() {
		if($('select[name="sc_wpfmp_settings[cron_method]"]').val() == "wordpress") {
			$(this).parent().find("div").hide();
            $('select[name="sc_wpfmp_settings[file_check_interval]"]').parent().parent().show();
		} else {
			$(this).parent().find("div").show();
            $('select[name="sc_wpfmp_settings[file_check_interval]"]').parent().parent().hide();
		}
	}).trigger("change");

	$('select[name="sc_wpfmp_settings[notify_by_email]"]').change(function() {
		if($('select[name="sc_wpfmp_settings[notify_by_email]"]').val() == 1) {
			$('input[name="sc_wpfmp_settings[from_address]"]').parent().parent().show();
            $('input[name="sc_wpfmp_settings[notify_address]"]').parent().parent().show();
		} else {
			$('input[name="sc_wpfmp_settings[from_address]"]').parent().parent().hide();
            $('input[name="sc_wpfmp_settings[notify_address]"]').parent().parent().hide();
		}
	}).trigger("change");
});