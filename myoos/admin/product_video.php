<?php
/**
   ---------------------------------------------------------------------

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
   ----------------------------------------------------------------------
 */

define('OOS_VALID_MOD', 'yes');
require 'includes/main.php';

require 'includes/functions/function_categories.php';

$action = filter_string_polyfill(filter_input(INPUT_GET, 'action')) ?: 'default';
$cPath = (isset($_GET['cPath']) ? oos_prepare_input($_GET['cPath']) : $current_category_id);
$pID = filter_input(INPUT_GET, 'pID', FILTER_VALIDATE_INT) ?: 0;
$nPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;


switch ($action) {
case 'insert_video':
case 'update_video':

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

        $nVideoCounter = (!isset($_POST['video_counter']) || !is_numeric($_POST['video_counter'])) ? 0 : intval($_POST['video_counter']);

        for ($i = 0, $n = $nVideoCounter; $i < $n; $i++) {
            $action = (!isset($_POST['video_id'][$i]) || !is_numeric($_POST['video_id'][$i])) ? 'insert_video' : 'update_video';

            $sql_data_array = ['products_id' => intval($products_id),
                               'video_source' => oos_db_prepare_input($_POST['video_source'][$i]),
                               'video_preload' => oos_db_prepare_input($_POST['video_preload'][$i])];

            if ($action == 'insert_video') {
                $insert_sql_data = ['video_date_added' => 'now()'];

                $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                oos_db_perform($oostable['products_video'], $sql_data_array);
                $video_id = $dbconn->Insert_ID();
            } elseif ($action == 'update_video') {
                $update_sql_data = ['video_last_modified' => 'now()'];
                $video_id = intval($_POST['video_id'][$i]);

                $sql_data_array = [...$sql_data_array, ...$update_sql_data];

                oos_db_perform($oostable['products_video'], $sql_data_array, 'UPDATE', 'video_id = \'' . intval($video_id) . '\'');
            }

            $aLanguages = oos_get_languages();
            $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

            for ($li = 0, $l = $nLanguages; $li < $l; $li++) {
                $language_id = $aLanguages[$li]['id'];

                $sql_data_array = ['video_title' => oos_db_prepare_input($_POST['video_title'][$i][$language_id]),
                                        'video_description' => oos_db_prepare_input($_POST['video_description_'. $i . '_'  . $aLanguages[$li]['id']])];

                if ($action == 'insert_video') {
                    $insert_sql_data = ['video_id' => $video_id,
                                        'video_languages_id' => $language_id];

                    $sql_data_array = [...$sql_data_array, ...$insert_sql_data];

                    oos_db_perform($oostable['products_video_description'], $sql_data_array);
                } elseif ($action == 'update_video') {
                    oos_db_perform($oostable['products_video_description'], $sql_data_array, 'UPDATE', 'video_id = \'' . intval($video_id) . '\' AND video_languages_id = \'' . intval($language_id) . '\'');
                }
            }

            if ((isset($_POST['remove_products_video'][$i]) && ($_POST['remove_products_video'][$i] == 'yes')) && (isset($_POST['video_source'][$i]))) {
                $video_source = oos_db_prepare_input($_POST['video_source'][$i]);
                $video_id = intval($_POST['video_id'][$i]);

                $products_videotable = $oostable['products_video'];
                $video_sql = "SELECT video_id, video_source, video_mp4, video_webm, video_ogv, video_poster
								  FROM $products_videotable 
								  WHERE video_id = '" . intval($video_id) . "'";
                $video_files = $dbconn->GetRow($video_sql);

                $dbconn->Execute("DELETE FROM " . $oostable['products_video'] . " WHERE video_id = '" . intval($video_id) . "'");
                $dbconn->Execute("DELETE FROM " . $oostable['products_video_description'] . " WHERE video_id = '" . intval($video_id) . "'");

                oos_remove_products_video($video_files);
            }

            // video
            if (isset($_FILES['video'])) {
                if ($_FILES["video"]["error"] == UPLOAD_ERR_OK) {
                    $filename = oos_db_prepare_input($_FILES['video']['name']);
                    $source = $_FILES['video']['tmp_name'];
                    $type = oos_db_prepare_input($_FILES['video']['type']);

                    $name = oos_strip_suffix($filename);
                    $ext = oos_get_suffix($filename);
                    if ($ext == 'avi') {
                        $poster = $name . '.jpg';
                        $video_mp4 = $name . '-x264.mp4';
                        $video_webm = $name . '-webm.webm';
                        $video_ogv = $name . '-ogg.ogv';

                        $sql_data_array = ['video_source' => oos_db_prepare_input($filename),
                                            'video_mp4' => oos_db_prepare_input($video_mp4),
                                            'video_webm' => oos_db_prepare_input($video_webm),
                                            'video_ogv' => oos_db_prepare_input($video_ogv),
                                            'video_poster' => oos_db_prepare_input($poster)];

                        oos_db_perform($oostable['products_video'], $sql_data_array, 'UPDATE', 'video_id = \'' . intval($video_id) . '\'');

                        $path = OOS_ABSOLUTE_PATH . OOS_MEDIA . 'video/';
                        $targetdir = $path;  // target directory
                        $uploadfile = $path . $filename; // target avi file

                        if (move_uploaded_file($source, $uploadfile)) {
                            $messageStack->add_session(TEXT_SUCCESSFULLY_UPLOADED_VIDEO, 'success');
                        } else {
                            $messageStack->add_session(ERROR_PROBLEM_WITH_VIDEO_FILE, 'error');
                        }

                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            $ffmpeg = FFMpeg\FFMpeg::create(
                                [
                                'ffmpeg.binaries'  => 'C:/ffmpeg/bin/ffmpeg.exe',
                                'ffprobe.binaries' => 'C:/ffmpeg/bin/ffprobe.exe',
                                'timeout'          => 3600, // The timeout for the underlying process
                                'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
                                ]
                            );
                        } else {
                            $ffmpeg = FFMpeg\FFMpeg::create();
                        }

                        $video = $ffmpeg->open($uploadfile);
                        /*
                        $video
                        ->filters()
                        ->resize(new FFMpeg\Coordinate\Dimension(320, 240))
                        ->synchronize();
                        */
                        $dir_video_images = OOS_ABSOLUTE_PATH . OOS_IMAGES . 'video/';
                        $frame = $dir_video_images . $poster;
                        $video
                            ->frame(FFMpeg\Coordinate\TimeCode::fromSeconds(4))
                            ->save($frame);
                        $video
                            ->save(new FFMpeg\Format\Video\X264(), $path . $name . '-x264.mp4')
                            ->save(new FFMpeg\Format\Video\Ogg(), $path . $name . '-ogg.ogv')
                            ->save(new FFMpeg\Format\Video\WebM(), $path .$name . '-webm.webm');
                    } else {
                        $messageStack->add_session(ERROR_NO_VIDEO_FILE, 'error');
                    }
                }
            }
        }
        oos_redirect_admin(oos_href_link_admin($aContents['categories'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . '&pID=' . intval($products_id)));
    }
    break;
}

