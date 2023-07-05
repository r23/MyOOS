<?php
/**
   ----------------------------------------------------------------------
   $Id: administrators.php,v 1.3 2007/06/13 16:38:21 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   Login and Logoff for osCommerce Administrators.

   Original Version by Blake Schwendiman
   blake@intechra.net

   Updated Version 1.1.0 (03/01/2002) by Christopher Conkie
   chris@conkiec.freeserve.co.uk

   updated version 1.2.0 (06/27/2002) by Steve Myers
   info@megashare.net

   updated version 1.3.0 (03/06/2003) by Steve Myers
   chinaz@cga.net.cn
   ----------------------------------------------------------------------
   The Exchange Project - Community Made Shopping!
   http://www.theexchangeproject.org

   Copyright (c) 2000,2001 The Exchange Project

   Implemented by Blake Schwendiman (blake@intechra.net)
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('TOP_BAR_TITLE', 'Administrators');
define('HEADING_TITLE', 'Set up Administrators');
define('SUB_BAR_TITLE', 'Add, edit or remove login information for Administrators.');

define('TEXT_ADMINISTRATOR_USERNAME', 'UserName:');
define('TEXT_ADMINISTRATOR_PASSWORD', 'Password:');
define('TEXT_ADMINISTRATOR_CONFPWD', 'Confirm Password:');
define('TEXT_CURRENT_ADMINISTRATORS', 'Current Administrators');
define('TEXT_ADD_ADMINISTRATOR', 'Add New Administrator');
define('TEXT_NO_CURRENT_ADMINS', 'There are currently no defined administrators.');
define('TEXT_ADMIN_DELETE', 'Delete');
define('TEXT_ADMIN_SAVE', 'Add New');
define('TEXT_PWD_ERROR', '<br><p class="main">The password did not match the confirmation password or the password was empty.  New administrator <b>not added</b>.</p>');
define('TEXT_UNAME_ERROR', '<br><p class="main">The username cannot be empty.  New administrator <b>not added</b>.</p>');
define('TEXT_FULL_ACCESS', 'This administrator has <b>full</b> access.');
define('TEXT_PARTIAL_ACCESS', 'This administrator has access to the following areas.  CTRL+Click to select multiple.');
define('TEXT_ADMIN_HAS_ACCESS_TO', 'Administrator Rights');
