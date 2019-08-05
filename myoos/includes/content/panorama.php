<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$aTemplate['page'] = $sTheme . '/panorama/pannellum.html';

$nPageType = OOS_PAGE_TYPE_MAINPAGE;
$sPagetitle = $aLang['heading_title'] . ' ' . OOS_META_TITLE;
  

require_once MYOOS_INCLUDE_PATH . '/includes/system.php';


    $informationtable = $oostable['information'];
    $information_descriptiontable = $oostable['information_description'];
    $sql = "SELECT i.information_id, id.information_name,
                   id.information_description, id.information_heading_title
            FROM $informationtable i,
                 $information_descriptiontable id
            WHERE i.information_id = '" . intval($nInformationsID) . "'
              AND id.information_id = i.information_id
              AND id.information_languages_id = '" .  intval($nLanguageID) . "'";
    $information = $dbconn->GetRow($sql);

    // links breadcrumb
    $oBreadcrumb->add($information['information_heading_title'], oos_href_link($aContents['information'], 'information_id=' . intval($nInformationsID)));
    $sCanonical = oos_href_link($aContents['information'], 'information_id=' . intval($nInformationsID), FALSE, TRUE);
	
    // assign Smarty variables;
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $information['information_heading_title'],
            'canonical'     => $sCanonical,
			
            'informations'       => $information,
            'get_params'         => 'information_id=' . intval($nInformationsID)
        )
    );

  
// display the template
$smarty->display($aTemplate['page']);
