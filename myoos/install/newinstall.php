<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: newinstall.php,v 1.5 2002/02/09 12:50:40
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
   Original Author of file: Gregor J. Rothfuss
   Purpose of file: Provide functions for a new install.
   ---------------------------------------------------------------------- */

 /**
  * This function creates the DB on new installs
  */
function make_db($dbhost, $dbuname, $dbpass, $dbname, $prefix_table, $dbtype, $dbmake)
{
    global $db;

    echo '<font class="oos-title">' . INPUT_DATA . '</font>';
    echo '<table align="center"><tr><td align="left">';

    if ($dbmake) {
        $db = NewADOConnection($dbtype);
        $dbh = $db->Connect($dbhost, $dbuname, $dbpass);
        if (!$dbh) {
            $dbpass = "";
            die("$dbtype://$dbuname:$dbpass@$dbhost failed to connect" . $db->ErrorMsg());
        }

        $dict = NewDataDictionary($db);

        $sqlarray = $dict->CreateDatabase($dbname);
        $dict->ExecuteSQLArray($sqlarray);
    }
    oosDBInit($dbhost, $dbuname, $dbpass, $dbname, $dbtype);
    if (!$prefix_table == '') {
        $prefix_table = $prefix_table . '_';
    }
    include 'newtables.php';
    echo '</td></tr></table>';
}

  /**
   * This function inserts the default data on new installs
   */
function oosInputData($gender, $firstname, $name, $pwd, $repeatpwd, $email, $phone, $prefix_table, $update)
{
    global $currentlang, $db, $update;


    echo '<font class="oos-title">' . INPUT_DATA . '</font>';
    echo '<table align="center"><tr><td align="left">';

    if (!$prefix_table == '') {
        $prefix_table = $prefix_table . '_';
    }

    // Put basic information in first
    $today = date("Y-m-d H:i:s");
    include 'newdata.php';

    $owp_pwd = oos_encrypt_password($pwd);

    include_once 'newconfigdata.php';

    $admin_groups_id = '1';
    $sql = "INSERT INTO ". $prefix_table . "admin
            (admin_groups_id,
             admin_gender,
             admin_firstname,
             admin_lastname,
             admin_email_address,
             admin_telephone,
             admin_password,
             admin_created)
             VALUES (" . $db->qstr($admin_groups_id) . ','
                     . $db->qstr($gender) . ','
                     . $db->qstr($firstname) . ','
                     . $db->qstr($name) . ','
                     . $db->qstr($email) . ','
                     . $db->qstr($phone) . ','
                     . $db->qstr($owp_pwd) . ','
                     . $db->DBTimeStamp($today) . ")";
    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'admin&nbsp;'. UPDATED . '</font>';
    }

    $login = '1';
    $status = '1';
    $max_order = '5800';
    $default_address = '1';
    $logs = 0;
    $sTime = time();
    $wishlist_link_id = '';
    for ($x=3;$x<10;$x++) {
        $wishlist_link_id .= substr($sTime, $x, 1) . oos_create_random_value(1, $type = 'chars');
    }
	
	
	
    $sql = "INSERT INTO ". $prefix_table . "customers
            (customers_firstname,
             customers_lastname,
             customers_email_address,
             customers_telephone,
             customers_status,
             customers_login,
             customers_max_order,
             customers_password,
             customers_wishlist_link_id,
             customers_default_address_id)
             VALUES (" . $db->qstr($firstname) . ','
                     . $db->qstr($name) . ','
                     . $db->qstr($email) . ','
                     . $db->qstr($phone) . ','
                     . $db->qstr($status) . ','
                     . $db->qstr($login) . ','
                     . $db->qstr($max_order) . ','
                     . $db->qstr($owp_pwd) . ','
                     . $db->qstr($wishlist_link_id) . ','
                     . $db->qstr($default_address) . ")";

    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'customers&nbsp;'. UPDATED . '</font>';
    }

    $customer_id = $db->Insert_ID();

    $book_id = 1;
    $country = 81;	
	$gender = ' ';
	$street_address  = ' ';
	$postcode = ' ';
	$city = ' ';	
    $sql = "INSERT INTO ". $prefix_table . "address_book
            (customers_id,
			entry_gender,
             address_book_id,
             entry_firstname,
             entry_lastname,
			 entry_street_address,
			 entry_postcode,
			 entry_city,
             entry_country_id)
             VALUES (" . $db->qstr($customer_id) . ','
					. $db->qstr($gender) . ','
                     . $db->qstr($book_id) . ','
                     . $db->qstr($firstname) . ','
                     . $db->qstr($name) . ','
 					 . $db->qstr($street_address) . ','
					 . $db->qstr($postcode) . ','				 
					 . $db->qstr($city) . ','
                     . $db->qstr($country) . ")";
    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'address_book&nbsp;'. UPDATED . '</font>';
    }

    $sql = "INSERT INTO ". $prefix_table . "customers_info
           (customers_info_id,
            customers_info_number_of_logons,
            customers_info_date_account_created) VALUES (" . $db->qstr($customer_id) . ','
                                                         . $db->qstr($logs) . ','
                                                         . $db->DBTimeStamp($today) . ")";
    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'customers_info&nbsp;'. UPDATED . '</font>';
    }

    $store_owner = $firstname . ' ' . $name;
    $sql = "UPDATE " . $prefix_table . "configuration SET configuration_value = " . $db->qstr($store_owner) . ", last_modified = " . $db->DBTimeStamp($today) . " WHERE configuration_key = 'STORE_OWNER'";
    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'configuration&nbsp;'. UPDATED . '</font>';
    }

    $sql = "UPDATE " . $prefix_table . "configuration SET configuration_value = " . $db->qstr($email) . ", last_modified = " . $db->DBTimeStamp($today) . " WHERE configuration_key = 'STORE_OWNER_EMAIL_ADDRESS'";
    $result = $db->Execute($sql);
    if ($result === false) {
        echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
    } else {
        echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . 'configuration&nbsp;'. UPDATED . '</font>';
    }

    echo '</td></tr></table>';
}
