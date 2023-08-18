<?php
/**
   ----------------------------------------------------------------------
   $Id: zones.php,v 1.2 2008/08/25 14:28:07 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: zones.php,v 1.19 2003/02/05 22:41:53 hpdl
   ----------------------------------------------------------------------
   osCommerce, Open Source E-Commerce Solutions
   http://www.oscommerce.com

   Copyright (c) 2003 osCommerce
   ----------------------------------------------------------------------
   Released under the GNU General Public License
   ----------------------------------------------------------------------
   USAGE
   By default, the module comes with support for 1 zone.  This can be
   easily changed by editing the line below in the zones constructor
   that defines $this->num_zones.

   Next, you will want to activate the module by going to the Admin screen,
   clicking on Modules, then clicking on Shipping.  A list of all shipping
   modules should appear.  Click on the green dot next to the one labeled
   zones.php.  A list of settings will appear to the right.  Click on the
   Edit button.

   PLEASE NOTE THAT YOU WILL LOSE YOUR CURRENT SHIPPING RATES AND OTHER
   SETTINGS IF YOU TURN OFF THIS SHIPPING METHOD.  Make sure you keep a
   backup of your shipping settings somewhere at all times.

   If you want an additional handling charge applied to orders that use this
   method, set the Handling Fee field.

   Next, you will need to define which countries are in each zone.  Determining
   this might take some time and effort.  You should group a set of countries
   that has similar shipping charges for the same weight.  For instance, when
   shipping from the US, the countries of Japan, Australia, New Zealand, and
   Singapore have similar shipping rates.  As an example, one of my customers
   is using this set of zones:
     1: USA
     2: Canada
     3: Austria, Belgium, Great Britain, France, Germany, Greenland, Iceland,
        Ireland, Italy, Norway, Holland/Netherlands, Denmark, Poland, Spain,
        Sweden, Switzerland, Finland, Portugal, Israel, Greece
     4: Japan, Australia, New Zealand, Singapore
     5: Taiwan, China, Hong Kong

   When you enter these country lists, enter them into the Zone X Countries
   fields, where "X" is the number of the zone.  They should be entered as
   two character ISO country codes in all capital letters.  They should be
   separated by commas with no spaces or other punctuation. For example:
     1: US
     2: CA
     3: AT,BE,GB,FR,DE,GL,IS,IE,IT,NO,NL,DK,PL,ES,SE,CH,FI,PT,IL,GR
     4: JP,AU,NZ,SG
     5: TW,CN,HK

   Now you need to set up the shipping rate tables for each zone.  Again,
   some time and effort will go into setting the appropriate rates.  You
   will define a set of weight ranges and the shipping price for each
   range.  For instance, you might want an order than weighs more than 0
   and less than or equal to 3 to cost 5.50 to ship to a certain zone.
   This would be defined by this:  3:5.5

   You should combine a bunch of these rates together in a comma delimited
   list and enter them into the "Zone X Shipping Table" fields where "X"
   is the zone number.  For example, this might be used for Zone 1:
     1:3.5,2:3.95,3:5.2,4:6.45,5:7.7,6:10.4,7:11.85, 8:13.3,9:14.75,10:16.2,11:17.65,
     12:19.1,13:20.55,14:22,15:23.45

   The above example includes weights over 0 and up to 15.  Note that
   units are not specified in this explanation since they should be
   specific to your locale.

   CAVEATS
   At this time, it does not deal with weights that are above the highest amount
   defined.  This will probably be the next area to be improved with the
   module.  For now, you could have one last very high range with a very
   high shipping rate to discourage orders of that magnitude.  For
   instance:  999:1000

   If you want to be able to ship to any country in the world, you will
   need to enter every country code into the Country fields. For most
   shops, you will not want to enter every country.  This is often
   because of too much fraud from certain places. If a country is not
   listed, then the module will add a $0.00 shipping charge and will
   indicate that shipping is not available to that destination.
   PLEASE NOTE THAT THE ORDER CAN STILL BE COMPLETED AND PROCESSED!

   It appears that the osC shipping system automatically rounds the
   shipping weight up to the nearest whole unit.  This makes it more
   difficult to design precise shipping tables.  If you want to, you
   can hack the shipping.php file to get rid of the rounding.

   Lastly, there is a limit of 255 characters on each of the Zone
   Shipping Tables and Zone Countries.
   ----------------------------------------------------------------------
 */

#[AllowDynamicProperties]
class zones
{
    public $code = 'zones';
    public $title;
    public $description;
    public $num_zones;
    public $enabled = false;

