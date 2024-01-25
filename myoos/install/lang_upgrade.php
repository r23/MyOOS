<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

global $db, $prefix_table;


$table = $prefix_table . 'block_info';
$result = $db->Execute("UPDATE " . $table . " SET block_language = '1' WHERE block_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET block_language = '2' WHERE block_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET block_language = '3' WHERE block_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET block_language = '4' WHERE block_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET block_language = '5' WHERE block_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET block_language = '6' WHERE block_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `block_language` `block_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'categories_description';
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '1' WHERE categories_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '2' WHERE categories_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '3' WHERE categories_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '4' WHERE categories_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '5' WHERE categories_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET categories_language = '6' WHERE categories_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `categories_language` `categories_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'coupons_description';
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '1' WHERE coupon_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '2' WHERE coupon_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '3' WHERE coupon_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '4' WHERE coupon_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '5' WHERE coupon_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET coupon_language = '6' WHERE coupon_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `coupon_language` `coupon_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'customers_status';
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '1' WHERE customers_status_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '2' WHERE customers_status_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '3' WHERE customers_status_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '4' WHERE customers_status_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '5' WHERE customers_status_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET customers_status_language = '6' WHERE customers_status_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `customers_status_language` `customers_status_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'link_categories_description';
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '1' WHERE link_categories_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '2' WHERE link_categories_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '3' WHERE link_categories_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '4' WHERE link_categories_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '5' WHERE link_categories_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET link_categories_language = '6' WHERE link_categories_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `link_categories_language` `link_categories_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'links_description';
$result = $db->Execute("UPDATE " . $table . " SET links_language = '1' WHERE links_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET links_language = '2' WHERE links_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET links_language = '3' WHERE links_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET links_language = '4' WHERE links_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET links_language = '5' WHERE links_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET links_language = '6' WHERE links_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `links_language` `links_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'links_status';
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '1' WHERE links_status_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '2' WHERE links_status_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '3' WHERE links_status_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '4' WHERE links_status_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '5' WHERE links_status_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET links_status_language = '6' WHERE links_status_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `links_status_language` `links_status_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'manufacturers_info';
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '1' WHERE manufacturers_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '2' WHERE manufacturers_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '3' WHERE manufacturers_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '4' WHERE manufacturers_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '5' WHERE manufacturers_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET manufacturers_language = '6' WHERE manufacturers_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `manufacturers_language` `manufacturers_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}



$table = $prefix_table . 'orders_status';
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '1' WHERE orders_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '2' WHERE orders_language = 'eng'");


$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `orders_language` `orders_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'page_type';
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '1' WHERE page_type_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '2' WHERE page_type_language = 'eng'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `page_type_language` `page_type_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_description';
$result = $db->Execute("UPDATE " . $table . " SET products_language = '1' WHERE products_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '2' WHERE products_language = 'eng'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_language` `products_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_options';
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '1' WHERE products_options_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '2' WHERE products_options_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '3' WHERE products_options_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '4' WHERE products_options_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '5' WHERE products_options_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_language = '6' WHERE products_options_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_options_language` `products_options_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_options_types';
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '1' WHERE products_options_types_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '2' WHERE products_options_types_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '3' WHERE products_options_types_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '4' WHERE products_options_types_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '5' WHERE products_options_types_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_types_language = '6' WHERE products_options_types_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_options_types_language` `products_options_types_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_options_values';
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '1' WHERE products_options_values_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '2' WHERE products_options_values_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '3' WHERE products_options_values_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '4' WHERE products_options_values_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '5' WHERE products_options_values_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET products_options_values_language = '6' WHERE products_options_values_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_options_values_language` `products_options_values_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_status';
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '1' WHERE products_status_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '2' WHERE products_status_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '3' WHERE products_status_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '4' WHERE products_status_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '5' WHERE products_status_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET products_status_language = '6' WHERE products_status_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `products_status_language` `products_status_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'reviews_description';
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '1' WHERE reviews_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '2' WHERE reviews_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '3' WHERE reviews_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '4' WHERE reviews_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '5' WHERE reviews_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET reviews_language = '6' WHERE reviews_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `reviews_language` `reviews_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
    echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
    echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}



if (!function_exists('dosql')) {
    function dosql($table, $flds)
    {
        global $db;

        $dict = NewDataDictionary($db);

        // $dict->debug = 1;
        $taboptarray = ['mysql' => 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;', 'REPLACE'];

        $sqlarray = $dict->createTableSQL($table, $flds, $taboptarray);
        $dict->executeSqlArray($sqlarray);

        echo '<br><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
    }
}


if (!function_exists('idxsql')) {
    function idxsql($idxname, $table, $idxflds)
    {
        global $db;

        $dict = NewDataDictionary($db);

        $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
        $dict->executeSqlArray($sqlarray);
    }
}


$table = $prefix_table . 'languages';
$flds = "
  languages_id I NOTNULL AUTO PRIMARY,
  name C(32) NOTNULL,
  iso_639_2 C(3) NOTNULL,
  iso_639_1 C(2) NOTNULL,
  status I1 DEFAULT '0',
  sort_order I1 NOTNULL DEFAULT '0'
";
dosql($table, $flds);

$idxname = 'idx_name';
$idxflds = 'name';
idxsql($idxname, $table, $idxflds);



$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (1, 'Deutsch', 'deu', 'de', 1, 1)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (2, 'English', 'eng', 'en', 1, 2)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (3, 'Nederlands', 'nld', 'nl', 1, 3)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (4, 'Polski', 'pol', 'pl', 0, 4)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (5, 'Russian', 'rus', 'ru', 0, 5)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (6, 'Spanish', 'spa', 'es', 0, 6)") or die("<b>".NOTUPDATED . $prefix_table . "languages</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "languages " . UPDATED .'</font>';
