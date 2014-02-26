<?php
/* ----------------------------------------------------------------------
   $Id: info_message.php 448 2013-06-27 22:50:49Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: header.php,v 1.39 2003/02/13 04:23:23 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

/*
<div class="alert alert-success">...</div>
<div class="alert alert-info">...</div>
<div class="alert alert-warning">...</div>
<div class="alert alert-danger">...</div>
*/

$aInfoMessage = array();

// check if the 'install' directory exists, and warn of its existence
if (WARN_INSTALL_EXISTENCE == 'true') {
    if (file_exists(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install')) {
        $aInfoMessage[] = array('type' => 'warning',
                              'text' => $aLang['warning_install_directory_exists']);
    }
}

// check if the configure.php file is writeable
if (WARN_CONFIG_WRITEABLE == 'true') {
    if ( (file_exists(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php')) 
            && (is_writeable(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php')) )
    {
        $aInfoMessage[] = array('type' => 'warning',
                                'text' => $aLang['warning_config_file_writeable']);
    }
}


// check if the session folder is writeable
if (WARN_SESSION_DIRECTORY_NOT_WRITEABLE == 'true') {
    if (!is_dir(oos_session_save_path())) {
        $aInfoMessage[] = array('type' => 'warning',
                                'text' => $aLang['warning_session_directory_non_existent']);
    } elseif (!is_writeable(oos_session_save_path())) {
        $aInfoMessage[] = array('type' => 'warning',
                                'text' => $aLang['warning_session_directory_not_writeable']);
    }
}


// check session.auto_start is disabled
if ( (function_exists('ini_get')) && (WARN_SESSION_AUTO_START == 'true') ) {
    if (ini_get('session.auto_start') == '1') {
        $aInfoMessage[] = array('type' => 'warning',
                              'text' => $aLang['warning_session_auto_start']);
    }
}

if ( (WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') ) {
    if (!is_dir(OOS_DOWNLOAD_PATH)) {
        $aInfoMessage[] = array('type' => 'warning',
                              'text' => $aLang['warning_download_directory_non_existent']);
    }
}

if (isset($_SESSION['error_cart_msg']) && !empty($_SESSION['error_cart_msg'])) {
    $aInfoMessage[] = array('type' => 'danger',
                            'title' => $aLang['danger'],
                            'text' => (string)$_SESSION['error_cart_msg']);
    $_SESSION['error_cart_msg'] = '';
}

if (isset($_SESSION['error_search_msg']) && !empty($_SESSION['error_search_msg'])) {
    $aInfoMessage[] = array('type' => 'danger',
                            'title' => $aLang['danger'],
                            'text' => (string)$_SESSION['error_search_msg']);
    $_SESSION['error_search_msg'] = '';
}

// todo remove 
if (isset($_GET['error_message']) && !empty($_GET['error_message'])) {
    $sErrorGetMessage = oos_var_prep_for_os(urldecode($_GET['error_message']));
    $aInfoMessage[] = array('type' => 'danger',
                            'title' => $aLang['danger'],
                            'text' => $sErrorGetMessage);
}

// todo remove
if (isset($_GET['info_message']) && !empty($_GET['info_message'])) {
    $sInfoGetMessage = oos_var_prep_for_os(urldecode($_GET['info_message']));  
    $aInfoMessage[] = array('type' => 'info',
                            'text' => $sInfoGetMessage );
}

if (isset($sErrorMessage) && !empty($sErrorMessage)) {

    $aInfoMessage[] = array('type' => 'danger',
                            'title' => $aLang['danger'],
                            'text' => $sErrorMessage);
}

if (isset($sInfoMessage) && !empty($sInfoMessage)) { 
    $aInfoMessage[] = array('type' => 'info',
                            'text' => $sInfoMessage );
}

if ($oMessage->size('upload') > 0) {
    $aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('upload') );
}

$smarty->assign('message', $aInfoMessage);

