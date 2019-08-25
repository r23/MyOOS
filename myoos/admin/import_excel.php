<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2019 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  define('OOS_VALID_MOD', 'yes');
  require 'includes/main.php';

  if (!defined('OOS_UPDATE_PATH')) {
    define('OOS_UPDATE_PATH', OOS_EXPORT_PATH );
  }


  function walk( $item1 ) {

   $item1 = str_replace('	','|',$item1);
   $item1 = str_replace('"','',$item1);

   $item1 = str_replace("\n",'',$item1);
   $item1 = str_replace("\r",'',$item1);

   //$item1 = str_replace("",'',$item1);
   $item1 = str_replace('"','\"',$item1);
   $item1 = str_replace("'",'\"',$item1);

   // echo $item1."<br>";
   $item1 = chop($item1);

   echo $item1."<br>";
   $items = explode("|", $item1);

   $products_id = $items[0];
   $products_model = $items[1];
   $products_name = $items[2];
   $products_tax_class_id = $items[3];
   $products_status = $items[4];
   $products_price = $items[5];

   $dbconn =& oosDBGetConn();
   $oostable =& oosDBGetTables();

   $tax_ratestable = $oostable['tax_rates'];
   $query = "SELECT tax_rate FROM $tax_ratestable WHERE tax_class_id = '" . intval($products_tax_class_id) . "'";
   $tax = $dbconn->GetOne($query);

   $price = ($products_price/($tax+100)*100);

   $productstable = $oostable['products'];
   $dbconn->Execute("UPDATE $productstable set products_price = '" . $price . "', products_status = '" . intval($products_status) . "' where products_id = '" . intval($products_id) . "'");

  }

  if (isset($_GET['split']) && !empty($_GET['split'])) {
    $split = $_GET['split'];
  }

  if (isset($_FILES['usrfl']) && !empty($_FILES['usrfl'])) {
    $usrfl = $_FILES['usrfl'];
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
  if (is_uploaded_file($usrfl['tmp_name'])) {

    oos_get_copy_uploaded_file($usrfl, OOS_UPDATE_PATH);

    echo "<p class=smallText>";
    echo 'File uploaded<br />';
    echo 'Temporary filename:: ' . $usrfl['tmp_name'] . '<br />';
    echo 'User filename: ' . $usrfl['name'] . '<br />';
    echo 'Size: ' . $usrfl['size'] . '<br />';
    echo '<br><br>';
    echo '<br>products_id | products_model | products_name | products_tax_class_id | products_status | products_price';
    echo '<br><br>';

    // get the entire file into an array
    $readed = file(OOS_UPDATE_PATH . $usrfl['name']);

    foreach ($readed as $arr) {
      walk($arr);
      $Counter++;
    }
    echo '<br><br>';
    echo "Total Records inserted......".$Counter."<br>";
  }
?>

<?php echo oos_draw_form('id', 'update_product', $aContents['import_excel'], '&split=0', 'post', FALSE, 'enctype="multipart/form-data"'); ?>

              <p>
                <div align = "left">
                <p><b>Upload Produkt-Datei</b></p>
                <p>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
                  <p></p>
                  <input name="usrfl" type="file" size="50">
                  <input type="submit" name="buttoninsert" value="UPDATE" ><br />
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
		<span>&copy; 2019 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>