<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2018 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

  function oos_draw_products_pull_down($name, $parameters = '', $exclude = '') {
    global $currencies;

    if ($exclude == '') {
      $exclude = array();
    }
    $select_string = '<select name="' . $name . '"';
    if ($parameters) {
      $select_string .= ' ' . $parameters;
    }
    $select_string .= '>';

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();

    $productsstable = $oostable['products'];
    $products_descriptionstable = $oostable['products_description'];
    $query = "SELECT p.products_id, pd.products_name, p.products_price
              FROM $productsstable p,
                   $products_descriptionstable pd
              WHERE p.products_status >= '1' AND 
                    p.products_id = pd.products_id AND 
                   pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'
              ORDER BY products_name";
    $result = $dbconn->Execute($query);

    while ($products = $result->fields) {
      if (!oos_in_array($products['products_id'], $exclude)) {
        $select_string .= '<option value="' . $products['products_id'] . '">' . $products['products_name'] . ' (' . $currencies->format($products['products_price']) . ')</option>';
      }

      // Move that ADOdb pointer!
      $result->MoveNext();
    }

    // Close result set
    $result->Close();

    $select_string .= '</select>';

    return $select_string;
  }


  function oos_set_featured_status($featured_id, $status) {

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();


    if ($status == '1') {
      $featuredtable = $oostable['featured'];
      return $dbconn->Execute("UPDATE $featuredtable SET status = '1', expires_date = NULL, date_status_change = now() WHERE featured_id = '" . (int)$featured_id . "'");
    } elseif ($status == '0') {
      $featuredtable = $oostable['featured'];
      return $dbconn->Execute("UPDATE $featuredtable SET status = '0', date_status_change = now() WHERE featured_id = '" . (int)$featured_id . "'");
    } else {
      return -1;
    }
  }

$language = $_SESSION['language'];

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);    
$action = (isset($_GET['action']) ? $_GET['action'] : '');


  if (!empty($action)) {
    switch ($action) {
      case 'setflag':
        oos_set_featured_status($_GET['id'], $_GET['flag']);
        oos_redirect_admin(oos_href_link_admin($aContents['featured'], ''));
        break;

      case 'insert':
		$expires_date = oos_db_prepare_input($_POST['expires_date']);

        $featuredtable = $oostable['featured'];
        $dbconn->Execute("INSERT INTO $featuredtable (products_id, featured_date_added, expires_date, status) VALUES ('" . $_POST['products_id'] . "', now(), '" . $expires_date . "', '1')");
        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'page=' . $nPage));
        break;

      case 'update':
        $expires_date = oos_db_prepare_input($_POST['expires_date']);

        $featuredtable = $oostable['featured'];
        $dbconn->Execute("UPDATE $featuredtable SET featured_last_modified = now(), expires_date = '" . $expires_date . "' WHERE featured_id = '" . $_POST['featured_id'] . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured_id));
        break;

      case 'deleteconfirm':
        $featured_id = oos_db_prepare_input($_GET['fID']);

        $featuredtable = $oostable['featured'];
        $dbconn->Execute("DELETE FROM $featuredtable WHERE featured_id = '" . oos_db_input($featured_id) . "'");

        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'page=' . $nPage));
        break;
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
			<div class="row wrapper gray-bg page-heading">
				<div class="col-lg-12">
					<h2><?php echo HEADING_TITLE; ?></h2>
					<ol class="breadcrumb">
						<li>
							<a href="<?php echo oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
						</li>
						<li>
							<a href="<?php echo oos_href_link_admin(oos_selected_file('catalog.php'), 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
						</li>
						<li class="active">
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

