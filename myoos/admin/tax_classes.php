<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: tax_classes.php,v 1.19 2002/03/17 18:04:54 harley_vb
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

$nPage = (!isset($_GET['page']) || !is_numeric($_GET['page'])) ? 1 : intval($_GET['page']);
$action = (isset($_GET['action']) ? $_GET['action'] : '');

if (!empty($action)) {
    switch ($action) {
    case 'insert':
        $tax_class_title = oos_db_prepare_input($_POST['tax_class_title']);
        $tax_class_description = oos_db_prepare_input($_POST['tax_class_description']);

        $tax_classtable = $oostable['tax_class'];
        $dbconn->Execute("INSERT INTO $tax_classtable (tax_class_title, tax_class_description, date_added) VALUES ('" . oos_db_input($tax_class_title) . "', '" . oos_db_input($tax_class_description) . "', now())");
        oos_redirect_admin(oos_href_link_admin($aContents['tax_classes']));
        break;

    case 'save':
        $tax_class_id = oos_db_prepare_input($_GET['tID']);
        $tax_class_title = oos_db_prepare_input($_POST['tax_class_title']);
        $tax_class_description = oos_db_prepare_input($_POST['tax_class_description']);

        $tax_classtable = $oostable['tax_class'];
        $dbconn->Execute("UPDATE $tax_classtable SET tax_class_id = '" . oos_db_input($tax_class_id) . "', tax_class_title = '" . oos_db_input($tax_class_title) . "', tax_class_description = '" . oos_db_input($tax_class_description) . "', last_modified = now() WHERE tax_class_id = '" . oos_db_input($tax_class_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tax_class_id));
        break;

    case 'deleteconfirm':
        $tax_class_id = oos_db_prepare_input($_GET['tID']);

        $tax_classtable = $oostable['tax_class'];
        $dbconn->Execute("DELETE FROM $tax_classtable WHERE tax_class_id = '" . oos_db_input($tax_class_id) . "'");
        oos_redirect_admin(oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage));
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
                            <?php echo '<a href="' . oos_href_link_admin($aContents['countries'], 'selected_box=taxes') . '">' . BOX_HEADING_LOCATION_AND_TAXES . '</a>'; ?>
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
                            <th><?php echo TABLE_HEADING_TAX_CLASSES; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
<?php
  $tax_classtable = $oostable['tax_class'];
  $classes_result_raw = "SELECT tax_class_id, tax_class_title, tax_class_description, last_modified, date_added
                         FROM $tax_classtable
                         ORDER BY  tax_class_title";
  $classes_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $classes_result_raw, $classes_result_numrows);
  $classes_result = $dbconn->Execute($classes_result_raw);
while ($classes = $classes_result->fields) {
    if ((!isset($_GET['tID']) || (isset($_GET['tID']) && ($_GET['tID'] == $classes['tax_class_id']))) && !isset($tcInfo) && (substr($action, 0, 3) != 'new')) {
        $tcInfo = new objectInfo($classes);
    }

    if (isset($tcInfo) && is_object($tcInfo) && ($classes['tax_class_id'] == $tcInfo->tax_class_id)) {
        echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id . '&action=edit') . '\'">' . "\n";
    } else {
        echo'              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $classes['tax_class_id']) . '\'">' . "\n";
    } ?>
                <td><?php echo $classes['tax_class_title']; ?></td>
                <td class="text-right"><?php if (isset($tcInfo) && is_object($tcInfo) && ($classes['tax_class_id'] == $tcInfo->tax_class_id)) {
        echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
    } else {
        echo '<a href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $classes['tax_class_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
    } ?>&nbsp;</td>
              </tr>
    <?php
    // Move that ADOdb pointer!
    $classes_result->MoveNext();
}


?>
              <tr>
                <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $classes_split->display_count($classes_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_TAX_CLASSES); ?></td>
                    <td class="smallText" align="right"><?php echo $classes_split->display_links($classes_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
<?php
if (empty($action)) {
    ?>
                  <tr>
                    <td colspan="2" align="right"><?php echo '<a href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&action=new') . '">' . oos_button(IMAGE_NEW_TAX_CLASS) . '</a>'; ?></td>
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
case 'new':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_NEW_TAX_CLASS . '</b>');

    $contents = array('form' => oos_draw_form('id', 'classes', $aContents['tax_classes'], 'page=' . $nPage . '&action=insert', 'post', false));
    $contents[] = array('text' => TEXT_INFO_INSERT_INTRO);
    $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_TITLE . '<br>' . oos_draw_input_field('tax_class_title'));
    $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . oos_draw_input_field('tax_class_description'));
    $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_INSERT) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
    break;

case 'edit':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_EDIT_TAX_CLASS . '</b>');

    $contents = array('form' => oos_draw_form('id', 'classes', $aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id . '&action=save', 'post', false));
    $contents[] = array('text' => TEXT_INFO_EDIT_INTRO);
    $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_TITLE . '<br>' . oos_draw_input_field('tax_class_title', $tcInfo->tax_class_title));
    $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . oos_draw_input_field('tax_class_description', $tcInfo->tax_class_description));
    $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_UPDATE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
    break;

case 'delete':
    $heading[] = array('text' => '<b>' . TEXT_INFO_HEADING_DELETE_TAX_CLASS . '</b>');

    $contents = array('form' => oos_draw_form('id', 'classes', $aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id . '&action=deleteconfirm', 'post', false));
    $contents[] = array('text' => TEXT_INFO_DELETE_INTRO);
    $contents[] = array('text' => '<br><b>' . $tcInfo->tax_class_title . '</b>');
    $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . '&nbsp;<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
    break;

default:
    if (isset($tcInfo) && is_object($tcInfo)) {
        $heading[] = array('text' => '<b>' . $tcInfo->tax_class_title . '</b>');

        $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['tax_classes'], 'page=' . $nPage . '&tID=' . $tcInfo->tax_class_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>');
        $contents[] = array('text' => '<br>' . TEXT_INFO_DATE_ADDED . ' ' . oos_date_short($tcInfo->date_added));
        $contents[] = array('text' => '' . TEXT_INFO_LAST_MODIFIED . ' ' . oos_date_short($tcInfo->last_modified));
        $contents[] = array('text' => '<br>' . TEXT_INFO_CLASS_DESCRIPTION . '<br>' . $tcInfo->tax_class_description);
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
?>