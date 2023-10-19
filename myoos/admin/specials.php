<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: specials.php,v 1.38 2002/05/16 15:32:22 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


/**
 * Sets the status of a special
 *
 * @param $specials_id
 * @param $status
 */
function oos_set_specials_status($specials_id, $status)
{

    // Get database information
    $dbconn = & oosDBGetConn();
    $oostable = & oosDBGetTables();

    if ($status == '1') {
        $specialstable = $oostable['specials'];
        return $dbconn->Execute("UPDATE $specialstable SET status = '1', expires_date = NULL, date_status_change = NULL WHERE specials_id = '" . intval($specials_id) . "'");
    } elseif ($status == '0') {
        $specialstable = $oostable['specials'];
        return $dbconn->Execute("UPDATE $specialstable SET status = '0', date_status_change = now() WHERE specials_id = '" . intval($specials_id) . "'");
    } else {
        return false;
    }
}

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$sID = filter_input(INPUT_GET, 'sID', FILTER_VALIDATE_INT);
$pID = filter_input(INPUT_GET, 'pID', FILTER_VALIDATE_INT);

switch ($action) {
    case 'setflag':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            oos_set_specials_status($_GET['id'], $_GET['flag']);
        }

        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'sID=' . intval($_GET['id']) . '&page=' . intval($nPage)));
        break;

    case 'insert':
        if (isset($_POST['products_id']) && is_numeric($_POST['products_id'])) {
            $bError = false; // reset error flag

            $products_id = oos_db_prepare_input($_POST['products_id']);
            $specials_price = oos_db_prepare_input($_POST['specials_price']);
            $expires_date = oos_db_prepare_input($_POST['expires_date']);

            if (strlen((string) $expires_date) < 6) {
                $bError = true;
                $messageStack->add_session(TEXT_EXPIRES_DATE_ERROR, 'error');
            }

            $products_price_historytable = $oostable['products_price_history'];
            $sql = "SELECT min(products_price) as history_price
						FROM $products_price_historytable
						WHERE products_id = '" . intval($products_id) . "'
						AND date_added >= DATE_SUB(NOW(),INTERVAL 30 DAY)";
            $history_price_result = $dbconn->Execute($sql);
            if ($history_price_result->RecordCount()) {
                $productstable = $oostable['products'];
                $product_info_sql = "SELECT products_price as history_price
										FROM $productstable
										WHERE products_id = '" . intval($products_id) . "'";
                $product_info_result = $dbconn->Execute($product_info_sql);
                $price = $product_info_result->fields;
            } else {
                $price = $history_price_result->fields;
            }

            // Check 30 Day
            /*
            $productstable = $oostable['products'];
            $product_check_sql = "SELECT products_status
                            FROM $productstable
                            WHERE products_id = '" . intval($products_id) . "'
                            AND products_date_added <= DATE_SUB(NOW(),INTERVAL 30 DAY)";
            $product_check_result = $dbconn->Execute($product_check_sql);
            if (!$product_check_result->RecordCount()) {
                $bError = true;
            }
            */

            if (str_ends_with((string) $_POST['specials_price'], '%')) {
                $productstable = $oostable['products'];
                $new_special_insert_result = $dbconn->Execute("SELECT products_id, products_price FROM $productstable WHERE products_id = '" . intval($products_id) . "'");
                $new_special_insert = $new_special_insert_result->fields;

                $products_price = $new_special_insert['products_price'];
                $specials_price = ($products_price - (($specials_price / 100) * $products_price));
            }

            $old_products_price = $price['history_price'];

            if ($old_products_price < $specials_price) {
                $old_products_price = '';
                $messageStack->add_session(TEXT_PRICE_ERROR, 'error');
            }


            if ($bError == false) {
                // insert a product on special
                $dbconn->Execute("INSERT INTO " . $oostable['specials'] . " (products_id, specials_new_products_price, specials_cross_out_price, specials_date_added, expires_date, status) VALUES ('" . intval($products_id) . "', '" . oos_db_input($specials_price) . "', '" . oos_db_input($old_products_price) . "', now(), '" . oos_db_input($expires_date) . "', '1')");
                $sID = $dbconn->Insert_ID();

                // product price history
                $sql_price_array = ['products_id' => intval($products_id), 'products_price' => oos_db_input($specials_price), 'date_added' => 'now()'];
                oos_db_perform($oostable['products_price_history'], $sql_price_array);

                oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID='. intval($sID)));
            } else {
                oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&pID='. intval($products_id)  . '&action=new'));
            }
        }
        break;

    case 'update':
        $specials_id = oos_db_prepare_input($_POST['specials_id']);
        $products_price = oos_db_prepare_input($_POST['products_price']);
        $specials_price = oos_db_prepare_input($_POST['specials_price']);
        $expires_date = oos_db_prepare_input($_POST['expires_date']);

        if (str_ends_with((string) $specials_price, '%')) {
            $specials_price = ($products_price - (($specials_price / 100) * $products_price));
        }

        $dbconn->Execute("UPDATE " . $oostable['specials'] . " SET specials_new_products_price = '" . oos_db_input($specials_price) . "', specials_last_modified = now(), expires_date = '" . oos_db_input($expires_date) . "', date_status_change = now(), status = 1 WHERE specials_id = '" .intval($specials_id) . "'");

        $specialstable = $oostable['specials'];
        $query = "SELECT products_id FROM $specialstable WHERE specials_id = '" . intval($specials_id) . "'";
        $products_id = $dbconn->GetOne($query);

        // product price history
        $sql_price_array = ['products_id' => intval($products_id), 'products_price' => oos_db_input($specials_price), 'date_added' => 'now()'];
        oos_db_perform($oostable['products_price_history'], $sql_price_array);


        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . intval($specials_id)));
        break;

    case 'deleteconfirm':
        $specials_id = oos_db_prepare_input($_GET['sID']);

        $specialstable = $oostable['specials'];
        $dbconn->Execute("DELETE FROM $specialstable WHERE specials_id = '" . oos_db_input($specials_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage)));
        break;
}


