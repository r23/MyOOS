<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: languages.php,v 1.32 2002/03/17 17:37:51 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if (!empty($action)) {
    switch ($action) {
    case 'setflag':
        $lID = oos_db_prepare_input($_GET['lID']);

        if (isset($_GET['flag']) && ($_GET['flag'] == '0')) {
            $dbconn->Execute(
                "UPDATE " . $oostable['languages'] . "
                        SET status = '0'
                        WHERE languages_id = '" . intval($lID) . "'"
            );
        } elseif (isset($_GET['flag']) && ($_GET['flag'] == '1')) {
            $dbconn->Execute(
                "UPDATE " . $oostable['languages'] . "
                        SET status = '1'
                        WHERE languages_id = '" . intval($lID) . "'"
            );
        }
        oos_redirect_admin(oos_href_link_admin($aContents['languages'], 'page=' . $nPage. '&lID=' . $_GET['lID']));
        break;

    case 'insert':
        $name = isset($_POST['name']) ? oos_db_prepare_input($_POST['name']) : '';
        $iso_639_2 = isset($_POST['iso_639_2']) ? oos_db_prepare_input($_POST['iso_639_2']) : '';
        $iso_639_1 = isset($_POST['iso_639_1']) ? oos_db_prepare_input($_POST['iso_639_1']) : '';
        $iso_3166_1 = isset($_POST['iso_3166_1']) ? oos_db_prepare_input($_POST['iso_3166_1']) : '';
        $sort_order = isset($_POST['sort_order']) ? intval($_POST['sort_order']) : 1;

        $sql = "INSERT INTO " . $oostable['languages'] . "
                (name,
                 iso_639_2,
                 iso_639_1,
				 iso_3166_1,
				 sort_order)
                 VALUES ('" . oos_db_input($name) . "',
                         '" . oos_db_input($iso_639_2) . "',
                         '" . oos_db_input($iso_639_1) . "',
						 '" . oos_db_input($iso_3166_1) . "',
						 '" . oos_db_input($sort_order) . "')";
        $dbconn->Execute($sql);
        $insert_id = $dbconn->Insert_ID();

        //block_info
        $block_info_result = $dbconn->Execute(
            "SELECT block_id, block_name
                                           FROM " . $oostable['block_info'] . "
                                           WHERE block_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($block_info = $block_info_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['block_info'] . "
                      (block_id,
                       block_languages_id,
                       block_name)
                       VALUES ('" . $block_info['block_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($block_info['block_name']) . "')"
            );

            // Move that ADOdb pointer!
            $block_info_result->MoveNext();
        }
        // create additional categories_description records
        $categories_result = $dbconn->Execute(
            "SELECT c.categories_id, cd.categories_name, cd.categories_heading_title, cd.categories_description,
                                                  cd.categories_description_meta
                                          FROM " . $oostable['categories'] . " c LEFT JOIN
                                               " . $oostable['categories_description'] . " cd
                                             ON c.categories_id = cd.categories_id
                                          WHERE cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );

        while ($categories = $categories_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['categories_description'] . "
                      (categories_id,
                       categories_languages_id,
                       categories_name,
                       categories_heading_title,
                       categories_description,
                       categories_description_meta) 
                       VALUES ('" . $categories['categories_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($categories['categories_name']) . "',
                               '" . oos_db_input($categories['categories_heading_title']) . "',
                               '" . oos_db_input($categories['categories_description']) . "',
                               '" . oos_db_input($categories['categories_description_meta']) . "')"
            );

            // Move that ADOdb pointer!
            $categories_result->MoveNext();
        }

        // categories_images
        $categories_images_result = $dbconn->Execute(
            "SELECT ci.categories_images_id, cid.categories_images_title, 
												cid.categories_images_caption, cid.categories_images_description
                                          FROM " . $oostable['categories_images'] . " ci LEFT JOIN
                                               " . $oostable['categories_images_description'] . " cid
                                             ON ci.categories_images_id = cid.categories_images_id
                                          WHERE cid.categories_images_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($categories_images = $categories_images_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['categories_images_description'] . "
                      (categories_images_id,
                       categories_images_languages_id,
                       categories_images_title,
                       categories_images_caption,
                       categories_images_description) 
                       VALUES ('" . $categories_images['categories_images_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($categories_images['categories_images_title']) . "',
                               '" . oos_db_input($categories_images['categories_images_caption']) . "',
                               '" . oos_db_input($categories_images['categories_images_description']) . "')"
            );

            // Move that ADOdb pointer!
            $categories_images_result->MoveNext();
        }

        // categories_panorama
        $categories_panorama_result = $dbconn->Execute(
            "SELECT cp.panorama_id, cpd.panorama_name, cpd.panorama_title,
												cpd.panorama_description_meta, cpd.panorama_keywords
                                          FROM " . $oostable['categories_panorama'] . " cp LEFT JOIN
                                               " . $oostable['categories_panorama_description'] . " cpd
                                             ON cp.panorama_id = cpd.panorama_id
                                          WHERE cpd.panorama_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($categories_panorama = $categories_panorama_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['categories_panorama_description'] . "
                      (panorama_id, 
						panorama_languages_id,
						panorama_name,
						panorama_title,
						panorama_viewed,
						panorama_description_meta,
						panorama_keywords) 
                       VALUES ('" . $categories_panorama['panorama_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($categories_panorama['panorama_name']) . "',
                               '" . oos_db_input($categories_panorama['panorama_title']) . "',
							   '0',
							   '" . oos_db_input($categories_panorama['panorama_description_meta']) . "',
                               '" . oos_db_input($categories_panorama['panorama_keywords']) . "')"
            );

            // Move that ADOdb pointer!
            $categories_panorama_result->MoveNext();
        }

        // hotspot_text
        $scene_hotspot_result = $dbconn->Execute(
            "SELECT sh.hotspot_id, sht.hotspot_text
                                          FROM " . $oostable['categories_panorama_scene_hotspot'] . " sh LEFT JOIN
                                               " . $oostable['categories_panorama_scene_hotspot_text'] . " sht
                                             ON sh.hotspot_id = sht.hotspot_id
                                          WHERE sht.hotspot_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($scene_hotspot = $scene_hotspot_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['categories_panorama_scene_hotspot_text'] . "
                      (hotspot_id, 
						hotspot_languages_id,
						hotspot_text) 
                       VALUES ('" . $scene_hotspot['hotspot_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($scene_hotspot['hotspot_text']) . "')"
            );

            // Move that ADOdb pointer!
            $scene_hotspot_result->MoveNext();
        }

        //coupons_description
        $coupon_result = $dbconn->Execute(
            "SELECT c.coupon_id, cd.coupon_name, cd.coupon_description
                                      FROM " . $oostable['coupons'] . " c LEFT JOIN
                                           " . $oostable['coupons_description'] . " cd
                                          ON c.coupon_id = cd.coupon_id
                                      WHERE cd.coupon_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($coupon = $coupon_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['coupons_description'] . "
                      (coupon_id,
                       coupon_languages_id,
                       coupon_name,
                       coupon_description)
                       VALUES ('" . $coupon['coupon_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($coupon['coupon_name']) . "',
                               '" . oos_db_input($coupon['coupon_description']) . "')"
            );

            // Move that ADOdb pointer!
            $coupon_result->MoveNext();
        }
        //customers_status
        $customers_status_result = $dbconn->Execute(
            "SELECT customers_status_id, customers_status_name, customers_status_ot_discount_flag,
                                                        customers_status_ot_discount, customers_status_ot_minimum, customers_status_public,
                                                        customers_status_show_price, customers_status_show_price_tax,
                                                        customers_status_qty_discounts, customers_status_payment
                                                FROM " . $oostable['customers_status'] . "
                                                WHERE customers_status_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($customers_status = $customers_status_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['customers_status'] . "
                      (customers_status_id,
                       customers_status_languages_id,
                       customers_status_name,
                       customers_status_ot_discount_flag,
                       customers_status_ot_discount,
                       customers_status_ot_minimum,
                       customers_status_public,
                       customers_status_show_price,
                       customers_status_show_price_tax,
                       customers_status_qty_discounts,
                       customers_status_payment) 
                       VALUES ('" . $customers_status['customers_status_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($customers_status['customers_status_name']) . "',
                               '" . oos_db_input($customers_status['customers_status_ot_discount_flag']) . "',
                               '" . oos_db_input($customers_status['customers_status_ot_discount']) . "',
                               '" . oos_db_input($customers_status['customers_status_ot_minimum']) . "',
                               '" . oos_db_input($customers_status['customers_status_public']) . "',
                               '" . oos_db_input($customers_status['customers_status_show_price']) . "',
                               '" . oos_db_input($customers_status['customers_status_show_price_tax']) . "',
                               '" . oos_db_input($customers_status['customers_status_qty_discounts']) . "',
                               '" . oos_db_input($customers_status['customers_status_payment']) . "')"
            );

            // Move that ADOdb pointer!
            $customers_status_result->MoveNext();
        }

        //information_description
        $information_result = $dbconn->Execute(
            "SELECT i.information_id, id.information_name, id.information_description
                                            FROM " . $oostable['information'] . " i LEFT JOIN
                                                 " . $oostable['information_description'] . " id
                                               on i.information_id = id.information_id
                                            WHERE id.information_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($information = $information_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['information_description'] . "
                      (information_id,
                       information_languages_id,
                       information_name,
                       information_description) 
                       VALUES ('" . $information['information_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($information['information_name']) . "',
                               '" . oos_db_input($information['information_description']) . "')"
            );

            // Move that ADOdb pointer!
            $information_result->MoveNext();
        }


        // manufacturers_info
        $manufacturers_result = $dbconn->Execute(
            "SELECT m.manufacturers_id, mi.manufacturers_url
                                             FROM " . $oostable['manufacturers'] . " m LEFT JOIN
                                                  " . $oostable['manufacturers_info'] . " mi
                                                 ON m.manufacturers_id = mi.manufacturers_id
                                             WHERE mi.manufacturers_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($manufacturers = $manufacturers_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['manufacturers_info'] . "
                      (manufacturers_id, 
                       manufacturers_languages_id,
                       manufacturers_url) 
                       VALUES ('" . $manufacturers['manufacturers_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($manufacturers['manufacturers_url']) . "')"
            );

            // Move that ADOdb pointer!
            $manufacturers_result->MoveNext();
        }

        // orders_status
        $orders_status_result = $dbconn->Execute(
            "SELECT orders_status_id, orders_status_name
                                              FROM " . $oostable['orders_status'] . "
                                              WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($orders_status = $orders_status_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['orders_status'] . "
                      (orders_status_id,
                       orders_languages_id,
                       orders_status_name)
                       VALUES ('" . $orders_status['orders_status_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($orders_status['orders_status_name']) . "')"
            );

            // Move that ADOdb pointer!
            $orders_status_result->MoveNext();
        }

        //page_type
        $page_type_result = $dbconn->Execute(
            "SELECT page_type_id, page_type_name
                                          FROM " . $oostable['page_type'] . "
                                          WHERE page_type_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($page_type = $page_type_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['page_type'] . "
                      (page_type_id,
                       page_type_languages_id,
                       page_type_name)
                       VALUES ('" . $page_type['page_type_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($page_type['page_type_name']) . "')"
            );

            // Move that ADOdb pointer!
            $page_type_result->MoveNext();
        }

        //products_description
        $products_result = $dbconn->Execute(
            "SELECT p.products_id, pd.products_name, pd.products_title, pd.products_description, pd.products_short_description, pd.products_essential_characteristics, 
													pd.products_old_electrical_equipment_description, pd.products_used_goods_description, pd.products_url, pd.products_description_meta, pd.products_keywords
                                         FROM " . $oostable['products'] . " p LEFT JOIN
                                              " . $oostable['products_description'] . " pd
                                            ON p.products_id = pd.products_id
                                        WHERE pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products = $products_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_description'] . "
                      (products_id,
                       products_languages_id,
                       products_name,
					   products_title,
                       products_description,
					   products_short_description,
					   products_essential_characteristics,
					   products_old_electrical_equipment_description, 
					   products_used_goods_description,
                       products_url,
					   products_description_meta,
					   products_keywords) 
                       VALUES ('" . $products['products_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($products['products_name']) . "',
							   '" . oos_db_input($products['products_title']) . "',
                               '" . oos_db_input($products['products_description']) . "',							   
							   '" . oos_db_input($products['products_short_description']) . "',
							   '" . oos_db_input($products['products_essential_characteristics']) . "',
							   '" . oos_db_input($products['products_old_electrical_equipment_description']) . "',
							   '" . oos_db_input($products['products_used_goods_description']) . "',							   
							   '" . oos_db_input($products['products_url']) . "',
							   '" . oos_db_input($products['products_description_meta']) . "',
                               '" . oos_db_input($products['products_keywords']) . "')"
            );

            // Move that ADOdb pointer!
            $products_result->MoveNext();
        }

        //products_models_description
        $models_result = $dbconn->Execute(
            "SELECT m.models_id, md.models_name, md.models_title, md.models_description_meta, md.models_keywords
                                         FROM " . $oostable['products_models'] . " m LEFT JOIN
                                              " . $oostable['products_models_description'] . " md
                                            ON m.models_id = md.models_id
                                        WHERE md.models_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($models = $models_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_models_description'] . "
                      (models_id,
                       models_languages_id,
                       models_name,
					   models_title,
					   models_description_meta,
					   models_keywords) 
                       VALUES ('" . $models['models_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($models['models_name']) . "',
							   '" . oos_db_input($models['models_title']) . "',							   
							   '" . oos_db_input($models['models_description_meta']) . "',
                               '" . oos_db_input($models['models_keywords']) . "')"
            );

            // Move that ADOdb pointer!
            $models_result->MoveNext();
        }


        //products_model_viewer_description
        $products_model_viewer_result = $dbconn->Execute(
            "SELECT m.model_viewer_id, md.model_viewer_title, md.model_viewer_description, md.model_viewer_keywords
                                         FROM " . $oostable['products_model_viewer'] . " m LEFT JOIN
                                              " . $oostable['products_model_viewer_description'] . " md
                                            ON m.model_viewer_id = md.model_viewer_id
                                        WHERE md.model_viewer_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($model_viewer = $products_model_viewer_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_model_viewer_description'] . "
                      (model_viewer_id,
                       model_viewer_languages_id,
                       model_viewer_title,
					   model_viewer_description,
					   model_viewer_keywords) 
                       VALUES ('" . $model_viewer['model_viewer_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($model_viewer['model_viewer_title']) . "',
							   '" . oos_db_input($model_viewer['model_viewer_description']) . "',							   
                               '" . oos_db_input($model_viewer['model_viewer_keywords']) . "')"
            );

            // Move that ADOdb pointer!
            $products_model_viewer_result->MoveNext();
        }


        // products_options
        $products_options_result = $dbconn->Execute(
            "SELECT products_options_id, products_options_name 
                                                 FROM " . $oostable['products_options'] . "
                                                WHERE products_options_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products_options = $products_options_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_options'] . "
                      (products_options_id,
                       products_options_languages_id,
                       products_options_name)
                       VALUES ('" . $products_options['products_options_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($products_options['products_options_name']) . "')"
            );

            // Move that ADOdb pointer!
            $products_options_result->MoveNext();
        }
        //products_options_values
        $products_options_values_result = $dbconn->Execute(
            "SELECT products_options_values_id, products_options_values_name 
                                                       FROM " . $oostable['products_options_values'] . "
                                                       WHERE products_options_values_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products_options_values = $products_options_values_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_options_values'] . "
                      (products_options_values_id,
                       products_options_values_languages_id,
                       products_options_values_name)
                       VALUES ('" . $products_options_values['products_options_values_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($products_options_values['products_options_values_name']) . "')"
            );

            // Move that ADOdb pointer!
            $products_options_values_result->MoveNext();
        }

        //products_options_values
        $products_options_values_result = $dbconn->Execute(
            "SELECT products_options_types_id, products_options_types_name
                                                       FROM " . $oostable['products_options_types'] . "
                                                       WHERE products_options_types_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products_options_values = $products_options_values_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " .$oostable['products_options_types'] . "
                      (products_options_types_id,
                       products_options_types_languages_id,
                       products_options_types_name)
                       VALUES ('" . $products_options_values['products_options_types_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($products_options_values['products_options_types_name']) . "')"
            );

            // Move that ADOdb pointer!
            $products_options_values_result->MoveNext();
        }

        // products_status
        $products_status_result = $dbconn->Execute(
            "SELECT products_status_id, products_status_name
                                                FROM " . $oostable['products_status'] . "
                                                WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products_status = $products_status_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_status'] . "
                      (products_status_id,
                       products_status_languages_id,
                       products_status_name)
                       VALUES ('" . $products_status['products_status_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($products_status['products_status_name']) . "')"
            );

            // Move that ADOdb pointer!
            $products_status_result->MoveNext();
        }

        // products_units
        $products_units_result = $dbconn->Execute(
            "SELECT products_units_id, products_unit_name, unit_of_measure
													FROM " . $oostable['products_units'] . "
													WHERE languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($products_units = $products_units_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_units'] . "
                      (products_units_id,
                       languages_id,
                       products_unit_name,
					   unit_of_measure)
                       VALUES ('" . $products_units['products_units_id'] . "',
                               '" . intval($insert_id) . "',
							   '" . oos_db_input($products_units['products_unit_name']) . "',
                               '" . oos_db_input($products_units['unit_of_measure']) . "')"
            );

            // Move that ADOdb pointer!
            $products_units_result->MoveNext();
        }

        //products_video_description
        $products_video_result = $dbconn->Execute(
            "SELECT v.video_id, vd.video_title, vd.video_description
                                         FROM " . $oostable['products_video'] . " v LEFT JOIN
                                              " . $oostable['products_video_description'] . " vd
                                            ON v.video_id = vd.video_id
                                        WHERE vd.video_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($video = $products_video_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['products_video_description'] . "
                      (video_id,
                       video_languages_id,
                       video_title,
					   video_description,
					   video_viewed) 
                       VALUES ('" . $video['video_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($video['video_title']) . "',
							   '" . oos_db_input($video['video_description']) . "',							   
                               '0')"
            );

            // Move that ADOdb pointer!
            $products_video_result->MoveNext();
        }





        // setting
        $setting_result = $dbconn->Execute(
            "SELECT setting_id, setting_name
                                              FROM " . $oostable['setting'] . "
                                              WHERE orders_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        while ($setting = $setting_result->fields) {
            $dbconn->Execute(
                "INSERT INTO " . $oostable['setting'] . "
                      (setting_id,
                       setting_languages_id,
                       setting_name)
                       VALUES ('" . $setting['setting_id'] . "',
                               '" . intval($insert_id) . "',
                               '" . oos_db_input($setting['setting_name']) . "')"
            );

            // Move that ADOdb pointer!
            $setting_result->MoveNext();
        }


        oos_redirect_admin(oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $insert_id));
        break;

    case 'save':
        $name = oos_db_prepare_input($_POST['name']);
        $iso_639_2 = oos_db_prepare_input($_POST['iso_639_2']);
        $iso_639_1 = oos_db_prepare_input($_POST['iso_639_1']);
        $iso_3166_1 = oos_db_prepare_input($_POST['iso_3166_1']);
        $sort_order = intval(oos_db_prepare_input($_POST['sort_order']));

        $lID = oos_db_prepare_input($_GET['lID']);

        $dbconn->Execute(
            "UPDATE " . $oostable['languages'] . "
                      SET name = '" . oos_db_input($name) . "', 
                      iso_639_2 = '" . oos_db_input($iso_639_2) . "',
                      iso_639_1 = '" . oos_db_input($iso_639_1) . "',
					  iso_3166_1 = '" . oos_db_input($iso_3166_1) . "',
                      sort_order = '" . oos_db_input($sort_order) . "'
                      WHERE languages_id = '" . intval($lID) . "'"
        );

        if (isset($_POST['default']) && ($_POST['default'] == 'on')) {
            $dbconn->Execute(
                "UPDATE " . $oostable['configuration'] . " 
                        SET configuration_value = '" . oos_db_input($iso_639_2) . "'
                        WHERE configuration_key = 'DEFAULT_LANGUAGE'"
            );

            $dbconn->Execute(
                "UPDATE " . $oostable['configuration'] . " 
                        SET configuration_value = '" . intval($lID2) . "'
                        WHERE configuration_key = 'DEFAULT_LANGUAGE_ID'"
            );
        }
        oos_redirect_admin(oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $_GET['lID']));
        break;

    case 'deleteconfirm':
        $lID = oos_db_prepare_input($_GET['lID']);

        $lng_result = $dbconn->Execute("SELECT iso_639_2 FROM " . $oostable['languages'] . " WHERE languages_id = '" . intval($lID) . "'");
        $lng = $lng_result->fields;

        $remove_language = true;
        if ($lng['iso_639_2'] == DEFAULT_LANGUAGE) {
            $remove_language = false;
            $messageStack->add_session(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
            oos_redirect_admin(oos_href_link_admin($aContents['languages'], 'page=' . $nPage));
        }

        $dbconn->Execute("DELETE FROM " . $oostable['languages'] . " WHERE languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['block_info'] . " WHERE block_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['categories_description'] . " WHERE categories_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['categories_images_description'] . " WHERE categories_images_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_description'] . " WHERE panorama_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['categories_panorama_scene_hotspot_text'] . " WHERE hotspot_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['coupons_description']  . " WHERE coupon_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['customers_status']  . " WHERE customers_status_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['information_description']  . " WHERE information_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['manufacturers_info'] . " WHERE manufacturers_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['orders_status'] . " WHERE orders_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['page_type'] . " WHERE page_type_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_description'] . " WHERE products_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_model_viewer_description'] . " WHERE model_viewer_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_models_description'] . " WHERE models_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_options'] . " WHERE products_options_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_options_types'] . " WHERE products_options_types_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_options_values'] . " WHERE products_options_values_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_status'] . " WHERE products_status_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_units'] . " WHERE languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['products_video_description'] . " WHERE video_languages_id = '" . intval($lID) . "'");
        $dbconn->Execute("DELETE FROM " . $oostable['setting'] . " WHERE setting_languages_id = '" . intval($lID) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['languages'], 'page=' . $nPage));
        break;

    case 'delete':
        $lID = oos_db_prepare_input($_GET['lID']);

        $lng_result = $dbconn->Execute("SELECT iso_639_2 FROM " . $oostable['languages'] . " WHERE languages_id = '" . oos_db_input($lID) . "'");
        $lng = $lng_result->fields;

        $remove_language = true;
        if ($lng['iso_639_2'] == DEFAULT_LANGUAGE) {
            $remove_language = false;
            $messageStack->add(ERROR_REMOVE_DEFAULT_LANGUAGE, 'error');
        }
        break;
    }
}
  $lang_select_array = array(array('id' => '0', 'text' => TEXT_ALL_LANGUAGES),
                             array('id' => '1', 'text' => TEXT_ACTIVE_LANGUAGES));
  require 'includes/header.php';
    ?>
