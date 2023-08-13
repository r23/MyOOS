<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';


function oos_set_featured_status($featured_id, $status)
{

    // Get database information
    $dbconn =& oosDBGetConn();
    $oostable =& oosDBGetTables();


    if ($status == '1') {
        $featuredtable = $oostable['featured'];
        return $dbconn->Execute("UPDATE $featuredtable SET status = '1', expires_date = NULL, date_status_change = now() WHERE featured_id = '" . intval($featured_id) . "'");
    } elseif ($status == '0') {
        $featuredtable = $oostable['featured'];
        return $dbconn->Execute("UPDATE $featuredtable SET status = '0', date_status_change = now() WHERE featured_id = '" . intval($featured_id) . "'");
    } else {
        return -1;
    }
}

require 'includes/classes/class_currencies.php';
$currencies = new currencies();

$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$fID = filter_input(INPUT_GET, 'fID', FILTER_VALIDATE_INT);

if (!empty($action)) {
    switch ($action) {
    case 'setflag':
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            oos_set_featured_status($_GET['id'], $_GET['flag']);
        }

        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'fID=' . intval($_GET['id']) . '&page=' . $nPage));
        break;

    case 'insert':
        $expires_date = oos_db_prepare_input($_POST['expires_date']);

        $featuredtable = $oostable['featured'];
        $dbconn->Execute("INSERT INTO $featuredtable (products_id, featured_date_added, expires_date, status) VALUES ('" . intval($_POST['products_id']) . "', now(), '" . oos_db_input($expires_date) . "', '1')");
        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'page=' . $nPage));
        break;

    case 'update':
        $featured_id = oos_db_prepare_input($_POST['featured_id']);
        $expires_date = oos_db_prepare_input($_POST['expires_date']);

        $featuredtable = $oostable['featured'];
        $dbconn->Execute("UPDATE $featuredtable SET featured_last_modified = now(), expires_date = '" . oos_db_input($expires_date) . "' WHERE featured_id = '" . intval($featured_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . intval($featured_id)));
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
    $form_action = 'insert';
    if (($action == 'edit') && isset($_GET['fID'])) {
        $form_action = 'update';

        $featuredtable = $oostable['featured'];
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $query = "SELECT p.products_id, p.products_image, pd.products_name, f.expires_date
                FROM $productstable p,
                     $products_descriptiontable pd,
                     $featuredtable f
                WHERE p.products_id = pd.products_id AND
                     pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                      p.products_id = f.products_id AND
                      f.featured_id = '" . intval($fID) . "'
                   ORDER BY pd.products_name";
        $product = $dbconn->GetRow($query);

        $sInfo = new objectInfo($product);
    } elseif (($action == 'new') && isset($_GET['pID'])) {
        $productstable = $oostable['products'];
        $products_descriptiontable = $oostable['products_description'];
        $sql = "SELECT p.products_id, p.products_image, pd.products_name
              FROM $productstable p,
                   $products_descriptiontable pd
              WHERE p.products_id = pd.products_id AND
                    pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND
                    p.products_id = '" . intval($_GET['pID']) . "'";
        $product = $dbconn->GetRow($sql);

        $sInfo = new objectInfo($product);
    } else {
        $sInfo = new objectInfo(array());

        $featured_array = [];
        $featuredtable = $oostable['featured'];
        $productstable = $oostable['products'];
        $featured_result = $dbconn->Execute("SELECT p.products_id FROM $productstable p, $featuredtable f WHERE f.products_id = p.products_id");
        while ($featured = $featured_result->fields) {
            $featured_array[] = $featured['products_id'];

            // Move that ADOdb pointer!
            $featured_result->MoveNext();
        }
    } ?>
