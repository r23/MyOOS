<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: gv_mail.php,v 1.3.2.4 2003/05/12 22:54:01 wilt
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2002 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');

require 'includes/main.php';
require 'includes/functions/function_coupon.php';
require 'includes/classes/class_currencies.php';

$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'export':
        # oos_redirect_admin(oos_href_link_admin($aContents['cart_cancelling'], 'page=' . $nPage));
        break;

    case 'deleteconfirm':
        break;
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
							<th><?php echo TABLE_HEADING_ZONE; ?></th>
							<th><?php echo TABLE_HEADING_TAX_CLASS_TITLE; ?></th>
							<th><?php echo TABLE_HEADING_TAX_RATE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>	
<?php

$rows = 0;
$aDocument = [];

$tax_classtable = $oostable['tax_class'];
$tax_ratestable = $oostable['tax_rates'];
$geo_zonestable = $oostable['geo_zones'];
$rates_result_raw = "SELECT r.tax_rates_id, z.geo_zone_id, z.geo_zone_name, tc.tax_class_title, tc.tax_class_id,
                              r.tax_rate, r.tax_description, r.date_added, r.last_modified
                      FROM $tax_classtable tc,
                           $tax_ratestable r LEFT JOIN
                           $geo_zonestable z
                       ON r.tax_zone_id = z.geo_zone_id
                      WHERE r.tax_class_id = tc.tax_class_id
					  ORDER BY z.geo_zone_name, tc.tax_class_title";
$rates_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $rates_result_raw, $rates_result_numrows);
$rates_result = $dbconn->Execute($rates_result_raw);
while ($rates = $rates_result->fields) {
    $rows++;
    if ((!isset($_GET['tID']) || (isset($_GET['tID']) && ($_GET['tID'] == $rates['tax_rates_id']))) && !isset($trInfo) && (!str_starts_with((string) $action, 'new'))) {
        $trInfo = new objectInfo($rates);
    }

    if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id'] == $trInfo->tax_rates_id)) {
        $aDocument[] = ['id' => $rows,
                        'link' => oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $trInfo->tax_rates_id . '&action=edit') ];
        echo '              <tr id="row-' . $rows .'">' . "\n";
    } else {
        $aDocument[] = ['id' => $rows,
                        'link' => oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $rates['tax_rates_id'])];
        echo '              <tr id="row-' . $rows .'">' . "\n";
    } ?>
				<td><?php echo $rates['geo_zone_name']; ?></td>
                <td><?php echo $rates['tax_class_title']; ?></td>
                <td><?php echo oos_display_tax_value($rates['tax_rate']); ?> %</td>
                <td class="text-right"><?php if (isset($trInfo) && is_object($trInfo) && ($rates['tax_rates_id'] == $trInfo->tax_rates_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $rates['tax_rates_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $rates_result->MoveNext();
}
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $rates_split->display_count($rates_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_TAX_RATES); ?></td>
                    <td class="smallText" align="right"><?php echo $rates_split->display_links($rates_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
   if ($action == 'default') {
       ?>
                  <tr>
                    <td colspan="4" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_TAX_RATE) . '</a>'; ?></td>
                  </tr>
<?php
   }
?>
                </table></td>
              </tr>
            </table></td>
<?php

$heading = [];
$contents = [];

switch ($action) {
    case 'delete':
        $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_TAX_RATE . '</b>'];

        $contents = ['form' => oos_draw_form('id', 'rates', $aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $trInfo->tax_rates_id  . '&action=deleteconfirm', 'post', false)];
        $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
        $contents[] = ['text' => '<br><b>' . $trInfo->tax_class_title . ' ' . number_format($trInfo->tax_rate, TAX_DECIMAL_PLACES) . '%</b>'];
        $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $trInfo->tax_rates_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
        break;

    default:
        if (isset($trInfo) && is_object($trInfo)) {
            $heading[] = ['text' => '<b>' . $trInfo->tax_class_title . '</b>'];
            $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $trInfo->tax_rates_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['tax_rates'], 'page=' . $nPage . '&tID=' . $trInfo->tax_rates_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
            $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($trInfo->date_added)];
            $contents[] = ['text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($trInfo->last_modified)];
            $contents[] = ['text' => '<br>' . TEXT_INFO_RATE_DESCRIPTION . '<br>' . $trInfo->tax_description];
        }
        break;
}

if ((oos_is_not_null($heading)) && (oos_is_not_null($contents))) {
    ?>
	<td class="w-25" valign="top">
		<table class="table table-striped">
<?php
    $box = new box();
    echo $box->infoBox($heading, $contents); ?>
		</table> 
	</td> 
<?php
}
?>
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
