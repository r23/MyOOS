<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.138 2002/11/18 21:38:22 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/functions/function_categories.php';
require 'includes/classes/class_currencies.php';

$currencies = new currencies();

$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (!empty($action)) {
    switch ($action) {
		case 'slave_delete':
			$dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']) . " AND master_id = " . intval($_GET['master_id']) . " LIMIT 1");
			$check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . $_GET['slave_id']);
			if ($check_product_result->RecordCount() == 0) {
				$dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = '1' WHERE products_id = " . $_GET['slave_id']);
			}
			$messageStack->add_session('Slave Deleted', 'success');
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['master_id'] . '&amp;action=slave_products'));
			break;

		case 'slave_visible':
			$dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = " . $_GET['visible'] . " WHERE products_id = " . $_GET['slave_id']);
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['master_id'] . '&amp;action=slave_products'));
			break;

		case 'setflag':
			if ( isset($_GET['flag']) && ($_GET['flag'] == '0') || ($_GET['flag'] == '1') || ($_GET['flag'] == '3') ) {
				if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
					oos_set_product_status($_GET['pID'], $_GET['flag']);
				} elseif (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
					oos_set_categories_status($_GET['cID'], $_GET['flag']);
				}
			}
			
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $_GET['cPath'] . '&amp;pID=' . $_GET['pID'] . (isset($_GET['page']) ? '&page=' . $_GET['page'] : '') . ((isset($_GET['search']) && !empty($_GET['search'])) ? '&search=' . $_GET['search'] : '')));
			break;

		case 'delete_category_confirm':
			if (isset($_POST['categories_id'])) {
				$categories_id = oos_db_prepare_input($_POST['categories_id']);
				
				$categories = oos_get_category_tree($categories_id, '', '0', '', TRUE);
				$products = array();
				$products_delete = array();

				for ($i = 0, $n = count($categories); $i < $n; $i++) {
					$product_ids_result = $dbconn->Execute("SELECT products_id FROM " . $oostable['products_to_categories'] . " WHERE categories_id = '" . intval($categories[$i]['id']) . "'");
					while ($product_ids = $product_ids_result->fields) {
						$products[$product_ids['products_id']]['categories'][] = $categories[$i]['id'];

						// Move that ADOdb pointer!
						$product_ids_result->MoveNext();
					}
				}

				reset($products);
				foreach ($products as $key => $value) { 	
					$category_ids = '';
					for ($i = 0, $n = count($value['categories']); $i < $n; $i++) {
						$category_ids .= '\'' . $value['categories'][$i] . '\', ';
					}
					$category_ids = substr($category_ids, 0, -2);

					$check_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($key) . "' and categories_id not in (" . $category_ids . ")");
					$check = $check_result->fields;
					if ($check['total'] < '1') {
						$products_delete[$key] = $key;
					}
				}

				for ($i = 0, $n = count($categories); $i < $n; $i++) {
					oos_remove_category($categories[$i]['id']);
				}

				foreach($products_delete as $key) {
					oos_remove_product($key);
				}
			}

			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath));
			break;

		case 'delete_product_confirm':
			if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
				$product_id = oos_db_prepare_input($_POST['products_id']);
				$product_categories = $_POST['product_categories'];

				for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
					$dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . (int)$product_id . "' and categories_id = '" . (int)$product_categories[$i] . "'");
				}

				$product_categories_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . (int)$product_id . "'");
				$product_categories = $product_categories_result->fields;

				if ($product_categories['total'] == '0') {
					oos_remove_product($product_id);
				}
			}
			oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath));
			break;


    }
}


$cPath_back = '';
if (is_array($cPath_array) && count($cPath_array) > 0) {
	for ($i = 0, $n = count($cPath_array) - 1; $i < $n; $i++) {
		if (empty($cPath_back)) {
			$cPath_back .= $cPath_array[$i];
		} else {
			$cPath_back .= '_' . $cPath_array[$i];
		}
	}
}

$cPath_back = (oos_is_not_null($cPath_back)) ? 'cPath=' . $cPath_back . '&' : '';	


// check if the catalog image directory exists
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
		
<?php
	$image_icon_status_array = array();
	$image_icon_status_array = array(array('id' => '0', 'text' => TEXT_PRODUCT_NOT_AVAILABLE));
	$image_icon_status_result = $dbconn->Execute("SELECT products_status_id, products_status_name FROM " . $oostable['products_status'] . " WHERE products_status_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY products_status_id");
	while ($image_icon_status = $image_icon_status_result->fields) {
		$image_icon_status_array[] = array('id' => $image_icon_status['products_status_id'],
											'text' => $image_icon_status['products_status_name']);

		// Move that ADOdb pointer!
		$image_icon_status_result->MoveNext();
    }
