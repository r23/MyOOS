<?php
/**
   ----------------------------------------------------------------------
   $Id: content.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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
$bActive = ($_SESSION['selected_box'] == 'content') ? true : false;

$aBlocks[] = ['heading' => BOX_HEADING_CONTENT, 'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(['selected_box']) . 'selected_box=content'), 'icon' => 'fas fa-object-ungroup', 'active' => $bActive, 'contents' => [['code' => $aContents['content_block'], 'title' => BOX_CONTENT_BLOCK, 'link' => oos_admin_files_boxes('content_block', 'selected_box=content')], ['code' => $aContents['content_page_type'], 'title' => BOX_CONTENT_PAGE_TYPE, 'link' => oos_admin_files_boxes('content_page_type', 'selected_box=content')]]];
