<?php
/**
   ----------------------------------------------------------------------
   $Id: plugins.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'plugins') ? true : false;

$aBlocks[] = ['heading' => BOX_HEADING_PLUGINS, 'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(['selected_box']) . 'selected_box=plugins'), 'icon' => 'fa fa-plug', 'active' => $bActive, 'contents' => [['code' => $aContents['plugins'], 'title' => BOX_PLUGINS_EVENT, 'link' => oos_href_link_admin($aContents['plugins'], 'selected_box=plugins')]]];
