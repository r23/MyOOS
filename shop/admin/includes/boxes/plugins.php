<?php
/* ----------------------------------------------------------------------
   $Id: plugins.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
   
$bActive = false;
if ($_SESSION['selected_box'] == 'plugins' ) {
	$bActive = true;
}
  
$aBlocks[] = array(
	'heading' => BOX_HEADING_PLUGINS,
	'link' => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=plugins'),
	'icon' => 'fa fa-plug',
	'active' => $bActive,
	'contents' => array(
		array(
			'title' => BOX_PLUGINS_EVENT,
			'link' => oos_href_link_admin($aContents['plugins'], 'selected_box=plugins', 'NONSSL')
		),	
	),
);
