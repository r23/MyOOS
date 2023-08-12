<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: information.php,v 1.51 2003/01/29 23:21:48 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/functions/function_informations.php';


$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$iID = (isset($_GET['iID']) ? intval($_GET['iID']) : 0);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

if (!empty($action)) {
    switch ($action) {
    case 'setflag':
        if (isset($_GET['flag']) && ($_GET['flag'] == '0')) {
            $dbconn->Execute(
                "UPDATE " . $oostable['information'] . " 
                         SET status = '0'
                         WHERE information_id = '" . intval($iID) . "'"
            );
        } elseif (isset($_GET['flag']) && ($_GET['flag'] == '1')) {
            $dbconn->Execute(
                "UPDATE " . $oostable['information'] . " 
                         SET status = '1'
                         WHERE information_id = '" . intval($iID) . "'"
            );
        }
                oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $nPage. '&iID=' . $_GET['iID']));
        break;

    case 'insert':
    case 'save':
          $nStatus = 1;
          $sort_order = oos_db_prepare_input($_POST['sort_order']);

        if (isset($_POST['information_id'])) {
            $information_id = oos_db_prepare_input($_POST['information_id']);
        }

        if ((isset($_GET['iID'])) && ($information_id == '')) {
            $information_id = intval($_GET['iID']);
        }

          $sql_data_array = [];
          $sql_data_array = array('sort_order' => $sort_order);

        if ($action == 'insert') {
            $insert_sql_data = [];
            $insert_sql_data = array('date_added' => 'now()',
                                    'status' => 1);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            oos_db_perform($oostable['information'], $sql_data_array);

            $information_id = $dbconn->Insert_ID();
        } elseif ($action == 'save') {
            $update_sql_data = array('last_modified' => 'now()');

            $sql_data_array = array_merge($sql_data_array, $update_sql_data);

            oos_db_perform($oostable['information'], $sql_data_array, 'UPDATE', "information_id = '" . intval($information_id) . "'");
        }

          $aLanguages = oos_get_languages();
          $nLanguages = count($aLanguages);

        for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
            $language_id = $aLanguages[$i]['id'];

            $sql_data_array = array('information_name' => oos_db_prepare_input($_POST['information_name'][$language_id]),
                                    'information_heading_title' => oos_db_prepare_input($_POST['information_heading_title'][$language_id]),
                                    'information_description' => oos_db_prepare_input($_POST['information_description'][$language_id]));

            if ($action == 'insert') {
                $insert_sql_data = array('information_id' => $information_id,
                                        'information_languages_id' => $language_id);

                $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                oos_db_perform($oostable['information_description'], $sql_data_array);
            } elseif ($action == 'save') {
                oos_db_perform($oostable['information_description'], $sql_data_array, 'UPDATE', "information_id = '" . intval($information_id) . "' AND information_languages_id = '" . intval($language_id) . "'");
            }
        }

          oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $information_id));
        break;

    case 'deleteconfirm':

        if ($iID > 6) {
            $informationtable = $oostable['information'];
            $dbconn->Execute("DELETE FROM $informationtable WHERE information_id = '" . intval($iID) . "'");

            $information_descriptiontable = $oostable['information_description'];
            $dbconn->Execute("DELETE FROM $information_descriptiontable WHERE information_id = '" . intval($iID) . "'");
        }

        oos_redirect_admin(oos_href_link_admin($aContents['information'], 'page=' . $nPage));
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
<?php

