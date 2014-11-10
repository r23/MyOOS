<?php
/* ----------------------------------------------------------------------
   $Id: configuration_upgrade.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

global $db, $prefix_table;

$table = $prefix_table . 'configuration';

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_textarea(' WHERE set_function = 'oosCfgTextarea('");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_country_list(' WHERE set_function = 'oosCfgPullDownCountryList('");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_zone_list(' WHERE set_function = 'oosCfgPullDownZoneList('");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'true\', \'false\'),' WHERE set_function = 'oosCfgSelectOption(array(\'true\', \'false\'),'");

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'and\', \'or\'),' WHERE set_function = 'oosCfgSelectOption(array(\'and\', \'or\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'True\', \'False\'),' WHERE set_function = 'oosCfgSelectOption(array(\'True\', \'False\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'sendmail\', \'smtp\'),' WHERE set_function = 'oosCfgSelectOption(array(\'sendmail\', \'smtp\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'products_name\', \'date_expected\'),' WHERE set_function = 'oosCfgSelectOption(array(\'products_name\', \'date_expected\'),'");

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'Path and article\', \'only article\', \'no additives\'),' WHERE set_function = 'oosCfgSelectOption(array(\'Path and article\', \'only article\', \'no additives\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'),' WHERE set_function = 'oosCfgSelectOption(array(\'None\', \'Standard\', \'Credit Note\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'no-cache\', \'cache\'),' WHERE set_function = 'oosCfgSelectOption(array(\'no-cache\', \'cache\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'None\', \'Standard\', \'Credit Note\'),' WHERE set_function = 'oosCfgSelectOption(array(\'None\', \'Standard\', \'Credit Note\'),'");

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'LF\', \'CRLF\'),' WHERE set_function = 'oosCfgSelectOption(array(\'LF\', \'CRLF\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'national\', \'international\', \'both\'),' WHERE set_function = 'oosCfgSelectOption(array(\'national\', \'international\', \'both\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'Meta Tag with categories edit\', \'description tag by category description replace\', \'no description tag per category\'),' WHERE set_function = 'oosCfgSelectOption(array(\'Meta Tag with categories edit\', \'description tag by category description replace\', \'no description tag per category\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'asc\', \'desc\'),' WHERE set_function = 'oosCfgSelectOption(array(\'asc\', \'desc\'),'");

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'true\', \'fck\', \'false\'),' WHERE set_function = 'oosCfgSelectOption(array(\'true\', \'fck\', \'false\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'Meta Tag with article edit\', \'description tag by article description replace\', \'no description tag per article\'),' WHERE set_function = 'oosCfgSelectOption(array(\'Meta Tag with article edit\', \'description tag by article description replace\', \'no description tag per article\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_select_option(array(\'INDEX,FOLLOW\', \'INDEX,NOFOLLOW\', \'NOINDEX,FOLLOW\', \'NOINDEX,NOFOLLOW\'),' WHERE set_function = 'oosCfgSelectOption(array(\'INDEX,FOLLOW\', \'INDEX,NOFOLLOW\', \'NOINDEX,FOLLOW\', \'NOINDEX,NOFOLLOW\'),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_zone_classes(' WHERE set_function = 'oosCfgPullDownZoneClasses('");

$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_tax_classes(' WHERE set_function = 'oosCfgPullDownTaxClasses('");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_order_statuses(' WHERE set_function = 'oosCfgPullDownOrderStatuses('");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_cfg_pull_down_country_list(' WHERE set_function = 'oosCfgPullDownCountryList('");


$result = $db->Execute("UPDATE " . $table . " SET use_function = 'oos_cfg_get_zone_class_title' WHERE use_function = 'oosCfgGetZoneClassTitle'");
$result = $db->Execute("UPDATE " . $table . " SET use_function = 'oos_cfg_get_order_status_name' WHERE use_function = 'oosCfgGetOrderStatusName'");
$result = $db->Execute("UPDATE " . $table . " SET use_function = 'oos_cfg_get_tax_class_title' WHERE use_function = 'oosCfgGetTaxClassTitle'");
$result = $db->Execute("UPDATE " . $table . " SET use_function = 'oos_cfg_get_country_name' WHERE use_function = 'oosCfgGetCountryName'");
$result = $db->Execute("UPDATE " . $table . " SET use_function = 'oos_cfg_get_zone_name' WHERE use_function = 'oosCfgGetZoneName'");

$table = $prefix_table . 'block';
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_block_select_option(array(\'left\', \'right\'),' WHERE set_function = 'oosBlockSelectOption(array(\'left\', \'right\',),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_block_select_option(array(\'left\', \'right\'),' WHERE set_function = 'oosBlockSelectOption(array(\'left\', \'right\', \'header\',),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_block_select_option(array(\'left\', \'right\'),' WHERE set_function = 'oosBlockSelectOption(array(\'left\', \'right\', ),'");
$result = $db->Execute("UPDATE " . $table . " SET set_function = 'oos_block_select_option(array(\'left\', \'right\'),' WHERE set_function = 'oosBlockSelectOption(array(\'left\', \'right\'),'");


?>