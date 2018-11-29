<?php
/* ----------------------------------------------------------------------
   $Id: catalog.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: catalog.php,v 1.20 2002/03/16 00:20:11 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$bActive = ($_SESSION['selected_box'] == 'catalog' )  ? TRUE : FALSE;

$aBlocks[] = array(
	'heading' => BOX_HEADING_CATALOG,
	'link' => oos_href_link_admin(basename($_SERVER['PHP_SELF']), oos_get_all_get_params(array('selected_box')) . 'selected_box=catalog'),
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
			'code' => $aContents['products_expected'],
			'title' => BOX_CATALOG_PRODUCTS_EXPECTED,
			'link' => oos_admin_files_boxes('products_expected', 'selected_box=catalog')
		),
		array(
			'code' => $aContents['featured'],
			'title' => BOX_CATALOG_PRODUCTS_FEATURED,
			'link' => oos_admin_files_boxes('featured', 'selected_box=catalog')
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
			'code' => $aContents['xsell_products'],
			'title' => BOX_CATALOG_XSELL_PRODUCTS,
			'link' =>  oos_admin_files_boxes('xsell_products', 'selected_box=catalog')
		),
		array(
			'code' => $aContents['up_sell_products'],
			'title' => BOX_CATALOG_UP_SELL_PRODUCTS,
			'link' => oos_admin_files_boxes('up_sell_products', 'selected_box=catalog')
		),
		array(
		'code' => $aContents['export_excel'],
			'title' => BOX_CATALOG_EXPORT_EXCEL,
			'link' => oos_admin_files_boxes('export_excel', 'selected_box=catalog')
		),
		array(
		'code' => $aContents['import_excel'],
			'title' => BOX_CATALOG_IMPORT_EXCEL,
			'link' => oos_admin_files_boxes('import_excel', 'selected_box=catalog')
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
				
	),
);
