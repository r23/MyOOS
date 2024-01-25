<?php
/**
   ----------------------------------------------------------------------
   $Id: class_message_stack.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
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
   ----------------------------------------------------------------------
 */

/**
 * ensure this file is being included by a parent file
 */
defined('OOS_VALID_MOD') or die('Direct Access to this location is not allowed.');

#[AllowDynamicProperties]
class messageStack
{
    public $size = 0;

    public function __construct()
    {
        $this->errors = [];

        if (isset($_SESSION['messageToStack'])) {
            for ($i = 0, $n = sizeof($_SESSION['messageToStack']); $i < $n; $i++) {
                $this->add($_SESSION['messageToStack'][$i]['text'], $_SESSION['messageToStack'][$i]['type']);
            }
            unset($_SESSION['messageToStack']);
        }
    }

    public function add($message, $type = 'error')
    {
        if ($type == 'error') {
            $this->errors[] = ['params' => 'alert-danger', 'text' => $message];
        } elseif ($type == 'warning') {
            $this->errors[] = ['params' => 'alert-warning', 'text' => $message];
        } elseif ($type == 'success') {
            $this->errors[] = ['params' => 'alert-success', 'text' =>  $message];
        } else {
            $this->errors[] = ['params' => 'alert-info', 'text' => $message];
        }

        $this->size++;
    }

    public function add_session($message, $type = 'error')
    {
        if (!isset($_SESSION['messageToStack'])) {
            $_SESSION['messageToStack'] = [];
        }

        $_SESSION['messageToStack'][] = ['text' => $message, 'type' => $type];
    }

    public function reset()
    {
        $this->errors = [];
        $this->size = 0;
    }

    public function output()
    {
        $sMessageBox =    '';

        $aContents = $this->errors;

        for ($i = 0, $n = is_countable($aContents) ? count($aContents) : 0; $i < $n; $i++) {
            $sMessageBox .=    '<div class="alert';
            if (isset($aContents[$i]['params']) && oos_is_not_null($aContents[$i]['params'])) {
                $sMessageBox .= ' ' . $aContents[$i]['params'];
            }
            $sMessageBox .= '" role="alert">';
            if (isset($aContents[$i]['text']) && oos_is_not_null($aContents[$i]['text'])) {
                $sMessageBox .= ' ' . $aContents[$i]['text'];
            }
            $sMessageBox .= '</div>' . "\n";
        }

        return $sMessageBox;
    }
}
