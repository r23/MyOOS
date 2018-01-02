<?php
/* ----------------------------------------------------------------------
   $Id: blocks.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
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

/*
$aBlocks[] = array(
	'heading' => 'Dashboard',
	'link' => oos_href_link_admin($aContents['default']),
	'icon' => 'fa fa-th-large',
	'active' => FALSE
);
*/
$aBlocks[] = array();


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
    $php_self = basename($_SERVER['PHP_SELF']);

	echo '<nav data-sidebar-anyclick-close="" class="sidebar">' . "\n" .
		'	<!-- START sidebar nav //-->' . "\n" .
		'	<ul class="nav">' . "\n" .
		'		<!-- Iterates over all sidebar items //-->' . "\n" .
		'		<li class="nav-heading ">' . "\n" .
		'			<span data-localize="sidebar.heading.HEADER">Dashboard</span>' . "\n" .
		'		</li>' . "\n";				
				
	foreach ($aBlocks as $panels ) {
		if ($panels['active'] == TRUE) {
			echo '<li class="active">' . "\n";
		} else {
			echo '<li>' . "\n";
		}
		
        # <li class="nav-heading ">
        #    <span data-localize="sidebar.heading.COMPONENTS">Components</span>
        # </li>

		if (!empty($panels)) {		
			echo '<a href="' . $panels['link'] . '#' . oos_strtolower($panels['heading']) . '" title="' . $panels['heading'] . '" data-toggle="collapse">' . "\n" .
				'	<i class="' . $panels['icon'] . '"></i>' . "\n" .
				'  <span data-localize="sidebar.nav.' . oos_strtolower($panels['heading']) . '.' . oos_strtoupper($panels['heading']) . '">' . $panels['heading'] . '</span>' . "\n" .
				'</a>' . "\n";			
		}
			
		if (is_array($panels['contents'])) {
			echo '<ul id="' . oos_strtolower($panels['heading']) . '" class="nav sidebar-subnav collapse">' . "\n" .
				 '	<li class="sidebar-subnav-header">' . $panels['heading'] . '</li>' . "\n";
				foreach ($panels['contents'] as $contents) {
					if ( ( $php_self == $contents['code'] ) 
						|| ((isset($_GET['gID'])) && ($_GET['gID'] == $contents['code']))
						|| ((isset($_GET['set'])) && ($_GET['set'] == $contents['code'])) ) {
						echo '<li class="active">' . "\n";
					} else {
						echo '<li>' . "\n";
					}
					
					echo '  <a href="' . $contents['link'] . '" title="' . $contents['title'] . '">' . "\n" .
						 '    <span data-localize="sidebar.nav.' . oos_strtolower($panels['heading']) . '.' . oos_strtoupper($contents['title']) . '">' . $contents['title'] . '</span>' . "\n" .
						 '  </a>' . "\n" .
						 '</li>' . "\n";
				}
			echo '</ul>' . "\n";
		}
		echo '</li>' . "\n";
	}
		  
	
	echo '     </ul>' . "\n" .
         '</nav>' . "\n";
}		