if (($action == 'new') || ($action == 'edit')) {
    $form_action = 'insert';
    if (($action == 'edit') && isset($_GET['sID'])) {
        $form_action = 'update';

        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $specialstable = $oostable['specials'];
        $sql = "SELECT p.products_tax_class_id, p.products_id, p.products_image, pd.products_name,
					p.products_price, s.specials_new_products_price, s.expires_date
              FROM $productstable p,
                   $products_descriptiontable pd,
                   $specialstable s
              WHERE p.products_id = pd.products_id AND
                  pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                  p.products_id = s.products_id AND
                  s.specials_id = '" . intval($sID) . "'";
        $product = $dbconn->GetRow($sql);

        $sInfo = new objectInfo($product);
    } elseif (($action == 'new') && isset($pID) && is_numeric($pID)) {
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql = "SELECT p.products_tax_class_id, p.products_id, p.products_image, pd.products_name, p.products_price
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    p.products_id = '" . intval($pID) . "'";
        $product = $dbconn->GetRow($sql);

        $sInfo = new objectInfo($product);
    } else {
        $sInfo = new objectInfo([]);

        $specials_array = [];
        $productstable = $oostable['products'];
        $specialstable = $oostable['specials'];
        $specials_result = $dbconn->Execute("SELECT p.products_id FROM $productstable p, $specialstable s WHERE s.products_id = p.products_id");
        while ($specials = $specials_result->fields) {
            $specials_array[] = $specials['products_id'];

            // Move that ADOdb pointer!
            $specials_result->MoveNext();
        }
    }

    /*
        if (isset($sInfo->products_id)) {
            // Check 30 Day
            $productstable = $oostable['products'];
            $product_check_sql = "SELECT products_status
                            FROM $productstable
                            WHERE products_id = '" . intval($sInfo->products_id) . "'
                              AND products_date_added <= DATE_SUB(NOW(),INTERVAL 30 DAY)";
            $product_check_result = $dbconn->Execute($product_check_sql);
            if (!$product_check_result->RecordCount()) {
                $price = '';
                $messageStack->add(TEXT_PRODUCT_ERROR, 'error');
            }
        }
    */
}


require 'includes/header.php';

?>
<!-- body //-->
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
							<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
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
<?php

