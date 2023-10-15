<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: payment.php,v 1.3.2.1 2003/05/03 23:41:23 wilt
   orig: payment.php,v 1.36 2003/02/11 00:04:53 hpdl
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


class payment
{
    public $modules;
    public $selected_module;

    // class constructor
    public function __construct($module = '')
    {
        global $aUser, $aLang, $GLOBALS;

        if (defined('MODULE_PAYMENT_INSTALLED') && oos_is_not_null($aUser['payment'])) {
            $this->modules = explode(';', (string) $aUser['payment']);

            $include_modules = [];

            if ((oos_is_not_null($module))) {
                $this->selected_module = $module;

                $include_modules[] = ['class' => $module, 'file' => $module . '.php'];
            } else {
                foreach ($this->modules as $value) {
                    $class = basename($value, '.php');
                    $include_modules[] = ['class' => $class, 'file' => $value];
                }
            }

            $sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;

            $n = is_countable($include_modules) ? count($include_modules) : 0;
            for ($i=0, $n; $i<$n; $i++) {
                include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/payment/' . $include_modules[$i]['file'];
                include_once MYOOS_INCLUDE_PATH . '/includes/modules/payment/' . $include_modules[$i]['file'];

                $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class']();
            }

            // if there is only one payment method, select it as default because in
            // checkout_confirmation.php the $payment variable is being assigned the
            if ((oos_count_payment_modules() == 1) && (!is_object($_SESSION['payment']))) {
                $_SESSION['payment'] = $include_modules[0]['class'];
            }

            if ((oos_is_not_null($module)) && (in_array($module, $this->modules)) && (isset($GLOBALS[$module]->form_action_url))) {
                $this->form_action_url = $GLOBALS[$module]->form_action_url;
            }
        }
    }


    // class methods
    /* The following method is needed in the checkout_confirmation.php page
       due to a chicken and egg problem with the payment class and order class.
       The payment modules needs the order destination data for the dynamic status
       feature, and the order class needs the payment module title.
       The following method is a work-around to implementing the method in all
       payment modules available which would break the modules in the contributions
       section. This should be looked into again post 2.2.
     */
    public function update_status()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module])) {
                if (function_exists('method_exists')) {
                    if (method_exists($GLOBALS[$this->selected_module], 'update_status')) {
                        $GLOBALS[$this->selected_module]->update_status();
                    }
                }
            }
        }
    }

    public function javascript_validation()
    {
        global $aLang;

        $js = '';
        if (is_array($this->modules)) {
            $js = '<script nonce="' . NONCE . '">' . "\n" .
              'public function check_form() {' . "\n" .
              '  let error = 0;' . "\n" .
              '  let error_message = "' . $aLang['js_error'] . '";' . "\n" .
              '  let payment_value = null;' . "\n" .
              '  if (document.checkout_payment.payment.length) {' . "\n" .
              '    for (let i=0; i<document.checkout_payment.payment.length; i++) {' . "\n" .
              '      if (document.checkout_payment.payment[i].checked) {' . "\n" .
              '        payment_value = document.checkout_payment.payment[i].value;' . "\n" .
              '      }' . "\n" .
              '    }' . "\n" .
              '  } else if (document.checkout_payment.payment.checked) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  } else if (document.checkout_payment.payment.value) {' . "\n" .
              '    payment_value = document.checkout_payment.payment.value;' . "\n" .
              '  }' . "\n\n";

            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if ($GLOBALS[$class]->enabled) {
                    $js .= $GLOBALS[$class]->javascript_validation();
                }
            }

            $js .= "\n" . '  if (payment_value == null && submitter != 1) {' . "\n" .
               '    error_message = error_message + "' . $aLang['js_error_no_payment_module_selected'] . '";' . "\n" .
               '    error = 1;' . "\n" .
               '  }' . "\n\n" .
               '  if (error == 1 && submitter != 1) {' . "\n" .
               '    alert(error_message);' . "\n" .
               '    return false;' . "\n" .
               '  } else {' . "\n" .
               '    return true;' . "\n" .
               '  }' . "\n" .
               '}' . "\n" .
               '</script>' . "\n";
        }

        return $js;
    }

    public function selection()
    {
        global $aUser, $aLang;

        $selection_array = [];

        if (is_array($this->modules)) {
            foreach ($this->modules as $value) {
                $class = basename((string) $value, '.php');
                if ($GLOBALS[$class]->enabled) {
                    $selection = $GLOBALS[$class]->selection();
                    if (is_array($selection)) {
                        $selection_array[] = $selection;
                    }
                }
            }
        }

        return $selection_array;
    }

    public function pre_confirmation_check()
    {
        global $credit_covers, $payment_modules;

        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                if ($credit_covers) {
                    $GLOBALS[$this->selected_module]->enabled = false;
                    $GLOBALS[$this->selected_module] = null;
                    $payment_modules = '';
                } else {
                    $GLOBALS[$this->selected_module]->pre_confirmation_check();
                }
            }
        }
    }

    public function confirmation()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                return $GLOBALS[$this->selected_module]->confirmation();
            }
        }
    }

    public function process_button()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                return $GLOBALS[$this->selected_module]->process_button();
            }
        }
    }

    public function before_process()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                return $GLOBALS[$this->selected_module]->before_process();
            }
        }
    }

    public function after_process()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                return $GLOBALS[$this->selected_module]->after_process();
            }
        }
    }

    public function get_error()
    {
        if (is_array($this->modules)) {
            if (is_object($GLOBALS[$this->selected_module]) && ($GLOBALS[$this->selected_module]->enabled)) {
                return $GLOBALS[$this->selected_module]->get_error();
            }
        }
    }
}
