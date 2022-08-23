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

if (!$prefix_table == '') {
    $prefix_table = $prefix_table . '_';
}

// admin
$table = $prefix_table . 'admin';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `admin_2fa` VARCHAR(96) NULL DEFAULT NULL AFTER `admin_email_address`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE  " . $table . " ADD `admin_2fa_active` VARCHAR(1) NULL DEFAULT '0' AFTER `admin_2fa`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// admin_files
$table = $prefix_table . 'admin_files';
$result = $db->Execute("INSERT INTO " . $table . " (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('categories_slider', 0, 3, 1)");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// customers
$table = $prefix_table . 'customers';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `customers_2fa` VARCHAR(96) NULL DEFAULT NULL AFTER `customers_email_address`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE  " . $table . " ADD `customers_2fa_active` VARCHAR(1) NULL DEFAULT '0' AFTER `customers_2fa`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

// orders_products
$table = $prefix_table . 'orders_products';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `products_old_electrical_equipment` TINYINT NOT NULL DEFAULT '0' AFTER `products_serial_number`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

// products
$table = $prefix_table . 'products';
$result = $db->Execute("ALTER TABLE  " . $table . " CHANGE `products_product_quantity` `products_product_quantity` DECIMAL(8.4) NULL DEFAULT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

// products_attributes
$table = $prefix_table . 'products_attributes';
$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `options_values_units_id` INT( 11 ) DEFAULT '0' NOT NULL AFTER `options_values_base_unit`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
}


// products_units
$table = $prefix_table . 'products_units';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `unit_of_measure` VARCHAR(30) NOT NULL AFTER `products_unit_name`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

// specials
$table = $prefix_table . 'specials';
$result = $db->Execute("ALTER TABLE  " . $table . " ADD `specials_cross_out_price` DECIMAL(10,4) NULL DEFAULT NULL AFTER `specials_new_products_price`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE  " . $table . " ADD `specials_start_date` DATETIME NOT NULL AFTER `specials_date_added`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_models';
$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `models_hdr_name` VARCHAR(255) AFTER `models_hdr`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `models_object_scalar` VARCHAR(4) AFTER `models_object_rotation`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `models_hdr_url` VARCHAR(255) AFTER `models_hdr_name`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `models_hdr_author` VARCHAR(255) AFTER `models_hdr_url`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$result = $db->Execute("ALTER TABLE " . $table . " ADD COLUMN `models_hdr_author_url` VARCHAR(255) AFTER `models_hdr_author`");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$table = $prefix_table . 'products_attributes_download';
$result = $db->Execute("ALTER TABLE  " . $table . "  ADD `download_id` INT(11) NOT NULL FIRST");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE  " . $table . "  DROP PRIMARY KEY");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("ALTER TABLE  " . $table . "  CHANGE `download_id` `download_id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`download_id`)");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}