if (($action == 'new') || ($action == 'edit')) {
    ?>
<!-- body_text //-->
	<div class="card card-default">
		<div class="card-header"><?php echo HEADING_TITLE; ?></div>
			<div class="card-body">

				<form name="new_special" <?php echo 'action="' . oos_href_link_admin($aContents['specials'], oos_get_all_get_params(['action', 'info', 'sID']) . 'action=' . $form_action) . '"'; ?> method="post">
<?php
    if ($form_action == 'update') {
        echo oos_draw_hidden_field('specials_id', intval($sID));
        echo oos_draw_hidden_field('products_price', ($sInfo->products_price ?? ''));
    }

    if (isset($sInfo->products_id)) {
        echo oos_draw_hidden_field('products_id', intval($sInfo->products_id));
    }

    if (!empty($sInfo->products_name)) {
        echo '<br><a href="' . oos_catalog_link($aCatalog['product_info'], 'products_id=' . $sInfo->products_id) . '" target="_blank" rel="noopener">' . product_info_image($sInfo->products_image, $sInfo->products_name) . '</a><br>';

        $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . intval($sInfo->products_tax_class_id) . "' ");
        $tax = $tax_result->fields;

        $in_price_netto = $sInfo->products_price;
        $in_price = ($in_price_netto * ($tax['tax_rate'] + 100) / 100);
        $in_price_netto = oos_round($in_price_netto, TAX_DECIMAL_PLACES);
        $in_price = oos_round($in_price, TAX_DECIMAL_PLACES);

        echo $sInfo->products_name;
        echo '<br>' . TEXT_INFO_ORIGINAL_PRICE . ' ' . $currencies->format($in_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_price_netto);
        if (isset($sInfo->specials_new_products_price)) {
            $in_new_price_netto = $sInfo->specials_new_products_price;
            $in_new_price = ($in_new_price_netto * ($tax['tax_rate'] + 100) / 100);
            $in_new_price_netto = oos_round($in_new_price_netto, TAX_DECIMAL_PLACES);
            $in_new_price = oos_round($in_new_price, TAX_DECIMAL_PLACES);
            echo '<br>' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($in_new_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_new_price_netto);
            echo '<br>' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / $sInfo->products_price) * 100)) . '%';
        }

        echo "\n";
        echo '<script nonce="' . NONCE  . '">' . "\n";
        echo 'let taxRate = ' . $tax['tax_rate'] . ';' . "\n"; ?>
function doRound(x, places) {
  num = Math.round(x * Math.pow(10, places)) / Math.pow(10, places);
  return num.toFixed(places);    
}


function updateWithTax() {
  let grossValue = document.forms["new_special"].specials_price.value;
  
  if (taxRate > 0) {
    grossValue = grossValue * ((taxRate / 100) + 1);
  }

  document.forms["new_special"].specials_price_gross.value = doRound(grossValue, 2);
}

function updateNet() {
  let netValue = document.forms["new_special"].specials_price_gross.value;
  
  if (taxRate > 0) {
    netValue = netValue / ((taxRate / 100) + 1);
  }

  document.forms["new_special"].specials_price.value = doRound(netValue, 2);
}


</script>
<?php
    } else {
        ?>
                     <fieldset>
                        <div class="form-group row mb-2 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_SPECIALS_PRODUCT; ?></label>
                           <div class="col-md-10">
								<?php echo oos_draw_products_pull_down('products_id', $specials_array); ?>
                           </div>
                        </div>
                     </fieldset>
<?php
    } ?>				 
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_SPECIALS_SPECIAL_PRICE; ?></label>
                              <div class="col-lg-10">
                                <?php

    if (isset($sInfo->specials_new_products_price)) {
        $sPrice = number_format($sInfo->specials_new_products_price, TAX_DECIMAL_PLACES, '.', '');
        echo oos_draw_input_field('specials_price', $sPrice, 'onkeyup="updateWithTax()"');
    } else {
        echo oos_draw_input_field('specials_price', '', 'onkeyup="updateWithTax()"');
    } ?>						
                              </div>
                           </div>
                        </fieldset>
          <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_SPECIALS_SPECIAL_PRICE_WITH_TAX; ?></label>
                              <div class="col-lg-10">
                                <?php
    if (isset($sInfo->specials_new_products_price)) {
        echo oos_draw_input_field('specials_price_gross', $in_new_price, 'onkeyup="updateNet()"');
    } else {
        echo oos_draw_input_field('specials_price_gross', '', 'onkeyup="updateNet()"');
    } ?>
                              </div>
                           </div>
                        </fieldset>					 				 
                     <fieldset>
                        <div class="form-group row mb-2">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_SPECIALS_EXPIRES_DATE; ?></label>
                           <div class="col-xl-6 col-10">
                              <div class="input-group date" id="datetimepicker1">
                                 <input class="form-control" type="text" name="expires_date" value="<?php echo($sInfo->expires_date ?? ''); ?>">
                                 <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </fieldset>
