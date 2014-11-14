<?php
/* ----------------------------------------------------------------------
   $Id: main.php,v 1.2 2007/11/13 00:45:48 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
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

  include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/main_main.php';
  include_once MYOOS_INCLUDE_PATH . '/includes/functions/function_default.php';


  
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

  include_once MYOOS_INCLUDE_PATH . '/includes/oos_system.php';
  if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/info_message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/oos_blocks.php';
  }

  
  
  if (TIME_BASED_GREETING == 'true') {
    $heading_title = oos_time_based_greeting();
  } else {
    $heading_title = $aLang['heading_title'];
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'oos_breadcrumb' => $oBreadcrumb->trail(BREADCRUMB_SEPARATOR),
          'oos_heading_title' => $heading_title,
          'oos_heading_image' => 'default.gif'
      )
  );


  if (isset($_SESSION['customer_id'])) {
    $smarty->assign('customer_greeting', oos_customer_greeting());
  }

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}

  if ($oEvent->installed_plugin('spezials')) {
    if (!$smarty->isCached($aOption['new_spezials'], $oos_modules_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/new_spezials.php';
    }
    $smarty->assign('new_spezials', $smarty->fetch($aOption['new_spezials'], $oos_modules_cache_id));
  }

  
  
  if ($oEvent->installed_plugin('featured')) {
    if (!$smarty->isCached($aOption['featured'], $oos_modules_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/featured.php';
    }
    $smarty->assign('featured', $smarty->fetch($aOption['featured'], $oos_modules_cache_id));
  }


  if (!$smarty->isCached($aOption['new_products'], $oos_modules_cache_id)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/new_products.php';
  }
  $smarty->assign('new_products', $smarty->fetch($aOption['new_products'], $oos_modules_cache_id));


  if ($oEvent->installed_plugin('manufacturers')) {
    if (!$smarty->isCached($aOption['mod_manufacturers'], $oos_modules_cache_id)) {
      include_once MYOOS_INCLUDE_PATH . '/includes/modules/mod_manufacturers.php';
    }
    $smarty->assign('mod_manufacturers', $smarty->fetch($aOption['mod_manufacturers'], $oos_modules_cache_id));
  }


  if (!$smarty->isCached($aOption['upcoming_products'], $oos_modules_cache_id)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/modules/upcoming_products.php';
  }
  
  $smarty->assign('upcoming_products', $smarty->fetch($aOption['upcoming_products'], $oos_modules_cache_id));
  $smarty->setCaching(false);

  $smarty->assign('oosPageHeading', $smarty->fetch($aOption['page_heading']));
  $smarty->assign('contents', $smarty->fetch($aOption['template_main']));

  
  // display the template
  include_once MYOOS_INCLUDE_PATH . '/includes/oos_display.php';


