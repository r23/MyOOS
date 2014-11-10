<?php
/* ----------------------------------------------------------------------
   $Id: show_banner.php,v 1.1 2007/06/13 17:33:39 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: affiliate_show_banner.php,v 1.13 2003/02/28 10:27:13 simarilius 
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

// Set the local configuration parameters - mainly for developers
  if (file_exists('includes/local/configure.php')) {
    require 'includes/local/configure.php';
  }
  require 'includes/configure.php';

// include server parameters
  require 'includes/oos_define.php';
  require 'includes/oos_tables.php';
  require 'includes/functions/function_global.php';
  require 'includes/functions/function_kernel.php';

// include the database functions
  if (!defined('ADODB_LOGSQL_TABLE')) {
    define('ADODB_LOGSQL_TABLE', $oostable['adodb_logsql']);
  }
  require 'includes/classes/thirdparty/adodb/adodb-errorhandler.inc.php';
  require 'includes/classes/thirdparty/adodb/adodb.inc.php';
  require 'includes/functions/function_db.php';


// make a connection to the database... now
  if (!oosDBInit()) {
    die('Unable to connect to database server!');
  }

  $dbconn =& oosDBGetConn();
  oosDB_importTables($oostable);

  function affiliate_show_banner($pic) {
    //Read Pic and send it to browser
    $fp = fopen($pic, "rb");
    if (!$fp) exit();
    // Get Image type
    $img_type = substr($pic, strrpos($pic, ".") + 1);
    // Get Imagename
    $pos = strrpos($pic, "/");
    if ($pos) {
      $img_name = substr($pic, strrpos($pic, "/" ) + 1);
    } else {
      $img_name = $pic;
    }
    header ("Content-type: image/$img_type");
    header ("Content-Disposition: inline; filename=$img_name");
    fpassthru($fp);
    exit();
  }

  function affiliate_debug($banner,$sql) {
?>
    <table border=1 cellpadding=2 cellspacing=2>
      <tr><td colspan=2>Check the pathes! (shop/includes/configure.php)</td></tr>
      <tr><td>absolute path to picture:</td><td><?php echo OOS_ABSOLUTE_PATH . OOS_IMAGES . $banner; ?></td></tr>
      <tr><td>build with:</td><td>OOS_ABSOLUTE_PATH . OOS_IMAGES . $banner</td></tr>
      <tr><td>OOS_SHOP</td><td><?php echo OOS_SHOP ; ?></td></tr>
      <tr><td>OOS_IMAGES</td><td><?php echo OOS_IMAGES; ?></td></tr>
      <tr><td>$banner</td><td><?php echo $banner; ?></td></tr>
      <tr><td>SQL-Query used:</td><td><?php echo $sql; ?></td></tr>
      <tr><th>Try to find error:</td><td>&nbsp;</th></tr>
      <tr><td>SQL-Query:</td><td><?php if ($banner) echo "Got Result"; else echo "No result"; ?></td></tr>
      <tr><td>Locating Pic</td><td>
<?php 
    $pic = OOS_ABSOLUTE_PATH . OOS_IMAGES . $banner;
    echo $pic . "<br>";
    if (!is_file($pic)) {
      echo "failed<br>";
    } else {
      echo "success<br>";
    }
?>
      </td></tr>
    </table>
<?php
    exit();
  }

// Register needed Post / Get Variables

  if (isset($_GET['ref'])) $affiliate_id = $_GET['ref'];
  if (isset($_POST['ref'])) $affiliate_id = $_POST['ref'];

  if (isset($_GET['affiliate_banner_id'])) $banner_id = (int)$_GET['affiliate_banner_id'];
  if (isset($_POST['affiliate_banner_id'])) $banner_id = (int)$_POST['affiliate_banner_id'];
  if (isset($_GET['affiliate_pbanner_id'])) $prod_banner_id = (int)$_GET['affiliate_pbanner_id'];
  if (isset($_POST['affiliate_pbanner_id'])) $prod_banner_id = (int)$_POST['affiliate_pbanner_id'];

  $banner = '';
  $products_id = '';

  $affiliate_bannerstable = $oostable['affiliate_banners'];
  if (isset($banner_id) && is_numeric($banner_id)) {
    $sql = "SELECT affiliate_banners_image, affiliate_products_id
            FROM $affiliate_bannerstable
            WHERE affiliate_banners_id = '" . (int)$banner_id  . "'
              AND affiliate_status = 1";
    $banner_values = $dbconn->Execute($sql);
    if ($banner_array = $banner_values->fields) {
      $banner = $banner_array['affiliate_banners_image'];
      $products_id = $banner_array['affiliate_products_id']; 
    }
  }

  if (isset($prod_banner_id) && is_numeric($prod_banner_id)) {
    $banner_id = 1; // Banner ID for these Banners is one
    $productstable = $oostable['products'];
    $sql = "SELECT products_image
            FROM $productstable
            WHERE products_id = '" . (int)$prod_banner_id  . "'
              AND products_status >= 1";
    $banner_values = $dbconn->Execute($sql);
    if ($banner_array = $banner_values->fields) {
      $banner = $banner_array['products_image'];
      $products_id = $prod_banner_id;
    }
  }

// DebugModus
  if (AFFILIATE_SHOW_BANNERS_DEBUG == 'true') affiliate_debug($banner,$sql);

  if (isset($banner)) {
    $pic = OOS_ABSOLUTE_PATH . OOS_IMAGES . $banner;

    // Show Banner only if it exists:
    if (is_file($pic)) {
      $today = date('Y-m-d');
    // Update stats:
      if (isset($affiliate_id) && is_numeric($affiliate_id)) {
        $affiliate_banners_historytable = $oostable['affiliate_banners_history'];
        $sql = "SELECT affiliate_banners_shown
                FROM $affiliate_banners_historytable
                WHERE affiliate_banners_id = '" . (int)$banner_id  . "'
                  AND affiliate_banners_products_id = '" . (int)$products_id . "'
                  AND affiliate_banners_affiliate_id = '" . (int)$affiliate_id. "'
                  AND affiliate_banners_history_date = '" . oos_db_input($today) . "'";
        $banner_stats_result = $dbconn->Execute($sql);
    // Banner has been shown today 
        if ($banner_stats_array = $banner_stats_result->fields) {
          $affiliate_banners_historytable = $oostable['affiliate_banners_history'];
          $dbconn->Execute("UPDATE $affiliate_banners_historytable
                      SET affiliate_banners_shown = affiliate_banners_shown + 1
                      WHERE affiliate_banners_id = '" . (int)$banner_id  . "' AND
                            affiliate_banners_affiliate_id = '" . (int)$affiliate_id . "' AND
                            affiliate_banners_products_id = '" . (int)$products_id . "' AND
                            affiliate_banners_history_date = '" . oos_db_input($today) . "'");
        } else { // First view of Banner today
          $affiliate_banners_historytable = $oostable['affiliate_banners_history'];

          $dbconn->Execute("INSERT INTO $affiliate_banners_historytable
                      (affiliate_banners_id,
                       affiliate_banners_products_id,
                       affiliate_banners_affiliate_id,
                       affiliate_banners_shown,
                       affiliate_banners_history_date) VALUES ('" . (int)$banner_id  . "',
                                                               '" . (int)$products_id . "',
                                                               '" . (int)$affiliate_id . "',
                                                               '1',
                                                               '" . oos_db_input($today) . "')");
        }
      }
    // Show Banner
      affiliate_show_banner($pic);
    }
  }

// Show default Banner if none is found
  if (is_file(AFFILIATE_SHOW_BANNERS_DEFAULT_PIC)) {
    affiliate_show_banner(AFFILIATE_SHOW_BANNERS_DEFAULT_PIC);
  } else {
    echo "<br>"; // Output something to prevent endless loading
  }
  exit();
?>
