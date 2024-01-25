<?php
/**
   ----------------------------------------------------------------------
   $Id: tools.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: tools.php,v 1.20 2002/03/16 00:20:11 hpdl
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

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'tools') ? true : false;

$aBlocks[] = ['heading' => BOX_HEADING_TOOLS, 'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(['selected_box']) . 'selected_box=tools'), 'icon' => 'fa fa-database', 'active' => $bActive, 'contents' => [['code' => $aContents['mail'], 'title' => BOX_TOOLS_MAIL, 'link' => oos_admin_files_boxes('mail', 'selected_box=tools')], ['code' => $aContents['newsletters'], 'title' => BOX_TOOLS_NEWSLETTER_MANAGER, 'link' => oos_admin_files_boxes('newsletters', 'selected_box=tools')]]];
