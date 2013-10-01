<?php
/* ----------------------------------------------------------------------
   $Id: oos_blocks.php 477 2013-07-14 21:57:50Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$aContentBlock = array();

$blocktable = $oostable['block'];
$block_infotable = $oostable['block_info'];
$block_to_page_typetable = $oostable['block_to_page_type'];
$block_sql = "SELECT b.block_id, b.block_side, b.block_status, b.block_file, b.block_type,
                       b.block_sort_order, b.block_login_flag, b.block_cache, bi.block_name
                FROM $blocktable b,
                     $block_to_page_typetable b2p,
                     $block_infotable bi
                WHERE b.block_status = '1'
                  AND b.block_id = b2p.block_id
                  AND bi.block_id = b2p.block_id
                  AND bi.block_languages_id = '" .  intval($nLanguageID) . "'
                  AND b2p.page_type_id = '" . intval($nPageType) . "'";
if (isset($_SESSION['customer_id'])) {
	$block_sql .= "  AND ( b.block_login_flag = '0' OR b.block_login_flag = '1')";
} else {
	$block_sql .= "  AND b.block_login_flag = '0'";
}
$block_sql .= " ORDER BY b.block_side, b.block_sort_order ASC";
$block_result = $dbconn->GetAll($block_sql);

foreach ($block_result as $block) {
	$block_heading = $block['block_name'];
	$block_file = trim($block['block_file']);
	$block_side = $block['block_side'];

	if (empty($block_file)) {
		continue;
	}
	if (!empty($block_side)) {
		$block_tpl = $sTheme . '/blocks/' . $block_file . '.html';
	}
	
	if ( (!empty($block['block_cache'])) && (!empty($block_side)) ) {
		if ( (USE_CACHE == 'true') && (!SID) ) {
			$smarty->setCaching(true);
		}
		$bid = trim('oos_' . $block['block_cache'] . '_cache_id');

		if (!$smarty->isCached($block_tpl, ${$bid})) {
			require_once MYOOS_INCLUDE_PATH . '/includes/blocks/block_' . $block_file . '.php';
		}
		if (!empty($block_side)) {
			$block_content = $smarty->fetch($block_tpl, ${$bid});
		}
	} else {

		$smarty->setCaching(false);
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks/block_' . $block_file . '.php';
		if (!empty($block_side)) {
			$block_content = $smarty->fetch($block_tpl);
		}
	}

	if (!empty($block_side)) {
		$aContentBlock[] = array('side' => $block_side,
								 'block_content' => $block_content );
	}								 

}

$nContentBlock = count($aContentBlock);
for ($i = 0, $nContentBlock; $i < $n; $i++) {
	switch ($aContentBlock[$i]['side']) {

	case 'left':
		$smarty->append('oos_blockleft', array('content' => $aContentBlock[$i]['block_content']));
		break;

	case 'right':
		$smarty->append('oos_blockright', array('content' => $aContentBlock[$i]['block_content']));
		break;

     }
}

$smarty->setCaching(false);