<div class="wrapper">
    <!-- Header //-->
    <header class="topnavbar-wrapper">
        <!-- Top Navbar //-->
        <?php require 'includes/menue.php'; ?>
    </header>
    <!-- END Header //-->
    <aside class="aside">
        <!-- Sidebar //-->
        <div class="aside-inner">
            <?php require 'includes/blocks.php'; ?>
        </div>
        <!-- END Sidebar (left) //-->
    </aside>
    
    <!-- Main section //-->
    <section>
        <!-- Page content //-->
        <div class="content-wrapper">
                            
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['currencies'], 'selected_box=localization') . '">' . BOX_HEADING_LOCALIZATION . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->
            
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">                
<!-- body_text //-->

<div class="table-responsive">
    <table class="table w-100">
          <tr>
            <td valign="top">
            
                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_LANGUAGE_NAME; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_LANGUAGE_ISO_639_2; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_LANGUAGE_ISO_639_1; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_LANGUAGE_ISO_3166_1; ?></th>
                            <th class="text-center"><?php echo TABLE_HEADING_LANGUAGE_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
  $languages_result_raw = "SELECT languages_id, name, iso_639_2, iso_639_1, iso_3166_1, status, sort_order 
                          FROM " . $oostable['languages'] . "
                          ORDER BY sort_order";
  $languages_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $languages_result_raw, $languages_result_numrows);
  $languages_result = $dbconn->Execute($languages_result_raw);

