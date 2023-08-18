<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: upload.php,v 1.2 2003/06/20 00:18:30 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');


/*
 * jQuery File Upload Plugin PHP Class
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * https://opensource.org/licenses/MIT
 */


#[\AllowDynamicProperties]
class upload
{
    public $file;
    public $filename;
    public $destination;
    public $extensions;
    public $tmp_filename;
    public $message_location;

    protected $options;

    // PHP File Upload error message codes:
    // http://php.net/manual/en/features.file-upload.errors.php
    protected $error_messages = [1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini', 2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form', 3 => 'The uploaded file was only partially uploaded', 4 => 'No file was uploaded', 6 => 'Missing a temporary folder', 7 => 'Failed to write file to disk', 8 => 'A PHP extension stopped the file upload', 'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini', 'max_file_size' => 'File is too big', 'min_file_size' => 'File is too small', 'accept_file_types' => 'Filetype not allowed', 'max_number_of_files' => 'Maximum number of files exceeded', 'max_width' => 'Image exceeds maximum width', 'min_width' => 'Image requires a minimum width', 'max_height' => 'Image exceeds maximum height', 'min_height' => 'Image requires a minimum height', 'abort' => 'File upload aborted', 'image_resize' => 'Failed to resize image'];

    final public const IMAGETYPE_GIF = 1;
    final public const IMAGETYPE_JPEG = 2;
    final public const IMAGETYPE_PNG = 3;
    final public const IMAGETYPE_WEBP = 18;

    protected $image_objects = [];

    public function __construct($file = '', $options = null, $destination = '', $extensions = '', $initialize = true, $error_messages = null)
    {
        $this->set_file($file);
        $this->set_destination($destination);
        $this->set_extensions($extensions);

        $this->response = [];
        $this->options = [
            'user_dirs' => false,
            'mkdir_mode' => 0755,
            'file_mode' => 0644,
            'param_name' => 'files',
            'access_control_allow_origin' => '*',
            'access_control_allow_credentials' => false,
            'access_control_allow_methods' => ['POST'],
            'access_control_allow_headers' => ['Content-Type', 'Content-Range', 'Content-Disposition'],
            // By default, allow redirects to the referer protocol+host:
            'redirect_allow_target' => '/^'.preg_quote(
                parse_url((string) $this->get_server_var('HTTP_REFERER'), PHP_URL_SCHEME)
                .'://'
                .parse_url((string) $this->get_server_var('HTTP_REFERER'), PHP_URL_HOST)
                .'/', // Trailing slash to not match subdomains by mistake
                '/' // preg_quote delimiter param
            ).'/',
            // Enable to provide file downloads via GET requests to the PHP script:
            //     1. Set to 1 to download files via readfile method through PHP
            //     2. Set to 2 to send a X-Sendfile header for lighttpd/Apache
            //     3. Set to 3 to send a X-Accel-Redirect header for nginx
            // If set to 2 or 3, adjust the upload_url option to the base path of
            // the redirect parameter, e.g. '/files/'.
            'download_via_php' => false,
            // Read files in chunks to avoid memory limits when download_via_php
            // is enabled, set to 0 to disable chunked reading of files:
            'readfile_chunk_size' => 10 * 1024 * 1024,
            // 10 MiB
            // Defines which files can be displayed inline when downloaded:
            'inline_file_types' => '/\.(gif|jpe?g|png|webp)$/i',
            // Defines which files (based on their names) are accepted for upload.
            // By default, only allows file uploads with image file extensions.
            // Only change this setting after making sure that any allowed file
            // types cannot be executed by the webserver in the files directory,
            // e.g. PHP scripts, nor executed by the browser when downloaded,
            // e.g. HTML files with embedded JavaScript code.
            // Please also read the SECURITY.md document in this repository.
            'accept_file_types' => '/\.(gif|jpe?g|png|webp)$/i',
            // Replaces dots in filenames with the given string.
            // Can be disabled by setting it to false or an empty string.
            // Note that this is a security feature for servers that support
            // multiple file extensions, e.g. the Apache AddHandler Directive:
            // https://httpd.apache.org/docs/current/mod/mod_mime.html#addhandler
            // Before disabling it, make sure that files uploaded with multiple
            // extensions cannot be executed by the webserver, e.g.
            // "example.php.png" with embedded PHP code, nor executed by the
            // browser when downloaded, e.g. "example.html.gif" with embedded
            // JavaScript code.
            'replace_dots_in_filenames' => '-',
            // The php.ini settings upload_max_filesize and post_max_size
            // take precedence over the following max_file_size setting:
            'max_file_size' => null,
            'min_file_size' => 1,
            // The maximum number of files for the upload directory:
            'max_number_of_files' => null,
            // Reads first file bytes to identify and correct file extensions:
            'correct_image_extensions' => false,
            // Image resolution restrictions:
            'max_width' => null,
            'max_height' => null,
            'min_width' => 1,
            'min_height' => 1,
            // Set the following option to false to enable resumable uploads:
            'discard_aborted_uploads' => true,
            // Set to 0 to use the GD library to scale and orient images,
            // set to 1 to use imagick (if installed, falls back to GD),
            // set to 2 to use the ImageMagick convert binary directly:
            'image_library' => 1,
            // Uncomment the following to define an array of resource limits
            // for imagick:
            /*
            'imagick_resource_limits' => array(
                imagick::RESOURCETYPE_MAP => 32,
                imagick::RESOURCETYPE_MEMORY => 32
            ),
            */
            // Command or path for to the ImageMagick convert binary:
            'convert_bin' => 'convert',
            // Uncomment the following to add parameters in front of each
            // ImageMagick convert call (the limit constraints seem only
            // to have an effect if put in front):
            /*
            'convert_params' => '-limit memory 32MiB -limit map 32MiB',
            */
            // Command or path for to the ImageMagick identify binary:
            'identify_bin' => 'identify',
            'image_versions' => [
                // The empty image version key defines options for the original image.
                // Keep in mind: these image manipulations are inherited by all other image versions from this point onwards.
                // Also note that the property 'no_cache' is not inherited, since it's not a manipulation.
                '' => [
                    // Automatically rotate images based on EXIF meta data:
                    'auto_orient' => true,
                ],
                // You can add arrays to generate different versions.
                // The name of the key is the name of the version (example: 'medium').
                // the array contains the options to apply.
                /*
                'medium' => array(
                    'max_width' => 800,
                    'max_height' => 600
                ),
                */
                // large // Large resolution (default 1024px x 1024px max) (height, width)
                // medium // Medium resolution (default 300px x 300px max) (height, width)
                // originals // Original image resolution (unmodified) (height, width)
                // small // Thumbnail (default 150px x 150px max)(height, width)
                'small' => [
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    // 'auto_orient' => true,
                    // 'crop' => true,
                    // 'jpeg_quality' => 70,
                    // 'no_cache' => true, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                    // 'strip' => true, (this strips EXIF tags, such as geolocation)
                    'max_width' => 150,
                    // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                    'max_height' => 150,
                ],
                'medium' => [
                    // Uncomment the following to use a defined directory for the thumbnails
                    // instead of a subdirectory based on the version identifier.
                    // Make sure that this directory doesn't allow execution of files if you
                    // don't pose any restrictions on the type of uploaded files, e.g. by
                    // copying the .htaccess file from the files directory for Apache:
                    // Uncomment the following to force the max
                    // dimensions and e.g. create square thumbnails:
                    // 'auto_orient' => true,
                    // 'crop' => true,
                    // 'jpeg_quality' => 70,
                    // 'no_cache' => true, (there's a caching option, but this remembers thumbnail sizes from a previous action!)
                    // 'strip' => true, (this strips EXIF tags, such as geolocation)
                    'max_width' => 300,
                    // either specify width, or set to 0. Then width is automatically adjusted - keeping aspect ratio to a specified max_height.
                    'max_height' => 300,
                ],
            ],
            'print_response' => false,
        ];
        if ($options) {
            $this->options = $options + $this->options;
        }

        if ($error_messages) {
            $this->error_messages = $error_messages + $this->error_messages;
        }

        $this->set_output_messages('direct');
    }

