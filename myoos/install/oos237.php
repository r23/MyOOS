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

if (!$prefix_table == '') $prefix_table = $prefix_table . '';

$today = date("Y-m-d H:i:s");

$table = $prefix_table . 'products_description';
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `products_facebook_title` VARCHAR(255) NOT NULL AFTER `products_description_meta`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `products_facebook_description` VARCHAR( 255 ) NOT NULL AFTER `products_facebook_title`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `products_twitter_title` VARCHAR(255) NOT NULL AFTER `products_facebook_description`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `products_twitter_description` VARCHAR( 255 ) NOT NULL AFTER `products_twitter_title`");


$table = $prefix_table . 'categories_description';
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `categories_facebook_title` VARCHAR(255) NOT NULL AFTER `categories_description_meta`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `categories_facebook_description` VARCHAR( 255 ) NOT NULL AFTER `categories_facebook_title`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `categories_twitter_title` VARCHAR(255) NOT NULL AFTER `categories_facebook_description`");
$result = $dbconn->Execute("ALTER TABLE " . $table . " ADD `categories_twitter_description` VARCHAR( 255 ) NOT NULL AFTER `categories_twitter_title`");


$result = $dbconn->Execute("INSERT INTO " . $prefix_table . "configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('OPEN_GRAPH_THUMBNAIL', '', 12, 6, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix_table . "configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('SITE_NAME', '', 12, 7, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix_table . "configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('TWITTER_CARD', 'summary_large_image', 12, 8, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, 'oos_cfg_select_option(array(\'summary_large_image\', \'summary\'),')") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
$result = $dbconn->Execute("INSERT INTO " . $prefix_table . "configuration (configuration_key, configuration_value, configuration_group_id, sort_order, last_modified, date_added, use_function, set_function) VALUES ('TWITTER_CREATOR', '', 12, 9, NULL, " . $dbconn->DBTimeStamp($today) . ", NULL, NULL)") OR die ("<b>".NOTUPDATED . $prefix_table . "configuration</b>");
