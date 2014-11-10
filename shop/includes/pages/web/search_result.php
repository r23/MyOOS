<?php
/* ----------------------------------------------------------------------
   $Id: search_result.php,v 1.1 2007/06/07 17:11:51 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: web_search_result.php,v 1.1 2004/07/02 chaicka  
   ----------------------------------------------------------------------
   WebSearch

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2004 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/' . $sMp . '_' . $sFile . '.php';

  require 'includes/classes/thirdparty/nusoap/lib/nusoap.php';
  require 'includes/classes/class_google_search.php';
  require 'includes/classes/class_web_search_results.php';

  require 'includes/functions/function_search.php';


  $bError = false;

  if ( (isset($_GET['keywords']) && empty($_GET['keywords'])) ) {
    $bError = true;

    $_SESSION['error_search_msg'] = $aLang['error_at_least_one_input'];
  } else {
    $keywords = '';

    if (isset($_GET['keywords'])) {
      $keywords = $_GET['keywords'];
    }

    if (oos_is_not_null($keywords)) {
      if (!oos_parse_search_string($keywords, $search_keywords)) {
        $bError = true;

        $_SESSION['error_search_msg'] = $aLang['error_invalid_keywords'];
      }
    }
  }

  if (empty($keywords)) {
    $bError = true;

    $_SESSION['error_search_msg'] = $aLang['error_at_least_one_input'];
  }

  if ($bError == true) {
    oos_redirect(oos_href_link($aModules['main'], $aFilename['main'], 'NONSSL'));
  }

  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title']);

  $search_query = $keywords;
  $oWebSearchSplit = new webSearchResults($search_query, MAX_DISPLAY_NEW_REVIEWS, WEB_SEARCH_GOOGLE_KEY);
  $nWebSearch = $oWebSearchSplit->number_of_rows;

  if ($nWebSearch > 0) {

    $aOption['template_main'] = $sTheme . '/modules/web_search_result.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
    $aOption['page_navigation'] = $sTheme . '/heading/page_navigation.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;

    require 'includes/oos_system.php';
    if (!isset($option)) {
      require 'includes/info_message.php';
      require 'includes/oos_blocks.php';
      require 'includes/oos_counter.php';
    }

    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif',

            'oos_page_split'    => $oWebSearchSplit->display_count($aLang['text_display_number_of_web_search_results']),
            'oos_display_links' => $oWebSearchSplit->display_links(MAX_DISPLAY_PAGE_LINKS, oos_get_all_get_parameters(array('page', 'info'))),
            'oos_page_numrows'  => $nWebSearch,

            'websearch_results' => $oWebSearchSplit->do_search();
        )
    );

    $oSmarty->assign('oosPageNavigation', $oSmarty->fetch($aOption['page_navigation']));

  } else {
    $aOption['template_main'] = $sTheme . '/system/info.html';
    $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;

    require 'includes/oos_system.php';
    if (!isset($option)) {
      require 'includes/info_message.php';
      require 'includes/oos_blocks.php';
      require 'includes/oos_counter.php';
    }

    // assign Smarty variables;
    $oSmarty->assign(
        array(
            'oos_breadcrumb'    => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
            'oos_heading_title' => $aLang['heading_title'],
            'oos_heading_image' => 'specials.gif',

            'text_information'  => $aLang['text_no_web_search_results']
       )
    );
  }

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';
?>