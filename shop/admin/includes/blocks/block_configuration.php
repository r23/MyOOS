<?php
/* ----------------------------------------------------------------------
   $Id: configuration.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: configuration.php,v 1.16 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );
   
$sLanguage = oos_var_prep_for_os($_SESSION['language']);
if (file_exists('includes/languages/' . $sLanguage . '/configuration_group.php'))
{
	include 'includes/languages/' . $sLanguage . '/configuration_group.php';
}   

$smarty->assign('heading_configuration', oos_href_link_admin($aFilename['configuration'], 'selected_box=configuration'));  

$aCfgGroups = array(); 
$configuration_groups_result = $dbconn->Execute("SELECT configuration_group_id as cgID FROM " . $oostable['configuration_group'] . " where visible = '1' ORDER BY sort_order");
while ($configuration_groups = $configuration_groups_result->fields) {
    $sTitle = constant(strtoupper($configuration_groups['cgID'] . '_TITLE'));
    $sLink = '<a href="' . oos_href_link_admin($aFilename['configuration'], 'gID=' . $configuration_groups['cgID']. '&amp;selected_box=configuration') . '" title="' . $sTitle . '">' . $sTitle . '</a>';
	$aCfgGroups[] = array('link' => $sLink);

	// Move that ADOdb pointer!
	$configuration_groups_result->MoveNext();
}

$smarty->assign('cfg_groups', $aCfgGroups);

