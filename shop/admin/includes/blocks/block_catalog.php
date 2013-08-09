<?php
/* ----------------------------------------------------------------------
   $Id: catalog.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   
/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_catalog', oos_href_link_admin($aFilename['categories'], 'selected_box=catalog'));  

$smarty->assign('categories', oos_admin_files_boxes('categories', BOX_CATALOG_CATEGORIES_PRODUCTS));  
$smarty->assign('new_product', '<a href="' . oos_href_link_admin($aFilename['products'], 'action=new_product', 'NONSSL') . '" title="' . BOX_CATALOG_QADD_PRODUCT . '">' . BOX_CATALOG_QADD_PRODUCT . '</a>');  
$smarty->assign('specials', oos_admin_files_boxes('specials', BOX_CATALOG_SPECIALS));  
$smarty->assign('products_expected', oos_admin_files_boxes('products_expected', BOX_CATALOG_PRODUCTS_EXPECTED));  
$smarty->assign('featured', oos_admin_files_boxes('featured', BOX_CATALOG_PRODUCTS_FEATURED));  
$smarty->assign('products_attributes', oos_admin_files_boxes('products_attributes', BOX_CATALOG_CATEGORIES_PRODUCTS_ATTRIBUTES));  
$smarty->assign('products_status', oos_admin_files_boxes('products_status', BOX_CATALOG_PRODUCTS_STATUS));  
$smarty->assign('products_units', oos_admin_files_boxes('products_units', BOX_CATALOG_PRODUCTS_UNITS));  
$smarty->assign('xsell_products', oos_admin_files_boxes('xsell_products', BOX_CATALOG_XSELL_PRODUCTS));  
$smarty->assign('up_sell_products', oos_admin_files_boxes('up_sell_products', BOX_CATALOG_UP_SELL_PRODUCTS));
$smarty->assign('export_excel', oos_admin_files_boxes('export_excel', BOX_CATALOG_EXPORT_EXCEL));  
$smarty->assign('import_excel', oos_admin_files_boxes('import_excel', BOX_CATALOG_IMPORT_EXCEL));  
$smarty->assign('manufacturers', oos_admin_files_boxes('manufacturers', BOX_CATALOG_MANUFACTURERS));  
$smarty->assign('reviews', oos_admin_files_boxes('reviews', BOX_CATALOG_REVIEWS));  
$smarty->assign('quick_stockupdate', oos_admin_files_boxes('quick_stockupdate', BOX_CATALOG_QUICK_STOCKUPDATE));  
