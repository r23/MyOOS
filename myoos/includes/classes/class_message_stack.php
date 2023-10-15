<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
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
   $oMessage->add('error', 'Error: Error 1');
   $oMessage->add('warning', 'Error: Error 2');
   if ($oMessage->size > 0) echo $oMessage->output();
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

class messageStack
{
    public function __construct()
    {
        $this->messages = [];

        if (isset($_SESSION)) {
            $n = is_countable($_SESSION['messageToStack']) ? count($_SESSION['messageToStack']) : 0;
            for ($i=0, $n; $i<$n; $i++) {
                $this->add($_SESSION['messageToStack'][$i]['text'], $_SESSION['messageToStack'][$i]['type']);
            }
            unset($_SESSION['messageToStack']);
        }
    }

    // class methods
    public function add($message, $type = 'danger')
    {
        $message = trim((string) $message);

        if (strlen($message ?? '') > 0) {
            if (!in_array($type, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'])) {
                $type = 'danger';
            }

            $this->messages[] = ['type' => $type, 'text' => $message];
        }
    }

    public function add_session($message, $type = 'danger')
    {
        if (!isset($_SESSION['messageToStack'])) {
            $_SESSION['messageToStack'] = [];
        }

        if (strlen($message ?? '') > 0) {
            if (!in_array($type, ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'])) {
                $type = 'danger';
            }

            $_SESSION['messageToStack'][] = ['type' => $type, 'text' => $message];
        }
    }

    public function reset()
    {
        $this->messages = [];
    }

    public function output($type)
    {
        $output = [];

        foreach ($this->messages as $next_message) {
            if ($next_message['type'] == $type) {
                $output[] = $next_message;
            }
        }

        return $output;
    }


    public function size($type)
    {
        if (!empty($_SESSION['messageToStack'])) {
            foreach ($_SESSION['messageToStack'] as $next_message) {
                $this->add($next_message['text'], $next_message['type']);
            }
        }

        $count = 0;

        foreach ($this->messages as $next_message) {
            if ($next_message['type'] == $type) {
                $count++;
            }
        }

        return $count;
    }
}
