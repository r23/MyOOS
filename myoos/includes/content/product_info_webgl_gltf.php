<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: product_info.php,v 1.92 2003/02/14 05:51:21 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being required by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

if (isset($_GET['models_id'])) {
    if (!isset($nModelsID)) {
        $nModelsID = intval($_GET['models_id']);
    }
} else {
    oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/product_info_webgl_gltf.php';

$sCanonical = oos_href_link($aContents['product_info_webgl_gltf'], 'models_id='. $nModelsID, false, true);

// 3-D Model
$products_modelstable = $oostable['products_models'];
$products_models_descriptiontable = $oostable['products_models_description'];
$products_models_sql = "SELECT m.models_id, m.products_id,  md.models_name, md.models_title, md.models_description_meta, 
								md.models_keywords, m.models_webgl_gltf, m.models_author, m.models_author_url, m.models_camera_pos, 
								m.models_object_rotation, m.models_add_lights, m.models_add_ground, m.models_shadows, m.models_add_env_map,
								m.models_extensions, m.models_hdr 
                        FROM $products_modelstable m,
                             $products_models_descriptiontable md
						WHERE m.models_id = '" . intval($nModelsID) . "'
                          AND md.models_id = m.models_id
                          AND md.models_languages_id = '" . intval($nLanguageID) . "'";

$products_models_result = $dbconn->Execute($products_models_sql);

if (!$products_models_result->RecordCount()) {
    // product not found
    header('HTTP/1.0 404 Not Found');
    $aLang['text_information'] = $aLang['text_model_not_found'];

    $aTemplate['page'] = $sTheme . '/webgl/model_not_found.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;
    $sPagetitle = '404 Not Found ' . OOS_META_TITLE;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));


    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['text_model_not_found'],
            'robots'        => 'noindex,follow,noodp,noydir',
            'canonical'     => $sCanonical
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
    $model_path = './media/models/gltf/' . $name . '/' . $model_info['models_extensions'] . '/';

    ob_start();
    include_once MYOOS_INCLUDE_PATH . '/includes/content/scene3d/product_info_webgl_gltf.js.php';
    $webgl = ob_get_contents();
    ob_end_clean();


    // Meta Tags
    $sPagetitle = $model_info['models_title'] . ' ' . OOS_META_TITLE;
    $sDescription = $model_info['models_description_meta'];

    $aTemplate['page'] = $sTheme . '/webgl/scene3d.html';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';

    $smarty->assign('canonical', $sCanonical);
    $smarty->assign('webgl', $webgl);
    $smarty->assign('model', $model_info);

    $smarty->setCaching(false);
}


// display the template
$smarty->display($aTemplate['page']);
