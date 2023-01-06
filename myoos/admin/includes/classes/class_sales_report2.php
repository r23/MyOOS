<?php
/**
   ----------------------------------------------------------------------
   $Id: class_sales_report2.php,v 1.1 2007/06/08 14:58:10 r23 Exp $

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: sales_report2.php,v 1.00 2003/03/08 19:25:29
   ----------------------------------------------------------------------
   Charly Wilhelm charly@yoshi.ch

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

#[AllowDynamicProperties]
class sales_report
{
    public $mode;
    public $GLOBALStartDate;
    public $startDate;
    public $endDate;
    public $actDate;
    public $showDate;
    public $showDateEnd;
    public $sortString;
    public $status;
    public $outlet;

    public function __construct($mode, $startDate = 0, $endDate = 0, $sort = 0, $statusFilter = 0, $filter = 0)
    {

        // startDate and endDate have to be a unix timestamp. Use mktime !
        // if set then both have to be valid startDate and endDate
        $this->mode = $mode;
        $this->tax_include = DISPLAY_PRICE_WITH_TAX;

        $this->statusFilter = $statusFilter;

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        $firstQuery = $dbconn->Execute("SELECT UNIX_TIMESTAMP(min(date_purchased)) as first FROM " . $oostable['orders']);
        $first = $firstQuery->fields;
        $this->GLOBALStartDate = mktime(0, 0, 0, date("m", $first['first']), date("d", $first['first']), date("Y", $first['first']));
        $statusQuery = $dbconn->Execute("SELECT * FROM " . $oostable['orders_status']);
        $i = 0;
        while ($outResp = $statusQuery->fields) {
            $status[$i] = $outResp;
            $i++;
            // Move that ADOdb pointer!
            $statusQuery->MoveNext();
        }
        $this->status = $status;


        if ($startDate == 0  or $startDate < $this->GLOBALStartDate) {
            // set startDate to GLOBALStartDate
            $this->startDate = $this->GLOBALStartDate;
        } else {
            $this->startDate = $startDate;
        }
        if ($this->startDate > mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
            $this->startDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        }

        if ($endDate > mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"))) {
            // set endDate to tomorrow
            $this->endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
        } else {
            $this->endDate = $endDate;
        }
        if ($this->endDate < $this->startDate + 24 * 60 * 60) {
            $this->endDate = $this->startDate + 24 * 60 * 60;
        }

        $this->actDate = $this->startDate;

        // query for order count
        $this->queryOrderCnt = "SELECT count(o.orders_id) as order_cnt FROM " . $oostable['orders'] . " o";

        // queries for item details count
        $this->queryItemCnt = "SELECT o.orders_id, op.products_id as pid, op.orders_products_id, op.products_name as pname, sum(op.products_quantity) as pquant, sum(op.final_price * op.products_quantity) as psum, op.products_tax as ptax FROM " . $oostable['orders'] . " o, " . $oostable['orders_products'] . " op WHERE o.orders_id = op.orders_id";

        // query for attributes
        $this->queryAttr = "SELECT count(op.products_id) as attr_cnt, o.orders_id, opa.orders_products_id, opa.products_options, opa.products_options_values, opa.options_values_price, opa.price_prefix FROM " . $oostable['orders_products_attributes'] . " opa, " . $oostable['orders'] . " o, " . $oostable['orders_products'] . " op WHERE o.orders_id = opa.orders_id AND op.orders_products_id = opa.orders_products_id";

        // query for shipping
        $this->queryShipping = "SELECT sum(ot.value) as shipping FROM " . $oostable['orders'] . " o, " . $oostable['orders_total'] . " ot WHERE ot.orders_id = o.orders_id AND  ot.class = 'ot_shipping'";

        switch ($sort) {
        case '0':
            $this->sortString = "";
            break;
        case '1':
            $this->sortString = " ORDER BY pname ASC ";
            break;
        case '2':
            $this->sortString = " ORDER BY pname DESC";
            break;
        case '3':
            $this->sortString = " ORDER BY pquant ASC, pname ASC";
            break;
        case '4':
            $this->sortString = " ORDER BY pquant DESC, pname ASC";
            break;
        case '5':
            $this->sortString = " ORDER BY psum ASC, pname ASC";
            break;
        case '6':
            $this->sortString = " ORDER BY psum DESC, pname ASC";
            break;
        }
    }

    public function getNext()
    {

        // Get database information
        $dbconn =& oosDBGetConn();
        $oostable =& oosDBGetTables();

        switch ($this->mode) {
            // yearly
        case '1':
            $sd = $this->actDate;
            $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd), date("Y", $sd) + 1);
            break;
            // monthly
        case '2':
            $sd = $this->actDate;
            $ed = mktime(0, 0, 0, date("m", $sd) + 1, 1, date("Y", $sd));
            break;
            // weekly
        case '3':
            $sd = $this->actDate;
            $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 7, date("Y", $sd));
            break;
            // daily
        case '4':
            $sd = $this->actDate;
            $ed = mktime(0, 0, 0, date("m", $sd), date("d", $sd) + 1, date("Y", $sd));
            break;
        }
        if ($ed > $this->endDate) {
            $ed = $this->endDate;
        }

        $filterString = "";
        if ($this->statusFilter > 0) {
            $filterString .= " AND o.orders_status = " . $this->statusFilter . " ";
        }
        $rqOrders = $dbconn->Execute($this->queryOrderCnt . " WHERE o.date_purchased >= '" . oos_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . oos_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);
        $order = $rqOrders->fields;

        $rqShipping = $dbconn->Execute($this->queryShipping . " AND o.date_purchased >= '" . oos_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . oos_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString);
        $shipping = $rqShipping->fields;

        $rqItems = $dbconn->Execute($this->queryItemCnt . " AND o.date_purchased >= '" . oos_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . oos_db_input(date("Y-m-d\TH:i:s", $ed)) . "'" . $filterString . " group by pid " . $this->sortString);

        // set the return values
        $this->actDate = $ed;
        $this->showDate = $sd;
        $this->showDateEnd = $ed - 60 * 60 * 24;

        // execute the query
        $cnt = 0;
        $itemTot = 0;
        $sumTot = 0;
        while ($resp[$cnt] = $rqItems->fields) {
            // to avoid rounding differences round for every quantum
            // multiply with the number of items afterwords.
            $price = $resp[$cnt]['psum'] / $resp[$cnt]['pquant'];

            // products_attributes
            // are there any attributes for this order_id ?
            $rqAttr = $dbconn->Execute($this->queryAttr . " AND o.date_purchased >= '" . oos_db_input(date("Y-m-d\TH:i:s", $sd)) . "' AND o.date_purchased < '" . oos_db_input(date("Y-m-d\TH:i:s", $ed)) . "' AND op.products_id = " . $resp[$cnt]['pid'] . $filterString . " group by products_options_values ORDER BY orders_products_id");
            $i = 0;
            while ($attr[$i] = $rqAttr->fields) {
                $i++;
                // Move that ADOdb pointer!
                $rqAttr->MoveNext();
            }

            // values per date
            if ($i > 0) {
                $price2 = 0;
                $price3 = 0;
                $option = [];
                $k = -1;
                $ord_pro_id_old = 0;
                for ($j = 0; $j < $i; $j++) {
                    if ($attr[$j]['price_prefix'] == "-") {
                        $price2 += (-1) *  $attr[$j]['options_values_price'];
                        $price3 = (-1) * $attr[$j]['options_values_price'];
                        $prefix = "-";
                    } else {
                        $price2 += $attr[$j]['options_values_price'];
                        $price3 = $attr[$j]['options_values_price'];
                        $prefix = "+";
                    }
                    $ord_pro_id = $attr[$j]['orders_products_id'];
                    if ($ord_pro_id != $ord_pro_id_old) {
                        $k++;
                        $l = 0;
                        // set values
                        $option[$k]['quant'] = $attr[$j]['attr_cnt'];
                        $option[$k]['options'][0] = $attr[$j]['products_options'];
                        $option[$k]['options_values'][0] = $attr[$j]['products_options_values'];
                        if ($price3 != 0) {
                            $option[$k]['price'][0] = oos_add_tax($price3, $resp[$cnt]['ptax']);
                        } else {
                            $option[$k]['price'][0] = 0;
                        }
                    } else {
                        $l++;
                        // update values
                        $option[$k]['options'][$l] = $attr[$j]['products_options'];
                        $option[$k]['options_values'][$l] = $attr[$j]['products_options_values'];
                        if ($price3 != 0) {
                            $option[$k]['price'][$l] = oos_add_tax($price3, $resp[$cnt]['ptax']);
                        } else {
                            $option[$k]['price'][$l] = 0;
                        }
                    }
                    $ord_pro_id_old = $ord_pro_id;
                }
                // set attr value
                $resp[$cnt]['attr'] = $option;
            } else {
                $resp[$cnt]['attr'] = "";
            }
            $resp[$cnt]['price'] = oos_add_tax($price, $resp[$cnt]['ptax']);
            $resp[$cnt]['psum'] = $resp[$cnt]['pquant'] * oos_add_tax($price, $resp[$cnt]['ptax']);
            $resp[$cnt]['order'] = $order['order_cnt'];
            $resp[$cnt]['shipping'] = $shipping['shipping'];

            // values per date and item
            $sumTot += $resp[$cnt]['psum'];
            $itemTot += $resp[$cnt]['pquant'];
            // add totsum and totitem until current row
            $resp[$cnt]['totsum'] = $sumTot;
            $resp[$cnt]['totitem'] = $itemTot;
            $cnt++;

            // Move that ADOdb pointer!
            $rqItems->MoveNext();
        }

        return $resp;
    }
}
