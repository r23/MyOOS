<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Teap.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );



/** ensure this file is being required by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (isset($_GET['panorama_id'])) {
	if (!isset($nPanoramaID)) $nPanoramaID = intval($_GET['panorama_id']);
} else {
	oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/panorama.php';

$sCanonical = oos_href_link($aContents['panorama'], 'panorama_id='. $nPanoramaID, FALSE, TRUE);

// Panorama
$categories_panoramatable = $oostable['categories_panorama'];
$categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
$categories_panorama_sql = "SELECT p.panorama_id, p.categories_id, pd.panorama_name, pd.panorama_title, pd.panorama_description_meta,
								p.panorama_image, p.panorama_preview, p.panorama_author, p.panorama_type, p.panorama_hfov,
								p.panorama_pitch, p.panorama_yaw, p.panorama_autoload, p.panorama_autorotates
                        FROM $categories_panoramatable p,
                             $categories_panorama_descriptiontable pd
						WHERE p.panorama_id = '" . intval($nPanoramaID) . "'
                          AND pd.panorama_id = p.panorama_id
                          AND pd.panorama_languages_id = '" . intval($nLanguageID) . "'";						
$categories_panorama_result = $dbconn->Execute($categories_panorama_sql);	

if (!$categories_panorama_result->RecordCount()) {
	// product not found
	header('HTTP/1.0 404 Not Found');
    $aLang['text_information'] = $aLang['text_model_not_found'];

    $aTemplate['page'] = $sTheme . '/webgl/model_not_found.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;
	$sPagetitle = '404 Not Found ' . OOS_META_TITLE;

    require_once MYOOS_INCLUDE_PATH . '/includes/systep.php';

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));
	
	
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['text_model_not_found'],
			'robots'		=> 'noindex,follow,noodp,noydir',
			'canonical'		=> $sCanonical	
        )
    );

} else {

    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $query = "UPDATE $categories_panorama_descriptiontable"
        . " SET panorama_viewed = panorama_viewed+1"
        . " WHERE panorama_id = ?"
        . "   AND panorama_languages_id = ?";
    $result = $dbconn->Execute($query, array((int)$nPanoramaID, (int)$nLanguageID));
    $panorama_info = $categories_panorama_result->fields;	
	
	$name = oos_strip_suffix($panorama_info['models_webgl_gltf']);
	$url = $name . '/' . $panorama_info['models_extensions'] . '/' . $panorama_info['models_webgl_gltf']; 
		
	ob_start();
	require_once MYOOS_INCLUDE_PATH . '/includes/content/scene3d/panorama.js.php';
	$webgl = ob_get_contents();
	ob_end_clean();		
		

    // Meta Tags
    $sPagetitle = $panorama_info['models_title'] . ' ' . OOS_META_TITLE;
	$sDescription = $panorama_info['models_description_meta'];

	$aTemplate['page'] = $sTheme . '/panorama/pannellup.html';
	
    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/systep.php';

	$smarty->assign('canonical', $sCanonical);
	$smarty->assign('webgl', $webgl);
	$smarty->assign('model', $panorama_info);

    $smarty->setCaching(false);
}


// display the template
$smarty->display($aTemplate['page']);
