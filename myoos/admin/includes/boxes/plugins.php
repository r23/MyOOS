<?php
/**
   ----------------------------------------------------------------------
   $Id: plugins.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$bActive = ($_SESSION['selected_box'] == 'plugins') ? true : false;

$aBlocks[] = array(
    'heading' => BOX_HEADING_PLUGINS,
    'link' => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=plugins'),
    'icon' => 'fa fa-plug',
    'active' => $bActive,
    'contents' => array(
        array(
            'code' => $aContents['plugins'],
            'title' => BOX_PLUGINS_EVENT,
            'link' => oos_href_link_admin($aContents['plugins'], 'selected_box=plugins')
        ),
    ),
);