?>

	<!-- Breadcrumbs //-->
	<div class="content-heading">
		<div class="col-lg-12">
			<h2><?php echo HEADING_TITLE; ?></h2>
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP; ?></a>
				</li>
				<li class="breadcrumb-item">
					<?php echo '<a href="' . oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG; ?></a>
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
							<th><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></th>
							<th><?php echo TABLE_HEADING_MANUFACTURERS; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_STATUS; ?></th>
							<th class="text-center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></th>
							<th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
						</tr>	
					</thead>
<?php
    $categories_count = 0;
    $rows = 0;
    $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.parent_id = '" . $current_category_id . "' and c.categories_id = cd.categories_id and cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY c.sort_order, cd.categories_name");

    while ($categories = $categories_result->fields) {
      $categories_count++;
      $rows++;

// Get parent_id for subcategories if search
      if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => oos_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => oos_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
      }

      if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '\'">' . "\n";
      } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories['categories_id']) . '\'">' . "\n";
      }
?>
                <td>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['categories'], oos_get_path($categories['categories_id'])) . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-folder"></i></button></a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="text-center">&nbsp;</td>
                 <td class="text-center">
 <?php
       if ($categories['categories_status'] == '1') {
         echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&amp;flag=0&amp;cID=' . $categories['categories_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
       } else {
         echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&amp;flag=1&amp;cID=' . $categories['categories_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
       }
?></td>
                <td class="text-center">&nbsp;<?php echo $categories['sort_order']; ?>&nbsp;</td>
                <td class="text-right"><?php if (isset($cInfo) && is_object($cInfo) && ($categories['categories_id'] == $cInfo->categories_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $categories['categories_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $categories_result->MoveNext();
    }


    $products_count = 0;
    $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price,p.products_base_price, p.products_base_unit, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_max, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd, " . $oostable['products_to_categories'] . " p2c WHERE p.products_id = pd.products_id and pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' and p.products_id = p2c.products_id and p2c.categories_id = '" . $current_category_id . "' ORDER BY pd.products_name");


    while ($products = $products_result->fields) {
      $products_count++;
      $rows++;

// Get categories_id for product if search
      if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo)  && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        // find out the rating average from customer reviews
        $reviews_result = $dbconn->Execute("SELECT (avg(reviews_rating) / 5 * 100) as average_rating FROM " . $oostable['reviews'] . " WHERE products_id = '" . $products['products_id'] . "'");
        $reviews = $reviews_result->fields;
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
      }

      if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id'] . '&amp;action=new_product_preview') . '\'">' . "\n";
      } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id']) . '\'">' . "\n";
      }
?>
                <td><?php echo '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id'] . '&amp;action=new_product_preview') . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-search"></i></button></a>&nbsp;' . '#' . $products['products_id'] . ' ' . $products['products_name']; ?></td>
                <td><?php echo oos_get_manufacturers_name($products['products_id']) ?></td>
                <td class="text-center">
<?php
    if ($products['products_status'] == '0') {
      echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=3&amp;pID=' . $products['products_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
    } else {
      echo '<a href="' . oos_href_link_admin($aContents['categories'], 'action=setflag&flag=0&amp;pID=' . $products['products_id'] . '&amp;cPath=' . $cPath) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
    }

?></td>
                <td class="text-center"><?php echo $products['products_sort_order']; ?></td>
                <td class="text-right"><?php if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $products['products_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
              </tr>
<?php
      // Move that ADOdb pointer!
      $products_result->MoveNext();
    }


?>
              <tr>
                <td colspan="5"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText"><?php echo TEXT_CATEGORIES . '&nbsp;' . $categories_count . '<br />' . TEXT_PRODUCTS . '&nbsp;' . $products_count; ?></td>
                    <td align="right" class="smallText"></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
