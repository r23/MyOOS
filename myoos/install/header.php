<?php
/* ----------------------------------------------------------------------
   $Id: header.php,v 1.1 2007/06/13 16:41:18 r23 Exp $
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team
   ----------------------------------------------------------------------
   Based on:
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com
   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html <?php echo HTML_PARAMS; ?>>

<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo INSTALLATION . ' Ver. ' . OOS_VERSION; ?></title>
<meta name="ROBOTS" content="NOFOLLOW">
<meta name="resource-type" content="document">
<meta http-equiv="expires" content="0">
<meta name="author" content="osis online shop">
<meta name="generator" content="OSIS Online Shop - http://www.oos-shop.de">
<link rel="StyleSheet" href="style/style.css" type="text/css">
<link href="style/rollover.js" type="text/javascript">
<script language="JavaScript" src="style/rollover.js"></script>
</head>

<body onload="MM_preloadImages('images/home_up.png','images/service_up.png','images/news_up.png','images/download_up.png','images/top_up.png','images/back_up.png')">
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="135"><img src="images/hippoos_top.png" alt="" width="135" height="86" /></td>
    <td>&nbsp;</td>
    <td valign="middle"><h1><img src="images/arrow_green.png" alt="" width="11" height="11" align="middle" /> MyOOS [Shopsystem]</h1></td>
    <td width="274" colspan="2" align="right" valign="middle"><a href="http://www.php.net/" target="_blank"><img src="images/powered_php.png" alt="" width="80" height="15" border="0" /></a><br />
      <br />
      <a href="http://www.mysql.org/" target="_blank"><img src="images/powered_mysql.png" alt="" width="93" height="15" border="0" /></a></td>
  </tr>
  <tr>
    <td width="135"><img src="images/hippoos_buttom.png" alt="" width="135" height="52" /></td>
    <td width="33" class="table_head_title"></td>
    <td width="358" class="table_head_title">Version: MyOOS <?php echo OOS_VERSION; ?></td>
    <td width="274" colspan="2" valign="top" class="table_head_title"><table width="274"  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td colspan="4"><img src="images/trans.gif" alt="" width="1" height="2" /></td>
        </tr>
      <tr>
        <td><a href="index.htm" target="_self" onmouseover="MM_swapImage('home','','images/home_up.png',1)" onmouseout="MM_swapImgRestore()"><img src="images/home.png" alt="www.oos-shop.de [Home]" name="home" width="70" height="41" border="0" id="home" /></a></td>
        <td><a href="http://foren.myoos.de/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('service','','images/service_up.png',1)"><img src="images/service.png" alt="www.oos-shop.de [Service]" name="service" width="68" height="41" border="0" id="service" /></a></td>
        <td><a href="http://blog.myoos.de/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('news','','images/news_up.png',1)"><img src="images/news.png" alt="www.oos-shop.de [News]" name="news" width="68" height="41" border="0" id="news" /></a></td>
        <td><a href="https://github.com/r23/MyOOS/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('download','','images/download_up.png',1)"><img src="images/download.png" alt="www.oos-shop.de [Download]" name="Download" width="69" height="41" border="0" id="download" /></a></td>
      </tr>
    </table>  </td>
  </tr>
  <tr>
    <td colspan="3" class="table_head_green"> &nbsp;<?php echo strftime(DATE_FORMAT_LONG); ?></td>
    <td align="right" class="table_head_green"><b>MyOOS</b></td>
    <td align="right" class="table_head_green"><img src="images/trans.gif" alt="" width="1" height="26" /></td>
  </tr>
  <tr>
    <td colspan="5" class="table_head_schadow"><img src="images/table_head_schadow.png" alt="" width="12" height="12" /></td>
  </tr>
</table>
<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>
