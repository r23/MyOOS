<?php
/* ----------------------------------------------------------------------
   $Id: class_order_total.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
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
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  class order_total {
    var $modules;

// class constructor
    function order_total() {
      global $aLang;

      if (defined('MODULE_ORDER_TOTAL_INSTALLED') && oos_is_not_null(MODULE_ORDER_TOTAL_INSTALLED)) {
        $this->modules = explode(';', MODULE_ORDER_TOTAL_INSTALLED);

        $sLanguage = oos_var_prep_for_os($_SESSION['language']);

        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          include_once MYOOS_INCLUDE_PATH . '/includes/languages/' . $sLanguage . '/modules/order_total/' . $value;
          include_once MYOOS_INCLUDE_PATH . '/includes/modules/order_total/' . $value;

          $class = substr($value, 0, strrpos($value, '.'));
          $GLOBALS[$class] = new $class;
        }
      }
    }

    function process() {
      $order_total_array = array();
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->output = array();
            $GLOBALS[$class]->process();

            for ($i=0, $n=sizeof($GLOBALS[$class]->output); $i<$n; $i++) {
              if (oos_is_not_null($GLOBALS[$class]->output[$i]['title']) && oos_is_not_null($GLOBALS[$class]->output[$i]['text'])) {
                $order_total_array[] = array('code' => $GLOBALS[$class]->code,
                                             'title' => $GLOBALS[$class]->output[$i]['title'],
                                             'text' => $GLOBALS[$class]->output[$i]['text'],
                                             'value' => $GLOBALS[$class]->output[$i]['value'],
                                             'sort_order' => $GLOBALS[$class]->sort_order);
              }
            }
          }
        }
      }

      return $order_total_array;
    }

    function output() {
      $output_string = '';
      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            $size = count($GLOBALS[$class]->output);
            for ($i=0; $i<$size; $i++) {
              $output_string .= '              <tr>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['title'] . '</td>' . "\n" .
                                '                <td align="right" class="main">' . $GLOBALS[$class]->output[$i]['text'] . '</td>' . "\n" .
                                '              </tr>';
            }
          }
        }
      }

      return $output_string;
    }

   /**
    * This function is called in checkout payment after display of payment methods. It actually calls
    * two credit class functions.
    *
    * use_credit_amount() is normally a checkbox used to decide whether the credit amount should be applied to reduce
    * the order total. Whether this is a Gift Voucher, or discount coupon or reward points etc.
    *
    * The second function called is credit_selection(). This in the credit classes already made is usually a redeem box.
    * for entering a Gift Voucher number. Note credit classes can decide whether this part is displayed depending on
    * E.g. a setting in the admin section.
    */
    function credit_selection() {
      global $aLang;

      $selection_string = '';
      $close_string = '';
      $credit_class_string = '';
      if ( (MODULE_ORDER_TOTAL_GV_STATUS == 'true') || (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ) { 
        $header_string = '<tr>' . "\n";
        $header_string .= '   <td><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
        $header_string .= '      <tr>' . "\n";
        $header_string .= '        <td class="main"><b>' . decode($aLang['table_heading_credit']) . '</b></td>' . "\n";
        $header_string .= '      </tr>' . "\n";
        $header_string .= '    </table></td>' . "\n";
        $header_string .= '  </tr>' . "\n";
        $header_string .= '<tr>' . "\n";
        $header_string .= '   <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">' . "\n";
        $header_string .= '     <tr class="infoBoxContents"><td><table border="0" width="100%" cellspacing="0" cellpadding="2">' ."\n";
        $header_string .= '       <tr><td width="10"></td>' . "\n";
        $header_string .= '           <td colspan="2"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n";
        $close_string   = '                           </table></td>';
        $close_string  .= '<td width="10"></td>';
        $close_string  .= '</tr></table></td></tr></table></td>';
        $close_string  .= '<tr><td width="100%"></td></tr>';
        reset($this->modules);
        $output_string = '';
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($GLOBALS[$class]->enabled) {
            if ($GLOBALS[$class]->credit_class) {
              if ($selection_string =='') $selection_string = $GLOBALS[$class]->credit_selection();
              $use_credit_string = $GLOBALS[$class]->use_credit_amount();
              $output_string .= '<tr colspan="4"><td colspan="4" width="100%"></td></tr>';
              $output_string .= ' <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" >' . "\n" .
                                '   <td width="10"></td>';
              if ( ($use_credit_string !='' ) && (MODULE_ORDER_TOTAL_GV_STATUS == 'true') ) {
                $output_string .= ' ' . $use_credit_string;
              } elseif ( (MODULE_ORDER_TOTAL_GV_STATUS == 'true') && (MODULE_ORDER_TOTAL_COUPON_STATUS == 'true') ) {
                $output_string .= '     <td class="main"></td>';
              } else {
                $output_string .= '     <td class="main"><b>' . $GLOBALS[$class]->header . '</b></td>';
              }
              $output_string .= '<td width="10"></td>';
              $output_string .= '  </tr>' . "\n"; 
            }
          }
        }
        if ($output_string != '') {
          $output_string = $header_string . $output_string .  $selection_string;
          $output_string .= $close_string;
        }
      }
      return $output_string;
    }



    function sendFax(){

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($class == 'ot_order2fax' and $GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->sendFax();
          }
        }
      }
    }

    function sendSMS(){

      if (is_array($this->modules)) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ($class == 'ot_order2sms' and $GLOBALS[$class]->enabled) {
            $GLOBALS[$class]->sendSMS();
          }
        }
      }
    }


   /**
    * update_credit_account is called in checkout process on a per product basis. It's purpose
    * is to decide whether each product in the cart should add something to a credit account.
    * e.g. for the Gift Voucher it checks whether the product is a Gift voucher and then adds the amount
    * to the Gift Voucher account.
    * Another use would be to check if the product would give reward points and add these to the points/reward account.
    */
    function update_credit_account($i) {
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $GLOBALS[$class]->update_credit_account($i);
          }
        }
      }
    }


   /**
    * This function is called in checkout confirmation.
    * It's main use is for credit classes that use the credit_selection() method. This is usually for
    * entering redeem codes(Gift Vouchers/Discount Coupons). This function is used to validate these codes.
    * If they are valid then the necessary actions are taken, if not valid we are returned to checkout payment
    * with an error
    */
    function collect_posts() {

      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $post_var = 'c' . $GLOBALS[$class]->code;
            if ($_POST[$post_var]) $_SESSION[$post_var] = oos_var_prep_for_os($_POST[$post_var]);
            $GLOBALS[$class]->collect_posts();
          }
        }
      }
    }

   /**
    * pre_confirmation_check is called on checkout confirmation. It's function is to decide whether the
    * credits available are greater than the order total. If they are then a variable (credit_covers) is set to
    * true. This is used to bypass the payment method. In other words if the Gift Voucher is more than the order
    * total, we don't want to go to paypal etc.
    */
    function pre_confirmation_check() {
      global $payment, $oOrder, $credit_covers;

      $credit_covers = false;
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        $total_deductions  = 0;
        reset($this->modules);
        $order_total = $oOrder->info['total'];
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $total_deductions += $GLOBALS[$class]->pre_confirmation_check($order_total);
          }
        }
        if ($oOrder->info['total'] - $total_deductions <= 0 ) {
          $credit_covers = true;
        }
      }
      return $credit_covers;
    }

   /**
    * this function is called in checkout process. it tests whether a decision was made at checkout payment to use
    * the credit amount be applied aginst the order. If so some action is taken. E.g. for a Gift voucher the account
    * is reduced the order total amount.
    */
    function apply_credit() {
      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $GLOBALS[$class]->apply_credit();
          }
        }
      }
    }

   /**
    * Called in checkout process to clear session variables created by each credit class module.
    */
    function clear_posts() {

      if (MODULE_ORDER_TOTAL_INSTALLED) {
        reset($this->modules);
        while (list(, $value) = each($this->modules)) {
          $class = substr($value, 0, strrpos($value, '.'));
          if ( ($GLOBALS[$class]->enabled && $GLOBALS[$class]->credit_class) ) {
            $_SESSION[$post_var] = 'c' . $GLOBALS[$class]->code;
          }
        }
      }
    }


   /**
    * Called at various times. This function calulates the total value of the order that the
    * credit will be appled aginst. This varies depending on whether the credit class applies
    * to shipping & tax
    */
    function get_order_total_main($class, $order_total) {
      global $credit, $oOrder;

      if ($GLOBALS[$class]->include_tax == 'false') $order_total=$order_total-$oOrder->info['tax'];
      if ($GLOBALS[$class]->include_shipping == 'false') $order_total=$order_total-$oOrder->info['shipping_cost'];
      return $order_total;
    }
  }

?>
