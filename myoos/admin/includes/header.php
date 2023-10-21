<?php
/**
   ----------------------------------------------------------------------
   $Id: header.php,v 1.1 2007/06/08 15:20:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: header.php,v 1.19 2002/04/13 16:11:52 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// Create a nonce RANDOM_VALUE
$nonce = bin2hex(random_bytes(16));
define('NONCE', $nonce);

// Send the CSP header with the nonce RANDOM_VALUE
header("Content-Security-Policy: script-src 'nonce-" . NONCE . "' 'unsafe-eval' 'strict-dynamic' 'unsafe-inline'; object-src 'none'; base-uri 'self'");


?><!DOCTYPE html>
<html lang="<?php echo $_SESSION['iso_639_1']; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.1, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo HEADING_TITLE . ' - ' . TITLE; ?></title>
    <meta http-equiv="expires" content="0" >

    <!-- Bootstrap style  --> 
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="css/icons.min.css" rel="stylesheet">    
    <link href="css/myoos.min.css" rel="stylesheet">
    <!-- DATETIMEPICKER-->
    <link href="js/plugins/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <!-- COLORPICKER-->
    <link href="js/plugins/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css" rel="stylesheet">
    <link href="css/plugins/flag-icon/css/flag-icon.min.css" rel="stylesheet" />
    <link href="css/pannellum.min.css" rel="stylesheet" />

    <script nonce="<?php echo NONCE; ?>" src="js/pannellum/pannellum.min.js"></script>
    <script nonce="<?php echo NONCE; ?>" src="js/pannellum/libpannellum.min.js"></script>
</head>
<body>
<?php
if ($messageStack->size > 0) {
    echo $messageStack->output();
}
?>


