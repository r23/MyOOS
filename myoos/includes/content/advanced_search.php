<?php
/**
   ----------------------------------------------------------------------

   OOS [OSIS Online Shop]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.49 2003/02/13 04:23:22 hpdl
   ----------------------------------------------------------------------
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

function oos_get_manufacturers()
{
    if (!is_array($aManufacturers)) {
        $aManufacturers = [];
    }

    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $manufacturers_result = $dbconn->Execute("SELECT manufacturers_id, manufacturers_name FROM " . $oostable['manufacturers'] . " ORDER BY manufacturers_name");
    while ($manufacturers = $manufacturers_result->fields) {
        $aManufacturers[] = ['id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']];
        $manufacturers_result->MoveNext();
    }
    return $aManufacturers;
}


require 'includes/languages/' . $sLanguage . '/search_advanced.php';

$error = '';
if (isset($_GET['errorno'])) {
    if (($_GET['errorno'] & 1) == 1) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_at_least_one_input']);
    }
    if (($_GET['errorno'] & 10) == 10) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_invalid_from_date']);
    }
    if (($_GET['errorno'] & 100) == 100) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_invalid_to_date']);
    }
    if (($_GET['errorno'] & 1000) == 1000) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_to_date_less_than_from_date']);
    }
    if (($_GET['errorno'] & 10000) == 10000) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_price_from_must_be_num']);
    }
    if (($_GET['errorno'] & 100000) == 100000) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_price_to_must_be_num']);
    }
    if (($_GET['errorno'] & 1_000_000) == 1_000_000) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_price_to_less_than_price_from']);
    }
    if (($_GET['errorno'] & 10_000_000) == 10_000_000) {
        $error .= str_replace('\n', '<br />', (string) $aLang['js_invalid_keywords']);
    }
}

$aCategoriesID = oos_get_categories([['id' => '', 'text' => $aLang['text_all_categories']]]);
$aManufacturersID = oos_get_manufacturers();


/*
  $options_box .= '  <tr>' . "\n" .
                  '    <td class="fieldKey">' . $aLang['entry_date_from'] . '</td>' . "\n" .
                  '    <td class="fieldValue">' . oos_draw_input_field('dfrom', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"') . '</td>' . "\n" .
                  '  </tr>' . "\n" .
                  '  <tr>' . "\n" .
                  '    <td class="fieldKey">' . $aLang['entry_date_to'] . '</td>' . "\n" .
                  '    <td class="fieldValue">' . oos_draw_input_field('dto', DOB_FORMAT_STRING, 'onFocus="RemoveFormatString(this, \'' . DOB_FORMAT_STRING . '\')"') . '</td>' . "\n" .
                  '  </tr>' . "\n";
*/


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title']);
$sCanonical = oos_href_link($aContents['advanced_search'], '', false, true);


$aTemplate['page'] = $sTheme . '/page/advanced_search.html';

$nPageType = OOS_PAGE_TYPE_CATALOG;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

// assign Smarty variables;
$smarty->assign(
    ['breadcrumb'        => $oBreadcrumb->trail(), 'heading_title'         => $aLang['heading_title'], 'canonical'            => $sCanonical, 'error'                => $error, 'categoriesID'        => $aCategoriesID, 'manufacturersID'     => $aManufacturersID]
);
// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
