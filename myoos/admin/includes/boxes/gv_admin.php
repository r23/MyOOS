<?php
/**
   ----------------------------------------------------------------------
   $Id: gv_admin.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_admin.php,v 1.2.2.1 2003/04/18 21:13:51 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce

   Gift Voucher System v1.0
   Copyright (c) 2001,2002 Ian C Wilson
   http://www.phesis.org
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

$php_self = filter_var($_SERVER['PHP_SELF'], FILTER_SANITIZE_URL);
$bActive = ($_SESSION['selected_box'] == 'gv_admin') ? true : false;

$aBlocks[] = array(
    'heading' => BOX_HEADING_GV_ADMIN,
    'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(array('selected_box')) . 'selected_box=gv_admin'),
    'icon' => 'fa fa-certificate',
    'active' => $bActive,
    'contents' => array(
        array(
            'code' => $aContents['coupon_admin'],
            'title' => BOX_COUPON_ADMIN,
            'link' => oos_admin_files_boxes('coupon_admin', 'selected_box=gv_admin')
        ),
        array(
            'code' => $aContents['gv_queue'],
            'title' => BOX_GV_ADMIN_QUEUE,
            'link' =>  oos_admin_files_boxes('gv_queue', 'selected_box=gv_admin')
        ),
        array(
            'code' => $aContents['gv_mail'],
            'title' => BOX_GV_ADMIN_MAIL,
            'link' => oos_admin_files_boxes('gv_mail', 'selected_box=gv_admin')
        ),
        array(
            'code' => $aContents['gv_sent'],
            'title' => BOX_GV_ADMIN_SENT,
            'link' => oos_admin_files_boxes('gv_sent', 'selected_box=gv_admin')
        ),
    ),
);
