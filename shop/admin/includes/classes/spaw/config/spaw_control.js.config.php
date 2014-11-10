<?php 
// ================================================
// SPAW PHP WYSIWYG editor control
// ================================================
// Configuration file
// ================================================
// Developed: Alan Mendelevich, alan@solmetra.lt
// Copyright: Solmetra (c)2003 All rights reserved.
// ------------------------------------------------
//                                www.solmetra.com
// ================================================
// v.1.0, 2003-03-27
// ================================================


if (!defined('OOS_HTTP_SERVER')) {
  // include('/homepage/shop/admin/includes/config.php');
  $self = $_SERVER['PHP_SELF'];
  $self = dirname ($self);
  $self = str_replace('/classes/spaw','/configure.php',$self);
  include($_SERVER['DOCUMENT_ROOT'] . $self);
}

if (!defined('OOS_WYSIWYG')) {
  define('OOS_WYSIWYG', 'includes/classes/spaw/');
}

// directory where spaw files are located
$spaw_dir = '';

// base url for images
$spaw_base_url = OOS_HTTP_SERVER . OOS_SHOP . 'admin/' . OOS_WYSIWYG;

?>