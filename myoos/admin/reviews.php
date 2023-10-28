<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: reviews.php,v 1.39 2002/03/17 17:49:46 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';

switch ($action) {
    case 'update':
        $reviews_id = oos_db_prepare_input($_GET['rID']);

        $reviews_rating = oos_db_prepare_input($_POST['reviews_rating']);
        $reviews_text = oos_db_prepare_input($_POST['reviews_text']);
        $reviews_status = oos_db_prepare_input($_POST['reviews_status']);

        $reviewstable = $oostable['reviews'];
        $dbconn->Execute("UPDATE $reviewstable SET reviews_rating = '" . oos_db_input($reviews_rating) . "', reviews_status = '" . oos_db_input($reviews_status) . "', last_modified = now() WHERE reviews_id = '" . oos_db_input($reviews_id) . "'");
        $reviews_descriptiontable = $oostable['reviews_description'];
        $dbconn->Execute("UPDATE $reviews_descriptiontable SET reviews_text = '" . oos_db_input($reviews_text) . "' WHERE reviews_id = '" . oos_db_input($reviews_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $reviews_id));
        break;

    case 'deleteconfirm':
        $reviews_id = oos_db_prepare_input($_GET['rID']);

        $reviewstable = $oostable['reviews'];
        $dbconn->Execute("DELETE FROM $reviewstable WHERE reviews_id = '" . oos_db_input($reviews_id) . "'");
        $reviews_descriptiontable = $oostable['reviews_description'];
        $dbconn->Execute("DELETE FROM $reviews_descriptiontable WHERE reviews_id = '" . oos_db_input($reviews_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['reviews'], 'page=' . $nPage));
        break;

    case 'setflag':
        if (($_GET['flag'] == '0') || ($_GET['flag'] == '1')) {
            if (isset($_GET['rID']) && is_numeric($_GET['rID'])) {
                oos_set_review_status($_GET['rID'], $_GET['flag']);
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $_GET['rID']));
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
  if ($action == 'edit') {
      $rID = oos_db_prepare_input($_GET['rID']);

      $reviewstable = $oostable['reviews'];
      $reviews_descriptiontable = $oostable['reviews_description'];
      $reviews_result = $dbconn->Execute("SELECT r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating, r.reviews_status FROM $reviewstable r, $reviews_descriptiontable rd WHERE r.reviews_id = '" . oos_db_input($rID) . "' AND r.reviews_id = rd.reviews_id");
      $reviews = $reviews_result->fields;

      $productstable = $oostable['products'];
      $products_result = $dbconn->Execute("SELECT products_image FROM $productstable WHERE products_id = '" . $reviews['products_id'] . "'");
      $products = $products_result->fields;

      $products_descriptiontable = $oostable['products_description'];
      $products_name_result = $dbconn->Execute("SELECT products_name FROM $products_descriptiontable WHERE products_id = '" . $reviews['products_id'] . "' AND products_languages_id = '" . intval($_SESSION['language_id']) . "'");
      $products_name = $products_name_result->fields;

      $rInfo_array = array_merge($reviews, $products, $products_name);
      $rInfo = new objectInfo($rInfo_array);

      $in_status = match ($rInfo->reviews_status) {
          '0' => false,
          default => true,
      }; ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr><?php echo oos_draw_form('id', 'review', $aContents['reviews'], 'page=' . $nPage . '&rID=' . $_GET['rID'] . '&action=preview', 'post', false); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo oos_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo oos_image(OOS_HTTPS_SERVER . OOS_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH); ?></td>
          </tr>
          <tr>
            <td class="main" colspan="2"><strong><?php echo TEXT_INFO_REVIEW_STATUS; ?></strong> <?php echo oos_draw_checkbox_field('reviews_status', '1', $in_status); ?></td>
          </tr>		  
		  
        </table></td>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo oos_draw_textarea_field('', 'reviews_text', 'soft', '60', '15', $rInfo->reviews_text); ?></td>
          </tr>
          <tr>
            <td class="smallText" align="right"><?php echo ENTRY_REVIEW_TEXT; ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo TEXT_BAD; ?>&nbsp;<?php for ($i = 1; $i <= 5; $i++) {
            echo oos_draw_radio_field('reviews_rating', $i, '', $rInfo->reviews_rating) . '&nbsp;';
        }
      echo TEXT_GOOD; ?></td>
      </tr>
          <tr>
        <td></td>
      </tr>  <tr>
        <td></td>
      </tr>
      <tr>
        <td align="right" class="main"><?php echo oos_draw_hidden_field('reviews_id', $rInfo->reviews_id) . oos_draw_hidden_field('products_id', $rInfo->products_id) . oos_draw_hidden_field('customers_name', $rInfo->customers_name) . oos_draw_hidden_field('products_name', $rInfo->products_name) . oos_draw_hidden_field('products_image', $rInfo->products_image) . oos_draw_hidden_field('date_added', $rInfo->date_added) . oos_submit_button('preview') . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . intval($_GET['rID'])) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?></td>
      </form></tr>
<?php
  } elseif ($action == 'preview') {
      if (oos_is_not_null($_POST)) {
          $rInfo = new objectInfo($_POST);
      } else {
          $reviewstable = $oostable['reviews'];
          $reviews_descriptiontable = $oostable['reviews_description'];
          $reviews_result = $dbconn->Execute("SELECT r.reviews_id, r.products_id, r.customers_name, r.date_added, r.last_modified, r.reviews_read, rd.reviews_text, r.reviews_rating, r.reviews_status FROM $reviewstable r, $reviews_descriptiontable rd WHERE r.reviews_id = '" . intval($_GET['rID']) . "' AND r.reviews_id = rd.reviews_id");
          $reviews = $reviews_result->fields;

          $productstable = $oostable['products'];
          $products_result = $dbconn->Execute("SELECT products_image FROM $productstable WHERE products_id = '" . $reviews['products_id'] . "'");
          $products = $products_result->fields;

          $products_descriptiontable = $oostable['products_description'];
          $products_name_result = $dbconn->Execute("SELECT products_name FROM $products_descriptiontable WHERE products_id = '" . $reviews['products_id'] . "' AND products_languages_id = '" . intval($_SESSION['language_id']) . "'");
          $products_name = $products_name_result->fields;

          $rInfo_array = array_merge($reviews, $products, $products_name);
          $rInfo = new objectInfo($rInfo_array);
      } ?>
	<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr><?php echo oos_draw_form('id', 'update', $aContents['reviews'], 'page=' . $nPage . '&rID=' . $_GET['rID'] . '&action=update', 'post', true, 'enctype="multipart/form-data"'); ?>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" valign="top"><b><?php echo ENTRY_PRODUCT; ?></b> <?php echo $rInfo->products_name; ?><br><b><?php echo ENTRY_FROM; ?></b> <?php echo $rInfo->customers_name; ?><br><br><b><?php echo ENTRY_DATE; ?></b> <?php echo oos_date_short($rInfo->date_added); ?></td>
            <td class="main" align="right" valign="top"><?php echo oos_image(OOS_HTTPS_SERVER . OOS_IMAGES . $rInfo->products_image, $rInfo->products_name, SMALL_IMAGE_WIDTH); ?></td>
          </tr>
        </table>
      </tr>
      <tr>
        <td><table witdh="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top" class="main"><b><?php echo ENTRY_REVIEW; ?></b><br><br><?php echo nl2br(htmlspecialchars((string) oos_break_string((string)$rInfo->reviews_text, 15))); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td></td>
      </tr>
      <tr>
        <td class="main"><b><?php echo ENTRY_RATING; ?></b>&nbsp;<?php echo oos_image(OOS_SHOP_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif', sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating)); ?>&nbsp;<small>[<?php echo sprintf(TEXT_OF_5_STARS, $rInfo->reviews_rating); ?>]</small></td>
      </tr>
      <tr>
        <td></td>
      </tr>
<?php
    if (oos_is_not_null($_POST)) {
        /* Re-Post all POST'ed variables */
        reset($_POST);
        foreach ($_POST as $key => $value) {
            echo '<input type="hidden" name="' . $key . '" value="' . htmlspecialchars(stripslashes((string)$value), ENT_QUOTES, 'UTF-8') . '">';
        } ?>
      <tr>
        <td align="right" class="smallText"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id . '&action=edit') . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a> ' . oos_submit_button(BUTTON_UPDATE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?></td>
      </form></tr>
<?php
    } else {
        if (isset($_GET['origin'])) {
            $back_url = $_GET['origin'];
            $back_url_params = '';
        } else {
            $back_url = $aContents['reviews'];
            $back_url_params = 'page=' . $nPage . '&rID=' . $rInfo->reviews_id;
        } ?>
      <tr>
        <td class="text-right"><?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?></td>
      </tr>
<?php
    } ?>
    </table>
<?php
  } else {
      ?>

	<div class="table-responsive">
		<table class="table w-100">
          <tr>
            <td valign="top">
			
				<table class="table table-striped table-hover w-100">
					<thead class="thead-dark">
						<tr>
							<th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_RATING; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_DATE_ADDED; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $rows = 0;
      $aDocument = [];
      $reviewstable = $oostable['reviews'];
      $reviews_result_raw = "SELECT reviews_id, products_id, date_added, last_modified, reviews_rating, reviews_status
                           FROM $reviewstable
                           ORDER BY date_added DESC";
      $reviews_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $reviews_result_raw, $reviews_result_numrows);
      $reviews_result = $dbconn->Execute($reviews_result_raw);
      while ($reviews = $reviews_result->fields) {
          $rows++;
          if ((!isset($_GET['rID']) || (isset($_GET['rID']) && ($_GET['rID'] == $reviews['reviews_id']))) && !isset($rInfo)) {
              $reviewstable = $oostable['reviews'];
              $reviews_descriptiontable = $oostable['reviews_description'];
              $reviews_text_result = $dbconn->Execute("SELECT r.reviews_read, r.customers_name, length(rd.reviews_text) as reviews_text_size FROM $reviewstable r, $reviews_descriptiontable rd WHERE r.reviews_id = '" . $reviews['reviews_id'] . "' AND r.reviews_id = rd.reviews_id");
              $reviews_text = $reviews_text_result->fields;

              $productstable = $oostable['products'];
              $products_image_result = $dbconn->Execute("SELECT products_image FROM $productstable WHERE products_id = '" . $reviews['products_id'] . "'");
              $products_image = $products_image_result->fields;

              $products_descriptiontable = $oostable['products_description'];
              $products_name_result = $dbconn->Execute("SELECT products_name FROM $products_descriptiontable WHERE products_id = '" . $reviews['products_id'] . "' AND products_languages_id = '" . intval($_SESSION['language_id']) . "'");
              $products_name = $products_name_result->fields;

              $reviewstable = $oostable['reviews'];
              $reviews_average_result = $dbconn->Execute("SELECT (avg(reviews_rating) / 5 * 100) as average_rating FROM $reviewstable WHERE products_id = '" . $reviews['products_id'] . "'");
              $reviews_average = $reviews_average_result->fields;

              $review_info = array_merge($reviews_text, $reviews_average, $products_name);
              $rInfo_array = array_merge($reviews, $review_info, $products_image);
              $rInfo = new objectInfo($rInfo_array);
          }

          if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id)) {
              $aDocument[] = ['id' => $rows,
                              'link' => oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id . '&action=preview') ];
              echo '                  <tr id="row-' . $rows .'">' . "\n";
          } else {
              $aDocument[] = ['id' => $rows,
                              'link' => oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $reviews['reviews_id'])];
              echo '                  <tr id="row-' . $rows .'">' . "\n";
          } ?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $reviews['reviews_id'] . '&action=preview') . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . oos_get_products_name($reviews['products_id']); ?></td>
                <td class="text-right"><?php echo $reviews['reviews_rating']; ?></td>
                <td class="text-center">
 <?php
        if ($reviews['reviews_status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['reviews'], 'action=setflag&flag=0&rID=' . $reviews['reviews_id'] . '&page=' . $nPage) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['reviews'], 'action=setflag&flag=1&rID=' . $reviews['reviews_id'] . '&page=' . $nPage). '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>				
                <td class="text-right"><?php echo oos_date_short($reviews['date_added']); ?></td>
                <td class="text-right"><?php if (isset($rInfo) && is_object($rInfo) && ($reviews['reviews_id'] == $rInfo->reviews_id)) {
                    echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
                } else {
                    echo '<a href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $reviews['reviews_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
                } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $reviews_result->MoveNext();
      } ?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $reviews_split->display_count($reviews_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_REVIEWS); ?></td>
                    <td class="smallText" align="right"><?php echo $reviews_split->display_links($reviews_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = [];
      $contents = [];

      switch ($action) {
          case 'delete':
              $heading[] = ['text' => '<b>' . TEXT_INFO_HEADING_DELETE_REVIEW . '</b>'];

              $contents = ['form' => oos_draw_form('id', 'reviews', $aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id . '&action=deleteconfirm', 'post', false)];
              $contents[] = ['text' => TEXT_INFO_DELETE_REVIEW_INTRO];
              $contents[] = ['text' => '<br><b>' . $rInfo->products_name . '</b>'];
              $contents[] = ['align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'];
              break;

          default:
              if (isset($rInfo) && is_object($rInfo)) {
                  $heading[] = ['text' => '<b>' . $rInfo->products_name . '</b>'];

                  $contents[] = ['align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['reviews'], 'page=' . $nPage . '&rID=' . $rInfo->reviews_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>'];
                  $contents[] = ['text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($rInfo->date_added)];
                  if (oos_is_not_null($rInfo->last_modified)) {
                      $contents[] = ['text' => TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($rInfo->last_modified)];
                  }
                  $contents[] = ['text' => '<br>' . product_info_image($rInfo->products_image, $rInfo->products_name)];
                  $contents[] = ['text' => '<br>' . TEXT_INFO_REVIEW_AUTHOR . ' ' . $rInfo->customers_name];
                  $contents[] = ['text' => TEXT_INFO_REVIEW_RATING . ' ' . oos_image(OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'stars_' . $rInfo->reviews_rating . '.gif')];
                  $contents[] = ['text' => TEXT_INFO_REVIEW_READ . ' ' . $rInfo->reviews_read];
                  $contents[] = ['text' => '<br>' . TEXT_INFO_REVIEW_SIZE . ' ' . $rInfo->reviews_text_size . ' bytes'];
                  $contents[] = ['text' => '<br>' . TEXT_INFO_PRODUCTS_AVERAGE_RATING . ' ' . number_format($rInfo->average_rating, 2) . '%'];
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

?>
<script nonce="<?php echo NONCE; ?>">
let element = document.getElementById('page');
if (element) {

	let form = document.getElementById('pages'); 

	element.addEventListener('change', function() { 
		form.submit(); 
	});
}
</script>
<?php

require 'includes/nice_exit.php';
