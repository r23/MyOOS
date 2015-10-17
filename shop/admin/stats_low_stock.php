<?php
/* ----------------------------------------------------------------------
   $Id: stats_low_stock.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_low_stock.php,v 1.10 2002/04/12 23:13:45 Mounir Ayari  
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2001 - 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  require 'includes/header.php'; 
?>
<div id="wrapper">
	<?php require 'includes/blocks.php'; ?>
		<div id="page-wrapper" class="white-bg">
			<div class="row border-bottom">
			<?php require 'includes/menue.php'; ?>
			</div>

			<div class="wrapper wrapper-content">
				<div class="row">
					<div class="col-lg-12">
					
			<!-- Breadcrumbs  -->
			<div class="row wrapper page-heading">
				<div class="col-lg-10">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']); ?>">Home</a>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['stats_products_purchased'], 'selected_box=reports') . '">' . BOX_HEADING_REPORTS . '</a>'; ?>
						</li>
						<li class="active">
							<strong><?php echo HEADING_TITLE; ?></strong>
						</li>
					</ol>
				</div>
				<div class="col-lg-2">

				</div>
			</div><!--/ End Breadcrumbs -->
			
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_QTY_LEFT; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

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
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_result_raw, $products_result_numrows);
  $products_result = $dbconn->Execute($products_result_raw);
  while ($products = $products_result->fields) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
          <tr class="datatableRow" onmouseover="this.className='datatableRowOver';this.style.cursor='hand'" onmouseout="this.className='datatableRow'" onclick="document.location.href='<?php echo oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $_GET['page'], 'NONSSL'); ?>'">
            <td align="left" class="smallText">&nbsp;<?php echo $rows; ?>.&nbsp;</td>
            <td class="smallText">&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_low_stock'] . '?page=' . $_GET['page'], 'NONSSL') . '" class="blacklink">' . $products['products_name'] . '</a>'; ?>&nbsp;</td>
            <td align="right" class="smallText">&nbsp;<?php echo $products['products_quantity']; ?>&nbsp;</td>
          </tr>
<?php
    // Move that ADOdb pointer!
    $products_result->MoveNext();
  }

  // Close result set
  $products_result->Close();
?>
          </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<!-- body_text_eof //-->

				</div>
			</div>
        </div>

	</div>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>