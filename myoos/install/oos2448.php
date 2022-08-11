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

$today = date("Y-m-d H:i:s");


require_once 'oostables2448.php';

$table = $prefix_table . 'admin_files';
$result = $db->Execute("INSERT INTO " . $table . " (admin_files_name, admin_files_is_boxes, admin_files_to_boxes, admin_groups_id) VALUES ('product_video', 0, 3, 1)");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


// configuration
$table = $prefix_table . 'configuration';
$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('PRODUCTS_CHARTS', 'true', 5, 12, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("DELETE FROM " . $table . " WHERE configuration_key = 'PRODUCTS_NOTIFICATIONS'");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('WARN_INSTALL_EXISTENCE', 'true', 11, 8, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('WARN_CONFIG_WRITEABLE', 'true', 11, 9, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}

$result = $db->Execute("INSERT INTO " . $table . " (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('WARN_DOWNLOAD_DIRECTORY_NOT_READABLE', 'true', 11, 10, NULL, " . $db->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'true\', \'false\'),')");
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