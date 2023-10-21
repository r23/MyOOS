<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_customers.php,v 1.29 2002/05/16 15:32:22 hpdl
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

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

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
                            <th><?php echo TABLE_HEADING_CUSTOMERS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_TOTAL_PURCHASED; ?>&nbsp;</th>
                        </tr>    
                    </thead>            
            
<?php
if (isset($nPage) && ($nPage > 1)) {
    $rows = $nPage * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
}
  $customerstable = $oostable['customers'];
$orders_productstable = $oostable['orders_products'];
$orderstable = $oostable['orders'];
$customers_sql_raw = "SELECT c.customers_firstname, c.customers_lastname,
                               sum(op.products_quantity * op.final_price) AS ordersum
                          FROM $customerstable c,
                               $orders_productstable op,
                               $orderstable o
                         WHERE c.customers_id = o.customers_id
                           AND o.orders_id = op.orders_id
                         GROUP BY c.customers_firstname, c.customers_lastname
                         ORDER BY ordersum DESC";
$customers_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $customers_sql_raw, $customers_result_numrows);
// fix counted customers
$orderstable = $oostable['orders'];
$customers_result_numrows = $dbconn->Execute(
    "SELECT customers_id
                                                FROM $orderstable
                                               GROUP BY customers_id"
);
$customers_result_numrows = $customers_result_numrows->RecordCount();

$customers_result = $dbconn->Execute($customers_sql_raw);
while ($customers = $customers_result->fields) {
    $rows++;

    if (strlen($rows) < 2) {
        $rows = '0' . $rows;
    } ?>
              <tr onclick="document.location.href='<?php echo oos_href_link_admin($aContents['customers'], 'search=' . $customers['customers_lastname']); ?>'">
                <td><?php echo $rows; ?>.</td>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['customers'], 'search=' . $customers['customers_lastname']) . '">' . $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . '</a>'; ?></td>
                <td class="text-right"><?php echo $currencies->format($customers['ordersum']); ?>&nbsp;</td>
              </tr>
      <?php
        // Move that ADOdb pointer!
        $customers_result->MoveNext();
}
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_CUSTOMERS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?>&nbsp;</td>
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
?>
<script nonce="<?php echo NONCE; ?>">
var form = document.getElementById('pages'); 
var select = document.getElementById('page'); 

select.addEventListener('change', function() { 
	form.submit(); 
});
</script>
<?php

require 'includes/nice_exit.php';
