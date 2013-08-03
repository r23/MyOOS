<?php
/* ----------------------------------------------------------------------
   $Id: advanced_search.php 409 2013-06-11 15:53:40Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: advanced_search.php,v 1.49 2003/02/13 04:23:22 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  function oos_get_manufacturers($aManufacturers = '') {

    if (!is_array($aManufacturers)) $aManufacturers = array();

    $dbconn =& oosDBGetConn();
    $oostable = oosDBGetTables();

    $manufacturers_result = $dbconn->Execute("SELECT manufacturers_id, manufacturers_name FROM " . $oostable['manufacturers'] . " ORDER BY manufacturers_name");
    while ($manufacturers = $manufacturers_result->fields) {
      $aManufacturers[] = array('id' => $manufacturers['manufacturers_id'], 'text' => $manufacturers['manufacturers_name']);
      $manufacturers_result->MoveNext();
    }
    return $aManufacturers;
  }

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/search_advanced.php';

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['advanced_search']));

  ob_start();
  require 'js/advanced_search.js.php';
  $javascript = ob_get_contents();
  ob_end_clean();

  $info_message = '';
  if (isset($_GET['errorno'])) {
    if (($_GET['errorno'] & 1) == 1) {
      $info_message = str_replace('\n', '<br />', $aLang['js_at_least_one_input']);
    }
    if (($_GET['errorno'] & 10) == 10) {
      $info_message = str_replace('\n', '<br />', $aLang['js_invalid_from_date']);
    }
    if (($_GET['errorno'] & 100) == 100) {
      $info_message = str_replace('\n', '<br />', $aLang['js_invalid_to_date']);
    }
    if (($_GET['errorno'] & 1000) == 1000) {
      $info_message = str_replace('\n', '<br />', $aLang['js_to_date_less_than_from_date']);
    }
    if (($_GET['errorno'] & 10000) == 10000) {
      $info_message = str_replace('\n', '<br />', $aLang['js_price_from_must_be_num']);
    }
    if (($_GET['errorno'] & 100000) == 100000) {
      $info_message = str_replace('\n', '<br />', $aLang['js_price_to_must_be_num']);
    }
    if (($_GET['errorno'] & 1000000) == 1000000) {
      $info_message = str_replace('\n', '<br />', $aLang['js_price_to_less_than_price_from']);
    }
    if (($_GET['errorno'] & 10000000) == 10000000) {
      $info_message = str_replace('\n', '<br />', $aLang['js_invalid_keywords']);
    }
  }
  $options_box = '<table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td class="fieldKey">' . $aLang['entry_categories'] . '</td>' . "\n" .
                 '    <td class="fieldValue">' . oos_draw_pull_down_menu('categories_id', oos_get_categories(array(array('id' => '', 'text' => $aLang['text_all_categories'])))) . '<br /></td>' . "\n" .
                 '  </tr>' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td class="fieldKey">&nbsp;</td>' . "\n" .
                 '    <td class="smallText">' . oos_draw_checkbox_field('inc_subcat', '1', true) . ' ' . $aLang['entry_include_subcategories'] . '</td>' . "\n" .
                 '  </tr>' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td colspan="2"></td>' . "\n" .
                 '  </tr>' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td class="fieldKey">' . $aLang['entry_manufacturers'] . '</td>' . "\n" .
                 '    <td class="fieldValue">' . oos_draw_pull_down_menu('manufacturers_id', oos_get_manufacturers(array(array('id' => '', 'text' => $aLang['text_all_manufacturers'])))) . '</td>' . "\n" .
                 '  </tr>' . "\n" .
                 '  <tr>' . "\n" .
                 '    <td colspan="2"></td>' . "\n" .
                 '  </tr>' . "\n";
  if ($_SESSION['member']->group['show_price'] == 1 ) {
    $options_box .= '  <tr>' . "\n" .
                    '    <td class="fieldKey">' . $aLang['entry_price_from'] . '</td>' . "\n" .
                    '    <td class="fieldValue">' . oos_draw_input_field('pfrom') . '</td>' . "\n" .
                    '  </tr>' . "\n" .
                    '  <tr>' . "\n" .
                    '    <td class="fieldKey">' . $aLang['entry_price_to'] . '</td>' . "\n" .
                    '    <td class="fieldValue">' . oos_draw_input_field('pto') . '</td>' . "\n" .
                    '  </tr>' . "\n" .
                    '  <tr>' . "\n" .
                    '    <td colspan="2"></td>' . "\n" .
                    '  </tr>' . "\n";
  }
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
  $options_box .= '</table>';

  $aTemplate['page'] = $sTheme . '/modules/advanced_search.tpl';

  $nPageType = OOS_PAGE_TYPE_CATALOG;

  require_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $aLang['heading_title'],
          'oos_heading_image' => 'browse.gif',

          'info_message'      => $info_message,
          'options_box'       => $options_box,
          'oos_js'            => $javascript
      )
  );


// display the template
$smarty->display($aTemplate['page']);
