<?php
/* ----------------------------------------------------------------------
   $Id: oos_blocks.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------  */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if(defined('NEW_MYOOS'))
{

  if (oos_admin_check_boxes('administrator.php') == true) {
    include 'includes/boxes/administrator.php';
  }
  if (oos_admin_check_boxes('configuration.php') == true) {
    include 'includes/boxes/configuration.php';
  }
  if (oos_admin_check_boxes('catalog.php') == true) {
    include 'includes/boxes/catalog.php';
  }
  if (oos_admin_check_boxes('content.php') == true) {
    include 'includes/boxes/content.php';
  }
  if (oos_admin_check_boxes('newsfeed.php') == true) {
    include 'includes/boxes/newsfeed.php';
  }
  if (oos_admin_check_boxes('modules.php') == true) {
    include 'includes/boxes/modules.php';
  }
  if (oos_admin_check_boxes('plugins.php') == true) {
    include 'includes/boxes/plugins.php';
  }
  if (oos_admin_check_boxes('customers.php') == true) {
    include 'includes/boxes/customers.php';
  }
  if (oos_admin_check_boxes('taxes.php') == true) {
    include 'includes/boxes/taxes.php';
  }
  if (oos_admin_check_boxes('localization.php') == true) {
    include 'includes/boxes/localization.php';
  }

  if (oos_admin_check_boxes('reports.php') == true) {
    include 'includes/boxes/reports.php';
  }
  if (oos_admin_check_boxes('tools.php') == true) {
    include 'includes/boxes/tools.php';
  }
  if (oos_admin_check_boxes('links.php') == true) {
    include 'includes/boxes/links.php';
  }
  if (oos_admin_check_boxes('gv_admin.php') == true) {
    include 'includes/boxes/gv_admin.php'; 
  }

  if (oos_admin_check_boxes('export.php') == true) {
    include 'includes/boxes/export.php';
  }
  if (oos_admin_check_boxes('information.php') == true) {
    include 'includes/boxes/information.php';
  }
} else {
	$aFilesResults = array();
	$aContentBlock  = array();
    $admin_filestable = $oostable['admin_files'];
    $query = "SELECT admin_files_id, admin_files_name, admin_files_is_boxes, admin_files_to_boxes
              FROM $admin_filestable
              WHERE FIND_IN_SET( '" . intval($_SESSION['login_groups_id']) . "', admin_groups_id) 
			  AND admin_files_is_boxes = '1'";
	$aFilesResults = array();			
	$aFilesResults = $dbconn->GetAll($query);

	foreach ($aFilesResults as $block) {
		$block_file = substr($block['admin_files_name'], 0, -4);

		if (empty($block_file))
		{
			continue;
		}

		if (is_readable('includes/blocks/block_' . $block_file . '.php'))
		{
			include '/includes/blocks/block_' . $block_file . '.php';	

			$block_tpl = 'default/blocks/' . $block_file . '.tpl';
			$block_content = $smarty->fetch($block_tpl);

			$aContentBlock[] = array('block_content' => $block_content );
		}
	}								 


	for ($i = 0, $n = count($aContentBlock); $i < $n; $i++) {
		$smarty->append('oos_block', array('content' => $aContentBlock[$i]['block_content']));

	}

}
