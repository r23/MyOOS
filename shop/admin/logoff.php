<?php
/* ----------------------------------------------------------------------
   $Id: logoff.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: logoff.php,v 1.12 2003/02/13 03:01:51 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/oos_main.php';

unset($_SESSION['login_id']);
unset($_SESSION['login_firstname']);
unset($_SESSION['login_groups_id']);
  
$aTemplate['page'] = 'default/page/logoff.tpl';

require_once 'includes/oos_system.php';

$smarty->assign('body', 'login-page');
$smarty->assign('form_action', oos_draw_form('login', $aFilename['login'], ''));
$smarty->assign('login_link', oos_href_link_admin($aFilename['login']));


// display the template
$smarty->display($aTemplate['page']);

require 'includes/oos_nice_exit.php';   
