<?php
/* ----------------------------------------------------------------------
   $Id: content.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
   
/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

$smarty->assign('heading_content', oos_href_link_admin($aFilename['content_block'], 'selected_box=content'));  

$smarty->assign('content_block',  oos_admin_files_boxes('content_block', 'selected_box=content', BOX_CONTENT_BLOCK));
$smarty->assign('content_page_type',  oos_admin_files_boxes('content_page_type', 'selected_box=content', BOX_CONTENT_PAGE_TYPE));

