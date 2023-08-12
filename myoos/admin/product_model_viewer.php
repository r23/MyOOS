<?php
/** ---------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.146 2003/07/11 14:40:27 hpdl
         categories.php,v 1.138 2002/11/18 21:38:22 dgw_
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : $current_category_id);
$pID = (isset($_GET['pID']) ? intval($_GET['pID']) : 0);

if (!empty($action)) {
    switch ($action) {
    case 'insert_model':
    case 'update_model':

        if (isset($_SESSION['formid']) && isset($_POST['formid']) && ($_SESSION['formid'] == $_POST['formid'])) {
            $products_id = intval($_POST['products_id']);

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



            $nModelCounter = (!isset($_POST['model_counter']) || !is_numeric($_POST['model_counter'])) ? 0 : intval($_POST['model_counter']);

            for ($i = 0, $n = $nModelCounter; $i < $n; $i++) {
                $action = (!isset($_POST['model_viewer_id'][$i]) || !is_numeric($_POST['model_viewer_id'][$i])) ? 'insert_model' : 'update_model';

                $sql_data_array = array('products_id' => intval($products_id),
                                        'model_viewer_background_color' => oos_db_prepare_input($_POST['model_viewer_background_color'][$i]),
                                        'model_viewer_scale' => oos_db_prepare_input($_POST['model_viewer_scale'][$i]),
                                        'model_viewer_auto_rotate' => oos_db_prepare_input($_POST['model_viewer_auto_rotate'][$i]),
                                        'model_viewer_hdr' => oos_db_prepare_input($_POST['model_viewer_hdr'][$i])
                                        );

                if ($action == 'insert_model') {
                    $insert_sql_data = array('model_viewer_date_added' => 'now()');

                    $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                    oos_db_perform($oostable['products_model_viewer'], $sql_data_array);
                    $model_viewer_id = $dbconn->Insert_ID();
                } elseif ($action == 'update_model') {
                    $update_sql_data = array('model_viewer_last_modified' => 'now()');
                    $model_viewer_id = intval($_POST['model_viewer_id'][$i]);

                    $sql_data_array = array_merge($sql_data_array, $update_sql_data);

                    oos_db_perform($oostable['products_model_viewer'], $sql_data_array, 'UPDATE', 'model_viewer_id = \'' . intval($model_viewer_id) . '\'');
                }

                $aLanguages = oos_get_languages();
                $nLanguages = count($aLanguages);

                for ($li = 0, $l = $nLanguages; $li < $l; $li++) {
                    $language_id = $aLanguages[$li]['id'];

                    $sql_data_array = array('model_viewer_title' => oos_db_prepare_input($_POST['model_viewer_title'][$i][$language_id]),
                                            'model_viewer_description' => oos_db_prepare_input($_POST['model_viewer_description_'. $i . '_'  . $aLanguages[$li]['id']]));

                    if ($action == 'insert_model') {
                        $insert_sql_data = array('model_viewer_id' => $model_viewer_id,
                                                'model_viewer_languages_id' => $language_id);

                        $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

                        oos_db_perform($oostable['products_model_viewer_description'], $sql_data_array);
                    } elseif ($action == 'update_model') {
                        oos_db_perform($oostable['products_model_viewer_description'], $sql_data_array, 'UPDATE', 'model_viewer_id = \'' . intval($model_viewer_id) . '\' AND model_viewer_languages_id = \'' . intval($language_id) . '\'');
                    }
                }


                if ((isset($_POST['remove_products_model_viewer'][$i]) && ($_POST['remove_products_model_viewer'][$i] == 'yes')) && (isset($_POST['model_viewer_glb'][$i]))) {
                    $model_viewer_glb = oos_db_prepare_input($_POST['model_viewer_glb'][$i]);
                    $model_viewer_usds =  oos_db_prepare_input($_POST['model_viewer_usdz'][$i]);

                    $dbconn->Execute("DELETE FROM " . $oostable['products_model_viewer'] . " WHERE model_viewer_id = '" . intval($_POST['model_viewer_id'][$i]) . "'");
                    $dbconn->Execute("DELETE FROM " . $oostable['products_model_viewer_description'] . " WHERE model_viewer_id = '" . intval($_POST['model_viewer_id'][$i]) . "'");

                    oos_remove_products_model($model_viewer_glb);
                    oos_remove_model_usds($model_viewer_usds);
                }

                // glb
                if (isset($_FILES['glb'])) {
                    if ($_FILES["glb"]["error"] == UPLOAD_ERR_OK) {
                        $filename = oos_db_prepare_input($_FILES['glb']['name']);
                        $source = $_FILES['glb']['tmp_name'];
                        $type = oos_db_prepare_input($_FILES['glb']['type']);

                        $name = oos_strip_suffix($filename);
                        $ext = oos_get_suffix($filename);
                        if ($ext == 'glb') {
                            $check =  OOS_ABSOLUTE_PATH . OOS_MEDIA . 'models/gltf/' . oos_var_prep_for_os($name);
                            if (is_dir($check)) {
                                oos_remove($check);
                            }

                            $path = OOS_ABSOLUTE_PATH . OOS_MEDIA . 'models/gltf/' . oos_var_prep_for_os($name) . '/glTF-Binary/';
                            $targetdir = $path;  // target directory
                            $uploadfile = $path . $filename; // target zip file

                            mkdir($check, 0755);
                            mkdir($targetdir, 0755);

                            if (move_uploaded_file($source, $uploadfile)) {
                                $messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED_GLB, 'success');
                            } else {
                                $messageStack->add_session(ERROR_PROBLEM_WITH_GLB_FILE, 'error');
                            }

                            $sql_data_array = array('model_viewer_glb' => oos_db_prepare_input($filename));

                            oos_db_perform($oostable['products_model_viewer'], $sql_data_array, 'UPDATE', 'model_viewer_id = \'' . intval($model_viewer_id) . '\'');
                        } else {
                            $messageStack->add_session(ERROR_NO_GLB_FILE, 'error');
                        }
                    }
                }


                // usdz
                if (isset($_FILES['usdz'])) {
                    if ($_FILES["usdz"]["error"] == UPLOAD_ERR_OK) {
                        $filename = oos_db_prepare_input($_FILES['usdz']['name']);
                        $source = $_FILES['usdz']['tmp_name'];
                        $type = oos_db_prepare_input($_FILES['usdz']['type']);

                        $name = oos_strip_suffix($filename);
                        $ext = oos_get_suffix($filename);
                        if ($ext == 'usdz') {
                            $path = OOS_ABSOLUTE_PATH . OOS_MEDIA . 'models/usdz/';
                            $targetdir = $path;  // target directory
                            $uploadfile = $path . $filename; // target zip file

                            if (move_uploaded_file($source, $uploadfile)) {
                                $messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED_USDZ, 'success');
                            } else {
                                $messageStack->add_session(ERROR_PROBLEM_WITH_USDZ_FILE, 'error');
                            }

                            $sql_data_array = array('model_viewer_usdz' => oos_db_prepare_input($filename));

                            oos_db_perform($oostable['products_model_viewer'], $sql_data_array, 'UPDATE', 'model_viewer_id = \'' . intval($model_viewer_id) . '\'');
                        } else {
                            $messageStack->add_session(ERROR_PROBLEM_WITH_USDZ_FILE, 'error');
                        }
                    }
                }
            }
            oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&pID=' . $products_id));
        }
        break;

    }
}

require 'includes/header.php';
?>
<script src="js/ckeditor/ckeditor.js"></script>
<!-- body //-->
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

                <div class="row">
                    <div class="col-lg-12">
<?php
if ($action == 'edit_3d') {
    $parameters = array('products_id' => '',
                        'products_name' => '',
                        'products_image' => '',
                        'products_models' => array());

    $pInfo = new objectInfo($parameters);

    if (isset($_GET['pID']) && empty($_POST)) {
        $productstable = $oostable['products'];

        $products_descriptiontable = $oostable['products_description'];
        $product_result = $dbconn->Execute(
            "SELECT p.products_id, p.products_image, pd.products_name
                                            FROM $productstable p,
                                                 $products_descriptiontable pd
                                           WHERE p.products_id = '" . intval($pID) . "' AND
                                                 p.products_id = pd.products_id AND
                                                 pd.products_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );
        $product = $product_result->fields;

        $pInfo = new objectInfo($product);

        $products_model_viewertable = $oostable['products_model_viewer'];
        $products_model_viewer_descriptiontable = $oostable['products_model_viewer_description'];
        $products_models_result = $dbconn->Execute(
            "SELECT m.model_viewer_id, m.products_id, md.model_viewer_title, md.model_viewer_description,
													m.model_viewer_glb, m.model_viewer_usdz, m.model_viewer_background_color,
													m.model_viewer_auto_rotate, m.model_viewer_scale, m.model_viewer_hdr
                                            FROM $products_model_viewertable m,
                                                 $products_model_viewer_descriptiontable md
                                           WHERE m.products_id = '" . intval($product['products_id']) . "' AND
                                                 m.model_viewer_id = md.model_viewer_id AND
                                                 md.model_viewer_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );

        if (!$products_models_result->RecordCount()) {
            $pInfo->products_models[] = array('products_id' => $product['products_id'],
                                            'model_viewer_glb' => '',
                                            'model_viewer_usdz' => '',
                                            'model_viewer_background_color' => '',
                                            'model_viewer_scale' => 'auto',
                                            'model_viewer_auto_rotate' => 'true',
                                            'model_viewer_hdr' => 'venice_sunset_2k.hdr');
        } else {
            while ($products_models = $products_models_result->fields) {
                $pInfo->products_models[] = array('model_viewer_id' => $products_models['model_viewer_id'],
                                            'products_id' => $products_models['products_id'],
                                            'model_viewer_glb' => $products_models['model_viewer_glb'],
                                            'model_viewer_usdz' => $products_models['model_viewer_usdz'],
                                            'model_viewer_background_color' => $products_models['model_viewer_background_color'],
                                            'model_viewer_scale' => $products_models['model_viewer_scale'],
                                            'model_viewer_auto_rotate' => $products_models['model_viewer_auto_rotate'],
                                            'model_viewer_hdr' => $products_models['model_viewer_hdr']);
                // Move that ADOdb pointer!
                $products_models_result->MoveNext();
            }
        }
    }

    $aLanguages = oos_get_languages();
    $nLanguages = count($aLanguages);

    $form_action = (isset($_GET['pID'])) ? 'update_model' : 'insert_model';

    $back_url = $aContents['categories'];
    $back_url_params = 'cPath=' . $cPath;
    if (oos_is_not_null($pInfo->products_id)) {
        $back_url_params .= '&pID=' . $pInfo->products_id;
    } ?>    
    <!-- Breadcrumbs //-->
    <div class="content-heading">
        <div class="col-lg-12">
            <h2><?php echo sprintf(TEXT_NEW_PRODUCT, $pInfo->products_name); ?></h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['default']) . '">' . HEADER_TITLE_TOP . '</a>'; ?>
                </li>
                <li class="breadcrumb-item">
                    <?php echo '<a href="' . oos_href_link_admin($aContents['categories'], 'selected_box=catalog') . '">' . BOX_HEADING_CATALOG . '</a>'; ?>
                </li>
                <li class="breadcrumb-item active">
                    <strong><?php echo sprintf(TEXT_NEW_PRODUCT, $pInfo->products_name); ?></strong>
                </li>
            </ol>
        </div>
    </div>
    <!-- END Breadcrumbs //-->

    <?php echo oos_draw_form('id', 'new_product', $aContents['product_model_viewer'], 'cPath=' . oos_prepare_input($cPath) . (!empty($pID) ? '&pID=' . intval($pID) : '') . '&action=' . $form_action, 'post', false, 'enctype="multipart/form-data"'); ?>
    <?php

                $sFormid = md5(uniqid(rand(), true));
    $_SESSION['formid'] = $sFormid;
    echo oos_draw_hidden_field('formid', $sFormid);
    echo oos_draw_hidden_field('products_id', $pInfo->products_id);
    echo oos_hide_session_id(); ?>
               <div role="tabpanel">
                  <ul class="nav nav-tabs nav-justified" id="myTab">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" href="#product" aria-controls="product" role="tab" data-toggle="tab"><?php echo TEXT_PRODUCTS; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#model" aria-controls="model" role="tab" data-toggle="tab"><?php echo TEXT_MODELS_MODEL; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#uplaod" aria-controls="picture" role="tab" data-toggle="tab"><?php echo TEXT_UPLOAD_MODELS; ?></a>
                     </li>
                  </ul>
                  <div class="tab-content">
                    <div class="text-right mt-3 mb-3">
                        <?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
                        <?php echo oos_submit_button(BUTTON_SAVE); ?>
                        <?php echo oos_reset_button(BUTTON_RESET); ?>               
                    </div>              
                     <div class="tab-pane active" id="product" role="tabpanel">


                           <div class="col-9">
                              <h2><?php echo $pInfo->products_name; ?></h2>
                           </div>    
                     
                           <div class="col-9">
                              <?php echo product_info_image($pInfo->products_image, $pInfo->products_name); ?>
                           </div>                     
                     
                     </div>
             
                     <div class="tab-pane" id="model" role="tabpanel">
                        <div class="col-9">
    <?php
    if (is_array($pInfo->products_models) || is_object($pInfo->products_models)) {
        $nCounter = 0;

        foreach ($pInfo->products_models as $models) {
            if (isset($models['model_viewer_id'])) {
                echo oos_draw_hidden_field('model_viewer_id['. $nCounter . ']', $models['model_viewer_id']); ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_MODELS_GLB; ?></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_input_field('model_viewer_glb['. $nCounter . ']', $models['model_viewer_glb'], '', false, 'text', true, true); ?>
                <?php echo oos_draw_hidden_field('model_viewer_glb['. $nCounter . ']', $models['model_viewer_glb']); ?>
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_MODELS_USDZ; ?></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_input_field('model_viewer_usdz['. $nCounter . ']', $models['model_viewer_usdz'], '', false, 'text', true, true); ?>
                <?php echo oos_draw_hidden_field('model_viewer_usdz['. $nCounter . ']', $models['model_viewer_usdz']); ?>
                              </div>
                           </div>
                        </fieldset>                            
                        
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_checkbox_field('remove_products_model_viewer['. $nCounter . ']', 'yes') . ' ' . TEXT_MODEL_REMOVE; ?>
                              </div>
                           </div>
                        </fieldset>    
                <?php
            }

            $model_viewer_id = (isset($models['model_viewer_id'])) ? $models['model_viewer_id'] : '';

            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                ?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                    echo TEXT_MODELS_TITLE;
                } ?></label>
                <?php if ($nLanguages > 1) {
                    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                } ?>
                              <div class="col-lg-9">
                <?php echo oos_draw_input_field('model_viewer_title['. $nCounter . '][' . $aLanguages[$i]['id'] . ']', (isset($model_viewer_title[$aLanguages[$i]['id']]) ? stripslashes($model_viewer_title[$aLanguages[$i]['id']]) : oos_get_model_viewer_title($model_viewer_id, $aLanguages[$i]['id']))); ?>        
                            
                              </div>
                           </div>
                        </fieldset>                        
                <?php
            }
            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                    echo TEXT_MODELS_DESCRIPTION;
                } ?></label>
                <?php if ($nLanguages > 1) {
                    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                } ?>
                              <div class="col-lg-9">
                <?php
                echo oos_draw_textarea_field('model_viewer_description_'. $nCounter . '_' . $aLanguages[$i]['id'], 'soft', '70', '15', (isset($_POST['model_viewer_description' .$aLanguages[$i]['id']]) ? stripslashes($_POST['model_viewer_description' .$aLanguages[$i]['id']]) : oos_get_model_viewer_description($model_viewer_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
        <script>
            CKEDITOR.replace( 'model_viewer_description_<?php echo  $nCounter . '_' . $aLanguages[$i]['id']; ?>');
        </script>
                <?php
            } ?>

                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_MODELS_BACKGROUND_COLOR; ?></label>
                              <div class="col-lg-10">
                                <input class="form-control" id="color_selectors" type="text" data-format="hex" data-color="<?php echo $models['model_viewer_background_color']; ?>" name="model_viewer_background_color[<?php echo $nCounter; ?>]" value="<?php echo $models['model_viewer_background_color']; ?>" /> 
                              </div>
                           </div>
                        </fieldset>
                        

                       <fieldset>
                           <div class="form-group row mt-5">
                                <label class="col-sm col-form-label"><?php echo TEXT_MODELS_HDR; ?></label>
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_hdr['. $nCounter . ']" value="none"';
            if ($models['model_viewer_hdr'] == 'none') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;' . TEXT_MODELS_HDR_NONE; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                    </div>
                                </div>
                           </div>                                
                                
                               <div class="form-group row mt-5">
                                <label class="col-sm col-form-label"></label>                                
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_hdr['. $nCounter . ']" value="venetian_crossroads_2k.hdr"';
            if ($models['model_viewer_hdr'] == 'venetian_crossroads_2k.hdr') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;venetian_crossroads_2k.hdr';
            echo oos_image(OOS_IMAGES . 'background/venetian_crossroads.jpg', 'venetian_crossroads_2k.hdr'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_hdr['. $nCounter . ']" value="vignaioli_2k.hdr"';
            if ($models['model_viewer_hdr'] == 'vignaioli_2k.hdr') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;vignaioli_2k.hdr';
            echo oos_image(OOS_IMAGES . 'background/vignaioli.jpg', 'vignaioli_2k.hdr'); ?>
                                        </label>
                                    </div>
                                </div>
                           </div>
                           
                           
                               <div class="form-group row mt-5">
                                <label class="col-sm col-form-label"></label>
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_hdr['. $nCounter . ']" value="canary_wharf_2k.hdr"';
            if ($models['model_viewer_hdr'] == 'canary_wharf_2k.hdr') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;canary_wharf_2k.hdr';
            echo oos_image(OOS_IMAGES . 'background/canary_wharf.jpg', 'canary_wharf_2k.hdr'); ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_hdr['. $nCounter . ']" value="venice_sunset_2k.hdr"';
            if ($models['model_viewer_hdr'] == 'venice_sunset_2k.hdr') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;venice_sunset_2k.hdr';
            echo oos_image(OOS_IMAGES . 'background/venice_sunset.jpg', 'venice_sunset_2k.hdr'); ?>
                                        </label>
                                    </div>
                                </div>
                           </div>                       
                        </fieldset>

                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_MODELS_OBJECT_SCALING; ?></label>

                                <div class="col-lg-2">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_scale['. $nCounter . ']" value="auto"';
            if ($models['model_viewer_scale'] == 'auto') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                            <span class="badge badge-success float-right">auto</span>
                                        </label>
                                    </div>
                                <div class="c-radio c-radio-nofont">
                                    <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_scale['. $nCounter . ']" value="fixed"';
            if ($models['model_viewer_scale'] == 'fixed') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                        <span class="badge badge-danger float-right">fixed</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-lg-8">
                                    <p><?php echo TEXT_MODELS_OBJECT_SCALING_HELP; ?></p>
                            </div>                            
                        </div>                              
                        </fieldset>        




                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_MODELS_OBJECT_ROTATION; ?></label>

                                <div class="col-lg-10">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_auto_rotate['. $nCounter . ']" value="true"';
            if ($models['model_viewer_auto_rotate'] == 'true') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                            <span class="badge badge-success float-right"><?php echo ENTRY_YES; ?></span>
                                        </label>
                                    </div>
                                <div class="c-radio c-radio-nofont">
                                    <label>
            <?php
                                            echo '<input type="radio" name="model_viewer_auto_rotate['. $nCounter . ']" value="false"';
            if ($models['model_viewer_auto_rotate'] == 'false') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                        <span class="badge badge-danger float-right"><?php echo ENTRY_NO; ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>                              
                        </fieldset>                        
                    
                              
                             
                </div>

            <?php
                    $nCounter++;
            echo oos_draw_hidden_field('model_counter', $nCounter);
        }
    } ?>

            </div>
            <div class="tab-pane" id="uplaod" role="tabpanel">



                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-3 col-form-label"><?php echo TEXT_MODELS_GLB; ?></label>
                              <div class="col-lg-9">
                                    <input type="file" name="glb" />
                              </div>
                           </div>
                        </fieldset>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-3 col-form-label"><?php echo TEXT_MODELS_USDZ; ?></label>
                              <div class="col-lg-9">
                                <input type="file" name="usdz" />
                              </div>
                           </div>
                        </fieldset>        



            </div>



                  </div>
               </div>
            <div class="text-right mt-3">
                <?php echo '<a class="btn btn-sm btn-warning mb-20" href="' . oos_href_link_admin($back_url, $back_url_params) . '" role="button"><strong><i class="fa fa-chevron-left"></i> ' . BUTTON_BACK . '</strong></a>'; ?>
                <?php echo oos_submit_button(BUTTON_SAVE); ?>
                <?php echo oos_reset_button(BUTTON_RESET); ?>               
            </div>                
            
            </form>
<!-- body_text_eof //-->
    <?php
}
?>

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
