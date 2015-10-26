<?php
/* ----------------------------------------------------------------------
   $Id: blocks.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------  */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

$aBlocks = array();

$aBlocks[] = array(
	'heading' => 'Dashboard',
	'link' => oos_href_link_admin($aContents['default']),
	'icon' => 'fa fa-th-large',
	'active' => FALSE
);


if (oos_admin_check_boxes('administrator.php') == TRUE) {
	include 'includes/boxes/administrator.php';
}
if (oos_admin_check_boxes('configuration.php') == TRUE) {
	include 'includes/boxes/configuration.php';
}
if (oos_admin_check_boxes('catalog.php') == TRUE) {
	include 'includes/boxes/catalog.php';
}
if (oos_admin_check_boxes('content.php') == TRUE) {
	include 'includes/boxes/content.php';
}
if (oos_admin_check_boxes('modules.php') == TRUE) {
	include 'includes/boxes/modules.php';
}
if (oos_admin_check_boxes('plugins.php') == TRUE) {
	include 'includes/boxes/plugins.php';
}
if (oos_admin_check_boxes('customers.php') == TRUE) {
	include 'includes/boxes/customers.php';
}
if (oos_admin_check_boxes('taxes.php') == TRUE) {
    include 'includes/boxes/taxes.php';
}
if (oos_admin_check_boxes('localization.php') == TRUE) {
	include 'includes/boxes/localization.php';
}

if (oos_admin_check_boxes('reports.php') == TRUE) {
    include 'includes/boxes/reports.php';
}
if (oos_admin_check_boxes('tools.php') == TRUE) {
	include 'includes/boxes/tools.php';
}

if (oos_admin_check_boxes('gv_admin.php') == TRUE) {
	include 'includes/boxes/gv_admin.php'; 
}

if (oos_admin_check_boxes('export.php') == TRUE) {
    include 'includes/boxes/export.php';
}
if (oos_admin_check_boxes('information.php') == TRUE) {
	include 'includes/boxes/information.php';
}

if (is_array($aBlocks)) {

	echo '       <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">';
				
				
	foreach ($aBlocks as $panels ) {
		if ($panels['active'] == TRUE) {
			echo '<li class="active">';
		} else {
			echo '<li>';
		}
		echo '<a href="' . $panels['link'] . '"><i class="' . $panels['icon'] . '"></i>' .
			'<span class="nav-label">' . $panels['heading'] . '</span><span class="fa arrow"></span></a>';

		if (is_array($panels['contents'])) {
			echo '<ul class="nav nav-second-level">';
				foreach ($panels['contents'] as $contents) {
					echo '<li><a href="' . $contents['link'] . '">' . $contents['title'] . '</a></li>';
				}
			echo '</ul>';
		}
		echo '</li>';
	}

	
	echo '     </ul></div>
        </nav>';
}		