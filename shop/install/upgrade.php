<?php
/* ----------------------------------------------------------------------
   $Id: upgrade.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/
   
   
   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: upgrade.php,v 1.3 2002/03/07 09:56:00 lothrien 
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
   Original Author of file:  Gregor J. Rothfuss
   Purpose of file: Provide upgrade functions for installer.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );


function oosDoUpgrade140 ($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype) {
    global $db, $currentlang, $update;
    include("oos140.php");
}

function oosDoUpgrade160 ($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype) {
    global $db, $currentlang, $update;
    include("oos160.php");
}


function print_SelectOOS() {
  global $update;
  
  echo '<font class="oos-title">' . OOSUPGRADE_1 . ':</font><br /><br />' . "\n" .
       '<font class="oos-error">' . UPGRADE_INFO . '</font><br /><br />' . "\n" .
       '<form name="oos updrade" action="update.php" method="post">' . "\n" .
       '<input type="hidden" name="aupdate" value="oos140">' . "\n";
  echo '<table width="50%" align="center">' . "\n" .
         ' <tr>' . "\n" .
         '  <td><input type="submit"  name ="op" value="OOS 1.4.0"></td>' . "\n" .
         '</tr></table></form><br>' . "\n" .
         '<form name="oos updrade" action="update.php" method="post">' . "\n" .
         '<input type="hidden" name="aupdate" value="oos160">' . "\n";
  print_FormHidden(); 
  echo '<table width="50%" align="center">' . "\n" .
       ' <tr>' . "\n" .
       '  <td><input type="submit"  name ="op" value="OOS 1.6.0"></td>' . "\n" .
       '</tr></table></form>' . "\n" . 
       '<font class="oos-normal">' . OOSUPGRADE_5 . '</font><br /><br />' . "\n";


}


function print_Next() {
   global $update;

   echo '<form action="update.php" method="post"><center><table width="50%">' . "\n";
   echo '<tr><td align=center><input type="hidden" name="op" value="Finish">' . "\n" .
        '<input type="submit" value="' . BTN_FINISH . '"></td></tr></table></center></form>' . "\n"; 
}


?>