while ($languages = $languages_result->fields) {
    if ((!isset($_GET['lID']) || (isset($_GET['lID']) && ($_GET['lID'] == $languages['languages_id']))) && !isset($lInfo) && (substr($action, 0, 3) != 'new')) {
        $lInfo = new objectInfo($languages);
    }

    if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id)) {
        echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $languages['languages_id']) . '\'">' . "\n";
    }

    if (DEFAULT_LANGUAGE == $languages['iso_639_2']) {
        echo '                <td><b>' . $languages['name'] . ' (' . TEXT_DEFAULT . ')</b></td>' . "\n";
    } else {
        echo '                <td>' . $languages['name'] . '</td>' . "\n";
    } ?>
                <td class="text-center"><?php echo $languages['iso_639_2']; ?></td>
                <td class="text-center"><?php echo $languages['iso_639_1']; ?></td>
                <td class="text-center"><?php echo $languages['iso_3166_1']; ?></td>
                <td class="text-center">
    <?php
    if ($languages['status'] == '1') {
        echo '<a href="' . oos_href_link_admin($aContents['languages'], 'action=setflag&flag=0&lID=' . $languages['languages_id'] . '&page=' . $nPage) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    } else {
        echo '<a href="' . oos_href_link_admin($aContents['languages'], 'action=setflag&flag=1&lID=' . $languages['languages_id'] . '&page=' . $nPage) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } ?></td>

                <td class="text-right"><?php if (isset($lInfo) && is_object($lInfo) && ($languages['languages_id'] == $lInfo->languages_id)) {
        echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
    } else {
        echo '<a href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $languages['languages_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
    } ?>&nbsp;</td>
              </tr>
    <?php
    // Move that ADOdb pointer!
    $languages_result->MoveNext();
}
?>
              <tr>
                <td colspan="6"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $languages_split->display_count($languages_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_LANGUAGES); ?></td>
                    <td class="smallText" align="right"><?php echo $languages_split->display_links($languages_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
if (empty($action)) {
    ?>
                  <tr>
                    <td align="right" colspan="2"><?php echo '<a href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=new') . '">' . oos_button(IMAGE_NEW_LANGUAGE) . '</a>'; ?></td>
                  </tr>
    <?php
}
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = [];
  $contents = [];

  switch ($action) {
case 'new':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_LANGUAGE . '</b>');

    $contents = array('form' => oos_draw_form('id', 'languages', $aContents['languages'], 'action=insert', 'post', false));
    $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . oos_draw_input_field('name'));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_639_2 . '<br>' . oos_draw_input_field('iso_639_2'));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_639_1 . '<br>' . oos_draw_input_field('iso_639_1'));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_3166_1 . '<br>' . oos_draw_input_field('iso_3166_1'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $_GET['lID']) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

    break;

