<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

   
$orders_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['orders'] . " WHERE orders_status = '0'");
$orders = $orders_result->fields;
$reviews_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['reviews']);
$reviews = $reviews_result->fields;   
   
?>
<nav role="navigation" class="navbar topnavbar">
	<!-- Logo //-->
	<div class="navbar-header">
		<a href="<?php echo oos_href_link_admin($aContents['default']); ?>" class="navbar-brand">
			<div class="brand-logo">
				<img src="images/myoos.png" alt=" MyOOS [Shopsystem]" class="img-responsive">
			</div>
			<div class="brand-logo-collapsed">
				<img src="images/myoos-single.png" alt=" MyOOS [Shopsystem]" class="img-responsive">
			</div>
		</a>
	</div>
	<!-- End Logo //-->
	<div class="nav-wrapper">
		<ul class="nav navbar-nav">
			<li>
				<a href="#" data-toggle-state="aside-collapsed" class="hidden-xs">
					<i class="fa fa-navicon"></i>
				</a>
				<a href="#" data-toggle-state="aside-toggled" data-no-persist="true" class="visible-xs sidebar-toggle">
					<i class="fa fa-navicon"></i>
				</a>
			</li>
		</ul>
		<!-- Right Navbar //-->
		<ul class="nav navbar-nav navbar-right">
			<!-- Fullscreen (only desktops) //-->
			<li class="visible-lg">
				<a href="#" data-toggle-fullscreen="">
					<i class="fa fa-expand"></i>
				</a>
			</li>
			<li>
				<a href="<?php echo oos_href_link_admin('reviews', 'selected_box=catalog'); ?>">
					<i class="fa fa-comment"></i><span class="label label-warning"><?php echo $reviews['count']; ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo oos_href_link_admin('orders', 'selected_box=customers'); ?>">
					<i class="fa fa-bell"></i><span class="label label-primary"><?php echo $orders['count']; ?></span>
				</a>
			</li>			
			<li>
				<a href="<?php echo oos_catalog_link($aCatalog['default']); ?>">
					<i class="fa fa-shopping-cart"></i><?php echo HEADER_TITLE_ONLINE_CATALOG; ?>
				</a>
			</li>
			<li>
				<a href="<?php echo oos_href_link_admin($aContents['logoff']); ?>">
					<i class="fa fa-sign-out"></i><?php echo $aLang['header_title_logoff']; ?>
				</a>
			</li>			
		</ul>
	</div>
</nav>
<!-- End Top Navbar //-->
