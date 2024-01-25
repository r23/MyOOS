<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: db.php,v 1.5 2002/02/05 00:16:22 proca
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
   Purpose of file: Provide common db functions for the installer.
   ----------------------------------------------------------------------
 */

/***
 * Connect to Database
 ***/
function oosDBInit($dbhost, $dbuname, $dbpass, $dbname, $dbtype = 'mysql')
{
    global $db;

    $db = ADONewConnection($dbtype);
    $dbh = $db->Connect($dbhost, $dbuname, $dbpass, $dbname);
    if (!$dbh) {
        $dbpass = "";
        die("$dbtype://$dbuname:$dbpass@$dbhost/$dbname failed to connect" . $db->ErrorMsg());
    }
    global $ADODB_FETCH_MODE;
    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

    return true;
}
