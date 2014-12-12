<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/newsletters_unsubscribe_success.php';

  $origin_href = oos_href_link($aContents['main']);
  // links breadcrumb
  $oBreadcrumb->add($aLang['navbar_title_1'], oos_href_link($aContents['newsletters']));
  $oBreadcrumb->add($aLang['navbar_title_2']);

  $aTemplate['page'] = $sTheme . '/page/newsletters_unsubscribe_success.html';
  $aTemplate['page_heading'] = $sTheme . '/heading/success_page_heading.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

  require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
  }

  // assign Smarty variables;
  $smarty->assign(
      array(
          'breadcrumb' => $oBreadcrumb->trail(),
          'heading_title' => $aLang['heading_title'],
		  'robots'		=> 'noindex,nofollow,noodp,noydir'
      )
  );

  $smarty->assign('origin_href', $origin_href);

  $smarty->assign('oosPageHeading', $smarty->fetch($aTemplate['page_heading']));


  // display the template
$smarty->display($aTemplate['page']);
