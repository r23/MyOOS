<?php
/* ----------------------------------------------------------------------
   $Id: links.php,v 1.1 2007/06/07 16:47:09 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: links.php,v 1.00 2003/10/03
   ----------------------------------------------------------------------
   Links Manager

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  include 'includes/languages/' . $sLanguage . '/links_links.php';

  // define our link functions
  include 'includes/functions/function_links.php';

  // lets retrieve all $_GET keys and values..
  $get_parameters = oos_get_all_get_parameters();
  $get_parameters = oos_remove_trailing($get_parameters);

  // calculate link category path
  $lPath = '';
  $display_mode = 'categories';
  if (isset($_GET['lPath'])) {
    $lPath = oos_var_prep_for_os($_GET['lPath']);
    $current_category_id = $lPath;
    $display_mode = 'links';
  } elseif (isset($_GET['links_id'])) {
    $lPath = oos_get_links_path($_GET['links_id']);
  }

  // links breadcrumb
  $link_categories_sql = "SELECT link_categories_name 
                          FROM " . $oostable['link_categories_description'] . " 
                          WHERE link_categories_id = '" . intval($lPath) . "' 
                            AND link_categories_languages_id = '" .  intval($nLanguageID) . "'";
  $link_categories_result = $dbconn->Execute($link_categories_sql);

  // links breadcrumb
  if ($display_mode == 'links') {
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['links'], $aFilename['links']));  
    $oBreadcrumb->add($link_categories_result->fields['link_categories_name'], oos_href_link($aModules['links'], $aFilename['links'] . '&lPath=' . $lPath));
  } else {
    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aModules['links'], $aFilename['links']));
  }

  if ($display_mode == 'categories') {
    $categories_sql = "SELECT lc.link_categories_id, lcd.link_categories_name, lcd.link_categories_description, lc.link_categories_image 
                       FROM " . $oostable['link_categories'] . " lc,
                            " . $oostable['link_categories_description'] . " lcd
                       WHERE lc.link_categories_id = lcd.link_categories_id
                         AND lc.link_categories_status = '1'
                         AND lcd.link_categories_languages_id = '" .  intval($nLanguageID) . "'
                       ORDER BY lcd.link_categories_name";
    $categories_result = $dbconn->Execute($categories_sql);
    $number_of_categories = $categories_result->RecordCount();

    if ($number_of_categories > 0) {
      $rows = 0;
      $categories_box = '';
      while ($categories = $categories_result->fields) {
        $rows++;
        $lPath_new = 'lPath=' . $categories['link_categories_id'];
        $width = (int)(100 / MAX_DISPLAY_CATEGORIES_PER_ROW) . '%';

        $categories_box .= '                <td align="center" class="smallText" width="' . $width . '" valign="top"><a href="' . oos_href_link($aModules['links'], $aFilename['links'], $lPath_new) . '">';

        if (oos_is_not_null($categories['link_categories_image'])) {
          $categories_box .= oos_href_links_image(OOS_IMAGES . $categories['link_categories_image'], $categories['link_categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT) . '<br />';
        } else {
          $categories_box .= oos_image(OOS_IMAGES . 'trans.gif', $categories['link_categories_name'], SUBCATEGORY_IMAGE_WIDTH, SUBCATEGORY_IMAGE_HEIGHT, 'style="border: 3px double black"') . '<br />';
        }

        $categories_box .= '<br /><b><u>' . $categories['link_categories_name'] . '</b></u></a><br /><br />' . $categories['link_categories_description'] . '</td>' . "\n";
        if ((($rows / MAX_DISPLAY_CATEGORIES_PER_ROW) == floor($rows / MAX_DISPLAY_CATEGORIES_PER_ROW)) && ($rows != $number_of_categories)) {
          $categories_box .= '              </tr>' . "\n";
          $categories_box .= '              <tr>' . "\n";
        }
        $categories_result->MoveNext();
      }
    } else {
      $categories_box .= '                <td class="smallText">' . $aLang['text_no_categories'] . '</td>' . "\n";
    }

    $aOption['template_main'] = $sTheme . '/modules/links_categories.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;

    include 'includes/oos_system.php';
    if (!isset($option)) {
      include 'includes/info_message.php';
      include 'includes/oos_blocks.php';
      include 'includes/oos_counter.php';
    }

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif'
        )
    );

    $oSmarty->assign('categories_box', $categories_box);

    $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
    $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  } elseif ($display_mode == 'links') {
    // create column list
    $define_list = array('LINK_LIST_TITLE' => LINK_LIST_TITLE,
                         'LINK_LIST_URL' => LINK_LIST_URL,
                         'LINK_LIST_IMAGE' => LINK_LIST_IMAGE,
                         'LINK_LIST_DESCRIPTION' => LINK_LIST_DESCRIPTION, 
                         'LINK_LIST_COUNT' => LINK_LIST_COUNT);

    asort($define_list);

    $column_list = array();
    reset($define_list);
    while (list($key, $value) = each($define_list)) {
      if ($value > 0) $column_list[] = $key;
    }

    $select_column_list = '';

    for ($i=0, $n=count($column_list); $i<$n; $i++) {
      switch ($column_list[$i]) {
        case 'LINK_LIST_TITLE':
          $select_column_list .= 'ld.links_title, ';
          break;

        case 'LINK_LIST_URL':
          $select_column_list .= 'l.links_url, ';
          break;

        case 'LINK_LIST_IMAGE':
          $select_column_list .= 'l.links_image_url, ';
          break;

        case 'LINK_LIST_DESCRIPTION':
          $select_column_list .= 'ld.links_description, ';
          break;

        case 'LINK_LIST_COUNT':
          $select_column_list .= 'l.links_clicked, ';
          break;
      }
    }

    // show the links in a given category
    // We show them all
    $listing_sql = "SELECT " . $select_column_list . " l.links_id
                    FROM " . $oostable['links_description'] . " ld,
                         " . $oostable['links'] . " l,
                         " . $oostable['links_to_link_categories'] . " l2lc
                    WHERE l.links_status = '2'
                      AND l.links_id = l2lc.links_id
                      AND ld.links_id = l2lc.links_id
                      AND ld.links_languages_id = '" .  intval($nLanguageID) . "'
                      AND l2lc.link_categories_id = '" . intval($current_category_id) . "'";

    if ( (!isset($_GET['sort'])) || (!ereg('[1-8][ad]', $_GET['sort'])) || (substr($_GET['sort'], 0, 1) > count($column_list)) ) {
      for ($i=0, $n=count($column_list); $i<$n; $i++) {
        if ($column_list[$i] == 'LINK_LIST_TITLE') {
          $_GET['sort'] = $i+1 . 'a';
          $listing_sql .= " ORDER BY ld.links_title";
          break;
        }
      }
    } else {
      $sort_col = substr($_GET['sort'], 0 , 1);
      $sort_order = substr($_GET['sort'], 1);
      $listing_sql .= ' ORDER BY ';
      switch ($column_list[$sort_col-1]) {
        case 'LINK_LIST_TITLE':
          $listing_sql .= "ld.links_title " . ($sort_order == 'd' ? 'desc' : '');
          break;

        case 'LINK_LIST_URL':
          $listing_sql .= "l.links_url " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;

        case 'LINK_LIST_IMAGE':
          $listing_sql .= "ld.links_title";
          break;

        case 'LINK_LIST_DESCRIPTION':
          $listing_sql .= "ld.links_description " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;

        case 'LINK_LIST_COUNT':
          $listing_sql .= "l.links_clicked " . ($sort_order == 'd' ? 'desc' : '') . ", ld.links_title";
          break;
      }
    }

    $image = 'list.gif';
    if ($current_category_id) {
      $image_sql = "SELECT link_categories_image 
                    FROM " . $oostable['link_categories'] . " 
                    WHERE link_categories_id = '" . intval($current_category_id) . "'";
      $image_result = $dbconn->Execute($image_sql);
      $image_value = $image_result->fields;

      if (oos_is_not_null($image_value['link_categories_image'])) {
        $image = $image_value['link_categories_image'];
      }
    }
    $oos_heading_image = oos_href_links_image(OOS_IMAGES . $image, $aLang['categories_heading_title'], HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT);

    $aOption['template_main'] = $sTheme . '/modules/links.html';
    $aOption['page_heading'] = $sTheme . '/modules/links_page_heading.html';
    $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;

    include 'includes/oos_system.php';
    if (!isset($option)) {
      include 'includes/info_message.php';
      include 'includes/oos_blocks.php';
      include 'includes/oos_counter.php';
    }

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => $oos_heading_image
        )
    );

    $oSmarty->assign('get_parameters', $get_parameters);

    include 'includes/modules/link_listing.php';

    $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));
    $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
    $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));
  }

  // display the template
  require 'includes/oos_display.php';

?>
