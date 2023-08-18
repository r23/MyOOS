<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reviews.php,v 1.36 2003/02/12 20:27:32 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

if (!$oEvent->installed_plugin('reviews')) {
    return false;
}

if ($sContent != $aContents['product_reviews_write']) {
    $reviewstable  = $oostable['reviews'];
    $productstable = $oostable['products'];
    $reviews_descriptiontable  = $oostable['reviews_description'];
    $products_descriptiontable = $oostable['products_description'];
    $random_select = "SELECT r.reviews_id, r.reviews_rating,
                             substring(rd.reviews_text, 1, 60) AS reviews_text,
                             p.products_id, p.products_image, pd.products_name
                      FROM $reviewstable r,
                           $reviews_descriptiontable rd,
                           $productstable p, 
                           $products_descriptiontable pd
                      WHERE p.products_setting = '2'
                        AND p.products_id = r.products_id
                        AND r.reviews_id = rd.reviews_id
                        AND rd.reviews_languages_id = '" . intval($nLanguageID) . "'
                        AND p.products_id = pd.products_id
                        AND pd.products_languages_id = '" . intval($nLanguageID) . "'";
    if (isset($_GET['products_id'])) {
        if (!isset($nProductsID)) {
			$sProductsId = filter_string_polyfill(filter_input(INPUT_GET, 'products_id'));
			$nProductsID = oos_get_product_id($sProductsId);
        }
        $random_select .= " AND p.products_id = '" . intval($nProductsID) . "'";
    }
    $random_select .= " ORDER BY r.reviews_id DESC";
    $random_product = oos_random_select($random_select, MAX_RANDOM_SELECT_REVIEWS);

    $smarty->assign(
        ['block_heading_reviews' => $block_heading, 'random_product' => $random_product]
    );
}
