<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

if (!defined('OOS_UPDATE_PATH')) {
    define('OOS_UPDATE_PATH', OOS_EXPORT_PATH);
}


function walk($item1)
{
    $item1 = str_replace('	', '|', (string) $item1);
    $item1 = str_replace('"', '', $item1);

    $item1 = str_replace("\n", '', $item1);
    $item1 = str_replace("\r", '', $item1);

    //$item1 = str_replace("",'',$item1);
    $item1 = str_replace('"', '\"', $item1);
    $item1 = str_replace("'", '\"', $item1);

    // echo $item1."<br>";
    $item1 = chop($item1);

    echo $item1."<br>";
    $items = explode("|", $item1);

    $products_id = $items[0];
    if (isset($products_id) && is_numeric($products_id)) {
        $products_model = $items[1];
        $products_name = $items[2];
        $products_tax_class_id = $items[3];
        $products_status = $items[4];
        $products_net_price = $items[5];
        $products_net_price = str_replace(',', '.', $products_net_price);
        $products_gross_price = $items[6];
        $products_gross_price = str_replace(',', '.', $products_gross_price);




        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $tax_ratestable = $oostable['tax_rates'];
        $query = "SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '" . intval($products_tax_class_id) . "'";
        $tax = $dbconn->GetOne($query);


        if (($products_net_price = 0) || ($products_net_price = '') || empty($products_net_price)) {
            $products_net_price = ($products_gross_price/($tax+100)*100);
            $products_net_price = oos_round($products_net_price, 4);
        }


        /*

                    $featuredtable = $oostable['featured'];
                    $sql = "SELECT f.products_id
                            FROM $featuredtable f
                        WHERE f.products_id = '" . intval($products_id) . "'
                        AND f.status = '1'";
                    $featured_result = $dbconn->Execute($sql);

                    if (!$featured_result->RecordCount()) {

                        $specialstable = $oostable['specials'];
                        $query = "SELECT specials_id
                                FROM $specialstable
                                WHERE products_id = '" . intval($products_id) . "'
                                AND status = '1'";
                        $specials_result = $dbconn->Execute($query);

                        if (!$specials_result->RecordCount()) {
                            $products_tax_class_id = $product_info['products_tax_class_id'];
                            $tax_ratestable = $oostable['tax_rates'];
                            $query = "SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '" . intval($products_tax_class_id) . "'";
                            $tax = $dbconn->GetOne($query);
                            $price = ($products_price/($tax+100)*100);

                            # echo 'gefunden: ' . $products_model  . '<br>';

                            $products_attributestable = $oostable['products_attributes'];
                            $products_options_sql = "SELECT pa.options_values_model, pa.options_values_image
                                             FROM $products_attributestable pa
                                             WHERE pa.options_values_model = '" . $products_model . "'";
                            $products_options_result = $dbconn->Execute($products_options_sql);
                            if ($products_options_result->RecordCount()) {
                                $dbconn->Execute("UPDATE $products_attributestable set options_values_price = '" . $price . "' where options_values_model = '" . $products_model . "'");
                            } else {
                                $productstable = $oostable['products'];
                                $dbconn->Execute("UPDATE $productstable set products_price = '" . $products_price . "' products_model = '" . $products_model . "' where products_id = '" . $products_id . "'");
                            }
                        } else {
                            # echo 'Sonderangebot gefunden: ' . $products_model  . '<br>';
                        }
                    } else {
                        # echo 'Top-Angebot gefunden: ' . $products_model  . ' <br>';
                    }
                }
        */

        // product price history
        $productstable = $oostable['products'];
        $products_price_sql = "SELECT products_price
							FROM $productstable 
							WHERE products_id = '" . intval($products_id) . "'";
        $products_price_result = $dbconn->Execute($products_price_sql);
        $products_price = $products_price_result->fields;
        $old_products_price = $products_price['products_price'];
        $new_products_price = $products_net_price;

        $epsilon = 0.00001;

        // https://www.php.net/manual/en/language.types.float.php#language.types.float.casting
        if (abs($old_products_price-$new_products_price) > $epsilon) {
            $sql_price_array = ['products_id' => intval($products_id), 'products_price' => oos_db_input($products_net_price), 'date_added' => 'now()'];
            oos_db_perform($oostable['products_price_history'], $sql_price_array);
        }

        $productstable = $oostable['products'];
        $dbconn->Execute(
            "UPDATE $productstable 
							SET products_price = '" . oos_db_input($products_net_price) . "', 
								products_model = '" . oos_db_input($products_model) . "',
								products_status = '" . intval($products_status) . "' 
							WHERE products_id = '" . intval($products_id) . "'"
        );
    }
}

if (isset($_GET['split']) && !empty($_GET['split'])) {
    $split = oos_db_prepare_input($_GET['split']);
}


if (isset($_FILES['files'])) {
    foreach ($_FILES['files']['name'] as $key => $name) {
        if (empty($name)) {
            // purge empty slots
            unset($_FILES['files']['name'][$key]);
            unset($_FILES['files']['type'][$key]);
            unset($_FILES['files']['tmp_name'][$key]);
            unset($_FILES['files']['error'][$key]);
            unset($_FILES['files']['size'][$key]);
        }
    }
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
<!-- body_text //-->
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>

<?php
if (isset($_FILES['usrfl'])) {
    if (is_uploaded_file($_FILES['usrfl']['tmp_name'])) {
        oos_get_copy_uploaded_file($_FILES['usrfl'], OOS_UPDATE_PATH);

        echo "<p class=smallText>";
        echo TEXT_FILE_UPLOADED . '<br>';
        echo TEXT_TEMPORARY_FILENAME . $_FILES['usrfl']['tmp_name'] . '<br>';
        echo TEXT_USER_FILENAME . $_FILES['usrfl']['name'] . '<br>';
        echo TEXT_SIZE . $_FILES['usrfl']['size'] . '<br>';
        echo '<br>';
        // echo '<br>products_id | products_model | products_name | products_tax_class_id | products_status | products_net_price | products_gross_price';
        echo '<br>';

        // get the entire file into an array
        $readed = file(OOS_UPDATE_PATH . $_FILES['usrfl']['name']);
        $nCounter = 0;

        foreach ($readed as $arr) {
            walk($arr);
            $nCounter++;
        }
        echo '<br><br>';
        echo TEXT_TOTAL_RECORDS . $nCounter;
    }
}
?>

<?php echo oos_draw_form('id', 'update_product', $aContents['import_excel'], '&split=0', 'post', false, 'enctype="multipart/form-data"'); ?>

              <p>
                <div align = "left">
                <p><b><?php echo TEXT_HEADING; ?></b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <?php echo oos_submit_button(BUTTON_UPDATE); ?>
                </p>
              </div>

      </form>


</td>
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
        <span>&copy; <?php echo date('Y'); ?> - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
    </footer>
</div>


<?php
    require 'includes/bottom.php';
    require 'includes/nice_exit.php';
?>