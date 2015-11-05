<?php
/* ----------------------------------------------------------------------
   $Id: stats_products_viewed.php,v 1.1 2007/06/08 17:14:42 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2015 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: stats_products_viewed.php,v 1.27 2003/01/29 23:22:44 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

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
			<div class="row wrapper white-bg page-heading">
				<div class="col-lg-10">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
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
			</div><!-- END Breadcrumbs //-->
			
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">				
<!-- body_text //-->
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_VIEWED; ?>&nbsp;</td>
              </tr>
<?php
  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

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
  $products_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_sql_raw, $products_numrows);
  $products_result = $dbconn->Execute($products_sql_raw);
  while ($products = $products_result->fields) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $_GET['page'], 'NONSSL'); ?>'">
                <td class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '&origin=' . $aContents['stats_products_viewed'] . '?page=' . $_GET['page'], 'NONSSL') . '">' . $products['products_name'] . '</a> (' . $products['name'] . ')'; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products['products_viewed']; ?>&nbsp;</td>
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
                <td class="smallText" align="right"><?php echo $products_split->display_links($products_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo '<a href="' . oos_href_link_admin($aContents['stats_products_viewed'],"action=reset") . '">' . oos_button('reset', IMAGE_RESET) . '</a>'; ?></td>
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
	</section>
	<!-- Page footer //-->
	<footer>
		<span>&copy; 2015 - <a href="http://www.oos-shop.de/" target="_blank">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>