    // class constructor
    public function __construct()
    {
        global $oOrder, $aLang;
        $this->title = $aLang['module_shipping_zones_text_title'];
        $this->description = $aLang['module_shipping_zones_text_description'];
        $this->sort_order = (defined('MODULE_SHIPPING_ZONES_SORT_ORDER') ? MODULE_SHIPPING_ZONES_SORT_ORDER : null);
        $this->icon = '';
        $this->enabled = (defined('MODULE_SHIPPING_ZONES_STATUS') && (MODULE_SHIPPING_ZONES_STATUS == 'true') ? true : false);

        // CUSTOMIZE THIS SETTING FOR THE NUMBER OF ZONES NEEDED
        $this->num_zones = (defined('MODULE_SHIPPING_ZONES_NUM_ZONES') ? MODULE_SHIPPING_ZONES_NUM_ZONES : 2);
    }

    // class methods
    public function quote($method = '')
    {
        global $oOrder, $aLang, $shipping_weight;

        if (!is_object($oOrder)) {
            $dest_country = isset($_SESSION['delivery_zone']) ? oos_prepare_input($_SESSION['delivery_zone']) : STORE_ORIGIN_COUNTRY;
        } else {
            $dest_country = $oOrder->delivery['country']['iso_code_2'];
        }

        $dest_zone = 0;
        $error = false;

        for ($i=1; $i<=$this->num_zones; $i++) {
            $countries_table = constant('MODULE_SHIPPING_ZONES_COUNTRIES_' . $i);
            $country_zones = preg_split("/[,]/", (string) $countries_table);
            if (in_array($dest_country, $country_zones)) {
                $dest_zone = $i;
                break;
            }
        }


        if ($dest_zone == 0) {
            $error = true;
        } else {
            $shipping = -1;
            $zones_cost = constant('MODULE_SHIPPING_ZONES_COST_' . $dest_zone);

            $zones_table = preg_split("/[:,]/", (string) $zones_cost);
            $size = is_countable($zones_table) ? count($zones_table) : 0;
            for ($i=0; $i<$size; $i+=2) {
                if ($shipping_weight <= $zones_table[$i]) {
                    $shipping = $zones_table[$i+1];
                    $shipping_method = $aLang['module_shipping_zones_text_way'] . ' ' . $dest_country . ' : ' . $shipping_weight . ' ' . $aLang['module_shipping_zones_text_units'];
                    break;
                }
            }

            if ($shipping == -1) {
                $shipping_cost = 0;
                $shipping_method = $aLang['module_shipping_zones_undefined_rate'];
            } else {
                $shipping_cost = ($shipping + constant('MODULE_SHIPPING_ZONES_HANDLING_' . $dest_zone));
            }
        }

        $this->quotes = ['id' => $this->code, 'module' => $aLang['module_shipping_zones_text_title'], 'methods' => [['id' => $this->code, 'title' => $shipping_method, 'cost' => $shipping_cost]]];

        if (oos_is_not_null($this->icon)) {
            $this->quotes['icon'] = oos_image($this->icon, $this->title);
        }

        if ($error == true) {
            $this->quotes['error'] = $aLang['module_shipping_zones_invalid_zone'];
        }

        return $this->quotes;
    }


    public function check()
    {
        if (!isset($this->_check)) {
            $this->_check = defined('MODULE_SHIPPING_ZONES_STATUS');
        }

        return $this->_check;
    }


    public function install()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) VALUES ('MODULE_SHIPPING_ZONES_STATUS', 'true', '6', '0', 'oos_cfg_select_option(array(\'true\', \'false\'), ', now())");

        $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_ZONES_SORT_ORDER', '0', '6', '0', now())");
        for ($i = 1; $i <= $this->num_zones; $i++) {
            $default_countries = '';
            if ($i == 1) {
                $default_countries = 'DE,US';
            }
            $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_ZONES_COUNTRIES_" . $i ."', '" . $default_countries . "', '6', '0', now())");
            $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) VALUES ('MODULE_SHIPPING_ZONES_COST_" . $i ."', '3:8.50,7:10.50,99:20.00', '6', '0', now())");
            $dbconn->Execute("INSERT INTO $configurationtable (configuration_key, configuration_value, configuration_group_id, sort_order, use_function, date_added) VALUES ('MODULE_SHIPPING_ZONES_HANDLING_" . $i."', '0', '6', '0', 'currencies->format', now())");
        }
    }


    public function remove()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $configurationtable = $oostable['configuration'];
        $dbconn->Execute("DELETE FROM $configurationtable WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }


    public function keys()
    {
        $keys = ['MODULE_SHIPPING_ZONES_STATUS', 'MODULE_SHIPPING_ZONES_SORT_ORDER'];

        for ($i=1; $i<=$this->num_zones; $i++) {
            $keys[] = 'MODULE_SHIPPING_ZONES_COUNTRIES_' . $i;
            $keys[] = 'MODULE_SHIPPING_ZONES_COST_' . $i;
            $keys[] = 'MODULE_SHIPPING_ZONES_HANDLING_' . $i;
        }

        return $keys;
    }
}
