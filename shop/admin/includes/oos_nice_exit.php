<?php
/* ----------------------------------------------------------------------
   $Id: oos_nice_exit.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 200 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_bottom.php,v 1.8 2002/03/15 02:40:38 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/*
  echo '<pre>';
  print_r($_SESSION);
  echo '<br />';
  print_r($_GET);
  echo '<br />';
  print_r($_POST);
  echo '</pre>';
*/

// close session (store variables)
oos_session_close();

