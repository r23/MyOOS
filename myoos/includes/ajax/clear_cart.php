<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2021 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

/** ensure this file is being included by a parent file */
defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

// start the session
if ( $session->hasStarted() === false ) $session->start();


require_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/shopping_cart.php';
  
if ( isset($_SESSION['formid']) && ($_SESSION['formid'] == $_POST['formid']) ) {

	if (isset($_SESSION['cart']) && ($_SESSION['cart']->count_contents() > 0)) {
		$products = $_SESSION['cart']->get_products();

		$n = count($products);
		for ($i=0, $n; $i<$n; $i++) {			
			$_SESSION['cart']->remove($products[$i]['id']);
		}
	}

	$contents = '<div class="text-right"></div>		
			<div class="widget widget-featured-entries pt-3">
			
												
					<div class="media"><!-- cart item -->
						<div class="featured-entry-thumb mr-3"><a href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=product_info&amp;products_id=1&amp;PHOENIXSID=5qfbbq7ujvinr6j6feirhcvk7i">
												
							</a><span class="item-remove-btn"><i data-feather="x"></i></span></div>
						<div class="media-body">
							<h6 class="featured-entry-title"><a href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=product_info&amp;products_id=1&amp;PHOENIXSID=5qfbbq7ujvinr6j6feirhcvk7i">T-Shirt Art Artikelname:</a></h6>
							<p class="featured-entry-meta">1&nbsp; <span class="text-muted">x</span> 0,00 €</p>
						</div>
					</div>	<!-- /cart item -->																
				
				<hr>
				<div class="d-flex justify-content-between align-items-center py-3">
				<div class="font-size-sm"> <span class="mr-2">Summe:</span><span class="font-weight-semibold text-dark">0,00 €</span></div><a class="btn btn-outline-secondary btn-sm" href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=shopping_cart&amp;PHOENIXSID=5qfbbq7ujvinr6j6feirhcvk7i">Warenkorb<i class="mr-n2" data-feather="chevron-right"></i></a>
				</div><a class="btn btn-primary btn-sm btn-block" href="http://localhost/ent/MyOOS/MyOOS/myoos/index.php?content=checkout_shipping&amp;PHOENIXSID=5qfbbq7ujvinr6j6feirhcvk7i"><i class="mr-1" data-feather="credit-card"></i>Kasse</a>			</div>

			';
	echo json_encode($contents); 

}

