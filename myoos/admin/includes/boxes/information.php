<?php
/**
   ----------------------------------------------------------------------
   $Id: information.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

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

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'information') ? true : false;

$aBlocks[] = array(
    'heading' => BOX_HEADING_INFORMATION,
    'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(array('selected_box')) . 'selected_box=information'),
    'icon' => 'fa fa-tasks',
    'active' => $bActive,
    'contents' => array(
        array(
            'code' => $aContents['information'],
            'title' => BOX_INFORMATION,
            'link' => oos_admin_files_boxes('information', 'selected_box=information')
        ),

    ),
);
