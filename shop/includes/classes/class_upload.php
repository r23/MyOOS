<?php
/* ----------------------------------------------------------------------
   $Id: class_upload.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: upload.php,v 1.2 2003/06/20 00:18:30 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */


  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class upload {
    var $sContent;
    var $sContentname;
    var $destination;
    var $permissions;
    var $extensions;
    var $tmp_filename;
    var $message_location;


    function upload($sContent = '', $destination = '', $permissions = '644', $extensions = array('jpg', 'jpeg', 'gif', 'png', 'eps', 'cdr', 'ai', 'pdf', 'tif', 'tiff', 'bmp')) {
      $this->set_file($sContent);
      $this->set_destination($destination);
      $this->set_permissions($permissions);
      $this->set_extensions($extensions);

      $this->set_output_messages('direct');

      if (oos_is_not_null($this->file) && oos_is_not_null($this->destination)) {
        $this->set_output_messages('session');

        if ( ($this->parse() == true) && ($this->save() == true) ) {
          return true;
        } else {
          return false;
        }
      }
    }


    function parse($key = '') {
      global $oMessage, $aLang;

      if (isset($_FILES[$this->file])) {
        if (oos_is_not_null($key)) {
          $sContent = array('name' => $_FILES[$this->file]['name'][$key],
                        'type' => $_FILES[$this->file]['type'][$key],
                        'size' => $_FILES[$this->file]['size'][$key],
                        'tmp_name' => $_FILES[$this->file]['tmp_name'][$key]);
        } else {
          $sContent = array('name' => $_FILES[$this->file]['name'],
                        'type' => $_FILES[$this->file]['type'],
                        'size' => $_FILES[$this->file]['size'],
                        'tmp_name' => $_FILES[$this->file]['tmp_name']);
        }
      } elseif (isset($GLOBALS['HTTP_POST_FILES'][$this->file])) {
        global $HTTP_POST_FILES;

        $sContent = array('name' => $HTTP_POST_FILES[$this->file]['name'],
                      'type' => $HTTP_POST_FILES[$this->file]['type'],
                      'size' => $HTTP_POST_FILES[$this->file]['size'],
                      'tmp_name' => $HTTP_POST_FILES[$this->file]['tmp_name']);
      } else {
        $sContent = array('name' => (isset($GLOBALS[$this->file . '_name']) ? $GLOBALS[$this->file . '_name'] : ''),
                      'type' => (isset($GLOBALS[$this->file . '_type']) ? $GLOBALS[$this->file . '_type'] : ''),
                      'size' => (isset($GLOBALS[$this->file . '_size']) ? $GLOBALS[$this->file . '_size'] : ''),
                      'tmp_name' => (isset($GLOBALS[$this->file]) ? $GLOBALS[$this->file] : ''));
      }


      if ( oos_is_not_null($sContent['tmp_name']) && ($sContent['tmp_name'] != 'none') && is_uploaded_file($sContent['tmp_name']) ) {

        if (oos_is_not_null($sContent['size']) and ($sContent['size'] > 2048000)) {
          if ($this->message_location == 'direct') {
            $oMessage->add('upload', $aLang['error_file_too_big'], 'error');
          } else {
            $oMessage->add_session('upload', $aLang['error_file_too_big'], 'error');
          }
          return false;
        }

        if (sizeof($this->extensions) > 0) {
          if (!in_array(strtolower(substr($sContent['name'], strrpos($sContent['name'], '.')+1)), $this->extensions)) {
            if ($this->message_location == 'direct') {
              $oMessage->add('upload', $aLang['error_filetype_not_allowed'], 'error');
            } else {
              $oMessage->add_session('upload', $aLang['error_filetype_not_allowed'], 'error');
            }
            return false;
          }
        }

        $this->set_file($sContent);
        $this->set_filename($sContent['name']);
        $this->set_tmp_filename($sContent['tmp_name']);

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

    function save() {
      global $oMessage, $aLang;

      if (substr($this->destination, -1) != '/') $this->destination .= '/';

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

    function set_file($sContent) {
      $this->file = $sContent;
    }

    function set_destination($destination) {
      $this->destination = $destination;
    }

    function set_permissions($permissions) {
      $this->permissions = octdec($permissions);
    }

    function set_filename($sContentname) {
      $this->filename = $sContentname;
    }

    function set_tmp_filename($sContentname) {
      $this->tmp_filename = $sContentname;
    }

    function set_extensions($extensions) {
      if (oos_is_not_null($extensions)) {
        if (is_array($extensions)) {
          $this->extensions = $extensions;
        } else {
          $this->extensions = array($extensions);
        }
      } else {
        $this->extensions = array();
      }
    }

    function check_destination() {
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

    function set_output_messages($location) {
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


