<?php
/* ----------------------------------------------------------------------
   $Id: class_message_stack.php 296 2013-04-13 14:48:55Z r23 $

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

   $oMessage = new messageStack();
   $oMessage->add('Error: Error 1', 'error');
   $oMessage->add('Error: Error 2', 'warning');
   if ($oMessage->size > 0) echo $oMessage->output();
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class messageStack {

    function messageStack() {
      global $messageToStack;

      $this->messages = array();

      if (isset($_SESSION['messageToStack'])) {
        $messageToStack = $_SESSION['messageToStack'];
        for ($i=0, $n=count($messageToStack); $i<$n; $i++) {
          $this->add($messageToStack[$i]['class'], $messageToStack[$i]['text'], $messageToStack[$i]['type']);
        }
        unset($_SESSION['messageToStack']);
      }
    }

// class methods
    function add($class, $message, $type = 'error') {
      $this->messages[] = array('class' => $class, 'type' => $type, 'text' => $message);
    }

    function add_session($class, $message, $type = 'error') {

      if (!isset($_SESSION['messageToStack'])) {
        $messageToStack = array();
      } else {
        $messageToStack = $_SESSION['messageToStack'];
      }

      $messageToStack[] = array('class' => $class, 'text' => $message, 'type' => $type);
      $_SESSION['messageToStack'] = $messageToStack;
      $this->add($class, $message, $type);
    }

    function reset() {
      $this->messages = array();
    }

    function output($class) {
      $output = array();
      for ($i=0, $n=count($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $output[] = $this->messages[$i];
        }
      }

      return $output;
    }

    function size($class) {
      $count = 0;

      for ($i=0, $n=count($this->messages); $i<$n; $i++) {
        if ($this->messages[$i]['class'] == $class) {
          $count++;
        }
      }

      return $count;
    }
  }
?>
