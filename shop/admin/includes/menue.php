<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

   
$orders_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['orders'] . " WHERE orders_status = '0'");
$orders = $orders_result->fields;
$reviews_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['reviews']);
$reviews = $reviews_result->fields;   
   
?>

<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header">
		<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
	</div>
	<ul class="nav navbar-top-links navbar-right">
		<li>
			<span class="m-r-sm text-muted welcome-message"><?php echo $aLang['header_title_top']; ?></span>
		</li>
		<li>
			<a class="count-info" href="<?php echo oos_admin_files_boxes('reviews', 'selected_box=catalog'); ?>">
				<i class="fa fa-comment"></i>  <span class="label label-warning"><?php echo $reviews['count']; ?></span>
			</a>
		</li>
		<li>
			<a class="count-info" href="<?php echo oos_admin_files_boxes('orders', 'selected_box=customers');  ?>">
				<i class="fa fa-bell"></i>  <span class="label label-primary"><?php echo $orders['count']; ?></span>
			</a>
		</li>
		<li>
			<a href="<?php echo oos_catalog_link($oosCatalogFilename['default']); ?>">
				<i class="fa fa-shopping-cart"></i><?php echo HEADER_TITLE_ONLINE_CATALOG; ?>
			</a>
		</li>
		<li>
			<a href="<?php echo oos_href_link_admin($aContents['logoff'], '', 'SSL'); ?>">
				<i class="fa fa-sign-out"></i><?php echo $aLang['header_title_logoff']; ?>
			</a>
		</li>
	</ul>
</nav>