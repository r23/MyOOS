<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   POST-NUKE Content Management System
   Copyright (C) 2001 by the Post-Nuke Development Team.
   http://www.postnuke.com/
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

function dosql($table, $flds)
{
    global $db;

    $dict = NewDataDictionary($db);

    // $dict->debug = 1;
    $taboptarray = ['mysql' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', 'REPLACE'];

    $sqlarray = $dict->createTableSQL($table, $flds, $taboptarray);
    $dict->executeSqlArray($sqlarray);



    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
}

function idxsql($idxname, $table, $idxflds)
{
    global $db;

    $dict = NewDataDictionary($db);

    $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
    $dict->executeSqlArray($sqlarray);
}


$table = $prefix_table . 'guest_account';
$flds = "
  guest_account_id I NOTNULL AUTO PRIMARY,
  customers_id I NOTNULL,
  date_added T
";
dosql($table, $flds);


$table = $prefix_table . 'customers_basket_mail';
$flds = "
  customers_basket_mail I NOTNULL AUTO PRIMARY,
  customers_basket_id I NOTNULL,
  customers_id I NOTNULL,
  products_id C(32) NOTNULL,
  customers_basket_mail_date_added T,
  orders_id I NOTNULL PRIMARY,
  orders_date T  
";
dosql($table, $flds);

/*
$table = $prefix_table . 'products_price_alarm';
$flds = "
  products_price_alarm_id I NOTNULL AUTO PRIMARY,
  products_id I NOTNULL PRIMARY,
  price_alarm_recipients_id I NOTNULL PRIMARY,
  products_price N '10.4' NOTNULL DEFAULT '0.0000',
  date_added T
";
dosql($table, $flds);

$table = $prefix_table . 'products_price_alarm_history';
$flds = "
  price_alarm_recipients_status_history_id I NOTNULL AUTO PRIMARY,
  price_alarm_recipients_id I NOTNULL DEFAULT '0',
  new_value I1 NOTNULL DEFAULT '0',
  old_value I1 DEFAULT NULL,
  date_added T,
  customer_notified I1 DEFAULT '0'
";
dosql($table, $flds);


$table = $prefix_table . 'products_price_alarm_recipients';
$flds = "
  price_alarm_recipients_id I NOTNULL AUTO PRIMARY,
  price_alert_receiver_email_address C(96) NOTNULL,
  price_alert_receiver_password C(255),	
  date_added T,
  mail_key C(32) NOTNULL,
  mail_sha1 C(232) NOTNULL,
  key_sent T,
  status I1 DEFAULT '0'
";
dosql($table, $flds);
*/