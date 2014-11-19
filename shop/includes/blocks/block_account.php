<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce

   IMPORTANT NOTE:

   This script is not part of the official osC distribution
   but an add-on contributed to the osC community. Please
   read the README and  INSTALL documents that are provided 
   with this file for further information and installation notes.

   loginbox.php -   Version 1.0
   This puts a login request in a box with a login button.
   If already logged in, will not show anything.

   Modified to utilize SSL to bypass Security Alert
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  $smarty->assign('block_heading_login', $block_heading);