<?php
    if (isset($price['history_price'])) {
        $cross_out_price = $currencies->format(($price['history_price'] * ($tax['tax_rate'] + 100) / 100)) . ' - ' . TEXT_TAX_INFO . $currencies->format($price['history_price']); ?>
                     <fieldset>
                        <div class="form-group row mb-2">
                           <label class="col-md-2 col-form-label" for="input-id-1"><?php echo TEXT_SPECIALS_CROSS_OUT_PRICE; ?></label>
                           <div class="col-md-10">
                              <?php echo oos_draw_input_field('cross_out_price', $cross_out_price, '', false, 'text'); ?> 
                           </div>
                        </div>
                     </fieldset>
<?php
    } ?>					 
		<div class="text-md-left mt-3">
			<p><?php echo TEXT_SPECIALS_PRICE_TIP; ?></p>
		</div>
	
		<div class="text-right mt-3">
			<?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . intval($sID)) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?>
			<?php echo(($form_action == 'insert') ? oos_submit_button(BUTTON_INSERT) : oos_submit_button(BUTTON_UPDATE)); ?>
		</div>
					
			</form>	
		</div>
	</div>
	  
<?php
} else {
    ?>
	<div class="table-responsive">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">	
			
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_PRODUCTS_PRICE; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $productstable = $oostable['products'];
    $products_descriptiontable = $oostable['products_description'];
    $specialstable = $oostable['specials'];
    $specials_sql_raw = "SELECT p.products_tax_class_id, p.products_id, pd.products_name, s.specials_id, 
								s.specials_new_products_price, s.specials_cross_out_price, s.specials_date_added,
                               s.specials_last_modified, s.expires_date, s.date_status_change, s.status
                           FROM $productstable p,
                                $specialstable s,
                                $products_descriptiontable pd
                           WHERE p.products_id = pd.products_id AND
                                 pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                                 p.products_id = s.products_id
                           ORDER BY pd.products_name";
    $specials_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $specials_sql_raw, $specials_numrows);
    $specials_result = $dbconn->Execute($specials_sql_raw);
    $aDocument = [];
    $rows = 0;
    while ($specials = $specials_result->fields) {
        $rows++;
        if ((!isset($_GET['sID']) || (isset($_GET['sID']) && ($_GET['sID'] == $specials['specials_id']))) && !isset($sInfo)) {
            $productstable = $oostable['products'];
            $products_result = $dbconn->Execute("SELECT products_image FROM $productstable WHERE products_id = '" . $specials['products_id'] . "'");
            $products = $products_result->fields;
            $sInfo_array = array_merge($specials, $products);
            $sInfo = new objectInfo($sInfo_array);
        }

        if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
            $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $sInfo->specials_id . '&action=edit')];
            echo ' <tr id="row-' . $rows .'">' . "\n";
        } else {
            $aDocument[] = ['id' => $rows,
                            'link' => oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $specials['specials_id'])];
            echo ' <tr id="row-' . $rows .'">' . "\n";
        }


        $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . $specials['products_tax_class_id'] . "' ");
        $tax = $tax_result->fields;

        $specials_cross_out_price = $specials['specials_cross_out_price'];
        $specials_new_products_price = $specials['specials_new_products_price'];

        $cross_out_price = ($specials_cross_out_price * ($tax['tax_rate'] + 100) / 100);
        $specials_price = ($specials_new_products_price * ($tax['tax_rate'] + 100) / 100);

        $cross_out_price = oos_round($cross_out_price, TAX_DECIMAL_PLACES);
        $specials_price = oos_round($specials_price, TAX_DECIMAL_PLACES); ?>			
                <td><?php echo $specials['products_name']; ?></td>
                <td  align="right"><s><?php echo $currencies->format($cross_out_price); ?></s> <span><?php echo $currencies->format($specials_price); ?></span></td>
                <td  align="right">
