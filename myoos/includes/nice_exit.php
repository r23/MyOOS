<?php
/**
   ----------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: application_bottom.php,v 1.14 2003/02/10 22:30:41 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions

   http://www.oscommerce.com
   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

if ($debug == 1) {
    echo '<pre>';
    print_r($_SESSION);
    echo '<br />';
    print_r($_COOKIE);
    echo '<br />';
    print_r($_GET);
    echo '<br />';
    print_r($_POST);
    echo '<br />';
    echo '</pre>';

    // echo "<p><pre>" . var_export($oObject, TRUE). "</pre></p>";
}


if (isset($_SESSION)) {
    // shopping_cart
    if (isset($_SESSION['new_products_id_in_cart'])) {
        unset($_SESSION['new_products_id_in_cart']);
    }
    if (isset($_SESSION['guest_login'])) {
        unset($_SESSION['guest_login']);
    }
}
