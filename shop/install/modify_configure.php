<?php
/* ----------------------------------------------------------------------
   $Id: modify_configure.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2006 by the OOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: modify_configure.php,v 1.13 2002/03/16 15:24:37 johnnyrocket
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
   Original Author of file: Scott Kirkwood (scott_kirkwood@bigfoot.com)
   Purpose of file: Routines to modify the configure.php file.
   General routine modify_file() is useful in it's own right.
   ---------------------------------------------------------------------- */

// mod_file is general, give it a source file a destination.
// an array of search patterns (Perl style) and replacement patterns
// Returns a string which starts with "Err" if there's an error
function modify_file($src, $dest, $reg_src, $reg_rep) {
    $in = @fopen($src, "r");
    if (!$in) {
      return MODIFY_FILE_1. " $src";
    }
    $i = 0;
    while (!feof($in)) {
        $file_buff1[$i++] = fgets($in, 4096);
    }
    fclose($in);

    $lines = 0; // Keep track of the number of lines changed

    while (list ($bline_num, $buffer) = each ($file_buff1)) {
        $new = preg_replace($reg_src, $reg_rep, $buffer);
        if ($new != $buffer) {
            $lines++;
        }
        $file_buff2[$bline_num] = $new;
    }

    if ($lines == 0) {
        // Skip the rest - no lines changed
      return MODIFY_FILE_3;
    }

    reset($file_buff1);
    $out_backup = @fopen($dest, "w");

    if (! $out_backup) {
      return MODIFY_FILE_2. " $dest";
    }

    while (list ($bline_num, $buffer) = each ($file_buff1)) {
        fputs($out_backup,$buffer);
    }

    fclose($out_backup);

    reset($file_buff2);
    $out_original = fopen($src, "w");
    if (! $out_original) {
      return MODIFY_FILE_2. " $src";
    }

    while (list ($bline_num, $buffer) = each ($file_buff2)) {
        fputs($out_original,$buffer);
    }

    fclose($out_original);

    // Success!
    return "$src updated with $lines lines of changes, backup is called $dest";
}

// Two global arrays
$reg_src = array();
$reg_rep = array();

// Setup various searches and replaces
// Scott Kirkwood
function add_src_rep($key, $rep) {
    global $reg_src, $reg_rep;

    $reg_src[] = "/(define\()([\"'])(".$key.")\\2,\s*([\"'])(.*?)\\4\s*\)/";
    $reg_rep[] = "define('".$key."', '".$rep."')";
}


function show_error_shop_info() {
    global $dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype;
    global $oos_server, $oos_ssl_server, $enable_ssl, $oos_root_path, $oos_shop_path, $oos_shop_dir, $oos_template_dir;


    echo '<br /><br /><b>'. SHOW_ERROR_INFO . ' <b>~/includes/configure.php</b><br /><br />';
echo <<< EOT
        <table><tr><td><font class="oos-normal">
        define('OOS_HTTP_SERVER', '$oos_server');<br />
        define('OOS_HTTPS_SERVER', '$oos_ssl_server');<br />
        define('OOS_SHOP', '$oos_shop_dir');<br />
        define('OOS_ABSOLUTE_PATH', '$oos_root_path$oos_shop_dir');<br />
        define('OOS_TEMP_PATH', '$oos_template_dir'); <br />
        define('OOS_DB_TYPE', '$dbtype');<br />
        define('OOS_DB_SERVER', '$dbhost');<br />
        define('OOS_DB_USERNAME', '$dbuname');<br />
        define('OOS_DB_PASSWORD', '$dbpass');<br />
        define('OOS_DB_DATABASE', '$dbname');<br />
        define('OOS_DB_PREFIX', '$prefix_table');<br />
        define('OOS_ENCODED', '0');<br />
        </b></td></tr></table>
EOT;

}


// Update the configure.php file with the database information.
function oosUpdateConfigShop($db_prefs = false) {
    global $reg_src, $reg_rep;
    global $dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype;
    global $oos_server, $oos_ssl_server, $enable_ssl, $oos_root_path, $oos_shop_path, $oos_shop_dir, $oos_template_dir;

    add_src_rep("OOS_HTTP_SERVER", $oos_server);
    add_src_rep("OOS_HTTPS_SERVER", $oos_ssl_server);
    if ($enable_ssl == 'on') {
      add_src_rep("ENABLE_SSL", 'true');
    } else {
      add_src_rep("ENABLE_SSL", 'false');
    }
    add_src_rep("OOS_SHOP", $oos_shop_dir);
    add_src_rep("OOS_ABSOLUTE_PATH", $oos_root_path . $oos_shop_dir);
    add_src_rep("OOS_TEMP_PATH", $oos_template_dir);

    add_src_rep("OOS_DB_TYPE", $dbtype);
    add_src_rep("OOS_DB_SERVER", $dbhost);
    add_src_rep("OOS_DB_USERNAME", base64_encode($dbuname));
    add_src_rep("OOS_DB_PASSWORD", base64_encode($dbpass));
    add_src_rep("OOS_DB_DATABASE", $dbname);
    add_src_rep("OOS_DB_PREFIX", $prefix_table);
    if (strstr($HTTP_ENV_VARS["OS"],"Win")) {
        add_src_rep("OOS_SYSTEM", '1');
    } else {
        add_src_rep("OOS_SYSTEM", '0');
    }
    add_src_rep("OOS_ENCODED", '1');

    $ret = modify_file("../includes/configure.php", "../includes/configure-old.php", $reg_src, $reg_rep);

    if (preg_match("/Error/", $ret)) {
        show_error_shop_info();
    }
}



