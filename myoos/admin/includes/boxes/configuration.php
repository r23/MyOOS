<?php
/**
   ----------------------------------------------------------------------
   $Id: configuration.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.16 2002/03/16 00:20:11 hpdl
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
$bActive = ($_SESSION['selected_box'] == 'configuration') ? true : false;

$aBlocks[] = ['heading' => BOX_HEADING_CONFIGURATION, 'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(['selected_box']) . 'selected_box=configuration'), 'icon' => 'fa fa-cogs', 'active' => $bActive];

$configuration_groups_result = $dbconn->Execute("SELECT configuration_group_id AS cg_id FROM " . $oostable['configuration_group'] . " WHERE visible = '1' ORDER BY sort_order");

while ($configuration_groups = $configuration_groups_result->fields) {
    $aBlocks[sizeof($aBlocks) - 1]['contents'][] = ['code' => $configuration_groups['cg_id'], 'title' => constant(strtoupper($configuration_groups['cg_id'] . '_TITLE')), 'link' => oos_href_link_admin($aContents['configuration'], 'selected_box=configuration&amp;gID=' . $configuration_groups['cg_id'])];

    // Move that ADOdb pointer!
    $configuration_groups_result->MoveNext();
}
