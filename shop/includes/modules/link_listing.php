<?php
/* ----------------------------------------------------------------------
   $Id: link_listing.php,v 1.2 2008/08/29 16:53:12 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2008 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: link_listing.php,v 1.00 2003/10/03 
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

  // define our listing functions
  include 'includes/functions/function_listing.php';

  $listing_numrows_sql = $listing_sql;
  $listing_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $listing_sql, $listing_numrows);
  // fix counted products
  $listing_numrows = $dbconn->Execute($listing_numrows_sql);
  $listing_numrows = $listing_numrows->RecordCount();

  $list_box_contents = array();
  $list_box_contents[] = array('params' => 'class="linkListing-heading"');
  $cur_row = count($list_box_contents) - 1;

  for ($col=0, $n=count($column_list); $col<$n; $col++) {
    switch ($column_list[$col]) {
      case 'LINK_LIST_TITLE':
        $lc_text = $aLang['table_heading_links_title'];
        $lc_align = '';
        break;

      case 'LINK_LIST_URL':
        $lc_text = $aLang['table_heading_links_url'];
        $lc_align = '';
        break;

      case 'LINK_LIST_IMAGE':
        $lc_text = $aLang['table_heading_links_image'];
        $lc_align = 'center';
        break;

      case 'LINK_LIST_DESCRIPTION':
        $lc_text = $aLang['table_heading_links_description'];
        $lc_align = 'center';
        break;

      case 'LINK_LIST_COUNT':
        $lc_text = $aLang['table_heading_links_count'];
        $lc_align = '';
        break;
    }

    if ($column_list[$col] != 'LINK_LIST_IMAGE') {
      $lc_text = oos_create_sort_heading($_GET['sort'], $col+1, $lc_text);
    }

    $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                           'params' => 'class="linkListing-heading"',
                                           'text' => '&nbsp;' . $lc_text . '&nbsp;');
  }

  if ($listing_numrows > 0) {
    $number_of_links = 0;
    $listing_result = $dbconn->Execute($listing_sql);
    while ($listing = $listing_result->fields) {
      $number_of_links++;

      if (($number_of_links/2) == floor($number_of_links/2)) {
        $list_box_contents[] = array('params' => 'class="linkListing-even"');
      } else {
        $list_box_contents[] = array('params' => 'class="linkListing-odd"');
      }

      $cur_row = count($list_box_contents) - 1;

      for ($col=0, $n=count($column_list); $col<$n; $col++) {
        $lc_align = '';

        switch ($column_list[$col]) {
          case 'LINK_LIST_TITLE':
            $lc_align = '';
            $lc_text = $listing['links_title'];
            break;

          case 'LINK_LIST_URL':
            $url = $listing['links_url'];
            $url = str_replace('&', ' &', $url);
            $url = str_replace('/', '/ ', $url);
            $lc_align = '';
            $lc_text = '<a href="' . oos_get_links_url($listing['links_id']) . '" target="_blank">' . $url . '</a>';
            break;

          case 'LINK_LIST_DESCRIPTION':
            $lc_align = '';
            $lc_text = $listing['links_description'];
            break;

          case 'LINK_LIST_IMAGE':
            $lc_align = 'center';
            if (oos_is_not_null($listing['links_image_url'])) {
              $lc_text = '<a href="' . oos_get_links_url($listing['links_id']) . '" target="_blank">' . oos_href_links_image($listing['links_image_url'], $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT) . '</a>';
            } else {
              $lc_text = '<a href="' . oos_get_links_url($listing['links_id']) . '" target="_blank">' . oos_image(OOS_IMAGES . 'trans.gif', $listing['links_title'], LINKS_IMAGE_WIDTH, LINKS_IMAGE_HEIGHT, 'style="border: 3px double black"') . '</a>';
            }
            break;

          case 'LINK_LIST_COUNT':
            $lc_align = '';
            $lc_text = $listing['links_clicked'];
            break;

        }

        $list_box_contents[$cur_row][] = array('align' => $lc_align,
                                               'params' => 'class="linkListing-data"',
                                               'text'  => $lc_text);
      }

      // Move that ADOdb pointer!
      $listing_result->MoveNext();
    }

    // Close result set
    $listing_result->Close();

  } else {
    $list_box_contents = array();

    $list_box_contents[0] = array('params' => 'class="linkListing-odd"');
    $list_box_contents[0][] = array('params' => 'class="linkListing-data"',
                                    'text'   => $aLang['text_no_links']);
  }

    $oSmarty->assign(
        array(
            'oos_page_split'    => $listing_split->display_count($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], $aLang['text_display_number_of_links']),
            'oos_display_links' => $listing_split->display_links($listing_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], oos_get_all_get_parameters(array('page', 'info'))),
            'oos_page_numrows'  => $listing_numrows
        )
    );

  $oSmarty->assign('list_box_contents', $list_box_contents);

?>