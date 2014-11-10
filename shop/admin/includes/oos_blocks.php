<?php
/* ----------------------------------------------------------------------
   $Id: oos_blocks.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: column_left.php,v 1.15 2002/01/11 05:03:25 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------  */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );


  if (MENU_DHTML == true) return false;

  if (oos_admin_check_boxes('administrator.php') == true) {
    include 'includes/boxes/administrator.php';
  }
  if (oos_admin_check_boxes('configuration.php') == true) {
    include 'includes/boxes/configuration.php';
  }
  if (oos_admin_check_boxes('catalog.php') == true) {
    include 'includes/boxes/catalog.php';
  }
  if (oos_admin_check_boxes('content.php') == true) {
    include 'includes/boxes/content.php';
  }
  if (oos_admin_check_boxes('newsfeed.php') == true) {
    include 'includes/boxes/newsfeed.php';
  }
  if (oos_admin_check_boxes('modules.php') == true) {
    include 'includes/boxes/modules.php';
  }
  if (oos_admin_check_boxes('plugins.php') == true) {
    include 'includes/boxes/plugins.php';
  }
  if (oos_admin_check_boxes('customers.php') == true) {
    include 'includes/boxes/customers.php';
  }
  if (oos_admin_check_boxes('ticket.php') == true) {
    include 'includes/boxes/ticket.php';
  }
  if (oos_admin_check_boxes('taxes.php') == true) {
    include 'includes/boxes/taxes.php';
  }
  if (oos_admin_check_boxes('localization.php') == true) {
    include 'includes/boxes/localization.php';
  }
  if (oos_admin_check_boxes('affiliate.php') == true) {
    include 'includes/boxes/affiliate.php';
  }
  if (oos_admin_check_boxes('reports.php') == true) {
    include 'includes/boxes/reports.php';
  }
  if (oos_admin_check_boxes('tools.php') == true) {
    include 'includes/boxes/tools.php';
  }
  if (oos_admin_check_boxes('links.php') == true) {
    include 'includes/boxes/links.php';
  }
  if (oos_admin_check_boxes('gv_admin.php') == true) {
    include 'includes/boxes/gv_admin.php'; 
  }
  if ( (oos_admin_check_boxes('rss_admin.php') == true)  && (DISPLAY_NEWSFEED == 'true') ) {
    include 'includes/boxes/rss_admin.php';
  }
  if (oos_admin_check_boxes('export.php') == true) {
    include 'includes/boxes/export.php';
  }
  if (oos_admin_check_boxes('information.php') == true) {
    include 'includes/boxes/information.php';
  }

?>