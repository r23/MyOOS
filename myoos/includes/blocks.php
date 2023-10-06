<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$aContentBlock = [];
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
    $block_sql .= " AND ( b.block_login_flag = '0' OR b.block_login_flag = '1')";
} else {
    $block_sql .= " AND b.block_login_flag = '0'";
}
$block_sql .= " ORDER BY b.block_side, b.block_sort_order ASC";
$block_result = $dbconn->GetAll($block_sql);
foreach ($block_result as $block) {
    $block_heading = $block['block_name'];
    $block_file = trim((string) $block['block_file']);
    $block_side = $block['block_side'];

    if (empty($block_file)) {
        continue;
    }

    if (!empty($block_side)) {
        $block_tpl = $sTheme . '/blocks/' . $block_file . '.html';
    }

    if ((!empty($block['block_cache'])) && (!empty($block_side))) {
        if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
            $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        }
        $bid = trim((string) 'oos_' . $block['block_cache'] . '_cache_id');
        if (!$smarty->isCached($block_tpl, ${$bid})) {
            include_once MYOOS_INCLUDE_PATH . '/includes/blocks/block_' . $block_file . '.php';
        }

        $block_content = $smarty->fetch($block_tpl, ${$bid});
    } else {
        include_once MYOOS_INCLUDE_PATH . '/includes/blocks/block_' . $block_file . '.php';
        if (!empty($block_side)) {
            $block_content = $smarty->fetch($block_tpl);
        }
    }
    if (!empty($block_content)) {
        $aContentBlock[] = ['side' => $block_side, 'block_content' => $block_content];
    }
}

$n = is_countable($aContentBlock) ? count($aContentBlock) : 0;
for ($i = 0, $n; $i < $n; $i++) {
    switch ($aContentBlock[$i]['side']) {

    case 'sidebar':
        $smarty->append('sidebar', ['content' => $aContentBlock[$i]['block_content']]);
        break;

    default:
        break;

    }
}

$smarty->setCaching(false);
