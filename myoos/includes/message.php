<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: header.php,v 1.39 2003/02/13 04:23:23 hpdl
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

/*
<div class="alert alert-success">...</div>
<div class="alert alert-info">...</div>
<div class="alert alert-warning">...</div>
<div class="alert alert-danger">...</div>
*/

// check if the 'install' directory exists, and warn of its existence
if ((WARN_INSTALL_EXISTENCE == 'true') && ($_SERVER['HTTP_HOST'] != 'localhost')) {
    if (file_exists(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/install')) {
        $aInfoMessage[] = ['type' => 'danger',
                            'text' => $aLang['warning_install_directory_exists']];
    }
}

// check if the configure.php file is writeable
if ((WARN_CONFIG_WRITEABLE == 'true') && ($_SERVER['HTTP_HOST'] != 'localhost')) {
    if ((file_exists(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php'))
        && (is_writeable(dirname(oos_server_get_var('SCRIPT_FILENAME')) . '/includes/configure.php'))
    ) {
        $aInfoMessage[] = ['type' => 'danger',
                            'text' => $aLang['warning_config_file_writeable']];
    }
}


if ((WARN_DOWNLOAD_DIRECTORY_NOT_READABLE == 'true') && (DOWNLOAD_ENABLED == 'true') && ($_SERVER['HTTP_HOST'] != 'localhost')) {
    if (!is_dir(OOS_DOWNLOAD_PATH)) {
        $aInfoMessage[] = ['type' => 'danger',
                            'text' => $aLang['warning_download_directory_non_existent']];
    }
}


if (isset($_SESSION)) {
    if (isset($_SESSION['success_message'])
        && !empty($_SESSION['success_message'])
        && ($_SERVER['HTTP_HOST'] != 'localhost')
    ) {
        $aInfoMessage[] = ['type' => 'success',
                        'title' => $aLang['success'],
                        'text' => (string)$_SESSION['success_message']];
        unset($_SESSION['success_message']);
    }

    if (isset($_SESSION['info_message']) && !empty($_SESSION['info_message'])) {
        $aInfoMessage[] = ['type' => 'info',
                    'title' => $aLang['info'],
                    'text' => (string)$_SESSION['info_message']];
        unset($_SESSION['info_message']);
    }


    if (isset($_SESSION['error_message']) && !empty($_SESSION['error_message'])) {
        $aInfoMessage[] = ['type' => 'danger',
                    'title' => $aLang['danger'],
                    'text' => (string)$_SESSION['error_message']];
        unset($_SESSION['error_message']);
    }
}


/*
if ($oMessage->size('upload') > 0) {
    $aInfoMessage = array_merge ($aInfoMessage, $oMessage->output('upload') );
}
*/

$aType = [];
$aType = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

foreach ($aType as $class) {
    if ($oMessage->size($class) > 0) {
        $aInfoMessage = array_merge($aInfoMessage, $oMessage->output($class));
    }
}


$smarty->assign('message', $aInfoMessage);
