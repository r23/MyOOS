<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_products_viewed.php,v 1.27 2003/01/29 23:22:44 hpdl
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

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$rows = 0;

if (isset($_GET['action']) && ($_GET['action'] == 'reset')) {
    $products_descriptiontable = $oostable['products_description'];
    $reset_sql = "UPDATE $products_descriptiontable SET products_viewed = '0'";
    $dbconn->Execute($reset_sql);
    oos_redirect_admin(oos_href_link_admin($aContents['stats_products_viewed'], 'reset=1'));
}

if (isset($_GET['reset']) && ($_GET['reset'] == '1')) {
    $messageStack->add(TEXT_VIEWS_RESET, 'success');
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
                            <th align="center"><?php echo TABLE_HEADING_VIEWED; ?>&nbsp;</th>
                        </tr>    
                    </thead>    
<?php
if (isset($nPage) && ($nPage > 1)) {
    $rows = $nPage * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
}

$aDocument = [];

$productstable = $oostable['products'];
$products_dscriptiontable = $oostable['products_description'];
$languagestable = $oostable['languages'];
$products_sql_raw = "SELECT p.products_id, pd.products_name, pd.products_viewed, l.name
                       FROM $productstable p,
                            $products_dscriptiontable pd,
                            $languagestable l
                       WHERE p.products_id = pd.products_id
                         AND l.languages_id = pd.products_languages_id
                       ORDER BY pd.products_viewed DESC";
$products_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $products_sql_raw, $products_numrows);
$products_result = $dbconn->Execute($products_sql_raw);

while ($products = $products_result->fields) {
    $rows++;

    $aDocument[] = ['id' => $rows,
                    'link' => oos_href_link_admin($aContents['products'], 'action=new_product_preview&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $nPage)];
    echo '              <tr id="row-' . $rows .'">' . "\n";


    if (strlen($rows) < 2) {
        $rows = '0' . $rows;
    } ?>
                <td><?php echo $rows; ?>.</td>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'action=new_product_preview&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $nPage) . '">' . $products['products_name'] . '</a> (' . $products['name'] . ')'; ?></td>
                <td class="text-center"><?php echo $products['products_viewed']; ?>&nbsp;</td>
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
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo '<a href="' . oos_href_link_admin($aContents['stats_products_viewed'], "action=reset") . '">' . oos_button('reset') . '</a>'; ?></td>
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

if (isset($aDocument) || !empty($aDocument)) {
    echo '<script nonce="' . NONCE . '">' . "\n";
    $nDocument = is_countable($aDocument) ? count($aDocument) : 0;
    for ($i = 0, $n = $nDocument; $i < $n; $i++) {
        echo 'document.getElementById(\'row-'. $aDocument[$i]['id'] . '\').addEventListener(\'click\', function() { ' . "\n";
        echo 'document.location.href = "' . $aDocument[$i]['link'] . '";' . "\n";
        echo '});' . "\n";
    }
    echo '</script>' . "\n";
}

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
