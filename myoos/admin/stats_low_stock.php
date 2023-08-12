<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_low_stock.php,v 1.10 2002/04/12 23:13:45 Mounir Ayari
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$rows = 0;


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
                    
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['stats_products_purchased'], 'selected_box=reports') . '">' . BOX_HEADING_REPORTS . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong><?php echo HEADING_TITLE; ?></strong>
                        </li>
                    </ol>
                </div>
            </div>
            <!-- END Breadcrumbs //-->
            
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12">                
<!-- body_text //-->
    <div class="table-responsive">
        <table class="table w-100">
          <tr>
            <td valign="top">
            
            
                <table class="table table-striped table-hover w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_NUMBER; ?></th>
                            <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_QTY_LEFT; ?>&nbsp;</th>
                        </tr>    
                    </thead>    
<?php
if (isset($nPage) && ($nPage > 1)) {
    $rows = $nPage * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
}

  $productstable = $oostable['products'];
  $products_descriptiontable = $oostable['products_description'];
  $products_result_raw = "SELECT p.products_id, p.products_quantity, p.products_reorder_level, pd.products_name
                            FROM $productstable p,
                                 $products_descriptiontable pd
                           WHERE p.products_id = pd.products_id
                             AND pd.products_languages_id = '" . intval($_SESSION['language_id']). "'
                             AND p.products_quantity <= p.products_reorder_level
                           GROUP BY pd.products_id
                           ORDER BY pd.products_name ASC";
  $products_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $products_result_raw, $products_result_numrows);
  $products_result = $dbconn->Execute($products_result_raw);
while ($products = $products_result->fields) {
    $rows++;

    if (strlen($rows) < 2) {
        $rows = '0' . $rows;
    } ?>
          <tr onclick="document.location.href='<?php echo oos_href_link_admin($aContents['products'], 'action=new_product_preview&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $nPage); ?>'">
            <td align="left" class="smallText">&nbsp;<?php echo $rows; ?>.&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'action=new_product_preview&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_low_stock'] . '?page=' . $nPage) . '" class="blacklink">' . $products['products_name'] . '</a>'; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<?php echo $products['products_quantity']; ?>&nbsp;</td>
          </tr>
    <?php
    // Move that ADOdb pointer!
    $products_result->MoveNext();
}
?>
          </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table>
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
    require 'includes/nice_exit.php';
?>