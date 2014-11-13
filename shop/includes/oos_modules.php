<?php
/* ----------------------------------------------------------------------
   $Id: oos_modules.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

   
  $aModules = array();
  $prefix_modules = '';
  if (!$prefix_modules == '') $prefix_modules =  $prefix_modules . '_';
   
  $aModules = array();
  $aModules['account'] = $prefix_modules . 'account';
  $aModules['admin'] = $prefix_modules . 'admin';
  $aModules['checkout'] = $prefix_modules . 'checkout';
  $aModules['info'] = $prefix_modules . 'info';
  $aModules['gv'] = $prefix_modules . 'gv';
  $aModules['main'] = $prefix_modules . 'main';
  $aModules['newsletters'] = $prefix_modules . 'newsletters';
  $aModules['products'] = $prefix_modules . 'products';
  $aModules['pub'] = $prefix_modules . 'pub';
  $aModules['reviews'] = $prefix_modules . 'reviews';
  $aModules['search'] = $prefix_modules . 'search';
  $aModules['user'] = $prefix_modules . 'user';

