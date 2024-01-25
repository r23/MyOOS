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


define('HEADING_TITLE', 'Product Video');

define('TEXT_NEW_PRODUCT', 'Product Video for &quot;%s&quot;');
define('TEXT_PRODUCTS', 'Products');
define('TEXT_VIDEO_SOURCE', 'Video');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_VIDEO_FILE', 'Video .avi File format:');

define('TEXT_VIDEO_TITLE', 'Headline, video title:');
define('TEXT_VIDEO_DESCRIPTION', 'Video description');
define('TEXT_VIDEO_PRELOAD', 'Start downloading the video data:');
define('TEXT_VIDEO_PRELOAD_HELP', 'Suggests to the browser whether to start downloading video data once the &lt;video&gt; element is loaded. Supported values are: <ul><li>auto: Start loading the video immediately (if the browser supports it).</li><li>metadata: Load only the metadata of the video, which includes information such as the duration and dimensions of the video.</li><li><b>none</b>: No data is preloaded. The browser waits until the user presses play to start downloading.</li></ul>');

define('TEXT_VIDEO_UPLAOD_TITLE', 'Upload new .avi video file.');
define('TEXT_VIDEO_BROWSER_UPLOADER', 'You use the browser\'s built-in file uploader.');
define('TEXT_VIDEO_MAX_UPLOAD', 'Maximum upload file size: : <strong>%s</strong>.');
define('TEXT_VIDEO_UPLAOD_PATIENCE', 'Please be patient. The uplaod will create an image of the video and the Vdieo will be automatically converted to the following formats: .mp4 .ogv and .webm.');
define('TEXT_VIDEO_UPLAOD_HELP', 'For video editing FFmpeg is required on the server.');

define('TEXT_VIDEO_REMOVE', 'Remove Video');
define('TEXT_UPLOAD_VIDEO', 'Upload file');

define('ERROR_NO_VIDEO_FILE', 'The file you are trying to upload is not a .mpg file. Please try again.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO', 'Your video file was uploaded.');
define('ERROR_PROBLEM_WITH_VIDEO_FILE', 'There was a problem with the upload. Please try again.');

define('ERROR_NO_VIDEO_FILE', 'The file you are trying to upload does not have a valid file format. Please try again.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO', 'Your video has been uploaded.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_MP4', 'Your MP4 video file has been uploaded.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_WEBM', 'Your WEBM video file has been uploaded.');
define('TEXT_SUCCESSFULLY_UPLOADED_VIDEO_OGV', 'Your OGV video file has been uploaded.');
define('TEXT_SUCCESSFULLY_UPLOADED_POSTER', 'Your poster in WebP format has been uploaded.');

define('ERROR_PROBLEM_WITH_VIDEO_FILE', 'There was a problem with the upload. Please try again.');
