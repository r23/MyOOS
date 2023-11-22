<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;

if (!isset($nProductsID)) {
    $products_id = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
    $nProductsID = oos_get_product_id($products_id);
} else {
    oos_redirect(oos_href_link($aContents['home']));
}

require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/products_info.php';

$productstable = $oostable['products'];
$products_descriptiontable = $oostable['products_description'];
$product_info_sql = "SELECT p.products_id, pd.products_name, pd.products_title, pd.products_description, pd.products_short_description, pd.products_url,
                              pd.products_description_meta, pd.products_facebook_title, pd.products_facebook_description, pd.products_twitter_title,
							  pd.products_twitter_description, pd.products_old_electrical_equipment_description, pd.products_used_goods_description,
							  p.products_model, p.products_replacement_product_id, p.products_used_goods,
                              p.products_quantity, p.products_image, p.products_price, p.products_base_price,
							  p.products_product_quantity, p.products_base_unit, p.products_quantity_order_min, 
							  p.products_quantity_order_max, p.products_quantity_order_units,
                              p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4,
                              p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty,
                              p.products_discount4_qty, p.products_tax_class_id, p.products_units_id, p.products_date_added,
                              p.products_date_available, p.products_last_modified, p.manufacturers_id, p.products_price_list, p.products_status
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
    $sCanonical = oos_href_link($aContents['product_info'], 'products_id='. $nProductsID, false, true);

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    $oBreadcrumb->add($aLang['navbar_title'], oos_href_link($aContents['products_new']));

    $smarty->assign(
        ['breadcrumb'    => $oBreadcrumb->trail(), 'heading_title' => $aLang['text_product_not_found'], 'robots'        => 'noindex,follow,noodp,noydir', 'canonical'        => $sCanonical]
    );
} else {
    $products_descriptiontable = $oostable['products_description'];
    $query = "UPDATE $products_descriptiontable"
        . " SET products_viewed = products_viewed+1"
        . " WHERE products_id = ?"
        . "   AND products_languages_id = ?";
    $result = $dbconn->Execute($query, [(int)$nProductsID, (int)$nLanguageID]);
    $product_info = $product_info_result->fields;

    // Meta Tags
    $sPagetitle = (empty($product_info['products_title']) ? $product_info['products_name'] : $product_info['products_title']);
    $sDescription = $product_info['products_description_meta'];

    $facebook_title = $product_info['products_facebook_title'];
    $twitter_title = $product_info['products_twitter_title'];

    $sDescription = $product_info['products_description_meta'];
    $facebook_description = $product_info['products_facebook_description'];
    $twitter_description = $product_info['products_twitter_description'];

    $og_image = isset($product_info['products_image']) ? OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'product/large/' . $product_info['products_image'] : '';

    $aTemplate['page'] = $sTheme . '/page/product_info.html';
    $aTemplate['also_purchased_products'] = $sTheme . '/products/_also_purchased_products.html';
    $aTemplate['xsell_products'] = $sTheme . '/products/xsell_products.html';
    $aTemplate['up_sell_products'] = $sTheme . '/products/up_sell_products.html';
    $aTemplate['page_heading'] = $sTheme . '/products/product_heading.html';

    $aTemplate['slavery_products'] = $sTheme . '/products/_slavery_product_listing.html';
    $aTemplate['slavery_page_navigation'] = $sTheme . '/system/_pagination.htm';

    $sCanonical = oos_href_link($aContents['product_info'], 'products_id='. $nProductsID, false, true);

    $nPageType = OOS_PAGE_TYPE_PRODUCTS;

    include_once MYOOS_INCLUDE_PATH . '/includes/system.php';
    if (!isset($option)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/message.php';
        include_once MYOOS_INCLUDE_PATH . '/includes/blocks.php';
    }

    // breadcrumb
    $oBreadcrumb->add($product_info['products_name']);

    // products history
    if (isset($_SESSION) && $bPersonalization == true) {
        $_SESSION['products_history']->add_current_products($nProductsID);
    }

    $info_product_price = null;
    $info_product_special_price = null;
    $info_base_product_price = null;
    $info_product_price_list = null;
    $schema_product_price = null;
    $cross_out_price = null;
    $base_product_price = $product_info['products_price'];

    // Selector
    $bTypeRadio = false;
    $aSelector = [];

    $options = '';
    $number_of_uploads = 0;

    $products_optionstable = $oostable['products_options'];
    $products_attributestable = $oostable['products_attributes'];
    $attributes_sql = "SELECT COUNT(*) AS total
                       FROM $products_optionstable popt,
                            $products_attributestable patrib
                       WHERE patrib.products_id = '" . intval($nProductsID) . "'
                         AND patrib.options_id = popt.products_options_id
                         AND popt.products_options_languages_id = '" . intval($nLanguageID) . "'";
    $products_attributes = $dbconn->Execute($attributes_sql);
    if ($products_attributes->fields['total'] > 0) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/products_options.php';
    }

    $smarty->assign('selector_array', $aSelector);

    // Product gallery
    $sImageLink = oos_href_link($aContents['product_info'], 'products_id='. $nProductsID, true, true);
    $smarty->assign('image_link', $sImageLink);

    $info_product_price = $oCurrencies->display_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
    $schema_product_price = $oCurrencies->schema_price($product_info['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']), 1, false);
    $calculate_price = $product_info['products_price'];

    if ($special_price = oos_get_products_special($product_info['products_id'])) {
        $calculate_price = $special_price['specials_new_products_price'];
        $base_product_price = $special_price['specials_new_products_price'];

        $info_product_only_until = sprintf($aLang['only_until'], oos_date_short($special_price['expires_date']));
        $smarty->assign('info_product_only_until', $info_product_only_until);
        $info_product_special_price = $oCurrencies->display_price($special_price['specials_new_products_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
        if ($special_price['specials_cross_out_price'] > 0) {
            $cross_out_price = $oCurrencies->display_price($special_price['specials_cross_out_price'], oos_get_tax_rate($product_info['products_tax_class_id']));
            $smarty->assign('cross_out_price', $cross_out_price);
        }
    }


    // Minimum Order Value
    if (defined('MINIMUM_ORDER_VALUE') && oos_is_not_null(MINIMUM_ORDER_VALUE)) {
        $minimum_order_value = str_replace(',', '.', (string) MINIMUM_ORDER_VALUE);

        $calculate_price = $oCurrencies->calculate_price($calculate_price, oos_get_tax_rate($product_info['products_tax_class_id']));

        if ($calculate_price < $minimum_order_value) {
            $smarty->assign('show_minimum_order_value', 1);
        }
    }



    $discounts_price = false;
    if ((oos_empty($special_price)) && (($product_info['products_discount4_qty'] > 0
        || $product_info['products_discount3_qty'] > 0
        || $product_info['products_discount2_qty'] > 0
        || $product_info['products_discount1_qty'] > 0))
    ) {
        if (($aUser['show_price'] == 1) && ($aUser['qty_discounts'] == 1)) {
            $discounts_price = true;
            include_once MYOOS_INCLUDE_PATH . '/includes/modules/discounts_price.php';

            if ($product_info['products_discount4'] > 0) {
                $price_discount = $product_info['products_discount4'];
            } elseif ($product_info['products_discount3'] > 0) {
                $price_discount = $product_info['products_discount3'];
            } elseif ($product_info['products_discount2'] > 0) {
                $price_discount = $product_info['products_discount2'];
            } elseif ($product_info['products_discount1'] > 0) {
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
        ['info_product_price'          => $info_product_price, 'schema_product_price'        => $schema_product_price, 'info_product_special_price'  => $info_product_special_price, 'info_base_product_price'     => $info_base_product_price, 'discounts_price'             => $discounts_price]
    );

    if ($product_info['products_price_list'] < 0) {
        $info_product_price_list = $oCurrencies->display_price($product_info['products_price_list'], oos_get_tax_rate($product_info['products_tax_class_id']));
    }
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
        $informationtable = $oostable['information'];
        $information_descriptiontable = $oostable['information_description'];
        $sql = "SELECT i.information_id, id.information_name,
						id.information_description, id.information_heading_title
				FROM $informationtable i,
					$information_descriptiontable id
				WHERE i.information_id = '7'
				AND id.information_id = i.information_id
				AND id.information_languages_id = '" .  intval($nLanguageID) . "'";
        $reviews_information = $dbconn->GetRow($sql);
        $smarty->assign('reviews_information', $reviews_information);


        $smarty->assign('reviews_write_link', oos_href_link($aContents['product_reviews_write'], 'products_id=' . intval($nProductsID)));


        $reviewstable = $oostable['reviews'];
        $reviews_sql = "SELECT COUNT(*) AS total FROM $reviewstable WHERE products_id = '" . intval($nProductsID) . "' AND reviews_status = '1'";
        $reviews = $dbconn->Execute($reviews_sql);
        $reviews_total = $reviews->fields['total'];
        $smarty->assign('reviews_total', $reviews_total);

        if ($reviews->RecordCount()) {
            $reviews_average_result = $dbconn->Execute("SELECT avg(reviews_rating) AS average_rating FROM $reviewstable WHERE products_id = '" .  intval($nProductsID) . "'");
            $reviews_average = $reviews_average_result->fields;
            $smarty->assign('average_rating', $reviews_average['average_rating']);
        }
    }

    // more products images
    $product_gallerytable = $oostable['products_gallery'];
    $product_gallery_sql = "SELECT image_name, sort_order
                        FROM $product_gallerytable
                        WHERE products_id = '" . intval($nProductsID) . "'
						ORDER BY sort_order";
    $product_gallery_result = $dbconn->Execute($product_gallery_sql);
    if ($product_gallery_result->RecordCount()) {
        $aProductsImages = [];
        $aProductsImages[] = ['image' => $product_info['products_image']];
        while ($product_gallery = $product_gallery_result->fields) {
            $aProductsImages[] = ['image' => $product_gallery['image_name']];

            // Move that ADOdb pointer!
            $product_gallery_result->MoveNext();
        }

        $smarty->assign('product_gallery', $aProductsImages);
    }

    // 3-D Model
    $products_modelstable = $oostable['products_models'];
    $products_models_descriptiontable = $oostable['products_models_description'];
    $products_models_sql = "SELECT pm.models_id, pmd.models_name
							FROM $products_modelstable pm,
								$products_models_descriptiontable pmd
							WHERE pm.products_id = '" . intval($nProductsID) . "'
							AND pmd.models_id = pm.models_id
							AND pmd.models_languages_id = '" . intval($nLanguageID) . "'";
    $products_models_result = $dbconn->Execute($products_models_sql);
    if ($products_models_result->RecordCount()) {
        $aProductsModels = [];
        while ($products_models = $products_models_result->fields) {
            $aProductsModels[] = ['models_id' => $products_models['models_id'], 'name' => $products_models['models_name'], 'products_id' => $products_models['products_id']];
            // Move that ADOdb pointer!
            $products_models_result->MoveNext();
        }

        $smarty->assign('products_models_array', $aProductsModels);
    }

    // AR  Model
    $products_model_viewertable = $oostable['products_model_viewer'];
    $products_model_viewer_descriptiontable = $oostable['products_model_viewer_description'];
    $products_model_viewer_sql = "SELECT m.model_viewer_id, m.products_id, m.model_viewer_glb,
										m.model_viewer_usdz, m.model_viewer_background_color, 
										m.model_viewer_auto_rotate, m.model_viewer_scale, m.model_viewer_hdr,
										md.model_viewer_title, md.model_viewer_description
								FROM $products_model_viewertable m,
										$products_model_viewer_descriptiontable md
								WHERE m.products_id = '" . intval($nProductsID) . "'
								AND m.model_viewer_id = md.model_viewer_id
								AND md.model_viewer_languages_id = '" . intval($nLanguageID) . "'";
    $products_model_viewer_result = $dbconn->Execute($products_model_viewer_sql);
    if ($products_model_viewer_result->RecordCount()) {
        $aModelViewer = [];
        while ($model_viewer = $products_model_viewer_result->fields) {
            $products_model_viewer_descriptiontable = $oostable['products_model_viewer_description'];
            $query = "UPDATE $products_model_viewer_descriptiontable"
                . " SET model_viewer_viewed = model_viewer_viewed+1"
                . " WHERE model_viewer_id = ?"
                . "   AND model_viewer_languages_id = ?";
            $result = $dbconn->Execute($query, [(int)$model_viewer['model_viewer_id'], (int)$nLanguageID]);

            $name = oos_strip_suffix($model_viewer['model_viewer_glb']);
            $url_glb = $name . '/glTF-Binary/' . $model_viewer['model_viewer_glb'];

            $aModelViewer[] = ['model_viewer_id' => $model_viewer['model_viewer_id'], 'model_viewer_glb' => $model_viewer['model_viewer_glb'], 'url_glb' => $url_glb, 'model_viewer_usdz' => $model_viewer['model_viewer_usdz'], 'model_viewer_background_color' => $model_viewer['model_viewer_background_color'], 'model_viewer_scale' => $model_viewer['model_viewer_scale'], 'model_viewer_auto_rotate' => $model_viewer['model_viewer_auto_rotate'], 'model_viewer_hdr' => $model_viewer['model_viewer_hdr'], 'model_viewer_title' => $model_viewer['model_viewer_title'], 'model_viewer_description' => $model_viewer['model_viewer_description']];

            // Move that ADOdb pointer!
            $products_model_viewer_result->MoveNext();
        }

        $smarty->assign('model_viewer_array', $aModelViewer);


        // QR - Code
        $detect = new Mobile_Detect();
        if (isset($_SESSION)) {
            if (!$_SESSION['isMobile']) {
                $_SESSION['isMobile'] = $detect->isMobile();
            }
        }

        $isMobile = isset($_SESSION['isMobile']) ? oos_var_prep_for_os($_SESSION['isMobile']) : $detect->isMobile();

        // Any mobile device (phones or tablets).
        if (!$isMobile) {
            $name = hash('ripemd160', $product_info['products_name'] . $nProductsID);
            $filename = $name . '.png';
            $cache_file = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'qrcode/' . $filename;

            if (!file_exists($cache_file)) {
                $sUrl = $sCanonical;
                if (str_contains((string) $sUrl, '&amp;')) {
                    $sUrl = str_replace('&amp;', '&', (string) $sUrl);
                }
                if (str_contains((string) $sUrl, '&&')) {
                    $sUrl = str_replace('&&', '&', (string) $sUrl);
                }

                // Create a basic QR code
                $writer = new PngWriter();

                // Create QR code
                $qrCode = QrCode::create($sUrl)
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setSize(300)
                    ->setMargin(10)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255));

                $result = $writer->write($qrCode);

                // Save it to a file
                $result->saveToFile($cache_file);

                // Save it to a file
                $qrCode->writeFile($cache_file);
            }
            $smarty->assign('qrcode', $filename);
        }
    }
    // End AR


    // Video
    $products_videotable = $oostable['products_video'];
    $products_video_descriptiontable = $oostable['products_video_description'];
    $products_video_sql = "SELECT v.video_id, v.products_id, v.video_source,
								v.video_mp4, v.video_webm, v.video_ogv,
								v.video_poster, v.video_preload, 
								vd.video_title, vd.video_description
						FROM $products_videotable v,
							$products_video_descriptiontable vd
						WHERE v.products_id = '" . intval($nProductsID) . "'
						AND v.video_id = vd.video_id
						AND vd.video_languages_id = '" . intval($nLanguageID) . "'";
    $products_video_result = $dbconn->Execute($products_video_sql);
    if ($products_video_result->RecordCount()) {
        $oos_js .= '<script nonce="' . NONCE . '" src="' . OOS_HTTPS_SERVER . OOS_SHOP . 'js/videojs/dist/video.min.js"></script>';
        $oos_css .= '<link rel="stylesheet" href="' . OOS_HTTPS_SERVER . OOS_SHOP . 'js/videojs/dist/video-js.min.css">';
        $aVideo = [];
        while ($video = $products_video_result->fields) {
            $products_video_descriptiontable = $oostable['products_video_description'];
            $query = "UPDATE $products_video_descriptiontable"
                . " SET video_viewed = video_viewed+1"
                . " WHERE video_id = ?"
                . "   AND video_languages_id = ?";
            $result = $dbconn->Execute($query, [(int)$video['video_id'], (int)$nLanguageID]);

            $video_path = OOS_HTTPS_SERVER . OOS_SHOP . OOS_MEDIA . 'video/';
            $video_poster = OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'video/' . $video['video_poster'];

            $video_source = (!empty($video['video_source'])) ? $video_path . $video['video_source'] : '';
            $video_mp4 = (!empty($video['video_mp4'])) ? $video_path . $video['video_mp4'] : '';
            $video_webm = (!empty($video['video_webm'])) ? $video_path . $video['video_webm'] : '';
            $video_ogv = (!empty($video['video_ogv'])) ? $video_path . $video['video_ogv'] : '';

            $aVideo[] = ['video_id' => $video['video_id'], 'video_source' => $video_source, 'video_mp4' => $video_mp4, 'video_webm' => $video_webm, 'video_ogv' => $video_ogv, 'video_poster' => $video_poster, 'video_preload' => $video['video_preload'], 'video_title' => $video['video_title'], 'video_description' => $video['video_description']];

            // Move that ADOdb pointer!
            $products_video_result->MoveNext();
        }

        $smarty->assign(
            ['video_array'        => $aVideo, 'oos_css'            => $oos_css, 'oos_js'            => $oos_js]
        );
    }


    // assign Smarty variables;
    $smarty->assign(
        ['breadcrumb' => $oBreadcrumb->trail(), 'canonical'     => $sCanonical]
    );

    $today = date("Y-m-d H:i:s");
    $smarty->assign('today', $today);

    // Price History Chart
    if ((PRODUCTS_CHARTS == 'true') && ($aUser['show_price'] == 1)) {
        ob_start();
        include_once MYOOS_INCLUDE_PATH . '/includes/content/chart/line.php';
        $chart = ob_get_contents();
        ob_end_clean();
        $smarty->assign('chart', $chart);
        /*
        $aPriceAlert = [];
        $five = $calculate_price - ($calculate_price / 100) * 5;
        $aPriceAlert['five'] = $oCurrencies->display_price($five, oos_get_tax_rate($product_info['products_tax_class_id']));

        $ten = $calculate_price - ($calculate_price / 100) * 10;
        $aPriceAlert['ten'] = $oCurrencies->display_price($ten, oos_get_tax_rate($product_info['products_tax_class_id']));

        $fifteen = $calculate_price - ($calculate_price / 100) * 15;
        $aPriceAlert['fifteen'] = $oCurrencies->display_price($fifteen, oos_get_tax_rate($product_info['products_tax_class_id']));

        $smarty->assign('pricealert', $aPriceAlert);
        */
    }

    if (!isset($block_get_parameters)) {
        $block_get_parameters = oos_get_all_get_parameters(['action']);
        $block_get_parameters = oos_remove_trailing($block_get_parameters);
        $smarty->assign('get_params', $block_get_parameters);
    }



    $smarty->assign('product_info', $product_info);
    $smarty->assign('heading_title', $product_info['products_name']);
    $smarty->assign('options', $options);

    $smarty->assign('redirect', oos_href_link($aContents['redirect'], 'action=url&amp;goto=' . urlencode((string) $product_info['products_url']), false, false));


    $notifications_block = false;
    if ($oEvent->installed_plugin('notify')) {
        $notifications_block = true;

        if (isset($_SESSION['customer_id'])) {
            $products_notificationstable = $oostable['products_notifications'];
            $query = "SELECT COUNT(*) AS total
                FROM $products_notificationstable
                WHERE products_id = '" . intval($nProductsID) . "'
                  AND customers_id = '" . intval($_SESSION['customer_id']) . "'";
            $check = $dbconn->Execute($query);
            $notification_exists = (($check->fields['total'] > 0) ? true : false);
        } else {
            $notification_exists = false;
        }
        $smarty->assign('notification_exists', $notification_exists);
    }
    $smarty->assign('notifications_block', $notifications_block);


    if ((USE_CACHE == 'true') && (!isset($_SESSION))) {
        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
    }

    if (!$smarty->isCached($aTemplate['slavery_products'], $sProductsInfoCacheID)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/slavery_products.php';
    }
    $smarty->assign('slavery_products', $smarty->fetch($aTemplate['slavery_products'], $sProductsInfoCacheID));


    // also purchased products
    if (!$smarty->isCached($aTemplate['also_purchased_products'], $sProductsInfoCacheID)) {
        include_once MYOOS_INCLUDE_PATH . '/includes/modules/also_purchased_products.php';
        $smarty->assign('also_purchased', $aPurchased);
    }
    $smarty->assign('also_purchased_products', $smarty->fetch($aTemplate['also_purchased_products'], $sProductsInfoCacheID));

    $smarty->setCaching(false);
}

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");

// register the outputfilter
# $smarty->loadFilter('output', 'trimwhitespace');

// display the template
$smarty->display($aTemplate['page']);
