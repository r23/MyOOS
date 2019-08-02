<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_info.php,v 1.92 2003/02/14 05:51:21 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being required by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

if (isset($_GET['models_id'])) {
	if (!isset($nModelsID)) $nModelsID = intval($_GET['models_id']);
} else {
	oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/product_info_webgl_gltf.php';

$sCanonical = oos_href_link($aContents['product_info_webgl_gltf'], 'models_id='. $nModelsID, FALSE, TRUE);

// 3-D Model
$products_modelstable = $oostable['products_models'];
$products_models_sql = "SELECT models_id, products_id, models_name, models_webgl_gltf, models_author, models_author_url, models_camera_pos, 
								models_object_rotation, models_add_lights, models_add_ground, models_shadows, models_add_env_map,
							models_extensions, models_hdr 
							FROM $products_modelstable 
							WHERE models_id = '" . intval($nModelsID) . "'";
$products_models_result = $dbconn->Execute($products_models_sql);	

if (!$products_models_result->RecordCount()) {
	// product not found
	header('HTTP/1.0 404 Not Found');
    $aLang['text_information'] = $aLang['text_model_not_found'];

    $aTemplate['page'] = $sTheme . '/webgl/model_not_found.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;
	$sPagetitle = '404 Not Found ' . OOS_META_TITLE;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';

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

    $products_models_descriptiontable = $oostable['products_models_description'];
    $query = "UPDATE $products_models_descriptiontable"
        . " SET models_viewed = models_viewed+1"
        . " WHERE models_id = ?"
        . "   AND models_languages_id = ?";
    $result = $dbconn->Execute($query, array((int)$nModelsID, (int)$nLanguageID));
    $model_info = $products_models_result->fields;	
	
	$name = oos_strip_suffix($model_info['models_webgl_gltf']);
	$url = $name . '/' . $model_info['models_extensions'] . '/' . $model_info['models_webgl_gltf']; 
$model_info['models_name'] = $name;
		
	ob_start();
	require_once MYOOS_INCLUDE_PATH . '/includes/content/scene3d/product_info_webgl_gltf.js.php';
	$webgl = ob_get_contents();
	ob_end_clean();		
		

    // Meta Tags
    $sPagetitle = $model_info['models_name'] . ' ' . OOS_META_TITLE;


    $aTemplate['page'] = $sTheme . '/webgl/scene3d.html';
	
    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';

	$smarty->assign('canonical', $sCanonical);
	$smarty->assign('webgl', $webgl);
	$smarty->assign('model', $model_info);

    $smarty->setCaching(false);
}


// display the template
$smarty->display($aTemplate['page']);
