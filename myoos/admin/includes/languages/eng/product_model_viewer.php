<?php
/**
   ----------------------------------------------------------------------
   $Id: products.php,v 1.3 2007/06/13 16:15:14 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: categories.php,v 1.22 2002/08/17 09:43:33 project3000
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */


define('HEADING_TITLE', 'Augmented Reality');

define('TEXT_NEW_PRODUCT', 'Augmented Reality in &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Products');
define('TEXT_MODELS_MODEL', '3D Model');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_MODELS_GLB', '3D-Modell .glb File format');
define('TEXT_MODELS_USDZ', '3D-Modell .usdz File format optional for Apple');

define('TEXT_MODELS_TITLE', 'Alternative text for ALT attribute:');
define('TEXT_MODELS_DESCRIPTION', '3D Model description');
define('TEXT_MODELS_BACKGROUND_COLOR', 'Background Color');

define('TEXT_MODELS_OBJECT_SCALING', 'Scaling behavior:');
define('TEXT_MODELS_OBJECT_SCALING_HELP', 'Controls the scaling behavior in AR mode in Scene Viewer. Set to "fixed" to disable scaling of the model, which sets it to always be at 100% scale. Defaults to "auto" which allows the model to be resized.');
define('TEXT_MODELS_OBJECT_ROTATION', 'Object Rotation:');

define('TEXT_MODELS_HDR', 'Panorama Wallpaper');
define('TEXT_MODELS_HDR_NONE', 'Do not use a background image');

define('TEXT_MODEL_REMOVE', 'Remove 3D-Model');

define('TEXT_UPLOAD_MODELS', 'Upload file');

define('ERROR_NO_GLB_FILE', 'The file you are trying to upload is not a .glb file. Please try again.');
define('TEXT_SUCCESSFULLY_UPLOADED_GLB', 'Your .glb file was uploaded.');
define('ERROR_PROBLEM_WITH_GLB_FILE', 'There was a problem with the upload. Please try again.');

define('ERROR_NO_USDZ_FILE', 'The file you are trying to upload is not a .usdz file. Please try again.');
define('TEXT_SUCCESSFULLY_UPLOADED_USDZ', 'Your .usdz file was uploaded.');
define('ERROR_PROBLEM_WITH_USDZ_FILE', 'There was a problem with the .usdz file upload. Please try again.');
