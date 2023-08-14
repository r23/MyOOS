<?php
/**
   ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2023 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: line.php
   ----------------------------------------------------------------------
   EchartsPHP


   Copyright © Hisune(hi@hisune.com/)
   ----------------------------------------------------------------------
   Apache License
   ----------------------------------------------------------------------
 */

/**
 * Created by Hisune.
 * User: hi@hisune.com
 * Date: 2018/2/27
 * Time: 19:13
 * 通用方法举例，双折线
 */
header('Content-Type: text/html; charset=utf-8');

function chartLine($xAxisData, $seriesData, $title = '')
{
    global $oCurrencies;

    Hisune\EchartsPHP\Config::$dist = OOS_HTTPS_SERVER . OOS_SHOP . 'js/echarts/dist';

    $chart = new Hisune\EchartsPHP\ECharts();
    $xAxis = new Hisune\EchartsPHP\Doc\IDE\XAxis();
    $yAxis = new Hisune\EchartsPHP\Doc\IDE\YAxis();

    $color = ['#c23531','#2f4554', '#61a0a8', '#d48265', '#91c7ae','#749f83',  '#ca8622', '#bda29a','#6e7074', '#546570', '#c4ccd3'];
    shuffle($color);

    $title && $chart->title->text = $title;
    $chart->color = $color;
    $chart->tooltip->trigger = 'axis';

    $chart->toolbox->show = true;
    $chart->toolbox->feature->dataZoom->yAxisIndex = 'none';
    $chart->toolbox->feature->dataView->readOnly = true;
    $chart->toolbox->feature->saveAsImage = [];

    $sCurrency = (isset($_SESSION['currency']) ? $_SESSION['currency'] : DEFAULT_CURRENCY);
    $symbol_left = $oCurrencies->get_currencies_symbol_left($sCurrency);
    $symbol_right = $oCurrencies->get_currencies_symbol_right($sCurrency);
    $yAxis->axisLabel = array(
        // this array value will automatic conversion to js callback function
        'formatter' => "
			function (value)
			{
				return value + ' " . $symbol_left . $symbol_right . "'
			}
		"
    );

    $xAxis->type = 'category';
    $xAxis->boundaryGap = false;
    $xAxis->data = $xAxisData;

    foreach ($seriesData as $ser) {
        $chart->legend->data[] = $ser['name'];
        $series = new \Hisune\EchartsPHP\Doc\IDE\Series();
        $series->name = $ser['name'];
        $series->type = 'line';
        $series->step = 'end';
        $series->data = $ser['data'];
        $chart->addSeries($series);
    }

    $chart->addXAxis($xAxis);
    $chart->addYAxis($yAxis);

    return $chart->render(uniqid());
}


$startD = filter_input(INPUT_GET, 'startD', FILTER_VALIDATE_INT) ?: 2;
$last_show_date = 0;

$startDate_1 = mktime(0, 0, 0, date("m"), date("d")-30, date("Y"));
$startDate_2 = mktime(0, 0, 0, date("m"), date("d")-90, date("Y"));
$startDate_3 = mktime(0, 0, 0, date("m"), date("d")-183, date("Y"));
$startDate_4 = mktime(0, 0, 0, date("m"), date("d")-365, date("Y"));


switch ($startD) {
case '1':
    $startDate = $startDate_1;
    break;

case '2':
    $startDate = $startDate_2;
    break;

case '3':
    $startDate = $startDate_3;
    break;

case '4':
    $startDate = $startDate_4;
    break;

default:
    $startDate = $startDate_2;
    break;
}

$endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
$get_products_id = filter_input(INPUT_GET, 'products_id', FILTER_SANITIZE_STRING);


// check start and end Date
$products_price_historytable = $oostable['products_price_history'];
$sql = "SELECT UNIX_TIMESTAMP(min(date_added)) as first
          FROM $products_price_historytable
	     WHERE products_id = '" . intval($nProductsID) . "'";
$first_result = $dbconn->Execute($sql);
$first = $first_result->fields;

