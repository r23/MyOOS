<?php
/* ----------------------------------------------------------------------
   $Id: class_navigation_history.php,v 1.1 2007/06/07 16:06:31 r23 Exp $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2014 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: navigation_history.php,v 1.5 2003/02/12 21:07:45 hpdl 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) or die( 'Direct Access to this location is not allowed.' );

  /**
   * Class Navigation History
   *
   *
   * @link      http://www.oos-shop.de/ Latest release of this class
   * @package   Products History
   * @version   $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   * @access    public
   */
  class oosNavigationHistory {

     var $path;
     var $snapshot;

    /**
     * Constructor of our Class
     */
     function oosNavigationHistory() {
       $this->reset();
     }


     function reset() {
       $this->path = array();
       $this->snapshot = array();
     }


     function add_current_page() {
      global $sMp, $sFile, $request_type;

      $get_all = ''; 
      if (isset($_GET)) {
        $get_all = oos_get_all_get_parameters();
        $get_all = oos_remove_trailing($get_all);
      }
      $this->path[] = array('modules' => $sMp,
                            'file' => $sFile,
                            'mode' => $request_type,
                            'get' => $get_all);

    }


    function remove_current_page() {
      global $sMp, $sFile;

      $last_entry_position = count($this->path) - 1;
      if ( ($this->path[$last_entry_position]['modules'] == $sMp)  && ($this->path[$last_entry_position]['file'] == $sFile) ) {
        unset($this->path[$last_entry_position]);
      }
    }


    function remove_last_page() {

      $last_entry_position = count($this->path) - 1;
      if ($last_entry_position > 0) {
        unset($this->path[$last_entry_position]);
      }
    }



    function set_snapshot($page = '') {
      global $sMp, $sFile, $request_type;

      if (is_array($page)) {
        $this->snapshot = array('modules' => $page['modules'],
                                'file' => $page['file'],
                                'mode' => $page['mode'],
                                'get' => $page['get']);
      } else {
        $get_all = ''; 
        if (isset($_GET)) {
          $get_all = oos_get_all_get_parameters();
          $get_all = oos_remove_trailing($get_all);
        }
        $this->snapshot = array('modules' => $sMp,
                                'file' => $sFile,
                                'mode' => $request_type,
                                'get' => $get_all);
      }
    }


    function clear_snapshot() {
      $this->snapshot = array();
    }

    function set_path_as_snapshot($history = 0) {
      $pos = (count($this->path)-1-$history);
      $this->snapshot = array('modules' => $this->path[$pos]['modules'],
                              'file' => $this->path[$pos]['file'],
                              'mode' => $this->path[$pos]['mode'],
                              'get' => $this->path[$pos]['get']);
    }


    function debug() {
      for ($i=0, $n=count($this->path); $i<$n; $i++) {
        echo $this->path[$i]['modules'] . '&' . $this->path[$i]['file'] . '&' . $this->path[$i]['get'] . '<br />';
        echo '<br />';
      }

      echo '<br /><br />';
      if (count($this->snapshot) > 0) {
        echo $this->snapshot['mode'] . ' ' . $this->snapshot['modules'] . '&' . $this->snapshot['file'] . '&' . $this->snapshot['get'] . '<br />';
      }
    }


    function unserialize($broken) {
      for(reset($broken);$kv=each($broken);) {
        $key=$kv['key'];
        if (gettype($this->$key)!="user function")
        $this->$key=$kv['value'];
      }
    }
  }
?>
