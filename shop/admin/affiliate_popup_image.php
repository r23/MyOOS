<?php
/* ----------------------------------------------------------------------
   $Id: affiliate_popup_image.php,v 1.1 2007/06/08 17:14:40 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2007 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_popup_image.php,v 1.1 2003/02/24 00:48:43 harley_vb 
   ----------------------------------------------------------------------
   OSC-Affiliate

   Contribution based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/oos_main.php';

  reset($_GET);
  while (list($key, ) = each($_GET)) {
    switch ($key) {
      case 'banner':
        $banners_id = oos_db_prepare_input($_GET['banner']);

        $banner_result = $dbconn->Execute("SELECT affiliate_banners_title, affiliate_banners_image, affiliate_banners_html_text FROM " . $oostable['affiliate_banners'] . " WHERE affiliate_banners_id = '" . oos_db_input($banners_id) . "'");
        $banner = $banner_result->fields;

        $page_title = $banner['affiliate_banners_title'];

        if ($banner['affiliate_banners_html_text']) {
          $image_source = $banner['affiliate_banners_html_text'];
        } elseif ($banner['affiliate_banners_image']) {
          $image_source = oos_image(OOS_HTTP_SERVER . OOS_IMAGES . $banner['affiliate_banners_image'], $page_title);
        }
        break;
    }
  }
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo $page_title; ?> - Administration [OOS]</title>
<script language="javascript"><!--
var i=0;

function resize() {
  if (navigator.appName == 'Netscape') i = 40;
  window.resizeTo(document.images[0].width + 30, document.images[0].height + 60 - i);
}
//--></script>
</head>

<body onload="resize();">

<?php echo $image_source; ?>

</body>
</html>
<?php require 'includes/oos_nice_exit.php'; ?>