case 'edit':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_LANGUAGE . '</b>');

    $contents = array('form' => oos_draw_form('id', 'languages', $aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=save', 'post', false));
    $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . '<br>' . oos_draw_input_field('name', $lInfo->name));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_639_2 . '<br>' . oos_draw_input_field('iso_639_2', $lInfo->iso_639_2));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_639_1 . '<br>' . oos_draw_input_field('iso_639_1', $lInfo->iso_639_1));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_3166_1 . '<br>' . oos_draw_input_field('iso_3166_1', $lInfo->iso_3166_1));
    $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . '<br>' . oos_draw_input_field('sort_order', $lInfo->sort_order));
    if (DEFAULT_LANGUAGE != $lInfo->iso_639_2 && $lInfo->status == '1') {
        $contents[] = array('text' => '<br>' . oos_draw_checkbox_field('default') . ' ' . TEXT_SET_DEFAULT);
    }
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

    break;

case 'delete':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_LANGUAGE . '</b>');

    $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
    $contents[] = array('text' => '<br><b>' . $lInfo->name . '</b>');
    $contents[] = array('align' => 'center', 'text' => '<br>' . (($remove_language) ? '<a href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=deleteconfirm') . '">' . oos_button(BUTTON_DELETE) . '</a>' : '') . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

    break;

