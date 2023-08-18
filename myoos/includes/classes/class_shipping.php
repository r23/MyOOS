<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: shipping.php,v 1.21 2003/02/11 00:04:53 hpdl
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

class shipping
{
    public $modules;
    public $selected_module;

    // class constructor
    public function __construct($module = '')
    {
        global $aLang;

        if (defined('MODULE_SHIPPING_INSTALLED') && oos_is_not_null(MODULE_SHIPPING_INSTALLED)) {
            $this->modules = explode(';', (string) MODULE_SHIPPING_INSTALLED);

            $include_modules = [];

            if ((oos_is_not_null($module))) {
                $this->selected_module = $module;
                if (isset($module['id'])) {
                    $class = substr((string) $module['id'], 0, strpos((string) $module['id'], '_'));
                } else {
                    $class = $module;
                }
                $include_modules[] = ['class' => $class, 'file' => $class . '.php'];
            } else {
                foreach ($this->modules as $value) {
                    $class = basename($value, '.php');
                    $include_modules[] = ['class' => $class, 'file' => $value];
                }
            }

            $sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;

            for ($i=0, $n=count($include_modules); $i<$n; $i++) {
                include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/shipping/' . $include_modules[$i]['file'];
                include_once MYOOS_INCLUDE_PATH . '/includes/modules/shipping/' . $include_modules[$i]['file'];


                if (class_exists($include_modules[$i]['class'])) {
                    $GLOBALS[$include_modules[$i]['class']] = new $include_modules[$i]['class']();
                }
            }
        }
    }

    public function quote($method = '', $module = '')
    {
        global $total_weight, $shipping_weight, $shipping_quoted, $shipping_num_boxes;

        $quotes_array = [];

        if (is_array($this->modules)) {
            $shipping_quoted = '';
            $shipping_num_boxes = 1;
            $shipping_weight = $total_weight;

            if ($total_weight > SHIPPING_MAX_WEIGHT) { // Split into many boxes
                $shipping_num_boxes = ceil($total_weight/SHIPPING_MAX_WEIGHT);
                $shipping_weight = $total_weight/$shipping_num_boxes;
            }

            if (SHIPPING_BOX_WEIGHT >= $shipping_weight*SHIPPING_BOX_PADDING/100) {
                $shipping_weight = $shipping_weight+SHIPPING_BOX_WEIGHT;
            } else {
                $shipping_weight = $shipping_weight + ($shipping_weight*SHIPPING_BOX_PADDING/100);
            }

            $include_quotes = [];

            foreach ($this->modules as $value) {
                $class = basename((string) $value, '.php');
                if (oos_is_not_null($module)) {
                    if (($module == $class) && ($GLOBALS[$class]->enabled)) {
                        $include_quotes[] = $class;
                    }
                } elseif ($GLOBALS[$class]->enabled) {
                    $include_quotes[] = $class;
                }
            }

            $size = count($include_quotes);
            for ($i=0; $i<$size; $i++) {
                $quotes = $GLOBALS[$include_quotes[$i]]->quote($method);
                if (is_array($quotes)) {
                    $quotes_array[] = $quotes;
                }
            }
        }

        return $quotes_array;
    }

    public function cheapest()
    {
        if (is_array($this->modules)) {
            $rates = [];

            foreach ($this->modules as $value) {
                $class = basename((string) $value, '.php');
                if ($GLOBALS[$class]->enabled) {
                    $quotes = $GLOBALS[$class]->quotes;
                    $size = is_countable($quotes['methods']) ? count($quotes['methods']) : 0;
                    for ($i=0; $i<$size; $i++) {
                        if ($quotes['methods'][$i]['cost']) {
                            $rates[] = ['id' => $quotes['id'] . '_' . $quotes['methods'][$i]['id'],
                                            'title' => $quotes['module'] . ' (' . $quotes['methods'][$i]['title'] . ')',
                                            'cost' => $quotes['methods'][$i]['cost']];
                        }
                    }
                }
            }

            $cheapest = false;
            $size = count($rates);
            for ($i=0; $i<$size; $i++) {
                if (is_array($cheapest)) {
                    if ($rates[$i]['cost'] < $cheapest['cost']) {
                        $cheapest = $rates[$i];
                    }
                } else {
                    $cheapest = $rates[$i];
                }
            }

            return $cheapest;
        }
    }
}
