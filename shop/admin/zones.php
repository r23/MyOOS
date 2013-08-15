<?php
/* ----------------------------------------------------------------------
   $Id: zones.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: zones.php,v 1.21 2002/03/17 18:07:48 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/oos_main.php';


if (isset($_GET['page']) && is_numeric($_GET['page']))
{
	$nPage =  intval($_GET['page']);
} else {
	$nPage = 1; 
}

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (!empty($action)) {
	switch ($action) {
		case 'insert':
			$zone_country_id = oos_db_prepare_input($_POST['zone_country_id']);
			$zone_code = oos_db_prepare_input($_POST['zone_code']);
			$zone_name = oos_db_prepare_input($_POST['zone_name']);

			$zonestable = $oostable['zones'];
			$dbconn->Execute("INSERT INTO $zonestable (zone_country_id, zone_code, zone_name) VALUES ('" . oos_db_input($zone_country_id) . "', '" . oos_db_input($zone_code) . "', '" . oos_db_input($zone_name) . "')");
			oos_redirect_admin(oos_href_link_admin($aFilename['zones']));
			break;

		case 'save':
			$zone_id = oos_db_prepare_input($_GET['cID']);
			$zone_country_id = oos_db_prepare_input($_POST['zone_country_id']);
			$zone_code = oos_db_prepare_input($_POST['zone_code']);
			$zone_name = oos_db_prepare_input($_POST['zone_name']);
		
			$zonestable = $oostable['zones'];
			$dbconn->Execute("UPDATE $zonestable SET zone_country_id = '" . oos_db_input($zone_country_id) . "', zone_code = '" . oos_db_input($zone_code) . "', zone_name = '" . oos_db_input($zone_name) . "' WHERE zone_id = '" . oos_db_input($zone_id) . "'");
			oos_redirect_admin(oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&amp;cID=' . $zone_id));
			break;

      case 'delete':
			$zone_id = oos_db_prepare_input($_GET['cID']);
	
			$zonestable = $oostable['zones'];
			$dbconn->Execute("DELETE FROM $zonestable WHERE zone_id = '" . oos_db_input($zone_id) . "'");
			oos_redirect_admin(oos_href_link_admin($aFilename['zones'], 'page=' . $nPage));
			break;
	}
}

$aTemplate['page'] = 'default/page/zones.tpl';

require_once 'includes/oos_system.php';
require_once 'includes/oos_blocks.php';


	$zonestable = $oostable['zones'];
	$countriestable = $oostable['countries'];
	$zones_result_raw = "SELECT z.zone_id, c.countries_id, c.countries_name, z.zone_name, z.zone_code, z.zone_country_id 
                      FROM $zonestable z,
                           $countriestable c
                      WHERE z.zone_country_id = c.countries_id 
                      ORDER BY c.countries_name, z.zone_name";
	$zones_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $zones_result_raw, $zones_result_numrows);
	$zones_result = $dbconn->Execute($zones_result_raw);
	
	$aZones = array();
	while ($zones = $zones_result->fields) {
		  
	  $edit_link = oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&amp;cID=' . $zones['zone_id'] . '&amp;action=edit');
	  $delete_link =oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&amp;cID=' . $zones['zone_id'] . '&amp;action=delete');
	  $info_link = oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&amp;cID=' . $zones['zone_id']);

      $aZones[] = array('zone_id' => $zones['zone_id'],
						'countries_name' => $zones['countries_name'],
                        'zone_code' => $zones['zone_code'],
						'zone_name'=> $zones['zone_name'],
                        'edit_link' => $edit_link,
						'delete_link' => $delete_link,
                        'info_link' => $info_link);

      // Move that ADOdb pointer!
      $zones_result->MoveNext();
    }

    $smarty->assign('zones', $aZones);


$smarty->assign('body', 'zones');
$smarty->assign('form_action', oos_draw_form('zones', $aFilename['zones'], 'page=' . $nPage));
$smarty->assign('new_zone', oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&amp;action=new'));
$smarty->assign('display_count', $zones_split->display_count($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_ZONES));
$smarty->assign('display_links', $zones_split->display_pagination($zones_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage, '', $aFilename['zones']));

// display the template
$smarty->display($aTemplate['page']);

require 'includes/oos_nice_exit.php';   
  
  
/*

  $heading = array();
  $contents = array();

  switch ($action) {
    case 'new':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('zones', $aFilename['zones'], 'page=' . $nPage . '&action=insert'));
      $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . oos_draw_input_field('zone_name'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_CODE . '<br />' . oos_draw_input_field('zone_code'));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_pull_down_menu('zone_country_id', oos_get_countries()));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('insert','insert_off.gif', IMAGE_INSERT) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['zones'], 'page=' . $nPage) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'edit':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('zones', $aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=save'));
      $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . oos_draw_input_field('zone_name', $cInfo->zone_name));
      $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_CODE . '<br />' . oos_draw_input_field('zone_code', $cInfo->zone_code));
      $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . '<br />' . oos_draw_pull_down_menu('zone_country_id', oos_get_countries(), $cInfo->countries_id));
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('update','update_off.gif', IMAGE_UPDATE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '">' . oos_image_swap_button('cancel','cancel_off_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_ZONE . '</b>');

      $contents = array('form' => oos_draw_form('zones', $aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=deleteconfirm'));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $cInfo->zone_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_image_swap_submits('delete','delete_off.gif', IMAGE_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id) . '">' . oos_image_swap_button('cancel','cancel_off.gif', IMAGE_CANCEL) . '</a>');
      break;

    default:
      if (isset($cInfo) && is_object($cInfo)) {
        $heading[] = array('text' => '<b>' . $cInfo->zone_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=edit') . '">' . oos_image_swap_button('edit','edit_off.gif', IMAGE_EDIT) . '</a> <a href="' . oos_href_link_admin($aFilename['zones'], 'page=' . $nPage . '&cID=' . $cInfo->zone_id . '&action=delete') . '">' . oos_image_swap_button('delete','delete_off.gif', IMAGE_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_ZONES_NAME . '<br />' . $cInfo->zone_name . ' (' . $cInfo->zone_code . ')');
        $contents[] = array('text' => '<br />' . TEXT_INFO_COUNTRY_NAME . ' ' . $cInfo->countries_name);
      }
      break;
  }
*/