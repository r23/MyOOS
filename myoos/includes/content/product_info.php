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

if (isset($_GET['products_id'])) {
	if (!isset($nProductsID)) $nProductsID = oos_get_product_id($_GET['products_id']);
} else {
	oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_info.php';

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_title, pd.products_description, pd.products_short_description, pd.products_url,
                              pd.products_description_meta, p.products_model, p.products_replacement_product_id,
                              p.products_quantity, p.products_image, p.products_price, p.products_base_price,
							  p.products_product_quantity, p.products_base_unit, p.products_quantity_order_min, 
							  p.products_quantity_order_max, p.products_quantity_order_units,
                              p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                              p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                              p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                              p.products_date_available, p.manufacturers_id, p.products_price_list, p.products_status
                        FROM $productstable p,
                             $products_descriptiontable pd
                        WHERE p.products_setting = '2'
                          AND p.products_id = '" . intval($nProductsID) . "'
                          AND pd.products_id = p.products_id
                          AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
$product_info_result = $dbconn->Execute($product_info_sql);

if (!$product_info_result->RecordCount()) {
	// product not found
	header('HTTP/1.0 404 Not Found');
    $aLang['text_information'] = $aLang['text_product_not_found'];

    $aTemplate['page'] = $sTheme . '/page/info.html';

    $nPageType = OOS_PAGE_TYPE_MAINPAGE;
	$sPagetitle = '404 Not Found ' . OOS_META_TITLE;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
	if (!isset($option)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));
	$sCanonical = oos_href_link($aContents['product_info'], 'products_id='. $nProductsID, FALSE, TRUE);	
	
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['text_product_not_found'],
			'robots'		=> 'noindex,follow,noodp,noydir',
			'canonical'		=> $sCanonical	
        )
    );

} else {

    $products_descriptiontable = $oostable['products_description'];
    $query = "UPDATE $products_descriptiontable"
        . " SET products_viewed = products_viewed+1"
        . " WHERE products_id = ?"
        . "   AND products_languages_id = ?";
    $result = $dbconn->Execute($query, array((int)$nProductsID, (int)$nLanguageID));
    $product_info = $product_info_result->fields;

    // Meta Tags
    $sPagetitle = (empty($product_info['products_title']) ? $product_info['products_name'] : $product_info['products_title']); 
    $sDescription = $product_info['products_description_meta'];

    $aTemplate['page'] = $sTheme . '/page/product_info.html';
    $aTemplate['also_purchased_products'] = $sTheme . '/products/_also_purchased_products.html';
    $aTemplate['xsell_products'] = $sTheme . '/products/xsell_products.html';
    $aTemplate['up_sell_products'] = $sTheme . '/products/up_sell_products.html';
    $aTemplate['page_heading'] = $sTheme . '/products/product_heading.html';

	$aTemplate['slavery_products'] = $sTheme . '/products/_slavery_product_listing.html';
	$aTemplate['slavery_page_navigation'] = $sTheme . '/system/_pagination.htm';	
	
    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    // breadcrumb
	$oBreadcrumb->add($product_info['products_name']);
	$sCanonical = oos_href_link($aContents['product_info'], 'products_id='. $nProductsID, FALSE, TRUE);		
	
    // products history
	if (isset($_SESSION)) {
		$_SESSION['products_history']->add_current_products($nProductsID);
	}

    $info_product_price = NULL;
    $info_product_special_price = NULL;
    $info_base_product_price = NULL;
    $info_product_price_list = 0;
	$schema_product_price = NULL;
	$base_product_price = $product_info['products_price'];

	$info_product_price = $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
	$schema_product_price = $oCurrencies->schema_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']), 1, FALSE);

	if ($info_special_price = oos_get_products_special_price($product_info['products_id'])) {
		$base_product_price = $info_special_price;
		$info_product_special_price = $oCurrencies->display_price($info_special_price, oos_get_tax_rate($product_info['products_tax_class_id']));
	} 

	$discounts_price = FALSE;
    if ( (oos_empty($info_special_price)) && ( ($product_info['products_discount4_qty'] > 0 
		|| $product_info['products_discount3_qty'] > 0 
		|| $product_info['products_discount2_qty'] > 0 
		|| $product_info['products_discount1_qty'] > 0 )) ) {

		if ( ($aUser['show_price'] == 1 ) && ($aUser['qty_discounts'] == 1) ) {
			$discounts_price = TRUE;
			require_once MYOOS_INCLUDE_PATH . '/includes/modules/discounts_price.php';

			if ( $product_info['products_discount4'] > 0 ) {
				$price_discount = $product_info['products_discount4'];
			} elseif ( $product_info['products_discount3'] > 0 ) {
				$price_discount = $product_info['products_discount3'];
			} elseif ( $product_info['products_discount2'] > 0 ) {
				$price_discount = $product_info['products_discount2'];
			} elseif ( $product_info['products_discount1'] > 0 ) {
				$price_discount = $product_info['products_discount1'];
			}
			if (isset($price_discount)) {
				$base_product_price = $price_discount;
				$smarty->assign('price_discount', $oCurrencies->display_price($price_discount, oos_get_tax_rate($product_info['products_tax_class_id'])));
			}
		}
	}

	if ($product_info['products_base_price'] != 1) {	
        $info_base_product_price = $oCurrencies->display_price($base_product_price * $product_info['products_base_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
	}
	  
    // assign Smarty variables;
    $smarty->assign(
        array(
            'info_product_price'			=> $info_product_price,
			'schema_product_price'			=> $schema_product_price, 
            'info_product_special_price'	=> $info_product_special_price,
            'info_base_product_price'		=> $info_base_product_price,
			'discounts_price' 				=> $discounts_price
        )
    );

	$info_product_price_list = $oCurrencies->display_price($product_info['products_price_list'], oos_get_tax_rate($product_info['products_tax_class_id']));
	$smarty->assign('info_product_price_list', $info_product_price_list);

	if ($oEvent->installed_plugin('manufacturers')) {
		$manufacturerstable = $oostable['manufacturers'];
		$manufacturers_infotable = $oostable['manufacturers_info'];
		$query = "SELECT m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url 
              FROM $manufacturerstable m,
                   $manufacturers_infotable mi
               WHERE m.manufacturers_id = '" . intval($product_info['manufacturers_id']) . "'
			     AND mi.manufacturers_id = m.manufacturers_id
			     AND mi.manufacturers_languages_id = '" . intval($nLanguageID) . "'";
		$manufacturers_result = $dbconn->Execute($query);
		$manufacturers_info = $manufacturers_result->fields;
		$smarty->assign('manufacturers_info', $manufacturers_info);
	}


    if ($oEvent->installed_plugin('reviews')) {
		$reviewstable = $oostable['reviews'];
		$reviews_sql = "SELECT COUNT(*) AS total FROM $reviewstable WHERE products_id = '" . intval($nProductsID) . "' AND reviews_status = '1'";
		$reviews = $dbconn->Execute($reviews_sql);
		$reviews_total = $reviews->fields['total'];
		$smarty->assign('reviews_total', $reviews_total);
	  
		if ($reviews->RecordCount()) {
			$reviews_average_result = $dbconn->Execute("SELECT avg(reviews_rating) as average_rating FROM $reviewstable WHERE products_id = '" .  intval($nProductsId) . "'");
			$reviews_average = $reviews_average_result->fields;
			$smarty->assign('average_rating', $reviews_average);		  
		}
    }	

	// more products images
	$products_imagestable = $oostable['products_images'];
	$products_images_sql = "SELECT image_name, sort_order
                        FROM $products_imagestable
                        WHERE products_id = '" . intval($nProductsID) . "'
						ORDER BY sort_order";
	$products_images_result = $dbconn->Execute($products_images_sql);	
	if ($products_images_result->RecordCount()) {
		
		$aProductsImages = array();
		while ($products_images = $products_images_result->fields) {

			$aProductsImages[] = array('image' => $products_images['image_name']);
			
			// Move that ADOdb pointer!
			$products_images_result->MoveNext();
		}

		$smarty->assign('products_images', $aProductsImages);
	}
	

	// 3-D Model
	$products_modelstable = $oostable['products_models'];
	$products_models_sql = "SELECT models_id, products_id, models_webgl_gltf, models_author, models_author_url, models_camera_pos, 
									models_object_rotation, models_add_lights, models_add_ground, models_shadows, models_add_env_map,
									models_extensions, models_hdr 
							FROM $products_modelstable 
							WHERE products_id = '" . intval($nProductsID) . "'";
	$products_models_result = $dbconn->Execute($products_models_sql);	
	if ($products_models_result->RecordCount()) {
		
		$aProductsModels = array();
		while ($products_models = $products_models_result->fields) {

			$aProductsModels[] = array('models_id' => $products_models['models_id'],
										'products_id' => $products_models['products_id'],
										'models_webgl_gltf' => $products_models['models_webgl_gltf'],
										'models_author' => $products_models['models_author'],
										'models_author_url' => $products_models['models_author_url'],
										'models_camera_pos' => $products_models['models_camera_pos'],
										'models_object_rotation' => $products_models['models_object_rotation'],
										'models_add_lights' => $products_models['models_add_lights'],
										'models_add_ground' => $products_models['models_add_ground'],
										'models_shadows' => $products_models['models_shadows'],
										'models_add_env_map' => $products_models['models_add_env_map'],
										'models_extensions' => $products_models['models_extensions'],
										'models_hdr' => $products_models['models_hdr']);		
			// Move that ADOdb pointer!
			$products_models_result->MoveNext();
		}
		
		foreach ($aProductsModels as $models) {

/*
			$aProductsModels[] = array('models_id' => $products_models['models_id'],
										'products_id' => $products_models['products_id'],
										'models_webgl_gltf' => $models['models_webgl_gltf'],
										'models_author' => $products_models['models_author'],
										'models_author_url' => $products_models['models_author_url'],
										'models_camera_pos' => $products_models['models_camera_pos'],
										'models_object_rotation' => $products_models['models_object_rotation'],
										'models_add_lights' => $products_models['models_add_lights'],
										'models_add_ground' => $products_models['models_add_ground'],
										'models_shadows' => $products_models['models_shadows'],
										'models_add_env_map' => $products_models['models_add_env_map'],
										'models_extensions' => $products_models['models_extensions'],
										'models_hdr' => $products_models['models_hdr']);
										
	<?php echo $models['models_hdr']; ?>									
										*/
			$name = oos_strip_suffix($models['models_webgl_gltf']);
			$url = $name . '/' . $models['models_extensions'] . '/' . $models['models_webgl_gltf']; 

		
			ob_start();
			require_once MYOOS_INCLUDE_PATH . '/includes/content/scene3d/product_info_webgl_gltf.js.php';
			$webgl = ob_get_contents();
			ob_end_clean();
		}
		$smarty->assign('webgl', $webgl);
	}

	
    require_once MYOOS_INCLUDE_PATH . '/includes/modules/products_options.php';

    // assign Smarty variables;
    $smarty->assign(
        array(
			'breadcrumb' => $oBreadcrumb->trail(),
			'canonical'		=> $sCanonical
		)
	);

    if (!isset($block_get_parameters)) {
		$block_get_parameters = oos_get_all_get_parameters(array('action'));
		$block_get_parameters = oos_remove_trailing($block_get_parameters);
		$smarty->assign('get_params', $block_get_parameters);
    }

	$today = date("Y-m-d H:i:s");
	$smarty->assign('today', $today);

    $smarty->assign('product_info', $product_info);
	$smarty->assign('heading_title', $product_info['products_name']);
    $smarty->assign('options', $options);

    $smarty->assign('redirect', oos_href_link($aContents['redirect'], 'action=url&amp;goto=' . urlencode($product_info['products_url']), FALSE, FALSE));

	
	$notifications_block = FALSE;
	if ($oEvent->installed_plugin('notify')) {
		$notifications_block = TRUE;

		if (isset($_SESSION['customer_id'])) {
			$products_notificationstable = $oostable['products_notifications'];
			$query = "SELECT COUNT(*) AS total
                FROM $products_notificationstable
                WHERE products_id = '" . intval($nProductsID) . "'
                  AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
			$check = $dbconn->Execute($query);
			$notification_exists = (($check->fields['total'] > 0) ? TRUE : FALSE);
		} else {
			$notification_exists = FALSE;
		}
		$smarty->assign('notification_exists', $notification_exists);
	}
	$smarty->assign('notifications_block', $notifications_block);	
	
	
	if ( (USE_CACHE == 'true') && (!isset($_SESSION)) ) {
		$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	}

	if (!$smarty->isCached($aTemplate['slavery_products'], $sProductsInfoCacheID)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';
	}
	$smarty->assign('slavery_products', $smarty->fetch($aTemplate['slavery_products'], $sProductsInfoCacheID));

	
	// also purchased products
	if (!$smarty->isCached($aTemplate['also_purchased_products'], $sProductsInfoCacheID)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/modules/also_purchased_products.php';
		$smarty->assign('also_purchased', $aPurchased);
	}
	$smarty->assign('also_purchased_products', $smarty->fetch($aTemplate['also_purchased_products'], $sProductsInfoCacheID));

    $smarty->setCaching(false);
}


// display the template
$smarty->display($aTemplate['page']);
