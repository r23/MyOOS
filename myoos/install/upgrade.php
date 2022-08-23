<?php
/* ----------------------------------------------------------------------
   $Id: upgrade.php,v 1.1 2007/06/13 16:41:18 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de


   Copyright (c) 2003 - 2021 by the MyOOS Development Team
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

defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');



function oosDoUpgrade2312($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2312.php";
}

function oosDoUpgrade241($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos241.php";
}

function oosDoUpgrade243($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos243.php";
}

function oosDoUpgrade2416($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2416.php";
}

function oosDoUpgrade2420($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2420.php";
}

function oosDoUpgrade2426($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2426.php";
}


function oosDoUpgrade2433($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2433.php";
}

function oosDoUpgrade2434($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2434.php";
}

function oosDoUpgrade2438($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2438.php";
}

function oosDoUpgrade2439($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2439.php";
}


function oosDoUpgrade2440($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2440.php";
}

function oosDoUpgrade2445($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2445.php";
}

function oosDoUpgrade2446($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2446.php";
}

function oosDoUpgrade2447($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2447.php";
}

function oosDoUpgrade2448($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2448.php";
}

function oosDoUpgrade2449($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2449.php";
}

function oosDoUpgrade2450($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2450.php";
}


function oosDoUpgrade2451($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype)
{
    global $db, $currentlang, $update;
    include "oos2451.php";
}



function print_SelectOOS()
{
    global $update;

    echo '<font class="oos-title">' . OOSUPGRADE_1 . ':</font><br /><br />' . "\n" .
       '<font class="oos-error">' . UPGRADE_INFO . '</font><br /><br />' . "\n" .
       '<form name="oos updrade" action="update.php" method="post">' . "\n" .
       '<fieldset>' . "\n" .
       '   <input type="radio" id="23" name="op" value="myOOS 2.3.12">' . "\n" .
       '    <label for="23"> myOOS 2.3.12</label> ' . "\n" .
       '    <input type="radio" id="241" name="op" value="myOOS 2.4.1" >' . "\n" .
       '   <label for="241"> myOOS 2.4.1</label> ' . "\n" .
       '    <input type="radio" id="243" name="op" value="myOOS 2.4.3" >' . "\n" .
       '   <label for="243"> myOOS 2.4.3</label> ' . "\n" .
       '    <input type="radio" id="2416" name="op" value="myOOS 2.4.16">' . "\n" .
       '   <label for="2416"> myOOS 2.4.16</label> ' . "\n" .
       '    <input type="radio" id="2420" name="op" value="myOOS 2.4.20">' . "\n" .
       '   <label for="2420"> myOOS 2.4.20</label> ' . "\n" .
       '    <input type="radio" id="2426" name="op" value="myOOS 2.4.26">' . "\n" .
       '   <label for="2426"> myOOS 2.4.26</label> ' . "\n" .
       '    <input type="radio" id="2433" name="op" value="myOOS 2.4.33">' . "\n" .
       '   <label for="2433"> myOOS 2.4.33</label> ' . "\n" .
       '    <input type="radio" id="2434" name="op" value="myOOS 2.4.34">' . "\n" .
       '   <label for="2434"> myOOS 2.4.34</label> ' . "\n" .
       '    <input type="radio" id="2438" name="op" value="myOOS 2.4.38">' . "\n" .
       '   <label for="2438"> myOOS 2.4.38</label> ' . "\n" .
       '    <input type="radio" id="2439" name="op" value="myOOS 2.4.39">' . "\n" .
       '   <label for="2439"> myOOS 2.4.39</label> ' . "\n" .
       '    <input type="radio" id="2440" name="op" value="myOOS 2.4.40">' . "\n" .
       '   <label for="2440"> myOOS 2.4.40</label> ' . "\n" .
       '    <input type="radio" id="2445" name="op" value="myOOS 2.4.45">' . "\n" .
       '   <label for="2445"> myOOS 2.4.45</label> ' . "\n" .
       '    <input type="radio" id="2446" name="op" value="myOOS 2.4.46">' . "\n" .
       '   <label for="2446"> myOOS 2.4.46</label> ' . "\n" .
       '    <input type="radio" id="2447" name="op" value="myOOS 2.4.47">' . "\n" .
       '   <label for="2447"> myOOS 2.4.47</label> ' . "\n" .
       '    <input type="radio" id="2448" name="op" value="myOOS 2.4.48">' . "\n" .
       '   <label for="2448"> myOOS 2.4.48</label> ' . "\n" .
       '    <input type="radio" id="2449" name="op" value="myOOS 2.4.49">' . "\n" .
       '   <label for="2449"> myOOS 2.4.49</label> ' . "\n" .
       '    <input type="radio" id="2450" name="op" value="myOOS 2.4.50">' . "\n" .
       '   <label for="2450"> myOOS 2.4.50</label> ' . "\n" .
       '    <input type="radio" id="2451" name="op" value="myOOS 2.4.51">' . "\n" .
       '   <label for="2451"> myOOS 2.4.51</label> ' . "\n" .
       ' </fieldset>' . "\n";
    print_FormHidden();
    echo '<table width="50%" align="center">' . "\n" .
       ' <tr>' . "\n" .
       '  <td><input type="submit" value="' . BTN_CONTINUE . '"></td>' . "\n" .
       '</tr></table></form>' . "\n" .
       '<font class="oos-normal">' . OOSUPGRADE_5 . '</font><br /><br />' . "\n";
}


function print_Next()
{
    global $update;

    echo '<form action="update.php" method="post"><center><table width="50%">' . "\n";
    echo '<tr><td align=center><input type="hidden" name="op" value="Finish">' . "\n" .
        '<input type="submit" value="' . BTN_FINISH . '"></td></tr></table></center></form>' . "\n";
}
