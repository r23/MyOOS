<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shipping.php,v 1.21 2003/02/13 04:23:23 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  $aTemplate['page'] = $sTheme . '/page/information.html';

  $nPageType = OOS_PAGE_TYPE_MAINPAGE;

 
  
  $nInformationsID = isset($_GET[information_id]) ? $_GET[information_id]+0 : 1;
  $sGroup = trim($_SESSION['user']->group['text']);
  $nContentCacheID = $sTheme . '|info|' . $sGroup . '|information|' . $nInformationsID . '|' . $sLanguage;

  require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
  if (!isset($option)) {
    require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
    require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
  }

if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
}



  if (!$smarty->isCached($aTemplate['page'], $nContentCacheID)) {
    $informationtable = $oostable['information'];
    $information_descriptiontable = $oostable['information_description'];
    $sql = "SELECT i.information_id, i.information_image, id.information_name,
                   id.information_description, id.information_heading_title,
                   id.information_url
            FROM $informationtable i,
                 $information_descriptiontable id
            WHERE i.information_id = '" . intval($nInformationsID) . "'
              AND id.information_id = i.information_id
              AND id.information_languages_id = '" .  intval($nLanguageID) . "'";
    $information = $dbconn->GetRow($sql);

    // links breadcrumb
    $oBreadcrumb->add($information['information_heading_title'], oos_href_link($aContents['information'], 'information_id=' . intval($nInformationsID)));

    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $information['information_heading_title'],
            'oos_heading_image' => $information['information_image'],

            'informations'       => $information,
            'get_params'         => 'information_id=' . intval($nInformationsID)
        )
    );

  }
  
 

$smarty->setCaching(false);
  
// display the template
$smarty->display($aTemplate['page']);
