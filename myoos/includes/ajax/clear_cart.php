<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

// start the session
if ($session->hasStarted() === false) {
    $session->start();
}


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shopping_cart.php';

if (isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid'])) {
    if (isset($_SESSION['cart']) && ($_SESSION['cart']->count_contents() > 0)) {
        $products = $_SESSION['cart']->get_products();

        $n = is_countable($products) ? count($products) : 0;
        for ($i = 0, $n; $i < $n; $i++) {
            $_SESSION['cart']->remove($products[$i]['id']);
        }
    }

    header("Content-Type: application/json");
    $contents = '<div class="container text-center m-py-60">
					<div class="mb-5">
						<span class="d-block g-color-gray-light-v1 fs-70 mb-4">
							<i class="fa fa-shopping-basket" aria-hidden="true"></i>
						</span>
						<h2 class="mb-30">' . $aLang['text_cart_empty'] . '</h2>
						<p>' . $aLang['text_cart_empty_help'] . '</p>
					</div>
					<a class="btn btn-primary fs-12 text-uppercase m-py-12 m-px-25" href="' . oos_href_link($aContents['home']) . '" role="button">' . $aLang['button_start_shopping'] . '</a>
				</div>';
    echo json_encode($contents, JSON_THROW_ON_ERROR);
}
