<?php
/* ----------------------------------------------------------------------
   $Id: header.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: header.php,v 1.19 2002/04/13 16:11:52 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
	<title><?php echo TITLE; ?></title>
	<meta http-equiv="expires" content="0" >

	<!-- Bootstrap style  --> 
	<link href="themes/phoenix/css/bootstrap.min.css" rel="stylesheet" />
	<link href="themes/phoenix/css/font-awesome.min.css" rel="stylesheet" />
	<link href="themes/phoenix/css/style.min.css" rel="stylesheet" />

  <script type="text/javascript" src="includes/imgswap.js"></script>
<?php
  if ($no_js_general == 'true') {
?>
   <link rel="StyleSheet" href="includes/stylesheet.css" type="text/css" >
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<?php
  } else {
?>
    <script type="text/javascript" src="includes/general.js"></script>
    <link rel="StyleSheet" href="includes/stylesheet.css" type="text/css" >
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<?php
  }
?>


<?php
  if ($messageStack->size > 0) {
    echo $messageStack->output();
  }
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td></td>
    <td align="right"><?php echo '<a href="http://www.oos-shop.de/" target="_blank">' . oos_image(OOS_IMAGES . 'support.png', HEADER_TITLE_SUPPORT_SITE, '50', '50') . '</a>&nbsp;&nbsp;<a href="' . oos_catalog_link($oosCatalogFilename['default']) . '">' . oos_image(OOS_IMAGES . 'checkout.png', HEADER_TITLE_ONLINE_CATALOG, '50', '50') . '</a>&nbsp;&nbsp;<a href="' . oos_href_link_admin($aContents['default'], '', 'NONSSL') . '">' . oos_image(OOS_IMAGES . 'administration.png', HEADER_TITLE_ADMINISTRATION, '50', '50') . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
  <tr class="headerBar">
    <td class="headerBarContent">&nbsp;&nbsp;<?php if (isset($_SESSION['login_id'])) { echo '<a href="' . oos_href_link_admin($aContents['admin_account'], '', 'SSL') . '" class="headerLink">' . HEADER_TITLE_ACCOUNT . '</a> | <a href="' . oos_href_link_admin($aContents['logoff'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_LOGOFF . '</a>'; } else { echo '<a href="' . oos_href_link_admin($aContents['default'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_TOP . '</a>'; }?></td>
    <td class="headerBarContent" align="right"><?php echo '<a href="http://www.oos-shop.de/" class="headerLink">' . HEADER_TITLE_SUPPORT_SITE . '</a> &nbsp;|&nbsp; <a href="' . oos_catalog_link($oosCatalogFilename['default']) . '" class="headerLink">' . HEADER_TITLE_ONLINE_CATALOG . '</a>&nbsp;|&nbsp; <a href="' . oos_href_link_admin($aContents['default'], '', 'NONSSL') . '" class="headerLink">' . HEADER_TITLE_ADMINISTRATION . '</a>'; ?>&nbsp;&nbsp;</td>
  </tr>
</table>



