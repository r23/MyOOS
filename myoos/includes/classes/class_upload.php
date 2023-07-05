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

class upload
{
    public $file;
    public $filename;
    public $destination;
    public $permissions;
    public $extensions;
    public $tmp_filename;
    public $message_location;


    public function __construct($file = '', $destination = '', $permissions = '644', $extensions = array('jpg', 'jpeg', 'gif', 'png', 'eps', 'cdr', 'ai', 'pdf', 'tif', 'tiff', 'bmp'))
    {
        $this->set_file($file);
        $this->set_destination($destination);
        $this->set_permissions($permissions);
        $this->set_extensions($extensions);

        $this->set_output_messages('direct');

        if (oos_is_not_null($this->file) && oos_is_not_null($this->destination)) {
            $this->set_output_messages('session');

            if (($this->parse() == true) && ($this->save() == true)) {
                return true;
            } else {
                return false;
            }
        }
    }


    public function parse()
    {
        global $oMessage, $aLang;

        $file = [];

        if (isset($_FILES[$this->file])) {
            $file = array('name' => $_FILES[$this->file]['name'],
                      'type' => $_FILES[$this->file]['type'],
                      'size' => $_FILES[$this->file]['size'],
                      'tmp_name' => $_FILES[$this->file]['tmp_name']);
        }


        if (isset($file['tmp_name']) && oos_is_not_null($file['tmp_name']) && ($file['tmp_name'] != 'none') && is_uploaded_file($file['tmp_name'])) {
            if (oos_is_not_null($file['size']) and ($file['size'] > 2048000)) {
                if ($this->message_location == 'direct') {
                    $oMessage->add('upload', $aLang['error_file_too_big'], 'error');
                } else {
                    $oMessage->add_session('upload', $aLang['error_file_too_big'], 'error');
                }
                return false;
            }

            if (sizeof($this->extensions) > 0) {
                if (!in_array(strtolower(substr($file['name'], strrpos($file['name'], '.')+1)), $this->extensions)) {
                    if ($this->message_location == 'direct') {
                        $oMessage->add('upload', $aLang['error_filetype_not_allowed'], 'error');
                    } else {
                        $oMessage->add_session('upload', $aLang['error_filetype_not_allowed'], 'error');
                    }
                    return false;
                }
            }

            $this->set_file($file);
            $this->set_filename($file['name']);
            $this->set_tmp_filename($file['tmp_name']);

            return $this->check_destination();
        } else {
            if ($this->message_location == 'direct') {
                $oMessage->add('upload', $aLang['warning_no_file_uploaded'], 'warning');
            } else {
                $oMessage->add_session('upload', $aLang['warning_no_file_uploaded'], 'warning');
            }
            return false;
        }
    }

    public function save()
    {
        global $oMessage, $aLang;

        if (substr($this->destination, -1) != '/') {
            $this->destination .= '/';
        }

        if (move_uploaded_file($this->file['tmp_name'], $this->destination . $this->filename)) {
            chmod($this->destination . $this->filename, $this->permissions);

            $oMessage->add_session('upload', $aLang['success_file_saved_successfully'], 'success');

            return true;
        } else {
            if ($this->message_location == 'direct') {
                $oMessage->add('upload', $aLang['error_file_not_saved'], 'error');
            } else {
                $oMessage->add_session('upload', $aLang['error_file_not_saved'], 'error');
            }

            return false;
        }
    }

    public function set_file($file)
    {
        $this->file = $file;
    }

    public function set_destination($destination)
    {
        $this->destination = $destination;
    }

    public function set_permissions($permissions)
    {
        $this->permissions = octdec($permissions);
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
                $this->extensions = array($extensions);
            }
        } else {
            $this->extensions = [];
        }
    }

    public function check_destination()
    {
        global $oMessage, $aLang;

        if (!is_writeable($this->destination)) {
            if (is_dir($this->destination)) {
                if ($this->message_location == 'direct') {
                    $oMessage->add('upload', $aLang['error_destination_not_writeable'], 'error');
                } else {
                    $oMessage->add_session('upload', $aLang['error_destination_not_writeable'], 'error');
                }
            } else {
                if ($this->message_location == 'direct') {
                    $oMessage->add('upload', $aLang['error_destination_does_not_exist'], 'error');
                } else {
                    $oMessage->add_session('upload', $aLang['error_destination_does_not_exist'], 'error');
                }
            }

            return false;
        } else {
            return true;
        }
    }

    public function set_output_messages($location)
    {
        switch ($location) {
        case 'session':
            $this->message_location = 'session';
            break;
        case 'direct':
        default:
            $this->message_location = 'direct';
            break;
        }
    }
}