<?php
    $heading = array();
    $contents = array();

    switch ($action) {

      case 'delete_category':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

        $contents = array('form' => oos_draw_form('id', 'categories', $aContents['wastebasket'], 'action=delete_category_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
        $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
        $contents[] = array('text' => '<br /><b>' . $cInfo->categories_name . '</b>');
        if ($cInfo->childs_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
        if ($cInfo->products_count > 0) $contents[] = array('text' => '<br />' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      case 'delete_product':
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

        $contents = array('form' => oos_draw_form('id', 'products', $aContents['wastebasket'], 'action=delete_product_confirm&amp;cPath=' . $cPath, 'post', FALSE) . oos_draw_hidden_field('products_id', $pInfo->products_id));
        $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
        $contents[] = array('text' => '<br /><b>' . $pInfo->products_name . '</b>');

        $product_categories_string = '';
        $product_categories = oos_generate_category_path($pInfo->products_id, 'product');
        for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
          $category_path = '';
          for ($j = 0, $k = count($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
          }
          $category_path = substr($category_path, 0, -16);
          $product_categories_string .= oos_draw_checkbox_field('product_categories[]', $product_categories[$i][count($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br />';
        }
        $product_categories_string = substr($product_categories_string, 0, -4);

        $contents[] = array('text' => '<br />' . $product_categories_string);
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . ' <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
        break;

      default:
        if ($rows > 0) {
          if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=edit_category') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=delete_category') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;cID=' . $cInfo->categories_id . '&amp;action=move_category') . '">' . oos_button('move', IMAGE_MOVE) . '</a>');
            $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cID) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            if (oos_is_not_null($cInfo->last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($cInfo->last_modified));
            $contents[] = array('text' => '<br />' . oos_info_image($cInfo->categories_image, $cInfo->categories_name) . '<br />' . $cInfo->categories_image);
            $contents[] = array('text' => '<br />' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br />' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
          } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . $pInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=new_product') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=delete_product') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=move_product') . '">' . oos_button('move', IMAGE_MOVE) . '</a> <a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=copy_to') . '">' . oos_button('copy_to', IMAGE_COPY_TO) . '</a>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=slave_products') . '">' . oos_button('slave', IMAGE_SLAVE) . '</a>');

            if (defined('MIN_DISPLAY_NEW_SPEZILAS')) {
              $productstable = $oostable['products'];
              $specialstable = $oostable['specials'];
              $query = "SELECT p.products_tax_class_id, p.products_id, s.specials_id, s.specials_new_products_price,
                               s.expires_date, s.status
                         FROM $productstable p,
                              $specialstable s
                        WHERE s.status = '1' AND
                              p.products_id = s.products_id AND
                              s.products_id = '" . $pInfo->products_id . "'";
              $specials_result = $dbconn->Execute($query);
              if (!$specials_result->RecordCount()) {
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'pID=' . $pInfo->products_id . '&amp;action=new') . '">' . oos_button('specials', IMAGE_SPECIALS) . '</a>');
              } else {
                $specials = $specials_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['specials'], 'sID=' . $specials['specials_id'] . '&amp;action=edit') . '">' . oos_button('specials', IMAGE_SPECIALS) . '</a>');
              }
            }


            if (defined('MAX_DISPLAY_FEATURED_PRODUCTS')) {
              $featuredtable = $oostable['featured'];
              $query = "SELECT featured_id, products_id, status
                         FROM $featuredtable p
                        WHERE status = '1' AND
                              products_id = '" . $pInfo->products_id . "'";
              $featured_result = $dbconn->Execute($query);
              if (!$featured_result->RecordCount()) {
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'pID=' . $pInfo->products_id . '&amp;action=new') . '">' . oos_button('featured', IMAGE_FEATURED) . '</a>');
              } else {
                $featured = $featured_result->fields;
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'fID=' . $featured['featured_id'] . '&amp;action=edit') . '">' . oos_button('featured', IMAGE_FEATURED) . '</a>');
              }
            }

            $contents[] = array('text' => '#' . $pInfo->products_id . ' ' . TEXT_CATEGORIES . ' ' . oos_get_categories_name($current_category_id) . '<br />' . TEXT_DATE_ADDED . ' ' . oos_date_short($pInfo->products_date_added));
            if (oos_is_not_null($pInfo->products_last_modified)) $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($pInfo->products_last_modified));
            if (date('Y-m-d') < $pInfo->products_date_available) $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . oos_date_short($pInfo->products_date_available));
            $contents[] = array('text' => '<br /><a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . $cPath . '&amp;pID=' . $pInfo->products_id . '&amp;action=new_product_preview') . '">' . oos_info_image($pInfo->products_image, $pInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br />' . $pInfo->products_image);

            $sPrice = $pInfo->products_price;
            $sPriceList = $pInfo->products_price_list;

            if ($action != 'new_product_preview'){
              $sPriceNetto = round($sPrice,TAX_DECIMAL_PLACES);
              $sPriceListNetto = round($sPriceList,TAX_DECIMAL_PLACES);
              $tax_result = $dbconn->Execute("SELECT tax_rate FROM " . $oostable['tax_rates'] . " WHERE tax_class_id = '" . $pInfo->products_tax_class_id . "' ");
              $tax = $tax_result->fields;
              $sPrice = ($sPrice*($tax['tax_rate']+100)/100);
              $sPriceList = ($sPriceList*($tax['tax_rate']+100)/100);

              if (isset($specials) && is_array($specials)) {
                $sSpecialsPriceNetto = round($specials['specials_new_products_price'],TAX_DECIMAL_PLACES);
                $sSpecialsPrice = round(($specials['specials_new_products_price']*($tax['tax_rate']+100)/100),TAX_DECIMAL_PLACES);
              }
            }			
		
            $sPrice = round($sPrice,TAX_DECIMAL_PLACES);
            $sPriceList = round($sPriceList,TAX_DECIMAL_PLACES);			
			
            if (isset($specials) && is_array($specials)) {
              $contents[] = array('text' => '<br /><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="oldPrice">' . $currencies->format($sPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="oldPrice">' . $currencies->format($sPriceNetto) . '</span>');
              $contents[] = array('text' => '<b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> <span class="specialPrice">' . $currencies->format($sSpecialsPrice) . '</span> - ' . TEXT_TAX_INFO . '<span class="specialPrice">' . $currencies->format($sSpecialsPriceNetto) . '</span>');

              $contents[] = array('text' => '' . TEXT_INFO_PERCENTAGE . ' ' . number_format(100 - (($sSpecialsPrice / $sPrice) * 100)) . '%');
              if (date('Y-m-d') < $specials['expires_date']) {
                $contents[] = array('text' => '' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($specials['expires_date']) . '</b>');
              }
            } else {
              $contents[] = array('text' => '<br /><b>' . TEXT_PRODUCTS_PRICE_INFO . '</b> ' . $currencies->format($sPrice) . ' - ' . TEXT_TAX_INFO . $currencies->format($sPriceNetto));
            }
			if ($sPriceList > 0) {
				$contents[] = array('text' => '' .  CAT_LIST_PRICE_TEXT . $currencies->format($sPriceList) . ' - ' . TEXT_TAX_INFO . $currencies->format($sPriceListNetto));
			}
			$contents[] = array('text' => '<br /><br />' . TEXT_PRODUCTS_QUANTITY_INFO . ' ' . $pInfo->products_quantity);
            $contents[] = array('text' => '' . CAT_QUANTITY_MIN_TEXT . $pInfo->products_quantity_order_min);
			$contents[] = array('text' => '' . CAT_QUANTITY_MAX_TEXT . $pInfo->products_quantity_order_max);
			$contents[] = array('text' => '' . CAT_QUANTITY_UNITS_TEXT . $pInfo->products_quantity_order_units);

            if ( $pInfo->products_discount1_qty > 0 ) {
              $sDiscount1 = $pInfo->products_discount1;
              $sDiscount1 = round($sDiscount1,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '<br /><br /><b>' . TEXT_DISCOUNTS_TITLE . ':</b>');
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount1_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount1_qty . ' ' . $currencies->format($sDiscount1) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount1Netto));
            }
            if ( $pInfo->products_discount2_qty > 0 ) {
              $sDiscount2 = $pInfo->products_discount2;
              $sDiscount2 = round($sDiscount2,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount2_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount2_qty . ' ' . $currencies->format($sDiscount2) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount2Netto));
            }
            if ( $pInfo->products_discount3_qty > 0 ) {
              $sDiscount3 = $pInfo->products_discount3;
              $sDiscount3 = round($sDiscount3,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount3_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount3_qty . ' ' . $currencies->format($sDiscount3) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount3Netto));
            }
            if ( $pInfo->products_discount4_qty > 0 ) {
              $sDiscount4 = $pInfo->products_discount4;
              $sDiscount4 = round($sDiscount4,TAX_DECIMAL_PLACES);
              $contents[] = array('text' => '&nbsp;&nbsp; ' . ($pInfo->products_discount4_qty < 10 ? '&nbsp;' : '') . $pInfo->products_discount4_qty . ' ' . $currencies->format($sDiscount4) . ' - ' . TEXT_TAX_INFO . $currencies->format($sDiscount4Netto));
            }
            $contents[] = array('text' => '<br />' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . number_format($pInfo->average_rating, 2) . '%');
          }
        } else { // create category/product info
          $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

          $contents[] = array('text' => TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS);
        }
        break;
    }

    if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
?>
	<td class="w-25">
		<table class="table table-striped">
<?php
		$box = new box;
		echo $box->infoBox($heading, $contents);  
?>
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
</div>


<?php
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>