default:
    if (isset($lInfo) && is_object($lInfo)) {
        $heading[] = array('text' => '<b>' . $lInfo->name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['languages'], 'page=' . $nPage . '&lID=' . $lInfo->languages_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_NAME . ' ' . $lInfo->name);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_ISO_639_2 . ' ' . $lInfo->iso_639_2);
        $contents[] = array('text' => TEXT_INFO_LANGUAGE_ISO_639_1 . ' ' . $lInfo->iso_639_1);
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_ISO_3166_1 . ' ' . $lInfo->iso_3166_1);
        $contents[] = array('text' => '<div class="flag flag-icon flag-icon-' . $lInfo->iso_3166_1 . ' width-full"></div>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_DIRECTORY . '<br>' . OOS_SHOP . 'includes/languages/<b>' . $lInfo->iso_639_2 . '</b>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_LANGUAGE_SORT_ORDER . ' ' . $lInfo->sort_order);
    }
    break;
  }

  if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
      ?>
    <td class="w-25" valign="top">
        <table class="table table-striped">
      <?php
        $box = new box();
      echo $box->infoBox($heading, $contents); ?>
        </table> 
    </td> 
      <?php
  }
    ?>
          </tr>
        </table>
    </div>
<!-- body_text_eof //-->

                </div>
            </div>
        </div>

        </div>
    </section>
    <!-- Page footer //-->
    <footer>
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>


<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>