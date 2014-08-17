<?php
/* ----------------------------------------------------------------------
   $Id: step.php 476 2013-07-13 08:22:48Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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

if (strlen(ini_get("safe_mode"))< 1) {
    @set_time_limit(0);
}

define('OOS_VALID_MOD', 'yes');
// Version information
define('OOS_VERSION', '2.0.24 -dev');

require_once '../includes/functions/function_global.php';
require_once '../includes/functions/function_kernel.php';
require_once '../includes/lib/phpass/PasswordHash.php';
require_once '../includes/functions/function_password.php';
require_once '../includes/lib/adodb/adodb.inc.php';

  include_once 'modify_configure.php';
  include_once 'upgrade.php';
  include_once 'newinstall.php';
  include_once 'gui.php';
  include_once 'db.php';
  include_once 'check.php';
  include_once 'language.php';

  if (isset($_POST)) {
    foreach ($_POST as $k=>$v) {
      $$k = oos_prepare_input($v);
    }
  }

  if (isset($alanguage)) {
    $currentlang = $alanguage;
  }

  if (isset($aupdate)) {
    $update = $aupdate;
  }

  if (!empty($encoded)) {
    $dbuname = base64_decode($dbuname);
    $dbpass = base64_decode($dbpass);
  }

  installer_get_language();

  include_once 'header.php';

/*  This starts the switch statement that filters through the form options.
 *  the @ is in front of $op to suppress error messages if $op is unset and E_ALL
 *  is on
 */
 switch (@$op) {

    case "Finish":
      print_oosFinish();
      break;

    case 'Set Login':
      oosDBInit($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
      oosInputData($gender, $firstname, $name, $pwd, $repeatpwd, $email, $phone, $fax, $prefix_table, $update);
      oosUpdateConfigShop(true); // Scott - added

      print_SetLogin();
      break;

    case 'Change Login':
      print_ChangeLogin();
      break;

    case 'Login':
      if ( ($pwd == '') || ($email == '') || ($pwd != $repeatpwd) ) {
        print_ChangeLogin();
      } else {
        print_Login();
      }
      break;


    case 'Start':
      make_db($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype, $dbmake);
      print_Start();
      break;

    case 'Admin':
       print_Admin();
       break; 

    case 'Submit':
      print_Submit();
      break;

    case 'CHM_check':
      print_CHMcheck();
      break;

    case 'ChangeServer':
      print_ChangeServer();
      break;

    case 'ConfigServer':
      print_ConfigServerInfo();
      break;

    case 'Design_Optionen':
      print_DesignOptionen();
      break;

    case 'Confirm':
      print_Confirm();
      break;

    case 'Change_Info':
      print_ChangeInfo();
      break;

    case 'New_Install':
      print_NewInstall();
      break;

    case 'DBSubmit':
      print_DBSubmit();
      break;

    case 'PHP_Check':
      if ($_POST['agreecheck'] == false) {
       print_select_language();
      } else {
        writeable_oosConfigure();
        oosCheckPHP();
      }
      break;

    case 'UpgardeOrInstall':
      print_oosUpgardeOrInstall();
      break;

    case 'Set Language':
      print_oosDefault();
      break;

    default:
      print_select_language();
      break;
  }
  include_once 'footer.php';

