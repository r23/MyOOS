<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: whos_online.php,v 1.30 2002/11/22 14:45:49 dgw_
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

$xx_mins_ago = (time() - 900);

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

 /**
  * Return session_save_path
  *
  * @private
  */
  function oos_session_save_path($sPath = '') {
     if (!empty($sPath)) {
       return session_save_path($sPath);
     } else {
       return session_save_path();
     }
   }


require 'includes/classes/class_currencies.php';
$currencies = new currencies();

require '../includes/classes/class_shopping_cart.php';

// remove entries that have expired
  $whos_onlinetable = $oostable['whos_online'];
  $dbconn->Execute("DELETE FROM $whos_onlinetable WHERE time_last_click < '" . $xx_mins_ago . "'");

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
							<?php echo '<a href="' . oos_href_link_admin($aContents['mail'], 'selected_box=tools') . '">' . BOX_HEADING_TOOLS . '</a>'; ?>
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
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
			
				<table class="table table-striped w-100">
					<thead>
						<tr>
							<td><?php echo TABLE_HEADING_CATEGORIES_PRODUCTS; ?></td>
							<td><?php echo TABLE_HEADING_MANUFACTURERS; ?></td>
							<td align="center"><?php echo TABLE_HEADING_STATUS; ?></td>
							<td align="center"><?php echo TABLE_HEADING_PRODUCT_SORT; ?></td>
							<td align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
						</tr>	
					</thead>
			
			<table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ONLINE; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_CUSTOMER_ID; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_FULL_NAME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_IP_ADDRESS; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_ENTRY_TIME; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_LAST_CLICK; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_LAST_PAGE_URL; ?>&nbsp;</td>
              </tr>
<?php
  $whos_onlinetable = $oostable['whos_online'];
  $sql = "SELECT customer_id, full_name, ip_address, time_entry,
                 time_last_click, last_page_url, session_id
          FROM $whos_onlinetable";
  $whos_online_result = $dbconn->Execute($sql);
  while ($whos_online = $whos_online_result->fields) {
    $time_online = (time() - $whos_online['time_entry']);
    if ((!isset($_GET['info']) || (isset($_GET['info']) && ($_GET['info'] == $whos_online['session_id']))) && !isset($info)) {
      $info = $whos_online['session_id'];
    }
    if ($whos_online['session_id'] == $info) {
      echo '              <tr class="dataTableRowSelected">' . "\n";
    } else {
      echo '              <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['whos_online'], oos_get_all_get_params(array('info', 'action')) . 'info=' . $whos_online['session_id']) . '\'">' . "\n";
    }
?>
                <td class="dataTableContent"><?php echo gmdate('H:i:s', $time_online); ?></td>
                <td class="dataTableContent" align="center"><?php echo $whos_online['customer_id']; ?></td>
                <td class="dataTableContent"><?php echo $whos_online['full_name']; ?></td>
                <td class="dataTableContent" align="center"><?php echo $whos_online['ip_address']; ?></td>
                <td class="dataTableContent"><?php echo date('H:i:s', $whos_online['time_entry']); ?></td>
                <td class="dataTableContent" align="center"><?php echo date('H:i:s', $whos_online['time_last_click']); ?></td>
                <td class="dataTableContent"><?php if (preg_match('/^(.*)' . $session->getName() . '=[a-f,0-9]+[&]*(.*)/', $whos_online['last_page_url'], $array)) { echo $array[1] . $array[2]; } else { echo $whos_online['last_page_url']; } ?>&nbsp;</td>
              </tr>
<?php
    // Move that ADOdb pointer!
    $whos_online_result->MoveNext();
  }

  // Close result set
  $whos_online_result->Close();
?>
              <tr>
                <td class="smallText" colspan="7"><?php echo sprintf(TEXT_NUMBER_OF_CUSTOMERS, $whos_online_result->RecordCount()); ?></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();
  if (isset($info)) {
    $heading[] = array('text' => '<b>' . TABLE_HEADING_SHOPPING_CART . '</b><br />');

      if ( (file_exists(oos_session_save_path() . '/sess_' . $info)) && (filesize(oos_session_save_path() . '/sess_' . $info) > 0) ) {
        $session_data = file(oos_session_save_path() . '/sess_' . $info);
        $session_data = trim(implode('', $session_data));
      }

    $currency = unserialize(oos_get_serialized_variable($session_data, 'currency', 'string'));

    $cart = unserialize(oos_get_serialized_variable($session_data, 'cart', 'object'));

    if (isset($cart) && is_object($cart)) {
      $products = $cart->get_products();
      for ($i = 0, $n = count($products); $i < $n; $i++) {
        $contents[] = array('text' => $products[$i]['quantity'] . ' x ' . $products[$i]['name']);
      }

      if (count($products) > 0) {
        $contents[] = array('text' => '');
        $contents[] = array('align' => 'right', 'text'  => TEXT_SHOPPING_CART_SUBTOTAL . ' ' . $currencies->format($cart->show_total(), true, $currency));
      } else {
        $contents[] = array('text' => '&nbsp;');
      }
    }
  }


    if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
?>
	<td width="25%" valign="top">
		<table class="table table-striped table-dark">
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
		<span>&copy; 2018 - <a href="https://www.oos-shop.de" target="_blank" rel="noopener">MyOOS [Shopsystem]</a></span>
	</footer>
</div>


<?php 
	require 'includes/bottom.php';
	require 'includes/nice_exit.php';
?>