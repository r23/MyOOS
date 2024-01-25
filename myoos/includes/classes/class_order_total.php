<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2024  by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order_total.php,v 1.3.2.7 2003/05/14 22:52:58 wilt
   orig: order_total.php,v 1.4 2003/02/11 00:04:53 hpdl
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

class order_total
{
    public $modules;

    // class constructor
    public function __construct()
    {
        global $aLang;

        if (defined('MODULE_ORDER_TOTAL_INSTALLED') && oos_is_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
            $this->modules = explode(';', (string) MODULE_ORDER_TOTAL_INSTALLED);

            $sLanguage = isset($_SESSION['language']) ? oos_var_prep_for_os($_SESSION['language']) : DEFAULT_LANGUAGE;

            reset($this->modules);
            foreach ($this->modules as $value) {
                include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/' . $value;
                include_once MYOOS_INCLUDE_PATH . '/includes/modules/order_total/' . $value;

                $class = substr($value, 0, strrpos($value, '.'));
                $GLOBALS[$class] = new $class();
            }
        }
    }

    public function process()
    {
        $order_total_array = [];
        if (is_array($this->modules)) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if ($GLOBALS[$class]->enabled) {
                    $GLOBALS[$class]->output = [];
                    $GLOBALS[$class]->process();

                    $n = is_countable($GLOBALS[$class]->output) ? count($GLOBALS[$class]->output) : 0;
                    for ($i = 0, $n; $i < $n; $i++) {
                        if (oos_is_not_null($GLOBALS[$class]->output[$i]['title']) && oos_is_not_null($GLOBALS[$class]->output[$i]['text'])) {
                            $order_total_array[] = ['code' => $GLOBALS[$class]->code,
                                                        'title' => $GLOBALS[$class]->output[$i]['title'],
                                                        'text' => $GLOBALS[$class]->output[$i]['text'],
                                                        'value' => $GLOBALS[$class]->output[$i]['value'],
                                                        'sort_order' => $GLOBALS[$class]->sort_order];
                        }
                    }
                }
            }
        }


        return $order_total_array;
    }

    public function shopping_cart_process()
    {
        $order_total_array = [];
        if (is_array($this->modules)) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if ($GLOBALS[$class]->enabled) {
                    $GLOBALS[$class]->output = [];
                    $GLOBALS[$class]->shopping_cart_process();

                    $n = is_countable($GLOBALS[$class]->output) ? count($GLOBALS[$class]->output) : 0;
                    for ($i = 0, $n; $i < $n; $i++) {
                        if (oos_is_not_null($GLOBALS[$class]->output[$i]['title']) && oos_is_not_null($GLOBALS[$class]->output[$i]['text'])) {
                            $order_total_array[] = ['code' => $GLOBALS[$class]->code,
                                                        'title' => $GLOBALS[$class]->output[$i]['title'],
                                                        'text' => $GLOBALS[$class]->output[$i]['text'],
                                                        'info' => $GLOBALS[$class]->output[$i]['info'],
                                                        'value' => $GLOBALS[$class]->output[$i]['value'],
                                                        'sort_order' => $GLOBALS[$class]->sort_order];
                        }
                    }
                }
            }
        }

        return $order_total_array;
    }




    public function output()
    {
        $output_string = null;
        if (is_array($this->modules)) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if ($GLOBALS[$class]->enabled) {
                    $size = is_countable($GLOBALS[$class]->output) ? count($GLOBALS[$class]->output) : 0;
                    for ($i = 0; $i < $size; $i++) {
                        if ($GLOBALS[$class]->output[$i]['text'] != '') {
                            $output_string .= '              <tr>' . "\n" .
                                    '                <td align="left">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                    '                <td align="right"><nobr>' . $GLOBALS[$class]->output[$i]['text'] . '</nobr></td>' . "\n" .
                                    '              </tr>' . "\n";
                        }

                        if ($GLOBALS[$class]->output[$i]['info'] != '') {
                            $output_string .= '              <tr>' . "\n" .
                                    '         <td colspan="2">' . $GLOBALS[$class]->output[$i]['info'] . '</td>' . "\n" .
                                    '              </tr>' . "\n";
                        }
                    }
                }
            }
        }
        return $output_string;
    }

    /**
     * This public function is called in checkout payment after display of payment methods. It actually calls
     * two credit class public functions.
     *
     * use_credit_amount() is normally a checkbox used to decide whether the credit amount should be applied to reduce
     * the order total. Whether this is a Gift Voucher, or discount coupon or reward points etc.
     *
     * The second public function called is credit_selection(). This in the credit classes already made is usually a redeem box.
     * for entering a Gift Voucher number. Note credit classes can decide whether this part is displayed depending on
     * E.g. a setting in the admin section.
     */
    public function credit_selection()
    {
        global $aLang;

        $selection_string = '';
        $close_string = '';
        $credit_class_string = '';

        if ((defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true')) || (defined('MODULE_ORDER_TOTAL_COUPON_STATUS') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'))) {
            $header_string = '<hr />' . "\n";
            $header_string .= '<div class="page-header">' . "\n";
            $header_string .= '	<h4>' . $aLang['table_heading_credit'] . '</h4>' . "\n";
            $header_string .= '</div>' . "\n";

            $output_string = '';
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if ($GLOBALS[$class]->enabled) {
                    if ($GLOBALS[$class]->credit_class) {
                        if ($selection_string == '') {
                            $selection_string = $GLOBALS[$class]->credit_selection();
                        }
                        $use_credit_string = $GLOBALS[$class]->use_credit_amount();
                        if (($use_credit_string != '') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true')) {
                            $output_string .= ' ' . $use_credit_string;
                        } elseif ((defined('MODULE_ORDER_TOTAL_GV_STATUS') && (MODULE_ORDER_TOTAL_GV_STATUS == 'true')) || (defined('MODULE_ORDER_TOTAL_COUPON_STATUS') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true'))) {
                            $output_string .= ' ';
                        } else {
                            $output_string .= '    <strong>' . $GLOBALS[$class]->header . '</strong>';
                        }
                    }
                }
            }
            if ($output_string != '') {
                $output_string = $header_string . $output_string .  $selection_string;
            }
        }
        return $output_string;
    }



    /**
     * update_credit_account is called in checkout process on a per product basis. It's purpose
     * is to decide whether each product in the cart should add something to a credit account.
     * e.g. for the Gift Voucher it checks whether the product is a Gift voucher and then adds the amount
     * to the Gift Voucher account.
     * Another use would be to check if the product would give reward points and add these to the points/reward account.
     */
    public function update_credit_account($i)
    {
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $GLOBALS[$class]->update_credit_account($i);
                }
            }
        }
    }


    /**
     * This public function is called in checkout confirmation.
     * It's main use is for credit classes that use the credit_selection() method. This is usually for
     * entering redeem codes(Gift Vouchers/Discount Coupons). This public function is used to validate these codes.
     * If they are valid then the necessary actions are taken, if not valid we are returned to checkout payment
     * with an error
     */
    public function collect_posts()
    {
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $post_var = 'c' . $GLOBALS[$class]->code;
                    $post_var = filter_string_polyfill($post_var);
                    if ($_POST[$post_var]) {
                        $_SESSION[$post_var] = filter_string_polyfill(filter_input(INPUT_POST, $post_var));
                    }
                    $GLOBALS[$class]->collect_posts();
                }
            }
        }
    }


    /**
     * This public function is called in checkout confirmation.
     * It's main use is for credit classes that use the credit_selection() method. This is usually for
     * entering redeem codes(Gift Vouchers/Discount Coupons). This public function is used to validate these codes.
     * If they are valid then the necessary actions are taken, if not valid we are returned to checkout payment
     * with an error
     */
    public function shopping_cart_collect_posts()
    {
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $post_var = 'c' . $GLOBALS[$class]->code;
                    $post_var = filter_var($post_var, FILTER_SANITIZE_STRING);
                    if ($_POST[$post_var]) {
                        $_SESSION[$post_var] = filter_string_polyfill(filter_input(INPUT_POST, $post_var));
                    }
                    $GLOBALS[$class]->shopping_cart_collect_posts();
                }
            }
        }
    }


    /**
     * pre_confirmation_check is called on checkout confirmation. It's public function is to decide whether the
     * credits available are greater than the order total. If they are then a variable (credit_covers) is set to
     * true. This is used to bypass the payment method. In other words if the Gift Voucher is more than the order
     * total, we don't want to go to paypal etc.
     */
    public function pre_confirmation_check()
    {
        global $payment, $oOrder, $credit_covers;

        $credit_covers = false;
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            $total_deductions  = 0;
            reset($this->modules);
            $order_total = $oOrder->info['total'];
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $total_deductions += $GLOBALS[$class]->pre_confirmation_check($order_total);
                }
            }
            if ($oOrder->info['total'] - $total_deductions <= 0) {
                $credit_covers = true;
            }
        }
        return $credit_covers;
    }

    /**
     * this public function is called in checkout process. it tests whether a decision was made at checkout payment to use
     * the credit amount be applied aginst the order. If so some action is taken. E.g. for a Gift voucher the account
     * is reduced the order total amount.
     */
    public function apply_credit()
    {
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $GLOBALS[$class]->apply_credit();
                }
            }
        }
    }

    /**
     * Called in checkout process to clear session variables created by each credit class module.
     */
    public function clear_posts()
    {
        if (MODULE_ORDER_TOTAL_INSTALLED) {
            reset($this->modules);
            foreach ($this->modules as $value) {
                $class = substr((string) $value, 0, strrpos((string) $value, '.'));
                if (($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class)) {
                    $post_var = 'c' . $GLOBALS[$class]->code;
                    $_SESSION[$post_var] = 'c' . $GLOBALS[$class]->code;
                }
            }
        }
    }
}
