<?php
/* ----------------------------------------------------------------------
   $Id: popup_image_news.php,v 1.1 2007/06/08 17:14:41 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   popup_image.php,v 1.13 2002/08/24 11:08:39 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   Big Image Modification 2002/03/04
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $_GET['title']; ?> - Administration [OOS]</title>
<base href="<?php echo (getenv('HTTPS') == 'on' ? OOS_HTTPS_SERVER : OOS_HTTP_SERVER) . OOS_SHOP; ?>">
<script language="javascript"><!--
var i=0;
function resize() {
  if (navigator.appName == 'Netscape') i=40;
  if (document.images[0]) window.resizeTo(document.images[0].width +30, document.images[0].height+60-i);
  self.focus();
}
//--></script>
</head>
<body onload="resize();">
<?php
  if (file_exists(OOS_ABSOLUTE_PATH . OOS_IMAGES . OOS_POPUP_IMAGES . $_GET['n_image'])) {
    $image = OOS_SHOP_IMAGES . OOS_POPUP_IMAGES . $_GET['n_image'];
  } else {
    $image = OOS_SHOP_IMAGES . $_GET['n_image'];
  }
    echo oos_image($image, $_GET['title']);
?>
</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>