<?php
        if ($specials['status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['specials'], 'action=setflag&flag=0&id=' . $specials['specials_id']) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $specials['specials_id'] . '&action=edit') . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
                <td class="text-right"><?php if (isset($sInfo) && is_object($sInfo) && ($specials['specials_id'] == $sInfo->specials_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $specials['specials_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
      </tr>
<?php
      // Move that ADOdb pointer!
      $specials_result->MoveNext();
    } ?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $specials_split->display_count($specials_numrows, MAX_DISPLAY_SEARCH_RESULTS, intval($nPage), TEXT_DISPLAY_NUMBER_OF_SPECIALS); ?></td>
                    <td class="smallText" align="right"><?php echo $specials_split->display_links($specials_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, intval($nPage)); ?></td>
                  </tr>
<?php
  if ($action == 'default') {
      ?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&action=new') . '">' . oos_button(IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
                  </tr>
<?php
  } ?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = [];
    $contents = [];
    switch ($action) {
        case 'delete':
            $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_SPECIALS . '</b>'];

            $contents = ['form' => oos_draw_form('id', 'specials', $aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $sInfo->specials_id . '&action=deleteconfirm', 'post', false)];
            $contents[] = ['text' => TEXT_INFO_DELETE_INTRO];
            $contents[] = ['text' => '<br><b>' . $sInfo->products_name . '</b>'];
            $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $sInfo->specials_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];

            break;

        default:
            if (isset($sInfo) && is_object($sInfo)) {
                $heading[] = ['text' => '<b>' . $sInfo->products_name . '</b>'];

                $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $sInfo->specials_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['specials'], 'page=' . intval($nPage) . '&sID=' . $sInfo->specials_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
                $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->specials_date_added)];
                $contents[] = ['text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->specials_last_modified)];
                $contents[] = ['align' => 'center', 'text' => '<br>' . product_info_image($sInfo->products_image, $sInfo->products_name)];


                $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . $sInfo->products_tax_class_id . "' ");
                $tax = $tax_result->fields;

                $in_price_netto = $sInfo->specials_cross_out_price;
                $in_new_price_netto = $sInfo->specials_new_products_price;

                $in_price = ($in_price_netto * ($tax['tax_rate'] + 100) / 100);
                $in_new_price = ($in_new_price_netto * ($tax['tax_rate'] + 100) / 100);

                $in_price_netto = oos_round($in_price_netto, TAX_DECIMAL_PLACES);
                $in_new_price_netto = oos_round($in_new_price_netto, TAX_DECIMAL_PLACES);

                $in_price = oos_round($in_price, TAX_DECIMAL_PLACES);
                $in_new_price = oos_round($in_new_price, TAX_DECIMAL_PLACES);

                $contents[] = ['text' => '<br>' . TEXT_SPECIALS_CROSS_OUT_PRICE . ' ' . $currencies->format($in_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_price_netto)];
                $contents[] = ['text' => '' . TEXT_INFO_NEW_PRICE . ' ' . $currencies->format($in_new_price) . ' - ' . TEXT_TAX_INFO . $currencies->format($in_new_price_netto)];
                $contents[] = ['text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sInfo->specials_new_products_price / $sInfo->specials_cross_out_price) * 100)) . '%'];

                if (date('Y-m-d') < $sInfo->expires_date) {
                    $contents[] = ['text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($sInfo->expires_date) . '</b>'];
                }
                if (oos_is_not_null($sInfo->date_status_change)) {
                    $contents[] = ['text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change)];
                }
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
    } ?>
          </tr>
        </table>
	</div>
<?php
}
?>
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

require 'includes/nice_exit.php';
