<?php
/**
   ----------------------------------------------------------------------
   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: order.php,v 1.29 2003/02/11 21:13:39 dgw_
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

class order
{
    public $info = [];
    public $totals = [];
    public $products = [];
    public $customer = [];
    public $delivery = [];
    public $billing;
    public $content_type;

    public function __construct($order_id = '')
    {
        if (oos_is_not_null($order_id)) {
            $this->query($order_id);
        } else {
            $this->cart();
        }
    }

    public function query($order_id)
    {
        $order_id = intval($order_id);
        $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $orderstable = $oostable['orders'];
        $sql = "SELECT customers_id, customers_name, customers_company, customers_street_address,
                     customers_city, customers_postcode, customers_state,
                     customers_country, customers_telephone, customers_email_address,
                     customers_address_format_id, delivery_name, delivery_company,
                     delivery_street_address, delivery_city, delivery_postcode,
                     delivery_state, delivery_country, delivery_address_format_id, billing_name,
                     billing_company, billing_street_address, billing_city,
                     billing_postcode, billing_state, billing_country, billing_address_format_id,
                     payment_method, currency, currency_value,
                     date_purchased, orders_status, last_modified
              FROM $orderstable
              WHERE orders_id = '" . intval($order_id) . "'";
        $order = $dbconn->GetRow($sql);

        $orders_totaltable = $oostable['orders_total'];
        $sql = "SELECT title, text
              FROM $orders_totaltable
              WHERE orders_id = '" . intval($order_id) . "'
              ORDER BY sort_order";
        $this->totals = $dbconn->GetAll($sql);

        $orders_totaltable = $oostable['orders_total'];
        $sql = "SELECT text
              FROM $orders_totaltable
              WHERE orders_id = '" . intval($order_id) . "'
                AND class = 'ot_total'";
        $order_total_text = $dbconn->GetOne($sql);

        $orders_totaltable = $oostable['orders_total'];
        $sql = "SELECT title
              FROM $orders_totaltable
              WHERE orders_id = '" . intval($order_id) . "'
                AND class = 'ot_shipping'";
        $shipping_method_title = $dbconn->GetOne($sql);

        $orders_statustable = $oostable['orders_status'];
        $sql = "SELECT orders_status_name
              FROM $orders_statustable
              WHERE orders_status_id = '" . $order['orders_status'] . "'
                AND orders_languages_id = '" .  intval($nLanguageID) . "'";
        $orders_status_name = $dbconn->GetOne($sql);

        $this->info = ['currency' => $order['currency'], 'currency_value' => $order['currency_value'], 'payment_method' => $order['payment_method'], 'date_purchased' => $order['date_purchased'], 'orders_status' => $orders_status_name, 'last_modified' => $order['last_modified'], 'total' => strip_tags((string) $order_total_text), 'shipping_method' => ((str_ends_with((string) $shipping_method_title, ':')) ? substr(strip_tags((string) $shipping_method_title), 0, -1) : strip_tags((string) $shipping_method_title))];

        $this->customer = ['id' => $order['customers_id'], 'name' => $order['customers_name'], 'company' => $order['customers_company'], 'street_address' => $order['customers_street_address'], 'city' => $order['customers_city'], 'postcode' => $order['customers_postcode'], 'state' => $order['customers_state'], 'country' => $order['customers_country'], 'format_id' => $order['customers_address_format_id'], 'telephone' => $order['customers_telephone'], 'email_address' => $order['customers_email_address']];

        $this->delivery = ['name' => $order['delivery_name'], 'company' => $order['delivery_company'], 'street_address' => $order['delivery_street_address'], 'city' => $order['delivery_city'], 'postcode' => $order['delivery_postcode'], 'state' => $order['delivery_state'], 'country' => $order['delivery_country'], 'format_id' => $order['delivery_address_format_id']];

        if (empty($this->delivery['name']) && empty($this->delivery['street_address'])) {
            $this->delivery = false;
        }

        $this->billing = ['name' => $order['billing_name'], 'company' => $order['billing_company'], 'street_address' => $order['billing_street_address'], 'city' => $order['billing_city'], 'postcode' => $order['billing_postcode'], 'state' => $order['billing_state'], 'country' => $order['billing_country'], 'format_id' => $order['billing_address_format_id']];

        $index = 0;

        $orders_productstable = $oostable['orders_products'];
        $sql = "SELECT orders_products_id, products_id, products_name, products_model, products_image,
                      products_ean, products_serial_number, products_old_electrical_equipment, products_free_redemption, products_price, products_tax,
                     products_quantity, final_price
              FROM $orders_productstable
              WHERE orders_id = '" . intval($order_id) . "'";
        $orders_products_result = $dbconn->Execute($sql);
        while ($orders_products = $orders_products_result->fields) {
            $products_setting = $this->get_products_setting($orders_products['products_id']);
            $this->products[$index] = ['qty' => $orders_products['products_quantity'], 'id' => $orders_products['products_id'], 'orders_id' => intval($order_id), 'status' => $products_setting, 'name' => $orders_products['products_name'], 'image' => $orders_products['products_image'], 'model' => $orders_products['products_model'], 'ean' => $orders_products['products_ean'], 'serial_number' => $orders_products['products_serial_number'], 'old_electrical_equipment' => $orders_products['products_old_electrical_equipment'], 'return_free_of_charge' => $orders_products['products_free_redemption'], 'tax' => $orders_products['products_tax'], 'price' => $orders_products['products_price'], 'final_price' => $orders_products['final_price']];

            $subindex = 0;
            $orders_products_attributestable = $oostable['orders_products_attributes'];
            $sql = "SELECT products_options, products_options_values, options_values_price, price_prefix
                FROM $orders_products_attributestable
                WHERE orders_id = '" . intval($order_id) . "'
                  AND orders_products_id = '" . $orders_products['orders_products_id'] . "'";
            $attributes_result = $dbconn->Execute($sql);
            if ($attributes_result->RecordCount()) {
                while ($attributes = $attributes_result->fields) {
                    $this->products[$index]['attributes'][$subindex] = ['option' => $attributes['products_options'], 'value' => $attributes['products_options_values'], 'prefix' => $attributes['price_prefix'], 'price' => $attributes['options_values_price']];

                    $subindex++;

                    // Move that ADOdb pointer!
                    $attributes_result->MoveNext();
                }
            }

            $this->info['tax_groups']["{$this->products[$index]['tax']}"] = '1';
            $this->info['net_total']["{$this->products[$index]['tax']}"] = '1';

            $index++;

            // Move that ADOdb pointer!
            $orders_products_result->MoveNext();
        }
    }

    public function cart()
    {
        global $oCurrencies, $aUser;

        $this->content_type = $_SESSION['cart']->get_content_type();
        $nLanguageID = isset($_SESSION['language_id']) ? intval($_SESSION['language_id']) : DEFAULT_LANGUAGE_ID;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $customerstable = $oostable['customers'];
        $address_booktable = $oostable['address_book'];
        $zonestable = $oostable['zones'];
        $countriestable = $oostable['countries'];
        $sql = "SELECT c.customers_firstname, c.customers_lastname, c.customers_telephone, c.customers_email_address,
                     ab.entry_company, ab.entry_street_address, ab.entry_postcode, ab.entry_city,
                     ab.entry_zone_id, z.zone_name, co.countries_id, co.countries_name, co.countries_iso_code_2,
                     co.countries_iso_code_3, co.address_format_id, ab.entry_state
              FROM $customerstable c,
                   $address_booktable ab LEFT JOIN
                   $zonestable z
               ON  (ab.entry_zone_id = z.zone_id) LEFT JOIN
                   $countriestable co
               ON (ab.entry_country_id = co.countries_id)
              WHERE c.customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                    ab.customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                    c.customers_default_address_id = ab.address_book_id";
        $customer_address = $dbconn->GetRow($sql);

        $address_booktable = $oostable['address_book'];
        $zonestable = $oostable['zones'];
        $countriestable = $oostable['countries'];
        $sql = "SELECT ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address,
                     ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name,
                     ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2,
                     c.countries_iso_code_3, c.address_format_id, ab.entry_state
				FROM $address_booktable ab LEFT JOIN
					$zonestable z
					ON (ab.entry_zone_id = z.zone_id) LEFT JOIN
                   $countriestable c ON
                   (ab.entry_country_id = c.countries_id)
				WHERE ab.customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                    ab.address_book_id = '" . intval($_SESSION['sendto']) . "'";
        $shipping_address = $dbconn->GetRow($sql);

        $address_booktable = $oostable['address_book'];
        $zonestable = $oostable['zones'];
        $countriestable = $oostable['countries'];
        $sql = "SELECT ab.entry_firstname, ab.entry_lastname, ab.entry_company, ab.entry_street_address,
                     ab.entry_postcode, ab.entry_city, ab.entry_zone_id, z.zone_name,
                     ab.entry_country_id, c.countries_id, c.countries_name, c.countries_iso_code_2,
                     c.countries_iso_code_3, c.address_format_id, ab.entry_state
              FROM $address_booktable ab LEFT JOIN
                   $zonestable z
                ON (ab.entry_zone_id = z.zone_id) LEFT JOIN
                   $countriestable c ON
                   (ab.entry_country_id = c.countries_id)
              WHERE ab.customers_id = '" . intval($_SESSION['customer_id']) . "' AND
                    ab.address_book_id = '" . intval($_SESSION['billto']) . "'";
        $billing_address = $dbconn->GetRow($sql);

        $class =& $_SESSION['payment'];

        if ($this->content_type == 'virtual') {
            $_SESSION['customer_country_id'] = $billing_address['entry_country_id'];
            $_SESSION['customer_zone_id'] = $billing_address['entry_zone_id'];                
            $tax_address = ['entry_country_id' => $billing_address['entry_country_id'], 'entry_zone_id' => $billing_address['entry_zone_id']];
        } else {
            // $_SESSION['customer_country_id'] = $shipping_address['entry_country_id'];
            // $_SESSION['customer_zone_id'] = $shipping_address['entry_zone_id'];        
            $tax_address = ['entry_country_id' => $shipping_address['entry_country_id'], 'entry_zone_id' => $shipping_address['entry_zone_id']];
        }

        $this->info = ['order_status' => DEFAULT_ORDERS_STATUS_ID, 'currency' => $_SESSION['currency'], 'currency_value' => $oCurrencies->currencies[$_SESSION['currency']]['value'], 'payment_method' => $GLOBALS[$class]->title, 'shipping_method' => $_SESSION['shipping']['title'], 'shipping_cost' => $_SESSION['shipping']['cost'], 'comments' => ($_SESSION['comments'] ?? ''), 'shipping_class' =>  ((strpos((string) $_SESSION['shipping']['id'], '_') > 0) ? substr(strrev(strchr(strrev((string) $_SESSION['shipping']['id']), '_')), 0, -1) : $_SESSION['shipping']['id']), 'payment_class' => $_SESSION['payment']];

        if (isset($GLOBALS['payment']) && is_object($GLOBALS['payment'])) {
            $this->info['payment_method'] = $GLOBALS['payment']->title;

            if (isset($GLOBALS['payment']->order_status) && is_numeric($GLOBALS['payment']->order_status) && ($GLOBALS['payment']->order_status > 0)) {
                $this->info['order_status'] = $GLOBALS['payment']->order_status;
            }
        }

        if (isset($_SESSION['guest_account']) && ($_SESSION['guest_account'] == '1')) {
            $email_address = oos_db_prepare_input($_SESSION['customers_email_address']);
        } else {
            $email_address = $customer_address['customers_email_address'];
        }

        $this->customer = ['firstname' => $customer_address['customers_firstname'], 'lastname' => $customer_address['customers_lastname'], 'company' => $customer_address['entry_company'], 'street_address' => $customer_address['entry_street_address'], 'city' => $customer_address['entry_city'], 'postcode' => $customer_address['entry_postcode'], 'state' => ((oos_is_not_null($customer_address['entry_state'])) ? $customer_address['entry_state'] : $customer_address['zone_name']), 'zone_id' => $customer_address['entry_zone_id'], 'country' => ['id' => $customer_address['countries_id'], 'title' => $customer_address['countries_name'], 'iso_code_2' => $customer_address['countries_iso_code_2'], 'iso_code_3' => $customer_address['countries_iso_code_3']], 'format_id' => $customer_address['address_format_id'], 'telephone' => $customer_address['customers_telephone'], 'email_address' => $email_address];

        $_SESSION['delivery_country_id'] = $shipping_address['entry_country_id'];

        $this->delivery = ['firstname' => $shipping_address['entry_firstname'], 'lastname' => $shipping_address['entry_lastname'], 'company' => $shipping_address['entry_company'], 'street_address' => $shipping_address['entry_street_address'], 'city' => $shipping_address['entry_city'], 'postcode' => $shipping_address['entry_postcode'], 'state' => ((oos_is_not_null($shipping_address['entry_state'])) ? $shipping_address['entry_state'] : $shipping_address['zone_name']), 'zone_id' => $shipping_address['entry_zone_id'], 'country' => ['id' => $shipping_address['countries_id'], 'title' => $shipping_address['countries_name'], 'iso_code_2' => $shipping_address['countries_iso_code_2'], 'iso_code_3' => $shipping_address['countries_iso_code_3']], 'country_id' => $shipping_address['entry_country_id'], 'format_id' => $shipping_address['address_format_id']];


        $this->billing = ['firstname' => $billing_address['entry_firstname'], 'lastname' => $billing_address['entry_lastname'], 'company' => $billing_address['entry_company'], 'street_address' => $billing_address['entry_street_address'], 'city' => $billing_address['entry_city'], 'postcode' => $billing_address['entry_postcode'], 'state' => ((oos_is_not_null($billing_address['entry_state'])) ? $billing_address['entry_state'] : $billing_address['zone_name']), 'country' => ['id' => $billing_address['countries_id'], 'title' => $billing_address['countries_name'], 'iso_code_2' => $billing_address['countries_iso_code_2'], 'iso_code_3' => $billing_address['countries_iso_code_3']], 'country_id' => $billing_address['entry_country_id'], 'format_id' => $billing_address['address_format_id']];
        $index = 0;
        $products = $_SESSION['cart']->get_products();

        $n = is_countable($products) ? count($products) : 0;
        for ($i=0, $n; $i<$n; $i++) {
            $this->products[$index] = ['qty' => $products[$i]['quantity'], 'name' => $products[$i]['name'], 'essential_characteristics' => $products[$i]['essential_characteristics'], 'image' => $products[$i]['image'], 'model' => $products[$i]['model'], 'ean' => $products[$i]['ean'], 'tax' => oos_get_tax_rate($products[$i]['tax_class_id'], $tax_address['entry_country_id'], $tax_address['entry_zone_id']), 'price' => $products[$i]['price'], 'final_price' => $products[$i]['price'] + $_SESSION['cart']->attributes_price($products[$i]['id']), 'weight' => $products[$i]['weight'], 'towlid' => $products[$i]['towlid'], 'products_base_price' => $products[$i]['products_base_price'], 'base_product_price' => $products[$i]['base_product_price'], 'products_units_id' => $products[$i]['products_units_id'], 'products_product_quantity' => $products[$i]['products_product_quantity'], 'old_electrical_equipment' => $products[$i]['old_electrical_equipment'], 'return_free_of_charge' => $products[$i]['return_free_of_charge'], 'id' => $products[$i]['id']];

            if ($products[$i]['attributes']) {
                $subindex = 0;
                reset($products[$i]['attributes']);
                foreach ($products[$i]['attributes'] as $option => $value) {
                    $products_optionstable = $oostable['products_options'];
                    $products_options_valuestable = $oostable['products_options_values'];
                    $products_attributestable = $oostable['products_attributes'];

                    if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
                        $sql = "SELECT popt.products_options_name, poval.products_options_values_name,
								pa.options_values_price, pa.price_prefix
							FROM $products_optionstable popt,
								$products_options_valuestable poval,
								$products_attributestable pa
							WHERE
								pa.products_id = '" . oos_db_input($products[$i]['id']) . "' AND
								pa.options_id = '" . oos_db_input($option) . "' AND
								pa.options_id = popt.products_options_id AND
								popt.products_options_languages_id = '" .  intval($nLanguageID) . "'";
                    } else {
                        $sql = "SELECT popt.products_options_name, poval.products_options_values_name,
									pa.options_values_price, pa.price_prefix
								FROM $products_optionstable popt,
									$products_options_valuestable poval,
									$products_attributestable pa
								WHERE pa.products_id = '" . oos_db_input($products[$i]['id']) . "' AND
									pa.options_id = '" . oos_db_input($option) . "' AND
									pa.options_id = popt.products_options_id AND
									pa.options_values_id = '" . oos_db_input($value) . "' AND
									pa.options_values_id = poval.products_options_values_id AND
									popt.products_options_languages_id = '" .  intval($nLanguageID) . "' AND
									poval.products_options_values_languages_id = '" .  intval($nLanguageID) . "'";
                    }
                    $attributes = $dbconn->GetRow($sql);

                    if ($value == PRODUCTS_OPTIONS_VALUE_TEXT_ID) {
                        $attr_value = $products[$i]['attributes_values'][$option];
                    } else {
                        $attr_value = $attributes['products_options_values_name'];
                    }
                    $this->products[$index]['attributes'][$subindex] = ['option' => $attributes['products_options_name'], 'value' => $attr_value, 'option_id' => $option, 'value_id' => $value, 'prefix' => $attributes['price_prefix'], 'price' => $attributes['options_values_price']];
                    $subindex++;
                }
            }

            $nPrice = $oCurrencies->calculate_price($this->products[$index]['final_price'], $this->products[$index]['tax'], $this->products[$index]['qty']);
            $this->info['subtotal'] += $nPrice;

            $this->info['total'] +=  $nPrice;

            $currency_type = ($_SESSION['currency'] ?? DEFAULT_CURRENCY);
            $decimal_places = $oCurrencies->get_decimal_places($currency_type);

            $products_tax = $this->products[$index]['tax'];
            if ($aUser['price_with_tax'] == 1) {
                $this->info['tax'] += $nPrice - ($nPrice / (($products_tax < 10) ? "1.0" . str_replace('.', '', (string) $products_tax) : "1." . str_replace('.', '', (string) $products_tax)));
                $nPriceNet = oos_round(($nPrice / (($products_tax < 10) ? "1.0" . str_replace('.', '', (string) $products_tax) : "1." . str_replace('.', '', (string) $products_tax))), $decimal_places);
                if (isset($this->info['tax_groups']["$products_tax"])) {
                    $this->info['tax_groups']["$products_tax"] += $nPrice - $nPriceNet;
                    $this->info['net_total']["$products_tax"] += $nPriceNet;
                } else {
                    $this->info['tax_groups']["$products_tax"] = $nPrice - $nPriceNet;
                    $this->info['net_total']["$products_tax"] = $nPriceNet;
                }
            } else {
                $this->info['tax'] += ($products_tax / 100) * $nPrice;
                if (isset($this->info['tax_groups']["$products_tax"])) {
                    $this->info['tax_groups']["$products_tax"] += oos_round(($products_tax / 100) * $nPrice, $decimal_places);
                    $this->info['net_total']["$products_tax"] += $nPrice;
                } else {
                    $this->info['tax_groups']["$products_tax"] = oos_round(($products_tax / 100) * $nPrice, $decimal_places);
                    $this->info['net_total']["$products_tax"] = $nPrice;
                }
            }

            $index++;
        }
    }


    /**
     * Return Product's StatusName
     *
     * @param  $nProductID
     * @return string
     */
    public function get_products_setting($nProductID)
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $settingtable = $oostable['setting'];
        $query = "SELECT products_setting
				FROM $settingtable 
				WHERE products_id = '" . intval($nProductID) . "'";
        $products_setting = $dbconn->GetOne($query);

        return $products_setting;
    }
}
