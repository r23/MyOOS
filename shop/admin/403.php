<?php
/* ----------------------------------------------------------------------
   $Id: forbiden.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: admin_account.php,v 1.29 2002/03/17 17:52:23 harley_vb 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/oos_main.php';

$aTemplate['page'] = 'default/page/403.tpl';

require_once '/includes/classes/class_template.php';
$smarty = new myOOS_Smarty;

if (empty($oos_pagetitle)) $oos_pagetitle = $aLang['heading_title'] . ' &lsaquo; ' . STORE_NAME . ' &#8212; MyOOS';

$smarty->assign(
      array(
          'formid'            => $sFormid,

          'lang'              => $aLang,
          'language'          => $sLanguage,

          'pagetitle'         => $oos_pagetitle,
      )
);
$smarty->assign('body', 'error-page');
$smarty->assign('default', oos_href_link_admin($aFilename['default']));
$smarty->assign('catalog_link', oos_catalog_link($aCatalogFilename['default']));

header("HTTP/1.0 403 Forbidden");
// display the template
$smarty->display($aTemplate['page']);

require 'includes/oos_nice_exit.php';
