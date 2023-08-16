<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.138 2002/11/18 21:38:22 dgw_
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

require 'includes/functions/function_categories.php';
require 'includes/classes/class_currencies.php';

$currencies = new currencies();

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : $current_category_id);

if (!empty($action)) {
    switch ($action) {
    case 'slave_delete':
        $dbconn->Execute("DELETE FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']) . " AND master_id = " . intval($_GET['master_id']) . " LIMIT 1");
        $check_product_result = $dbconn->Execute("SELECT slave_id, master_id FROM " . $oostable['products_to_master'] . " WHERE slave_id = " . intval($_GET['slave_id']));
        if ($check_product_result->RecordCount() == 0) {
            $dbconn->Execute("UPDATE " . $oostable['products'] . " SET products_slave_visible = '1' WHERE products_id = " . intval($_GET['slave_id']));
        }
        $messageStack->add_session('Slave Deleted', 'success');
        oos_redirect_admin(oos_href_link_admin($aContents['wastebasket'], 'cPath=' . $cPath  . '&pID=' . $_GET['master_id'] . '&action=slave_products'));
        break;

    case 'untrash':
        if (isset($_GET['flag']) && ($_GET['flag'] == '1') || ($_GET['flag'] == '2')) {
            if (isset($_GET['pID']) && is_numeric($_GET['pID'])) {
                oos_set_product_status($_GET['pID'], $_GET['flag']);
            } elseif (isset($_GET['cID']) && is_numeric($_GET['cID'])) {
                oos_set_categories_status($_GET['cID'], $_GET['flag']);
            }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['wastebasket'], '&cPath=' . oos_prepare_input($cPath) . '&page=' . $nPage));
        break;

    case 'delete_category_confirm':
        if (isset($_POST['categories_id']) && is_numeric($_POST['categories_id'])) {
            $categories_id = oos_db_prepare_input($_POST['categories_id']);

            $categories = oos_get_category_tree($categories_id, '', '0', '', true);
            $products = [];
            $products_delete = [];

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

            foreach ($products_delete as $key) {
                oos_remove_product($key);
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['wastebasket'], 'cPath=' . $cPath));
        break;

    case 'delete_product_confirm':
        if (isset($_POST['products_id']) && isset($_POST['product_categories']) && is_array($_POST['product_categories'])) {
            $product_id = oos_db_prepare_input($_POST['products_id']);
            $product_categories = oos_db_prepare_input($_POST['product_categories']);

            for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
                $dbconn->Execute("DELETE FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($product_id) . "' AND categories_id = '" . intval($product_categories[$i]) . "'");
            }

            $product_categories_result = $dbconn->Execute("SELECT COUNT(*) AS total FROM " . $oostable['products_to_categories'] . " WHERE products_id = '" . intval($product_id) . "'");
            $product_categories = $product_categories_result->fields;

            if ($product_categories['total'] == '0') {
                oos_remove_product($product_id);
            }
        }

        oos_redirect_admin(oos_href_link_admin($aContents['wastebasket'], 'cPath=' . $cPath));
        break;


    }
}

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

    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo HEADING_TITLE; ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP; ?></a>
                </li>
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG; ?></a>
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
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>
                    </thead>
<?php
    $categories_count = 0;
    $rows = 0;
if (isset($_GET['search'])) {
    $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.categories_id = cd.categories_id AND c.categories_status = 0 AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' AND cd.categories_name like '%" . oos_db_input($_GET['search']) . "%' ORDER BY c.sort_order, cd.categories_name");
} else {
    $categories_result = $dbconn->Execute("SELECT c.categories_id, cd.categories_name, c.categories_image, c.parent_id, c.sort_order, c.date_added, c.last_modified, c.categories_status FROM " . $oostable['categories'] . " c, " . $oostable['categories_description'] . " cd WHERE c.parent_id = '" . intval($current_category_id) . "' AND c.categories_status = 0 AND c.categories_id = cd.categories_id AND cd.categories_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY c.sort_order, cd.categories_name");
}

