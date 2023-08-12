<?php
/**
   ----------------------------------------------------------------------
   $Id: catalog.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: catalog.php,v 1.20 2002/03/16 00:20:11 hpdl
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

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'catalog') ? true : false;

$aBlocks[] = array(
    'heading' => BOX_HEADING_CATALOG,
    'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(array('selected_box')) . 'selected_box=catalog'),
    'icon' => 'fa fa-desktop',
    'active' => $bActive,
    'contents' => array(
        array(
            'code' => $aContents['categories'],
            'title' => BOX_CATALOG_CATEGORIES_PRODUCTS,
            'link' => oos_admin_files_boxes('categories', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['specials'],
            'title' => BOX_CATALOG_SPECIALS,
            'link' => oos_admin_files_boxes('specials', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['featured'],
            'title' => BOX_CATALOG_PRODUCTS_FEATURED,
            'link' => oos_admin_files_boxes('featured', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['categories_slider'],
            'title' => BOX_CATALOG_SLIDER,
            'link' => oos_admin_files_boxes('categories_slider', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['products_expected'],
            'title' => BOX_CATALOG_PRODUCTS_EXPECTED,
            'link' => oos_admin_files_boxes('products_expected', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['products_attributes'],
            'title' => BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES,
            'link' => oos_admin_files_boxes('products_attributes', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['products_status'],
            'title' => BOX_CATALOG_PRODUCTS_STATUS,
            'link' => oos_admin_files_boxes('products_status', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['products_units'],
            'title' => BOX_CATALOG_PRODUCTS_UNITS,
            'link' => oos_admin_files_boxes('products_units', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['manufacturers'],
            'title' => BOX_CATALOG_MANUFACTURERS,
            'link' => oos_admin_files_boxes('manufacturers', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['reviews'],
            'title' => BOX_CATALOG_REVIEWS,
            'link' => oos_admin_files_boxes('reviews', 'selected_box=catalog')
        ),
        array(
            'code' => $aContents['wastebasket'],
            'title' => BOX_CATALOG_WASTEBASKET,
            'link' => oos_admin_files_boxes('wastebasket', 'selected_box=catalog')
        ),

    ),
);
