<?php
/**
   ----------------------------------------------------------------------
   $Id: blocks.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

/*
$aBlocks[] = array(
    'heading' => 'Dashboard',
    'link' => oos_href_link_admin($aContents['default']),
    'icon' => 'fa fa-th-large',
    'active' => FALSE
);
*/
$aBlocks[] = [];

if (oos_admin_check_boxes('customers.php') == true) {
    include 'includes/boxes/customers.php';
}
if (oos_admin_check_boxes('catalog.php') == true) {
    include 'includes/boxes/catalog.php';
}
if (oos_admin_check_boxes('reports.php') == true) {
    include 'includes/boxes/reports.php';
}
if (oos_admin_check_boxes('configuration.php') == true) {
    include 'includes/boxes/configuration.php';
}
if (oos_admin_check_boxes('content.php') == true) {
    include 'includes/boxes/content.php';
}
if (oos_admin_check_boxes('modules.php') == true) {
    include 'includes/boxes/modules.php';
}
if (oos_admin_check_boxes('plugins.php') == true) {
    include 'includes/boxes/plugins.php';
}
if (oos_admin_check_boxes('taxes.php') == true) {
    include 'includes/boxes/taxes.php';
}
if (oos_admin_check_boxes('localization.php') == true) {
    include 'includes/boxes/localization.php';
}
if (oos_admin_check_boxes('tools.php') == true) {
    include 'includes/boxes/tools.php';
}
if (oos_admin_check_boxes('gv_admin.php') == true) {
    include 'includes/boxes/gv_admin.php';
}
if (oos_admin_check_boxes('export.php') == true) {
    include 'includes/boxes/export.php';
}
if (oos_admin_check_boxes('information.php') == true) {
    include 'includes/boxes/information.php';
}
if (oos_admin_check_boxes('administrator.php') == true) {
    include 'includes/boxes/administrator.php';
}
if (is_array($aBlocks)) {
    $php_self = basename((string) filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL));

    echo '<nav class="sidebar" data-sidebar-anyclick-close="">' . "\n" .
        '	<!-- START sidebar nav //-->' . "\n" .
        '	<ul class="sidebar-nav">' . "\n" .
        '		<!-- Iterates over all sidebar items //-->' . "\n" .
        '		<li class="nav-heading">' . "\n" .
        '			<span data-localize="sidebar.heading.HEADER">Dashboard</span>' . "\n" .
        '		</li>' . "\n";

    foreach ($aBlocks as $panels) {
        if (isset($panels['active']) && ($panels['active'] == true)) {
            echo '<li class="active">' . "\n";
        } else {
            echo '<li class=" ">' . "\n";
        }

        if (!empty($panels)) {
            echo '<a href="#' . oos_strtolower($panels['heading']) . '" title="' . $panels['heading'] . '" data-toggle="collapse">' . "\n" .
                '	<i class="' . $panels['icon'] . '" aria-hidden="true"></i>' . "\n" .
                '  <span data-localize="sidebar.nav.' . oos_strtolower($panels['heading']) . '.' . oos_strtoupper($panels['heading']) . '">' . $panels['heading'] . '</span>' . "\n" .
                '</a>' . "\n";
        }

        if (isset($panels['contents']) && (is_array($panels['contents']))) {
            echo '<ul class="sidebar-nav sidebar-subnav collapse" id="' . oos_strtolower($panels['heading']) . '">' . "\n" .
                 '	<li class="sidebar-subnav-header">' . $panels['heading'] . '</li>' . "\n";
            foreach ($panels['contents'] as $contents) {
                if (($php_self == $contents['code'])
                    || ((isset($_GET['gID'])) && ($_GET['gID'] == $contents['code']))
                    || ((isset($_GET['set'])) && ($_GET['set'] == $contents['code']))
                ) {
                    echo '<li class="active">' . "\n";
                } else {
                    echo '<li class=" ">' . "\n";
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