require 'includes/header.php';
?>
<script nonce="<?php echo NONCE; ?>" src="js/ckeditor/ckeditor.js"></script>
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
if ($action == 'edit_video') {
    $parameters = ['products_id' => '',
                    'products_name' => '',
                    'products_image' => '',
                    'products_videos' => []];


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

        $products_video_viewertable = $oostable['products_video'];
        $products_video_descriptiontable = $oostable['products_video_description'];
        $products_videos_result = $dbconn->Execute(
            "SELECT v.video_id, v.products_id, vd.video_title, vd.video_description,
													v.video_source, v.video_poster, v.video_preload
                                            FROM $products_video_viewertable v,
                                                 $products_video_descriptiontable vd
                                           WHERE v.products_id = '" . intval($product['products_id']) . "' AND
                                                 v.video_id = vd.video_id AND
                                                 vd.video_languages_id = '" . intval($_SESSION['language_id']) . "'"
        );

        if (!$products_videos_result->RecordCount()) {
            $pInfo->products_videos[] = ['products_id' => $product['products_id'],
                                            'video_source' => '',
                                            'video_poster' => '',
                                            'video_preload' => ''];
        } else {
            while ($products_videos = $products_videos_result->fields) {
                $pInfo->products_videos[] = ['video_id' => $products_videos['video_id'],
                                            'products_id' => $products_videos['products_id'],
                                            'video_source' => $products_videos['video_source'],
                                            'video_poster' => $products_videos['video_poster'],
                                            'video_preload' => $products_videos['video_preload']];
                // Move that ADOdb pointer!
                $products_videos_result->MoveNext();
            }
        }
    }

    $aLanguages = oos_get_languages();
    $nLanguages = is_countable($aLanguages) ? count($aLanguages) : 0;

    $form_action = (isset($_GET['pID'])) ? 'update_video' : 'insert_video';

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

    <?php echo oos_draw_form('id', 'new_video', $aContents['product_video'], 'cPath=' . oos_prepare_input($cPath) . '&page=' . intval($nPage) . (!empty($pID) ? '&pID=' . intval($pID) : '') . '&action=' . $form_action, 'post', false, 'enctype="multipart/form-data"'); ?>
    <?php

    $sFormid = md5(uniqid(random_int(0, mt_getrandmax()), true));
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
                        <a class="nav-link" href="#video" aria-controls="video" role="tab" data-toggle="tab"><?php echo TEXT_VIDEO_SOURCE; ?></a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" href="#uplaod" aria-controls="picture" role="tab" data-toggle="tab"><?php echo TEXT_UPLOAD_VIDEO; ?></a>
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
             
                     <div class="tab-pane" id="video" role="tabpanel">
                        <div class="col-9">
    <?php
    if (is_array($pInfo->products_videos) || is_object($pInfo->products_videos)) {
        $nCounter = 0;

        foreach ($pInfo->products_videos as $video) {
            if (isset($video['video_id'])) {
                echo oos_draw_hidden_field('video_id['. $nCounter . ']', $video['video_id']); ?>
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_VIDEO_FILE; ?></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_input_field('video_source['. $nCounter . ']', $video['video_source'], '', false, 'text'); ?>
                <?php echo oos_draw_hidden_field('video_source['. $nCounter . ']', $video['video_source']); ?>
                              </div>
                           </div>
                        </fieldset>
                        
                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"></label>
                              <div class="col-lg-10">
                <?php echo oos_draw_checkbox_field('remove_products_video['. $nCounter . ']', 'yes') . ' ' . TEXT_VIDEO_REMOVE; ?>
                              </div>
                           </div>
                        </fieldset>    
                <?php
            }

            $video_id = $video['video_id'] ?? '';

            for ($i = 0, $n = $nLanguages; $i < $n; $i++) {
                ?>

                        <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php if ($i == 0) {
                                    echo TEXT_VIDEO_TITLE;
                                                                     } ?></label>
                <?php if ($nLanguages > 1) {
                    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                } ?>
                              <div class="col-lg-9">
                <?php echo oos_draw_input_field('video_title['. $nCounter . '][' . $aLanguages[$i]['id'] . ']', (isset($video_title[$aLanguages[$i]['id']]) ? stripslashes((string) $video_title[$aLanguages[$i]['id']]) : oos_get_video_title($video_id, $aLanguages[$i]['id']))); ?>        
                            
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
                                    echo TEXT_VIDEO_DESCRIPTION;
                                                                     } ?></label>
                <?php if ($nLanguages > 1) {
                    echo '<div class="col-lg-1">' .  oos_flag_icon($aLanguages[$i]) . '</div>';
                } ?>
                              <div class="col-lg-9">
                <?php
                echo oos_draw_textarea_field('video_description_'. $nCounter . '_' . $aLanguages[$i]['id'], 'soft', '70', '15', (isset($_POST['video_description' .$aLanguages[$i]['id']]) ? stripslashes((string) $_POST['video_description' .$aLanguages[$i]['id']]) : oos_get_video_description($video_id, $aLanguages[$i]['id']))); ?>
                              </div>
                           </div>
                        </fieldset>
        <script>
            CKEDITOR.replace( 'video_description_<?php echo  $nCounter . '_' . $aLanguages[$i]['id']; ?>');
        </script>
                <?php
            } ?>

                

                       <fieldset>
                           <div class="form-group row">
                              <label class="col-lg-2 col-form-label"><?php echo TEXT_VIDEO_PRELOAD; ?></label>

                                <div class="col-lg-2">
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="video_preload['. $nCounter . ']" value="auto" checked="checked">&nbsp;'; ?>
                                            <span class="badge badge-success float-right">auto</span>
                                        </label>
                                    </div>
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="video_preload['. $nCounter . ']" value="auto"';
            if ($video['video_preload'] == 'metadata') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                            <span class="badge badge-success float-right">metadata</span>
                                        </label>
                                    </div>                                    
                                    <div class="c-radio c-radio-nofont">
                                        <label>
            <?php
                                            echo '<input type="radio" name="video_preload['. $nCounter . ']" value=""';
            if ($video['video_preload'] == 'none') {
                echo ' checked="checked"';
            }
            echo  '>&nbsp;'; ?>
                                            <span class="badge badge-danger float-right">none</span>
                                        </label>
                                    </div>
                                </div>
                            
                                <div class="col-lg-8">
            <?php echo TEXT_VIDEO_PRELOAD_HELP; ?>
                                </div>                            
                            </div>                              
                        </fieldset>        

                </div>

            <?php
                    $nCounter++;
            echo oos_draw_hidden_field('video_counter', $nCounter);
        }
    } ?>

            </div>
            <div class="tab-pane" id="uplaod" role="tabpanel">

                <div class="col-9">
                    <h2><?php echo TEXT_VIDEO_UPLAOD_TITLE; ?></h2>

                    <h3><?php echo $pInfo->products_name; ?></h2>

                    <p><?php echo TEXT_VIDEO_BROWSER_UPLOADER; ?></p>

    <?php
    $max_upload_size = oos_max_upload_size();
    if (! $max_upload_size) {
        $max_upload_size = 0;
    } ?>
    
                    <p><?php echo sprintf(TEXT_VIDEO_MAX_UPLOAD, size_format($max_upload_size)); ?></p>

                    <p><?php echo TEXT_VIDEO_UPLAOD_PATIENCE; ?></p> 

                    <p><?php echo TEXT_VIDEO_UPLAOD_HELP; ?></p>
                    
                </div>


                <fieldset>
                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label"><?php echo TEXT_VIDEO_FILE; ?></label>
                        <div class="col-lg-9">
                            <input type="file" name="video" />
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
