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


if (isset($_GET['panorama_id'])) {
    $nPanoramaID = filter_input(INPUT_GET, 'panorama_id', FILTER_VALIDATE_INT);
} else {
    oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/panorama.php';

$sCanonical = oos_href_link($aContents['panorama'], 'panorama_id='. $nPanoramaID, false, true);

// Panorama
$categories_panoramatable = $oostable['categories_panorama'];
$categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
$categories_panorama_scenetable = $oostable['categories_panorama_scene'];
$categories_panorama_sql = "SELECT c.panorama_id, c.categories_id, c.panorama_preview, c.panorama_author, 
								c.panorama_autoload, c.panorama_autorotates, c.panorama_date_added, c.panorama_last_modified,
								cd.panorama_name, cd.panorama_title, cd.panorama_description_meta, cd.panorama_keywords,
								s.scene_id, s.scene_image, s.scene_type, s.scene_hfov, s.scene_pitch, s.scene_yaw, s.scene_default
							FROM $categories_panoramatable c,
								$categories_panorama_descriptiontable cd,
								$categories_panorama_scenetable s
							WHERE c.panorama_id = '" . intval($nPanoramaID) . "'
							AND	cd.panorama_id = c.panorama_id
							AND	s.panorama_id = c.panorama_id
							AND cd.panorama_languages_id = '" . intval($nLanguageID) . "'";
$categories_panorama_result = $dbconn->Execute($categories_panorama_sql);
if (!$categories_panorama_result->RecordCount()) {
    // product not found
    header('HTTP/1.0 404 Not Found');
    $aLang['text_information'] = $aLang['text_model_not_found'];

    $aTemplate['page'] = $sTheme . '/webgl/model_not_found.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;
    $sPagetitle = '404 Not Found ' . OOS_META_TITLE;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));


    $smarty->assign(
        ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['text_model_not_found'], 'robots'        => 'noindex,follow,noodp,noydir', 'canonical'        => $sCanonical]
    );
} else {
    $categories_panorama_descriptiontable = $oostable['categories_panorama_description'];
    $query = "UPDATE $categories_panorama_descriptiontable"
        . " SET panorama_viewed = panorama_viewed+1"
        . " WHERE panorama_id = ?"
        . "   AND panorama_languages_id = ?";
    $result = $dbconn->Execute($query, [(int)$nPanoramaID, (int)$nLanguageID]);

    $panorama_info = $categories_panorama_result->fields;



    $html = "\n";
    $html .= '"hotSpots": [' . "\n";

    $categories_panorama_scene_hotspot = $oostable['categories_panorama_scene_hotspot'];
    $categories_panorama_scene_hotspot_texttable = $oostable['categories_panorama_scene_hotspot_text'];
    $query = "SELECT h.hotspot_id, h.scene_id, h.hotspot_pitch, h.hotspot_yaw, h.hotspot_type,
                 h.hotspot_icon_class, h.products_id, h.categories_id, h.hotspot_url, 
				 ht.hotspot_text
          FROM $categories_panorama_scene_hotspot h,
               $categories_panorama_scene_hotspot_texttable ht
          WHERE h.scene_id = '" . intval($panorama_info['scene_id']) . "'
			AND h.panorama_id = '" . intval($nPanoramaID) . "'
            AND h.hotspot_id = ht.hotspot_id
            AND ht.hotspot_languages_id = '" . intval($nLanguageID) . "'";
    $hotspot_result = $dbconn->Execute($query);
    while ($hotspot = $hotspot_result->fields) {
        if (($hotspot['hotspot_pitch'] != 0) || ($hotspot['hotspot_yaw'] != 0)) {
            $html .= '       {' . "\n";
            $html .= '            "pitch": ' . $hotspot['hotspot_pitch'] . ',' . "\n";
            $html .= '            "yaw": ' . $hotspot['hotspot_yaw'] . ',' . "\n";
            $html .= '            "type": "' . $hotspot['hotspot_type'] . '",' . "\n";
            if (!empty($hotspot['hotspot_text'])) {
                $html .= '            "text": "' . $hotspot['hotspot_text'] . '",' . "\n";
            }
            if (!empty($hotspot['products_id'])) {
                $html .= '            "URL":  "' .  oos_href_link($aContents['product_info'], 'products_id=' . $hotspot['products_id']) . '",' . "\n";
            }
            $html .= '        },' . "\n";
        }

        // Move that ADOdb pointer!
        $hotspot_result->MoveNext();
    }
    $html .= '],' . "\n";

    ob_start();
    include_once MYOOS_INCLUDE_PATH . '/includes/content/scene3d/panorama.js.php';
    $panorama = ob_get_contents();
    ob_end_clean();


    // Meta Tags
    $sPagetitle = $panorama_info['panorama_title'] . ' ' . OOS_META_TITLE;
    $sDescription = $panorama_info['panorama_description_meta'];

    $aTemplate['page'] = $sTheme . '/panorama/pannellum.html';

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';

    $smarty->assign('canonical', $sCanonical);
    $smarty->assign('panorama', $panorama);

    $smarty->setCaching(false);
}

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-$nonce' 'unsafe-eval'");


// register the outputfilter
$smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