while ($categories = $categories_result->fields) {
    $categories_count++;
    $rows++;

    // Get parent_id for subcategories if search
    if (isset($_GET['search'])) {
        $cPath = intval($categories['parent_id']);
    }

    if ((!isset($_GET['cID']) && !isset($_GET['pID']) || (isset($_GET['cID']) && ($_GET['cID'] == $categories['categories_id']))) && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        $category_childs = array('childs_count' => oos_childs_in_category_count($categories['categories_id']));
        $category_products = array('products_count' => oos_products_in_category_count($categories['categories_id']));

        $cInfo_array = array_merge($categories, $category_childs, $category_products);
        $cInfo = new objectInfo($cInfo_array);
    } ?>
            <tr>
                <td>&nbsp;<?php echo '<a href="' . oos_href_link_admin($aContents['wastebasket'], oos_get_path($categories['categories_id'])) . '"><button class="btn btn-white btn-sm" type="button"><i class="fa fa-folder"></i></button></a>&nbsp;<b>' . ' #' . $categories['categories_id'] . ' ' . $categories['categories_name'] . '</b>'; ?></td>
                <td class="text-center">&nbsp;</td>
                <td class="text-right"><?php  echo
                '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '"><i class="fas fa-pencil-alt" title="' .  BUTTON_EDIT . '"></i></a>
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE_PERMANENTLY . '"></i></a>
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=untrash&flag=2') .  '"><i class="fa fa-undo" title="' . BUTTON_UNTRASH. '"></i></a>'; ?>&nbsp;</td>                
              </tr>
    <?php
    // Move that ADOdb pointer!
    $categories_result->MoveNext();
}

    $products_count = 0;
if (isset($_GET['search'])) {
    $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_tax_class_id, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_setting, p2c.categories_id, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_max, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE p.products_setting = '0' AND p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND pd.products_name like '%" . oos_db_input($_GET['search']) . "%' OR p.products_model like '%" . oos_db_input($_GET['search']) . "%' ORDER BY pd.products_name");
} else {
    $products_result = $dbconn->Execute("SELECT p.products_id, pd.products_name, p.products_quantity, p.products_reorder_level, p.products_image, p.products_price, p.products_base_price, p.products_base_unit, p.products_date_added, p.products_last_modified, p.products_date_available, p.products_status, p.products_tax_class_id, p.products_setting, p.products_price_list, p.products_quantity_order_min, p.products_quantity_order_max, p.products_quantity_order_units, p.products_discount1, p.products_discount2, p.products_discount3, p.products_discount4, p.products_discount1_qty, p.products_discount2_qty, p.products_discount3_qty, p.products_discount4_qty, p.products_sort_order FROM " . $oostable['products'] . " p, " . $oostable['products_description'] . " pd WHERE p.products_setting = '0' AND p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' ORDER BY pd.products_name");
}

while ($products = $products_result->fields) {
    $products_count++;
    $rows++;

    // Get categories_id for product if search
    if (isset($_GET['search'])) {
        $cPath = intval($products['categories_id']);
    }

    if ((!isset($_GET['pID']) && !isset($_GET['cID']) || (isset($_GET['pID']) && ($_GET['pID'] == $products['products_id']))) && !isset($pInfo)  && !isset($cInfo) && (substr($action, 0, 3) != 'new')) {
        // find out the rating average from customer reviews
        $reviews_result = $dbconn->Execute("SELECT (avg(reviews_rating) / 5 * 100) as average_rating FROM " . $oostable['reviews'] . " WHERE products_id = '" . intval($products['products_id']) . "'");
        $reviews = $reviews_result->fields;
        $pInfo_array = array_merge($products, $reviews);
        $pInfo = new objectInfo($pInfo_array);
    }

    if (isset($pInfo) && is_object($pInfo) && ($products['products_id'] == $pInfo->products_id)) {
        echo '              <tr>' . "\n";
    } else {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products['products_id']) . '\'">' . "\n";
    } ?>
                <td><?php echo '#' . $products['products_id'] . ' ' . $products['products_name']; ?></td>
                <td><?php echo oos_get_manufacturers_name($products['products_id']) ?></td>

                <td class="text-right"><?php echo
                        '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products['products_id'] . '&action=new_product') . '"><i class="fas fa-pencil-alt" title="' .  BUTTON_EDIT . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products['products_id'] . '&action=delete_product') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>
							<a href="' . oos_href_link_admin($aContents['wastebasket'], 'pID=' . $products['products_id'] . '&action=untrash&flag=2') . '"><i class="fa fa-undo" title="' . BUTTON_UNTRASH . '"></i></a>'; ?>&nbsp;</td>


              </tr>
    <?php
    // Move that ADOdb pointer!
    $products_result->MoveNext();
}


?>

            </table></td>
<?php
    $heading = [];
    $contents = [];

    switch ($action) {

case 'delete_category':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_CATEGORY . '</b>');

    $contents = array('form' => oos_draw_form('id', 'categories', $aContents['wastebasket'], 'action=delete_category_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('categories_id', $cInfo->categories_id));
    $contents[] = array('text' => TEXT_DELETE_CATEGORY_INTRO);
    $contents[] = array('text' => '<br><b>' . $cInfo->categories_name . '</b>');
    if ($cInfo->childs_count > 0) {
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_CHILDS, $cInfo->childs_count));
    }
    if ($cInfo->products_count > 0) {
        $contents[] = array('text' => '<br>' . sprintf(TEXT_DELETE_WARNING_PRODUCTS, $cInfo->products_count));
    }
          $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE_PERMANENTLY) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

    break;

