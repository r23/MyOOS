<?php
/**
   ----------------------------------------------------------------------
   $Id: modules.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: modules.php,v 1.15 2002/04/03 23:25:41 hpdl
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
$bActive = ($_SESSION['selected_box'] == 'modules') ? true : false;

$aBlocks[] = array(
    'heading' => BOX_HEADING_MODULES,
    'link' => oos_href_link_admin(basename($php_self), oos_get_all_get_params(array('selected_box')) . 'selected_box=modules'),
    'icon' => 'fa fa-edit',
    'active' => $bActive,
    'contents' => array(
        array(
            'code' => 'payment',
            'title' => BOX_MODULES_PAYMENT,
            'link' =>  oos_href_link_admin($aContents['modules'], 'selected_box=modules&amp;set=payment')
        ),
        array(
            'code' => 'shipping',
            'title' => BOX_MODULES_SHIPPING,
            'link' => oos_href_link_admin($aContents['modules'], 'selected_box=modules&amp;set=shipping')
        ),
        array(
            'code' => 'ordertotal',
            'title' => BOX_MODULES_ORDER_TOTAL,
            'link' => oos_href_link_admin($aContents['modules'], 'selected_box=modules&amp;set=ordertotal')
        ),
    ),
);
