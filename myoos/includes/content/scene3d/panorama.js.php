<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: pannellum.html
   ----------------------------------------------------------------------
   Pannellum
   https://pannellum.org/


   Copyright © Matthew Petroff(http://mpetroff.net/)
   ----------------------------------------------------------------------
   The MIT License
   ----------------------------------------------------------------------
 */
?>
<script>
pannellum.viewer('panorama', {
    "type": "equirectangular",
    "panorama": "<?php echo OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/' . oos_output_string($panorama_info['scene_image']); ?>",
<?php if (!empty($panorama_info['panorama_pitch'])) {
    echo '"pitch": "' . $panorama_info['panorama_pitch'] . '," ';
} ?>    
<?php if (!empty($panorama_info['panorama_yaw'])) {
    echo '"yaw": "' . $panorama_info['panorama_yaw'] . '," ';
} ?>
<?php if (!empty($panorama_info['panorama_hfov'])) {
    echo '"hfov": "' . $panorama_info['panorama_hfov'] . '," ';
} ?>            
<?php if (!empty($panorama_info['panorama_preview'])) {
    echo '"preview": "' . OOS_HTTPS_SERVER . OOS_SHOP . OOS_IMAGES . 'panoramas/large/' . oos_output_string($panorama_info['panorama_preview']) . '",';
} ?>
<?php if (!empty($panorama_info['panorama_autoload']) && ($panorama_info['panorama_autoload'] == 'true')) {
    echo '"autoLoad": true, ';
} ?>                                
<?php if (!empty($panorama_info['panorama_autorotates'])) {
    echo '"autoRotate": ' . $panorama_info['panorama_autorotates']. ',';
} ?>
<?php if (!empty($panorama_info['panorama_author'])) { ?>
    "title": "<?php echo $panorama_info['panorama_name']; ?>",
    "author": "<?php echo $panorama_info['panorama_author']; ?>",
    <?php
}

if (!empty($html)) {
    echo $html;
}
?>
    "strings": {
        "loadButtonLabel": "<?php echo $aLang['text_load_button_label']; ?>",
        "loadingLabel": "<?php echo $aLang['text_loading_label']; ?>",
        "bylineLabel": "<?php echo $aLang['text_byline_label']; ?>",    
        "noPanoramaError": "<?php echo $aLang['text_no_panorama_error']; ?>",
        "fileAccessError": "<?php echo $aLang['text_file_access_error']; ?>",
        "malformedURLError": "<?php echo $aLang['text_malformed_url_error']; ?>",
        "iOS8WebGLError": "<?php echo $aLang['text_webgl_error']; ?>",
        "genericWebGLError": "<?php echo $aLang['text_generic_webgl_error']; ?>",
        "textureSizeError": "<?php echo $aLang['text_texture_size_error']; ?>", 
        "unknownError": "<?php echo $aLang['text_unknown_error']; ?>"        
    }        
});
</script>