case 'delete_product':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_PRODUCT . '</b>');

    $contents = array('form' => oos_draw_form('id', 'products', $aContents['wastebasket'], 'action=delete_product_confirm&cPath=' . $cPath, 'post', false) . oos_draw_hidden_field('products_id', $pInfo->products_id));
    $contents[] = array('text' => TEXT_DELETE_PRODUCT_INTRO);
    $contents[] = array('text' => '<br><b>' . $pInfo->products_name . '</b>');


    $product_categories_string = '';
    $product_categories = oos_generate_category_path($pInfo->products_id, 'product');
    for ($i = 0, $n = count($product_categories); $i < $n; $i++) {
        $category_path = '';
        for ($j = 0, $k = count($product_categories[$i]); $j < $k; $j++) {
            $category_path .= $product_categories[$i][$j]['text'] . '&nbsp;&gt;&nbsp;';
        }
        $category_path = substr($category_path, 0, -16);
        $product_categories_string .= oos_draw_checkbox_field('product_categories[]', $product_categories[$i][count($product_categories[$i])-1]['id'], true) . '&nbsp;' . $category_path . '<br>';
    }
          $product_categories_string = substr($product_categories_string, 0, -6);

          $contents[] = array('text' => '<br>' . $product_categories_string);
          $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE_PERMANENTLY) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
    break;

default:
    if ($rows > 0) {
        if (isset($cInfo) && is_object($cInfo)) { // category info box contents
            $heading[] = array('text' => '<b>' . $cInfo->categories_name . '</b>');
            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=edit_category') . '">' . oos_button(BUTTON_EDIT) . '</a> 
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=delete_category') . '">' . oos_button(BUTTON_DELETE) . '</a> 
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&cID=' . $cInfo->categories_id . '&action=untrash&flag=2') . '">' . oos_button(BUTTON_UNTRASH) . '</a>');
            $contents[] = array('text' =>  TEXT_CATEGORIES . ' ' . oos_get_categories_name($cPath) . ' ' . oos_get_categories_name($cInfo->categories_id) . '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($cInfo->date_added));
            $contents[] = array('text' => '<br>' . oos_info_image('category/medium/' . $cInfo->categories_image, $cInfo->categories_name) . '<br>' . $cInfo->categories_image);
            $contents[] = array('text' => '<br>' . TEXT_SUBCATEGORIES . ' ' . $cInfo->childs_count . '<br>' . TEXT_PRODUCTS . ' ' . $cInfo->products_count);
        } elseif (isset($pInfo) && is_object($pInfo)) { // product info box contents
            $heading[] = array('text' => '<b>' . $pInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['products'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=new_product') . '">' . oos_button(BUTTON_EDIT) . '</a> 
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=delete_product') . '">' . oos_button(BUTTON_DELETE) . '</a> 
			<a href="' . oos_href_link_admin($aContents['wastebasket'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $pInfo->products_id . '&action=untrash&flag=2') . '">' . oos_button(BUTTON_UNTRASH) . '</a>');

            $contents[] = array('text' => '#' . $pInfo->products_id . ' ' . TEXT_CATEGORIES . ' ' . oos_get_categories_name($current_category_id) . '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($pInfo->products_date_added));
            if (oos_is_not_null($pInfo->products_last_modified)) {
                $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($pInfo->products_last_modified));
            }
            if (date('Y-m-d') < $pInfo->products_date_available) {
                $contents[] = array('text' => TEXT_DATE_AVAILABLE . ' ' . oos_date_short($pInfo->products_date_available));
            }
            $contents[] = array('text' => '<br>' . product_info_image($pInfo->products_image, $pInfo->products_name) . '<br>' . $pInfo->products_image);
            $contents[] = array('text' => '<br>' . TEXT_PRODUCTS_AVERAGE_RATING . ' ' . ((isset($pInfo->average_rating)) ? number_format($pInfo->average_rating, 2) . '%' : ""));
        }
    } else { // create category/product info
        $parent_categories_name = oos_output_generated_category_path($current_category_id);
        $heading[] = array('text' => '<b>' . EMPTY_CATEGORY . '</b>');

        $contents[] = array('text' => sprintf(TEXT_NO_CHILD_CATEGORIES_OR_PRODUCTS, $parent_categories_name));
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
</div>


<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>
