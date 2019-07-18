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

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/product_info_webgl_gltf.php';

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
	$sCanonical = oos_href_link($aContents['product_info_webgl_gltf'], 'products_id='. $nProductsID, FALSE, TRUE);	
	
    $smarty->assign(
        array(
            'breadcrumb'    => $oBreadcrumb->trail(),
            'heading_title' => $aLang['text_product_not_found'],
			'robots'		=> 'noindex,follow,noodp,noydir',
			'canonical'		=> $sCanonical	
        )
    );

} else {

    $product_info = $product_info_result->fields;


	ob_start();
	require_once MYOOS_INCLUDE_PATH . '/includes/modules/three/product_info_webgl_gltf.js.php';
	$javascript = ob_get_contents();
	ob_end_clean();


    // Meta Tags
    $sPagetitle = (empty($product_info['products_title']) ? $product_info['products_name'] : $product_info['products_title']); 
    $sDescription = $product_info['products_description_meta'];

    $aTemplate['page'] = $sTheme . '/page/product_info_webgl_gltf.html';
	
    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    require_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
		require_once MYOOS_INCLUDE_PATH . '/includes/message.php';
		require_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    // breadcrumb
	$oBreadcrumb->add($product_info['products_name'], oos_href_link($aContents['product_info'], 'products_id='. $nProductsID));
	$oBreadcrumb->add('360°');
	$sCanonical = oos_href_link($aContents['product_info_webgl_gltf'], 'products_id='. $nProductsID, FALSE, TRUE);		
	
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


    // assign Smarty variables;
    $smarty->assign(
        array(
			'breadcrumb' => $oBreadcrumb->trail(),
			'canonical'  => $sCanonical
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
	$smarty->assign('javascript', $javascript);	
	
	

    $smarty->setCaching(false);
}


// display the template
$smarty->display($aTemplate['page']);
