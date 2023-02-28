<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

http_response_code(404);

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/404.php';

$aTemplate['page'] = $sTheme . '/page/404.html';

$nPageType = OOS_PAGE_TYPE_SERVICE;

$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;
$sCanonical = oos_href_link($aContents['404'], '', false, true);

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
if (!isset($option)) {
    include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
}

if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
    $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}


// links breadcrumb
$oBreadcrumb->add($aLang['navbar_title']);

// assign Smarty variables;
$smarty->assign(
    array(
        'breadcrumb'    => $oBreadcrumb->trail(),
        'heading_title' => $aLang['heading_title'],
        'robots'        => 'noindex,nofollow,noodp,noydir',
        'canonical'     => $sCanonical
    )
);


// display the template
$smarty->display($aTemplate['page']);
