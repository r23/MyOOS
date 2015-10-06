<?php
/* ----------------------------------------------------------------------
   $Id: stats_products_purchased.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_products_purchased.php,v 1.27 2002/11/18 15:10:23 project3000 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
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
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_PURCHASED; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

  $productstable = $oostable['products'];
  $products_dscriptiontable = $oostable['products_description'];
  $products_sql_raw = "SELECT p.products_id, p.products_ordered, pd.products_name
                         FROM $productstable p,
                              $products_dscriptiontable pd
                        WHERE pd.products_id = p.products_id
                          AND pd.products_languages_id = '" . intval($_SESSION['language_id']). "'
                          AND p.products_ordered > 0
                        GROUP BY pd.products_id
                        ORDER BY p.products_ordered DESC, pd.products_name";
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_sql_raw, $products_numrows);
  $products_result = $dbconn->Execute($products_sql_raw);
  while ($products = $products_result->fields) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_purchased'] . '?page=' . $_GET['page'], 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_purchased'] . '?page=' . $_GET['page'], 'NONSSL') . '">' . $products['products_name'] . '</a>'; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_ordered']; ?>&nbsp;</td>
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
                <td class="smallText" valign="top"><?php echo $products_split->display_count($products_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?>&nbsp;</td>
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