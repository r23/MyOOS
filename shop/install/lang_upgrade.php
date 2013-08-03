<?php
/* ----------------------------------------------------------------------
   $Id: lang_upgrade.php 437 2013-06-22 15:33:30Z r23 $

   OOS [OSIS Online Shop]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

global $db, $prefix_table;

$table = $prefix_table . 'affiliate_payment_status';
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '1' WHERE affiliate_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '2' WHERE affiliate_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '3' WHERE affiliate_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '4' WHERE affiliate_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '5' WHERE affiliate_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET affiliate_language = '6' WHERE affiliate_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `affiliate_language` `affiliate_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


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


$table = $prefix_table . 'newsfeed_categories';
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '1' WHERE newsfeed_categories_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '2' WHERE newsfeed_categories_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '3' WHERE newsfeed_categories_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '4' WHERE newsfeed_categories_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '5' WHERE newsfeed_categories_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_categories_language = '6' WHERE newsfeed_categories_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `newsfeed_categories_language` `newsfeed_categories_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'newsfeed_info';
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '1' WHERE newsfeed_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '2' WHERE newsfeed_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '3' WHERE newsfeed_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '4' WHERE newsfeed_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '5' WHERE newsfeed_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_language = '6' WHERE newsfeed_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `newsfeed_language` `newsfeed_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'newsfeed_manager';
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '1' WHERE newsfeed_manager_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '2' WHERE newsfeed_manager_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '3' WHERE newsfeed_manager_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '4' WHERE newsfeed_manager_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '5' WHERE newsfeed_manager_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET newsfeed_manager_language = '6' WHERE newsfeed_manager_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `newsfeed_manager_language` `newsfeed_manager_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'news_categories_description';
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '1' WHERE news_categories_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '2' WHERE news_categories_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '3' WHERE news_categories_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '4' WHERE news_categories_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '5' WHERE news_categories_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET news_categories_language = '6' WHERE news_categories_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `news_categories_language` `news_categories_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'news_description';
$result = $db->Execute("UPDATE " . $table . " SET news_language = '1' WHERE news_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET news_language = '2' WHERE news_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET news_language = '3' WHERE news_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET news_language = '4' WHERE news_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET news_language = '5' WHERE news_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET news_language = '6' WHERE news_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `news_language` `news_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'news_reviews_description';
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '1' WHERE news_reviews_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '2' WHERE news_reviews_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '3' WHERE news_reviews_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '4' WHERE news_reviews_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '5' WHERE news_reviews_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET news_reviews_language = '6' WHERE news_reviews_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `news_reviews_language` `news_reviews_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}



$table = $prefix_table . 'orders_status';
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '1' WHERE orders_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '2' WHERE orders_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '3' WHERE orders_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '4' WHERE orders_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '5' WHERE orders_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET orders_language = '6' WHERE orders_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `orders_language` `orders_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'page_type';
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '1' WHERE page_type_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '2' WHERE page_type_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '3' WHERE page_type_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '4' WHERE page_type_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '5' WHERE page_type_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET page_type_language = '6' WHERE page_type_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `page_type_language` `page_type_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'products_description';
$result = $db->Execute("UPDATE " . $table . " SET products_language = '1' WHERE products_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '2' WHERE products_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '3' WHERE products_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '4' WHERE products_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '5' WHERE products_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET products_language = '6' WHERE products_language = 'spa'");

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


$table = $prefix_table . 'ticket_admins';
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '1' WHERE ticket_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '2' WHERE ticket_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '3' WHERE ticket_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '4' WHERE ticket_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '5' WHERE ticket_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '6' WHERE ticket_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `ticket_language` `ticket_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'ticket_department';
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '1' WHERE ticket_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '2' WHERE ticket_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '3' WHERE ticket_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '4' WHERE ticket_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '5' WHERE ticket_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '6' WHERE ticket_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `ticket_language` `ticket_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'ticket_priority';
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '1' WHERE ticket_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '2' WHERE ticket_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '3' WHERE ticket_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '4' WHERE ticket_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '5' WHERE ticket_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '6' WHERE ticket_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `ticket_language` `ticket_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'ticket_reply';
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '1' WHERE ticket_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '2' WHERE ticket_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '3' WHERE ticket_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '4' WHERE ticket_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '5' WHERE ticket_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '6' WHERE ticket_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `ticket_language` `ticket_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}


$table = $prefix_table . 'ticket_status';
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '1' WHERE ticket_language = 'deu'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '2' WHERE ticket_language = 'eng'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '3' WHERE ticket_language = 'nld'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '4' WHERE ticket_language = 'pol'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '5' WHERE ticket_language = 'rus'");
$result = $db->Execute("UPDATE " . $table . " SET ticket_language = '6' WHERE ticket_language = 'spa'");

$result = $db->Execute("ALTER TABLE " . $table . " CHANGE `ticket_language` `ticket_languages_id` INT( 11 ) DEFAULT '1' NOT NULL");
if ($result === false) {
  echo '<br /><img src="images/no.gif" alt="" border="0" align="absmiddle"><font class="oos-error">' .  $db->ErrorMsg() . NOTMADE . '</font>';
} else {
  echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"><font class="oos-title">' . $table . ' ' . UPDATED .'</font>';
}



if (!function_exists('dosql')) {
   function dosql($table, $flds) {
     GLOBAL $db;

     $dict = NewDataDictionary($db);

     $taboptarray = array('mysql' => 'TYPE=MyISAM', 'REPLACE'); 

     $sqlarray = $dict->CreateTableSQL($table, $flds, $taboptarray);
     $dict->ExecuteSQLArray($sqlarray); 

     echo '<br><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $table . " " . MADE . '</font>';
   }
}


if (!function_exists('idxsql')) {
  function idxsql($idxname, $table, $idxflds) {
     GLOBAL $db;

     $dict = NewDataDictionary($db);

     $sqlarray = $dict->CreateIndexSQL($idxname, $table, $idxflds);
     $dict->ExecuteSQLArray($sqlarray);
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



$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (1, 'Deutsch', 'deu', 'de', 1, 1)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (2, 'English', 'eng', 'en', 1, 2)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (3, 'Nederlands', 'nld', 'nl', 1, 3)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (4, 'Polski', 'pol', 'pl', 0, 4)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (5, 'Russian', 'rus', 'ru', 0, 5)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");
$result = $db->Execute("INSERT INTO " . $prefix_table . "languages (languages_id, name, iso_639_2, iso_639_1, status, sort_order) VALUES (6, 'Spanish', 'spa', 'es', 0, 6)") OR die ("<b>".NOTUPDATED . $prefix_table . "languages</b>");


echo '<br /><img src="images/yes.gif" alt="" border="0" align="absmiddle"> <font class="oos-title">' . $prefix_table . "languages " . UPDATED .'</font>';


?>
