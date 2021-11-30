<?php
/* ----------------------------------------------------------------------
   $Id: oos160.php,v 1.3 2009/01/13 21:29:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team
   ----------------------------------------------------------------------
   Based on:

   File: pn64.php,v 1.45 2002/03/16 15:24:37 johnnyrocket
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

global $db, $prefix_table, $currentlang;

if (!$prefix_table == '') $prefix_table = $prefix_table . '_';

$today = date("Y-m-d H:i:s");

// configuration
$table = $prefix_table . 'configuration';
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('TAKE_BACK_OBLIGATION', 'true', 1, 22, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'))");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// customers_basket
$table = $prefix_table . 'customers_basket';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `free_redemption` VARCHAR(1) NOT NULL DEFAULT ''  AFTER `customers_basket_quantity`");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

// customers_wishlist
$table = $prefix_table . 'customers_wishlist';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `free_redemption` VARCHAR(1) NOT NULL DEFAULT '' AFTER `customers_wishlist_quantity`");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// orders_products
$table = $prefix_table . 'orders_products';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `products_free_redemption` VARCHAR(1) NOT NULL DEFAULT '' AFTER `customers_wishlist_quantity`");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}



// products
$table = $prefix_table . 'products';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_old_electrical_equipment` TINYINT NOT NULL DEFAULT '0' AFTER `products_units_id`");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
	echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// products_description
$table = $prefix_table . 'products_description';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_old_electrical_equipment_description`  TEXT NULL DEFAULT NULL AFTER `products_essential_characteristics`");
if ($result === false) {
	echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
	echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