$global_start_date = mktime(0, 0, 0, date("m", $first['first']), date("d", $first['first']), date("Y", $first['first']));
if ($startDate == 0  or $startDate < $global_start_date) {
    $before_date = date("Y-m-d", $startDate);
    $sInfoTitle = sprintf($aLang['text_price_chart_info'], $product_info['products_name'], oos_date_short($before_date));

    $global_before_date = date("Y-m-d", $global_start_date);
    $sInfoText = sprintf($aLang['text_info_price_chart'], $product_info['products_name'], oos_date_short($global_before_date));

    // set startDate to
    $startDate = $global_start_date;
}

//deactivate selection
$ds = 5;
if ($startDate_4 < $global_start_date) {
    $ds = 4;
} elseif ($startDate_3 < $global_start_date) {
    $ds = 3;
} elseif ($startDate_2 < $global_start_date) {
    $ds = 2;
} elseif ($startDate_1 < $global_start_date) {
    $ds = 1;
}

$products_price_historytable = $oostable['products_price_history'];
$sql = "SELECT products_price, date_added
          FROM $products_price_historytable
	     WHERE products_id = '" . intval($nProductsID) . "'
		   AND date_added >= '" . oos_db_input(date("Y-m-d\TH:i:s", $startDate)) . "' 
		   AND date_added < '" . oos_db_input(date("Y-m-d\TH:i:s", $endDate)) . "'
      ORDER BY date_added ASC";
$price_history_result = $dbconn->Execute($sql);
if ($price_history_result->RecordCount() >= 2) {
    echo '<h3>' . $aLang['text_price_chart_titel'] . '</h3>';
    echo '<h4>' . $product_info['products_name'] . '</h4>';

    if (isset($sInfoTitle)) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">		
			<h4>' . $sInfoTitle . '</h4>
			<p>' . $sInfoText . '</p>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>';
    }
    $aDate = [];
    $aData = [];

    while ($price_history = $price_history_result->fields) {
        $history_price = $oCurrencies->schema_price($price_history['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']), 1, false);

        $aDate[] = oos_date_short($price_history['date_added']);
        $aData[] = $history_price;

        // Move that ADOdb pointer!
        $price_history_result->MoveNext();
    }


    // current price with date
    $aDate = array_merge($aDate, [oos_date_short($today)]);
    $aData = array_merge($aData, [$schema_product_price]);


    echo '<p class="text-end">';
    if ($ds > 1) {
        if ($startD == 1) {
            echo '1 ' . $aLang['text_month'] . '&nbsp;|&nbsp;';
        } else {
            echo '<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $get_products_id . '&startD=1#anchor_1') . '">1 ' . $aLang['text_month'] . '</a>&nbsp;|&nbsp;';
        }
    }
    if ($ds > 2) {
        if ($startD == 2) {
            echo '3 ' . $aLang['text_month'] . '&nbsp;|&nbsp;';
        } else {
            echo '<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $get_products_id . '&startD=2#anchor_1') . '">3 ' . $aLang['text_months'] . '</a>&nbsp;|&nbsp;';
        }
    }
    if ($ds > 3) {
        if ($startD == 3) {
            echo '6 ' . $aLang['text_month'] . '&nbsp;|&nbsp;';
        } else {
            echo '<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $get_products_id . '&startD=3#anchor_1') . '">6 ' . $aLang['text_months'] . '</a>&nbsp;|&nbsp;';
        }
    }
    if ($ds > 4) {
        if ($startD == 4) {
            echo '1 ' . $aLang['text_year'] . '&nbsp;|&nbsp;';
        } else {
            echo '<a href="' . oos_href_link($aContents['product_info'], 'products_id=' . $get_products_id . '&startD=4#anchor_1') . '">1' . $aLang['text_year'] . '</a>&nbsp;';
        }
    }
    echo '</p>';


    echo chartLine(
        $aDate,
        [
            ['name' => $product_info['products_name'], 'data' => $aData],
        ],
        $aLang['text_price_chart_titel']
    );
}