<!-- body_text //-->
    <div class="card card-default">
        <div class="card-header"><?php echo HEADING_TITLE; ?></div>
            <div class="card-body">

                <form name="new_feature" <?php echo 'action="' . oos_href_link_admin($aContents['featured'], oos_get_all_get_params(array('action', 'info', 'fID')) . 'action=' . $form_action) . '"'; ?> method="post">
    <?php
    if ($form_action == 'update') {
        echo oos_draw_hidden_field('featured_id', intval($fID));
    } elseif (isset($_GET['pID'])) {
        echo oos_draw_hidden_field('products_id', $sInfo->products_id);
    } ?>

    <?php
    if (!empty($sInfo->products_name)) {
        echo '<br><a href="' . oos_catalog_link($aCatalog['product_info'], 'products_id=' . $sInfo->products_id) . '" target="_blank" rel="noopener">' . product_info_image($sInfo->products_image, $sInfo->products_name) . '</a><br>';
    } else {
        ?>
                    <fieldset>
                        <div class="form-group row mb-3 mt-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_FEATURED_PRODUCT; ?></label>
                           <div class="col-md-10">
                                <?php echo oos_draw_products_pull_down('products_id', $featured_array); ?>
                           </div>
                        </div>
                     </fieldset>
        <?php
    } ?>
                     <fieldset>
                        <div class="form-group row mb-3">
                           <label class="col-md-2 col-form-label mb-2"><?php echo TEXT_FEATURED_EXPIRES_DATE; ?></label>
                           <div class="col-xl-6 col-10">
                              <div class="input-group date" id="datetimepicker1">
                                 <input class="form-control" type="text" name="expires_date" value="<?php echo(isset($sInfo->expires_date) ? $sInfo->expires_date : ''); ?>">

                                 <span class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </fieldset>
        
                    <div class="clearfix mt-120"></div>
                    
                    <div class="text-right mt-3">
                        <?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $fID) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>'; ?>
                        <?php echo(($form_action == 'insert') ? oos_submit_button(BUTTON_INSERT) : oos_submit_button(BUTTON_UPDATE)); ?>
                    </div>                    
                    
            </form>    
        </div>
    </div>

    <?php
} else {
        ?>
    <div class="table-responsive">
        <table class="table w-100">
          <tr>
            <td valign="top">
            
                <table class="table table-striped w-100">
                    <thead class="thead-dark">
                        <tr>
                            <th><?php echo TABLE_HEADING_PRODUCTS; ?></th>
                            <th class="text-right">&nbsp;</th>
                            <th class="text-right"><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
    <?php
    $featured_result_raw = "SELECT p.products_id, pd.products_name, s.featured_id, s.featured_date_added, s.featured_last_modified, s.expires_date, s.date_status_change, s.status FROM " . $oostable['products'] . " p, " . $oostable['featured'] . " s, " . $oostable['products_description'] . " pd WHERE p.products_id = pd.products_id AND pd.products_languages_id = '" . intval($_SESSION['language_id']) . "' AND p.products_id = s.products_id ORDER BY pd.products_name";
        $featured_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $featured_result_raw, $featured_result_numrows);
        $featured_result = $dbconn->Execute($featured_result_raw);
        while ($featured = $featured_result->fields) {
            if ((!isset($_GET['fID']) || (isset($_GET['fID']) && ($_GET['fID'] == $featured['featured_id']))) && !isset($sInfo)) {
                $productstable = $oostable['products'];
                $products_result = $dbconn->Execute("SELECT products_image FROM " . $oostable['products'] . " WHERE products_id = '" . $featured['products_id'] . "'");
                $products = $products_result->fields;
                $sInfo_array = array_merge($featured, $products);
                $sInfo = new objectInfo($sInfo_array);
            }

            if (isset($sInfo) && is_object($sInfo) && ($featured['featured_id'] == $sInfo->featured_id)) {
                echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=edit') . '\'">' . "\n";
            } else {
                echo '                  <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured['featured_id']) . '\'">' . "\n";
            } ?>
                <td><?php echo $featured['products_name']; ?></td>
                <td  align="right">&nbsp;</td>
                <td  align="right">
        <?php
        if ($featured['status'] == '1') {
            echo '<i class="fa fa-circle text-success" title="' . IMAGE_ICON_STATUS_GREEN . '"></i>&nbsp;<a href="' . oos_href_link_admin($aContents['featured'], 'action=setflag&flag=0&id=' . $featured['featured_id']) . '"><i class="fa fa-circle-notch text-danger" title="' . IMAGE_ICON_STATUS_RED_LIGHT . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['featured'], 'action=setflag&flag=1&id=' . $featured['featured_id']) . '"><i class="fa fa-circle-notch text-success" title="' . IMAGE_ICON_STATUS_GREEN_LIGHT . '"></i></a>&nbsp;<i class="fa fa-circle text-danger" title="' . IMAGE_ICON_STATUS_RED . '"></i>';
        } ?></td>
                <td class="text-right">
        <?php
        if (isset($sInfo) && is_object($sInfo) && ($featured['featured_id'] == $sInfo->featured_id)) {
            echo '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=edit') . '"><i class="fas fa-pencil-alt" title="' . BUTTON_EDIT . '"></i></a>
				<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=delete') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured['featured_id']. '&action=edit') . '"><i class="fas fa-pencil-alt" title="' . BUTTON_EDIT . '"></i></a>
				<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $featured['featured_id'] . '&action=delete') . '"><i class="fa fa-trash" title="' .  BUTTON_DELETE . '"></i></a>';
        } ?>
            &nbsp;</td>                

              </tr>
        <?php
        // Move that ADOdb pointer!
        $featured_result->MoveNext();
        } ?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellpadding="0" cellspacing="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $featured_split->display_count($featured_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_FEATURED); ?></td>
                    <td class="smallText" align="right"><?php echo $featured_split->display_links($featured_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
    <?php
    if (empty($action)) {
        ?>
                  <tr> 
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_PRODUCT) . '</a>'; ?></td>
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
        $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_FEATURED . '</b>');

        $contents = array('form' => oos_draw_form('id', 'featured', $aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=deleteconfirm', 'post', false));
        $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
        $contents[] = array('text' => '<br><b>' . $sInfo->products_name . '</b>');
        $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');

        break;

    default:
        if (isset($sInfo) && is_object($sInfo)) {
            $heading[] = array('text' => '<b>' . $sInfo->products_name . '</b>');

            $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['featured'], 'page=' . $nPage . '&fID=' . $sInfo->featured_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>');
            $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($sInfo->featured_date_added));
            $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($sInfo->featured_last_modified));
            $contents[] = array('align' => 'center', 'text' => '<br>' . product_info_image($sInfo->products_image, $sInfo->products_name));
            $contents[] = array('text' => '<br>' . TEXT_INFO_EXPIRES_DATE . ' <b>' . oos_date_short($sInfo->expires_date) . '</b>');
            $contents[] = array('text' => '' . TEXT_INFO_STATUS_CHANGE . ' ' . oos_date_short($sInfo->date_status_change));
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
    require 'includes/nice_exit.php';
?>