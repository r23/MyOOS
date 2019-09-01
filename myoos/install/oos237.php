<?php
/* ----------------------------------------------------------------------
   $Id: oos160.php,v 1.3 2009/01/13 21:29:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team
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


$table = $prefix_table . 'products_description';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_facebook_title` VARCHAR(255) NOT NULL AFTER `products_description_meta`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_facebook_description` VARCHAR( 255 ) NOT NULL AFTER `products_facebook_title`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_twitter_title` VARCHAR(255) NOT NULL AFTER `products_facebook_description`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `products_twitter_description` VARCHAR( 255 ) NOT NULL AFTER `products_twitter_title`");


$table = $prefix_table . 'categories_description';
$result = $db->Execute("ALTER TABLE " . $table . " ADD `categories_facebook_title` VARCHAR(255) NOT NULL AFTER `categories_description_meta`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `categories_facebook_description` VARCHAR( 255 ) NOT NULL AFTER `categories_facebook_title`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `categories_twitter_title` VARCHAR(255) NOT NULL AFTER `categories_facebook_description`");
$result = $db->Execute("ALTER TABLE " . $table . " ADD `categories_twitter_description` VARCHAR( 255 ) NOT NULL AFTER `categories_twitter_title`");