if ($action == 'new' || $action == 'edit') {
    $parameters = array('information_id' => '',
                        'information_name' => '',
                        'information_heading_title' => '',
                        'information_description' => '',
                        'sort_order' => '',
                        'date_added' => '',
                        'status' => 1,
                        'last_modified' => '');
    $iInfo = new objectInfo($parameters);

    if (isset($_GET['iID']) && empty($_POST)) {
        $informationtable = $oostable['information'];
        $information_descriptiontable = $oostable['information_description'];
        $query = "SELECT i.information_id, id.information_name, id.information_heading_title, id.information_description, i.sort_order, i.date_added, i.last_modified, i.status 
                              FROM $informationtable i,
                                   $information_descriptiontable id
                              WHERE i.information_id = '" . intval($iID) . "' AND
									i.information_id = id.information_id AND
                                    id.information_languages_id = '" . intval($_SESSION['language_id']) . "'";
        $information_result = $dbconn->Execute($query);
        $information = $information_result->fields;

        $iInfo = new objectInfo($information);
    }

    $aLanguages = oos_get_languages();
    $nLanguages = count($aLanguages);

    $form_action = (isset($_GET['iID'])) ? 'save' : 'insert';
    $text_new_or_edit = ($action=='new') ? TEXT_HEADING_NEW_INFORMATION : TEXT_HEADING_EDIT_INFORMATION; ?>
<script src="js/ckeditor/ckeditor.js"></script>


    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo $text_new_or_edit; ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                </li>
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['information'], 'selected_box=information') . '">' . BOX_HEADING_INFORMATION . '</a>'; ?>
                </li>
                <li class="breadcrumb-item active">
                    <strong><?php echo $text_new_or_edit; ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <!-- END Breadcrumbs //-->


    <?php echo oos_draw_form('id', 'information', $aContents['information'], 'page=' . $nPage .  (!empty($iID) ? '&iID=' . intval($iID) : '') . '&action=' . $form_action, 'post', false, 'enctype="multipart/form-data"'); ?>
    <?php echo oos_draw_hidden_field('date_added', (($iInfo->date_added) ? $iInfo->date_added : date('Y-m-d')));
    echo oos_hide_session_id(); ?>

               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified" id="myTab">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#edit" aria-controls="edit" role="tab" data-toggle="tab"><?php echo TABLE_HEADING_INFORMATION; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#data" aria-controls="data" role="tab" data-toggle="tab"><?php echo TEXT_DATA; ?></a>
                     </li>
                  </ul>
                  <div class="tab-content">
                    <div class="text-right mt-3 mb-3">   
                        <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['information'], 'selected_box=information') . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>        
                        <?php echo oos_submit_button(BUTTON_SAVE); ?>
                        <?php echo oos_reset_button(BUTTON_RESET); ?>               
                    </div>                  
                    <div class="tab-pane active" id="edit" role="tabpanel">


    <?php
    for ($i=0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_INFORMATION_NAME;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('information_name[' . $aLanguages[$i]['id'] . ']', ((isset($information_name[$aLanguages[$i]['id']])) ? stripslashes($information_name[$aLanguages[$i]['id']]) : oos_get_informations_name($iInfo->information_id, $aLanguages[$i]['id'])), '', false, 'text', true, false); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i=0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_INFORMATION_HEADING_TITLE;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_input_field('information_heading_title[' . $aLanguages[$i]['id'] . ']', ((isset($information_heading_title[$aLanguages[$i]['id']])) ? stripslashes($information_heading_title[$aLanguages[$i]['id']]) : oos_get_informations_heading_title($iInfo->information_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
        <?php
    }
    for ($i=0; $i < count($aLanguages); $i++) {
        ?>
                    <fieldset>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
            echo TEXT_INFORMATION_DESCRIPTION;
        } ?></label>
        <?php if ($nLanguages > 1) {
            echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
        } ?>
                            <div class="col-lg-9">
                                <?php echo oos_draw_editor_field('information_description[' . $aLanguages[$i]['id'] . ']', 'soft', '70', '55', ((isset($information_description[$aLanguages[$i]['id']])) ? stripslashes($information_description[$aLanguages[$i]['id']]) : oos_get_informations_description($iInfo->information_id, $aLanguages[$i]['id']))); ?>
                            </div>
                        </div>
                    </fieldset>
            <script>
                CKEDITOR.replace( 'information_description[<?php echo $aLanguages[$i]['id']; ?>]');
            </script>
        <?php
    } ?>
                     </div>
                     <div class="tab-pane" id="data" role="tabpanel">
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_INFORMATION_SORT_ORDER; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('sort_order', $iInfo->sort_order); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label">ID:</label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('information_id', $iInfo->information_id, '', false, 'text', true, true, ''); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TABLE_HEADING_STATUS; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('status', 1, '', false, 'text', true, true, ''); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_DATE_ADDED; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('date_added', oos_date_short($iInfo->date_added), '', false, 'text', true, true, ''); ?></div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_LAST_MODIFIED; ?></label>
                              <div class="col-lg-10"><?php echo oos_draw_input_field('last_modified', oos_date_short($iInfo->last_modified), '', false, 'text', true, true, ''); ?></div>
                           </div>
                        </fieldset>                                        
                     </div>
                  </div>
               </div>
                    <div class="text-right mt-3 mb-3">   
                        <?php echo '<a  class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['information'], 'selected_box=information') . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>        
                        <?php echo oos_submit_button(BUTTON_SAVE); ?>
                        <?php echo oos_reset_button(BUTTON_RESET); ?>               
                    </div>                   
        </form>
    </div>
    <?php
} else {
        ?>
            <!-- Breadcrumbs //-->
            <div class="content-heading">
                <div class="col-lg-12">
                    <h2><?php echo HEADING_TITLE; ?></h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                        </li>
                        <li class="breadcrumb-item">
                            <?php echo '<a href="' . oos_href_link_admin($aContents['information'], 'selected_box=information') . '">' . BOX_HEADING_INFORMATION . '</a>'; ?>
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
                            <th><?php echo TABLE_HEADING_INFORMATION; ?></th>
                            <th><?php echo TABLE_HEADING_STATUS; ?></th>
                            <th><?php echo TABLE_HEADING_SORT; ?></th>
                            <th class="text-right"><?php echo TABLE_HEADING_ACTION; ?>&nbsp;</th>
                        </tr>    
                    </thead>
    <?php
    $informationtable = $oostable['information'];
        $information_descriptiontable = $oostable['information_description'];
        $information_result_raw = "SELECT i.information_id, id.information_name, i.sort_order, i.date_added, i.last_modified, status 
                              FROM $informationtable i,
                                   $information_descriptiontable id
                              WHERE i.information_id = id.information_id AND
                                    id.information_languages_id = '" . intval($_SESSION['language_id']) . "'
                              ORDER BY i.sort_order, id.information_name";
        $information_split = new splitPageResults($nPage, MAX_DISPLAY_SEARCH_RESULTS, $information_result_raw, $information_result_numrows);
        $information_result = $dbconn->Execute($information_result_raw);
        while ($information = $information_result->fields) {
            if ((!isset($_GET['iID']) || (isset($_GET['iID']) && ($_GET['iID'] == $information['information_id']))) && !isset($iInfo) && (substr($action, 0, 3) != 'new')) {
                $iInfo_array = array_merge($information);
                $iInfo = new objectInfo($iInfo_array);
            }

            if (isset($iInfo) && is_object($iInfo) && ($information['information_id'] == $iInfo->information_id)) {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $information['information_id'] . '&action=edit') . '\'">' . "\n";
            } else {
                echo '              <tr onclick="document.location.href=\'' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $information['information_id']) . '\'">' . "\n";
            } ?>
                <td><?php echo $information['information_name']; ?></td>
                <td>
        <?php
        if ($information['status'] == '1') {
            echo '<a href="' . oos_href_link_admin($aContents['information'], 'action=setflag&flag=0&iID=' . $information['information_id'] . '&page=' . $nPage) . '">' . oos_image(OOS_IMAGES . 'icon_status_green.gif', IMAGE_ICON_STATUS_RED_LIGHT, 10, 10) . '</a>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['information'], 'action=setflag&flag=1&iID=' . $information['information_id'] . '&page=' . $nPage) . '">' . oos_image(OOS_IMAGES . 'icon_status_red.gif', IMAGE_ICON_STATUS_GREEN_LIGHT, 10, 10) . '</a>';
        } ?></td>
                <td><?php echo $information['sort_order']; ?></td>
                <td class="text-right"><?php if (isset($iInfo) && is_object($iInfo) && ($information['information_id'] == $iInfo->information_id)) {
            echo '<button class="btn btn-info" type="button"><i class="fa fa-eye-slash" title="' . IMAGE_ICON_INFO . '" aria-hidden="true"></i></i></button>';
        } else {
            echo '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $information['information_id']) . '"><button class="btn btn-default" type="button"><i class="fa fa-eye-slash"></i></button></a>';
        } ?>&nbsp;</td>
             </tr>
        <?php
        // Move that ADOdb pointer!
        $information_result->MoveNext();
        } ?>
              <tr>
                <td colspan="4"><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $information_split->display_count($information_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, $nPage, TEXT_DISPLAY_NUMBER_OF_INFORMATION); ?></td>
                    <td class="smallText" align="right"><?php echo $information_split->display_links($information_result_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $nPage); ?></td>
                  </tr>
                </table></td>
              </tr>
    <?php
    if (empty($action)) {
        ?>
              <tr>
                <td align="right" colspan="4" class="smallText"><?php echo '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&action=new') . '">' . oos_button(BUTTON_INSERT) . '</a>'; ?></td>
              </tr>
        <?php
    } ?>
            </table></td>
    <?php
    $heading = [];
        $contents = [];

        switch ($action) {

    case 'delete':
        if ($iInfo->information_id > 6) {
            $heading[] = array('text' => '<b>' . TEXT_HEADING_DELETE_INFORMATION . '</b>');

            $contents = array('form' => oos_draw_form('id', 'information', $aContents['information'], 'page=' . $nPage . '&iID=' . $iInfo->information_id . '&action=deleteconfirm', 'post', false));
            $contents[] = array('text' => TEXT_DELETE_INTRO);
            $contents[] = array('text' => '<br><b>' . $iInfo->information_name . '</b>');
            $contents[] = array('align' => 'center', 'text' => '<br>' . oos_submit_button(BUTTON_DELETE) . ' <a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $iInfo->information_id) . '" role="button"><strong>' . BUTTON_CANCEL . '</strong></a>');
        }
        break;

    default:
        if (isset($iInfo) && is_object($iInfo)) {
            $heading[] = array('text' => '<b>' . $iInfo->information_name . '</b>');
            if ($iInfo->information_id > 5) {
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $iInfo->information_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a> <a href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $iInfo->information_id . '&action=delete') . '">' . oos_button(BUTTON_DELETE) . '</a>');
            } else {
                $contents[] = array('align' => 'center', 'text' => '<a href="' . oos_href_link_admin($aContents['information'], 'page=' . $nPage . '&iID=' . $iInfo->information_id . '&action=edit') . '">' . oos_button(BUTTON_EDIT) . '</a>');
            }
            $contents[] = array('text' => '<br>' . TEXT_DATE_ADDED . ' ' . oos_date_short($iInfo->date_added));
            if (oos_is_not_null($iInfo->last_modified)) {
                $contents[] = array('text' => TEXT_LAST_MODIFIED . ' ' . oos_date_short($iInfo->last_modified));
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
<!-- body_text_eof //-->
    <?php
    }
?> 
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