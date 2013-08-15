<?php
/* ----------------------------------------------------------------------
   $Id: $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


require_once '/includes/classes/class_template.php';
$smarty = new myOOS_Smarty;

//debug
if ($debug == 'true')
{
	$smarty->force_compile   = true;
	$smarty->debugging       = true;
	$smarty->clearAllCache();
	$smarty->clearCompiledTemplate();
}


if (empty($oos_pagetitle)) $oos_pagetitle = $aLang['heading_title'] . ' &lsaquo; ' . STORE_NAME . ' &#8212; MyOOS';

$sFormid = md5(uniqid(rand(), true));
$_SESSION['formid'] = $sFormid;

require_once '/includes/info_message.php';

$smarty->assign(
      array(
          'formid'            => $sFormid,

          'lang'              => $aLang,
          'language'          => $sLanguage,

          'oos_session_name'  => oos_session_name(),
          'oos_session_id'    => oos_session_id(),

          'pagetitle'         => $oos_pagetitle,
      )
);

$smarty->assign('home', oos_href_link_admin($aFilename['default']));
$smarty->assign('catalog_link', oos_catalog_link($aCatalogFilename['default']));
$smarty->assign('support_site', 'http://www.oos-shop.de/'); 
$smarty->assign('admin_account', oos_href_link_admin($aFilename['admin_account']));
$smarty->assign('logoff', oos_href_link_admin($aFilename['logoff']));
