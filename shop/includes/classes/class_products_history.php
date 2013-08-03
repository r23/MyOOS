<?php
/* ----------------------------------------------------------------------
   $Id: class_products_history.php 296 2013-04-13 14:48:55Z r23 $

   MyOOS [Shopsystem]
   http://www.oos-shop.de/

   Copyright (c) 2003 - 2013 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------- */

  /** ensure this file is being included by a parent file */
  defined( 'OOS_VALID_MOD' ) OR die( 'Direct Access to this location is not allowed.' );

  /**
   * Class Products History
   *
   *
   * @link      http://www.oos-shop.de/ Latest release of this class
   * @package   Products History
   * @copyright Copyright (c) 2003 - 2004 r23.de. All rights reserved.
   * @author    r23 <info@r23.de>
   * @version   $Revision: 1.1 $ - changed by $Author: r23 $ on $Date: 2007/06/07 16:06:31 $
   * @access    public
   */
   class oosProductsHistory {

     /**
     * @access private
     * @var    int
     */
      var $products_history;


     /**
     * Constructor of our Class
     *
     * @access    public
     * @author    r23 <info@r23.de>
     */
      function oosProductsHistory() {
        $this->reset();
      }


     /**
     * @param $products_id
     */
      function add_current_products($products_id) {
        if (!$this->in_history($products_id)) {
          if ($this->count_history() >= MAX_DISPLAY_PRODUCTS_IN_PRODUCTS_HISTORY_BOX) {
            $temp = array_shift($this->products_history); 
          }
          array_push($this->products_history, $products_id);
        }
      }


     /**
     * @param $products_id
     * @return boolean
     */
      function in_history($products_id) {
        if (in_array ($products_id, $this->products_history)) {
          return true;
        } else {
          return false;
        }
      }


     /**
     * get total number of products
     */
      function count_history() {
        return count($this->products_history);
      }


     /**
     * get Product's id
     */
      function get_product_id_list() {
        $product_id_list = '';
        if (is_array($this->products_history)) {
          reset($this->products_history);
          while (list($key, $products_id ) = each($this->products_history)) {
            $product_id_list .= ', ' . $products_id;
          }
        }

        return substr($product_id_list, 2);
      }


     /**
     *
     */
      function reset() {
        $this->products_history = array();
      }
    }
?>
