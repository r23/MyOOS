<?php
/* ----------------------------------------------------------------------
   $Id: class_message_stack.php 437 2013-06-22 15:33:30Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: message_stack.php,v 1.5 2002/11/22 18:45:46 dgw_ 
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- 
   Example usage:

   $messageStack = new messageStack();
   $messageStack->add('Error: Error 1', 'error');
   $messageStack->add('Error: Error 2', 'warning');
   if ($messageStack->size > 0) echo $messageStack->output();
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class messageStack extends tableBlock {
    var $size = 0;

    function messageStack() {
      global $messageToStack;

      $this->errors = array();

      if (isset($_SESSION['messageToStack'])) {
         $messageToStack =& $_SESSION['messageToStack'];
      }
      for ($i=0; $i < count($messageToStack); $i++) {
        $this->add($messageToStack[$i]['text'], $messageToStack[$i]['type']);
      }
      unset($_SESSION['messageToStack']);
    }

    function add($message, $type = 'error') {
      $this->errors[] = array('type' => $type, 'text' => $message);
    }

    function add_session($message, $type = 'error') {
      global $messageToStack;

      if (!isset($_SESSION['messageToStack'])) {
        $_SESSION['messageToStack'] = array();
      }

      $_SESSION['messageToStack'][] = array('text' => $message, 'type' => $type);

    }

    function reset() {
      $this->errors = array();
    }

    function output($type) {
      $output = array();
      for ($i=0, $n=count($this->errors); $i<$n; $i++) {
        if ($this->errors[$i]['type'] == $type) {
          $output[] = $this->errors[$i];
        }
      }

      return $output;
    }
	
    function size($type) {
      $count = 0;

      for ($i=0, $n=count($this->errors); $i<$n; $i++) {
        if ($this->errors[$i]['type'] == $type) {
          $count++;
        }
      }

      return $count;
    }
  }

