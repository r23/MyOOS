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


$table = $prefix_table . 'customers';
$result = $db->Execute("ALTER TABLE " . $table . " CHANGE COLUMN `customers_password` `customers_password` VARCHAR(255) NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'admin';
$result = $db->Execute("ALTER TABLE " . $table . " CHANGE COLUMN `admin_password` `admin_password` VARCHAR(255) NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle">&nbsp;<font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}