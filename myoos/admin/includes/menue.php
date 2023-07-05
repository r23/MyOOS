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


$orders_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['orders'] . " WHERE orders_status = '1'");
$orders = $orders_result->fields;

$reviews_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['reviews']  . " WHERE reviews_status = '0'");
$reviews = $reviews_result->fields;

?>
<nav class="navbar topnavbar" role="navigation">
    <!-- logo //-->
    <div class="navbar-header">
        <a href="<?php echo oos_href_link_admin($aContents['default']); ?>" class="navbar-brand">
            <div class="brand-logo">
                <img src="images/myoos.png" alt="MyOOS [Shopsystem]" class="img-fluid">
            </div>
            <div class="brand-logo-collapsed">
                <img src="images/myoos-single.png" alt="MyOOS [Shopsystem]" class="img-fluid">
            </div>
        </a>
    </div>
    <!-- end logo //-->
    <!-- start left navbar //-->
    <ul class="navbar-nav mr-auto flex-row">
        <li class="nav-item">
            <a class="nav-link d-none d-md-block d-lg-block d-xl-block" href="#" data-trigger-resize="" data-toggle-state="aside-collapsed">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </a>
            <a class="nav-link sidebar-toggle d-md-none" href="#" data-toggle-state="aside-toggled" data-no-persist="true">
                <i class="fas fa-bars" aria-hidden="true"></i>
            </a>
        </li>
    </ul>
    <!-- end left navbar //-->
    <!-- start right navbar //-->
    <ul class="navbar-nav flex-row">
        <!-- Fullscreen (only desktops) //-->
        <li class="nav-item d-none d-md-block">
            <a class="nav-link" href="#" data-toggle-fullscreen="">
                <i class="fas fa-expand" aria-hidden="true"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo oos_href_link_admin($aContents['reviews'], 'selected_box=catalog'); ?>">
                <i class="fas fa-comment" aria-hidden="true"></i>
                <span class="badge badge-danger"><?php echo $reviews['count']; ?></span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo oos_href_link_admin($aContents['orders'], 'selected_box=customers'); ?>">
             <i class="fas fa-bell" aria-hidden="true"></i>
                <span class="badge badge-danger"><?php echo $orders['count']; ?></span>
            </a>
        </li>            
        <li class="nav-item">
            <a class="nav-link" href="<?php echo oos_catalog_link($aCatalog['default']); ?>">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i> <?php echo HEADER_TITLE_ONLINE_CATALOG; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo oos_href_link_admin($aContents['logoff']); ?>">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> <?php echo $aLang['header_title_logoff']; ?>
            </a>
        </li>            
    </ul>
</nav>
<!-- end top navbar //-->
