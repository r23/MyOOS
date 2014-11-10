<?php
/* ----------------------------------------------------------------------
   $Id: main.php,v 1.2 2007/11/13 00:45:48 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: default.php,v 1.2 2003/01/09 09:40:07 elarifr
   orig: default.php,v 1.81 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  require 'includes/languages/' . $sLanguage . '/main_main.php';
  require 'includes/functions/function_default.php';

 /**
  * Return a customer greeting
  *
  * @return string
  */
  function oos_customer_greeting() {
    global $aLang;

    $aFilename = oos_get_filename();
    $aModules = oos_get_modules();

    $personal_text = '';
    if ( (isset($_SESSION['customer_lastname'])) && (ACCOUNT_GENDER == 'true') ) {
      if ($_SESSION['customer_gender'] == 'm') {
        $personal_text = $aLang['male_address'] . ' ' . $_SESSION['customer_lastname'];
      } else {
        $personal_text = $aLang['female_address'] . ' ' . $_SESSION['customer_lastname'];
      }
    }

    if (isset($_SESSION['customer_lastname']) && isset($_SESSION['customer_id'])) {
      $sGreeting = sprintf($aLang['text_greeting_personal'], $personal_text, oos_href_link($aModules['products'], $aFilename['products_new']));
    } else {
      $sGreeting = '';
      // $sGreeting = sprintf($aLang['text_greeting_guest'], oos_href_link($aModules['user'], $aFilename['login'], '', 'SSL'), oos_href_link($aModules['user'], $aFilename['create_account'], '', 'SSL'));
    }

    return $sGreeting;
  }



  // default

  $aOption['template_main'] = $sTheme . '/system/main.html';
  $aOption['page_heading'] = $sTheme . '/heading/page_heading.html';
  $aOption['new_news'] = $sTheme . '/modules/new_news.html';
  if ($oEvent->installed_plugin('spezials')) $aOption['new_spezials'] = $sTheme . '/modules/products/new_spezials.html';
  if ($oEvent->installed_plugin('featured')) $aOption['featured'] = $sTheme . '/modules/products/featured.html';
  if ($oEvent->installed_plugin('manufacturers')) $aOption['mod_manufacturers'] = $sTheme . '/modules/products/manufacturers.html';
  $aOption['new_products'] = $sTheme . '/modules/products/new_products.html';
  $aOption['upcoming_products'] = $sTheme . '/modules/products/upcoming_products.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require 'includes/oos_system.php';
  if (!isset($option)) {
    require 'includes/info_message.php';
    require 'includes/oos_blocks.php';
    require 'includes/oos_counter.php';
  }

  if (TIME_BASED_GREETING == 'true') {
    $heading_title = oos_time_based_greeting();
  } else {
    $heading_title = $aLang['heading_title'];
  }

  // assign Smarty variables;
  $oSmarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $heading_title,
          'oos_heading_image' => 'default.gif'
      )
  );


  if (isset($_SESSION['customer_id'])) {
    $oSmarty->assign('customer_greeting', oos_customer_greeting());
  }

  if ( (USE_CACHE == 'true') && (!SID) && (!isset($_SESSION['customer_id'])) ){
    $oSmarty->caching = true;
  }


  if (!$oSmarty->is_cached($aOption['new_news'], $oos_news_cache_id)) {
    require 'includes/modules/new_news.php';
  }
  $oSmarty->assign('new_news', $oSmarty->fetch($aOption['new_news'], $oos_news_cache_id));

  if ($oEvent->installed_plugin('spezials')) {
    if (!$oSmarty->is_cached($aOption['new_spezials'], $oos_modules_cache_id)) {
      require 'includes/modules/new_spezials.php';
    }
    $oSmarty->assign('new_spezials', $oSmarty->fetch($aOption['new_spezials'], $oos_modules_cache_id));
  }

  if ($oEvent->installed_plugin('featured')) {
    if (!$oSmarty->is_cached($aOption['featured'], $oos_modules_cache_id)) {
      require 'includes/modules/featured.php';
    }
    $oSmarty->assign('featured', $oSmarty->fetch($aOption['featured'], $oos_modules_cache_id));
  }


  if (!$oSmarty->is_cached($aOption['new_products'], $oos_modules_cache_id)) {
    require 'includes/modules/new_products.php';
  }
  $oSmarty->assign('new_products', $oSmarty->fetch($aOption['new_products'], $oos_modules_cache_id));


  if ($oEvent->installed_plugin('manufacturers')) {
    if (!$oSmarty->is_cached($aOption['mod_manufacturers'], $oos_modules_cache_id)) {
      require 'includes/modules/mod_manufacturers.php';
    }
    $oSmarty->assign('mod_manufacturers', $oSmarty->fetch($aOption['mod_manufacturers'], $oos_modules_cache_id));
  }


  if (!$oSmarty->is_cached($aOption['upcoming_products'], $oos_modules_cache_id)) {
    require 'includes/modules/upcoming_products.php';
  }
  $oSmarty->assign('upcoming_products', $oSmarty->fetch($aOption['upcoming_products'], $oos_modules_cache_id));
  $oSmarty->caching = false;

  $oSmarty->assign('oosPageHeading', $oSmarty->fetch($aOption['page_heading']));
  $oSmarty->assign('contents', $oSmarty->fetch($aOption['template_main']));

  // display the template
  require 'includes/oos_display.php';

?>