    public function set_file($file)
    {
        $this->file = $file;
    }

    public function set_destination($destination)
    {
        $this->destination = $destination;
    }

    public function set_filename($filename)
    {
        $this->filename = $filename;
    }

    public function set_tmp_filename($filename)
    {
        $this->tmp_filename = $filename;
    }

    public function set_extensions($extensions)
    {
        if (oos_is_not_null($extensions)) {
            if (is_array($extensions)) {
                $this->extensions = $extensions;
            } else {
                $this->extensions = [$extensions];
            }
        } else {
            $this->extensions = [];
        }
    }

    public function check_destination()
    {
        global $aLang, $messageStack;

        if (!is_writeable($this->destination)) {
            if (is_dir($this->destination)) {
                if ($this->message_location == 'direct') {
                    $messageStack->add(sprintf($aLang['error_destination_not_writeable'], $this->destination), 'error');
                } else {
                    $messageStack->add_session(sprintf($aLang['error_destination_not_writeable'], $this->destination), 'error');
                }
            } else {
                if ($this->message_location == 'direct') {
                    $messageStack->add(sprintf($aLang['error_destination_does_not_exist'], $this->destination), 'error');
                } else {
                    $messageStack->add_session(sprintf($aLang['error_destination_does_not_exist'], $this->destination), 'error');
                }
            }
            return false;
        } else {
            return true;
        }
    }

    public function set_output_messages($location)
    {
        $this->message_location = match ($location) {
            'session' => 'session',
            default => 'direct',
        };
    }


    protected function get_upload_path($file_name = null, $version = null)
    {
        $file_name = $file_name ?: '';
        if (empty($version)) {
            $version_path = 'originals/';
        } else {
            $version_path = $version.'/';
        }
        return $this->destination.$version_path.$file_name;
    }


