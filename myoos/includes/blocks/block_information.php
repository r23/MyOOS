<?php
/**
   ----------------------------------------------------------------------
   $Id: block_information.php 412 2013-06-13 18:12:58Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: information.php,v 1.1.2.1 2003/04/18 17:42:37 wilt
   orig: information.php,v 1.6 2003/02/10 22:31:00 hpdl
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

$informationtable = $oostable['information'];
$information_descriptiontable = $oostable['information_description'];
$sql = "SELECT id.information_id, id.information_name, i.sort_order
        FROM $informationtable i,
             $information_descriptiontable id
        WHERE id.information_id = i.information_id AND
              i.status = '1' AND
              id.information_languages_id = '" . intval($nLanguageID) . "'
         ORDER BY i.sort_order DESC";
$smarty->assign('information', $dbconn->GetAll($sql));
$smarty->assign('block_heading_information', $block_heading);