<?php
  if ( ($action == 'new') || ($action == 'edit') ) {
    $form_action = 'insert';
    if ( ($action == 'edit') && ($_GET['fID']) ) {
      $form_action = 'update';

      $featuredtable = $oostable['featured'];
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $query = "SELECT p.products_id, pd.products_name, f.expires_date
                FROM $productstable p,
                     $products_descriptiontable pd,
                     $featuredtable f
                WHERE p.products_id = pd.products_id AND
                     pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                      p.products_id = f.products_id AND
                      f.featured_id = '" . intval($_GET['fID']) . "'
                   ORDER BY pd.products_name";
      $product = $dbconn->GetRow($query);

      $sInfo = new objectInfo($product);
    } elseif ( ($action == 'new') && isset($_GET['pID']) ) {
      $productstable = $oostable['products'];
      $products_descriptiontable = $oostable['products_description'];
      $sql = "SELECT p.products_id, pd.products_name
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    p.products_id = '" . intval($_GET['pID']) . "'";
      $product = $dbconn->GetRow($sql);

      $sInfo = new objectInfo($product);
    } else {
      $sInfo = new objectInfo(array());

// create an array of featured products, which will be excluded from the pull down menu of products
// (when creating a new featured product)
      $featured_array = array();
      $featuredtable = $oostable['featured'];
      $productstable = $oostable['products'];
      $featured_result = $dbconn->Execute("SELECT p.products_id FROM $productstable p, $featuredtable f WHERE f.products_id = p.products_id");
      while ($featured = $featured_result->fields) {
        $featured_array[] = $featured['products_id'];

        // Move that ADOdb pointer!
        $featured_result->MoveNext();
      }

      // Close result set
      $featured_result->Close();

    }
?>
      <tr><form name="new_feature" <?php echo 'action="' . oos_href_link_admin($aContents['featured'], oos_get_all_get_params(array('action', 'info', 'fID')) . 'action=' . $form_action) . '"'; ?> method="post">
<?php
  if ($form_action == 'update') {
    echo oos_draw_hidden_field('featured_id', $_GET['fID']);
  } elseif (isset($_GET['pID']) ) {
    echo oos_draw_hidden_field('products_id', $sInfo->products_id);
  }
?>
        <td><br /><table border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main"><?php echo TEXT_FEATURED_PRODUCT; ?>&nbsp;</td>
            <td class="main"><?php echo ($sInfo->products_name) ? $sInfo->products_name : oos_draw_products_pull_down('products_id', 'style="font-size:10px"', $featured_array); echo oos_draw_hidden_field('products_price', $sInfo->products_price); ?></td>
          </tr>
          <tr>
            <td class="main"><?php echo TEXT_FEATURED_EXPIRES_DATE; ?>&nbsp;</td>
            <td class="main">
				<div class="input-group date" id="datetimepicker1">
					<input class="form-control" type="text" name="expires_date" value="<?php echo $sInfo->expires_date; ?>">
					<span class="input-group-addon">
						<span class="fa fa-calendar"></span>
					</span>
				</div>			
			</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td class="main" align="right" valign="top"><br /><?php echo (($form_action == 'insert') ? oos_submit_button('insert', BUTTON_INSERT) : oos_submit_button('update', IMAGE_UPDATE)). '&nbsp;&nbsp;&nbsp;<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $_GET['fID']) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>'; ?></td>
          </tr>
        </table></td>
      </form></tr>
<?php
  } else {
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="right">&nbsp;</td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_STATUS; ?></td>
                <td class="dataTableHeadingContent" align="right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
              </tr>
<?php
    $featured_result_raw = "SELECT p.products_id, pd.products_name, s.featured_id, s.featured_date_added, s.featured_last_modified, s.expires_date, s.date_status_change, s.status FROM " . $oostable['products'] . " p, " . $oostable['featured'] . " s, " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND p.products_id = s.products_id ORDER BY pd.products_name";
    $featured_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $featured_result_raw, $featured_result_numrows);
    $featured_result = $dbconn->Execute($featured_result_raw);
    while ($featured = $featured_result->fields) {
      if ( (!isset($_GET['fID']) || ($_GET['fID'] == $featured['featured_id']))  && !isset($sInfo) ) {

        $products_result = $dbconn->Execute("SELECT products_image FROM " . $oostable['products'] . " WHERE products_id = '" . $featured['products_id'] . "'");
        $products = $products_result->fields;
        $sInfo_array = array_merge($featured, $products);
        $sInfo = new objectInfo($sInfo_array);
      }

      if (isset($sInfo) && is_object($sInfo) && ($featured['featured_id'] == $sInfo->featured_id) ) {
        echo '                  <tr class="dataTableRowSelected" onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=edit') . '\'">' . "\n";
      } else {
        echo '                  <tr class="dataTableRow" onmouseover="this.className=\'dataTableRowOver\';this.style.cursor=\'hand\'" onmouseout="this.className=\'dataTableRow\'" onclick="document.location.href=\'' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured['featured_id']) . '\'">' . "\n";
      }
?>
                <td  class="dataTableContent"><?php echo $featured['products_name']; ?></td>
                <td  class="dataTableContent" align="right">&nbsp;</td>
                <td  class="dataTableContent" align="right">
<?php
      if ($featured['status'] == '1') {
        echo '<a href="' . oos_href_link_admin($aContents['featured'], 'action=setflag&flag=0&id=' . $featured['featured_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
      } else {
        echo '<a href="' . oos_href_link_admin($aContents['featured'], 'action=setflag&flag=1&id=' . $featured['featured_id']) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
      }
?></td>
                <td class="dataTableContent" align="right"><?php if ( (is_object($sInfo)) && ($featured['featured_id'] == $sInfo->featured_id) ) { echo '<button class="btn btn-info" type="button"><i class="fa fa-check"></i></button>'; } else { echo '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured['featured_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>'; } ?>&nbsp;</td>
      </tr>
<?php
      // Move that ADOdb pointer!
      $featured_result->MoveNext();
    }

    // Close result set
    $featured_result->Close();
?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0"cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $featured_split->display_count($featured_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
                    <td class="smallText" align="right"><?php echo $featured_split->display_links($featured_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
  if (empty($action)) {
?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&action=new') . '">' . oos_button('new_product', IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
                  </tr>
<?php
  }
?>
                </table></td>
              </tr>
            </table></td>
<?php
  $heading = array();
  $contents = array();

  switch ($action) {
    case 'delete':
      $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FEATURED . '</b>');

      $contents = array('form' => oos_draw_form('id', 'featured', $aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=deleteconfirm', 'post', FALSE));
      $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
      $contents[] = array('text' => '<br /><b>' . $sInfo->products_name . '</b>');
      $contents[] = array('align' => 'center', 'text' => '<br />' . oos_submit_button('delete', BUTTON_DELETE) . '&nbsp;<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id) . '">' . oos_button('cancel', BUTTON_CANCEL) . '</a>');
      break;

    default:
      if (isset($sInfo) && is_object($sInfo)) {
        $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=edit') . '">' . oos_button('edit', BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=delete') . '">' . oos_button('delete',  BUTTON_DELETE) . '</a>');
        $contents[] = array('text' => '<br />' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->featured_date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->featured_last_modified));
        $contents[] = array('align' => 'center', 'text' => '<br />' . oos_info_image($sInfo->products_image, $sInfo->products_name, SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT));

        $contents[] = array('text' => '<br />' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($sInfo->expires_date) . '</b>');
        $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change));
      }
      break;
  }
  if ( (oos_is_not_null($heading)) && (oos_is_not_null($contents)) ) {
    echo '            <td width="25%" valign="top">' . "\n";

    $box = new box;
    echo $box->infoBox($heading, $contents);

    echo '            </td>' . "\n";
  }
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