    protected function set_additional_file_properties($file)
    {
        if ($this->options['access_control_allow_credentials']) {
            $file->deleteWithCredentials = true;
        }
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    protected function fix_integer_overflow($size)
    {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

    protected function get_file_size($file_path, $clear_stat_cache = false)
    {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fix_integer_overflow(@filesize($file_path));
    }

    protected function is_valid_file_object($file_name)
    {
        $file_path = $this->get_upload_path($file_name);
        if (is_file($file_path) && $file_name[0] !== '.') {
            return true;
        }
        return false;
    }

    protected function get_file_object($file_name)
    {
        if ($this->is_valid_file_object($file_name)) {
            $file = new \stdClass();
            $file->name = $file_name;
            $file->size = $this->get_file_size(
                $this->get_upload_path($file_name)
            );
            foreach ($this->options['image_versions'] as $version => $options) {
                if (!empty($version)) {
                    if (is_file($this->get_upload_path($file_name, $version))) {
                        /*
                        $file->{$version.'Url'} = $this->get_download_url(
                            $file->name,
                            $version
                        );
                        */
                    }
                }
            }
            $this->set_additional_file_properties($file);
            return $file;
        }
        return null;
    }

    protected function get_file_objects($iteration_method = 'get_file_object')
    {
        $upload_dir = $this->get_upload_path();
        if (!is_dir($upload_dir)) {
            return [];
        }
        return array_values(
            array_filter(
                array_map(
                    [$this, $iteration_method],
                    scandir($upload_dir)
                )
            )
        );
    }

    protected function count_file_objects()
    {
        return is_countable($this->get_file_objects('is_valid_file_object')) ? count($this->get_file_objects('is_valid_file_object')) : 0;
    }

    protected function get_error_message($error)
    {
        return $this->error_messages[$error] ?? $error;
    }

    public function get_config_bytes($val)
    {
        $val = trim((string) $val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;
        switch ($last) {
        case 'g':
            $val *= 1024;
            // no break
        case 'm':
            $val *= 1024;
            // no break
        case 'k':
            $val *= 1024;
        }
        return $this->fix_integer_overflow($val);
    }

    protected function validate($uploaded_file, $file, $error, $index)
    {
        if ($error) {
            $file->error = $this->get_error_message($error);
            return false;
        }
        $content_length = $this->fix_integer_overflow(
            (int)$this->get_server_var('CONTENT_LENGTH')
        );
        $post_max_size = $this->get_config_bytes(ini_get('post_max_size'));
        if ($post_max_size && ($content_length > $post_max_size)) {
            $file->error = $this->get_error_message('post_max_size');
            return false;
        }
        if (!preg_match($this->options['accept_file_types'], (string) $file->name)) {
            $file->error = $this->get_error_message('accept_file_types');
            return false;
        }
        if ($uploaded_file && is_uploaded_file($uploaded_file)) {
            $file_size = $this->get_file_size($uploaded_file);
        } else {
            $file_size = $content_length;
        }
        if ($this->options['max_file_size'] && ($file_size > $this->options['max_file_size']
            || $file->size > $this->options['max_file_size'])
        ) {
            $file->error = $this->get_error_message('max_file_size');
            return false;
        }
        if ($this->options['min_file_size']
            && $file_size < $this->options['min_file_size']
        ) {
            $file->error = $this->get_error_message('min_file_size');
            return false;
        }
        if (is_int($this->options['max_number_of_files'])
            && ($this->count_file_objects() >= $this->options['max_number_of_files'])
            // Ignore additional chunks of existing files:
            && !is_file($this->get_upload_path($file->name))
        ) {
            $file->error = $this->get_error_message('max_number_of_files');
            return false;
        }
        $max_width = @$this->options['max_width'];
        $max_height = @$this->options['max_height'];
        $min_width = @$this->options['min_width'];
        $min_height = @$this->options['min_height'];
        if (($max_width || $max_height || $min_width || $min_height)
            && $this->is_valid_image_file($uploaded_file)
        ) {
            [$img_width, $img_height] = $this->get_image_size($uploaded_file);
            // If we are auto rotating the image by default, do the checks on
            // the correct orientation
            if (@$this->options['image_versions']['']['auto_orient']
                && function_exists('exif_read_data')
                && ($exif = @exif_read_data($uploaded_file))
                && (((int) @$exif['Orientation']) >= 5)
            ) {
                $tmp = $img_width;
                $img_width = $img_height;
                $img_height = $tmp;
                unset($tmp);
            }
        }
        if (!empty($img_width)) {
            if ($max_width && $img_width > $max_width) {
                $file->error = $this->get_error_message('max_width');
                return false;
            }
            if ($max_height && $img_height > $max_height) {
                $file->error = $this->get_error_message('max_height');
                return false;
            }
            if ($min_width && $img_width < $min_width) {
                $file->error = $this->get_error_message('min_width');
                return false;
            }
            if ($min_height && $img_height < $min_height) {
                $file->error = $this->get_error_message('min_height');
                return false;
            }
        }
        return true;
    }

    protected function upcount_name_callback($matches)
    {
        $index = isset($matches[1]) ? ((int)$matches[1]) + 1 : 1;
        $ext = $matches[2] ?? '';
        return '_('.$index.')'.$ext;
    }

    protected function upcount_name($name)
    {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            $this->upcount_name_callback(...),
            (string) $name,
            1
        );
    }

    protected function get_unique_filename(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        while (is_dir($this->get_upload_path($name))) {
            $name = $this->upcount_name($name);
        }
        // Keep an existing filename if this is part of a chunked upload:
        $uploaded_bytes = $this->fix_integer_overflow((int)@$content_range[1]);
        while (is_file($this->get_upload_path($name))) {
            if ($uploaded_bytes === $this->get_file_size(
                $this->get_upload_path($name)
            )
            ) {
                break;
            }
            $name = $this->upcount_name($name);
        }
        return $name;
    }

    protected function fix_file_extension(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        // Add missing file extension for known image types:
        if (!str_contains((string) $name, '.')
            && preg_match('/^image\/(gif|jpe?g|png|webp)/', (string) $type, $matches)
        ) {
            $name .= '.'.$matches[1];
        }
        if ($this->options['correct_image_extensions']) {
            switch ($this->imagetype($file_path)) {
            case self::IMAGETYPE_WEBP:
                $extensions = ['webp'];
                break;
            case self::IMAGETYPE_JPEG:
                $extensions = ['jpg', 'jpeg'];
                break;
            case self::IMAGETYPE_PNG:
                $extensions = ['png'];
                break;
            case self::IMAGETYPE_GIF:
                $extensions = ['gif'];
                break;
            }
            // Adjust incorrect image file extensions:
            if (!empty($extensions)) {
                $parts = explode('.', (string) $name);
                $extIndex = count($parts) - 1;
                $ext = strtolower(@$parts[$extIndex]);
                if (!in_array($ext, $extensions)) {
                    $parts[$extIndex] = $extensions[0];
                    $name = implode('.', $parts);
                }
            }
        }
        return $name;
    }

    protected function trim_file_name(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {

        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $name = trim((string) $this->basename(stripslashes((string) $name)), ".\x00..\x20");
        // Replace dots in filenames to avoid security issues with servers
        // that interpret multiple file extensions, e.g. "example.php.png":
        $replacement = $this->options['replace_dots_in_filenames'];
        if (!empty($replacement)) {
            $parts = explode('.', $name);
            if (count($parts) > 2) {
                $ext = array_pop($parts);
                $name = implode($replacement, $parts).'.'.$ext;
            }
        }
        // Use a timestamp for empty filenames:
        if (!$name) {
            $name = str_replace('.', '-', microtime(true));
        }
        return $name;
    }

    protected function get_file_name(
        $file_path,
        $name,
        $size,
        $type,
        $error,
        $index,
        $content_range
    ) {
        $name = $this->trim_file_name(
            $file_path,
            $name,
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
        return $this->get_unique_filename(
            $file_path,
            $this->fix_file_extension(
                $file_path,
                $name,
                $size,
                $type,
                $error,
                $index,
                $content_range
            ),
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
    }

    protected function get_scaled_image_file_paths($file_name, $version)
    {
        $file_path = $this->get_upload_path($file_name);
        if (!empty($version)) {
            $version_dir = $this->get_upload_path(null, $version);
            if (!is_dir($version_dir)) {
                mkdir($version_dir, $this->options['mkdir_mode'], true);
            }
            $new_file_path = $version_dir.'/'.$file_name;
        } else {
            $new_file_path = $file_path;
        }
        return [$file_path, $new_file_path];
    }

    protected function gd_get_image_object($file_path, $func, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->gd_destroy_image_object($file_path);
            $this->image_objects[$file_path] = $func($file_path);
        }
        return $this->image_objects[$file_path];
    }

    protected function gd_set_image_object($file_path, $image)
    {
        $this->gd_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function gd_destroy_image_object($file_path)
    {
        $image = $this->image_objects[$file_path] ?? null ;
        return $image && imagedestroy($image);
    }

    protected function gd_imageflip($image, $mode)
    {
        if (function_exists('imageflip')) {
            return imageflip($image, $mode);
        }
        $new_width = $src_width = imagesx($image);
        $new_height = $src_height = imagesy($image);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $src_x = 0;
        $src_y = 0;
        switch ($mode) {
        case '1': // flip on the horizontal axis
            $src_y = $new_height - 1;
            $src_height = -$new_height;
            break;
        case '2': // flip on the vertical axis
            $src_x  = $new_width - 1;
            $src_width = -$new_width;
            break;
        case '3': // flip on both axes
            $src_y = $new_height - 1;
            $src_height = -$new_height;
            $src_x  = $new_width - 1;
            $src_width = -$new_width;
            break;
        default:
            return $image;
        }
        imagecopyresampled(
            $new_img,
            $image,
            0,
            0,
            $src_x,
            $src_y,
            $new_width,
            $new_height,
            $src_width,
            $src_height
        );
        return $new_img;
    }

    protected function gd_orient_image($file_path, $src_img)
    {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = (int)@$exif['Orientation'];
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
        case 2:
            $new_img = $this->gd_imageflip(
                $src_img,
                defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
            );
            break;
        case 3:
            $new_img = imagerotate($src_img, 180, 0);
            break;
        case 4:
            $new_img = $this->gd_imageflip(
                $src_img,
                defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
            );
            break;
        case 5:
            $tmp_img = $this->gd_imageflip(
                $src_img,
                defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
            );
            $new_img = imagerotate($tmp_img, 270, 0);
            imagedestroy($tmp_img);
            break;
        case 6:
            $new_img = imagerotate($src_img, 270, 0);
            break;
        case 7:
            $tmp_img = $this->gd_imageflip(
                $src_img,
                defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
            );
            $new_img = imagerotate($tmp_img, 270, 0);
            imagedestroy($tmp_img);
            break;
        case 8:
            $new_img = imagerotate($src_img, 90, 0);
            break;
        default:
            return false;
        }
        $this->gd_set_image_object($file_path, $new_img);
        return true;
    }

    protected function gd_create_scaled_image($file_name, $version, $options)
    {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        [$file_path, $new_file_path] =
            $this->get_scaled_image_file_paths($file_name, $version);
        $type = strtolower(substr(strrchr((string) $file_name, '.'), 1));
        switch ($type) {
        case 'jpg':
        case 'jpeg':
            $src_func = 'imagecreatefromjpeg';
            $write_func = 'imagejpeg';
            $image_quality = $options['jpeg_quality'] ?? 92;
            break;
        case 'gif':
            $src_func = 'imagecreatefromgif';
            $write_func = 'imagegif';
            $image_quality = null;
            break;
        case 'png':
            $src_func = 'imagecreatefrompng';
            $write_func = 'imagepng';
            $image_quality = $options['png_quality'] ?? 9;
            break;
        case 'webp':
            $src_func = 'imagecreatefromwebp';
            $write_func = 'imagewebp';
            $image_quality = $options['webp_quality'] ?? 100;
            break;
        default:
            return false;
        }
        $src_img = $this->gd_get_image_object(
            $file_path,
            $src_func,
            !empty($options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gd_orient_image(
            $file_path,
            $src_img
        )
        ) {
            $image_oriented = true;
            $src_img = $this->gd_get_image_object(
                $file_path,
                $src_func
            );
        }
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($options['max_width'])) {
            $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $max_height = $options['max_height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        if ($scale >= 1) {
            if ($image_oriented) {
                return $write_func($src_img, $new_file_path, $image_quality);
            }
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
        case 'gif':
        case 'png':
            imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            // no break
        case 'png':
            imagealphablending($new_img, false);
            imagesavealpha($new_img, true);
            break;
        }
        $success = imagecopyresampled(
            $new_img,
            $src_img,
            $dst_x,
            $dst_y,
            0,
            0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        ) && $write_func($new_img, $new_file_path, $image_quality);
        $this->gd_set_image_object($file_path, $new_img);
        return $success;
    }

    protected function imagick_get_image_object($file_path, $no_cache = false)
    {
        if (empty($this->image_objects[$file_path]) || $no_cache) {
            $this->imagick_destroy_image_object($file_path);
            $image = new \Imagick();
            if (!empty($this->options['imagick_resource_limits'])) {
                foreach ($this->options['imagick_resource_limits'] as $type => $limit) {
                    $image->setResourceLimit($type, $limit);
                }
            }
            $image->readImage($file_path);
            $this->image_objects[$file_path] = $image;
        }
        return $this->image_objects[$file_path];
    }

    protected function imagick_set_image_object($file_path, $image)
    {
        $this->imagick_destroy_image_object($file_path);
        $this->image_objects[$file_path] = $image;
    }

    protected function imagick_destroy_image_object($file_path)
    {
        $image = $this->image_objects[$file_path] ?? null ;
        return $image && $image->destroy();
    }

    protected function imagick_orient_image($image)
    {
        $orientation = $image->getImageOrientation();
        $background = new \ImagickPixel('none');
        switch ($orientation) {
        case \imagick::ORIENTATION_TOPRIGHT: // 2
            $image->flopImage(); // horizontal flop around y-axis
            break;
        case \imagick::ORIENTATION_BOTTOMRIGHT: // 3
            $image->rotateImage($background, 180);
            break;
        case \imagick::ORIENTATION_BOTTOMLEFT: // 4
            $image->flipImage(); // vertical flip around x-axis
            break;
        case \imagick::ORIENTATION_LEFTTOP: // 5
            $image->flopImage(); // horizontal flop around y-axis
            $image->rotateImage($background, 270);
            break;
        case \imagick::ORIENTATION_RIGHTTOP: // 6
            $image->rotateImage($background, 90);
            break;
        case \imagick::ORIENTATION_RIGHTBOTTOM: // 7
            $image->flipImage(); // vertical flip around x-axis
            $image->rotateImage($background, 270);
            break;
        case \imagick::ORIENTATION_LEFTBOTTOM: // 8
            $image->rotateImage($background, 270);
            break;
        default:
            return false;
        }
        $image->setImageOrientation(\imagick::ORIENTATION_TOPLEFT); // 1
        return true;
    }

    protected function imagick_create_scaled_image($file_name, $version, $options)
    {
        [$file_path, $new_file_path] =
            $this->get_scaled_image_file_paths($file_name, $version);
        $image = $this->imagick_get_image_object(
            $file_path,
            !empty($options['crop']) || !empty($options['no_cache'])
        );
        if ($image->getImageFormat() === 'GIF') {
            // Handle animated GIFs:
            $images = $image->coalesceImages();
            foreach ($images as $frame) {
                $image = $frame;
                $this->imagick_set_image_object($file_name, $image);
                break;
            }
        }
        $image_oriented = false;
        if (!empty($options['auto_orient'])) {
            $image_oriented = $this->imagick_orient_image($image);
        }

        $image_resize = false;
        $new_width = $max_width = $img_width = $image->getImageWidth();
        $new_height = $max_height = $img_height = $image->getImageHeight();

        // use isset(). User might be setting max_width = 0 (auto in regular resizing). Value 0 would be considered empty when you use empty()
        if (isset($options['max_width'])) {
            $image_resize = true;
            $new_width = $max_width = $options['max_width'];
        }
        if (isset($options['max_height'])) {
            $image_resize = true;
            $new_height = $max_height = $options['max_height'];
        }

        $image_strip = ($options['strip'] ?? false);

        if (!$image_oriented && ($max_width >= $img_width) && ($max_height >= $img_height) && !$image_strip && empty($options["jpeg_quality"])) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $crop = ($options['crop'] ?? false);

        if ($crop) {
            $x = 0;
            $y = 0;
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = 0; // Enables proportional scaling based on max_height
                $x = ($img_width / ($img_height / $max_height) - $max_width) / 2;
            } else {
                $new_height = 0; // Enables proportional scaling based on max_width
                $y = ($img_height / ($img_width / $max_width) - $max_height) / 2;
            }
        }
        $success = $image->resizeImage(
            $new_width,
            $new_height,
            $options['filter'] ?? \imagick::FILTER_LANCZOS,
            $options['blur'] ?? 1,
            $new_width && $new_height // fit image into constraints if not to be cropped
        );
        if ($success && $crop) {
            $success = $image->cropImage(
                $max_width,
                $max_height,
                $x,
                $y
            );
            if ($success) {
                $success = $image->setImagePage($max_width, $max_height, 0, 0);
            }
        }
        $type = strtolower(substr(strrchr((string) $file_name, '.'), 1));
        switch ($type) {
        case 'jpg':
        case 'jpeg':
            if (!empty($options['jpeg_quality'])) {
                $image->setImageCompression(\imagick::COMPRESSION_JPEG);
                $image->setImageCompressionQuality($options['jpeg_quality']);
            }
            break;
        }
        if ($image_strip) {
            $image->stripImage();
        }
        return $success && $image->writeImage($new_file_path);
    }

    protected function imagemagick_create_scaled_image($file_name, $version, $options)
    {
        [$file_path, $new_file_path] =
            $this->get_scaled_image_file_paths($file_name, $version);
        $resize = @$options['max_width']
            .(empty($options['max_height']) ? '' : 'X'.$options['max_height']);
        if (!$resize && empty($options['auto_orient'])) {
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        $cmd = $this->options['convert_bin'];
        if (!empty($this->options['convert_params'])) {
            $cmd .= ' '.$this->options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg((string) $file_path);
        if (!empty($options['auto_orient'])) {
            $cmd .= ' -auto-orient';
        }
        if ($resize) {
            // Handle animated GIFs:
            $cmd .= ' -coalesce';
            if (empty($options['crop'])) {
                $cmd .= ' -resize '.escapeshellarg($resize.'>');
            } else {
                $cmd .= ' -resize '.escapeshellarg($resize.'^');
                $cmd .= ' -gravity center';
                $cmd .= ' -crop '.escapeshellarg($resize.'+0+0');
            }
            // Make sure the page dimensions are correct (fixes offsets of animated GIFs):
            $cmd .= ' +repage';
        }
        if (!empty($options['convert_params'])) {
            $cmd .= ' '.$options['convert_params'];
        }
        $cmd .= ' '.escapeshellarg((string) $new_file_path);
        exec($cmd, $output, $error);
        if ($error) {
            error_log(implode('\n', $output));
            return false;
        }
        return true;
    }

    protected function get_image_size($file_path)
    {
        if ($this->options['image_library']) {
            if (extension_loaded('imagick')) {
                $image = new \Imagick();
                try {
                    if (@$image->pingImage($file_path)) {
                        $dimensions = [$image->getImageWidth(), $image->getImageHeight()];
                        $image->destroy();
                        return $dimensions;
                    }
                    return false;
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
            }
            if ($this->options['image_library'] === 2) {
                $cmd = $this->options['identify_bin'];
                $cmd .= ' -ping '.escapeshellarg((string) $file_path);
                exec($cmd, $output, $error);
                if (!$error && !empty($output)) {
                    // image.jpg JPEG 1920x1080 1920x1080+0+0 8-bit sRGB 465KB 0.000u 0:00.000
                    $infos = preg_split('/\s+/', substr($output[0], strlen((string) $file_path)));
                    $dimensions = preg_split('/x/', $infos[2]);
                    return $dimensions;
                }
                return false;
            }
        }
        if (!function_exists('getimagesize')) {
            error_log('Function not found: getimagesize');
            return false;
        }
        return @getimagesize($file_path);
    }

    protected function create_scaled_image($file_name, $version, $options)
    {
        try {
            if ($this->options['image_library'] === 2) {
                return $this->imagemagick_create_scaled_image($file_name, $version, $options);
            }
            if ($this->options['image_library'] && extension_loaded('imagick')) {
                return $this->imagick_create_scaled_image($file_name, $version, $options);
            }
            return $this->gd_create_scaled_image($file_name, $version, $options);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    protected function destroy_image_object($file_path)
    {
        if ($this->options['image_library'] && extension_loaded('imagick')) {
            return $this->imagick_destroy_image_object($file_path);
        }
    }

    protected function imagetype($file_path)
    {
        $fp = fopen($file_path, 'r');
        $data = fread($fp, 4);
        fclose($fp);
        // GIF: 47 49 46 38
        if ($data === 'GIF8') {
            return self::IMAGETYPE_GIF;
        }
        // JPG: FF D8 FF
        if (bin2hex(substr($data, 0, 3)) === 'ffd8ff') {
            return self::IMAGETYPE_JPEG;
        }
        // PNG: 89 50 4E 47
        if (bin2hex(@$data[0]).substr($data, 1, 4) === '89PNG') {
            return self::IMAGETYPE_PNG;
        }
        // WebP: 52 49 46 46 ?? ?? ?? ?? 57 45 42 50
        if ($data === 'RIFF') {
            return self::IMAGETYPE_WEBP;
        }

        return false;
    }

    protected function is_valid_image_file($file_path)
    {
        if (!preg_match('/\.(gif|jpe?g|png|webp)$/i', (string) $file_path)) {
            return false;
        }
        return !!$this->imagetype($file_path);
    }

    protected function handle_image_file($file_path, $file)
    {
        $failed_versions = [];
        foreach ($this->options['image_versions'] as $version => $options) {
            if ($this->create_scaled_image($file->name, $version, $options)) {
                if (!empty($version)) {
                    /*
                    $file->{$version.'Url'} = $this->get_download_url(
                        $file->name,
                        $version
                    );
                    */
                } else {
                    $file->size = $this->get_file_size($file_path, true);
                }
            } else {
                $failed_versions[] = $version ?: 'original';
            }
        }
        if (count($failed_versions)) {
            $file->error = $this->get_error_message('image_resize')
                    .' ('.implode($failed_versions, ', ').')';
        }
        // Free memory:
        $this->destroy_image_object($file_path);
    }

    protected function handle_file_upload(
        $uploaded_file,
        $name,
        $size,
        $type,
        $error,
        $index = null,
        $content_range = null
    ) {
        $file = new \stdClass();
        $file->name = $this->get_file_name(
            $uploaded_file,
            $name,
            $size,
            $type,
            $error,
            $index,
            $content_range
        );
        $file->size = $this->fix_integer_overflow((int)$size);
        $file->type = $type;

        if ($this->validate($uploaded_file, $file, $error, $index)) {
            $this->handle_form_data($file, $index);
            $upload_dir = $this->get_upload_path();
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, $this->options['mkdir_mode'], true);
            }
            $file_path = $this->get_upload_path($file->name);
            $append_file = $content_range && is_file($file_path) &&
                $file->size > $this->get_file_size($file_path);
            if ($uploaded_file && is_uploaded_file($uploaded_file)) {
                // multipart/formdata uploads (POST method uploads)
                if ($append_file) {
                    file_put_contents(
                        $file_path,
                        fopen($uploaded_file, 'r'),
                        FILE_APPEND
                    );
                } else {
                    move_uploaded_file($uploaded_file, $file_path);
                }
            }
            $file_size = $this->get_file_size($file_path, $append_file);
            if ($file_size === $file->size) {
                if ($this->is_valid_image_file($file_path)) {
                    $this->handle_image_file($file_path, $file);
                }
            } else {
                $file->size = $file_size;
                if (!$content_range && $this->options['discard_aborted_uploads']) {
                    @unlink($file_path);
                    $file->error = $this->get_error_message('abort');
                }
            }
            $this->set_additional_file_properties($file);
        }
        return $file;
    }

    protected function readfile($file_path)
    {
        $file_size = $this->get_file_size($file_path);
        $chunk_size = $this->options['readfile_chunk_size'];
        if ($chunk_size && $file_size > $chunk_size) {
            $handle = fopen($file_path, 'rb');
            while (!feof($handle)) {
                echo fread($handle, $chunk_size);
                @ob_flush();
                @flush();
            }
            fclose($handle);
            return $file_size;
        }
        return readfile($file_path);
    }

    protected function body($str)
    {
        echo $str;
    }

    protected function header($str)
    {
        header($str);
    }

    protected function get_upload_data($id)
    {
        return @$_FILES[$id];
    }

    protected function get_post_param($id)
    {
        return @$_POST[$id];
    }

    protected function get_query_param($id)
    {
        return @$_GET[$id];
    }

    protected function get_server_var($id)
    {
        return @$_SERVER[$id];
    }

    protected function handle_form_data($file, $index)
    {
        // Handle form data, e.g. $_POST['description'][$index]
    }

    protected function get_version_param()
    {
        return $this->basename(stripslashes((string) $this->get_query_param('version')));
    }

    protected function get_singular_param_name()
    {
        return substr((string) $this->options['param_name'], 0, -1);
    }

    protected function get_file_name_param()
    {
        $name = $this->get_singular_param_name();
        return $this->basename(stripslashes((string) $this->get_query_param($name)));
    }

    protected function get_file_names_params()
    {
        $params = $this->get_query_param($this->options['param_name']);
        if (!$params) {
            return null;
        }
        foreach ($params as $key => $value) {
            $params[$key] = $this->basename(stripslashes((string) $value));
        }
        return $params;
    }

    protected function get_file_type($file_path)
    {
        return match (strtolower(pathinfo((string) $file_path, PATHINFO_EXTENSION))) {
            'jpeg', 'jpg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => '',
        };
    }

    public function generate_response($content, $print_response = true)
    {
        $files = $content[$this->options['param_name']] ?? null;
        if ($files && is_array($files) && is_object($files[0]) && $files[0]->name) {
            $this->set_filename($files[0]->name);
        }

        foreach ($files as $index => $value) {
            if (is_object($files[$index]) && $files[$index]->name) {
                $this->response[] = $files[$index]->name;
            }
        }

        return;
    }

    public function get_response()
    {
        return $this->response;
    }

    public function head()
    {
        $this->header('Pragma: no-cache');
        $this->header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->header('Content-Disposition: inline; filename="files.json"');
        // Prevent Internet Explorer from MIME-sniffing the content-type:
        $this->header('X-Content-Type-Options: nosniff');
        if ($this->options['access_control_allow_origin']) {
            $this->send_access_control_headers();
        }
        $this->send_content_type_header();
    }


    public function parse($print_response = false)
    {
        $upload = $this->get_upload_data($this->file);
        // Parse the Content-Disposition header, if available:
        $content_disposition_header = $this->get_server_var('HTTP_CONTENT_DISPOSITION');
        $file_name = $content_disposition_header ?
            rawurldecode(
                preg_replace(
                    '/(^[^"]+")|("$)/',
                    '',
                    (string) $content_disposition_header
                )
            ) : null;
        // Parse the Content-Range header, which has the following form:
        // Content-Range: bytes 0-524287/2000000
        $content_range_header = $this->get_server_var('HTTP_CONTENT_RANGE');
        $content_range = $content_range_header ?
            preg_split('/[^0-9]+/', (string) $content_range_header) : null;
        $size =  $content_range ? $content_range[3] : null;

        $files = [];
        if ($upload) {
            if (is_array($upload['tmp_name'])) {
                // param_name is an array identifier like "files[]",
                // $upload is a multi-dimensional array:
                foreach ($upload['tmp_name'] as $index => $value) {
                    $files[] = $this->handle_file_upload(
                        $upload['tmp_name'][$index],
                        $file_name ?: $upload['name'][$index],
                        $size ?: $upload['size'][$index],
                        $upload['type'][$index],
                        $upload['error'][$index],
                        $index,
                        $content_range
                    );
                }
            } else {
                // param_name is a single object identifier like "file",
                // $upload is a one-dimensional array:
                $files[] = $this->handle_file_upload(
                    $upload['tmp_name'] ?? null,
                    $file_name ?: $upload['name'] ?? null,
                    $size ?: $upload['size'] ?? $this->get_server_var('CONTENT_LENGTH'),
                    $upload['type'] ?? $this->get_server_var('CONTENT_TYPE'),
                    $upload['error'] ?? null,
                    null,
                    $content_range
                );
            }
        }

        $response = [$this->options['param_name'] => $files];
        $this->generate_response($response, $print_response);

        if (isset($upload['error']) && $upload['error'] != 0) {
            return false;
        } else {
            return true;
        }
    }

    protected function basename($filepath, $suffix = '')
    {
        $splited = preg_split('/\//', rtrim((string) $filepath, '/ '));
        return substr(basename('X'.$splited[(is_countable($splited) ? count($splited) : 0)-1], $suffix), 1);
    }
}
