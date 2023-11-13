<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: install.php,v 1.91 2002/02/05 11:09:04 jgm
   ----------------------------------------------------------------------
   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   Based on:
   PHP-NUKE Web Portal System - http://phpnuke.org/
   Thatware - http://thatware.org/
   ----------------------------------------------------------------------
   LICENSE

   This program is free software; you can redistribute it and/or
   modify it under the terms of the GNU General Public License (GPL)
   as published by the Free Software Foundation; either version 2
   of the License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   To read the license please visit http://www.gnu.org/copyleft/gpl.html
   ----------------------------------------------------------------------
   Original Author of file:
   Purpose of file:
   ---------------------------------------------------------------------- */

/**
 * PostNuke Install Script.
 *
 * This script will set the database up, and do the basic configurations of the script.
 * Once this script has run, please delete this file from your root directory.
 * There is a security risk if you keep this file around.
 *
 * This module of the PostNuke project was inspired by the myPHPNuke project.
 *
 * The PostNuke project is free software released under the GNU License.
 * Please read the credits file for more information on who has made this project possible.
 */

// Set the level of error reporting
error_reporting(E_ALL & ~E_NOTICE);

define('OOS_VALID_MOD', true);
require_once '../includes/version.php';

// Set the local configuration parameters - mainly for developers
if (is_readable('../includes/local/configure.php')) {
    include_once '../includes/local/configure.php';
} else {
    include_once '../includes/configure.php';
}


if (!defined('MYOOS_INCLUDE_PATH')) {
    define('MYOOS_INCLUDE_PATH', OOS_ABSOLUTE_PATH);
}

$autoloader = include_once MYOOS_INCLUDE_PATH . '/vendor/autoload.php';

require_once MYOOS_INCLUDE_PATH . '/includes/functions/function_kernel.php';
require_once MYOOS_INCLUDE_PATH . '/includes/classes/class_template.php';

require 'modify_configure.php';
require 'upgrade.php';

require 'gui.php';
require 'db.php';
require 'language.php';

$dbtype = OOS_DB_TYPE;
$dbhost = OOS_DB_SERVER;
$dbname = OOS_DB_DATABASE;
$prefix_table = OOS_DB_PREFIX;

// Decode encoded DB parameters
if (OOS_ENCODED == '1') {
    $dbuname = base64_decode((string) OOS_DB_USERNAME);
    $dbpass = base64_decode((string) OOS_DB_PASSWORD);
} else {
    $dbuname = OOS_DB_USERNAME;
    $dbpass = OOS_DB_PASSWORD;
}

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = oos_prepare_input($v);
    }
}

installer_get_language();

require 'header.php';

/*  This starts the switch statement that filters through the form options.
 *  the @ is in front of $op to suppress error messages if $op is unset and E_ALL
 *  is on
 */
switch (@$op) {

    case "Finish":
        print_oosFinish();
        break;


    case "myOOS 2.4.58":
        oosDBInit($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
        oosDoUpgrade2458($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype);
        print_Next();
        break;


    default:

        $smarty = new myOOS_Smarty();

        $smarty->force_compile   = true;
        $smarty->clearAllCache();
        $smarty->clearCompiledTemplate();

        print_SelectOOS();
        break;
}

require 'footer.php';
