<?php
/* ----------------------------------------------------------------------

   MyOOS [Shopsystem]
   https://www.oos-shop.de

   Copyright (c) 2003 - 2022 by the MyOOS Development Team.
   ----------------------------------------------------------------------
   Based on:

   File: line.php
   ----------------------------------------------------------------------
   EchartsPHP


   Copyright © Hisune(hi@hisune.com/)
   ----------------------------------------------------------------------
   Apache License
   ---------------------------------------------------------------------- */

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
	
	Hisune\EchartsPHP\Config::$dist = OOS_HTTPS_SERVER . OOS_SHOP . '/js/echarts/dist';
	
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

    $xAxis->type = 'category';
    $xAxis->boundaryGap = false;
    $xAxis->data = $xAxisData;

    foreach($seriesData as $ser){
        $chart->legend->data[] = $ser['name'];
        $series = new \Hisune\EchartsPHP\Doc\IDE\Series();
        $series->name = $ser['name'];
        $series->type = 'line';
		$series->step = 'start';
        $series->data = $ser['data'];
        $chart->addSeries($series);
    }

    $chart->addXAxis($xAxis);
    $chart->addYAxis($yAxis);

    return $chart->render(uniqid());
}


$products_price_historytable = $oostable['products_price_history'];
$sql = "SELECT products_price, date_added
          FROM $products_price_historytable
	     WHERE products_id = '" . intval($nProductsID) . "'
      ORDER BY date_added DESC";
$price_history_result = $dbconn->Execute($sql);
if ($price_history_result->RecordCount() >= 2) {
    $aDate = [];
	$aData = [];
    while ($price_history = $price_history_result->fields) {
		$history_price = $oCurrencies->schema_price($price_history['products_price'], oos_get_tax_rate($product_info['products_tax_class_id']), 1, false);

        $aDate[] = $price_history['date_added'];
		$aData[] = $history_price;

        // Move that ADOdb pointer!
        $price_history_result->MoveNext();
    }

	
	// current price with date
	$aDate = array_merge($aDate, [$today]);
	$aData = array_merge($aData, [$schema_product_price]);

	echo chartLine( $aDate, 
		[
			['name' => $product_info['products_name'], 'data' => $aData],
		],
		$aLang['text_price_chart_titel']
	);

}

