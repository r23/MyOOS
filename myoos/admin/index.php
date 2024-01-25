<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: index.php,v 1.17 2003/02/14 12:57:29 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$languages = oos_get_languages();
$languages_array = [];
$languages_selected = DEFAULT_LANGUAGE;
for ($i = 0, $n = is_countable($languages) ? count($languages) : 0; $i < $n; $i++) {
    $languages_array[] = ['id' => $languages[$i]['iso_639_2'], 'text' => $languages[$i]['name']];
    if ($languages[$i]['iso_639_2'] == $_SESSION['language']) {
        $languages_selected = $languages[$i]['iso_639_2'];
    }
}

require 'includes/header.php';
?>
<div class="wrapper">
    <!-- Header //-->
    <header class="topnavbar-wrapper">
        <!-- Top Navbar //-->
        <?php require 'includes/menue.php'; ?>
    </header>
    <!-- END Header //-->
    <aside class="aside">
        <!-- Sidebar //-->
        <div class="aside-inner">
            <?php require 'includes/blocks.php'; ?>
        </div>
        <!-- END Sidebar (left) //-->
    </aside>
    
    <!-- Main section //-->
    <section>
        <!-- Page content //-->
        <div class="content-wrapper">
            <div class="row  justify-content-between mb-5">            

                <div class="col-xl-3 col-lg-6 col-md-12">
                <!-- Breadcrumbs //-->
                    <div class="content-heading">
                        <h2><?php echo HEADING_TITLE; ?></h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item active">
                                <strong><?php echo HEADER_TITLE_TOP; ?></strong>
                            </li>
                        </ol>
                    </div>
                    <!-- END Breadcrumbs //-->
                </div>
                <div class="col-xl-3 col-lg-6 col-md-12">
                    <div class="card flex-row align-items-center align-items-stretch border-0">
                        <div class="col-4 d-flex align-items-center bg-info-dark justify-content-center rounded-left">
                            <em class="fa fa-language fa-3x"></em>
                        </div>
                        <div class="col-8 py-4 bg-info justify-content-center rounded-right">
                            <div class="text-center">
                                <?php echo oos_draw_form('id', 'languages', 'index.php', '', 'get', false); ?>
                                    <?php echo oos_draw_pull_down_menu('language', 'language-select', $languages_array, $languages_selected); ?> 
                                </form>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">


<?php
    $customers_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['customers']);
$customers = $customers_result->fields;

$products_result = $dbconn->Execute("SELECT COUNT(*) AS count FROM " . $oostable['products'] . " WHERE products_status >= '1'");
$products = $products_result->fields;

?>
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card flex-row align-items-center align-items-stretch border-0">
                                <div class="col-4 d-flex align-items-center bg-primary-dark card-title justify-content-center rounded-left">
                                    <a href="<?php echo oos_href_link_admin($aContents['customers'], 'selected_box=customers'); ?>"><em class="fas fa-users fa-3x"></em></a>
                                </div>
                                <div class="col-8 py-3 card-body bg-primary rounded-right">
                                    <div class="h2 mt-0"><?php echo $customers['count']; ?></div>
                                    <div class="text-uppercase"><a href="<?php echo oos_href_link_admin($aContents['customers'], 'selected_box=customers'); ?>"><?php echo BOX_ENTRY_CUSTOMERS; ?></a></div>
                                </div>
                            </div>
                        </div>        
                        
                        <div class="col-xl-3 col-md-6">
                            <div class="card flex-row align-items-center align-items-stretch border-0">
                                <div class="col-4 d-flex align-items-center bg-purple-dark card-title justify-content-center rounded-left">
                                    <a href="<?php echo oos_href_link_admin($aContents['categories'], 'selected_box=catalog'); ?>"><em class="fa fa-cubes fa-3x"></em></a>
                                </div>
                                <div class="col-8 py-3 card-body bg-purple rounded-right">
                                    <div class="h2 mt-0"><?php echo $products['count']; ?></div>
                                    <div class="text-uppercase"><a href="<?php echo oos_href_link_admin($aContents['categories'], 'selected_box=catalog'); ?>"><?php echo BOX_ENTRY_PRODUCTS; ?></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card flex-row align-items-center align-items-stretch border-0">
                                <div class="col-4 d-flex align-items-center bg-green-dark card-title justify-content-center rounded-left">
                                    <a href="<?php echo oos_href_link_admin($aContents['reviews'], 'selected_box=catalog'); ?>"><em class="far fa-comments fa-3x"></em></a>
                                </div>
                                <div class="col-8 py-3 card-body bg-green rounded-right">
                                    <div class="h2 mt-0"><?php echo $reviews['count']; ?></div>
                                    <div class="text-uppercase"><a href="<?php echo oos_href_link_admin($aContents['reviews'], 'selected_box=catalog'); ?>"><?php echo BOX_ENTRY_REVIEWS; ?></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-md-12">
                            <div class="card flex-row align-items-center align-items-stretch border-0">
                                <div class="col-4 d-flex align-items-center bg-danger-dark card-title justify-content-center rounded-left">
                                    <a href="<?php echo oos_href_link_admin($aContents['orders'], 'selected_box=customers'); ?>"><em class="fa fa-shopping-cart fa-3x"></em></a>
                                </div>
                                <div class="col-8 py-3 card-body bg-danger rounded-right">
                                    <div class="h2 mt-0"><?php echo $orders['count']; ?></div>
                                    <div class="text-uppercase"><a href="<?php echo oos_href_link_admin($aContents['orders'], 'selected_box=customers'); ?>"><?php echo BOX_TITLE_ORDERS; ?></a></div>
                                </div>
                            </div>
                        </div>
                    </div>                
<!-- body_text //-->
                    <div class="row">
                        <div class="col-sm-3 col-md-6 col-lg-4">....</div>
                        <div class="col-sm-3 col-md-6 col-lg-4">....</div>
                        <div class="col-sm-3 col-md-6 col-lg-4">....</div>        
                    </div>
<!-- body_text_eof //-->
                </div>
            </div>
        </div>

        </div>
    </section>
    <!-- Page footer //-->
    <footer>
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>

<?php
    require 'includes/bottom.php';
?>
<script nonce="<?php echo NONCE; ?>">
// Add an event listener to the select element
document.getElementById('language-select').addEventListener('change', function() { 
    // Submit the form 
    this.form.submit(); 
}); 
</script>
<?php
    require 'includes/nice_exit.php';
?>